<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Booking — Urban Kost</title>
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
    --red-lt:      #fef2f2;
    --red-line:    #fecaca;
    --blue:        #2563eb;
    --blue-lt:     #eff6ff;
    --blue-line:   #bfdbfe;
    --amber:       #f59e0b;
    --amber-lt:    #fffbeb;
    --amber-line:  #fde68a;
    --purple:      #7c3aed;
    --purple-lt:   #f5f3ff;
    --purple-line: #ddd6fe;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
    --card-r: 14px;
    --r-pill: 999px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
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

.page-wrap { max-width: 1160px; margin: 0 auto; padding: 32px 24px 64px; }

.page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
}
.page-header-title {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    color: var(--neutral-900); letter-spacing: -.025em; line-height: 1.15;
    display: flex; align-items: center; gap: 10px;
}
.page-header-title .title-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--green-600), var(--green-400));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .95rem; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(42,179,107,.3);
}
.page-header-sub { font-size: .82rem; color: var(--neutral-500); margin-top: 5px; }
.header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-start; flex-shrink: 0; }

.btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    background: #fff;
    color: var(--green-900); font-size: .8rem; font-weight: 600;
    padding: 9px 18px; border-radius: 10px;
    text-decoration: none; border: none; cursor: pointer;
    transition: all .18s; white-space: nowrap;
    box-shadow: 0 2px 10px rgba(42,179,107,.28);
}
.btn-primary:hover { background: var(--green-50); color: var(--green-900); transform: translateY(-1px); }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--neutral-0); border: 1px solid var(--green-200);
    color: var(--neutral-600); font-size: .8rem; font-weight: 600;
    padding: 9px 16px; border-radius: 10px; cursor: pointer;
    text-decoration: none; transition: all .15s; white-space: nowrap;
}
.btn-back:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-700); }

.flash {
    padding: 12px 16px; border-radius: 10px; margin-bottom: 14px;
    font-size: .82rem; font-weight: 500; display: flex; align-items: flex-start; gap: 9px;
    border: 1px solid;
}

.stat-row {
    display: grid; grid-template-columns: repeat(5, 1fr);
    gap: 12px; margin-bottom: 20px;
}
.stat-card {
    background: var(--neutral-0); border: 1px solid var(--green-100);
    border-radius: var(--card-r); padding: 14px 16px;
    box-shadow: var(--shadow-sm); transition: transform .18s, box-shadow .18s;
    position: relative; overflow: hidden;
}
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: var(--card-r) var(--card-r) 0 0;
    background: var(--accent-color, var(--green-500));
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.stat-icon { font-size: 18px; margin-bottom: 8px; }
.stat-val {
    font-family: 'Fraunces', serif;
    font-size: 1.6rem; font-weight: 700; line-height: 1;
    color: var(--neutral-900); letter-spacing: -.02em;
}
.stat-lbl {
    font-size: .65rem; font-weight: 600; color: var(--neutral-500);
    margin-top: 5px; text-transform: uppercase; letter-spacing: .06em;
}

