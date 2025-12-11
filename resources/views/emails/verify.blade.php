<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .header h1 {
            color: #333;
        }
        .content {
            padding: 20px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #3490dc; /* Warna tombol */
            color: #ffffff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #2779bd;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .link-text {
            word-break: break-all;
            color: #3490dc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $user->name }}!</h2>
            <p>Terima kasih telah mendaftar. Untuk mulai menggunakan akun Anda, mohon verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
            
            <a href="{{ $url }}" class="btn">Verifikasi Email Saya</a>
            
            <p style="margin-top: 30px;">Jika Anda tidak merasa mendaftar di aplikasi kami, abaikan email ini.</p>
        </div>

        <div class="footer">
            <p>Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:</p>
            <p><a href="{{ $url }}" class="link-text">{{ $url }}</a></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>