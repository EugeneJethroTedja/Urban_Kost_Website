<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner — Urban Kost</title>
    <meta name="description" content="Dashboard Owner Urban Kost">
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
    .owner-page  { min-height: 100vh; display: flex; flex-direction: column; }
    .owner-main  { flex: 1; padding: 32px 0 64px; width: 100%; }
    .owner-inner { max-width: 1280px; margin: 0 auto; padding: 0 24px; width: 100%; }
    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 36px 32px;
        margin-bottom: 28px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    }
    .hero-banner::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .hero-content { position: relative; z-index: 1; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 5px 14px; border-radius: 100px;
        font-size: .68rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82);
        margin-bottom: 14px;
    }
    .hero-eyebrow .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--green-300); flex-shrink: 0;
    }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 8px;
    }
    .hero-title em {
        font-style: italic;
        font-weight: 400;
        color: rgba(255,255,255,.72);
    }
    .hero-sub {
        font-size: .9rem;
        color: rgba(255,255,255,.62);
        font-weight: 400;
        max-width: 400px;
        line-height: 1.6;
    }
    .hero-date {
        margin-top: 18px;
        display: inline-flex; align-items: center; gap: 7px;
        padding: 6px 14px; border-radius: 10px;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.78);
        font-size: .73rem; font-weight: 500;
    }
    .hero-arc {
        position: absolute; right: -100px; top: -100px;
        width: 380px; height: 380px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none; z-index: 1;
    }
    .alert-strip {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 13px 16px; border-radius: 10px;
        margin-bottom: 12px;
        font-size: .82rem; font-weight: 500;
        border: 1px solid; line-height: 1.55;
    }
    .alert-strip.warn {
        background: var(--amber-pale);
        border-color: rgba(245,158,11,.3);
        color: #78350f;
    }
    .alert-strip.info {
        background: var(--green-50);
        border-color: var(--green-200);
        color: var(--green-800);
    }
    .alert-strip i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (min-width: 900px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
    }
    .stat-card {
        position: relative; overflow: hidden;
        border-radius: 16px;
        padding: 20px 18px;
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        transition: border-color .25s, box-shadow .3s, transform .25s;
        cursor: default;
    }
    .stat-card:hover {
        border-color: var(--green-300);
        box-shadow: var(--shadow-lg);
        transform: translateY(-3px);
    }
    .stat-icon-wrap {
        width: 42px; height: 42px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; margin-bottom: 14px; flex-shrink: 0;
    }
    .stat-value {
        font-family: 'Fraunces', serif;
        font-size: 2rem; font-weight: 700;
        color: var(--neutral-900);
        line-height: 1; margin-bottom: 4px;
        letter-spacing: -.025em;
    }
    .stat-label {
        font-size: .68rem; font-weight: 600;
        color: var(--neutral-400);
        text-transform: uppercase; letter-spacing: .07em;
    }
    .stat-trend {
        display: inline-flex; align-items: center; gap: 4px;
        margin-top: 8px; padding: 3px 9px; border-radius: 100px;
        font-size: .62rem; font-weight: 600;
    }
    .stat-trend.up   { background: var(--green-100); color: var(--green-700); }
    .stat-trend.down { background: var(--red-pale);  color: var(--red); }
    .stat-trend.neu  { background: var(--neutral-100); color: var(--neutral-500); }
    .stat-bg-icon {
        position: absolute; right: -6px; bottom: -6px;
        font-size: 4rem; opacity: .05; line-height: 1; color: var(--neutral-900);
    }
    .section-hd {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; flex-wrap: wrap; gap: 8px;
    }
    .section-hd h2 {
        font-family: 'Fraunces', serif;
        font-size: 1.15rem; font-weight: 700;
        color: var(--neutral-900);
        letter-spacing: -.02em;
        display: flex; align-items: center; gap: 8px;
    }
    .section-hd h2 i { color: var(--green-500); font-size: .95rem; }
    .section-hd a {
        font-size: .73rem; font-weight: 600;
        color: var(--green-600);
        text-decoration: none;
        display: flex; align-items: center; gap: 4px;
        padding: 6px 13px; border-radius: 8px;
        border: 1px solid var(--green-200);
        background: var(--green-50);
        transition: all .2s;
    }
    .section-hd a:hover { background: var(--green-100); border-color: var(--green-300); }
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
        font-size: .72rem; font-weight: 600;
        color: var(--neutral-600);
        display: flex; align-items: center; gap: 7px;
        text-transform: uppercase; letter-spacing: .07em;
    }
    .o-card-header-title i { color: var(--green-500); }
    .two-col   { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 24px; }
    .three-col { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 24px; }
    @media (min-width: 900px) {
        .two-col   { grid-template-columns: 1fr 1fr; }
        .three-col { grid-template-columns: 2fr 1fr; }
    }
    .kpi-item {
        display: flex; align-items: center;
        padding: 14px 0; border-bottom: 1px solid var(--neutral-100); gap: 12px;
    }
    .kpi-item:last-child { border-bottom: none; }
    .kpi-icon {
        width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
    }
    .kpi-meta { flex: 1; min-width: 0; }
    .kpi-name   { font-size: .77rem; font-weight: 600; color: var(--neutral-700); }
    .kpi-target { font-size: .63rem; color: var(--neutral-400); margin-top: 2px; }
    .kpi-progress-bar {
        height: 4px; border-radius: 100px;
        background: var(--neutral-100); margin-top: 6px; overflow: hidden;
    }
    .kpi-progress-fill { height: 100%; border-radius: 100px; transition: width .6s ease; }
    .kpi-value-wrap { text-align: right; flex-shrink: 0; min-width: 44px; }
    .kpi-value  { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; }
    .kpi-status { font-size: .6rem; font-weight: 600; margin-top: 2px; }
    .revenue-box {
        background: linear-gradient(135deg, var(--green-900), var(--green-700), var(--green-500));
        border-radius: 14px; padding: 20px;
        color: #fff; position: relative; overflow: hidden;
        margin-top: 16px;
    }
    .revenue-box::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(134,217,174,.2) 0%, transparent 50%);
        pointer-events: none;
    }
    .revenue-box-label {
        font-size: .65rem; font-weight: 600;
        opacity: .7; text-transform: uppercase; letter-spacing: .1em;
    }
    .revenue-box-value {
        font-family: 'Fraunces', serif;
        font-size: 1.9rem; font-weight: 700;
        letter-spacing: -.025em;
        margin: 6px 0 2px;
    }
    .revenue-box-sub { font-size: .73rem; opacity: .65; }
    .revenue-box-tax {
        margin-top: 12px; padding: 8px 12px;
        background: rgba(255,255,255,.12);
        border-radius: 9px;
        border: 1px solid rgba(255,255,255,.18);
        font-size: .72rem; font-weight: 500; line-height: 1.55;
    }
    .admin-table { width: 100%; border-collapse: collapse; min-width: 520px; }
    .admin-table th {
        padding: 10px 14px;
        font-size: .63rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--neutral-400);
        background: var(--neutral-50);
        border-bottom: 1px solid var(--neutral-100);
        text-align: left;
    }
    .admin-table td {
        padding: 13px 14px;
        border-bottom: 1px solid var(--neutral-100);
        font-size: .8rem; color: var(--neutral-600);
        vertical-align: middle;
    }
    .admin-table tr:last-child td { border-bottom: none; }
    .admin-table tr:hover td { background: var(--green-50); }
    .admin-name  { font-weight: 600; color: var(--neutral-900); }
    .admin-email { font-size: .72rem; color: var(--neutral-400); }
    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 100px;
        font-size: .63rem; font-weight: 600;
    }
    .badge-status::before {
        content: ''; width: 5px; height: 5px; border-radius: 50%;
        background: currentColor; display: inline-block;
    }
    .badge-status.on  { background: var(--green-100); color: var(--green-700); }
    .badge-status.off { background: var(--red-pale); color: var(--red); }
    .btn-toggle {
        padding: 5px 12px; border-radius: 8px;
        font-size: .7rem; font-weight: 600;
        border: 1px solid; cursor: pointer;
        transition: all .2s;
        display: inline-flex; align-items: center; gap: 5px;
        white-space: nowrap;
    }
    .btn-toggle.warn { border-color: var(--amber); color: #78350f; background: var(--amber-pale); }
    .btn-toggle.warn:hover { background: var(--amber); color: #fff; }
    .btn-toggle.go { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }
    .btn-toggle.go:hover { background: var(--green-500); color: #fff; }
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px; padding: 16px;
    }
    .qa-btn {
        display: flex; align-items: center; gap: 10px;
        padding: 13px 14px; border-radius: 12px;
        text-decoration: none; border: 1px solid;
        transition: all .2s;
    }
    .qa-btn-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
    }
    .qa-btn-text { font-size: .76rem; font-weight: 600; color: inherit; line-height: 1.3; }
    .qa-btn-sub  { font-size: .61rem; opacity: .65; margin-top: 2px; }
    .qa-green { border-color: var(--green-200); color: var(--green-800); background: var(--green-50); }
    .qa-green .qa-btn-icon { background: var(--green-100); color: var(--green-700); }
    .qa-green:hover { background: var(--green-100); border-color: var(--green-300); transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .qa-amber { border-color: rgba(245,158,11,.3); color: #78350f; background: var(--amber-pale); }
    .qa-amber .qa-btn-icon { background: rgba(245,158,11,.15); color: var(--amber); }
    .qa-amber:hover { background: rgba(245,158,11,.2); transform: translateY(-2px); }
    .qa-blue { border-color: rgba(37,99,235,.2); color: #1e3a8a; background: var(--blue-pale); }
    .qa-blue .qa-btn-icon { background: rgba(37,99,235,.12); color: var(--blue); }
    .qa-blue:hover { background: rgba(37,99,235,.12); transform: translateY(-2px); }
    .qa-neutral { border-color: var(--neutral-200); color: var(--neutral-700); background: var(--neutral-0); }
    .qa-neutral .qa-btn-icon { background: var(--neutral-100); color: var(--neutral-500); }
    .qa-neutral:hover { border-color: var(--green-300); background: var(--green-50); color: var(--green-800); transform: translateY(-2px); }
    .table-footer-actions {
        padding: 16px 20px;
        border-top: 1px solid var(--neutral-100);
        display: flex; gap: 10px; flex-wrap: wrap;
    }
    .btn-primary-sm {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 20px; border-radius: 9px;
        font-size: .8rem; font-weight: 600;
        background: var(--green-500); color: #fff;
        text-decoration: none;
        box-shadow: 0 2px 10px rgba(42,179,107,.28);
        transition: all .2s;
        border: none;
    }
    .btn-primary-sm:hover { background: var(--green-600); color: #fff; transform: translateY(-1px); }
    .btn-ghost-sm {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 20px; border-radius: 9px;
        font-size: .8rem; font-weight: 600;
        border: 1px solid var(--green-200);
        color: var(--green-700); background: var(--green-50);
        text-decoration: none; transition: all .2s;
    }
    .btn-ghost-sm:hover { background: var(--green-100); border-color: var(--green-300); }
    .avatar-circle {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--green-500), var(--green-400));
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .72rem; font-weight: 700;
        box-shadow: 0 2px 8px rgba(42,179,107,.3);
    }
    @media (max-width: 480px) {
        .owner-inner { padding: 0 14px; }
        .hero-banner { padding: 22px 18px; }
        .hero-arc    { display: none; }
        .hero-title  { font-size: 1.25rem; }
        .stats-grid  { gap: 10px; }
        .stat-card   { padding: 14px 12px; }
        .stat-value  { font-size: 1.6rem; }
        .quick-actions { grid-template-columns: 1fr; gap: 8px; padding: 12px; }
        .revenue-box-value { font-size: 1.6rem; }
        .kpi-item { gap: 8px; }
    }
    @media (min-width: 481px) and (max-width: 899px) {
        .owner-inner { padding: 0 20px; }
    }
    @media (min-width: 900px) {
        .owner-inner { padding: 0 40px; }
        .owner-main  { padding: 40px 0 64px; }
    }
    @media (min-width: 1280px) {
        .owner-inner { padding: 0 48px; }
    }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@include('partials.owner-sidebar')

<div class="owner-page">
<main class="owner-main">

@php
    $ownerName     = auth()->user()->name ?? 'Owner';
    $totalRooms    = $stats['total_rooms']     ?? 0;
    $availRooms    = $stats['available_rooms'] ?? 0;
    $occupiedRooms = $totalRooms - $availRooms;
    $totalTenants  = $stats['total_tenants']   ?? 0;
    $totalAdmins   = $stats['total_admins']    ?? 0;
    $occRate       = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

    $curYear  = now()->year;
    $curMonth = now()->month;
    $totalInv = \App\Models\Invoice::whereYear('period_start', $curYear)->whereMonth('period_start', $curMonth)->count();
    $lateInv  = \App\Models\Invoice::where('status', 'paid')->whereYear('period_start', $curYear)->whereMonth('period_start', $curMonth)->whereRaw('paid_at > due_date')->count()
              + \App\Models\Invoice::where('status', 'overdue')->whereYear('period_start', $curYear)->whereMonth('period_start', $curMonth)->count();
    $lateRate = $totalInv > 0 ? round(($lateInv / $totalInv) * 100) : 0;

    $totalTkt = \App\Models\Ticket::whereYear('created_at', $curYear)->whereMonth('created_at', $curMonth)->count();
    $resvTkt  = \App\Models\Ticket::where('status', 'resolved')->whereYear('created_at', $curYear)->whereMonth('created_at', $curMonth)->count();
    $resvRate = $totalTkt > 0 ? round(($resvTkt / $totalTkt) * 100) : 100;

    // MTTR tiket yang selesai bulan ini — pakai menit lalu dikonversi ke jam dengan
    // 1 desimal, supaya tiket yang selesai dalam hitungan menit tidak terbaca "0 jam".
    $resolvedThisMonth = \App\Models\Ticket::whereNotNull('resolved_at')->whereYear('resolved_at', $curYear)->whereMonth('resolved_at', $curMonth)->get();
    $hasMttrData = $resolvedThisMonth->isNotEmpty();
    $avgMttr = $hasMttrData
        ? round($resolvedThisMonth->avg(fn($t) => $t->created_at->diffInMinutes($t->resolved_at) / 60), 1)
        : 0;

    $ytdRev  = \App\Models\Invoice::where('status', 'paid')
                    ->whereYear('period_start', now()->year)
                    ->sum('total_amount');
    $ytdTax  = max(0, $ytdRev - 500000000) * 0.005;
    $taxWarn = $ytdRev >= 400000000;

    $pendingBook = \App\Models\Booking::where('status', 'pending')->count();
    $openTkt     = \App\Models\Ticket::whereIn('status', ['open', 'in_progress'])->count();
    $pendingPay  = \App\Models\Invoice::where('status', 'pending_verification')->count();

    $lpOk   = $totalInv == 0 || $lateRate <= 20;
    $trOk   = $totalTkt == 0 || $resvRate >= 100;
    $mttrOk = !$hasMttrData || $avgMttr <= 24;
    $raOk   = $availRooms > 0;
@endphp

<div class="owner-inner">

    @if($taxWarn)
    <div class="alert-strip warn">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>Peringatan Pajak — YTD Revenue mendekati atau melebihi Rp 400 juta. Segera konsultasikan dengan akuntan Anda.</span>
    </div>
    @endif

    @if($pendingBook > 0 || $openTkt > 0)
    <div class="alert-strip info">
        <i class="bi bi-info-circle-fill"></i>
        <span>
            @if($pendingBook > 0)
                {{ $pendingBook }} booking menunggu konfirmasi
            @endif
            @if($pendingBook > 0 && $openTkt > 0)
                &middot;
            @endif
            @if($openTkt > 0)
                {{ $openTkt }} tiket belum terselesaikan
            @endif
        </span>
    </div>
    @endif

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Dashboard Owner
            </div>
            <h1 class="hero-title">
                Selamat Datang,<br><em>{{ $ownerName }}</em>
            </h1>
            <p class="hero-sub">Pantau performa hunian Anda secara menyeluruh.</p>
            <div class="hero-date">
                <i class="bi bi-calendar3"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: var(--green-100); color: var(--green-600);">
                <i class="bi bi-house-door-fill"></i>
            </div>
            <div class="stat-value">{{ $totalRooms }}</div>
            <div class="stat-label">Total Kamar</div>
            <div class="stat-trend neu">
                Urban Kost
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: rgba(78,201,138,.15); color: var(--green-500);">
                <i class="bi bi-door-open-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--green-600);">{{ $availRooms }}</div>
            <div class="stat-label">Kamar Tersedia</div>
            <div class="stat-trend {{ $availRooms > 0 ? 'up' : 'down' }}">
                <i class="bi bi-{{ $availRooms > 0 ? 'check-circle' : 'x-circle' }}-fill"></i>
                {{ $availRooms > 0 ? 'Siap disewa' : 'Penuh' }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: var(--amber-pale); color: #92400e;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value" style="color: #92400e;">{{ $totalTenants }}</div>
            <div class="stat-label">Total Penghuni</div>
            <div class="stat-trend up">
                <i class="bi bi-person-check-fill"></i>Aktif
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background: var(--blue-pale); color: var(--blue);">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--blue);">{{ $totalAdmins }}</div>
            <div class="stat-label">Admin Aktif</div>
            <div class="stat-trend neu">
                <i class="bi bi-shield-check"></i>Terdaftar
            </div>
        </div>
    </div>

    <div class="three-col">

        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title">
                    <i class="bi bi-bar-chart-fill"></i>Target KPI Bulan Ini (Indikator Kunci untuk Mengukur Performa)
                </span>
                <span style="font-size: .68rem; color: var(--neutral-400); font-weight: 500;">
                    {{ now()->translatedFormat('F Y') }}
                </span>
            </div>
            <div style="padding: 8px 22px 22px;">
                <div class="kpi-item">
                    <div class="kpi-icon" style="background: {{ $trOk ? 'var(--green-100)' : 'var(--amber-pale)' }}; color: {{ $trOk ? 'var(--green-700)' : 'var(--amber)' }};">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-name">Penyelesaian Tiket</div>
                        @if($totalTkt > 0)
                        <div class="kpi-target">Target: 100% &middot; {{ $resvTkt }}/{{ $totalTkt }} tiket</div>
                        @else
                        <div class="kpi-target">Belum ada tiket masuk bulan ini</div>
                        @endif
                        <div class="kpi-progress-bar">
                            <div class="kpi-progress-fill" style="width: {{ min($resvRate, 100) }}%; background: {{ $trOk ? 'var(--green-500)' : 'var(--amber)' }};"></div>
                        </div>
                    </div>
                    <div class="kpi-value-wrap">
                        @if($totalTkt > 0)
                        <div class="kpi-value" style="color: {{ $trOk ? 'var(--green-600)' : 'var(--amber)' }};">{{ $resvRate }}%</div>
                        <div class="kpi-status" style="color: {{ $trOk ? 'var(--green-600)' : 'var(--amber)' }};">{{ $trOk ? 'Selesai' : 'Proses' }}</div>
                        @else
                        <div class="kpi-value" style="color: var(--neutral-400);">&mdash;</div>
                        <div class="kpi-status" style="color: var(--neutral-400);">Tidak ada data</div>
                        @endif
                    </div>
                </div>

                <div class="kpi-item">
                    <div class="kpi-icon" style="background: {{ $mttrOk ? 'var(--green-100)' : 'var(--red-pale)' }}; color: {{ $mttrOk ? 'var(--green-700)' : 'var(--red)' }};">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-name">MTTR Tiket</div>
                        @if($hasMttrData)
                        <div class="kpi-target">Target: &lt; 24 jam rata-rata &middot; {{ $resolvedThisMonth->count() }} tiket selesai bulan ini</div>
                        @else
                        <div class="kpi-target">Belum ada tiket yang selesai bulan ini</div>
                        @endif
                        <div class="kpi-progress-bar">
                            <div class="kpi-progress-fill" style="width: {{ min(($avgMttr / 48) * 100, 100) }}%; background: {{ $mttrOk ? 'var(--green-500)' : 'var(--red)' }};"></div>
                        </div>
                    </div>
                    <div class="kpi-value-wrap">
                        @if($hasMttrData)
                        <div class="kpi-value" style="color: {{ $mttrOk ? 'var(--green-600)' : 'var(--red)' }};">{{ $avgMttr }}j</div>
                        <div class="kpi-status" style="color: {{ $mttrOk ? 'var(--green-600)' : 'var(--red)' }};">{{ $mttrOk ? 'Cepat' : 'Lambat' }}</div>
                        @else
                        <div class="kpi-value" style="color: var(--neutral-400);">&mdash;</div>
                        <div class="kpi-status" style="color: var(--neutral-400);">Tidak ada data</div>
                        @endif
                    </div>
                </div>

                <div class="revenue-box">
                    <div class="revenue-box-label">YTD Revenue {{ now()->year }} (TOTAL PENDAPATAN DARI AWAL TAHUN HINGGA SAAT INI)</div>
                    <div class="revenue-box-value">Rp {{ number_format($ytdRev / 1000000, 1) }} jt</div>
                    <div class="revenue-box-sub">Total pendapatan tahun berjalan</div>
                    <div class="revenue-box-tax">
                        <i class="bi bi-calculator"></i>
                        Estimasi PPh Final 0.5%:
                        <strong>Rp {{ number_format($ytdTax / 1000, 1) }} rb</strong>
                        @if($taxWarn)
                            &middot; <span style="color: #fde68a;">Mendekati batas</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">

            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-grid-fill"></i>Aksi Cepat
                    </span>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('owner.admins.create') }}" class="qa-btn qa-green">
                        <div class="qa-btn-icon"><i class="bi bi-person-plus-fill"></i></div>
                        <div>
                            <div class="qa-btn-text">Tambah Admin</div>
                            <div class="qa-btn-sub">Kelola akses sistem</div>
                        </div>
                    </a>
                    <a href="{{ route('owner.analytics') }}" class="qa-btn qa-blue">
                        <div class="qa-btn-icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <div>
                            <div class="qa-btn-text">Lihat Analitik</div>
                            <div class="qa-btn-sub">Laporan lengkap</div>
                        </div>
                    </a>
                    <a href="{{ route('owner.tax.index') }}" class="qa-btn qa-amber">
                        <div class="qa-btn-icon"><i class="bi bi-calculator-fill"></i></div>
                        <div>
                            <div class="qa-btn-text">Cek Pajak</div>
                            <div class="qa-btn-sub">PPh Final UMKM</div>
                        </div>
                    </a>
                    <a href="{{ route('owner.analytics') }}" class="qa-btn qa-neutral">
                        <div class="qa-btn-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></div>
                        <div>
                            <div class="qa-btn-text">Laporan Kamar</div>
                            <div class="qa-btn-sub">Status hunian</div>
                        </div>
                    </a>
                </div>
            </div>

            @if($pendingBook > 0 || $pendingPay > 0 || $openTkt > 0)
            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-bell-fill"></i>Perlu Perhatian
                    </span>
                </div>
                <div style="padding: 8px 0;">
                    @if($pendingBook > 0)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 11px 20px; border-bottom: 1px solid var(--neutral-100);">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 9px; background: var(--amber-pale); color: var(--amber); display: flex; align-items: center; justify-content: center; font-size: .85rem;">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>
                            <div>
                                <div style="font-size: .78rem; font-weight: 600; color: var(--neutral-800);">Booking Pending</div>
                                <div style="font-size: .65rem; color: var(--neutral-400);">Menunggu konfirmasi</div>
                            </div>
                        </div>
                        <span style="font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--amber);">{{ $pendingBook }}</span>
                    </div>
                    @endif
                    @if($pendingPay > 0)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 11px 20px; border-bottom: 1px solid var(--neutral-100);">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 9px; background: var(--blue-pale); color: var(--blue); display: flex; align-items: center; justify-content: center; font-size: .85rem;">
                                <i class="bi bi-credit-card-fill"></i>
                            </div>
                            <div>
                                <div style="font-size: .78rem; font-weight: 600; color: var(--neutral-800);">Verifikasi Pembayaran</div>
                                <div style="font-size: .65rem; color: var(--neutral-400);">Menunggu verifikasi</div>
                            </div>
                        </div>
                        <span style="font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--blue);">{{ $pendingPay }}</span>
                    </div>
                    @endif
                    @if($openTkt > 0)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 11px 20px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 9px; background: var(--red-pale); color: var(--red); display: flex; align-items: center; justify-content: center; font-size: .85rem;">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </div>
                            <div>
                                <div style="font-size: .78rem; font-weight: 600; color: var(--neutral-800);">Tiket Terbuka</div>
                                <div style="font-size: .65rem; color: var(--neutral-400);">Belum terselesaikan</div>
                            </div>
                        </div>
                        <span style="font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--red);">{{ $openTkt }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

    </div>

    <div class="section-hd">
        <h2><i class="bi bi-people-fill"></i>Admin Terdaftar</h2>
        <a href="{{ route('owner.admins.index') }}">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="o-card" style="margin-bottom: 36px;">
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Admin</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $i => $admin)
                    <tr>
                        <td style="color: var(--neutral-400); font-size: .72rem; font-weight: 500;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div class="admin-name">{{ $admin->name }}</div>
                            </div>
                        </td>
                        <td><span class="admin-email">{{ $admin->email }}</span></td>
                        <td>
                            <span class="badge-status {{ $admin->is_active ? 'on' : 'off' }}">
                                {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td style="font-size: .72rem; color: var(--neutral-400);">
                            {{ $admin->created_at ? $admin->created_at->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('owner.admins.toggle', $admin->id) }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn-toggle {{ $admin->is_active ? 'warn' : 'go' }}">
                                    <i class="bi bi-{{ $admin->is_active ? 'pause' : 'play' }}-circle-fill"></i>
                                    {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px 16px;">
                            <div style="color: var(--neutral-400); font-size: .85rem;">
                                <div style="font-size: 2rem; color: var(--green-200); margin-bottom: 10px;">
                                    <i class="bi bi-person-x"></i>
                                </div>
                                Belum ada admin terdaftar.
                                <a href="{{ route('owner.admins.create') }}" style="color: var(--green-600); font-weight: 600;">Tambah sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer-actions">
            <a href="{{ route('owner.admins.create') }}" class="btn-primary-sm">
                <i class="bi bi-person-plus-fill"></i>Tambah Admin Baru
            </a>
            <a href="{{ route('owner.admins.index') }}" class="btn-ghost-sm">
                <i class="bi bi-list-ul"></i>Semua Admin
            </a>
        </div>
    </div>

</div>

</main>
</div>

</body>
</html>