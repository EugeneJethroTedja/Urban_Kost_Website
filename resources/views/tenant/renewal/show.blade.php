<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Perpanjangan #{{ $renewal->id }} — Urban Kost</title>
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
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-light:   #fee2e2;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-light: #fef3c7;
    --indigo:      #4f46e5;
    --indigo-pale: #eef2ff;
    --indigo-light:#e0e7ff;
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
.bg-layer {
    position: fixed; inset: 0; z-index: -1; overflow: hidden;
    background: var(--green-50);
}
.bg-layer::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%);
}
.bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}
.page-body {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px 64px;
}
@media (max-width: 768px) { .page-body { padding: 20px 16px 48px; } }
@media (max-width: 480px) { .page-body { padding: 16px 12px 48px; } }

.hero-banner {
    position: relative; overflow: hidden;
    border-radius: 20px;
    padding: 28px 28px 26px;
    margin-bottom: 24px;
    box-shadow: 0 8px 40px rgba(14,63,39,.3), 0 2px 8px rgba(0,0,0,.1);
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
    position: absolute; right: -80px; top: -80px;
    width: 300px; height: 300px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1);
    pointer-events: none; z-index: 1;
}
.hero-content { position: relative; z-index: 2; }
.hero-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: rgba(255,255,255,.55);
    margin-bottom: 12px; flex-wrap: wrap;
}
.hero-breadcrumb a { color: rgba(255,255,255,.75); text-decoration: none; font-weight: 600; }
.hero-breadcrumb a:hover { color: #fff; }
.hero-breadcrumb-sep { font-size: .6rem; opacity: .5; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 4px 12px; border-radius: 100px;
    font-size: .66rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82); margin-bottom: 10px;
}
.hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-row { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
.hero-title {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(1.3rem, 3.5vw, 1.9rem);
    color: #fff; line-height: 1.15; letter-spacing: -.025em;
    margin-bottom: 4px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .85rem; color: rgba(255,255,255,.62); line-height: 1.6; }
.hero-edit-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    color: #fff; font-size: .8rem; font-weight: 600;
    text-decoration: none; transition: all .2s; white-space: nowrap; flex-shrink: 0;
    font-family: 'Instrument Sans', sans-serif;
}
.hero-edit-btn:hover { background: rgba(255,255,255,.25); color: #fff; }

.alert-strip {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    font-size: .82rem; border: 1px solid; margin-bottom: 14px; line-height: 1.55;
}
.alert-strip.success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip.info    { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip.warning { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
.alert-strip.error   { background: var(--red-pale); border-color: rgba(220,38,38,.25); color: var(--red); }

.back-nav { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .8rem; font-weight: 600; color: var(--neutral-500);
    text-decoration: none; padding: 7px 14px; border-radius: 9px;
    border: 1px solid var(--neutral-200); background: var(--neutral-0); transition: all .18s;
}
.btn-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

.grid-2 { display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; }
@media (max-width: 1100px) { .grid-2 { grid-template-columns: 1fr; } }

.o-card {
    background: var(--neutral-0);
    border-radius: 16px;
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-bottom: 20px;
}
.o-card-header {
    padding: 14px 20px;
    border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 600; color: var(--neutral-700);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.o-card-header i { color: var(--green-500); }
.o-card-body { padding: 22px; }

.status-header {
    padding: 26px 28px; position: relative; overflow: hidden;
}
.status-header::before {
    content: ''; position: absolute; right: -50px; top: -50px;
    width: 200px; height: 200px; border-radius: 50%;
    border: 36px solid rgba(255,255,255,.07);
}
.status-header::after {
    content: ''; position: absolute; right: 60px; bottom: -30px;
    width: 120px; height: 120px; border-radius: 50%;
    border: 20px solid rgba(255,255,255,.04);
}
.sh-pending-admin   { background: linear-gradient(135deg, var(--green-900), var(--green-700)); }
.sh-pending-payment { background: linear-gradient(135deg, var(--green-900), var(--green-500)); }
.sh-approved        { background: linear-gradient(135deg, var(--green-900), var(--green-600)); }
.sh-rejected        { background: linear-gradient(135deg, #7f1d1d, var(--red)); }
.sh-icon-wrap {
    width: 50px; height: 50px; border-radius: 50%;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; margin-bottom: 12px;
    position: relative; z-index: 1;
}
.sh-title { font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 4px; position: relative; z-index: 1; letter-spacing: -.02em; }
.sh-sub   { font-size: .8rem; color: rgba(255,255,255,.72); position: relative; z-index: 1; line-height: 1.5; }
.sh-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.18); color: #fff;
    font-size: .76rem; font-weight: 600;
    padding: 5px 14px; border-radius: 100px;
    margin-top: 12px; position: relative; z-index: 1;
    border: 1px solid rgba(255,255,255,.25);
}

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
@media (max-width: 480px) { .info-grid { grid-template-columns: 1fr; } }
.info-lbl { font-size: .62rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 4px; }
.info-val { font-size: .88rem; font-weight: 600; color: var(--neutral-900); }

.svc-chip {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .7rem; font-weight: 600; padding: 3px 10px;
    border-radius: 100px; background: var(--green-50);
    color: var(--green-800); border: 1px solid var(--green-200); margin: 2px;
}

.cost-box {
    background: var(--green-50); border: 1.5px solid var(--green-200);
    border-radius: 12px; padding: 18px;
}
.cost-box-title {
    font-size: .62rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .07em; color: var(--green-700); margin-bottom: 14px;
}
.cost-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .82rem; padding: 7px 0; border-bottom: 1px solid rgba(15,31,24,.05);
}
.cost-row:last-child { border-bottom: none; }
.cost-row.total {
    border-top: 2px solid var(--green-200); border-bottom: none;
    padding-top: 13px; margin-top: 4px; font-weight: 700; font-size: 1rem;
}
.cost-label { color: var(--neutral-500); }
.cost-total-val { color: var(--green-700); font-weight: 700; }

.invoice-row {
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 12px; padding: 14px 0;
}
.inv-num { font-size: .9rem; font-weight: 600; color: var(--neutral-900); }
.inv-due { font-size: .74rem; color: var(--neutral-500); margin-top: 2px; }
.inv-amount { font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 700; color: var(--green-700); letter-spacing: -.015em; }

.badge { display: inline-flex; align-items: center; font-size: .68rem; font-weight: 600; padding: 3px 10px; border-radius: 100px; }
.badge-paid    { background: var(--green-100); color: var(--green-800); }
.badge-unpaid  { background: var(--amber-pale); color: #92400e; }
.badge-overdue { background: var(--red-pale); color: #991b1b; }
.badge-pending { background: var(--green-50); color: var(--green-700); border: 1px solid var(--green-200); }
.badge-default { background: var(--neutral-100); color: var(--neutral-500); }

.btn-pay {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 14px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 50%, var(--green-500) 100%);
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .9rem; font-weight: 600;
    text-decoration: none; cursor: pointer; transition: all .22s;
    box-shadow: 0 4px 16px rgba(42,179,107,.28); margin-top: 4px;
}
.btn-pay:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.38); color: #fff; }

.pay-item { padding: 14px 0; border-bottom: 1px solid var(--neutral-100); }
.pay-item:last-child { border-bottom: none; }
.pay-code   { font-size: .82rem; font-weight: 600; font-family: monospace; color: var(--neutral-900); }
.pay-meta   { font-size: .72rem; color: var(--neutral-500); margin-top: 2px; }
.pay-amount { font-family: 'Fraunces', serif; font-size: .9rem; font-weight: 700; color: var(--green-700); margin-bottom: 4px; text-align: right; letter-spacing: -.015em; }
.pay-status-ok { font-size: .7rem; color: var(--green-700); margin-top: 4px; }

.step-timeline { display: flex; flex-direction: column; }
.st-item { display: flex; gap: 14px; align-items: flex-start; }
.st-line { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
.st-dot {
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 600; flex-shrink: 0; z-index: 1;
}
.st-dot-done   { background: var(--green-600); color: #fff; box-shadow: 0 0 0 4px var(--green-100); }
.st-dot-active { background: var(--green-500); color: #fff; box-shadow: 0 0 0 4px rgba(42,179,107,.2); }
.st-dot-idle   { background: var(--neutral-100); color: var(--neutral-400); border: 1.5px solid var(--neutral-200); }
.st-connector  { width: 2px; flex: 1; min-height: 28px; background: var(--neutral-200); margin: 3px 0; }
.st-connector-done { background: var(--green-400); }
.st-body  { padding-bottom: 22px; flex: 1; }
.st-title { font-size: .84rem; font-weight: 600; color: var(--neutral-900); margin-bottom: 3px; }
.st-sub   { font-size: .75rem; color: var(--neutral-500); line-height: 1.5; }

.room-img { width: 100%; height: 140px; object-fit: cover; display: block; }
.room-placeholder {
    height: 110px;
    background: linear-gradient(135deg, var(--green-900), var(--green-600));
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.18); font-size: 3rem;
}

.action-panel { border: 1.5px solid var(--green-400) !important; }
.action-panel-header {
    background: linear-gradient(135deg, var(--green-900), var(--green-600));
    padding: 14px 20px; display: flex; align-items: center; gap: 9px;
    font-size: .82rem; font-weight: 600; color: #fff;
}
.action-panel-header i { opacity: .8; }
.total-box {
    background: var(--green-50); border: 1.5px solid var(--green-200);
    border-radius: 12px; padding: 18px; text-align: center; margin-bottom: 18px;
}
.total-box-label {
    font-size: .63rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .09em; color: var(--green-700); margin-bottom: 6px;
}
.total-box-amount {
    font-family: 'Fraunces', serif;
    font-size: 1.9rem; font-weight: 700; color: var(--green-700); line-height: 1;
    letter-spacing: -.025em;
}
.total-box-due { font-size: .74rem; color: var(--neutral-500); margin-top: 6px; }

.room-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
.room-info-item {
    background: var(--neutral-50); border: 1px solid var(--neutral-200);
    border-radius: 9px; padding: 10px 12px;
}
.room-info-item-label {
    font-size: .58rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .06em; color: var(--neutral-400); margin-bottom: 3px;
}
.room-info-item-val { font-size: .82rem; font-weight: 600; color: var(--neutral-900); }

@media (max-width: 480px) {
    .hero-banner { padding: 20px 16px; }
    .hero-arc    { display: none; }
    .hero-title  { font-size: 1.25rem; }
    .o-card-body { padding: 15px; }
    .o-card-header { padding: 12px 15px; }
    .status-header { padding: 20px 18px; }
    .room-info-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="bg-layer"><div class="bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-body">

    @php
        $shClass = match($renewal->status) {
            'pending_admin' => 'sh-approved',
            'pending_payment' => 'sh-pending-payment',
            'approved'        => 'sh-approved',
            'rejected'        => 'sh-rejected',
            default           => 'sh-pending-admin',
        };
        $shIcon = match($renewal->status) {
            'pending_admin'   => 'bi-hourglass-split',
            'pending_payment' => 'bi-credit-card-fill',
            'approved'        => 'bi-check-circle-fill',
            'rejected'        => 'bi-x-circle-fill',
            default           => 'bi-hourglass-split',
        };
        $heroBg = match($renewal->status) {
            'pending_admin' => 'linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 35%, var(--green-700) 100%)',
            'pending_payment' => 'linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 35%, var(--green-500) 100%)',
            'approved'        => 'linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 35%, var(--green-600) 100%)',
            'rejected'        => 'linear-gradient(135deg, #7f1d1d 0%, #991b1b 40%, #dc2626 100%)',
            default           => 'linear-gradient(135deg, var(--green-900), var(--green-600))',
        };
        $shTitle = match($renewal->status) {
            'pending_admin'   => 'Menunggu Persetujuan',
            'pending_payment' => 'Menunggu Pembayaran',
            'approved'        => 'Perpanjangan Selesai',
            'rejected'        => 'Perpanjangan Ditolak',
            default           => $renewal->status,
        };
        $shSub = match($renewal->status) {
            'pending_admin'   => 'Pengajuan #' . $renewal->id . ' sedang direview oleh admin',
            'pending_payment' => 'Selesaikan pembayaran sebelum jatuh tempo',
            'approved'        => 'Sewa berhasil diperpanjang. Periode baru sudah diperbarui',
            'rejected'        => ($renewal->rejection_reason ?? 'Hubungi admin untuk informasi lebih lanjut'),
            default           => '',
        };
    @endphp

    <div class="back-nav">
        <a href="{{ route('tenant.renewal.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i>Kembali
        </a>
    </div>

    <div class="hero-banner" style="background: {{ $heroBg }}">
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Detail Perpanjangan Sewa</div>
            <div class="hero-row">
                <div>
                    <h1 class="hero-title">{{ $shTitle }}<br><em>Kamar {{ $renewal->room?->room_number }}</em></h1>
                    <p class="hero-sub">{{ $shSub }}</p>
                    @if($renewal->status === 'approved' && $renewal->new_end_date)
                    <div style="margin-top:12px;display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.22);padding:5px 14px;border-radius:100px;font-size:.76rem;font-weight:600;color:#fff;">
                        <i class="bi bi-calendar-check"></i>
                        Sewa hingga {{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}
                    </div>
                    @endif
                </div>
                @if($renewal->status === 'pending_admin')
                <a href="{{ route('tenant.renewal.edit', $renewal->id) }}" class="hero-edit-btn">
                    <i class="bi bi-pencil-fill"></i> Edit Pengajuan
                </a>
                @endif
            </div>
        </div>
    </div>

    @foreach(['success','info','error','warning'] as $t)
    @if(session($t))
    <div class="alert-strip {{ $t === 'error' ? 'error' : $t }}">
        <i class="bi bi-{{ $t === 'success' ? 'check-circle-fill' : ($t === 'error' ? 'exclamation-circle-fill' : ($t === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill')) }}"></i>
        <span>{!! session($t) !!}</span>
    </div>
    @endif
    @endforeach

    <div class="grid-2">
        <div>
            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-file-text"></i> Detail Perpanjangan</div>
                <div class="o-card-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-lbl">Kamar</div>
                            <div class="info-val">
                                {{ $renewal->room?->room_number }}
                                @if($renewal->room?->room_name)
                                    <span style="font-weight:500;color:var(--neutral-500)"> — {{ $renewal->room->room_name }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="info-lbl">Durasi Perpanjangan</div>
                            <div class="info-val">{{ $renewal->duration_months }} Bulan</div>
                        </div>
                        <div>
                            <div class="info-lbl">Tanggal Pengajuan</div>
                            <div class="info-val">{{ $renewal->created_at->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-lbl">Diajukan Oleh</div>
                            <div class="info-val">
                                @if(($renewal->initiated_by ?? '') === 'tenant')
                                    <span style="background:var(--green-50);color:var(--green-700);padding:2px 10px;border-radius:100px;border:1px solid var(--green-200);font-size:.78rem">Saya sendiri</span>
                                @else
                                    <span style="background:var(--neutral-100);color:var(--neutral-500);padding:2px 10px;border-radius:100px;border:1px solid var(--neutral-200);font-size:.78rem">Dari sistem</span>
                                @endif
                            </div>
                        </div>
                        @if($renewal->new_end_date)
                        <div style="grid-column:span 2">
                            <div class="info-lbl">Tanggal Berakhir Baru</div>
                            <div class="info-val" style="color:var(--green-700)">
                                <i class="bi bi-calendar-check"></i>
                                {{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($renewal->additional_services && count($renewal->additional_services) > 0)
                    <div style="margin-bottom:18px">
                        <div style="font-size:.62rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--neutral-400);margin-bottom:8px">Layanan Tambahan</div>
                        <div>
                            @foreach($renewal->additional_services as $key => $svc)
                            <span class="svc-chip">
                                <i class="bi bi-check" style="font-size:.65rem"></i>
                                {{ $svc['label'] ?? $key }}
                                @if(isset($svc['price']))
                                    <span style="opacity:.7">(Rp {{ number_format($svc['price'], 0, ',', '.') }}/bln)</span>
                                @endif
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @php
                        $roomMonthly = $renewal->room?->price ?? 0;
                        $svcMonthly  = (int)($renewal->service_amount_monthly ?? 0);
                    @endphp
                    <div class="cost-box">
                        <div class="cost-box-title">Rincian Biaya</div>
                        <div class="cost-row">
                            <span class="cost-label">Harga sewa / bulan</span>
                            <span style="font-weight:600">Rp {{ number_format($roomMonthly, 0, ',', '.') }}</span>
                        </div>
                        @if($svcMonthly > 0)
                        <div class="cost-row">
                            <span class="cost-label">Layanan tambahan / bulan</span>
                            <span style="font-weight:600">Rp {{ number_format($svcMonthly, 0, ',', '.') }}</span>
                        </div>
                        <div class="cost-row">
                            <span class="cost-label">Total / bulan</span>
                            <span style="font-weight:600">Rp {{ number_format($roomMonthly + $svcMonthly, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="cost-row">
                            <span class="cost-label">Durasi</span>
                            <span style="font-weight:600">× {{ $renewal->duration_months }} bulan</span>
                        </div>
                        <div class="cost-row total">
                            <span>Total Tagihan</span>
                            <span class="cost-total-val">Rp {{ number_format($renewal->amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($renewal->invoice)
            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-receipt"></i> Invoice Perpanjangan</div>
                <div class="o-card-body">
                    <div class="invoice-row">
                        <div>
                            <div class="inv-num">{{ $renewal->invoice->invoice_number }}</div>
                            <div class="inv-due">Jatuh tempo: <strong>{{ $renewal->invoice->due_date?->format('d M Y') }}</strong></div>
                        </div>
                        <div style="text-align:right">
                            <div class="inv-amount">Rp {{ number_format($renewal->invoice->total_amount, 0, ',', '.') }}</div>
                            @php
                                $ibadge = match($renewal->invoice->status) {
                                    'paid'                 => 'badge-paid',
                                    'unpaid'               => 'badge-unpaid',
                                    'overdue'              => 'badge-overdue',
                                    'pending_verification' => 'badge-pending',
                                    default                => 'badge-default',
                                };
                                $ilabel = match($renewal->invoice->status) {
                                    'paid'                 => 'Lunas',
                                    'unpaid'               => 'Belum Dibayar',
                                    'overdue'              => 'Terlambat',
                                    'pending_verification' => 'Sedang Diverifikasi',
                                    default                => $renewal->invoice->status,
                                };
                            @endphp
                            <span class="badge {{ $ibadge }}">{{ $ilabel }}</span>
                        </div>
                    </div>
                    @if(in_array($renewal->invoice->status, ['unpaid', 'overdue']))
                    <a href="{{ route('tenant.renewal.pay-midtrans', $renewal->id) }}" class="btn-pay">
                        <i class="bi bi-qr-code-scan"></i> Bayar dengan QRIS 
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if(isset($payments) && $payments->count() > 0)
            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</div>
                <div style="padding:8px 20px">
                    @foreach($payments as $pay)
                    <div class="pay-item">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
                            <div>
                                <div class="pay-code">{{ $pay->payment_code }}</div>
                                <div class="pay-meta">Midtrans · {{ $pay->created_at->format('d M Y') }}</div>
                                @if($pay->status === 'verified')
                                <div class="pay-status-ok">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Terkonfirmasi otomatis {{ $pay->verified_at?->format('d M Y') }}
                                </div>
                                @endif
                            </div>
                            <div>
                                <div class="pay-amount">Rp {{ number_format($pay->amount, 0, ',', '.') }}</div>
                                @php
                                    $payBadge = match($pay->status) {
                                        'verified' => 'badge-paid',
                                        'pending'  => 'badge-pending',
                                        default    => 'badge-overdue',
                                    };
                                    $payLabel = match($pay->status) {
                                        'verified' => 'Terverifikasi',
                                        'pending'  => 'Menunggu',
                                        'rejected' => 'Gagal',
                                        default    => $pay->status,
                                    };
                                @endphp
                                <span class="badge {{ $payBadge }}" style="float:right">{{ $payLabel }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div>
            @if($renewal->status === 'pending_payment' && $renewal->invoice && in_array($renewal->invoice->status, ['unpaid', 'overdue']) && !$renewal->invoice->payments->where('status', 'pending')->count())
            <div class="o-card action-panel">
                <div class="action-panel-header">
                    <i class="bi bi-credit-card"></i>
                    <span>Selesaikan Pembayaran</span>
                </div>
                <div class="o-card-body">
                    <div class="total-box">
                        <div class="total-box-label">Total Tagihan</div>
                        <div class="total-box-amount">Rp {{ number_format($renewal->invoice->total_amount, 0, ',', '.') }}</div>
                        <div class="total-box-due">
                            Jatuh tempo: <strong>{{ $renewal->invoice->due_date?->format('d M Y') }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('tenant.renewal.pay-midtrans', $renewal->id) }}" class="btn-pay">
                        <i class="bi bi-qr-code-scan"></i><span>Bayar dengan QRIS</span> 
                    </a>
                    <div style="text-align:center;margin-top:10px;font-size:.72rem;color:var(--neutral-500)">
                        <i class="bi bi-shield-check" style="color:var(--green-600)"></i> Aman &amp; dikonfirmasi otomatis
                    </div>
                </div>
            </div>
            @endif

            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-list-check"></i> Alur Perpanjangan</div>
                <div style="padding:20px">
                    @php
                        $step1done   = $renewal->status !== 'pending_admin';
                        $step1active = $renewal->status === 'pending_admin';
                        $step2done   = $renewal->status === 'approved';
                        $step2active = $renewal->status === 'pending_payment';
                        $step3done   = $renewal->status === 'approved';
                    @endphp
                    <div class="step-timeline">
                        <div class="st-item">
                            <div class="st-line">
                                <div class="st-dot {{ $step1done ? 'st-dot-done' : ($step1active ? 'st-dot-active' : 'st-dot-idle') }}">
                                    @if($step1done)<i class="bi bi-check" style="font-size:.9rem"></i>@else 1 @endif
                                </div>
                                <div class="st-connector {{ $step1done ? 'st-connector-done' : '' }}"></div>
                            </div>
                            <div class="st-body">
                                <div class="st-title">Pengajuan Dikirim</div>
                                <div class="st-sub">Admin mereview &amp; menerbitkan invoice dengan rincian biaya</div>
                            </div>
                        </div>
                        <div class="st-item">
                            <div class="st-line">
                                <div class="st-dot {{ $step2done ? 'st-dot-done' : ($step2active ? 'st-dot-active' : 'st-dot-idle') }}">
                                    @if($step2done)<i class="bi bi-check" style="font-size:.9rem"></i>@else 2 @endif
                                </div>
                                <div class="st-connector {{ $step2done ? 'st-connector-done' : '' }}"></div>
                            </div>
                            <div class="st-body">
                                <div class="st-title">Bayar via Midtrans</div>
                                <div class="st-sub">QRIS atau Virtual Account Bank, terkonfirmasi otomatis</div>
                            </div>
                        </div>
                        <div class="st-item">
                            <div class="st-line">
                                <div class="st-dot {{ $step3done ? 'st-dot-done' : 'st-dot-idle' }}">
                                    @if($step3done)<i class="bi bi-check" style="font-size:.9rem"></i>@else 3 @endif
                                </div>
                            </div>
                            <div class="st-body" style="padding-bottom:0">
                                <div class="st-title">Sewa Diperpanjang Otomatis</div>
                                <div class="st-sub">
                                    @if($step3done)
                                        Tanggal akhir sewa diperbarui
                                        @if($renewal->new_end_date)
                                            <br><strong style="color:var(--green-700)">Hingga {{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}</strong>
                                        @endif
                                    @else
                                        Tanggal akhir sewa diperbarui otomatis setelah bayar
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($renewal->room)
            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-house"></i> Info Kamar</div>
                @if($renewal->room->image_path)
                    <img src="{{ asset('storage/' . $renewal->room->image_path) }}" alt="Foto Kamar" class="room-img">
                @else
                    <div class="room-placeholder"><i class="bi bi-house-door-fill"></i></div>
                @endif
                <div class="o-card-body">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:12px">
                        <div>
                            <div style="font-family:'Fraunces',serif;font-size:.95rem;font-weight:700;color:var(--neutral-900);margin-bottom:2px;letter-spacing:-.015em">
                                {{ $renewal->room->room_name ?? 'Kamar ' . $renewal->room->room_number }}
                            </div>
                            <div style="font-size:.74rem;color:var(--neutral-500)">
                                No. {{ $renewal->room->room_number }}
                            </div>
                        </div>
                        @if($renewal->room->status ?? null)
                        <span style="font-size:.68rem;font-weight:600;padding:3px 10px;border-radius:100px;
                            background:{{ $renewal->room->status === 'occupied' ? 'var(--green-100)' : 'var(--neutral-100)' }};
                            color:{{ $renewal->room->status === 'occupied' ? 'var(--green-800)' : 'var(--neutral-500)' }};
                            border:1px solid {{ $renewal->room->status === 'occupied' ? 'var(--green-200)' : 'var(--neutral-200)' }}">
                            {{ $renewal->room->status === 'occupied' ? 'Ditempati' : ucfirst($renewal->room->status) }}
                        </span>
                        @endif
                    </div>

                    <div style="background:var(--green-50);border:1.5px solid var(--green-200);border-radius:10px;padding:12px 14px;margin-bottom:14px;display:flex;align-items:center;gap:10px">
                        <i class="bi bi-tag-fill" style="color:var(--green-600);font-size:1rem"></i>
                        <div>
                            <div style="font-size:.6rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--green-700);margin-bottom:1px">Harga Sewa</div>
                            <div style="font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--green-700);line-height:1;letter-spacing:-.02em">
                                {{ $renewal->room->formatted_price ?? 'Rp ' . number_format($renewal->room->price ?? 0, 0, ',', '.') }}
                                <span style="font-family:'Instrument Sans',sans-serif;font-size:.72rem;font-weight:500;color:var(--neutral-500)">/bulan</span>
                            </div>
                        </div>
                    </div>

                    @if($renewal->room->room_type || $renewal->room->floor || $renewal->room->capacity || $renewal->room->size)
                    <div class="room-info-grid">
                        @if($renewal->room->room_type ?? null)
                        <div class="room-info-item">
                            <div class="room-info-item-label">Tipe Kamar</div>
                            <div class="room-info-item-val"><i class="bi bi-door-open" style="color:var(--green-600)"></i> {{ ucfirst($renewal->room->room_type) }}</div>
                        </div>
                        @endif
                        @if($renewal->room->floor ?? null)
                        <div class="room-info-item">
                            <div class="room-info-item-label">Lantai</div>
                            <div class="room-info-item-val"><i class="bi bi-building" style="color:var(--green-600)"></i> Lantai {{ $renewal->room->floor }}</div>
                        </div>
                        @endif
                        @if($renewal->room->capacity ?? null)
                        <div class="room-info-item">
                            <div class="room-info-item-label">Kapasitas</div>
                            <div class="room-info-item-val"><i class="bi bi-people" style="color:var(--green-600)"></i> {{ $renewal->room->capacity }} Orang</div>
                        </div>
                        @endif
                        @if($renewal->room->size ?? null)
                        <div class="room-info-item">
                            <div class="room-info-item-label">Ukuran</div>
                            <div class="room-info-item-val"><i class="bi bi-aspect-ratio" style="color:var(--green-600)"></i> {{ $renewal->room->size }} m²</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($renewal->room->facilities && count($renewal->room->facilities) > 0)
                    <div style="margin-bottom:14px">
                        <div style="font-size:.6rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--neutral-400);margin-bottom:8px">Fasilitas</div>
                        <div style="display:flex;flex-wrap:wrap;gap:5px">
                            @foreach($renewal->room->facilities as $facility)
                            <span class="svc-chip">
                                <i class="bi bi-check-circle-fill" style="font-size:.65rem"></i>
                                {{ is_string($facility) ? $facility : ($facility['name'] ?? $facility) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($renewal->room->description ?? null)
                    <div style="padding-top:12px;border-top:1px solid var(--neutral-100)">
                        <div style="font-size:.6rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--neutral-400);margin-bottom:6px">Deskripsi</div>
                        <div style="font-size:.79rem;color:var(--neutral-700);line-height:1.6">{{ $renewal->room->description }}</div>
                    </div>
                    @endif

                    @if(Route::has('tenant.room.show'))
                    <a href="{{ route('tenant.room.show', $renewal->room->id) }}"
                       style="display:flex;align-items:center;justify-content:center;gap:7px;margin-top:14px;
                              padding:9px;border-radius:9px;border:1px solid var(--green-200);
                              background:var(--green-50);color:var(--green-700);font-size:.78rem;font-weight:600;
                              text-decoration:none;transition:all .18s"
                       onmouseover="this.style.background='var(--green-100)'"
                       onmouseout="this.style.background='var(--green-50)'">
                        <i class="bi bi-eye"></i> Lihat Detail Kamar
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>