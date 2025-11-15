# 🧪 Testing Attendance System - Quick Guide

## 🚀 Quick Start Testing

### Prerequisites
```bash
# 1. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 2. Create storage link (for certificates)
php artisan storage:link

# 3. Ensure mail is configured in .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Diantara"
```

---

## 📝 Test Scenario 1: Complete Flow (Happy Path)

### Step 1: Create Event (Admin)
```
1. Login as admin
2. Go to Admin → Events → Create
3. Fill event details:
   - Title: "Workshop Laravel"
   - Date: TODAY or TOMORROW
   - Time: Set time in the PAST (for testing)
   - Location: "Online"
4. Save event
```

### Step 2: Register as Participant
```
1. Logout from admin
2. Register new user account (if not exists)
3. Login as regular user
4. Browse events
5. Click event detail
6. Click "Daftar Event"
7. Confirm registration
```

### Step 3: Check Email
```
1. Open email inbox
2. Find email: "Token Absensi - Workshop Laravel"
3. Copy 10-digit token
   Example: 1234567890
```

### Step 4: Attendance
```
1. Go to event detail page
2. Click "Daftar Hadir" button
3. Input 10-digit token
4. Click "Konfirmasi Absensi"
5. ✅ Success! Redirected to certificate page
```

### Step 5: Download Certificate
```
1. Certificate should auto-display
2. Click download
3. ✅ PDF certificate downloaded
```

---

## 🧪 Test Scenario 2: Time Validation

### Setup
```
Create event with:
- Date: TOMORROW
- Time: 10:00 AM
```

### Test
```
1. Register for event → ✅ Token received
2. Try to access attendance page
3. ❌ Error: "Absensi belum dibuka. Event dimulai pada: {date} {time}"
```

### Fix for Testing
```
Option 1: Change event time to past
Option 2: Change server time (not recommended)
Option 3: Temporarily comment out time validation
```

---

## 🧪 Test Scenario 3: Invalid Token

### Test
```
1. Register for event → ✅ Token received
2. Go to attendance page
3. Input WRONG token: 9999999999
4. Click submit
5. ❌ Error: "Token tidak valid"
```

---

## 🧪 Test Scenario 4: Duplicate Attendance

### Test
```
1. Register for event → ✅ Token received
2. Complete attendance → ✅ Success
3. Try to attend again with same token
4. ❌ Error: "Anda sudah melakukan absensi"
```

---

## 🧪 Test Scenario 5: Admin Check-in

### Test
```
1. Login as admin
2. Go to /admin/attendance
3. Get participant token (from email or database)
4. Input token in admin form
5. Click "Konfirmasi Absensi"
6. ✅ Success message with participant name
7. Check recent attendance list
```

---

## 🔍 Verification Checklist

### Database Checks

**Check Participant:**
```sql
SELECT * FROM participants 
WHERE token = '1234567890';

-- Should show:
-- status = 'attended'
-- certificate_path = 'certificates/certificate_X_timestamp.pdf'
```

**Check Attendance:**
```sql
SELECT * FROM attendances 
WHERE participant_id = X;

-- Should show:
-- checked_in_at = current timestamp
-- checked_in_by = user name
```

**Check Certificate File:**
```bash
# Check if file exists
ls -la storage/app/public/certificates/

# Should show PDF file
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Email not sent

**Check:**
```bash
# Check mail configuration
php artisan tinker
>>> config('mail.mailer')
>>> config('mail.from.address')
```

**Solution:**
```
1. Verify .env mail settings
2. Use Gmail App Password (not regular password)
3. Check spam folder
4. Check storage/logs/laravel.log
```

**Temporary Workaround:**
```php
// In EventRegistrationController@store
// Show token in success message
return back()->with('success', "Token: {$token}");
```

### Issue 2: "Absensi belum dibuka"

**Quick Fix for Testing:**
```php
// In AttendanceController@show
// Comment out time validation temporarily
/*
if ($now->lt($eventDateTime)) {
    return redirect()->route('events.show', $event)
        ->with('error', 'Absensi belum dibuka...');
}
*/
```

**Proper Fix:**
```
1. Set event time to past
2. Or set event date to today with past time
```

### Issue 3: Certificate not generated

**Check:**
```bash
# 1. Storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# 2. Create symbolic link
php artisan storage:link

