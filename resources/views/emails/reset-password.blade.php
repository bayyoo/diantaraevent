<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name', 'Diantara') }}</title>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
        }
        .header {
            background: linear-gradient(135deg, #7681FF 0%, #5A67D8 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #1f2937;
            margin-top: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content p {
            color: #6b7280;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background: #7681FF;
            color: #ffffff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background: #5A67D8;
        }
        .info-box {
            background-color: #f3f4f6;
            border-left: 4px solid #7681FF;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #374151;
            font-size: 14px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            color: #9ca3af;
            font-size: 14px;
            margin: 5px 0;
        }
        .footer a {
            color: #7681FF;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .logo {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 15px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            .header, .content, .footer {
                padding: 20px !important;
            }
            .content h2 {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <span style="color: #7681FF; font-weight: bold; font-size: 18px;">DIANTARA</span>
            </div>
            <h1>Reset Password</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Halo!</h2>
            
            <p>Kami menerima permintaan untuk reset password akun Diantara Anda. Jika Anda tidak membuat permintaan ini, abaikan email ini.</p>
            
            <p>Untuk reset password Anda, klik tombol di bawah ini:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </div>
            
            <div class="info-box">
                <p><strong>Catatan Penting:</strong></p>
                <p>• Link ini akan kedaluwarsa dalam 60 menit</p>
                <p>• Untuk keamanan, jangan bagikan link ini kepada siapa pun</p>
                <p>• Jika tombol tidak bekerja, copy dan paste link berikut ke browser Anda:</p>
            </div>
            
            <p style="word-break: break-all; background-color: #f9fafb; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 14px;">
                {{ $url }}
            </p>
            
            <p>Jika Anda mengalami masalah atau tidak meminta reset password ini, silakan hubungi tim support kami.</p>
            
            <p>Terima kasih,<br>
            <strong>Tim Diantara</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Diantara. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit our website</a> | 
                <a href="#">Help Center</a> | 
                <a href="#">Contact Support</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #6b7280;">
                Email ini dikirim otomatis, mohon jangan membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>




