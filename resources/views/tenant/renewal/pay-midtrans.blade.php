<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bayar Perpanjangan {{ $renewal->room?->room_number }} — Urban Kost</title>
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

.pay-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 35%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 26px 28px; position: relative; overflow: hidden;
}

.pay-hero-lbl {
    font-size: .63rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .1em; color: var(--green-300); margin-bottom: 8px;
    position: relative; z-index: 1;
}
.pay-hero-amount {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 4vw, 2.4rem); font-weight: 700; color: #fff; line-height: 1;
    position: relative; z-index: 1; letter-spacing: -.025em;
}
.pay-hero-sub {
    font-size: .78rem; color: rgba(255,255,255,.62);
    margin-top: 8px; position: relative; z-index: 1;
}
.pay-hero-sub strong { color: rgba(255,255,255,.9); }

.step-label {
    font-size: .62rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .1em; color: var(--neutral-400); margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.step-label::before {
    content: attr(data-n);
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--green-100); color: var(--green-700);
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 600; flex-shrink: 0;
}

.method-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 12px; margin-bottom: 24px;
}
@media (max-width: 400px) { .method-grid { grid-template-columns: 1fr; } }
.method-card {
    border: 1.5px solid var(--neutral-200); border-radius: 14px;
    padding: 18px 12px; cursor: pointer; transition: all .2s;
    background: var(--neutral-0); display: flex; flex-direction: column;
    align-items: center; gap: 6px; position: relative;
    user-select: none; text-align: center;
}
.method-card:hover { border-color: var(--green-400); background: var(--green-50); }
.method-card.selected {
    border-color: var(--green-500);
    background: var(--green-50);
    box-shadow: 0 0 0 3px rgba(42,179,107,.10);
}
.method-card.selected::after {
    content: '\F26B';
    font-family: 'bootstrap-icons';
    position: absolute; top: 10px; right: 12px;
    color: var(--green-600); font-size: .9rem;
}
.method-emoji { font-size: 2.2rem; line-height: 1; }
.method-name  { font-size: .85rem; font-weight: 600; color: var(--neutral-900); }
.method-desc  { font-size: .67rem; color: var(--neutral-400); line-height: 1.4; }

.bank-section {
    display: none; background: var(--neutral-50);
    border: 1px solid var(--neutral-200); border-radius: 12px;
    padding: 16px; margin-bottom: 24px;
}
.bank-section.show { display: block; }
.bank-section-label {
    font-size: .62rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .08em; color: var(--neutral-500); margin-bottom: 12px;
}
.bank-pills { display: flex; flex-wrap: wrap; gap: 8px; }
.bank-pill {
    padding: 7px 18px; border-radius: 100px;
    border: 1.5px solid var(--neutral-200);
    font-size: .78rem; font-weight: 600;
    color: var(--neutral-600); cursor: pointer; background: var(--neutral-0); transition: all .18s;
}
.bank-pill:hover { border-color: var(--green-500); color: var(--green-700); background: var(--green-50); }
.bank-pill.selected { background: var(--green-600); border-color: var(--green-600); color: #fff; }

.btn-pay-main {
    width: 100%; padding: 15px; border-radius: 12px; border: none;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 50%, var(--green-500) 100%);
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .95rem; font-weight: 600;
    cursor: pointer; transition: all .22s;
    display: flex; align-items: center; justify-content: center; gap: 9px;
    box-shadow: 0 4px 18px rgba(42,179,107,.28);
    margin-bottom: 12px;
}
.btn-pay-main:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(42,179,107,.38);
}
.btn-pay-main:disabled {
    background: var(--neutral-200); color: var(--neutral-400);
    cursor: not-allowed; box-shadow: none; transform: none;
}
.btn-pay-main.state-pending {
    background: linear-gradient(135deg, var(--green-500), var(--green-600)) !important;
    cursor: default;
}
.btn-pay-main.state-success {
    background: linear-gradient(135deg, var(--green-600), var(--green-700)) !important;
    cursor: default;
}

