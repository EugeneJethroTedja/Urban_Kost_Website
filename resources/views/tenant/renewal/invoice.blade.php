<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice Perpanjangan Sewa — Urban Kost</title>
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
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-light:  #dbeafe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
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
    padding: 32px 24px 60px;
}
@media (max-width: 768px) { .page-body { padding: 20px 16px 60px; } }
@media (max-width: 480px) { .page-body { padding: 16px 12px 60px; } }

.alert-strip {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    font-size: .82rem; border: 1px solid;
    margin-bottom: 16px; line-height: 1.55;
}
.alert-strip.success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip.info    { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip.warning { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
.alert-strip.error   { background: var(--red-pale); border-color: rgba(220,38,38,.25); color: var(--red); }

.back-nav { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; flex-wrap: wrap; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .8rem; font-weight: 600; color: var(--neutral-500);
    text-decoration: none; padding: 7px 14px; border-radius: 9px;
    border: 1px solid var(--neutral-200); background: var(--neutral-0);
    transition: all .18s;
}
.btn-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

.grid-2 { display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
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

.inv-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 35%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 28px 30px; position: relative; overflow: hidden;
}

.inv-tag {
    font-size: .63rem; font-weight: 600; letter-spacing: .12em;
    text-transform: uppercase; color: var(--green-300); margin-bottom: 10px;
    position: relative; z-index: 1;
}
.inv-number {
    font-size: .78rem; font-weight: 600; color: rgba(255,255,255,.55);
    margin-bottom: 6px; position: relative; z-index: 1; font-family: monospace;
}
.inv-amount {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 4vw, 2.4rem); font-weight: 700; color: #fff; line-height: 1;
    position: relative; z-index: 1; letter-spacing: -.025em;
}
.inv-sub {
    font-size: .78rem; color: rgba(255,255,255,.6);
    margin-top: 10px; position: relative; z-index: 1; line-height: 1.6;
}
.inv-sub strong { color: rgba(255,255,255,.9); }
.inv-status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 600; padding: 5px 14px;
    border-radius: 100px; border: 1px solid rgba(255,255,255,.22);
    position: relative; z-index: 1; margin-top: 16px;
}

.cost-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .83rem; padding: 9px 0; border-bottom: 1px solid var(--neutral-100);
}
.cost-row:last-child { border-bottom: none; }
.cost-row.total {
    border-top: 2px solid var(--neutral-200); border-bottom: none;
    padding-top: 14px; margin-top: 4px; font-weight: 700; font-size: 1rem;
}
.cost-label { color: var(--neutral-500); }
.cost-total-val { color: var(--green-700); font-weight: 700; font-size: 1.05rem; }

.svc-chip {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .7rem; font-weight: 600; padding: 3px 10px;
    border-radius: 100px; background: var(--green-50); color: var(--green-800);
    border: 1px solid var(--green-200); margin: 2px;
}

.pay-item { padding: 14px 0; border-bottom: 1px solid var(--neutral-100); }
.pay-item:last-child { border-bottom: none; }
.pay-code { font-size: .82rem; font-weight: 600; font-family: monospace; color: var(--neutral-900); }
.pay-meta { font-size: .72rem; color: var(--neutral-500); margin-top: 2px; }
.pay-note { font-size: .72rem; margin-top: 4px; }
.pay-note-ok      { color: var(--green-700); }
.pay-note-bad     { color: var(--red); }
.pay-note-pending { color: var(--green-600); }
.pay-badge {
    display: inline-block; font-size: .65rem; font-weight: 600;
    padding: 2px 9px; border-radius: 100px;
}
.pay-verified { background: var(--green-100); color: var(--green-800); }
.pay-pending  { background: var(--green-50);  color: var(--green-700); border: 1px solid var(--green-200); }
.pay-rejected { background: var(--red-pale);  color: var(--red); }

