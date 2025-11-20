<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\Participant;

class PaymentController extends Controller
{

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
            if (!config('services.xendit.secret_key')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Xendit belum lengkap.',
                    'error' => 'XENDIT_SECRET_KEY kosong. Isi .env lalu jalankan php artisan config:clear'
                ], 500);
            }
            
            // Generate unique order ID
            $orderId = 'DIANTARA-' . $event->id . '-' . time() . '-' . rand(1000, 9999);
            
            // Create Xendit Invoice payload
            $invoiceParams = [
                'external_id' => $orderId,
                'payer_email' => $request->participant_email,
                'description' => 'Tiket ' . $event->title,
                'amount' => (int) $request->amount,
                'success_redirect_url' => route('payment.finish', ['order_id' => $orderId]),
                'failure_redirect_url' => route('payment.error', ['order_id' => $orderId]),
            ];

            // Call Xendit Invoice API via HTTP (basic auth with secret key)
            $apiKey = config('services.xendit.secret_key');
            $baseUrl = config('services.xendit.env') === 'live'
                ? 'https://api.xendit.co'
                : 'https://api.xendit.co'; // sandbox & live same host, env diatur dari api key

            $response = Http::withBasicAuth($apiKey, '')
                ->acceptJson()
                ->post($baseUrl . '/v2/invoices', $invoiceParams);

            if (!$response->successful()) {
                Log::error('Xendit API error when creating invoice', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat invoice Xendit.',
                    'error' => $response->json('message') ?? $response->body(),
                ], 500);
            }

            $invoice = $response->json();

            // Store pending participant data (participants.token & attendance_token required/unique)
            $registrationToken = Str::upper(Str::random(10));
            $attendanceTokenPlaceholder = Str::upper(Str::random(10));
            $participant = Participant::create([
                'event_id' => $event->id,
                'name' => $request->participant_name,
                'email' => $request->participant_email,
                'phone' => $request->participant_phone,
                'token' => $registrationToken,
                'order_id' => $orderId,
                'payment_status' => 'pending',
                'amount' => $request->amount,
                'snap_token' => $invoice['id'],
                // Placeholder unik; akan diganti saat payment sukses di handleNotification
                'attendance_token' => $attendanceTokenPlaceholder,
            ]);

            return response()->json([
                'success' => true,
                'payment_url' => $invoice['invoice_url'] ?? null,
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
     * Handle payment notification from Xendit Invoice webhook
     */
    public function handleNotification(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Xendit webhook received', [
                'payload' => $payload,
            ]);

            // Xendit invoice callback bisa mengirim `external_id` & `status` di root
            // atau di dalam `data` tergantung jenis event.
            $data = $payload['data'] ?? [];

            $orderId = $data['external_id'] ?? ($payload['external_id'] ?? null);
            $status = strtoupper($data['status'] ?? ($payload['status'] ?? ''));

            if (!$orderId) {
                Log::warning('Xendit webhook tanpa external_id', ['payload' => $payload]);
                return response()->json(['status' => 'error', 'message' => 'external_id missing'], 400);
            }

            $participant = Participant::where('order_id', $orderId)->first();

            if (!$participant) {
                Log::error('Participant not found for order: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Map status Xendit Invoice ke status internal
            // Contoh status Xendit: PENDING, PAID, EXPIRED, FAILED
            if (in_array($status, ['PAID', 'SETTLED'])) {
                $participant->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Generate attendance token
                $this->generateAttendanceToken($participant);

                // Generate e-ticket dengan QR/token (jika library PDF tersedia)
                try {
                    if (class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf')) {
                        $ticketController = new \App\Http\Controllers\TicketController();
                        $ticketPath = $ticketController->generateTicketPath($participant);
                        if ($ticketPath) {
                            $participant->update(['ticket_path' => $ticketPath]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('E-Ticket generation failed after payment (Xendit): ' . $e->getMessage());
                }
            } elseif (in_array($status, ['PENDING'])) {
                $participant->update(['payment_status' => 'pending']);
            } elseif (in_array($status, ['EXPIRED', 'FAILED', 'CANCELLED', 'VOID'])) {
                $participant->update(['payment_status' => 'failed']);
            } else {
                // Jika status tidak dikenali, log saja untuk debugging
                Log::warning('Unhandled Xendit status', [
                    'order_id' => $orderId,
                    'status' => $status,
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Xendit webhook handling failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
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
