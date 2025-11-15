# Solusi Error 419 Page Expired

## 📋 Deskripsi Masalah

Error **419 Page Expired** terjadi ketika:
- CSRF token expired atau tidak valid
- User membuka form terlalu lama (lebih dari session lifetime)
- Session timeout sebelum form disubmit

Sesuai **UKK Requirement**, sistem memiliki **session timeout 5 menit** yang menyebabkan CSRF token expired jika user idle terlalu lama.

---

## ✅ Solusi yang Diimplementasikan

### 1. **Auto-Refresh CSRF Token**
File: `public/js/csrf-refresh.js`

**Fitur:**
- Auto-refresh CSRF token setiap **4 menit** (sebelum timeout 5 menit)
- Update semua CSRF input fields dan meta tag
- Refresh token sebelum form submission
- Mencegah error 419 secara proaktif

**Cara Kerja:**
```javascript
// Refresh token setiap 4 menit
setInterval(refreshCsrfToken, 240000);

// Refresh token sebelum submit form
document.addEventListener('submit', async function(e) {
    await refreshCsrfToken();
    form.submit();
});
```

### 2. **Session Timeout Warning**
**Fitur:**
- Warning muncul **1 menit sebelum** session expired (menit ke-4)
- Tombol "Perpanjang Sesi" untuk extend session
- Auto logout setelah 5 menit jika tidak ada aktivitas

**User Experience:**
1. User idle selama 4 menit → Warning muncul
2. User klik "Perpanjang Sesi" → Token refresh + timer reset
3. Jika diabaikan → Auto logout ke halaman login

### 3. **Custom Error Page 419**
File: `resources/views/errors/419.blade.php`

**Fitur:**
- Halaman error yang user-friendly
- Penjelasan kenapa error terjadi
- Tips untuk menghindari error
- Auto reload setelah 5 detik
- Tombol manual reload dan kembali

### 4. **Session Lifetime Configuration**
File: `config/session.php`

**Perubahan:**
```php
// SEBELUM: 480 menit (8 jam)
'lifetime' => (int) env('SESSION_LIFETIME', 480),

// SESUDAH: 5 menit (sesuai UKK requirement)
'lifetime' => (int) env('SESSION_LIFETIME', 5),
```

### 5. **CSRF Token Refresh Endpoint**
File: `app/Http/Controllers/CsrfTokenController.php`

**Endpoint:** `GET /csrf-token`

**Response:**
```json
{
    "csrf_token": "new_token_here",
    "timestamp": "2025-10-09T07:12:13+07:00"
}
```

---

## 📁 File yang Dimodifikasi

### ✨ File Baru:
1. `public/js/csrf-refresh.js` - Script auto-refresh CSRF token
2. `app/Http/Controllers/CsrfTokenController.php` - Controller untuk refresh token
3. `resources/views/errors/419.blade.php` - Custom error page
4. `docs/419-ERROR-FIX.md` - Dokumentasi ini

### 🔧 File yang Diupdate:
1. `config/session.php` - Session lifetime: 480 → 5 menit
2. `routes/web.php` - Tambah route `/csrf-token`
3. `resources/views/layouts/app.blade.php` - Include csrf-refresh.js
4. `resources/views/admin/layout.blade.php` - Include csrf-refresh.js + CSRF meta tag
5. `resources/views/auth/login.blade.php` - Include csrf-refresh.js

---

## 🚀 Cara Menggunakan

### Setup Otomatis
Script sudah otomatis berjalan di semua halaman yang include layout. Tidak perlu konfigurasi tambahan.

### Manual Testing

**1. Test Session Timeout:**
```bash
# Buka halaman login
# Tunggu 5 menit tanpa aktivitas
# Coba submit form → Akan muncul error 419 page
```

**2. Test Auto-Refresh:**
```bash
# Buka browser console (F12)
# Lihat log: "CSRF token refreshed successfully" setiap 4 menit
```

**3. Test Warning:**
```bash
# Login ke sistem
# Idle selama 4 menit
# Warning akan muncul di pojok kanan atas
```

---

## 🎯 Sesuai UKK Requirements

✅ **Session timeout 5 menit** - Implemented  
✅ **Auto logout setelah idle** - Implemented  
✅ **User-friendly error handling** - Implemented  
✅ **Prevent form submission errors** - Implemented  

---

## 🔍 Troubleshooting

### Error masih muncul setelah implementasi?

**Solusi 1: Clear cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Solusi 2: Restart server**
```bash
# Stop XAMPP Apache
# Start XAMPP Apache
```

**Solusi 3: Clear browser cache**
- Tekan `Ctrl + Shift + Delete`
- Clear cookies dan cache
- Restart browser

### Script tidak berjalan?

**Check console browser (F12):**
- Lihat apakah ada error JavaScript
- Pastikan file `csrf-refresh.js` ter-load
- Check network tab untuk request `/csrf-token`

### Warning tidak muncul?

**Check:**
1. Pastikan user sudah login (auth)
2. Pastikan script ter-include di layout
3. Check browser console untuk error

---

## 📊 Monitoring

### Log yang Perlu Diperhatikan

**Browser Console:**
```
✅ CSRF token refreshed successfully (setiap 4 menit)
✅ Sesi berhasil diperpanjang! (saat user extend)
```

**Network Tab:**
```
GET /csrf-token → 200 OK
Response: {"csrf_token":"...","timestamp":"..."}
```

---

## 🔐 Security Notes

1. **CSRF Protection tetap aktif** - Token hanya di-refresh, tidak di-disable
2. **Session security terjaga** - Timeout tetap 5 menit sesuai requirement
3. **Token validation** - Setiap request tetap divalidasi oleh Laravel
4. **XSS Protection** - Script menggunakan DOM API yang aman

---

## 📝 Additional Notes

### Untuk Development:
Jika ingin session lebih lama saat development, update `.env`:
```env
SESSION_LIFETIME=480  # 8 jam untuk development
```

### Untuk Production:
Pastikan session lifetime tetap 5 menit:
```env
SESSION_LIFETIME=5  # 5 menit (UKK requirement)
```

---

## 👨‍💻 Developer Notes

**Tested on:**
- Laravel 11.x
- PHP 8.2
- XAMPP 8.2.12
- Chrome/Edge Browser

**Last Updated:** 2025-10-09  
**Status:** ✅ Production Ready