.pending-banner {
    display: none; margin-top: 14px; padding: 16px 18px;
    border-radius: 12px; background: var(--green-50);
    border: 1px solid var(--green-200);
    font-size: .8rem; color: var(--green-800); line-height: 1.6;
}
.pending-banner.show { display: block; }
.pending-banner a { color: var(--green-700); font-weight: 600; }

.security-note {
    text-align: center; font-size: .72rem; color: var(--neutral-400);
    display: flex; align-items: center; justify-content: center;
    gap: 6px; margin-top: 4px;
}

.room-img { width: 100%; height: 150px; object-fit: cover; display: block; }
.room-placeholder {
    height: 110px;
    background: linear-gradient(135deg, var(--green-900), var(--green-600));
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.18); font-size: 3rem;
}

.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .82rem; padding: 8px 0; border-bottom: 1px solid var(--neutral-100);
}
.summary-row:last-child { border-bottom: none; }
.summary-row.total {
    border-top: 2px solid var(--neutral-200); border-bottom: none;
    padding-top: 13px; margin-top: 4px;
    font-weight: 700; font-size: 1rem;
}
.sum-label     { color: var(--neutral-500); }
.sum-total-val {
    font-family: 'Fraunces', serif;
    color: var(--green-700); font-size: 1.1rem; font-weight: 700;
    letter-spacing: -.02em;
}
.svc-chip {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .68rem; font-weight: 600; padding: 2px 9px;
    border-radius: 100px; background: var(--green-50); color: var(--green-800);
    border: 1px solid var(--green-200); margin: 2px;
}
.info-auto {
    background: var(--green-50); border: 1px solid var(--green-200);
    border-radius: 10px; padding: 12px 14px;
    font-size: .76rem; color: var(--green-800); margin-top: 16px;
}

.blocked-banner {
    border-radius: 14px; padding: 28px 24px; text-align: center;
    margin-bottom: 16px;
}
.blocked-banner.pending {
    background: var(--green-50); border: 1.5px solid var(--green-200);
}
.blocked-banner.paid {
    background: var(--green-50); border: 1.5px solid var(--green-400);
}
.blocked-banner-icon {
    width: 52px; height: 52px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; margin: 0 auto 12px;
}
.blocked-banner-title { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; margin-bottom: 6px; letter-spacing: -.015em; }
.blocked-banner-sub   { font-size: .8rem; color: var(--neutral-500); line-height: 1.6; margin-bottom: 16px; }
.btn-to-invoice {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px; border: none;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 50%, var(--green-500) 100%);
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .82rem; font-weight: 600;
    text-decoration: none; transition: all .2s;
    box-shadow: 0 3px 12px rgba(42,179,107,.28);
}
.btn-to-invoice:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 20px rgba(42,179,107,.38); }

.method-card.dimmed { opacity: .4; pointer-events: none; filter: grayscale(1); }
.bank-pill.dimmed   { opacity: .4; pointer-events: none; }

.spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,.4);
    border-top-color: #fff; border-radius: 50%;
    animation: spin .7s linear infinite; flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

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
.back-nav { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .8rem; font-weight: 600; color: var(--neutral-500);
    text-decoration: none; padding: 7px 14px; border-radius: 9px;
    border: 1px solid var(--neutral-200); background: var(--neutral-0); transition: all .18s;
}
.btn-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }
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
        <a href="{{ route('tenant.renewal.invoice', $renewal->id) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i>Kembali ke Invoice
        </a>
    </div>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Pembayaran</div>
            <h1 class="hero-title">Pembayaran<br><em>Perpanjangan Sewa</em></h1>
            <p class="hero-sub">Kamar {{ $renewal->room?->room_number }} · {{ $renewal->duration_months }} bulan · Jatuh tempo {{ $invoice->due_date?->format('d M Y') }}</p>
        </div>
    </div>

    <div class="grid-2">
        <div>
            <div class="o-card">
                <div class="pay-hero">
                    <div class="pay-hero-lbl">Total Pembayaran</div>
                    <div class="pay-hero-amount">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                    <div class="pay-hero-sub">
                        Kamar {{ $renewal->room?->room_number }} · {{ $renewal->duration_months }} bulan ·
                        Jatuh tempo <strong>{{ $invoice->due_date?->format('d M Y') }}</strong>
                    </div>
                </div>

                <div class="o-card-body">
                    @php $isBlocked = in_array($invoice->status, ['pending_verification', 'paid']); @endphp

                    @if($invoice->status === 'pending_verification')
                    <div class="blocked-banner pending">
                        <div class="blocked-banner-icon" style="background:var(--green-50);color:var(--green-500)">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="blocked-banner-title" style="color:var(--green-800)">Pembayaran Sedang Diverifikasi</div>
                        <p class="blocked-banner-sub">
                            Kami sudah menerima pembayaran Anda dan sedang dalam proses verifikasi.
                            Tombol bayar dinonaktifkan untuk mencegah pembayaran ganda.
                        </p>
                        <a href="{{ route('tenant.renewal.invoice', $renewal->id) }}" class="btn-to-invoice">
                            <i class="bi bi-receipt"></i> Lihat Status Invoice
                        </a>
                    </div>
                    @elseif($invoice->status === 'paid')
                    <div class="blocked-banner paid">
                        <div class="blocked-banner-icon" style="background:var(--green-100);color:var(--green-600)">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="blocked-banner-title" style="color:var(--green-700)">Invoice Sudah Lunas</div>
                        <p class="blocked-banner-sub">
                            Pembayaran telah terkonfirmasi dan masa sewa sudah diperpanjang.
                            Tidak perlu melakukan pembayaran lagi.
                        </p>
                        <a href="{{ route('tenant.renewal.invoice', $renewal->id) }}" class="btn-to-invoice">
                            <i class="bi bi-receipt"></i> Lihat Invoice
                        </a>
                    </div>
                    @endif

                    <div class="step-label" data-n="1" @if($isBlocked) style="opacity:.4" @endif>
                        Pilih Metode Pembayaran
                    </div>

                    <div class="method-grid">
                        <div class="method-card {{ $isBlocked ? 'dimmed' : '' }}" id="choice-qris"
                            @if(!$isBlocked) onclick="selectMethod('qris')" @endif>
                            <div class="method-emoji">
                                <i class="bi bi-qr-code-scan" style="font-size:2rem;color:var(--green-600)"></i>
                            </div>
                            <div class="method-name">QRIS</div>
                            <div class="method-desc">GoPay · OVO · Dana<br>ShopeePay · LinkAja</div>
                        </div>
                        <div class="method-card {{ $isBlocked ? 'dimmed' : '' }}" id="choice-bank"
                            @if(!$isBlocked) onclick="selectMethod('bank')" @endif>
                            <div class="method-emoji">
                                <i class="bi bi-bank" style="font-size:2rem;color:var(--green-600)"></i>
                            </div>
                            <div class="method-name">Virtual Account</div>
                            <div class="method-desc">BCA · BNI · BRI<br>Mandiri · Bank Lain</div>
                        </div>
                    </div>

                    <div id="bankSection" class="bank-section">
                        <div class="bank-section-label">Pilih Bank Tujuan</div>
                        <div class="bank-pills">
                            <div class="bank-pill {{ $isBlocked ? 'dimmed' : '' }}" id="pill-bca"
                                 @if(!$isBlocked) onclick="selectBank('bca_va','pill-bca')" @endif>BCA</div>
                            <div class="bank-pill {{ $isBlocked ? 'dimmed' : '' }}" id="pill-bni"
                                 @if(!$isBlocked) onclick="selectBank('bni_va','pill-bni')" @endif>BNI</div>
                            <div class="bank-pill {{ $isBlocked ? 'dimmed' : '' }}" id="pill-bri"
                                 @if(!$isBlocked) onclick="selectBank('bri_va','pill-bri')" @endif>BRI</div>
                        </div>
                    </div>

                    <div class="step-label" data-n="2" @if($isBlocked) style="opacity:.4" @endif>
                        Konfirmasi &amp; Bayar
                    </div>

                    @if($isBlocked)
                    <button class="btn-pay-main" disabled>
                        <i class="bi bi-lock-fill"></i>
                        <span>
                            @if($invoice->status === 'pending_verification')
                                Menunggu Verifikasi...
                            @else
                                Pembayaran Sudah Selesai
                            @endif
                        </span>
                    </button>
                    @else
                    <button id="payBtn" class="btn-pay-main" onclick="payNow()" disabled>
                        <i class="bi bi-credit-card-fill"></i>
                        <span id="payBtnLabel">Pilih metode pembayaran dulu</span>
                    </button>

                    <div class="pending-banner" id="pendingBanner">
                        <strong>Instruksi pembayaran sudah dibuat.</strong><br>
                        Selesaikan transfer ke nomor Virtual Account yang diberikan Midtrans.
                        Status diperbarui otomatis setelah transfer berhasil.<br>
                        <a href="{{ route('tenant.renewal.invoice', $renewal->id) }}" style="display:inline-block;margin-top:8px">
                            Lihat status invoice →
                        </a>
                    </div>
                    @endif

                    @if(!$isBlocked)
                    <div class="security-note">
                        <i class="bi bi-shield-lock-fill" style="color:var(--green-600)"></i>
                        Aman & Diproses Midtrans
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="o-card" style="position:sticky;top:80px">
                @if($renewal->room?->image_path)
                    <img src="{{ asset('storage/' . $renewal->room->image_path) }}"
                         alt="{{ $renewal->room->room_name }}" class="room-img">
                @else
                    <div class="room-placeholder"><i class="bi bi-house-door-fill"></i></div>
                @endif

                <div class="o-card-body">
                    <div style="font-family:'Fraunces',serif;font-size:1.05rem;font-weight:700;margin-bottom:2px;letter-spacing:-.015em">
                        {{ $renewal->room?->room_name ?? 'Kamar ' . $renewal->room?->room_number }}
                    </div>
                    <div style="font-size:.74rem;color:var(--neutral-500);margin-bottom:18px">
                        No. {{ $renewal->room?->room_number }}
                    </div>

                    <div class="summary-row">
                        <span class="sum-label">Sewa kamar / bulan</span>
                        <span style="font-weight:600">
                            {{ $renewal->room?->formatted_price ?? 'Rp ' . number_format($renewal->room?->price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($invoice->service_amount && $invoice->service_amount > 0)
                        @php $svcMonthly = $renewal->duration_months > 0 ? round($invoice->service_amount / $renewal->duration_months) : 0; @endphp
                        <div class="summary-row">
                            <span class="sum-label">Layanan tambahan / bulan</span>
                            <span style="font-weight:600;color:var(--green-600)">+Rp {{ number_format($svcMonthly, 0, ',', '.') }}</span>
                        </div>
                        @if($renewal->additional_services && count($renewal->additional_services) > 0)
                        <div class="summary-row" style="flex-direction:column;align-items:flex-start;gap:8px;border-bottom:none;padding-bottom:0">
                            <div style="display:flex;flex-wrap:wrap">
                                @foreach($renewal->additional_services as $key => $svc)
                                    <span class="svc-chip">{{ $svc['label'] ?? $key }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif

                    <div class="summary-row">
                        <span class="sum-label">Durasi</span>
                        <span style="font-weight:600">{{ $renewal->duration_months }} bulan</span>
                    </div>
                    <div class="summary-row">
                        <span class="sum-label">Jatuh tempo</span>
                        <span style="font-weight:600">{{ $invoice->due_date?->format('d M Y') }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Tagihan</span>
                        <span class="sum-total-val">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="info-auto">
                        <i class="bi bi-check-circle-fill" style="color:var(--green-600)"></i>
                        <strong>Otomatis:</strong> Setelah bayar, masa sewa langsung diperpanjang tanpa konfirmasi manual admin.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(config('midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script>
let selectedMethod    = null;
let selectedBank      = null;
let hasPendingPayment = false;

function selectMethod(method) {
    selectedMethod = method;
    selectedBank   = null;
    document.querySelectorAll('.method-card').forEach(el => el.classList.remove('selected'));
    document.getElementById('choice-' + method).classList.add('selected');
    const bankSec = document.getElementById('bankSection');
    if (method === 'bank') {
        bankSec.classList.add('show');
        document.querySelectorAll('.bank-pill').forEach(p => p.classList.remove('selected'));
        setPayBtnState(false);
    } else {
        bankSec.classList.remove('show');
        setPayBtnState(true);
    }
}

function selectBank(bankCode, pillId) {
    selectedBank = bankCode;
    document.querySelectorAll('.bank-pill').forEach(p => p.classList.remove('selected'));
    document.getElementById(pillId).classList.add('selected');
    setPayBtnState(true);
}

function setPayBtnState(ready) {
    const btn = document.getElementById('payBtn');
    btn.disabled  = !ready;
    btn.className = 'btn-pay-main';
    if (!ready) {
        btn.innerHTML = '<i class="bi bi-credit-card-fill"></i><span>Pilih metode pembayaran dulu</span>';
        return;
    }
    if (selectedMethod === 'qris') {
        btn.innerHTML = '<i class="bi bi-qr-code-scan"></i><span>Bayar dengan QRIS</span>';
    } else {
        const names = { bca_va:'BCA', bni_va:'BNI', bri_va:'BRI', permata_va:'Mandiri', other_va:'Bank Lain' };
        btn.innerHTML = '<i class="bi bi-bank"></i><span>Bayar via VA ' + (names[selectedBank] ?? '') + '</span>';
    }
}

async function payNow() {
    if (!selectedMethod) { alert('Silakan pilih metode pembayaran.'); return; }
    if (selectedMethod === 'bank' && !selectedBank) { alert('Silakan pilih bank tujuan Virtual Account.'); return; }

    const btn = document.getElementById('payBtn');
    btn.disabled  = true;
    btn.innerHTML = '<div class="spinner"></div><span>Memproses...</span>';

    try {
        const res = await fetch("{{ route('tenant.renewal.snap-token', $renewal->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ method: selectedMethod, bank: selectedBank }),
        });

        const data = await res.json();
        if (!res.ok || !data.snap_token) throw new Error(data.error ?? 'Gagal mendapatkan token pembayaran.');

        snap.pay(data.snap_token, {
            onSuccess: function(result) {
                fetch('/midtrans/payment-callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: result.order_id })
                }).finally(() => {
                    btn.className = 'btn-pay-main state-success';
                    btn.disabled  = true;
                    btn.innerHTML = '<i class="bi bi-check-circle-fill"></i><span>Pembayaran Berhasil! Mengalihkan...</span>';
                    setTimeout(() => { window.location.href = "{{ route('tenant.renewal.invoice', $renewal->id) }}"; }, 1500);
                });
            },
            onPending: function() {
                hasPendingPayment = true;
                btn.className = 'btn-pay-main state-pending';
                btn.disabled  = true;
                btn.innerHTML = '<i class="bi bi-clock-fill"></i><span>Menunggu Pembayaran...</span>';
                document.getElementById('pendingBanner').classList.add('show');
            },
            onError: function(result) {
                console.error('[Snap onError]', result);
                hasPendingPayment = false;
                setPayBtnState(true);
                alert('Pembayaran gagal. Silakan coba lagi atau pilih metode lain.');
            },
            onClose: function() {
                if (hasPendingPayment) {
                    document.getElementById('pendingBanner').classList.add('show');
                } else {
                    setPayBtnState(true);
                }
            }
        });

    } catch(err) {
        console.error('[payNow error]', err);
        setPayBtnState(true);
        alert(err.message);
    }
}
</script>

</body>
</html>