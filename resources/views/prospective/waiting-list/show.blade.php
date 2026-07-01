<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Waiting List — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
    .page-wrap { min-height: 100vh; display: flex; flex-direction: column; }
    .page-main { flex: 1; padding: 32px 0 64px; width: 100%; }
    .page-inner { max-width: 1100px; margin: 0 auto; padding: 0 24px; width: 100%; }
    .flash {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 13px 16px; border-radius: 10px;
        margin-bottom: 12px; font-size: .82rem; font-weight: 500;
        border: 1px solid; line-height: 1.55;
    }
    .flash i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }
    .breadbar {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 24px; flex-wrap: wrap;
    }
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 9px;
        border: 1px solid var(--green-200);
        color: var(--green-700); background: var(--neutral-0);
        text-decoration: none; font-size: .76rem; font-weight: 600;
        transition: all .15s;
    }
    .btn-back:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-800); }
    .page-title {
        font-family: 'Fraunces', serif;
        font-size: 1.25rem; font-weight: 700;
        color: var(--neutral-900); letter-spacing: -.02em;
    }
    .status-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 12px; border-radius: 100px;
        font-size: .65rem; font-weight: 700; border: 1px solid;
    }
    .o-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 16px;
    }
    .o-card-header {
        padding: 14px 20px;
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
    .o-card-body { padding: 16px 20px; }
    .dr {
        display: flex; justify-content: space-between;
        align-items: center; padding: 9px 0;
        border-bottom: 1px solid var(--neutral-100);
        font-size: .8rem;
    }
    .dr:last-child { border-bottom: none; }
    .dr .lbl { color: var(--neutral-500); }
    .dr .val  { font-weight: 600; color: var(--neutral-900); }
    .alert-strip {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 11px 14px; border-radius: 10px;
        font-size: .8rem; font-weight: 500;
        border: 1px solid; line-height: 1.55; margin-bottom: 14px;
    }
    .alert-strip i { flex-shrink: 0; margin-top: 2px; }
    .pay-summary {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 12px; margin-bottom: 16px;
    }
    @media (max-width: 480px) { .pay-summary { grid-template-columns: 1fr; } }
    .pay-box {
        border-radius: 12px; padding: 14px 16px; border: 1px solid;
    }
    .pay-box-label {
        font-size: .6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px;
    }
    .pay-box-num {
        font-family: 'Fraunces', serif;
        font-size: 1.05rem; font-weight: 700;
        letter-spacing: -.02em;
    }
    .pay-box-status {
        display: inline-block; margin-top: 6px;
        padding: 2px 8px; border-radius: 6px;
        font-size: .6rem; font-weight: 700; border: 1px solid;
    }
    .prog-wrap { margin-bottom: 14px; }
    .prog-hd {
        display: flex; justify-content: space-between;
        font-size: .67rem; font-weight: 600;
        color: var(--neutral-400); margin-bottom: 5px;
    }
    .prog-hd .ok { color: var(--green-600); }
    .prog-track {
        height: 7px; border-radius: 100px;
        background: var(--neutral-200); overflow: hidden;
    }
    .prog-fill {
        height: 100%; border-radius: 100px;
        background: linear-gradient(90deg, var(--green-600), var(--green-400));
        transition: width .6s;
    }
    .prog-fill.partial {
        background: linear-gradient(90deg, var(--amber), #fde68a);
    }
    .inv-item {
        display: flex; align-items: flex-start;
        gap: 12px; padding: 12px 0;
        border-bottom: 1px solid var(--neutral-100);
    }
    .inv-item:last-child { border-bottom: none; }
    .inv-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 8px; border-radius: 100px;
        font-size: .6rem; font-weight: 700; border: 1px solid; margin-bottom: 4px;
    }
    .inv-code { font-family: monospace; font-size: .65rem; color: var(--neutral-400); }
    .inv-due  { font-size: .68rem; color: var(--neutral-500); }
    .inv-st {
        display: inline-block; padding: 2px 8px;
        border-radius: 6px; font-size: .6rem; font-weight: 700;
    }
    .tl { display: flex; flex-direction: column; }
    .tl-item { display: flex; gap: 0; align-items: stretch; }
    .tl-col {
        display: flex; flex-direction: column;
        align-items: center; width: 30px; flex-shrink: 0;
    }
    .tl-dot {
        width: 24px; height: 24px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 700; flex-shrink: 0;
        z-index: 1; border: 2px solid #fff;
    }
    .tl-dot.done { background: var(--green-600); color: #fff; box-shadow: 0 0 0 2px var(--green-200); }
    .tl-dot.fail { background: var(--red); color: #fff; box-shadow: 0 0 0 2px #fecaca; }
    .tl-dot.wait { background: var(--neutral-200); color: var(--neutral-400); }
    .tl-dot.act  { background: var(--blue); color: #fff; box-shadow: 0 0 0 2px #bfdbfe; }
    .tl-line {
        width: 2px; flex: 1; min-height: 16px;
        background: var(--neutral-200); margin: 0 auto;
    }
    .tl-line.done { background: var(--green-300); }
    .tl-content { flex: 1; padding: 1px 0 18px 12px; }
    .tl-title { font-weight: 600; font-size: .8rem; color: var(--neutral-800); }
    .tl-time  { font-size: .67rem; color: var(--neutral-400); margin-top: 2px; }
    .tl-desc  { font-size: .67rem; color: var(--neutral-500); margin-top: 2px; }
    .ir {
        display: flex; justify-content: space-between;
        align-items: center; padding: 8px 0;
        border-bottom: 1px solid var(--neutral-100); font-size: .8rem;
    }
    .ir:last-child { border-bottom: none; }
    .ir .lbl { color: var(--neutral-500); }
    .ir .val  { font-weight: 600; }
    .action-panel-head {
        background: linear-gradient(135deg, var(--green-900), var(--green-700), var(--green-500));
        padding: 20px 22px; position: relative; overflow: hidden;
    }
    .action-panel-head::before {
        content: ''; position: absolute; right: -20px; top: -20px;
        width: 100px; height: 100px; border-radius: 50%;
        border: 18px solid rgba(255,255,255,.07);
    }
    .aph-label {
        font-size: .63rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: .09em; color: rgba(255,255,255,.6);
        position: relative; z-index: 1; margin-bottom: 4px;
    }
    .aph-name {
        font-family: 'Fraunces', serif;
        font-size: 1.4rem; font-weight: 700; color: #fff;
        position: relative; z-index: 1; line-height: 1.15;
        letter-spacing: -.02em;
    }
    .aph-sub {
        font-size: .72rem; color: rgba(255,255,255,.55);
        position: relative; z-index: 1; margin-top: 4px;
    }
    .btn-pay {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 12px; border-radius: 10px;
        font-family: 'Instrument Sans', sans-serif;
        font-size: .85rem; font-weight: 600;
        cursor: pointer; transition: all .2s; text-decoration: none;
        border: none;
    }
    .btn-pay-green {
        background: var(--green-600); color: #fff;
        box-shadow: 0 3px 12px rgba(29,148,88,.28);
    }
    .btn-pay-green:hover { background: var(--green-700); color: #fff; transform: translateY(-1px); }
    .btn-pay-amber {
        background: var(--amber); color: #fff;
        box-shadow: 0 3px 12px rgba(245,158,11,.28);
    }
    .btn-pay-amber:hover { background: #d97706; color: #fff; transform: translateY(-1px); }
    .btn-ol {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px; border-radius: 9px;
        font-size: .73rem; font-weight: 600;
        background: transparent; border: 1px solid var(--green-200);
        color: var(--green-700); text-decoration: none;
        transition: all .15s; cursor: pointer;
        font-family: 'Instrument Sans', sans-serif;
    }
    .btn-ol:hover { background: var(--green-50); border-color: var(--green-300); }
    .btn-danger-ol {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px; border-radius: 9px;
        font-size: .73rem; font-weight: 600;
        background: transparent; border: 1px solid #fecaca;
        color: var(--red); cursor: pointer; transition: all .15s;
        font-family: 'Instrument Sans', sans-serif;
    }
    .btn-danger-ol:hover { background: var(--red-pale); }
    .cancel-zone {
        border: 1px solid #fecaca; border-radius: 14px;
        overflow: hidden; margin-bottom: 16px;
    }
    .cancel-zone-head {
        padding: 11px 18px; background: var(--red-pale);
        border-bottom: 1px solid #fecaca;
        font-size: .78rem; font-weight: 600; color: var(--red);
        display: flex; align-items: center; gap: 7px;
    }
    .cancel-zone-body { padding: 14px 18px; }
    .sticky-col { position: sticky; top: 20px; }
    @media (max-width: 768px) {
        .page-inner { padding: 0 14px; }
        .sticky-col  { position: static; }
    }
    @media (min-width: 900px) {
        .page-inner { padding: 0 40px; }
        .page-main  { padding: 40px 0 64px; }
    }

    .hero-banner {
        position: relative; overflow: hidden; border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 36px 32px; margin-bottom: 24px;
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
    .hero-left  { position: relative; z-index: 1; }
    .hero-right { position: relative; z-index: 1; flex-shrink: 0; }
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
    .btn-hero {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px; font-size: .82rem; font-weight: 600;
        color: var(--green-900); background: rgba(255,255,255,.92);
        border: none; border-radius: 10px; cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,.12); transition: all .2s;
        text-decoration: none; white-space: nowrap;
    }
    .btn-hero:hover { background: #fff; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(0,0,0,.16); color: var(--green-900); }
    @media (max-width: 580px) {
        .hero-banner { flex-direction: column; padding: 22px 20px; }
        .hero-right  { width: 100%; }
        .btn-hero    { width: 100%; justify-content: center; }
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.prospective-sidebar')

<div class="page-wrap">
<main class="page-main">
<div class="page-inner">

    @foreach(['success','warning','error','info'] as $t)
    @if(session($t))
    @php
        $c = [
            'success' => ['var(--green-50)',   'var(--green-200)', 'check-circle-fill',         'var(--green-600)'],
            'warning' => ['var(--amber-pale)', '#fde68a',          'exclamation-triangle-fill', 'var(--amber)'],
            'error'   => ['var(--red-pale)',   '#fecaca',          'x-circle-fill',             'var(--red)'],
            'info'    => ['var(--blue-pale)',   '#bfdbfe',          'info-circle-fill',          'var(--blue)'],
        ][$t];
    @endphp
    <div class="flash" style="background:{{ $c[0] }};border-color:{{ $c[1] }};color:{{ $c[3] }}">
        <i class="bi bi-{{ $c[2] }}"></i>
        <div>{!! session($t) !!}</div>
    </div>
    @endif
    @endforeach

    @if(request('payment') === 'success')
    <div class="flash" style="background:var(--green-50);border-color:var(--green-200);color:var(--green-700)">
        <i class="bi bi-check-circle-fill"></i>
        <div>Pembayaran berhasil diproses.</div>
    </div>
    @elseif(request('payment') === 'pending')
    <div class="flash" style="background:var(--amber-pale);border-color:#fde68a;color:var(--amber)">
        <i class="bi bi-clock-fill"></i>
        <div>Instruksi pembayaran telah dibuat. Selesaikan transfer sesuai instruksi.</div>
    </div>
    @endif

    @php
        $sc = [
            'waiting'   => ['var(--amber-pale)', '#92400e',       '#fde68a',          'Menunggu'],
            'notified'  => ['var(--blue-pale)',   'var(--blue)',   '#bfdbfe',          'Tersedia'],
            'cancelled' => ['var(--neutral-100)', 'var(--neutral-600)', 'var(--neutral-200)', 'Dibatalkan'],
            'converted' => ['var(--green-50)',    'var(--green-700)', 'var(--green-200)',   'Aktif'],
        ][$wl->status] ?? ['var(--neutral-100)', 'var(--neutral-600)', 'var(--neutral-200)', ucfirst($wl->status)];
    @endphp

    <div style="margin-bottom: 16px;">
        <a href="{{ route('prospective.waiting-list.index') }}" class="btn-hero"
        style="background:var(--neutral-0);color:var(--neutral-600);border:1px solid var(--neutral-200);box-shadow:var(--shadow-sm);">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('prospective.waiting-list.index') }}" style="color:inherit;text-decoration:none">Waiting List</a>
                &nbsp;/&nbsp; Detail
            </div>
            <h1 class="hero-title">Detail <em>Waiting List</em></h1>
            <p class="hero-sub">
                Kamar {{ $wl->room?->room_number }} — {{ $wl->room?->room_name ?? 'Posisi #' . $wl->position }}
                &nbsp;·&nbsp; Posisi #{{ $wl->position }}
            </p>
        </div>
        <div class="hero-right">
            <span style="
                display: inline-flex; align-items: center; gap: 6px;
                padding: 8px 18px; border-radius: 10px;
                background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
                font-size: .78rem; font-weight: 600; color: #fff;
            ">
                @php
                    $statusIcon = match($wl->status) {
                        'waiting'   => 'bi-hourglass-split',
                        'notified'  => 'bi-house-check-fill',
                        'converted' => 'bi-check-circle-fill',
                        'cancelled' => 'bi-x-circle-fill',
                        default     => 'bi-circle',
                    };
                @endphp
                <i class="bi {{ $statusIcon }}"></i>
                {{ $sc[3] }}
            </span>
        </div>
    </div>

    <div class="row g-3">

    <div class="col-lg-7">

        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title">
                    <i class="bi bi-door-closed-fill"></i>Info Kamar &amp; Booking
                </span>
                <span style="font-family:monospace;font-size:.68rem;color:var(--neutral-400)">
                    {{ $wl->booking?->booking_code ?? '-' }}
                </span>
            </div>
            <div class="o-card-body">
                <div class="dr">
                    <span class="lbl">Kamar</span>
                    <span class="val">{{ $wl->room?->room_number }} — {{ $wl->room?->room_name }}</span>
                </div>
                <div class="dr">
                    <span class="lbl">Lantai / Ukuran</span>
                    <span class="val">
                        {{ $wl->room?->floor ? 'Lt.' . $wl->room->floor : '—' }}
                        {{ $wl->room?->size ? '· ' . $wl->room->size : '' }}
                    </span>
                </div>
                <div class="dr">
                    <span class="lbl">Posisi Antrian</span>
                    <span class="val">#{{ $wl->position }}</span>
                </div>
                <div class="dr">
                    <span class="lbl">Rencana Masuk</span>
                    <span class="val">{{ $wl->booking?->check_in_date?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="dr">
                    <span class="lbl">Durasi Sewa</span>
                    <span class="val">{{ $duration }} bulan</span>
                </div>
                <div class="dr">
                    <span class="lbl">Masuk Waiting List</span>
                    <span class="val">{{ $wl->approved_at?->format('d M Y') ?? '—' }}</span>
                </div>
                @if($wl->notified_at)
                <div class="dr">
                    <span class="lbl">Dinotifikasi</span>
                    <span class="val">{{ $wl->notified_at->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title">
                    <i class="bi bi-cash-stack"></i>Rincian Pembayaran
                </span>
                @if($remaining == 0 && $totalPaid > 0)
                <span style="font-size:.62rem;font-weight:700;padding:3px 10px;border-radius:100px;background:var(--green-100);color:var(--green-700);border:1px solid var(--green-200)">
                    Lunas
                </span>
                @endif
            </div>
            <div class="o-card-body">

                @if($wl->status === 'notified')
                <div class="alert-strip" style="background:var(--blue-pale);border-color:#bfdbfe;color:#1e3a5f">
                    <i class="bi bi-house-check-fill"></i>
                    <div>Kamar tersedia. Segera lunasi sisa tagihan sebesar <strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong>.</div>
                </div>
                @elseif($dpInvoice && !$dpIsPaid)
                <div class="alert-strip" style="background:var(--amber-pale);border-color:#fde68a;color:#78350f">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>DP belum dibayar. Bayar DP untuk mengamankan posisi antrian.</div>
                </div>
                @endif

                <div class="pay-summary">
                    <div class="pay-box" style="background:var(--amber-pale);border-color:#fde68a">
                        <div class="pay-box-label" style="color:#92400e">DP Booking</div>
                        <div class="pay-box-num" style="color:#92400e">Rp {{ number_format($dpAmount, 0, ',', '.') }}</div>
                        @if($dpInvoice)
                        @php
                            $dptag = match($dpInvoice->status) {
                                'paid'                 => ['var(--green-50)',   'var(--green-700)',   'var(--green-200)', 'Lunas'],
                                'pending_verification' => ['var(--blue-pale)',  'var(--blue)',         '#bfdbfe',          'Diverifikasi'],
                                'overdue'              => ['var(--red-pale)',   'var(--red)',          '#fecaca',          'Terlambat'],
                                default                => ['var(--amber-pale)', '#92400e',             '#fde68a',          'Belum Bayar'],
                            };
                        @endphp
                        <span class="pay-box-status" style="background:{{ $dptag[0] }};color:{{ $dptag[1] }};border-color:{{ $dptag[2] }}">
                            {{ $dptag[3] }}
                        </span>
                        @else
                        <span class="pay-box-status" style="background:var(--amber-pale);color:#92400e;border-color:#fde68a">Belum Ada Invoice</span>
                        @endif
                    </div>
                    <div class="pay-box" style="background:var(--blue-pale);border-color:#bfdbfe">
                        <div class="pay-box-label" style="color:var(--blue)">Pelunasan / Sisa</div>
                        <div class="pay-box-num" style="color:var(--blue)">Rp {{ number_format($sisaAmount, 0, ',', '.') }}</div>
                        @if($remainingInvoice)
                        @php
                            $sistag = match($remainingInvoice->status) {
                                'paid'                 => ['var(--green-50)',   'var(--green-700)',   'var(--green-200)', 'Lunas'],
                                'pending_verification' => ['var(--blue-pale)',  'var(--blue)',         '#bfdbfe',          'Diverifikasi'],
                                'overdue'              => ['var(--red-pale)',   'var(--red)',          '#fecaca',          'Terlambat'],
                                default                => ['var(--amber-pale)', '#92400e',             '#fde68a',          'Belum Bayar'],
                            };
                        @endphp
                        <span class="pay-box-status" style="background:{{ $sistag[0] }};color:{{ $sistag[1] }};border-color:{{ $sistag[2] }}">
                            {{ $sistag[3] }}
                        </span>
                        @else
                        <span class="pay-box-status" style="background:var(--neutral-100);color:var(--neutral-500);border-color:var(--neutral-200)">Belum Diterbitkan</span>
                        @endif
                    </div>
                </div>

                @php
                    $paymentMethod = $wl->booking?->payment_method ?? 'lump_sum';
                    $bulanPertama  = $roomPrice + $serviceAmount;
                    $progDenom = $paymentMethod === 'monthly' ? $bulanPertama : $totalSewa;
                    $progPaid  = $paymentMethod === 'monthly' ? min($totalPaid, $bulanPertama) : $totalPaid;
                    $pct = $progDenom > 0 ? min(100, round($progPaid / $progDenom * 100)) : 0;
                    $progLabel = $paymentMethod === 'monthly'
                        ? 'Bulan pertama: Rp ' . number_format($progPaid, 0, ',', '.') . ' / Rp ' . number_format($bulanPertama, 0, ',', '.')
                        : 'Terbayar: Rp ' . number_format($totalPaid, 0, ',', '.') . ' / Rp ' . number_format($totalSewa, 0, ',', '.');
                @endphp
                <div class="prog-wrap">
                    <div class="prog-hd">
                        <span>{!! $progLabel !!}</span>
                        <span class="{{ $pct >= 100 ? 'ok' : '' }}">{{ $pct }}%</span>
                    </div>
                    <div class="prog-track">
                        <div class="prog-fill {{ !$dpIsPaid && $pct < 100 ? 'partial' : '' }}" style="width:{{ $pct }}%"></div>
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0 0;border-top:1px solid var(--neutral-200);font-weight:600;font-size:.82rem">
                    @if($paymentMethod === 'monthly')
                        <span>Total Sewa Estimasi ({{ $duration }} bln)</span>
                        <span style="font-family:'Fraunces',serif;color:var(--green-700)">Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                    @else
                        <span>Total Sewa ({{ $duration }} bln)</span>
                        <span style="font-family:'Fraunces',serif;color:var(--green-700)">Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                    @endif
                </div>
                @if($paymentMethod === 'monthly')
                <div style="font-size:.67rem;color:var(--neutral-400);margin-top:4px">
                    <i class="bi bi-info-circle"></i>&nbsp;Tagihan bulan berikutnya diterbitkan otomatis saat status aktif.
                </div>
                @endif

                @if($dpIsPaid)
                <div style="margin-top:12px;padding:10px 13px;border-radius:10px;background:var(--green-50);border:1px solid var(--green-200);font-size:.74rem;color:var(--green-700)">
                    <i class="bi bi-info-circle"></i>&nbsp;Pembatalan setelah DP: denda <strong>10%</strong> dari DP
                    (Rp {{ number_format(round($dpAmount * 0.10), 0, ',', '.') }}).
                </div>
                @endif

            </div>
        </div>

        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title">
                    <i class="bi bi-receipt"></i>Riwayat Invoice
                </span>
                <a href="{{ route('prospective.invoices.index') }}" class="btn-ol" style="font-size:.66rem;padding:3px 10px">
                    <i class="bi bi-list-ul"></i>Semua
                </a>
            </div>
            <div style="padding:0 20px">
                @forelse($invoices->sortByDesc('created_at') as $inv)
                @php
                    $isDP   = str_contains($inv->invoice_number, '-DP-');
                    $isSisa = str_contains($inv->invoice_number, '-SISA-');
                    $isOv   = $inv->status === 'overdue' || ($inv->due_date?->isPast() && $inv->status === 'unpaid');
                    $isPv   = $inv->status === 'pending_verification';
                    $isDone = $inv->status === 'paid';
                    $ac     = $isOv ? 'ov' : ($isPv ? 'pv' : ($isDone ? 'paid' : 'unp'));
                    $stLbl  = $isOv ? 'Terlambat' : ($isPv ? 'Diverifikasi' : ($isDone ? 'Lunas' : 'Belum Bayar'));
                    $stStyle = match($ac) {
                        'ov'   => 'background:var(--red-pale);color:var(--red)',
                        'pv'   => 'background:var(--blue-pale);color:var(--blue)',
                        'paid' => 'background:var(--green-50);color:var(--green-700)',
                        default => 'background:var(--amber-pale);color:#92400e',
                    };
                    $iRoute = auth()->user()->hasRole('tenant')
                        ? route('tenant.invoices.show', $inv->id)
                        : route('prospective.invoices.show', $inv->id);
                @endphp
                <div class="inv-item">
                    <div style="flex:1;min-width:0">
                        <span class="inv-badge" style="{{ $isDP ? 'background:var(--amber-pale);color:#92400e;border-color:#fde68a' : 'background:var(--blue-pale);color:var(--blue);border-color:#bfdbfe' }}">
                            <i class="bi {{ $isDP ? 'bi-wallet2' : 'bi-cash-coin' }}"></i>
                            {{ $isDP ? 'DP Booking' : ($isSisa ? 'Pelunasan' : 'Tagihan') }}
                        </span>
                        <div class="inv-code">{{ $inv->invoice_number }}</div>
                        <div class="inv-due">
                            <i class="bi bi-calendar3"></i>&nbsp;Jatuh tempo: {{ $inv->due_date?->format('d M Y') ?? '—' }}
                            @if($isDone && $inv->paid_at)
                                &middot; Dibayar: {{ $inv->paid_at->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-family:'Fraunces',serif;font-size:.95rem;font-weight:700;color:{{ $ac === 'paid' ? 'var(--green-600)' : ($ac === 'ov' ? 'var(--red)' : ($ac === 'pv' ? 'var(--blue)' : '#92400e')) }}">
                            Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                        </div>
                        <span class="inv-st" style="{{ $stStyle }};margin-top:4px;display:inline-block">{{ $stLbl }}</span>
                    </div>
                    <div style="flex-shrink:0;margin-left:10px">
                        @if($isOv || (!$isDone && !$isPv))
                        <a href="{{ $iRoute }}" class="btn-ol" style="font-size:.68rem;padding:4px 10px;color:#92400e;border-color:#fde68a">
                            <i class="bi bi-credit-card-fill"></i>Bayar
                        </a>
                        @else
                        <a href="{{ $iRoute }}" class="btn-ol" style="font-size:.68rem;padding:4px 10px">
                            <i class="bi bi-eye"></i>Lihat
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:28px 0;font-size:.78rem;color:var(--neutral-400)">
                    <i class="bi bi-receipt"></i>&nbsp;Belum ada invoice. Admin akan menerbitkan segera.
                </div>
                @endforelse
            </div>
        </div>

        <div class="o-card">
            <div class="o-card-header">
                <span class="o-card-header-title"><i class="bi bi-clock-history"></i>Timeline Proses</span>
            </div>
            <div class="o-card-body">
                @php
                    $steps = [
                        [
                            'state' => $wl->booking?->created_at ? 'done' : 'wait',
                            'label' => 'Advance Booking Dikirim',
                            'time'  => $wl->booking?->created_at?->format('d M Y'),
                            'desc'  => 'Permintaan diterima sistem',
                        ],
                        [
                            'state' => $wl->approved_at ? 'done' : 'wait',
                            'label' => 'Disetujui & Masuk Waiting List',
                            'time'  => $wl->approved_at?->format('d M Y') ?? 'Menunggu persetujuan admin',
                            'desc'  => 'Posisi #' . $wl->position . ' dikonfirmasi',
                        ],
                        [
                            'state' => $dpIsPaid ? 'done' : ($dpInvoice ? 'act' : 'wait'),
                            'label' => 'DP Booking Dibayar',
                            'time'  => $dpIsPaid ? ($dpInvoice->paid_at?->format('d M Y') ?? 'Sudah lunas') : 'Menunggu pembayaran DP',
                            'desc'  => 'Rp ' . number_format($dpAmount, 0, ',', '.'),
                        ],
                        [
                            'state' => $wl->notified_at ? 'done' : 'wait',
                            'label' => 'Kamar Tersedia — Notifikasi Dikirim',
                            'time'  => $wl->notified_at?->format('d M Y') ?? 'Menunggu penghuni lama keluar',
                            'desc'  => 'Penghuni aktif telah keluar',
                        ],
                        [
                            'state' => $wl->converted_at ? 'done' : ($wl->cancelled_at ? 'fail' : 'wait'),
                            'label' => $wl->converted_at
                                ? 'Resmi Menjadi Penghuni Aktif'
                                : ($wl->cancelled_at ? 'Posisi Dibatalkan' : 'Lunasi Tagihan untuk Aktif'),
                            'time'  => $wl->converted_at?->format('d M Y')
                                ?? $wl->cancelled_at?->format('d M Y')
                                ?? 'Menunggu pelunasan',
                            'desc'  => $wl->converted_at
                                ? 'Proses aktivasi selesai.'
                                : ($wl->cancelled_at
                                    ? ($wl->cancellation_reason ?? 'Dibatalkan')
                                    : 'Bayar sisa tagihan untuk aktif'),
                        ],
                    ];
                @endphp
                <div class="tl">
                    @foreach($steps as $i => $s)
                    <div class="tl-item">
                        <div class="tl-col">
                            <div class="tl-dot {{ $s['state'] }}">
                                @if($s['state'] === 'done')
                                    <i class="bi bi-check" style="font-size:.7rem"></i>
                                @elseif($s['state'] === 'fail')
                                    <i class="bi bi-x" style="font-size:.7rem"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>
                            @if(!$loop->last)
                                <div class="tl-line {{ $s['state'] === 'done' ? 'done' : '' }}"></div>
                            @endif
                        </div>
                        <div class="tl-content">
                            <div class="tl-title">{{ $s['label'] }}</div>
                            @if($s['time'])<div class="tl-time">{{ $s['time'] }}</div>@endif
                            @if($s['desc'])<div class="tl-desc">{{ $s['desc'] }}</div>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if(in_array($wl->status, ['waiting', 'notified']))
        @php $penalty = $dpIsPaid ? (int) round($dpAmount * 0.10) : 0; @endphp
        <div class="cancel-zone">
            <div class="cancel-zone-head">
                <i class="bi bi-x-circle"></i>Batalkan Posisi Waiting List
            </div>
            <div class="cancel-zone-body">
                <p style="font-size:.78rem;color:var(--neutral-600);margin-bottom:12px">
                    @if($dpIsPaid)
                        Pembatalan setelah DP akan dikenakan denda <strong>10%</strong> dari DP
                        (Rp {{ number_format(round($dpAmount * 0.10), 0, ',', '.') }}).
                    @else
                        Belum ada DP yang terbayar — tidak ada denda pembatalan.
                    @endif
                </p>
                <button class="btn-danger-ol" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-circle"></i>Batalkan Posisi Ini
                </button>
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-5">
        <div class="sticky-col">

            <div class="o-card" style="overflow:hidden">
                <div class="action-panel-head">
                    <div class="aph-label">Kamar {{ $wl->room?->room_number }}</div>
                    <div class="aph-name">{{ $wl->room?->room_name ?? 'Kamar ' . $wl->room?->room_number }}</div>
                    <div class="aph-sub">
                        Rp {{ number_format($roomPrice, 0, ',', '.') }}/bulan
                        @if($wl->room?->floor) &middot; Lt.{{ $wl->room->floor }}@endif
                    </div>
                </div>
                <div class="o-card-body">
                    <div class="ir">
                        <span class="lbl">Posisi Antrian</span>
                        <span class="val">#{{ $wl->position }}</span>
                    </div>
                    <div class="ir">
                        <span class="lbl">Total Sewa</span>
                        <span class="val" style="color:var(--green-700);font-family:'Fraunces',serif">Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                    </div>
                    <div class="ir">
                        <span class="lbl">Sudah Terbayar</span>
                        <span class="val" style="color:var(--green-600)">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                    </div>
                    @if($remaining > 0)
                    <div class="ir">
                        <span class="lbl">Sisa Tagihan</span>
                        <span class="val" style="color:var(--blue)">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($activeInvoice && in_array($activeInvoice->status, ['unpaid', 'overdue']))
            @php
                $isDP  = str_contains($activeInvoice->invoice_number, '-DP-');
                $invR  = auth()->user()->hasRole('tenant')
                    ? route('tenant.invoices.show', $activeInvoice->id)
                    : route('prospective.invoices.show', $activeInvoice->id);
            @endphp
            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title" style="color:#92400e">
                        <i class="bi bi-exclamation-circle" style="color:var(--amber)"></i>
                        {{ $isDP ? 'DP Perlu Dibayar' : 'Pelunasan Perlu Dibayar' }}
                    </span>
                    @if($activeInvoice->isOverdue())
                    <span style="font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:100px;background:var(--red-pale);color:var(--red);border:1px solid #fecaca">
                        Terlambat
                    </span>
                    @endif
                </div>
                <div class="o-card-body" style="background:var(--amber-pale)">
                    <div style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:700;color:#92400e;letter-spacing:-.02em">
                        Rp {{ number_format($activeInvoice->total_amount, 0, ',', '.') }}
                    </div>
                    <div style="font-size:.73rem;color:var(--neutral-600);margin-top:6px">
                        <span style="font-family:monospace;font-size:.65rem">{{ $activeInvoice->invoice_number }}</span><br>
                        Jatuh tempo: <strong>{{ $activeInvoice->due_date?->format('d M Y') }}</strong>
                    </div>
                    <a href="{{ $invR }}" class="btn-pay btn-pay-amber" style="margin-top:14px">
                        <i class="bi bi-credit-card-fill"></i>Bayar Sekarang
                    </a>
                </div>
            </div>

            @elseif($activeInvoice?->status === 'pending_verification')
            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title" style="color:var(--blue)">
                        <i class="bi bi-hourglass-split" style="color:var(--blue)"></i>Menunggu Verifikasi
                    </span>
                </div>
                <div class="o-card-body" style="background:var(--blue-pale)">
                    <div style="font-weight:600;color:var(--blue);font-size:.85rem">Bukti pembayaran sedang diproses admin</div>
                    <div style="font-family:monospace;font-size:.65rem;color:var(--neutral-400);margin-top:4px">{{ $activeInvoice->invoice_number }}</div>
                    <div style="font-size:.73rem;color:var(--neutral-600);margin-top:6px">Konfirmasi dalam 1×24 jam kerja.</div>
                </div>
            </div>

            @elseif($remaining == 0 && $totalPaid > 0)
            <div class="o-card">
                <div class="o-card-body" style="text-align:center;padding:28px 20px;background:var(--green-50)">
                    <div style="font-size:2rem;margin-bottom:10px;color:var(--green-400)">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div style="font-family:'Fraunces',serif;font-weight:700;color:var(--green-700);font-size:.95rem;margin-bottom:6px;letter-spacing:-.01em">Semua Pembayaran Lunas</div>
                    <div style="font-size:.75rem;color:var(--neutral-500)">Menunggu aktivasi akun penghuni oleh admin.</div>
                </div>
            </div>
            @endif

            @if($invoices->count() > 0)
            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title"><i class="bi bi-bar-chart-fill"></i>Ringkasan Invoice</span>
                </div>
                <div class="o-card-body">
                    @php
                        $iPaid = $invoices->where('status', 'paid');
                        $iUnp  = $invoices->whereIn('status', ['unpaid', 'overdue']);
                        $iPv   = $invoices->where('status', 'pending_verification');
                    @endphp
                    @if($iPaid->count())
                    <div class="ir">
                        <span class="lbl">Lunas ({{ $iPaid->count() }})</span>
                        <span class="val" style="color:var(--green-600)">Rp {{ number_format($iPaid->sum('total_amount'), 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($iPv->count())
                    <div class="ir">
                        <span class="lbl">Diverifikasi ({{ $iPv->count() }})</span>
                        <span class="val" style="color:var(--blue)">Rp {{ number_format($iPv->sum('total_amount'), 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($iUnp->count())
                    <div class="ir">
                        <span class="lbl">Belum Bayar ({{ $iUnp->count() }})</span>
                        <span class="val" style="color:var(--amber)">Rp {{ number_format($iUnp->sum('total_amount'), 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="ir" style="border-top:1px solid var(--neutral-200);font-weight:700;padding-top:10px;margin-top:4px">
                        <span>Total Invoice</span>
                        <span style="font-family:'Fraunces',serif;color:var(--green-700)">
                            Rp {{ number_format($invoices->sum('total_amount'), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    </div>

    @if(in_array($wl->status, ['waiting', 'notified']))
    @php $penalty = $dpIsPaid ? (int) round($dpAmount * 0.10) : 0; @endphp
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:var(--shadow-lg)">
                <div class="modal-header" style="background:var(--red-pale);border-bottom:1px solid #fecaca;border-radius:16px 16px 0 0">
                    <h5 class="modal-title" style="font-family:'Fraunces',serif;font-size:.92rem;font-weight:700;color:var(--red)">
                        Konfirmasi Pembatalan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('prospective.waiting-list.cancel', $wl->id) }}">
                    @csrf
                    <div class="modal-body" style="padding:18px 22px">
                        @if($penalty > 0)
                        <div style="background:var(--red-pale);border:1px solid #fecaca;border-radius:10px;padding:12px 14px;font-size:.78rem;color:#7f1d1d;margin-bottom:14px">
                            Denda <strong>10%</strong> dari DP (Rp {{ number_format($dpAmount, 0, ',', '.') }}):
                            <span style="font-family:'Fraunces',serif;font-size:1rem;font-weight:700;display:block;margin-top:3px">
                                Rp {{ number_format($penalty, 0, ',', '.') }}
                            </span>
                            <span style="font-size:.67rem;opacity:.7">Sisa 90% direfund melalui admin.</span>
                        </div>
                        @else
                        <div style="background:var(--green-50);border:1px solid var(--green-200);border-radius:10px;padding:10px 14px;font-size:.78rem;color:var(--green-700);margin-bottom:14px">
                            <i class="bi bi-info-circle"></i>&nbsp;Tidak ada denda — DP belum dibayar.
                        </div>
                        @endif
                        <label style="font-size:.74rem;font-weight:600;display:block;margin-bottom:6px;color:var(--neutral-700)">
                            Alasan Pembatalan <span style="color:var(--red)">*</span>
                        </label>
                        <textarea name="cancellation_reason" rows="3" required
                            style="width:100%;border:1px solid var(--neutral-200);border-radius:9px;padding:9px 12px;font-size:.77rem;font-family:'Instrument Sans',sans-serif;resize:none;outline:none;transition:border-color .15s;color:var(--neutral-900);background:var(--neutral-0)"
                            onfocus="this.style.borderColor='var(--green-400)'"
                            onblur="this.style.borderColor='var(--neutral-200)'"
                            placeholder="Jelaskan alasan pembatalan..."></textarea>
                    </div>
                    <div class="modal-footer" style="padding:12px 22px;gap:8px;border-top:1px solid var(--neutral-100)">
                        <button type="button" class="btn-ol" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:.78rem;font-weight:600;background:var(--red);color:#fff;border:none;cursor:pointer;font-family:'Instrument Sans',sans-serif">
                            <i class="bi bi-x-circle-fill"></i>
                            Ya, Batalkan{{ $penalty > 0 ? ' (Denda Rp ' . number_format($penalty, 0, ',', '.') . ')' : '' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>