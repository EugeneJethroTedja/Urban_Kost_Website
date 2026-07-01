<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting List — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        --blue-line:   #bfdbfe;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --red-line:    #fecaca;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --amber-line:  #fde68a;
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

    .page { max-width: 820px; margin: 0 auto; padding: 32px 24px 72px; }

    .flash {
        display: flex; gap: 9px; align-items: flex-start;
        padding: 11px 14px; border-radius: 10px; border: 1px solid;
        font-size: .8rem; margin-bottom: 12px;
        font-family: 'Instrument Sans', sans-serif;
    }

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
    .hero-banner-dots {
        position: absolute; inset: 0; opacity: .03;
        background-image: radial-gradient(circle, #fff 1px, transparent 1px);
        background-size: 28px 28px; pointer-events: none;
    }
    .hero-arc {
        position: absolute; right: -100px; top: -100px;
        width: 320px; height: 320px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none; z-index: 1;
    }
    .hero-content { position: relative; z-index: 1; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 5px 14px; border-radius: var(--r-pill);
        font-size: .68rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82);
        margin-bottom: 14px;
    }
    .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.4rem, 3.5vw, 2rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 8px;
    }
    .hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
    .hero-sub { font-size: .82rem; color: rgba(255,255,255,.52); margin-bottom: 18px; max-width: 380px; }
    .hero-stats { display: flex; flex-wrap: wrap; gap: 8px; }
    .hstat {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 13px; border-radius: var(--r-pill);
        font-size: .7rem; font-weight: 600;
        border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.1); color: rgba(255,255,255,.85);
    }
    .hstat i { color: var(--green-400); }
    .hstat.s-notif { background: rgba(96,165,250,.2); border-color: rgba(147,197,253,.3); color: #bfdbfe; }
    .hstat.s-notif i { color: #60a5fa; }
    .hstat.s-warn  { background: rgba(245,158,11,.18); border-color: rgba(251,191,36,.3); color: #fde68a; }
    .hstat.s-warn i { color: #fbbf24; }

    .wl-card {
        background: var(--neutral-0);
        border-radius: var(--r-card);
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 16px;
        border-left-width: 4px; border-left-style: solid;
        transition: box-shadow .22s, transform .18s;
    }
    .wl-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
    .wl-card.waiting   { border-left-color: var(--amber); }
    .wl-card.notified  { border-left-color: var(--blue); }
    .wl-card.cancelled { border-left-color: var(--neutral-400); opacity: .75; }
    .wl-card.converted { border-left-color: var(--green-500); }

    .wl-head {
        padding: 16px 20px;
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 12px; flex-wrap: wrap;
        border-bottom: 1px solid var(--neutral-100);
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .room-name {
        font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 700;
        color: var(--neutral-900); margin-bottom: 4px;
    }
    .room-meta {
        font-size: .68rem; color: var(--neutral-400);
        display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-top: 2px;
    }
    .room-meta span { display: inline-flex; align-items: center; gap: 3px; }
    .head-r { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
    .price-tag {
        font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: var(--green-600);
    }
    .price-tag small { font-size: .6rem; font-weight: 400; font-family: 'Instrument Sans', sans-serif; color: var(--neutral-400); }
    .pills { display: flex; gap: 5px; flex-wrap: wrap; justify-content: flex-end; }

    .pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: var(--r-pill);
        font-size: .62rem; font-weight: 700; letter-spacing: .03em; border: 1px solid;
    }
    .pill-pos       { background: var(--neutral-100); color: var(--neutral-600); border-color: var(--neutral-200); }
    .pill-waiting   { background: var(--amber-pale); color: #92400e; border-color: var(--amber-line); }
    .pill-notified  { background: var(--blue-pale); color: var(--blue); border-color: var(--blue-line); }
    .pill-cancelled { background: var(--neutral-100); color: var(--neutral-500); border-color: var(--neutral-200); }
    .pill-converted { background: var(--green-100); color: var(--green-700); border-color: var(--green-200); }

    .wl-body { padding: 16px 20px; }

    .abox {
        display: flex; gap: 9px; align-items: flex-start;
        padding: 10px 14px; border-radius: 10px; border: 1px solid;
        font-size: .77rem; margin-bottom: 12px; line-height: 1.55;
    }
    .abox-blue  { background: var(--blue-pale); border-color: var(--blue-line); color: #1e3a5f; }
    .abox-amber { background: var(--amber-pale); border-color: var(--amber-line); color: #78350f; }
    .abox-green { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
    .abox-red   { background: var(--red-pale); border-color: var(--red-line); color: #7f1d1d; }
    .abox-gray  { background: var(--neutral-50); border-color: var(--neutral-200); color: var(--neutral-600); }

    .prog-wrap { margin-bottom: 14px; }
    .prog-hd {
        display: flex; justify-content: space-between;
        font-size: .68rem; font-weight: 600; color: var(--neutral-400); margin-bottom: 5px;
    }
    .prog-hd .ok { color: var(--green-600); }
    .prog-track { height: 6px; border-radius: var(--r-pill); background: var(--neutral-100); overflow: hidden; }
    .prog-fill {
        height: 100%; border-radius: var(--r-pill);
        background: linear-gradient(90deg, var(--green-600), var(--green-400));
        transition: width .5s ease;
    }
    .prog-fill.partial { background: linear-gradient(90deg, #d97706, var(--amber)); }

    .inv-box {
        border: 1px solid var(--neutral-200); border-radius: 10px;
        overflow: hidden; margin-bottom: 12px;
    }
    .inv-box-head {
        padding: 7px 14px; background: var(--green-50);
        border-bottom: 1px solid var(--green-100);
        font-size: .63rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--neutral-500); display: flex; align-items: center; gap: 6px;
    }
    .inv-box-head i { color: var(--green-500); }

    .inv-row {
        display: grid; grid-template-columns: auto 1fr auto auto auto;
        align-items: center; gap: 10px;
        padding: 10px 14px; border-bottom: 1px solid var(--neutral-100);
        font-size: .77rem; transition: background .12s;
    }
    .inv-row:last-child { border-bottom: none; }
    .inv-row:hover { background: var(--green-50); }

    .inv-type-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: var(--r-pill);
        font-size: .6rem; font-weight: 800; border: 1px solid; white-space: nowrap;
    }
    .type-dp   { background: var(--amber-pale); color: #92400e; border-color: var(--amber-line); }
    .type-sisa { background: var(--blue-pale); color: var(--blue); border-color: var(--blue-line); }

    .inv-info { min-width: 0; }
    .inv-num  { font-family: monospace; font-size: .63rem; color: var(--neutral-400); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .inv-due  { font-size: .65rem; color: var(--neutral-500); white-space: nowrap; }

    .inv-amt { font-family: 'Fraunces', serif; font-size: .9rem; font-weight: 700; white-space: nowrap; }
    .amt-ov   { color: var(--red); }
    .amt-unp  { color: #d97706; }
    .amt-pv   { color: var(--blue); }
    .amt-paid { color: var(--green-600); }

    .st-badge { display: inline-block; padding: 2px 7px; border-radius: 5px; font-size: .59rem; font-weight: 800; white-space: nowrap; }
    .st-ov   { background: var(--red-pale); color: var(--red); }
    .st-unp  { background: var(--amber-pale); color: #92400e; }
    .st-pv   { background: var(--blue-pale); color: var(--blue); }
    .st-paid { background: var(--green-100); color: var(--green-700); }

    .info-bar {
        display: flex; flex-wrap: wrap; gap: 10px;
        font-size: .68rem; color: var(--neutral-400); margin-top: 8px;
    }
    .info-bar span { display: inline-flex; align-items: center; gap: 4px; }

    .wl-foot {
        padding: 12px 20px; background: var(--green-50);
        border-top: 1px solid var(--green-100);
        display: flex; flex-wrap: wrap; gap: 7px; align-items: center;
    }

    .btn {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px; border-radius: 9px;
        font-size: .74rem; font-weight: 700; border: none;
        cursor: pointer; text-decoration: none;
        transition: all .18s; white-space: nowrap;
        font-family: 'Instrument Sans', sans-serif;
    }
    .btn-green      { background: linear-gradient(145deg, var(--green-500), var(--green-400)); color: #fff; box-shadow: 0 2px 8px rgba(42,179,107,.28); }
    .btn-green:hover { background: linear-gradient(145deg, var(--green-600), var(--green-500)); color: #fff; transform: translateY(-1px); }
    .btn-amber      { background: var(--amber); color: #fff; }
    .btn-amber:hover { background: #d97706; color: #fff; }
    .btn-ol         { background: transparent; border: 1.5px solid var(--neutral-200); color: var(--neutral-600); }
    .btn-ol:hover   { border-color: var(--green-400); color: var(--green-600); background: var(--green-50); }
    .btn-red-o      { background: transparent; border: 1.5px solid var(--red-line); color: var(--red); }
    .btn-red-o:hover { background: var(--red-pale); }
    .btn-sm         { padding: 4px 10px; font-size: .67rem; border-radius: 7px; }

    .empty {
        text-align: center; padding: 56px 24px;
        background: var(--neutral-0); border-radius: var(--r-card);
        border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    }
    .empty-ico {
        width: 56px; height: 56px; border-radius: 14px;
        background: var(--green-100); display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: var(--green-500); margin: 0 auto 14px;
    }
    .empty-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--neutral-700); margin-bottom: 6px; }
    .empty-sub   { font-size: .78rem; color: var(--neutral-400); max-width: 260px; margin: 0 auto 18px; line-height: 1.6; }

    .modal-content { border-radius: 16px !important; border: none !important; box-shadow: var(--shadow-lg) !important; }

    @media (max-width: 580px) {
        .page { padding: 16px 14px 56px; }
        .hero-banner { padding: 22px 18px; }
        .inv-row { grid-template-columns: auto 1fr auto auto; gap: 7px; }
        .inv-due { display: none; }
        .wl-head { gap: 8px; }
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.prospective-sidebar')

<div class="page">

@foreach(['success','warning','error','info'] as $t)
    @if(session($t))
        @php
            $c = [
                'success' => ['var(--green-50)', 'var(--green-200)', 'check-circle-fill'],
                'warning' => ['var(--amber-pale)', 'var(--amber-line)', 'exclamation-triangle-fill'],
                'error'   => ['var(--red-pale)', 'var(--red-line)', 'x-circle-fill'],
                'info'    => ['var(--blue-pale)', 'var(--blue-line)', 'info-circle-fill'],
            ][$t];
        @endphp
        <div class="flash" style="background:{{ $c[0] }};border-color:{{ $c[1] }}">
            <i class="bi bi-{{ $c[2] }} flex-shrink-0" style="margin-top:1px"></i>
            <div>{!! session($t) !!}</div>
        </div>
    @endif
@endforeach

@php
    $cntActive   = $waitingLists->whereIn('status', ['waiting', 'notified'])->count();
    $cntNotified = $waitingLists->where('status', 'notified')->count();
    $cntDpBelum  = 0;
    foreach ($waitingLists->whereIn('status', ['waiting', 'notified']) as $_wl) {
        if (! ($invoicesPerWl[$_wl->id]['dp_is_paid'] ?? false)) {
            $cntDpBelum++;
        }
    }
@endphp

<div class="hero-banner">
    <div class="hero-banner-dots"></div>
    <div class="hero-content">
        <div class="hero-eyebrow">
            <span class="dot"></span>
            Detail Advanced Booking
        </div>
        <h1 class="hero-title">Waiting List <em>Saya</em></h1>
        <p class="hero-sub">Pantau posisi, status kamar, dan tagihan dalam satu halaman</p>
        <div class="hero-stats">
            <span class="hstat"><i class="bi bi-list-ol"></i>{{ $cntActive }} posisi aktif</span>
            @if($cntNotified)
                <span class="hstat s-notif"><i class="bi bi-bell-fill"></i>{{ $cntNotified }} kamar tersedia</span>
            @endif
            @if($cntDpBelum)
                <span class="hstat s-warn"><i class="bi bi-exclamation-triangle-fill"></i>{{ $cntDpBelum }} DP belum lunas</span>
            @endif
        </div>
    </div>
</div>

@forelse($waitingLists as $wl)
    @php
        $isActive  = in_array($wl->status, ['waiting', 'notified']);
        $inv       = $invoicesPerWl[$wl->id] ?? [];

        $dpInv     = $inv['dp']              ?? null;
        $sisaInv   = $inv['remaining']       ?? null;
        $dpIsPaid  = $inv['dp_is_paid']      ?? false;
        $totalPaid = $inv['total_paid']       ?? 0;
        $allInv    = $inv['all']             ?? collect();

        $roomPrice  = $wl->room?->price ?? 0;
        $duration   = $wl->booking?->duration_months ?? 1;
        $totalSewa  = $inv['total_sewa']       ?? ($roomPrice * $duration);
        $dpAmount   = $inv['dp_amount']        ?? 0;
        $sisaAmount = $inv['sisa_amount']      ?? 0;
        $remaining  = $inv['remaining_amount'] ?? 0;

        $pct        = $totalSewa > 0 ? min(100, round($totalPaid / $totalSewa * 100)) : 0;
        $penalty    = $dpIsPaid ? (int) round($dpAmount * 0.10) : 0;
        $activeInv  = $activeInvoices[$wl->id] ?? null;
    @endphp

    <div class="wl-card {{ $wl->status }}">
        <div class="wl-head">
            <div>
                <div class="room-name">
                    <i class="bi bi-door-closed me-1" style="color:var(--green-500);font-size:.85rem"></i>
                    {{ $wl->room?->room_name ?? 'Kamar ' . $wl->room?->room_number }}
                </div>
                <div class="room-meta">
                    <span><i class="bi bi-hash"></i>No.{{ $wl->room?->room_number }}</span>
                    @if($wl->room?->floor)
                        <span><i class="bi bi-layers"></i>Lt.{{ $wl->room->floor }}</span>
                    @endif
                    @if($wl->booking)
                        <span style="font-family:monospace;font-size:.65rem;color:var(--neutral-500)">{{ $wl->booking->booking_code }}</span>
                    @endif
                </div>
            </div>
            <div class="head-r">
                <div class="price-tag">Rp {{ number_format($roomPrice, 0, ',', '.') }}<small>/bln</small></div>
                <div class="pills">
                    <span class="pill pill-pos"><i class="bi bi-hash"></i>#{{ $wl->position }}</span>
                    <span class="pill pill-{{ $wl->status }}">
                        @if($wl->status === 'waiting') Menunggu
                        @elseif($wl->status === 'notified') Tersedia
                        @elseif($wl->status === 'cancelled') Dibatalkan
                        @else Aktif
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="wl-body">
            @if($wl->status === 'notified')
                <div class="abox abox-blue">
                    <i class="bi bi-house-check-fill flex-shrink-0" style="margin-top:1px;color:var(--blue)"></i>
                    <div>
                        <strong>Kamar Siap Ditempati.</strong>
                        Segera lunasi sisa <strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong> untuk resmi menghuni.
                        @if($wl->notified_at)
                            <span style="font-size:.67rem;opacity:.65"> &mdash; {{ $wl->notified_at->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
            @elseif($isActive && !$dpIsPaid && $dpInv)
                <div class="abox abox-amber">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0" style="margin-top:1px;color:var(--amber)"></i>
                    <div>
                        <strong>DP Belum Dibayar.</strong>
                        Bayar DP <strong>Rp {{ number_format($dpAmount, 0, ',', '.') }}</strong> untuk mengamankan posisi Anda.
                    </div>
                </div>
            @elseif($isActive && !$dpInv)
                <div class="abox abox-gray">
                    <i class="bi bi-info-circle flex-shrink-0" style="margin-top:1px"></i>
                    <div>Invoice DP belum diterbitkan. Admin akan membuat invoice setelah booking disetujui.</div>
                </div>
            @elseif($isActive && $dpIsPaid && $remaining == 0)
                <div class="abox abox-green">
                    <i class="bi bi-patch-check-fill flex-shrink-0" style="margin-top:1px;color:var(--green-600)"></i>
                    <div><strong>Semua Pembayaran Lunas.</strong> Menunggu aktivasi akun oleh admin.</div>
                </div>
            @elseif($wl->status === 'cancelled' && $wl->cancellation_reason)
                <div class="abox abox-red">
                    <i class="bi bi-x-circle-fill flex-shrink-0" style="margin-top:1px;color:var(--red)"></i>
                    <div>
                        <strong>Dibatalkan:</strong> {{ $wl->cancellation_reason }}
                        @if($wl->penalty_amount)
                            &middot; Denda: <strong>Rp {{ number_format($wl->penalty_amount, 0, ',', '.') }}</strong>
                        @endif
                    </div>
                </div>
            @endif

            <div class="prog-wrap">
                <div class="prog-hd">
                    <span>Terbayar: <strong>Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong> dari Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                    <span class="{{ $pct >= 100 ? 'ok' : '' }}">{{ $pct }}%</span>
                </div>
                <div class="prog-track">
                    <div class="prog-fill {{ !$dpIsPaid && $pct < 100 ? 'partial' : '' }}" style="width:{{ $pct }}%"></div>
                </div>
            </div>

            @if($allInv->count())
                <div class="inv-box">
                    <div class="inv-box-head">
                        <i class="bi bi-receipt"></i>Tagihan Terkait ({{ $allInv->count() }})
                    </div>

                    @foreach($allInv as $i)
                        @php
                            $isDP   = str_contains($i->invoice_number, '-DP-');
                            $isSisa = str_contains($i->invoice_number, '-SISA-');
                            $typeLbl = $isDP ? 'DP Booking' : ($isSisa ? 'Pelunasan' : 'Tagihan');

                            $isOv   = $i->status === 'overdue' || ($i->due_date?->isPast() && $i->status === 'unpaid');
                            $isPv   = $i->status === 'pending_verification';
                            $isDone = $i->status === 'paid';
                            $ac     = $isOv ? 'ov' : ($isPv ? 'pv' : ($isDone ? 'paid' : 'unp'));
                            $stLbl  = $isOv ? 'TERLAMBAT' : ($isPv ? 'DIVERIFIKASI' : ($isDone ? 'LUNAS' : 'BELUM BAYAR'));

                            $iRoute = auth()->user()->hasRole('tenant')
                                ? route('tenant.invoices.show', $i->id)
                                : route('prospective.invoices.show', $i->id);
                        @endphp
                        <div class="inv-row">
                            <div>
                                <span class="inv-type-badge {{ $isDP ? 'type-dp' : 'type-sisa' }}">
                                    <i class="bi {{ $isDP ? 'bi-wallet2' : 'bi-cash-coin' }}"></i>{{ $typeLbl }}
                                </span>
                                <div class="inv-num">{{ $i->invoice_number }}</div>
                            </div>
                            <div class="inv-info">
                                <div class="inv-due">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $i->due_date?->format('d M Y') ?? '—' }}
                                    @if($isOv)
                                        <span style="color:var(--red);font-weight:700;font-size:.62rem;margin-left:4px">
                                            +{{ $i->due_date->diffInDays(now()) }} hari telat
                                        </span>
                                    @elseif(!$isDone && !$isPv && $i->due_date && now()->diffInDays($i->due_date, false) <= 3 && now()->diffInDays($i->due_date, false) >= 0)
                                        <span style="color:#d97706;font-weight:700;font-size:.62rem;margin-left:4px">
                                            {{ now()->diffInDays($i->due_date, false) }} hari lagi
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div><span class="st-badge st-{{ $ac }}">{{ $stLbl }}</span></div>
                            <div class="inv-amt amt-{{ $ac }}">Rp {{ number_format($i->total_amount, 0, ',', '.') }}</div>
                            <div>
                                @if($isOv || (!$isDone && !$isPv))
                                    <a href="{{ $iRoute }}" class="btn btn-amber btn-sm">
                                        <i class="bi bi-credit-card-fill"></i>Bayar
                                    </a>
                                @else
                                    <a href="{{ $iRoute }}" class="btn btn-ol btn-sm">
                                        <i class="bi bi-eye"></i>Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="font-size:.72rem;color:var(--neutral-400);margin-bottom:10px">
                    <i class="bi bi-info-circle me-1"></i>Belum ada invoice. Admin akan menerbitkan invoice DP setelah booking disetujui.
                </p>
            @endif

            <div class="info-bar">
                @if($wl->booking?->check_in_date)
                    <span><i class="bi bi-calendar-event"></i>Masuk: <strong>{{ $wl->booking->check_in_date->format('d M Y') }}</strong></span>
                @endif
                @if($duration)
                    <span><i class="bi bi-clock"></i><strong>{{ $duration }} bulan</strong></span>
                @endif
                <span><i class="bi bi-calendar-check"></i>WL sejak: <strong>{{ $wl->approved_at?->format('d M Y') ?? '—' }}</strong></span>
                <span><i class="bi bi-cash-stack"></i>Total sewa: <strong>Rp {{ number_format($totalSewa, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        @if($isActive)
            <div class="wl-foot">
                <a href="{{ route('prospective.waiting-list.show', $wl->id) }}" class="btn btn-ol">
                    <i class="bi bi-eye"></i>Detail
                </a>
                <a href="{{ route('prospective.invoices.index') }}" class="btn btn-ol">
                    <i class="bi bi-receipt"></i>Semua Tagihan
                </a>
                @if($activeInv && in_array($activeInv->status, ['unpaid', 'overdue']))
                    @php
                        $payR = auth()->user()->hasRole('tenant')
                            ? route('tenant.invoices.show', $activeInv->id)
                            : route('prospective.invoices.show', $activeInv->id);
                    @endphp
                @endif
                <button class="btn btn-red-o ms-auto" data-bs-toggle="modal" data-bs-target="#cModal{{ $wl->id }}">
                    <i class="bi bi-x-circle"></i>Batalkan
                </button>
            </div>
        @endif
    </div>

    @if($isActive)
        <div class="modal fade" id="cModal{{ $wl->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--red-pale);border-bottom:1px solid var(--red-line)">
                        <h5 class="modal-title" style="font-size:.88rem;font-weight:700;color:var(--red);font-family:'Instrument Sans',sans-serif">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Batalkan Waiting List
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:18px 22px">
                        <p style="font-size:.8rem;color:var(--neutral-600);margin-bottom:13px;font-family:'Instrument Sans',sans-serif">
                            Kamar <strong>{{ $wl->room?->room_number }}</strong> &mdash; Posisi <strong>#{{ $wl->position }}</strong>
                        </p>
                        @if($penalty > 0)
                            <div style="background:var(--red-pale);border:1px solid var(--red-line);border-radius:9px;padding:11px 14px;font-size:.77rem;color:#7f1d1d;margin-bottom:14px">
                                <strong>Denda 10%</strong> dari DP (Rp {{ number_format($dpAmount, 0, ',', '.') }}):
                                <div style="font-family:'Fraunces',serif;font-size:1rem;font-weight:700;margin-top:3px">Rp {{ number_format($penalty, 0, ',', '.') }}</div>
                                <div style="font-size:.68rem;opacity:.7;margin-top:2px">Sisa 90% diproses refund secara manual oleh admin.</div>
                            </div>
                        @else
                            <div style="background:var(--green-50);border:1px solid var(--green-200);border-radius:9px;padding:10px 14px;font-size:.77rem;color:var(--green-800);margin-bottom:14px">
                                <i class="bi bi-info-circle me-1"></i>Tidak ada denda karena DP belum dibayar.
                            </div>
                        @endif
                        <form method="POST" action="{{ route('prospective.waiting-list.cancel', $wl->id) }}" id="cf{{ $wl->id }}">
                            @csrf
                            <label style="font-size:.75rem;font-weight:700;display:block;margin-bottom:5px;font-family:'Instrument Sans',sans-serif">
                                Alasan Pembatalan <span style="color:var(--red)">*</span>
                            </label>
                            <textarea name="cancellation_reason" rows="3" required
                                style="width:100%;border:1.5px solid var(--neutral-200);border-radius:8px;padding:9px 12px;font-size:.78rem;font-family:'Instrument Sans',sans-serif;resize:none;outline:none;transition:border-color .15s"
                                onfocus="this.style.borderColor='var(--green-400)'"
                                onblur="this.style.borderColor='var(--neutral-200)'"
                                placeholder="Jelaskan alasan pembatalan Anda..."></textarea>
                        </form>
                    </div>
                    <div class="modal-footer" style="padding:11px 22px;gap:7px">
                        <button type="button" class="btn btn-ol" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" form="cf{{ $wl->id }}" class="btn" style="background:var(--red);color:#fff;font-size:.74rem">
                            <i class="bi bi-x-circle-fill"></i>
                            Ya, Batalkan{{ $penalty > 0 ? ' (Denda Rp ' . number_format($penalty, 0, ',', '.') . ')' : '' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@empty
    <div class="empty">
        <div class="empty-ico"><i class="bi bi-list-ol"></i></div>
        <div class="empty-title">Belum Ada Waiting List</div>
        <div class="empty-sub">
            Lakukan advance booking pada kamar yang sedang terisi untuk masuk dalam antrian.
        </div>
        <a href="{{ route('home') }}#kamar" class="btn btn-green">
            <i class="bi bi-house-door-fill"></i>Cari Kamar
        </a>
    </div>
@endforelse

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>