<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tagihan — Urban Kost</title>
    <meta name="description" content="Detail Tagihan Urban Kost">
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
        --blue:        #2563eb;
        --blue-pale:   #eff6ff;
        --blue-200:    #bfdbfe;
        --blue-600:    #2563eb;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --red-100:     #fee2e2;
        --red-200:     #fecaca;
        --red-600:     #dc2626;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --amber-100:   #fef3c7;
        --amber-200:   #fde68a;
        --amber-600:   #d97706;
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
        --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
        --r-card: 16px;
        --r-pill: 999px;
        --g50:  var(--green-50);
        --g100: var(--green-100);
        --g200: var(--green-200);
        --g300: var(--green-300);
        --g400: var(--green-400);
        --g500: var(--green-500);
        --g600: var(--green-600);
        --g700: var(--green-700);
        --g800: var(--green-800);
        --g900: var(--green-900);
        --n0:   var(--neutral-0);
        --n50:  var(--neutral-50);
        --n100: var(--neutral-100);
        --n200: var(--neutral-200);
        --n400: var(--neutral-400);
        --n500: var(--neutral-500);
        --n600: var(--neutral-600);
        --n900: var(--neutral-900);
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

    .page { max-width: 1040px; margin: 0 auto; padding: 32px 24px 80px; }

    .flash {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 16px; border-radius: 10px;
        margin-bottom: 14px; font-size: .82rem; font-weight: 500;
        border: 1px solid; line-height: 1.55;
    }
    .flash i { font-size: .9rem; flex-shrink: 0; margin-top: 2px; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 15px; border-radius: 9px;
        border: 1px solid var(--green-200);
        color: var(--neutral-600);
        text-decoration: none; font-size: .78rem; font-weight: 600;
        background: var(--neutral-0); transition: all .2s; flex-shrink: 0;
        margin-bottom: 16px;
    }
    .btn-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

    .hero-banner {
        position: relative; overflow: hidden; border-radius: 20px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 28px 28px 24px; margin-bottom: 22px;
        box-shadow: 0 8px 40px rgba(14,63,39,.28), 0 2px 8px rgba(0,0,0,.08);
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
        position: absolute; right: -80px; top: -80px;
        width: 280px; height: 280px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1); pointer-events: none; z-index: 1;
    }
    .hero-content {
        position: relative; z-index: 2;
        display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    }
    .hero-left {}
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        padding: 4px 12px; border-radius: var(--r-pill);
        font-size: .65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.8); margin-bottom: 10px;
    }
    .hero-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-inv-code {
        font-family: 'Fraunces', serif; font-weight: 700;
        font-size: clamp(1.3rem, 3vw, 1.75rem);
        color: #fff; line-height: 1.15; letter-spacing: -.025em; margin-bottom: 6px;
    }
    .hero-meta { font-size: .8rem; color: rgba(255,255,255,.62); line-height: 1.6; }
    .hero-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
        border-radius: var(--r-pill); padding: 4px 12px;
        font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.88);
    }
    .hero-right {
        text-align: right; flex-shrink: 0;
    }
    .hero-amount-lbl { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.5); margin-bottom: 4px; }
    .hero-amount {
        font-family: 'Fraunces', serif; font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 700; color: #fff; line-height: 1; letter-spacing: -.025em;
    }
    .hero-due { font-size: .72rem; color: rgba(255,255,255,.55); margin-top: 5px; }
    .hero-due.danger { color: #fca5a5; }

    .uk-card {
        background: var(--neutral-0);
        border-radius: var(--r-card);
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 16px;
    }
    .uk-card-head {
        padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .uk-card-head-title {
        font-size: .72rem; font-weight: 600;
        color: var(--neutral-600);
        display: flex; align-items: center; gap: 7px;
        text-transform: uppercase; letter-spacing: .07em;
    }
    .uk-card-head-title i { color: var(--green-500); }
    .uk-card-body { padding: 20px; }

    .d-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid var(--neutral-100); font-size: .83rem;
        gap: 12px;
    }
    .d-row:last-child { border-bottom: none; }
    .d-row .lbl { color: var(--neutral-400); flex-shrink: 0; }
    .d-row .val { font-weight: 600; text-align: right; color: var(--neutral-700); }

    .rincian-box {
        background: var(--green-50);
        border: 1px solid var(--green-100);
        border-radius: 12px; padding: 16px 18px; margin-top: 16px;
    }
    .section-lbl {
        font-size: .63rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: .09em; color: var(--neutral-400); margin-bottom: 10px;
    }
    .rin-row { display: flex; justify-content: space-between; font-size: .82rem; margin-bottom: 8px; gap: 12px; }
    .rin-row:last-child { margin-bottom: 0; }
    .rin-total {
        display: flex; justify-content: space-between;
        font-weight: 700; font-size: .98rem;
        padding-top: 12px; margin-top: 8px;
        border-top: 2px solid var(--green-200);
    }
    .rin-total-val { color: var(--green-700); }

    .pay-item { padding: 14px 0; border-bottom: 1px solid var(--neutral-100); }
    .pay-item:last-child { border-bottom: none; }

    .panel-head {
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 22px 20px; position: relative; overflow: hidden;
    }
    .panel-head::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .panel-head-arc {
        position: absolute; right: -60px; top: -60px;
        width: 200px; height: 200px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none; z-index: 1;
    }
    .panel-lbl {
        font-size: .63rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: .09em; color: rgba(255,255,255,.65);
        margin-bottom: 6px; position: relative; z-index: 2;
    }
    .panel-amount {
        font-family: 'Fraunces', serif;
        font-size: 1.85rem; font-weight: 700;
        color: #fff; line-height: 1.1; letter-spacing: -.025em;
        position: relative; z-index: 2;
    }
    .panel-sub {
        font-size: .72rem; color: rgba(255,255,255,.6);
        position: relative; z-index: 2; margin-top: 5px;
    }
    .panel-body { padding: 20px; }

    .method-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
    .method-card {
        border: 1px solid var(--neutral-200); border-radius: 12px;
        padding: 14px 12px; cursor: pointer; transition: all .2s;
        background: var(--neutral-0);
        display: flex; flex-direction: column; align-items: center;
        gap: 5px; position: relative; user-select: none; text-align: center;
    }
    .method-card:hover { border-color: var(--green-300); background: var(--green-50); }
    .method-card.selected {
        border-color: var(--green-500); background: var(--green-50);
        box-shadow: 0 0 0 3px rgba(42,179,107,.12);
    }
    .method-card.selected::after {
        content: '\F26B'; font-family: 'bootstrap-icons';
        position: absolute; top: 7px; right: 9px;
        color: var(--green-500); font-size: .78rem;
    }
    .method-ico { font-size: 1.5rem; line-height: 1; }
    .method-title { font-weight: 700; font-size: .78rem; color: var(--neutral-700); }
    .method-title-sel { color: var(--green-700); }
    .method-desc { font-size: .62rem; color: var(--neutral-400); line-height: 1.4; }

    .bank-pills {
        display: none; flex-wrap: wrap; gap: 7px; margin-bottom: 14px;
        padding: 12px 14px;
        background: var(--green-50);
        border-radius: 10px; border: 1px solid var(--green-100);
    }
    .bank-pills.show { display: flex; }
    .bank-pill {
        padding: 5px 13px; border-radius: var(--r-pill);
        border: 1px solid var(--neutral-200);
        font-size: .72rem; font-weight: 600; color: var(--neutral-600);
        cursor: pointer; background: var(--neutral-0); transition: all .18s;
    }
    .bank-pill:hover { border-color: var(--green-400); color: var(--green-700); }
    .bank-pill.selected { background: var(--green-500); border-color: var(--green-500); color: #fff; }

    .btn-pay {
        width: 100%; padding: 13px;
        background: linear-gradient(135deg, var(--green-500), var(--green-700));
        color: #fff; border: none; border-radius: 11px;
        font-family: 'Instrument Sans', sans-serif;
        font-size: .875rem; font-weight: 600;
        cursor: pointer; transition: all .22s;
        display: flex; align-items: center; justify-content: center; gap: 9px;
        box-shadow: 0 4px 14px rgba(42,179,107,.28);
    }
    .btn-pay:hover:not(:disabled) { background: linear-gradient(135deg, var(--green-600), var(--green-800)); transform: translateY(-1px); }
    .btn-pay:disabled { background: var(--neutral-200); color: var(--neutral-400); cursor: not-allowed; box-shadow: none; transform: none; }
    .btn-pay.st-loading { background: var(--neutral-500) !important; cursor: wait; }
    .btn-pay.st-pending { background: var(--amber-600) !important; cursor: default; }
    .btn-pay.st-success { background: var(--green-600) !important; cursor: default; }

    .secure-row {
        display: flex; align-items: center; justify-content: center;
        gap: 6px; margin-top: 12px; font-size: .68rem; color: var(--neutral-400);
    }
    .secure-row i { color: var(--green-500); }

    .pending-banner {
        display: none; margin-top: 13px; padding: 12px 14px;
        border-radius: 10px;
        background: var(--amber-pale); border: 1px solid var(--amber-200);
        font-size: .78rem; color: var(--amber-600); line-height: 1.55;
    }
    .pending-banner.show { display: block; }

    .proc-block {
        padding: 28px 20px; text-align: center;
        background: var(--blue-pale); border: 1px solid var(--blue-200);
        border-radius: 14px;
    }
    .paid-block {
        padding: 36px 24px; text-align: center;
        background: var(--green-50); border: 1px solid var(--green-200);
        border-radius: 14px;
    }

    .guide-item { display: flex; gap: 10px; margin-bottom: 10px; font-size: .8rem; }
    .guide-num { font-weight: 700; color: var(--green-600); flex-shrink: 0; width: 18px; }

    .col-row { display: grid; grid-template-columns: 1fr; gap: 20px; }
    @media (min-width: 900px) { .col-row { grid-template-columns: 1.55fr 1fr; } }

    @media (max-width: 768px) {
        .page { padding: 20px 16px 64px; }
        .hero-arc { display: none; }
        .hero-right { text-align: left; }
        .hero-content { flex-direction: column; }
    }
    @media (max-width: 480px) {
        .page { padding: 16px 12px 64px; }
        .method-grid { grid-template-columns: 1fr; }
        .hero-banner { padding: 22px 18px; }
        .hero-inv-code { font-size: 1.25rem; }
        .panel-amount { font-size: 1.55rem; }
    }
    @media (min-width: 900px)  { .page { padding: 40px 40px 80px; } }
    @media (min-width: 1280px) { .page { padding: 40px 48px 80px; } }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.prospective-sidebar')

<div class="page">

    @foreach(['success','warning','error','info'] as $type)
    @if(session($type))
    @php
        $fc = [
            'success' => ['var(--green-50)',  'var(--green-200)', 'check-circle-fill',        'var(--green-600)'],
            'error'   => ['var(--red-pale)',  'var(--red-200)',   'exclamation-circle-fill',  'var(--red-600)'],
            'warning' => ['var(--amber-pale)','var(--amber-200)','exclamation-triangle-fill','var(--amber-600)'],
            'info'    => ['var(--blue-pale)', 'var(--blue-200)', 'info-circle-fill',         'var(--blue-600)'],
        ][$type];
    @endphp
    <div class="flash" style="background:{{ $fc[0] }};border-color:{{ $fc[1] }}">
        <i class="bi bi-{{ $fc[2] }}" style="color:{{ $fc[3] }}"></i>
        <div>{!! session($type) !!}</div>
    </div>
    @endif
    @endforeach

    @if(request('payment') === 'success')
    <div class="flash" style="background:var(--green-50);border-color:var(--green-200)">
        <i class="bi bi-check-circle-fill" style="color:var(--green-500)"></i>
        <div>Pembayaran berhasil. Status tagihan sedang diperbarui secara otomatis.</div>
    </div>
    @elseif(request('payment') === 'pending')
    <div class="flash" style="background:var(--amber-pale);border-color:var(--amber-200)">
        <i class="bi bi-clock-fill" style="color:var(--amber)"></i>
        <div>Instruksi pembayaran sudah dibuat. Selesaikan transfer untuk mengkonfirmasi tagihan.</div>
    </div>
    @endif

    <a href="{{ route('prospective.invoices.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    @php
        $daysLeft = $invoice->due_date
            ? now()->startOfDay()->diffInDays($invoice->due_date->startOfDay(), false)
            : null;
    @endphp
    <div class="hero-banner">
        <div class="hero-banner-dots"></div>
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-eyebrow"><span class="dot"></span>Detail Tagihan</div>
                <div class="hero-inv-code">{{ $invoice->invoice_number }}</div>
                <div class="hero-meta">
                    @if($invoice->room) Kamar {{ $invoice->room->room_number }}
                    @if($invoice->room->room_name) &middot; {{ $invoice->room->room_name }}@endif
                    @endif
                    @if($invoice->period_start && $invoice->period_end)
                        <br>{{ $invoice->period_start->format('d M Y') }} &ndash; {{ $invoice->period_end->format('d M Y') }}
                    @endif
                </div>
                <div class="hero-badges">
                    <div class="hero-badge">{!! $invoice->status_badge !!}</div>
                    @if($daysLeft === 0)
                    <div class="hero-badge" style="background:rgba(245,158,11,.25);border-color:rgba(245,158,11,.4)">
                        <i class="bi bi-clock-fill"></i> Hari ini
                    </div>
                    @elseif($invoice->isOverdue())
                    <div class="hero-badge" style="background:rgba(220,38,38,.25);border-color:rgba(220,38,38,.4)">
                        <i class="bi bi-exclamation-triangle-fill"></i> Terlambat
                    </div>
                    @endif
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-amount-lbl">Total Tagihan</div>
                <div class="hero-amount">{{ $invoice->formatted_total }}</div>
                <div class="hero-due {{ $daysLeft !== null && $daysLeft < 0 ? 'danger' : '' }}">
                    <i class="bi bi-clock"></i>
                    @if($daysLeft === 0)
                        Jatuh tempo hari ini
                    @elseif($daysLeft !== null && $daysLeft < 0)
                        Terlambat {{ abs($daysLeft) }} hari
                    @else
                        Jatuh tempo: {{ $invoice->due_date?->format('d M Y') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-row">
        <div>
            <div class="uk-card">
                <div class="uk-card-head">
                    <div class="uk-card-head-title">
                        <i class="bi bi-receipt-cutoff"></i> Detail Tagihan
                    </div>
                </div>
                <div class="uk-card-body">
                    <div class="d-row">
                        <span class="lbl">Kamar</span>
                        <span class="val">{{ $invoice->room?->room_number }} — {{ $invoice->room?->room_name }}</span>
                    </div>
                    <div class="d-row">
                        <span class="lbl">Periode Sewa</span>
                        <span class="val">{{ $invoice->period_start?->format('d M Y') }} – {{ $invoice->period_end?->format('d M Y') }}</span>
                    </div>
                    <div class="d-row">
                        <span class="lbl">Jatuh Tempo</span>
                        <span class="val">
                            {{ $invoice->due_date?->format('d M Y') }}
                            @if($daysLeft === 0)
                            <span style="margin-left:6px;display:inline-flex;align-items:center;gap:4px;font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:var(--r-pill);background:var(--amber-pale);color:var(--amber-600);border:1px solid var(--amber-200)">Hari ini</span>
                            @elseif($invoice->isOverdue())
                            <span style="margin-left:6px;display:inline-flex;align-items:center;gap:4px;font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:var(--r-pill);background:var(--red-pale);color:var(--red-600);border:1px solid var(--red-100)">Terlambat</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-row">
                        <span class="lbl">Status</span>
                        <span>{!! $invoice->status_badge !!}</span>
                    </div>

                    <div class="rincian-box">
                        <div class="section-lbl">Rincian Tagihan</div>
                        <div class="rin-row">
                            <span style="color:var(--neutral-400)">Harga Sewa Kamar</span>
                            <span style="font-weight:600">Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($invoice->service_amount > 0)
                        <div class="rin-row">
                            <span style="color:var(--neutral-400)">Layanan Tambahan</span>
                            <span style="font-weight:600">Rp {{ number_format($invoice->service_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="rin-total">
                            <span>Total Tagihan</span>
                            <span class="rin-total-val">{{ $invoice->formatted_total }}</span>
                        </div>
                    </div>

                    @if($invoice->notes)
                    @php
                        $notesRaw = $invoice->notes;
                        $intro = $notesRaw;
                        $services = [];

                        if (str_contains($notesRaw, 'Layanan tambahan:')) {
                            [$introPart, $servicesPart] = explode('Layanan tambahan:', $notesRaw, 2);
                            $intro = trim($introPart);
                            $services = array_filter(array_map('trim', explode(',', trim($servicesPart))));
                        }
                    @endphp
                    <div style="margin-top:14px;padding:13px 16px;border-radius:10px;background:var(--green-50);border:1px solid var(--green-100)">
                        <div style="display:flex;align-items:center;gap:6px;font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--green-600);margin-bottom:8px">
                            <i class="bi bi-chat-left-text-fill"></i> Catatan
                        </div>
                        @if($intro)
                        <p style="font-size:.82rem;color:var(--neutral-600);line-height:1.6;margin:0 0 10px">{{ $intro }}</p>
                        @endif
                        @if(count($services) > 0)
                        <div style="font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--neutral-400);margin-bottom:6px">Layanan Tambahan</div>
                        <div style="display:flex;flex-direction:column;gap:4px">
                            @foreach($services as $svc)
                            <div style="display:flex;align-items:center;gap:8px;font-size:.8rem;color:var(--neutral-600);padding:5px 10px;background:var(--neutral-0);border-radius:7px;border:1px solid var(--green-100)">
                                <i class="bi bi-check-circle-fill" style="color:var(--green-400);font-size:.7rem;flex-shrink:0"></i>
                                {{ $svc }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @if(str_contains($notesRaw, 'Bayar untuk'))
                        @php
                            $closing = '';
                            if (preg_match('/Bayar untuk[^.]*\.?/', $notesRaw, $m)) {
                                $closing = trim($m[0]);
                            }
                        @endphp
                        @if($closing)
                        <p style="font-size:.78rem;color:var(--green-600);font-weight:600;margin:10px 0 0;padding-top:10px;border-top:1px solid var(--green-100)">
                            <i class="bi bi-info-circle"></i> {{ $closing }}
                        </p>
                        @endif
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="uk-card">
                <div class="uk-card-head">
                    <div class="uk-card-head-title">
                        <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                    </div>
                </div>
                <div class="uk-card-body">
                    @forelse($payments as $pay)
                    <div class="pay-item">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px">
                            <div>
                                <div style="font-weight:700;font-size:.83rem;color:var(--neutral-900)">{{ $pay->payment_code }}</div>
                                <div style="font-size:.72rem;color:var(--neutral-400);margin-top:3px">
                                    {{ $pay->method_label ?? $pay->method }} &middot; {{ $pay->created_at->format('d M Y') }}
                                </div>
                                @if($pay->method === 'midtrans')
                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:.6rem;font-weight:600;padding:2px 8px;border-radius:var(--r-pill);background:var(--blue-pale);color:var(--blue-600);border:1px solid var(--blue-200);margin-top:4px">
                                    <i class="bi bi-shield-lock"></i> Midtrans
                                </span>
                                @endif
                            </div>
                            <div style="text-align:right;flex-shrink:0">
                                <div style="font-weight:700;color:var(--green-700);font-size:.9rem">{{ $pay->formatted_amount }}</div>
                                <div style="margin-top:4px">{!! $pay->status_badge !!}</div>
                            </div>
                        </div>
                        @if($pay->status === 'verified')
                        <div style="font-size:.72rem;color:var(--green-600);margin-top:8px;display:flex;align-items:center;gap:5px">
                            <i class="bi bi-check-circle-fill"></i>
                            Dikonfirmasi {{ $pay->verified_at?->format('d M Y') }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <div style="text-align:center;color:var(--neutral-400);font-size:.82rem;padding:28px 0">
                        <i class="bi bi-inbox" style="font-size:1.5rem;display:block;margin-bottom:8px;color:var(--neutral-200)"></i>
                        Belum ada riwayat pembayaran.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            @if(in_array($invoice->status, ['unpaid','overdue']))
            <div class="uk-card">
                <div class="panel-head">
                    <div class="panel-lbl">Total Pembayaran</div>
                    <div class="panel-amount">{{ $invoice->formatted_total }}</div>
                    <div class="panel-sub">
                        @if($daysLeft === 0)
                            <span style="color:#fde68a;font-weight:700">Jatuh tempo hari ini</span>
                        @elseif($invoice->isOverdue())
                            Jatuh tempo: {{ $invoice->due_date?->format('d M Y') }}
                            &nbsp;&middot; <span style="color:#fcd34d;font-weight:700">Sudah terlambat</span>
                        @else
                            Jatuh tempo: {{ $invoice->due_date?->format('d M Y') }}
                        @endif
                    </div>
                </div>
                <div class="panel-body">
                    <div class="section-lbl" style="margin-bottom:12px">Pilih Metode Pembayaran</div>
                    <div class="method-grid">
                        <div class="method-card" id="choice-qris" onclick="selectMethod('qris')">
                            <div class="method-ico"><i class="bi bi-qr-code-scan" style="font-size:1.35rem;color:var(--green-600)"></i></div>
                            <div class="method-title">QRIS</div>
                            <div class="method-desc">GoPay &middot; OVO &middot; Dana &middot; ShopeePay</div>
                        </div>
                        <div class="method-card" id="choice-bank" onclick="selectMethod('bank')">
                            <div class="method-ico"><i class="bi bi-bank2" style="font-size:1.35rem;color:var(--green-600)"></i></div>
                            <div class="method-title">Virtual Account</div>
                            <div class="method-desc">BCA &middot; BNI &middot; BRI &middot; Mandiri</div>
                        </div>
                    </div>

                    <div class="bank-pills" id="bankOptions">
                        <div style="width:100%;font-size:.62rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--neutral-400);margin-bottom:5px">Pilih Bank:</div>
                        <div class="bank-pill" id="pill-bca" onclick="selectBank('bca_va')">BCA</div>
                        <div class="bank-pill" id="pill-bni" onclick="selectBank('bni_va')">BNI</div>
                        <div class="bank-pill" id="pill-bri" onclick="selectBank('bri_va')">BRI</div>
                    </div>

                    <div class="section-lbl" style="margin-bottom:10px;margin-top:4px">Konfirmasi &amp; Bayar</div>
                    <button id="payBtn" class="btn-pay" onclick="payNow()" disabled>
                        <i class="bi bi-credit-card-fill"></i>
                        <span>Pilih metode pembayaran dulu</span>
                    </button>

                    <div class="pending-banner" id="pendingBanner">
                        <strong>Instruksi pembayaran sudah dibuat.</strong><br>
                        Selesaikan transfer ke nomor Virtual Account. Status diperbarui otomatis setelah transfer berhasil.<br>
                        <a href="{{ route('prospective.invoices.index') }}" style="color:var(--amber-600);font-weight:700;margin-top:6px;display:inline-block">
                            Lihat daftar tagihan
                        </a>
                    </div>

                    <div class="secure-row">
                        <i class="bi bi-shield-lock-fill"></i>
                        Transaksi aman &middot; Diproses oleh Midtrans
                    </div>
                </div>
            </div>

            @elseif($invoice->status === 'pending_verification')
            <div class="uk-card">
                <div class="uk-card-body">
                    <div class="proc-block">
                        <div style="font-size:1.8rem;margin-bottom:10px;color:var(--blue-600)"><i class="bi bi-clock-history"></i></div>
                        <div style="font-weight:700;font-size:.92rem;color:var(--blue-600);margin-bottom:8px">Sedang Diproses</div>
                        <div style="font-size:.8rem;color:var(--neutral-500);line-height:1.6">Pembayaran Anda sedang dikonfirmasi. Status akan diperbarui secara otomatis. Tidak perlu melakukan pembayaran ulang.</div>
                    </div>
                    <div style="font-size:.74rem;color:var(--neutral-400);text-align:center;margin-top:12px">
                        Jika status tidak berubah dalam 1&times;24 jam, silakan hubungi admin.
                    </div>
                </div>
            </div>

            @elseif($invoice->status === 'paid')
            <div class="uk-card">
                <div class="uk-card-body">
                    <div class="paid-block">
                        <div style="font-size:2rem;margin-bottom:12px;color:var(--green-500)"><i class="bi bi-check-circle-fill"></i></div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--green-600);margin-bottom:8px">Pembayaran Lunas</div>
                        <div style="font-size:.8rem;color:var(--neutral-500);line-height:1.6">Tagihan ini sudah lunas. Akun Anda akan diproses oleh admin untuk aktivasi sebagai penghuni.</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="uk-card">
                <div class="uk-card-head">
                    <div class="uk-card-head-title">
                        <i class="bi bi-info-circle"></i> Cara Pembayaran
                    </div>
                </div>
                <div class="uk-card-body">
                    @foreach([
                        'Pilih metode: QRIS atau Virtual Account bank pilihan Anda.',
                        'Klik tombol bayar — jendela Midtrans akan muncul.',
                        'Ikuti instruksi di jendela tersebut (scan QR atau transfer ke nomor VA).',
                        'Setelah berhasil, status tagihan dikonfirmasi secara otomatis.',
                    ] as $i => $txt)
                    <div class="guide-item">
                        <span class="guide-num">{{ $i + 1 }}.</span>
                        <span style="color:var(--neutral-600)">{{ $txt }}</span>
                    </div>
                    @endforeach
                    <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid var(--neutral-100);margin-top:4px;font-size:.78rem">
                        <i class="bi bi-clock-fill" style="color:var(--amber);flex-shrink:0;margin-top:2px"></i>
                        <span style="color:var(--neutral-600)">Jatuh tempo: <strong>{{ $invoice->due_date?->format('d M Y') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(in_array($invoice->status, ['unpaid','overdue']))
@if(config('midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>
let selectedMethod = null, selectedBank = null, hasPendingPayment = false, isProcessing = false;

function selectMethod(method) {
    selectedMethod = method; selectedBank = null;
    document.querySelectorAll('.method-card').forEach(el => el.classList.remove('selected'));
    document.getElementById('choice-' + method).classList.add('selected');
    const bankOpts = document.getElementById('bankOptions');
    if (method === 'bank') {
        bankOpts.classList.add('show');
        document.querySelectorAll('.bank-pill').forEach(p => p.classList.remove('selected'));
        setPayBtnReady(false);
    } else {
        bankOpts.classList.remove('show');
        setPayBtnReady(true);
    }
}

function selectBank(bankCode) {
    selectedBank = bankCode;
    document.querySelectorAll('.bank-pill').forEach(p => p.classList.remove('selected'));
    const pillMap = { bca_va: 'pill-bca', bni_va: 'pill-bni', bri_va: 'pill-bri' };
    const pill = document.getElementById(pillMap[bankCode]);
    if (pill) pill.classList.add('selected');
    setPayBtnReady(true);
}

function setPayBtnReady(ready) {
    const btn = document.getElementById('payBtn');
    if (!btn) return;
    btn.disabled = !ready; btn.className = 'btn-pay';
    if (!ready) { btn.innerHTML = '<i class="bi bi-credit-card-fill"></i><span>Pilih metode pembayaran dulu</span>'; return; }
    if (selectedMethod === 'qris') {
        btn.innerHTML = '<i class="bi bi-qr-code-scan"></i><span>Bayar dengan QRIS</span>';
    } else {
        const names = { bca_va: 'BCA', bni_va: 'BNI', bri_va: 'BRI' };
        btn.innerHTML = '<i class="bi bi-bank2"></i><span>Bayar via VA ' + (names[selectedBank] ?? '') + '</span>';
    }
}

async function payNow() {
    if (isProcessing) return;
    if (!selectedMethod) { alert('Silakan pilih metode pembayaran.'); return; }
    if (selectedMethod === 'bank' && !selectedBank) { alert('Silakan pilih bank Virtual Account.'); return; }
    isProcessing = true;
    const btn = document.getElementById('payBtn');
    btn.disabled = true; btn.className = 'btn-pay st-loading';
    btn.innerHTML = '<span style="display:inline-block;width:15px;height:15px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite"></span>&nbsp;Memproses...';
    try {
        const res = await fetch("{{ route('prospective.invoices.snap-token', $invoice->id) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ method: selectedMethod, bank: selectedBank }),
        });
        const data = await res.json();
        if (!res.ok || !data.snap_token) {
            isProcessing = false; setPayBtnReady(true);
            alert(data.error ?? 'Gagal mendapatkan token pembayaran.'); return;
        }
        window.snap.pay(data.snap_token, {
            onSuccess: function () {
                isProcessing = false; hasPendingPayment = false;
                btn.className = 'btn-pay st-success'; btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i>&nbsp;Pembayaran Berhasil';
                setTimeout(() => { window.location.href = "{{ route('prospective.invoices.show', $invoice->id) }}?payment=success"; }, 1500);
            },
            onPending: function () {
                isProcessing = false; hasPendingPayment = true;
                btn.className = 'btn-pay st-pending'; btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-clock-fill"></i>&nbsp;Menunggu Pembayaran...';
                document.getElementById('pendingBanner').classList.add('show');
            },
            onError: function () { isProcessing = false; setPayBtnReady(true); alert('Pembayaran gagal. Silakan coba lagi.'); },
            onClose: function () {
                isProcessing = false;
                if (hasPendingPayment) { document.getElementById('pendingBanner').classList.add('show'); }
                else { setPayBtnReady(true); }
            }
        });
    } catch (err) { isProcessing = false; setPayBtnReady(true); alert('Terjadi kesalahan: ' + err.message); }
}
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endif

</body>
</html>