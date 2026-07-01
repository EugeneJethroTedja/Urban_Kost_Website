<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin — Urban Kost</title>
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
    --neutral-300: #c2d4cc;
    --neutral-400: #8fa89d;
    --neutral-500: #5f7a6e;
    --neutral-600: #3e5549;
    --neutral-700: #2d4038;
    --neutral-900: #0f1f18;
    --accent:      #f4a535;
    --red:         #ef4444;
    --red-lt:      #fef2f2;
    --blue:        #3b82f6;
    --blue-lt:     #eff6ff;
    --violet:      #7c3aed;
    --violet-lt:   #ede9fe;
    --amber:       #f59e0b;
    --amber-lt:    #fffbeb;
    --shadow-sm:   0 1px 4px rgba(15,31,24,.06);
    --shadow-md:   0 4px 20px rgba(15,31,24,.09);
    --shadow-lg:   0 12px 48px rgba(15,31,24,.13);
    --card-r:      14px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh;
    font-size: 14px;
    -webkit-font-smoothing: antialiased;
}

::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--green-50); }
::-webkit-scrollbar-thumb { background: var(--green-400); border-radius: 3px; }

.page-wrap {
    max-width: 1080px;
    margin: 0 auto;
    padding: 28px 20px 64px;
}

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.page-header h1 {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(1.5rem, 3vw, 2rem);
    color: var(--neutral-900);
    letter-spacing: -.025em;
    line-height: 1.15;
}

.page-header h1 em {
    font-style: italic;
    font-weight: 400;
    color: var(--green-500);
}

.page-header-sub {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .875rem;
    color: var(--neutral-500);
    margin-top: 5px;
    line-height: 1.6;
}

.date-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--neutral-0);
    border: 1px solid var(--green-200);
    border-radius: 10px;
    padding: 8px 16px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .8rem;
    font-weight: 600;
    color: var(--neutral-600);
    white-space: nowrap;
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
    align-self: flex-start;
}

.date-chip i { color: var(--green-500); }

.stat-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 22px;
}

.stat-card {
    background: var(--neutral-0);
    border: 1px solid var(--green-100);
    border-radius: var(--card-r);
    padding: 16px 15px 14px;
    position: relative;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}

.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--card-r) var(--card-r) 0 0;
}

