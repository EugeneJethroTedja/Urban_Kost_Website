<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perpanjangan Sewa — Urban Kost</title>
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
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-200:   #fde68a;
    --amber-600:   #d97706;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-200:    #bfdbfe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-200:     #fecaca;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
    --r-card: 16px;
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
.page-wrap { max-width: 1100px; margin: 0 auto; padding: 32px 24px 64px; }

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
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82);
    margin-bottom: 14px; position: relative; z-index: 1;
}
.hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.5rem, 4vw, 2.2rem); font-weight: 700;
    color: #fff; line-height: 1.15;
    letter-spacing: -.025em; margin-bottom: 8px;
    position: relative; z-index: 1;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .88rem; color: rgba(255,255,255,.65); position: relative; z-index: 1; }

.stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.scard {
    position: relative; overflow: hidden;
    border-radius: var(--r-card);
    padding: 20px 18px;
    background: var(--neutral-0);
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    transition: border-color .25s, box-shadow .3s, transform .25s;
}
.scard:hover { border-color: var(--green-300); box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.scard::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: var(--r-card) var(--r-card) 0 0;
}
.scard.amber::before  { background: linear-gradient(90deg, var(--amber), #fcd34d); }
.scard.blue::before   { background: linear-gradient(90deg, var(--blue), #60a5fa); }
.scard.green::before  { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
.scard.red::before    { background: linear-gradient(90deg, var(--red), #f87171); }
.scard-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; margin-bottom: 12px;
}
.scard.amber .scard-icon { background: var(--amber-pale); color: #92400e; }
.scard.blue  .scard-icon { background: var(--blue-pale);  color: var(--blue); }
.scard.green .scard-icon { background: var(--green-100);  color: var(--green-700); }
.scard.red   .scard-icon { background: var(--red-pale);   color: var(--red); }
.scard-val {
    font-family: 'Fraunces', serif;
    font-size: 2rem; font-weight: 700;
    color: var(--neutral-900); line-height: 1;
    letter-spacing: -.03em; margin-bottom: 3px;
}
.scard-lbl { font-size: .68rem; font-weight: 600; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .07em; }
.scard-badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: .62rem; font-weight: 600; padding: 3px 8px;
    border-radius: var(--r-pill); margin-top: 8px;
}
.scard.amber .scard-badge { background: var(--amber-pale); color: #92400e; }
.scard.blue  .scard-badge { background: var(--blue-pale);  color: var(--blue); }
.scard.green .scard-badge { background: var(--green-100);  color: var(--green-700); }
.scard.red   .scard-badge { background: var(--red-pale);   color: var(--red); }
.scard-bg-icon {
    position: absolute; right: -6px; bottom: -6px;
    font-size: 4rem; opacity: .04; line-height: 1; color: var(--neutral-900);
}

.toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
.filter-bar { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
.filter-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 14px; border-radius: var(--r-pill);
    font-size: .73rem; font-weight: 600;
    border: 1px solid var(--neutral-200);
    background: var(--neutral-0); color: var(--neutral-500);
    cursor: pointer; transition: all .18s; text-decoration: none; white-space: nowrap;
}
.filter-chip:hover { border-color: var(--green-400); color: var(--green-600); background: var(--green-50); }
.filter-chip.active { border-color: var(--green-500); background: var(--green-100); color: var(--green-700); }
.filter-count {
    background: var(--green-500); color: #fff;
    font-size: .58rem; font-weight: 700; padding: 1px 6px; border-radius: var(--r-pill);
}
.filter-chip:not(.active) .filter-count { background: var(--neutral-200); color: var(--neutral-500); }

.search-wrap { position: relative; }
.search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--neutral-400); font-size: .8rem; pointer-events: none; }
.search-input {
    padding: 8px 12px 8px 32px; border: 1px solid var(--neutral-200);
    border-radius: 9px; font-size: .78rem;
    font-family: 'Instrument Sans', sans-serif;
    color: var(--neutral-900); background: var(--neutral-0);
    transition: border-color .15s, box-shadow .15s; width: 220px; outline: none;
}
.search-input:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }

.uk-card {
    background: var(--neutral-0); border: 1px solid var(--green-100);
    border-radius: var(--r-card); box-shadow: var(--shadow-md); overflow: hidden;
}
.uk-card-head {
    display: flex; align-items: center; justify-content: space-between; gap: 9px;
    padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.card-head-left { display: flex; align-items: center; gap: 9px; font-size: .72rem; font-weight: 600; color: var(--neutral-600); text-transform: uppercase; letter-spacing: .07em; }
.head-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .82rem; background: var(--green-100); color: var(--green-700); flex-shrink: 0; }

.tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.dtbl { width: 100%; border-collapse: collapse; }
.dtbl thead th {
    padding: 9px 16px; font-size: .63rem; font-weight: 600;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--neutral-400); background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-100); white-space: nowrap;
}
.dtbl tbody tr { border-bottom: 1px solid var(--neutral-100); transition: background .12s; }
.dtbl tbody tr:last-child { border-bottom: none; }
.dtbl tbody tr:hover td { background: var(--green-50); }
.dtbl tbody td { padding: 12px 16px; font-size: .8rem; vertical-align: middle; }

.tenant-name { font-weight: 600; font-size: .82rem; color: var(--neutral-900); }
.tenant-email { font-size: .69rem; color: var(--neutral-400); margin-top: 2px; }
.room-chip { display: inline-flex; align-items: center; gap: 4px; background: var(--green-50); border: 1px solid var(--green-100); border-radius: 6px; padding: 3px 9px; font-size: .72rem; font-weight: 600; color: var(--green-700); }
.dur-badge { display: inline-flex; align-items: center; gap: 3px; background: var(--green-50); border: 1px solid var(--green-200); border-radius: 6px; padding: 3px 9px; font-size: .72rem; font-weight: 600; color: var(--green-700); }
.amount-txt { font-weight: 700; color: var(--green-700); font-size: .84rem; }
.date-txt { font-size: .74rem; color: var(--neutral-400); }

.st-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: var(--r-pill); font-size: .67rem; font-weight: 600; white-space: nowrap; }
.st-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
.st-pending-admin { background: var(--amber-pale); color: #854d0e; }
.st-pending-pay   { background: var(--blue-pale);  color: #1e40af; }
.st-approved      { background: var(--green-100);  color: var(--green-800); }
.st-rejected      { background: var(--red-pale);   color: var(--red); }

.inv-badge { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 6px; font-size: .65rem; font-weight: 600; }
.inv-none   { background: var(--neutral-100); color: var(--neutral-500); }
.inv-unpaid { background: #fef9c3; color: #854d0e; }
.inv-paid   { background: var(--green-100); color: var(--green-800); }
.inv-overdue{ background: var(--red-pale);  color: var(--red); }

.tbl-action {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 13px; border-radius: 8px;
    border: 1px solid var(--neutral-200); color: var(--neutral-600);
    text-decoration: none; font-size: .74rem; font-weight: 600;
    transition: all .15s; background: var(--neutral-0); white-space: nowrap;
}
.tbl-action:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

.empty-state { text-align: center; padding: 56px 20px; display: flex; flex-direction: column; align-items: center; gap: 10px; }
.empty-icon { width: 60px; height: 60px; border-radius: 16px; background: var(--green-100); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--green-500); margin-bottom: 4px; }
.empty-title { font-size: .9rem; font-weight: 600; color: var(--neutral-600); }
.empty-sub { font-size: .75rem; color: var(--neutral-400); }

.pagination-wrap { display: flex; justify-content: flex-end; padding: 12px 18px; border-top: 1px solid var(--neutral-100); background: var(--green-50); }

.mobile-card-list { display: none; padding: 12px; gap: 10px; flex-direction: column; }
.m-card { background: var(--neutral-0); border: 1px solid var(--green-100); border-radius: var(--r-card); padding: 14px; box-shadow: var(--shadow-sm); }
.m-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
.m-card-name { font-weight: 600; font-size: .85rem; color: var(--neutral-900); }
.m-card-email { font-size: .68rem; color: var(--neutral-400); margin-top: 2px; }
.m-card-body { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px; }
.m-field-lbl { font-size: .6rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 3px; }
.m-card-footer { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--neutral-100); padding-top: 10px; }

.fade-up { opacity: 0; transform: translateY(14px); animation: fadeUp .4s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .04s; } .d2 { animation-delay: .09s; } .d3 { animation-delay: .14s; } .d4 { animation-delay: .19s; }

@media (max-width: 900px) { .stat-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
    .page-wrap { padding: 20px 16px 48px; }
    .toolbar { flex-direction: column; align-items: stretch; }
    .search-input { width: 100%; }
    .filter-bar { flex-wrap: wrap; }
    .hide-mobile { display: none !important; }
    .hero-banner { padding: 24px 20px; }
}
@media (max-width: 600px) {
    .tbl-wrap { display: none !important; }
    .mobile-card-list { display: flex; }
    .stat-row { grid-template-columns: repeat(2, 1fr); }
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">

    <div class="hero-banner fade-up d1">
        <div class="hero-eyebrow">
            <span class="dot"></span>
            Manajemen Perpanjangan
        </div>
        <h1 class="hero-title">Perpanjangan <em>Sewa</em></h1>
        <p class="hero-sub">Kelola pengajuan perpanjangan sewa dari penghuni</p>
    </div>

    @php
        $totalPending  = $renewals->where('status','pending_admin')->count();
        $totalPay      = $renewals->where('status','pending_payment')->count();
        $totalApproved = $renewals->where('status','approved')->count();
        $totalRejected = $renewals->where('status','rejected')->count();
    @endphp

    <div class="stat-row fade-up d2">
        <div class="scard amber">
            <div class="scard-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="scard-val">{{ $totalPending }}</div>
            <div class="scard-lbl">Menunggu Konfirmasi</div>
            <div class="scard-badge"><i class="bi bi-clock"></i> Review</div>
        </div>
        <div class="scard blue">
            <div class="scard-icon"><i class="bi bi-credit-card-fill"></i></div>
            <div class="scard-val">{{ $totalPay }}</div>
            <div class="scard-lbl">Menunggu Pembayaran</div>
            <div class="scard-badge"><i class="bi bi-arrow-right-circle"></i> Pending</div>
        </div>
        <div class="scard green">
            <div class="scard-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="scard-val">{{ $totalApproved }}</div>
            <div class="scard-lbl">Disetujui</div>
            <div class="scard-badge"><i class="bi bi-graph-up"></i> Selesai</div>
        </div>
        <div class="scard red">
            <div class="scard-icon"><i class="bi bi-x-circle-fill"></i></div>
            <div class="scard-val">{{ $totalRejected }}</div>
            <div class="scard-lbl">Ditolak</div>
            <div class="scard-badge"><i class="bi bi-dash-circle"></i> Ditolak</div>
        </div>
    </div>

    <div class="toolbar fade-up d3">
        <div class="filter-bar">
            <span style="font-size:.72rem;font-weight:600;color:var(--neutral-400)">Filter:</span>
            <a href="{{ route('admin.renewals.index') }}" class="filter-chip {{ !request('status') ? 'active' : '' }}">
                Semua <span class="filter-count">{{ $renewals->count() }}</span>
            </a>
            <a href="{{ route('admin.renewals.index', ['status'=>'pending_admin']) }}" class="filter-chip {{ request('status')==='pending_admin' ? 'active' : '' }}">
                <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--amber)"></i>
                Konfirmasi <span class="filter-count" style="background:var(--amber)">{{ $totalPending }}</span>
            </a>
            <a href="{{ route('admin.renewals.index', ['status'=>'pending_payment']) }}" class="filter-chip {{ request('status')==='pending_payment' ? 'active' : '' }}">
                <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--blue)"></i>
                Pembayaran <span class="filter-count" style="background:var(--blue)">{{ $totalPay }}</span>
            </a>
            <a href="{{ route('admin.renewals.index', ['status'=>'approved']) }}" class="filter-chip {{ request('status')==='approved' ? 'active' : '' }}">
                <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--green-500)"></i>
                Disetujui <span class="filter-count">{{ $totalApproved }}</span>
            </a>
            <a href="{{ route('admin.renewals.index', ['status'=>'rejected']) }}" class="filter-chip {{ request('status')==='rejected' ? 'active' : '' }}">
                <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--red)"></i>
                Ditolak <span class="filter-count" style="background:var(--red)">{{ $totalRejected }}</span>
            </a>
        </div>
        <form method="GET" action="{{ route('admin.renewals.index') }}">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="search-input"
                       placeholder="Cari penghuni / kamar..."
                       value="{{ request('search') }}">
            </div>
        </form>
    </div>

    <div class="uk-card fade-up d4">
        <div class="uk-card-head">
            <div class="card-head-left">
                <div class="head-icon"><i class="bi bi-arrow-repeat"></i></div>
                Daftar Perpanjangan Sewa
                <span style="background:var(--green-100);color:var(--green-800);font-size:.63rem;font-weight:600;padding:2px 9px;border-radius:var(--r-pill);border:1px solid var(--green-200)">
                    {{ $renewals->count() }} pengajuan
                </span>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>Penghuni</th>
                        <th>Kamar</th>
                        <th class="hide-mobile">Durasi</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="hide-mobile">Invoice</th>
                        <th class="hide-mobile">Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($renewals as $r)
                    @php
                        $stMap = [
                            'pending_admin'   => ['cls'=>'st-pending-admin','txt'=>'Konfirmasi'],
                            'pending_payment' => ['cls'=>'st-pending-pay',  'txt'=>'Menunggu Pembayaran'],
                            'approved'        => ['cls'=>'st-approved',     'txt'=>'Disetujui'],
                            'rejected'        => ['cls'=>'st-rejected',     'txt'=>'Ditolak'],
                        ];
                        $st = $stMap[$r->status] ?? ['cls'=>'st-pending-admin','txt'=>ucfirst($r->status)];
                        $invMap = [
                            'unpaid'  => ['cls'=>'inv-unpaid', 'txt'=>'Belum Bayar'],
                            'paid'    => ['cls'=>'inv-paid',   'txt'=>'Lunas'],
                            'overdue' => ['cls'=>'inv-overdue','txt'=>'Terlambat'],
                        ];
                        $inv = $r->invoice ? ($invMap[$r->invoice->status] ?? ['cls'=>'inv-unpaid','txt'=>$r->invoice->status]) : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="tenant-name">{{ $r->user?->name }}</div>
                            <div class="tenant-email">{{ $r->user?->email }}</div>
                        </td>
                        <td>
                            <span class="room-chip">
                                <i class="bi bi-door-open" style="font-size:.6rem"></i>
                                {{ $r->room?->room_number }}
                            </span>
                        </td>
                        <td class="hide-mobile">
                            <span class="dur-badge">
                                <i class="bi bi-calendar3" style="font-size:.6rem"></i>
                                {{ $r->duration_months }} bln
                            </span>
                        </td>
                        <td><div class="amount-txt">{{ $r->formatted_amount }}</div></td>
                        <td><span class="st-badge {{ $st['cls'] }}">{{ $st['txt'] }}</span></td>
                        <td class="hide-mobile">
                            @if($inv)
                                <span class="inv-badge {{ $inv['cls'] }}">{{ $inv['txt'] }}</span>
                            @else
                                <span class="inv-badge inv-none">Belum ada</span>
                            @endif
                        </td>
                        <td class="hide-mobile"><div class="date-txt">{{ $r->created_at->format('d M Y') }}</div></td>
                        <td>
                            <a href="{{ route('admin.renewals.show', $r->id) }}" class="tbl-action">
                                <i class="bi bi-arrow-right"></i>
                                <span class="hide-mobile">Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-arrow-repeat"></i></div>
                                <div class="empty-title">Belum ada pengajuan perpanjangan</div>
                                <div class="empty-sub">Pengajuan dari penghuni akan muncul di sini</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-card-list">
            @forelse($renewals as $r)
            @php
                $stMap = [
                    'pending_admin'   => ['cls'=>'st-pending-admin','txt'=>'Konfirmasi'],
                    'pending_payment' => ['cls'=>'st-pending-pay',  'txt'=>'Bayar'],
                    'approved'        => ['cls'=>'st-approved',     'txt'=>'Disetujui'],
                    'rejected'        => ['cls'=>'st-rejected',     'txt'=>'Ditolak'],
                ];
                $st = $stMap[$r->status] ?? ['cls'=>'st-pending-admin','txt'=>ucfirst($r->status)];
                $invMap = [
                    'unpaid'  => ['cls'=>'inv-unpaid', 'txt'=>'Belum Bayar'],
                    'paid'    => ['cls'=>'inv-paid',   'txt'=>'Lunas'],
                    'overdue' => ['cls'=>'inv-overdue','txt'=>'Terlambat'],
                ];
                $inv = $r->invoice ? ($invMap[$r->invoice->status] ?? ['cls'=>'inv-unpaid','txt'=>$r->invoice->status]) : null;
            @endphp
            <div class="m-card">
                <div class="m-card-top">
                    <div>
                        <div class="m-card-name">{{ $r->user?->name }}</div>
                        <div class="m-card-email">{{ $r->user?->email }}</div>
                    </div>
                    <span class="st-badge {{ $st['cls'] }}">{{ $st['txt'] }}</span>
                </div>
                <div class="m-card-body">
                    <div>
                        <div class="m-field-lbl">Kamar</div>
                        <span class="room-chip">{{ $r->room?->room_number }}</span>
                    </div>
                    <div>
                        <div class="m-field-lbl">Durasi</div>
                        <span class="dur-badge">{{ $r->duration_months }} bln</span>
                    </div>
                    <div>
                        <div class="m-field-lbl">Total</div>
                        <div class="amount-txt">{{ $r->formatted_amount }}</div>
                    </div>
                    <div>
                        <div class="m-field-lbl">Invoice</div>
                        @if($inv)
                            <span class="inv-badge {{ $inv['cls'] }}">{{ $inv['txt'] }}</span>
                        @else
                            <span class="inv-badge inv-none">Belum ada</span>
                        @endif
                    </div>
                </div>
                <div class="m-card-footer">
                    <span class="date-txt">{{ $r->created_at->format('d M Y') }}</span>
                    <a href="{{ route('admin.renewals.show', $r->id) }}" class="tbl-action">
                        <i class="bi bi-arrow-right"></i> Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-arrow-repeat"></i></div>
                <div class="empty-title">Belum ada pengajuan perpanjangan</div>
                <div class="empty-sub">Pengajuan dari penghuni akan muncul di sini</div>
            </div>
            @endforelse
        </div>

        @if($renewals->hasPages())
        <div class="pagination-wrap">{{ $renewals->links() }}</div>
        @endif
    </div>

</div>
</body>
</html>