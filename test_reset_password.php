<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Password;

echo "=== TESTING RESET PASSWORD ===\n\n";

// Test dengan email yang ada di form
$testEmail = 'test@diantara.com';

echo "1. Checking user: {$testEmail}\n";
$user = User::where('email', $testEmail)->first();

if ($user) {
    echo "✅ User found!\n";
    echo "   - Name: {$user->name}\n";
    echo "   - Email verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
    echo "   - Created: {$user->created_at}\n";
    
    if (!$user->email_verified_at) {
        echo "❌ User not verified! Verifying now...\n";
        $user->email_verified_at = now();
        $user->save();
        echo "✅ User verified!\n";
    }
    
    echo "\n2. Testing password reset...\n";
    try {
        $status = Password::sendResetLink(['email' => $testEmail]);
        
        if ($status === Password::RESET_LINK_SENT) {
            echo "✅ Reset link sent successfully!\n";
            echo "   Check your email: {$testEmail}\n";
            echo "   Also check spam/junk folder\n";
        } else {
            echo "❌ Failed to send reset link\n";
            echo "   Status: {$status}\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ User not found!\n";
    echo "Creating user for testing...\n";
    
    $user = User::create([
        'name' => 'Test User',
        'full_name' => 'Test User Full Name',
        'email' => $testEmail,
        'phone' => '08123456789',
        'address' => 'Test Address',
        'last_education' => 'S1',
        'password' => bcrypt('password123'),
        'email_verified_at' => now()
    ]);
    
    echo "✅ User created and verified!\n";
    echo "Now try the reset password again.\n";
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. Go to: http://127.0.0.1:8000/forgot-password\n";
echo "2. Enter email: {$testEmail}\n";
echo "3. Check email inbox (and spam folder)\n";
echo "4. If still no email, provide your real Gmail address\n";
