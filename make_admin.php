<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = 'bysetias@gmail.com';

echo "=== MAKING USER ADMIN ===\n\n";

// Find user by email
$user = User::where('email', $email)->first();

if ($user) {
    echo "✅ User found: {$user->email}\n";
    echo "Current admin status: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
    
    if (!$user->is_admin) {
        $user->is_admin = true;
        $user->save();
        echo "✅ User has been made admin!\n";
    } else {
        echo "ℹ️ User is already an admin.\n";
    }
    
    echo "\n=== USER INFO ===\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Admin: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
    echo "Email Verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
    
} else {
    echo "❌ User not found with email: {$email}\n";
    echo "Creating admin user...\n";
    
    $user = User::create([
        'name' => 'Admin User',
        'full_name' => 'Administrator',
        'email' => $email,
        'phone' => '08123456789',
        'address' => 'Admin Address',
        'last_education' => 'S1',
        'password' => bcrypt('password123'),
        'is_admin' => true,
        'email_verified_at' => now()
    ]);
    
    echo "✅ Admin user created!\n";
    echo "Email: {$user->email}\n";
    echo "Password: password123\n";
    echo "Admin: YES\n";
}

echo "\n=== ADMIN ACCESS ===\n";
echo "You can now access admin panel at:\n";
echo "URL: http://127.0.0.1:8000/admin\n";
echo "Email: {$email}\n";
echo "Password: " . ($user->wasRecentlyCreated ? 'password123' : 'your existing password') . "\n";
