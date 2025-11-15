<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Password;

$gmailAddress = 'bysetiawan04@gmail.com';

echo "=== CREATING USER WITH YOUR GMAIL ===\n\n";

// Create or update user with your Gmail
$user = User::updateOrCreate(
    ['email' => $gmailAddress],
    [
        'name' => 'Setiawan',
        'full_name' => 'Setiawan Full Name',
        'phone' => '08123456789',
        'address' => 'Alamat Test',
        'last_education' => 'S1',
        'password' => bcrypt('password123'),
        'email_verified_at' => now() // Langsung verified
    ]
);

echo "✅ User created/updated: {$user->email}\n";
echo "✅ Email verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
echo "✅ Password: password123\n\n";

echo "=== TESTING RESET PASSWORD ===\n";

try {
    $status = Password::sendResetLink(['email' => $gmailAddress]);
    
    if ($status === Password::RESET_LINK_SENT) {
        echo "✅ Reset password email sent successfully!\n";
        echo "📧 Check your Gmail: {$gmailAddress}\n";
        echo "📁 Also check SPAM/JUNK folder\n";
        echo "⏰ Email might take 1-2 minutes to arrive\n\n";
    } else {
        echo "❌ Failed to send reset email\n";
        echo "Status: {$status}\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== NEXT STEPS ===\n";
echo "1. Check your Gmail inbox: {$gmailAddress}\n";
echo "2. Check SPAM/JUNK folder if not in inbox\n";
echo "3. Or test reset password via web:\n";
echo "   - Go to: http://127.0.0.1:8000/forgot-password\n";
echo "   - Enter: {$gmailAddress}\n";
echo "   - Click 'Kirim Link Reset Password'\n\n";

echo "=== USER LOGIN INFO ===\n";
echo "Email: {$gmailAddress}\n";
echo "Password: password123\n";
echo "Status: Verified ✅\n";