.action-card { border: 1.5px solid var(--green-400) !important; }
.action-header {
    background: linear-gradient(135deg, var(--green-900), var(--green-600));
    padding: 14px 20px; display: flex; align-items: center; gap: 9px;
    font-size: .82rem; font-weight: 600; color: #fff;
}
.total-box {
    background: var(--green-50); border: 1.5px solid var(--green-200);
    border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 18px;
}
.total-box-lbl {
    font-size: .63rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .09em; color: var(--green-700); margin-bottom: 8px;
}
.total-box-amt {
    font-family: 'Fraunces', serif;
    font-size: 1.9rem; font-weight: 700; color: var(--green-700); line-height: 1;
    letter-spacing: -.025em;
}
.total-box-due { font-size: .74rem; color: var(--neutral-500); margin-top: 8px; }
.btn-pay {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 14px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 50%, var(--green-500) 100%);
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .9rem; font-weight: 600;
    text-decoration: none; cursor: pointer; transition: all .22s;
    box-shadow: 0 4px 18px rgba(42,179,107,.28);
}
.btn-pay:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.38); color: #fff; }
.btn-pay .sub-label { font-size: .68rem; font-weight: 400; opacity: .85; }

.sp-paid    { border: 1.5px solid var(--green-400) !important; }
.sp-pending { border: 1.5px solid var(--green-300) !important; }
.sp-body { padding: 32px 22px; text-align: center; }
.sp-icon {
    width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin: 0 auto 14px;
}
.sp-title { font-family: 'Fraunces', serif; font-weight: 700; font-size: 1rem; margin-bottom: 6px; letter-spacing: -.015em; }
.sp-sub   { font-size: .82rem; color: var(--neutral-500); line-height: 1.6; }

.step-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
.step-num {
    width: 24px; height: 24px; border-radius: 50%;
    background: var(--green-600); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .63rem; font-weight: 600; flex-shrink: 0; margin-top: 1px;
}
.step-text { font-size: .8rem; color: var(--neutral-600); line-height: 1.55; }
.info-auto {
    background: var(--green-50); border: 1px solid var(--green-200);
    border-radius: 10px; padding: 12px 14px;
    font-size: .76rem; color: var(--green-800); margin-top: 6px;
}

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

.empty-state { padding: 50px 24px; text-align: center; }
.empty-icon {
    width: 52px; height: 52px; border-radius: 50%;
    background: var(--green-100); color: var(--green-500);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; margin: 0 auto 14px;
}
.empty-title { font-family: 'Fraunces', serif; font-weight: 700; font-size: 1rem; margin-bottom: 8px; letter-spacing: -.015em; }
.empty-sub   { font-size: .83rem; color: var(--neutral-500); margin-bottom: 18px; line-height: 1.5; }
.btn-outline-sm {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px; font-size: .8rem; font-weight: 600;
    border: 1px solid var(--green-200); color: var(--green-700); background: var(--green-50);
    text-decoration: none; transition: all .18s;
}
.btn-outline-sm:hover { background: var(--green-100); border-color: var(--green-300); }

.hero-banner {
    position: relative; overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 30px 28px;
    margin-bottom: 24px;
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
    position: absolute; right: -80px; top: -80px;
    width: 300px; height: 300px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1);
    pointer-events: none; z-index: 1;
}
.hero-content { position: relative; z-index: 2; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82); margin-bottom: 12px;
}
.hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: 'Fraunces', serif; font-weight: 700;
    font-size: clamp(1.3rem, 3.5vw, 1.9rem);
    color: #fff; line-height: 1.15; letter-spacing: -.025em; margin-bottom: 6px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .85rem; color: rgba(255,255,255,.62); line-height: 1.6; }
