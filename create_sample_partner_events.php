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
    // Check if we have partners
    $partners = Capsule::table('partners')->where('status', 'verified')->get();
    
    if ($partners->isEmpty()) {
        echo "❌ No verified partners found. Creating sample partner first...\n";
        
        // Create sample partner
        $partnerId = Capsule::table('partners')->insertGetId([
            'name' => 'Creative Events Indonesia',
            'email' => 'partner@creativevents.id',
            'password' => bcrypt('password123'),
            'status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create partner organization
        $orgId = Capsule::table('partner_organizations')->insertGetId([
            'partner_id' => $partnerId,
            'name' => 'Creative Events Indonesia',
            'type' => 'event_organizer',
            'description' => 'Professional event organizer specializing in creative and cultural events',
            'contact_info' => json_encode([
                'phone' => '+62812345678',
                'email' => 'info@creativevents.id',
                'website' => 'https://creativevents.id'
            ]),
            'business_info' => json_encode([
                'registration_number' => 'CV-2024-001',
                'tax_number' => '12.345.678.9-123.000',
                'address' => 'Jl. Sudirman No. 123, Jakarta Selatan'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Created sample partner and organization\n";
    } else {
        $partnerId = $partners->first()->id;
        $orgId = Capsule::table('partner_organizations')->where('partner_id', $partnerId)->first()->id ?? null;
    }
    
    if (!$orgId) {
        echo "❌ No partner organization found\n";
        return;
    }
    
    // Create sample partner events
    $partnerEvents = [
        [
            'partner_id' => $partnerId,
            'organization_id' => $orgId,
            'title' => 'Jakarta Creative Festival 2025',
            'slug' => 'jakarta-creative-festival-2025',
            'description' => 'Festival kreativitas terbesar di Jakarta dengan berbagai workshop, pameran seni, dan pertunjukan musik dari seniman lokal dan internasional.',
            'category' => 'festival',
            'start_date' => date('Y-m-d H:i:s', strtotime('+5 days')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'location' => 'Jakarta Convention Center',
            'location_details' => json_encode([
                'address' => 'Jl. Gatot Subroto, Jakarta Selatan',
                'venue_type' => 'convention_center',
                'facilities' => ['parking', 'food_court', 'wifi']
            ]),
            'status' => 'published',
            'published_at' => now(),
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'partner_id' => $partnerId,
            'organization_id' => $orgId,
            'title' => 'Digital Art Workshop Series',
            'slug' => 'digital-art-workshop-series',
            'description' => 'Workshop series untuk belajar digital art dari basic hingga advanced. Dipandu oleh digital artist profesional dengan pengalaman internasional.',
            'category' => 'workshop',
            'start_date' => date('Y-m-d H:i:s', strtotime('+10 days')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+10 days')),
            'location' => 'Creative Hub Jakarta',
            'location_details' => json_encode([
                'address' => 'Jl. Kemang Raya No. 45, Jakarta Selatan',
                'venue_type' => 'creative_space',
                'facilities' => ['computer_lab', 'drawing_tablet', 'projector']
            ]),
            'status' => 'published',
            'published_at' => now(),
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'partner_id' => $partnerId,
            'organization_id' => $orgId,
            'title' => 'Indie Music Concert: Rising Stars',
            'slug' => 'indie-music-concert-rising-stars',
            'description' => 'Konser musik indie menampilkan band-band lokal berbakat. Nikmati musik berkualitas dalam suasana yang intimate dan mendukung musisi lokal.',
            'category' => 'concert',
            'start_date' => date('Y-m-d H:i:s', strtotime('+15 days')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+15 days')),
            'location' => 'The Hall Senayan City',
            'location_details' => json_encode([
                'address' => 'Senayan City Mall, Jakarta Pusat',
                'venue_type' => 'concert_hall',
                'facilities' => ['sound_system', 'lighting', 'bar']
            ]),
            'status' => 'published',
            'published_at' => now(),
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'partner_id' => $partnerId,
            'organization_id' => $orgId,
            'title' => 'Startup Pitch Competition 2025',
            'slug' => 'startup-pitch-competition-2025',
            'description' => 'Kompetisi pitch untuk startup Indonesia dengan total hadiah 1 miliar rupiah. Kesempatan untuk bertemu dengan investor dan mentor terbaik.',
            'category' => 'competition',
            'start_date' => date('Y-m-d H:i:s', strtotime('+20 days')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+20 days')),
            'location' => 'Universitas Indonesia',
            'location_details' => json_encode([
                'address' => 'Kampus UI Depok, Jawa Barat',
                'venue_type' => 'university',
                'facilities' => ['auditorium', 'networking_area', 'exhibition_space']
            ]),
            'status' => 'published',
            'published_at' => now(),
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'partner_id' => $partnerId,
            'organization_id' => $orgId,
            'title' => 'Culinary Festival Nusantara',
            'slug' => 'culinary-festival-nusantara',
            'description' => 'Festival kuliner terbesar dengan 200+ booth makanan tradisional dari seluruh Indonesia. Cooking demo, food competition, dan cultural performance.',
            'category' => 'festival',
            'start_date' => date('Y-m-d H:i:s', strtotime('+25 days')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+27 days')),
            'location' => 'Monas Jakarta',
            'location_details' => json_encode([
                'address' => 'Lapangan Monas, Jakarta Pusat',
                'venue_type' => 'outdoor',
                'facilities' => ['food_court', 'stage', 'parking']
            ]),
            'status' => 'published',
            'published_at' => now(),
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]
    ];
    
    foreach ($partnerEvents as $event) {
        Capsule::table('partner_events')->insert($event);
    }
    
    echo "✅ Created " . count($partnerEvents) . " sample partner events\n";
    
    // Show statistics
    $totalPartnerEvents = Capsule::table('partner_events')->count();
    $publishedPartnerEvents = Capsule::table('partner_events')->where('status', 'published')->count();
    
    echo "📊 Total partner events: {$totalPartnerEvents}\n";
    echo "📊 Published partner events: {$publishedPartnerEvents}\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
