<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Tagihan {{ $invoice->invoice_number }} — Urban Kost</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
}
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
    --blue-light:  #dbeafe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-light:   #fecaca;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-light: #fde68a;
    --indigo:      #4f46e5;
    --indigo-pale: #eef2ff;
    --indigo-light:#e0e7ff;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
}
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--green-50); }
::-webkit-scrollbar-thumb { background: var(--green-400); border-radius: 3px; }

.tenant-bg {
    position: fixed; inset: 0; z-index: -1; overflow: hidden;
    background: var(--green-50);
}
.tenant-bg::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.08) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.05) 0%, transparent 50%);
}

.page-body {
    max-width: 1120px; margin: 0 auto;
    padding: 28px 24px 72px;
    width: 100%;
}

.ph {
    display: flex; align-items: center;
    justify-content: space-between;
    gap: 10px; margin-bottom: 20px;
    flex-wrap: wrap;
}
.ph-left { display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1; }
.ph-back {
    display: flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; flex-shrink: 0;
    border-radius: 10px; background: var(--neutral-0);
    border: 1px solid var(--neutral-200); color: var(--neutral-600);
    text-decoration: none; font-size: .95rem;
    box-shadow: var(--shadow-sm);
    transition: border-color .2s, color .2s, background .2s;
}
.ph-back:hover { border-color: var(--green-300); color: var(--green-600); background: var(--green-50); }
.ph-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(.95rem, 2.5vw, 1.2rem);
    font-weight: 700; color: var(--neutral-900);
    letter-spacing: -.02em;
}
.ph-sub { font-size: .72rem; color: var(--neutral-500); margin-top: 1px; }

.inv-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    border-radius: 18px; overflow: hidden;
    box-shadow: 0 8px 40px rgba(14,63,39,.32);
    margin-bottom: 20px; position: relative;
}
.inv-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.16) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.10) 0%, transparent 40%);
    pointer-events: none;
}
.inv-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
    background-size: 40px 40px;
}
.inv-hero-inner {
    position: relative; z-index: 1;
    display: flex; align-items: stretch; flex-wrap: wrap;
}