.stat-card.c-green::before   { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
.stat-card.c-emerald::before { background: linear-gradient(90deg, #10b981, #34d399); }
.stat-card.c-blue::before    { background: linear-gradient(90deg, var(--blue), #60a5fa); }
.stat-card.c-amber::before   { background: linear-gradient(90deg, var(--amber), #fcd34d); }
.stat-card.c-violet::before  { background: linear-gradient(90deg, var(--violet), #a78bfa); }
.stat-card.c-red::before     { background: linear-gradient(90deg, var(--red), #f87171); }

.stat-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    margin-bottom: 10px;
}

.stat-card.c-green   .stat-icon { background: var(--green-50);  color: var(--green-700); border: 1px solid var(--green-100); }
.stat-card.c-emerald .stat-icon { background: #d1fae5;          color: #065f46;           border: 1px solid #a7f3d0; }
.stat-card.c-blue    .stat-icon { background: var(--blue-lt);   color: var(--blue);       border: 1px solid #bfdbfe; }
.stat-card.c-amber   .stat-icon { background: var(--amber-lt);  color: #92400e;           border: 1px solid #fde68a; }
.stat-card.c-violet  .stat-icon { background: var(--violet-lt); color: var(--violet);     border: 1px solid #ddd6fe; }
.stat-card.c-red     .stat-icon { background: var(--red-lt);    color: var(--red);        border: 1px solid #fecaca; }

.stat-val {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.5rem, 3vw, 1.9rem);
    font-weight: 700;
    color: var(--neutral-900);
    line-height: 1;
    letter-spacing: -.03em;
}

.stat-lbl {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .65rem;
    font-weight: 600;
    color: var(--neutral-500);
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: .07em;
}

.stat-trend {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .65rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 99px;
    margin-top: 7px;
    width: fit-content;
}

.stat-trend.up     { background: var(--green-50);  color: var(--green-700); border: 1px solid var(--green-100); }
.stat-trend.warn   { background: var(--amber-lt);  color: #92400e;          border: 1px solid #fde68a; }
.stat-trend.danger { background: var(--red-lt);    color: var(--red);       border: 1px solid #fecaca; }

.main-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 18px;
}

.left-col  { display: flex; flex-direction: column; gap: 16px; min-width: 0; }
.right-col { display: flex; flex-direction: column; gap: 16px; min-width: 0; }

.dcard {
    background: var(--neutral-0);
    border-radius: var(--card-r);
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.dcard-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--green-100);
    background: var(--green-50);
}

.dcard-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Fraunces', serif;
    font-size: .95rem;
    font-weight: 600;
    color: var(--neutral-800);
    letter-spacing: -.01em;
}

.dcard-title i { color: var(--green-500); font-size: .9rem; }

.dcard-action {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .75rem;
    font-weight: 600;
    color: var(--green-600);
    text-decoration: none;
    background: var(--neutral-0);
    border: 1px solid var(--green-200);
    border-radius: 8px;
    padding: 5px 11px;
    transition: all .2s;
    white-space: nowrap;
}

.dcard-action:hover { background: var(--green-100); color: var(--green-700); }

.dcard-body { padding: 18px; }
.dcard-body.p0 { padding: 0; }

.tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

.dtbl { width: 100%; border-collapse: collapse; min-width: 480px; }
.dtbl thead tr { border-bottom: 1px solid var(--green-100); }
.dtbl thead th {
    padding: 10px 14px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .65rem;
    font-weight: 600;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: var(--neutral-500);
    background: var(--green-50);
    white-space: nowrap;
    text-align: left;
}

.dtbl tbody tr {
    border-bottom: 1px solid var(--green-50);
    transition: background .15s;
    cursor: pointer;
}

.dtbl tbody tr:last-child { border-bottom: none; }
.dtbl tbody tr:hover { background: #f5fdf9; }
.dtbl tbody td {
    padding: 13px 14px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .825rem;
    vertical-align: middle;
}

.booking-code { font-weight: 600; color: var(--green-700); font-size: .825rem; }
.tenant-name  { font-weight: 600; font-size: .825rem; color: var(--neutral-900); }
.tenant-email { font-size: .73rem; color: var(--neutral-400); margin-top: 2px; }

.room-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--green-50);
    color: var(--green-800);
    font-weight: 600;
    font-size: .72rem;
    padding: 3px 9px;
    border-radius: 7px;
    border: 1px solid var(--green-200);
}

.date-txt { font-size: .73rem; color: var(--neutral-500); }

.tbl-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid var(--neutral-200);
    color: var(--neutral-500);
    text-decoration: none;
    font-size: .9rem;
    transition: all .2s;
}

.tbl-action:hover { background: var(--green-600); color: var(--neutral-0); border-color: var(--green-600); }

.empty-row td {
    text-align: center;
    padding: 40px;
    color: var(--neutral-400);
    font-size: .825rem;
}

.st-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 99px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
    border: 1px solid transparent;
}

.st-badge .dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.st-occupied { background: var(--green-50);  color: var(--green-800); border-color: var(--green-200); }
.st-occupied .dot { background: var(--green-500); }
.st-waiting  { background: #fef9c3; color: #854d0e; border-color: #fde047; }
.st-waiting .dot  { background: #ca8a04; }
.st-pending  { background: var(--blue-lt); color: #1e40af; border-color: #bfdbfe; }
.st-pending .dot  { background: var(--blue); }
.st-rejected { background: var(--red-lt);  color: #991b1b; border-color: #fca5a5; }
.st-rejected .dot { background: var(--red); }
.st-done     { background: var(--neutral-100); color: var(--neutral-500); border-color: var(--neutral-200); }
.st-done .dot { background: var(--neutral-400); }

.occ-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 14px; }
.occ-pct {
    font-family: 'Fraunces', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--neutral-900);
    letter-spacing: -.04em;
    line-height: 1;
}
.occ-label {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .65rem;
    font-weight: 600;
    color: var(--neutral-400);
    margin-top: 3px;
    text-transform: uppercase;
    letter-spacing: .07em;
}

.occ-track { height: 8px; border-radius: 99px; background: var(--green-50); border: 1px solid var(--green-100); overflow: hidden; margin-bottom: 14px; }
.occ-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--green-500), var(--green-400)); }

.occ-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 7px; text-align: center; }
.occ-item {
    background: var(--green-50);
    border: 1px solid var(--green-100);
    border-radius: 10px;
    padding: 10px 4px;
}
.occ-num {
    font-family: 'Fraunces', serif;
    font-size: 1.4rem;
    font-weight: 700;
    letter-spacing: -.03em;
    line-height: 1;
}
.occ-lbl {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .62rem;
    font-weight: 600;
    color: var(--neutral-400);
    margin-top: 3px;
    text-transform: uppercase;
    letter-spacing: .07em;
}
.occ-num.avail  { color: var(--green-600); }
.occ-num.filled { color: var(--red); }
.occ-num.other  { color: var(--neutral-500); }

.qa-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .825rem;
    font-weight: 600;
    color: var(--neutral-700);
    text-decoration: none;
    border: 1px solid var(--neutral-200);
    background: var(--neutral-0);
    transition: all .2s;
    width: 100%;
    margin-bottom: 7px;
}

.qa-btn:last-child { margin-bottom: 0; }
.qa-btn:hover {
    border-color: var(--green-300);
    background: var(--green-50);
    color: var(--green-700);
    transform: translateX(2px);
}

.qa-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}

.qa-label { flex: 1; min-width: 0; }

.qa-badge {
    margin-left: auto;
    font-size: .65rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 99px;
    background: var(--red);
    color: var(--neutral-0);
    flex-shrink: 0;
}

.qa-badge.amber { background: var(--amber); }

.act-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 12px 18px;
    border-bottom: 1px solid var(--green-50);
}

.act-item:last-child { border-bottom: none; }

.act-dot {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    flex-shrink: 0;
    margin-top: 1px;
}

.act-dot.green  { background: var(--green-50);  color: var(--green-700); border: 1px solid var(--green-100); }
.act-dot.amber  { background: var(--amber-lt);  color: #92400e; border: 1px solid #fde68a; }
.act-dot.red    { background: var(--red-lt);    color: var(--red); border: 1px solid #fecaca; }
.act-dot.blue   { background: var(--blue-lt);   color: var(--blue); border: 1px solid #bfdbfe; }

.act-text {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .8rem;
    color: var(--neutral-600);
    font-weight: 500;
    line-height: 1.5;
}

.act-text strong { font-weight: 700; color: var(--neutral-900); }
.act-time {
    font-family: 'Instrument Sans', sans-serif;
    font-size: .68rem;
    color: var(--neutral-400);
    margin-top: 2px;
}

/* ===== HERO BANNER (sinkron dengan rooms/index) ===== */
.hero-banner {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 32px 28px;
    margin-bottom: 24px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.hero-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}

.hero-left { position: relative; z-index: 1; }
.hero-right { position: relative; z-index: 1; flex-shrink: 0; }

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.82);
    margin-bottom: 14px;
}

.hero-eyebrow .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--green-300);
    flex-shrink: 0;
}

.hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.5rem, 4vw, 2.1rem);
    font-weight: 700;
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
    font-size: .88rem;
    color: rgba(255,255,255,.65);
}

.hero-date {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.92);
    border: none;
    border-radius: 10px;
    padding: 11px 18px;
    font-size: .82rem;
    font-weight: 600;
    color: var(--green-900);
    white-space: nowrap;
    box-shadow: 0 2px 10px rgba(0,0,0,.12);
    transition: all .2s;
}

.hero-date i { color: var(--green-700); }
.hero-date:hover {
    background: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 18px rgba(0,0,0,.16);
}

@media (max-width: 768px) {
    .hero-banner { flex-direction: column; padding: 22px 20px; }
    .hero-right { width: 100%; }
    .hero-date { width: 100%; justify-content: center; }
}

@media (max-width: 640px) {
    .hero-banner { padding: 20px 18px; }
    .hero-right { width: 100%; }
    .hero-date { width: 100%; justify-content: center; }
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: none; }
}

