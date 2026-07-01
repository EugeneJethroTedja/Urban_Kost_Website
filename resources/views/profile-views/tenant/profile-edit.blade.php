<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil — Urban Kost</title>
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
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
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
    .owner-bg {
        position: fixed; inset: 0; z-index: -1; overflow: hidden;
        background: var(--green-50);
    }
    .owner-bg::before {
        content: '';
        position: absolute; inset: 0;
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
    .page-shell {
        max-width: 700px;
        margin: 0 auto;
        padding: 32px 24px 64px;
    }
    .breadcrumb-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        font-size: .75rem;
        color: var(--neutral-400);
    }
    .breadcrumb-row a {
        color: var(--green-600);
        text-decoration: none;
        font-weight: 600;
        display: flex; align-items: center; gap: 5px;
        transition: color .15s;
    }
    .breadcrumb-row a:hover { color: var(--green-500); }
    .breadcrumb-row .sep { color: var(--neutral-200); font-size: .62rem; }
    .breadcrumb-row .current { color: var(--neutral-500); font-weight: 500; }
    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 32px;
        margin-bottom: 20px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .hero-banner::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .hero-arc {
        position: absolute; right: -100px; top: -100px;
        width: 320px; height: 320px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none;
    }
    .hero-avatar {
        width: 62px; height: 62px;
        border-radius: 16px;
        background: linear-gradient(145deg, var(--green-400), var(--green-500));
        display: flex; align-items: center; justify-content: center;
        font-family: 'Fraunces', serif;
        font-size: 1.6rem; font-weight: 700;
        color: #fff;
        border: 2.5px solid rgba(255,255,255,.2);
        box-shadow: 0 6px 18px rgba(0,0,0,.2);
        flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .hero-meta { position: relative; z-index: 1; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 3px 11px; border-radius: 100px;
        font-size: .63rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.8);
        margin-bottom: 7px;
    }
    .hero-eyebrow .dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--green-300); flex-shrink: 0;
    }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-size: clamp(1.2rem, 3vw, 1.55rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
    }
    .hero-title em {
        font-style: italic;
        font-weight: 400;
        color: rgba(255,255,255,.72);
    }
    .hero-sub {
        font-size: .78rem;
        color: rgba(255,255,255,.55);
        margin-top: 4px;
    }
    .o-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 14px;
    }
    .o-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; gap: 10px;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .o-card-header-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: var(--green-100);
        display: flex; align-items: center; justify-content: center;
        color: var(--green-700); font-size: .88rem;
    }
    .o-card-header-title {
        font-size: .68rem; font-weight: 600;
        color: var(--neutral-600);
        text-transform: uppercase; letter-spacing: .07em;
    }
    .o-card-body { padding: 22px; }
    .field-group { margin-bottom: 18px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-label {
        display: block;
        font-size: .72rem;
        font-weight: 600;
        color: var(--neutral-600);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 8px;
    }
    .field-required { color: var(--red); margin-left: 2px; }
    .input-wrap { position: relative; }
    .input-icon {
        position: absolute;
        left: 12px; top: 50%;
        transform: translateY(-50%);
        color: var(--neutral-400);
        font-size: .85rem;
        pointer-events: none;
    }
    .field-input {
        width: 100%;
        padding: 11px 14px 11px 36px;
        font-size: .88rem;
        font-family: 'Instrument Sans', sans-serif;
        color: var(--neutral-900);
        background: var(--neutral-50);
        border: 1.5px solid var(--neutral-200);
        border-radius: 10px;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .field-input:focus {
        background: var(--neutral-0);
        border-color: var(--green-500);
        box-shadow: 0 0 0 3px rgba(42,179,107,.12);
    }
    .field-input.is-invalid {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(220,38,38,.1);
    }
    .field-hint {
        font-size: .7rem;
        color: var(--neutral-400);
        margin-top: 6px;
        display: flex; align-items: center; gap: 4px;
    }
    .field-error {
        font-size: .7rem;
        color: var(--red);
        margin-top: 6px;
        display: flex; align-items: center; gap: 4px;
    }
    .locked-field {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        background: var(--neutral-100);
        border: 1.5px solid var(--neutral-200);
        border-radius: 10px;
    }
    .locked-field i { color: var(--neutral-400); font-size: .85rem; flex-shrink: 0; }
    .locked-field span { font-size: .88rem; color: var(--neutral-400); flex: 1; }
    .locked-badge {
        font-size: .6rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        background: var(--neutral-200);
        color: var(--neutral-500);
        padding: 2px 8px;
        border-radius: 5px;
        flex-shrink: 0;
    }
    .action-card {
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .action-left {
        font-size: .75rem;
        color: var(--neutral-400);
        display: flex; align-items: center; gap: 6px;
    }
    .action-right { display: flex; gap: 10px; align-items: center; }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 18px;
        background: var(--neutral-0);
        border: 1.5px solid var(--neutral-200);
        color: var(--neutral-600);
        font-size: .82rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-cancel:hover {
        border-color: var(--neutral-400);
        color: var(--neutral-900);
        background: var(--neutral-50);
    }
    .btn-save {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 22px;
        background: linear-gradient(135deg, var(--green-600), var(--green-700));
        border: none;
        color: #fff;
        font-size: .82rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 3px 12px rgba(29,148,88,.3);
    }
    .btn-save:hover {
        background: linear-gradient(135deg, var(--green-700), var(--green-800));
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(29,148,88,.35);
    }
    .btn-save:active { transform: none; }
    @media (max-width: 480px) {
        .page-shell { padding: 16px 14px 48px; }
        .hero-banner { padding: 22px 18px; }
        .hero-arc { display: none; }
        .action-card { flex-direction: column; align-items: stretch; }
        .action-right { flex-direction: column; }
        .btn-save, .btn-cancel { width: 100%; justify-content: center; }
        .action-left { display: none; }
    }
    @media (min-width: 900px) {
        .page-shell { padding: 40px 48px 64px; }
    }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-shell">
    <div class="hero-banner">
        <div class="hero-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="hero-meta">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Perbarui Profil
            </div>
            <div class="hero-title">
                Perbarui informasi akun Anda
            </div>
            <div class="hero-sub">{{ $user->email }}</div>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="o-card">
            <div class="o-card-header">
                <div class="o-card-header-icon"><i class="bi bi-person-fill"></i></div>
                <span class="o-card-header-title">Informasi Dasar</span>
            </div>
            <div class="o-card-body">
                <div class="field-group">
                    <label class="field-label">
                        Nama Lengkap <span class="field-required">*</span>
                    </label>
                    <div class="input-wrap">
                        <i class="bi bi-person input-icon"></i>
                        <input
                            type="text"
                            name="name"
                            class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap"
                            autofocus
                        >
                    </div>
                    @error('name')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Nomor HP</label>
                    <div class="input-wrap">
                        <i class="bi bi-phone input-icon"></i>
                        <input
                            type="text"
                            name="phone"
                            class="field-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="Contoh: 08123456789"
                        >
                    </div>
                    @error('phone')
                        <div class="field-error">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Opsional — digunakan untuk keperluan darurat
                    </div>
                </div>

                @if(isset($activeBooking) && $activeBooking)
                <div class="field-group">
                    <label class="field-label">Kontak Darurat</label>
                    <div class="input-wrap">
                        <i class="bi bi-telephone input-icon"></i>
                        <input
                            type="text"
                            name="emergency_contact"
                            class="field-input"
                            value="{{ old('emergency_contact', $activeBooking->emergency_contact) }}"
                            placeholder="Contoh: 08123456789 (nama kerabat)"
                        >
                    </div>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Nomor yang bisa dihubungi admin dalam keadaan darurat
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="o-card">
            <div class="o-card-header">
                <div class="o-card-header-icon"><i class="bi bi-envelope-fill"></i></div>
                <span class="o-card-header-title">Alamat Email</span>
            </div>
            <div class="o-card-body">
                <div class="field-group" style="margin-bottom:0">
                    <label class="field-label">Email Login</label>
                    <div class="locked-field">
                        <i class="bi bi-lock-fill"></i>
                        <span>{{ $user->email }}</span>
                        <span class="locked-badge">Terkunci</span>
                    </div>
                    <div class="field-hint">
                        <i class="bi bi-shield-lock"></i>
                        Email tidak dapat diubah karena digunakan sebagai identitas login OTP
                    </div>
                </div>
            </div>
        </div>

        <div class="action-card">
            <div class="action-left">
                <i class="bi bi-info-circle"></i>
                Perubahan disimpan secara langsung
            </div>
            <div class="action-right">
                <a href="{{ route('profile.show') }}" class="btn-cancel">
                    <i class="bi bi-x-lg" style="font-size:.8rem"></i>
                    Batal
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg" style="font-size:.88rem"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

</div>

</body>
</html>