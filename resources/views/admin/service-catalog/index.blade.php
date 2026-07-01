<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Katalog Layanan — Urban Kost Admin</title>
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
    --amber:       #f4a535;
    --amber-pale:  #fffbf0;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --card-r: 14px;
    --font-body: 'Instrument Sans', sans-serif;
    --font-display: 'Fraunces', serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: var(--font-body);
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh;
    font-size: 14px;
    -webkit-font-smoothing: antialiased;
}
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--green-50); }
::-webkit-scrollbar-thumb { background: var(--green-400); border-radius: 3px; }

/* Subtle background grid */
body::before {
    content: '';
    position: fixed; inset: 0; z-index: -1;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.08) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.05) 0%, transparent 50%);
    pointer-events: none;
}

.page-wrap { max-width: 1100px; margin: 0 auto; padding: 28px 24px 60px; }
@media (max-width: 640px) { .page-wrap { padding: 16px 12px 48px; } }
@media (min-width: 1024px) { .page-wrap { padding: 36px 40px 64px; } }

/* ── Hero Card ─────────────────────────────────── */
.hero-card {
    position: relative; overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 28px 32px;
    margin-bottom: 22px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    min-height: 130px;
}
.hero-card::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-arc {
    position: absolute; right: -80px; top: -80px;
    width: 300px; height: 300px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1);
    pointer-events: none;
}
.hero-arc-2 {
    position: absolute; right: -20px; top: -20px;
    width: 180px; height: 180px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.06);
    pointer-events: none;
}
.hero-left { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 3px 11px; border-radius: 100px;
    font-size: .63rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.8);
    margin-bottom: 8px;
}
.hero-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.3rem, 3.5vw, 1.75rem);
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
    letter-spacing: -.025em;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.65); }
.hero-sub { font-size: .78rem; color: rgba(255,255,255,.5); margin-top: 5px; }
.hero-right { position: relative; z-index: 1; display: flex; gap: 10px; flex-shrink: 0; flex-wrap: wrap; }

/* ── Buttons ───────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 6px; padding: 9px 16px; border-radius: 10px;
    font-size: .8rem; font-weight: 600; font-family: var(--font-body);
    cursor: pointer; border: none; transition: all .15s;
    text-decoration: none; white-space: nowrap;
    min-height: 40px; -webkit-tap-highlight-color: transparent;
}
.btn-primary {
    background: #fff;
    color: var(--green-900);
    box-shadow: 0 3px 12px rgba(29,148,88,.3);
}
.btn-primary:hover { background: var(--green-50); transform: translateY(-1px); box-shadow: 0 5px 16px rgba(29,148,88,.35); color: var(--green-900); }
.btn-primary:active { transform: none; }
.btn-ghost-white {
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.25);
    color: #fff;
}
.btn-ghost-white:hover { background: rgba(255,255,255,.22); border-color: rgba(255,255,255,.4); color: #fff; }
.btn-outline {
    background: var(--neutral-0); border: 1.5px solid var(--neutral-200);
    color: var(--neutral-600);
}
.btn-outline:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
.btn-xs { padding: 6px 8px; font-size: 11px; border-radius: 8px; min-height: 32px; min-width: 32px; }
.btn-sm { padding: 8px 14px; font-size: .78rem; }
.btn-toggle-on  { background: var(--amber-pale); border: 1.5px solid #fde68a; color: #92400e; }
.btn-toggle-on:hover  { background: #fef3c7; }
.btn-toggle-off { background: var(--green-50); border: 1.5px solid var(--green-200); color: var(--green-700); }
.btn-toggle-off:hover { background: var(--green-100); }
.btn-danger-xs  { background: var(--red-pale); border: 1.5px solid #fca5a5; color: #991b1b; }
.btn-danger-xs:hover { background: #fee2e2; }

/* ── Flash ─────────────────────────────────────── */
.flash {
    padding: 11px 15px; border-radius: 11px; margin-bottom: 16px;
    font-size: .8rem; font-weight: 500;
    display: flex; align-items: flex-start; gap: 9px;
}

