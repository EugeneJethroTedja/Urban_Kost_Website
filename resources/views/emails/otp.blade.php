<!DOCTYPE html>
<html lang="id" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <title>Kode OTP Urban Kost</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; display: block; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }

        body {
            font-family: 'Segoe UI', Georgia, 'Times New Roman', serif;
            background-color: #dfe9e1;
        }

        .email-wrapper {
            background-color: #dfe9e1;
            padding: 40px 16px;
        }

        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(20, 50, 35, 0.18);
        }

        .header {
            background: linear-gradient(150deg, #152d20 0%, #1e4230 50%, #0d2018 100%);
            padding: 44px 48px 36px;
            text-align: center;
        }

        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(196,162,70,0.4);
            border-radius: 16px;
            padding: 14px 20px;
            font-size: 2rem;
            line-height: 1;
            margin-bottom: 18px;
        }

        .header-brand {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.05em;
            margin: 0 0 5px;
        }

        .header-tagline {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(196,162,70,0.85);
            margin: 0;
        }

        .ribbon {
            background: linear-gradient(90deg, #2d6644, #3d8a5a, #2d6644);
            padding: 9px 24px;
            text-align: center;
        }

        .ribbon-text {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(200,230,210,0.85);
            margin: 0;
        }

        .body {
            padding: 40px 48px 32px;
        }

        .greeting {
            font-size: 1.05rem;
            font-weight: 700;
            color: #152d20;
            margin: 0 0 12px;
        }

        .intro-text {
            font-size: 0.875rem;
            color: #4f7a60;
            line-height: 1.8;
            margin: 0 0 30px;
        }

        .intro-text strong {
            color: #1a3a2a;
            font-weight: 700;
        }

        .otp-card {
            background: linear-gradient(140deg, #f4efe4 0%, #eaf4ed 100%);
            border: 1.5px solid rgba(55,115,80,0.2);
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            margin-bottom: 26px;
            position: relative;
        }

        .otp-card-accent {
            width: 56px;
            height: 3px;
            background: linear-gradient(90deg, #3d7a55, #6ab87e);
            border-radius: 3px;
            margin: 0 auto 14px;
        }

        .otp-label {
            font-size: 0.67rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #6aaa80;
            margin: 0 0 16px;
        }

        .otp-code {
            font-family: 'Courier New', 'Lucida Console', monospace;
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: 12px;
            color: #152d20;
            text-indent: 12px;
            line-height: 1;
            margin: 0 0 18px;
        }

        .otp-timer {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(55,115,80,0.1);
            border: 1px solid rgba(55,115,80,0.18);
            border-radius: 30px;
            padding: 6px 16px;
            font-size: 0.77rem;
            color: #3d7a55;
            font-weight: 600;
        }

        .steps {
            margin: 0 0 26px;
        }

        .steps-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9ab8a0;
            margin: 0 0 14px;
        }

        .step-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 12px;
        }

        .step-num {
            flex-shrink: 0;
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #2d6644, #4a9a65);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }

        .step-text {
            font-size: 0.83rem;
            color: #4f7a60;
            line-height: 1.65;
            padding-top: 3px;
        }

        .warning-box {
            display: flex;
            gap: 12px;
            background: rgba(196,162,70,0.08);
            border-left: 3px solid rgba(196,162,70,0.7);
            border-radius: 0 10px 10px 0;
            padding: 14px 16px;
            margin-bottom: 26px;
        }

        .warning-icon { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

        .warning-text {
            font-size: 0.8rem;
            color: #5c4008;
            line-height: 1.65;
            margin: 0;
        }

        .warning-text strong { color: #3d2800; }

        .divider {
            border: none;
            border-top: 1px solid rgba(55,115,80,0.12);
            margin: 24px 0;
        }

        .footer-note {
            font-size: 0.78rem;
            color: #8aac96;
            line-height: 1.75;
            margin: 0;
        }

        .footer-note a {
            color: #3d7a55;
            text-decoration: underline;
            text-decoration-color: rgba(61,122,85,0.35);
        }

        .footer {
            background: #122518;
            padding: 26px 48px;
            text-align: center;
        }

        .footer-brand {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 0.9rem;
            color: rgba(160,210,175,0.65);
            letter-spacing: 0.08em;
            margin: 0 0 8px;
        }

        .footer-dots {
            margin: 0 0 8px;
        }

        .footer-dot {
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(160,210,175,0.2);
            margin: 0 3px;
        }

        .footer-dot:nth-child(2) { background: rgba(196,162,70,0.3); }

        .footer-copy {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.25);
            line-height: 1.7;
            margin: 0;
        }

        .footer-copy a {
            color: rgba(160,210,175,0.4);
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .email-wrapper { padding: 24px 10px !important; }
            .body { padding: 30px 24px 24px !important; }
            .header { padding: 34px 24px 28px !important; }
            .footer { padding: 22px 24px !important; }
            .otp-code { font-size: 2.4rem !important; letter-spacing: 8px !important; text-indent: 8px !important; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
<div class="container">

    <div class="header">
        <div class="header-badge"></div>
        <p class="header-brand">Urban Kost</p>
        <p class="header-tagline">Yogyakarta &nbsp;·&nbsp; Indonesia</p>
    </div>

    <div class="ribbon">
        <p class="ribbon-text">
            @if($type === 'register')
                 &nbsp; Verifikasi Pendaftaran Akun &nbsp; 
            @else
                 &nbsp; Permintaan Login Akun &nbsp; 
            @endif
        </p>
    </div>

    <div class="body">

        <p class="greeting">Halo, {{ $userName ?: 'Pengguna' }} </p>

        @if($type === 'register')
        <p class="intro-text">
            Terima kasih telah mendaftar di <strong>Urban Kost</strong>. Untuk menyelesaikan proses pendaftaran dan mengaktifkan akun Anda, masukkan kode OTP berikut di halaman verifikasi.
        </p>
        @else
        <p class="intro-text">
            Kami menerima permintaan login ke akun <strong>Urban Kost</strong> Anda. Gunakan kode di bawah ini untuk melanjutkan — kode bersifat rahasia dan hanya berlaku satu kali.
        </p>
        @endif

        <div class="otp-card">
            <div class="otp-card-accent"></div>
            <p class="otp-label">Kode OTP Anda</p>
            <div class="otp-code">{{ $otp }}</div>
            <div class="otp-timer"> &nbsp;Berlaku selama <strong>10 menit</strong></div>
        </div>

        <div class="steps">
            <p class="steps-title">Cara Menggunakan</p>

            <div class="step-row">
                <div class="step-num">1</div>
                <p class="step-text">Buka halaman verifikasi di browser Anda yang masih aktif.</p>
            </div>
            <div class="step-row">
                <div class="step-num">2</div>
                <p class="step-text">Masukkan 6 digit kode OTP di atas ke kolom yang tersedia.</p>
            </div>
            <div class="step-row">
                <div class="step-num">3</div>
                <p class="step-text">Klik <strong style="color:#1a3a2a">Verifikasi</strong> — akun Anda langsung aktif.</p>
            </div>
        </div>

        <div class="warning-box">
            <div class="warning-icon"></div>
            <p class="warning-text">
                <strong>Peringatan Keamanan:</strong> Jangan bagikan kode ini kepada siapapun, termasuk pihak yang mengaku sebagai admin Urban Kost. Tim kami <em>tidak pernah</em> meminta kode OTP Anda.
            </p>
        </div>

        <p class="intro-text" style="margin-bottom:0">
            Jika Anda tidak melakukan permintaan ini, abaikan email ini. Akun Anda tetap aman dan tidak ada tindakan lebih lanjut yang diperlukan.
        </p>

        <hr class="divider">

        <p class="footer-note">
            Email ini dikirim otomatis oleh sistem Urban Kost — mohon tidak membalas email ini.<br>
            Butuh bantuan? Hubungi kami di <a href="mailto:info@urbankost.id">info@urbankost.id</a> atau melalui WhatsApp admin.
        </p>

    </div>

    <div class="footer">
        <p class="footer-brand">Urban Kost</p>
        <div class="footer-dots">
            <span class="footer-dot"></span>
            <span class="footer-dot"></span>
            <span class="footer-dot"></span>
        </div>
        <p class="footer-copy">
            © {{ date('Y') }} Urban Kost — Yogyakarta, Indonesia<br>
            <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
        </p>
    </div>

</div>
</div>
</body>
</html>