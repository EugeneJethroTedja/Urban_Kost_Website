<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Invoice — Urban Kost</title>
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
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-100:    #dbeafe;
    --blue-200:    #bfdbfe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-100:     #fee2e2;
    --red-200:     #fecaca;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-100:   #fef3c7;
    --amber-200:   #fde68a;
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
.page-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50); }
.page-bg::before {
    content: ''; position: absolute; inset: 0;
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
.page-wrap { max-width: 1200px; margin: 0 auto; padding: 90px 28px 64px; }
.breadcrumb-bar {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 20px; font-size: .75rem; color: var(--neutral-500);
}
.breadcrumb-bar a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { color: var(--green-700); }
.breadcrumb-bar i { font-size: .6rem; color: var(--neutral-400); }
.page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
}
.page-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 700; color: var(--neutral-900);
    letter-spacing: -.03em; line-height: 1.1;
}
.page-title em { font-style: italic; font-weight: 400; color: var(--green-600); }
.page-subtitle { font-size: .78rem; color: var(--neutral-500); font-weight: 400; margin-top: 5px; }
.header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--neutral-0); color: var(--neutral-600);
    font-weight: 600; font-size: .8rem;
    padding: 9px 18px; border-radius: 10px;
    border: 1px solid var(--neutral-200); text-decoration: none; transition: all .15s;
}
.btn-back:hover { border-color: var(--green-300); color: var(--neutral-900); background: var(--green-50); }
.btn-print {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--neutral-900); color: #fff;
    font-weight: 600; font-size: .8rem;
    padding: 9px 18px; border-radius: 10px;
    border: none; cursor: pointer; transition: all .2s;
}
.btn-print:hover { background: var(--green-900); transform: translateY(-1px); }
.overdue-bar {
    background: var(--red-pale); border: 1px solid var(--red-200);
    border-radius: 12px; padding: 13px 18px;
    display: flex; align-items: center; gap: 12px; margin-bottom: 20px;
}
.overdue-bar i { color: var(--red); font-size: 1rem; flex-shrink: 0; }
.overdue-bar-text { font-size: .8rem; color: var(--red); font-weight: 600; line-height: 1.5; }
.overdue-bar-text span { font-size: .72rem; font-weight: 400; color: #b91c1c; }
.invoice-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    border-radius: 20px; padding: 32px 36px; margin-bottom: 24px;
    position: relative; overflow: hidden;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
}
.invoice-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}

