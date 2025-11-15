<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Create or get admin user
$user = User::firstOrCreate(
    ['email' => 'admin@diantara.com'],
    [
        'name' => 'Admin',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);

echo "User created/found: " . $user->name . "\n";

// Create events
$events = [
    [
        'title' => 'SCREAM OK DANCE 2025',
        'slug' => 'scream-ok-dance-2025',
        'description' => 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah!',
        'event_date' => Carbon::now()->addDays(12),
        'event_time' => '18:00:00',
        'location' => 'Jakarta Convention Center',
        'capacity' => 500,
        'created_by' => $user->id
    ],
    [
        'title' => 'BERISIK ASIK Music Festival',
        'slug' => 'berisik-asik-music-festival',
        'description' => 'Festival musik dengan lineup artis ternama Indonesia!',
        'event_date' => Carbon::now()->addDays(25),
        'event_time' => '16:00:00',
        'location' => 'Gelora Bung Karno Stadium',
        'capacity' => 10000,
        'created_by' => $user->id
    ],
    [
        'title' => 'SUCI FEST 2025',
        'slug' => 'suci-fest-2025',
        'description' => 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air!',
        'event_date' => Carbon::now()->addDays(18),
        'event_time' => '20:00:00',
        'location' => 'Balai Sarbini Jakarta',
        'capacity' => 800,
        'created_by' => $user->id
    ],
    [
        'title' => 'Disney THE LITTLE MERMAID JR',
        'slug' => 'disney-the-little-mermaid-jr',
        'description' => 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga!',
        'event_date' => Carbon::now()->addDays(8),
        'event_time' => '15:00:00',
        'location' => 'Teater Jakarta',
        'capacity' => 300,
        'created_by' => $user->id
    ],
    [
        'title' => 'Konser K.O.M',
        'slug' => 'konser-kom',
        'description' => 'Konser musik K-Pop terbesar dengan artis internasional!',
        'event_date' => Carbon::now()->addDays(30),
        'event_time' => '19:00:00',
        'location' => 'ICE BSD City',
        'capacity' => 5000,
        'created_by' => $user->id
    ],
    [
        'title' => 'Workshop Laravel untuk Pemula',
        'slug' => 'workshop-laravel-untuk-pemula',
        'description' => 'Belajar dasar-dasar Laravel framework dari nol hingga mahir.',
        'event_date' => Carbon::now()->addDays(5),
        'event_time' => '09:00:00',
        'location' => 'Aula Utama Kampus',
        'capacity' => 50,
        'created_by' => $user->id
    ],
    [
        'title' => 'IMPACTNATION JAPAN FESTIVAL 2025',
        'slug' => 'impactnation-japan-festival-2025',
        'description' => 'Festival budaya Jepang terbesar di Indonesia.',
        'event_date' => Carbon::now()->addDays(22),
        'event_time' => '10:00:00',
        'location' => 'Jakarta International Expo',
        'capacity' => 2000,
        'created_by' => $user->id
    ],
    [
        'title' => 'MAGNIFEST 2025',
        'slug' => 'magnifest-2025',
        'description' => 'Festival seni dan budaya dengan berbagai pertunjukan menarik.',
        'event_date' => Carbon::now()->addDays(15),
        'event_time' => '14:00:00',
        'location' => 'Taman Ismail Marzuki',
        'capacity' => 1500,
        'created_by' => $user->id
    ]
];

foreach ($events as $eventData) {
    $event = Event::firstOrCreate(
        ['slug' => $eventData['slug']],
        $eventData
    );
    echo "Event: " . $event->title . " - " . ($event->wasRecentlyCreated ? "CREATED" : "EXISTS") . "\n";
}

echo "\nTotal events in database: " . Event::count() . "\n";
