<?php
// Simple database connection and event population
$host = '127.0.0.1';
$dbname = 'diantara';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Populating Events Database</h2>";
    
    // Check if admin user exists, create if not
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute(['admin@diantara.com']);
    $admin = $stmt->fetch();
    
    if (!$admin) {
        echo "<p>Creating admin user...</p>";
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, email_verified_at, is_admin, created_at, updated_at) VALUES (?, ?, ?, NOW(), 1, NOW(), NOW())");
        $stmt->execute(['Admin', 'admin@diantara.com', password_hash('password', PASSWORD_DEFAULT)]);
        $adminId = $pdo->lastInsertId();
    } else {
        $adminId = $admin['id'];
    }
    
    // Events data
    $events = [
        [
            'title' => 'SCREAM OK DANCE 2025',
            'slug' => 'scream-ok-dance-2025',
            'description' => 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah. Daftarkan tim dance terbaikmu!',
            'event_date' => date('Y-m-d', strtotime('+12 days')),
            'event_time' => '18:00:00',
            'location' => 'Jakarta Convention Center',
            'capacity' => 500,
        ],
        [
            'title' => 'BERISIK ASIK Music Festival',
            'slug' => 'berisik-asik-music-festival',
            'description' => 'Festival musik dengan lineup artis ternama Indonesia. Nikmati pengalaman musik yang tak terlupakan!',
            'event_date' => date('Y-m-d', strtotime('+25 days')),
            'event_time' => '16:00:00',
            'location' => 'Gelora Bung Karno Stadium',
            'capacity' => 10000,
        ],
        [
            'title' => 'SUCI FEST 2025',
            'slug' => 'suci-fest-2025',
            'description' => 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air. Siap-siap ketawa sampai perut sakit!',
            'event_date' => date('Y-m-d', strtotime('+18 days')),
            'event_time' => '20:00:00',
            'location' => 'Balai Sarbini Jakarta',
            'capacity' => 800,
        ],
        [
            'title' => 'Disney THE LITTLE MERMAID JR',
            'slug' => 'disney-the-little-mermaid-jr',
            'description' => 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga. Saksikan petualangan Ariel di bawah laut!',
            'event_date' => date('Y-m-d', strtotime('+8 days')),
            'event_time' => '15:00:00',
            'location' => 'Teater Jakarta',
            'capacity' => 300,
        ],
        [
            'title' => 'Workshop Laravel untuk Pemula',
            'slug' => 'workshop-laravel-untuk-pemula',
            'description' => 'Belajar dasar-dasar Laravel framework dari nol hingga mahir. Workshop ini cocok untuk pemula yang ingin memulai karir sebagai web developer.',
            'event_date' => date('Y-m-d', strtotime('+5 days')),
            'event_time' => '09:00:00',
            'location' => 'Aula Utama Kampus',
            'capacity' => 50,
        ],
        [
            'title' => 'Tech Talk: AI & Machine Learning',
            'slug' => 'tech-talk-ai-machine-learning',
            'description' => 'Pembahasan mendalam tentang perkembangan AI dan Machine Learning serta implementasinya dalam industri.',
            'event_date' => date('Y-m-d', strtotime('+3 days')),
            'event_time' => '15:30:00',
            'location' => 'Auditorium Kampus',
            'capacity' => 150,
        ],
        [
            'title' => 'IMPACTNATION JAPAN FESTIVAL 2025',
            'slug' => 'impactnation-japan-festival-2025',
            'description' => 'Festival budaya Jepang terbesar di Indonesia. Nikmati berbagai pertunjukan, makanan, dan budaya Jepang.',
            'event_date' => date('Y-m-d', strtotime('+22 days')),
            'event_time' => '10:00:00',
            'location' => 'Jakarta International Expo',
            'capacity' => 2000,
        ],
        [
            'title' => 'MAGNIFEST 2025',
            'slug' => 'magnifest-2025',
            'description' => 'Festival seni dan budaya dengan berbagai pertunjukan menarik dari seniman lokal dan internasional.',
            'event_date' => date('Y-m-d', strtotime('+15 days')),
            'event_time' => '14:00:00',
            'location' => 'Taman Ismail Marzuki',
            'capacity' => 1500,
        ],
    ];
    
    $created = 0;
    $existing = 0;
    
    foreach ($events as $event) {
        // Check if event exists
        $stmt = $pdo->prepare("SELECT id FROM events WHERE slug = ? LIMIT 1");
        $stmt->execute([$event['slug']]);
        
        if (!$stmt->fetch()) {
            // Insert new event
            $stmt = $pdo->prepare("INSERT INTO events (title, slug, description, event_date, event_time, location, capacity, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $event['title'],
                $event['slug'],
                $event['description'],
                $event['event_date'],
                $event['event_time'],
                $event['location'],
                $event['capacity'],
                $adminId
            ]);
            echo "<p>✅ Created: " . $event['title'] . "</p>";
            $created++;
        } else {
            echo "<p>⚠️ Already exists: " . $event['title'] . "</p>";
            $existing++;
        }
    }
    
    // Get total count
    $stmt = $pdo->query("SELECT COUNT(*) FROM events");
    $total = $stmt->fetchColumn();
    
    echo "<h3>Summary:</h3>";
    echo "<p>Created: {$created} events</p>";
    echo "<p>Already existed: {$existing} events</p>";
    echo "<p>Total events in database: {$total}</p>";
    echo "<p><a href='/'>← Back to Homepage</a></p>";
    
} catch (PDOException $e) {
    echo "<p>Database Error: " . $e->getMessage() . "</p>";
}
?>