.fade-up { opacity: 0; animation: fadeUp .45s ease forwards; }
.d1 { animation-delay: .04s; }
.d2 { animation-delay: .09s; }
.d3 { animation-delay: .14s; }
.d4 { animation-delay: .19s; }
.d5 { animation-delay: .24s; }
.d6 { animation-delay: .29s; }
.d7 { animation-delay: .34s; }
.d8 { animation-delay: .39s; }

@media (max-width: 1080px) {
    .stat-row { grid-template-columns: repeat(3, 1fr); }
    .main-grid { grid-template-columns: 1fr 280px; }
}

@media (max-width: 860px) {
    .main-grid { grid-template-columns: 1fr; }
    .right-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
}

@media (max-width: 640px) {
    .page-wrap { padding: 16px 14px 52px; }
    .stat-row { grid-template-columns: repeat(2, 1fr); gap: 9px; }
    .page-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .right-col { grid-template-columns: 1fr; }
    .dtbl thead th, .dtbl tbody td { padding: 9px 10px; }
}

@media (max-width: 400px) {
    .occ-pct { font-size: 1.8rem; }
    .stat-val { font-size: 1.4rem; }
}

@media (hover: none) {
    .stat-card:hover { transform: none; box-shadow: var(--shadow-sm); }
    .qa-btn:hover { transform: none; }
}
</style>
</head>
<body>

