<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar — Urban Kost</title>
<link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root {
    --green-50:  #f0faf4;
    --green-100: #dcf5e7;
    --green-200: #b8eacf;
    --green-300: #86d9ae;
    --green-400: #4ec98a;
    --green-500: #2ab36b;
    --green-600: #1d9458;
    --green-700: #167745;
    --green-800: #135a37;
    --green-900: #0e3f27;
    --neutral-0:   #ffffff;
    --neutral-50:  #f8faf9;
    --neutral-100: #f0f4f2;
    --neutral-200: #e1e9e5;
    --neutral-400: #8fa89d;
    --neutral-500: #5f7a6e;
    --neutral-600: #3e5549;
    --neutral-700: #2d4038;
    --neutral-900: #0f1f18;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --shadow-xl: 0 24px 80px rgba(14,63,39,.22);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    min-height: 100vh;
    background: var(--green-50);
    display: flex; align-items: center; justify-content: center;
    padding: 24px 16px;
    position: relative;
    -webkit-font-smoothing: antialiased;
    color: var(--neutral-900);
}
body::before {
    content: '';
    position: fixed; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.12) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.08) 0%, transparent 50%),
        radial-gradient(circle at 70% 10%, rgba(78,201,138,.07) 0%, transparent 40%);
    pointer-events: none;
}
body::after {
    content: '';
    position: fixed; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

.auth-wrapper {
    display: flex; width: 100%; max-width: 880px;
    border-radius: 20px; overflow: hidden;
    box-shadow: var(--shadow-xl), 0 0 0 1px rgba(42,179,107,.1);
    position: relative; z-index: 1;
    animation: slideUp .55s cubic-bezier(.16,1,.3,1) both;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(32px) scale(.97); }
    to   { opacity: 1; transform: none; }
}

.auth-left {
    flex: 1; position: relative; overflow: hidden;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    display: flex; flex-direction: column;
    justify-content: center; padding: 48px 40px;
}
.auth-left::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.auth-left-dots {
    position: absolute; inset: 0; opacity: .04;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 28px 28px; pointer-events: none;
}
.auth-arc {
    position: absolute; right: -120px; top: -120px;
    width: 420px; height: 420px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1); pointer-events: none;
}

.left-inner { position: relative; z-index: 1; max-width: 300px; }

.brand { display: flex; align-items: center; gap: 12px; margin-bottom: 40px; }
.brand-mark {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.05rem;
}
.brand-name {
    font-family: 'Fraunces', serif; font-weight: 700; font-size: 1.35rem;
    color: #fff; letter-spacing: -.02em; line-height: 1;
}
.brand-tag { font-size: .65rem; color: rgba(255,255,255,.6); letter-spacing: .1em; text-transform: uppercase; margin-top: 3px; }

.left-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    padding: 4px 12px; border-radius: 999px;
    font-size: .65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.8); margin-bottom: 14px;
}
.left-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }

.left-title {
    font-family: 'Fraunces', serif; font-weight: 700;
    font-size: clamp(1.5rem, 3vw, 1.9rem);
    color: #fff; line-height: 1.2; letter-spacing: -.025em; margin-bottom: 10px;
}
.left-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.left-sub { font-size: .82rem; color: rgba(255,255,255,.6); line-height: 1.65; margin-bottom: 28px; }

.check-list { display: flex; flex-direction: column; gap: 8px; }
.check-item {
    display: flex; align-items: center; gap: 11px;
    padding: 10px 13px; border-radius: 11px;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
    transition: all .2s;
}
.check-item:hover { background: rgba(255,255,255,.17); transform: translateX(4px); }
.check-icon {
    width: 26px; height: 26px; border-radius: 7px; flex-shrink: 0;
    background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.9); font-size: .76rem;
}
.check-text { font-size: .78rem; color: rgba(255,255,255,.8); line-height: 1.4; }

.auth-right {
    background: var(--neutral-0);
    width: 390px; flex-shrink: 0;
    padding: 36px 36px 32px;
    display: flex; flex-direction: column; justify-content: center;
    position: relative;
}
.auth-right::before {
    content: '';
    position: absolute; top: -50px; right: -50px;
    width: 190px; height: 190px;
    background: radial-gradient(circle, rgba(42,179,107,.07) 0%, transparent 70%);
    pointer-events: none;
}

.back-link {
    display: inline-flex; align-items: center; gap: 5px;
    color: var(--neutral-500); text-decoration: none;
    font-size: .75rem; font-weight: 600; margin-bottom: 22px;
    transition: all .2s; width: fit-content;
}
.back-link:hover { color: var(--green-600); transform: translateX(-3px); }

.auth-title {
    font-family: 'Fraunces', serif; font-weight: 700;
    font-size: 1.85rem; color: var(--neutral-900);
    line-height: 1.15; letter-spacing: -.025em; margin-bottom: 5px;
}
.auth-title span { color: var(--green-700); }
.auth-sub { font-size: .8rem; color: var(--neutral-500); margin-bottom: 20px; line-height: 1.6; }

.uk-alert {
    padding: 10px 12px; border-radius: 9px;
    font-size: .78rem; margin-bottom: 14px;
    display: flex; align-items: flex-start; gap: 8px; line-height: 1.5; border: 1px solid;
}
.uk-alert i { flex-shrink: 0; margin-top: 1px; }
.uk-alert-danger { background: var(--red-pale); border-color: rgba(220,38,38,.2); color: #7d1a22; }

.form-group { margin-bottom: 14px; }
.form-label {
    display: block; font-size: .69rem; font-weight: 600;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--neutral-700); margin-bottom: 6px;
}
.form-label .req { color: var(--green-500); }

