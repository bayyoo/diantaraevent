<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $event->title }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            position: relative;
            height: 100vh;
            box-sizing: border-box;
        }
        
        .certificate-container {
            background: white;
            border: 8px solid #2c3e50;
            border-radius: 20px;
            padding: 60px;
            height: calc(100% - 120px);
            position: relative;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
        }
        
        .certificate-border {
            border: 3px solid #3498db;
            border-radius: 15px;
            padding: 40px;
            height: calc(100% - 80px);
            position: relative;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 40px;
        }
        
        .content {
            text-align: center;
            margin: 40px 0;
        }
        
        .awarded-to {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .participant-name {
            font-size: 42px;
            font-weight: bold;
            color: #e74c3c;
            margin: 20px 0;
            text-decoration: underline;
            text-decoration-color: #3498db;
        }
        
        .event-details {
            font-size: 20px;
            color: #2c3e50;
            margin: 30px 0;
            line-height: 1.6;
        }
        
        .event-title {
            font-weight: bold;
            color: #3498db;
        }
        
        .footer {
            position: absolute;
            bottom: 20px;
            left: 40px;
            right: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .certificate-info {
            text-align: left;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #2c3e50;
            width: 200px;
            margin: 40px auto 10px;
        }
        
        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .signature-title {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .decorative-element {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            border: 3px solid #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(52, 152, 219, 0.1);
            font-weight: bold;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="watermark">DIANTARA</div>
            
            <div class="decorative-element">
                <i class="fas fa-award"></i>
            </div>
            
            <div class="header">
                <div class="logo">DIANTARA</div>
                <div class="certificate-title">Certificate of Participation</div>
                <div class="subtitle">This is to certify that</div>
            </div>
            
            <div class="content">
                <div class="participant-name">{{ $user->full_name }}</div>
                
                <div class="awarded-to">has successfully participated in</div>
                
                <div class="event-details">
                    <div class="event-title">{{ $event->title }}</div>
                    <div>held on {{ $event_date }}</div>
                    <div>at {{ $event->location }}</div>
                </div>
            </div>
            
            <div class="footer">
                <div class="certificate-info">
                    <div>Certificate No: {{ $certificate_number }}</div>
                    <div>Generated on: {{ $generated_date }}</div>
                    <div>Verified by: Diantara Event Management System</div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="signature-name">
                        {{ $event->certificate_signer_name ?? 'Event Organizer' }}
                    </div>
                    <div class="signature-title">
                        {{ $event->certificate_signer_title ?? 'Organizer' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