.ihs-left {
    padding: clamp(18px, 3vw, 28px) clamp(16px, 3vw, 28px);
    flex: 1 1 160px; min-width: 0;
}
.ihs-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.13); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.18);
    color: rgba(255,255,255,.88);
    font-size: .62rem; font-weight: 600; letter-spacing: .08em;
    text-transform: uppercase;
    padding: 4px 12px; border-radius: 100px; margin-bottom: 10px;
}
.ihs-num {
    font-family: 'Fraunces', serif;
    font-size: clamp(.82rem, 2vw, 1.05rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.02em; margin-bottom: 5px;
    word-break: break-all; line-height: 1.3;
}
.ihs-desc {
    font-size: .73rem; color: rgba(255,255,255,.6);
    line-height: 1.5; word-break: break-word;
}
.ihs-vdiv {
    width: 1px; background: rgba(255,255,255,.12);
    align-self: stretch; margin: 14px 0; flex-shrink: 0;
}
.ihs-stat {
    padding: clamp(14px, 2.5vw, 26px) clamp(10px, 1.8vw, 22px);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 4px; flex-shrink: 0;
}
.ihs-stat-lbl {
    font-size: .57rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.5); white-space: nowrap;
}
.ihs-stat-val {
    font-family: 'Fraunces', serif;
    font-size: clamp(.8rem, 1.6vw, 1rem);
    font-weight: 700; color: #fff; text-align: center;
}
.ihs-amount {
    padding: clamp(14px, 2.5vw, 22px) clamp(14px, 2.5vw, 28px);
    display: flex; flex-direction: column;
    justify-content: center; align-items: flex-end;
    background: rgba(255,255,255,.08); backdrop-filter: blur(10px);
    border-left: 1px solid rgba(255,255,255,.1);
    text-align: right; flex-shrink: 0;
    min-width: 0; flex: 0 1 auto;
}
.ihs-amount-lbl {
    font-size: .58rem; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: rgba(255,255,255,.5); margin-bottom: 5px;
    white-space: nowrap;
}
.ihs-amount-val {
    font-family: 'Fraunces', serif;
    font-size: clamp(1rem, 3vw, 1.55rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.03em; line-height: 1.1;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: 100%;
}
.ihs-overdue-note {
    margin-top: 8px; font-size: .65rem; font-weight: 600; color: #fca5a5;
    display: flex; align-items: center; gap: 4px; justify-content: flex-end;
    white-space: nowrap;
}

.bhero {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 100px;
    font-size: .66rem; font-weight: 600;
    background: rgba(255,255,255,.16); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.22); color: #fff;
    white-space: nowrap;
}
.bhero.bh-paid    { background: rgba(78,201,138,.25); border-color: rgba(78,201,138,.4); }
.bhero.bh-overdue { background: rgba(220,38,38,.25);  border-color: rgba(220,38,38,.4); }
.bhero.bh-pending { background: rgba(244,165,53,.22); border-color: rgba(244,165,53,.38); }

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 100px;
    font-size: .62rem; font-weight: 600; letter-spacing: .03em;
    white-space: nowrap;
}
.badge-paid    { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-200); }
.badge-unpaid  { background: var(--amber-pale); color: #92400e;         border: 1px solid var(--amber-light); }
.badge-overdue { background: var(--red-pale);   color: #991b1b;         border: 1px solid var(--red-light); }
.badge-pending { background: var(--indigo-pale); color: #3730a3;        border: 1px solid var(--indigo-light); }

.layout-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 18px;
    align-items: start;
}

.o-card {
    background: var(--neutral-0);
    border-radius: 14px;
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-bottom: 18px;
}
.o-card:last-child { margin-bottom: 0; }
.o-card-header {
    padding: 13px 18px;
    border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    flex-wrap: wrap; gap: 8px;
}
.o-card-header-title {
    font-size: .7rem; font-weight: 600;
    color: var(--neutral-600);
    display: flex; align-items: center; gap: 7px;
    text-transform: uppercase; letter-spacing: .07em;
}
.o-card-header-title i { color: var(--green-500); }
.card-body { padding: clamp(14px, 2.5vw, 20px); }

.info-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: clamp(12px, 2vw, 18px); margin-bottom: 16px;
}
.info-label {
    font-size: .58rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .1em;
    color: var(--neutral-400); margin-bottom: 4px;
}
.info-value {
    font-family: 'Fraunces', serif; font-size: .88rem;
    font-weight: 700; color: var(--neutral-900); line-height: 1.45;
    word-break: break-word;
}
.info-value.danger { color: var(--red); }

.cost-box {
    background: var(--green-50);
    border: 1px solid var(--green-100);
    border-radius: 11px;
    padding: clamp(12px, 2vw, 18px);
    position: relative; overflow: hidden;
}
.cost-row {
    display: flex; justify-content: space-between;
    align-items: center; gap: 8px;
    font-size: .8rem; padding: 7px 0;
    border-bottom: 1px dashed var(--green-100);
    flex-wrap: wrap;
}
.cost-row:last-child { border-bottom: none; }
.cost-row.total {
    border-top: 1.5px solid var(--green-200);
    border-bottom: none;
    padding-top: 11px; margin-top: 6px; flex-wrap: nowrap;
}
.cost-lbl { color: var(--neutral-500); display: flex; align-items: center; gap: 5px; flex-shrink: 0; }
.cost-lbl i { color: var(--green-500); opacity: .7; }
.cost-val { font-family: 'Fraunces', serif; font-weight: 700; color: var(--neutral-800); text-align: right; }
.cost-val.plus  { color: var(--green-600); }
.cost-val.grand { font-size: clamp(.92rem, 3vw, 1.08rem); font-weight: 700; color: var(--green-700); }
.tax-note {
    font-size: .63rem; color: var(--neutral-400);
    margin-top: 10px; padding-top: 8px;
    border-top: 1px dashed var(--green-100);
    display: flex; align-items: center; gap: 5px;
}

.notes-box {
    margin-top: 14px; padding: 11px 13px;
    background: var(--green-50); border: 1px solid var(--green-100);
    border-radius: 10px; font-size: .78rem; color: var(--green-700); line-height: 1.65;
}

.pay-row {
    padding: clamp(12px, 2vw, 16px) clamp(14px, 2.5vw, 20px);
    border-bottom: 1px solid var(--neutral-100);
    transition: background .14s;
}
.pay-row:last-child { border-bottom: none; }
.pay-row:hover { background: var(--green-50); }
.pay-row-top {
    display: flex; justify-content: space-between;
    align-items: flex-start; gap: 10px;
}
.pay-info { min-width: 0; flex: 1; }
.pay-code {
    font-family: 'Fraunces', serif; font-size: .85rem;
    font-weight: 700; color: var(--neutral-900); margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.pay-meta { font-size: .68rem; color: var(--neutral-400); line-height: 1.6; }
.pay-right { text-align: right; flex-shrink: 0; min-width: 80px; }
.pay-amount {
    font-family: 'Fraunces', serif; font-size: .88rem;
    font-weight: 700; color: var(--green-700); white-space: nowrap;
}
.pay-footer {
    display: flex; align-items: center;
    gap: 8px; margin-top: 8px; flex-wrap: wrap;
}
.proof-link {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .68rem; font-weight: 600; color: var(--blue);
    text-decoration: none;
    padding: 5px 11px; min-height: 32px;
    border: 1px solid var(--blue-light); border-radius: 7px;
    background: var(--blue-pale); transition: all .15s;
    white-space: nowrap;
}
.proof-link:hover { background: var(--blue); color: #fff; border-color: var(--blue); }

.verified-note {
    font-size: .68rem; color: var(--green-600);
    margin-top: 6px; display: flex; align-items: center;
    gap: 5px; flex-wrap: wrap;
}
.reject-box {
    margin-top: 8px; padding: 9px 12px;
    background: var(--red-pale); border: 1px solid var(--red-light);
    border-radius: 9px; font-size: .74rem; color: #991b1b;
    display: flex; gap: 8px; align-items: flex-start;
}
.reject-box i { flex-shrink: 0; margin-top: 1px; }
.empty-hist {
    text-align: center; padding: 40px 20px;
    color: var(--neutral-400); font-size: .8rem;
}
.empty-hist i { font-size: 2rem; display: block; opacity: .2; margin-bottom: 8px; }

.pay-panel-header {
    padding: clamp(16px, 3vw, 22px);
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 40%, var(--green-700) 70%, var(--green-500) 100%);
    position: relative; overflow: hidden;
}

.pph-inner { position: relative; z-index: 1; }
.pph-label {
    font-size: .57rem; font-weight: 600; letter-spacing: .12em;
    text-transform: uppercase; color: rgba(255,255,255,.55); margin-bottom: 5px;
}
.pph-amount {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.15rem, 4.5vw, 1.55rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.03em; line-height: 1;
    word-break: keep-all; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.pph-inv {
    font-size: .7rem; color: rgba(255,255,255,.48);
    margin-top: 5px; word-break: break-all;
}

.sec-label {
    font-size: .6rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .09em; color: var(--neutral-400); margin-bottom: 10px;
}

.method-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 8px; margin-bottom: 14px;
}
.method-card {
    border: 1.5px solid var(--neutral-200); border-radius: 12px;
    padding: clamp(12px, 2vw, 15px) 6px;
    cursor: pointer; background: var(--neutral-0);
    display: flex; flex-direction: column;
    align-items: center; gap: 5px;
    text-align: center; user-select: none;
    position: relative; min-width: 0;
    transition: border-color .2s, background .2s, transform .18s;
}
.method-card:hover { border-color: var(--green-400); background: var(--green-50); transform: translateY(-2px); }
.method-card.selected {
    border-color: var(--green-500); background: var(--green-50);
    box-shadow: 0 0 0 3px rgba(42,179,107,.12);
}
.method-card.selected::after {
    content: '\F26B'; font-family: 'bootstrap-icons';
    position: absolute; top: 6px; right: 7px;
    color: var(--green-600); font-size: .7rem; font-weight: 700;
}
.mc-icon  { font-size: 1.35rem; line-height: 1; }
.mc-title {
    font-family: 'Instrument Sans', sans-serif;
    font-weight: 600; font-size: .72rem; color: var(--neutral-900);
}
.mc-desc  { font-size: .57rem; color: var(--neutral-400); line-height: 1.3; }
.method-card.selected .mc-title { color: var(--green-700); }

.bank-opts {
    display: none; flex-wrap: wrap; gap: 6px;
    margin-bottom: 14px; padding: 11px 12px;
    background: var(--green-50); border-radius: 10px;
    border: 1px solid var(--green-100);
}
.bank-opts.show { display: flex; }

.bank-pill {
    padding: 6px 13px; border-radius: 100px;
    border: 1.5px solid var(--neutral-200);
    font-size: .7rem; font-weight: 600; color: var(--neutral-600);
    cursor: pointer; background: var(--neutral-0);
    transition: all .18s; min-height: 32px;
    display: inline-flex; align-items: center;
}
.bank-pill:hover { border-color: var(--green-500); color: var(--green-700); }
.bank-pill.selected { background: var(--green-600); border-color: var(--green-600); color: #fff; }

.btn-pay {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; width: 100%;
    padding: 13px 16px; min-height: 48px;
    border-radius: 11px; border: none;
    background: linear-gradient(135deg, var(--green-700), var(--green-500));
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: clamp(.78rem, 2.5vw, .875rem);
    font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 16px rgba(42,179,107,.28);
    transition: transform .2s, box-shadow .2s;
    text-align: center;
}
.btn-pay:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(42,179,107,.38); }
.btn-pay:disabled { background: var(--neutral-200); color: var(--neutral-400); cursor: not-allowed; box-shadow: none; }
.btn-pay.state-pending { background: linear-gradient(135deg, var(--accent), #d97706); cursor: default; }
.btn-pay.state-success { background: linear-gradient(135deg, var(--green-700), var(--green-600)); cursor: default; }

.pending-banner {
    display: none; margin-top: 12px; padding: 10px 13px;
    border-radius: 10px; background: var(--amber-pale);
    border: 1px solid var(--amber-light);
    font-size: .75rem; color: #92400e; line-height: 1.6;
}
.pending-banner.show { display: block; }

.secure-strip {
    display: flex; align-items: center; justify-content: center;
    gap: 5px; margin-top: 10px; font-size: .65rem;
    color: var(--neutral-400); flex-wrap: wrap; text-align: center;
}
.secure-strip i { color: var(--green-500); }

.pending-warning {
    padding: 12px 13px; border-radius: 10px;
    background: var(--amber-pale); border: 1px solid var(--amber-light);
    font-size: .77rem; color: #92400e;
    display: flex; gap: 10px; align-items: flex-start; line-height: 1.55;
}
.pending-warning i { flex-shrink: 0; margin-top: 1px; }

.pay-placeholder {
    text-align: center; padding: 16px 0 8px;
    color: var(--neutral-400); font-size: .78rem;
}
.pay-placeholder i { font-size: 1.6rem; display: block; margin-bottom: 6px; opacity: .2; }

.status-card {
    text-align: center; padding: clamp(22px, 5vw, 36px) 20px;
    border-radius: 14px; margin-bottom: 16px;
    border: 1px solid;
}
.status-card.sc-paid    { background: var(--green-50); border-color: var(--green-200); }
.status-card.sc-pending { background: var(--amber-pale); border-color: var(--amber-light); }
.status-icon { font-size: 2rem; margin-bottom: 8px; }
.status-card h6 {
    font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; margin-bottom: 6px;
}
.status-card p  { font-size: .76rem; color: var(--neutral-500); line-height: 1.65; max-width: 260px; margin: 0 auto; }
.status-card.sc-paid h6    { color: var(--green-800); }
.status-card.sc-pending h6 { color: #92400e; }

.guide-step {
    display: flex; gap: 10px; padding: 9px 0;
    border-bottom: 1px solid var(--green-50); font-size: .77rem;
}
.guide-step:last-child { border-bottom: none; }
.guide-num {
    width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--green-700), var(--green-500));
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: .58rem; font-weight: 600;
}
.guide-num.warn { background: linear-gradient(135deg, var(--red), #b91c1c); }
.guide-txt { color: var(--neutral-600); line-height: 1.65; word-break: break-word; }

.alert-strip {
    display: flex; align-items: flex-start; gap: 9px;
    padding: 11px 14px; border-radius: 10px;
    margin-bottom: 12px;
    font-size: .8rem; border: 1px solid; line-height: 1.55;
}
.alert-strip i { flex-shrink: 0; margin-top: 2px; }
.alert-success { background: var(--green-50);   border-color: var(--green-200);   color: var(--green-800); }
.alert-warning { background: var(--amber-pale);  border-color: var(--amber-light); color: #92400e; }
.alert-error   { background: var(--red-pale);    border-color: var(--red-light);   color: #991b1b; }
.alert-info    { background: var(--indigo-pale); border-color: var(--indigo-light);color: #3730a3; }

@keyframes spin { to { transform: rotate(360deg); } }
.spinner {
    display: inline-block; width: 13px; height: 13px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff; border-radius: 50%;
    animation: spin .7s linear infinite; flex-shrink: 0;
}

@media (max-width: 860px) {
    .layout-grid { grid-template-columns: 1fr; }
    .col-right { order: -1; }
    .ihs-stat, .ihs-vdiv { display: none; }
}
@media (max-width: 600px) {
    .ihs-amount {
        border-left: none; border-top: 1px solid rgba(255,255,255,.1);
        width: 100%; flex: unset;
        align-items: flex-start; text-align: left;
        padding: 12px clamp(16px, 3vw, 28px) 16px;
    }
    .ihs-amount-val {
        white-space: normal; word-break: keep-all;
        font-size: clamp(1.3rem, 7vw, 1.7rem);
        overflow: visible; text-overflow: unset;
    }
    .ihs-overdue-note { justify-content: flex-start; }
    .info-grid { grid-template-columns: 1fr; gap: 10px; }
    .method-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
    .page-body { padding: 14px 12px 64px; }
    .card-body { padding: 13px; }
    .o-card-header { padding: 11px 13px; }
    .pay-code { font-size: .8rem; }
    .guide-step { font-size: .74rem; gap: 8px; }
    .guide-num { width: 22px; height: 22px; font-size: .54rem; }
    .cost-row { font-size: .76rem; }
    .cost-val.grand { font-size: .9rem; }
    .info-value { font-size: .82rem; }
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
<div class="tenant-bg"></div>

@include('partials.tenant-sidebar')

<div class="page-body">
    <a href="{{ route('tenant.invoices.index') }}" class="btn-back" style="margin-bottom: 16px;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <div style="
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 35%, var(--green-700) 70%, var(--green-500) 100%);
        border-radius: 16px; padding: 28px 32px; margin-bottom: 20px;
        position: relative; overflow: hidden;
        box-shadow: 0 8px 32px rgba(14,63,39,.28);
        display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    ">
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(134,217,174,.14) 0%,transparent 50%),radial-gradient(circle at 10% 90%,rgba(78,201,138,.10) 0%,transparent 40%);pointer-events:none"></div>
        <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:40px 40px;pointer-events:none"></div>
        <div style="position:relative;z-index:1">
            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);padding:3px 12px;border-radius:100px;font-size:.62rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.8);margin-bottom:10px">
                <span style="width:5px;height:5px;border-radius:50%;background:var(--green-300);flex-shrink:0"></span>
                Tagihan
            </div>
            <h1 style="font-family:'Fraunces',serif;font-size:clamp(1.3rem,3vw,1.9rem);font-weight:700;color:#fff;letter-spacing:-.025em;line-height:1.15;margin-bottom:5px">
                Detail <em style="font-style:italic;font-weight:400;color:#fff">Tagihan</em>
            </h1>
            <p style="font-size:.82rem;color:rgba(255,255,255,.6)">{{ $invoice->invoice_number }}</p>
        </div>
    </div>
    @foreach(['success','info','warning','error'] as $t)
        @if(session($t))
        <div class="alert-strip alert-{{ $t === 'error' ? 'error' : $t }}">
            <i class="bi bi-{{ $t === 'success' ? 'check-circle-fill' : ($t === 'error' ? 'exclamation-circle-fill' : ($t === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill')) }}"></i>
            <div>{!! session($t) !!}</div>
        </div>
        @endif
    @endforeach

    @if(request('payment') === 'success')
    <div class="alert-strip alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div><strong>Pembayaran berhasil.</strong> Status invoice diperbarui otomatis.</div>
    </div>
    @elseif(request('payment') === 'pending')
    <div class="alert-strip alert-warning">
        <i class="bi bi-hourglass-split"></i>
        <div><strong>Instruksi pembayaran sudah dikirim.</strong> Selesaikan sesuai instruksi yang tampil.</div>
    </div>
    @endif

    @php
        $sb = match($invoice->status) {
            'paid'                 => ['paid',    'check-circle-fill',         'Lunas'],
            'pending_verification' => ['pending', 'hourglass-split',           'Menunggu Verifikasi'],
            'overdue'              => ['overdue', 'exclamation-triangle-fill', 'Terlambat'],
            default                => ['unpaid',  'receipt',                   'Belum Dibayar'],
        };
    @endphp


    @php
        $bhClass = match($invoice->status) {
            'paid'                 => 'bh-paid',
            'pending_verification' => 'bh-pending',
            'overdue'              => 'bh-overdue',
            default                => '',
        };
        $bhIcon = match($invoice->status) {
            'paid'                 => 'check-circle-fill',
            'pending_verification' => 'hourglass-split',
            'overdue'              => 'exclamation-triangle-fill',
            default                => 'receipt',
        };
        $bhLabel = match($invoice->status) {
            'paid'                 => 'Lunas',
            'pending_verification' => 'Menunggu Verifikasi',
            'overdue'              => 'Terlambat',
            default                => 'Belum Dibayar',
        };
    @endphp
    @php
        $daysLeftTenant = $invoice->due_date
            ? now()->startOfDay()->diffInDays($invoice->due_date->startOfDay(), false)
            : null;
    @endphp
    <div class="inv-hero">
        <div class="inv-hero-grid"></div>
        <div class="inv-hero-inner">
            <div class="ihs-left">
                <div class="bhero {{ $bhClass }}">
                    <i class="bi bi-{{ $bhIcon }}"></i> {{ $bhLabel }}
                </div>
                <div class="ihs-num" style="margin-top:10px">{{ $invoice->invoice_number }}</div>
                <div class="ihs-desc">
                    @if($invoice->room) Kamar {{ $invoice->room->room_number }} @endif
                    @if($invoice->period_start && $invoice->period_end)
                        &middot; {{ $invoice->period_start->format('d M') }} – {{ $invoice->period_end->format('d M Y') }}
                    @endif
                </div>
            </div>
            <div class="ihs-vdiv"></div>
            <div class="ihs-stat">
                <div class="ihs-stat-lbl">Jatuh Tempo</div>
                <div class="ihs-stat-val">{{ $invoice->due_date?->format('d M Y') }}</div>
            </div>
            <div class="ihs-vdiv"></div>
            <div class="ihs-stat">
                <div class="ihs-stat-lbl">Transaksi</div>
                <div class="ihs-stat-val">{{ $payments->count() }}x</div>
            </div>
            <div class="ihs-amount">
                <div class="ihs-amount-lbl">Total Tagihan</div>
                <div class="ihs-amount-val">{{ $invoice->formatted_total }}</div>
                @if($daysLeftTenant === 0)
                <div class="ihs-overdue-note" style="color:#fde68a">
                    <i class="bi bi-clock-fill"></i>
                    Jatuh tempo hari ini
                </div>
                @elseif($invoice->isOverdue())
                <div class="ihs-overdue-note">
                    <i class="bi bi-clock-fill"></i>
                    Terlambat {{ abs($invoice->days_until_due) }} hari
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="layout-grid">
        <div class="col-left">
            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-receipt-cutoff"></i>Informasi Tagihan
                    </span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-label">Nomor Invoice</div>
                            <div class="info-value">{{ $invoice->invoice_number }}</div>
                        </div>
                        <div>
                            <div class="info-label">Kamar</div>
                            <div class="info-value">
                                {{ $invoice->room?->room_number }}
                                @if($invoice->room?->room_name)
                                    <span style="font-weight:500;color:var(--neutral-500)"> ({{ $invoice->room->room_name }})</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Periode Sewa</div>
                            <div class="info-value" style="font-size:.82rem">
                                {{ $invoice->period_start?->format('d M Y') }} – {{ $invoice->period_end?->format('d M Y') }}
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Jatuh Tempo</div>
                            <div class="info-value {{ $daysLeftTenant !== null && $daysLeftTenant < 0 ? 'danger' : '' }}">
                                {{ $invoice->due_date?->format('d M Y') }}
                                @if($daysLeftTenant === 0)
                                    <span class="badge badge-unpaid" style="font-size:.55rem;margin-left:4px;vertical-align:middle">
                                        Hari ini
                                    </span>
                                @elseif($invoice->isOverdue())
                                    <span class="badge badge-overdue" style="font-size:.55rem;margin-left:4px;vertical-align:middle">
                                        Terlambat {{ abs($invoice->days_until_due) }}h
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="cost-box">
                        <div class="cost-row">
                            <span class="cost-lbl"><i class="bi bi-house"></i> Sewa Kamar</span>
                            <span class="cost-val">Rp {{ number_format($invoice->base_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($invoice->service_amount > 0)
                        <div class="cost-row">
                            <span class="cost-lbl"><i class="bi bi-plus-circle"></i> Layanan Tambahan</span>
                            <span class="cost-val plus">+Rp {{ number_format($invoice->service_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="cost-row total">
                            <span style="font-weight:600;color:var(--neutral-700)">Total Tagihan</span>
                            <span class="cost-val grand">{{ $invoice->formatted_total }}</span>
                        </div>
                        @if(isset($invoice->tax_amount) && $invoice->tax_amount > 0)
                        @endif
                    </div>

                    @if($invoice->notes)
                    @php
                        $notesRaw2 = $invoice->notes;
                        $intro2    = $notesRaw2;
                        $services2 = [];
                        if (str_contains($notesRaw2, 'Layanan tambahan:')) {
                            [$introPart2, $servicesPart2] = explode('Layanan tambahan:', $notesRaw2, 2);
                            $intro2    = trim($introPart2);
                            $raw2 = trim($servicesPart2);
                            if (str_contains($raw2, ',')) {
                                $services2 = array_filter(array_map('trim', explode(',', $raw2)));
                            } else {
                                $services2 = array_filter(array_map('trim', preg_split('/\s*-\s*/', $raw2)));
                            }
                        }
                        preg_match('/Bayar untuk[^.]*\.?/', $notesRaw2, $closingMatch2);
                        $closing2 = trim($closingMatch2[0] ?? '');
                    @endphp
                    <div class="notes-box" style="padding:0;background:transparent;border:none">
                        <div style="display:flex;align-items:center;gap:6px;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--green-600);margin-bottom:8px">
                            <i class="bi bi-chat-left-text-fill"></i> Catatan
                        </div>
                        @if($intro2)
                        <p style="font-size:.78rem;color:var(--neutral-600);line-height:1.6;margin:0 0 10px">{{ $intro2 }}</p>
                        @endif
                        @if(count($services2) > 0)
                        <div style="font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--neutral-400);margin-bottom:6px">Layanan Tambahan</div>
                        <div style="display:flex;flex-direction:column;gap:4px;margin-bottom:10px">
                            @foreach($services2 as $svc2)
                            <div style="display:flex;align-items:center;gap:8px;font-size:.78rem;color:var(--neutral-600);padding:5px 10px;background:var(--neutral-0);border-radius:7px;border:1px solid var(--green-100)">
                                <i class="bi bi-check-circle-fill" style="color:var(--green-400);font-size:.68rem;flex-shrink:0"></i>
                                {{ $svc2 }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @if($closing2)
                        <p style="font-size:.75rem;color:var(--green-600);font-weight:600;margin:0;padding-top:8px;border-top:1px solid var(--green-100)">
                            <i class="bi bi-info-circle"></i> {{ $closing2 }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-clock-history"></i>Riwayat Pembayaran
                    </span>
                    @if($payments->count() > 0)
                    <span class="badge badge-paid">{{ $payments->count() }} transaksi</span>
                    @endif
                </div>

                @forelse($payments as $pay)
                <div class="pay-row">
                    <div class="pay-row-top">
                        <div class="pay-info">
                            <div class="pay-code">{{ $pay->payment_code }}</div>
                            <div class="pay-meta">
                                {{ $pay->method_label ?? $pay->method }}
                                &middot; {{ $pay->created_at->format('d M Y') }}
                            </div>
                            @if($pay->bank_name)
                            <div class="pay-meta">
                                <i class="bi bi-bank"></i>
                                {{ $pay->bank_name }}{{ $pay->account_number ? ' — ' . $pay->account_number : '' }}
                            </div>
                            @endif
                        </div>
                        <div class="pay-right">
                            @php
                                $ps = match($pay->status) {
                                    'verified' => ['paid',    'check-circle-fill', 'Terverifikasi'],
                                    'pending'  => ['pending', 'hourglass-split',   'Menunggu'],
                                    'rejected' => ['overdue', 'x-circle-fill',     'Ditolak'],
                                    default    => ['unpaid',  'receipt',           $pay->status],
                                };
                            @endphp
                            <div class="pay-amount">{{ $pay->formatted_amount ?? 'Rp ' . number_format($pay->amount, 0, ',', '.') }}</div>
                            <span class="badge badge-{{ $ps[0] }}" style="margin-top:5px">
                                <i class="bi bi-{{ $ps[1] }}"></i> {{ $ps[2] }}
                            </span>
                        </div>
                    </div>
                    <div class="pay-footer">
                        @if($pay->transfer_proof)
                        <a href="{{ asset('storage/' . $pay->transfer_proof) }}" target="_blank" class="proof-link">
                            <i class="bi bi-image"></i> Lihat Bukti
                        </a>
                        @endif
                    </div>
                    @if($pay->status === 'rejected' && $pay->rejection_reason)
                    <div class="reject-box">
                        <i class="bi bi-x-circle-fill"></i>
                        <div><strong>Ditolak:</strong> {{ $pay->rejection_reason }}</div>
                    </div>
                    @endif
                    @if($pay->status === 'verified')
                    <div class="verified-note">
                        <i class="bi bi-check-circle"></i>
                        Diverifikasi {{ $pay->verifier?->name }} &middot; {{ $pay->verified_at?->format('d M Y') }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-hist">
                    <i class="bi bi-clock-history"></i>
                    Belum ada riwayat pembayaran
                </div>
                @endforelse
            </div>
        </div>

        <div class="col-right">
            @php
                $hasPendingPayment = $payments->where('status', 'pending')->count() > 0;
                $canPay = in_array($invoice->status, ['unpaid', 'overdue']);
            @endphp

            @if($canPay)
            <div class="o-card" style="overflow:hidden">
                <div class="pay-panel-header">
                    <div class="pph-inner">
                        <div class="pph-label">Bayar Tagihan</div>
                        <div class="pph-amount">{{ $invoice->formatted_total }}</div>
                        <div class="pph-inv">{{ $invoice->invoice_number }}</div>
                    </div>
                </div>
                <div class="card-body" style="padding:16px 18px">
                    @if($hasPendingPayment)
                    <div class="pending-warning">
                        <i class="bi bi-hourglass-split"></i>
                        <div>Ada bukti pembayaran yang <strong>sedang diverifikasi</strong>. Tunggu hasilnya sebelum membayar ulang.</div>
                    </div>
                    @else
                    <div class="sec-label">1. Pilih Metode</div>
                    <div class="method-grid">
                        <div class="method-card" id="choice-qris" onclick="selectMethod('qris')">
                            <div class="mc-icon"><i class="bi bi-qr-code-scan" style="font-size:1.3rem;color:var(--green-600)"></i></div>
                            <div class="mc-title">QRIS</div>
                            <div class="mc-desc">GoPay &middot; Dana &middot; ShopeePay</div>
                        </div>
                        @if($midtransEnabled)
                        <div class="method-card" id="choice-bank" onclick="selectMethod('bank')">
                            <div class="mc-icon"><i class="bi bi-bank" style="font-size:1.3rem;color:var(--green-600)"></i></div>
                            <div class="mc-title">Virtual Account</div>
                            <div class="mc-desc">BCA &middot; BNI &middot; BRI</div>
                        </div>
                        @endif
                    </div>

                    <div class="bank-opts" id="bankOptions">
                        <div class="sec-label" style="width:100%;margin-bottom:4px">Pilih Bank VA:</div>
                        <div class="bank-pill" id="pill-bca" onclick="selectBank('bca_va')">BCA</div>
                        <div class="bank-pill" id="pill-bni" onclick="selectBank('bni_va')">BNI</div>
                        <div class="bank-pill" id="pill-bri" onclick="selectBank('bri_va')">BRI</div>
                    </div>

                    <div id="panel-midtrans" style="display:none">
                        <div class="sec-label" style="margin-top:4px">2. Konfirmasi &amp; Bayar</div>
                        <button id="payBtn" class="btn-pay" onclick="payNow()" disabled>
                            <i class="bi bi-credit-card-fill"></i>
                            <span id="payBtnLabel">Pilih metode dulu</span>
                        </button>
                        <div class="pending-banner" id="pendingBanner">
                            <strong>Instruksi sudah dibuat.</strong><br>
                            Selesaikan transfer ke VA atau scan QRIS. Status diperbarui otomatis.
                        </div>
                        <div class="secure-strip">
                            <i class="bi bi-shield-lock-fill"></i>
                            Aman &middot; Diproses Midtrans &middot; Konfirmasi Otomatis
                        </div>
                    </div>

                    <div class="pay-placeholder" id="panel-placeholder">
                        <i class="bi bi-hand-index-thumb"></i>
                        Pilih metode di atas untuk melanjutkan
                    </div>
                    @endif
                </div>
            </div>

            @elseif($invoice->status === 'pending_verification')
            <div class="status-card sc-pending">
                <div class="status-icon"><i class="bi bi-hourglass-split" style="color:var(--accent)"></i></div>
                <h6>Sedang Diverifikasi</h6>
                <p>Bukti pembayaran sudah diterima. Admin akan memverifikasi dalam 1×24 jam.</p>
            </div>

            @elseif($invoice->status === 'paid')
            <div class="status-card sc-paid">
                <div class="status-icon"><i class="bi bi-check-circle-fill" style="color:var(--green-500)"></i></div>
                <h6>Tagihan Lunas</h6>
                <p>Tagihan ini sudah lunas. Terima kasih telah membayar tepat waktu.</p>
            </div>
            @endif

            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-question-circle"></i>Panduan Pembayaran
                    </span>
                </div>
                <div style="padding:12px 18px 16px">
                    <div class="guide-step">
                        <div class="guide-num">1</div>
                        <div class="guide-txt">Pilih <strong>QRIS</strong> atau <strong>Virtual Account</strong> untuk konfirmasi otomatis</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-num">2</div>
                        <div class="guide-txt">Klik tombol bayar, selesaikan sesuai instruksi yang tampil</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-num">3</div>
                        <div class="guide-txt">Status diperbarui <strong>otomatis</strong> (tidak perlu mengunggah bukti)</div>
                    </div>
                    <div class="guide-step">
                        <div class="guide-num warn">!</div>
                        <div class="guide-txt" style="color:var(--red)">
                            Jatuh tempo: <strong>{{ $invoice->due_date?->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($midtransEnabled)
    @if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
@endif

<script>
let selectedMethod = null;
let selectedBank   = null;
let hasPendingPayment = false;

const snapEnabled  = @json($midtransEnabled);
const snapTokenUrl = '{{ route('tenant.invoices.snap-token', $invoice->id) }}';
const returnUrl    = '{{ route('tenant.invoices.show', $invoice->id) }}';

function selectMethod(method) {
    selectedMethod = method;
    selectedBank   = null;

    document.querySelectorAll('.method-card').forEach(el => el.classList.remove('selected'));
    const el = document.getElementById('choice-' + method);
    if (el) el.classList.add('selected');

    const pm = document.getElementById('panel-midtrans');
    const pp = document.getElementById('panel-placeholder');
    const bo = document.getElementById('bankOptions');

    if (pm) pm.style.display = 'none';
    if (pp) pp.style.display = 'none';
    if (bo) bo.classList.remove('show');

    if (method === 'qris') {
        if (pm) pm.style.display = 'block';
        setPayBtnReady(true);
    } else if (method === 'bank') {
        if (pm) pm.style.display = 'block';
        if (bo) bo.classList.add('show');
        setPayBtnReady(false);
    }
}

function selectBank(code) {
    selectedBank = code;
    document.querySelectorAll('.bank-pill').forEach(p => p.classList.remove('selected'));
    const map = { bca_va:'pill-bca', bni_va:'pill-bni', bri_va:'pill-bri' };
    const p = document.getElementById(map[code]);
    if (p) p.classList.add('selected');
    setPayBtnReady(true);
}

function setPayBtnReady(ready) {
    const btn   = document.getElementById('payBtn');
    const label = document.getElementById('payBtnLabel');
    if (!btn) return;
    btn.disabled = !ready;
    btn.className = 'btn-pay';
    if (!ready) {
        btn.innerHTML = '<i class="bi bi-bank"></i><span>Pilih bank dulu</span>';
        return;
    }
    if (selectedMethod === 'qris') {
        btn.innerHTML = '<i class="bi bi-qr-code-scan"></i><span>Bayar dengan QRIS</span>';
    } else {
        const names = { bca_va:'BCA', bni_va:'BNI', bri_va:'BRI' };
        btn.innerHTML = '<i class="bi bi-bank"></i><span>Bayar via VA ' + (names[selectedBank] ?? '') + '</span>';
    }
}

async function payNow() {
    if (!selectedMethod || (selectedMethod === 'bank' && !selectedBank)) {
        alert('Pilih metode pembayaran terlebih dahulu.'); return;
    }
    if (!snapEnabled) {
        alert('Pembayaran online belum tersedia. Hubungi admin.'); return;
    }
    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>&nbsp;Memproses...';

    try {
        const res  = await fetch(snapTokenUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ method: selectedMethod, bank: selectedBank })
        });
        const data = await res.json();
        if (!res.ok || !data.snap_token) throw new Error(data.error ?? 'Gagal mendapat token pembayaran.');

        window.snap.pay(data.snap_token, {
            onSuccess() {
                btn.className = 'btn-pay state-success';
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i>&nbsp;Berhasil!';
                setTimeout(() => { window.location.href = returnUrl + '?payment=success'; }, 1200);
            },
            onPending() {
                btn.className = 'btn-pay state-pending';
                btn.disabled  = true;
                btn.innerHTML = '<i class="bi bi-clock-fill"></i>&nbsp;Menunggu Pembayaran...';
                document.getElementById('pendingBanner')?.classList.add('show');
            },
            onError(r) {
                setPayBtnReady(true);
                alert('Terjadi kesalahan: ' + (r.status_message ?? 'Coba lagi.'));
            },
            onClose() {
                hasPendingPayment
                    ? document.getElementById('pendingBanner')?.classList.add('show')
                    : setPayBtnReady(true);
            }
        });
    } catch (err) {
        setPayBtnReady(true);
        alert(err.message);
    }
}

@if($midtransEnabled && in_array($invoice->status, ['unpaid','overdue']))
    @if(!($hasPendingPayment ?? false))
    document.addEventListener('DOMContentLoaded', () => setTimeout(() => selectMethod('qris'), 200));
    @endif
@endif
</script>
</body>
</html>