<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin — Urban Kost</title>
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
    .page-shell {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 40px 80px;
    }
    .flash {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: .84rem; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
    }
    .flash.success { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-300); }
    .flash.error   { background: var(--red-pale); color: var(--red); border: 1px solid rgba(220,38,38,.2); }

    /* ── Hero Banner ── */
    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 32px 32px 28px;
        margin-bottom: 28px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
        display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; flex-wrap: wrap;
    }
    .hero-banner::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .hero-arc {
        position: absolute; right: -100px; top: -100px;
        width: 340px; height: 340px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none;
    }
    .hero-content { position: relative; z-index: 1; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 4px 13px; border-radius: 100px;
        font-size: .66rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82);
        margin-bottom: 12px;
    }
    .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.4rem, 3.5vw, 2rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 6px;
    }
    .hero-sub {
        font-size: .85rem;
        color: rgba(255,255,255,.62);
        font-weight: 400;
        max-width: 420px;
        line-height: 1.6;
    }
    .hero-action {
        position: relative; z-index: 1; flex-shrink: 0;
    }
    .btn-add {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 11px 22px; border-radius: 10px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.3);
        color: #fff; font-family: 'Instrument Sans', sans-serif;
        font-size: .84rem; font-weight: 600;
        text-decoration: none; white-space: nowrap;
        backdrop-filter: blur(4px);
        transition: background .2s, border-color .2s, transform .18s;
    }
    .btn-add:hover {
        background: rgba(255,255,255,.25);
        border-color: rgba(255,255,255,.5);
        transform: translateY(-2px);
    }
    .btn-add:active { transform: translateY(0); }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    .stat-chip {
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        border-radius: 14px; padding: 16px 18px;
        display: flex; align-items: center; gap: 12px;
        box-shadow: var(--shadow-sm);
        transition: box-shadow .2s, transform .2s;
    }
    .stat-chip:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .stat-chip-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .95rem;
    }
    .stat-chip-icon.green { background: var(--green-100); color: var(--green-700); }
    .stat-chip-icon.red   { background: var(--red-pale); color: var(--red); }
    .stat-chip-val {
        font-family: 'Fraunces', serif; font-weight: 700;
        font-size: clamp(1.3rem, 3vw, 1.6rem);
        color: var(--neutral-900); line-height: 1;
    }
    .stat-chip-label {
        font-size: .65rem; font-weight: 600; color: var(--neutral-400);
        margin-top: 3px; letter-spacing: .06em; text-transform: uppercase;
    }
    .search-bar {
        display: flex; gap: 8px; flex-wrap: wrap;
        margin-bottom: 14px; align-items: center;
    }
    .search-wrap {
        flex: 1; min-width: 180px;
        position: relative; display: flex; align-items: center;
    }
    .search-wrap i {
        position: absolute; left: 12px;
        font-size: .88rem; color: var(--neutral-400);
        pointer-events: none;
    }
    .search-input {
        width: 100%;
        padding: 10px 12px 10px 34px;
        border-radius: 10px;
        border: 1px solid var(--neutral-200);
        font-family: 'Instrument Sans', sans-serif;
        font-size: .875rem; font-weight: 400;
        color: var(--neutral-900); background: var(--neutral-0);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .search-input:hover { border-color: var(--green-300); }
    .search-input:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
    .search-input::placeholder { color: var(--neutral-400); }
    .filter-select {
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--neutral-200);
        font-family: 'Instrument Sans', sans-serif;
        font-size: .875rem; font-weight: 400;
        color: var(--neutral-700); background: var(--neutral-0);
        outline: none; cursor: pointer;
        transition: border-color .15s;
        min-width: 130px;
    }
    .filter-select:hover { border-color: var(--green-300); }
    .filter-select:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
    .btn-search {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 18px; border-radius: 10px;
        background: var(--green-600); color: #fff;
        font-family: 'Instrument Sans', sans-serif;
        font-size: .875rem; font-weight: 600;
        border: none; cursor: pointer; white-space: nowrap;
        transition: background .15s, transform .15s;
    }
    .btn-search:hover { background: var(--green-700); transform: translateY(-1px); }
    .btn-reset {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 14px; border-radius: 10px;
        background: transparent; color: var(--neutral-500);
        font-family: 'Instrument Sans', sans-serif;
        font-size: .875rem; font-weight: 600;
        border: 1px solid var(--neutral-200); cursor: pointer;
        text-decoration: none; white-space: nowrap;
        transition: border-color .15s, color .15s, background .15s;
    }
    .btn-reset:hover { border-color: var(--green-300); color: var(--green-700); background: var(--green-50); }
    .search-result-info {
        font-size: .78rem; color: var(--neutral-500); font-weight: 400;
        margin-bottom: 12px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
    }
    .search-result-info .kw {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--green-100); color: var(--green-800);
        padding: 2px 9px; border-radius: 999px;
        font-weight: 600; font-size: .72rem;
    }
    .main-card {
        background: var(--neutral-0);
        border-radius: 18px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .main-card-header {
        padding: 15px 24px;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
        border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; flex-wrap: wrap;
    }
    .main-card-header-left { display: flex; align-items: center; gap: 9px; }
    .main-card-header-left i { font-size: .95rem; color: var(--green-500); }
    .main-card-header-left h2 {
        font-family: 'Fraunces', serif;
        font-size: .95rem; font-weight: 700;
        color: var(--neutral-700); letter-spacing: -.02em;
    }
    .count-pill {
        background: var(--green-600); color: #fff;
        font-size: .63rem; font-weight: 700;
        padding: 2px 9px; border-radius: 999px;
        box-shadow: 0 2px 8px rgba(42,179,107,.28);
    }
    .table-scroll-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .admin-table { width: 100%; border-collapse: collapse; min-width: 640px; }
    .admin-table thead th {
        padding: 11px 18px;
        font-size: .63rem; font-weight: 600;
        letter-spacing: .08em; text-transform: uppercase;
        color: var(--neutral-400);
        background: var(--neutral-50);
        border-bottom: 1px solid var(--neutral-100);
        white-space: nowrap;
    }
    .admin-table thead th:first-child { padding-left: 24px; }
    .admin-table thead th:last-child  { text-align: right; padding-right: 24px; }
    .admin-table tbody tr { border-bottom: 1px solid var(--neutral-100); transition: background .14s; }
    .admin-table tbody tr:last-child { border-bottom: none; }
    .admin-table tbody tr:hover { background: var(--green-50); }
    .admin-table td { padding: 14px 18px; font-size: .82rem; vertical-align: middle; color: var(--neutral-600); }
    .admin-table td:first-child { padding-left: 24px; }
    .admin-table td:last-child  { padding-right: 24px; text-align: right; }
    .admin-avatar-cell { display: flex; align-items: center; gap: 11px; }
    .admin-avatar {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--green-700), var(--green-400));
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .75rem; font-weight: 700;
        box-shadow: 0 2px 8px rgba(42,179,107,.28);
    }
    .admin-name   { font-weight: 600; color: var(--neutral-900); font-size: .84rem; line-height: 1.3; white-space: nowrap; }
    .admin-joined { font-size: .67rem; color: var(--neutral-400); margin-top: 2px; font-weight: 400; white-space: nowrap; }
    .admin-email  { color: var(--neutral-600); font-size: .78rem; font-weight: 400; }
    .admin-phone  { color: var(--neutral-500); font-size: .78rem; white-space: nowrap; }
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 999px;
        font-size: .68rem; font-weight: 600; white-space: nowrap;
    }
    .status-badge.active   { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-200); }
    .status-badge.inactive { background: var(--red-pale); color: var(--red); border: 1px solid rgba(220,38,38,.18); }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .status-dot.active   { background: var(--green-500); box-shadow: 0 0 0 2px rgba(42,179,107,.22); }
    .status-dot.inactive { background: var(--red); }
    .action-group { display: flex; align-items: center; gap: 5px; justify-content: flex-end; flex-wrap: nowrap; }
    .act-btn {
        width: 32px; height: 32px; border-radius: 9px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .78rem; cursor: pointer; border: 1px solid;
        transition: all .15s; text-decoration: none;
        font-family: 'Instrument Sans', sans-serif; flex-shrink: 0;
    }
    .act-btn.edit      { background: var(--blue-pale); color: var(--blue); border-color: rgba(37,99,235,.22); }
    .act-btn.edit:hover { background: rgba(37,99,235,.13); border-color: var(--blue); }
    .act-btn.toggle-on  { background: var(--accent-pale); color: #92400e; border-color: rgba(244,165,53,.3); }
    .act-btn.toggle-on:hover  { background: rgba(244,165,53,.2); border-color: var(--accent); }
    .act-btn.toggle-off { background: var(--green-100); color: var(--green-700); border-color: var(--green-200); }
    .act-btn.toggle-off:hover { background: var(--green-200); border-color: var(--green-400); }
    .act-btn.delete     { background: var(--red-pale); color: var(--red); border-color: rgba(220,38,38,.18); }
    .act-btn.delete:hover { background: rgba(220,38,38,.12); border-color: var(--red); }
    .empty-state { padding: 70px 24px; text-align: center; }
    .empty-state-icon {
        width: 60px; height: 60px; border-radius: 18px;
        margin: 0 auto 20px;
        background: var(--green-50);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: var(--green-300);
        border: 1px solid var(--green-200);
    }
    .empty-state h3 { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--neutral-600); margin-bottom: 8px; }
    .empty-state p  { font-size: .84rem; color: var(--neutral-400); margin-bottom: 22px; }
    .empty-state a {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        background: linear-gradient(135deg, var(--green-800), var(--green-600));
        color: #fff; font-weight: 600; font-size: .84rem; text-decoration: none;
        box-shadow: 0 4px 14px rgba(42,179,107,.28);
        transition: transform .18s, box-shadow .18s;
    }
    .empty-state a:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(42,179,107,.36); }
    .pagination-wrap {
        padding: 16px 24px; border-top: 1px solid var(--green-100);
        display: flex; justify-content: flex-end;
    }
    mark { background: rgba(42,179,107,.18); color: var(--green-800); border-radius: 3px; padding: 0 2px; font-weight: 600; }
    @media (max-width: 1024px) { .page-shell { padding: 28px 28px 60px; } }
    @media (max-width: 768px) {
        .page-shell { padding: 24px 20px 60px; }
        .filter-select { min-width: 110px; }
        .hero-banner { padding: 24px 20px 22px; }
    }
    @media (max-width: 600px) {
        .page-shell { padding: 24px 16px 60px; }
        .stats-row { gap: 8px; }
        .stat-chip { padding: 12px 10px; gap: 8px; }
        .stat-chip-val { font-size: 1.2rem; }
        .stat-chip-label { font-size: .6rem; }
        .stat-chip-icon { width: 32px; height: 32px; font-size: .8rem; }
        .search-bar { flex-direction: column; gap: 8px; }
        .search-wrap { min-width: 100%; }
        .filter-select { width: 100%; }
        .btn-search, .btn-reset { width: 100%; justify-content: center; }
        .hero-banner { flex-direction: column; align-items: flex-start; }
        .btn-add { width: 100%; justify-content: center; }
    }
    @media (max-width: 700px) {
        .table-scroll-wrap { overflow-x: visible; }
        .admin-table { min-width: 0; }
        .admin-table thead { display: none; }
        .admin-table tbody tr {
            display: block;
            background: var(--neutral-0);
            border: 1px solid var(--green-100);
            border-radius: 14px;
            margin: 12px 0;
            padding: 16px;
            box-shadow: var(--shadow-sm);
        }
        .admin-table tbody tr:last-child { border-bottom: 1px solid var(--green-100); }
        .admin-table td {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 7px 0; font-size: .84rem; border: none;
            border-bottom: 1px solid var(--neutral-100);
        }
        .admin-table td:first-child { padding-left: 0; border-bottom: 1px solid var(--green-100); padding-bottom: 12px; margin-bottom: 4px; }
        .admin-table td:last-child  { border-bottom: none; padding-bottom: 0; text-align: left; padding-right: 0; margin-top: 4px; }
        .admin-table td[data-label]:not([data-label=""])::before {
            content: attr(data-label);
            font-size: .65rem; font-weight: 600;
            color: var(--neutral-400);
            text-transform: uppercase; letter-spacing: .08em;
            min-width: 90px; flex-shrink: 0; padding-top: 2px;
        }
        .admin-email { word-break: break-word; overflow-wrap: anywhere; }
        .action-group { justify-content: flex-start; }
    }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@include('partials.owner-sidebar')

