<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login Notification</title>
</head>
<body>
    <h2>Halo {{ $user->name }},</h2>
    <p>
        Kami mendeteksi login baru ke akun Anda menggunakan Google pada:<br>
        <strong>{{ $time }}</strong>
    </p>
    <p>Jika ini memang Anda, tidak perlu melakukan apa-apa. Jika bukan, segera hubungi admin kami.</p>

    <br>
    <p>Terima kasih,<br>Tim Marketplace PERSIKINDO</p>
</body>
</html>
