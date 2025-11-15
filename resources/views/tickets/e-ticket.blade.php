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
        }
        
        .ticket-container {
            width: 180mm;
            margin: 20mm auto;
            background: linear-gradient(135deg, #E8B4F9 0%, #C084FC 50%, #7C3AED 100%);
            border-radius: 20px;
            padding: 8mm;
            box-shadow: 0 10px 40px rgba(124, 58, 237, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        /* Decorative elements */
        .decoration {
            position: absolute;
            opacity: 0.15;
        }
        
        .circle-1 {
            width: 150px;
            height: 150px;
            background: #FFD700;
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }
        
        .circle-2 {
            width: 100px;
            height: 100px;
            background: #90EE90;
            border-radius: 50%;
            bottom: 30px;
            left: 20px;
        }
        
        .triangle {
            width: 0;
            height: 0;
            border-left: 60px solid transparent;
            border-right: 60px solid transparent;
            border-bottom: 100px solid #FFB6C1;
            top: 100px;
            right: 80px;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #7C3AED 0%, #9D4EDD 100%);
            padding: 15px 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 10;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 900;
            color: #FFFFFF;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            margin-bottom: 5px;
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
            border-radius: 15px;
            padding: 25px;
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
            background: linear-gradient(135deg, #90EE90 0%, #32CD32 100%);
            color: #FFFFFF;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* QR Code Section */
        .qr-section {
            background: #FFEB3B;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            border: 3px dashed #333;
        }
        
        .qr-label {
            font-size: 11px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .qr-code {
            width: 150px;
            height: 150px;
            margin: 10px auto;
            background: #FFFFFF;
            padding: 10px;
            border-radius: 10px;
        }
        
        .booking-id {
            font-size: 16px;
            font-weight: 800;
            color: #333;
            margin-top: 10px;
            letter-spacing: 1px;
        }
        
        .status-badge {
            display: inline-block;
            background: #4CAF50;
            color: #FFFFFF;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 700;
            margin-top: 8px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.9);
            border-radius: 10px;
            position: relative;
            z-index: 10;
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
            <div class="event-title">{{ $event->title }}</div>
            
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">📝 Name</div>
                    <div class="info-value">{{ $participant->name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">🏷️ Category</div>
                    <div class="info-value">
                        <span class="category-badge">{{ strtoupper($event->category ?? 'GENERAL') }}</span>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📅 Date</div>
                    <div class="info-value">{{ $event->event_date->format('d M Y, H:i') }} WIB</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">📍 Location</div>
                    <div class="info-value">{{ $event->location }}</div>
                </div>
            </div>
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-label">TERIMAKASIH & SELAMAT MENIKMATI</div>
                
                <div class="qr-code">
                    {!! QrCode::size(130)->generate($participant->token) !!}
                </div>
                
                <div class="qr-label">Booking ID</div>
                <div class="booking-id">{{ strtoupper($participant->token) }}</div>
                
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
