<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pajak PPh Final — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
        --blue:        #2563eb;
        --blue-pale:   #eff6ff;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
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
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
            radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%);
    }
    .owner-bg-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
        background-size: 60px 60px;
    }
    .owner-page { min-height: 100vh; display: flex; flex-direction: column; }
    .owner-main { flex: 1; padding: 24px 0 56px; width: 100%; }
    .owner-inner { max-width: 1200px; margin: 0 auto; padding: 0 16px; width: 100%; }
    @media (min-width: 640px) { .owner-inner { padding: 0 24px; } }
    @media (min-width: 1024px) { .owner-inner { padding: 0 32px; } .owner-main { padding: 32px 0 56px; } }

    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 70%, var(--green-500) 100%);
        padding: 24px 20px;
        margin-bottom: 20px;
        box-shadow: 0 8px 32px rgba(14,63,39,.3);
    }
    @media (min-width: 640px) { .hero-banner { padding: 28px 28px; } }
    .hero-top { display: flex; flex-direction: column; gap: 16px; }
    @media (min-width: 900px) { .hero-top { flex-direction: row; align-items: flex-start; justify-content: space-between; gap: 20px; } }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        padding: 5px 12px; border-radius: 100px;
        font-size: .68rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase;
        color: rgba(255,255,255,.82); margin-bottom: 12px;
    }
    .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-title {
        font-family: 'Fraunces', serif; font-weight: 700;
        font-size: clamp(1.3rem, 4vw, 1.85rem);
        color: #fff; line-height: 1.2; letter-spacing: -.02em; margin-bottom: 6px;
    }
    .hero-sub { font-size: .85rem; color: rgba(255,255,255,.62); max-width: 480px; line-height: 1.6; }
    .hero-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .hero-form { display: flex; flex-wrap: wrap; gap: 8px; margin: 0; }
    .year-select {
        padding: 8px 12px; font-size: .78rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        border: 1.5px solid rgba(255,255,255,.25); border-radius: 9px;
        background: rgba(255,255,255,.12); color: #fff; cursor: pointer; outline: none;
    }
    .year-select option { background: var(--neutral-900); color: var(--neutral-100); }
    .btn-hero {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; font-size: .78rem; font-weight: 600;
        border-radius: 9px; text-decoration: none; border: none;
        cursor: pointer; white-space: nowrap;
    }
    .btn-hero-ghost { background: rgba(255,255,255,.12); border: 1.5px solid rgba(255,255,255,.25); color: rgba(255,255,255,.9); }
    .btn-hero-solid { background: #fff; color: var(--green-800); }

    .alert-strip {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 13px 15px; border-radius: 11px;
        margin-bottom: 14px; font-size: .82rem; line-height: 1.55;
        border: 1px solid;
    }
    .alert-strip.warn { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
    .alert-strip.safe { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
    .alert-strip.danger { background: var(--red-pale); border-color: rgba(220,38,38,.25); color: #7f1d1d; }
    .alert-strip i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }

    .info-strip {
        background: var(--blue-pale); border: 1px solid rgba(37,99,235,.18);
        border-radius: 11px; padding: 13px 15px;
        font-size: .8rem; color: #1e3a8a; line-height: 1.6;
        margin-bottom: 18px; display: flex; gap: 10px; align-items: flex-start;
    }
    .info-strip i { font-size: 1rem; margin-top: 1px; flex-shrink: 0; }

    .stats-grid { display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 20px; }
    @media (min-width: 560px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 900px) { .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; } }
    .stat-card {
        border-radius: 15px; padding: 18px;
        background: var(--neutral-0); border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
    }
    .stat-icon-wrap {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem; margin-bottom: 12px;
    }
    .stat-value {
        font-family: 'Fraunces', serif; font-size: 1.55rem; font-weight: 700;
        color: var(--neutral-900); line-height: 1.1; margin-bottom: 4px; letter-spacing: -.02em;
        word-break: break-word;
    }
    .stat-label { font-size: .68rem; font-weight: 600; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .06em; }
    .stat-sub { font-size: .72rem; color: var(--neutral-500); margin-top: 5px; }

    .o-card { background: var(--neutral-0); border-radius: 15px; border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden; margin-bottom: 18px; }
    .o-card-header {
        padding: 14px 18px; border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
        flex-wrap: wrap;
    }
    .o-card-header-title {
        font-size: .72rem; font-weight: 600; color: var(--neutral-600);
        display: flex; align-items: center; gap: 7px;
        text-transform: uppercase; letter-spacing: .06em;
    }
    .o-card-header-title i { color: var(--green-500); }

    .prog-card { background: var(--neutral-0); border: 1px solid var(--green-100); border-radius: 15px; padding: 18px; box-shadow: var(--shadow-md); margin-bottom: 18px; }
    .prog-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 10px; margin-bottom: 12px; }
    .prog-title { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: var(--neutral-900); }
    .prog-title-sub { font-size: .72rem; color: var(--neutral-500); margin-top: 2px; }
    .prog-pct { font-family: 'Fraunces', serif; font-size: 1.7rem; line-height: 1; }
    .prog-wrap { background: var(--neutral-100); border-radius: 100px; height: 12px; overflow: hidden; border: 1px solid var(--green-100); }
    .prog-fill { height: 100%; border-radius: 100px; display: flex; align-items: center; padding-left: 9px; font-size: .6rem; font-weight: 700; color: #fff; }
    .prog-marks { display: flex; justify-content: space-between; margin-top: 6px; font-size: .65rem; color: var(--neutral-400); flex-wrap: wrap; gap: 4px; }
    .prog-stats { display: grid; grid-template-columns: 1fr; gap: 10px; margin-top: 16px; }
    @media (min-width: 560px) { .prog-stats { grid-template-columns: repeat(2, 1fr); } }
    .prog-stat { background: var(--neutral-50); border: 1px solid var(--green-100); border-radius: 10px; padding: 12px; text-align: center; }
    .prog-stat-val { font-family: 'Fraunces', serif; font-size: .95rem; color: var(--neutral-900); word-break: break-word; }
    .prog-stat-lbl { font-size: .62rem; font-weight: 700; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .04em; margin-top: 4px; }

    .calc-card { padding: 18px; }
    .calc-row { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 1px solid var(--neutral-100); font-size: .82rem; gap: 12px; flex-wrap: wrap; }
    .calc-row:last-child { border-bottom: none; }
    .calc-row-lbl { color: var(--neutral-500); }
    .calc-row-val { font-weight: 700; color: var(--neutral-800); text-align: right; }
    .calc-final { margin-top: 12px; padding: 14px 16px; border-radius: 11px; background: var(--green-50); border: 1.5px solid var(--green-300); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
    .calc-final-lbl { font-size: .8rem; font-weight: 600; color: var(--neutral-700); }
    .calc-final-val { font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 700; color: var(--green-700); }

    .two-col { display: grid; grid-template-columns: 1fr; gap: 18px; margin-bottom: 18px; }
    @media (min-width: 900px) { .two-col { grid-template-columns: 1fr 1fr; } }

    .table-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 540px; }
    .data-table th {
        padding: 10px 12px; font-size: .62rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .06em; color: var(--neutral-400);
        background: var(--neutral-50); border-bottom: 1px solid var(--neutral-100);
        text-align: left; white-space: nowrap;
    }
    .data-table td { padding: 10px 12px; border-bottom: 1px solid var(--neutral-100); font-size: .8rem; color: var(--neutral-600); vertical-align: middle; }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tfoot td { padding: 10px 12px; font-size: .8rem; font-weight: 700; background: var(--green-50); border-top: 2px solid var(--green-200); }
    .data-table tr:hover td { background: var(--green-50); }
    .data-table tr.is-current td { background: var(--amber-pale); }

    .badge { display: inline-flex; align-items: center; gap: 3px; padding: 2px 9px; border-radius: 100px; font-size: .62rem; font-weight: 700; white-space: nowrap; }
    .badge-green { background: var(--green-100); color: var(--green-700); }
    .badge-amber { background: var(--amber-pale); color: #92400e; border: 1px solid rgba(245,158,11,.25); }
    .badge-neu   { background: var(--neutral-100); color: var(--neutral-500); border: 1px solid var(--neutral-200); }

    .prog-mini { background: var(--neutral-200); border-radius: 100px; height: 5px; overflow: hidden; min-width: 36px; }
    .prog-mini-fill { height: 100%; border-radius: 100px; }

    .sim-shell {
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 100%);
        border-radius: 15px; overflow: hidden; box-shadow: 0 8px 32px rgba(14,63,39,.28);
        margin-bottom: 18px;
    }
    .sim-head { padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 12px; }
    .sim-head-ico { width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,.12); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; flex-shrink: 0; }
    .sim-head-title { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; color: #fff; }
    .sim-head-sub { font-size: .73rem; color: rgba(255,255,255,.5); margin-top: 2px; }
    .sim-body { display: grid; grid-template-columns: 1fr; }
    @media (min-width: 760px) { .sim-body { grid-template-columns: 1fr 1fr; } }
    .sim-panel { padding: 20px; }
    .sim-panel-left { border-bottom: 1px solid rgba(255,255,255,.08); }
    @media (min-width: 760px) { .sim-panel-left { border-bottom: none; border-right: 1px solid rgba(255,255,255,.08); } }
    .sim-label { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.45); margin-bottom: 8px; }
    .sim-inp {
        width: 100%; background: rgba(255,255,255,.08); border: 1.5px solid rgba(255,255,255,.15);
        border-radius: 9px; color: #fff; font-size: .88rem; font-weight: 600;
        padding: 10px 13px; outline: none; font-family: 'Instrument Sans', sans-serif;
    }
    .sim-inp::placeholder { color: rgba(255,255,255,.22); }
    input[type=range].sim-range { width: 100%; margin: 10px 0 4px; accent-color: var(--green-400); }
    .sim-range-labels { display: flex; justify-content: space-between; font-size: .6rem; color: rgba(255,255,255,.3); flex-wrap: wrap; gap: 4px; }
    .sim-calc-btn {
        width: 100%; margin-top: 14px; padding: 11px; background: var(--green-500); color: #fff; border: none;
        border-radius: 9px; font-size: .83rem; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        font-family: 'Instrument Sans', sans-serif;
    }
    .sim-calc-btn:hover { background: var(--green-600); }
    .sim-facts { margin-top: 18px; }
    .fact-item { display: flex; gap: 7px; font-size: .72rem; color: rgba(255,255,255,.5); margin-bottom: 8px; line-height: 1.55; }
    .fact-item i { color: var(--green-300); flex-shrink: 0; margin-top: 2px; }

    .result-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 180px; text-align: center; color: rgba(255,255,255,.28); }
    .result-empty i { font-size: 2rem; margin-bottom: 10px; opacity: .5; }
    .result-empty p { font-size: .78rem; line-height: 1.6; }

    .result-cards { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 12px; }
    .r-card { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1); border-radius: 9px; padding: 11px 13px; }
    .r-card-val { font-family: 'Fraunces', serif; font-size: 1rem; color: #fff; word-break: break-word; }
    .r-card-lbl { font-size: .6rem; color: rgba(255,255,255,.38); margin-top: 3px; text-transform: uppercase; letter-spacing: .04em; }
    .r-message { padding: 10px 13px; border-radius: 8px; font-size: .75rem; font-weight: 500; margin-top: 10px; line-height: 1.5; }
    .r-msg-ok { background: rgba(42,179,107,.18); color: #86efac; }
    .r-msg-warn { background: rgba(245,158,11,.18); color: #fcd34d; }
    .r-msg-danger { background: rgba(220,38,38,.18); color: #fca5a5; }
    .r-breakdown { margin-top: 12px; }
    .r-breakdown-title { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.3); margin-bottom: 8px; }
    .r-row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,.06); font-size: .74rem; gap: 8px; }
    .r-row:last-child { border-bottom: none; }
    .r-row-lbl { color: rgba(255,255,255,.48); }
    .r-row-val { font-weight: 600; color: #fff; text-align: right; }

    .footer-note { padding: 10px 16px; background: var(--neutral-50); border-top: 1px solid var(--green-100); font-size: .7rem; color: var(--neutral-400); line-height: 1.6; }

    @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@include('partials.owner-sidebar')

<div class="owner-page">
<main class="owner-main">
<div class="owner-inner">

    <div class="hero-banner">
        <div class="hero-top">
            <div>
                <div class="hero-eyebrow"><span class="dot"></span>Pajak Tahunan</div>
                <h1 class="hero-title">Pajak PPh Final 0,5%</h1>
                <p class="hero-sub">Dihitung dari total pendapatan satu tahun (1 Januari sampai 31 Desember), sesuai PP No. 55 Tahun 2022 untuk Wajib Pajak Orang Pribadi.</p>
            </div>
            <div class="hero-actions no-print">
                <form method="GET" action="{{ route('owner.tax.index') }}" class="hero-form">
                    <select name="year" class="year-select" onchange="this.form.submit()">
                        @foreach($years as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('owner.tax.export') }}?year={{ $year }}&format=excel" class="btn-hero btn-hero-ghost">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                </a>
                <a href="{{ route('owner.tax.export') }}?year={{ $year }}&format=pdf" class="btn-hero btn-hero-solid">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

    @if($hitungOP['bebas'])
    <div class="alert-strip safe">
        <i class="bi bi-check-circle-fill"></i>
        <span>Pendapatan tahun {{ $year }} sebesar Rp {{ number_format($omzetTahunIni, 0, ',', '.') }} ({{ $tpct }}% dari batas Rp 500 juta). Belum melewati batas, sehingga tidak ada pajak yang harus dibayar tahun ini.</span>
    </div>
    @else
    <div class="alert-strip warn">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>Pendapatan tahun {{ $year }} sebesar Rp {{ number_format($omzetTahunIni, 0, ',', '.') }} telah melewati batas Rp 500 juta. Pajak 0,5% dikenakan pada kelebihannya, yaitu Rp {{ number_format($hitungOP['dasar_kena_pajak'], 0, ',', '.') }}, sehingga pajak yang harus dibayar sekitar Rp {{ number_format($hitungOP['pph'], 0, ',', '.') }}.</span>
    </div>
    @endif

    @if($hitungOP['melebihi_umkm'])
    <div class="alert-strip danger">
        <i class="bi bi-exclamation-octagon-fill"></i>
        <span>Pendapatan telah melewati Rp 4,8 miliar per tahun. Tarif 0,5% untuk usaha kecil sudah tidak berlaku. Sebaiknya berkonsultasi dengan konsultan pajak.</span>
    </div>
    @endif

    <div class="info-strip">
        <i class="bi bi-info-circle-fill"></i>
        <span>Sebagai usaha milik pribadi, Urban Kost masuk kategori Wajib Pajak Orang Pribadi. Pendapatan sampai Rp 500 juta per tahun tidak dikenai pajak. Jika melebihi itu, pajak 0,5% hanya dihitung dari kelebihannya saja.</span>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: var(--green-100); color: var(--green-600);"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">Rp {{ number_format($omzetTahunIni / 1000000, 1) }} jt</div>
            <div class="stat-label">Total Pendapatan {{ $year }}</div>
            <div class="stat-sub">1 Januari &ndash; 31 Desember {{ $year }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: {{ $tpct >= 80 ? 'var(--red-pale)' : 'var(--blue-pale)' }}; color: {{ $tpct >= 80 ? 'var(--red)' : 'var(--blue)' }};"><i class="bi bi-speedometer2"></i></div>
            <div class="stat-value" style="color: {{ $tpct >= 80 ? 'var(--red)' : 'var(--blue)' }};">{{ $tpct }}%</div>
            <div class="stat-label">Dari Batas Rp 500 Juta</div>
            <div class="stat-sub">Sisa Rp {{ number_format($remaining / 1000000, 1) }} jt</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: {{ $hitungOP['pph'] > 0 ? 'var(--amber-pale)' : 'var(--green-100)' }}; color: {{ $hitungOP['pph'] > 0 ? '#92400e' : 'var(--green-600)' }};"><i class="bi bi-calculator-fill"></i></div>
            <div class="stat-value" style="color: {{ $hitungOP['pph'] > 0 ? '#92400e' : 'var(--green-600)' }};">Rp {{ number_format($hitungOP['pph'], 0, ',', '.') }}</div>
            <div class="stat-label">Pajak yang Harus Dibayar</div>
            <div class="stat-sub">Tahun {{ $year }}</div>
        </div>
    </div>

    <div class="prog-card">
        <div class="prog-header">
            <div>
                <div class="prog-title">Pendapatan Tahunan vs Batas Bebas Pajak</div>
                <div class="prog-title-sub">Batas bebas pajak: Rp 500.000.000 per tahun</div>
            </div>
            <div class="prog-pct" style="color: {{ $tpct >= 80 ? 'var(--red)' : ($tpct >= 50 ? 'var(--amber)' : 'var(--green-600)') }};">{{ $tpct }}%</div>
        </div>
        <div class="prog-wrap">
            <div class="prog-fill" style="width: {{ $tpct }}%; background: {{ $tpct >= 100 ? 'var(--red)' : ($tpct >= 80 ? 'var(--amber)' : 'var(--green-500)') }};">
                @if($tpct > 10) {{ $tpct }}% @endif
            </div>
        </div>
        <div class="prog-marks">
            <span>Rp 0</span><span>Rp 250jt</span><span style="color: {{ $tpct >= 80 ? 'var(--red)' : 'inherit' }}; font-weight: {{ $tpct >= 80 ? '700' : '400' }};">Rp 500jt</span>
        </div>
        <div class="prog-stats">
            <div class="prog-stat">
                <div class="prog-stat-val" style="color: var(--green-600);">Rp {{ number_format($omzetTahunIni, 0, ',', '.') }}</div>
                <div class="prog-stat-lbl">Pendapatan {{ $year }}</div>
            </div>
            <div class="prog-stat">
                <div class="prog-stat-val" style="color: {{ $tpct >= 80 ? 'var(--red)' : 'var(--blue)' }};">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                <div class="prog-stat-lbl">Sisa Sampai Batas</div>
            </div>
        </div>
    </div>

    <div class="o-card">
        <div class="o-card-header">
            <span class="o-card-header-title"><i class="bi bi-receipt"></i>Rincian Perhitungan Pajak Tahun {{ $year }}</span>
        </div>
        <div class="calc-card">
            <div class="calc-row"><span class="calc-row-lbl">Total pendapatan tahun {{ $year }}</span><span class="calc-row-val">Rp {{ number_format($omzetTahunIni, 0, ',', '.') }}</span></div>
            <div class="calc-row"><span class="calc-row-lbl">Batas bebas pajak</span><span class="calc-row-val" style="color: var(--green-600);">&minus; Rp {{ number_format(min($omzetTahunIni, 500000000), 0, ',', '.') }}</span></div>
            <div class="calc-row"><span class="calc-row-lbl">Jumlah yang dikenai pajak</span><span class="calc-row-val">Rp {{ number_format($hitungOP['dasar_kena_pajak'], 0, ',', '.') }}</span></div>
            <div class="calc-row"><span class="calc-row-lbl">Tarif pajak</span><span class="calc-row-val">0,5%</span></div>
            <div class="calc-final">
                <span class="calc-final-lbl">Pajak yang Harus Dibayar</span>
                <span class="calc-final-val">Rp {{ number_format($hitungOP['pph'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="two-col">
        <div class="o-card" style="margin-bottom: 0;">
            <div class="o-card-header">
                <span class="o-card-header-title"><i class="bi bi-bar-chart-fill"></i>Pendapatan Bulanan {{ $year }}</span>
            </div>
            <div style="padding: 14px;">
                <canvas id="chartOmzet" height="200"></canvas>
            </div>
            <div class="footer-note">Grafik ini menunjukkan asal pendapatan per bulan. Pajak tetap dihitung dari total satu tahun, bukan per bulan.</div>
        </div>

        <div class="o-card" style="margin-bottom: 0;">
            <div class="o-card-header">
                <span class="o-card-header-title"><i class="bi bi-calendar-event-fill"></i>Bulan Ini</span>
                <span class="badge badge-amber">{{ now()->format('F Y') }}</span>
            </div>
            <div style="padding: 16px; display: flex; flex-direction: column; gap: 8px;">
                <div style="background: var(--green-50); border: 1px solid var(--green-200); border-radius: 11px; padding: 16px; text-align: center; margin-bottom: 4px;">
                    <div style="font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--green-600); margin-bottom: 5px;">Pendapatan {{ now()->format('F Y') }}</div>
                    <div style="font-family: 'Fraunces', serif; font-size: 1.7rem; font-weight: 700; color: var(--green-700); line-height: 1; word-break: break-word;">Rp {{ number_format($curRev, 0, ',', '.') }}</div>
                    <div style="font-size: .7rem; color: var(--green-500); margin-top: 5px;">{{ $curCount }} invoice lunas</div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 9px 12px; background: var(--neutral-50); border: 1px solid var(--green-100); border-radius: 9px; font-size: .78rem; gap: 8px; flex-wrap: wrap;">
                    <span style="color: var(--neutral-600);"><i class="bi bi-calendar-range"></i> Jenis Pelaporan</span>
                    <span style="font-weight: 700; color: var(--neutral-800);">SPT Tahunan</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 9px 12px; background: {{ $hitungOP['pph'] > 0 ? 'var(--red-pale)' : 'var(--neutral-50)' }}; border: 1px solid {{ $hitungOP['pph'] > 0 ? 'rgba(220,38,38,.18)' : 'var(--green-100)' }}; border-radius: 9px; font-size: .78rem; gap: 8px; flex-wrap: wrap;">
                    <span style="color: {{ $hitungOP['pph'] > 0 ? 'var(--red)' : 'var(--neutral-600)' }};"><i class="bi bi-alarm-fill"></i> Batas Lapor SPT Tahunan</span>
                    <span style="font-weight: 700; color: {{ $hitungOP['pph'] > 0 ? 'var(--red)' : 'var(--neutral-800)' }};">{{ $taxDeadline }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 9px 12px; background: var(--neutral-50); border: 1px solid var(--green-100); border-radius: 9px; font-size: .78rem; gap: 8px; flex-wrap: wrap;">
                    <span style="color: var(--neutral-600);"><i class="bi bi-percent"></i> Tarif Pajak</span>
                    <span style="font-weight: 700; color: var(--neutral-800);">0,5%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="o-card">
        <div class="o-card-header">
            <span class="o-card-header-title"><i class="bi bi-table"></i>Pendapatan Per Bulan {{ $year }}</span>
            <span class="badge badge-green">Total: Rp {{ number_format($omzetTahunIni / 1000000, 1) }} jt</span>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th style="text-align: right;">Pendapatan (Rp)</th>
                        <th style="text-align: center;">Invoice Lunas</th>
                        <th style="text-align: center;">Belum Bayar</th>
                        <th style="text-align: right;">Kumulatif (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                @php $kumulatif = 0; @endphp
                @foreach($monthlyData as $md)
                @php $kumulatif += $md['revenue']; @endphp
                <tr>
                    <td style="font-weight: 600; color: var(--neutral-800);">{{ $md['month'] }}</td>
                    <td style="text-align: right; font-weight: 600;">{{ $md['revenue'] > 0 ? 'Rp ' . number_format($md['revenue'], 0, ',', '.') : '—' }}</td>
                    <td style="text-align: center;">
                        @if($md['paid_count'] > 0)<span class="badge badge-green">{{ $md['paid_count'] }}</span>@else<span class="badge badge-neu">0</span>@endif
                    </td>
                    <td style="text-align: center;">
                        @if($md['unpaid_count'] > 0)<span class="badge badge-amber">{{ $md['unpaid_count'] }}</span>@else<span class="badge badge-neu">0</span>@endif
                    </td>
                    <td style="text-align: right; font-size: .77rem; color: var(--neutral-500);">Rp {{ number_format($kumulatif, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total {{ $year }}</td>
                        <td style="text-align: right; color: var(--green-600);">Rp {{ number_format($omzetTahunIni, 0, ',', '.') }}</td>
                        <td colspan="2" style="text-align: center;">—</td>
                        <td style="text-align: right;">Rp {{ number_format($omzetTahunIni, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="o-card">
        <div class="o-card-header">
            <span class="o-card-header-title"><i class="bi bi-clock-history"></i>Riwayat 5 Tahun Terakhir</span>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th style="text-align: right;">Pendapatan (Rp)</th>
                        <th style="text-align: center;">Dari Batas Rp 500jt</th>
                        <th style="text-align: right;">Pajak (Rp)</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($historiTahunan as $h)
                @php
                    $hIsOver = $h['tpct'] >= 100;
                    $hIsWarn = $h['tpct'] >= 80 && !$hIsOver;
                    $hProgClr = $hIsOver ? 'var(--red)' : ($hIsWarn ? 'var(--amber)' : 'var(--green-500)');
                @endphp
                <tr class="{{ $h['is_current'] ? 'is-current' : '' }}">
                    <td style="font-weight: 700; color: var(--neutral-900);">{{ $h['year'] }}</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($h['omzet'], 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; align-items: center; gap: 7px; justify-content: center;">
                            <div class="prog-mini" style="flex: 1; max-width: 70px;">
                                <div class="prog-mini-fill" style="width: {{ $h['tpct'] }}%; background: {{ $hProgClr }};"></div>
                            </div>
                            <span style="font-size: .7rem; font-weight: 700; color: {{ $hProgClr }};">{{ $h['tpct'] }}%</span>
                        </div>
                    </td>
                    <td style="text-align: right; color: {{ $h['pph_op'] > 0 ? 'var(--amber)' : 'var(--green-600)' }}; font-weight: 600;">
                        {{ $h['pph_op'] > 0 ? 'Rp ' . number_format($h['pph_op'], 0, ',', '.') : 'Bebas' }}
                    </td>
                    <td style="text-align: center;">
                        @if($h['bebas_op'])
                            <span class="badge badge-green"><i class="bi bi-check-circle-fill"></i> Bebas Pajak</span>
                        @else
                            <span class="badge badge-amber"><i class="bi bi-exclamation-circle-fill"></i> Ada Pajak</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer-note">Setiap tahun dihitung tersendiri. Batas Rp 500 juta berlaku kembali dari awal setiap tahun pajak baru.</div>
    </div>

    <div class="sim-shell">
        <div class="sim-head">
            <div class="sim-head-ico"><i class="bi bi-calculator-fill"></i></div>
            <div>
                <div class="sim-head-title">Simulasi Hitung Pajak</div>
                <div class="sim-head-sub">Masukkan perkiraan pendapatan satu tahun untuk melihat estimasi pajak</div>
            </div>
        </div>
        <div class="sim-body">
            <div class="sim-panel sim-panel-left">
                <div class="sim-label">Total Pendapatan 1 Tahun (Rp)</div>
                <input type="text" id="simInp" class="sim-inp" placeholder="Contoh: 600.000.000"
                    oninput="syncSlider(this.value)" onkeydown="if(event.key==='Enter') hitungPPh()">
                <input type="range" id="simRange" class="sim-range" min="0" max="1000000000" step="5000000" value="0" oninput="syncInput(this.value)">
                <div class="sim-range-labels"><span>Rp 0</span><span>Rp 250jt</span><span>Rp 500jt</span><span>Rp 750jt</span><span>Rp 1M</span></div>

                <button class="sim-calc-btn" onclick="hitungPPh()"><i class="bi bi-calculator-fill"></i> Hitung Pajak</button>

                <div class="sim-facts">
                    <div class="sim-label">Yang Perlu Diketahui</div>
                    <div class="fact-item"><i class="bi bi-check-circle-fill"></i><span>Pendapatan sampai Rp 500 juta per tahun tidak dikenai pajak</span></div>
                    <div class="fact-item"><i class="bi bi-check-circle-fill"></i><span>Pajak 0,5% hanya dihitung dari kelebihan di atas Rp 500 juta</span></div>
                    <div class="fact-item"><i class="bi bi-check-circle-fill"></i><span>Berlaku sampai pendapatan Rp 4,8 miliar per tahun</span></div>
                    <div class="fact-item"><i class="bi bi-check-circle-fill"></i><span>Dilaporkan lewat SPT Tahunan, paling lambat 31 Maret tahun berikutnya</span></div>
                </div>
            </div>

            <div class="sim-panel" id="simResult">
                <div class="result-empty" id="emptyState">
                    <i class="bi bi-calculator"></i>
                    <p>Masukkan jumlah pendapatan, lalu klik<br>Hitung Pajak untuk melihat hasilnya.</p>
                </div>
                <div id="resultContent" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>
</main>
</div>

<script>
    Chart.defaults.font.family = "'Instrument Sans', sans-serif";
    Chart.defaults.font.size = 11;

    const months   = @json(array_column($monthlyData, 'month_short'));
    const revenues = @json(array_column($monthlyData, 'revenue'));

    new Chart(document.getElementById('chartOmzet'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenues,
                backgroundColor: 'rgba(42,179,107,.7)',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt' },
                    grid: { color: 'rgba(15,31,24,.04)' }
                }
            }
        }
    });

    const THRESH  = 500000000;
    const CEILING = 4800000000;

    function syncSlider(val) {
        const n = parseFloat(val.replace(/\D/g, '')) || 0;
        document.getElementById('simRange').value = Math.min(n, 1000000000);
    }
    function syncInput(val) {
        document.getElementById('simInp').value = parseInt(val).toLocaleString('id-ID');
    }
    function fmt(v) { return 'Rp ' + Math.round(v).toLocaleString('id-ID'); }
    function pct(v, t) { return t > 0 ? Math.min(Math.round((v / t) * 1000) / 10, 100) : 0; }

    function hitungPPh() {
        const rawVal = document.getElementById('simInp').value.replace(/\D/g, '');
        const omzet = parseFloat(rawVal) || 0;
        if (!omzet) { alert('Masukkan jumlah pendapatan terlebih dahulu.'); return; }

        let tax = 0, taxable = 0;
        if (omzet > THRESH) {
            taxable = omzet - THRESH;
            tax = taxable * 0.005;
        }

        const tpctNow = pct(omzet, THRESH);
        const sisa = Math.max(0, THRESH - omzet);
        const progColor = tpctNow >= 100 ? '#dc2626' : (tpctNow >= 80 ? '#f59e0b' : '#2ab36b');

        let msg = '', msgClass = '';
        if (omzet > CEILING) {
            msg = 'Pendapatan melewati Rp 4,8 miliar per tahun. Tarif 0,5% untuk usaha kecil sudah tidak berlaku. Sebaiknya berkonsultasi dengan konsultan pajak.';
            msgClass = 'r-msg-danger';
        } else if (omzet > THRESH) {
            msg = 'Pendapatan melebihi Rp 500 juta. Pajak dihitung dari kelebihannya saja, yaitu ' + fmt(taxable) + ', sehingga pajak yang dibayar sekitar ' + fmt(tax) + '.';
            msgClass = 'r-msg-warn';
        } else {
            msg = 'Pendapatan masih di bawah Rp 500 juta, sehingga tidak ada pajak yang harus dibayar.';
            msgClass = 'r-msg-ok';
        }

        const html = `
            <div class="sim-label" style="margin-bottom: 10px;">Hasil Simulasi</div>
            <div class="result-cards">
                <div class="r-card"><div class="r-card-val" style="color: #86efac;">${fmt(omzet)}</div><div class="r-card-lbl">Pendapatan 1 Tahun</div></div>
                <div class="r-card"><div class="r-card-val" style="color: ${tax > 0 ? '#fcd34d' : '#86efac'};">${fmt(tax)}</div><div class="r-card-lbl">Pajak Dibayar</div></div>
                <div class="r-card"><div class="r-card-val" style="color: ${tpctNow >= 80 ? '#fca5a5' : '#93c5fd'};">${tpctNow}%</div><div class="r-card-lbl">Dari Batas Rp 500jt</div></div>
                <div class="r-card"><div class="r-card-val" style="color: ${sisa > 0 ? '#86efac' : '#fca5a5'};">${fmt(sisa)}</div><div class="r-card-lbl">Sisa Sampai Batas</div></div>
            </div>
            <div class="r-message ${msgClass}">${msg}</div>
            <div class="r-breakdown">
                <div class="r-breakdown-title">Rincian</div>
                <div class="r-row"><span class="r-row-lbl">Total pendapatan</span><span class="r-row-val">${fmt(omzet)}</span></div>
                <div class="r-row"><span class="r-row-lbl">Batas bebas pajak</span><span class="r-row-val" style="color: #86efac;">- ${fmt(Math.min(omzet, THRESH))}</span></div>
                <div class="r-row"><span class="r-row-lbl">Yang dikenai pajak</span><span class="r-row-val">${fmt(taxable)}</span></div>
                <div class="r-row"><span class="r-row-lbl">Tarif</span><span class="r-row-val">x 0,5%</span></div>
                <div class="r-row"><span class="r-row-lbl" style="color: #fff; font-weight: 600;">Pajak yang dibayar</span><span class="r-row-val" style="color: #fcd34d;">${fmt(tax)}</span></div>
            </div>
        `;

        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('resultContent').style.display = 'block';
        document.getElementById('resultContent').innerHTML = html;
    }

    document.getElementById('simInp').addEventListener('input', function() {
        const n = parseFloat(this.value.replace(/\D/g, '')) || 0;
        document.getElementById('simRange').value = Math.min(n, 1000000000);
        if (n > 0) this.value = n.toLocaleString('id-ID');
    });
</script>

</body>
</html>