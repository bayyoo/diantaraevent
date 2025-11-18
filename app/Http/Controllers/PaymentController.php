<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\Participant;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create payment for event registration
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'participant_name' => 'required|string|max:255',
            'participant_email' => 'required|email',
            'participant_phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1000', // Minimum Rp 1,000
        ]);

        try {
            $event = Event::findOrFail($request->event_id);
            if (!config('midtrans.server_key')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Midtrans belum lengkap.',
                    'error' => 'MIDTRANS_SERVER_KEY kosong. Isi .env lalu jalankan php artisan config:clear'
                ], 500);
            }
            if (config('midtrans.is_production') && str_contains(config('midtrans.server_key'), 'SB-')) {
                // Guard against sandbox key accidentally used in production
                Log::warning('Sandbox key used while is_production=true');
            }
            
            // Generate unique order ID
            $orderId = 'DIANTARA-' . $event->id . '-' . time() . '-' . rand(1000, 9999);
            
            // Create transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ];

            // Customer details
            $customerDetails = [
                'first_name' => $request->participant_name,
                'email' => $request->participant_email,
                'phone' => $request->participant_phone,
            ];

            // Item details
            $itemDetails = [
                [
                    'id' => 'event-' . $event->id,
                    'price' => (int) $request->amount,
                    'quantity' => 1,
                    'name' => 'Tiket ' . $event->title,
                    'brand' => 'Diantara',
                    'category' => 'Event Ticket',
                ]
            ];

            // Transaction data
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'unfinish' => route('payment.unfinish'),
                    'error' => route('payment.error'),
                ]
            ];

            // Create Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($transactionData);

            // Store pending participant data (participants.token is required)
            $registrationToken = Str::upper(Str::random(10));
            $participant = Participant::create([
                'event_id' => $event->id,
                'name' => $request->participant_name,
                'email' => $request->participant_email,
                'phone' => $request->participant_phone,
                'token' => $registrationToken,
                'order_id' => $orderId,
                'payment_status' => 'pending',
                'amount' => $request->amount,
                'snap_token' => $snapToken,
                // Placeholder; akan diganti saat payment sukses di handleNotification
                'attendance_token' => '',
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'participant_id' => $participant->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage(), [
                'event_id' => $request->event_id,
                'amount' => $request->amount,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment notification from Midtrans
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Payment notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            $participant = Participant::where('order_id', $orderId)->first();
            
            if (!$participant) {
                Log::error('Participant not found for order: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Handle different transaction statuses
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'challenge') {
                    $participant->update(['payment_status' => 'challenge']);
                } else {
                    $participant->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    
                    // Generate attendance token
                    $this->generateAttendanceToken($participant);

                    // Generate e-ticket with QR/token (if PDF library available)
                    try {
                        if (class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf')) {
                            $ticketController = new \App\Http\Controllers\TicketController();
                            $ticketPath = $ticketController->generateTicketPath($participant);
                            if ($ticketPath) {
                                $participant->update(['ticket_path' => $ticketPath]);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('E-Ticket generation failed after payment: ' . $e->getMessage());
                    }
                }
            } elseif ($transactionStatus == 'pending') {
                $participant->update(['payment_status' => 'pending']);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $participant->update(['payment_status' => 'failed']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Payment notification handling failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Generate attendance token for paid participant
     */
    private function generateAttendanceToken($participant)
    {
        $token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10));
        
        $participant->update(['attendance_token' => $token]);
        
        // TODO: Send email with attendance token
        // Mail::to($participant->email)->send(new AttendanceTokenMail($participant));
        
        Log::info('Attendance token generated', [
            'participant_id' => $participant->id,
            'token' => $token
        ]);
    }

    /**
     * Payment finish page
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $participant = Participant::where('order_id', $orderId)->first();
        
        return view('payment.finish', compact('participant'));
    }

    /**
     * Payment unfinish page
     */
    public function unfinish(Request $request)
    {
        return view('payment.unfinish');
    }

    /**
     * Payment error page
     */
    public function error(Request $request)
    {
        return view('payment.error');
    }
}
