<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Perpanjangan — Urban Kost</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
    --amber-200:   #fde68a;
    --amber-600:   #d97706;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-200:    #bfdbfe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-200:     #fecaca;
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
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
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
.page-wrap { max-width: 1100px; margin: 0 auto; padding: 32px 24px 64px; }

.breadcrumb-bar { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: .75rem; color: var(--neutral-400); }
.breadcrumb-bar a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { color: var(--green-700); }
.breadcrumb-bar i { font-size: .6rem; color: var(--neutral-200); }

.flash { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: .82rem; font-weight: 500; border: 1px solid; }
.flash.success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.flash.error   { background: var(--red-pale);  border-color: var(--red-200);  color: var(--red); }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
.page-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(42,179,107,.1);
    border: 1px solid var(--green-200);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--green-700); margin-bottom: 8px;
}
.page-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 3vw, 1.9rem); font-weight: 700;
    color: var(--neutral-900); letter-spacing: -.03em; line-height: 1.15;
}
.page-title span { color: var(--green-600); }
.page-sub { font-size: .79rem; color: var(--neutral-400); margin-top: 4px; }
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--neutral-0); color: var(--neutral-600);
    font-weight: 600; font-size: .8rem; padding: 9px 18px;
    border-radius: 10px; border: 1px solid var(--green-200);
    text-decoration: none; transition: all .15s; white-space: nowrap;
}
.btn-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

.renewal-hero {
    border-radius: var(--r-card); padding: 28px 32px; margin-bottom: 24px;
    position: relative; overflow: hidden;
    box-shadow: 0 8px 32px rgba(14,63,39,.25);
}
.renewal-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-pending-admin,
.hero-pending-pay,
.hero-approved,
.hero-rejected {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
}
.hero-inner { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; }
.hero-label { font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.65); margin-bottom: 6px; }
.hero-name { font-family: 'Fraunces', serif; font-size: clamp(1.3rem, 3vw, 1.9rem); font-weight: 700; color: #fff; letter-spacing: -.03em; line-height: 1; }
.hero-room { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2); border-radius: 8px; padding: 5px 12px; font-size: .75rem; font-weight: 600; color: rgba(255,255,255,.9); margin-top: 10px; }
.hero-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; border-radius: var(--r-pill); font-size: .72rem; font-weight: 600; margin-top: 10px; margin-left: 6px; background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.28); }
.hero-right { text-align: right; }
.hero-amount-label { font-size: .68rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.65); margin-bottom: 4px; }
.hero-amount { font-family: 'Fraunces', serif; font-size: clamp(1.7rem, 4vw, 2.4rem); font-weight: 700; color: #fff; letter-spacing: -.04em; line-height: 1; }
.hero-dur { font-size: .74rem; font-weight: 500; color: rgba(255,255,255,.72); margin-top: 5px; }

.detail-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }

