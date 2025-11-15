<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database connection
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

try {
    // Update all events that don't have status or have NULL status to 'published'
    $updated = Capsule::table('events')
        ->whereNull('status')
        ->orWhere('status', '')
        ->orWhere('status', 'draft')
        ->update([
            'status' => 'published',
            'approved_at' => now(),
            'updated_at' => now()
        ]);

    echo "✅ Updated {$updated} events to 'published' status\n";

    // Show current events count
    $totalEvents = Capsule::table('events')->count();
    $publishedEvents = Capsule::table('events')->where('status', 'published')->count();
    
    echo "📊 Total events: {$totalEvents}\n";
    echo "📊 Published events: {$publishedEvents}\n";
    
    // Show some sample events
    $sampleEvents = Capsule::table('events')
        ->select('id', 'title', 'status', 'event_date')
        ->limit(5)
        ->get();
    
    echo "\n📋 Sample events:\n";
    foreach ($sampleEvents as $event) {
        echo "- ID: {$event->id} | {$event->title} | Status: {$event->status} | Date: {$event->event_date}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
