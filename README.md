# Diantara - Event Management System

Sistem Informasi Manajemen Kegiatan (Event Management System) yang dikembangkan menggunakan Laravel 12 untuk memenuhi kebutuhan UKK (Uji Kompetensi Keahlian).

## 🚀 Fitur Utama

### ✅ Fitur yang Telah Diimplementasi

1. **Autentikasi & Keamanan**
   - Register dengan verifikasi OTP via email
   - Login/Logout dengan session management
   - Session timeout otomatis (5 menit)
   - Role-based access control (Admin/User)

2. **Manajemen Event (Admin)**
   - CRUD Event lengkap
   - Upload flyer event
   - Validasi H-3 untuk pembuatan event
   - Auto-close pendaftaran saat event dimulai

3. **Manajemen Peserta (Admin)**
   - CRUD Peserta lengkap
   - Generate token unik 10 digit
   - Tracking status peserta

4. **Pendaftaran Event (User)**
   - Katalog event publik dengan search & filter
   - Pendaftaran event dengan token via email
   - Sistem absensi dengan verifikasi token
   - Generate sertifikat otomatis setelah absensi

5. **Dashboard Admin**
   - Statistik event per bulan (Chart.js)
   - Statistik peserta per bulan
   - Top 10 event berdasarkan jumlah peserta
   - Export data ke CSV (Events, Participants, Attendances)

6. **Sistem Absensi**
   - Verifikasi token 10 digit
   - Absensi hanya bisa dilakukan saat event berlangsung
   - Update status peserta otomatis

7. **Generate Sertifikat**
   - Sertifikat HTML otomatis setelah absensi
   - Nomor sertifikat unik
   - Template sertifikat yang dapat dikustomisasi

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Frontend**: Blade Templates, Tailwind CSS, Bootstrap
- **Database**: MySQL/MariaDB
- **Charts**: Chart.js
- **Email**: Laravel Mail
- **File Storage**: Laravel Storage

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM
- XAMPP/LARAGON (untuk development)

## 🔧 Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd DIANTARA-main
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=diantara_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Email Configuration**
   Konfigurasi email di `.env` untuk fitur OTP:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

6. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Build Assets**
   ```bash
   npm run build
   ```

9. **Run Application**
   ```bash
   php artisan serve
   ```

## 👤 Default Users

Setelah seeding, gunakan akun berikut untuk testing:

**Admin:**
- Email: admin@diantara.com
- Password: password

**User:**
- Email: user@diantara.com
- Password: password

## 📱 Struktur Database

### Tables
- `users` - Data pengguna
- `events` - Data event
- `participants` - Data peserta event
- `attendances` - Data kehadiran
- `certificates` - Data sertifikat

### Relations
- User hasMany Participants
- Event hasMany Participants
- Participant hasOne Attendance
- Participant hasOne Certificate

## 🔄 Workflow Sistem

1. **Admin** membuat event (minimal H-3)
2. **User** mendaftar dan verifikasi OTP
3. **User** melihat katalog event dan mendaftar
4. **System** kirim token 10 digit via email
5. **User** hadir dan input token untuk absensi
6. **System** generate sertifikat otomatis
7. **Admin** monitor statistik dan export data

## 🎯 Fitur UKK yang Dipenuhi

- ✅ Register dengan OTP verification
- ✅ CRUD Event & Participants (Admin)
- ✅ Event registration dengan token
- ✅ Attendance system dengan token verification
- ✅ Auto certificate generation
- ✅ H-3 validation untuk event creation
- ✅ Auto-close registration
- ✅ Dashboard dengan statistics
- ✅ Export data CSV
- ✅ Session timeout 5 menit
- ✅ Public event catalog dengan search/filter

## 🚧 Development Status

**Completed Features:**
- Core authentication system
- Event management (CRUD)
- Participant management (CRUD)
- Event registration system
- Attendance verification
- Certificate generation
- Admin dashboard with charts
- Data export functionality
- Session timeout implementation
- Public event catalog

**Pending Features:**
- Mobile responsive design optimization
- PWA implementation (manifest.json & service worker)

## 📞 Support

Untuk pertanyaan atau bantuan, silakan hubungi developer melalui:
- Email: developer@diantara.com
- GitHub Issues: [Repository Issues](repository-url/issues)

## 📄 License

This project is licensed under the MIT License.

---

**Diantara Event Management System** - Dikembangkan untuk UKK (Uji Kompetensi Keahlian) 2024
