<?php

// Simple script to add events quickly
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

// Database configuration
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'diantara',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Check if users table exists and get first user
try {
    $userId = Capsule::table('users')->first()->id ?? 1;
} catch (Exception $e) {
    echo "Creating admin user...\n";
    $userId = Capsule::table('users')->insertGetId([
        'name' => 'Admin',
        'email' => 'admin@diantara.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
        'email_verified_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
}

// Events data
$events = [
    [
        'title' => 'SCREAM OK DANCE 2025',
        'slug' => 'scream-ok-dance-2025',
        'description' => 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah. Daftarkan tim dance terbaikmu!',
        'event_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
        'event_time' => '18:00:00',
        'location' => 'Jakarta Convention Center',
        'capacity' => 500,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'BERISIK ASIK Music Festival',
        'slug' => 'berisik-asik-music-festival',
        'description' => 'Festival musik dengan lineup artis ternama Indonesia. Nikmati pengalaman musik yang tak terlupakan!',
        'event_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
        'event_time' => '16:00:00',
        'location' => 'Gelora Bung Karno Stadium',
        'capacity' => 10000,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'SUCI FEST 2025',
        'slug' => 'suci-fest-2025',
        'description' => 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air. Siap-siap ketawa sampai perut sakit!',
        'event_date' => Carbon::now()->addDays(18)->format('Y-m-d'),
        'event_time' => '20:00:00',
        'location' => 'Balai Sarbini Jakarta',
        'capacity' => 800,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'Disney THE LITTLE MERMAID JR',
        'slug' => 'disney-the-little-mermaid-jr',
        'description' => 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga. Saksikan petualangan Ariel di bawah laut!',
        'event_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
        'event_time' => '15:00:00',
        'location' => 'Teater Jakarta',
        'capacity' => 300,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'Konser K.O.M',
        'slug' => 'konser-kom',
        'description' => 'Konser musik K-Pop terbesar dengan artis internasional. Jangan sampai terlewat!',
        'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
        'event_time' => '19:00:00',
        'location' => 'ICE BSD City',
        'capacity' => 5000,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'Workshop Laravel untuk Pemula',
        'slug' => 'workshop-laravel-untuk-pemula',
        'description' => 'Belajar dasar-dasar Laravel framework dari nol hingga mahir. Workshop ini cocok untuk pemula yang ingin memulai karir sebagai web developer.',
        'event_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        'event_time' => '09:00:00',
        'location' => 'Aula Utama Kampus',
        'capacity' => 50,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'IMPACTNATION JAPAN FESTIVAL 2025',
        'slug' => 'impactnation-japan-festival-2025',
        'description' => 'Festival budaya Jepang terbesar di Indonesia. Nikmati berbagai pertunjukan, makanan, dan budaya Jepang.',
        'event_date' => Carbon::now()->addDays(22)->format('Y-m-d'),
        'event_time' => '10:00:00',
        'location' => 'Jakarta International Expo',
        'capacity' => 2000,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'MAGNIFEST 2025',
        'slug' => 'magnifest-2025',
        'description' => 'Festival seni dan budaya dengan berbagai pertunjukan menarik dari seniman lokal dan internasional.',
        'event_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
        'event_time' => '14:00:00',
        'location' => 'Taman Ismail Marzuki',
        'capacity' => 1500,
        'created_by' => $userId,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
];

// Insert events
foreach ($events as $event) {
    try {
        // Check if event already exists
        $existing = Capsule::table('events')->where('slug', $event['slug'])->first();
        if (!$existing) {
            Capsule::table('events')->insert($event);
            echo "Created event: " . $event['title'] . "\n";
        } else {
            echo "Event already exists: " . $event['title'] . "\n";
        }
    } catch (Exception $e) {
        echo "Error creating event " . $event['title'] . ": " . $e->getMessage() . "\n";
    }
}

echo "\nDone! Total events in database: " . Capsule::table('events')->count() . "\n";
