<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $participant->user->name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            box-sizing: border-box;
        }
        
        .certificate-container {
            width: 100%;
            height: 100%;
            background: white;
            border: 8px solid #2c3e50;
            border-radius: 15px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .certificate-container::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #34495e;
            border-radius: 8px;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
            z-index: 1;
        }
        
        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 8px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .certificate-subtitle {
            font-size: 18px;
            color: #7f8c8d;
            font-style: italic;
            letter-spacing: 2px;
        }
        
        .certificate-body {
            text-align: center;
            max-width: 600px;
            z-index: 1;
            margin-bottom: 40px;
        }
        
        .certificate-text {
            font-size: 20px;
            color: #2c3e50;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .participant-name {
            font-size: 36px;
            font-weight: bold;
            color: #e74c3c;
            margin: 20px 0;
            text-decoration: underline;
            text-decoration-color: #e74c3c;
            text-underline-offset: 8px;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: bold;
            color: #3498db;
            margin: 20px 0;
            font-style: italic;
        }
        
        .event-date {
            font-size: 18px;
            color: #7f8c8d;
            margin-top: 20px;
        }
        
        .certificate-footer {
            position: absolute;
            bottom: 60px;
            right: 80px;
            text-align: center;
            z-index: 1;
        }
        
        .signature-line {
            width: 200px;
            border-bottom: 2px solid #2c3e50;
            margin: 0 auto 10px;
        }
        
        .signature-text {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .decorative-element {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 3px solid #ecf0f1;
            border-radius: 50%;
            opacity: 0.3;
        }
        
        .decorative-element.top-left {
            top: 40px;
            left: 40px;
        }
        
        .decorative-element.top-right {
            top: 40px;
            right: 40px;
        }
        
        .decorative-element.bottom-left {
            bottom: 40px;
            left: 40px;
        }
        
        .decorative-element.bottom-right {
            bottom: 40px;
            right: 40px;
        }
        
        .certificate-id {
            position: absolute;
            bottom: 20px;
            left: 40px;
            font-size: 12px;
            color: #bdc3c7;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Decorative Elements -->
        <div class="decorative-element top-left"></div>
        <div class="decorative-element top-right"></div>
        <div class="decorative-element bottom-left"></div>
        <div class="decorative-element bottom-right"></div>
        
        <!-- Certificate Header -->
        <div class="certificate-header">
            <div class="certificate-title">SERTIFIKAT</div>
            <div class="certificate-subtitle">Certificate of Participation</div>
        </div>
        
        <!-- Certificate Body -->
        <div class="certificate-body">
            <div class="certificate-text">
                Diberikan kepada:
            </div>
            
            <div class="participant-name">
                {{ $participant->user->name }}
            </div>
            
            <div class="certificate-text">
                Atas partisipasinya dalam kegiatan:
            </div>
            
            <div class="event-title">
                {{ $participant->event->title }}
            </div>
            
            <div class="event-date">
                Diselenggarakan pada {{ \Carbon\Carbon::parse($participant->event->event_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>
        
        <!-- Certificate Footer -->
        <div class="certificate-footer">
            <div class="signature-line"></div>
            <div class="signature-text">Penyelenggara</div>
        </div>
        
        <!-- Certificate ID -->
        <div class="certificate-id">
            ID: CERT-{{ $participant->id }}-{{ date('Y') }}
        </div>
    </div>
</body>
</html>