.d-card { background: var(--neutral-0); border-radius: var(--r-card); border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden; margin-bottom: 20px; }
.d-card:last-child { margin-bottom: 0; }
.d-card-head { padding: 14px 20px; border-bottom: 1px solid var(--neutral-100); background: linear-gradient(135deg, var(--green-50), var(--neutral-0)); display: flex; align-items: center; gap: 10px; }
.d-card-icon { width: 32px; height: 32px; border-radius: 9px; background: var(--green-100); color: var(--green-700); display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
.d-card-icon.blue  { background: var(--blue-pale); color: var(--blue); }
.d-card-icon.amber { background: var(--amber-pale); color: #92400e; }
.d-card-icon.red   { background: var(--red-pale);   color: var(--red); }
.d-card-title { font-size: .72rem; font-weight: 600; color: var(--neutral-700); text-transform: uppercase; letter-spacing: .07em; }
.d-card-sub   { font-size: .7rem; color: var(--neutral-400); margin-top: 1px; }
.d-card-body  { padding: 20px; }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.info-label { font-size: .62rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--neutral-400); margin-bottom: 5px; }
.info-val   { font-size: .87rem; font-weight: 600; color: var(--neutral-700); }
.info-val-sub { font-size: .72rem; color: var(--neutral-400); margin-top: 2px; }
.info-chip { display: inline-flex; align-items: center; background: var(--green-50); border: 1px solid var(--green-100); border-radius: 7px; padding: 3px 10px; font-size: .78rem; font-weight: 600; color: var(--green-700); }

.summary-box { background: var(--green-50); border: 1px solid var(--green-200); border-radius: 12px; padding: 18px; margin-bottom: 18px; }
.summary-title { font-size: .63rem; font-weight: 600; letter-spacing: .07em; text-transform: uppercase; color: var(--green-700); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
.summary-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: .82rem; }
.summary-row + .summary-row { border-top: 1px dashed var(--green-200); }
.sr-label { color: var(--neutral-500); }
.sr-val   { font-weight: 600; color: var(--neutral-700); }
.summary-total { margin-top: 10px; padding-top: 10px; border-top: 2px solid var(--green-200); display: flex; justify-content: space-between; align-items: baseline; }
.st-label { font-size: .9rem; font-weight: 700; color: var(--neutral-900); }
.st-val   { font-family: 'Fraunces', serif; font-size: 1.6rem; font-weight: 700; color: var(--green-700); letter-spacing: -.03em; }

.info-alert { border-radius: 10px; padding: 12px 14px; display: flex; align-items: flex-start; gap: 9px; margin-bottom: 16px; border: 1px solid; }
.info-alert i { flex-shrink: 0; margin-top: 2px; font-size: .85rem; }
.info-alert-text { font-size: .78rem; line-height: 1.55; }
.info-alert.blue   { background: var(--blue-pale);  border-color: var(--blue-200);  }
.info-alert.blue i, .info-alert.blue .info-alert-text { color: #1e40af; }
.info-alert.amber  { background: var(--amber-pale); border-color: var(--amber-200); }
.info-alert.amber i, .info-alert.amber .info-alert-text { color: #92400e; }
.info-alert.green  { background: var(--green-50);  border-color: var(--green-200);  }
.info-alert.green i, .info-alert.green .info-alert-text { color: var(--green-800); }
.info-alert.red    { background: var(--red-pale);   border-color: var(--red-200);   }
.info-alert.red i, .info-alert.red .info-alert-text { color: var(--red); }

.btn-approve { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px 20px; background: linear-gradient(135deg, var(--green-500), var(--green-700)); color: #fff; font-weight: 600; font-size: .85rem; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 14px rgba(42,179,107,.28); transition: all .2s; margin-bottom: 10px; font-family: 'Instrument Sans', sans-serif; }
.btn-approve:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(42,179,107,.36); }
.btn-reject-toggle { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 11px 20px; background: var(--neutral-0); color: var(--red); font-weight: 600; font-size: .84rem; border: 1px solid var(--red-200); border-radius: 10px; cursor: pointer; transition: all .15s; font-family: 'Instrument Sans', sans-serif; }
.btn-reject-toggle:hover { background: var(--red-pale); }
.btn-cancel-toggle { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 9px 16px; background: var(--neutral-0); color: var(--red); font-weight: 600; font-size: .78rem; border: 1px solid var(--red-200); border-radius: 9px; cursor: pointer; transition: all .15s; margin-top: 10px; font-family: 'Instrument Sans', sans-serif; }
.btn-cancel-toggle:hover { background: var(--red-pale); }
.reject-collapse { display: none; padding-top: 14px; }
.reject-collapse.open { display: block; }
.form-label-sm { font-size: .73rem; font-weight: 600; color: var(--neutral-700); margin-bottom: 5px; display: block; }
.form-textarea { width: 100%; padding: 9px 12px; border: 1px solid var(--neutral-200); border-radius: 9px; font-size: .8rem; font-family: 'Instrument Sans', sans-serif; color: var(--neutral-900); resize: vertical; min-height: 80px; outline: none; transition: border-color .15s; }
.form-textarea:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.btn-confirm-reject { width: 100%; padding: 10px; background: var(--red); color: #fff; font-weight: 600; font-size: .82rem; border: none; border-radius: 9px; cursor: pointer; margin-top: 10px; transition: background .15s; font-family: 'Instrument Sans', sans-serif; }
.btn-confirm-reject:hover { background: #b91c1c; }
.divider { border: none; border-top: 1px solid var(--neutral-100); margin: 16px 0; }

.pay-item { padding: 16px 20px; }
.pay-item + .pay-item { border-top: 1px solid var(--neutral-100); }
.pay-code { font-weight: 600; font-size: .82rem; color: var(--neutral-900); }
.pay-meta { font-size: .69rem; color: var(--neutral-400); margin-top: 3px; }
.pay-amount { font-weight: 700; font-size: .92rem; color: var(--green-700); text-align: right; }
.pstatus { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: var(--r-pill); font-size: .65rem; font-weight: 600; }
.pst-pending  { background: var(--blue-pale);  color: var(--blue); }
.pst-verified { background: var(--green-100);  color: var(--green-800); }
.pst-rejected { background: var(--red-pale);   color: var(--red); }

.tl-step { position: relative; padding-left: 26px; padding-bottom: 18px; }
.tl-step:last-child { padding-bottom: 0; }
.tl-step::before { content: ''; position: absolute; left: 5px; top: 14px; bottom: 0; width: 2px; background: var(--neutral-200); }
.tl-step:last-child::before { display: none; }
.tl-dot { position: absolute; left: 0; top: 3px; width: 12px; height: 12px; border-radius: 50%; border: 2.5px solid var(--neutral-0); box-shadow: 0 0 0 2px var(--neutral-200); background: var(--neutral-200); }
.tl-dot.done     { background: var(--green-500); box-shadow: 0 0 0 2px var(--green-300); }
.tl-dot.active   { background: var(--green-600); box-shadow: 0 0 0 3px rgba(42,179,107,.25); }
.tl-dot.rejected { background: var(--red); box-shadow: 0 0 0 2px var(--red-200); }
.tl-title { font-size: .8rem; font-weight: 600; color: var(--neutral-400); }
.tl-title.done     { color: var(--green-700); }
.tl-title.active   { color: var(--green-800); }
.tl-title.rejected { color: var(--red); }
.tl-desc { font-size: .69rem; color: var(--neutral-400); margin-top: 2px; }
.tl-badge { display: inline-flex; align-items: center; background: var(--blue-pale); color: var(--blue); font-size: .62rem; font-weight: 600; padding: 1px 7px; border-radius: var(--r-pill); margin-left: 6px; }

.status-card-success { text-align: center; padding: 28px 20px; border: 1.5px solid var(--green-300); border-radius: var(--r-card); background: linear-gradient(to bottom, var(--green-50), var(--neutral-0)); margin-bottom: 20px; }
.status-card-rejected{ text-align: center; padding: 28px 20px; border: 1.5px solid var(--red-200); border-radius: var(--r-card); background: linear-gradient(to bottom, var(--red-pale), var(--neutral-0)); margin-bottom: 20px; }
.status-icon  { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 12px; }
.status-icon.green { background: var(--green-100); color: var(--green-600); }
.status-icon.red   { background: var(--red-pale);  color: var(--red); }
.status-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; letter-spacing: -.02em; }

.tenant-avatar { width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, var(--green-500), var(--green-700)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; font-weight: 700; font-family: 'Fraunces', serif; margin: 0 auto 10px; box-shadow: 0 4px 14px rgba(42,179,107,.28); }
.tenant-name-big  { font-size: .88rem; font-weight: 600; color: var(--neutral-900); text-align: center; }
.tenant-email-sm  { font-size: .71rem; color: var(--neutral-400); text-align: center; margin-top: 2px; }
.tdivider { border: none; border-top: 1px solid var(--neutral-100); margin: 12px 0; }
.tstat-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; font-size: .78rem; }
.tstat-row + .tstat-row { border-top: 1px solid var(--neutral-100); }
.tsr-label { color: var(--neutral-400); }
.tsr-val { font-weight: 600; color: var(--neutral-700); }
.tsr-val.green { color: var(--green-700); }
.tsr-val.red   { color: var(--red); }
.tsr-val.blue  { color: var(--blue); }
.tsr-val.amber { color: #92400e; }

.btn-hero-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 16px;
    border-radius: 10px;
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(255,255,255,.6);
    color: var(--neutral-700);
    font-size: .78rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.btn-hero-back:hover {
    background: #fff;
    color: var(--neutral-900);
}

.total-box { background: var(--green-50); border: 1px solid var(--green-200); border-radius: 12px; padding: 18px; text-align: center; margin-bottom: 18px; }
.total-box-label { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: var(--green-700); margin-bottom: 6px; }
.total-box-amount { font-family: 'Fraunces', serif; font-size: 1.9rem; font-weight: 700; color: var(--green-700); line-height: 1; letter-spacing: -.025em; }
.total-box-due { font-size: .73rem; color: var(--neutral-400); margin-top: 6px; }
.action-panel { border: 1.5px solid var(--green-400) !important; }
.action-panel-header { background: linear-gradient(135deg, var(--green-900), var(--green-700), var(--green-500)); padding: 14px 20px; display: flex; align-items: center; gap: 8px; font-size: .72rem; font-weight: 600; color: #fff; text-transform: uppercase; letter-spacing: .07em; }

.btn-pay-action { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 13px 20px; background: linear-gradient(135deg, var(--green-500), var(--green-700)); color: #fff; font-weight: 600; font-size: .88rem; border: none; border-radius: 11px; cursor: pointer; box-shadow: 0 4px 16px rgba(42,179,107,.28); transition: all .2s; font-family: 'Instrument Sans', sans-serif; text-decoration: none; }
.btn-pay-action:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.35); color: #fff; }

.empty-pay { text-align: center; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; gap: 8px; }
.empty-pay-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--green-100); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--green-400); }

.fade-up { opacity: 0; transform: translateY(14px); animation: fadeUp .4s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .04s; } .d2 { animation-delay: .09s; } .d3 { animation-delay: .14s; } .d4 { animation-delay: .19s; }

@media (max-width: 960px) { .detail-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) {
    .page-wrap { padding: 16px 12px 48px; }
    .renewal-hero { padding: 20px 18px; }
    .hero-inner { flex-direction: column; gap: 12px; }
    .hero-right { text-align: left; }
    .d-card-body { padding: 16px; }
    .info-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    @if(session('success'))
    <div class="flash success fade-up d1">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash error fade-up d1">
        <i class="bi bi-exclamation-circle-fill"></i> {!! session('error') !!}
    </div>
    @endif

    @php
        $heroClass = match($renewal->status) {
            'pending_admin'   => 'hero-pending-admin',
            'pending_payment' => 'hero-pending-pay',
            'approved'        => 'hero-approved',
            'rejected'        => 'hero-rejected',
            default           => 'hero-pending-admin',
        };
        $statusLabel = match($renewal->status) {
            'pending_admin'   => ['txt'=>'Menunggu Konfirmasi', 'icon'=>'hourglass-split'],
            'pending_payment' => ['txt'=>'Menunggu Pembayaran', 'icon'=>'credit-card'],
            'approved'        => ['txt'=>'Disetujui',           'icon'=>'check-circle-fill'],
            'rejected'        => ['txt'=>'Ditolak',             'icon'=>'x-circle-fill'],
            default           => ['txt'=>ucfirst($renewal->status), 'icon'=>'circle'],
        };
    @endphp

    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.renewals.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="renewal-hero {{ $heroClass }} fade-up d2">
        <div class="hero-inner">
            <div>
                <div class="hero-eyebrow" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.82);margin-bottom:14px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--green-300);flex-shrink:0;display:inline-block"></span>
                    <a href="{{ route('admin.renewals.index') }}" style="color:inherit;text-decoration:none">Perpanjangan Sewa</a>& Detail
                </div>
                <div class="hero-label">Penghuni</div>
                <div class="hero-name">{{ $renewal->user?->name }}</div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px">
                    <div class="hero-room">
                        <i class="bi bi-door-open-fill"></i>
                        Kamar {{ $renewal->room?->room_number }}
                    </div>
                    @php
                        $statusStyle = match($renewal->status) {
                            'pending_admin'   => 'background:rgba(245,158,11,.25);border-color:rgba(245,158,11,.5);color:#fef3c7',
                            'pending_payment' => 'background:rgba(37,99,235,.25);border-color:rgba(37,99,235,.4);color:#dbeafe',
                            'approved'        => 'background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.35);color:#fff',
                            'rejected'        => 'background:rgba(220,38,38,.3);border-color:rgba(220,38,38,.5);color:#fee2e2',
                            default           => 'background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.25);color:#fff',
                        };
                    @endphp
                    <div class="hero-status" style="{{ $statusStyle }}">
                        <i class="bi bi-{{ $statusLabel['icon'] }}"></i>
                        {{ $statusLabel['txt'] }}
                    </div>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-amount-label">Total Biaya</div>
                <div class="hero-amount">{{ $renewal->formatted_amount }}</div>
                <div class="hero-dur">
                    <i class="bi bi-calendar3"></i>
                    {{ $renewal->duration_months }} bulan perpanjangan
                </div>
            </div>
        </div>
    </div>

    <div class="detail-grid fade-up d3">
        <div>
            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <div class="d-card-title">Detail Perpanjangan</div>
                        <div class="d-card-sub">Informasi lengkap pengajuan</div>
                    </div>
                </div>
                <div class="d-card-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-label">Penghuni</div>
                            <div class="info-val">{{ $renewal->user?->name }}</div>
                            <div class="info-val-sub">{{ $renewal->user?->email }}</div>
                        </div>
                        <div>
                            <div class="info-label">Kamar</div>
                            <div class="info-val">
                                <span class="info-chip"><i class="bi bi-door-open" style="margin-right:4px"></i>{{ $renewal->room?->room_number }}</span>
                            </div>
                            <div class="info-val-sub">{{ $renewal->room?->room_name }} &middot; {{ $renewal->room?->formatted_price }}/bulan</div>
                        </div>
                        <div>
                            <div class="info-label">Durasi Perpanjangan</div>
                            <div class="info-val">{{ $renewal->duration_months }} Bulan</div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Pengajuan</div>
                            <div class="info-val" style="font-size:.82rem">{{ $renewal->created_at->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Diajukan Oleh</div>
                            <div class="info-val" style="font-size:.8rem">
                                @if(($renewal->initiated_by ?? '') === 'tenant')
                                    <span class="info-chip"><i class="bi bi-person" style="margin-right:4px"></i>Penghuni sendiri</span>
                                @else
                                    <span class="info-chip"><i class="bi bi-calendar-check" style="margin-right:4px"></i>Advance booking</span>
                                @endif
                            </div>
                        </div>
                        @if($renewal->new_end_date)
                        <div>
                            <div class="info-label">Akhir Sewa Baru</div>
                            <div class="info-val" style="color:var(--green-700)">{{ optional($renewal->new_end_date)->format('d M Y') }}</div>
                        </div>
                        @endif
                        @if($renewal->reviewed_at)
                        <div>
                            <div class="info-label">Direview Oleh</div>
                            <div class="info-val">{{ $renewal->reviewer?->name }}</div>
                            <div class="info-val-sub">{{ $renewal->reviewed_at->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                    @if($renewal->status === 'rejected' && $renewal->rejection_reason)
                    <div style="margin-top:16px">
                        <div class="info-alert red">
                            <i class="bi bi-x-circle-fill"></i>
                            <div class="info-alert-text"><strong>Alasan Penolakan:</strong> {{ $renewal->rejection_reason }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-receipt-cutoff"></i></div>
                    <div>
                        <div class="d-card-title">Rincian Biaya</div>
                        <div class="d-card-sub">Kalkulasi total perpanjangan</div>
                    </div>
                </div>
                <div class="d-card-body">
                    <div class="summary-box">
                        <div class="summary-title"><i class="bi bi-calculator"></i> Breakdown Biaya</div>
                        <div class="summary-row">
                            <span class="sr-label"><i class="bi bi-house" style="color:var(--green-500);margin-right:4px"></i>Sewa / Bulan</span>
                            <span class="sr-val">{{ $renewal->room?->formatted_price }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="sr-label"><i class="bi bi-calendar3" style="color:var(--blue);margin-right:4px"></i>Durasi</span>
                            <span class="sr-val">&times; {{ $renewal->duration_months }} bulan</span>
                        </div>
                        <div class="summary-total">
                            <span class="st-label">Total</span>
                            <span class="st-val">{{ $renewal->formatted_amount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($renewal->invoice)
            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon blue"><i class="bi bi-receipt"></i></div>
                    <div>
                        <div class="d-card-title">Invoice Perpanjangan</div>
                        <div class="d-card-sub">{{ $renewal->invoice->invoice_number }}</div>
                    </div>
                </div>
                <div class="d-card-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-label">Nomor Invoice</div>
                            <div class="info-val" style="font-size:.8rem;color:var(--green-700)">{{ $renewal->invoice->invoice_number }}</div>
                        </div>
                        <div>
                            <div class="info-label">Status Invoice</div>
                            <div class="info-val">
                                @php
                                    $invSt = match($renewal->invoice->status){
                                        'paid'    => ['bg'=>'var(--green-100)','clr'=>'var(--green-800)','txt'=>'Lunas'],
                                        'overdue' => ['bg'=>'var(--red-pale)', 'clr'=>'var(--red)',       'txt'=>'Terlambat'],
                                        default   => ['bg'=>'#fef9c3',         'clr'=>'#854d0e',          'txt'=>'Belum Bayar'],
                                    };
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;background:{{ $invSt['bg'] }};color:{{ $invSt['clr'] }};padding:3px 10px;border-radius:var(--r-pill);font-size:.7rem;font-weight:600">
                                    {{ $invSt['txt'] }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Total Invoice</div>
                            <div class="info-val" style="color:var(--green-700)">{{ $renewal->invoice->formatted_total }}</div>
                        </div>
                        <div>
                            <div class="info-label">Jatuh Tempo</div>
                            <div class="info-val" style="{{ $renewal->invoice->isOverdue() ? 'color:var(--red)' : '' }};font-size:.82rem">
                                {{ $renewal->invoice->due_date?->format('d M Y') }}
                                @if($renewal->invoice->isOverdue())
                                    <span style="background:var(--red-pale);color:var(--red);font-size:.6rem;font-weight:600;padding:1px 6px;border-radius:5px;margin-left:4px">Terlambat</span>
                                @endif
                            </div>
                        </div>
                        @if($renewal->invoice->paid_at)
                        <div>
                            <div class="info-label">Dibayar Pada</div>
                            <div class="info-val" style="color:var(--green-700);font-size:.82rem">{{ optional($renewal->invoice->paid_at)->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon blue"><i class="bi bi-clock-history"></i></div>
                    @php $payments = $renewal->invoice?->payments()->latest()->get() ?? collect(); @endphp
                    <div>
                        <div class="d-card-title">Riwayat Pembayaran</div>
                        <div class="d-card-sub">{{ $payments->count() }} pembayaran tercatat</div>
                    </div>
                </div>
                @forelse($payments as $pay)
                <div class="pay-item">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
                        <div>
                            <div class="pay-code">{{ $pay->payment_code }}</div>
                            <div class="pay-meta">
                                <i class="bi bi-credit-card" style="margin-right:4px"></i>{{ ucfirst($pay->method) }}
                                &middot;
                                <i class="bi bi-calendar3" style="margin-right:4px"></i>{{ $pay->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div style="text-align:right">
                            <div class="pay-amount">Rp {{ number_format($pay->amount, 0, ',', '.') }}</div>
                            @php
                                $pst = match($pay->status){
                                    'pending'  => ['pst-pending', 'Menunggu'],
                                    'verified' => ['pst-verified','Terverifikasi'],
                                    'rejected' => ['pst-rejected','Ditolak'],
                                    default    => ['pst-pending', $pay->status],
                                };
                            @endphp
                            <span class="pstatus {{ $pst[0] }}" style="margin-top:4px">{{ $pst[1] }}</span>
                        </div>
                    </div>
                    @if($pay->method === 'midtrans')
                    <div style="display:inline-flex;align-items:center;gap:5px;background:var(--blue-pale);color:var(--blue);font-size:.7rem;font-weight:600;padding:4px 10px;border-radius:7px;margin-top:8px">
                        <i class="bi bi-shield-lock-fill"></i> Midtrans — Otomatis Terverifikasi
                    </div>
                    @endif
                    @if($pay->status === 'verified')
                    <div style="display:flex;align-items:center;gap:6px;font-size:.73rem;color:var(--green-700);font-weight:600;margin-top:8px">
                        <i class="bi bi-shield-check"></i>
                        Terverifikasi &middot; {{ $pay->verified_at?->format('d M Y') }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-pay">
                    <div class="empty-pay-icon"><i class="bi bi-inbox"></i></div>
                    <div style="font-size:.83rem;font-weight:600;color:var(--neutral-600)">Belum ada pembayaran</div>
                    <div style="font-size:.73rem;color:var(--neutral-400)">Pembayaran tenant akan tampil di sini</div>
                </div>
                @endforelse
            </div>
        </div>

        <div>
            @if($renewal->status === 'pending_admin')
            <div class="d-card action-panel" style="margin-bottom:20px">
                <div class="action-panel-header">
                    <i class="bi bi-hourglass-split"></i>
                    <span>Keputusan Admin</span>
                </div>
                <div class="d-card-body">
                    <div class="info-alert amber" style="margin-bottom:16px">
                        <i class="bi bi-info-circle-fill"></i>
                        <div class="info-alert-text">
                            <strong>{{ $renewal->user?->name }}</strong> mengajukan perpanjangan kamar
                            <strong>{{ $renewal->room?->room_number }}</strong> selama
                            <strong>{{ $renewal->duration_months }} bulan</strong> senilai
                            <strong>{{ $renewal->formatted_amount }}</strong>.
                        </div>
                    </div>
                    <div class="info-alert green" style="margin-bottom:18px">
                        <i class="bi bi-bell-fill"></i>
                        <div class="info-alert-text">
                            Jika disetujui, invoice otomatis dikirim ke penghuni. Sewa diperpanjang setelah pembayaran diterima.
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.renewals.approve', $renewal->id) }}">
                        @csrf
                        <button type="submit" class="btn-approve"
                            onclick="return confirm('Setujui perpanjangan ini? Invoice akan otomatis dikirim ke tenant.')">
                            <i class="bi bi-check-circle-fill"></i>
                            Setujui &amp; Kirim Invoice
                        </button>
                    </form>
                    <button class="btn-reject-toggle" onclick="toggleCollapse('rejectForm')">
                        <i class="bi bi-x-circle"></i> Tolak Perpanjangan
                    </button>
                    <div id="rejectForm" class="reject-collapse">
                        <form method="POST" action="{{ route('admin.renewals.reject', $renewal->id) }}">
                            @csrf
                            <label class="form-label-sm">Alasan Penolakan <span style="color:var(--red)">*</span></label>
                            <textarea name="reason" class="form-textarea" placeholder="Jelaskan alasan penolakan..." required></textarea>
                            <button type="submit" class="btn-confirm-reject">
                                <i class="bi bi-x-circle" style="margin-right:4px"></i> Konfirmasi Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @elseif($renewal->status === 'pending_payment')
            <div class="d-card action-panel" style="margin-bottom:20px">
                <div class="action-panel-header">
                    <i class="bi bi-credit-card-fill"></i>
                    <span>Menunggu Pembayaran</span>
                </div>
                <div class="d-card-body">
                    <div style="text-align:center;padding:12px 0 16px">
                        <div style="font-weight:600;font-size:.88rem;color:var(--neutral-800);margin-bottom:5px">Invoice Sudah Terkirim</div>
                        <div style="font-size:.77rem;color:var(--neutral-500);line-height:1.55">
                            Menunggu <strong>{{ $renewal->user?->name }}</strong> menyelesaikan
                            pembayaran sebesar <strong>{{ $renewal->formatted_amount }}</strong>.
                        </div>
                        @if($renewal->invoice?->due_date)
                        <div style="margin-top:10px;font-size:.74rem;font-weight:600;{{ $renewal->invoice->isOverdue() ? 'color:var(--red)' : 'color:var(--neutral-400)' }}">
                            <i class="bi bi-calendar-event" style="margin-right:4px"></i>
                            Jatuh tempo: {{ optional($renewal->invoice->due_date)->format('d M Y') }}
                            @if($renewal->invoice->isOverdue())
                                <span style="background:var(--red-pale);color:var(--red);font-size:.6rem;font-weight:600;padding:1px 6px;border-radius:5px;margin-left:4px">Lewat</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="info-alert blue">
                        <i class="bi bi-cpu-fill"></i>
                        <div class="info-alert-text">
                            Setelah tenant membayar via Midtrans, sistem otomatis mengkonfirmasi perpanjangan. Tidak diperlukan aksi dari admin.
                        </div>
                    </div>
                    <div class="divider"></div>
                    <button class="btn-cancel-toggle" onclick="toggleCollapse('cancelForm')">
                        <i class="bi bi-x-circle" style="margin-right:4px"></i> Batalkan Perpanjangan
                    </button>
                    <div id="cancelForm" class="reject-collapse">
                        <div class="info-alert red" style="margin:10px 0 8px">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div class="info-alert-text">Pembatalan hanya jika ada alasan mendesak. Refund dilakukan manual bila sudah bayar.</div>
                        </div>
                        <form method="POST" action="{{ route('admin.renewals.reject', $renewal->id) }}">
                            @csrf
                            <textarea name="reason" class="form-textarea" placeholder="Alasan pembatalan..." required></textarea>
                            <button type="submit" class="btn-confirm-reject">Konfirmasi Batalkan</button>
                        </form>
                    </div>
                </div>
            </div>

            @elseif($renewal->status === 'approved')
            <div class="status-card-success">
                <div class="status-icon green"><i class="bi bi-check-circle-fill"></i></div>
                <div class="status-title" style="color:var(--green-700)">Perpanjangan Selesai</div>
                <p style="font-size:.78rem;color:var(--neutral-500);margin:8px 0 0;line-height:1.6">
                    Sewa kamar <strong>{{ $renewal->room?->room_number }}</strong>
                    milik <strong>{{ $renewal->user?->name }}</strong> berhasil diperpanjang
                    <strong>{{ $renewal->duration_months }} bulan</strong>.
                </p>
                @if($renewal->new_end_date)
                <div style="background:var(--green-50);border:1px solid var(--green-200);border-radius:9px;padding:10px 14px;margin-top:14px">
                    <div style="font-size:.63rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--green-600);margin-bottom:3px">Berakhir Pada</div>
                    <div style="font-family:'Fraunces',serif;font-size:1.2rem;font-weight:700;color:var(--green-700)">{{ $renewal->new_end_date->format('d M Y') }}</div>
                </div>
                @endif
                @if($renewal->approved_at)
                <div style="font-size:.72rem;color:var(--neutral-400);margin-top:12px">
                    <i class="bi bi-clock" style="margin-right:4px"></i>
                    Dikonfirmasi otomatis &middot; {{ optional($renewal->approved_at)->format('d M Y') }}
                </div>
                @endif
            </div>

            @elseif($renewal->status === 'rejected')
            <div class="status-card-rejected">
                <div class="status-icon red"><i class="bi bi-x-circle-fill"></i></div>
                <div class="status-title" style="color:var(--red)">Perpanjangan Ditolak</div>
                <p style="font-size:.78rem;color:var(--neutral-500);margin:8px 0 0;line-height:1.6">{{ $renewal->rejection_reason }}</p>
                @if($renewal->reviewed_at)
                <div style="font-size:.72rem;color:var(--neutral-400);margin-top:12px">
                    <i class="bi bi-clock" style="margin-right:4px"></i>
                    Ditolak oleh {{ $renewal->reviewer?->name }}
                    &middot; {{ optional($renewal->reviewed_at)->format('d M Y') }}
                </div>
                @endif
            </div>
            @endif

            <div class="d-card" style="margin-bottom:20px">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-activity"></i></div>
                    <div>
                        <div class="d-card-title">Alur Perpanjangan</div>
                        <div class="d-card-sub">Progres status pengajuan</div>
                    </div>
                </div>
                <div class="d-card-body">
                    @php
                        $orderMap = ['pending_admin'=>0,'pending_payment'=>1,'approved'=>2,'rejected'=>-1];
                        $cur = $orderMap[$renewal->status] ?? 0;
                        $tlSteps = [
                            ['Admin Review',      'Setujui atau tolak pengajuan tenant'],
                            ['Tenant Bayar',      'Invoice terkirim, tenant bayar via Midtrans'],
                            ['Sewa Diperpanjang', 'Sistem otomatis memperbarui tanggal sewa'],
                        ];
                    @endphp
                    @foreach($tlSteps as $i => [$lbl, $desc])
                    @php $done = $cur > $i; $active = $cur === $i; @endphp
                    <div class="tl-step">
                        <div class="tl-dot {{ $done ? 'done' : '' }} {{ $active ? 'active' : '' }}"></div>
                        <div class="tl-title {{ $done ? 'done' : '' }} {{ $active ? 'active' : '' }}">
                            {{ $lbl }}
                            @if($active)<span class="tl-badge">Saat ini</span>@endif
                        </div>
                        <div class="tl-desc">{{ $desc }}</div>
                    </div>
                    @endforeach
                    @if($renewal->status === 'rejected')
                    <div class="tl-step" style="margin-top:10px">
                        <div class="tl-dot rejected"></div>
                        <div class="tl-title rejected">Ditolak</div>
                        <div class="tl-desc">Pengajuan tidak dapat dilanjutkan</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-person-circle"></i></div>
                    <div>
                        <div class="d-card-title">Info Penghuni</div>
                        <div class="d-card-sub">Data penyewa</div>
                    </div>
                </div>
                <div class="d-card-body" style="padding-top:20px">
                    <div class="tenant-avatar">
                        {{ strtoupper(substr($renewal->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="tenant-name-big">{{ $renewal->user?->name }}</div>
                    <div class="tenant-email-sm">{{ $renewal->user?->email }}</div>
                    @if($renewal->user?->phone)
                    <div style="font-size:.74rem;color:var(--neutral-400);text-align:center;margin-top:4px">
                        <i class="bi bi-phone" style="margin-right:4px"></i>{{ $renewal->user->phone }}
                    </div>
                    @endif
                    @php
                        $allRenewals   = $renewal->user?->renewals ?? collect();
                        $doneRenewals  = $allRenewals->where('status','approved')->count();
                        $totalRenewAmt = $allRenewals->where('status','approved')->sum('total_amount');
                    @endphp
                    <div class="tdivider"></div>
                    <div class="tstat-row">
                        <span class="tsr-label">Status Saat Ini</span>
                        <span class="tsr-val {{ $renewal->status === 'approved' ? 'green' : ($renewal->status === 'rejected' ? 'red' : 'amber') }}">
                            {{ $statusLabel['txt'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function toggleCollapse(id) {
    document.getElementById(id).classList.toggle('open');
}
</script>
</body>
</html>