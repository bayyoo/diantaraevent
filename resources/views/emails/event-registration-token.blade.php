<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Absensi Event</title>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #7681FF 0%, #9D4EDD 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .token-box {
            background: linear-gradient(135deg, #7681FF 0%, #9D4EDD 100%);
            border: none;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(118, 129, 255, 0.3);
        }
        .token {
            font-size: 36px;
            font-weight: bold;
            color: #FFFFFF;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .event-details {
            background: #F8F9FF;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #7681FF;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }
        .warning {
            background: #FFF4E6;
            border-left: 4px solid #FF9800;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .logo {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">DIANTARA</div>
        <h1 style="margin: 10px 0;">🎫 Token Absensi Event</h1>
        <p style="margin: 5px 0; opacity: 0.95;">Pendaftaran Berhasil!</p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $participant->name }}</strong>,</p>
        
        <p>Selamat! Anda telah berhasil mendaftar untuk mengikuti event berikut:</p>

        <div class="event-details">
            <h3>📅 {{ $event->title }}</h3>
            <p><strong>Tanggal:</strong> {{ $event->event_date->format('d F Y') }}</p>
            @if($event->event_time)
                <p><strong>Waktu:</strong> {{ $event->event_time->format('H:i') }} WIB</p>
            @endif
            <p><strong>Lokasi:</strong> {{ $event->location }}</p>
        </div>

        <p>Berikut adalah <strong>Token Absensi</strong> Anda:</p>

        <div class="token-box">
            <p style="color: #FFFFFF; margin: 0 0 10px 0; font-size: 14px; opacity: 0.9;">Token Absensi Anda</p>
            <div class="token">{{ $participant->token }}</div>
            <p style="margin-top: 10px; color: #FFFFFF; font-size: 12px; opacity: 0.85;">Simpan token ini dengan baik!</p>
        </div>

        <div class="warning">
            <h4>⚠️ Penting untuk Diperhatikan:</h4>
            <ul>
                <li>Token ini akan digunakan untuk <strong>absensi pada hari H</strong> setelah event berlangsung</li>
                <li>Tombol daftar hadir hanya akan aktif <strong>setelah jam event dimulai</strong></li>
                <li>Anda harus mengisi daftar hadir untuk mendapatkan <strong>sertifikat</strong></li>
                <li>Jangan bagikan token ini kepada orang lain</li>
            </ul>
        </div>

        <p>Terima kasih telah mendaftar. Sampai jumpa di event!</p>
    </div>

    <div class="footer">
        <p style="margin: 5px 0; font-weight: 600; font-size: 16px;">DIANTARA</p>
        <p style="margin: 5px 0;">&copy; {{ date('Y') }} Diantara - Sistem Manajemen Event</p>
        <p style="margin: 5px 0; font-size: 12px; opacity: 0.8;">Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>
