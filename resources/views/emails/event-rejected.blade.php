<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Rejected</title>
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
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
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
        .warning-icon {
            text-align: center;
            font-size: 60px;
            color: #EF4444;
            margin-bottom: 20px;
        }
        .event-details {
            background-color: #fef2f2;
            border-left: 4px solid #EF4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .event-details h3 {
            margin-top: 0;
            color: #DC2626;
        }
        .rejection-reason {
            background-color: #fff7ed;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .rejection-reason strong {
            color: #D97706;
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
        .tips {
            background-color: #eff6ff;
            border-left: 4px solid #3B82F6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Event Ditolak</h1>
        </div>
        
        <div class="content">
            <div class="warning-icon">❌</div>
            
            <p>Halo <strong>{{ $event->creator->name }}</strong>,</p>
            
            <p>Mohon maaf, event yang kamu buat <strong>tidak dapat disetujui</strong> oleh admin karena beberapa alasan.</p>
            
            <div class="event-details">
                <h3>{{ $event->title }}</h3>
                <p style="margin: 5px 0; color: #6b7280;">
                    📅 {{ $event->event_date->format('d F Y') }} • 
                    🕐 {{ $event->event_time ? $event->event_time->format('H:i') : '-' }} WIB
                </p>
            </div>
            
            <div class="rejection-reason">
                <p style="margin-top: 0;"><strong>Alasan Penolakan:</strong></p>
                <p style="margin-bottom: 0;">{{ $event->rejection_reason }}</p>
            </div>
            
            <p>Jangan berkecil hati! Kamu bisa memperbaiki event sesuai dengan feedback di atas dan submit ulang untuk direview kembali.</p>
            
            <div class="tips">
                <p style="margin-top: 0;"><strong>💡 Tips:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Pastikan informasi event lengkap dan jelas</li>
                    <li>Gunakan gambar flyer yang berkualitas</li>
                    <li>Deskripsi event harus informatif dan menarik</li>
                    <li>Pastikan tanggal dan lokasi sudah benar</li>
                </ul>
            </div>
            
            <center>
                <a href="{{ route('user.events.edit', $event) }}" class="button">
                    Edit & Submit Ulang
                </a>
            </center>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                Jika ada pertanyaan, silakan hubungi admin kami.
            </p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem Diantara.</p>
            <p>&copy; {{ date('Y') }} Diantara. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
