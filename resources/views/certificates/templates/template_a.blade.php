<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin:0; padding:0; }
        .page { width: 100%; height: 100%; padding: 40px; box-sizing: border-box; }
        .border { border: 6px solid #2563eb; padding: 24px; height: 100%; }
        .title { text-align: center; font-size: 30px; font-weight: bold; letter-spacing: 2px; color:#1e293b; }
        .subtitle { text-align:center; color:#334155; margin-top: 6px; }
        .name { text-align:center; margin: 40px 0 10px; font-size: 36px; font-weight: 700; }
        .event { text-align:center; font-size: 14px; color:#334155; }
        .footer { position:absolute; bottom: 60px; left:40px; right:40px; display:flex; justify-content: space-between; align-items:center; }
        .sign { text-align:center; }
        .sign img { height: 70px; }
        .line { height:1px; background:#94a3b8; margin-top:6px; }
        .certno { position:absolute; top:40px; right:40px; font-size: 12px; color:#64748b; }
        .stamp { position:absolute; right: 120px; bottom: 80px; opacity:0.7; }
        .stamp img { height: 90px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="border">
            <div class="certno">No: {{ $certificate_number }}</div>
            @if(!empty($logo_path))
                <div style="position:absolute; top:40px; left:40px;">
                    <img src="{{ $logo_path }}" alt="Logo" style="height:50px;">
                </div>
            @endif
            <div class="title">CERTIFICATE OF PARTICIPATION</div>
            <div class="subtitle">This certificate is proudly presented to</div>
            <div class="name">{{ $user->full_name ?? $user->name }}</div>
            <div class="event">For successfully attending <strong>{{ $event->title }}</strong> held on {{ $event_date }}</div>
            <div class="footer">
                <div class="sign">
                    @if(!empty($org['signature1_image']))
                        <img src="{{ public_path('storage/'.$org['signature1_image']) }}" alt="sig1">
                    @endif
                    <div class="line"></div>
                    <div style="font-size:12px;">{{ $org['signature1_name'] ?? '' }}</div>
                    <div style="font-size:11px;color:#475569;">{{ $org['signature1_title'] ?? '' }}</div>
                </div>
                <div class="sign">
                    @if(!empty($org['signature2_image']))
                        <img src="{{ public_path('storage/'.$org['signature2_image']) }}" alt="sig2">
                    @endif
                    <div class="line"></div>
                    <div style="font-size:12px;">{{ $org['signature2_name'] ?? '' }}</div>
                    <div style="font-size:11px;color:#475569;">{{ $org['signature2_title'] ?? '' }}</div>
                </div>
            </div>
            @if(!empty($org['stamp_image']))
            <div class="stamp">
                <img src="{{ public_path('storage/'.$org['stamp_image']) }}" alt="stamp">
            </div>
            @endif
        </div>
    </div>
</body>
</html>
