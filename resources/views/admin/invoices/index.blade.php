<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice &amp; Pembayaran — Urban Kost</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
    --blue-100:    #dbeafe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-100:     #fee2e2;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-100:   #fef3c7;
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
.page-bg {
    position: fixed; inset: 0; z-index: -1; overflow: hidden;
    background: var(--green-50);
}
.page-bg::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%);
}
.page-bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}
.page-wrap { max-width: 1200px; margin: 0 auto; padding: 90px 28px 56px; width: 100%; }
.page-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    margin-bottom: 24px; flex-wrap: wrap; gap: 14px;
}
.page-title-wrap {}
.page-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 700;
    color: var(--neutral-900);
    letter-spacing: -.03em;
    line-height: 1.1;
}
.page-title em {
    font-style: italic;
    font-weight: 400;
    color: var(--green-600);
}
.page-subtitle {
    font-size: .78rem; color: var(--neutral-500);
    font-weight: 400; margin-top: 4px;
}
.btn-create {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green-500); color: #fff;
    font-weight: 600; font-size: .82rem;
    padding: 10px 20px; border-radius: 10px; border: none;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(42,179,107,.32);
    transition: all .2s; white-space: nowrap;
}
.btn-create:hover { background: var(--green-600); color: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(42,179,107,.4); }
.flash-msg {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 13px 16px; border-radius: 10px;
    margin-bottom: 18px; font-size: .82rem; font-weight: 500;
    border: 1px solid; line-height: 1.55;
}
.flash-success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.flash-error   { background: var(--red-pale); border-color: var(--red-100); color: var(--red); }
.flash-msg i   { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }
.stats-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 24px;
}
.stat-card {
    position: relative; overflow: hidden;
    border-radius: 16px; padding: 20px 18px;
    background: var(--neutral-0);
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    transition: border-color .25s, box-shadow .3s, transform .25s;
}
.stat-card:hover { border-color: var(--green-300); box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: 16px 16px 0 0;
}
.stat-card.c-amber::before { background: linear-gradient(90deg, var(--amber), #fcd34d); }
.stat-card.c-red::before   { background: linear-gradient(90deg, var(--red), #f87171); }
.stat-card.c-blue::before  { background: linear-gradient(90deg, var(--blue), #60a5fa); }
.stat-card.c-green::before { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
.stat-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; margin-bottom: 12px;
}
.stat-value {
    font-family: 'Fraunces', serif;
    font-size: 2rem; font-weight: 700;
    line-height: 1; margin-bottom: 4px; letter-spacing: -.03em;
}
.stat-label {
    font-size: .65rem; font-weight: 600;
    color: var(--neutral-400); text-transform: uppercase; letter-spacing: .07em;
}
.stat-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .62rem; font-weight: 600;
    padding: 2px 8px; border-radius: 100px; margin-top: 8px;
}
.o-card {
    background: var(--neutral-0); border-radius: 16px;
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden;
}
.card-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 15px 20px; border-bottom: 1px solid var(--neutral-100);
    flex-wrap: wrap; gap: 12px;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.card-toolbar-title {
    font-size: .72rem; font-weight: 600; color: var(--neutral-600);
    display: flex; align-items: center; gap: 7px;
    text-transform: uppercase; letter-spacing: .07em;
}
.card-toolbar-title i { color: var(--green-500); }
.filter-active-badge {
    font-size: .62rem; font-weight: 600; padding: 2px 8px;
    background: var(--amber-pale); border-radius: 6px; color: #92400e;
}
.filter-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.search-wrap { position: relative; }
.search-wrap i {
    position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
    color: var(--neutral-400); font-size: .82rem; pointer-events: none;
}
.search-input {
    padding: 7px 12px 7px 32px; border: 1px solid var(--green-200);
    border-radius: 9px; font-size: .78rem;
    font-family: 'Instrument Sans', sans-serif;
    color: var(--neutral-900); background: #fff;
    transition: border-color .15s, box-shadow .15s; width: 220px; outline: none;
}
.search-input:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.filter-select {
    padding: 7px 10px; border: 1px solid var(--green-200); border-radius: 9px;
    font-size: .78rem; font-family: 'Instrument Sans', sans-serif;
    color: var(--neutral-700); background: #fff; outline: none; cursor: pointer;
    transition: border-color .15s;
}
.filter-select:focus { border-color: var(--green-400); }
.btn-filter {
    padding: 7px 14px; border-radius: 9px;
    border: 1px solid var(--green-200); background: #fff;
    color: var(--green-700); font-size: .78rem; font-weight: 600;
    cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all .15s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-filter:hover { border-color: var(--green-400); background: var(--green-50); }
.btn-reset {
    padding: 7px 12px; border-radius: 9px;
    border: 1px solid var(--neutral-200); background: #fff;
    color: var(--neutral-500); font-size: .78rem; font-weight: 600;
    cursor: pointer; display: flex; align-items: center; gap: 5px;
    text-decoration: none; transition: all .15s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-reset:hover { border-color: var(--neutral-400); color: var(--neutral-700); }
.inv-table { width: 100%; border-collapse: collapse; }
.inv-table thead th {
    padding: 10px 16px; font-size: .63rem; font-weight: 600;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--neutral-400); background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-100); white-space: nowrap; text-align: left;
}
.inv-table tbody tr { border-bottom: 1px solid var(--neutral-100); transition: background .12s; }
.inv-table tbody tr:last-child { border-bottom: none; }
.inv-table tbody tr:hover { background: var(--green-50); }
.inv-table tbody tr.row-overdue { background: rgba(254,242,242,.5); }
.inv-table tbody tr.row-overdue:hover { background: rgba(254,226,226,.6); }
.inv-table tbody td { padding: 12px 16px; font-size: .8rem; vertical-align: middle; }
.inv-number {
    font-family: 'Fraunces', serif;
    font-weight: 600; color: var(--green-700); font-size: .78rem;
    letter-spacing: -.01em;
}
.inv-type-chip {
    display: inline-flex; align-items: center;
    background: var(--neutral-100); border-radius: 5px;
    padding: 2px 7px; font-size: .6rem; font-weight: 600; color: var(--neutral-500); margin-top: 3px;
}
.inv-type-chip.manual { background: #ede9fe; color: #5b21b6; }
.tenant-name { font-weight: 600; font-size: .82rem; color: var(--neutral-900); }
.tenant-email { font-size: .69rem; color: var(--neutral-400); margin-top: 2px; }
.room-chip {
    display: inline-flex; align-items: center;
    background: var(--green-100); border-radius: 6px;
    padding: 3px 9px; font-size: .72rem; font-weight: 600; color: var(--green-700);
}
.amount-txt {
    font-family: 'Fraunces', serif;
    font-weight: 700; color: var(--green-700); font-size: .85rem;
}
.due-txt { font-size: .74rem; color: var(--neutral-500); }
.due-txt.overdue { color: var(--red); font-weight: 600; }
.late-chip {
    display: inline-flex; align-items: center;
    background: var(--red-pale); color: var(--red);
    font-size: .6rem; font-weight: 600;
    padding: 2px 6px; border-radius: 5px; margin-top: 3px;
}
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 100px;
    font-size: .65rem; font-weight: 600; white-space: nowrap; border: 1px solid;
}
.status-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.st-unpaid   { background: #fef9c3; color: #854d0e; border-color: #fde68a; }
.st-overdue  { background: var(--red-pale); color: var(--red); border-color: var(--red-100); }
.st-pending  { background: var(--blue-pale); color: var(--blue); border-color: var(--blue-100); }
.st-paid     { background: var(--green-100); color: var(--green-700); border-color: var(--green-200); }
.st-cancelled{ background: var(--neutral-100); color: var(--neutral-500); border-color: var(--neutral-200); }
.actions-td { display: flex; gap: 6px; align-items: center; }
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 8px;
    border: 1px solid var(--green-200); color: var(--neutral-500);
    text-decoration: none; font-size: .78rem;
    transition: all .15s; background: #fff; cursor: pointer;
}
.action-btn:hover { border-color: var(--green-400); color: var(--green-600); background: var(--green-50); }
.action-btn.edit:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-pale); }
.action-btn.del:hover  { border-color: var(--red); color: var(--red); background: var(--red-pale); }
.empty-state { text-align: center; padding: 56px 20px; }
.empty-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: var(--green-50); border: 2px dashed var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: var(--green-300); margin: 0 auto 12px;
}
.empty-title {
    font-family: 'Fraunces', serif;
    font-size: .95rem; font-weight: 700; color: var(--neutral-500); margin-bottom: 5px;
    letter-spacing: -.01em;
}
.empty-sub { font-size: .76rem; color: var(--neutral-400); }
.card-footer-bar {
    padding: 14px 20px; border-top: 1px solid var(--neutral-100);
    display: flex; justify-content: space-between; align-items: center;
    background: var(--neutral-50); flex-wrap: wrap; gap: 10px;
}
.total-count { font-size: .75rem; color: var(--neutral-500); }
.pagi-wrap { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }
.pagi-btn {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; padding: 0 10px;
    border-radius: 8px; border: 1px solid var(--green-200);
    background: #fff; color: var(--neutral-600);
    font-size: .75rem; font-weight: 600;
    font-family: 'Instrument Sans', sans-serif;
    text-decoration: none; cursor: pointer; transition: all .15s;
}
.pagi-btn:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }
.pagi-btn.active { background: var(--green-600); border-color: var(--green-600); color: #fff; pointer-events: none; }
.pagi-btn.disabled { background: var(--neutral-50); border-color: var(--neutral-200); color: var(--neutral-300); pointer-events: none; }
.pagi-dots { display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 32px; font-size: .75rem; color: var(--neutral-400); }
.del-modal-bg {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 9999;
    align-items: center; justify-content: center;
    backdrop-filter: blur(3px);
}
.del-modal-bg.show { display: flex; }
.del-modal {
    background: #fff; border-radius: 18px; padding: 32px;
    max-width: 420px; width: 90%;
    box-shadow: var(--shadow-lg); animation: popIn .25s ease;
}
@keyframes popIn { from { opacity: 0; transform: scale(.94); } to { opacity: 1; transform: scale(1); } }
.del-modal-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: var(--red-pale); display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: var(--red); margin: 0 auto 16px;
}
.del-modal-title {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem; font-weight: 700;
    color: var(--neutral-900); text-align: center; margin-bottom: 8px;
    letter-spacing: -.02em;
}
.del-modal-body { font-size: .82rem; color: var(--neutral-500); text-align: center; line-height: 1.6; margin-bottom: 24px; }
.del-modal-body strong { color: var(--neutral-800); }
.del-modal-actions { display: flex; gap: 10px; }
.del-modal-actions button, .del-modal-actions .del-confirm-form { flex: 1; }
.del-cancel {
    width: 100%; padding: 10px; border-radius: 9px;
    font-size: .84rem; font-weight: 600; cursor: pointer;
    border: 1px solid var(--neutral-200); background: var(--neutral-100); color: var(--neutral-700);
    transition: all .15s; font-family: 'Instrument Sans', sans-serif;
}
.del-cancel:hover { background: var(--neutral-200); }
.del-confirm {
    width: 100%; padding: 10px; border-radius: 9px;
    font-size: .84rem; font-weight: 600; cursor: pointer;
    background: var(--red); color: #fff; border: none;
    box-shadow: 0 4px 12px rgba(220,38,38,.3);
    transition: all .15s; font-family: 'Instrument Sans', sans-serif;
}
.del-confirm:hover { background: #b91c1c; transform: translateY(-1px); }
.fade-up { opacity: 0; transform: translateY(12px); animation: fadeUp .4s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .04s; } .d2 { animation-delay: .08s; } .d3 { animation-delay: .12s; }
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
    .page-wrap { padding: 18px 14px 40px; }
    .filter-row { flex-direction: column; align-items: stretch; }
    .search-input { width: 100%; }
    .hide-mobile { display: none !important; }
}

.hero-banner {
    position: relative; overflow: hidden; border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 36px 32px; margin-bottom: 28px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;
}
.hero-banner::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-left  { position: relative; z-index: 1; }
.hero-right { position: relative; z-index: 1; flex-shrink: 0; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82); margin-bottom: 14px;
}
.hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.5rem, 4vw, 2.2rem); font-weight: 700;
    color: #fff; line-height: 1.15; letter-spacing: -.025em; margin-bottom: 8px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .88rem; color: rgba(255,255,255,.65); }
