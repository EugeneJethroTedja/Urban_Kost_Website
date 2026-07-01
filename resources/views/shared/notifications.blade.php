<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi — Urban Kost</title>
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
        --blue-border: #bfdbfe;
        --blue-dark:   #1e40af;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --red-border:  #fecaca;
        --red-dark:    #991b1b;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --amber-border:#fde68a;
        --amber-dark:  #92400e;
        --purple:      #8b5cf6;
        --purple-pale: #f5f3ff;
        --purple-border:#ddd6fe;
        --purple-dark: #5b21b6;
        --teal:        #14b8a6;
        --teal-pale:   #f0fdfa;
        --teal-border: #99f6e4;
        --indigo-pale: #f0f4ff;
        --indigo-border:#c7d2fe;
        --indigo-dark: #3730a3;
        --orange-pale: #fff7ed;
        --orange-border:#fed7aa;
        --orange-dark: #9a3412;
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
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
        max-width: 980px;
        margin: 0 auto;
        padding: 32px 24px 80px;
    }
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .page-header-left {
        display: flex; align-items: center;
        gap: 12px; min-width: 0; flex: 1;
    }
    .page-header-back {
        display: flex; align-items: center; justify-content: center;
        width: 38px; height: 38px;
        border-radius: 10px;
        background: var(--neutral-0);
        border: 1.5px solid var(--neutral-200);
        color: var(--neutral-600);
        text-decoration: none;
        font-size: 1rem;
        transition: all .18s;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }
    .page-header-back:hover {
        border-color: var(--green-400);
        color: var(--green-600);
        background: var(--green-50);
    }
    .hero-banner {
        position: relative; overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 32px;
        margin-bottom: 20px;
        box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
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
        padding: 5px 14px; border-radius: 100px;
        font-size: .65rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82);
        margin-bottom: 12px;
    }
    .hero-eyebrow .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--green-300); flex-shrink: 0;
    }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.4rem, 4vw, 2rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 6px;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .hero-title .count-badge {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 24px; height: 24px; padding: 0 7px;
        background: var(--green-500);
        border: 2px solid rgba(255,255,255,.3);
        color: #fff;
        border-radius: 100px;
        font-family: 'Instrument Sans', sans-serif;
        font-size: .62rem; font-weight: 600;
        flex-shrink: 0;
    }
    .hero-sub {
        font-size: .83rem;
        color: rgba(255,255,255,.6);
        font-weight: 400;
    }
    .hero-actions { position: relative; z-index: 1; flex-shrink: 0; }
    .btn-primary-sm {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        font-size: .8rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        background: rgba(255,255,255,.15);
        border: 1.5px solid rgba(255,255,255,.3);
        color: #fff;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-primary-sm:hover {
        background: rgba(255,255,255,.25);
        border-color: rgba(255,255,255,.5);
        transform: translateY(-1px);
    }
    .stat-pills {
        display: flex; gap: 8px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .stat-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px;
        border-radius: 100px;
        font-size: .72rem; font-weight: 600;
        border: 1.5px solid;
        white-space: nowrap;
        background: var(--neutral-0);
        box-shadow: var(--shadow-sm);
    }
    .stat-pill-total  { border-color: var(--green-200);    color: var(--green-800); }
    .stat-pill-unread { border-color: var(--amber-border);  color: var(--amber-dark); background: var(--amber-pale); }
    .stat-pill-read   { border-color: var(--neutral-200);  color: var(--neutral-600); }
    .stat-pill i { font-size: .78rem; }
    .notif-list {
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .notif-list-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        flex-wrap: wrap;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .notif-list-title {
        display: flex; align-items: center; gap: 8px;
        font-size: .72rem; font-weight: 600;
        color: var(--neutral-600);
        text-transform: uppercase; letter-spacing: .07em;
    }
    .notif-list-title i { color: var(--green-500); }
    .notif-list-badge {
        font-size: .7rem;
        color: var(--amber-dark); font-weight: 600;
        display: flex; align-items: center; gap: 5px;
    }
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--neutral-100);
        transition: background .15s;
        position: relative;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: var(--green-50); }
    .notif-item.unread {
        background: linear-gradient(90deg, rgba(42,179,107,.04) 0%, transparent 60%);
        border-left: 3px solid var(--green-400);
    }
    .notif-item.unread:hover {
        background: linear-gradient(90deg, rgba(42,179,107,.08) 0%, var(--green-50) 70%);
    }
    .notif-item.unread::after {
        content: '';
        position: absolute;
        top: 20px; right: 18px;
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--green-500);
        box-shadow: 0 0 0 2px rgba(42,179,107,.25);
    }
    .notif-icon-wrap {
        flex-shrink: 0;
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem;
        transition: transform .2s;
    }
    .notif-item:hover .notif-icon-wrap { transform: scale(1.06); }
    .ni-booking { background: var(--blue-pale);   color: var(--blue-dark);   border: 1.5px solid var(--blue-border); }
    .ni-payment { background: var(--green-50);    color: var(--green-700);   border: 1.5px solid var(--green-200); }
    .ni-warning { background: var(--amber-pale);  color: var(--amber-dark);  border: 1.5px solid var(--amber-border); }
    .ni-danger  { background: var(--red-pale);    color: var(--red-dark);    border: 1.5px solid var(--red-border); }
    .ni-success { background: var(--green-100);   color: var(--green-800);   border: 1.5px solid var(--green-200); }
    .ni-info    { background: var(--teal-pale);   color: #0f766e;            border: 1.5px solid var(--teal-border); }
    .ni-waiting { background: var(--purple-pale); color: var(--purple-dark); border: 1.5px solid var(--purple-border); }
    .ni-ticket  { background: var(--indigo-pale); color: var(--indigo-dark); border: 1.5px solid var(--indigo-border); }
    .ni-renewal { background: var(--orange-pale); color: var(--orange-dark); border: 1.5px solid var(--orange-border); }
    .ni-default { background: var(--neutral-100); color: var(--neutral-600); border: 1.5px solid var(--neutral-200); }
    .notif-content { flex: 1; min-width: 0; }
    .notif-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 5px;
    }
    .notif-title {
        font-size: .86rem;
        font-weight: 600; color: var(--neutral-900);
        line-height: 1.35; flex: 1;
        display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
        word-break: break-word;
    }
    .notif-title.read { font-weight: 500; color: var(--neutral-600); }
    .new-dot {
        display: inline-flex; align-items: center;
        font-size: .52rem; font-weight: 700; letter-spacing: .05em;
        padding: 2px 7px; border-radius: 100px;
        background: var(--green-500); color: #fff;
        white-space: nowrap; flex-shrink: 0;
    }
    .notif-meta-right {
        display: flex; align-items: center; gap: 6px;
        flex-shrink: 0; flex-wrap: wrap;
    }
    .type-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .58rem;
        font-weight: 600; letter-spacing: .04em;
        padding: 2px 8px; border-radius: 100px; white-space: nowrap;
        border: 1px solid;
    }
    .tb-booking { background: var(--blue-pale);   color: var(--blue-dark);   border-color: var(--blue-border); }
    .tb-payment { background: var(--green-50);    color: var(--green-700);   border-color: var(--green-200); }
    .tb-warning { background: var(--amber-pale);  color: var(--amber-dark);  border-color: var(--amber-border); }
    .tb-danger  { background: var(--red-pale);    color: var(--red-dark);    border-color: var(--red-border); }
    .tb-success { background: var(--green-100);   color: var(--green-800);   border-color: var(--green-200); }
    .tb-info    { background: var(--teal-pale);   color: #0f766e;            border-color: var(--teal-border); }
    .tb-waiting { background: var(--purple-pale); color: var(--purple-dark); border-color: var(--purple-border); }
    .tb-ticket  { background: var(--indigo-pale); color: var(--indigo-dark); border-color: var(--indigo-border); }
    .tb-renewal { background: var(--orange-pale); color: var(--orange-dark); border-color: var(--orange-border); }
    .tb-default { background: var(--neutral-100); color: var(--neutral-600); border-color: var(--neutral-200); }
    .notif-time {
        font-size: .66rem;
        color: var(--neutral-400); white-space: nowrap;
    }
    .notif-body {
        font-size: .8rem;
        color: var(--neutral-600); line-height: 1.6;
        margin-bottom: 10px;
        word-break: break-word;
    }
    .notif-body.read { color: var(--neutral-400); }
    .payload-tags {
        display: flex; flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }
    .payload-tag {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .62rem; font-weight: 600;
        padding: 2px 8px; border-radius: 6px;
        white-space: nowrap; border: 1px solid;
    }
    .pt-invoice { background: var(--green-50);    border-color: var(--green-200);    color: var(--green-700); }
    .pt-booking { background: var(--blue-pale);   border-color: var(--blue-border);  color: var(--blue-dark); }
    .pt-wl      { background: var(--purple-pale); border-color: var(--purple-border);color: var(--purple-dark); }
    .pt-ticket  { background: var(--indigo-pale); border-color: var(--indigo-border);color: var(--indigo-dark); }
    .pt-stage   { background: var(--red-pale);    border-color: var(--red-border);   color: var(--red-dark); }
    .notif-actions {
        display: flex; align-items: center;
        gap: 8px; flex-wrap: wrap;
        margin-top: 2px;
    }
    .btn-notif {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px;
        border-radius: 8px;
        font-size: .72rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        border: none; cursor: pointer; text-decoration: none;
        transition: all .18s; white-space: nowrap;
    }
    .btn-notif-ghost {
        background: transparent; color: var(--neutral-500);
        border: 1.5px solid var(--neutral-200);
    }
    .btn-notif-ghost:hover {
        border-color: var(--green-400); color: var(--green-700);
        background: var(--green-50);
    }
    .notif-datetime {
        font-size: .65rem;
        color: var(--neutral-400); white-space: nowrap;
        margin-left: auto;
    }
    .empty-state { text-align: center; padding: 64px 24px; }
    .empty-icon-wrap {
        width: 72px; height: 72px;
        border-radius: 18px;
        background: var(--green-50); border: 1.5px solid var(--green-100);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 18px;
        font-size: 1.8rem; color: var(--green-400);
    }
    .empty-title {
        font-family: 'Fraunces', serif;
        font-size: 1rem; font-weight: 700;
        color: var(--neutral-700); margin-bottom: 8px;
    }
    .empty-desc { font-size: .8rem; color: var(--neutral-400); line-height: 1.6; }
    .pagination-wrapper {
        padding: 14px 20px;
        border-top: 1px solid var(--neutral-100);
        background: var(--neutral-50);
    }
    .pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px; flex-wrap: wrap;
    }
    .pagination-info {
        font-size: .72rem;
        color: var(--neutral-500); font-weight: 500;
        white-space: nowrap;
    }
    .pagination-nav {
        display: flex; align-items: center;
        gap: 4px; flex-wrap: wrap;
    }
    .pg-btn {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 10px;
        border-radius: 8px;
        font-size: .75rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        text-decoration: none;
        transition: all .18s;
        border: 1.5px solid var(--neutral-200);
        background: var(--neutral-0);
        color: var(--neutral-600);
        cursor: pointer; white-space: nowrap;
    }
    .pg-btn:hover { background: var(--green-50); border-color: var(--green-400); color: var(--green-700); }
    .pg-btn.active {
        background: linear-gradient(135deg, var(--green-600), var(--green-700));
        border-color: var(--green-600); color: #fff;
        box-shadow: 0 2px 8px rgba(29,148,88,.3);
        pointer-events: none;
    }
    .pg-btn.disabled {
        background: var(--neutral-100); border-color: var(--neutral-200);
        color: var(--neutral-400); pointer-events: none;
    }
    .pg-btn.dots {
        background: transparent; border-color: transparent;
        color: var(--neutral-400); pointer-events: none;
        min-width: auto; padding: 0 4px;
    }
    .pg-nav-btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 13px; border-radius: 9px;
        font-size: .73rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        text-decoration: none;
        border: 1.5px solid var(--neutral-200);
        background: var(--neutral-0); color: var(--neutral-600);
        transition: all .18s; white-space: nowrap;
    }
    .pg-nav-btn:hover { background: var(--green-50); border-color: var(--green-400); color: var(--green-700); }
    .pg-nav-btn.disabled { background: var(--neutral-100); border-color: var(--neutral-200); color: var(--neutral-400); pointer-events: none; }
    .form-inline { display: inline; }
    @media (max-width: 600px) {
        .page-shell { padding: 16px 14px 64px; }
        .hero-banner { flex-direction: column; align-items: flex-start; padding: 22px 18px; }
        .hero-arc { display: none; }
        .notif-item { padding: 14px; gap: 10px; }
        .notif-item.unread::after { top: 14px; right: 14px; }
        .notif-datetime { display: none; }
        .pagination-row { flex-direction: column; align-items: flex-start; }
        .stat-pills { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 4px; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .stat-pills::-webkit-scrollbar { display: none; }
    }
    @media (max-width: 400px) {
        .type-badge { display: none; }
    }
    @media (min-width: 900px) {
        .page-shell { padding: 40px 48px 80px; }
    }
    </style>
</head>
<body>

<div class="owner-bg"><div class="owner-bg-grid"></div></div>

@php $userRole = auth()->user()->role?->name ?? 'guest'; @endphp

@if($userRole === 'admin')
    @include('partials.admin-sidebar')
@elseif($userRole === 'owner')
    @include('partials.owner-sidebar')
@elseif($userRole === 'tenant')
    @include('partials.tenant-sidebar')
@elseif($userRole === 'prospective_tenant')
    @include('partials.prospective-sidebar')
@else
    @include('partials.tenant-sidebar')
@endif

<div class="page-shell">

    <div class="page-header">
        <div class="page-header-left">
            <div style="margin-bottom: 16px;">
                <a href="{{ url()->previous() }}" class="page-header-back" style="
                    display: inline-flex; align-items: center; gap: 8px;
                    width: auto; padding: 9px 18px;
                    font-size: .82rem; font-weight: 600;
                    font-family: 'Instrument Sans', sans-serif;
                    border-radius: 10px;
                ">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Urban Kost
            </div>
            <div class="hero-title">
                Notifikasi
                @if(($notifications->total() ?? 0) > 0)
                    <span class="count-badge">
                        {{ $notifications->total() > 99 ? '99+' : $notifications->total() }}
                    </span>
                @endif
            </div>
            <div class="hero-sub">Semua pemberitahuan aktivitas akun Anda</div>
        </div>
        <div class="hero-actions">
            @if(($unreadCount ?? 0) > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="form-inline">
                @csrf
                <button type="submit" class="btn-primary-sm">
                    <i class="bi bi-check-all"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="stat-pills">
        <div class="stat-pill stat-pill-total">
            <i class="bi bi-bell"></i>
            {{ $notifications->total() }} Total
        </div>
        @if(($unreadCount ?? 0) > 0)
        <div class="stat-pill stat-pill-unread">
            <i class="bi bi-circle-fill" style="font-size:.42rem"></i>
            {{ $unreadCount }} Belum Dibaca
        </div>
        @endif
        @if(($notifications->total() - ($unreadCount ?? 0)) > 0)
        <div class="stat-pill stat-pill-read">
            <i class="bi bi-check2-all"></i>
            {{ $notifications->total() - ($unreadCount ?? 0) }} Sudah Dibaca
        </div>
        @endif
    </div>

    <div class="notif-list">
        <div class="notif-list-header">
            <div class="notif-list-title">
                <i class="bi bi-bell-fill"></i>
                Semua Notifikasi
            </div>
            @if(($unreadCount ?? 0) > 0)
            <span class="notif-list-badge">
                <i class="bi bi-circle-fill" style="font-size:.38rem;color:var(--amber)"></i>
                {{ $unreadCount }} notifikasi baru
            </span>
            @endif
        </div>

        @forelse($notifications as $notif)
        @php
            $iconMap = [
                'new_booking'                   => ['bi-calendar-plus',     'ni-booking',  'tb-booking',  'Booking Baru'],
                'booking_approved'              => ['bi-calendar-check',    'ni-success',  'tb-success',  'Booking Disetujui'],
                'booking_rejected'              => ['bi-calendar-x',        'ni-danger',   'tb-danger',   'Booking Ditolak'],
                'booking_cancelled_renewal'     => ['bi-x-circle',          'ni-danger',   'tb-danger',   'Booking Dibatalkan'],
                'booking_processing'            => ['bi-hourglass-split',   'ni-info',     'tb-info',     'Sedang Diproses'],
                'renewal_inquiry'               => ['bi-arrow-repeat',      'ni-renewal',  'tb-renewal',  'Konfirmasi Perpanjangan'],
                'renewal_confirmed'             => ['bi-check-circle',      'ni-success',  'tb-success',  'Perpanjangan Dikonfirmasi'],
                'renewal_declined'              => ['bi-calendar-check',    'ni-info',     'tb-info',     'Tidak Perpanjang'],
                'renewal_self_initiated'        => ['bi-arrow-repeat',      'ni-renewal',  'tb-renewal',  'Perpanjangan Diajukan'],
                'renewal_needs_approval'        => ['bi-arrow-repeat',      'ni-warning',  'tb-warning',  'Perlu Persetujuan'],
                'renewal_payment_success'       => ['bi-check-circle',      'ni-success',  'tb-success',  'Pembayaran Diterima'],
                'waiting_list_approved'         => ['bi-list-check',        'ni-waiting',  'tb-waiting',  'WL Disetujui'],
                'waiting_list_created'          => ['bi-list-ul',           'ni-booking',  'tb-booking',  'WL Baru'],
                'waiting_list_dp_paid'          => ['bi-cash-coin',         'ni-success',  'tb-success',  'DP WL Lunas'],
                'waiting_list_dp_received'      => ['bi-cash-coin',         'ni-success',  'tb-success',  'DP WL Masuk'],
                'waiting_list_cancelled'        => ['bi-x-circle',          'ni-danger',   'tb-danger',   'WL Dibatalkan'],
                'waiting_list_cancelled_admin'  => ['bi-x-circle',          'ni-danger',   'tb-danger',   'WL Dibatalkan'],
                'room_available_for_wl'         => ['bi-house-check',       'ni-success',  'tb-success',  'Kamar Tersedia'],
                'invoice_created'               => ['bi-receipt',           'ni-payment',  'tb-payment',  'Tagihan Baru'],
                'invoice_created_auto'          => ['bi-receipt',           'ni-payment',  'tb-payment',  'Invoice Otomatis'],
                'invoice_paid'                  => ['bi-check-circle',      'ni-success',  'tb-success',  'Invoice Lunas'],
                'invoice_paid_needs_activation' => ['bi-person-check',      'ni-warning',  'tb-warning',  'Perlu Aktivasi'],
                'payment_uploaded'              => ['bi-cloud-upload',      'ni-info',     'tb-info',     'Bukti Dikirim'],
                'payment_verified'              => ['bi-check-circle',      'ni-success',  'tb-success',  'Pembayaran Terverifikasi'],
                'payment_rejected'              => ['bi-x-circle',          'ni-danger',   'tb-danger',   'Pembayaran Ditolak'],
                'payment_reminder_h3'           => ['bi-alarm',             'ni-warning',  'tb-warning',  'Pengingat H-3'],
                'payment_reminder_h2'           => ['bi-alarm',             'ni-warning',  'tb-warning',  'Pengingat H-2'],
                'payment_reminder_h1'           => ['bi-alarm',             'ni-danger',   'tb-danger',   'Tagihan Besok'],
                'payment_reminder_h0'           => ['bi-alarm-fill',        'ni-danger',   'tb-danger',   'Tagihan Hari Ini'],
                'new_ticket'                    => ['bi-ticket-perforated', 'ni-ticket',   'tb-ticket',   'Tiket Baru'],
                'ticket_replied'                => ['bi-chat-left-dots',    'ni-ticket',   'tb-ticket',   'Tiket Dibalas'],
                'ticket_resolved'               => ['bi-check-circle',      'ni-success',  'tb-success',  'Tiket Selesai'],
                'advance_booking_notice'        => ['bi-bell',              'ni-booking',  'tb-booking',  'Advance Booking'],
                'room_handover_notice'          => ['bi-house-door',        'ni-warning',  'tb-warning',  'Serah Terima'],
                'tenancy_ended'                 => ['bi-house-slash',       'ni-info',     'tb-info',     'Sewa Berakhir'],
                'role_upgraded'                 => ['bi-person-check',      'ni-success',  'tb-success',  'Akun Diupgrade'],
                'default'                       => ['bi-bell',              'ni-default',  'tb-default',  'Notifikasi'],
            ];

            [$icon, $niClass, $tbClass, $typeLabel] = $iconMap[$notif->type] ?? $iconMap['default'];
            $isUnread = !$notif->is_read;
            $role     = auth()->user()->role?->name;
            $payload  = is_array($notif->payload)
                          ? $notif->payload
                          : (json_decode($notif->payload ?? '{}', true) ?? []);

            $actionUrl = $notif->action_url ?? null;
            if (!$actionUrl) {
                $actionUrl = match(true) {
                    str_starts_with($notif->type, 'new_booking') && in_array($role, ['admin','owner'])
                        => isset($payload['booking_id']) ? route('admin.bookings.show', $payload['booking_id']) : null,
                    in_array($notif->type, ['invoice_created_auto','invoice_paid_needs_activation','payment_uploaded']) && in_array($role, ['admin','owner'])
                        => isset($payload['invoice_id']) ? route('admin.invoices.show', $payload['invoice_id']) : null,
                    in_array($notif->type, ['waiting_list_created','waiting_list_dp_received','waiting_list_cancelled_admin']) && in_array($role, ['admin','owner'])
                        => isset($payload['waiting_list_id']) ? route('admin.waiting-list.show', $payload['waiting_list_id']) : null,
                    in_array($notif->type, ['renewal_self_initiated','renewal_confirmed','renewal_needs_approval']) && in_array($role, ['admin','owner'])
                        => isset($payload['renewal_id']) ? route('admin.renewals.show', $payload['renewal_id']) : null,
                    in_array($notif->type, ['new_ticket']) && in_array($role, ['admin','owner'])
                        => isset($payload['ticket_id']) ? route('admin.tickets.show', $payload['ticket_id']) : null,
                    in_array($notif->type, ['invoice_created','invoice_paid','payment_verified','payment_rejected','payment_reminder_h3','payment_reminder_h2','payment_reminder_h1','payment_reminder_h0']) && $role === 'tenant'
                        => isset($payload['invoice_id']) ? route('tenant.invoices.show', $payload['invoice_id']) : null,
                    in_array($notif->type, ['renewal_inquiry','renewal_confirmed','renewal_payment_success']) && $role === 'tenant'
                        => isset($payload['renewal_id'])
                            ? route('tenant.renewal.show', $payload['renewal_id'])
                            : (isset($payload['booking_id']) ? route('tenant.renewal.show', $payload['booking_id']) : null),
                    in_array($notif->type, ['ticket_replied','ticket_resolved']) && $role === 'tenant'
                        => isset($payload['ticket_id']) ? route('tenant.tickets.show', $payload['ticket_id']) : null,
                    $notif->type === 'role_upgraded' && $role === 'tenant'
                        => route('tenant.dashboard'),
                    in_array($notif->type, ['advance_booking_notice','room_handover_notice','tenancy_ended']) && $role === 'tenant'
                        => route('tenant.dashboard'),
                    in_array($notif->type, ['booking_approved','booking_rejected','booking_cancelled_renewal','booking_processing']) && $role === 'prospective_tenant'
                        => isset($payload['booking_id']) ? route('prospective.bookings') : null,
                    in_array($notif->type, ['invoice_created','payment_verified','payment_rejected']) && $role === 'prospective_tenant'
                        => isset($payload['invoice_id']) ? route('prospective.invoices.show', $payload['invoice_id']) : null,
                    in_array($notif->type, ['waiting_list_approved','waiting_list_dp_paid','waiting_list_cancelled','room_available_for_wl']) && $role === 'prospective_tenant'
                        => isset($payload['waiting_list_id']) ? route('prospective.waiting-list.show', $payload['waiting_list_id']) : null,
                    default => null,
                };
            }

            $diffHuman = $notif->created_at->diffForHumans();
            $fullTime  = $notif->created_at->format('d M Y') . '';
        @endphp

        <div class="notif-item {{ $isUnread ? 'unread' : '' }}">
            <div class="notif-icon-wrap {{ $niClass }}">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div class="notif-content">
                <div class="notif-top">
                    <div class="notif-title {{ $isUnread ? '' : 'read' }}">
                        {{ $notif->title }}
                        @if($isUnread)
                            <span class="new-dot">BARU</span>
                        @endif
                    </div>
                    <div class="notif-meta-right">
                        <span class="type-badge {{ $tbClass }}">{{ $typeLabel }}</span>
                        <span class="notif-time" title="{{ $fullTime }}">{{ $diffHuman }}</span>
                    </div>
                </div>

                <div class="notif-body {{ $isUnread ? '' : 'read' }}">{{ $notif->body }}</div>

                @if(!empty($payload))
                <div class="payload-tags">
                    @if(!empty($payload['invoice_id']))
                    <span class="payload-tag pt-invoice">
                        <i class="bi bi-receipt"></i> Invoice #{{ $payload['invoice_id'] }}
                    </span>
                    @endif
                    @if(!empty($payload['booking_id']))
                    <span class="payload-tag pt-booking">
                        <i class="bi bi-calendar"></i> Booking #{{ $payload['booking_id'] }}
                    </span>
                    @endif
                    @if(!empty($payload['waiting_list_id']))
                    <span class="payload-tag pt-wl">
                        <i class="bi bi-list-ul"></i> WL #{{ $payload['waiting_list_id'] }}
                    </span>
                    @endif
                    @if(!empty($payload['ticket_id']))
                    <span class="payload-tag pt-ticket">
                        <i class="bi bi-ticket-perforated"></i> Tiket #{{ $payload['ticket_id'] }}
                    </span>
                    @endif
                    @if(!empty($payload['stage']))
                    <span class="payload-tag pt-stage">
                        <i class="bi bi-clock-fill"></i> {{ $payload['stage'] }}
                    </span>
                    @endif
                </div>
                @endif

                <div class="notif-actions">
                    @if($isUnread)
                        <form method="POST"
                              action="{{ route('notifications.read', $notif->id) }}"
                              class="form-inline">
                            @csrf
                            <button type="submit" class="btn-notif btn-notif-ghost">
                                <i class="bi bi-check2"></i>
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif

                    <span class="notif-datetime">{{ $fullTime }}</span>
                </div>
            </div>
        </div>

        @empty
        <div class="empty-state">
            <div class="empty-icon-wrap"><i class="bi bi-bell-slash"></i></div>
            <div class="empty-title">Belum Ada Notifikasi</div>
            <div class="empty-desc">
                Notifikasi akan muncul di sini saat ada aktivitas di akun Anda.
            </div>
        </div>
        @endforelse

        @if($notifications->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-row">
                <p class="pagination-info">
                    Menampilkan
                    <strong style="color:var(--neutral-700)">{{ $notifications->firstItem() }}–{{ $notifications->lastItem() }}</strong>
                    dari
                    <strong style="color:var(--neutral-700)">{{ $notifications->total() }}</strong>
                    notifikasi
                </p>

                <div class="pagination-nav">
                    @if($notifications->onFirstPage())
                        <span class="pg-nav-btn disabled">
                            <i class="bi bi-chevron-left"></i> Sebelumnya
                        </span>
                    @else
                        <a href="{{ $notifications->previousPageUrl() }}" class="pg-nav-btn">
                            <i class="bi bi-chevron-left"></i> Sebelumnya
                        </a>
                    @endif

                    @php
                        $current  = $notifications->currentPage();
                        $last     = $notifications->lastPage();
                        $window   = 1;
                        $shown    = [];
                        for ($p = 1; $p <= $last; $p++) {
                            if ($p === 1 || $p === $last || abs($p - $current) <= $window) {
                                $shown[] = $p;
                            }
                        }
                        $shown = array_unique($shown);
                        sort($shown);
                    @endphp

                    @php $prev = null; @endphp
                    @foreach($shown as $page)
                        @if($prev !== null && $page - $prev > 1)
                            <span class="pg-btn dots">&hellip;</span>
                        @endif
                        @if($page === $current)
                            <span class="pg-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $notifications->url($page) }}" class="pg-btn">{{ $page }}</a>
                        @endif
                        @php $prev = $page; @endphp
                    @endforeach

                    @if($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}" class="pg-nav-btn">
                            Berikutnya <i class="bi bi-chevron-right"></i>
                        </a>
                    @else
                        <span class="pg-nav-btn disabled">
                            Berikutnya <i class="bi bi-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
</body>
</html>