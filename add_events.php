<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Get first user or create admin user
$user = User::first();
if (!$user) {
    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@diantara.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
}

$events = [
    [
        'title' => 'SCREAM OK DANCE 2025',
        'slug' => Str::slug('SCREAM OK DANCE 2025'),
        'description' => 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah. Daftarkan tim dance terbaikmu!',
        'event_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
        'event_time' => '18:00:00',
        'location' => 'Jakarta Convention Center',
        'capacity' => 500,
        'created_by' => $user->id,
    ],
    [
        'title' => 'BERISIK ASIK Music Festival',
        'slug' => Str::slug('BERISIK ASIK Music Festival'),
        'description' => 'Festival musik dengan lineup artis ternama Indonesia. Nikmati pengalaman musik yang tak terlupakan!',
        'event_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
        'event_time' => '16:00:00',
        'location' => 'Gelora Bung Karno Stadium',
        'capacity' => 10000,
        'created_by' => $user->id,
    ],
    [
        'title' => 'SUCI FEST 2025',
        'slug' => Str::slug('SUCI FEST 2025'),
        'description' => 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air. Siap-siap ketawa sampai perut sakit!',
        'event_date' => Carbon::now()->addDays(18)->format('Y-m-d'),
        'event_time' => '20:00:00',
        'location' => 'Balai Sarbini Jakarta',
        'capacity' => 800,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Disney THE LITTLE MERMAID JR',
        'slug' => Str::slug('Disney THE LITTLE MERMAID JR'),
        'description' => 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga. Saksikan petualangan Ariel di bawah laut!',
        'event_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
        'event_time' => '15:00:00',
        'location' => 'Teater Jakarta',
        'capacity' => 300,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Workshop Laravel untuk Pemula',
        'slug' => Str::slug('Workshop Laravel untuk Pemula'),
        'description' => 'Belajar dasar-dasar Laravel framework dari nol hingga mahir. Workshop ini cocok untuk pemula yang ingin memulai karir sebagai web developer.',
        'event_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        'event_time' => '09:00:00',
        'location' => 'Aula Utama Kampus',
        'capacity' => 50,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Seminar Digital Marketing 2024',
        'slug' => Str::slug('Seminar Digital Marketing 2024'),
        'description' => 'Pelajari strategi digital marketing terkini untuk meningkatkan bisnis Anda di era digital. Pembicara dari praktisi berpengalaman.',
        'event_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
        'event_time' => '13:00:00',
        'location' => 'Hotel Grand Ballroom',
        'capacity' => 100,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Kompetisi Programming Contest',
        'slug' => Str::slug('Kompetisi Programming Contest'),
        'description' => 'Kompetisi programming untuk mahasiswa se-Indonesia. Hadiah total 50 juta rupiah untuk para pemenang.',
        'event_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
        'event_time' => '08:00:00',
        'location' => 'Lab Komputer Gedung A',
        'capacity' => 30,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Webinar UI/UX Design Trends',
        'slug' => Str::slug('Webinar UI/UX Design Trends'),
        'description' => 'Diskusi tentang tren terbaru dalam desain UI/UX dan bagaimana menerapkannya dalam proyek nyata.',
        'event_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
        'event_time' => '19:00:00',
        'location' => 'Online via Zoom',
        'capacity' => 200,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Tech Talk: AI & Machine Learning',
        'slug' => Str::slug('Tech Talk: AI & Machine Learning'),
        'description' => 'Pembahasan mendalam tentang perkembangan AI dan Machine Learning serta implementasinya dalam industri.',
        'event_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        'event_time' => '15:30:00',
        'location' => 'Auditorium Kampus',
        'capacity' => 150,
        'created_by' => $user->id,
    ],
    [
        'title' => 'Bootcamp React Native',
        'slug' => Str::slug('Bootcamp React Native'),
        'description' => 'Intensive bootcamp untuk belajar React Native dalam 3 hari. Dari basic hingga deploy aplikasi ke Play Store.',
        'event_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
        'event_time' => '09:00:00',
        'location' => 'Ruang Training Center',
        'capacity' => 25,
        'created_by' => $user->id,
    ],
];

foreach ($events as $eventData) {
    Event::updateOrCreate(
        ['slug' => $eventData['slug']],
        $eventData
    );
}

echo "Events created successfully!\n";
echo "Total events: " . Event::count() . "\n";
