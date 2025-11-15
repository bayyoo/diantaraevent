<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\EventCertificate;
use App\Models\EventAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CertificateService
{
    /**
     * Generate certificate for user after event completion
     */
    public function generateCertificate(Event $event, User $user): ?EventCertificate
    {
        // Check if event has certificate enabled
        if (!$event->has_certificate) {
            return null;
        }

        // Check if user attended the event
        $attendance = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('is_attended', true)
            ->first();

        if (!$attendance) {
            return null;
        }

        // Check if certificate already exists
        $existingCertificate = EventCertificate::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingCertificate) {
            return $existingCertificate;
        }

        // Generate certificate
        $certificateNumber = EventCertificate::generateCertificateNumber();
        
        if ($event->certificate_type === 'custom' && $event->certificate_file_path) {
            // Use custom certificate template
            $filePath = $this->generateCustomCertificate($event, $user, $certificateNumber);
        } else {
            // Use default template
            $filePath = $this->generateDefaultCertificate($event, $user, $certificateNumber);
        }

        // Save certificate record
        return EventCertificate::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'certificate_number' => $certificateNumber,
            'participant_name' => $user->full_name,
            'certificate_file_path' => $filePath,
            'generated_at' => now()
        ]);
    }

    /**
     * Generate certificate using default template
     */
    private function generateDefaultCertificate(Event $event, User $user, string $certificateNumber): string
    {
        $data = [
            'event' => $event,
            'user' => $user,
            'certificate_number' => $certificateNumber,
            'generated_date' => Carbon::now()->format('d F Y'),
            'event_date' => Carbon::parse($event->event_date)->format('d F Y')
        ];

        $pdf = Pdf::loadView('certificates.default-template', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $fileName = 'certificates/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());
        
        return $fileName;
    }

    /**
     * Generate certificate using custom template
     */
    private function generateCustomCertificate(Event $event, User $user, string $certificateNumber): string
    {
        // For custom certificates, we'll overlay text on the custom image/PDF
        $data = [
            'event' => $event,
            'user' => $user,
            'certificate_number' => $certificateNumber,
            'generated_date' => Carbon::now()->format('d F Y'),
            'event_date' => Carbon::parse($event->event_date)->format('d F Y'),
            'custom_template_path' => $event->certificate_file_path
        ];

        $pdf = Pdf::loadView('certificates.custom-template', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $fileName = 'certificates/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());
        
        return $fileName;
    }

    /**
     * Check if certificates should be generated for an event
     */
    public function shouldGenerateCertificates(Event $event): bool
    {
        // Generate certificates after event ends
        $eventEndTime = Carbon::parse($event->event_date . ' ' . $event->event_time)->addHours(3); // Assume 3 hours duration
        
        return now()->greaterThan($eventEndTime) && $event->has_certificate;
    }

    /**
     * Generate certificates for all attended users of an event
     */
    public function generateEventCertificates(Event $event): int
    {
        if (!$this->shouldGenerateCertificates($event)) {
            return 0;
        }

        $attendances = EventAttendance::where('event_id', $event->id)
            ->where('is_attended', true)
            ->with('user')
            ->get();

        $generated = 0;
        foreach ($attendances as $attendance) {
            $certificate = $this->generateCertificate($event, $attendance->user);
            if ($certificate) {
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Get certificate download URL
     */
    public function getCertificateDownloadUrl(EventCertificate $certificate): string
    {
        return route('certificates.download', $certificate->id);
    }
}