.input-wrap { position: relative; }
.input-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--neutral-400); font-size: .84rem; transition: color .2s; pointer-events: none;
}
.input-wrap:focus-within .input-icon { color: var(--green-500); }

.form-control {
    width: 100%; padding: 11px 12px 11px 36px;
    border: 1px solid var(--neutral-200); border-radius: 10px;
    background: var(--neutral-50);
    font-family: 'Instrument Sans', sans-serif;
    font-size: .862rem; color: var(--neutral-900);
    transition: all .2s; outline: none;
}
.form-control:focus {
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(42,179,107,.12);
    background: var(--neutral-0);
}
.form-control::placeholder { color: var(--neutral-400); }
.form-control.is-invalid { border-color: var(--red); }
.form-text { font-size: .7rem; color: var(--neutral-400); margin-top: 5px; }
.invalid-feedback { font-size: .72rem; color: var(--red); margin-top: 4px; display: block; }

.info-box {
    padding: 10px 13px; background: var(--green-50);
    border: 1px solid var(--green-100); border-radius: 10px; margin-bottom: 16px;
}
.info-box-title { font-size: .71rem; font-weight: 700; color: var(--green-700); display: block; margin-bottom: 3px; }
.info-box p { font-size: .73rem; color: var(--neutral-600); line-height: 1.55; margin: 0; }

.btn-submit {
    width: 100%; padding: 12px;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    border: none; border-radius: 11px; color: #fff;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .875rem; font-weight: 600; cursor: pointer;
    transition: all .25s; box-shadow: 0 4px 18px rgba(42,179,107,.3);
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-bottom: 14px;
}
.btn-submit:hover { background: linear-gradient(135deg, var(--green-600), var(--green-800)); transform: translateY(-1px); box-shadow: 0 8px 26px rgba(42,179,107,.38); }
.btn-submit:active { transform: none; }

.login-link { text-align: center; font-size: .79rem; color: var(--neutral-500); }
.login-link a { color: var(--green-700); font-weight: 700; text-decoration: none; }
.login-link a:hover { text-decoration: underline; }

@media (max-width: 800px) {
    .auth-wrapper { flex-direction: column; max-width: 480px; }
    .auth-left { padding: 22px 22px 18px; }
    .left-title { font-size: 1.4rem; }
    .left-sub, .check-list { display: none; }
    .brand { margin-bottom: 0; }
    .auth-arc { display: none; }
    .auth-right { width: 100%; padding: 28px 22px 24px; }
}
@media (max-width: 420px) {
    body { padding: 12px; }
    .auth-right { padding: 24px 18px 20px; }
    .auth-title { font-size: 1.6rem; }
}
@media (min-width: 1200px) { .auth-wrapper { max-width: 960px; } }
</style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-left">
        <div class="auth-left-dots"></div>
        <div class="left-inner">
            <div class="brand">
                <div class="brand-mark"><i class="bi bi-house-heart-fill"></i></div>
                <div>
                    <div class="brand-name">Urban Kost</div>
                    <div class="brand-tag">Hunian Premium &middot; Yogyakarta</div>
                </div>
            </div>
            <div class="left-eyebrow"><span class="dot"></span>Pendaftaran</div>
            <h2 class="left-title">Bergabung dengan<br><em>Urban Kost</em></h2>
            <p class="left-sub">Daftarkan diri dan nikmati kemudahan pemesanan kamar secara digital, kapan saja dan di mana saja.</p>
            <div class="check-list">
                <div class="check-item">
                    <div class="check-icon"><i class="bi bi-calendar-check"></i></div>
                    <div class="check-text">Booking kamar online kapan saja</div>
                </div>
                <div class="check-item">
                    <div class="check-icon"><i class="bi bi-activity"></i></div>
                    <div class="check-text">Pantau status permohonan secara langsung</div>
                </div>
                <div class="check-item">
                    <div class="check-icon"><i class="bi bi-bell"></i></div>
                    <div class="check-text">Notifikasi tagihan dan pembaruan otomatis</div>
                </div>
                <div class="check-item">
                    <div class="check-icon"><i class="bi bi-person-circle"></i></div>
                    <div class="check-text">Dashboard personal eksklusif penghuni</div>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left-short" style="font-size:1rem"></i> Kembali ke beranda
        </a>

        <h1 class="auth-title">Buat Akun<br><span>Baru</span></h1>
        <p class="auth-sub">Pendaftaran gratis sebagai calon penghuni Urban Kost.</p>

        @if(session('error'))
        <div class="uk-alert uk-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{!! session('error') !!}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nama sesuai KTP"
                        value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="emailanda@gmail.com"
                        value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{!! $message !!}</div>@enderror
                </div>
                <div class="form-text">Digunakan untuk menerima kode OTP saat login.</div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor HP / WhatsApp</label>
                <div class="input-wrap">
                    <i class="bi bi-telephone input-icon"></i>
                    <input type="text" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="0812-3456-7890"
                        value="{{ old('phone') }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="info-box">
                <span class="info-box-title"><i class="bi bi-info-circle-fill"></i> Informasi Pendaftaran</span>
                <p>Setelah mendaftar, Anda mendapat akses sebagai <strong>Calon Penghuni</strong>. Status <strong>Penghuni Aktif</strong> ditetapkan oleh admin.</p>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-person-plus-fill"></i> Daftar Sekarang
            </button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>

</body>
</html>