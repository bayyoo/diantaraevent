# 🔧 Troubleshooting Guide - DIANTARA

## Common Issues & Solutions

---

## ❌ Error: Class "Barryvdh\DomPDF\Facade\Pdf" not found

### Penyebab:
Package DomPDF belum terinstall atau composer install belum selesai.

### Solusi:

**1. Install DomPDF:**
```bash
composer require barryvdh/laravel-dompdf
```

**2. Install QR Code:**
```bash
composer require simplesoftwareio/simple-qrcode
```

**3. Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

**4. Restart server:**
```bash
# Stop XAMPP Apache
# Start XAMPP Apache
```

### Temporary Workaround:
Sistem sudah di-update untuk skip e-ticket generation jika package belum terinstall. Registration akan tetap jalan, e-ticket akan di-generate setelah package selesai install.

---

## ❌ Error: 419 Page Expired

### Penyebab:
CSRF token expired (session timeout 5 menit).

### Solusi:
Sudah ada auto-refresh CSRF token system. Jika masih error:

```bash
# Clear cache
php artisan optimize:clear

# Check session config
php artisan tinker
>>> config('session.lifetime')  # Should be 5
```

**Lihat:** `docs/419-ERROR-FIX.md`

---

## ❌ Email tidak terkirim

### Penyebab:
Konfigurasi email belum benar.

### Solusi:

**1. Check .env:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Diantara"
```

**2. Gmail App Password:**
- Go to: https://myaccount.google.com/apppasswords
- Generate app password
- Use that password in .env

**3. Test email:**
```bash
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

---

## ❌ Certificate/E-Ticket tidak generate

### Penyebab:
Storage permissions atau package belum terinstall.

### Solusi:

**1. Create storage link:**
```bash
php artisan storage:link
```

**2. Fix permissions:**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

**3. Check packages:**
```bash
composer show | grep dompdf
composer show | grep qrcode
```

**4. Manual test:**
```bash
php artisan tinker
>>> $participant = App\Models\Participant::first();
>>> $pdf = Pdf::loadView('tickets.e-ticket', compact('participant'));
>>> $pdf->save(storage_path('app/public/test.pdf'));
```

---

## ❌ QR Code tidak muncul

### Penyebab:
Package simple-qrcode belum terinstall.

### Solusi:

**1. Install package:**
```bash
composer require simplesoftwareio/simple-qrcode
```

**2. Clear cache:**
```bash
php artisan config:clear
composer dump-autoload
```

**3. Test QR Code:**
```bash
php artisan tinker
>>> QrCode::size(100)->generate('test');
```

---

## ❌ Attendance: "Absensi belum dibuka"

### Penyebab:
Event time belum lewat (validasi UKK requirement).

### Solusi:

**Option 1: Edit event time**
- Set event time ke masa lalu
- Atau set ke waktu sekarang

**Option 2: Temporary disable validation**
Edit `AttendanceController.php`:
```php
// Comment out time validation for testing
/*
if ($now->lt($eventDateTime)) {
    return redirect()->route('events.show', $event)
        ->with('error', 'Absensi belum dibuka...');
}
*/
```

**Option 3: Change server time (not recommended)**

---

## ❌ Token tidak valid

### Penyebab:
Token tidak cocok atau participant tidak ada.

### Solusi:

**1. Check token di database:**
```bash
php artisan tinker
>>> App\Models\Participant::where('token', '1234567890')->first();
```

**2. Check email:**
- Buka email
- Copy token exact (10 digits)
- Paste di form attendance

**3. Get token from database:**
```bash
php artisan tinker
>>> $p = App\Models\Participant::latest()->first();
>>> echo "Token: " . $p->token;
```

---

## ❌ Migration error: Column already exists

### Penyebab:
Migration sudah pernah dijalankan.

### Solusi:

**Option 1: Skip migration**
```bash
# Mark as migrated without running
php artisan migrate:status
```

**Option 2: Rollback specific migration**
```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

**Option 3: Fresh migration (WARNING: Data hilang!)**
```bash
php artisan migrate:fresh
php artisan db:seed
```

---

## ❌ Composer install stuck/slow

### Solusi:

**1. Kill process:**
```bash
# Windows: Ctrl+C di terminal
# Or: Task Manager → End composer process
```

**2. Clear composer cache:**
```bash
composer clear-cache
```

**3. Install with verbose:**
```bash
composer install -vvv
```

**4. Install specific package:**
```bash
composer require barryvdh/laravel-dompdf --no-interaction
```

---

## ❌ Success page tidak muncul

### Penyebab:
Route belum terdaftar atau cache belum clear.

### Solusi:

**1. Clear route cache:**
```bash
php artisan route:clear
php artisan route:list | grep success
```

**2. Check route:**
```bash
php artisan route:list | grep events.registration.success
```

Should show:
```
GET events/{event}/success/{participant} ... events.registration.success
```

**3. Manual access:**
```
http://127.0.0.1:8000/events/1/success/1
```

---

## 🔍 General Debugging Steps

### 1. Clear All Caches
```bash
php artisan optimize:clear
# Or individually:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### 2. Check Logs
```bash
# Laravel log
tail -f storage/logs/laravel.log

# Apache error log
tail -f C:\xampp\apache\logs\error.log
```

### 3. Enable Debug Mode
```env
# .env
APP_DEBUG=true
APP_ENV=local
```

### 4. Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### 5. Check PHP Version
```bash
php -v  # Should be 8.2+
```

### 6. Check Composer
```bash
composer --version
composer diagnose
```

---

## 📞 Need More Help?

### Check Documentation:
- `docs/ATTENDANCE-SYSTEM.md` - Attendance system
- `docs/E-TICKET-SYSTEM.md` - E-Ticket system
- `docs/419-ERROR-FIX.md` - Session timeout
- `docs/TESTING-ATTENDANCE.md` - Testing guide

### Laravel Resources:
- Laravel Docs: https://laravel.com/docs
- DomPDF: https://github.com/barryvdh/laravel-dompdf
- QR Code: https://github.com/SimpleSoftwareIO/simple-qrcode

---

## ✅ Quick Health Check

Run this to check if everything is OK:

```bash
# 1. Check PHP
php -v

# 2. Check Composer
composer --version

# 3. Check Laravel
php artisan --version

# 4. Check database
php artisan migrate:status

# 5. Check routes
php artisan route:list

# 6. Check packages
composer show | grep dompdf
composer show | grep qrcode

# 7. Check storage
ls -la storage/app/public/

# 8. Check permissions
ls -la storage/
```

All should return success! ✅

---

**Last Updated:** 2025-10-09  
**Status:** Active troubleshooting guide