.filter-bar {
    background: var(--neutral-0); border: 1px solid var(--green-100);
    border-radius: var(--card-r); padding: 14px 16px;
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    margin-bottom: 16px; box-shadow: var(--shadow-sm);
}
.filter-bar input, .filter-bar select {
    border: 1px solid var(--neutral-200); border-radius: 9px;
    padding: 8px 12px; font-size: .8rem;
    font-family: 'Instrument Sans', sans-serif;
    background: var(--neutral-0); color: var(--neutral-900);
    transition: border-color .15s, box-shadow .15s; outline: none; height: 36px;
}
.filter-bar input:focus, .filter-bar select:focus {
    border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.12);
}
.filter-bar input { flex: 1; min-width: 200px; }
.filter-bar select { min-width: 150px; }
.btn-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 0 14px; height: 36px; border-radius: 9px;
    font-size: .78rem; font-weight: 600;
    font-family: 'Instrument Sans', sans-serif;
    cursor: pointer; border: none; transition: all .15s;
    white-space: nowrap; text-decoration: none; flex-shrink: 0;
}
.btn-sm.primary { background: var(--green-600); color: #fff; }
.btn-sm.primary:hover { background: var(--green-700); }
.btn-sm.ghost { background: transparent; color: var(--neutral-500); border: 1px solid var(--neutral-200); }
.btn-sm.ghost:hover { border-color: var(--green-300); color: var(--green-600); background: var(--green-50); }

.table-card {
    background: var(--neutral-0); border-radius: var(--card-r);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden;
}
.table-card-header {
    padding: 14px 18px; border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; flex-wrap: wrap;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.table-card-title {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Fraunces', serif;
    font-size: .95rem; font-weight: 700; color: var(--neutral-800);
}
.table-card-title i { color: var(--green-500); }
.count-badge {
    background: var(--green-100); color: var(--green-800);
    font-size: .65rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; border: 1px solid var(--green-200);
}
.meta-text { font-size: .72rem; color: var(--neutral-400); display: flex; align-items: center; gap: 4px; }

.tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.tbl { width: 100%; border-collapse: collapse; min-width: 720px; }
.tbl thead tr { border-bottom: 2px solid var(--green-100); }
.tbl th {
    padding: 10px 14px; font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .07em;
    color: var(--neutral-500); white-space: nowrap;
    background: var(--green-50); text-align: left;
}
.tbl th:last-child { text-align: center; }
.tbl td {
    padding: 13px 14px; border-bottom: 1px solid var(--green-50);
    vertical-align: middle; font-size: .8rem;
}
.tbl tbody tr { cursor: pointer; transition: background .1s; }
.tbl tbody tr:hover { background: var(--green-50); }
.tbl tbody tr:last-child td { border-bottom: none; }

.booking-code { font-weight: 700; font-size: .8rem; color: var(--green-700); letter-spacing: .01em; }
.badge-advance {
    display: inline-flex; align-items: center; gap: 3px;
    background: var(--amber-lt); color: #92400e;
    font-size: .62rem; font-weight: 700;
    padding: 1px 7px; border-radius: 20px; margin-top: 4px;
    border: 1px solid var(--amber-line);
}
.tenant-name  { font-weight: 600; font-size: .8rem; color: var(--neutral-900); }
.tenant-email { font-size: .72rem; color: var(--neutral-400); margin-top: 2px; }
.room-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--green-50); color: var(--green-800);
    font-weight: 700; font-size: .72rem;
    padding: 4px 10px; border-radius: 8px; border: 1px solid var(--green-200);
    white-space: nowrap;
}
.date-text { font-size: .78rem; color: var(--neutral-700); white-space: nowrap; }
.dur-text  { font-size: .88rem; font-weight: 700; color: var(--neutral-900); }
.dur-unit  { font-size: .7rem; font-weight: 400; color: var(--neutral-400); }

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .67rem; font-weight: 700;
    padding: 4px 10px; border-radius: var(--r-pill);
    white-space: nowrap; border: 1px solid transparent;
}
.status-badge .dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.status-badge.pending        { background: #fef9c3; color: #854d0e; border-color: #fde047; }
.status-badge.pending .dot   { background: #ca8a04; }
.status-badge.approved       { background: var(--green-100); color: var(--green-800); border-color: var(--green-200); }
.status-badge.approved .dot  { background: var(--green-500); }
.status-badge.active         { background: var(--green-600); color: #fff; border-color: var(--green-700); }
.status-badge.active .dot    { background: #bbf7d0; }
.status-badge.waiting_list   { background: var(--blue-lt); color: #1e40af; border-color: var(--blue-line); }
.status-badge.waiting_list .dot { background: var(--blue); }
.status-badge.rejected       { background: var(--red-lt); color: #991b1b; border-color: var(--red-line); }
.status-badge.rejected .dot  { background: var(--red); }
.status-badge.cancelled      { background: var(--neutral-100); color: var(--neutral-600); border-color: var(--neutral-200); }
.status-badge.cancelled .dot { background: var(--neutral-400); }
.status-badge.completed      { background: var(--purple-lt); color: #6b21a8; border-color: var(--purple-line); }
.status-badge.completed .dot { background: var(--purple); }
.status-badge.under_review   { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
.status-badge.under_review .dot { background: #f97316; }

.renewal-badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: .62rem; font-weight: 700;
    padding: 2px 7px; border-radius: 20px; margin-top: 4px;
}
.renewal-badge.will { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-200); }
.renewal-badge.wont { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
.renewal-badge.pend { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }

.btn-action {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 8px;
    border: 1px solid var(--neutral-200);
    background: var(--neutral-0); color: var(--neutral-500);
    text-decoration: none; font-size: .9rem; transition: all .15s; flex-shrink: 0;
}
.btn-action:hover { background: var(--green-600); color: #fff; border-color: var(--green-600); transform: scale(1.05); }
.btn-action.edit:hover { background: var(--blue); border-color: var(--blue); color: #fff; }
.btn-action.del:hover  { background: var(--red);  border-color: var(--red);  color: #fff; }
.actions-wrap { display: flex; align-items: center; justify-content: center; gap: 5px; }

.empty-state { text-align: center; padding: 60px 20px; }
.empty-state .empty-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: var(--green-100); border: 1px solid var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--green-500); margin: 0 auto 14px;
}
.empty-state h3 { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: var(--neutral-600); margin-bottom: 6px; }
.empty-state p  { color: var(--neutral-400); font-size: .8rem; }

.pagination-wrap {
    padding: 14px 18px; border-top: 1px solid var(--neutral-100);
    background: var(--green-50);
}
.pagination-inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.pagination-info  { font-size: .72rem; font-weight: 600; color: var(--neutral-500); white-space: nowrap; }
.pagination-info strong { color: var(--neutral-700); }
.pagination-links { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
.pg-pill {
    display: inline-flex; align-items: center; justify-content: center;
    height: 32px; padding: 0 10px; min-width: 32px; border-radius: 8px;
    font-size: .72rem; font-weight: 700; text-decoration: none;
    border: 1px solid var(--neutral-200); background: var(--neutral-0); color: var(--neutral-600);
    cursor: pointer; transition: all .15s; white-space: nowrap;
}
.pg-pill:hover    { background: var(--green-50); border-color: var(--green-400); color: var(--green-700); }
.pg-pill.active   { background: var(--green-600); border-color: var(--green-700); color: #fff; pointer-events: none; }
.pg-pill.disabled { background: var(--neutral-100); border-color: var(--neutral-200); color: var(--neutral-400); pointer-events: none; }
.pg-pill.dots     { background: transparent; border-color: transparent; color: var(--neutral-400); pointer-events: none; }
.pg-nav {
    display: inline-flex; align-items: center; gap: 5px;
    height: 32px; padding: 0 12px; border-radius: 9px; font-size: .72rem; font-weight: 700;
    text-decoration: none; border: 1px solid var(--neutral-200);
    background: var(--neutral-0); color: var(--neutral-600);
    cursor: pointer; transition: all .15s; white-space: nowrap;
}
.pg-nav:hover    { background: var(--green-50); border-color: var(--green-400); color: var(--green-700); }
.pg-nav.disabled { background: var(--neutral-100); border-color: var(--neutral-200); color: var(--neutral-400); pointer-events: none; }

.hero-banner {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 55%, var(--green-500) 100%);
    border-radius: 18px;
    padding: 36px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(14,63,39,.25);
}
.hero-banner::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(78,201,138,.18) 0%, transparent 55%),
        radial-gradient(circle at 10% 90%, rgba(42,179,107,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-label {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: .65rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: rgba(255,255,255,.7);
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
    padding: 4px 12px; border-radius: var(--r-pill);
    margin-bottom: 10px;
}
.hero-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--green-300); flex-shrink: 0;
    box-shadow: 0 0 6px var(--green-300);
}
.hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.6rem, 4vw, 2.2rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.03em; line-height: 1.1;
    margin-bottom: 6px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub {
    font-size: .82rem; color: rgba(255,255,255,.65);
}
.hero-actions {
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center; flex-shrink: 0;
}

.hero-banner .btn-back {
    background: rgba(255,255,255,.12);
    border-color: rgba(255,255,255,.25);
    color: rgba(255,255,255,.85);
}
.hero-banner .btn-back:hover {
    background: rgba(255,255,255,.22);
    border-color: rgba(255,255,255,.4);
    color: #fff;
}
@media (max-width: 640px) {
    .hero-banner { padding: 24px 20px; }
    .hero-actions { width: 100%; }
    .hero-actions a { flex: 1; justify-content: center; }
}

@keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
.page-header { animation: fadeUp .3s ease both; }
.stat-row    { animation: fadeUp .3s .06s ease both; }
.filter-bar  { animation: fadeUp .3s .12s ease both; }
.table-card  { animation: fadeUp .3s .17s ease both; }

@media (max-width: 900px) { .stat-row { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 760px) {
    .filter-bar { flex-direction: column; align-items: stretch; }
    .filter-bar input, .filter-bar select, .btn-sm { width: 100%; flex: unset; }
}
@media (max-width: 640px) {
    .page-wrap { padding: 14px 12px 56px; }
    .stat-row { grid-template-columns: repeat(2, 1fr); gap: 8px; }
    .page-header { gap: 12px; margin-bottom: 16px; }
    .header-actions { width: 100%; }
    .header-actions a { flex: 1; justify-content: center; }
    .tbl thead { display: none; }
    .tbl, .tbl tbody, .tbl tr, .tbl td { display: block; width: 100%; }
    .tbl tbody tr {
        border: 1px solid var(--neutral-200); border-radius: var(--card-r);
        margin: 0 0 10px; padding: 14px;
        background: var(--neutral-0); box-shadow: var(--shadow-sm);
    }
    .tbl tbody tr:hover { background: var(--neutral-0); }
    .tbl td { border: none; padding: 0; margin-bottom: 8px; }
    .tbl td[data-label]::before {
        content: attr(data-label);
        font-size: .62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em; color: var(--green-600);
        display: block; margin-bottom: 2px;
    }
    .tbl td.col-action { padding-top: 10px; border-top: 1px solid var(--neutral-100); }
    .tbl td.col-action::before { display: none; }
    .actions-wrap { justify-content: flex-start; gap: 8px; }
    .btn-action { width: auto; height: 34px; border-radius: 9px; padding: 0 14px; gap: 6px; font-size: .75rem; font-weight: 600; }
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-label">
                <span class="hero-dot"></span> Manajemen Booking Manual
            </div>
            <h1 class="hero-title">Manajemen <em>Booking</em></h1>
            <p class="hero-sub">Kelola semua permohonan sewa kamar kost</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.bookings.manual.create') }}" class="btn-primary">
                <i class="bi bi-plus-circle-fill"></i>Buat Booking Manual
            </a>
        </div>
    </div>

    @foreach(['success','warning','error','info'] as $type)
        @if(session($type))
        @php
            $flashStyles = [
                'success' => ['bg'=>'var(--green-50)','border'=>'var(--green-200)','color'=>'var(--green-800)','icon'=>'check-circle-fill'],
                'error'   => ['bg'=>'var(--red-lt)','border'=>'var(--red-line)','color'=>'#991b1b','icon'=>'exclamation-circle-fill'],
                'warning' => ['bg'=>'var(--amber-lt)','border'=>'var(--amber-line)','color'=>'#92400e','icon'=>'exclamation-triangle-fill'],
                'info'    => ['bg'=>'var(--blue-lt)','border'=>'var(--blue-line)','color'=>'#1e40af','icon'=>'info-circle-fill'],
            ];
            $fs = $flashStyles[$type];
        @endphp
        <div class="flash" style="background:{{ $fs['bg'] }};border-color:{{ $fs['border'] }};color:{{ $fs['color'] }}">
            <i class="bi bi-{{ $fs['icon'] }}"></i>
            <span>{!! session($type) !!}</span>
        </div>
        @endif
    @endforeach

    @php
    $statGroups = [
        ['icon'=>'bi-hourglass-split','color'=>'#ca8a04','accent'=>'var(--amber)',
         'val'=>$bookings->where('status','pending')->count(),'lbl'=>'Pending'],
        ['icon'=>'bi-check2-circle','color'=>'var(--green-600)','accent'=>'var(--green-500)',
         'val'=>$bookings->where('status','approved')->count(),'lbl'=>'Disetujui'],
        ['icon'=>'bi-house-heart-fill','color'=>'var(--green-700)','accent'=>'var(--green-600)',
         'val'=>$bookings->where('status','active')->count(),'lbl'=>'Menempati'],
        ['icon'=>'bi-list-ol','color'=>'var(--blue)','accent'=>'var(--blue)',
         'val'=>$bookings->where('status','waiting_list')->count(),'lbl'=>'Antrian'],
        ['icon'=>'bi-trophy-fill','color'=>'var(--purple)','accent'=>'var(--purple)',
         'val'=>$bookings->whereIn('status',['completed','done'])->count(),'lbl'=>'Selesai'],
    ];
    @endphp
    <div class="stat-row">
        @foreach($statGroups as $s)
        <div class="stat-card" style="--accent-color:{{ $s['accent'] }}">
            <div class="stat-icon" style="color:{{ $s['color'] }}"><i class="bi {{ $s['icon'] }}"></i></div>
            <div class="stat-val">{{ $s['val'] }}</div>
            <div class="stat-lbl">{{ $s['lbl'] }}</div>
        </div>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.bookings.index') }}">
        <div class="filter-bar">
            <input type="text" name="search"
                placeholder="Cari kode, nama, email..."
                value="{{ request('search') }}">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="pending"      @selected(request('status')=='pending')>Pending</option>
                <option value="under_review" @selected(request('status')=='under_review')>Direview</option>
                <option value="approved"     @selected(request('status')=='approved')>Disetujui</option>
                <option value="active"       @selected(request('status')=='active')>Menempati</option>
                <option value="waiting_list" @selected(request('status')=='waiting_list')>Antrian</option>
                <option value="rejected"     @selected(request('status')=='rejected')>Ditolak</option>
                <option value="cancelled"    @selected(request('status')=='cancelled')>Dibatalkan</option>
                <option value="completed"    @selected(request('status')=='completed')>Selesai</option>
            </select>
            <select name="booking_type">
                <option value="">Semua Tipe</option>
                <option value="regular" @selected(request('booking_type')=='regular')>Regular</option>
                <option value="advance" @selected(request('booking_type')=='advance')>Advance</option>
            </select>
            <button type="submit" class="btn-sm primary"><i class="bi bi-search"></i>Filter</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn-sm ghost">
                <i class="bi bi-arrow-counterclockwise"></i>Reset
            </a>
        </div>
    </form>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="bi bi-calendar2-check"></i>
                Semua Booking
                <span class="count-badge">{{ $bookings->total() ?? $bookings->count() }}</span>
            </div>
            <div class="meta-text">
                <i class="bi bi-clock"></i>Diperbarui baru saja
            </div>
        </div>

        <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Pemohon</th>
                    <th>Kamar</th>
                    <th>Tgl Masuk</th>
                    <th style="text-align:center">Dur.</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($bookings as $b)
            @php
            $statusMap = [
                'pending'      => ['pending',      'Menunggu'],
                'under_review' => ['under_review', 'Direview'],
                'approved'     => ['approved',     'Disetujui'],
                'active'       => ['active',       'Menempati'],
                'waiting_list' => ['waiting_list', 'Antrian'],
                'rejected'     => ['rejected',     'Ditolak'],
                'cancelled'    => ['cancelled',    'Dibatalkan'],
                'completed'    => ['completed',    'Selesai'],
                'done'         => ['completed',    'Selesai'],
            ];
            [$statusClass,$statusLabel] = $statusMap[$b->status] ?? ['cancelled', ucfirst($b->status)];
            $isEditable  = !in_array($b->status,['completed','cancelled','expired']);
            $isDeletable = in_array($b->status, ['pending','rejected','cancelled','expired'])
                || ($b->booking_type === 'advance' && $b->status === 'waiting_list');
            @endphp
            <tr onclick="window.location='{{ route('admin.bookings.show', $b->id) }}'">

                <td data-label="Kode Booking" class="col-code">
                    <span class="booking-code">{{ $b->booking_code }}</span>
                    @if(isset($b->booking_type) && $b->booking_type === 'advance')
                        <div><span class="badge-advance"><i class="bi bi-clock-history"></i>Advance</span></div>
                    @endif
                </td>

                <td data-label="Pemohon">
                    <div class="tenant-name">{{ $b->full_name }}</div>
                    <div class="tenant-email">{{ $b->user?->email }}</div>
                </td>

                <td data-label="Kamar">
                    <span class="room-badge">
                        <i class="bi bi-door-closed-fill" style="font-size:.65rem"></i>
                        {{ $b->room?->room_number ?? '-' }}
                    </span>
                </td>

                <td data-label="Tgl Masuk">
                    <span class="date-text">{{ $b->check_in_date?->format('d M Y') ?? '-' }}</span>
                </td>

                <td data-label="Durasi" style="text-align:center">
                    <span class="dur-text">{{ $b->duration_months ?? '-' }}<span class="dur-unit"> bln</span></span>
                </td>

                <td data-label="Status">
                    <span class="status-badge {{ $statusClass }}">
                        <span class="dot"></span>{{ $statusLabel }}
                    </span>
                    @if(isset($b->renewal_status))
                        @if($b->renewal_status === 'will_renew')
                            <div><span class="renewal-badge will"><i class="bi bi-check-circle"></i>Perpanjang</span></div>
                        @elseif($b->renewal_status === 'wont_renew')
                            <div><span class="renewal-badge wont"><i class="bi bi-arrow-right-circle"></i>Siap proses</span></div>
                        @elseif($b->renewal_status === 'pending_inquiry')
                            <div><span class="renewal-badge pend"><i class="bi bi-clock"></i>Menunggu</span></div>
                        @endif
                    @endif
                </td>

                <td data-label="Dibuat">
                    <span class="date-text">{{ $b->created_at->format('d M Y') }}</span>
                </td>

                <td data-label="" class="col-action" onclick="event.stopPropagation()">
                    <div class="actions-wrap">
                        <a href="{{ route('admin.bookings.show', $b->id) }}"
                           class="btn-action" title="Detail"><i class="bi bi-eye-fill"></i></a>
                        @if($isEditable)
                            <a href="{{ route('admin.bookings.edit', $b->id) }}"
                               class="btn-action edit" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                        @endif
                        @if($isDeletable)
                            <form method="POST" action="{{ route('admin.bookings.destroy', $b->id) }}"
                                  onsubmit="return confirm('Hapus booking {{ $b->booking_code }}? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action del" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="border:none;padding:0">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                        <h3>Belum ada data booking</h3>
                        <p>Coba ubah filter pencarian atau buat booking baru</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        </div>

        @if(method_exists($bookings,'hasPages') && $bookings->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-inner">
                <p class="pagination-info">
                    Menampilkan
                    <strong>{{ $bookings->firstItem() }}&ndash;{{ $bookings->lastItem() }}</strong>
                    dari <strong>{{ $bookings->total() }}</strong> data
                </p>
                <div class="pagination-links">
                    @if($bookings->onFirstPage())
                        <span class="pg-nav disabled"><i class="bi bi-chevron-left"></i>Sebelumnya</span>
                    @else
                        <a href="{{ $bookings->previousPageUrl() }}" class="pg-nav"><i class="bi bi-chevron-left"></i>Sebelumnya</a>
                    @endif
                    @php
                        $cur  = $bookings->currentPage();
                        $last = $bookings->lastPage();
                        $pages = collect();
                        for ($p=1;$p<=$last;$p++){
                            if($p===1||$p===$last||abs($p-$cur)<=1) $pages->push($p);
                        }
                        $pages=$pages->unique()->sort()->values();
                    @endphp
                    @php $prev=null; @endphp
                    @foreach($pages as $page)
                        @if($prev!==null && $page-$prev>1)
                            <span class="pg-pill dots">&hellip;</span>
                        @endif
                        @if($page===$cur)
                            <span class="pg-pill active">{{ $page }}</span>
                        @else
                            <a href="{{ $bookings->url($page) }}" class="pg-pill">{{ $page }}</a>
                        @endif
                        @php $prev=$page; @endphp
                    @endforeach
                    @if($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}" class="pg-nav">Berikutnya<i class="bi bi-chevron-right"></i></a>
                    @else
                        <span class="pg-nav disabled">Berikutnya<i class="bi bi-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
</body>
</html>