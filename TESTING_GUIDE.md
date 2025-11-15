# 🎯 PANDUAN TESTING SISTEM SERTIFIKAT

## 📋 DATA TESTING YANG SUDAH DIBUAT:

### User Test:
- **Email:** testuser@demo.com
- **Password:** password123
- **Status:** Sudah verified, bisa langsung login

### Event:
- **Nama:** Fintech Conference 2025
- **ID:** 18
- **Tanggal:** 23 Oktober 2025 (HARI INI)
- **Jam:** 08:30 WIB

### Token Absensi:
- **Token:** 2646990005
- **Status:** Sudah digunakan untuk absensi
- **Sertifikat:** Sudah di-generate

---

## 🚀 CARA TESTING:

### OPSI 1: Test dengan User yang Sudah Ada (RECOMMENDED)

#### Step 1: Login
1. Buka browser: **http://localhost:8000/login**
2. Masukkan:
   - Email: `testuser@demo.com`
   - Password: `password123`
3. Klik **Login**

#### Step 2: Lihat Event Saya
1. Setelah login, klik menu **"My Events"** atau **"Transaksi Event"**
2. Atau langsung buka: **http://localhost:8000/my-events**

#### Step 3: Lihat Status Event
Lo bakal lihat event **"Fintech Conference 2025"** dengan:
- ✅ Badge hijau **"Sudah Hadir"**
- ✅ Tombol hijau **"Unduh Sertifikat"**

#### Step 4: Download Sertifikat
1. Klik tombol **"Unduh Sertifikat"**
2. PDF sertifikat akan otomatis ter-download
3. Buka PDF dan lihat sertifikat dengan nama **"Test User Demo"**

---

### OPSI 2: Test Full Flow dari Awal (Daftar → Absen → Sertifikat)

#### Step 1: Buat User Baru
1. Buka: **http://localhost:8000/register**
2. Isi form registrasi
3. Verifikasi email dengan OTP
4. Login

#### Step 2: Daftar Event Baru
1. Buka: **http://localhost:8000/catalog**
2. Pilih event yang **tanggalnya HARI INI**
3. Klik **"Daftar Event"**
4. **SIMPAN TOKEN** yang muncul di halaman sukses
5. Cek email untuk token (jika email sudah dikonfigurasi)

#### Step 3: Absensi
1. Buka: **http://localhost:8000/my-events**
2. Cari event yang baru didaftar
3. Klik tombol **"Absen Sekarang"** (hanya muncul di hari H)
4. Masukkan **token 10 digit**
5. Klik **"Konfirmasi Absensi"**

#### Step 4: Otomatis Dapat Sertifikat
- Setelah absen sukses, otomatis redirect ke halaman sertifikat
- Klik **"Download PDF"**
- Sertifikat langsung ter-download!

---

### OPSI 3: Test via Script PHP (Backend Testing)

Jalankan script yang sudah dibuat:

```bash
# 1. Cek event & user yang ada
php test_demo.php

# 2. Buat user baru & daftar event
php create_test_user.php

# 3. Test absensi & generate sertifikat
php test_attendance.php
```

---

## 🔍 TROUBLESHOOTING:

### ❌ "Absensi belum dibuka"
**Solusi:** Event harus hari ini dan jam sudah lewat. Ubah tanggal/jam event:
1. Login sebagai admin: **http://localhost:8000/admin/login**
2. Edit event, ubah tanggal jadi hari ini
3. Ubah jam jadi sebelum jam sekarang

### ❌ "Token tidak valid"
**Solusi:** 
- Pastikan token yang diinput **PERSIS SAMA** dengan yang dikirim
- Token harus 10 digit angka
- Cek token di database atau email

### ❌ Sertifikat tidak muncul
**Solusi:**
- Pastikan sudah absen (status = "attended")
- Refresh halaman "My Events"
- Cek folder: `storage/app/public/certificates/`

### ❌ Server tidak jalan
**Solusi:**
```bash
# Stop server yang lama
Ctrl + C

# Start ulang
php artisan serve
```

---

## 📂 LOKASI FILE PENTING:

### Sertifikat PDF:
```
storage/app/public/certificates/certificate_8_1761184834.pdf
```

### View Sertifikat:
```
resources/views/certificates/certificate.blade.php
```

### Controller:
```
app/Http/Controllers/CertificateController.php
app/Http/Controllers/AttendanceController.php
```

### Model:
```
app/Models/Attendance.php (SUDAH DIPERBAIKI)
app/Models/Certificate.php
app/Models/Participant.php
```

---

## 🎓 ALUR LENGKAP SISTEM:

```
1. User Daftar Event
   ↓
2. Dapat Token 10 Digit via Email
   ↓
3. Hari H Event (setelah jam event)
   ↓
4. User Input Token untuk Absensi
   ↓
5. Sistem Validasi Token
   ↓
6. Buat Record Attendance
   ↓
7. Update Status Participant → "attended"
   ↓
8. 🎯 OTOMATIS GENERATE SERTIFIKAT PDF
   ↓
9. Simpan Path Sertifikat ke Database
   ↓
10. User Download Sertifikat Kapan Aja
```

---

## ✅ CHECKLIST TESTING:

- [ ] Login berhasil dengan user test
- [ ] Bisa lihat event di "My Events"
- [ ] Badge "Sudah Hadir" muncul
- [ ] Tombol "Unduh Sertifikat" muncul
- [ ] Sertifikat PDF ter-download
- [ ] Sertifikat berisi nama user yang benar
- [ ] Sertifikat berisi nama event yang benar
- [ ] Sertifikat landscape A4
- [ ] Nomor sertifikat unik muncul

---

## 🔗 QUICK LINKS:

- **Login:** http://localhost:8000/login
- **My Events:** http://localhost:8000/my-events
- **Catalog:** http://localhost:8000/catalog
- **Certificate Search:** http://localhost:8000/certificate/search
- **Admin Login:** http://localhost:8000/admin/login

---

**SELAMAT TESTING! 🚀**

Kalau ada error, cek file `storage/logs/laravel.log`
