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
        --blue:        #2563eb;
        --blue-pale:   #eff6ff;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --purple:      #9333ea;
        --purple-pale: #faf5ff;
        --purple-200:  #e9d5ff;
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
    .page-wrap { min-height: 100vh; display: flex; flex-direction: column; }
    .page-main { flex: 1; padding: 32px 0 64px; width: 100%; }
    .page-inner { max-width: 960px; margin: 0 auto; padding: 0 24px; width: 100%; }
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
    .hero-eyebrow .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--green-300); flex-shrink: 0;
    }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 8px;
    }
    .hero-title em {
        font-style: italic;
        font-weight: 400;
        color: rgba(255,255,255,.72);
    }
    .hero-sub {
        font-size: .9rem;
        color: rgba(255,255,255,.62);
        font-weight: 400;
        max-width: 400px;
        line-height: 1.6;
    }
    .flash {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 13px 16px; border-radius: 10px;
        margin-bottom: 12px;
        font-size: .82rem; font-weight: 500;
        border: 1px solid; line-height: 1.55;
    }
    .flash i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }

    /* ── Stats grid: 3-col on mobile xs, 3-col on all sizes ─────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        position: relative; overflow: hidden;
        border-radius: 16px;
        padding: 22px 20px;
        background: var(--neutral-0);
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        transition: border-color .25s, box-shadow .3s, transform .25s;
        cursor: default;
    }
    .stat-card:hover {
        border-color: var(--green-300);
        box-shadow: var(--shadow-lg);
        transform: translateY(-3px);
    }
    .stat-card::after {
        content: ''; position: absolute;
        bottom: 0; left: 0; right: 0; height: 3px;
        border-radius: 0 0 16px 16px;
    }
    .stat-card.c-red::after   { background: var(--red); }
    .stat-card.c-amber::after { background: var(--amber); }
    .stat-card.c-green::after { background: var(--green-500); }
    .stat-icon-wrap {
        width: 40px; height: 40px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; margin-bottom: 14px;
    }
    .stat-value {
        font-family: 'Fraunces', serif;
        font-size: 2rem; font-weight: 700;
        line-height: 1; margin-bottom: 4px;
        letter-spacing: -.025em;
    }
    .stat-label {
        font-size: .68rem; font-weight: 600;
        color: var(--neutral-400);
        text-transform: uppercase; letter-spacing: .07em;
    }

    /* ── On very small screens, keep 3-col but shrink padding ───────── */
    @media (max-width: 360px) {
        .stat-card { padding: 16px 12px; }
        .stat-icon-wrap { width: 32px; height: 32px; font-size: .8rem; margin-bottom: 10px; }
        .stat-value { font-size: 1.6rem; }
        .stat-label { font-size: .6rem; }
    }

    .group-wrap { margin-bottom: 32px; }
    .group-header {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 14px; padding: 14px 18px;
        border-radius: 14px; border: 1px solid;
    }
    .group-header-icon {
        width: 34px; height: 34px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; flex-shrink: 0;
        background: rgba(255,255,255,.6);
    }
    .group-header-title {
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        flex: 1;
    }
    .group-header-count {
        font-size: .65rem; font-weight: 700;
        padding: 3px 10px; border-radius: 100px;
        border: 1px solid;
        background: rgba(255,255,255,.6);
    }
    .inv-card {
        background: var(--neutral-0);
        border-radius: 14px;
        border: 1px solid var(--green-100);
        border-left: 4px solid;
        margin-bottom: 10px; overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: box-shadow .2s, transform .2s;
    }
    .inv-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }
    .inv-card.s-overdue { border-left-color: var(--red); }
    .inv-card.s-unpaid  { border-left-color: var(--amber); }
    .inv-card.s-paid    { border-left-color: var(--green-500); }
    .inv-inner {
        display: flex; align-items: center;
        gap: 14px; padding: 18px 20px; flex-wrap: wrap;
    }
    .inv-ico {
        width: 46px; height: 46px; border-radius: 13px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .inv-body { flex: 1; min-width: 200px; }
    .inv-type-tag {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .6rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .07em; padding: 2px 8px; border-radius: 100px;
        border: 1px solid; margin-bottom: 5px;
    }
    .inv-num {
        font-family: 'Fraunces', serif;
        font-weight: 600; font-size: .92rem; color: var(--neutral-900);
        letter-spacing: -.01em;
    }
    .inv-sub { font-size: .72rem; color: var(--neutral-500); margin-top: 3px; }
    .inv-right { text-align: right; flex-shrink: 0; }
    .inv-amount {
        font-family: 'Fraunces', serif;
        font-size: 1.2rem; color: var(--green-600); font-weight: 700;
        letter-spacing: -.02em;
    }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 100px;
        font-size: .62rem; font-weight: 600; border: 1px solid;
    }
    .btn-action {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 15px; border-radius: 9px;
        font-size: .75rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        border: none; cursor: pointer; text-decoration: none;
        transition: all .18s; white-space: nowrap; margin-top: 8px;
    }
    .btn-action-primary {
        background: var(--green-500); color: #fff;
        box-shadow: 0 2px 10px rgba(42,179,107,.28);
    }
    .btn-action-primary:hover { background: var(--green-600); color: #fff; }
    .btn-action-outline {
        background: transparent;
        border: 1px solid var(--green-200);
        color: var(--green-700);
    }
    .btn-action-outline:hover {
        background: var(--green-50);
        border-color: var(--green-300);
    }
    .empty-state {
        background: var(--neutral-0);
        border-radius: 20px;
        border: 2px dashed var(--green-200);
        text-align: center; padding: 64px 24px;
        box-shadow: var(--shadow-sm);
    }
    .empty-state-icon {
        font-size: 2.8rem; color: var(--green-200);
        margin-bottom: 16px; line-height: 1;
    }
    .empty-state-title {
        font-family: 'Fraunces', serif;
        font-size: 1.1rem; font-weight: 700;
        color: var(--neutral-700); margin-bottom: 8px;
        letter-spacing: -.02em;
    }
    .empty-state-sub {
        font-size: .82rem; color: var(--neutral-400);
        max-width: 320px; margin: 0 auto; line-height: 1.65;
    }
    .pagination-wrap {
        display: flex; align-items: center; justify-content: center;
        gap: 6px; list-style: none; padding: 0; margin: 28px 0 0; flex-wrap: wrap;
    }
    .pagination-wrap li a,
    .pagination-wrap li span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 10px;
        border-radius: 9px;
        font-size: .78rem; font-weight: 600;
        font-family: 'Instrument Sans', sans-serif;
        text-decoration: none; transition: all .18s;
        border: 1px solid var(--green-200);
        background: var(--neutral-0); color: var(--neutral-500);
    }
    .pagination-wrap li a:hover {
        border-color: var(--green-400);
        color: var(--green-700);
        background: var(--green-50);
    }
    .pagination-wrap li.active span {
        background: var(--green-500);
        border-color: var(--green-500);
        color: #fff;
        box-shadow: 0 2px 10px rgba(42,179,107,.28);
    }
    .pagination-wrap li.disabled span {
        background: var(--neutral-50);
        color: var(--neutral-200);
        border-color: var(--neutral-200);
        cursor: not-allowed;
    }
    .pagination-wrap li.dots span {
        border-color: transparent;
        background: transparent;
        color: var(--neutral-400);
        min-width: 24px;
    }
    @media (max-width: 480px) {
        .page-inner { padding: 0 14px; }
        .hero-banner { padding: 22px 18px; }
        .hero-arc    { display: none; }
        .hero-title  { font-size: 1.25rem; }
        .stats-grid  { gap: 10px; }
        .inv-right { width: 100%; display: flex; align-items: center; justify-content: space-between; text-align: left; }
        .inv-amount { font-size: 1rem; }
    }
    @media (min-width: 900px) {
        .page-inner { padding: 0 40px; }
        .page-main  { padding: 40px 0 64px; }
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.prospective-sidebar')

<div class="page-wrap">
<main class="page-main">
<div class="page-inner">

    @foreach(['success','warning','error','info'] as $type)
    @if(session($type))
    @php
        $fc = [
            'success' => ['var(--green-50)',   'var(--green-200)', 'check-circle-fill',         'var(--green-600)'],
            'error'   => ['var(--red-pale)',   '#fecaca',          'exclamation-circle-fill',   'var(--red)'],
            'warning' => ['var(--amber-pale)', '#fde68a',          'exclamation-triangle-fill', 'var(--amber)'],
            'info'    => ['var(--blue-pale)',   '#bfdbfe',          'info-circle-fill',          'var(--blue)'],
        ][$type];
    @endphp
    <div class="flash" style="background:{{ $fc[0] }};border-color:{{ $fc[1] }};color:{{ $fc[3] }}">
        <i class="bi bi-{{ $fc[2] }}"></i>
        <div>{!! session($type) !!}</div>
    </div>
    @endif
    @endforeach

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Detail Tagihan
            </div>
            <h1 class="hero-title">
                Tagihan Saya<br><em>Kelola Pembayaran</em>
            </h1>
            <p class="hero-sub">Pantau seluruh tagihan sewa kamar Anda di satu tempat.</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card c-red">
            <div class="stat-icon-wrap" style="background: var(--red-pale); color: var(--red);">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--red);">{{ $stats['overdue'] ?? 0 }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
        <div class="stat-card c-amber">
            <div class="stat-icon-wrap" style="background: var(--amber-pale); color: var(--amber);">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--amber);">{{ $stats['unpaid'] }}</div>
            <div class="stat-label">Belum Bayar</div>
        </div>
        <div class="stat-card c-green">
            <div class="stat-icon-wrap" style="background: var(--green-100); color: var(--green-600);">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-value" style="color: var(--green-600);">{{ $stats['paid'] }}</div>
            <div class="stat-label">Lunas</div>
        </div>
    </div>

    @php
        $grouped = [
            'down_payment' => [],
            'settlement'   => [],
            'renewal'      => [],
            'other'        => [],
        ];
        foreach ($invoices as $inv) {
            $t   = $inv->invoice_type ?? '';
            $num = strtoupper($inv->invoice_number ?? '');
            if ($t === 'down_payment') {
                $grouped['down_payment'][] = $inv;
            } elseif ($t === 'settlement') {
                $grouped['settlement'][] = $inv;
            } elseif ($t === 'renewal' || str_contains($num, 'RNWL') || str_contains($num, '-RNW')) {
                $grouped['renewal'][] = $inv;
            } else {
                $grouped['other'][] = $inv;
            }
        }
        $groupConfig = [
            'down_payment' => [
                'label'      => 'DP / Uang Muka Booking',
                'icon'       => 'bi-wallet2',
                'color'      => 'var(--blue)',
                'bg'         => 'var(--blue-pale)',
                'border'     => '#bfdbfe',
                'type_label' => 'DP Booking',
            ],
            'settlement' => [
                'label'      => 'Pelunasan / Sisa Pembayaran',
                'icon'       => 'bi-cash-coin',
                'color'      => 'var(--amber)',
                'bg'         => 'var(--amber-pale)',
                'border'     => '#fde68a',
                'type_label' => 'Pelunasan',
            ],
            'renewal' => [
                'label'      => 'Perpanjangan Sewa',
                'icon'       => 'bi-arrow-repeat',
                'color'      => 'var(--purple)',
                'bg'         => 'var(--purple-pale)',
                'border'     => 'var(--purple-200)',
                'type_label' => 'Renewal',
            ],
            'other' => [
                'label'      => 'Tagihan Sewa Bulanan',
                'icon'       => 'bi-house-fill',
                'color'      => 'var(--green-700)',
                'bg'         => 'var(--green-50)',
                'border'     => 'var(--green-200)',
                'type_label' => 'Sewa Bulanan',
            ],
        ];
        $hasAny = false;
    @endphp

    @foreach(['down_payment','settlement','renewal','other'] as $gk)
    @php $items = $grouped[$gk]; $cfg = $groupConfig[$gk]; @endphp
    @if(count($items) > 0)
    @php $hasAny = true; @endphp

    <div class="group-wrap">
        <div class="group-header" style="background:{{ $cfg['bg'] }};border-color:{{ $cfg['border'] }}">
            <div class="group-header-icon" style="color:{{ $cfg['color'] }}">
                <i class="bi {{ $cfg['icon'] }}"></i>
            </div>
            <span class="group-header-title" style="color:{{ $cfg['color'] }}">{{ $cfg['label'] }}</span>
            <span class="group-header-count" style="color:{{ $cfg['color'] }};border-color:{{ $cfg['border'] }}">
                {{ count($items) }} tagihan
            </span>
        </div>

        @foreach($items as $inv)
        @php
            $st = $inv->status;
            $displayStatus = ($st === 'pending_verification') ? 'paid' : $st;

            $cardClass = match($displayStatus) {
                'paid'    => 's-paid',
                'overdue' => 's-overdue',
                default   => 's-unpaid',
            };
            $icoStyle = match($displayStatus) {
                'paid'    => 'background:var(--green-100);color:var(--green-700)',
                'overdue' => 'background:var(--red-pale);color:var(--red)',
                default   => 'background:var(--amber-pale);color:var(--amber)',
            };
            $ico = match($displayStatus) {
                'paid'    => 'bi-check-circle-fill',
                'overdue' => 'bi-exclamation-triangle-fill',
                default   => 'bi-receipt',
            };
            $route = auth()->user()->hasRole('tenant')
                ? route('tenant.invoices.show', $inv->id)
                : route('prospective.invoices.show', $inv->id);
        @endphp

        <div class="inv-card {{ $cardClass }}">
            <div class="inv-inner">
                <div class="inv-ico" style="{{ $icoStyle }}">
                    <i class="bi {{ $ico }}"></i>
                </div>
                <div class="inv-body">
                    <span class="inv-type-tag" style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};border-color:{{ $cfg['border'] }}">
                        <i class="bi {{ $cfg['icon'] }}"></i>
                        {{ $cfg['type_label'] }}
                    </span>
                    <div class="inv-num">{{ $inv->invoice_number }}</div>
                    <div class="inv-sub">
                        Kamar {{ $inv->room?->room_number }}
                        @if($inv->period_start && $inv->period_end)
                        &nbsp;&middot;&nbsp;{{ $inv->period_start->format('d M') }} – {{ $inv->period_end->format('d M Y') }}
                        @endif
                    </div>
                    @if($displayStatus === 'unpaid' && $inv->days_until_due !== null && $inv->days_until_due == 0)
                    <div style="margin-top:5px">
                        <span class="badge-pill" style="background:var(--amber-pale);color:var(--amber);border-color:#fde68a">
                            <i class="bi bi-clock-fill"></i>Jatuh tempo hari ini
                        </span>
                    </div>
                    @elseif($inv->isOverdue())
                    <div style="margin-top:5px">
                        <span class="badge-pill" style="background:var(--red-pale);color:var(--red);border-color:#fecaca">
                            <i class="bi bi-alarm-fill"></i>Terlambat {{ abs($inv->days_until_due) }} hari
                        </span>
                    </div>
                    @elseif($displayStatus === 'unpaid' && $inv->days_until_due !== null)
                    <div style="margin-top:4px;font-size:.7rem;color:var(--neutral-500)">
                        Jatuh tempo: <strong>{{ $inv->due_date?->format('d M Y') }}</strong>
                        @if($inv->days_until_due <= 3 && $inv->days_until_due >= 1)
                        <span class="badge-pill" style="background:var(--amber-pale);color:var(--amber);border-color:#fde68a;margin-left:4px">
                            {{ $inv->days_until_due }}h lagi
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="inv-right">
                    <div class="inv-amount">{{ $inv->formatted_total }}</div>
                    <div style="margin-top:5px">{!! $inv->status_badge !!}</div>
                    <a href="{{ $route }}"
                       class="btn-action {{ in_array($displayStatus, ['unpaid','overdue']) ? 'btn-action-primary' : 'btn-action-outline' }}">
                        @if(in_array($displayStatus, ['unpaid','overdue']))
                            <i class="bi bi-credit-card-fill"></i>Bayar
                        @else
                            <i class="bi bi-eye"></i>Detail
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    @endif
    @endforeach

    @if(!$hasAny)
    <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-receipt"></i></div>
        <div class="empty-state-title">Belum Ada Tagihan</div>
        <p class="empty-state-sub">
            Tagihan akan muncul di sini setelah pembayaran Anda diproses.
        </p>
    </div>
    @endif

    @if($invoices->hasPages())
    <ul class="pagination-wrap">
        @if($invoices->onFirstPage())
            <li class="disabled"><span><i class="bi bi-chevron-left"></i></span></li>
        @else
            <li><a href="{{ $invoices->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a></li>
        @endif

        @foreach($invoices->links()->elements as $element)
            @if(is_string($element))
                <li class="dots"><span>&hellip;</span></li>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $invoices->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if($invoices->hasMorePages())
            <li><a href="{{ $invoices->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a></li>
        @else
            <li class="disabled"><span><i class="bi bi-chevron-right"></i></span></li>
        @endif
    </ul>
    @endif

</div>
</main>
</div>

</body>
</html>