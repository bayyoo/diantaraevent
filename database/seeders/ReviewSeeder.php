<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Event;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $events = Event::take(10)->get();
        
        $comments = [
            'Event yang sangat menarik dan bermanfaat!',
            'Pengalaman yang luar biasa, sangat direkomendasikan!',
            'Organizernya profesional, acaranya seru!',
            'Worth it banget, ilmunya daging semua!',
            'Tempatnya bagus, fasilitasnya lengkap!',
            'Pematerinya keren, materinya mudah dipahami!',
            'Networking yang bagus, ketemu banyak orang baru!',
            'Acaranya terorganisir dengan baik!',
            'Sangat puas dengan event ini!',
            'Recommended untuk yang mau belajar hal baru!',
        ];

        foreach ($events as $event) {
            // Random 2-4 reviews per event
            $reviewCount = rand(2, 4);
            $selectedUsers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($selectedUsers as $user) {
                try {
                    Review::create([
                        'user_id' => $user->id,
                        'reviewable_id' => $event->id,
                        'reviewable_type' => Event::class,
                        'rating' => rand(3, 5),
                        'comment' => $comments[array_rand($comments)],
                    ]);
                } catch (\Exception $e) {
                    // Skip if duplicate (user already reviewed this event)
                    continue;
                }
            }
        }
    }
}