# 3. Check dompdf
composer require barryvdh/laravel-dompdf
```

**Verify:**
```php
// Check if certificate template exists
ls resources/views/certificates/certificate.blade.php
```

### Issue 4: Token not found

**Check Database:**
```sql
-- Verify token exists
SELECT id, name, email, token, status 
FROM participants 
WHERE event_id = X;
```

**Verify:**
- Token is exactly 10 digits
- Token is unique
- Participant exists for that event

---

## 📊 Test Data Generator

### Create Test Participants

```php
// Run in tinker: php artisan tinker

use App\Models\User;
use App\Models\Event;
use App\Models\Participant;

// Get first event
$event = Event::first();

// Create 5 test participants
for ($i = 1; $i <= 5; $i++) {
    $user = User::create([
        'name' => "Test User $i",
        'email' => "test$i@example.com",
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]);
    
    $token = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    
    Participant::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'name' => $user->name,
        'email' => $user->email,
        'token' => $token,
        'status' => 'registered'
    ]);
    
    echo "Created: $user->name | Token: $token\n";
}
```

---

## 🎯 Testing Checklist

### Functional Tests
- [ ] Event registration generates token
- [ ] Token email sent successfully
- [ ] Token is exactly 10 digits
- [ ] Token is unique
- [ ] Attendance page accessible after event time
- [ ] Attendance page blocked before event time
- [ ] Valid token accepted
- [ ] Invalid token rejected
- [ ] Duplicate attendance prevented
- [ ] Certificate auto-generated
- [ ] Certificate downloadable
- [ ] Admin can manually check-in
- [ ] Recent attendance list updates

### UI/UX Tests
- [ ] Attendance form is responsive
- [ ] Token input auto-formats
- [ ] Error messages clear
- [ ] Success messages clear
- [ ] Email template looks good
- [ ] Certificate design looks good

### Security Tests
- [ ] Cannot attend without login
- [ ] Cannot attend other user's event
- [ ] Cannot reuse token
- [ ] Token validation secure
- [ ] CSRF protection active

---

## 📸 Expected Results

### Email Screenshot
```
Subject: Token Absensi - Workshop Laravel

[Header with logo]

Halo Test User,

Selamat! Anda telah berhasil mendaftar...

[Event Details Box]
📅 Workshop Laravel
Tanggal: 09 Oktober 2025
Waktu: 09:00 WIB
Lokasi: Online

[Token Box - Large]
1234567890

[Warning Box]
⚠️ Penting untuk Diperhatikan:
- Token ini akan digunakan untuk absensi...
```

### Attendance Form
```
[Header: Daftar Hadir Event]

Event: Workshop Laravel
Date: 09 Oktober 2025
Time: 09:00 WIB
Location: Online

Peserta: Test User (test@example.com)

[Token Input - Large, Centered]
[0000000000]

[Submit Button: Konfirmasi Absensi]
```

### Success Message
```
✅ Absensi berhasil! Sertifikat Anda sudah tersedia.

[Certificate Preview/Download]
```

---

## 🚀 Quick Commands

```bash
# Clear everything
php artisan optimize:clear

# Check routes
php artisan route:list | grep attendance

# Check mail config
php artisan tinker
>>> config('mail')

# Create storage link
php artisan storage:link

# Check logs
tail -f storage/logs/laravel.log

# Database check
php artisan tinker
>>> App\Models\Participant::with('user','event')->get()
```

---

## ✅ Final Verification

After all tests pass:

1. ✅ Token generation works
2. ✅ Email delivery works
3. ✅ Time validation works
4. ✅ Token validation works
5. ✅ Attendance recording works
6. ✅ Certificate generation works
7. ✅ Admin check-in works
8. ✅ No duplicate attendance
9. ✅ All error messages clear
10. ✅ All success flows work

---

**Ready for Production!** 🎉

**Next Priority:**
1. Dashboard with statistics charts
2. Export attendance to Excel/CSV
3. Public event catalog with search/filter
