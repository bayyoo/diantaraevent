<?php

require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Get or create admin user
$admin = User::where('email', 'admin@diantara.com')->first();
if (!$admin) {
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@diantara.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
        'is_admin' => true,
    ]);
}

$events = [
    [
        'title' => 'SCREAM OK DANCE 2025',
        'description' => 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah. Daftarkan tim dance terbaikmu!',
        'event_date' => Carbon::now()->addDays(12),
        'event_time' => '18:00:00',
        'location' => 'Jakarta Convention Center',
        'capacity' => 500,
    ],
    [
        'title' => 'BERISIK ASIK Music Festival',
        'description' => 'Festival musik dengan lineup artis ternama Indonesia. Nikmati pengalaman musik yang tak terlupakan!',
        'event_date' => Carbon::now()->addDays(25),
        'event_time' => '16:00:00',
        'location' => 'Gelora Bung Karno Stadium',
        'capacity' => 10000,
    ],
    [
        'title' => 'SUCI FEST 2025',
        'description' => 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air. Siap-siap ketawa sampai perut sakit!',
        'event_date' => Carbon::now()->addDays(18),
        'event_time' => '20:00:00',
        'location' => 'Balai Sarbini Jakarta',
        'capacity' => 800,
    ],
    [
        'title' => 'Disney THE LITTLE MERMAID JR',
        'description' => 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga. Saksikan petualangan Ariel di bawah laut!',
        'event_date' => Carbon::now()->addDays(8),
        'event_time' => '15:00:00',
        'location' => 'Teater Jakarta',
        'capacity' => 300,
    ],
    [
        'title' => 'Workshop Laravel untuk Pemula',
        'description' => 'Belajar dasar-dasar Laravel framework dari nol hingga mahir. Workshop ini cocok untuk pemula yang ingin memulai karir sebagai web developer.',
        'event_date' => Carbon::now()->addDays(5),
        'event_time' => '09:00:00',
        'location' => 'Aula Utama Kampus',
        'capacity' => 50,
    ],
    [
        'title' => 'Tech Talk: AI & Machine Learning',
        'description' => 'Pembahasan mendalam tentang perkembangan AI dan Machine Learning serta implementasinya dalam industri.',
        'event_date' => Carbon::now()->addDays(3),
        'event_time' => '15:30:00',
        'location' => 'Auditorium Kampus',
        'capacity' => 150,
    ],
    [
        'title' => 'IMPACTNATION JAPAN FESTIVAL 2025',
        'description' => 'Festival budaya Jepang terbesar di Indonesia. Nikmati berbagai pertunjukan, makanan, dan budaya Jepang.',
        'event_date' => Carbon::now()->addDays(22),
        'event_time' => '10:00:00',
        'location' => 'Jakarta International Expo',
        'capacity' => 2000,
    ],
    [
        'title' => 'MAGNIFEST 2025',
        'description' => 'Festival seni dan budaya dengan berbagai pertunjukan menarik dari seniman lokal dan internasional.',
        'event_date' => Carbon::now()->addDays(15),
        'event_time' => '14:00:00',
        'location' => 'Taman Ismail Marzuki',
        'capacity' => 1500,
    ],
];

$created = 0;
$existing = 0;

foreach ($events as $eventData) {
    $slug = Str::slug($eventData['title']);
    
    $event = Event::where('slug', $slug)->first();
    
    if (!$event) {
        Event::create([
            'title' => $eventData['title'],
            'slug' => $slug,
            'description' => $eventData['description'],
            'event_date' => $eventData['event_date'],
            'event_time' => $eventData['event_time'],
            'location' => $eventData['location'],
            'capacity' => $eventData['capacity'],
            'created_by' => $admin->id,
        ]);
        echo "✅ Created: " . $eventData['title'] . "\n";
        $created++;
    } else {
        echo "⚠️ Already exists: " . $eventData['title'] . "\n";
        $existing++;
    }
}

echo "\n=== Summary ===\n";
echo "Created: {$created} events\n";
echo "Already existed: {$existing} events\n";
echo "Total events: " . Event::count() . "\n";
echo "\nEvents have been added! Visit http://127.0.0.1:8000 to see them.\n";
