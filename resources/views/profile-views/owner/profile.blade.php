<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — Urban Kost</title>
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
        --blue:        #2563eb;
        --blue-pale:   #eff6ff;
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
        max-width: 900px;
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
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color .15s;
    }
    .breadcrumb-row a:hover { color: var(--green-500); }
    .breadcrumb-row .sep { color: var(--neutral-200); font-size: .62rem; }
    .breadcrumb-row .current { color: var(--neutral-500); font-weight: 500; }
    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 36px 32px;
        margin-bottom: 20px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        min-height: 190px;
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
        width: 380px; height: 380px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none;
    }
    .hero-left {
        display: flex;
        align-items: flex-end;
        gap: 20px;
        position: relative;
        z-index: 1;
    }
    .hero-avatar {
        width: 76px; height: 76px;
        border-radius: 18px;
        background: linear-gradient(145deg, var(--green-400), var(--green-500));
        display: flex; align-items: center; justify-content: center;
        font-family: 'Fraunces', serif;
        font-size: 1.9rem; font-weight: 700;
        color: #fff;
        border: 2.5px solid rgba(255,255,255,.2);
        box-shadow: 0 6px 20px rgba(0,0,0,.25);
        flex-shrink: 0;
    }
    .hero-meta { }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 3px 11px; border-radius: 100px;
        font-size: .65rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.8);
        margin-bottom: 8px;
    }
    .hero-eyebrow .dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--green-300); flex-shrink: 0;
    }
    .hero-name {
        font-family: 'Fraunces', serif;
        font-size: clamp(1.4rem, 4vw, 2rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.1;
        letter-spacing: -.025em;
    }
    .hero-email {
        font-size: .8rem;
        color: rgba(255,255,255,.55);
        margin-top: 5px;
        display: flex; align-items: center; gap: 5px;
    }
    .hero-right { position: relative; z-index: 1; flex-shrink: 0; }
    .edit-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        background: rgba(255,255,255,.12);
        border: 1.5px solid rgba(255,255,255,.25);
        color: #fff;
        font-family: 'Instrument Sans', sans-serif;
        font-size: .8rem;
        font-weight: 600;
        border-radius: 11px;
        text-decoration: none;
        transition: all .2s;
    }
    .edit-btn:hover {
        background: rgba(255,255,255,.22);
        border-color: rgba(255,255,255,.4);
        color: #fff;
        transform: translateY(-1px);
    }
    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--green-50);
        border: 1px solid var(--green-200);
        color: var(--green-800);
        padding: 13px 18px;
        border-radius: 12px;
        font-size: .83rem;
        font-weight: 500;
        margin-bottom: 18px;
    }
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 18px;
    }
    .stat-card {
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--shadow-sm);
        transition: border-color .25s, box-shadow .3s, transform .25s;
    }
    .stat-card:hover {
        border-color: var(--green-300);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 42px; height: 42px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .stat-icon.green { background: var(--green-100); color: var(--green-700); }
    .stat-icon.amber { background: var(--accent-pale); color: #92400e; }
    .stat-icon.blue  { background: var(--blue-pale); color: var(--blue); }
    .stat-label {
        font-size: .67rem; color: var(--neutral-400);
        font-weight: 600; text-transform: uppercase;
        letter-spacing: .06em; margin-bottom: 3px;
    }
    .stat-value {
        font-family: 'Fraunces', serif;
        font-size: .95rem; font-weight: 700; color: var(--neutral-900);
        line-height: 1.2;
    }
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    .o-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .o-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .o-card-header-title {
        font-size: .68rem; font-weight: 600;
        color: var(--neutral-600);
        display: flex; align-items: center; gap: 7px;
        text-transform: uppercase; letter-spacing: .07em;
    }
    .o-card-header-title i { color: var(--green-500); }
    .field-stack { display: flex; flex-direction: column; padding: 20px; gap: 0; }
    .field-item { padding: 13px 0; border-bottom: 1px solid var(--neutral-100); }
    .field-item:last-child { border-bottom: none; }
    .field-label {
        font-size: .67rem;
        color: var(--neutral-400);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 5px;
    }
    .field-value {
        font-size: .88rem;
        font-weight: 600;
        color: var(--neutral-900);
    }
    .field-value.muted { color: var(--neutral-400); font-weight: 400; font-style: italic; }
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .68rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 100px;
    }
    .status-pill.active   { background: var(--green-100); color: var(--green-700); border: 1px solid var(--green-200); }
    .status-pill.inactive { background: var(--red-pale);  color: var(--red);       border: 1px solid #fecaca; }
    .status-pill::before {
        content: '';
        width: 5px; height: 5px; border-radius: 50%;
        background: currentColor;
    }
    .security-card {
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 16px;
    }
    .otp-block {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(90deg, var(--green-50), var(--neutral-0));
        border-top: 1px solid var(--green-100);
    }
    .otp-icon {
        width: 42px; height: 42px;
        border-radius: 11px;
        background: var(--green-100);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        color: var(--green-700); font-size: 1rem;
    }
    .otp-title { font-size: .85rem; font-weight: 600; color: var(--neutral-900); margin-bottom: 2px; }
    .otp-sub   { font-size: .72rem; color: var(--neutral-500); }
    .otp-badge {
        margin-left: auto;
        background: var(--green-100);
        color: var(--green-700);
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 6px;
        flex-shrink: 0;
        border: 1px solid var(--green-200);
    }
    .activity-card {
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .timeline { display: flex; flex-direction: column; padding: 8px 20px 20px; }
    .tl-item {
        display: flex;
        gap: 14px;
        padding-bottom: 18px;
        position: relative;
    }
    .tl-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 14px; top: 28px; bottom: 0;
        width: 1.5px;
        background: var(--neutral-100);
    }
    .tl-dot {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: .68rem;
        position: relative; z-index: 1;
    }
    .tl-dot.green { background: var(--green-100); color: var(--green-700); border: 1.5px solid var(--green-200); }
    .tl-dot.gray  { background: var(--neutral-100); color: var(--neutral-500); border: 1.5px solid var(--neutral-200); }
    .tl-title { font-size: .82rem; font-weight: 600; color: var(--neutral-900); margin-bottom: 2px; padding-top: 4px; }
    .tl-time  { font-size: .7rem; color: var(--neutral-400); }
    @media (max-width: 700px) {
        .two-col { grid-template-columns: 1fr; }
        .stats-row { grid-template-columns: 1fr 1fr; }
        .hero-banner { flex-direction: column; align-items: flex-start; min-height: auto; padding: 24px 20px; }
        .hero-arc { display: none; }
    }
    @media (max-width: 480px) {
        .stats-row { grid-template-columns: 1fr; }
        .page-shell { padding: 16px 14px 48px; }
        .hero-name { font-size: 1.3rem; }
        .hero-avatar { width: 58px; height: 58px; font-size: 1.5rem; }
    }
    @media (min-width: 900px) {
        .page-shell { padding: 40px 48px 64px; }
    }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@include('partials.owner-sidebar')

<div class="page-shell">
    @if(session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="hero-meta">
                <div class="hero-eyebrow">
                    <span class="dot"></span>
                    Owner
                </div>
                <div class="hero-name">{{ $user->name }}</div>
                <div class="hero-email">
                    <i class="bi bi-envelope-fill" style="font-size:.72rem"></i>
                    {{ $user->email }}
                </div>
            </div>
        </div>
        <div class="hero-right">
            <a href="{{ route('profile.edit') }}" class="edit-btn">
                <i class="bi bi-pencil-square"></i>
                Edit Profil
            </a>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-calendar3-week"></i></div>
            <div>
                <div class="stat-label">Bergabung Sejak</div>
                <div class="stat-value">{{ $user->created_at->locale('id')->isoFormat('D MMM Y') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Lama Bergabung</div>
                <div class="stat-value">{{ $user->created_at->locale('id')->diffForHumans(null, true) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="stat-label">Status Akun</div>
                <div class="stat-value">
                    @if($user->is_active)
                        <span class="status-pill active">Aktif</span>
                    @else
                        <span class="status-pill inactive">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="two-col">
        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title">
                    <i class="bi bi-person-lines-fill"></i>Informasi Akun
                </span>
            </div>
            <div class="field-stack">
                <div class="field-item">
                    <div class="field-label">Nama Lengkap</div>
                    <div class="field-value">{{ $user->name }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Email</div>
                    <div class="field-value">{{ $user->email }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Nomor HP</div>
                    <div class="field-value {{ $user->phone ? '' : 'muted' }}">
                        {{ $user->phone ?? 'Belum diisi' }}
                    </div>
                </div>
                <div class="field-item">
                    <div class="field-label">Role</div>
                    <div class="field-value">Owner</div>
                </div>
            </div>
        </div>

        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title">
                    <i class="bi bi-info-circle-fill"></i>Detail Akun
                </span>
            </div>
            <div class="field-stack">
                <div class="field-item">
                    <div class="field-label">Status</div>
                    <div class="field-value">
                        @if($user->is_active)
                            <span class="status-pill active">Aktif</span>
                        @else
                            <span class="status-pill inactive">Nonaktif</span>
                        @endif
                    </div>
                </div>
                <div class="field-item">
                    <div class="field-label">Tanggal Daftar</div>
                    <div class="field-value">{{ $user->created_at->locale('id')->isoFormat('D MMM Y') }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Terakhir Diperbarui</div>
                    <div class="field-value">{{ $user->updated_at->locale('id')->isoFormat('D MMM Y') }}</div>
                </div>
                <div class="field-item">
                    <div class="field-label">Dibuat Oleh</div>
                    <div class="field-value {{ $user->creator ? '' : 'muted' }}">
                        {{ $user->creator->name ?? 'Sistem' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="security-card">
        <div class="o-card-header">
            <span class="o-card-header-title">
                <i class="bi bi-shield-lock-fill"></i>Keamanan Login
            </span>
        </div>
        <div class="otp-block">
            <div class="otp-icon"><i class="bi bi-envelope-check-fill"></i></div>
            <div>
                <div class="otp-title">Verifikasi OTP via Email</div>
                <div class="otp-sub">
                    Kode OTP dikirim ke <strong>{{ $user->email }}</strong> setiap kali login
                </div>
            </div>
            <div class="otp-badge">Aktif</div>
        </div>
    </div>
</div>
</body>
</html>