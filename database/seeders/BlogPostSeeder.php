<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;
use Carbon\Carbon;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user as author
        $admin = User::first();
        
        if (!$admin) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $posts = [
            [
                'title' => '15 Event Seru di Jakarta 2025 yang Wajib Kamu Kunjungi',
                'excerpt' => 'Temukan berbagai event menarik di Jakarta sepanjang tahun 2025, dari konser musik hingga pameran seni.',
                'content' => '<p>Jakarta sebagai ibu kota Indonesia selalu menjadi pusat berbagai event menarik. Tahun 2025 diprediksi akan menjadi tahun yang penuh dengan acara-acara spektakuler yang sayang untuk dilewatkan.</p><p>Dari konser musik internasional, festival kuliner, pameran seni, hingga kompetisi olahraga, Jakarta menawarkan beragam pilihan hiburan untuk semua kalangan.</p><p>Berikut adalah 15 event yang wajib masuk dalam daftar kunjungan kamu di tahun 2025...</p>',
                'category' => 'event',
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Tips Memilih Event yang Tepat Sesuai Minat Kamu',
                'excerpt' => 'Panduan lengkap memilih event yang sesuai dengan minat dan budget kamu.',
                'content' => '<p>Memilih event yang tepat bisa jadi tantangan tersendiri, terutama dengan banyaknya pilihan yang tersedia. Artikel ini akan membantu kamu menemukan event yang paling sesuai dengan minat dan kebutuhan.</p><p>Pertama, kenali minat dan hobi kamu. Apakah kamu lebih suka musik, seni, olahraga, atau teknologi? Setelah itu, tentukan budget yang tersedia...</p>',
                'category' => 'tips',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Cara Mendapatkan Tiket Event favorit dengan Harga Terbaik',
                'excerpt' => 'Strategi jitu mendapatkan tiket event impian tanpa harus merogoh kocek terlalu dalam.',
                'content' => '<p>Mendapatkan tiket event favorit dengan harga terbaik memerlukan strategi yang tepat. Berikut beberapa tips yang bisa kamu terapkan.</p><p>Early bird promo adalah kesempatan terbaik untuk mendapatkan harga spesial. Biasanya tiket early bird dijual dengan diskon hingga 40%...</p>',
                'category' => 'promo',
                'published_at' => Carbon::now()->subDays(7),
            ],
            [
                'title' => 'Festival Musik Terbesar di Indonesia 2025',
                'excerpt' => 'Jangan lewatkan festival musik terbesar yang akan menghadirkan artis internasional dan lokal terbaik.',
                'content' => '<p>Tahun 2025 akan menjadi tahun yang luar biasa bagi pecinta musik di Indonesia. Berbagai festival musik besar akan digelar dengan lineup artis yang spektakuler.</p><p>Dari We The Fest, Java Jazz Festival, hingga Soundrenaline, semuannya akan hadir dengan konsep yang lebih fresh dan menarik...</p>',
                'category' => 'event',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Panduan Lengkap Menghadiri Event Pertama Kali',
                'excerpt' => 'Tips dan trik untuk first-timer agar pengalaman menghadiri event jadi lebih menyenangkan.',
                'content' => '<p>Menghadiri event untuk pertama kalinya bisa jadi pengalaman yang mendebarkan. artikel ini akan memberikan panduan lengkap agar kamu bisa menikmati event dengan maksimal.</p><p>Persiapan adalah kunci. Pastikan kamu sudah membaca informasi event dengan detail, termasuk lokasi, waktu, dan aturan yang berlaku...</p>',
                'category' => 'tips',
                'published_at' => Carbon::now()->subDays(12),
            ],
            [
                'title' => 'Event Teknologi dan Startup yang Wajib Dihadiri',
                'excerpt' => 'Konferensi dan workshop teknologi terbaik untuk mengembangkan skill dan networking.',
                'content' => '<p>Bagi kamu yang bergerak di bidang teknologi dan startup, menghadiri event-event industri adalah investasi yang sangat berharga.</p><p>Dari Tech in Asia Conference, Startup Grind, hingga berbagai hackathon, semuannya menawarkan kesempatan belajar dan networking yang luar biasa...</p>',
                'category' => 'event',
                'published_at' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($posts as $postData) {
            BlogPost::create([
                'title' => $postData['title'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'category' => $postData['category'],
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => $postData['published_at'],
            ]);
        }
    }
}
