<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode verifikasi akun Nexus Experience Manager Anda</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #374151;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f9fafb;
        }
        .container {
            background-color: #ffffff;
            margin: 40px 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ffffff;
            padding: 40px 40px 20px 40px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 16px;
            color: #374151;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #374151;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .otp-container {
            text-align: center;
            margin: 40px 0;
            background-color: #f8fafc;
            padding: 30px;
            border-radius: 8px;
            border: 2px dashed #e2e8f0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #1f2937;
            letter-spacing: 6px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            background-color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: inline-block;
        }
        .instruction {
            font-size: 16px;
            color: #374151;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #10b981;
            color: white !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 30px 0;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }
        .help-section {
            background-color: #f9fafb;
            padding: 30px;
            margin: 40px 0;
            border-radius: 8px;
        }
        .help-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
        }
        .help-content {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }
        .contact-info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .contact-item {
            font-size: 14px;
            color: #10b981;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        .footer-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        .footer-links {
            font-size: 12px;
            color: #10b981;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 24px; font-weight: bold; color: #2563eb; margin-bottom: 8px;">
                NEXUS
            </div>
            <div style="font-size: 14px; color: #6b7280;">
                EXPERIENCE MANAGER
            </div>
        </div>

        <div class="content">
            <div class="greeting">Halo {{ $name }},</div>
            
            <div class="message">
                Berikut kode verifikasi akun Nexus Experience Manager Anda.<br>
                Silahkan masukkan kode verifikasi ini pada halaman verifikasi akun Nexus Experience Manager:
            </div>
            
            <div class="otp-container">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 15px; font-weight: 600;">
                    KODE VERIFIKASI
                </div>
                <div class="otp-code">{{ $otp }}</div>
                <div style="font-size: 12px; color: #9ca3af; margin-top: 10px;">
                    Berlaku selama 5 menit
                </div>
            </div>

            <div class="instruction">
                Silahkan segera lakukan verifikasi akun Nexus Experience Manager Anda.
            </div>

            <div style="text-align: center;">
                <a href="{{ route('diantaranexus.verify-otp') }}" class="button">Verifikasi Akun Saya</a>
            </div>

            <div class="help-section">
                <div class="help-title">Butuh Bantuan?</div>
                <div class="help-content">
                    Hubungi kami melalui:
                </div>
                <div class="contact-info">
                    <div class="contact-item">
                        Chat WhatsApp<br>
                        +6281119510906
                    </div>
                    <div class="contact-item">
                        Email<br>
                        hello@nexusapp.com
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div style="font-size: 16px; font-weight: bold; color: #2563eb; margin-bottom: 10px;">
                NEXUS
            </div>
            <div class="footer-text">www.nexusapp.com | Cari Event | Buat Event</div>
            <div class="footer-text">Copyright © 2024 Nexus. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
