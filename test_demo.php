<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK EVENT YANG ADA ===\n\n";

$events = \App\Models\Event::select('id', 'title', 'event_date', 'event_time')->get();

if ($events->count() > 0) {
    foreach ($events as $event) {
        echo "ID: {$event->id}\n";
        echo "Title: {$event->title}\n";
        echo "Date: {$event->event_date->format('Y-m-d')}\n";
        echo "Time: " . ($event->event_time ? $event->event_time->format('H:i') : 'N/A') . "\n";
        echo "---\n";
    }
} else {
    echo "Belum ada event!\n";
}

echo "\n=== CEK USER YANG ADA ===\n\n";

$users = \App\Models\User::where('is_admin', 0)->select('id', 'name', 'email')->limit(5)->get();

if ($users->count() > 0) {
    foreach ($users as $user) {
        echo "ID: {$user->id} - {$user->name} ({$user->email})\n";
    }
} else {
    echo "Belum ada user!\n";
}
