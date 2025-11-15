# 📋 Attendance System Documentation

## Overview

Sistem absensi untuk mencatat kehadiran peserta event menggunakan token 10-digit yang dikirim via email.

---

## 🎯 UKK Requirements

✅ **10-digit random token** sent via email upon registration  
✅ **Token required** for attendance verification  
✅ **Attendance only available** on event day **after event time**  
✅ **Certificate generated** after attendance  

---

## 🔄 Complete Flow

### 1. Event Registration
```
User → Register Event
  ↓
System generates 10-digit token
  ↓
Token sent via email
  ↓
Participant status: 'registered'
```

**File:** `EventRegistrationController@store`
- Generate unique 10-digit token
- Save to `participants.token`
- Send email with token

### 2. Attendance Day
```
Event Day + Event Time arrives
  ↓
User opens attendance page
  ↓
Input 10-digit token
  ↓
System validates token + time
  ↓
Attendance recorded
  ↓
Certificate auto-generated
  ↓
Status: 'attended'
```

**File:** `AttendanceController@store`
- Validate event time (must be after event starts)
- Validate token
- Create attendance record
- Generate certificate
- Update participant status

---

## 📁 Files Structure

### Controllers
```
app/Http/Controllers/
├── AttendanceController.php          # Public & Admin attendance
├── EventRegistrationController.php   # Event registration with token
└── CertificateController.php         # Certificate generation
```

### Views
```
resources/views/
├── attendance/
│   └── form.blade.php               # Public token input form
├── admin/
│   └── attendance/
│       └── index.blade.php          # Admin attendance dashboard
└── emails/
    └── event-registration-token.blade.php  # Token email
```

### Mail
```
app/Mail/
└── EventRegistrationToken.php       # Email class
```

---

## 🗄️ Database

