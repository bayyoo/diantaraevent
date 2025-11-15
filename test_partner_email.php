<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "=== TESTING PARTNER EMAIL ===\n\n";

$testEmail = 'bysetias@gmail.com';
$testName = 'Test Partner';
$testOtp = '123456';

echo "Testing email to: {$testEmail}\n";
echo "OTP: {$testOtp}\n\n";

try {
    Mail::send('emails.partner-otp', ['otp' => $testOtp, 'name' => $testName], function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Verifikasi Akun Partner Nexus - Kode OTP');
    });
    
    echo "✅ Email sent successfully!\n";
    
} catch (\Exception $e) {
    echo "❌ Email failed to send:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Check mail configuration
    echo "\n=== MAIL CONFIGURATION ===\n";
    echo "MAIL_MAILER: " . config('mail.default') . "\n";
    echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
    echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
    echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
}
