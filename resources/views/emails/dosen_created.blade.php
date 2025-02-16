<!DOCTYPE html>
<html>

<head>
    <title>Akun Dosen Baru</title>
</head>

<body>
    <h2>Halo, {{ $nama }}</h2>
    <p>Akun dosen Anda telah dibuat di sistem kami.</p>
    <p>Berikut adalah detail akun Anda:</p>
    <ul>
        <li>Email: <strong>{{ $email }}</strong></li>
        <li>Password: <strong>{{ $password }}</strong></li>
    </ul>
    <p>Silakan login dan segera ubah password Anda untuk keamanan akun Anda.</p>
    <p>Terima kasih.</p>
</body>

</html>