/* ── Stat Row ──────────────────────────────────── */
.stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 18px; }
.stat-card {
    background: var(--neutral-0);
    border: 1px solid var(--green-100);
    border-radius: var(--card-r);
    padding: 16px 18px;
    box-shadow: var(--shadow-sm);
    display: flex; align-items: center; gap: 14px;
    transition: border-color .2s, box-shadow .2s, transform .2s;
}
.stat-card:hover { border-color: var(--green-300); box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-icon {
    width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: .95rem;
}
.stat-icon.green { background: var(--green-100); color: var(--green-700); }
.stat-icon.amber { background: var(--amber-pale); color: #92400e; }
.stat-icon.neutral { background: var(--neutral-100); color: var(--neutral-500); }
.stat-val {
    font-family: var(--font-display);
    font-size: 1.4rem; font-weight: 700;
    color: var(--neutral-900); line-height: 1;
}
.stat-lbl { font-size: .67rem; font-weight: 600; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

/* ── Filter Bar ────────────────────────────────── */
.filter-bar {
    background: var(--neutral-0);
    border: 1px solid var(--green-100);
    border-radius: var(--card-r);
    padding: 12px 14px;
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    margin-bottom: 16px;
    box-shadow: var(--shadow-sm);
}
.filter-bar input, .filter-bar select {
    border: 1.5px solid var(--neutral-200);
    border-radius: 9px;
    padding: 9px 12px;
    font-size: .82rem;
    font-family: var(--font-body);
    background: var(--neutral-50);
    color: var(--neutral-900);
    transition: border-color .15s, box-shadow .15s;
    outline: none;
    min-height: 40px;
}
.filter-bar input:focus, .filter-bar select:focus {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(42,179,107,.12);
    background: var(--neutral-0);
}
.filter-bar input { flex: 1; min-width: 160px; }
.filter-bar select { min-width: 150px; }
@media (max-width: 560px) {
    .filter-bar { flex-direction: column; align-items: stretch; }
    .filter-bar input, .filter-bar select { width: 100%; flex: unset; }
    .filter-btns { display: flex; gap: 8px; }
    .filter-btns .btn { flex: 1; justify-content: center; }
}

/* ── Table Card ────────────────────────────────── */
.table-card {
    background: var(--neutral-0);
    border-radius: var(--card-r);
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}
.table-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--green-100);
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; flex-wrap: wrap;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.table-card-title {
    display: flex; align-items: center; gap: 7px;
    font-size: .72rem; font-weight: 600;
    color: var(--neutral-600);
    text-transform: uppercase; letter-spacing: .07em;
}
.table-card-title i { color: var(--green-500); }
.count-badge {
    background: var(--green-100); color: var(--green-800);
    font-size: .62rem; font-weight: 700; padding: 2px 9px;
    border-radius: 20px; border: 1px solid var(--green-200);
}

/* ── Table ─────────────────────────────────────── */
.tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.tbl { width: 100%; border-collapse: collapse; min-width: 620px; }
.tbl thead tr { border-bottom: 1px solid var(--green-100); }
.tbl th {
    padding: 10px 14px;
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .07em;
    color: var(--neutral-500);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    text-align: left; white-space: nowrap;
}
.tbl td { padding: 13px 14px; border-bottom: 1px solid var(--neutral-100); vertical-align: middle; font-size: .82rem; }
.tbl tbody tr:hover { background: #f5fdf8; }
.tbl tbody tr:last-child td { border-bottom: none; }

/* ── Badges ────────────────────────────────────── */
.badge-active {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--green-100); color: var(--green-700);
    border: 1px solid var(--green-200);
    font-size: .65rem; font-weight: 600; padding: 3px 10px; border-radius: 100px;
}
.badge-active::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--green-500); flex-shrink: 0; }
.badge-inactive {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--neutral-100); color: var(--neutral-500);
    border: 1px solid var(--neutral-200);
    font-size: .65rem; font-weight: 600; padding: 3px 10px; border-radius: 100px;
}
.badge-inactive::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--neutral-400); flex-shrink: 0; }

/* ── Cell helpers ──────────────────────────────── */
.svc-name { font-weight: 600; color: var(--neutral-900); display: flex; align-items: center; gap: 6px; }
.svc-key {
    font-family: monospace; background: var(--neutral-100);
    border: 1px solid var(--neutral-200); color: var(--neutral-600);
    font-size: .72rem; padding: 2px 7px; border-radius: 5px;
}
.svc-price { font-weight: 700; color: var(--green-700); font-family: var(--font-display); font-size: .88rem; }
.svc-desc { font-size: .75rem; color: var(--neutral-400); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.sort-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 24px; height: 24px; border-radius: 50%;
    background: var(--green-100); color: var(--green-700);
    font-size: .68rem; font-weight: 700; flex-shrink: 0;
}
.act-row { display: flex; gap: 5px; justify-content: flex-end; }

/* ── Mobile Card List ──────────────────────────── */
.mobile-list { display: none; padding: 10px; }
.svc-card {
    background: var(--neutral-0);
    border: 1px solid var(--green-100);
    border-radius: 12px; padding: 14px; margin-bottom: 10px;
    box-shadow: var(--shadow-sm);
}
.svc-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 8px; }
.svc-card-name { font-weight: 600; color: var(--neutral-900); font-size: .88rem; display: flex; align-items: center; gap: 6px; }
.svc-card-meta { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 10px; }
.svc-card-actions { display: flex; gap: 6px; flex-wrap: wrap; }

