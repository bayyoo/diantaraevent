<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin:0; padding:0; }
        .container { padding: 50px; }
        .header { text-align:center; margin-bottom: 40px; }
        .brand { color:#2563eb; font-weight:800; letter-spacing: 1px; }
        .cert-title { font-size: 28px; font-weight:700; margin-top:6px; }
        .panel { border:1px solid #e5e7eb; border-radius: 10px; padding: 24px; }
        .label { color:#6b7280; font-size: 12px; text-transform: uppercase; letter-spacing:1px; }
        .name { font-size: 34px; font-weight:700; margin: 10px 0; }
        .detail { color:#374151; font-size: 14px; }
        .grid { display:flex; justify-content: space-between; margin-top: 40px; }
        .sig { text-align:center; width: 40%; }
        .sig img { height: 70px; }
        .line { height:1px; background:#9ca3af; margin-top:6px; }
        .certno { text-align:right; color:#6b7280; font-size:12px; margin-bottom: 8px; }
        .stamp { position:absolute; right: 90px; bottom: 110px; opacity:0.75; }
        .stamp img { height: 90px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="certno">Certificate: {{ $certificate_number }}</div>
        <div class="header">
            @if(!empty($logo_path))
                <img src="{{ $logo_path }}" alt="Logo" style="height:50px;margin-bottom:8px;">
            @endif
            <div class="brand">DIANTARA NEXUS</div>
            <div class="cert-title">Certificate of Completion</div>
        </div>
        <div class="panel">
            <div class="label">Presented to</div>
            <div class="name">{{ $user->full_name ?? $user->name }}</div>
            <div class="detail">For completing and fully attending the event <strong>{{ $event->title }}</strong> on {{ $event_date }}.</div>
        </div>
        <div class="grid">
            <div class="sig">
                @if(!empty($org['signature1_image']))
                    <img src="{{ public_path('storage/'.$org['signature1_image']) }}" alt="sig1">
                @endif
                <div class="line"></div>
                <div style="font-size:12px;">{{ $org['signature1_name'] ?? '' }}</div>
                <div style="font-size:11px;color:#6b7280;">{{ $org['signature1_title'] ?? '' }}</div>
            </div>
            <div class="sig">
                @if(!empty($org['signature2_image']))
                    <img src="{{ public_path('storage/'.$org['signature2_image']) }}" alt="sig2">
                @endif
                <div class="line"></div>
                <div style="font-size:12px;">{{ $org['signature2_name'] ?? '' }}</div>
                <div style="font-size:11px;color:#6b7280;">{{ $org['signature2_title'] ?? '' }}</div>
            </div>
        </div>
        @if(!empty($org['stamp_image']))
        <div class="stamp">
            <img src="{{ public_path('storage/'.$org['stamp_image']) }}" alt="stamp">
        </div>
        @endif
    </div>
</body>
</html>
