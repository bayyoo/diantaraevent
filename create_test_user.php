<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Creating test user for password reset...\n";

// Create verified test user
$user = User::updateOrCreate(
    ['email' => 'test@diantara.com'],
    [
        'name' => 'Test User',
        'full_name' => 'Test User Full Name',
        'phone' => '08123456789',
        'address' => 'Test Address',
        'last_education' => 'S1',
        'password' => bcrypt('password123'),
        'email_verified_at' => now()
    ]
);

echo "✅ Test user created/updated: {$user->email}\n";
echo "Password: password123\n";
echo "Email verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";

// Create unverified test user
$unverifiedUser = User::updateOrCreate(
    ['email' => 'unverified@diantara.com'],
    [
        'name' => 'Unverified User',
        'full_name' => 'Unverified User Full Name',
        'phone' => '08123456788',
        'address' => 'Test Address 2',
        'last_education' => 'S1',
        'password' => bcrypt('password123'),
        'email_verified_at' => null
    ]
);

echo "✅ Unverified test user created: {$unverifiedUser->email}\n";
echo "Email verified: " . ($unverifiedUser->email_verified_at ? 'YES' : 'NO') . "\n";

echo "\n🧪 TEST SCENARIOS:\n";
echo "1. Reset password with verified email: test@diantara.com\n";
echo "2. Reset password with unverified email: unverified@diantara.com\n";
echo "3. Reset password with non-existent email: nonexistent@diantara.com\n";
echo "\nYou can now test password reset functionality!\n";

echo "\n=== BUAT USER TEST ===\n\n";

// Cek apakah user test sudah ada
$existingUser = User::where('email', 'testuser@demo.com')->first();

if ($existingUser) {
    echo "User test sudah ada!\n";
    echo "Email: {$existingUser->email}\n";
    echo "ID: {$existingUser->id}\n";
    echo "Email Verified: " . ($existingUser->email_verified_at ? 'Yes' : 'No') . "\n";
} else {
    // Buat user baru
    $user = User::create([
        'name' => 'Test User Demo',
        'email' => 'testuser@demo.com',
        'password' => bcrypt('password123'),
        'phone' => '081234567890',
        'address' => 'Jl. Test Demo No. 123',
        'last_education' => 'S1 Informatika',
        'is_admin' => false,
        'email_verified_at' => now(), // Langsung verified biar bisa login
    ]);
    
    echo "User test berhasil dibuat!\n";
    echo "Email: {$user->email}\n";
    echo "Password: password123\n";
    echo "ID: {$user->id}\n";
}

echo "\n=== SEKARANG DAFTARKAN USER KE EVENT ===\n\n";

// Pilih event hari ini (ID 18 atau 24)
$event = \App\Models\Event::find(18); // Fintech Conference 2025

if (!$event) {
    echo "Event tidak ditemukan!\n";
    exit;
}

echo "Event: {$event->title}\n";
echo "Tanggal: {$event->event_date->format('Y-m-d')}\n";
echo "Jam: {$event->event_time->format('H:i')}\n\n";

// Cek apakah user sudah terdaftar
$user = \App\Models\User::where('email', 'testuser@demo.com')->first();
$existingParticipant = \App\Models\Participant::where('user_id', $user->id)
    ->where('event_id', $event->id)
    ->first();

if ($existingParticipant) {
    echo "User sudah terdaftar di event ini!\n";
    echo "Token: {$existingParticipant->token}\n";
    echo "Status: {$existingParticipant->status}\n";
} else {
    // Generate token 10 digit
    $token = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    
    // Daftarkan user ke event
    $participant = \App\Models\Participant::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'token' => $token,
        'status' => 'registered',
    ]);
    
    echo "✅ User berhasil didaftarkan!\n";
    echo "Token: {$token}\n";
    echo "Status: {$participant->status}\n";
    echo "\n⚠️ SIMPAN TOKEN INI UNTUK ABSENSI!\n";
}