.btn-hero {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px; font-size: .82rem; font-weight: 600;
    color: var(--green-900); background: rgba(255,255,255,.92);
    border: none; border-radius: 10px; cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,.12); transition: all .2s;
    text-decoration: none; white-space: nowrap;
}
.btn-hero:hover { background: #fff; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(0,0,0,.16); color: var(--green-900); }
@media (max-width: 768px) {
    .hero-banner { flex-direction: column; padding: 22px 20px; }
    .hero-right { width: 100%; }
    .btn-hero { width: 100%; justify-content: center; }
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">

    @if(session('success'))
    <div class="flash-msg flash-success fade-up">
        <i class="bi bi-check-circle-fill"></i>
        <span>{!! session('success') !!}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="flash-msg flash-error fade-up">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{!! session('error') !!}</span>
    </div>
    @endif

    <div class="hero-banner fade-up d1">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Manajemen Invoice
            </div>
            <h1 class="hero-title">Invoice <em>& Pembayaran</em></h1>
            <p class="hero-sub">Kelola tagihan dan verifikasi pembayaran penghuni</p>
        </div>
        <div class="hero-right">
            <a href="{{ route('admin.invoices.create') }}" class="btn-hero">
                <i class="bi bi-plus-circle-fill"></i>Buat Invoice Baru
            </a>
        </div>
    </div>

    <div class="stats-grid fade-up d2">
        <div class="stat-card c-amber">
            <div class="stat-icon-wrap" style="background: var(--amber-pale); color: var(--amber);">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value" style="color: var(--amber);">{{ $stats['unpaid'] }}</div>
            <div class="stat-label">Belum Bayar</div>
            <div class="stat-badge" style="background: var(--amber-pale); color: #92400e;">
                <i class="bi bi-clock"></i>Menunggu
            </div>
        </div>
        <div class="stat-card c-red">
            <div class="stat-icon-wrap" style="background: var(--red-pale); color: var(--red);">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--red);">{{ $stats['overdue'] }}</div>
            <div class="stat-label">Terlambat</div>
            <div class="stat-badge" style="background: var(--red-pale); color: var(--red);">
                <i class="bi bi-alarm"></i>Jatuh Tempo
            </div>
        </div>
        <div class="stat-card c-blue">
            <div class="stat-icon-wrap" style="background: var(--blue-pale); color: var(--blue);">
                <i class="bi bi-cloud-upload-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--blue);">{{ $stats['pending'] }}</div>
            <div class="stat-label">Perlu Verifikasi</div>
            <div class="stat-badge" style="background: var(--blue-pale); color: var(--blue);">
                <i class="bi bi-eye"></i>Review
            </div>
        </div>
        <div class="stat-card c-green">
            <div class="stat-icon-wrap" style="background: var(--green-100); color: var(--green-600);">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="stat-value" style="color: var(--green-600);">{{ number_format($stats['total_revenue']/1000000,1) }}jt</div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-badge" style="background: var(--green-100); color: var(--green-700);">
                <i class="bi bi-graph-up"></i>Total Lunas
            </div>
        </div>
    </div>

    <div class="o-card fade-up d3">
        <div class="card-toolbar">
            <div class="card-toolbar-title">
                <i class="bi bi-receipt-cutoff"></i>
                Semua Invoice
                @if(request()->hasAny(['search','status','month','year']))
                <span class="filter-active-badge">Filter aktif</span>
                @endif
            </div>
            <form method="GET" class="filter-row" id="filterForm">
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="search-input"
                        placeholder="Nama / no invoice..."
                        value="{{ request('search') }}">
                </div>
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    @foreach(['unpaid'=>'Belum Bayar','pending_verification'=>'Verifikasi','paid'=>'Lunas','overdue'=>'Terlambat','cancelled'=>'Batal'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                <select name="month" class="filter-select">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ request('month')==$m?'selected':'' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-filter">
                    <i class="bi bi-funnel-fill"></i>Filter
                </button>
                @if(request()->hasAny(['search','status','month','year']))
                <a href="{{ route('admin.invoices.index') }}" class="btn-reset">
                    <i class="bi bi-x"></i>Reset
                </a>
                @endif
            </form>
        </div>

        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Penghuni</th>
                        <th class="hide-mobile">Kamar</th>
                        <th class="hide-mobile">Periode</th>
                        <th>Total</th>
                        <th class="hide-mobile">Jatuh Tempo</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                    @php
                        $stMap = [
                            'unpaid'               => ['cls'=>'st-unpaid',    'txt'=>'Belum Bayar'],
                            'pending_verification' => ['cls'=>'st-pending',   'txt'=>'Verifikasi'],
                            'paid'                 => ['cls'=>'st-paid',      'txt'=>'Lunas'],
                            'overdue'              => ['cls'=>'st-overdue',   'txt'=>'Terlambat'],
                            'cancelled'            => ['cls'=>'st-cancelled', 'txt'=>'Batal'],
                        ];
                        $stInfo  = $stMap[$inv->status] ?? ['cls'=>'st-unpaid','txt'=>$inv->status];
                        $isManual = $inv->type === 'manual';
                        $canEdit  = in_array($inv->status, ['unpaid','overdue']);
                        $canDel   = in_array($inv->status, ['unpaid','overdue','cancelled']) && !$inv->payments()->exists();
                    @endphp
                    <tr class="{{ $inv->isOverdue() ? 'row-overdue' : '' }}">
                        <td>
                            <div class="inv-number">{{ $inv->invoice_number }}</div>
                            @if($isManual)
                            <div class="inv-type-chip manual">
                                <i class="bi bi-pencil-fill" style="font-size:.6rem;margin-right:3px"></i>Manual
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="tenant-name">{{ $inv->user?->name }}</div>
                            <div class="tenant-email">{{ $inv->user?->email }}</div>
                        </td>
                        <td class="hide-mobile">
                            <span class="room-chip">{{ $inv->room?->room_number ?? '-' }}</span>
                        </td>
                        <td class="hide-mobile">
                            <span style="font-size:.74rem;color:var(--neutral-600)">
                                {{ $inv->period_start?->format('d M') }} – {{ $inv->period_end?->format('d M Y') }}
                            </span>
                        </td>
                        <td><div class="amount-txt">{{ $inv->formatted_total }}</div></td>
                        <td class="hide-mobile">
                            <div class="due-txt {{ $inv->isOverdue() ? 'overdue' : '' }}">
                                {{ $inv->due_date?->format('d M Y') }}
                            </div>
                            @if($inv->isOverdue())
                            <div class="late-chip">
                                <i class="bi bi-clock-history" style="margin-right:3px"></i>
                                {{ abs($inv->days_until_due) }}h
                            </div>
                            @endif
                        </td>
                        <td><span class="status-badge {{ $stInfo['cls'] }}">{{ $stInfo['txt'] }}</span></td>
                        <td>
                            <div class="actions-td" style="justify-content:flex-end">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" class="action-btn" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($canEdit)
                                <a href="{{ route('admin.invoices.edit', $inv->id) }}" class="action-btn edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                @if($canDel)
                                <button type="button" class="action-btn del" title="Hapus"
                                    onclick="confirmDelete({{ $inv->id }}, '{{ $inv->invoice_number }}', '{{ $inv->user?->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-receipt"></i></div>
                                <div class="empty-title">
                                    @if(request()->hasAny(['search','status'])) Tidak ditemukan hasil @else Belum ada invoice @endif
                                </div>
                                <div class="empty-sub">
                                    @if(request()->hasAny(['search','status'])) Coba ubah kata kunci atau filter @else Invoice yang dibuat akan muncul di sini @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer-bar">
            <div class="total-count">
                Menampilkan {{ $invoices->firstItem() ?? 0 }}–{{ $invoices->lastItem() ?? 0 }}
                dari {{ $invoices->total() }} invoice
            </div>
            <div id="pagiContainer">{{ $invoices->links() }}</div>
        </div>
    </div>

</div>

<div class="del-modal-bg" id="delModal">
    <div class="del-modal">
        <div class="del-modal-icon"><i class="bi bi-trash-fill"></i></div>
        <div class="del-modal-title">Hapus Invoice?</div>
        <div class="del-modal-body" id="delModalBody">Invoice ini akan dihapus permanen.</div>
        <div class="del-modal-actions">
            <button class="del-cancel" onclick="closeDelModal()">Batal</button>
            <form id="delForm" method="POST" class="del-confirm-form" style="display:contents">
                @csrf @method('DELETE')
                <button type="submit" class="del-confirm">
                    <i class="bi bi-trash-fill" style="margin-right:4px"></i>Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    var container = document.getElementById('pagiContainer');
    if (!container) return;
    var wrap = document.createElement('div');
    wrap.className = 'pagi-wrap';
    var ul = container.querySelector('ul.pagination');
    if (ul) {
        ul.querySelectorAll('li.page-item').forEach(function(li){
            var link = li.querySelector('a.page-link, span.page-link');
            if (!link) return;
            var txt = link.textContent.trim();
            if (txt === '...' || txt === '…') {
                var dots = document.createElement('span');
                dots.className = 'pagi-dots'; dots.textContent = '···';
                wrap.appendChild(dots); return;
            }
            var isDisabled = li.classList.contains('disabled');
            var isActive   = li.classList.contains('active');
            var href       = link.getAttribute('href');
            var btn;
            if (href && !isDisabled) { btn = document.createElement('a'); btn.href = href; }
            else { btn = document.createElement('span'); }
            btn.className = 'pagi-btn' + (isActive?' active':'') + (isDisabled?' disabled':'');
            btn.textContent = txt;
            wrap.appendChild(btn);
        });
        if (wrap.children.length) { container.innerHTML = ''; container.appendChild(wrap); }
    }
})();
function confirmDelete(id, number, name) {
    document.getElementById('delModalBody').innerHTML =
        'Invoice <strong>' + number + '</strong> milik <strong>' + name + '</strong> akan dihapus permanen.';
    document.getElementById('delForm').action = '/admin/invoices/' + id;
    document.getElementById('delModal').classList.add('show');
}
function closeDelModal() { document.getElementById('delModal').classList.remove('show'); }
document.getElementById('delModal').addEventListener('click', function(e){ if (e.target===this) closeDelModal(); });
var si = document.querySelector('.search-input');
if (si) si.addEventListener('keydown', function(e){ if(e.key==='Enter') document.getElementById('filterForm').submit(); });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>