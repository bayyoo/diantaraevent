<?php

use App\Models\Participant;
use App\Models\EventAttendance;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Bootstrap Laravel
$kernel->bootstrap();

echo "Starting attendance token sync...".PHP_EOL;

$processed = 0;

Participant::chunk(100, function ($participants) use (&$processed) {
    foreach ($participants as $participant) {
        $token = $participant->attendance_token ?: $participant->token;

        if (!$token) {
            continue;
        }

        // Pastikan kedua kolom di participants sama
        $participant->attendance_token = $token;
        $participant->token = $token;
        $participant->save();

        // Sinkronkan ke tabel event_attendances
        EventAttendance::updateOrCreate(
            [
                'event_id' => $participant->event_id,
                'user_id'  => $participant->user_id,
            ],
            [
                'attendance_token' => $token,
                'is_attended'      => false,
            ]
        );

        $processed++;
    }
});

echo "Done. Synced participants: {$processed}".PHP_EOL;

