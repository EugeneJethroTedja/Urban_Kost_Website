<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Analitik — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
        --accent:      #f4a535;
        --accent-pale: #fffbf0;
        --blue:        #2563eb;
        --blue-pale:   #eff6ff;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --violet:      #7c3aed;
        --violet-pale: #f5f3ff;
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
        --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
        --rad: 16px;
        --rad-sm: 10px;
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

    .ana-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50); }
    .ana-bg::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
            radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%),
            radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
    }
    .ana-bg-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
        background-size: 60px 60px;
    }
    .ana-page { min-height: 100vh; display: flex; flex-direction: column; }
    .ana-main { flex: 1; padding: 32px 0 64px; width: 100%; }
    .ana-inner { max-width: 1280px; margin: 0 auto; padding: 0 24px; width: 100%; }

    .page-header {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 36px 32px; margin-bottom: 28px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    }
    .page-header::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .page-header-arc {
        position: absolute; right: -100px; top: -100px;
        width: 380px; height: 380px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none; z-index: 1;
    }
    .page-header-content { position: relative; z-index: 1; }
    .page-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        padding: 5px 14px; border-radius: 100px;
        font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82); margin-bottom: 14px;
    }
    .page-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .page-title {
        font-family: 'Fraunces', serif; font-weight: 700;
        font-size: clamp(1.5rem, 4vw, 2.2rem); color: #fff;
        line-height: 1.15; letter-spacing: -.025em; margin-bottom: 8px;
    }
    .page-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
    .page-sub { font-size: .9rem; color: rgba(255,255,255,.62); font-weight: 400; max-width: 480px; line-height: 1.6; }
    .page-header-date {
        margin-top: 18px;
        display: inline-flex; align-items: center; gap: 7px;
        padding: 6px 14px; border-radius: 10px;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.78); font-size: .73rem; font-weight: 500;
    }

    .filter-bar {
        background: var(--neutral-0); border: 1px solid var(--green-100);
        border-radius: var(--rad); padding: 14px 18px;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        box-shadow: var(--shadow-sm); margin-bottom: 28px;
    }
    .filter-lbl { font-size: .78rem; font-weight: 600; color: var(--neutral-600); }
    .filter-bar select,
    .filter-bar input {
        font-size: .79rem; padding: 7px 11px;
        border: 1.5px solid var(--neutral-200); border-radius: 9px;
        background: var(--neutral-0); color: var(--neutral-700);
        font-family: 'Instrument Sans', sans-serif; font-weight: 500;
    }
    .filter-bar select:focus, .filter-bar input:focus { outline: none; border-color: var(--green-400); }
    .btn-apply {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; font-size: .79rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        background: linear-gradient(135deg, var(--green-600), var(--green-500));
        color: #fff; border: none; border-radius: 9px; cursor: pointer;
        box-shadow: 0 2px 10px rgba(42,179,107,.28); transition: all .2s;
    }
    .btn-apply:hover { background: var(--green-700); transform: translateY(-1px); }

    .section-divider {
        display: flex; align-items: center; gap: 14px;
        margin: 32px 0 20px; padding: 18px 22px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 50%, var(--green-700) 100%);
        border-radius: var(--rad); color: #fff;
        box-shadow: 0 4px 20px rgba(14,63,39,.25);
        position: relative; overflow: hidden;
    }

    .grid-2 > .o-card { margin-bottom: 0; }

    .section-divider::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 50%, rgba(134,217,174,.12) 0%, transparent 50%);
        pointer-events: none;
    }
    .section-divider-icon {
        width: 40px; height: 40px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.15);
        position: relative; z-index: 1;
    }
    .section-divider-body { position: relative; z-index: 1; }
    .section-divider-title {
        font-family: 'Fraunces', serif; font-size: 1.05rem;
        font-weight: 700; letter-spacing: -.02em;
    }
    .section-divider-sub { font-size: .72rem; color: rgba(255,255,255,.55); margin-top: 2px; }
    .section-divider-export {
        margin-left: auto; display: flex; gap: 8px; flex-wrap: wrap;
        position: relative; z-index: 1;
    }
    .btn-export-sm {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 13px; font-size: .72rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        border-radius: 8px; text-decoration: none; transition: all .15s;
        border: 1px solid; cursor: pointer;
    }
    .btn-export-xl {
        background: rgba(42,179,107,.18); color: var(--green-300);
        border-color: rgba(42,179,107,.3);
    }
    .btn-export-xl:hover { background: rgba(42,179,107,.3); color: #fff; }
    .btn-export-pdf {
        background: rgba(220,38,38,.15); color: #fca5a5;
        border-color: rgba(220,38,38,.25);
    }
    .btn-export-pdf:hover { background: rgba(220,38,38,.28); color: #fff; }

    .kpi-strip {
        display: grid; grid-template-columns: repeat(2, 1fr);
        gap: 14px; margin-bottom: 20px;
    }
    @media (min-width: 900px) { .kpi-strip { grid-template-columns: repeat(4, 1fr); } }
    .kpi {
        background: var(--neutral-0); border: 1px solid var(--green-100);
        border-radius: var(--rad); padding: 18px 20px;
        box-shadow: var(--shadow-md); position: relative; overflow: hidden;
        transition: border-color .25s, box-shadow .3s, transform .25s; cursor: default;
    }
    .kpi:hover { border-color: var(--green-300); box-shadow: var(--shadow-lg); transform: translateY(-2px); }
    .kpi::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0;
        height: 3px; border-radius: var(--rad) var(--rad) 0 0;
    }
    .kpi.green::before  { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
    .kpi.blue::before   { background: linear-gradient(90deg, var(--blue), #60a5fa); }
    .kpi.amber::before  { background: linear-gradient(90deg, var(--amber), #fcd34d); }
    .kpi.red::before    { background: linear-gradient(90deg, var(--red), #f87171); }
    .kpi.violet::before { background: linear-gradient(90deg, var(--violet), #a78bfa); }
    .kpi-val {
        font-family: 'Fraunces', serif; font-size: 1.75rem; font-weight: 700;
        color: var(--neutral-900); letter-spacing: -.03em; line-height: 1; margin-bottom: 6px;
    }
    .kpi-lbl { font-size: .67rem; font-weight: 600; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .07em; }
    .kpi-sub { font-size: .7rem; color: var(--neutral-500); margin-top: 3px; }
    .kpi-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 1.8rem; opacity: .05; color: var(--neutral-900); }

    .o-card {
        background: var(--neutral-0); border-radius: var(--rad);
        border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 16px;
    }
    .card-head {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
        font-size: .8rem; font-weight: 600; color: var(--neutral-700);
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .card-head-right { margin-left: auto; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .hico { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
    .hico.green  { background: var(--green-100); color: var(--green-700); }
    .hico.blue   { background: var(--blue-pale); color: var(--blue); }
    .hico.amber  { background: var(--amber-pale); color: #92400e; }
    .hico.red    { background: var(--red-pale); color: var(--red); }
    .hico.violet { background: var(--violet-pale); color: var(--violet); }
    .card-body { padding: 20px; }

    .info-box {
        background: var(--green-50); border: 1px solid var(--green-200);
        border-radius: var(--rad-sm); padding: 12px 16px; margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
    }
    .info-box i { color: var(--green-600); margin-top: 2px; flex-shrink: 0; }
    .info-box p { font-size: .77rem; color: var(--neutral-600); line-height: 1.55; font-weight: 500; }
    .info-box p strong { color: var(--green-700); }

    .grid-2 { display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px; }
    @media (min-width: 900px) { .grid-2 { grid-template-columns: 1fr 1fr; } }

    .admin-table { width: 100%; border-collapse: collapse; min-width: 520px; }
    .admin-table th {
        padding: 10px 14px; font-size: .63rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--neutral-400); background: var(--neutral-50);
        border-bottom: 1px solid var(--neutral-100); text-align: left; white-space: nowrap;
    }
    .admin-table td {
        padding: 11px 14px; border-bottom: 1px solid var(--neutral-100);
        font-size: .79rem; color: var(--neutral-600); vertical-align: middle;
    }
    .admin-table tr:last-child td { border-bottom: none; }
    .admin-table tr:hover td { background: var(--green-50); }
    .tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .sub-filter {
        background: var(--neutral-50); border: 1px solid var(--neutral-200);
        border-radius: var(--rad-sm); padding: 10px 14px;
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 14px;
    }
    .sub-filter select {
        font-size: .75rem; padding: 6px 9px;
        border: 1.5px solid var(--neutral-200); border-radius: 8px;
        background: var(--neutral-0); color: var(--neutral-700);
        font-family: 'Instrument Sans', sans-serif; font-weight: 500;
    }
    .sub-filter label { font-size: .72rem; font-weight: 600; color: var(--neutral-500); }
    .btn-sub-apply {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 13px; font-size: .73rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        background: var(--neutral-700); color: #fff;
        border: none; border-radius: 8px; cursor: pointer; transition: background .2s;
    }
    .btn-sub-apply:hover { background: var(--green-700); }

    .badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 600;
    }
    .badge-green  { background: var(--green-100); color: var(--green-800); }
    .badge-red    { background: var(--red-pale); color: var(--red); }
    .badge-amber  { background: var(--amber-pale); color: #92400e; }
    .badge-blue   { background: var(--blue-pale); color: var(--blue); }
    .badge-gray   { background: var(--neutral-100); color: var(--neutral-600); }
    .badge-violet { background: var(--violet-pale); color: var(--violet); }
    .badge-orange { background: #fff7ed; color: #9a3412; }

    .alert-strip {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 13px 16px; border-radius: 10px; margin-bottom: 14px;
        font-size: .82rem; font-weight: 500; border: 1px solid; line-height: 1.55;
    }
    .alert-strip.danger { background: var(--red-pale); border-color: rgba(220,38,38,.3); color: #7f1d1d; }
    .alert-strip.warn   { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
    .alert-strip.info   { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
    .alert-strip i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }

    .progress-bar-wrap { background: var(--neutral-100); border-radius: 100px; height: 6px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 100px; transition: width .5s ease; }

    .rank-num { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .67rem; font-weight: 700; }
    .rank-1 { background: #fef08a; color: #713f12; }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fde8cc; color: #9a3412; }
    .rank-n { background: var(--neutral-100); color: var(--neutral-500); }

    .mini-stats {
        display: grid; gap: 0;
        border: 1px solid var(--green-100); border-radius: var(--rad-sm);
        overflow: hidden; margin-bottom: 16px;
        background: var(--neutral-0); box-shadow: var(--shadow-sm);
    }
    .mini-stat { padding: 16px 18px; border-right: 1px solid var(--neutral-100); text-align: center; }
    .mini-stat:last-child { border-right: none; }
    .mini-stat-val { font-family: 'Fraunces', serif; font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--neutral-900); }
    .mini-stat-lbl { font-size: .64rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--neutral-400); margin-top: 3px; }

    .insight-box {
        background: var(--neutral-50); border: 1px solid var(--neutral-200);
        border-radius: var(--rad-sm); padding: 12px 14px;
        font-size: .75rem; color: var(--neutral-600); line-height: 1.6; margin-top: 14px;
    }
    .insight-tag {
        display: inline-block; padding: 2px 8px; border-radius: 5px;
        font-size: .64rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-right: 4px;
    }
    .tag-good { background: var(--green-100); color: var(--green-800); }
    .tag-warn { background: var(--amber-pale); color: #92400e; }
    .tag-bad  { background: var(--red-pale); color: var(--red); }

    .ticket-row {
        border: 1px solid var(--neutral-200); border-radius: var(--rad-sm);
        padding: 14px 16px; margin-bottom: 10px;
        background: var(--neutral-0); transition: box-shadow .2s;
    }
    .ticket-row:hover { box-shadow: var(--shadow-md); }
    .ticket-row.priority-urgent  { border-left: 3px solid var(--red); }
    .ticket-row.priority-high    { border-left: 3px solid #f97316; }
    .ticket-row.priority-normal  { border-left: 3px solid var(--amber); }
    .ticket-row.priority-low     { border-left: 3px solid var(--green-500); }

    .pagi {
        display: flex; align-items: center; gap: 4px;
        padding: 14px 20px; border-top: 1px solid var(--neutral-100);
    }
    .pagi a, .pagi span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 8px;
        font-size: .75rem; font-weight: 600; text-decoration: none;
        border: 1px solid var(--neutral-200); color: var(--neutral-600);
    }
    .pagi .active { background: var(--green-600); color: #fff; border-color: var(--green-600); }
    .pagi a:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-700); }
    .pagi-info { font-size: .72rem; color: var(--neutral-400); margin-left: auto; }

    @media (max-width: 480px) {
        .ana-inner { padding: 0 14px; }
        .kpi-strip { gap: 10px; }
        .mini-stats { grid-template-columns: repeat(2,1fr) !important; }
        .mini-stat { border-right: none; border-bottom: 1px solid var(--neutral-100); }
        .mini-stat:last-child { border-bottom: none; }
        .section-divider { flex-wrap: wrap; }
        .section-divider-export { margin-left: 0; }
        .page-header { padding: 22px 18px; }
    }
    @media (min-width: 900px) {
        .ana-inner { padding: 0 40px; }
        .ana-main  { padding: 40px 0 64px; }
    }
    @media (min-width: 1280px) { .ana-inner { padding: 0 48px; } }
    </style>
</head>
<body>

<div class="ana-bg"><div class="ana-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="ana-page">
<main class="ana-main">
<div class="ana-inner">

    <div class="page-header">
        <div class="page-header-content">
            <div class="page-eyebrow">
                <span class="dot"></span>
                Laporan Analitik
            </div>
            <h1 class="page-title">Laporan & <em>Analitik</em></h1>
            <p class="page-sub">Pendapatan dan arus kas, riwayat booking per kamar, keterlambatan pembayaran, dan performa layanan tiket.</p>
            <div class="page-header-date">
                <i class="bi bi-calendar3"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    <form method="GET" id="mainFilter" class="filter-bar">
        <span class="filter-lbl">Tahun:</span>
        <select name="year">
            @foreach($years as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <span class="filter-lbl">Bulan Referensi:</span>
        <select name="month">
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->format('F') }}</option>
            @endforeach
        </select>
        @foreach(['cf_month','cf_status','cf_type','bk_room','bk_status','bk_month','lt_month','tk_status','tk_priority','tk_category','tk_month'] as $p)
            @if(request($p))<input type="hidden" name="{{ $p }}" value="{{ request($p) }}">@endif
        @endforeach
        <button type="submit" class="btn-apply">
            <i class="bi bi-funnel-fill"></i> Terapkan
        </button>
    </form>

    <div class="section-divider">
        <div class="section-divider-icon"><i class="bi bi-cash-stack"></i></div>
        <div class="section-divider-body">
            <div class="section-divider-title">Pendapatan & Keuangan</div>
            <div class="section-divider-sub">Total pendapatan, arus kas masuk, distribusi invoice, dan kamar paling menghasilkan</div>
        </div>
        <div class="section-divider-export">
            <a href="{{ route('admin.reports.export', ['section'=>'pendapatan','year'=>$year]) }}" class="btn-export-sm btn-export-xl">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('admin.reports.export', ['section'=>'pendapatan','year'=>$year,'format'=>'pdf']) }}" class="btn-export-sm btn-export-pdf">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="info-box">
        <i class="bi bi-info-circle-fill"></i>
        <p>Bagian ini menampilkan <strong>total pendapatan yang sudah dibayar & belum</strong>, rincian per bulan, komposisi per tipe invoice, dan <strong>detail arus kas masuk</strong> dari setiap transaksi yang dapat difilter.</p>
    </div>

    <div class="kpi-strip">
        <div class="kpi blue">
            <div class="kpi-val">Rp {{ number_format($totalRevenue / 1000000, 1) }}jt</div>
            <div class="kpi-lbl">Total Pendapatan {{ $year }}</div>
            <div class="kpi-sub">{{ $paidInvoices }} invoice lunas</div>
        </div>
        <div class="kpi green">
            <div class="kpi-val">Rp {{ number_format($monthRevenue / 1000000, 1) }}jt</div>
            <div class="kpi-lbl">Pendapatan {{ \Carbon\Carbon::create(null, $month)->format('M') }}</div>
            <div class="kpi-sub">Bulan terpilih</div>
        </div>
        <div class="kpi amber">
            <div class="kpi-val">{{ $unpaidInvoices }}</div>
            <div class="kpi-lbl">Belum Terbayar</div>
            <div class="kpi-sub">Invoice unpaid / overdue</div>
        </div>
        <div class="kpi red">
            <div class="kpi-val">Rp {{ number_format($overdueAmount / 1000000, 1) }}jt</div>
            <div class="kpi-lbl">Total Tunggakan</div>
            <div class="kpi-sub">{{ $overdueInvoices }} invoice overdue</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="o-card">
            <div class="card-head">
                <div class="hico blue"><i class="bi bi-bar-chart"></i></div>
                Pendapatan Bulanan {{ $year }}
                <div class="card-head-right">
                    <span style="font-size:.7rem;background:var(--green-100);color:var(--green-800);padding:3px 10px;border-radius:100px;font-weight:600;border:1px solid var(--green-200)">
                        Total Rp {{ number_format($totalRevenue / 1000000, 1) }}jt
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div style="position:relative"><canvas id="revenueChart" height="160"></canvas></div>
                @php
                    $maxRevMonth = collect($monthlyData)->sortByDesc('revenue')->first();
                    $minRevMonth = collect($monthlyData)->where('revenue', '>', 0)->sortBy('revenue')->first();
                @endphp
                @if($maxRevMonth)
                <div class="insight-box">
                    <span class="insight-tag tag-good">Catatan</span>
                    Tertinggi: <strong>{{ $maxRevMonth['month'] }} (Rp {{ number_format($maxRevMonth['revenue'] / 1000000, 1) }}jt)</strong>.
                    @if($minRevMonth) Terendah: <strong>{{ $minRevMonth['month'] }}</strong>. @endif
                    Rata-rata: <strong>Rp {{ number_format(($totalRevenue / 12) / 1000000, 1) }}jt/bulan</strong>.
                </div>
                @endif
            </div>
        </div>

        <div class="o-card">
            <div class="card-head">
                <div class="hico green"><i class="bi bi-trophy"></i></div>
                Top 5 Kamar Paling Menghasilkan {{ $year }}
                <div class="card-head-right">
                    <span style="font-size:.7rem;color:var(--neutral-400)">Berdasarkan invoice lunas</span>
                </div>
            </div>
            <div class="tbl-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Kamar</th><th>Total Pendapatan</th><th>Invoice</th><th>Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topRooms as $i => $tr)
                        <tr>
                            <td>
                                <div class="rank-num {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">{{ $i+1 }}</div>
                            </td>
                            <td>
                                <span style="font-weight:700;color:var(--neutral-900)">{{ $tr->room?->room_number }}</span>
                                @if($tr->room?->room_name)<span style="font-size:.72rem;color:var(--neutral-400);margin-left:6px">{{ $tr->room->room_name }}</span>@endif
                            </td>
                            <td style="font-weight:700;color:var(--green-700)">Rp {{ number_format($tr->total_revenue, 0, ',', '.') }}</td>
                            <td><span class="badge badge-blue">{{ $tr->invoice_count }}x</span></td>
                            <td>
                                @php $pct = $totalRevenue > 0 ? round(($tr->total_revenue / $totalRevenue) * 100, 1) : 0 @endphp
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div style="flex:1" class="progress-bar-wrap">
                                        <div class="progress-bar-fill" style="width:{{ $pct }}%;background:var(--green-500)"></div>
                                    </div>
                                    <span style="font-size:.72rem;font-weight:600;color:var(--neutral-600);width:38px">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--neutral-400);font-size:.82rem">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($topRooms->isNotEmpty())
            <div style="padding:12px 20px;border-top:1px solid var(--neutral-100);display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:.72rem;color:var(--neutral-400)">Kontribusi top 5 dari total pendapatan</span>
                @php
                    $top5Total = $topRooms->sum('total_revenue');
                    $top5Pct   = $totalRevenue > 0 ? round(($top5Total / $totalRevenue) * 100, 1) : 0;
                @endphp
                <span style="font-size:.78rem;font-weight:700;color:var(--green-700)">
                    {{ $top5Pct }}% &nbsp;(Rp {{ number_format($top5Total / 1000000, 1) }}jt)
                </span>
            </div>
            @endif
        </div>
    </div>

    <div class="o-card">
        <div class="card-head">
            <div class="hico blue"><i class="bi bi-arrow-down-circle"></i></div>
            Arus Kas Masuk (Detail Invoice) {{ $year }}
            <div class="card-head-right">
                <span style="font-size:.72rem;color:var(--neutral-400)">{{ $cashFlowInvoices->total() }} invoice ditemukan</span>
            </div>
        </div>
        <div class="card-body" style="padding:14px 20px">
            <form method="GET" class="sub-filter">
                @foreach(['year','month'] as $p)<input type="hidden" name="{{ $p }}" value="{{ $$p }}">@endforeach
                @foreach(['bk_room','bk_status','bk_month','lt_month','tk_status','tk_priority','tk_category','tk_month'] as $p)
                    @if(request($p))<input type="hidden" name="{{ $p }}" value="{{ request($p) }}">@endif
                @endforeach
                <label>Bulan:</label>
                <select name="cf_month">
                    <option value="all">Semua</option>
                    @foreach(range(1,12) as $m)<option value="{{ $m }}" {{ request('cf_month')==$m?'selected':'' }}>{{ \Carbon\Carbon::create(null,$m)->format('M') }}</option>@endforeach
                </select>
                <label>Status:</label>
                <select name="cf_status">
                    <option value="all">Semua</option>
                    <option value="paid" {{ request('cf_status')=='paid'?'selected':'' }}>Lunas</option>
                    <option value="unpaid" {{ request('cf_status')=='unpaid'?'selected':'' }}>Belum Bayar</option>
                    <option value="overdue" {{ request('cf_status')=='overdue'?'selected':'' }}>Overdue</option>
                    <option value="pending_verification" {{ request('cf_status')=='pending_verification'?'selected':'' }}>Pending Verifikasi</option>
                </select>
                <label>Tipe:</label>
                <select name="cf_type">
                    <option value="sewa" {{ request('cf_type')=='sewa'?'selected':'' }}>Sewa</option>
                    <option value="renewal" {{ request('cf_type')=='renewal'?'selected':'' }}>Perpanjangan</option>
                </select>
                <button type="submit" class="btn-sub-apply"><i class="bi bi-search"></i> Filter</button>
            </form>
        </div>
        <div class="tbl-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th><th>Tenant</th><th>Kamar</th><th>Tipe</th>
                        <th>Periode</th><th>Tagihan</th><th>Jatuh Tempo</th><th>Status</th><th>Dibayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashFlowInvoices as $inv)
                    @php
                        $sc = match($inv->status) { 'paid'=>'badge-green','overdue'=>'badge-red','unpaid'=>'badge-amber','pending_verification'=>'badge-blue',default=>'badge-gray' };
                        $sl = match($inv->status) { 'paid'=>'Lunas','overdue'=>'Overdue','unpaid'=>'Belum Bayar','pending_verification'=>'Verifikasi','cancelled'=>'Batal',default=>$inv->status };
                        $rb = match($inv->status) { 'overdue'=>'background:var(--red-pale)','unpaid'=>'background:var(--amber-pale)',default=>'' };
                    @endphp
                    <tr style="{{ $rb }}">
                        <td style="font-weight:700;font-size:.74rem;font-family:monospace">{{ $inv->invoice_number }}</td>
                        <td>
                            <div style="font-weight:600;font-size:.78rem">{{ $inv->user?->name ?? '-' }}</div>
                            <div style="font-size:.68rem;color:var(--neutral-400)">{{ $inv->user?->email }}</div>
                        </td>
                        <td style="font-weight:700">{{ $inv->room?->room_number ?? '-' }}</td>
                        <td>
                            @php $tl = match(true) {
                                in_array($inv->invoice_type, ['monthly', 'monthly_first', 'waiting_list_dp', 'waiting_list_remaining']) => 'Sewa',
                                $inv->invoice_type === 'renewal' => 'Perpanjangan',
                                default => $inv->invoice_type ?? '-'
                            }; 
                            @endphp
                            <span class="badge badge-gray">{{ $tl }}</span>
                        </td>
                        <td style="font-size:.73rem;color:var(--neutral-500)">
                            @if($inv->period_start && $inv->period_end) {{ $inv->period_start->format('d M') }} – {{ $inv->period_end->format('d M Y') }} @else - @endif
                        </td>
                        <td style="font-weight:700;color:var(--neutral-800)">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                        <td style="font-size:.74rem">
                            @if($inv->due_date)
                                <span style="color:{{ $inv->due_date->isPast() && $inv->status!='paid' ? 'var(--red)' : 'var(--neutral-600)' }};font-weight:600">{{ $inv->due_date->format('d M Y') }}</span>
                            @else - @endif
                        </td>
                        <td><span class="badge {{ $sc }}">{{ $sl }}</span></td>
                        <td style="font-size:.74rem;color:var(--green-600)">{{ $inv->paid_at ? $inv->paid_at->format('d M Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--neutral-400);font-size:.82rem">Tidak ada data invoice</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cashFlowInvoices->hasPages())
        <div class="pagi">
            @foreach($cashFlowInvoices->onEachSide(1)->links()->elements[0] ?? [] as $page => $url)
                <a href="{{ $url }}" class="{{ $cashFlowInvoices->currentPage()==$page?'active':'' }}">{{ $page }}</a>
            @endforeach
            <span class="pagi-info">{{ $cashFlowInvoices->firstItem() }}–{{ $cashFlowInvoices->lastItem() }} dari {{ $cashFlowInvoices->total() }}</span>
        </div>
        @endif
    </div>

    <div class="section-divider">
        <div class="section-divider-icon"><i class="bi bi-calendar-check"></i></div>
        <div class="section-divider-body">
            <div class="section-divider-title">Riwayat & Statistik Booking per Kamar</div>
            <div class="section-divider-sub">Perbandingan aktivitas booking setiap kamar, riwayat lengkap, dan kamar yang jarang terisi</div>
        </div>
        <div class="section-divider-export">
            <a href="{{ route('admin.reports.export', ['section'=>'booking','year'=>$year]) }}" class="btn-export-sm btn-export-xl">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('admin.reports.export', ['section'=>'booking','year'=>$year,'format'=>'pdf']) }}" class="btn-export-sm btn-export-pdf">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="info-box">
        <i class="bi bi-info-circle-fill"></i>
        <p>Lihat <strong>seberapa aktif setiap kamar dibooking</strong>, perbandingan antar kamar, dan identifikasi kamar yang tidak pernah atau jarang tersentuh.</p>
    </div>

    <div class="kpi-strip">
        <div class="kpi blue">
            <div class="kpi-val">{{ $totalBookings }}</div>
            <div class="kpi-lbl">Total Booking {{ $year }}</div>
            <div class="kpi-sub">Semua tipe & status</div>
        </div>
        <div class="kpi green">
            <div class="kpi-val">{{ $approvedBookings }}</div>
            <div class="kpi-lbl">Disetujui / Aktif</div>
            <div class="kpi-sub">{{ $totalBookings > 0 ? round(($approvedBookings / $totalBookings) * 100) : 0 }}% approval rate</div>
        </div>
        <div class="kpi red">
            <div class="kpi-val">{{ $cancelledBookings }}</div>
            <div class="kpi-lbl">Dibatalkan / Ditolak</div>
            <div class="kpi-sub">{{ $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100) : 0 }}% dari total</div>
        </div>
        <div class="kpi amber">
            <div class="kpi-val">{{ $avgDuration }}</div>
            <div class="kpi-lbl">Rata-rata Durasi</div>
            <div class="kpi-sub">Bulan per booking</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="o-card">
            <div class="card-head"><div class="hico blue"><i class="bi bi-graph-up"></i></div>Tren Booking Bulanan {{ $year }}</div>
            <div class="card-body"><canvas id="bookingTrendChart" height="160"></canvas></div>
        </div>
        <div class="o-card">
            <div class="card-head">
                <div class="hico red"><i class="bi bi-exclamation-circle"></i></div>
                Kamar Paling Jarang Dibooking
                <div class="card-head-right"><span style="font-size:.7rem;color:var(--neutral-400)">Perlu perhatian</span></div>
            </div>
            <div class="card-body">
                @if($leastBookedRooms->isEmpty())
                <div style="text-align:center;padding:24px;color:var(--neutral-400);font-size:.82rem">Semua kamar sudah aktif dibooking</div>
                @else
                <div style="display:flex;flex-direction:column;gap:10px">
                    @foreach($leastBookedRooms as $lb)
                    @php $r = $lb['room']; $bkTotal = $lb['total_bookings']; @endphp
                    <div style="display:flex;align-items:center;gap:12px;padding:11px 14px;border:1px solid var(--neutral-200);border-radius:10px;{{ $bkTotal===0?'background:var(--red-pale);border-color:rgba(220,38,38,.25)':'' }}">
                        <div style="width:38px;height:38px;border-radius:10px;background:{{ $bkTotal===0?'var(--red-pale)':'var(--amber-pale)' }};display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;color:{{ $bkTotal===0?'var(--red)':'var(--amber)' }};flex-shrink:0">
                            {{ $r->room_number }}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;font-size:.8rem;color:var(--neutral-900)">{{ $r->room_name ?? 'Kamar '.$r->room_number }}</div>
                            <div style="font-size:.69rem;color:var(--neutral-400)">Status: <strong>{{ $r->status }}</strong> &middot; Rp {{ number_format($r->price/1000,0) }}rb/bln</div>
                        </div>
                        <div style="text-align:right">
                            <span class="badge {{ $bkTotal===0?'badge-red':'badge-amber' }}">{{ $bkTotal }}x booking</span>
                            @if($lb['days_vacant']>0)<div style="font-size:.66rem;color:var(--neutral-400);margin-top:3px">~{{ $lb['days_vacant'] }} hari kosong</div>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
    </div>

    <div class="o-card">
        <div class="card-head"><div class="hico blue"><i class="bi bi-table"></i></div>Perbandingan Statistik Semua Kamar {{ $year }}</div>
        <div class="tbl-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>Kamar</th><th>Status</th><th>Tenant Aktif</th><th>Total Booking</th><th>Disetujui</th><th>Dibatalkan</th><th>Avg Durasi</th><th>Total Pendapatan</th><th>Aktivitas</th></tr>
                </thead>
                <tbody>
                    @php $maxBkStat = collect($bookingStatsByRoom)->max('total_bookings') ?: 1; @endphp
                    @foreach($bookingStatsByRoom as $bs)
                    @php
                        $sc = match($bs['room']->status) { 'occupied'=>'badge-green','available'=>'badge-blue','reserved'=>'badge-amber','maintenance'=>'badge-gray',default=>'badge-gray' };
                        $sl = match($bs['room']->status) { 'occupied'=>'Terisi','available'=>'Kosong','reserved'=>'Dipesan','maintenance'=>'Perbaikan',default=>$bs['room']->status };
                        $rh = $bs['total_bookings']===0 ? 'background:var(--red-pale)' : '';
                    @endphp
                    <tr style="{{ $rh }}">
                        <td>
                            <span style="font-weight:700;color:var(--neutral-900)">{{ $bs['room']->room_number }}</span>
                            @if($bs['room']->room_name)<span style="font-size:.71rem;color:var(--neutral-400);margin-left:5px">{{ $bs['room']->room_name }}</span>@endif
                        </td>
                        <td><span class="badge {{ $sc }}">{{ $sl }}</span></td>
                        <td style="font-size:.76rem">{!! $bs['current_tenant'] ?? '<span style="color:var(--neutral-300)">—</span>' !!}</td>
                        <td>
                            @if($bs['total_bookings']===0)<span class="badge badge-red">0x</span>
                            @else<span class="badge badge-blue">{{ $bs['total_bookings'] }}x</span>@endif
                        </td>
                        <td><span class="badge badge-green">{{ $bs['completed']+$bs['active'] }}x</span></td>
                        <td><span class="badge badge-red">{{ $bs['cancelled'] }}x</span></td>
                        <td><span class="badge badge-gray">{{ $bs['avg_duration'] }} bln</span></td>
                        <td style="font-weight:700;color:{{ $bs['total_revenue']>0?'var(--green-700)':'var(--neutral-300)' }}">
                            {{ $bs['total_revenue']>0 ? 'Rp '.number_format($bs['total_revenue'],0,',','.') : '—' }}
                        </td>
                        <td style="width:100px">
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" style="width:{{ $maxBkStat>0?round(($bs['total_bookings']/$maxBkStat)*100):0 }}%;background:{{ $bs['total_bookings']===0?'var(--red)':'var(--green-400)' }}"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="o-card">
        <div class="card-head">
            <div class="hico green"><i class="bi bi-list-check"></i></div>
            Riwayat Detail Booking {{ $year }}
            <div class="card-head-right"><span style="font-size:.72rem;color:var(--neutral-400)">{{ $bookingDetail->total() }} booking ditemukan</span></div>
        </div>
        <div class="card-body" style="padding:14px 20px">
            <form method="GET" class="sub-filter">
                @foreach(['year','month','cf_month','cf_status','cf_type','lt_month','tk_status','tk_priority','tk_category','tk_month'] as $p)
                    @if(request($p)||in_array($p,['year','month']))<input type="hidden" name="{{ $p }}" value="{{ $$p??request($p) }}">@endif
                @endforeach
                <label>Kamar:</label>
                <select name="bk_room">
                    <option value="all">Semua</option>
                    @foreach($allRooms as $r)<option value="{{ $r->id }}" {{ request('bk_room')==$r->id?'selected':'' }}>{{ $r->room_number }}</option>@endforeach
                </select>
                <label>Status:</label>
                <select name="bk_status">
                    <option value="all">Semua</option>
                    @foreach(['pending','under_review','approved','active','completed','cancelled','rejected','waiting_list'] as $s)
                    <option value="{{ $s }}" {{ request('bk_status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
                <label>Bulan:</label>
                <select name="bk_month">
                    <option value="all">Semua</option>
                    @foreach(range(1,12) as $m)<option value="{{ $m }}" {{ request('bk_month')==$m?'selected':'' }}>{{ \Carbon\Carbon::create(null,$m)->format('M') }}</option>@endforeach
                </select>
                <button type="submit" class="btn-sub-apply"><i class="bi bi-search"></i> Filter</button>
            </form>
        </div>
        <div class="tbl-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>Kode Booking</th><th>Tenant</th><th>Kamar</th><th>Tipe</th><th>Check-in</th><th>Durasi</th><th>Status</th><th>Dibuat</th><th>Catatan</th></tr>
                </thead>
                <tbody>
                    @forelse($bookingDetail as $bk)
                    @php
                        $bb = match($bk->status) { 'pending'=>'badge-amber','under_review'=>'badge-blue','approved'=>'badge-green','active'=>'badge-green','completed'=>'badge-violet','cancelled'=>'badge-red','rejected'=>'badge-red','waiting_list'=>'badge-orange',default=>'badge-gray' };
                        $bl = match($bk->status) { 'pending'=>'Pending','under_review'=>'Ditinjau','approved'=>'Disetujui','active'=>'Aktif','completed'=>'Selesai','cancelled'=>'Dibatalkan','rejected'=>'Ditolak','waiting_list'=>'Waiting List',default=>$bk->status };
                    @endphp
                    <tr>
                        <td style="font-weight:700;font-size:.74rem;font-family:monospace">{{ $bk->booking_code }}</td>
                        <td>
                            <div style="font-weight:600;font-size:.78rem">{{ $bk->user?->name ?? $bk->full_name ?? '-' }}</div>
                            <div style="font-size:.68rem;color:var(--neutral-400)">{{ $bk->user?->email }}</div>
                        </td>
                        <td style="font-weight:700">{{ $bk->room?->room_number ?? '-' }}</td>
                        <td>
                            @if($bk->booking_type==='advance')<span class="badge badge-orange">Advance</span>
                            @else<span class="badge badge-gray">Regular</span>@endif
                        </td>
                        <td style="font-size:.75rem">{{ $bk->check_in_date ? $bk->check_in_date->format('d M Y') : '—' }}</td>
                        <td><span class="badge badge-gray">{{ $bk->duration_months ?? '—' }} bln</span></td>
                        <td><span class="badge {{ $bb }}">{{ $bl }}</span></td>
                        <td style="font-size:.74rem;color:var(--neutral-500)">{{ $bk->created_at->format('d M Y') }}</td>
                        <td style="max-width:160px">
                            @if($bk->admin_notes)<span style="font-size:.7rem;color:var(--neutral-400)" title="{{ $bk->admin_notes }}">{{ \Str::limit($bk->admin_notes,35) }}</span>
                            @elseif($bk->cancellation_reason)<span style="font-size:.7rem;color:var(--red)" title="{{ $bk->cancellation_reason }}">{{ \Str::limit($bk->cancellation_reason,35) }}</span>
                            @else<span style="color:var(--neutral-200)">—</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--neutral-400);font-size:.82rem">Tidak ada data booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookingDetail->hasPages())
        <div class="pagi">
            @foreach($bookingDetail->onEachSide(1)->links()->elements[0] ?? [] as $page => $url)
                <a href="{{ $url }}" class="{{ $bookingDetail->currentPage()==$page?'active':'' }}">{{ $page }}</a>
            @endforeach
            <span class="pagi-info">{{ $bookingDetail->firstItem() }}–{{ $bookingDetail->lastItem() }} dari {{ $bookingDetail->total() }}</span>
        </div>
        @endif
    </div>

    <div class="section-divider">
        <div class="section-divider-icon"><i class="bi bi-clock-history"></i></div>
        <div class="section-divider-body">
            <div class="section-divider-title">Pembayaran & Keterlambatan</div>
            <div class="section-divider-sub">Analisis ketepatan waktu, invoice overdue aktif, tren dan detail pembayaran terlambat</div>
        </div>
        <div class="section-divider-export">
            <a href="{{ route('admin.reports.export', ['section'=>'keterlambatan','year'=>$year]) }}" class="btn-export-sm btn-export-xl">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('admin.reports.export', ['section'=>'keterlambatan','year'=>$year,'format'=>'pdf']) }}" class="btn-export-sm btn-export-pdf">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="info-box">
        <i class="bi bi-info-circle-fill"></i>
        <p>Pantau <strong>arus kas secara akurat</strong> dengan melihat invoice yang sudah jatuh tempo, tenant yang sering terlambat, dan rata-rata hari keterlambatan.</p>
    </div>

    @if($currentOverdue->count())
    <div class="alert-strip danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><strong>{{ $currentOverdue->count() }} invoice sedang overdue</strong> dengan total tunggakan <strong>Rp {{ number_format($overdueAmount, 0, ',', '.') }}</strong>. Tindak segera.</span>
    </div>
    <div class="o-card">
        <div class="card-head"><div class="hico red"><i class="bi bi-exclamation-triangle"></i></div>Invoice Overdue Aktif — Perlu Tindakan</div>
        <div class="tbl-wrap">
            <table class="admin-table">
                <thead><tr><th>No. Invoice</th><th>Tenant</th><th>Kamar</th><th>Tagihan</th><th>Jatuh Tempo</th><th>Terlambat</th></tr></thead>
                <tbody>
                    @foreach($currentOverdue as $od)
                    <tr style="background:var(--red-pale)">
                        <td style="font-weight:700;font-size:.74rem;font-family:monospace">{{ $od->invoice_number }}</td>
                        <td>
                            <div style="font-weight:600">{{ $od->user?->name ?? '-' }}</div>
                            <div style="font-size:.68rem;color:var(--neutral-400)">{{ $od->user?->email }}</div>
                        </td>
                        <td style="font-weight:700">{{ $od->room?->room_number ?? '-' }}</td>
                        <td style="font-weight:700;color:var(--red)">Rp {{ number_format($od->total_amount, 0, ',', '.') }}</td>
                        <td style="font-size:.75rem;color:var(--red);font-weight:700">{{ $od->due_date?->format('d M Y') ?? '-' }}</td>
                        <td>@if($od->due_date)<span class="badge badge-red">{{ now()->diffInDays($od->due_date) }} hari</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="mini-stats" style="grid-template-columns:repeat(3,1fr)">
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--green-600)">{{ $paidCount }}</div><div class="mini-stat-lbl">Invoice Lunas {{ $year }}</div></div>
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--amber)">{{ $unpaidCount }}</div><div class="mini-stat-lbl">Belum Bayar</div></div>
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--red)">{{ $overdueInvoices }}</div><div class="mini-stat-lbl">Overdue Aktif</div></div>
    </div>

    <div class="grid-2">
        <div class="o-card">
            <div class="card-head"><div class="hico amber"><i class="bi bi-bar-chart"></i></div>Invoice Terlambat per Bulan {{ $year }}</div>
            <div class="card-body"><canvas id="latePayChart" height="160"></canvas></div>
        </div>
        <div class="o-card">
            <div class="card-head"><div class="hico amber"><i class="bi bi-graph-up-arrow"></i></div>Rata-rata Hari Keterlambatan</div>
            <div class="card-body">
                <canvas id="lateDaysChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="o-card">
        <div class="card-head">
            <div class="hico red"><i class="bi bi-person-x"></i></div>
            Tenant Paling Sering Terlambat {{ $year }}
            <div class="card-head-right"><span style="font-size:.7rem;color:var(--neutral-400)">&ge;2x terlambat</span></div>
        </div>
        <div class="card-body">
            @if($chronicallyLateUsers->isEmpty())
            <div style="text-align:center;padding:32px;color:var(--neutral-400)">
                <i class="bi bi-check-circle" style="font-size:2rem;display:block;opacity:.2;margin-bottom:10px"></i>
                <p style="font-size:.82rem">Tidak ada tenant yang sering terlambat dalam periode ini.</p>
            </div>
            @else
            <div class="alert-strip danger" style="margin-bottom:16px">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><strong>{{ $chronicallyLateUsers->count() }} tenant</strong> perlu perhatian khusus.</span>
            </div>
            <div class="tbl-wrap">
                <table class="admin-table">
                    <thead><tr><th>#</th><th>Tenant</th><th>Kontak</th><th>Jml Terlambat</th><th>Total Dibayar</th></tr></thead>
                    <tbody>
                        @foreach($chronicallyLateUsers as $i => $clu)
                        <tr>
                            <td><div class="rank-num {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">{{ $i+1 }}</div></td>
                            <td>
                                <div style="font-weight:700">{{ $clu->user?->name ?? '—' }}</div>
                                <div style="font-size:.7rem;color:var(--neutral-400)">{{ $clu->user?->email }}</div>
                            </td>
                            <td style="font-size:.75rem;color:var(--neutral-500)">{{ $clu->user?->phone ?? '—' }}</td>
                            <td><span class="badge badge-red">{{ $clu->late_count }}x</span></td>
                            <td style="font-size:.76rem;color:var(--green-700);font-weight:700">Rp {{ number_format($clu->total_paid,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="o-card">
        <div class="card-head">
            <div class="hico amber"><i class="bi bi-list-ul"></i></div>
            Detail Invoice Terlambat {{ $year }}
            <div class="card-head-right"><span style="font-size:.72rem;color:var(--neutral-400)">{{ $lateInvoiceDetail->total() }} invoice</span></div>
        </div>
        <div class="card-body" style="padding:14px 20px">
            <form method="GET" class="sub-filter">
                @foreach(['year','month','cf_month','cf_status','cf_type','bk_room','bk_status','bk_month','tk_status','tk_priority','tk_category','tk_month'] as $p)
                    @if(request($p)||in_array($p,['year','month']))<input type="hidden" name="{{ $p }}" value="{{ $$p??request($p) }}">@endif
                @endforeach
                <label>Bulan:</label>
                <select name="lt_month">
                    <option value="all">Semua</option>
                    @foreach(range(1,12) as $m)<option value="{{ $m }}" {{ request('lt_month')==$m?'selected':'' }}>{{ \Carbon\Carbon::create(null,$m)->format('M') }}</option>@endforeach
                </select>
                <button type="submit" class="btn-sub-apply"><i class="bi bi-search"></i> Filter</button>
            </form>
        </div>
        <div class="tbl-wrap">
            <table class="admin-table">
                <thead><tr><th>No. Invoice</th><th>Tenant</th><th>Kamar</th><th>Tagihan</th><th>Jatuh Tempo</th><th>Dibayar</th><th>Hari Telat</th></tr></thead>
                <tbody>
                    @forelse($lateInvoiceDetail as $li)
                    @php $dl = $li->paid_at && $li->due_date ? (int)$li->paid_at->diffInDays($li->due_date) : 0; @endphp
                    <tr style="{{ $dl>14?'background:var(--red-pale)':($dl>7?'background:var(--amber-pale)':'') }}">
                        <td style="font-weight:700;font-size:.74rem;font-family:monospace">{{ $li->invoice_number }}</td>
                        <td>
                            <div style="font-weight:600">{{ $li->user?->name ?? '-' }}</div>
                            <div style="font-size:.68rem;color:var(--neutral-400)">{{ $li->user?->email }}</div>
                        </td>
                        <td style="font-weight:700">{{ $li->room?->room_number ?? '-' }}</td>
                        <td style="font-weight:700">Rp {{ number_format($li->total_amount,0,',','.') }}</td>
                        <td style="font-size:.75rem">{{ $li->due_date?->format('d M Y') }}</td>
                        <td style="font-size:.75rem;color:var(--green-600)">{{ $li->paid_at?->format('d M Y') }}</td>
                        <td><span class="badge {{ $dl>14?'badge-red':($dl>7?'badge-amber':'badge-gray') }}">{{ $dl }} hari</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--neutral-400);font-size:.82rem">Tidak ada data keterlambatan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lateInvoiceDetail->hasPages())
        <div class="pagi">
            @foreach($lateInvoiceDetail->onEachSide(1)->links()->elements[0] ?? [] as $page => $url)
                <a href="{{ $url }}" class="{{ $lateInvoiceDetail->currentPage()==$page?'active':'' }}">{{ $page }}</a>
            @endforeach
            <span class="pagi-info">{{ $lateInvoiceDetail->firstItem() }}–{{ $lateInvoiceDetail->lastItem() }} dari {{ $lateInvoiceDetail->total() }}</span>
        </div>
        @endif
    </div>

    <div class="section-divider">
        <div class="section-divider-icon"><i class="bi bi-headset"></i></div>
        <div class="section-divider-body">
            <div class="section-divider-title">Keluhan & Performa Layanan</div>
            <div class="section-divider-sub">Rekap, tren, MTTR, dan riwayat detail setiap tiket keluhan penghuni</div>
        </div>
        <div class="section-divider-export">
            <a href="{{ route('admin.reports.export', ['section'=>'tiket','year'=>$year]) }}" class="btn-export-sm btn-export-xl">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('admin.reports.export', ['section'=>'tiket','year'=>$year,'format'=>'pdf']) }}" class="btn-export-sm btn-export-pdf">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="info-box">
        <i class="bi bi-info-circle-fill"></i>
        <p>Kepuasan penghuni berdampak langsung pada tingkat perpanjangan sewa.</p>
    </div>

    @if($urgentOpenTickets->count())
    <div class="alert-strip danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><strong>{{ $urgentOpenTickets->count() }} tiket urgent masih terbuka.</strong> Perlu penanganan segera.</span>
    </div>
    <div class="o-card" style="margin-bottom:16px">
        <div class="card-head"><div class="hico red"><i class="bi bi-bell-fill"></i></div>Tiket Urgent Belum Selesai</div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
            @foreach($urgentOpenTickets as $ut)
            <div class="ticket-row priority-urgent" style="display:flex;align-items:center;gap:10px">
                <div style="flex:1;min-width:0">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap">
                        <span style="font-weight:700;font-size:.82rem">{{ $ut->subject }}</span>
                        <span class="badge badge-red">Urgent</span>
                        <span class="badge badge-amber">{{ ucfirst($ut->status) }}</span>
                    </div>
                    <div style="font-size:.71rem;color:var(--neutral-400)">
                        {{ $ut->ticket_number }} &middot; Tenant: <strong>{{ $ut->user?->name }}</strong> &middot; {{ $ut->category }}
                        &middot; Dibuat: {{ $ut->created_at->diffForHumans() }}
                    </div>
                </div>
                @if($ut->assignee)<span style="font-size:.72rem;color:var(--blue);white-space:nowrap">PIC: {{ $ut->assignee->name }}</span>@endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mini-stats" style="grid-template-columns:repeat(4,1fr)">
        <div class="mini-stat"><div class="mini-stat-val">{{ $totalTickets }}</div><div class="mini-stat-lbl">Total Tiket {{ $year }}</div></div>
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--green-600)">{{ $resolvedTickets }}</div><div class="mini-stat-lbl">Terselesaikan</div></div>
        <div class="mini-stat"><div class="mini-stat-val" style="color:var(--amber)">{{ $openTickets }}</div><div class="mini-stat-lbl">Masih Terbuka</div></div>
        <div class="mini-stat"><div class="mini-stat-val" style="color:{{ $avgMttr<=24?'var(--green-600)':'var(--red)' }}">{{ $avgMttr }}j</div><div class="mini-stat-lbl">Rata-rata MTTR</div></div>
    </div>

    <div class="grid-2">
        <div class="o-card">
            <div class="card-head"><div class="hico violet"><i class="bi bi-bar-chart"></i></div>Tiket per Bulan {{ $year }}</div>
            <div class="card-body">
                <canvas id="ticketMonthlyChart" height="160"></canvas>
                @php $resolutionRate = $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100) : 0; @endphp
                <div class="insight-box">
                    <span class="insight-tag {{ $resolutionRate>=80?'tag-good':($resolutionRate>=60?'tag-warn':'tag-bad') }}">
                        {{ $resolutionRate>=80?'Baik':($resolutionRate>=60?'Sedang':'Perlu Perhatian') }}
                    </span>
                    Tingkat penyelesaian: <strong>{{ $resolutionRate }}%</strong>.
                    MTTR: <strong>{{ $avgMttr }} jam{{ $avgMttr>24?' (melebihi target)':'' }}</strong>.
                    @if($avgRating) Rating: <strong>{{ $avgRating }}/5</strong>.@endif
                </div>
            </div>
        </div>

        <div class="o-card">
            <div class="card-head"><div class="hico violet"><i class="bi bi-tags"></i></div>Per Kategori & Prioritas</div>
            <div class="card-body">
                @php
                    $catColors = ['maintenance'=>'#f59e0b','payment'=>'#2563eb','facility'=>'#2ab36b','complaint'=>'#dc2626','other'=>'#8fa89d'];
                    $maxCat = $ticketsByCategory->max('count') ?: 1;
                @endphp
                @foreach($ticketsByCategory as $tc)
                @php $cc = $catColors[$tc->category] ?? '#8fa89d'; @endphp
                <div style="margin-bottom:14px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;font-size:.78rem">
                        <span style="font-weight:600;color:var(--neutral-700)">{{ ucfirst($tc->category) }}</span>
                        <div style="display:flex;align-items:center;gap:8px">
                            @if($tc->avg_rating)<span style="color:var(--amber);font-size:.7rem">{{ number_format($tc->avg_rating,1) }}/5</span>@endif
                            <span class="badge badge-gray">{{ $tc->count }} tiket</span>
                            <span class="badge badge-green">{{ $tc->resolved_count ?? 0 }} selesai</span>
                        </div>
                    </div>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-fill" style="width:{{ round(($tc->count/$maxCat)*100) }}%;background:{{ $cc }}"></div>
                    </div>
                </div>
                @endforeach

                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:16px">
                    @foreach([['urgent','Urgent','var(--red)','var(--red-pale)'],['high','Tinggi','#f97316','#fff7ed'],['normal','Normal','var(--amber)','var(--amber-pale)'],['low','Rendah','var(--green-600)','var(--green-50)']] as [$key,$lbl,$col,$bg])
                    <div style="text-align:center;padding:10px 8px;background:{{ $bg }};border-radius:9px">
                        <div style="font-family:'Fraunces',serif;font-size:1.4rem;font-weight:700;color:{{ $col }}">{{ $ticketsByPriority->get($key)?->count ?? 0 }}</div>
                        <div style="font-size:.61rem;font-weight:600;color:{{ $col }};text-transform:uppercase;letter-spacing:.05em;margin-top:2px">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>

                <div style="margin-top:14px;padding:11px 14px;background:var(--neutral-50);border-radius:10px;display:flex;justify-content:space-between;border:1px solid var(--neutral-200)">
                    <span style="font-size:.76rem;color:var(--neutral-600);font-weight:600">MTTR Rata-rata</span>
                    <span style="font-weight:700;color:{{ $avgMttr<=24?'var(--green-700)':'var(--red)' }};font-size:.9rem">{{ $avgMttr }} jam</span>
                </div>
            </div>
        </div>
    </div>

    <div class="o-card">
        <div class="card-head">
            <div class="hico violet"><i class="bi bi-list-task"></i></div>
            Riwayat Detail Tiket {{ $year }}
            <div class="card-head-right"><span style="font-size:.72rem;color:var(--neutral-400)">{{ $ticketDetail->total() }} tiket ditemukan</span></div>
        </div>
        <div class="card-body" style="padding:14px 20px">
            <form method="GET" class="sub-filter">
                @foreach(['year','month','cf_month','cf_status','cf_type','bk_room','bk_status','bk_month','lt_month'] as $p)
                    @if(request($p)||in_array($p,['year','month']))<input type="hidden" name="{{ $p }}" value="{{ $$p??request($p) }}">@endif
                @endforeach
                <label>Status:</label>
                <select name="tk_status">
                    <option value="all">Semua</option>
                    @foreach(['open','in_progress','pending','resolved','closed'] as $s)
                    <option value="{{ $s }}" {{ request('tk_status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
                <label>Prioritas:</label>
                <select name="tk_priority">
                    <option value="all">Semua</option>
                    @foreach(['urgent','high','normal','low'] as $p)<option value="{{ $p }}" {{ request('tk_priority')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>@endforeach
                </select>
                <label>Kategori:</label>
                <select name="tk_category">
                    <option value="all">Semua</option>
                    @foreach($ticketCategories as $tc)<option value="{{ $tc }}" {{ request('tk_category')==$tc?'selected':'' }}>{{ ucfirst($tc) }}</option>@endforeach
                </select>
                <label>Bulan:</label>
                <select name="tk_month">
                    <option value="all">Semua</option>
                    @foreach(range(1,12) as $m)<option value="{{ $m }}" {{ request('tk_month')==$m?'selected':'' }}>{{ \Carbon\Carbon::create(null,$m)->format('M') }}</option>@endforeach
                </select>
                <button type="submit" class="btn-sub-apply"><i class="bi bi-search"></i> Filter</button>
            </form>
        </div>

        <div style="padding:0 14px 14px">
            @forelse($ticketDetail as $tk)
            @php
                $pc = 'priority-'.($tk->priority??'normal');
                $sb = match($tk->status) { 'open'=>'badge-red','in_progress'=>'badge-blue','pending'=>'badge-amber','resolved'=>'badge-green','closed'=>'badge-gray',default=>'badge-gray' };
                $sl = match($tk->status) { 'open'=>'Open','in_progress'=>'In Progress','pending'=>'Pending','resolved'=>'Resolved','closed'=>'Closed',default=>$tk->status };
                $pb = match($tk->priority??'normal') { 'urgent'=>'badge-red','high'=>'badge-orange','normal'=>'badge-amber','low'=>'badge-green',default=>'badge-gray' };
                $mt = $tk->resolved_at ? round($tk->created_at->diffInMinutes($tk->resolved_at)/60,1) : null;
            @endphp
            <div class="ticket-row {{ $pc }}">
                <div style="display:flex;align-items:flex-start;gap:12px">
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:5px">
                            <span style="font-weight:700;font-size:.82rem;color:var(--neutral-900)">{{ $tk->subject }}</span>
                            <span class="badge {{ $pb }}">{{ ucfirst($tk->priority??'normal') }}</span>
                            <span class="badge {{ $sb }}">{{ $sl }}</span>
                            @if($tk->category)<span class="badge badge-gray">{{ ucfirst($tk->category) }}</span>@endif
                        </div>
                        <div style="font-size:.72rem;color:var(--neutral-500);margin-bottom:8px">
                            <strong>{{ $tk->ticket_number }}</strong>
                            &middot; Tenant: <strong>{{ $tk->user?->name ?? '—' }}</strong>
                            @if($tk->assignee) &middot; PIC: <strong>{{ $tk->assignee->name }}</strong>@endif
                            &middot; Dibuat: {{ $tk->created_at->format('d M Y') }}
                            @if($tk->resolved_at) &middot; Selesai: {{ $tk->resolved_at->format('d M Y ') }}@endif
                        </div>
                        @if($tk->description)
                        <div style="font-size:.74rem;color:var(--neutral-600);background:var(--neutral-50);border-radius:8px;padding:8px 12px;border-left:3px solid var(--neutral-200);line-height:1.55;margin-bottom:8px">
                            {{ \Str::limit($tk->description, 200) }}
                        </div>
                        @endif
                        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                            @if($mt!==null)
                                <span style="font-size:.7rem;font-weight:600;color:{{ $mt<=24?'var(--green-600)':'var(--red)' }}">
                                    <i class="bi bi-clock"></i> MTTR: {{ $mt }} jam
                                </span>
                            @endif
                            @if($tk->rating)<span style="font-size:.7rem;font-weight:600;color:var(--amber)">Rating: {{ $tk->rating }}/5</span>@endif
                            @if($tk->replies?->count())<span style="font-size:.7rem;color:var(--neutral-400)"><i class="bi bi-chat"></i> {{ $tk->replies->count() }} balasan</span>@endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:40px 16px;color:var(--neutral-400)">
                <i class="bi bi-ticket-perforated" style="font-size:2rem;display:block;opacity:.2;margin-bottom:10px"></i>
                <p style="font-size:.82rem">Tidak ada tiket dengan filter ini</p>
            </div>
            @endforelse
        </div>

        @if($ticketDetail->hasPages())
        <div class="pagi">
            @foreach($ticketDetail->onEachSide(1)->links()->elements[0] ?? [] as $page => $url)
                <a href="{{ $url }}" class="{{ $ticketDetail->currentPage()==$page?'active':'' }}">{{ $page }}</a>
            @endforeach
            <span class="pagi-info">{{ $ticketDetail->firstItem() }}–{{ $ticketDetail->lastItem() }} dari {{ $ticketDetail->total() }}</span>
        </div>
        @endif
    </div>

</div>
</main>
</div>

<script>
Chart.defaults.font.family = "'Instrument Sans', sans-serif";
Chart.defaults.font.size   = 11;

const months = @json(array_column($monthlyData, 'month'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label: 'Lunas (Rp)', data: @json(array_column($monthlyData,'revenue')), backgroundColor: 'rgba(42,179,107,.8)', borderRadius: 5 },
            { label: 'Belum Bayar (Rp)', data: @json(array_column($monthlyData,'unpaid')), backgroundColor: 'rgba(245,158,11,.55)', borderRadius: 5 },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { x: { stacked: false }, y: { beginAtZero: true, ticks: { callback: v => 'Rp '+(v/1000000).toFixed(1)+'jt' } } }
    }
});

new Chart(document.getElementById('bookingTrendChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label: 'Total',      data: @json(array_column($bookingTrendMonthly,'total')),     backgroundColor: 'rgba(37,99,235,.45)', borderRadius: 5 },
            { label: 'Disetujui',  data: @json(array_column($bookingTrendMonthly,'approved')),  backgroundColor: 'rgba(42,179,107,.8)',  borderRadius: 5 },
            { label: 'Dibatalkan', data: @json(array_column($bookingTrendMonthly,'cancelled')), backgroundColor: 'rgba(220,38,38,.6)',   borderRadius: 5 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

new Chart(document.getElementById('latePayChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{ label: 'Invoice Terlambat', data: @json(array_column($latePaymentMonthly,'late_count')), backgroundColor: 'rgba(245,158,11,.8)', borderRadius: 5 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

new Chart(document.getElementById('lateDaysChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            { label: 'Avg Hari Terlambat', data: @json(array_column($latePaymentMonthly,'avg_days_late')), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,.1)', tension: .4, fill: true, pointBackgroundColor: '#f59e0b', pointRadius: 4 },
            { label: 'Maks Hari Terlambat', data: @json(array_column($latePaymentMonthly,'max_days_late')), borderColor: '#dc2626', backgroundColor: 'transparent', tension: .4, borderDash: [5,3], pointRadius: 3 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { callback: v => v+' hari' } } } }
});

new Chart(document.getElementById('ticketMonthlyChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label: 'Total',   data: @json(array_column($ticketMonthly,'total')),    backgroundColor: 'rgba(124,58,237,.45)', borderRadius: 5 },
            { label: 'Selesai', data: @json(array_column($ticketMonthly,'resolved')), backgroundColor: 'rgba(42,179,107,.8)',  borderRadius: 5 },
            { label: 'Urgent',  data: @json(array_column($ticketMonthly,'urgent')),   backgroundColor: 'rgba(220,38,38,.7)',   borderRadius: 5 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>

</body>
</html>