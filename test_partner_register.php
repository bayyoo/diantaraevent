<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Partner;
use Illuminate\Support\Facades\Hash;

echo "=== TESTING PARTNER REGISTRATION ===\n\n";

// Test data
$testData = [
    'name' => 'Test Partner',
    'email' => 'testpartner@example.com',
    'password' => Hash::make('password123'),
    'phone' => '08123456789',
    'organization_name' => 'Test Organization',
    'address' => 'Test Address',
    'status' => 'pending'
];

try {
    // Check if partner already exists
    $existingPartner = Partner::where('email', $testData['email'])->first();
    if ($existingPartner) {
        echo "Partner already exists, deleting...\n";
        $existingPartner->delete();
    }
    
    // Create new partner
    $partner = Partner::create($testData);
    
    echo "✅ Partner created successfully!\n";
    echo "ID: {$partner->id}\n";
    echo "Name: {$partner->name}\n";
    echo "Email: {$partner->email}\n";
    echo "Status: {$partner->status}\n";
    
} catch (Exception $e) {
    echo "❌ Error creating partner:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== CHECKING PARTNER MODEL ===\n";
try {
    $partners = Partner::all();
    echo "Total partners: " . $partners->count() . "\n";
    foreach($partners as $partner) {
        echo "- {$partner->name} ({$partner->email}) - {$partner->status}\n";
    }
} catch (Exception $e) {
    echo "❌ Error reading partners: " . $e->getMessage() . "\n";
}
