<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya — Urban Kost</title>
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

    .page-wrap { max-width: 1000px; margin: 0 auto; padding: 32px 24px 64px; }

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
    .hero-arc {
        position: absolute; right: -100px; top: -100px;
        width: 380px; height: 380px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none; z-index: 1;
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
    .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 8px;
    }
    .hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
    .hero-sub { font-size: .9rem; color: rgba(255,255,255,.62); max-width: 420px; line-height: 1.6; }
    .hero-actions { display: flex; align-items: center; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
    .btn-hero-white {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; color: var(--green-700);
        font-family: 'Instrument Sans', sans-serif; font-weight: 600;
        font-size: .85rem; padding: 11px 22px; border-radius: 50px;
        text-decoration: none; box-shadow: 0 4px 20px rgba(0,0,0,.18);
        transition: transform .2s, box-shadow .2s; white-space: nowrap;
    }
    .btn-hero-white:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(0,0,0,.22); color: var(--green-700); }

    .stats-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
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
    .stat-icon-wrap {
        width: 42px; height: 42px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; margin-bottom: 14px;
    }
    .stat-value {
        font-family: 'Fraunces', serif;
        font-size: 2rem; font-weight: 700;
        color: var(--neutral-900); line-height: 1;
        margin-bottom: 4px; letter-spacing: -.025em;
    }
    .stat-label {
        font-size: .68rem; font-weight: 600;
        color: var(--neutral-400);
        text-transform: uppercase; letter-spacing: .07em;
    }

    .toolbar {
        display: flex; gap: 10px; margin-bottom: 16px;
        align-items: center; flex-wrap: wrap;
    }
    .search-wrap { position: relative; flex: 1; min-width: 200px; }
    .search-wrap .search-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--neutral-400); font-size: .9rem; pointer-events: none;
    }
    .search-input {
        width: 100%; padding: 10px 16px 10px 38px;
        background: var(--neutral-0);
        border: 1px solid var(--neutral-200);
        border-radius: 50px;
        font-family: 'Instrument Sans', sans-serif;
        font-size: .85rem; color: var(--neutral-900);
        outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .search-input::placeholder { color: var(--neutral-400); }
    .search-input:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
    .search-clear {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: var(--green-100); border: none; border-radius: 50%;
        width: 22px; height: 22px; display: none;
        align-items: center; justify-content: center;
        cursor: pointer; color: var(--neutral-600); font-size: .75rem;
    }
    .search-clear.visible { display: flex; }

    .filter-pills { display: flex; gap: 6px; flex-wrap: wrap; }
    .filter-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 8px 14px; border-radius: 50px; font-size: .78rem;
        font-weight: 600; border: 1px solid var(--neutral-200);
        background: var(--neutral-0); color: var(--neutral-500);
        cursor: pointer; text-decoration: none;
        transition: all .15s; white-space: nowrap;
    }
    .filter-btn:hover, .filter-btn.active {
        background: var(--green-600); border-color: var(--green-600); color: #fff;
    }
    .filter-count {
        background: var(--green-100); color: var(--green-700);
        border-radius: 50px; padding: 1px 6px;
        font-size: .65rem; font-weight: 700;
    }
    .filter-btn.active .filter-count { background: rgba(255,255,255,.25); color: #fff; }

    .search-info {
        font-size: .78rem; color: var(--neutral-500);
        margin-bottom: 10px; display: none;
        align-items: center; gap: 6px; font-weight: 500;
    }
    .search-info.visible { display: flex; }
    .search-info strong { color: var(--green-700); }
    .search-info a { color: var(--neutral-400); font-size: .75rem; text-decoration: underline; }

    .o-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 20px;
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
    .o-card-count {
        font-size: .72rem; font-weight: 700; color: var(--neutral-400);
        background: var(--green-50); border: 1px solid var(--green-100);
        padding: 3px 10px; border-radius: 50px;
    }

    .ticket-item-link { text-decoration: none; display: block; }
    .ticket-item {
        padding: 16px 20px; border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; gap: 14px;
        transition: background .14s;
    }
    .ticket-item:last-child { border-bottom: none; }
    .ticket-item-link:hover .ticket-item { background: var(--green-50); }
    .ticket-item-link.search-hidden { display: none; }

    .ticket-ava {
        width: 44px; height: 44px; border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0; position: relative;
    }
    .ava-urgent { background: #fee2e2; }
    .ava-high   { background: #fff7ed; }
    .ava-normal { background: var(--blue-pale); }
    .ava-done   { background: var(--green-100); }
    .ava-urgent::after {
        content: ''; position: absolute; top: -3px; right: -3px;
        width: 10px; height: 10px; background: var(--red);
        border-radius: 50%; border: 2px solid var(--neutral-0);
        animation: pulse-dot 1.5s infinite;
    }
    @keyframes pulse-dot { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.25);opacity:.7} }

    .ticket-body { flex: 1; min-width: 0; }
    .ticket-num { font-size: .68rem; font-weight: 700; color: var(--green-600); letter-spacing: .04em; margin-bottom: 3px; text-transform: uppercase; }
    .ticket-subject {
        font-family: 'Fraunces', serif; font-weight: 700;
        font-size: .9rem; color: var(--neutral-900);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px;
    }
    .ticket-meta { font-size: .73rem; color: var(--neutral-400); font-weight: 500; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .meta-dot { width: 3px; height: 3px; background: var(--neutral-300); border-radius: 50%; display: inline-block; }
    .rate-nudge {
        display: inline-flex; align-items: center; gap: 4px; margin-top: 4px;
        background: var(--amber-pale); border: 1px solid #fde68a; color: #92400e;
        font-size: .67rem; font-weight: 700; padding: 2px 8px; border-radius: 50px;
    }
    .ticket-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: .67rem; font-weight: 700; }
    .badge-open     { background: #dbeafe; color: #1d4ed8; }
    .badge-progress { background: var(--amber-pale); color: #92400e; }
    .badge-resolved { background: var(--green-100); color: var(--green-700); }
    .badge-closed   { background: var(--neutral-100); color: var(--neutral-500); }
    .badge-urgent   { background: #fee2e2; color: #b91c1c; }
    .badge-high     { background: #ffedd5; color: #c2410c; }
    .badge-normal   { background: var(--blue-pale); color: #1d4ed8; }
    .badge-low      { background: var(--green-50); color: var(--green-700); }

    mark { background: #fef08a; border-radius: 3px; padding: 0 2px; color: var(--neutral-900); }

    .empty-state { padding: 64px 20px; text-align: center; }
    .empty-icon { font-size: 2.5rem; color: var(--green-200); margin-bottom: 14px; display: block; }
    .empty-state h3 { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 8px; }
    .empty-state p { color: var(--neutral-400); font-size: .85rem; line-height: 1.65; margin-bottom: 24px; max-width: 280px; margin-left: auto; margin-right: auto; }

    .no-result { display: none; padding: 40px 20px; text-align: center; color: var(--neutral-400); }
    .no-result i { font-size: 2rem; display: block; margin-bottom: 10px; opacity: .35; }
    .no-result p { font-size: .85rem; }
    .no-result.visible { display: block; }

    .btn-primary-sm {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 20px; border-radius: 9px; font-size: .8rem; font-weight: 600;
        background: var(--green-500); color: #fff; text-decoration: none;
        box-shadow: 0 2px 10px rgba(42,179,107,.28); transition: all .2s; border: none;
    }
    .btn-primary-sm:hover { background: var(--green-600); color: #fff; transform: translateY(-1px); }

    .sla-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-top: 20px;
    }
    .sla-head {
        padding: 14px 20px;
        background: linear-gradient(135deg, var(--green-800), var(--green-600));
        display: flex; align-items: center; gap: 10px;
    }
    .sla-head h3 { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: #fff; }
    .sla-head i { color: rgba(255,255,255,.8); }
    .sla-grid { display: grid; grid-template-columns: repeat(4, 1fr); }
    .sla-item { padding: 18px 14px; border-right: 1px solid var(--neutral-100); text-align: center; }
    .sla-item:last-child { border-right: none; }
    .sla-p { font-family: 'Fraunces', serif; font-weight: 700; font-size: .82rem; color: var(--neutral-700); margin-bottom: 4px; }
    .sla-t { font-size: .72rem; font-weight: 600; color: var(--green-600); }

    .pagination-wrap { padding: 14px 20px; border-top: 1px solid var(--neutral-100); display: flex; justify-content: flex-end; }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .sla-grid { grid-template-columns: repeat(2, 1fr); }
        .sla-item:nth-child(2) { border-right: none; }
        .sla-item:nth-child(3) { border-top: 1px solid var(--neutral-100); }
        .sla-item:nth-child(4) { border-top: 1px solid var(--neutral-100); border-right: none; }
    }
    @media (max-width: 540px) {
        .page-wrap { padding: 16px 14px 48px; }
        .hero-banner { padding: 26px 20px; }
        .hero-arc { display: none; }
        .toolbar { flex-direction: column; align-items: stretch; }
    }
    @media (max-width: 400px) {
        .stats-grid { grid-template-columns: 1fr; }
        .sla-grid { grid-template-columns: 1fr 1fr; }
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-wrap">

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Pusat Bantuan
            </div>
            <h1 class="hero-title">Tiket <em>Keluhan</em> Saya</h1>
            <p class="hero-sub">Sampaikan keluhan atau permintaan layanan. Tim Urban Kost siap merespons sesuai prioritas.</p>
            <div class="hero-actions">
                <a href="{{ route('tenant.tickets.create') }}" class="btn-hero-white">
                    <i class="bi bi-plus-circle-fill"></i> Buat Tiket Baru
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-value">{{ $stats['running'] }}</div>
            <div class="stat-label">Berjalan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:var(--green-100);color:var(--green-600)"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-value" style="color:var(--green-600)">{{ $stats['finished'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrap" style="background:var(--blue-pale);color:var(--blue)"><i class="bi bi-ticket-fill"></i></div>
            <div class="stat-value" style="color:var(--blue)">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>

    <div class="toolbar">
        <div class="search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="ticketSearch" class="search-input"
                placeholder="Cari tiket berdasarkan subjek atau nomor..."
                value="{{ request('q') }}" autocomplete="off">
            <button class="search-clear" id="searchClear"><i class="bi bi-x"></i></button>
        </div>
        <div class="filter-pills">
            <a href="{{ route('tenant.tickets.index', array_filter(['q' => request('q')])) }}"
               class="filter-btn {{ !request('status') ? 'active' : '' }}">
                Semua <span class="filter-count">{{ $stats['total'] }}</span>
            </a>
            <a href="{{ route('tenant.tickets.index', array_filter(['status' => 'running', 'q' => request('q')])) }}"
               class="filter-btn {{ request('status') === 'running' ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i> Berjalan
                <span class="filter-count">{{ $stats['running'] }}</span>
            </a>
            <a href="{{ route('tenant.tickets.index', array_filter(['status' => 'finished', 'q' => request('q')])) }}"
               class="filter-btn {{ request('status') === 'finished' ? 'active' : '' }}">
                <i class="bi bi-check-circle-fill"></i> Selesai
                <span class="filter-count">{{ $stats['finished'] }}</span>
            </a>
        </div>
    </div>

    <div class="search-info" id="searchInfo">
        <i class="bi bi-search"></i>
        Hasil pencarian: <strong id="searchCount">0</strong> tiket untuk
        "<strong id="searchKeyword"></strong>"
        &nbsp;&middot;&nbsp;
        <a href="#" id="searchReset">Reset</a>
    </div>

    <div class="o-card">
        <div class="o-card-header">
            <span class="o-card-header-title"><i class="bi bi-list-ul"></i> Daftar Tiket</span>
            <span class="o-card-count" id="cardCount">{{ $tickets->total() }} tiket</span>
        </div>

        <div id="ticketList">
        @forelse($tickets as $t)
        <a href="{{ route('tenant.tickets.show', $t->id) }}"
           class="ticket-item-link"
           data-search="{{ strtolower($t->subject . ' ' . $t->ticket_number . ' ' . $t->category) }}">
            <div class="ticket-item">

                @if($t->priority === 'urgent')
                    <div class="ticket-ava ava-urgent"><i class="bi bi-exclamation-circle-fill" style="color:var(--red)"></i></div>
                @elseif($t->priority === 'high')
                    <div class="ticket-ava ava-high"><i class="bi bi-exclamation-triangle-fill" style="color:#f97316"></i></div>
                @elseif(in_array($t->status, ['resolved','closed']))
                    <div class="ticket-ava ava-done"><i class="bi bi-check-circle-fill" style="color:var(--green-600)"></i></div>
                @elseif($t->status === 'in_progress')
                    <div class="ticket-ava ava-normal"><i class="bi bi-arrow-repeat" style="color:#7c3aed"></i></div>
                @else
                    <div class="ticket-ava ava-normal"><i class="bi bi-ticket" style="color:var(--blue)"></i></div>
                @endif

                <div class="ticket-body">
                    <div class="ticket-num">{{ $t->ticket_number }}</div>
                    <div class="ticket-subject searchable-subject">{{ $t->subject }}</div>
                    <div class="ticket-meta">
                        <i class="bi bi-tag-fill" style="color:var(--green-400)"></i>
                        {{ ucfirst($t->category) }}
                        <span class="meta-dot"></span>
                        <i class="bi bi-calendar3" style="color:var(--green-400)"></i>
                        {{ $t->created_at->format('d M Y') }}
                        @if($t->updated_at->ne($t->created_at))
                        <span class="meta-dot"></span>
                        Diperbarui {{ $t->updated_at->diffForHumans() }}
                        @endif
                    </div>
                    @if($t->status === 'resolved' && !$t->rating)
                        <span class="rate-nudge"><i class="bi bi-star-fill"></i> Berikan Penilaian</span>
                    @endif
                </div>

                <div class="ticket-right">
                    {!! $t->status_badge !!}
                    {!! $t->priority_badge !!}
                </div>
                <i class="bi bi-chevron-right" style="color:var(--neutral-400);font-size:.78rem"></i>
            </div>
        </a>
        @empty
        <div class="empty-state">
            <i class="bi bi-ticket empty-icon"></i>
            <h3>Belum Ada Tiket</h3>
            <p>Belum ada tiket yang dibuat. Jika ada keluhan atau permintaan layanan, kami siap membantu.</p>
            <a href="{{ route('tenant.tickets.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-circle-fill"></i> Buat Tiket Pertama
            </a>
        </div>
        @endforelse

        <div class="no-result" id="noResult">
            <i class="bi bi-search"></i>
            <p>Tidak ada tiket yang sesuai dengan pencarian.<br>Coba kata kunci lain.</p>
        </div>
        </div>

        @if($tickets->hasPages())
        <div class="pagination-wrap">{{ $tickets->links() }}</div>
        @endif
    </div>

    <div class="sla-card">
        <div class="sla-head">
            <i class="bi bi-clock-history"></i>
            <h3>Garansi Waktu Penanganan</h3>
        </div>
        <div class="sla-grid">
            <div class="sla-item"><div class="sla-p">Urgent</div><div class="sla-t">Ditangani &lt; 4 jam</div></div>
            <div class="sla-item"><div class="sla-p">Tinggi</div><div class="sla-t">Ditangani &lt; 12 jam</div></div>
            <div class="sla-item"><div class="sla-p">Normal</div><div class="sla-t">Ditangani &lt; 24 jam</div></div>
            <div class="sla-item"><div class="sla-p">Rendah</div><div class="sla-t">Ditangani &lt; 3 hari kerja</div></div>
        </div>
    </div>

</div>

<script>
(function () {
    const input      = document.getElementById('ticketSearch');
    const clearBtn   = document.getElementById('searchClear');
    const searchInfo = document.getElementById('searchInfo');
    const searchCount= document.getElementById('searchCount');
    const searchKw   = document.getElementById('searchKeyword');
    const searchReset= document.getElementById('searchReset');
    const noResult   = document.getElementById('noResult');
    const cardCount  = document.getElementById('cardCount');
    const items      = document.querySelectorAll('.ticket-item-link[data-search]');

    function highlight(text, keyword) {
        if (!keyword) return text;
        const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>');
    }

    function doSearch(keyword) {
        const kw = keyword.trim().toLowerCase();
        clearBtn.classList.toggle('visible', kw.length > 0);
        let visible = 0;

        items.forEach(item => {
            const match = !kw || item.getAttribute('data-search').includes(kw);
            item.classList.toggle('search-hidden', !match);
            if (match) {
                visible++;
                const subjEl = item.querySelector('.searchable-subject');
                if (subjEl) {
                    if (!subjEl.dataset.original) subjEl.dataset.original = subjEl.textContent;
                    subjEl.innerHTML = kw ? highlight(subjEl.dataset.original, kw) : subjEl.dataset.original;
                }
            } else {
                const subjEl = item.querySelector('.searchable-subject');
                if (subjEl && subjEl.dataset.original) subjEl.innerHTML = subjEl.dataset.original;
            }
        });

        if (kw) {
            searchInfo.classList.add('visible');
            searchCount.textContent = visible;
            searchKw.textContent    = keyword.trim();
            cardCount.textContent   = visible + ' tiket';
        } else {
            searchInfo.classList.remove('visible');
            cardCount.textContent = '{{ $tickets->total() }} tiket';
        }
        noResult.classList.toggle('visible', kw.length > 0 && visible === 0);
    }

    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => doSearch(this.value), 150);
    });
    clearBtn.addEventListener('click', function () { input.value = ''; doSearch(''); input.focus(); });
    searchReset.addEventListener('click', function (e) { e.preventDefault(); input.value = ''; doSearch(''); input.focus(); });
    if (input.value) doSearch(input.value);
})();
</script>
</body>
</html>