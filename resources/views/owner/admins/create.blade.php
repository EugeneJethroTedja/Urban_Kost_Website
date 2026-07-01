<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin — Urban Kost</title>
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
        --accent:      #f4a535;
        --accent-pale: #fffbf0;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
        --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
        font-family: 'Instrument Sans', sans-serif;
        background: var(--green-50);
        color: var(--neutral-900);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
    }
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: var(--green-50); }
    ::-webkit-scrollbar-thumb { background: var(--green-400); border-radius: 3px; }
    .owner-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50); }
    .owner-bg::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
            radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%),
            radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
    }
    .owner-bg-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
        background-size: 60px 60px;
    }
    .page-shell { max-width: 680px; margin: 0 auto; padding: 44px 32px 80px; }
    .flash {
        padding: 13px 18px; border-radius: 12px; margin-bottom: 22px;
        font-size: .84rem; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
    }
    .flash.success { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-300); }
    .flash.error   { background: var(--red-pale); color: var(--red); border: 1px solid rgba(220,38,38,.2); }

    /* ── Hero Banner ── */
    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 28px 28px 24px;
        margin-bottom: 24px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    }
    .hero-banner::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .hero-arc {
        position: absolute; right: -80px; top: -80px;
        width: 280px; height: 280px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none;
    }
    .hero-content { position: relative; z-index: 1; display: flex; align-items: center; gap: 16px; }
    .hero-icon {
        width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.3rem;
    }
    .hero-text { }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 3px 11px; border-radius: 100px;
        font-size: .64rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.8);
        margin-bottom: 8px;
    }
    .hero-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700; font-size: 1.55rem;
        color: #fff; line-height: 1.1;
        letter-spacing: -.025em; margin-bottom: 5px;
    }
    .hero-sub { font-size: .82rem; color: rgba(255,255,255,.62); font-weight: 400; line-height: 1.5; }

    .back-link {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: .8rem; font-weight: 600; color: var(--green-700);
        text-decoration: none; margin-bottom: 20px;
        padding: 6px 12px; border-radius: 8px;
        border: 1px solid var(--green-200);
        background: var(--green-50);
        transition: background .15s, border-color .15s;
    }
    .back-link:hover { background: var(--green-100); border-color: var(--green-300); }
    .back-link i { font-size: .75rem; }
    .notice-banner {
        display: flex; align-items: flex-start; gap: 12px;
        background: var(--accent-pale);
        border: 1px solid rgba(244,165,53,.35);
        border-radius: 14px; padding: 14px 18px;
        margin-bottom: 24px;
    }
    .notice-banner-icon {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        background: rgba(244,165,53,.15);
        display: flex; align-items: center; justify-content: center;
        color: #92400e; font-size: .9rem;
    }
    .notice-banner-text { font-size: .8rem; color: #78350f; font-weight: 500; line-height: 1.55; }
    .notice-banner-text strong { font-weight: 700; display: block; margin-bottom: 2px; font-size: .82rem; }
    .form-card {
        background: var(--neutral-0); border-radius: 18px;
        border: 1px solid var(--green-200); box-shadow: var(--shadow-md); overflow: hidden;
    }
    .form-card-header {
        padding: 16px 24px;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
        border-bottom: 1px solid var(--green-100);
        display: flex; align-items: center; gap: 9px;
    }
    .form-card-header i { font-size: .95rem; color: var(--green-500); }
    .form-card-header h2 {
        font-family: 'Fraunces', serif;
        font-size: .95rem; font-weight: 700;
        color: var(--neutral-700); letter-spacing: -.02em;
    }
    .form-card-body { padding: 28px; }
    .field-group { margin-bottom: 22px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-label {
        display: block; font-size: .72rem; font-weight: 600;
        color: var(--neutral-600); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 7px;
    }
    .field-label .req { color: var(--red); margin-left: 2px; }
    .field-input {
        width: 100%; padding: 11px 14px; border-radius: 10px;
        border: 1px solid var(--neutral-200);
        font-family: 'Instrument Sans', sans-serif;
        font-size: .88rem; font-weight: 400;
        color: var(--neutral-900); background: var(--neutral-0);
        outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .field-input:hover { border-color: var(--green-300); }
    .field-input:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.13); }
    .field-input.is-invalid { border-color: var(--red); }
    .field-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12); }
    .field-hint {
        font-size: .73rem; color: var(--neutral-400); margin-top: 6px; font-weight: 400;
        display: flex; align-items: flex-start; gap: 5px;
    }
    .field-hint i { font-size: .7rem; margin-top: 2px; flex-shrink: 0; }
    .invalid-msg {
        font-size: .73rem; color: var(--red); margin-top: 6px; font-weight: 600;
        display: flex; align-items: center; gap: 5px;
    }
    .form-divider { height: 1px; background: linear-gradient(90deg, transparent, var(--green-200), transparent); margin: 24px 0; }
    .otp-info {
        display: flex; align-items: flex-start; gap: 12px;
        background: var(--green-50); border: 1px solid var(--green-200); border-radius: 12px; padding: 14px 18px;
    }
    .otp-info-icon {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        background: var(--green-100);
        display: flex; align-items: center; justify-content: center;
        color: var(--green-700); font-size: .9rem;
    }
    .otp-info-text { font-size: .79rem; color: var(--green-800); font-weight: 400; line-height: 1.6; }
    .otp-info-text strong { font-weight: 600; }
    .action-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 24px; border-radius: 10px;
        background: linear-gradient(135deg, var(--green-800), var(--green-600));
        color: #fff; font-family: 'Instrument Sans', sans-serif;
        font-size: .85rem; font-weight: 600;
        border: none; cursor: pointer;
        box-shadow: 0 4px 16px rgba(42,179,107,.28);
        transition: transform .18s, box-shadow .18s;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(42,179,107,.36); }
    .btn-submit:active { transform: translateY(0); }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 11px 20px; border-radius: 10px;
        background: transparent; color: var(--neutral-500);
        font-family: 'Instrument Sans', sans-serif;
        font-size: .85rem; font-weight: 600;
        border: 1px solid var(--neutral-200); cursor: pointer;
        text-decoration: none;
        transition: border-color .15s, color .15s, background .15s;
    }
    .btn-cancel:hover { border-color: var(--green-300); color: var(--green-700); background: var(--green-50); }
    @media (max-width: 600px) {
        .page-shell { padding: 24px 16px 60px; }
        .form-card-body { padding: 20px 16px; }
        .action-row { flex-direction: column; }
        .btn-submit, .btn-cancel { width: 100%; justify-content: center; }
        .hero-content { flex-direction: column; align-items: flex-start; gap: 12px; }
        .hero-banner { padding: 22px 18px 20px; }
    }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@include('partials.owner-sidebar')