### Participants Table
```sql
CREATE TABLE participants (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    event_id BIGINT,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(20),
    token VARCHAR(10) UNIQUE,           -- 10-digit attendance token
    status ENUM('registered', 'attended', 'cancelled'),
    certificate_path VARCHAR(255) NULL, -- Path to generated certificate
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Attendances Table
```sql
CREATE TABLE attendances (
    id BIGINT PRIMARY KEY,
    participant_id BIGINT,
    event_id BIGINT,
    checked_in_at TIMESTAMP,
    checked_in_by VARCHAR(255),
    note TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 🚀 Usage

### For Participants (Public)

#### Step 1: Register Event
```
1. Browse event catalog
2. Click "Daftar Event"
3. Confirm registration
4. Receive token via email
```

#### Step 2: Attendance
```
1. Wait until event day + event time
2. Go to event detail page
3. Click "Daftar Hadir"
4. Input 10-digit token
5. Submit
6. Download certificate
```

**URL:** `/events/{event}/attendance`

### For Admin

#### Admin Attendance Dashboard
```
1. Login as admin
2. Go to Admin → Attendance
3. Input participant token
4. Click "Konfirmasi Absensi"
5. Attendance recorded
```

**URL:** `/admin/attendance`

**Features:**
- Manual token input
- Recent attendance list
- Statistics (today, week, month)
- Auto-submit when 10 digits entered

---

## 🔐 Validations

### Time Validation
```php
// Attendance only available after event starts
$eventDateTime = Carbon::parse($event->event_date . ' ' . $event->event_time);

if (now()->lt($eventDateTime)) {
    return error('Absensi belum dibuka');
}
```

### Token Validation
```php
// Token must be 10 digits
'token' => 'required|string|size:10'

// Token must exist and match user + event
$participant = Participant::where('user_id', auth()->id())
    ->where('event_id', $event->id)
    ->where('token', $request->token)
    ->first();
```

### Duplicate Prevention
```php
// Check if already attended
if ($participant->status === 'attended') {
    return error('Sudah absen');
}
```

---

## 📧 Email Template

**Subject:** Token Absensi - {Event Title}

**Content:**
- Event details (title, date, time, location)
- 10-digit token (large, centered, monospace)
- Instructions for attendance
- Important notes

**File:** `resources/views/emails/event-registration-token.blade.php`

---

## 🎓 Certificate Generation

### Auto-Generate After Attendance
```php
// In AttendanceController@store
$certificateController = new CertificateController();
$certificatePath = $certificateController->generateCertificatePath($participant);
$participant->update(['certificate_path' => $certificatePath]);
```

### Certificate Storage
```
storage/app/public/certificates/
└── certificate_{participant_id}_{timestamp}.pdf
```

### Download Certificate
**URL:** `/certificate/{participant}`

**Requirements:**
- Participant must have status 'attended'
- Certificate path must exist

---

## 🧪 Testing

### Test Scenario 1: Normal Flow
```
1. ✅ Register event → Token sent
2. ✅ Wait until event time
3. ✅ Input correct token → Success
4. ✅ Certificate generated
5. ✅ Status = 'attended'
```

### Test Scenario 2: Early Attendance
```
1. ✅ Register event → Token sent
2. ❌ Try to attend before event time
3. ❌ Error: "Absensi belum dibuka"
```

### Test Scenario 3: Wrong Token
```
1. ✅ Register event → Token sent
2. ✅ Event time arrives
3. ❌ Input wrong token
4. ❌ Error: "Token tidak valid"
```

### Test Scenario 4: Duplicate Attendance
```
1. ✅ Register event → Token sent
2. ✅ Attend with token → Success
3. ❌ Try to attend again
4. ❌ Error: "Sudah absen"
```

### Test Scenario 5: Admin Check-in
```
1. ✅ Admin login
2. ✅ Go to /admin/attendance
3. ✅ Input participant token
4. ✅ Success → Attendance recorded
```

---

## 🐛 Troubleshooting

### Issue: Token not received

**Solution:**
1. Check email configuration in `.env`
2. Check spam folder
3. Check `mail.log` for errors
4. Manually show token in success message (fallback)

### Issue: "Absensi belum dibuka"

**Solution:**
1. Check event date and time
2. Ensure event time has passed
3. Check server timezone
4. Verify event_date and event_time columns

### Issue: Certificate not generated

**Solution:**
1. Check storage permissions: `chmod -R 775 storage`
2. Create symbolic link: `php artisan storage:link`
3. Check dompdf installation
4. Check certificate template exists

### Issue: Token validation fails

**Solution:**
1. Verify token is exactly 10 digits
2. Check participant exists in database
3. Verify event_id matches
4. Check token column is unique

---

## 📊 Statistics

### Admin Dashboard Queries

**Total Attendances Today:**
```php
Attendance::whereDate('checked_in_at', today())->count()
```

**Attendances This Week:**
```php
Attendance::where('checked_in_at', '>=', now()->startOfWeek())->count()
```

**Attendances This Month:**
```php
Attendance::where('checked_in_at', '>=', now()->startOfMonth())->count()
```

**Recent Attendances:**
```php
Attendance::with(['participant.user', 'event'])
    ->orderBy('checked_in_at', 'desc')
    ->limit(50)
    ->get()
```

---

## 🔗 Routes

### Public Routes
```php
// Show attendance form
GET /events/{event}/attendance
→ AttendanceController@show

// Submit attendance
POST /events/{event}/attendance
→ AttendanceController@store
```

### Admin Routes
```php
// Admin attendance dashboard
GET /admin/attendance
→ AttendanceController@index

// Admin manual check-in
POST /admin/attendance/checkin
→ AttendanceController@checkIn
```

### Certificate Route
```php
// Download certificate
GET /certificate/{participant}
→ CertificateController@generate
```

---

## ✅ Checklist Implementation

- [x] Token generation (10 digits)
- [x] Email token to participant
- [x] Public attendance form
- [x] Time validation (after event starts)
- [x] Token validation
- [x] Duplicate prevention
- [x] Attendance recording
- [x] Certificate auto-generation
- [x] Admin attendance dashboard
- [x] Admin manual check-in
- [x] Recent attendance list
- [x] Statistics display

---

## 🎯 Next Steps

1. ✅ **Attendance System** - COMPLETED
2. ⏳ **Certificate Template** - Review & improve design
3. ⏳ **Dashboard Charts** - Attendance statistics
4. ⏳ **Export Excel** - Export attendance data
5. ⏳ **Public Catalog** - Event catalog with search/filter

---

**Status:** ✅ Fully Implemented  
**Last Updated:** 2025-10-09  
**Tested:** Ready for testing
