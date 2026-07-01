<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tagihan Saya — Urban Kost</title>
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
    --red-50:      #fff5f5;
    --red-100:     #fee2e2;
    --red-600:     #dc2626;
    --red-700:     #b91c1c;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-100:   #fef3c7;
    --amber-500:   #f59e0b;
    --amber-600:   #d97706;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-100:    #dbeafe;
    --blue-600:    #2563eb;
    --nav-h:       64px;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
    --r-card: 16px;
    --r-pill: 999px;
    --g50:  var(--green-50);
    --g100: var(--green-100);
    --g200: var(--green-200);
    --g300: var(--green-300);
    --g400: var(--green-400);
    --g500: var(--green-500);
    --g600: var(--green-600);
    --g700: var(--green-700);
    --g800: var(--green-800);
    --g900: var(--green-900);
    --n0:   var(--neutral-0);
    --n50:  var(--neutral-50);
    --n100: var(--neutral-100);
    --n200: var(--neutral-200);
    --n400: var(--neutral-400);
    --n500: var(--neutral-500);
    --n600: var(--neutral-600);
    --n900: var(--neutral-900);
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
.page-bg {
    position: fixed; inset: 0; z-index: -1; overflow: hidden;
    background: var(--green-50);
}
.page-bg::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%),
        radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
}
.page-bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}
.page-wrap { max-width: 980px; margin: 0 auto; padding: 28px 20px 64px; }

.hero-banner {
    position: relative; overflow: hidden; border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 32px 28px; margin-bottom: 24px;
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
.hero-banner-dots {
    position: absolute; inset: 0; opacity: .03;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 28px 28px; pointer-events: none;
}
.hero-arc {
    position: absolute; right: -100px; top: -100px;
    width: 380px; height: 380px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1); pointer-events: none; z-index: 1;
}
.hero-content { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: var(--r-pill);
    font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82); margin-bottom: 14px;
}
.hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: 'Fraunces', serif; font-weight: 700;
    font-size: clamp(1.5rem, 4vw, 2.1rem);
    color: #fff; line-height: 1.15; letter-spacing: -.025em; margin-bottom: 8px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .9rem; color: rgba(255,255,255,.62); font-weight: 400; max-width: 440px; line-height: 1.6; }
.hero-status {
    display: inline-flex; align-items: center; gap: 7px;
    margin-top: 18px; padding: 7px 16px; border-radius: var(--r-pill);
    font-size: .78rem; font-weight: 600;
}
.hero-status.warn   { background: rgba(244,165,53,.18); border: 1px solid rgba(244,165,53,.35); color: #fde68a; }
.hero-status.ok     { background: rgba(255,255,255,.13); border: 1px solid rgba(255,255,255,.2);  color: rgba(255,255,255,.88); }
.hero-status.danger { background: rgba(220,38,38,.2);   border: 1px solid rgba(220,38,38,.35);   color: #fca5a5; }
.hero-card-float {
    position: absolute; right: 32px; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,.14); backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,.22); border-radius: 18px;
    padding: 20px 24px; text-align: center; min-width: 150px; z-index: 2;
}
.hero-card-lbl  { font-size: .63rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.55); margin-bottom: 6px; }
.hero-card-num  { font-family: 'Fraunces', serif; font-size: 1.6rem; font-weight: 700; color: #fff; line-height: 1; letter-spacing: -.025em; }
.hero-card-sub  { font-size: .68rem; color: rgba(255,255,255,.5); margin-top: 5px; }

.stats-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 14px; margin-bottom: 22px;
}
@media (min-width: 860px) { .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; } }