<div class="page-shell">

    @if(session('success'))
        <div class="flash success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
    @endif

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>Manajemen Admin
            </div>
            <h1 class="hero-title">Daftar Admin</h1>
            <p class="hero-sub">Kelola akun admin yang memiliki akses ke sistem Urban Kost.</p>
        </div>
        <div class="hero-action">
            <a href="{{ route('owner.admins.create') }}" class="btn-add">
                <i class="bi bi-person-plus-fill"></i>Tambah Admin
            </a>
        </div>
    </div>

    @php
        $totalAdmins    = $admins->total();
        $activeAdmins   = \App\Models\User::whereHas('role', fn($q) => $q->where('name','admin'))->where('is_active', true)->count();
        $inactiveAdmins = \App\Models\User::whereHas('role', fn($q) => $q->where('name','admin'))->where('is_active', false)->count();
    @endphp

    <div class="stats-row">
        <div class="stat-chip">
            <div class="stat-chip-icon green"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-chip-val">{{ $totalAdmins }}</div>
                <div class="stat-chip-label">Total Admin</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon green"><i class="bi bi-person-check-fill"></i></div>
            <div>
                <div class="stat-chip-val">{{ $activeAdmins }}</div>
                <div class="stat-chip-label">Aktif</div>
            </div>
        </div>
        <div class="stat-chip">
            <div class="stat-chip-icon red"><i class="bi bi-person-dash-fill"></i></div>
            <div>
                <div class="stat-chip-val">{{ $inactiveAdmins }}</div>
                <div class="stat-chip-label">Nonaktif</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('owner.admins.index') }}" class="search-bar">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="search"
                class="search-input"
                value="{{ request('search') }}"
                placeholder="Cari nama, email, atau no. HP..."
                autocomplete="off"
            >
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn-search"><i class="bi bi-search"></i>Cari</button>
        @if(request('search') || request('status'))
            <a href="{{ route('owner.admins.index') }}" class="btn-reset">
                <i class="bi bi-x-lg"></i>Reset
            </a>
        @endif
    </form>

    @if(request('search') || request('status'))
        <div class="search-result-info">
            <i class="bi bi-funnel-fill" style="font-size:.72rem;color:var(--green-600)"></i>
            Menampilkan {{ $admins->total() }} hasil
            @if(request('search'))
                untuk kata kunci
                <span class="kw"><i class="bi bi-search" style="font-size:.65rem"></i>{{ request('search') }}</span>
            @endif
            @if(request('status'))
                &bull; status
                <span class="kw">{{ request('status') === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
            @endif
        </div>
    @endif

    <div class="main-card">
        <div class="main-card-header">
            <div class="main-card-header-left">
                <i class="bi bi-person-badge-fill"></i>
                <h2>Daftar Akun Admin</h2>
                <span class="count-pill">{{ $totalAdmins }}</span>
            </div>
        </div>

        <div class="table-scroll-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    @php $initial = strtoupper(substr($admin->name, 0, 1)) @endphp
                    <tr>
                        <td data-label="">
                            <div class="admin-avatar-cell">
                                <div class="admin-avatar">{{ $initial }}</div>
                                <div>
                                    <div class="admin-name">{{ $admin->name }}</div>
                                    <div class="admin-joined">Bergabung {{ $admin->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Email"><span class="admin-email">{{ $admin->email }}</span></td>
                        <td data-label="No. HP"><span class="admin-phone">{{ $admin->phone ?? '—' }}</span></td>
                        <td data-label="Status">
                            @if($admin->is_active)
                                <span class="status-badge active"><span class="status-dot active"></span>Aktif</span>
                            @else
                                <span class="status-badge inactive"><span class="status-dot inactive"></span>Nonaktif</span>
                            @endif
                        </td>
                        <td data-label="Tanggal">
                            <span style="font-size:.76rem;color:var(--neutral-400);font-weight:400;white-space:nowrap">
                                {{ $admin->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td data-label="Aksi">
                            <div class="action-group">
                                <a href="{{ route('owner.admins.edit', $admin->id) }}" class="act-btn edit" title="Edit Admin">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form method="POST" action="{{ route('owner.admins.toggle', $admin->id) }}" style="margin:0">
                                    @csrf
                                    <button type="submit"
                                        class="act-btn {{ $admin->is_active ? 'toggle-on' : 'toggle-off' }}"
                                        title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-{{ $admin->is_active ? 'pause-circle' : 'play-circle' }}-fill"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('owner.admins.destroy', $admin->id) }}"
                                      style="margin:0"
                                      onsubmit="return confirm('Hapus admin {{ addslashes($admin->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-btn delete" title="Hapus Admin">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:0;border:none">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    @if(request('search') || request('status'))
                                        <i class="bi bi-search"></i>
                                    @else
                                        <i class="bi bi-people"></i>
                                    @endif
                                </div>
                                @if(request('search') || request('status'))
                                    <h3>Tidak Ada Hasil</h3>
                                    <p>Tidak ditemukan admin yang sesuai dengan filter pencarian.</p>
                                    <a href="{{ route('owner.admins.index') }}"><i class="bi bi-arrow-counterclockwise"></i>Reset Pencarian</a>
                                @else
                                    <h3>Belum Ada Admin</h3>
                                    <p>Tambahkan admin agar mereka dapat mengelola sistem Urban Kost.</p>
                                    <a href="{{ route('owner.admins.create') }}"><i class="bi bi-person-plus-fill"></i>Tambah Admin Pertama</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
        <div class="pagination-wrap">
            {{ $admins->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

</body>
</html>