<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST ABSENSI & GENERATE SERTIFIKAT ===\n\n";

$token = '2646990005'; // Token yang didapat dari registrasi
$eventId = 18;

// Cari participant berdasarkan token
$participant = \App\Models\Participant::where('token', $token)
    ->where('event_id', $eventId)
    ->with(['user', 'event'])
    ->first();

if (!$participant) {
    echo "❌ Token tidak valid!\n";
    exit;
}

echo "✅ Token valid!\n";
echo "Peserta: {$participant->name}\n";
echo "Email: {$participant->email}\n";
echo "Event: {$participant->event->title}\n";
echo "Status saat ini: {$participant->status}\n\n";

// Cek apakah sudah absen
if ($participant->status === 'attended') {
    echo "⚠️ Peserta sudah melakukan absensi sebelumnya!\n";
    
    // Cek apakah sertifikat sudah ada
    if ($participant->certificate_path) {
        echo "Sertifikat: {$participant->certificate_path}\n";
    }
    exit;
}

echo "=== PROSES ABSENSI ===\n\n";

try {
    // 1. Buat record attendance
    $attendance = \App\Models\Attendance::create([
        'participant_id' => $participant->id,
        'event_id' => $participant->event_id,
        'token_used' => $token,
        'attended_at' => now()
    ]);
    
    echo "✅ Attendance record created (ID: {$attendance->id})\n";
    
    // 2. Update status participant
    $participant->update([
        'status' => 'attended',
        'attended_at' => now()
    ]);
    
    echo "✅ Participant status updated to 'attended'\n";
    
    // 3. Generate sertifikat
    echo "\n=== GENERATE SERTIFIKAT ===\n\n";
    
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
        echo "✅ Directory created: {$directory}\n";
    }
    
    // Generate PDF using DomPDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.certificate', compact('participant'));
    $pdf->setPaper('A4', 'landscape');
    
    // Save PDF
    $pdf->save($fullPath);
    
    echo "✅ Sertifikat PDF berhasil di-generate!\n";
    echo "Path: {$path}\n";
    echo "Full path: {$fullPath}\n";
    
    // Update participant certificate path
    $participant->update(['certificate_path' => $path]);
    
    echo "✅ Certificate path updated in database\n";
    
    echo "\n=== SELESAI! ===\n\n";
    echo "🎉 ABSENSI BERHASIL!\n";
    echo "🎓 SERTIFIKAT OTOMATIS DI-GENERATE!\n\n";
    echo "Download sertifikat di:\n";
    echo "http://localhost/certificate/generate/{$participant->id}\n";
    echo "\nAtau lihat di:\n";
    echo "http://localhost/my-events\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
