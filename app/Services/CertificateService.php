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

        // Check attendance: cukup pastikan user sudah tercatat hadir di EventAttendance
        $attendance = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('is_attended', true)
            ->first();
        if (!$attendance) {
            return null;
        }

        // Ensure event ended
        if (!$event->hasEnded()) {
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
        
        // Use selected template
        $filePath = $this->renderSelectedTemplate($event, $user, $certificateNumber);

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
        $org = $this->getOrganizationSignatureData($event);
        $certificateMeta = $event->metadata['certificate'] ?? [];
        $logoPath = $certificateMeta['logo_path'] ?? null;
        $logoFullPath = $logoPath
            ? public_path($logoPath)
            : public_path('images/diantara-nexus-logo.png');
        $data = [
            'event' => $event,
            'user' => $user,
            'certificate_number' => $certificateNumber,
            'generated_date' => Carbon::now()->format('d F Y'),
            'event_date' => Carbon::parse($event->event_date)->format('d F Y'),
            'org' => $org,
            'logo_path' => $logoFullPath,
        ];

        $view = $event->certificate_template === 'template_b'
            ? 'certificates.templates.template_b'
            : 'certificates.templates.template_a';

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('A4', 'landscape');

        $fileName = 'certificates/' . $certificateNumber . '.pdf';
        $fullPath = public_path($fileName);
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        file_put_contents($fullPath, $pdf->output());

        return $fileName;
    }

    /**
     * Generate certificate using custom template
     */
    private function generateCustomCertificate(Event $event, User $user, string $certificateNumber): string
    {
        // For custom certificates, we'll overlay text/signatures on uploaded image/PDF-like background
        $org = $this->getOrganizationSignatureData($event);
        $certificateMeta = $event->metadata['certificate'] ?? [];
        $logoPath = $certificateMeta['logo_path'] ?? null;
        $logoFullPath = $logoPath
            ? public_path('storage/' . $logoPath)
            : public_path('images/diantara-nexus-logo.png');
        $data = [
            'event' => $event,
            'user' => $user,
            'certificate_number' => $certificateNumber,
            'generated_date' => Carbon::now()->format('d F Y'),
            'event_date' => Carbon::parse($event->event_date)->format('d F Y'),
            'custom_template_path' => $event->custom_certificate_path,
            'org' => $org,
            'logo_path' => $logoFullPath,
        ];

        // Reuse template A layout that displays background if provided
        $pdf = Pdf::loadView('certificates.templates.template_a', $data);
        $pdf->setPaper('A4', 'landscape');

        $fileName = 'certificates/' . $certificateNumber . '.pdf';
        $fullPath = public_path($fileName);
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        file_put_contents($fullPath, $pdf->output());

        return $fileName;
    }

    /**
     * Check if certificates should be generated for an event
     */
    public function shouldGenerateCertificates(Event $event): bool
    {
        // Sertifikat boleh digenerate kapan saja selama event mengaktifkan fitur sertifikat
        return $event->has_certificate;
    }

    /**
     * Generate certificates for all attended users of an event
     */
    public function generateEventCertificates(Event $event): int
    {
        if (!$this->shouldGenerateCertificates($event)) {
            return 0;
        }

        $generated = 0;
        // If sessions exist, use participants who have full session attendance
        $totalSessions = $event->sessions()->count();
        if ($totalSessions > 0) {
            $userIds = \App\Models\EventAttendanceSession::select('user_id')
                ->where('event_id', $event->id)
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) >= ?', [$totalSessions])
                ->pluck('user_id');
            $users = User::whereIn('id', $userIds)->get();
            foreach ($users as $user) {
                if ($this->generateCertificate($event, $user)) { $generated++; }
            }
        } else {
            $attendances = EventAttendance::where('event_id', $event->id)
                ->where('is_attended', true)
                ->with('user')
                ->get();
            foreach ($attendances as $attendance) {
                if ($this->generateCertificate($event, $attendance->user)) { $generated++; }
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

    private function getOrganizationSignatureData(Event $event): array
    {
        $org = [];
        if ($event->organization_id) {
            $organization = \App\Models\PartnerOrganization::find($event->organization_id);
            if ($organization) {
                $org = [
                    'signature1_name' => $organization->signature1_name,
                    'signature1_title' => $organization->signature1_title,
                    'signature1_image' => $organization->signature1_image,
                    'signature2_name' => $organization->signature2_name,
                    'signature2_title' => $organization->signature2_title,
                    'signature2_image' => $organization->signature2_image,
                    'stamp_image' => $organization->stamp_image,
                ];
            }
        }
        return $org;
    }

    private function renderSelectedTemplate(Event $event, User $user, string $certificateNumber): string
    {
        if ($event->certificate_template === 'custom' && $event->custom_certificate_path) {
            return $this->generateCustomCertificate($event, $user, $certificateNumber);
        }
        return $this->generateDefaultCertificate($event, $user, $certificateNumber);
    }
}