.hero-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: rgba(255,255,255,.55);
    margin-bottom: 12px; flex-wrap: wrap;
}
.hero-breadcrumb a { color: rgba(255,255,255,.75); text-decoration: none; font-weight: 600; }
.hero-breadcrumb a:hover { color: #fff; }
.hero-breadcrumb-sep { font-size: .6rem; opacity: .5; }
@media (max-width: 480px) {
    .hero-banner { padding: 20px 16px; }
    .hero-arc    { display: none; }
    .hero-title  { font-size: 1.2rem; }
}
</style>
</head>
<body>

<div class="bg-layer"><div class="bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-body">
    <div class="back-nav">
        <a href="{{ route('tenant.renewal.show', $renewal->id) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i>Ke Detail
        </a>
        <a href="{{ route('tenant.renewal.index') }}" class="btn-back">
            <i class="bi bi-list"></i>Semua Perpanjangan
        </a>
    </div>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Bayar Perpanjangan Sewa</div>
            <h1 class="hero-title">Invoice<br><em>Perpanjangan Sewa</em></h1>
            <p class="hero-sub">Detail tagihan dan riwayat pembayaran perpanjangan Anda.</p>
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
            @if($invoice)
                <div class="o-card">
                    <div class="inv-hero">
                        <div class="inv-tag">Invoice Perpanjangan</div>
                        <div class="inv-number">{{ $invoice->invoice_number }}</div>
                        <div class="inv-amount">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                        <div class="inv-sub">
                            Jatuh tempo: <strong>{{ $invoice->due_date?->format('d M Y') }}</strong>
                            &nbsp;·&nbsp; Kamar {{ $renewal->room?->room_number }}
                            &nbsp;·&nbsp; {{ $renewal->duration_months }} bulan
                        </div>
                        @php
                            $statusMap = [
                                'paid'                 => ['label' => 'Lunas',             'bg' => 'rgba(78,201,138,.2)'],
                                'unpaid'               => ['label' => 'Belum Dibayar',      'bg' => 'var(--amber)','color' => '#fff'],
                                'overdue'              => ['label' => 'Terlambat',          'bg' => 'rgba(220,38,38,.22)'],
                                'pending_verification' => ['label' => 'Sedang Diverifikasi','bg' => 'rgba(42,179,107,.2)'],
                            ];
                            $ibadge = $statusMap[$invoice->status] ?? ['label' => $invoice->status, 'bg' => 'rgba(255,255,255,.1)'];
                        @endphp
                        <div class="inv-status-badge" style="background:{{ $ibadge['bg'] }}">
                            {{ $ibadge['label'] }}
                        </div>
                    </div>
                </div>

                <div class="o-card">
                    <div class="o-card-header"><i class="bi bi-file-earmark-text"></i> Rincian Tagihan</div>
                    <div class="o-card-body">
                        <div class="cost-row">
                            <span class="cost-label">Kamar</span>
                            <span style="font-weight:600">
                                {{ $renewal->room?->room_number }}
                                @if($renewal->room?->room_name) — {{ $renewal->room->room_name }}@endif
                            </span>
                        </div>
                        <div class="cost-row">
                            <span class="cost-label">Harga sewa / bulan</span>
                            <span style="font-weight:600">
                                {{ $renewal->room?->formatted_price ?? 'Rp ' . number_format($renewal->room?->price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        @if($invoice->service_amount && $invoice->service_amount > 0)
                            @php $svcMonthly = $renewal->duration_months > 0 ? $invoice->service_amount / $renewal->duration_months : 0; @endphp
                            <div class="cost-row">
                                <span class="cost-label">Layanan tambahan / bulan</span>
                                <span style="font-weight:600">Rp {{ number_format($svcMonthly, 0, ',', '.') }}</span>
                            </div>
                            @if($renewal->additional_services && count($renewal->additional_services) > 0)
                            <div class="cost-row" style="flex-direction:column;align-items:flex-start;gap:8px">
                                <span class="cost-label" style="font-size:.68rem">Detail layanan</span>
                                <div style="display:flex;flex-wrap:wrap">
                                    @foreach($renewal->additional_services as $key => $svc)
                                        <span class="svc-chip">{{ $svc['label'] ?? $key }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endif
                        <div class="cost-row">
                            <span class="cost-label">Durasi perpanjangan</span>
                            <span style="font-weight:600">{{ $renewal->duration_months }} bulan</span>
                        </div>
                        @if($invoice->period_start && $invoice->period_end)
                        <div class="cost-row">
                            <span class="cost-label">Periode</span>
                            <span style="font-weight:600">
                                {{ $invoice->period_start->format('d M Y') }} — {{ $invoice->period_end->format('d M Y') }}
                            </span>
                        </div>
                        @endif
                        <div class="cost-row total">
                            <span>Total Tagihan</span>
                            <span class="cost-total-val">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="o-card">
                    <div class="o-card-header"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</div>
                    <div style="padding: 8px 20px">
                        @forelse($payments as $pay)
                            <div class="pay-item">
                                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
                                    <div>
                                        <div class="pay-code">{{ $pay->payment_code }}</div>
                                        <div class="pay-meta">Midtrans · {{ $pay->created_at->format('d M Y') }}</div>
                                        @if($pay->status === 'verified')
                                            <div class="pay-note pay-note-ok">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Terkonfirmasi otomatis
                                                @if($pay->verified_at) · {{ $pay->verified_at->format('d M Y') }}@endif
                                            </div>
                                        @elseif($pay->status === 'rejected')
                                            <div class="pay-note pay-note-bad">
                                                <i class="bi bi-x-circle-fill"></i> Gagal / Kadaluarsa
                                            </div>
                                        @elseif($pay->status === 'pending')
                                            <div class="pay-note pay-note-pending">
                                                <i class="bi bi-hourglass-split"></i> Menunggu konfirmasi Midtrans
                                            </div>
                                        @endif
                                    </div>
                                    <div style="text-align:right;flex-shrink:0">
                                        <div style="font-size:.92rem;font-weight:700;color:var(--green-700);margin-bottom:4px">
                                            Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                        </div>
                                        @php
                                            $payLabel = match($pay->status) {
                                                'verified' => 'Terverifikasi',
                                                'pending'  => 'Menunggu',
                                                'rejected' => 'Gagal',
                                                default    => $pay->status,
                                            };
                                        @endphp
                                        <span class="pay-badge pay-{{ $pay->status }}">{{ $payLabel }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align:center;padding:32px;color:var(--neutral-400);font-size:.85rem">
                                <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.4"></i>
                                Belum ada riwayat pembayaran
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="o-card">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <div class="empty-title">Invoice Belum Tersedia</div>
                        <p class="empty-sub">Invoice diterbitkan setelah admin menyetujui pengajuan perpanjangan Anda.</p>
                        <a href="{{ route('tenant.renewal.show', $renewal->id) }}" class="btn-outline-sm">
                            <i class="bi bi-arrow-left"></i>Lihat Status Pengajuan
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div>
            @if($invoice)
                @if(in_array($invoice->status, ['unpaid', 'overdue']) && !$payments->where('status', 'pending')->count())
                    <div class="o-card action-card">
                        <div class="action-header">
                            <i class="bi bi-credit-card"></i>
                            <span>Selesaikan Pembayaran</span>
                        </div>
                        <div class="o-card-body">
                            <div class="total-box">
                                <div class="total-box-lbl">Total Tagihan</div>
                                <div class="total-box-amt">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                                <div class="total-box-due">
                                    Jatuh tempo: <strong>{{ $invoice->due_date?->format('d M Y') }}</strong>
                                    @if($invoice->status === 'overdue')
                                        <span style="display:block;color:var(--red);font-weight:600;margin-top:3px">Sudah terlambat</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('tenant.renewal.pay-midtrans', $renewal->id) }}" class="btn-pay">
                                <i class></i><span>Pilih Metode Pembayaran</span>
                            </a>
                            <div style="text-align:center;margin-top:10px;font-size:.72rem;color:var(--neutral-500)">
                                <i class="bi bi-shield-check" style="color:var(--green-600)"></i> Aman &amp; dikonfirmasi otomatis
                            </div>
                        </div>
                    </div>
                @elseif($invoice->status === 'paid')
                    <div class="o-card sp-paid">
                        <div class="sp-body">
                            <div class="sp-icon" style="background:var(--green-100);color:var(--green-600)">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="sp-title" style="color:var(--green-800)">Invoice Lunas</div>
                            <p class="sp-sub">
                                Pembayaran diterima &amp; dikonfirmasi otomatis.
                                @if($renewal->new_end_date)
                                    <br>Sewa diperpanjang hingga <strong>{{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}</strong>.
                                @endif
                            </p>
                        </div>
                    </div>
                @elseif($invoice->status === 'pending_verification')
                    <div class="o-card sp-pending">
                        <div class="sp-body">
                            <div class="sp-icon" style="background:var(--green-50);color:var(--green-500)">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="sp-title" style="color:var(--green-700)">Menunggu Konfirmasi</div>
                            <p class="sp-sub">Pembayaran sedang diverifikasi oleh Midtrans. Terkonfirmasi otomatis setelah berhasil.</p>
                        </div>
                    </div>
                @endif
            @endif

            @if($invoice && in_array($invoice->status, ['unpaid', 'overdue']))
            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-info-circle"></i> Cara Pembayaran</div>
                <div class="o-card-body">
                    @foreach([
                        'Klik tombol "Bayar via Midtrans" di atas',
                        'Pilih metode: QRIS atau Virtual Account Bank',
                        'Ikuti instruksi di popup Midtrans',
                        'Pembayaran terkonfirmasi otomatis (sewa langsung diperpanjang)',
                    ] as $i => $stepText)
                    <div class="step-item">
                        <div class="step-num">{{ $i + 1 }}</div>
                        <div class="step-text">{{ $stepText }}</div>
                    </div>
                    @endforeach
                    <div class="info-auto">
                        <i class="bi bi-shield-check" style="color:var(--green-600)"></i>
                        <strong>Otomatis:</strong> Setelah pembayaran berhasil, masa sewa langsung diperbarui tanpa konfirmasi admin.
                    </div>
                </div>
            </div>
            @endif

            <div class="o-card">
                <div class="o-card-header"><i class="bi bi-list-check"></i> Status Perpanjangan</div>
                <div style="padding:20px">
                    @php
                        $step1done   = $renewal->status !== 'pending_admin';
                        $step2done   = $renewal->status === 'approved';
                        $step2active = $renewal->status === 'pending_payment';
                        $step3done   = $renewal->status === 'approved';
                    @endphp
                    <div class="step-timeline">
                        <div class="st-item">
                            <div class="st-line">
                                <div class="st-dot {{ $step1done ? 'st-dot-done' : 'st-dot-active' }}">
                                    @if($step1done)<i class="bi bi-check" style="font-size:.9rem"></i>@else 1 @endif
                                </div>
                                <div class="st-connector {{ $step1done ? 'st-connector-done' : '' }}"></div>
                            </div>
                            <div class="st-body">
                                <div class="st-title">Admin setujui &amp; terbitkan invoice</div>
                                <div class="st-sub">{{ $step1done ? 'Selesai' : 'Dalam proses...' }}</div>
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
                                <div class="st-title">Bayar invoice via Midtrans</div>
                                <div class="st-sub">
                                    @if($step2done) Lunas
                                    @elseif($step2active) Menunggu pembayaran Anda
                                    @else Menunggu...
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="st-item">
                            <div class="st-line">
                                <div class="st-dot {{ $step3done ? 'st-dot-done' : 'st-dot-idle' }}">
                                    @if($step3done)<i class="bi bi-check" style="font-size:.9rem"></i>@else 3 @endif
                                </div>
                            </div>
                            <div class="st-body" style="padding-bottom:0">
                                <div class="st-title">Sewa diperpanjang otomatis</div>
                                <div class="st-sub">
                                    @if($step3done)
                                        Tanggal akhir sewa diperbarui
                                        @if($renewal->new_end_date)
                                            <br><strong style="color:var(--green-700)">Hingga {{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}</strong>
                                        @endif
                                    @else
                                        Otomatis setelah pembayaran berhasil
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>