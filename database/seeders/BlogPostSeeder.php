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
                'title' => '15 Event Seru di Jakarta 2025, Dari Festival Musik hingga Pameran Karier',
                'excerpt' => 'DIANTARA merangkum 15 agenda besar di Jakarta sepanjang 2025 yang diprediksi akan menarik puluhan ribu pengunjung, mulai dari festival musik, konferensi teknologi, hingga pameran pendidikan.',
                'content' => '<p>Jakarta kembali bersiap menjadi pusat berbagai gelaran berskala nasional dan internasional pada 2025. Berdasarkan data penyelenggara dan kalender event resmi, setidaknya ada puluhan acara besar yang akan digelar sepanjang tahun mendatang.</p><p>Dalam kurasi DIANTARA, terdapat 15 event utama yang patut mendapat perhatian publik. Deretan acara tersebut mencakup festival musik lintas genre, pameran karier dan pendidikan, hingga konferensi teknologi yang menargetkan pelaku industri digital.</p><p>Sejumlah penyelenggara menyebutkan bahwa edisi 2025 akan menghadirkan pengalaman yang lebih interaktif, mulai dari zona experiential, sesi networking terkurasi, hingga integrasi layanan digital seperti e-ticketing dan e-certificate. Informasi lengkap mengenai tanggal, lokasi, dan cara pendaftaran dapat dipantau melalui platform DIANTARA.</p>',
                'category' => 'event',
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Cara Memilih Event yang Relevan dengan Karier dan Minat Kamu',
                'excerpt' => 'Banyaknya pilihan acara sering membuat peserta bingung menentukan prioritas. Berikut panduan praktis menyaring event yang benar-benar memberi dampak bagi pengembangan diri dan karier.',
                'content' => '<p>Dalam beberapa tahun terakhir, jumlah event profesional dan hiburan di kota-kota besar meningkat signifikan. Kondisi ini memberi banyak opsi bagi masyarakat, namun di sisi lain membuat keputusan memilih event menjadi tidak sederhana.</p><p>Pakar pengembangan karier menyarankan agar peserta terlebih dahulu memetakan tujuan utama: apakah ingin menambah skill, memperluas jaringan, atau sekadar mencari hiburan. Setelah tujuan jelas, barulah menyaring event berdasarkan reputasi penyelenggara, kurikulum acara, dan profil pembicara.</p><p>Peserta juga disarankan memeriksa fasilitas pendukung seperti sertifikat, materi pasca acara, hingga kemungkinan tindak lanjut komunitas. Dengan pendekatan ini, setiap tiket yang dibeli tidak hanya menjadi konsumsi hiburan, tetapi juga investasi jangka panjang.</p>',
                'category' => 'tips',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Strategi Mendapatkan Tiket Event Favorit dengan Harga Promo',
                'excerpt' => 'Tim DIANTARA merangkum pola promosi yang kerap digunakan penyelenggara event, mulai dari early bird hingga bundling tiket, agar peserta bisa mengatur budget dengan lebih efisien.',
                'content' => '<p>Persaingan mendapatkan tiket event populer sering kali membuat harga melambung dalam waktu singkat. Namun, pelaku industri menyebut masih banyak cara yang dapat dimanfaatkan peserta untuk memperoleh harga lebih terjangkau.</p><p>Salah satu strategi yang paling umum adalah memanfaatkan periode early bird. Pada fase ini, penyelenggara biasanya memberikan diskon 20–40 persen untuk mendorong penjualan awal dan mendapatkan gambaran minat peserta.</p><p>Selain itu, beberapa event menawarkan skema bundling, kode referral komunitas, hingga pembayaran bertahap melalui mitra pembayaran digital. Dengan memantau kanal resmi penyelenggara dan platform agregator seperti DIANTARA, peserta memiliki peluang lebih besar untuk tidak tertinggal informasi promo.</p>',
                'category' => 'promo',
                'published_at' => Carbon::now()->subDays(7),
            ],
            [
                'title' => 'Festival Musik 2025 Diprediksi Dongkrak Sektor Pariwisata Lokal',
                'excerpt' => 'Rangkaian festival musik berskala besar yang digelar di sejumlah kota pada 2025 diperkirakan memberikan efek ganda bagi sektor pariwisata dan ekonomi kreatif.',
                'content' => '<p>Kalangan promotor memperkirakan bahwa musim festival musik 2025 akan menjadi salah satu yang paling sibuk dalam satu dekade terakhir. Jadwal yang padat, ditambah nama-nama besar dari dalam dan luar negeri, membuka peluang kunjungan wisata ke daerah penyelenggara.</p><p>Pemerintah daerah menyambut tren ini dengan menyiapkan paket kolaborasi bersama pelaku hotel, transportasi, dan UMKM lokal. Konsepnya, satu tiket festival tidak hanya memberikan akses ke panggung utama, tetapi juga ke berbagai aktivitas pendukung seperti tur wisata tematik dan bazar produk kreatif.</p><p>Sejumlah analis menilai, jika dikelola dengan baik, festival musik dapat menjadi sarana promosi kota sekaligus motor penggerak ekonomi yang berkelanjutan. Data dan jadwal resmi festival dapat dipantau melalui kanal informasi DIANTARA.</p>',
                'category' => 'event',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Checklist Lengkap untuk Kamu yang Baru Pertama Kali Hadir di Event Besar',
                'excerpt' => 'Mulai dari registrasi, manajemen waktu, hingga etika selama acara, berikut daftar hal yang sebaiknya disiapkan peserta pemula sebelum menghadiri event skala besar.',
                'content' => '<p>Bagi banyak orang, menghadiri event besar seperti konferensi, konser, atau festival menjadi pengalaman pertama yang mendebarkan. Tanpa persiapan yang matang, peserta berisiko melewatkan sesi penting atau merasa kewalahan di lokasi.</p><p>Praktisi event management menyarankan beberapa langkah dasar: membaca informasi resmi dengan saksama, mengunduh tiket dan QR code jauh hari, serta memastikan transportasi dan akomodasi jika diperlukan. Peserta juga dianjurkan datang lebih awal untuk proses registrasi dan orientasi area.</p><p>Di dalam venue, penting untuk memperhatikan etika dasar seperti tidak menghalangi pandangan peserta lain, menghormati aturan dokumentasi, dan memanfaatkan area istirahat saat diperlukan. Dengan mengikuti checklist ini, pengalaman pertama menghadiri event akan terasa lebih terstruktur dan menyenangkan.</p>',
                'category' => 'tips',
                'published_at' => Carbon::now()->subDays(12),
            ],
            [
                'title' => 'Konferensi Teknologi dan Startup Jadi Magnet Kolaborasi Baru',
                'excerpt' => 'Berbagai konferensi dan meetup startup yang digelar tahun ini disebut berhasil mempertemukan investor, pendiri perusahaan rintisan, serta talenta digital muda dalam satu ekosistem.',
                'content' => '<p>Gelaran konferensi teknologi dan startup dalam beberapa tahun terakhir menunjukkan perubahan pola interaksi antar pelaku industri. Jika sebelumnya acara lebih banyak berfokus pada presentasi satu arah, kini formatnya berkembang menjadi ruang kolaborasi yang lebih terbuka.</p><p>Pada sejumlah konferensi, penyelenggara menyediakan sesi matchmaking yang mempertemukan founder dengan calon investor serta program mentoring singkat bersama praktisi berpengalaman. Di sisi lain, talenta digital mendapatkan akses ke lowongan kerja, kompetisi ide, dan workshop teknis.</p><p>Pakar ekosistem startup menilai, keberlanjutan event semacam ini krusial untuk menjaga aliran pengetahuan dan pendanaan. Platform seperti DIANTARA membantu mengkurasi informasi jadwal dan pendaftaran sehingga pelaku industri dapat memilih acara yang paling relevan dengan kebutuhan mereka.</p>',
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