@include('partials.admin-sidebar')

@php
    $occupancyRate = $stats['total_rooms'] > 0
        ? round(($stats['occupied_rooms'] / $stats['total_rooms']) * 100) : 0;
    $otherRooms = $stats['total_rooms'] - ($stats['available_rooms'] ?? 0) - ($stats['occupied_rooms'] ?? 0);
    $pendingBook    = \App\Models\Booking::where('status','pending')->count();
    $pendingRenewal = \App\Models\Renewal::whereIn('status',['pending_admin','pending_payment'])->count();
    $pendingPay     = \App\Models\Invoice::where('status','pending_verification')->count();
    $openTkt        = \App\Models\Ticket::whereIn('status',['open','in_progress'])->count();
@endphp

<div class="page-wrap">
    <div class="hero-banner fade-up d1">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Dashboard Admin
            </div>
            <h1 class="hero-title">
                Selamat datang, <em>{{ Str::words(auth()->user()->name ?? 'Admin', 1, '') }}</em>
            </h1>
            <p class="hero-sub">Ringkasan aktivitas Urban Kost hari ini</p>
        </div>
        <div class="hero-right">
            <div class="hero-date">
                <i class="bi bi-calendar3"></i>
                <span id="liveDateLabel"></span>
            </div>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-card c-green fade-up d1">
            <div class="stat-icon"><i class="bi bi-house-door-fill"></i></div>
            <div class="stat-val">{{ $stats['total_rooms'] }}</div>
            <div class="stat-lbl">Total Kamar</div>
            <div class="stat-trend up"><i class="bi bi-check2-circle"></i> Terdaftar</div>
        </div>
        <div class="stat-card c-emerald fade-up d2">
            <div class="stat-icon"><i class="bi bi-door-open-fill"></i></div>
            <div class="stat-val">{{ $stats['available_rooms'] }}</div>
            <div class="stat-lbl">Kamar Tersedia</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-short"></i> Siap disewa</div>
        </div>
        <div class="stat-card c-blue fade-up d3">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-val">{{ $stats['total_tenants'] }}</div>
            <div class="stat-lbl">Total Penghuni</div>
            <div class="stat-trend up"><i class="bi bi-person-check"></i> Aktif</div>
        </div>
        <div class="stat-card c-amber fade-up d4">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-val">{{ $stats['pending_bookings'] }}</div>
            <div class="stat-lbl">Booking Pending</div>
            @if($stats['pending_bookings'] > 0)
                <div class="stat-trend warn"><i class="bi bi-exclamation-circle"></i> Perlu ditinjau</div>
            @else
                <div class="stat-trend up"><i class="bi bi-check2"></i> Semua beres</div>
            @endif
        </div>
        <div class="stat-card c-violet fade-up d5">
            <div class="stat-icon"><i class="bi bi-headset"></i></div>
            <div class="stat-val">{{ $stats['open_tickets'] }}</div>
            <div class="stat-lbl">
                Tiket Aktif
                @if(($stats['urgent_tickets'] ?? 0) > 0)
                    <span style="display:inline-block;background:var(--red);color:var(--neutral-0);
                                 font-size:.6rem;font-weight:700;padding:1px 5px;
                                 border-radius:4px;margin-left:3px;vertical-align:middle">
                        {{ $stats['urgent_tickets'] }} urgent
                    </span>
                @endif
            </div>
            @if($stats['open_tickets'] > 0)
                <div class="stat-trend danger"><i class="bi bi-exclamation-triangle"></i> Perlu respons</div>
            @else
                <div class="stat-trend up"><i class="bi bi-check-circle"></i> Tidak ada keluhan</div>
            @endif
        </div>
    </div>

    <div class="main-grid">
        <div class="left-col">

            <div class="dcard fade-up d7">
                <div class="dcard-head">
                    <div class="dcard-title">
                        <i class="bi bi-calendar-check-fill"></i>
                        Booking Terbaru
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="dcard-action">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="dcard-body p0">
                    <div class="tbl-wrap">
                        <table class="dtbl">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Pemohon</th>
                                    <th>Kamar</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th style="text-align:center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                @php
                                    $st = $booking->status;
                                    $badgeCfg = match($st) {
                                        'active','approved'                         => ['cls'=>'st-occupied','icon'=>'bi-house-check-fill','text'=>'Ditempati'],
                                        'waiting_list','waiting-list','waitinglist' => ['cls'=>'st-waiting', 'icon'=>'bi-hourglass-split',  'text'=>'Antrian'],
                                        'pending'                                   => ['cls'=>'st-pending', 'icon'=>'bi-clock',             'text'=>'Menunggu'],
                                        'rejected','cancelled'                      => ['cls'=>'st-rejected','icon'=>'bi-x-circle-fill',     'text'=>'Ditolak'],
                                        'completed','selesai'                       => ['cls'=>'st-done',    'icon'=>'bi-check-circle-fill',  'text'=>'Selesai'],
                                        default                                     => ['cls'=>'st-occupied','icon'=>'bi-house-check-fill',   'text'=>ucfirst($st)],
                                    };
                                @endphp
                                <tr onclick="window.location='{{ route('admin.bookings.show', $booking->id) }}'">
                                    <td><span class="booking-code">{{ $booking->booking_code }}</span></td>
                                    <td>
                                        <div class="tenant-name">{{ $booking->full_name }}</div>
                                        <div class="tenant-email">{{ $booking->user?->email }}</div>
                                    </td>
                                    <td>
                                        <span class="room-badge">
                                            <i class="bi bi-door-closed-fill" style="font-size:.65rem"></i>
                                            {{ $booking->room?->room_number ?? '-' }}
                                        </span>
                                    </td>
                                    <td><span class="date-txt">{{ $booking->created_at->format('d M Y') }}</span></td>
                                    <td>
                                        <span class="st-badge {{ $badgeCfg['cls'] }}">
                                            <span class="dot"></span>{{ $badgeCfg['text'] }}
                                        </span>
                                    </td>
                                    <td style="text-align:center">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                           class="tbl-action"
                                           onclick="event.stopPropagation()"
                                           title="Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr class="empty-row">
                                    <td colspan="6">
                                        <i class="bi bi-calendar-x" style="display:block;font-size:1.8rem;color:var(--green-200);margin-bottom:10px"></i>
                                        Belum ada data booking
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="dcard fade-up d8">
                <div class="dcard-head">
                    <div class="dcard-title">
                        <i class="bi bi-activity"></i>
                        Aktivitas Terkini
                    </div>
                </div>
                @forelse($recentActivities ?? [] as $act)
                <div class="act-item">
                    <div class="act-dot {{ $act['color'] ?? 'green' }}">
                        <i class="bi {{ $act['icon'] ?? 'bi-circle' }}"></i>
                    </div>
                    <div>
                        <div class="act-text">{!! $act['text'] !!}</div>
                        <div class="act-time">{{ $act['time'] ?? '' }}</div>
                    </div>
                </div>
                @empty
                <div class="act-item">
                    <div class="act-dot green"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <div class="act-text">Sistem berjalan normal. Semua data terkini.</div>
                        <div class="act-time">Baru saja</div>
                    </div>
                </div>
                @endforelse
            </div>

        </div>

        <div class="right-col">

            <div class="dcard fade-up d7">
                <div class="dcard-head">
                    <div class="dcard-title">
                        <i class="bi bi-bar-chart-fill"></i>
                        Tingkat Hunian
                    </div>
                </div>
                <div class="dcard-body">
                    <div class="occ-header">
                        <div>
                            <div class="occ-pct">{{ $occupancyRate }}%</div>
                            <div class="occ-label">Kamar terisi</div>
                        </div>
                        <svg width="52" height="52" viewBox="0 0 36 36" style="transform:rotate(-90deg);flex-shrink:0">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--green-100)" stroke-width="3.5"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--green-500)" stroke-width="3.5"
                                stroke-dasharray="{{ $occupancyRate }} {{ 100 - $occupancyRate }}"
                                stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="occ-track">
                        <div class="occ-fill" style="width:{{ $occupancyRate }}%"></div>
                    </div>
                    <div class="occ-grid">
                        <div class="occ-item">
                            <div class="occ-num avail">{{ $stats['available_rooms'] }}</div>
                            <div class="occ-lbl">Tersedia</div>
                        </div>
                        <div class="occ-item">
                            <div class="occ-num filled">{{ $stats['occupied_rooms'] }}</div>
                            <div class="occ-lbl">Terisi</div>
                        </div>
                        <div class="occ-item">
                            <div class="occ-num other">{{ $otherRooms }}</div>
                            <div class="occ-lbl">Lainnya</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dcard fade-up d8">
                <div class="dcard-head">
                    <div class="dcard-title">
                        <i class="bi bi-lightning-charge-fill"></i>
                        Aksi Cepat
                    </div>
                </div>
                <div class="dcard-body">
                    <a href="{{ route('admin.rooms.create') }}" class="qa-btn">
                        <div class="qa-icon" style="background:var(--green-50);color:var(--green-700);border:1px solid var(--green-100)">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                        <span class="qa-label">Tambah Kamar Baru</span>
                    </a>
                    <a href="{{ route('admin.tenants.create') }}" class="qa-btn">
                        <div class="qa-icon" style="background:var(--blue-lt);color:var(--blue);border:1px solid #bfdbfe">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <span class="qa-label">Daftarkan Penghuni</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:var(--amber-lt);color:#92400e;border:1px solid #fde68a">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <span class="qa-label">Review Booking</span>
                        @if($pendingBook > 0)
                            <span class="qa-badge">{{ $pendingBook }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.renewals.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <span class="qa-label">Perpanjangan Sewa</span>
                        @if($pendingRenewal > 0)
                            <span class="qa-badge amber">{{ $pendingRenewal }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.invoices.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:var(--red-lt);color:var(--red);border:1px solid #fecaca">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <span class="qa-label">Verifikasi Pembayaran</span>
                        @if($pendingPay > 0)
                            <span class="qa-badge amber">{{ $pendingPay }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.tickets.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:var(--violet-lt);color:var(--violet);border:1px solid #ddd6fe">
                            <i class="bi bi-headset"></i>
                        </div>
                        <span class="qa-label">Tiket Keluhan</span>
                        @if($openTkt > 0)
                            <span class="qa-badge">{{ $openTkt }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="qa-btn">
                        <div class="qa-icon" style="background:var(--neutral-100);color:var(--neutral-500);border:1px solid var(--neutral-200)">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <span class="qa-label">Laporan dan Analitik</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
(function(){
    var el = document.getElementById('liveDateLabel');
    function tick(){
        if(!el) return;
        el.textContent = new Date().toLocaleDateString('id-ID',{
            weekday:'long',day:'numeric',month:'long',year:'numeric'
        });
    }
    tick();
    setInterval(tick, 60000);
})();
</script>

</body>
</html>