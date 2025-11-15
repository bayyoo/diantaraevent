<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;

echo "=== CHECKING EVENTS ===\n\n";

$totalEvents = Event::count();
$publishedEvents = Event::where('status', 'published')->count();
$approvedEvents = Event::where('status', 'approved')->count();
$draftEvents = Event::where('status', 'draft')->count();
$pendingEvents = Event::where('status', 'pending')->count();

echo "📊 EVENT STATISTICS:\n";
echo "Total Events: {$totalEvents}\n";
echo "Published Events: {$publishedEvents}\n";
echo "Approved Events: {$approvedEvents}\n";
echo "Draft Events: {$draftEvents}\n";
echo "Pending Events: {$pendingEvents}\n\n";

echo "📋 RECENT EVENTS:\n";
$recentEvents = Event::latest()->take(10)->get();
foreach($recentEvents as $event) {
    echo "- {$event->title} (Status: {$event->status})\n";
}

echo "\n🔍 HOMEPAGE QUERY TEST:\n";
$homepageEvents = Event::where('status', 'published')
    ->orWhere('status', 'approved')
    ->latest()
    ->take(20)
    ->get();

echo "Events shown on homepage: " . $homepageEvents->count() . "\n";

if($homepageEvents->count() == 0) {
    echo "\n❌ NO EVENTS FOR HOMEPAGE!\n";
    echo "Let's fix this by updating event statuses...\n";
    
    $eventsToUpdate = Event::whereIn('status', ['draft', 'pending'])->get();
    foreach($eventsToUpdate as $event) {
        $event->status = 'published';
        $event->save();
        echo "✅ Updated: {$event->title} -> published\n";
    }
    
    echo "\nRechecking homepage events...\n";
    $homepageEventsAfter = Event::where('status', 'published')
        ->orWhere('status', 'approved')
        ->latest()
        ->take(20)
        ->get();
    echo "Events after fix: " . $homepageEventsAfter->count() . "\n";
}
