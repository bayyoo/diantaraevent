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
            padding: 0;
            position: relative;
            height: 100vh;
            background: white;
        }
        
        .certificate-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('storage/' . $custom_template_path) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .certificate-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 10;
        }
        
        .participant-name {
            font-size: 48px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.9);
            padding: 10px 30px;
            border-radius: 10px;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: bold;
            color: #3498db;
            margin: 15px 0;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.8);
            padding: 8px 20px;
            border-radius: 8px;
        }
        
        .event-date {
            font-size: 20px;
            color: #2c3e50;
            margin: 10px 0;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.7);
            padding: 5px 15px;
            border-radius: 5px;
        }
        
        .certificate-info {
            position: absolute;
            bottom: 30px;
            left: 30px;
            font-size: 12px;
            color: #2c3e50;
            background: rgba(255,255,255,0.9);
            padding: 10px;
            border-radius: 5px;
        }
        
        .signature-section {
            position: absolute;
            bottom: 30px;
            right: 30px;
            text-align: center;
            background: rgba(255,255,255,0.9);
            padding: 15px;
            border-radius: 5px;
        }
        
        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }
        
        .signature-title {
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="certificate-background"></div>
    
    <div class="certificate-overlay">
        <div class="participant-name">{{ $user->full_name }}</div>
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-date">{{ $event_date }}</div>
    </div>
    
    <div class="certificate-info">
        <div>Certificate No: {{ $certificate_number }}</div>
        <div>Generated: {{ $generated_date }}</div>
    </div>
    
    <div class="signature-section">
        <div class="signature-name">
            {{ $event->certificate_signer_name ?? 'Event Organizer' }}
        </div>
        <div class="signature-title">
            {{ $event->certificate_signer_title ?? 'Organizer' }}
        </div>
    </div>
</body>
</html>
