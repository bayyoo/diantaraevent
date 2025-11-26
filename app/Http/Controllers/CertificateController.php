<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\EventCertificate;
use App\Models\EventAttendance;
use App\Models\Event;
use App\Models\User;
use App\Services\CertificateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Generate certificate PDF for a participant
     */
    public function generate(Participant $participant)
    {
        try {
            // Load participant with user and event relationships
            $participant->load(['user', 'event']);
            
            // Check if participant has attended the event
            if ($participant->status !== 'attended') {
                return redirect()->route('home')
                    ->with('error', 'Sertifikat hanya tersedia untuk peserta yang sudah hadir.');
            }
            
            // Generate PDF
            $pdf = Pdf::loadView('certificates.certificate', compact('participant'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');
            
            // Set options for better rendering
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            
            // Generate filename
            $filename = 'certificate_' . str_replace(' ', '_', strtolower($participant->user->name)) . '_' . date('Ymd') . '.pdf';
            
            // Return PDF download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Certificate download failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengunduh sertifikat. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Generate and save certificate path (called after attendance)
     */
    public function generateCertificatePath(Participant $participant)
    {
        try {
            // Load relationships
            $participant->load(['user', 'event']);
            
            // Generate filename
            $filename = 'certificate_' . $participant->id . '_' . time() . '.pdf';
            $path = 'certificates/' . $filename;
            
            // Create directory if not exists
            $fullPath = storage_path('app/public/' . $path);
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Generate PDF
            $pdf = Pdf::loadView('certificates.certificate', compact('participant'));
            $pdf->setPaper('A4', 'landscape');
            
            // Set options for better rendering
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            
            // Save PDF to storage
            $pdf->save($fullPath);
            
            \Log::info('Certificate generated successfully for participant: ' . $participant->id);
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Certificate generation failed for participant ' . $participant->id . ': ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * View certificate in browser before download
     */
    public function view(Participant $participant)
    {
        try {
            // Load participant with user and event relationships
            $participant->load(['user', 'event']);
            
            // Check if participant has attended the event
            if ($participant->status !== 'attended') {
                return redirect()->route('home')
                    ->with('error', 'Sertifikat hanya tersedia untuk peserta yang sudah hadir.');
            }
            
            // Generate PDF
            $pdf = Pdf::loadView('certificates.certificate', compact('participant'));
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            
            // Return PDF stream (view in browser)
            return $pdf->stream('certificate_' . $participant->user->name . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Certificate view failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menampilkan sertifikat. Silakan coba lagi.');
        }
    }

    /**
     * Show certificate search page
     */
    public function searchPage()
    {
        return view('certificates.search');
    }

    /**
     * Search certificates by name or email
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3'
        ]);

        $searchTerm = $request->search;

        // Search for participants who have attended and have certificates
        $certificates = Participant::with(['user', 'event'])
            ->where('status', 'attended')
            ->whereHas('user', function($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($certificates->isEmpty()) {
            return back()->with('info', 'Tidak ada sertifikat ditemukan untuk pencarian: ' . $searchTerm);
        }

        return view('certificates.search', compact('certificates'));
    }

    /**
     * Download certificate using new system
     */
    public function download(EventCertificate $certificate)
    {
        try {
            // Check if user can download this certificate
            if (auth()->check() && auth()->id() !== $certificate->user_id) {
                abort(403, 'Unauthorized access to certificate');
            }

            // Check if certificate file exists in public directory
            $filePath = public_path($certificate->certificate_file_path);
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Certificate file not found.');
            }

            // Increment download count
            $certificate->incrementDownload();

            // Return file download from public directory
            return response()->download(
                $filePath,
                'Certificate_' . $certificate->certificate_number . '.pdf'
            );

        } catch (\Exception $e) {
            \Log::error('Certificate download failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to download certificate.');
        }
    }

    /**
     * Generate certificate for event participant
     */
    public function generateEventCertificate(Event $event, User $user)
    {
        $certificateService = new CertificateService();
        
        try {
            $certificate = $certificateService->generateCertificate($event, $user);
            
            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate cannot be generated. User may not have attended the event or event does not have certificates enabled.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully',
                'certificate' => [
                    'id' => $certificate->id,
                    'number' => $certificate->certificate_number,
                    'download_url' => route('certificates.download', $certificate->id)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Certificate generation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificate'
            ], 500);
        }
    }

    /**
     * Generate certificates for all attendees of an event
     */
    public function generateEventCertificates(Event $event)
    {
        $certificateService = new CertificateService();
        
        try {
            $generated = $certificateService->generateEventCertificates($event);
            
            return response()->json([
                'success' => true,
                'message' => "Generated {$generated} certificates",
                'count' => $generated
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk certificate generation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificates'
            ], 500);
        }
    }

    /**
     * Get user's certificates
     */
    public function userCertificates()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $certificateService = new CertificateService();

        $attendances = EventAttendance::where('user_id', $user->id)
            ->where('is_attended', true)
            ->with('event')
            ->get();

        foreach ($attendances as $attendance) {
            $event = $attendance->event;
            if ($event && $certificateService->shouldGenerateCertificates($event)) {
                $certificateService->generateCertificate($event, $user);
            }
        }

        $certificates = EventCertificate::where('user_id', $user->id)
            ->with(['event'])
            ->orderBy('generated_at', 'desc')
            ->get();

        return view('certificates.user-certificates', compact('certificates'));
    }

    /**
     * Search certificates by certificate number
     */
    public function searchByCertificateNumber(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|string'
        ]);

        $certificate = EventCertificate::where('certificate_number', $request->certificate_number)
            ->with(['event', 'user'])
            ->first();

        if (!$certificate) {
            return back()->with('error', 'Certificate not found with number: ' . $request->certificate_number);
        }

        return view('certificates.certificate-details', compact('certificate'));
    }
}
