    
     <?php
// Direct database population script
$host = '127.0.0.1';
$dbname = 'diantara';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Adding Events to Database...</h2>";
    
    // Check if admin user exists, create if not
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute(['admin@diantara.com']);
    $admin = $stmt->fetch();
    
    if (!$admin) {
        echo "<p>Creating admin user...</p>";
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, email_verified_at, is_admin, created_at, updated_at) VALUES (?, ?, ?, NOW(), 1, NOW(), NOW())");
        $stmt->execute(['Admin', 'admin@diantara.com', password_hash('password', PASSWORD_DEFAULT)]);
        $adminId = $pdo->lastInsertId();
        echo "<p>✅ Admin user created with ID: $adminId</p>";
    } else {
        $adminId = $admin['id'];
        echo "<p>✅ Using existing admin user ID: $adminId</p>";
    }
    
    // Events data with future dates
    $events = [
        [
            'title' => 'SCREAM OK DANCE 2025',
            'slug' => 'scream-ok-dance-2025',
            'description' => 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah. Daftarkan tim dance terbaikmu!',
            'event_date' => '2025-09-18',
            'event_time' => '18:00:00',
            'location' => 'Jakarta Convention Center',
            'capacity' => 500,
        ],
        [
            'title' => 'BERISIK ASIK Music Festival',
            'slug' => 'berisik-asik-music-festival',
            'description' => 'Festival musik dengan lineup artis ternama Indonesia. Nikmati pengalaman musik yang tak terlupakan!',
            'event_date' => '2025-10-01',
            'event_time' => '16:00:00',
            'location' => 'Gelora Bung Karno Stadium',
            'capacity' => 10000,
        ],
        [
            'title' => 'SUCI FEST 2025',
            'slug' => 'suci-fest-2025',
            'description' => 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air. Siap-siap ketawa sampai perut sakit!',
            'event_date' => '2025-09-24',
            'event_time' => '20:00:00',
            'location' => 'Balai Sarbini Jakarta',
            'capacity' => 800,
        ],
        [
            'title' => 'Disney THE LITTLE MERMAID JR',
            'slug' => 'disney-the-little-mermaid-jr',
            'description' => 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga. Saksikan petualangan Ariel di bawah laut!',
            'event_date' => '2025-09-14',
            'event_time' => '15:00:00',
            'location' => 'Teater Jakarta',
            'capacity' => 300,
        ],
        [
            'title' => 'Workshop Laravel untuk Pemula',
            'slug' => 'workshop-laravel-untuk-pemula',
            'description' => 'Belajar dasar-dasar Laravel framework dari nol hingga mahir. Workshop ini cocok untuk pemula yang ingin memulai karir sebagai web developer.',
            'event_date' => '2025-09-11',
            'event_time' => '09:00:00',
            'location' => 'Aula Utama Kampus',
            'capacity' => 50,
        ],
        [
            'title' => 'Tech Talk: AI & Machine Learning',
            'slug' => 'tech-talk-ai-machine-learning',
            'description' => 'Pembahasan mendalam tentang perkembangan AI dan Machine Learning serta implementasinya dalam industri.',
            'event_date' => '2025-09-09',
            'event_time' => '15:30:00',
            'location' => 'Auditorium Kampus',
            'capacity' => 150,
        ],
        [
            'title' => 'IMPACTNATION JAPAN FESTIVAL 2025',
            'slug' => 'impactnation-japan-festival-2025',
            'description' => 'Festival budaya Jepang terbesar di Indonesia. Nikmati berbagai pertunjukan, makanan, dan budaya Jepang.',
            'event_date' => '2025-09-28',
            'event_time' => '10:00:00',
            'location' => 'Jakarta International Expo',
            'capacity' => 2000,
        ],
        [
            'title' => 'MAGNIFEST 2025',
            'slug' => 'magnifest-2025',
            'description' => 'Festival seni dan budaya dengan berbagai pertunjukan menarik dari seniman lokal dan internasional.',
            'event_date' => '2025-09-21',
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
            echo "<p>✅ Created: " . $event['title'] . " (Date: " . $event['event_date'] . ")</p>";
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
    echo "<p><strong>Created:</strong> {$created} events</p>";
    echo "<p><strong>Already existed:</strong> {$existing} events</p>";
    echo "<p><strong>Total events in database:</strong> {$total}</p>";
    
    if ($created > 0) {
        echo "<p style='color: green; font-weight: bold;'>✅ Events successfully added! Go back to homepage to see them.</p>";
    }
    
    echo "<p><a href='/' style='background: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px;'>← Back to Homepage</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and the database 'diantara' exists.</p>";
}
?>
