<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create test user if not exists
        $testUser = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        // Create test event if not exists
        $testEvent = Event::first();
        
        if (!$testEvent) {
            $testEvent = Event::create([
                'title' => 'Test Event',
                'description' => 'Test event for attendance testing',
                'event_date' => now()->addDays(1),
                'event_time' => '10:00:00',
                'location' => 'Test Location',
                'capacity' => 100,
                'created_by' => 1
            ]);
        }

        // Create test participant with specific token for testing
        Participant::firstOrCreate([
            'token' => '1234567890'
        ], [
            'user_id' => $testUser->id,
            'event_id' => $testEvent->id,
            'token' => '1234567890',
            'status' => 'registered',
            'registered_at' => now()
        ]);

        // Create additional test participants with 10-digit tokens
        $tokens = ['2345678901', '3456789012', '4567890123', '5678901234', '6789012345'];
        
        foreach ($tokens as $token) {
            Participant::firstOrCreate([
                'token' => $token
            ], [
                'user_id' => $testUser->id,
                'event_id' => $testEvent->id,
                'token' => $token,
                'status' => 'registered',
                'registered_at' => now()
            ]);
        }
    }
}
