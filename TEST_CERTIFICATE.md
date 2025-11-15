# 📋 Test Certificate PDF - Checklist

## ✅ Persiapan

### 1. Pastikan Package Terinstall
```bash
composer show barryvdh/laravel-dompdf
```
**Expected Output:** Informasi package version 3.1+

Jika belum install:
```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
php artisan cache:clear
```

### 2. Cek Storage Permission
```bash
php artisan storage:link
```

### 3. Start Server
```bash
php artisan serve
```

---

## 🧪 Test Scenarios

### Test 1: Certificate Search Page (Public)
**URL:** `http://localhost:8000/certificate/search`

**Steps:**
1. Buka URL di browser
2. Lihat form search

**Expected Result:**
- ✅ Form search muncul dengan input field
- ✅ Ada placeholder "Contoh: John Doe atau john@example.com"
- ✅ Ada button "Cari Sertifikat"
- ✅ Ada info box di bawah

**Status:** [ ] Pass / [ ] Fail

---

### Test 2: Search Certificate (No Data)
**URL:** `http://localhost:8000/certificate/search`

**Steps:**
1. Input nama random: "Testing User 123"
2. Klik "Cari Sertifikat"

**Expected Result:**
- ✅ Muncul pesan info biru: "Tidak ada sertifikat ditemukan untuk pencarian: Testing User 123"
- ✅ Tidak ada error

**Status:** [ ] Pass / [ ] Fail

---

### Test 3: Full Flow - Register → Attend → Certificate

#### Step 3.1: Login User
**URL:** `http://localhost:8000/login`

**Credentials:**
```
Email: user@diantara.com
Password: password
```

**Expected:** Login berhasil, redirect ke home

**Status:** [ ] Pass / [ ] Fail

---

#### Step 3.2: Browse Events
**URL:** `http://localhost:8000/catalog`

**Steps:**
1. Lihat list event
2. Pilih event yang:
   - Tanggal event sudah lewat ATAU
   - Event hari ini dan waktu sudah lewat

**Expected:** Ada event yang bisa dipilih

**Status:** [ ] Pass / [ ] Fail

---

#### Step 3.3: Register Event
**Steps:**
1. Klik event yang dipilih
2. Klik button "Daftar Event"
3. Confirm registration

**Expected:**
- ✅ Registration berhasil
- ✅ Dapat email dengan token 10 digit
- ✅ Redirect ke success page

**Status:** [ ] Pass / [ ] Fail

**Token Received:** ___________

---

#### Step 3.4: Attendance (Absen)
**URL:** `http://localhost:8000/events/{event_id}/attendance`

**Steps:**
1. Buka URL attendance untuk event yang tadi didaftar
2. Input token 10 digit dari email
3. Klik "Submit Absensi"

**Expected:**
- ✅ Absensi berhasil
- ✅ Auto redirect ke download certificate
- ✅ PDF langsung ke-download
- ✅ Filename format: `certificate_nama_user_YYYYMMDD.pdf`

**Status:** [ ] Pass / [ ] Fail

---

#### Step 3.5: Verify PDF Content
**Steps:**
1. Buka file PDF yang ter-download
2. Cek konten

**Expected PDF Content:**
- ✅ Format: A4 Landscape
- ✅ Ada border & decorative elements (circles di 4 corner)
- ✅ Title: "SERTIFIKAT" (besar, bold)
- ✅ Subtitle: "Certificate of Participation"
- ✅ Text: "Diberikan kepada:"
- ✅ Nama peserta (merah, underline)
- ✅ Text: "Atas partisipasinya dalam kegiatan:"
- ✅ Nama event (biru, italic)
- ✅ Tanggal event (format: Senin, 5 November 2024)
- ✅ Signature line dengan text "Penyelenggara"
- ✅ Certificate ID di kiri bawah: "ID: CERT-{id}-2025"

**Status:** [ ] Pass / [ ] Fail

---

### Test 4: View Certificate (Browser Preview)
**URL:** `http://localhost:8000/certificate/{participant_id}/view`

**Steps:**
1. Buka URL dengan participant ID yang sudah attend
2. Lihat PDF di browser

**Expected:**
- ✅ PDF muncul di browser (tidak langsung download)
- ✅ Bisa scroll/zoom PDF
- ✅ Konten sama seperti downloaded PDF

**Status:** [ ] Pass / [ ] Fail

---

### Test 5: Search Certificate (With Data)
**URL:** `http://localhost:8000/certificate/search`

**Steps:**
1. Input nama user yang tadi absen
2. Klik "Cari Sertifikat"

**Expected:**
- ✅ Muncul card certificate
- ✅ Ada info: nama, email, tanggal event
- ✅ Ada badge hijau "Sudah Hadir"
- ✅ Ada 2 button: "Lihat" dan "Unduh"

