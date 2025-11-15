<?php

// Simple script to add events
$host = '127.0.0.1';
$dbname = 'antaradb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if users exist, if not create admin user
    $userStmt = $pdo->query("SELECT id FROM users LIMIT 1");
    $user = $userStmt->fetch();
    
    if (!$user) {
        $pdo->exec("INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES 
                   ('Admin', 'admin@diantara.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', NOW(), NOW(), NOW())");
        $userId = $pdo->lastInsertId();
        echo "Admin user created with ID: $userId\n";
    } else {
        $userId = $user['id'];
        echo "Using existing user ID: $userId\n";
    }
    
    // Events data
    $events = [
        // Festival & Konser
        ['SCREAM OK DANCE 2025', 'scream-ok-dance-2025', 'Kompetisi dance terbesar tahun ini dengan hadiah jutaan rupiah!', date('Y-m-d', strtotime('+12 days')), '18:00:00', 'Jakarta Convention Center', 500],
        ['BERISIK ASIK Music Festival', 'berisik-asik-music-festival', 'Festival musik dengan lineup artis ternama Indonesia!', date('Y-m-d', strtotime('+25 days')), '16:00:00', 'Gelora Bung Karno Stadium', 10000],
        ['Konser K.O.M', 'konser-kom', 'Konser musik K-Pop terbesar dengan artis internasional!', date('Y-m-d', strtotime('+30 days')), '19:00:00', 'ICE BSD City', 5000],
        ['JAZZ FESTIVAL 2025', 'jazz-festival-2025', 'Festival jazz internasional dengan musisi kelas dunia!', date('Y-m-d', strtotime('+35 days')), '19:30:00', 'Plaza Senayan', 2000],
        ['ROCK N ROLL NIGHT', 'rock-n-roll-night', 'Konser rock legendaris dengan band-band ternama!', date('Y-m-d', strtotime('+40 days')), '20:00:00', 'Stadion Utama Gelora Bung Karno', 15000],
        
        // Comedy & Entertainment
        ['SUCI FEST 2025', 'suci-fest-2025', 'Stand Up Comedy Indonesia Festival dengan komika terbaik tanah air!', date('Y-m-d', strtotime('+18 days')), '20:00:00', 'Balai Sarbini Jakarta', 800],
        ['Disney THE LITTLE MERMAID JR', 'disney-the-little-mermaid-jr', 'Pertunjukan musikal Disney yang memukau untuk seluruh keluarga!', date('Y-m-d', strtotime('+8 days')), '15:00:00', 'Teater Jakarta', 300],
        ['MAGIC SHOW SPECTACULAR', 'magic-show-spectacular', 'Pertunjukan sulap menakjubkan dari master magician internasional!', date('Y-m-d', strtotime('+28 days')), '19:00:00', 'Teater Taman Ismail Marzuki', 400],
        
        // Workshop & Training
        ['Workshop Laravel untuk Pemula', 'workshop-laravel-untuk-pemula', 'Belajar dasar-dasar Laravel framework dari nol hingga mahir.', date('Y-m-d', strtotime('+5 days')), '09:00:00', 'Aula Utama Kampus', 50],
        ['Workshop Digital Marketing', 'workshop-digital-marketing', 'Pelajari strategi digital marketing yang efektif untuk bisnis modern.', date('Y-m-d', strtotime('+7 days')), '09:00:00', 'Co-working Space Sudirman', 30],
        ['Workshop Photography & Videography', 'workshop-photography-videography', 'Kuasai teknik fotografi dan videografi profesional dari ahlinya.', date('Y-m-d', strtotime('+10 days')), '08:00:00', 'Studio Photography Jakarta', 25],
        ['Workshop UI/UX Design', 'workshop-ui-ux-design', 'Belajar desain interface dan user experience yang menarik.', date('Y-m-d', strtotime('+14 days')), '09:30:00', 'Design Hub Jakarta', 40],
        ['Workshop Data Science & AI', 'workshop-data-science-ai', 'Pelajari data science dan artificial intelligence untuk masa depan.', date('Y-m-d', strtotime('+21 days')), '09:00:00', 'Tech Innovation Center', 60],
        
        // Seminar & Conference
        ['Tech Talk: AI & Machine Learning', 'tech-talk-ai-machine-learning', 'Pembahasan mendalam tentang perkembangan AI dan Machine Learning.', date('Y-m-d', strtotime('+9 days')), '15:30:00', 'Auditorium Kampus', 150],
        ['Seminar Entrepreneurship', 'seminar-entrepreneurship', 'Tips dan strategi menjadi entrepreneur sukses dari praktisi berpengalaman.', date('Y-m-d', strtotime('+16 days')), '13:00:00', 'Convention Hall Jakarta', 200],
        ['Fintech Conference 2025', 'fintech-conference-2025', 'Konferensi teknologi finansial terbesar di Indonesia.', date('Y-m-d', strtotime('+32 days')), '08:30:00', 'Jakarta Convention Center', 500],
        ['Blockchain & Cryptocurrency Summit', 'blockchain-cryptocurrency-summit', 'Pelajari masa depan blockchain dan cryptocurrency.', date('Y-m-d', strtotime('+38 days')), '09:00:00', 'Hotel Mulia Jakarta', 300],
        
        // Festival Budaya
        ['IMPACTNATION JAPAN FESTIVAL 2025', 'impactnation-japan-festival-2025', 'Festival budaya Jepang terbesar di Indonesia.', date('Y-m-d', strtotime('+22 days')), '10:00:00', 'Jakarta International Expo', 2000],
        ['MAGNIFEST 2025', 'magnifest-2025', 'Festival seni dan budaya dengan berbagai pertunjukan menarik.', date('Y-m-d', strtotime('+15 days')), '14:00:00', 'Taman Ismail Marzuki', 1500],
        ['FESTIVAL KULINER NUSANTARA', 'festival-kuliner-nusantara', 'Nikmati kelezatan kuliner tradisional dari seluruh Nusantara.', date('Y-m-d', strtotime('+20 days')), '11:00:00', 'Lapangan Monas', 3000],
        ['FESTIVAL BUDAYA BALI', 'festival-budaya-bali', 'Pertunjukan budaya Bali yang autentik dan menawan.', date('Y-m-d', strtotime('+26 days')), '16:00:00', 'Cultural Park Bali', 1200],
        
        // Competition & Sports
        ['GAMING COMPETITION 2025', 'gaming-competition-2025', 'Kompetisi gaming terbesar dengan hadiah fantastis!', date('Y-m-d', strtotime('+33 days')), '10:00:00', 'Gaming Arena Jakarta', 500],
        ['FUTSAL CHAMPIONSHIP', 'futsal-championship', 'Turnamen futsal dengan tim-tim terbaik se-Jakarta.', date('Y-m-d', strtotime('+19 days')), '08:00:00', 'GOR Soemantri Brojonegoro', 200],
        ['COOKING COMPETITION', 'cooking-competition', 'Kompetisi memasak dengan chef-chef profesional sebagai juri.', date('Y-m-d', strtotime('+24 days')), '09:00:00', 'Culinary Studio Jakarta', 80],
        
        // Art & Culture
        ['ART EXHIBITION: Modern Indonesian Art', 'art-exhibition-modern-indonesian', 'Pameran seni modern Indonesia dari seniman ternama.', date('Y-m-d', strtotime('+17 days')), '10:00:00', 'National Gallery Jakarta', 300],
        ['PHOTOGRAPHY EXHIBITION', 'photography-exhibition', 'Pameran fotografi dengan karya-karya menakjubkan.', date('Y-m-d', strtotime('+29 days')), '14:00:00', 'Galeri Foto Jakarta', 150],
        ['BOOK FAIR & LITERATURE FESTIVAL', 'book-fair-literature-festival', 'Pameran buku dan festival literasi terbesar tahun ini.', date('Y-m-d', strtotime('+36 days')), '09:00:00', 'Perpustakaan Nasional', 1000],
        
        // Health & Wellness
        ['YOGA & MEDITATION WORKSHOP', 'yoga-meditation-workshop', 'Workshop yoga dan meditasi untuk kesehatan mental dan fisik.', date('Y-m-d', strtotime('+11 days')), '06:00:00', 'Taman Suropati', 100],
        ['HEALTH & FITNESS EXPO', 'health-fitness-expo', 'Pameran kesehatan dan kebugaran dengan berbagai produk terbaru.', date('Y-m-d', strtotime('+34 days')), '09:00:00', 'Jakarta Convention Center', 800],
        
        // Business & Networking
        ['STARTUP PITCH COMPETITION', 'startup-pitch-competition', 'Kompetisi pitch startup dengan investor dan mentor berpengalaman.', date('Y-m-d', strtotime('+27 days')), '13:30:00', 'Co-working Space Sudirman', 120],
        ['BUSINESS NETWORKING EVENT', 'business-networking-event', 'Acara networking untuk pebisnis dan entrepreneur.', date('Y-m-d', strtotime('+31 days')), '18:30:00', 'Hotel Grand Indonesia', 200],
        
        // Technology & Innovation
        ['ROBOTICS COMPETITION', 'robotics-competition', 'Kompetisi robotika untuk mahasiswa dan profesional.', date('Y-m-d', strtotime('+23 days')), '09:00:00', 'Universitas Indonesia', 300],
        ['HACKATHON 2025', 'hackathon-2025', 'Marathon coding 48 jam untuk menciptakan solusi inovatif.', date('Y-m-d', strtotime('+37 days')), '08:00:00', 'Tech Hub Jakarta', 150]
    ];
    
    foreach ($events as $event) {
        // Check if event exists
        $checkStmt = $pdo->prepare("SELECT id FROM events WHERE slug = ?");
        $checkStmt->execute([$event[1]]);
        
        if (!$checkStmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO events (title, slug, description, event_date, event_time, location, capacity, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$event[0], $event[1], $event[2], $event[3], $event[4], $event[5], $event[6], $userId]);
            echo "Created event: " . $event[0] . "\n";
        } else {
            echo "Event already exists: " . $event[0] . "\n";
        }
    }
    
    $countStmt = $pdo->query("SELECT COUNT(*) FROM events");
    $count = $countStmt->fetchColumn();
    echo "\nTotal events in database: $count\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