<div class="page-shell">

    @if(session('success'))
        <div class="flash success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
    @endif

    <a href="{{ route('owner.admins.index') }}" 
    style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;border:1px solid var(--green-200);background:var(--neutral-0);color:var(--neutral-600);text-decoration:none;font-size:.78rem;font-weight:600;transition:all .2s;margin-bottom:16px"
    onmouseover="this.style.borderColor='var(--green-400)';this.style.color='var(--green-700)';this.style.background='var(--green-50)'"
    onmouseout="this.style.borderColor='var(--green-200)';this.style.color='var(--neutral-600)';this.style.background='var(--neutral-0)'">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-icon"><i class="bi bi-person-plus-fill"></i></div>
            <div class="hero-text">
                <div class="hero-eyebrow"><span class="dot"></span>Manajemen Admin</div>
                <h1 class="hero-title">Tambah Admin Baru</h1>
                <p class="hero-sub">Buat akun admin dengan akses ke sistem Urban Kost.</p>
            </div>
        </div>
    </div>

    <div class="notice-banner">
        <div class="notice-banner-icon"><i class="bi bi-shield-exclamation"></i></div>
        <div class="notice-banner-text">
            <strong>Hanya Owner yang dapat menambahkan Admin</strong>
            Admin memiliki akses penuh ke manajemen kamar, penghuni, dan booking. Pastikan data yang dimasukkan sudah benar.
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-person-badge-fill"></i>
            <h2>Informasi Akun Admin</h2>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('owner.admins.store') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="name">Nama Lengkap<span class="req">*</span></label>
                    <input type="text" id="name" name="name"
                        class="field-input @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required
                        placeholder="Contoh: Budi Santoso" autocomplete="off">
                    @error('name')
                        <div class="invalid-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email<span class="req">*</span></label>
                    <input type="email" id="email" name="email"
                        class="field-input @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required
                        placeholder="admin@urbankost.id" autocomplete="off">
                    @error('email')
                        <div class="invalid-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                    @enderror
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>Kode OTP login akan dikirim ke email ini setiap kali admin masuk.
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="phone">No. HP / WhatsApp</label>
                    <input type="text" id="phone" name="phone"
                        class="field-input @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="0812-xxxx-xxxx">
                    @error('phone')
                        <div class="invalid-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                    @enderror
                    <div class="field-hint">Digunakan untuk mendapatkan notifikasi whatsapp.</div>
                </div>

                <div class="form-divider"></div>

                <div class="otp-info">
                    <div class="otp-info-icon"><i class="bi bi-envelope-check-fill"></i></div>
                    <div class="otp-info-text">
                        Setelah akun dibuat, <strong>email verifikasi beserta OTP</strong> akan dikirim otomatis ke alamat email admin. Admin dapat langsung masuk menggunakan OTP tersebut.
                    </div>
                </div>

                <div class="form-divider"></div>

                <div class="action-row">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-person-check-fill"></i>Tambah Admin & Kirim OTP
                    </button>
                    <a href="{{ route('owner.admins.index') }}" class="btn-cancel">
                        <i class="bi bi-x-lg"></i>Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

</body>
</html>