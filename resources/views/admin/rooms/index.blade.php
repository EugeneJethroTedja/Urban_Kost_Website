<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Kamar — Urban Kost</title>
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
.page-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50); }
.page-bg::before {
    content: ''; position: absolute; inset: 0;
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
.hero-left { position: relative; z-index: 1; }
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
.hero-right { position: relative; z-index: 1; flex-shrink: 0; }
.btn-add {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px; font-size: .82rem; font-weight: 600;
    color: var(--green-900); background: rgba(255,255,255,.92);
    border: none; border-radius: 10px; cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,.12); transition: all .2s;
    text-decoration: none; white-space: nowrap;
}
.btn-add:hover { background: #fff; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(0,0,0,.16); color: var(--green-900); }

.stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.scard {
    position: relative; overflow: hidden; border-radius: var(--r-card);
    padding: 20px 18px; background: var(--neutral-0);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    transition: border-color .25s, box-shadow .3s, transform .25s;
}
.scard:hover { border-color: var(--green-300); box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.scard::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--r-card) var(--r-card) 0 0; }
.scard.green::before   { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
.scard.emerald::before { background: linear-gradient(90deg, #10b981, #34d399); }
.scard.amber::before   { background: linear-gradient(90deg, var(--amber), #fcd34d); }
.scard.red::before     { background: linear-gradient(90deg, var(--red), #f87171); }
.scard.blue::before    { background: linear-gradient(90deg, var(--blue), #60a5fa); }
.scard-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; margin-bottom: 12px; }
.scard.green   .scard-icon { background: var(--green-100); color: var(--green-700); }
.scard.emerald .scard-icon { background: #d1fae5; color: #065f46; }
.scard.amber   .scard-icon { background: var(--amber-pale); color: #92400e; }
.scard.red     .scard-icon { background: var(--red-pale); color: var(--red); }
.scard.blue    .scard-icon { background: var(--blue-pale); color: var(--blue); }
.scard-val { font-family: 'Fraunces', serif; font-size: 2rem; font-weight: 700; color: var(--neutral-900); line-height: 1; letter-spacing: -.03em; margin-bottom: 3px; }
.scard-lbl { font-size: .68rem; font-weight: 600; color: var(--neutral-400); text-transform: uppercase; letter-spacing: .07em; }
.scard-bg-icon { position: absolute; right: -6px; bottom: -6px; font-size: 4rem; opacity: .04; line-height: 1; color: var(--neutral-900); }

.flash-alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; margin-bottom: 14px; font-size: .8rem; font-weight: 500; border: 1px solid; }

.search-form { margin-bottom: 14px; }
.search-wrap { position: relative; max-width: 420px; }
.search-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--neutral-400); font-size: .82rem; pointer-events: none; }
.search-input {
    width: 100%; padding: 10px 40px 10px 36px;
    font-size: .82rem; font-family: 'Instrument Sans', sans-serif;
    border: 1px solid var(--neutral-200); border-radius: 10px;
    outline: none; transition: border-color .15s, box-shadow .15s;
    background: var(--neutral-0); color: var(--neutral-900);
}
.search-input:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.search-clear {
    position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
    color: var(--neutral-400); text-decoration: none; font-size: .82rem;
    width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;
    border-radius: 50%; background: var(--neutral-100); transition: all .15s;
}
.search-clear:hover { background: var(--red-pale); color: var(--red); }

.filter-bar { display: flex; align-items: center; gap: 7px; margin-bottom: 14px; flex-wrap: wrap; }
.filter-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 13px; border-radius: var(--r-pill);
    font-size: .73rem; font-weight: 600;
    border: 1px solid var(--neutral-200); background: var(--neutral-0); color: var(--neutral-500);
    cursor: pointer; transition: all .15s; text-decoration: none;
}
.filter-chip:hover { border-color: var(--green-400); background: var(--green-50); color: var(--green-700); }
.filter-chip.active { border-color: var(--green-500); background: var(--green-100); color: var(--green-700); }
.filter-count { background: var(--green-500); color: #fff; font-size: .58rem; font-weight: 700; padding: 1px 6px; border-radius: var(--r-pill); }
.filter-chip:not(.active) .filter-count { background: var(--neutral-200); color: var(--neutral-500); }

.uk-card { background: var(--neutral-0); border: 1px solid var(--green-100); border-radius: var(--r-card); box-shadow: var(--shadow-md); overflow: hidden; }
.uk-card-head { display: flex; align-items: center; gap: 9px; padding: 14px 20px; border-bottom: 1px solid var(--neutral-100); background: linear-gradient(135deg, var(--green-50), var(--neutral-0)); }
.card-head-left { display: flex; align-items: center; gap: 9px; font-size: .72rem; font-weight: 600; color: var(--neutral-600); text-transform: uppercase; letter-spacing: .07em; }
.head-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .82rem; background: var(--green-100); color: var(--green-700); flex-shrink: 0; }

.tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.dtbl { width: 100%; border-collapse: collapse; }
.dtbl thead th { padding: 9px 16px; font-size: .63rem; font-weight: 600; letter-spacing: .07em; text-transform: uppercase; color: var(--neutral-400); background: var(--neutral-50); border-bottom: 1px solid var(--neutral-100); white-space: nowrap; }
.dtbl tbody tr { border-bottom: 1px solid var(--neutral-100); transition: background .12s; }
.dtbl tbody tr:last-child { border-bottom: none; }
.dtbl tbody tr:hover td { background: var(--green-50); }
.dtbl tbody td { padding: 12px 16px; font-size: .8rem; vertical-align: middle; }

.room-num { font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 700; color: var(--green-700); letter-spacing: -.02em; }
.room-name-txt { font-size: .75rem; color: var(--neutral-400); margin-top: 1px; }
.price-txt { font-weight: 700; color: var(--neutral-900); font-size: .82rem; }
.price-sub { font-size: .67rem; color: var(--neutral-400); margin-top: 1px; }
.floor-chip { display: inline-flex; align-items: center; gap: 3px; background: var(--green-50); border: 1px solid var(--green-100); border-radius: 6px; padding: 3px 8px; font-size: .71rem; font-weight: 600; color: var(--green-600); }
.size-txt { font-size: .77rem; color: var(--neutral-500); font-weight: 500; }

.fac-wrap { display: flex; flex-wrap: wrap; gap: 4px; max-width: 220px; }
.fac-chip { display: inline-flex; align-items: center; gap: 3px; background: var(--green-50); border: 1px solid var(--green-200); border-radius: 5px; padding: 2px 7px; font-size: .65rem; font-weight: 600; color: var(--green-700); }
.fac-more { background: var(--neutral-100); border-color: var(--neutral-200); color: var(--neutral-500); }

.st-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: var(--r-pill); font-size: .68rem; font-weight: 600; white-space: nowrap; }
.st-available   { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-200); }
.st-occupied    { background: var(--red-pale);   color: var(--red);       border: 1px solid var(--red-200); }
.st-reserved    { background: var(--blue-pale);  color: var(--blue);      border: 1px solid var(--blue-200); }
.st-maintenance { background: var(--amber-pale); color: #92400e;          border: 1px solid var(--amber-200); }

.room-thumb { width: 42px; height: 42px; border-radius: 9px; object-fit: cover; border: 1.5px solid var(--green-200); flex-shrink: 0; }
.room-thumb-placeholder { width: 42px; height: 42px; border-radius: 9px; background: var(--green-100); border: 1px solid var(--green-200); display: flex; align-items: center; justify-content: center; color: var(--green-400); font-size: 1rem; flex-shrink: 0; }

.tbl-action { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 7px; border: 1px solid var(--neutral-200); color: var(--neutral-500); text-decoration: none; font-size: .78rem; transition: all .15s; background: var(--neutral-0); cursor: pointer; }
.tbl-action:hover { border-color: var(--green-300); color: var(--green-600); background: var(--green-50); }
.tbl-action.danger:hover { border-color: var(--red-200); color: var(--red); background: var(--red-pale); }

.empty-state { text-align: center; padding: 48px 20px; color: var(--neutral-400); }
.empty-state i { font-size: 2.5rem; display: block; opacity: .2; margin-bottom: 12px; color: var(--green-500); }
.empty-state p { font-size: .82rem; line-height: 1.6; }
.empty-state a { color: var(--green-600); font-weight: 600; text-decoration: none; }

.pagination-wrap { display: flex; justify-content: flex-end; padding: 12px 18px; border-top: 1px solid var(--neutral-100); background: var(--green-50); }

.fade-up { opacity: 0; transform: translateY(14px); animation: fadeUp .4s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .04s; } .d2 { animation-delay: .09s; } .d3 { animation-delay: .14s; } .d4 { animation-delay: .18s; }

@media (max-width: 900px) { .stat-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
    .page-wrap { padding: 20px 16px 48px; }
    .hero-banner { flex-direction: column; padding: 22px 20px; }
    .hero-right { width: 100%; }
    .btn-add { width: 100%; justify-content: center; }
    .hide-mobile { display: none; }
}
@media (max-width: 640px) {
    .page-wrap { padding: 16px 12px 48px; }
    .dtbl thead th, .dtbl tbody td { padding: 8px 10px; }
    .fac-wrap { max-width: 120px; }
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">

    <div class="hero-banner fade-up d1">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Manajemen Kamar
            </div>
            <h1 class="hero-title">Manajemen <em>Kamar</em></h1>
            <p class="hero-sub">Kelola data kamar, harga, fasilitas, dan status hunian</p>
        </div>
        <div class="hero-right">
            <a href="{{ route('admin.rooms.create') }}" class="btn-add">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kamar
            </a>
        </div>
    </div>

    @foreach(['success','error','warning'] as $type)
    @if(session($type))
    <div class="flash-alert fade-up d1" style="
        background:{{ $type==='success'?'var(--green-100)':($type==='error'?'var(--red-pale)':'var(--amber-pale)') }};
        border-color:{{ $type==='success'?'var(--green-200)':($type==='error'?'var(--red-200)':'var(--amber-200)') }};
        color:{{ $type==='success'?'var(--green-800)':($type==='error'?'var(--red)':'#92400e') }};
    ">
        <i class="bi bi-{{ $type==='success'?'check-circle-fill':($type==='error'?'exclamation-circle-fill':'exclamation-triangle-fill') }}"></i>
        {!! session($type) !!}
    </div>
    @endif
    @endforeach

    @php
        $allRooms    = $rooms->total();
        $available   = \App\Models\Room::where('status','available')->count();
        $occupied    = \App\Models\Room::where('status','occupied')->count();
        $reserved    = \App\Models\Room::where('status','reserved')->count();
        $maintenance = \App\Models\Room::where('status','maintenance')->count();
    @endphp

    <div class="stat-row fade-up d2">
        <div class="scard green">
            <div class="scard-icon"><i class="bi bi-house-door-fill"></i></div>
            <div class="scard-val">{{ $allRooms }}</div>
            <div class="scard-lbl">Total Kamar</div>
        </div>
        <div class="scard emerald">
            <div class="scard-icon"><i class="bi bi-door-open-fill"></i></div>
            <div class="scard-val">{{ $available }}</div>
            <div class="scard-lbl">Tersedia</div>
        </div>
        <div class="scard red">
            <div class="scard-icon"><i class="bi bi-people-fill"></i></div>
            <div class="scard-val">{{ $occupied }}</div>
            <div class="scard-lbl">Terisi</div>
        </div>
        <div class="scard amber">
            <div class="scard-icon"><i class="bi bi-tools"></i></div>
            <div class="scard-val">{{ $reserved + $maintenance }}</div>
            <div class="scard-lbl">Reserved / Perbaikan</div>
        </div>
    </div>

    <div class="search-form fade-up d3" id="searchFormWrap">
        <form method="GET" action="{{ route('admin.rooms.index') }}" id="searchForm">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="search-input"
                       value="{{ request('search') }}"
                       placeholder="Cari nomor atau nama kamar..."
                       autocomplete="off">
                @if(request('search'))
                <a href="{{ route('admin.rooms.index', array_filter(['status' => request('status')])) }}" class="search-clear" title="Hapus pencarian">&times;</a>
                @endif
            </div>
        </form>
    </div>

    <div class="filter-bar fade-up d3">
        <span style="font-size:.72rem;font-weight:600;color:var(--neutral-400)">Filter:</span>
        <a href="{{ route('admin.rooms.index', array_filter(['search' => request('search')])) }}" class="filter-chip {{ !request('status') ? 'active' : '' }}">
            Semua <span class="filter-count">{{ $rooms->total() }}</span>
        </a>
        <a href="{{ route('admin.rooms.index', array_filter(['status'=>'available','search'=>request('search')])) }}" class="filter-chip {{ request('status')==='available' ? 'active' : '' }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--green-500)"></i>
            Tersedia <span class="filter-count" style="background:var(--green-600)">{{ $available }}</span>
        </a>
        <a href="{{ route('admin.rooms.index', array_filter(['status'=>'occupied','search'=>request('search')])) }}" class="filter-chip {{ request('status')==='occupied' ? 'active' : '' }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--red)"></i>
            Terisi <span class="filter-count" style="background:var(--red)">{{ $occupied }}</span>
        </a>
        <a href="{{ route('admin.rooms.index', array_filter(['status'=>'reserved','search'=>request('search')])) }}" class="filter-chip {{ request('status')==='reserved' ? 'active' : '' }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--blue)"></i>
            Dipesan <span class="filter-count" style="background:var(--blue)">{{ $reserved }}</span>
        </a>
        <a href="{{ route('admin.rooms.index', array_filter(['status'=>'maintenance','search'=>request('search')])) }}" class="filter-chip {{ request('status')==='maintenance' ? 'active' : '' }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--amber)"></i>
            Perbaikan <span class="filter-count" style="background:var(--amber)">{{ $maintenance }}</span>
        </a>
    </div>

    <div class="uk-card fade-up d4">
        <div class="uk-card-head">
            <div class="card-head-left">
                <div class="head-icon"><i class="bi bi-house-door-fill"></i></div>
                Daftar Kamar
                <span style="background:var(--green-100);color:var(--green-800);font-size:.63rem;font-weight:600;padding:2px 9px;border-radius:var(--r-pill);border:1px solid var(--green-200)">
                    {{ $rooms->total() }} kamar
                </span>
            </div>
        </div>
        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>Kamar</th>
                        <th class="hide-mobile">Harga / Bulan</th>
                        <th class="hide-mobile">Lantai &amp; Ukuran</th>
                        <th>Status</th>
                        <th class="hide-mobile">Fasilitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($room->primaryImageUrl)
                                    <img src="{{ $room->primaryImageUrl }}" class="room-thumb" alt="{{ $room->room_name }}" loading="lazy">
                                @else
                                    <div class="room-thumb-placeholder"><i class="bi bi-house"></i></div>
                                @endif
                                <div>
                                    <div class="room-num">{{ $room->room_number }}</div>
                                    <div class="room-name-txt">{{ $room->room_name ?? 'Tanpa nama' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mobile">
                            <div class="price-txt">{{ $room->formatted_price }}</div>
                            <div class="price-sub">per bulan</div>
                        </td>
                        <td class="hide-mobile">
                            <div style="display:flex;flex-direction:column;gap:4px">
                                <span class="floor-chip">
                                    <i class="bi bi-layers-fill" style="font-size:.6rem"></i>
                                    Lantai {{ $room->floor ?? 1 }}
                                </span>
                                @if($room->size)
                                <span class="size-txt">{{ $room->size }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $stMap = [
                                    'available'   => ['cls'=>'st-available',  'icon'=>'bi-door-open-fill','txt'=>'Tersedia'],
                                    'occupied'    => ['cls'=>'st-occupied',   'icon'=>'bi-people-fill',  'txt'=>'Terisi'],
                                    'reserved'    => ['cls'=>'st-reserved',   'icon'=>'bi-bookmark-fill','txt'=>'Dipesan'],
                                    'maintenance' => ['cls'=>'st-maintenance','icon'=>'bi-tools',        'txt'=>'Perbaikan'],
                                ];
                                $sc = $stMap[$room->status] ?? ['cls'=>'st-maintenance','icon'=>'bi-circle','txt'=>ucfirst($room->status)];
                            @endphp
                            <span class="st-badge {{ $sc['cls'] }}">
                                <i class="bi {{ $sc['icon'] }}" style="font-size:.6rem"></i>
                                {{ $sc['txt'] }}
                            </span>
                        </td>
                        <td class="hide-mobile">
                            @if($room->facilities && count($room->facilities))
                            <div class="fac-wrap">
                                @foreach(array_slice($room->facilities, 0, 3) as $f)
                                <span class="fac-chip">{{ $f }}</span>
                                @endforeach
                                @if(count($room->facilities) > 3)
                                <span class="fac-chip fac-more">+{{ count($room->facilities) - 3 }}</span>
                                @endif
                            </div>
                            @else
                            <span style="color:var(--neutral-200);font-size:.75rem">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="tbl-action" title="Edit Kamar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.rooms.destroy', $room->id) }}"
                                      onsubmit="return confirm('Hapus kamar {{ $room->room_number }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="tbl-action danger" title="Hapus Kamar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-house-slash"></i>
                                <p>Belum ada data kamar.<br>
                                <a href="{{ route('admin.rooms.create') }}">Tambahkan kamar pertama</a></p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rooms->hasPages())
        <div class="pagination-wrap">{{ $rooms->links() }}</div>
        @endif
    </div>

</div>

<script>
(function() {
    const input = document.querySelector('input[name="search"]');
    if (!input) return;
    let timer;
    input.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => document.getElementById('searchForm').submit(), 400);
    });
})();
</script>
</body>
</html>