.hero-inner {
    position: relative; z-index: 1;
    display: flex; justify-content: space-between; align-items: flex-start;
    flex-wrap: wrap; gap: 20px;
}
.hero-inv-number {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 3vw, 2rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.04em; line-height: 1; margin-bottom: 6px;
}
.hero-inv-sub { font-size: .78rem; color: rgba(255,255,255,.72); }
.hero-status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 100px;
    font-size: .7rem; font-weight: 600;
    letter-spacing: .03em; text-transform: uppercase;
    backdrop-filter: blur(8px); margin-top: 14px;
}
.hs-unpaid   { background: rgba(245,158,11,.25); color: #fef3c7; border: 1px solid rgba(245,158,11,.4); }
.hs-overdue  { background: rgba(220,38,38,.3);   color: #fee2e2; border: 1px solid rgba(220,38,38,.5); }
.hs-pending  { background: rgba(37,99,235,.3);   color: #dbeafe; border: 1px solid rgba(37,99,235,.5); }
.hs-paid     { background: rgba(255,255,255,.25); color: #fff;   border: 1px solid rgba(255,255,255,.4); }
.hs-cancelled{ background: rgba(0,0,0,.2);        color: rgba(255,255,255,.7); border: 1px solid rgba(255,255,255,.2); }
.hero-right { text-align: right; }
.hero-amount-label { font-size: .68rem; font-weight: 600; color: rgba(255,255,255,.65); letter-spacing: .08em; text-transform: uppercase; margin-bottom: 4px; }
.hero-amount {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 700; color: #fff; letter-spacing: -.05em; line-height: 1;
}
.hero-tax { font-size: .68rem; color: rgba(255,255,255,.6); margin-top: 6px; }
.hero-due {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
    border-radius: 8px; padding: 5px 12px;
    font-size: .73rem; font-weight: 600; color: rgba(255,255,255,.9); margin-top: 8px;
}
.detail-grid {
    display: grid; grid-template-columns: 1fr 340px;
    gap: 20px; align-items: start;
}
.o-card {
    background: var(--neutral-0); border-radius: 16px;
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 20px;
}
.o-card:last-child { margin-bottom: 0; }
.o-card-header {
    padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    display: flex; align-items: center; gap: 10px;
}
.o-card-header-icon {
    width: 32px; height: 32px; border-radius: 9px;
    background: var(--green-100); color: var(--green-700);
    display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0;
}
.o-card-header-icon.blue   { background: var(--blue-pale);  color: var(--blue); }
.o-card-header-icon.amber  { background: var(--amber-pale); color: #92400e; }
.o-card-header-icon.red    { background: var(--red-pale);   color: var(--red); }
.o-card-header-title {
    font-size: .72rem; font-weight: 600; color: var(--neutral-600);
    text-transform: uppercase; letter-spacing: .07em;
}
.o-card-header-sub { font-size: .68rem; color: var(--neutral-400); margin-top: 2px; }
.o-card-body { padding: 20px; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.info-label {
    font-size: .63rem; font-weight: 600; letter-spacing: .08em;
    text-transform: uppercase; color: var(--neutral-400); margin-bottom: 5px;
}
.info-val { font-size: .9rem; font-weight: 600; color: var(--neutral-900); }
.info-val-sub { font-size: .73rem; color: var(--neutral-500); margin-top: 2px; }
.info-chip {
    display: inline-flex; align-items: center;
    background: var(--green-100); border-radius: 7px;
    padding: 4px 10px; font-size: .78rem; font-weight: 600; color: var(--green-700);
}
.billing-wrap {
    background: linear-gradient(135deg, var(--green-50), var(--green-100));
    border: 1px solid var(--green-200); border-radius: 12px; padding: 18px;
}
.billing-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; font-size: .84rem;
}
.billing-row + .billing-row { border-top: 1px dashed var(--neutral-200); }
.billing-row-label { color: var(--neutral-600); }
.billing-row-val { font-weight: 700; color: var(--neutral-900); }
.billing-divider { border: none; border-top: 2px solid var(--green-300); margin: 12px 0; }
.billing-total-row { display: flex; justify-content: space-between; align-items: baseline; padding-top: 2px; }
.billing-total-label { font-size: .95rem; font-weight: 700; color: var(--neutral-900); }
.billing-total-val {
    font-family: 'Fraunces', serif;
    font-size: 1.7rem; font-weight: 700; color: var(--green-700); letter-spacing: -.04em;
}
.billing-tax-note {
    display: flex; justify-content: space-between;
    font-size: .7rem; color: var(--neutral-400);
    margin-top: 10px; padding-top: 10px; border-top: 1px dashed var(--neutral-200);
}
.tax-chip {
    background: var(--amber-pale); color: #92400e;
    font-size: .62rem; font-weight: 600; padding: 2px 8px; border-radius: 100px;
}
.notes-box {
    margin-top: 16px; padding: 13px 16px;
    background: var(--green-50); border: 1px solid var(--green-200); border-radius: 10px;
}
.notes-label { font-size: .63rem; font-weight: 600; color: var(--green-700); letter-spacing: .07em; text-transform: uppercase; margin-bottom: 6px; }
.notes-text { font-size: .82rem; color: var(--neutral-700); line-height: 1.6; }
.payment-item { padding: 20px; }
.payment-item + .payment-item { border-top: 1px solid var(--neutral-100); }
.pay-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
.pay-code { font-weight: 600; font-size: .84rem; color: var(--neutral-900); }
.pay-meta { font-size: .7rem; color: var(--neutral-500); margin-top: 3px; }
.pay-amount {
    font-family: 'Fraunces', serif;
    font-weight: 700; font-size: .95rem; color: var(--green-700); text-align: right;
    letter-spacing: -.02em;
}
.pay-badges { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; justify-content: flex-end; }
.pstatus {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 100px;
    font-size: .65rem; font-weight: 600; border: 1px solid;
}
.pst-pending  { background: var(--blue-pale);  color: var(--blue); border-color: var(--blue-100); }
.pst-verified { background: var(--green-100);  color: var(--green-700); border-color: var(--green-200); }
.pst-rejected { background: var(--red-pale);   color: var(--red); border-color: var(--red-100); }
.pay-info-row {
    display: flex; align-items: center; gap: 6px;
    font-size: .76rem; color: var(--neutral-500); margin-bottom: 8px;
}
.proof-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--blue-pale); color: var(--blue);
    font-weight: 600; font-size: .75rem;
    padding: 6px 14px; border-radius: 8px;
    border: 1px solid var(--blue-200); text-decoration: none; transition: all .15s;
}
.proof-btn:hover { background: var(--blue-100); color: #1d4ed8; }
.reject-box {
    background: var(--red-pale); border: 1px solid var(--red-200);
    border-radius: 9px; padding: 10px 14px;
    font-size: .76rem; color: var(--red); margin-bottom: 10px;
}
.verified-note {
    display: flex; align-items: center; gap: 6px;
    font-size: .74rem; color: var(--green-600); font-weight: 600;
}
.pay-actions { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
.btn-verify {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--green-500); color: #fff;
    font-weight: 600; font-size: .8rem;
    padding: 9px 20px; border-radius: 9px; border: none; cursor: pointer;
    box-shadow: 0 4px 12px rgba(42,179,107,.3); transition: all .2s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-verify:hover { background: var(--green-600); transform: translateY(-2px); }
.btn-reject {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--neutral-0); color: var(--red);
    font-weight: 600; font-size: .78rem;
    padding: 9px 16px; border-radius: 9px;
    border: 1px solid var(--red-200); cursor: pointer; transition: all .15s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-reject:hover { background: var(--red-pale); }
.empty-pay { text-align: center; padding: 48px 20px; }
.empty-pay-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: var(--green-50); border: 2px dashed var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: var(--green-300); margin: 0 auto 10px;
}
.reject-panel {
    display: none; margin-top: 14px;
    background: var(--red-pale); border: 1px solid var(--red-200);
    border-radius: 12px; overflow: hidden;
    animation: slideDown .2s ease;
}
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }
.reject-panel-head {
    display: flex; align-items: center; gap: 8px;
    padding: 12px 16px; border-bottom: 1px solid var(--red-200);
    background: rgba(220,38,38,.06);
}
.reject-panel-head i { color: var(--red); font-size: .95rem; }
.reject-panel-title { font-size: .82rem; font-weight: 600; color: var(--red); }
.reject-panel-body { padding: 16px; }
.reject-label { font-size: .68rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: #b91c1c; margin-bottom: 7px; }
.reject-textarea {
    width: 100%; border: 1px solid var(--red-200); border-radius: 9px;
    padding: 10px 13px; font-size: .82rem; font-family: 'Instrument Sans', sans-serif;
    color: var(--neutral-900); background: #fff;
    resize: vertical; min-height: 80px; outline: none; transition: border-color .15s; line-height: 1.55;
}
.reject-textarea:focus { border-color: var(--red); }
.reject-hint { display: flex; align-items: center; gap: 5px; font-size: .7rem; color: #b91c1c; margin-top: 7px; opacity: .8; }
.reject-footer {
    display: flex; gap: 8px; justify-content: flex-end;
    padding: 12px 16px; border-top: 1px solid var(--red-200);
    background: rgba(220,38,38,.04);
}
.btn-cancel-inline {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--neutral-0); color: var(--neutral-600);
    font-weight: 600; font-size: .78rem;
    padding: 8px 16px; border-radius: 8px;
    border: 1px solid var(--neutral-200); cursor: pointer; transition: all .15s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-cancel-inline:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
.btn-reject-submit {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--red); color: #fff;
    font-weight: 600; font-size: .78rem;
    padding: 8px 18px; border-radius: 8px; border: none; cursor: pointer;
    box-shadow: 0 3px 10px rgba(220,38,38,.3); transition: all .2s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-reject-submit:hover { background: #b91c1c; transform: translateY(-1px); }
.sidebar-col { display: flex; flex-direction: column; gap: 20px; }
.tl-step { position: relative; padding-left: 26px; padding-bottom: 20px; }
.tl-step:last-child { padding-bottom: 0; }
.tl-step::before {
    content: ''; position: absolute; left: 5px; top: 14px; bottom: 0;
    width: 2px; background: var(--neutral-200);
}
.tl-step:last-child::before { display: none; }
.tl-dot {
    position: absolute; left: 0; top: 3px;
    width: 12px; height: 12px; border-radius: 50%;
    border: 2px solid #fff; box-shadow: 0 0 0 2px var(--neutral-300);
    background: var(--neutral-300);
}
.tl-dot.done   { background: var(--green-500); box-shadow: 0 0 0 2px var(--green-300); }
.tl-dot.active { background: var(--green-600); box-shadow: 0 0 0 3px rgba(42,179,107,.25); }
.tl-title { font-size: .82rem; font-weight: 600; color: var(--neutral-500); }
.tl-title.done   { color: var(--green-700); }
.tl-title.active { color: var(--green-800); }
.tl-desc { font-size: .7rem; color: var(--neutral-400); margin-top: 2px; }
.tenant-avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.3rem; font-weight: 700;
    font-family: 'Fraunces', serif;
    margin: 0 auto 12px;
    box-shadow: 0 4px 14px rgba(42,179,107,.3);
}
.tenant-name-big { font-family: 'Fraunces', serif; font-size: .9rem; font-weight: 700; color: var(--neutral-900); text-align: center; letter-spacing: -.01em; }
.tenant-email-sm { font-size: .72rem; color: var(--neutral-400); text-align: center; margin-top: 2px; }
.tenant-phone { font-size: .76rem; color: var(--neutral-500); text-align: center; margin-top: 4px; }
.tenant-divider { border: none; border-top: 1px solid var(--neutral-100); margin: 14px 0; }
.tenant-stat-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0; font-size: .8rem;
}
.tenant-stat-row + .tenant-stat-row { border-top: 1px solid var(--neutral-100); }
.tsr-label { color: var(--neutral-500); }
.tsr-val { font-weight: 600; color: var(--neutral-900); }
.tsr-val.green { color: var(--green-600); }
.tsr-val.red   { color: var(--red); }
.tsr-val.blue  { color: var(--blue); }
.room-info-box {
    background: var(--green-50); border: 1px solid var(--green-200);
    border-radius: 10px; padding: 13px 14px;
}
.fade-up { opacity: 0; transform: translateY(14px); animation: fadeUp .45s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; } .d3 { animation-delay: .16s; } .d4 { animation-delay: .22s; }
@media print {
    .no-print { display: none !important; }
    body { background: #fff; }
    .invoice-hero { box-shadow: none; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
}
@media (max-width: 960px) { .detail-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) {
    .page-wrap { padding: 16px 14px 48px; }
    .invoice-hero { padding: 22px 20px; }
    .hero-inner { flex-direction: column; gap: 16px; }
    .hero-right { text-align: left; }
    .o-card-body { padding: 16px; }
    .payment-item { padding: 16px; }
    .info-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .header-actions { width: 100%; }
    .header-actions a, .header-actions button { flex: 1; justify-content: center; }
}

.hero-banner {
    position: relative; overflow: hidden; border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 36px 32px; margin-bottom: 28px;
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
@media (max-width: 768px) {
    .hero-banner { flex-direction: column; padding: 22px 20px; }
    .hero-right { width: 100%; }
    .btn-hero { width: 100%; justify-content: center; }
}
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: var(--neutral-0); color: var(--neutral-600);
    border: 1px solid var(--neutral-200);
    font-size: .8rem; font-weight: 600;
    text-decoration: none; transition: all .15s;
}
.btn-back:hover {
    border-color: var(--green-300);
    color: var(--green-700);
    background: var(--green-50);
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="hero-banner fade-up d1 no-print">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('admin.invoices.index') }}" style="color:inherit;text-decoration:none">Invoice</a>
                &nbsp;/&nbsp; Detail
            </div>
            <h1 class="hero-title">Detail <em>Invoice</em></h1>
            <p class="hero-sub">Informasi lengkap tagihan dan riwayat pembayaran</p>
        </div>
    </div>

    @if($invoice->isOverdue())
    <div class="overdue-bar fade-up d1 no-print">
        <div class="overdue-bar-text">
            Invoice ini sudah melewati jatuh tempo.
            <br><span>Terlambat {{ abs($invoice->days_until_due) }} hari sejak {{ $invoice->due_date?->format('d M Y') }}</span>
        </div>
    </div>
    @endif

    @php
        $stMap = [
            'unpaid'               => ['cls'=>'hs-unpaid',   'txt'=>'Belum Dibayar',      'icon'=>'hourglass-split'],
            'pending_verification' => ['cls'=>'hs-pending',  'txt'=>'Menunggu Verifikasi', 'icon'=>'cloud-upload-fill'],
            'paid'                 => ['cls'=>'hs-paid',     'txt'=>'Lunas',               'icon'=>'check-circle-fill'],
            'overdue'              => ['cls'=>'hs-overdue',  'txt'=>'Terlambat',           'icon'=>'alarm-fill'],
            'cancelled'            => ['cls'=>'hs-cancelled','txt'=>'Dibatalkan',          'icon'=>'x-circle-fill'],
        ];
        $st = $stMap[$invoice->status] ?? $stMap['unpaid'];
    @endphp

    <div class="invoice-hero fade-up d2">
        <div class="hero-inner">
            <div>
                <div class="hero-inv-number">{{ $invoice->invoice_number }}</div>
                <div class="hero-inv-sub">
                    <i class="bi bi-calendar3" style="margin-right:5px"></i>
                    Periode {{ $invoice->period_start?->format('d M') }} – {{ $invoice->period_end?->format('d M Y') }}
                </div>
                <div class="hero-status {{ $st['cls'] }}">
                    <i class="bi bi-{{ $st['icon'] }}"></i>{{ $st['txt'] }}
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-amount-label">Total Tagihan</div>
                <div class="hero-amount">{{ $invoice->formatted_total }}</div>
                <div class="hero-tax">PPh Final 0.5%: Rp {{ number_format($invoice->tax_amount,0,',','.') }}</div>
                <div class="hero-due">
                    <i class="bi bi-calendar-event"></i>
                    Jatuh tempo {{ $invoice->due_date?->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="detail-grid fade-up d3">

        <div>
            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-person-fill"></i></div>
                    <div>
                        <div class="o-card-header-title">Data Penghuni &amp; Kamar</div>
                        <div class="o-card-header-sub">Informasi penyewa yang ditagih</div>
                    </div>
                </div>
                <div class="o-card-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-label">Nama Penghuni</div>
                            <div class="info-val">{{ $invoice->user?->name }}</div>
                            <div class="info-val-sub">{{ $invoice->user?->email }}</div>
                        </div>
                        <div>
                            <div class="info-label">Nomor Kamar</div>
                            <div class="info-val">
                                <span class="info-chip"><i class="bi bi-door-open" style="margin-right:4px"></i>{{ $invoice->room?->room_number }}</span>
                            </div>
                            <div class="info-val-sub">{{ $invoice->room?->room_name }} &middot; {{ $invoice->room?->formatted_price }}/bulan</div>
                        </div>
                        <div>
                            <div class="info-label">Periode Sewa</div>
                            <div class="info-val" style="font-size:.84rem">{{ $invoice->period_start?->format('d M Y') }}</div>
                            <div class="info-val-sub">s/d {{ $invoice->period_end?->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="info-label">Jatuh Tempo</div>
                            <div class="info-val" style="font-size:.84rem;{{ $invoice->isOverdue() ? 'color:var(--red)' : '' }}">
                                {{ $invoice->due_date?->format('d M Y') }}
                            </div>
                            @if($invoice->isOverdue())
                                <div class="info-val-sub" style="color:var(--red);font-weight:600">
                                    <i class="bi bi-clock-history" style="margin-right:4px"></i>Terlambat {{ abs($invoice->days_until_due) }} hari
                                </div>
                            @elseif($invoice->days_until_due <= 3 && $invoice->days_until_due >= 0)
                                <div class="info-val-sub" style="color:var(--amber);font-weight:600">
                                    <i class="bi bi-clock" style="margin-right:4px"></i>{{ $invoice->days_until_due }} hari lagi
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-receipt-cutoff"></i></div>
                    <div>
                        <div class="o-card-header-title">Rincian Tagihan</div>
                        <div class="o-card-header-sub">Breakdown biaya sewa dan layanan</div>
                    </div>
                </div>
                <div class="o-card-body">
                    <div class="billing-wrap">
                        <div class="billing-row">
                            <div class="billing-row-label">
                                <i class="bi bi-house" style="color:var(--green-500);margin-right:6px"></i>
                                Sewa Kamar ({{ $invoice->period_start?->format('F Y') }})
                            </div>
                            <div class="billing-row-val">Rp {{ number_format($invoice->base_amount,0,',','.') }}</div>
                        </div>
                        @if($invoice->service_amount > 0)
                        <div class="billing-row">
                            <div class="billing-row-label">
                                <i class="bi bi-stars" style="color:var(--amber);margin-right:6px"></i>
                                Layanan Tambahan
                            </div>
                            <div class="billing-row-val">Rp {{ number_format($invoice->service_amount,0,',','.') }}</div>
                        </div>
                        @endif
                        <hr class="billing-divider">
                        <div class="billing-total-row">
                            <div class="billing-total-label">Total Tagihan</div>
                            <div class="billing-total-val">{{ $invoice->formatted_total }}</div>
                        </div>
                    </div>
                    @if($invoice->notes)
                    <div class="notes-box">
                        <div class="notes-label"><i class="bi bi-sticky" style="margin-right:5px"></i>Catatan</div>
                        @php
                            $notesParts = explode('Layanan tambahan:', $invoice->notes);
                            $mainNote   = trim($notesParts[0]);
                            $services   = isset($notesParts[1])
                                ? array_filter(array_map('trim', preg_split('/,(?=[^)]*(?:\(|$))/', $notesParts[1])))
                                : [];
                        @endphp

                        @if($mainNote)
                            <div class="notes-text" style="margin-bottom: {{ count($services) ? '10px' : '0' }}">{{ $mainNote }}</div>
                        @endif

                        @if(count($services))
                            <div style="font-size:.63rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--green-700);margin-bottom:6px">
                                Layanan Tambahan
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                @foreach($services as $svc)
                                    <span style="
                                        display:inline-flex;align-items:center;gap:4px;
                                        background:var(--green-100);border:1px solid var(--green-200);
                                        border-radius:8px;padding:4px 10px;
                                        font-size:.75rem;font-weight:600;color:var(--green-700);
                                    ">{{ trim($svc) }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon blue"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <div class="o-card-header-title">Riwayat Pembayaran</div>
                        <div class="o-card-header-sub">{{ $payments->count() }} pembayaran tercatat</div>
                    </div>
                </div>

                @forelse($payments as $pay)
                <div class="payment-item">
                    <div class="pay-header">
                        <div>
                            <div class="pay-code">{{ $pay->payment_code }}</div>
                            <div class="pay-meta">
                                <i class="bi bi-credit-card" style="margin-right:4px"></i>{{ $pay->method_label }}
                                &nbsp;&middot;&nbsp;
                                <i class="bi bi-calendar3" style="margin-right:4px"></i>{{ $pay->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div>
                            <div class="pay-amount">{{ $pay->formatted_amount }}</div>
                            <div class="pay-badges">
                                @php
                                    $pst = match($pay->status) {
                                        'pending'  => ['pst-pending',  'Menunggu Verifikasi', 'clock'],
                                        'verified' => ['pst-verified', 'Terverifikasi',       'check-circle'],
                                        'rejected' => ['pst-rejected', 'Ditolak',             'x-circle'],
                                        default    => ['pst-pending',  $pay->status,          'circle'],
                                    };
                                @endphp
                                <span class="pstatus {{ $pst[0] }}">
                                    <i class="bi bi-{{ $pst[2] }}" style="margin-right:3px"></i>{{ $pst[1] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($pay->bank_name)
                    <div class="pay-info-row">
                        <i class="bi bi-bank"></i>
                        {{ $pay->bank_name }}{{ $pay->account_number ? ' — ' . $pay->account_number : '' }}
                    </div>
                    @endif

                    @if($pay->transfer_proof)
                    <div style="margin-bottom:12px">
                        <a href="{{ asset('storage/'.$pay->transfer_proof) }}" target="_blank" class="proof-btn">
                            <i class="bi bi-image"></i>Lihat Bukti Transfer
                        </a>
                    </div>
                    @endif

                    @if($pay->status === 'rejected' && $pay->rejection_reason)
                    <div class="reject-box">
                        <i class="bi bi-x-circle" style="margin-right:5px"></i><strong>Alasan ditolak:</strong> {{ $pay->rejection_reason }}
                    </div>
                    @endif

                    @if($pay->status === 'verified')
                    <div class="verified-note">
                        <i class="bi bi-shield-check"></i>
                        Diverifikasi oleh <strong>{{ $pay->verifier?->name }}</strong>
                        &nbsp;&middot;&nbsp;{{ $pay->verified_at?->format('d M Y') }}
                    </div>
                    @endif

                    @if($pay->status === 'pending')
                    <div class="pay-actions no-print">
                        <form method="POST" action="{{ route('admin.payments.verify', $pay->id) }}" style="display:inline">
                            @csrf
                            <button type="button" class="btn-verify"
                                onclick="if(confirm('Verifikasi pembayaran ini? Invoice akan ditandai Lunas.')) this.closest('form').submit()">
                                <i class="bi bi-check-circle-fill"></i>Verifikasi &amp; Tandai Lunas
                            </button>
                        </form>
                        <button class="btn-reject"
                            onclick="this.closest('.payment-item').querySelector('.reject-panel').style.display='block';this.style.display='none'">
                            <i class="bi bi-x-circle"></i>Tolak
                        </button>
                    </div>

                    <div class="reject-panel no-print">
                        <div class="reject-panel-head">
                            <i class="bi bi-x-octagon-fill"></i>
                            <span class="reject-panel-title">Tolak Bukti Pembayaran</span>
                        </div>
                        <div class="reject-panel-body">
                            <form method="POST" action="{{ route('admin.payments.reject', $pay->id) }}">
                                @csrf
                                <div class="reject-label">Alasan Penolakan <span style="color:var(--red)">*</span></div>
                                <textarea name="rejection_reason" class="reject-textarea"
                                    placeholder="Contoh: Nominal tidak sesuai, bukti tidak terbaca, rekening tujuan salah..."
                                    required></textarea>
                                <div class="reject-hint">
                                    <i class="bi bi-bell"></i>
                                    Penghuni akan dinotifikasi via in-app.
                                </div>
                                <div class="reject-footer">
                                    <button type="button" class="btn-cancel-inline"
                                        onclick="this.closest('.reject-panel').style.display='none';this.closest('.payment-item').querySelector('.btn-reject').style.display='inline-flex'">
                                        <i class="bi bi-arrow-left"></i>Batal
                                    </button>
                                    <button type="submit" class="btn-reject-submit">
                                        <i class="bi bi-x-circle-fill"></i>Tolak Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-pay">
                    <div class="empty-pay-icon"><i class="bi bi-inbox"></i></div>
                    <div style="font-size:.85rem;font-weight:600;color:var(--neutral-600);margin-bottom:4px">Belum ada pembayaran</div>
                    <div style="font-size:.74rem;color:var(--neutral-400)">Pembayaran yang masuk akan tampil di sini</div>
                </div>
                @endforelse
            </div>
        </div>

        <div class="sidebar-col">

            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-activity"></i></div>
                    <div>
                        <div class="o-card-header-title">Alur Pembayaran</div>
                        <div class="o-card-header-sub">Progres status invoice</div>
                    </div>
                </div>
                <div class="o-card-body">
                    @php
                        $orderMap = ['unpaid'=>0,'overdue'=>0,'pending_verification'=>1,'paid'=>2,'cancelled'=>-1];
                        $cur = $orderMap[$invoice->status] ?? 0;
                        $tlSteps = [
                            ['Invoice Diterbitkan',     'Tagihan dikirim ke penghuni',       'bi-plus-circle'],
                            ['Bayar Melalui Midtrans',  'Penghuni bayar melalui midtrans',     'bi-cloud-upload'],
                            ['Verifikasi Midtrans',     'Midtrans memeriksa pembayaran otomatis',         'bi-shield-check'],
                            ['Pembayaran Lunas',        'Invoice selesai',                   'bi-check-circle'],
                        ];
                        $tlMap = [0, 1, 1, 2];
                    @endphp
                    <div>
                        @foreach($tlSteps as $i => [$lbl, $desc, $icon])
                        @php
                            $done   = $cur >= $tlMap[$i];
                            $active = $cur == $tlMap[$i] && $i <= 2;
                        @endphp
                        <div class="tl-step">
                            <div class="tl-dot {{ $done ? 'done' : '' }} {{ $active ? 'active' : '' }}"></div>
                            <div class="tl-title {{ $done ? 'done' : '' }} {{ $active ? 'active' : '' }}">
                                <i class="bi {{ $icon }}" style="margin-right:5px"></i>{{ $lbl }}
                            </div>
                            <div class="tl-desc">{{ $desc }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-person-circle"></i></div>
                    <div>
                        <div class="o-card-header-title">Info Penghuni</div>
                        <div class="o-card-header-sub">Kontak penyewa</div>
                    </div>
                </div>
                <div class="o-card-body" style="padding-top:20px">
                    <div class="tenant-avatar">
                        {{ strtoupper(substr($invoice->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="tenant-name-big">{{ $invoice->user?->name }}</div>
                    <div class="tenant-email-sm">{{ $invoice->user?->email }}</div>
                    @if($invoice->user?->phone)
                    <div class="tenant-phone">
                        <i class="bi bi-telephone" style="margin-right:4px"></i>{{ $invoice->user->phone }}
                    </div>
                    @endif
                    @php
                        $allInvoices = \App\Models\Invoice::where('user_id', $invoice->user_id)->get();
                        $totalPaid   = $allInvoices->where('status','paid')->count();
                        $totalLate   = $allInvoices->where('status','overdue')->count();
                        $paidAmount  = $allInvoices->where('status','paid')->sum('total_amount');
                    @endphp
                    <hr class="tenant-divider">
                    <div class="tenant-stat-row">
                        <span class="tsr-label">Invoice Lunas</span>
                        <span class="tsr-val green">{{ $totalPaid }}</span>
                    </div>
                    <div class="tenant-stat-row">
                        <span class="tsr-label">Total Dibayar</span>
                        <span class="tsr-val blue">Rp {{ number_format($paidAmount/1000000, 1) }}jt</span>
                    </div>
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon amber"><i class="bi bi-door-open-fill"></i></div>
                    <div>
                        <div class="o-card-header-title">Info Kamar</div>
                        <div class="o-card-header-sub">Detail unit yang disewa</div>
                    </div>
                </div>
                <div class="o-card-body">
                    <div style="display:flex;gap:12px;align-items:center;margin-bottom:14px">
                        <div style="width:44px;height:44px;border-radius:11px;background:var(--green-100);display:flex;align-items:center;justify-content:center;color:var(--green-700);font-size:1.1rem;flex-shrink:0">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                        <div>
                            <div style="font-family:'Fraunces',serif;font-size:.95rem;font-weight:700;color:var(--neutral-900);letter-spacing:-.01em">Kamar {{ $invoice->room?->room_number }}</div>
                            <div style="font-size:.73rem;color:var(--neutral-400);margin-top:2px">{{ $invoice->room?->room_name }}</div>
                        </div>
                    </div>
                    <div class="room-info-box">
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:8px">
                            <span style="color:var(--neutral-500)">Harga Sewa</span>
                            <span style="font-weight:700;color:var(--green-700)">
                                {{ $invoice->room?->formatted_price }}<span style="font-weight:400;color:var(--neutral-400)">/bln</span>
                            </span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.8rem">
                            <span style="color:var(--neutral-500)">Invoice ID</span>
                            <span style="font-weight:600;color:var(--neutral-700);font-size:.72rem">#{{ $invoice->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>