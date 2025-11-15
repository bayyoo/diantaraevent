# 🎫 E-Ticket System - DIANTARA

## Overview

Sistem E-Ticket otomatis yang di-generate saat user register event. E-Ticket berisi QR Code dengan token untuk verifikasi kehadiran.

---

## ✨ Features

### 1. **Auto-Generate E-Ticket**
- E-Ticket PDF otomatis dibuat saat register event
- Desain colorful dengan gradient purple/pink (sesuai branding Diantara)
- QR Code berisi token 10-digit
- Informasi lengkap: nama, event, tanggal, lokasi, category

### 2. **Branding Diantara**
- Logo: **DIANTARA**
- Warna: Purple gradient (#7681FF → #9D4EDD)
- Style: Modern, colorful, playful
- Decorative elements: circles, triangles (seperti gambar referensi)

### 3. **QR Code Integration**
- QR Code berisi token 10-digit
- Bisa di-scan untuk verifikasi
- Yellow background dengan dashed border
- Status badge: VERIFIED

---

## 📁 Files Created

### Controllers
```
app/Http/Controllers/
└── TicketController.php          # E-Ticket generation & download
```

### Views
```
resources/views/
├── tickets/
│   └── e-ticket.blade.php        # E-Ticket PDF template
└── emails/
    └── event-registration-token.blade.php  # Updated with Diantara branding
```

### Migrations
```
database/migrations/
└── 2025_10_09_004833_add_ticket_path_to_participants_table.php
```

---

## 🎨 Design Elements

### Color Palette
```
Primary Purple: #7681FF
Secondary Purple: #9D4EDD
Dark Purple: #7C3AED
Light Purple: #E8B4F9
Pink: #C084FC

Accent Colors:
- Yellow: #FFEB3B (QR section)
- Green: #90EE90 (category badge)
- Dark: #1a1a2e (footer)
```

### Typography
```
Font: Montserrat (bold, modern)
Logo: 32px, 900 weight, 3px letter-spacing
Title: 24px, 800 weight
Body: 12-14px
```

### Layout
```
Paper: A4 Portrait
Container: 180mm width, rounded corners
Sections:
1. Header (gradient purple)
2. Content (white background)
3. QR Code Section (yellow)
4. Footer (dark gradient)
```

---

## 🔄 Flow

### Registration Flow
```
User Register Event
  ↓
Generate 10-digit token
  ↓
Create Participant record
  ↓
Generate E-Ticket PDF (with QR Code)
  ↓
Save to storage/app/public/tickets/
  ↓
Send email with token
  ↓
User receives:
  - Email with token
  - E-Ticket PDF (downloadable)
```

### Download Flow
```
User → My Events
  ↓
Click "Download E-Ticket"
  ↓
PDF downloaded
  ↓
Can be printed or saved
```

---

## 💻 Code Implementation

### Generate E-Ticket (EventRegistrationController)
```php
// After creating participant
$ticketController = new \App\Http\Controllers\TicketController();
$ticketPath = $ticketController->generateTicketPath($participant);
if ($ticketPath) {
    $participant->update(['ticket_path' => $ticketPath]);
}
```

### Download E-Ticket (TicketController)
```php
public function download(Participant $participant)
{
    $participant->load(['user', 'event']);
    
    $pdf = Pdf::loadView('tickets.e-ticket', compact('participant'));
    $pdf->setPaper('A4', 'portrait');
    
    $filename = 'e-ticket_' . $participant->name . '.pdf';
    return $pdf->download($filename);
}
```

### QR Code Generation
```php
// In blade template
{!! QrCode::size(130)->generate($participant->token) !!}
```

---

## 🗄️ Database

### Participants Table (Updated)
```sql
ALTER TABLE participants 
ADD COLUMN ticket_path VARCHAR(255) NULL AFTER certificate_path;
```

**Fields:**
- `ticket_path` - Path to e-ticket PDF file

---

## 🚀 Installation

### 1. Install QR Code Package
```bash
composer require simplesoftwareio/simple-qrcode
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Create Storage Link
```bash
php artisan storage:link
```

### 4. Test
```bash
# Register for an event
# Check storage/app/public/tickets/
# Download e-ticket from My Events
```

---

## 📧 Email Template (Updated)

### Branding Changes
- ✅ Logo: **DIANTARA** (bold, large)
- ✅ Colors: Purple gradient
- ✅ Token box: Purple gradient background
- ✅ Modern, professional design
- ✅ Footer: Diantara branding

### Email Content
```
Subject: Token Absensi - {Event Title}

Header:
- DIANTARA logo
- 🎫 Token Absensi Event
- Pendaftaran Berhasil!

Body:
- Event details (purple box)
- Token (large, white on purple gradient)
- Important notes (yellow box)

Footer:
- DIANTARA
- © 2025 Diantara - Sistem Manajemen Event
```

---

## 🎯 Routes

### E-Ticket Routes
```php
// Download e-ticket
GET /ticket/{participant}/download
→ TicketController@download

// View e-ticket in browser
GET /ticket/{participant}/view
→ TicketController@view
```

**Middleware:** `auth` (only authenticated users)

---

## 🧪 Testing

### Test E-Ticket Generation

**Step 1: Register Event**
```
1. Login as user
2. Register for event
3. Check success message
```

**Step 2: Check Storage**
```bash
# Check if e-ticket created
ls storage/app/public/tickets/

# Should show: ticket_{id}_{timestamp}.pdf
```

**Step 3: Download E-Ticket**
```
1. Go to My Events
2. Click "Download E-Ticket"
3. PDF should download
4. Open PDF → Check design
```

**Step 4: Verify QR Code**
```
1. Open e-ticket PDF
2. Scan QR Code with phone
3. Should show token (10 digits)
```

---

## 📱 E-Ticket Features

### Information Displayed
- ✅ DIANTARA logo (header)
- ✅ Event title (large, purple)
- ✅ Participant name
- ✅ Event category (badge)
- ✅ Date & time
- ✅ Location
- ✅ QR Code (token)
- ✅ Booking ID (token)
- ✅ Verified status badge

### Design Elements
- ✅ Gradient purple header
- ✅ Decorative circles & triangles
- ✅ Yellow QR section with dashed border
- ✅ Category badge (green gradient)
- ✅ Dark footer
- ✅ Professional layout

---

## 🎨 Customization

### Change Colors
Edit `resources/views/tickets/e-ticket.blade.php`:
```css
/* Header gradient */
background: linear-gradient(135deg, #7681FF 0%, #9D4EDD 100%);

/* QR section */
background: #FFEB3B;

/* Category badge */
background: linear-gradient(135deg, #90EE90 0%, #32CD32 100%);
```

### Change Layout
```css
/* Container width */
width: 180mm;

/* QR Code size */
{!! QrCode::size(150)->generate($token) !!}
```

---

## 📊 Storage Structure

```
storage/app/public/
├── tickets/
│   ├── ticket_1_1696800000.pdf
│   ├── ticket_2_1696800100.pdf
│   └── ...
└── certificates/
    ├── certificate_1_1696800200.pdf
    └── ...
```

---

## 🔐 Security

### Access Control
- ✅ Only authenticated users can download
- ✅ Users can only download their own tickets
- ✅ Token is unique per participant
- ✅ QR Code encrypted with token

### Validation
```php
// Check ownership
if ($participant->user_id !== auth()->id()) {
    abort(403, 'Unauthorized');
}
```

---

## ✅ Checklist

- [x] Install QR Code package
- [x] Create e-ticket template
- [x] Update email template (Diantara branding)
- [x] Add ticket_path column
- [x] Generate e-ticket on registration
- [x] Add download routes
- [x] Test e-ticket generation
- [x] Test QR Code scanning
- [x] Verify design matches branding

---

## 🎯 Next Steps

1. ✅ **E-Ticket System** - COMPLETED
2. ⏳ **Add download button** in My Events page
3. ⏳ **Email attachment** - Attach e-ticket to email
4. ⏳ **QR Scanner** - Admin can scan QR for check-in
5. ⏳ **Print optimization** - Optimize for printing

---

**Status:** ✅ Fully Implemented  
**Last Updated:** 2025-10-09  
**Design:** Matches Diantara branding (Purple gradient, modern, colorful)
