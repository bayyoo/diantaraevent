<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket - {{ $participant->name }}</title>
    <style>
        @page {
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', 'Arial', sans-serif;
            width: 210mm;
            height: 297mm;
            position: relative;
            background: #F3F4F6;
        }
        
        .ticket-container {
            width: 180mm;
            margin: 20mm auto;
            background: #FFFFFF;
            border-radius: 20px;
            padding: 8mm;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.15);
            position: relative;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }
        
        /* Decorative elements */
        .decoration {
            position: absolute;
            opacity: 0.15;
        }
        
        .circle-1 {
            width: 150px;
            height: 150px;
            background: #C4B5FD;
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }
        
        .circle-2 {
            width: 100px;
            height: 100px;
            background: #FDE68A;
            border-radius: 50%;
            bottom: 30px;
            left: 20px;
        }
        
        .triangle {
            width: 0;
            height: 0;
            border-left: 60px solid transparent;
            border-right: 60px solid transparent;
            border-bottom: 100px solid #F9A8D4;
            top: 100px;
            right: 80px;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #4C1D95 0%, #7C3AED 50%, #A855F7 100%);
            padding: 18px 24px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 10;
        }
        
        .logo {
            font-size: 30px;
            font-weight: 900;
            color: #FFFFFF;
            letter-spacing: 3px;
            text-shadow: 0 4px 12px rgba(15,23,42,0.4);
            margin-bottom: 6px;
        }
        
        .header-subtitle {
            color: #FFFFFF;
            font-size: 11px;
            opacity: 0.9;
            letter-spacing: 1px;
        }
        
        /* Content */
        .content {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 28px 26px 24px 26px;
            position: relative;
            z-index: 10;
        }
        
        .event-title {
            font-size: 24px;
            font-weight: 800;
            color: #7C3AED;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
            margin-bottom: 12px;
        }
        
        .info-label {
            display: table-cell;
            font-size: 12px;
            color: #666;
            font-weight: 600;
            padding: 8px 0;
            width: 35%;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            font-size: 14px;
            color: #1a1a2e;
            font-weight: 700;
            padding: 8px 0;
            vertical-align: top;
        }
        
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, #22C55E 0%, #16A34A 100%);
            color: #FFFFFF;
            padding: 6px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* QR Code Section */
        .qr-section {
            background: #F9FAFB;
            border-radius: 16px;
            padding: 20px 22px;
            text-align: center;
            margin-top: 22px;
            border: 2px dashed #E5E7EB;
        }
        
        .qr-label {
            font-size: 11px;
            color: #4B5563;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .qr-code {
            width: 180px;
            height: 70px;
            margin: 10px auto 0 auto;
            background: #111827;
            padding: 10px 14px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .booking-id {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            margin-top: 12px;
            letter-spacing: 2px;
        }
        
        .status-badge {
            display: inline-block;
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            padding: 5px 16px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 18px;
            padding: 14px;
            background: rgba(249,250,251,0.95);
            border-radius: 12px;
            position: relative;
            z-index: 10;
            border: 1px dashed #E5E7EB;
        }
        
        .footer-text {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
        }
        
        .footer-bold {
            font-weight: 700;
            color: #7C3AED;
        }
        
        /* Icons */
        .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Decorative Elements -->
        <div class="decoration circle-1"></div>
        <div class="decoration circle-2"></div>
        <div class="decoration triangle"></div>
        
        <!-- Header -->
        <div class="header">
            <div class="logo">DIANTARA</div>
            <div class="header-subtitle">SISTEM MANAJEMEN EVENT</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="event-title">{{ $participant->event->title }}</div>
            
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">📝 Name</div>
                    <div class="info-value">{{ $participant->name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">🏷️ Category</div>
                    <div class="info-value">
                        <span class="category-badge">{{ strtoupper($participant->event->category ?? 'GENERAL') }}</span>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📅 Date</div>
                    <div class="info-value">{{ $participant->event->event_date->format('d M Y, H:i') }} WIB</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📍 Location</div>
                    <div class="info-value">{{ $participant->event->location }}</div>
                </div>
            </div>
            
            <!-- Token/Booking Section (tanpa QR) -->
            <div class="qr-section">
                <div class="qr-label">TOKEN / BOOKING ID UNTUK ABSENSI</div>

                <div class="qr-code" style="display:flex;align-items:center;justify-content:center;height:100%;font-size:14px;color:#111827;padding:8px;text-align:center;font-weight:700;letter-spacing:2px;">
                    {{ strtoupper($participant->token) }}
                </div>

                <div class="qr-label">Tunjukkan token ini saat check-in event</div>
                <div class="status-badge">✓ VERIFIED</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <span class="footer-bold">PENTING:</span> Tunjukkan e-ticket ini saat check-in.<br>
                Simpan e-ticket ini dengan baik. Jangan bagikan kepada orang lain.<br>
                <span class="footer-bold">© {{ date('Y') }} DIANTARA</span> - Sistem Manajemen Event
            </div>
        </div>
    </div>
</body>
</html>
