<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #7681FF 0%, #5A67D8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            font-size: 60px;
            color: #10B981;
            margin-bottom: 20px;
        }
        .event-details {
            background-color: #f9fafb;
            border-left: 4px solid #7681FF;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .event-details h3 {
            margin-top: 0;
            color: #7681FF;
        }
        .detail-item {
            margin: 10px 0;
            display: flex;
            align-items: start;
        }
        .detail-item i {
            color: #7681FF;
            margin-right: 10px;
            width: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #7681FF 0%, #5A67D8 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Event Disetujui!</h1>
        </div>
        
        <div class="content">
            <div class="success-icon">✅</div>
            
            <p>Halo <strong>{{ $event->creator->name }}</strong>,</p>
            
            <p>Selamat! Event yang kamu buat telah <strong>disetujui</strong> oleh admin dan sekarang sudah dipublikasikan di platform Diantara.</p>
            
            <div class="event-details">
                <h3>{{ $event->title }}</h3>
                <div class="detail-item">
                    <span>📅</span>
                    <span><strong>Tanggal:</strong> {{ $event->event_date->format('d F Y') }}</span>
                </div>
                <div class="detail-item">
                    <span>🕐</span>
                    <span><strong>Waktu:</strong> {{ $event->event_time ? $event->event_time->format('H:i') : '-' }} WIB</span>
                </div>
                <div class="detail-item">
                    <span>📍</span>
                    <span><strong>Lokasi:</strong> {{ $event->location }}</span>
                </div>
                <div class="detail-item">
                    <span>💰</span>
                    <span><strong>Harga:</strong> 
                        @if($event->price > 0)
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </span>
                </div>
            </div>
            
            <p>Event kamu sekarang dapat dilihat oleh semua pengguna dan mereka sudah bisa mendaftar!</p>
            
            <center>
                <a href="{{ route('events.show', $event) }}" class="button">
                    Lihat Event Saya
                </a>
            </center>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Tips:</strong> Bagikan link event kamu ke teman-teman dan sosial media untuk mendapatkan lebih banyak peserta!
            </p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem Diantara.</p>
            <p>&copy; {{ date('Y') }} Diantara. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