/* ── Empty State ───────────────────────────────── */
.empty-state { text-align: center; padding: 52px 20px; }
.empty-state i { font-size: 2rem; color: var(--green-300); display: block; margin-bottom: 10px; }
.empty-state p { color: var(--neutral-400); font-size: .82rem; }

/* ── Pagination ────────────────────────────────── */
.pagination-wrap {
    padding: 12px 18px; border-top: 1px solid var(--green-100);
    display: flex; justify-content: flex-end;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}

/* ── Delete Modal ──────────────────────────────── */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45); backdrop-filter: blur(4px);
    z-index: 9999; display: none; align-items: flex-end;
    justify-content: center; padding: 0;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: var(--neutral-0);
    border-radius: 18px 18px 0 0;
    padding: 24px 20px 32px; width: 100%; max-width: 460px;
    box-shadow: 0 -8px 40px rgba(0,0,0,.15);
    animation: slideUp .25s ease;
}
@media (min-width: 480px) {
    .modal-overlay { align-items: center; padding: 16px; }
    .modal-box { border-radius: 18px; padding: 28px; animation: fadeScale .2s ease; }
}
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
@keyframes fadeScale { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
.modal-title {
    font-family: var(--font-display);
    font-size: 1.1rem; font-weight: 700;
    color: var(--neutral-900); margin-bottom: 8px;
    display: flex; align-items: center; gap: 8px;
}
.modal-body { font-size: .82rem; color: var(--neutral-500); line-height: 1.6; margin-bottom: 18px; }
.modal-actions { display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
@media (max-width: 480px) {
    .modal-actions { flex-direction: column-reverse; }
    .modal-actions .btn, .modal-actions form, .modal-actions form button { width: 100%; justify-content: center; }
}

/* ── Responsive ────────────────────────────────── */
@media (max-width: 640px) {
    .tbl-wrap { display: none; }
    .mobile-list { display: block; }
    .stat-row { grid-template-columns: 1fr 1fr 1fr; }
    .hero-card { flex-direction: column; align-items: flex-start; min-height: auto; padding: 22px 20px; }
    .hero-arc, .hero-arc-2 { display: none; }
}
@media (max-width: 400px) {
    .stat-row { grid-template-columns: 1fr; }
    .stat-card { gap: 10px; }
}

@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.hero-card   { animation: fadeUp .3s ease both; }
.stat-row    { animation: fadeUp .3s .07s ease both; }
.filter-bar  { animation: fadeUp .3s .13s ease both; }
.table-card  { animation: fadeUp .3s .18s ease both; }
</style>
</head>
<body>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <div class="hero-card">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Manajemen Layanan
            </div>
            <div class="hero-title">
                Katalog <em>Layanan Tambahan</em>
            </div>
            <div class="hero-sub">Kelola layanan yang dapat dipilih penyewa saat booking</div>
        </div>
        <div class="hero-right">
            <a href="{{ route('admin.service-catalog.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Tambah Layanan
            </a>
        </div>
    </div>

    @foreach(['success','error','warning','info'] as $type)
    @if(session($type))
    <div class="flash" style="
        background:{{ $type==='success'?'var(--green-50)':($type==='error'?'var(--red-pale)':($type==='warning'?'var(--amber-pale)':'var(--blue-pale)')) }};
        border:1px solid {{ $type==='success'?'var(--green-200)':($type==='error'?'#fca5a5':($type==='warning'?'#fde68a':'#bfdbfe')) }};
        color:{{ $type==='success'?'var(--green-800)':($type==='error'?'#991b1b':($type==='warning'?'#92400e':'#1e40af')) }}">
        <i class="bi bi-{{ $type==='success'?'check-circle-fill':($type==='error'?'x-circle-fill':($type==='warning'?'exclamation-triangle-fill':'info-circle-fill')) }}"></i>
        <span>{!! session($type) !!}</span>
    </div>
    @endif
    @endforeach

    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div>
                <div class="stat-val">{{ $stats['total'] }}</div>
                <div class="stat-lbl">Total Layanan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-val" style="color:var(--green-600)">{{ $stats['active'] }}</div>
                <div class="stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon neutral"><i class="bi bi-pause-circle-fill"></i></div>
            <div>
                <div class="stat-val" style="color:var(--neutral-400)">{{ $stats['inactive'] }}</div>
                <div class="stat-lbl">Nonaktif</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.service-catalog.index') }}">
    <div class="filter-bar">
        <input type="text" name="search" placeholder="  Cari nama, key..." value="{{ request('search') }}">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status')==='active'   ? 'selected':'' }}> Aktif</option>
            <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}> Nonaktif</option>
        </select>
        <div class="filter-btns" style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i> Filter
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.service-catalog.index') }}" class="btn btn-outline btn-sm">
                <i class="bi bi-x-lg"></i> Reset
            </a>
            @endif
        </div>
    </div>
    </form>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Semua Layanan
                <span class="count-badge">{{ $services->total() ?? $services->count() }}</span>
            </div>
        </div>

        <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:50px;text-align:center">No.</th>
                    <th>Nama Layanan</th>
                    <th>Key</th>
                    <th>Harga/Bulan</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($services as $svc)
            <tr>
                <td style="text-align:center">
                    <span class="sort-num">{{ $svc->sort_order ?: $loop->iteration }}</span>
                </td>
                <td>
                    <div class="svc-name">
                        @if($svc->icon)<span style="font-size:1.1rem">{{ $svc->icon }}</span>@endif
                        {{ $svc->label }}
                    </div>
                </td>
                <td><span class="svc-key">{{ $svc->key }}</span></td>
                <td><span class="svc-price">{{ $svc->formatted_price }}</span></td>
                <td>
                    @if($svc->is_active)
                        <span class="badge-active">Aktif</span>
                    @else
                        <span class="badge-inactive">Nonaktif</span>
                    @endif
                </td>
                <td><span class="svc-desc">{{ $svc->description ?: '—' }}</span></td>
                <td>
                    <div class="act-row">
                        <form method="POST" action="{{ route('admin.service-catalog.toggle', $svc->id) }}" style="margin:0">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn btn-xs {{ $svc->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}"
                                    title="{{ $svc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bi bi-{{ $svc->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.service-catalog.edit', $svc->id) }}"
                           class="btn btn-xs btn-outline" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <button type="button" class="btn btn-xs btn-danger-xs" title="Hapus"
                                onclick="openDeleteModal({{ $svc->id }}, '{{ addslashes($svc->label) }}')">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <p>Belum ada layanan tambahan.</p>
                        <a href="{{ route('admin.service-catalog.create') }}" class="btn btn-primary" style="margin-top:14px">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Layanan Pertama
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        </div>

        <div class="mobile-list">
            @forelse($services as $svc)
            <div class="svc-card">
                <div class="svc-card-top">
                    <div class="svc-card-name">
                        <span class="sort-num">{{ $svc->sort_order ?: $loop->iteration }}</span>
                        @if($svc->icon)<span style="font-size:1.05rem">{{ $svc->icon }}</span>@endif
                        {{ $svc->label }}
                    </div>
                    @if($svc->is_active)
                        <span class="badge-active">Aktif</span>
                    @else
                        <span class="badge-inactive">Nonaktif</span>
                    @endif
                </div>
                <div class="svc-card-meta">
                    <span class="svc-key">{{ $svc->key }}</span>
                    <span class="svc-price">{{ $svc->formatted_price }}/bln</span>
                </div>
                @if($svc->description)
                <div style="font-size:.75rem;color:var(--neutral-400);margin-bottom:10px">{{ $svc->description }}</div>
                @endif
                <div class="svc-card-actions">
                    <form method="POST" action="{{ route('admin.service-catalog.toggle', $svc->id) }}" style="margin:0">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm {{ $svc->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                            <i class="bi bi-{{ $svc->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                            {{ $svc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.service-catalog.edit', $svc->id) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-danger-xs"
                            onclick="openDeleteModal({{ $svc->id }}, '{{ addslashes($svc->label) }}')">
                        <i class="bi bi-trash3-fill"></i> Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-grid-3x3-gap"></i>
                <p>Belum ada layanan tambahan.</p>
                <a href="{{ route('admin.service-catalog.create') }}" class="btn btn-primary" style="margin-top:14px">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Layanan Pertama
                </a>
            </div>
            @endforelse
        </div>

        @if(method_exists($services,'hasPages') && $services->hasPages())
        <div class="pagination-wrap">{{ $services->links() }}</div>
        @endif
    </div>

</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-title">
            <i class="bi bi-exclamation-triangle-fill" style="color:var(--red)"></i>
            Hapus Layanan
        </div>
        <div class="modal-body">
            Anda yakin ingin menghapus layanan <strong id="deleteModalLabel">—</strong>?<br>
            Layanan tidak bisa dihapus jika masih dipakai di booking aktif. Tindakan ini tidak bisa dibatalkan.
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:var(--red);color:#fff;gap:6px">
                    <i class="bi bi-trash3-fill"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, label) {
    document.getElementById('deleteModalLabel').textContent = label;
    document.getElementById('deleteForm').action = `/admin/service-catalog/${id}`;
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteModal(); });
</script>
</body>
</html>