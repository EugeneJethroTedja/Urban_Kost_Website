<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tiket — Urban Kost</title>
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
            --accent:      #f4a535;
            --accent-pale: #fffbf0;
            --blue:        #2563eb;
            --blue-pale:   #eff6ff;
            --blue-border: #bfdbfe;
            --red:         #dc2626;
            --red-pale:    #fef2f2;
            --red-border:  #fca5a5;
            --amber:       #f59e0b;
            --amber-pale:  #fffbeb;
            --amber-border:#fde68a;
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

        .page-bg { position: fixed; inset: 0; z-index: -1; background: var(--green-50); }
        .page-bg::before {
            content: ''; position: absolute; inset: 0;
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

        .page-wrap { max-width: 1280px; margin: 0 auto; padding: 32px 24px 64px; }

        .hero-banner {
            position: relative; overflow: hidden; border-radius: 20px;
            background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
            padding: 36px 32px; margin-bottom: 28px;
            box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
        }
        .hero-banner::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
                radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
            pointer-events: none;
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
            padding: 5px 14px; border-radius: 100px;
            font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.82); margin-bottom: 14px;
        }
        .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
        .hero-title {
            font-family: 'Fraunces', serif; font-weight: 700;
            font-size: clamp(1.5rem, 4vw, 2.2rem); color: #fff;
            line-height: 1.15; letter-spacing: -.025em; margin-bottom: 8px;
        }
        .hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
        .hero-sub { font-size: .9rem; color: rgba(255,255,255,.62); max-width: 440px; line-height: 1.6; }

        .stats-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 16px; margin-bottom: 24px;
        }
        .stat-card {
            position: relative; overflow: hidden; border-radius: 16px;
            padding: 20px 18px; color: #fff;
            box-shadow: var(--shadow-md);
            transition: transform .25s, box-shadow .3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .stat-card-open        { background: linear-gradient(135deg, var(--green-800), var(--green-600)); }
        .stat-card-pending     { background: linear-gradient(135deg, #1e40af, var(--blue)); }
        .stat-card-urgent      { background: linear-gradient(135deg, #92400e, var(--amber)); }
        .stat-card-resolved    { background: linear-gradient(135deg, var(--green-900), var(--green-700)); }
        .stat-bg-icon { position: absolute; right: -6px; bottom: -6px; font-size: 4rem; opacity: .12; line-height: 1; }
        .stat-value {
            font-family: 'Fraunces', serif; font-size: 2rem; font-weight: 700;
            line-height: 1; margin-bottom: 4px; letter-spacing: -.025em; color: #fff;
        }
        .stat-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.75); }
        .stat-trend {
            display: inline-flex; align-items: center; gap: 4px; margin-top: 8px;
            padding: 3px 9px; border-radius: 100px; font-size: .62rem; font-weight: 600;
            background: rgba(255,255,255,.18); color: rgba(255,255,255,.9);
        }

        .section-hd {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px; flex-wrap: wrap; gap: 8px;
        }
        .section-hd h2 {
            font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 700;
            color: var(--neutral-900); letter-spacing: -.02em;
            display: flex; align-items: center; gap: 8px;
        }
        .section-hd h2 i { color: var(--green-500); font-size: .95rem; }

        .o-card {
            background: var(--neutral-0); border-radius: 16px;
            border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden;
        }
        .o-card-header {
            padding: 15px 20px; border-bottom: 1px solid var(--neutral-100);
            display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
        }
        .o-card-header-title {
            font-size: .72rem; font-weight: 600; color: var(--neutral-600);
            display: flex; align-items: center; gap: 7px;
            text-transform: uppercase; letter-spacing: .07em;
        }
        .o-card-header-title i { color: var(--green-500); }

        .filter-wrap {
            padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
            background: var(--neutral-50);
        }
        .filter-label { font-size: .68rem; font-weight: 700; color: var(--neutral-400); white-space: nowrap; text-transform: uppercase; letter-spacing: .07em; }
        .filter-chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 12px; border-radius: 100px; font-size: .73rem; font-weight: 600;
            border: 1.5px solid var(--neutral-200); background: #fff;
            color: var(--neutral-500); cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .filter-chip:hover { border-color: var(--green-300); background: var(--green-50); color: var(--green-700); }
        .filter-chip.active { border-color: var(--green-400); background: var(--green-100); color: var(--green-800); }
        .filter-count {
            font-size: .6rem; font-weight: 700; padding: 1px 5px;
            border-radius: 100px; background: var(--green-500); color: #fff;
        }
        .filter-count.red   { background: var(--red); }
        .filter-count.amber { background: var(--amber); }
        .filter-count.blue  { background: var(--blue); }
        .filter-sep { width: 1px; height: 20px; background: var(--neutral-200); }
        .filter-select {
            padding: 6px 28px 6px 10px; font-size: .75rem; font-family: 'Instrument Sans', sans-serif;
            color: var(--neutral-700); border: 1.5px solid var(--neutral-200);
            border-radius: 9px; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%238fa89d' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 8px center;
            appearance: none; outline: none; cursor: pointer; transition: border-color .15s; font-weight: 600;
        }
        .filter-select:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
        .btn-filter {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px;
            font-size: .75rem; font-weight: 600; font-family: 'Instrument Sans', sans-serif;
            color: #fff; background: var(--green-500); border: none; border-radius: 9px;
            cursor: pointer; transition: all .15s;
        }
        .btn-filter:hover { background: var(--green-600); }
        .btn-reset {
            display: inline-flex; align-items: center; gap: 5px; padding: 6px 11px;
            font-size: .75rem; font-weight: 600; font-family: 'Instrument Sans', sans-serif;
            color: var(--neutral-500); background: #fff;
            border: 1.5px solid var(--neutral-200); border-radius: 9px; cursor: pointer;
            text-decoration: none; transition: all .15s;
        }
        .btn-reset:hover { border-color: var(--neutral-400); color: var(--neutral-700); }

        .tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .dtbl { width: 100%; border-collapse: collapse; min-width: 640px; }
        .dtbl thead th {
            padding: 10px 14px; font-size: .63rem; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase; color: var(--neutral-400);
            background: var(--neutral-50); border-bottom: 1px solid var(--neutral-100); text-align: left;
        }
        .dtbl tbody tr { border-bottom: 1px solid var(--neutral-100); transition: background .12s; }
        .dtbl tbody tr:last-child { border-bottom: none; }
        .dtbl tbody tr:hover { background: var(--green-50); }
        .dtbl tbody tr.urgent-row { background: linear-gradient(90deg, rgba(220,38,38,.04), transparent); }
        .dtbl tbody tr.urgent-row:hover { background: linear-gradient(90deg, rgba(220,38,38,.07), var(--green-50)); }
        .dtbl tbody td { padding: 12px 14px; font-size: .8rem; vertical-align: middle; }

        .ticket-num { font-weight: 700; font-size: .76rem; color: var(--green-700); display: flex; align-items: center; gap: 5px; font-variant-numeric: tabular-nums; }
        .urgent-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--red); flex-shrink: 0; animation: pulse 1.5s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.25)} }

        .tenant-name { font-weight: 700; color: var(--neutral-900); font-size: .82rem; }
        .tenant-room { font-size: .69rem; color: var(--neutral-400); margin-top: 1px; }
        .subject-txt { font-size: .8rem; color: var(--neutral-700); max-width: 200px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-weight: 500; }
        .cat-chip {
            display: inline-flex; align-items: center; gap: 4px; background: var(--green-50);
            border-radius: 7px; padding: 3px 9px; font-size: .69rem; font-weight: 700;
            color: var(--green-700); border: 1px solid var(--green-100);
        }
        .date-txt { font-size: .74rem; color: var(--neutral-500); }
        .mttr-val { font-size: .68rem; font-weight: 700; color: var(--green-600); margin-top: 2px; display: flex; align-items: center; gap: 3px; }

        .pr-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 9px; border-radius: 100px; font-size: .67rem; font-weight: 700; white-space: nowrap; }
        .pr-urgent { background: var(--red-pale);   color: var(--red);   border: 1px solid var(--red-border); }
        .pr-high   { background: var(--amber-pale); color: #92400e;      border: 1px solid var(--amber-border); }
        .pr-normal { background: var(--blue-pale);  color: var(--blue);  border: 1px solid var(--blue-border); }
        .pr-low    { background: var(--neutral-100); color: var(--neutral-500); border: 1px solid var(--neutral-200); }

        .st-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 9px; border-radius: 100px; font-size: .67rem; font-weight: 700; white-space: nowrap; }
        .st-open        { background: var(--green-50);   color: var(--green-700); border: 1px solid var(--green-200); }
        .st-in_progress { background: var(--blue-pale);  color: var(--blue);      border: 1px solid var(--blue-border); }
        .st-pending     { background: var(--amber-pale); color: #92400e;          border: 1px solid var(--amber-border); }
        .st-resolved    { background: var(--green-100);  color: var(--green-800); border: 1px solid var(--green-200); }
        .st-closed      { background: var(--neutral-100); color: var(--neutral-500); border: 1px solid var(--neutral-200); }

        .tbl-action {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 8px;
            border: 1px solid var(--neutral-200); color: var(--neutral-500);
            text-decoration: none; font-size: .78rem; transition: all .15s; background: #fff;
        }
        .tbl-action:hover { border-color: var(--green-300); color: var(--green-600); background: var(--green-50); }

        .empty-state { text-align: center; padding: 56px 20px; color: var(--neutral-400); }
        .empty-state i { font-size: 2.8rem; display: block; color: var(--green-200); margin-bottom: 14px; }
        .empty-state p { font-size: .84rem; line-height: 1.7; }

        .pagination-wrap { display: flex; justify-content: flex-end; padding: 12px 18px; border-top: 1px solid var(--neutral-100); }

        .urgent-alert {
            display: inline-flex; align-items: center; gap: 5px; margin-left: auto;
            font-size: .68rem; font-weight: 600; color: var(--red);
            background: var(--red-pale); border: 1px solid var(--red-border);
            padding: 3px 10px; border-radius: 100px;
        }

        .flash-alert {
            display: flex; align-items: center; gap: 10px; padding: 12px 16px;
            border-radius: 10px; margin-bottom: 14px; font-size: .82rem; font-weight: 500; border: 1px solid;
        }
        .flash-success { background: var(--green-50);  border-color: var(--green-200); color: var(--green-800); }
        .flash-error   { background: var(--red-pale);  border-color: var(--red-border); color: var(--red); }
        .flash-warning { background: var(--amber-pale); border-color: var(--amber-border); color: #92400e; }

        .reveal { opacity: 0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease; }
        .reveal.in { opacity: 1; transform: none; }
        .d1 { transition-delay: .05s; } .d2 { transition-delay: .1s; } .d3 { transition-delay: .15s; } .d4 { transition-delay: .2s; }

        @media (min-width: 900px) { .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; } }
        @media (max-width: 768px) {
            .page-wrap { padding: 16px 16px 48px; }
            .hero-banner { padding: 24px 20px; }
            .hero-arc { display: none; }
            .hide-mobile { display: none; }
            .filter-wrap { gap: 7px; }
        }
        @media (max-width: 480px) {
            .page-wrap { padding: 14px 12px 48px; }
            .hero-title { font-size: 1.3rem; }
            .stats-grid { gap: 10px; }
            .stat-card { padding: 15px 13px; }
            .stat-value { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">

    <div class="hero-banner reveal d1">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Manajamen Keluhan & Laporan Penghuni
            </div>
            <h1 class="hero-title">Manajemen <em>Tiket</em></h1>
            <p class="hero-sub">Pantau dan tangani seluruh keluhan penghuni secara terpusat dan terstruktur.</p>
        </div>
    </div>

    @foreach(['success','error','warning'] as $type)
    @if(session($type))
    <div class="flash-alert flash-{{ $type }} reveal d1">
        <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'exclamation-circle-fill' : 'exclamation-triangle-fill') }}"></i>
        {!! session($type) !!}
    </div>
    @endif
    @endforeach

    <div class="stats-grid reveal d2">
        <div class="stat-card stat-card-open">
            <div class="stat-value">{{ $stats['open'] }}</div>
            <div class="stat-label">Tiket Terbuka</div>
            <div class="stat-trend"><i class="bi bi-folder2-open"></i> Menunggu respons</div>
        </div>
        <div class="stat-card stat-card-urgent">
            <div class="stat-value">{{ $stats['urgent'] }}</div>
            <div class="stat-label">Prioritas Tinggi</div>
            <div class="stat-trend"><i class="bi bi-exclamation-circle"></i> Perlu ditangani</div>
        </div>
        <div class="stat-card stat-card-pending">
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Sedang Ditangani</div>
            <div class="stat-trend"><i class="bi bi-hourglass-split"></i> In progress</div>
        </div>
        <div class="stat-card stat-card-resolved">
            <div class="stat-value">{{ $stats['resolved'] }}</div>
            <div class="stat-label">Selesai Hari Ini</div>
            <div class="stat-trend"><i class="bi bi-check-circle"></i> Terselesaikan</div>
        </div>
    </div>

    <div class="section-hd reveal d3">
        <h2><i class="bi bi-list-ul"></i> Daftar Tiket</h2>
    </div>

    <div class="o-card reveal d3">
        <div class="o-card-header">
            <span class="o-card-header-title">
                <i class="bi bi-funnel-fill"></i> Filter Tiket
            </span>
            <span style="font-size:.68rem;color:var(--neutral-400);font-weight:500">
                {{ $tickets->total() }} tiket ditemukan
            </span>
        </div>

        <form method="GET" action="{{ route('admin.tickets.index') }}">
            <div class="filter-wrap">
                <span class="filter-label">Status:</span>
                @php
                    $statusChips = [
                        ''            => ['label' => 'Semua',       'cnt' => null,                'col' => ''],
                        'open'        => ['label' => 'Terbuka',     'cnt' => $stats['open'],      'col' => ''],
                        'in_progress' => ['label' => 'Ditangani',   'cnt' => $stats['pending'],   'col' => 'blue'],
                        'resolved'    => ['label' => 'Selesai',     'cnt' => $stats['resolved'],  'col' => ''],
                        'closed'      => ['label' => 'Ditutup',     'cnt' => null,                'col' => ''],
                    ];
                @endphp
                @foreach($statusChips as $val => $cfg)
                <a href="{{ route('admin.tickets.index', array_merge(request()->except('status','page'), $val ? ['status' => $val] : [])) }}"
                   class="filter-chip {{ request('status', '') === $val ? 'active' : '' }}">
                    {{ $cfg['label'] }}
                    @if($cfg['cnt'] !== null)
                    <span class="filter-count {{ $cfg['col'] }}">{{ $cfg['cnt'] }}</span>
                    @endif
                </a>
                @endforeach

                <div class="filter-sep"></div>

                <select name="priority" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Prioritas</option>
                    @foreach(['urgent' => 'Urgent', 'high' => 'Tinggi', 'normal' => 'Normal', 'low' => 'Rendah'] as $v => $l)
                    <option value="{{ $v }}" {{ request('priority') === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>

                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach(['maintenance' => 'Maintenance', 'billing' => 'Billing', 'noise' => 'Kebisingan', 'security' => 'Keamanan', 'other' => 'Lainnya'] as $v => $l)
                    <option value="{{ $v }}" {{ request('category') === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>

                @if(request()->hasAny(['status', 'priority', 'category']))
                <a href="{{ route('admin.tickets.index') }}" class="btn-reset">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
                @endif
            </div>
        </form>

        <div class="o-card-header" style="border-top:1px solid var(--neutral-100);border-bottom:1px solid var(--neutral-100)">
            <span class="o-card-header-title">
                <i class="bi bi-headset"></i> Semua Tiket
            </span>
            @if($stats['urgent'] > 0)
            <span class="urgent-alert">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--red);animation:pulse 1.5s infinite"></span>
                {{ $stats['urgent'] }} tiket perlu perhatian segera
            </span>
            @endif
        </div>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Penghuni</th>
                        <th>Subjek</th>
                        <th class="hide-mobile">Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th class="hide-mobile">Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $t)
                    @php $isUrgentActive = $t->priority === 'urgent' && in_array($t->status, ['open', 'in_progress']); @endphp
                    <tr class="{{ $isUrgentActive ? 'urgent-row' : '' }}">
                        <td>
                            <div class="ticket-num">
                                @if($isUrgentActive)<span class="urgent-dot"></span>@endif
                                {{ $t->ticket_number }}
                            </div>
                        </td>
                        <td>
                            <div class="tenant-name">{{ $t->user?->name }}</div>
                            @if($t->user?->phone)
                            <div class="tenant-room">{{ $t->user->phone }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="subject-txt" title="{{ $t->subject }}">{{ $t->subject }}</div>
                        </td>
                        <td class="hide-mobile">
                            <span class="cat-chip">
                                <i class="bi {{ match($t->category) {
                                    'maintenance' => 'bi-tools',
                                    'billing'     => 'bi-receipt',
                                    'noise'       => 'bi-volume-up-fill',
                                    'security'    => 'bi-shield-fill',
                                    default       => 'bi-tag-fill'
                                } }}" style="font-size:.6rem"></i>
                                {{ ucfirst($t->category) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $prMap = [
                                    'urgent' => ['cls' => 'pr-urgent', 'icon' => 'bi-exclamation-circle-fill', 'txt' => 'Urgent'],
                                    'high'   => ['cls' => 'pr-high',   'icon' => 'bi-arrow-up-circle-fill',   'txt' => 'Tinggi'],
                                    'normal' => ['cls' => 'pr-normal', 'icon' => 'bi-dash-circle-fill',        'txt' => 'Normal'],
                                    'low'    => ['cls' => 'pr-low',    'icon' => 'bi-arrow-down-circle-fill',  'txt' => 'Rendah'],
                                ];
                                $pr = $prMap[$t->priority] ?? $prMap['normal'];
                            @endphp
                            <span class="pr-badge {{ $pr['cls'] }}">
                                <i class="bi {{ $pr['icon'] }}" style="font-size:.58rem"></i>
                                {{ $pr['txt'] }}
                            </span>
                        </td>
                        <td>
                            @php
                                $stMap = [
                                    'open'        => ['cls' => 'st-open',        'icon' => 'bi-folder2-open',     'txt' => 'Terbuka'],
                                    'in_progress' => ['cls' => 'st-in_progress', 'icon' => 'bi-hourglass-split',  'txt' => 'Ditangani'],
                                    'pending'     => ['cls' => 'st-pending',     'icon' => 'bi-clock',            'txt' => 'Menunggu'],
                                    'resolved'    => ['cls' => 'st-resolved',    'icon' => 'bi-check-circle-fill','txt' => 'Selesai'],
                                    'closed'      => ['cls' => 'st-closed',      'icon' => 'bi-x-circle-fill',    'txt' => 'Ditutup'],
                                ];
                                $st = $stMap[$t->status] ?? $stMap['pending'];
                            @endphp
                            <span class="st-badge {{ $st['cls'] }}">
                                <i class="bi {{ $st['icon'] }}" style="font-size:.55rem"></i>
                                {{ $st['txt'] }}
                            </span>
                        </td>
                        <td class="hide-mobile">
                            <div class="date-txt">{{ $t->created_at->format('d M Y') }}</div>
                            @if($t->mttr_hours)
                            <div class="mttr-val">
                                <i class="bi bi-clock-fill" style="font-size:.6rem"></i>
                                {{ $t->mttr_hours }} jam
                            </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $t->id) }}" class="tbl-action" title="Lihat Detail">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Tidak ada tiket ditemukan.<br>Semua keluhan sudah tertangani.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div class="pagination-wrap">{{ $tickets->links() }}</div>
        @endif
    </div>

</div>

<script>
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold: 0.08 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
</script>

@stack('scripts')
</body>
</html>