**Status:** [ ] Pass / [ ] Fail

---

#### Step 5.1: Test View Button
**Steps:**
1. Klik button "Lihat"

**Expected:**
- ✅ PDF muncul di tab baru
- ✅ Bisa preview sebelum download

**Status:** [ ] Pass / [ ] Fail

---

#### Step 5.2: Test Download Button
**Steps:**
1. Klik button "Unduh"

**Expected:**
- ✅ PDF langsung ke-download
- ✅ Filename sesuai format

**Status:** [ ] Pass / [ ] Fail

---

### Test 6: My Events Page
**URL:** `http://localhost:8000/my-events`

**Steps:**
1. Login sebagai user yang sudah attend
2. Buka My Events

**Expected:**
- ✅ Event yang sudah dihadiri ada badge "Sudah Hadir"
- ✅ Ada button hijau "Download Sertifikat"
- ✅ Klik button → PDF download

**Status:** [ ] Pass / [ ] Fail

---

### Test 7: Admin View Certificate
**URL:** `http://localhost:8000/admin/participants`

**Steps:**
1. Login sebagai admin:
   ```
   Email: admin@diantara.com
   Password: password
   ```
2. Buka Admin → Participants
3. Lihat peserta dengan status "Attended"

**Expected:**
- ✅ Ada icon certificate (purple) di kolom Actions
- ✅ Klik icon → PDF download di tab baru

**Status:** [ ] Pass / [ ] Fail

---

### Test 8: Error Handling - Certificate Not Attended
**URL:** `http://localhost:8000/certificate/{participant_id}/download`

**Steps:**
1. Ambil participant ID yang status = "registered" (belum attend)
2. Buka URL certificate download

**Expected:**
- ✅ Tidak error 500
- ✅ Redirect ke home dengan error message
- ✅ Message: "Sertifikat hanya tersedia untuk peserta yang sudah hadir."

**Status:** [ ] Pass / [ ] Fail

---

### Test 9: Storage Check
**Steps:**
1. Buka folder: `storage/app/public/certificates/`
2. Cek file PDF yang ter-generate

**Expected:**
- ✅ Folder `certificates` ada
- ✅ Ada file PDF dengan format: `certificate_{id}_{timestamp}.pdf`
- ✅ File bisa dibuka dan valid

**Status:** [ ] Pass / [ ] Fail

---

### Test 10: Log Check
**Steps:**
1. Buka file: `storage/logs/laravel.log`
2. Cari log certificate generation

**Expected:**
- ✅ Ada log: "Certificate generated successfully for participant: {id}"
- ✅ Tidak ada error log terkait certificate

**Status:** [ ] Pass / [ ] Fail

---

## 🐛 Common Issues & Solutions

### Issue 1: Class 'Barryvdh\DomPDF\Facade\Pdf' not found
**Solution:**
```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
php artisan optimize:clear
```

### Issue 2: PDF blank/kosong
**Solution:**
- Cek `storage/logs/laravel.log` untuk error detail
- Pastikan view `certificates/certificate.blade.php` tidak ada syntax error
- Test dengan tinker:
```bash
php artisan tinker
$p = App\Models\Participant::where('status', 'attended')->first();
$pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.certificate', ['participant' => $p]);
$pdf->save(storage_path('test.pdf'));
```

### Issue 3: Storage permission denied
**Solution:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan storage:link
```

### Issue 4: Font tidak muncul di PDF
**Solution:**
- Font di PDF menggunakan 'Times New Roman' yang built-in
- Jika masih error, cek config dompdf

### Issue 5: Redirect loop setelah attendance
**Solution:**
- Pastikan route `certificate.generate` sudah benar
- Cek di `routes/web.php` line 110-111

---

## 📊 Test Summary

**Total Tests:** 10
**Passed:** ___
**Failed:** ___
**Success Rate:** ___%

---

## ✅ Final Checklist

- [ ] Package dompdf terinstall
- [ ] Route certificate sudah benar
- [ ] Controller method lengkap (generate, view, generateCertificatePath)
- [ ] Template certificate.blade.php valid
- [ ] Storage folder writable
- [ ] PDF bisa di-download
- [ ] PDF bisa di-view di browser
- [ ] Certificate search berfungsi
- [ ] My Events page ada button download
- [ ] Admin panel ada icon certificate
- [ ] Error handling berfungsi
- [ ] Log generation tercatat

---

## 🎯 Next Steps

Jika semua test PASS:
✅ **PDF Certificate Feature = COMPLETE!**

Lanjut ke:
1. Mobile Responsive / PWA
2. Validasi Figma Design

---

**Tested by:** ___________
**Date:** ___________
**Notes:** ___________
