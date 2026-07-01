<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penghuni {{ $tenant->name }} — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
    :root {
        --green-50:#f0faf4;--green-100:#dcf5e7;--green-200:#b8eacf;
        --green-300:#86d9ae;--green-400:#4ec98a;--green-500:#2ab36b;
        --green-600:#1d9458;--green-700:#167745;--green-800:#135a37;--green-900:#0e3f27;
        --neutral-0:#ffffff;--neutral-50:#f8faf9;--neutral-100:#f0f4f2;
        --neutral-200:#e1e9e5;--neutral-400:#8fa89d;--neutral-500:#5f7a6e;
        --neutral-600:#3e5549;--neutral-700:#2d4038;--neutral-900:#0f1f18;
        --accent:#f4a535;--accent-pale:#fffbf0;
        --blue:#2563eb;--blue-pale:#eff6ff;
        --red:#dc2626;--red-pale:#fef2f2;
        --amber:#f59e0b;--amber-pale:#fffbeb;
        --violet:#7c3aed;
        --shadow-sm:0 1px 4px rgba(15,31,24,.06);
        --shadow-md:0 4px 20px rgba(15,31,24,.09);
        --shadow-lg:0 12px 48px rgba(15,31,24,.13);
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'Instrument Sans',sans-serif;background:var(--green-50);color:var(--neutral-900);line-height:1.6;-webkit-font-smoothing:antialiased;min-height:100vh}
    ::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:var(--green-50)}::-webkit-scrollbar-thumb{background:var(--green-400);border-radius:3px}
    .page-bg{position:fixed;inset:0;z-index:-1;overflow:hidden;background:var(--green-50)}
    .page-bg::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 15% 15%,rgba(42,179,107,.08) 0%,transparent 50%),radial-gradient(circle at 85% 80%,rgba(29,148,88,.06) 0%,transparent 50%)}
    .page-bg-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(42,179,107,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(42,179,107,.04) 1px,transparent 1px);background-size:60px 60px}
    .page-wrap{max-width:1280px;margin:0 auto;padding:32px 24px 64px}
    .hero-banner{position:relative;overflow:hidden;border-radius:20px;background:linear-gradient(135deg,var(--green-900) 0%,var(--green-800) 30%,var(--green-700) 65%,var(--green-500) 100%);padding:32px 32px;margin-bottom:28px;box-shadow:0 8px 40px rgba(14,63,39,.35)}
    .hero-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(134,217,174,.18) 0%,transparent 50%),radial-gradient(circle at 10% 80%,rgba(78,201,138,.12) 0%,transparent 40%);pointer-events:none}
    .hero-arc{position:absolute;right:-100px;top:-100px;width:380px;height:380px;border-radius:50%;border:1px solid rgba(255,255,255,.1);pointer-events:none}
    .hero-content{position:relative;z-index:1;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px}
    .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.82);margin-bottom:14px}
    .hero-eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--green-300);flex-shrink:0}
    .hero-title{font-family:'Fraunces',serif;font-weight:700;font-size:clamp(1.4rem,4vw,2rem);color:#fff;line-height:1.15;letter-spacing:-.025em;margin-bottom:6px}
    .hero-title em{font-style:italic;font-weight:400;color:rgba(255,255,255,.72)}
    .hero-sub{font-size:.85rem;color:rgba(255,255,255,.60);max-width:400px;line-height:1.6}
    .hero-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
    .btn-hero-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .92);
        border: 1px solid rgba(255, 255, 255, .6);
        color: var(--neutral-700);
        font-size: .78rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .btn-hero-back:hover {
        background: #fff;
        color: var(--neutral-900);
    }
    .btn-hero-edit{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:10px;background:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.5);color:var(--green-800);font-size:.78rem;font-weight:700;text-decoration:none;transition:all .2s}
    .btn-hero-edit:hover{background:#fff;color:var(--green-700)}
    .alert-strip{display:flex;align-items:flex-start;gap:10px;padding:13px 16px;border-radius:10px;margin-bottom:12px;font-size:.82rem;font-weight:500;border:1px solid;line-height:1.55}
    .alert-strip.success{background:var(--green-50);border-color:var(--green-200);color:var(--green-800)}
    .alert-strip.error{background:var(--red-pale);border-color:rgba(220,38,38,.25);color:#991b1b}
    .alert-strip.warn{background:var(--amber-pale);border-color:rgba(245,158,11,.3);color:#78350f}
    .alert-strip.info{background:var(--blue-pale);border-color:rgba(37,99,235,.2);color:#1e40af}
    .alert-strip i{font-size:.95rem;flex-shrink:0;margin-top:2px}
    .activation-banner{display:flex;align-items:center;gap:16px;padding:16px 20px;border-radius:16px;margin-bottom:14px;background:linear-gradient(135deg,var(--green-50),#d1fce5);border:1.5px solid var(--green-300);box-shadow:var(--shadow-sm);flex-wrap:wrap}
    .activation-banner-title{font-size:.85rem;font-weight:700;color:var(--green-800);margin-bottom:3px;font-family:'Fraunces',serif}
    .activation-banner-sub{font-size:.76rem;color:var(--green-700);line-height:1.5}
    .btn-activate-banner{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;font-size:.78rem;font-weight:700;font-family:'Instrument Sans',sans-serif;color:#fff;background:linear-gradient(135deg,var(--green-600),var(--green-500));border:none;border-radius:9px;cursor:pointer;box-shadow:0 2px 8px rgba(42,179,107,.3);transition:all .2s;white-space:nowrap;flex-shrink:0}
    .btn-activate-banner:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(42,179,107,.4)}
    .booking-active-card{background:linear-gradient(135deg,var(--green-50),#f0faf4);border:1.5px solid var(--green-200);border-radius:16px;overflow:hidden;margin-bottom:16px;box-shadow:var(--shadow-sm)}
    .booking-active-head{display:flex;align-items:center;gap:9px;padding:12px 18px;border-bottom:1px solid var(--green-200);font-family:'Fraunces',serif;font-size:.82rem;font-weight:700;color:var(--green-800);background:rgba(42,179,107,.05)}
    .booking-active-body{padding:18px}
    .booking-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
    @media(max-width:600px){.booking-stats{grid-template-columns:repeat(2,1fr)}}
    .bstat-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--neutral-400);margin-bottom:4px}
    .bstat-val{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--neutral-900);letter-spacing:-.02em;line-height:1.1}
    .bstat-val.green{color:var(--green-700)}.bstat-val.red{color:var(--red)}.bstat-val.amber{color:#92400e}
    .bstat-sub{font-size:.68rem;color:var(--neutral-400);margin-top:2px}
    .detail-grid{display:grid;grid-template-columns:300px 1fr;gap:18px;align-items:start}
    @media(max-width:900px){.detail-grid{grid-template-columns:1fr}}
    .o-card{background:var(--neutral-0);border-radius:16px;border:1px solid var(--green-100);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:16px}
    .o-card:last-child{margin-bottom:0}
    .o-card-header{padding:13px 18px;border-bottom:1px solid var(--neutral-100);display:flex;align-items:center;gap:9px;font-family:'Fraunces',serif;font-size:.82rem;font-weight:700;color:var(--neutral-800);background:linear-gradient(135deg,var(--green-50),var(--neutral-0))}
    .o-card-header .head-icon{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.8rem;background:var(--green-100);color:var(--green-700);flex-shrink:0}
    .o-card-header .head-count{margin-left:auto;background:var(--green-100);color:var(--green-800);font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:100px;border:1px solid var(--green-200)}
    .o-card-body{padding:18px}
    .profile-avatar-wrap{text-align:center;padding:24px 18px 18px}
    .profile-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--green-500),var(--green-800));display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#fff;font-weight:700;margin:0 auto 14px;box-shadow:0 4px 16px rgba(42,179,107,.3)}
    .profile-name{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--neutral-900);letter-spacing:-.02em;margin-bottom:3px}
    .profile-email{font-size:.76rem;color:var(--neutral-400);margin-bottom:12px}
    .profile-status{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:100px;font-size:.7rem;font-weight:700}
    .status-active{background:var(--green-100);color:var(--green-800);border:1px solid var(--green-200)}
    .status-inactive{background:var(--red-pale);color:var(--red);border:1px solid rgba(220,38,38,.2)}
    .info-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--neutral-100);font-size:.78rem;gap:8px}
    .info-row:last-child{border-bottom:none}
    .info-lbl{color:var(--neutral-400);font-weight:600;flex-shrink:0}
    .info-val{font-weight:700;color:var(--neutral-900);text-align:right}
    .action-section{display:flex;flex-direction:column;gap:8px;padding:16px 18px}
    .action-block{border-radius:10px;padding:13px 15px}
    .action-block.amber-block{background:var(--amber-pale);border:1.5px solid rgba(245,158,11,.3)}
    .action-block-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px;display:flex;align-items:center;gap:5px;color:#92400e}
    .action-block-desc{font-size:.73rem;line-height:1.5;margin-bottom:10px;color:#92400e}
    .btn-action{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:9px 14px;font-size:.78rem;font-weight:700;font-family:'Instrument Sans',sans-serif;border-radius:9px;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
    .btn-action-green{background:linear-gradient(135deg,var(--green-600),var(--green-500));color:#fff;box-shadow:0 2px 8px rgba(42,179,107,.25)}
    .btn-action-green:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(42,179,107,.35);color:#fff}
    .btn-action-blue{background:var(--blue-pale);color:var(--blue);border:1.5px solid rgba(37,99,235,.2)}
    .btn-action-blue:hover{background:#dbeafe;color:#1d4ed8}
    .btn-action-amber{background:var(--amber-pale);color:#92400e;border:1.5px solid rgba(245,158,11,.3)}
    .btn-action-amber:hover{background:rgba(245,158,11,.2)}
    .btn-action-red{background:var(--red-pale);color:var(--red);border:1.5px solid rgba(220,38,38,.2)}
    .btn-action-red:hover{background:rgba(220,38,38,.1)}
    .btn-action-ghost{background:var(--neutral-50);color:var(--neutral-600);border:1.5px solid var(--neutral-200)}
    .btn-action-ghost:hover{background:var(--neutral-100);color:var(--neutral-800)}
    .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
    .dtbl{width:100%;border-collapse:collapse}
    .dtbl thead th{padding:9px 16px;font-size:.63rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--neutral-400);background:var(--neutral-50);border-bottom:1px solid var(--neutral-100);white-space:nowrap;text-align:left}
    .dtbl tbody tr{border-bottom:1px solid var(--neutral-100);transition:background .12s}
    .dtbl tbody tr:last-child{border-bottom:none}
    .dtbl tbody tr:hover td{background:var(--green-50)}
    .dtbl tbody td{padding:10px 16px;font-size:.79rem;vertical-align:middle;color:var(--neutral-600)}
    .booking-code{font-weight:700;color:var(--green-700);font-size:.75rem}
    .inv-num{font-weight:700;color:var(--green-700);font-size:.73rem}
    .room-chip{display:inline-flex;align-items:center;gap:4px;background:var(--neutral-100);border-radius:6px;padding:3px 8px;font-size:.71rem;font-weight:600;color:var(--neutral-700)}
    .badge-st{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:100px;font-size:.67rem;font-weight:600;white-space:nowrap}
    .st-active,.st-approved,.st-paid{background:var(--green-100);color:var(--green-800)}
    .st-pending,.st-verify{background:#dbeafe;color:var(--blue)}
    .st-expired,.st-completed{background:var(--neutral-100);color:var(--neutral-500)}
    .st-rejected{background:var(--red-pale);color:var(--red)}
    .st-unpaid,.st-overdue{background:var(--amber-pale);color:#92400e}
    .tbl-link{font-size:.72rem;color:var(--green-600);font-weight:700;text-decoration:none}
    .tbl-link:hover{text-decoration:underline}
    .btn-activate-sm{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;font-size:.68rem;font-weight:700;font-family:'Instrument Sans',sans-serif;color:#fff;background:linear-gradient(135deg,var(--green-600),var(--green-500));border:none;border-radius:6px;cursor:pointer;box-shadow:0 1px 4px rgba(42,179,107,.25);transition:all .15s}
    .btn-activate-sm:hover{transform:translateY(-1px)}
    .empty-row td{text-align:center;padding:28px;color:var(--neutral-400);font-size:.78rem}
    .modal-backdrop-custom{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1040;backdrop-filter:blur(3px);align-items:center;justify-content:center}
    .modal-backdrop-custom.show{display:flex}
    .modal-box{background:#fff;border-radius:16px;width:100%;max-width:440px;margin:20px;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;animation:modalIn .2s ease}
    @keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:none}}
    .modal-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid rgba(245,158,11,.2);background:var(--amber-pale)}
    .modal-title{font-family:'Fraunces',serif;font-size:.9rem;font-weight:700;color:#92400e}
    .modal-close{width:28px;height:28px;border-radius:7px;border:none;background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#92400e;font-size:.85rem;transition:background .12s}
    .modal-close:hover{background:rgba(245,158,11,.2)}
    .modal-body{padding:20px}
    .modal-alert{background:var(--amber-pale);border:1.5px solid rgba(245,158,11,.3);border-radius:10px;padding:13px 15px;font-size:.78rem;color:#92400e;line-height:1.6;margin-bottom:14px}
    .modal-alert ul{padding-left:16px;margin:6px 0 0}
    .modal-footer-custom{display:flex;justify-content:flex-end;gap:8px;padding:14px 20px;border-top:1px solid var(--neutral-100)}
    .modal-label{font-size:.76rem;font-weight:700;color:var(--neutral-700);margin-bottom:5px;display:block}
    .modal-textarea{width:100%;padding:9px 12px;font-size:.78rem;font-family:'Instrument Sans',sans-serif;border:1.5px solid var(--neutral-200);border-radius:9px;resize:vertical;outline:none;color:var(--neutral-800);min-height:64px;transition:border-color .15s}
    .modal-textarea:focus{border-color:var(--amber);box-shadow:0 0 0 3px rgba(245,158,11,.12)}
    .fade-up{opacity:0;transform:translateY(14px);animation:fadeUp .4s ease forwards}
    @keyframes fadeUp{to{opacity:1;transform:none}}
    .d1{animation-delay:.04s}.d2{animation-delay:.09s}.d3{animation-delay:.14s}.d4{animation-delay:.18s}.d5{animation-delay:.22s}
    @media(max-width:640px){.page-wrap{padding:16px 12px 48px}.o-card-body,.booking-active-body{padding:14px 12px}.dtbl thead th,.dtbl tbody td{padding:8px 10px}.hide-mobile{display:none}.activation-banner{flex-direction:column;align-items:flex-start}.detail-grid{gap:14px}}
    @media(max-width:480px){.hero-arc{display:none}.hero-banner{padding:22px 18px}}
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <a href="{{ route('admin.tenants.index') }}" class="btn-hero-back" style="margin-bottom: 16px;">
        <i class="bi bi-arrow-left"></i>Kembali
    </a> 
    <div class="hero-banner fade-up d1">
        <div class="hero-content">
            <div>
                <div class="hero-eyebrow"><span class="dot"></span>Manajemen Penghuni</div>
                <h1 class="hero-title">Detail <em>{{ $tenant->name }}</em></h1>
                <p class="hero-sub">Profil lengkap, riwayat booking, dan invoice penghuni.</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn-hero-edit">
                    <i class="bi bi-pencil-fill"></i> Edit
                </a>
            </div>
        </div>
    </div>

    @foreach(['success','error','warning','info'] as $type)
    @if(session($type))
    <div class="alert-strip {{ $type === 'success' ? 'success' : ($type === 'error' ? 'error' : ($type === 'warning' ? 'warn' : 'info')) }} fade-up d1">
        <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'exclamation-circle-fill' : ($type === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill')) }}"></i>
        <div>{!! session($type) !!}</div>
    </div>
    @endif
    @endforeach

    @php
        $pendingActivationInvoices = $tenant->invoices()
            ->where('status', 'paid')
            ->whereIn('invoice_type', ['booking', 'monthly_first'])
            ->whereHas('booking', fn($q) => $q->where('status', 'approved'))
            ->with('room')->get();

        $pendingWlInvoices = \App\Models\Invoice::where('user_id', $tenant->id)
            ->where('invoice_type', 'waiting_list_remaining')
            ->where('status', 'paid')
            ->whereHas('booking', fn($q) => $q->where('status', 'waiting_list'))
            ->with('room')->get();
    @endphp

    @foreach($pendingActivationInvoices as $pai)
    <div class="activation-banner fade-up d1">
        <div style="flex:1;min-width:0">
            <div class="activation-banner-title">Invoice {{ $pai->invoice_number }} Sudah Lunas — Perlu Aktivasi</div>
            <div class="activation-banner-sub">
                Pembayaran kamar {{ $pai->room?->room_number }} sebesar <strong>{{ $pai->formatted_total }}</strong> sudah diterima.
            </div>
        </div>
        <form method="POST" action="{{ route('admin.invoices.activate-tenant', $pai->id) }}">
            @csrf
            <button type="submit" class="btn-activate-banner"><i class="bi bi-person-check-fill"></i> Aktifkan Sekarang</button>
        </form>
    </div>
    @endforeach

    @foreach($pendingWlInvoices as $pwi)
    <div class="activation-banner fade-up d1">
        <div style="flex:1;min-width:0">
            <div class="activation-banner-title">Pelunasan Waiting List Lunas — Perlu Aktivasi</div>
            <div class="activation-banner-sub">Pelunasan sisa sewa kamar {{ $pwi->room?->room_number }} sudah diterima.</div>
        </div>
        <form method="POST" action="{{ route('admin.invoices.activate-tenant', $pwi->id) }}">
            @csrf
            <button type="submit" class="btn-activate-banner"><i class="bi bi-person-check-fill"></i> Aktifkan Sekarang</button>
        </form>
    </div>
    @endforeach

    @php
        $activeBooking = $tenant->bookings
            ->whereIn('status', ['approved','active'])
            ->sortByDesc('created_at')
            ->first();

        $endDate = null;
        if ($activeBooking) {
            $endDate = !empty($activeBooking->end_date)
                ? \Carbon\Carbon::parse($activeBooking->end_date)
                : ($activeBooking->check_in_date
                    ? $activeBooking->check_in_date->addMonths($activeBooking->duration_months)
                    : null);
        }
    @endphp

    @if($activeBooking)
    <div class="booking-active-card fade-up d2">
        <div class="booking-active-head">
            <div class="o-card-header head-icon" style="width:28px;height:28px;border-radius:7px;background:var(--green-100);color:var(--green-700);display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;border:none;padding:0">
                <i class="bi bi-house-check-fill"></i>
            </div>
            Booking Aktif — Kamar {{ $activeBooking->room?->room_number }}
            <span style="margin-left:auto;font-size:.62rem;font-weight:700;padding:3px 9px;border-radius:100px;background:var(--green-500);color:#fff">{{ strtoupper($activeBooking->status) }}</span>
        </div>
        <div class="booking-active-body">
            <div class="booking-stats">
                <div>
                    <div class="bstat-label">Kamar</div>
                    <div class="bstat-val green">{{ $activeBooking->room?->room_number }}</div>
                    <div class="bstat-sub">{{ $activeBooking->room?->room_type ?? '' }}</div>
                </div>
                <div>
                    <div class="bstat-label">Check-in</div>
                    <div class="bstat-val">{{ $activeBooking->check_in_date?->format('d M Y') ?? '—' }}</div>
                    <div class="bstat-sub">Tanggal masuk</div>
                </div>
                <div>
                    <div class="bstat-label">Durasi</div>
                    <div class="bstat-val">{{ $activeBooking->duration_months }}</div>
                    <div class="bstat-sub">bulan</div>
                </div>
                <div>
                    <div class="bstat-label">Berakhir</div>
                    <div class="bstat-val {{ $endDate?->isPast() ? 'red' : (($endDate && now()->diffInDays($endDate, false) <= 30) ? 'amber' : 'green') }}">
                        {{ $endDate?->format('d M Y') ?? '—' }}
                    </div>
                    <div class="bstat-sub">
                        @if($endDate?->isPast())
                            <span style="color:var(--red);font-weight:700">Sudah berakhir</span>
                        @elseif($endDate && now()->diffInDays($endDate, false) <= 30)
                            <span style="color:var(--amber);font-weight:700">{{ now()->diffInDays($endDate) }} hari lagi</span>
                        @else Aktif @endif
                    </div>
                </div>
            </div>
            @if($activeBooking->emergency_contact)
            <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--green-200);display:flex;align-items:center;gap:10px">
                <div style="width:32px;height:32px;border-radius:8px;background:var(--green-100);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-telephone-fill" style="font-size:.8rem;color:var(--green-700)"></i>
                </div>
                <div>
                    <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--neutral-400);margin-bottom:2px">Kontak Darurat</div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--neutral-900)">{{ $activeBooking->emergency_contact }}</div>
                </div>
                <a href="https://wa.me/62{{ ltrim($activeBooking->emergency_contact, '0') }}"
                target="_blank"
                style="margin-left:auto;display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:var(--green-50);border:1px solid var(--green-200);border-radius:8px;font-size:.73rem;font-weight:700;color:var(--green-700);text-decoration:none">
                    <i class="bi bi-whatsapp"></i> Hubungi
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="detail-grid">

        <div>

            <div class="o-card fade-up d2">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">{{ strtoupper(substr($tenant->name,0,1)) }}</div>
                    <div class="profile-name">{{ $tenant->name }}</div>
                    <div class="profile-email">{{ $tenant->email }}</div>
                    @if($tenant->is_active)
                        <span class="profile-status status-active">Aktif</span>
                    @else
                        <span class="profile-status status-inactive">Nonaktif</span>
                    @endif
                    <div style="margin-top:16px;text-align:left;width:100%">
                        <div class="info-row">
                            <span class="info-lbl">Role</span>
                            <span class="info-val" style="font-size:.75rem">{{ $tenant->role?->display_name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-lbl">No. HP</span>
                            <span class="info-val" style="font-size:.78rem">{{ $tenant->phone ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-lbl">Bergabung</span>
                            <span class="info-val" style="font-size:.76rem">{{ $tenant->created_at->format('d M Y') }}</span>
                        </div>
                        @php
                            $emergencyContact = $tenant->bookings
                                ->whereIn('status', ['approved','active'])
                                ->sortByDesc('created_at')
                                ->first()?->emergency_contact
                                ?? $tenant->bookings->sortByDesc('created_at')->first()?->emergency_contact;
                        @endphp
                        <div class="info-row">
                            <span class="info-lbl">Kontak Darurat</span>
                            <span class="info-val" style="font-size:.78rem">
                                @if($emergencyContact)
                                    <a href="tel:{{ $emergencyContact }}" style="color:var(--green-700);text-decoration:none;font-weight:700">
                                        <i class="bi bi-telephone-fill" style="font-size:.7rem"></i> {{ $emergencyContact }}
                                    </a>
                                @else
                                    <span style="color:var(--neutral-400)">—</span>
                                @endif
                            </span>
                        </div>
                        @if($tenant->admin_notes)
                        <div class="info-row" style="flex-direction:column;align-items:flex-start;gap:4px">
                            <span class="info-lbl">Catatan Admin</span>
                            <span style="font-size:.74rem;color:var(--neutral-600);line-height:1.5">{{ $tenant->admin_notes }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="o-card fade-up d3">
                <div class="o-card-header">
                    <div class="head-icon" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-gear-fill"></i></div>
                    Aksi Admin
                </div>
                <div class="action-section">

                    <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn-action btn-action-blue">
                        <i class="bi bi-pencil-fill"></i> Edit Data Penghuni
                    </a>

                    @if($tenant->role?->name === 'tenant')
                    <div class="action-block amber-block">
                        <div class="action-block-label">
                            <i class="bi bi-arrow-down-circle"></i>
                            Turunkan ke Calon Penghuni
                        </div>
                        <div class="action-block-desc">
                            Booking aktif akan diakhiri dan kamar dikembalikan ke status Tersedia.
                        </div>
                        <button class="btn-action btn-action-amber"
                                onclick="document.getElementById('downgradeModal').classList.add('show')">
                            <i class="bi bi-arrow-down-circle"></i> Turunkan ke Calon Penghuni
                        </button>
                    </div>
                    @endif

                    @if($tenant->is_active)
                    <form method="POST" action="{{ route('admin.tenants.deactivate', $tenant->id) }}"
                          onsubmit="return confirm('Nonaktifkan akun {{ $tenant->name }}?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-action btn-action-red">
                            <i class="bi bi-person-slash"></i> Nonaktifkan Akun
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.tenants.activate', $tenant->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-action btn-action-green">
                            <i class="bi bi-person-check-fill"></i> Aktifkan Akun
                        </button>
                    </form>
                    @endif

                    <button type="button" class="btn-action btn-action-red"
                        onclick="confirmDeleteFromShow()">
                        <i class="bi bi-trash3-fill"></i> Hapus Penghuni
                    </button>
                    <form id="deleteFormShow" method="POST"
                          action="{{ route('admin.tenants.destroy', $tenant->id) }}"
                          style="display:none">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>

        </div>

        <div>

            <div class="o-card fade-up d3">
                <div class="o-card-header">
                    <div class="head-icon"><i class="bi bi-calendar-check-fill"></i></div>
                    Riwayat Booking
                    <span class="head-count">{{ $tenant->bookings->count() }}</span>
                </div>
                <div class="tbl-wrap">
                    <table class="dtbl">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kamar</th>
                                <th class="hide-mobile">Tgl Masuk</th>
                                <th class="hide-mobile">Durasi</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenant->bookings->sortByDesc('created_at') as $b)
                            <tr>
                                <td><span class="booking-code">{{ $b->booking_code }}</span></td>
                                <td>
                                    @if($b->room)
                                    <span class="room-chip"><i class="bi bi-house" style="color:var(--green-500)"></i>{{ $b->room->room_number }}</span>
                                    @else <span style="color:var(--neutral-400)">—</span> @endif
                                </td>
                                <td class="hide-mobile"><span style="font-size:.74rem;color:var(--neutral-500)">{{ $b->check_in_date?->format('d M Y') ?? '—' }}</span></td>
                                <td class="hide-mobile"><span style="font-size:.74rem;color:var(--neutral-600);font-weight:600">{{ $b->duration_months }} bln</span></td>
                                <td>
                                    @php
                                        $stMap = ['active'=>['cls'=>'st-active','txt'=>'Aktif'],'approved'=>['cls'=>'st-approved','txt'=>'Disetujui'],'pending'=>['cls'=>'st-pending','txt'=>'Menunggu'],'rejected'=>['cls'=>'st-rejected','txt'=>'Ditolak'],'cancelled'=>['cls'=>'st-rejected','txt'=>'Dibatalkan'],'expired'=>['cls'=>'st-expired','txt'=>'Kedaluwarsa'],'completed'=>['cls'=>'st-completed','txt'=>'Selesai']];
                                        $sc = $stMap[$b->status] ?? ['cls'=>'st-expired','txt'=>ucfirst($b->status)];
                                    @endphp
                                    <span class="badge-st {{ $sc['cls'] }}">{{ $sc['txt'] }}</span>
                                </td>
                                <td><a href="{{ route('admin.bookings.show', $b->id) }}" class="tbl-link">Detail</a></td>
                            </tr>
                            @empty
                            <tr class="empty-row"><td colspan="6">Belum ada riwayat booking</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="o-card fade-up d4">
                <div class="o-card-header">
                    <div class="head-icon" style="background:var(--blue-pale);color:var(--blue)"><i class="bi bi-receipt-cutoff"></i></div>
                    Invoice Terbaru
                </div>
                <div class="tbl-wrap">
                    <table class="dtbl">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Total</th>
                                <th class="hide-mobile">Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aktivasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenant->invoices()->latest()->take(8)->get() as $inv)
                            <tr>
                                <td><span class="inv-num">{{ $inv->invoice_number }}</span></td>
                                <td><span style="font-weight:700;color:var(--neutral-900);font-size:.8rem">{{ $inv->formatted_total }}</span></td>
                                <td class="hide-mobile"><span style="font-size:.74rem;color:var(--neutral-500)">{{ $inv->due_date?->format('d M Y') ?? '—' }}</span></td>
                                <td>
                                    @php $invMap = ['paid'=>['cls'=>'st-paid','txt'=>'Lunas'],'pending'=>['cls'=>'st-unpaid','txt'=>'Belum Bayar'],'overdue'=>['cls'=>'st-overdue','txt'=>'Terlambat'],'pending_verification'=>['cls'=>'st-verify','txt'=>'Verifikasi']]; $ic = $invMap[$inv->status] ?? ['cls'=>'st-expired','txt'=>ucfirst($inv->status)]; @endphp
                                    <span class="badge-st {{ $ic['cls'] }}">{{ $ic['txt'] }}</span>
                                </td>
                                <td>
                                    @if($inv->status === 'paid' && $inv->pending_tenant_activation)
                                    <form method="POST" action="{{ route('admin.invoices.activate-tenant', $inv->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-activate-sm"><i class="bi bi-person-check-fill"></i> Aktifkan</button>
                                    </form>
                                    @else
                                    <span style="font-size:.68rem;color:var(--neutral-400)">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row"><td colspan="5">Belum ada invoice</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

@if($tenant->role?->name === 'tenant')
<div class="modal-backdrop-custom" id="downgradeModal" onclick="handleModalClose(event)">
    <div class="modal-box">
        <div class="modal-head">
            <div class="modal-title">Turunkan ke Calon Penghuni</div>
            <button class="modal-close" onclick="document.getElementById('downgradeModal').classList.remove('show')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <div class="modal-alert">
                Aksi ini akan mengubah role <strong>{{ $tenant->name }}</strong> menjadi Calon Penghuni, mengakhiri booking aktif, dan mengembalikan kamar ke status Tersedia.
            </div>
            <form method="POST" action="{{ route('admin.tenants.downgrade', $tenant->id) }}" id="downgradeForm">
                @csrf @method('PATCH')
                <label class="modal-label">Catatan <span style="color:var(--neutral-400);font-weight:400">(opsional)</span></label>
                <textarea name="notes" class="modal-textarea" placeholder="Alasan downgrade / catatan..."></textarea>
            </form>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-action btn-action-ghost" style="width:auto;padding:8px 16px" onclick="document.getElementById('downgradeModal').classList.remove('show')">Batal</button>
            <button type="submit" form="downgradeForm" class="btn-action btn-action-amber" style="width:auto;padding:8px 18px"><i class="bi bi-arrow-down-circle"></i> Ya, Turunkan</button>
        </div>
    </div>
</div>
@endif

<script>
function handleModalClose(e) {
    if (e.target === document.getElementById('downgradeModal')) {
        document.getElementById('downgradeModal').classList.remove('show');
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.getElementById('downgradeModal')?.classList.remove('show');
});
function confirmDeleteFromShow() {
    if (confirm('Yakin ingin menghapus penghuni "{{ addslashes($tenant->name) }}"?\n\nSemua data terkait akan ikut terhapus dan tidak bisa dikembalikan.')) {
        document.getElementById('deleteFormShow').submit();
    }
}
</script>

@stack('scripts')
</body>
</html>