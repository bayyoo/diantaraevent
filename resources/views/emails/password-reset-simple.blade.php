<!DOCTYPE html>
<html>
<body>
    <p>Halo,</p>
    <p>Kami menerima permintaan untuk reset password akun Diantara Anda.</p>
    <p>
        Klik tombol di bawah ini untuk mengatur ulang password Anda:
    </p>
    <p>
        <a href="{{ $resetUrl }}" style="display:inline-block;padding:10px 16px;background:#4f46e5;color:#ffffff;text-decoration:none;border-radius:6px;">Reset Password</a>
    </p>
    <p>Jika tombol di atas tidak berfungsi, salin dan buka link berikut di browser Anda:</p>
    <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
    <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
</body>
</html>
