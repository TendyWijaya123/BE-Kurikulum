<!DOCTYPE html>
<html>

<head>
    <title>Password Diperbarui</title>
</head>

<body>
    <h2>Halo, {{ $name }}</h2>
    <p>Password akun Anda telah diperbarui.</p>
    <p>Berikut password baru Anda:</p>
    <ul>
        <li>Email: <strong>{{ $email }}</strong></li>
        <li>Password Baru: <strong>{{ $password }}</strong></li>
    </ul>
    <p>Silakan login dan segera ubah password Anda untuk keamanan.</p>
    <p>Terima kasih.</p>
</body>

</html>