.stat-card {
    position: relative; overflow: hidden; border-radius: var(--r-card);
    padding: 18px 16px; background: var(--neutral-0);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    transition: border-color .25s, box-shadow .3s, transform .25s;
}
.stat-card:hover { border-color: var(--green-300); box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: var(--r-card) var(--r-card) 0 0;
}
.stat-card.c-amber::before { background: linear-gradient(90deg, #fcd34d, var(--amber-500)); }
.stat-card.c-green::before { background: linear-gradient(90deg, var(--green-400), var(--green-500)); }
.stat-card.c-red::before   { background: linear-gradient(90deg, #f87171, var(--red-600)); }
.stat-card.c-blue::before  { background: linear-gradient(90deg, #60a5fa, var(--blue-600)); }
.stat-icon-wrap {
    width: 40px; height: 40px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; margin-bottom: 12px;
}
.stat-value {
    font-family: 'Fraunces', serif; font-size: 1.85rem; font-weight: 700;
    color: var(--neutral-900); line-height: 1; margin-bottom: 4px; letter-spacing: -.025em;
}
.stat-label { font-size: .68rem; font-weight: 600; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .07em; }
.stat-bg-icon {
    position: absolute; right: -6px; bottom: -6px;
    font-size: 4rem; opacity: .04; line-height: 1; color: var(--neutral-900); pointer-events: none;
}

.section-hd {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px; flex-wrap: wrap; gap: 8px;
}
.section-hd h2 {
    font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700;
    color: var(--neutral-900); letter-spacing: -.02em;
    display: flex; align-items: center; gap: 8px;
}
.section-hd h2 i { color: var(--green-500); font-size: .95rem; }

.o-card {
    background: var(--neutral-0); border-radius: var(--r-card);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden;
}
.o-card-header {
    padding: 14px 18px; border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.o-card-header-title {
    font-size: .72rem; font-weight: 600; color: var(--neutral-600);
    display: flex; align-items: center; gap: 7px;
    text-transform: uppercase; letter-spacing: .07em;
}
.o-card-header-title i { color: var(--green-500); }

.filter-bar {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 14px; overflow-x: auto; padding-bottom: 2px;
    scrollbar-width: none;
}
.filter-bar::-webkit-scrollbar { display: none; }
.filter-lbl {
    font-size: .7rem; font-weight: 600; color: var(--neutral-400);
    text-transform: uppercase; letter-spacing: .06em; flex-shrink: 0;
}
.filter-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: var(--r-pill);
    font-size: .76rem; font-weight: 600;
    border: 1px solid var(--green-200); background: var(--neutral-0);
    color: var(--neutral-500); text-decoration: none;
    transition: all .18s; white-space: nowrap; flex-shrink: 0;
}
.filter-pill:hover, .filter-pill.active {
    background: var(--green-600); border-color: var(--green-600); color: #fff;
}
.filter-cnt {
    background: var(--green-100); color: var(--green-700);
    border-radius: var(--r-pill); padding: 1px 7px;
    font-size: .62rem; font-weight: 700; transition: all .18s;
}
.filter-pill.active .filter-cnt { background: rgba(255,255,255,.22); color: #fff; }
.filter-pill:hover .filter-cnt  { background: rgba(255,255,255,.22); color: #fff; }

.inv-row {
    display: grid; grid-template-columns: 46px 1fr auto auto;
    align-items: center; gap: 14px; padding: 16px 18px;
    border-bottom: 1px solid var(--neutral-100); transition: background .14s;
    position: relative;
}
.inv-row:last-child { border-bottom: none; }
.inv-row:hover { background: var(--green-50); }
.inv-row.is-overdue { border-left: 3px solid var(--red-600); }
.inv-row.is-unpaid  { border-left: 3px solid var(--amber-500); }
.inv-row.is-pending { border-left: 3px solid var(--green-400); }
.inv-row.is-paid    { border-left: 3px solid var(--green-200); }

.inv-ico {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem; flex-shrink: 0; transition: transform .2s;
}
.inv-row:hover .inv-ico { transform: scale(1.06) rotate(-3deg); }
.ii-paid    { background: var(--green-100); color: var(--green-700); }
.ii-pending { background: var(--green-50); color: var(--green-600); border: 1px solid var(--green-200); }
.ii-unpaid  { background: var(--amber-pale); color: var(--amber-600); border: 1px solid var(--amber-100); }
.ii-overdue { background: var(--red-50); color: var(--red-600); border: 1px solid var(--red-100); }
.ii-overdue-pulse {
    position: relative;
}
.ii-overdue-pulse::after {
    content: ''; position: absolute; top: -3px; right: -3px;
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--red-600); border: 2px solid var(--neutral-0);
    animation: pulse-dot 1.6s ease-in-out infinite;
}
@keyframes pulse-dot { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.3);opacity:.7} }

.inv-content { min-width: 0; }
.inv-num {
    font-family: 'Fraunces', serif; font-size: .86rem; font-weight: 700;
    color: var(--neutral-900); margin-bottom: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.inv-meta { font-size: .72rem; color: var(--neutral-400); line-height: 1.5; }
.inv-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 6px; }

.itag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .64rem; font-weight: 600; padding: 2px 9px; border-radius: var(--r-pill); border: 1px solid;
}
.itag-overdue { background: var(--red-50);    color: var(--red-700);    border-color: var(--red-100); }
.itag-unpaid  { background: var(--amber-pale); color: var(--amber-600); border-color: var(--amber-100); }
.itag-pending { background: var(--green-50);  color: var(--green-700);  border-color: var(--green-200); }
.itag-paid    { background: var(--green-100); color: var(--green-800);  border-color: var(--green-200); }

.inv-right { text-align: right; flex-shrink: 0; }
.inv-amount {
    font-family: 'Fraunces', serif; font-size: .98rem; font-weight: 700;
    color: var(--neutral-900); line-height: 1; margin-bottom: 4px; white-space: nowrap;
}
.inv-amount.danger { color: var(--red-600); }
.inv-due { font-size: .66rem; color: var(--neutral-400); margin-top: 3px; }

.inv-action { flex-shrink: 0; }
.btn-pay {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px;
    font-size: .76rem; font-weight: 600; text-decoration: none;
    background: var(--green-500); color: #fff;
    border: none; cursor: pointer; transition: all .2s;
    box-shadow: 0 2px 10px rgba(42,179,107,.28);
    white-space: nowrap;
}
.btn-pay:hover { background: var(--green-600); color: #fff; transform: translateY(-1px); }
.btn-wait {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px;
    font-size: .76rem; font-weight: 600;
    background: var(--green-50); color: var(--green-600);
    border: 1px solid var(--green-200); cursor: not-allowed; white-space: nowrap;
}
.btn-view {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px;
    font-size: .76rem; font-weight: 600; text-decoration: none;
    background: var(--neutral-0); color: var(--neutral-600);
    border: 1px solid var(--neutral-200); transition: all .2s; white-space: nowrap;
}
.btn-view:hover { border-color: var(--green-300); color: var(--green-700); background: var(--green-50); }

.empty-state { text-align: center; padding: 64px 24px; }
.empty-icon {
    width: 60px; height: 60px; border-radius: 50%;
    background: var(--green-50); border: 2px dashed var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: var(--green-300); margin: 0 auto 16px;
}
.empty-state h3 { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; color: var(--neutral-500); margin-bottom: 6px; }
.empty-state p  { font-size: .8rem; color: var(--neutral-400); }

.inv-pagination {
    padding: 14px 18px; border-top: 1px solid var(--neutral-100);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
}
.pagination-info { font-size: .74rem; color: var(--neutral-400); font-weight: 500; }
.inv-pagination nav { display: flex; align-items: center; }
.inv-pagination nav > div,
.inv-pagination nav ul,
.inv-pagination nav ol {
    display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
    list-style: none; padding: 0; margin: 0;
}
.inv-pagination nav li { list-style: none; }
.inv-pagination nav span,
.inv-pagination nav a,
.inv-pagination nav li span,
.inv-pagination nav li a {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 34px; height: 34px; padding: 0 10px; border-radius: 9px;
    font-size: .76rem; font-weight: 600; text-decoration: none;
    border: 1px solid var(--green-200); background: var(--neutral-0);
    color: var(--neutral-600); transition: all .18s;
}
.inv-pagination nav a:hover,
.inv-pagination nav li a:hover {
    background: var(--green-600); border-color: var(--green-600); color: #fff;
}
.inv-pagination nav span[aria-current="page"],
.inv-pagination nav li.active span {
    background: var(--green-600); border-color: var(--green-600); color: #fff;
}
.inv-pagination nav span.disabled,
.inv-pagination nav li.disabled span {
    background: var(--green-50); border-color: var(--green-100); color: var(--neutral-400); cursor: not-allowed;
}

.info-strip {
    background: var(--green-50); border: 1px solid var(--green-200);
    border-radius: 12px; padding: 14px 18px; margin-top: 20px;
    display: grid; grid-template-columns: repeat(3, 1fr);
}
.info-strip-item { padding: 12px 16px; border-right: 1px solid var(--green-100); text-align: center; }
.info-strip-item:last-child { border-right: none; }
.info-strip-icon { font-size: 1.2rem; color: var(--green-400); margin-bottom: 6px; }
.info-strip-title { font-size: .76rem; font-weight: 600; color: var(--neutral-700); margin-bottom: 2px; }
.info-strip-val   { font-size: .7rem; color: var(--green-600); font-weight: 500; }

.alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    font-size: .82rem; border: 1px solid; margin-bottom: 16px; line-height: 1.55;
}
.alert i { flex-shrink: 0; margin-top: 2px; }
.alert-success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-warning  { background: var(--amber-pale); border-color: var(--amber-100); color: #78350f; }
.alert-error    { background: var(--red-50);  border-color: var(--red-100);  color: var(--red-700); }

@media (max-width: 860px) {
    .hero-card-float { display: none; }
    .hero-banner { padding: 26px 22px; }
}
@media (max-width: 700px) {
    .inv-row { grid-template-columns: 42px 1fr auto; grid-template-rows: auto auto; gap: 8px 10px; padding: 14px 14px; }
    .inv-ico     { grid-row: 1 / 3; align-self: center; width: 40px; height: 40px; font-size: 1rem; }
    .inv-content { grid-row: 1; grid-column: 2; }
    .inv-right   { grid-row: 2; grid-column: 2; text-align: left; }
    .inv-action  { grid-row: 1 / 3; grid-column: 3; align-self: center; }
    .info-strip  { grid-template-columns: 1fr; }
    .info-strip-item { border-right: none; border-bottom: 1px solid var(--green-100); }
    .info-strip-item:last-child { border-bottom: none; }
}
@media (max-width: 480px) {
    .page-wrap { padding: 16px 12px 64px; }
    .stats-grid { gap: 10px; }
    .stat-card  { padding: 14px 12px; }
    .stat-value { font-size: 1.6rem; }
    .hero-arc   { display: none; }
    .hero-title { font-size: 1.3rem; }
}
@media (min-width: 900px)  { .page-wrap { padding: 36px 32px 64px; } }
@media (min-width: 1280px) { .page-wrap { padding: 36px 32px 64px; } }
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-wrap">

    @foreach(['success','warning','error'] as $t)
        @if(session($t))
        <div class="alert alert-{{ $t }}">
            <i class="bi bi-{{ $t === 'success' ? 'check-circle-fill' : ($t === 'error' ? 'exclamation-circle-fill' : 'exclamation-triangle-fill') }}"></i>
            <div>{!! session($t) !!}</div>
        </div>
        @endif
    @endforeach

    <div class="hero-banner">
        <div class="hero-banner-dots"></div>
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Kelola Tagihan</div>
            <h1 class="hero-title">Tagihan <em>Saya</em></h1>
            <p class="hero-sub">Pantau status pembayaran dan kelola tagihan sewa Anda.</p>
            @if($stats['unpaid'] > 0)
                <div class="hero-status warn">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $stats['unpaid'] }} tagihan menunggu pembayaran
                </div>
            @elseif($stats['pending'] > 0)
                <div class="hero-status ok">
                    <i class="bi bi-hourglass-split"></i>
                    {{ $stats['pending'] }} tagihan sedang diproses
                </div>
            @else
                <div class="hero-status ok">
                    <i class="bi bi-check-circle-fill"></i>
                    Semua tagihan sudah lunas
                </div>
            @endif
        </div>
        <div class="hero-card-float">
            <div class="hero-card-lbl">Total Dibayar</div>
            <div class="hero-card-num">Rp {{ number_format($stats['total_paid_amount'] / 1000000, 1) }}jt</div>
            <div class="hero-card-sub">{{ $stats['paid'] }} tagihan lunas</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card c-amber">
            <div class="stat-icon-wrap" style="background:var(--amber-pale);color:var(--amber-600)">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div class="stat-value" style="color:var(--amber-600)">{{ $stats['unpaid'] }}</div>
            <div class="stat-label">Belum Dibayar</div>
        </div>
        <div class="stat-card c-green">
            <div class="stat-icon-wrap" style="background:var(--green-100);color:var(--green-700)">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value" style="color:var(--green-700)">{{ $stats['paid'] }}</div>
            <div class="stat-label">Sudah Lunas</div>
        </div>
        <div class="stat-card c-blue">
            <div class="stat-icon-wrap" style="background:var(--blue-pale);color:var(--blue-600)">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-value" style="color:var(--blue-600);font-size:1.3rem">Rp {{ number_format($stats['total_paid_amount'] / 1000000, 1) }}jt</div>
            <div class="stat-label">Total Dibayar</div>
        </div>
    </div>

    <div class="section-hd">
        <h2><i class="bi bi-receipt-cutoff"></i>Daftar Tagihan</h2>
    </div>

    <div class="filter-bar">
        <span class="filter-lbl">Filter:</span>
        <a href="{{ route('tenant.invoices.index') }}"
           class="filter-pill {{ !request('status') ? 'active' : '' }}">
            Semua <span class="filter-cnt">{{ $invoices->total() }}</span>
        </a>
    </div>

    <div class="o-card">
        <div class="o-card-header">
            <span class="o-card-header-title">
                <i class="bi bi-receipt-cutoff"></i>Tagihan Saya
            </span>
            <span style="font-size:.7rem;font-weight:600;color:var(--neutral-400)">{{ $invoices->total() }} tagihan</span>
        </div>

        @forelse($invoices as $inv)
        @php
            $daysUntil = $inv->due_date ? now()->startOfDay()->diffInDays($inv->due_date->startOfDay(), false) : null;
            $isOverdue = (method_exists($inv,'isOverdue') ? $inv->isOverdue() : ($inv->status === 'overdue'))
                        && ($daysUntil !== null && $daysUntil < 0);
            $isPending = $inv->status === 'pending_verification';
            $isPaid    = $inv->status === 'paid';
            $isUnpaid  = in_array($inv->status, ['unpaid', 'overdue']);
            $rowClass  = $isOverdue ? 'is-overdue' : ($isPending ? 'is-pending' : ($isPaid ? 'is-paid' : 'is-unpaid'));
            $icoClass  = $isPaid    ? 'ii-paid'
                    : ($isOverdue ? 'ii-overdue ii-overdue-pulse'
                    : ($isPending  ? 'ii-pending'
                    : 'ii-unpaid'));
            $icoName   = $isPaid    ? 'bi-check-circle-fill'
                    : ($isOverdue ? 'bi-exclamation-triangle-fill'
                    : ($isPending  ? 'bi-hourglass-split'
                    : 'bi-receipt'));
        @endphp
        <div class="inv-row {{ $rowClass }}">
            <div class="inv-ico {{ $icoClass }}">
                <i class="bi {{ $icoName }}"></i>
            </div>
            <div class="inv-content">
                <div class="inv-num">{{ $inv->invoice_number }}</div>
                <div class="inv-meta">
                    @if($inv->room) Kamar {{ $inv->room->room_number }} &nbsp;&middot;&nbsp; @endif
                    @if($inv->period_start && $inv->period_end)
                        {{ $inv->period_start->format('d M') }} &ndash; {{ $inv->period_end->format('d M Y') }}
                    @else
                        Jatuh tempo {{ $inv->due_date?->format('d M Y') }}
                    @endif
                </div>
                <div class="inv-tags">
                    @if($daysUntil === 0 && !$isPaid && !$isPending)
                        <span class="itag itag-unpaid"><i class="bi bi-clock-fill"></i> Jatuh tempo hari ini</span>
                    @elseif($isOverdue)
                        <span class="itag itag-overdue"><i class="bi bi-clock-fill"></i> Terlambat {{ abs((int)$daysUntil) }} hari</span>
                    @elseif($isPending)
                        <span class="itag itag-pending"><i class="bi bi-hourglass-split"></i> Menunggu verifikasi</span>
                    @elseif($isPaid)
                        <span class="itag itag-paid"><i class="bi bi-check-circle-fill"></i> Lunas</span>
                    @else
                        <span class="itag itag-unpaid"><i class="bi bi-circle"></i> Belum dibayar</span>
                    @endif
                </div>
            </div>
            <div class="inv-right">
                <div class="inv-amount {{ $isOverdue ? 'danger' : '' }}">
                    {{ method_exists($inv,'getFormattedTotalAttribute') ? $inv->formatted_total : 'Rp '.number_format($inv->total_amount, 0, ',', '.') }}
                </div>
                @if($inv->due_date && !$isPaid)
                    <div class="inv-due">Due {{ $inv->due_date->format('d M Y') }}</div>
                @endif
            </div>
            <div class="inv-action">
                @if($isPending)
                    <span class="btn-wait"><i class="bi bi-hourglass-split"></i> Menunggu</span>
                @elseif($isUnpaid)
                    <a href="{{ route('tenant.invoices.show', $inv->id) }}" class="btn-pay">
                        <i class="bi bi-credit-card-fill"></i> Bayar
                    </a>
                @else
                    <a href="{{ route('tenant.invoices.show', $inv->id) }}" class="btn-view">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-receipt"></i></div>
            <h3>Belum Ada Tagihan</h3>
            <p>Tagihan akan muncul di sini setelah diterbitkan oleh admin.</p>
        </div>
        @endforelse

        @if($invoices->hasPages())
        <div class="inv-pagination">
            <span class="pagination-info">
                Menampilkan {{ $invoices->firstItem() }}&ndash;{{ $invoices->lastItem() }} dari {{ $invoices->total() }} tagihan
            </span>
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

    <div class="info-strip">
        <div class="info-strip-item">
            <div class="info-strip-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div class="info-strip-title">QRIS / Virtual Account</div>
            <div class="info-strip-val">Konfirmasi otomatis</div>
        </div>
        <div class="info-strip-item">
            <div class="info-strip-icon"><i class="bi bi-bank"></i></div>
            <div class="info-strip-title">Transfer Manual</div>
            <div class="info-strip-val">Verifikasi kurang dari 1x24 jam</div>
        </div>
        <div class="info-strip-item">
            <div class="info-strip-icon"><i class="bi bi-shield-check"></i></div>
            <div class="info-strip-title">Keamanan Data</div>
            <div class="info-strip-val">Diproses via Midtrans</div>
        </div>
    </div>

</div>
</body>
</html>