<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = 'bysetias@gmail.com';

$user = User::where('email', $email)->first();

if ($user) {
    $user->email_verified_at = now();
    $user->save();
    
    echo "✅ Email verified for: {$user->email}\n";
    echo "✅ Admin status: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
    echo "✅ Email verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
} else {
    echo "❌ User not found\n";
}
