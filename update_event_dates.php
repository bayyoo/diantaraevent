<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'antaradb',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Get all events
    $events = Capsule::table('events')->get();
    
    echo "📊 Total events found: " . $events->count() . "\n";
    
    $updated = 0;
    $today = date('Y-m-d');
    
    foreach ($events as $index => $event) {
        // Create a mix of past, present, and future events
        if ($index % 3 == 0) {
            // Future events (upcoming)
            $newDate = date('Y-m-d', strtotime('+' . rand(1, 30) . ' days'));
        } elseif ($index % 3 == 1) {
            // Recent past events
            $newDate = date('Y-m-d', strtotime('-' . rand(1, 15) . ' days'));
        } else {
            // Today or very soon
            $newDate = date('Y-m-d', strtotime('+' . rand(0, 7) . ' days'));
        }
        
        Capsule::table('events')
            ->where('id', $event->id)
            ->update([
                'event_date' => $newDate,
                'updated_at' => now()
            ]);
            
        $updated++;
    }
    
    echo "✅ Updated {$updated} events with new dates\n";
    
    // Show statistics
    $upcomingCount = Capsule::table('events')
        ->where('event_date', '>=', $today)
        ->count();
        
    $pastCount = Capsule::table('events')
        ->where('event_date', '<', $today)
        ->count();
    
    echo "📅 Upcoming events: {$upcomingCount}\n";
    echo "📅 Past events: {$pastCount}\n";
    
    // Show some sample events
    $sampleEvents = Capsule::table('events')
        ->select('id', 'title', 'event_date')
        ->orderBy('event_date', 'desc')
        ->limit(5)
        ->get();
    
    echo "\n📋 Sample events (latest dates):\n";
    foreach ($sampleEvents as $event) {
        $status = $event->event_date >= $today ? '🔮 Upcoming' : '📅 Past';
        echo "- {$status} | {$event->title} | {$event->event_date}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
