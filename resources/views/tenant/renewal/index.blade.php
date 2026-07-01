@php
    $activeServices = [];
    if ($activeBooking && $activeBooking->additional_services) {
        $activeServices = is_array($activeBooking->additional_services)
            ? array_keys($activeBooking->additional_services)
            : array_keys((array) $activeBooking->additional_services);
    }

    $roomPrice = $activeBooking?->room?->price ?? 0;

    $endDate = $activeBooking?->computed_end_date?->copy() ?? null;
    if (!$endDate && $activeBooking) {
        if (!empty($activeBooking->end_date)) {
            $endDate = \Carbon\Carbon::parse($activeBooking->end_date);
        } elseif ($activeBooking->check_in_date && $activeBooking->duration_months) {
            $endDate = \Carbon\Carbon::parse($activeBooking->check_in_date)
                ->addMonths($activeBooking->duration_months);
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Perpanjangan Sewa — Urban Kost</title>
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
    --accent-pale: #fffbf0;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-light:  #dbeafe;
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
    padding-bottom: env(safe-area-inset-bottom, 0px);
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
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%),
        radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
}
.bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}
.page-wrap {
    max-width: 1160px;
    margin: 0 auto;
    padding: 32px 24px 64px;
}
@media (max-width: 768px) { .page-wrap { padding: 20px 16px 48px; } }
@media (max-width: 480px) { .page-wrap { padding: 16px 12px 40px; } }

.hero-banner {
    position: relative; overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 32px 28px;
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
    position: absolute; right: -100px; top: -100px;
    width: 340px; height: 340px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1);
    pointer-events: none; z-index: 1;
}
.hero-content { position: relative; z-index: 2; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82);
    margin-bottom: 12px;
}
.hero-eyebrow .dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--green-300); flex-shrink: 0;
}
.hero-title {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(1.4rem, 4vw, 2rem);
    color: #fff;
    line-height: 1.15;
    letter-spacing: -.025em;
    margin-bottom: 6px;
}
.hero-title em {
    font-style: italic;
    font-weight: 400;
    color: rgba(255,255,255,.72);
}
.hero-sub {
    font-size: .88rem;
    color: rgba(255,255,255,.62);
    max-width: 440px;
    line-height: 1.6;
}
.hero-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: rgba(255,255,255,.55);
    margin-bottom: 14px; flex-wrap: wrap;
}
.hero-breadcrumb a { color: rgba(255,255,255,.75); text-decoration: none; font-weight: 600; }
.hero-breadcrumb a:hover { color: #fff; }
.hero-breadcrumb-sep { font-size: .6rem; opacity: .5; }

.rn-grid { display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
@media (max-width: 1024px) { .rn-grid { grid-template-columns: 1fr 320px; } }
@media (max-width: 840px)  { .rn-grid { grid-template-columns: 1fr; } }

.alert-strip {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 13px 16px; border-radius: 10px;
    margin-bottom: 12px;
    font-size: .82rem; font-weight: 500;
    border: 1px solid; line-height: 1.55;
}
.alert-strip.success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip.warning { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
.alert-strip.error   { background: var(--red-pale); border-color: rgba(220,38,38,.25); color: var(--red); }
.alert-strip.info    { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }

.pending-banner {
    background: var(--amber-pale);
    border: 1px solid rgba(245,158,11,.3);
    border-radius: 12px; padding: 16px 18px;
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 20px; flex-wrap: wrap;
}
.pending-banner-icon {
    width: 40px; height: 40px; border-radius: 11px;
    background: var(--amber); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(245,158,11,.3);
}
.pending-banner-body { flex: 1; min-width: 160px; }
.pending-banner-title { font-weight: 600; font-size: .88rem; color: #78350f; margin-bottom: 2px; }
.pending-banner-sub   { font-size: .76rem; color: #92400e; line-height: 1.5; }
.btn-amber {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 9px;
    background: var(--amber); color: #fff;
    font-weight: 600; font-size: .78rem; text-decoration: none;
    border: none; cursor: pointer; transition: background .15s; white-space: nowrap;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-amber:hover { background: #d97706; color: #fff; }

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
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.o-card-header-left { display: flex; align-items: center; gap: 9px; }
.o-card-header-icon {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.o-card-header-title { font-size: .8rem; font-weight: 600; color: var(--neutral-700); }
.o-card-header-sub   { font-size: .7rem; color: var(--neutral-400); margin-top: 1px; }
.o-card-body { padding: 22px; }

.room-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 45%, var(--green-600) 100%);
    padding: 22px 24px 18px;
    position: relative; overflow: hidden;
}

.room-hero-inner {
    position: relative; z-index: 1;
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.room-hero-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
    border-radius: 100px; padding: 4px 12px;
    font-size: .68rem; font-weight: 600; color: #fff;
    margin-bottom: 10px; backdrop-filter: blur(8px);
}
.room-hero-num {
    font-family: 'Fraunces', serif;
    font-size: 2rem; font-weight: 700; color: #fff;
    line-height: 1; letter-spacing: -.02em;
}
.room-hero-name { font-size: .78rem; color: rgba(255,255,255,.55); margin-top: 4px; }
.room-hero-price-tag { display: flex; flex-direction: column; align-items: flex-end; flex-shrink: 0; }
.room-hero-price-label {
    font-size: .63rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .08em; color: rgba(255,255,255,.5); margin-bottom: 3px;
}
.room-hero-price-val {
    font-family: 'Fraunces', serif;
    font-size: 1.4rem; font-weight: 700; color: #fff; white-space: nowrap; letter-spacing: -.02em;
}
.room-hero-price-period { font-size: .68rem; color: rgba(255,255,255,.5); font-weight: 500; text-align: right; }

.tenure-track {
    position: relative; z-index: 1;
    background: rgba(255,255,255,.1); border-radius: 10px;
    padding: 13px 15px; margin-top: 16px;
    backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.12);
}
.tenure-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; gap: 8px; flex-wrap: wrap; }
.tenure-label  { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.55); }
.tenure-status { font-size: .72rem; font-weight: 600; padding: 2px 10px; border-radius: 100px; }
.tenure-status.ok      { background: rgba(78,201,138,.2); color: var(--green-300); border: 1px solid rgba(78,201,138,.3); }
.tenure-status.warn    { background: rgba(251,191,36,.2); color: #fbbf24; border: 1px solid rgba(251,191,36,.3); }
.tenure-status.expired { background: rgba(220,38,38,.2); color: #f87171; border: 1px solid rgba(220,38,38,.3); }
.tenure-bar-bg   { height: 4px; background: rgba(255,255,255,.15); border-radius: 3px; overflow: hidden; }
.tenure-bar-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--green-400), #fbbf24); transition: width .6s ease; }
.tenure-dates {
    display: flex; justify-content: space-between;
    margin-top: 8px; font-size: .65rem;
    color: rgba(255,255,255,.5); flex-wrap: wrap; gap: 4px;
}
.tenure-dates strong { color: rgba(255,255,255,.85); }
.deadline-info {
    margin-top: 10px; background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12); border-radius: 7px;
    padding: 8px 12px; font-size: .7rem; color: rgba(255,255,255,.7);
    display: flex; align-items: flex-start; gap: 7px; line-height: 1.5;
}
.deadline-info i { font-size: .85rem; flex-shrink: 0; margin-top: 1px; }

.services-strip {
    padding: 13px 20px;
    border-top: 1px solid var(--green-100);
    background: var(--green-50);
}
.services-strip-label {
    font-size: .62rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .1em;
    color: var(--green-600); margin-bottom: 8px;
}
.svc-pills { display: flex; flex-wrap: wrap; gap: 6px; }
.svc-pill {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--neutral-0); border: 1px solid var(--green-200);
    border-radius: 7px; padding: 4px 10px;
    font-size: .72rem; font-weight: 500; color: var(--green-700);
}
.renewal-start {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    padding: 12px 20px;
    border-top: 1px solid var(--neutral-100);
    background: linear-gradient(135deg, var(--neutral-0), var(--green-50));
}
.renewal-start-text { font-size: .78rem; color: var(--neutral-500); }
.renewal-start-date {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .75rem; font-weight: 600; color: var(--green-700);
    background: var(--green-100); border: 1px solid var(--green-200);
    padding: 5px 13px; border-radius: 100px;
}

.form-section-label {
    font-size: .63rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--neutral-400); margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}
.form-section-label::before {
    content: attr(data-step);
    width: 18px; height: 18px; border-radius: 50%;
    background: var(--green-600); color: #fff;
    font-size: .6rem; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dur-select-wrap { position: relative; margin-bottom: 20px; }
.dur-select-wrap select {
    width: 100%; padding: 13px 46px 13px 16px;
    border: 1.5px solid var(--neutral-200); border-radius: 10px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .9rem; font-weight: 600;
    color: var(--neutral-900); background: var(--neutral-0);
    appearance: none; -webkit-appearance: none;
    cursor: pointer; transition: border-color .2s, box-shadow .2s; outline: none;
}
.dur-select-wrap select:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.dur-select-wrap select:hover { border-color: var(--green-400); }
.dur-select-arrow { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--green-600); font-size: 1rem; }

.dur-preview {
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, var(--green-50), #f0fff4);
    border: 1.5px solid var(--green-200); border-radius: 12px;
    padding: 13px 16px; margin-bottom: 20px; transition: all .3s;
}
.dur-preview.highlight { border-color: var(--green-400); background: linear-gradient(135deg, var(--green-100), var(--green-50)); }
.dur-preview-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: var(--green-600); color: #fff; font-size: 1rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 3px 10px rgba(29,148,88,.3);
}
.dur-preview-label { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .09em; color: var(--green-600); margin-bottom: 2px; }
.dur-preview-val { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: var(--green-800); }
.dur-preview-sub { font-size: .7rem; color: var(--green-600); margin-top: 1px; }

.form-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--neutral-200) 20%, var(--neutral-200) 80%, transparent);
    margin: 20px 0;
}

.svc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
@media (max-width: 640px) { .svc-grid { grid-template-columns: 1fr; } }
.svc-item {
    border: 1.5px solid var(--neutral-200); border-radius: 12px; padding: 13px;
    cursor: pointer; transition: all .18s; display: flex; align-items: center; gap: 11px;
    background: var(--neutral-0); user-select: none; position: relative;
}
.svc-item:hover  { border-color: var(--green-300); background: var(--green-50); }
.svc-item.active { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.08); }
.svc-item.removing { border-color: var(--red); background: var(--red-pale); }
.svc-item-emoji { font-size: 1.5rem; flex-shrink: 0; line-height: 1; }
.svc-item-name  { font-size: .8rem; font-weight: 600; color: var(--neutral-900); line-height: 1.3; }
.svc-item-price { font-size: .7rem; color: var(--green-600); font-weight: 600; margin-top: 2px; }
.svc-checkbox   { position: absolute; top: 10px; right: 10px; width: 17px; height: 17px; accent-color: var(--green-600); }
.svc-state-tag {
    position: absolute; top: -1px; left: 10px;
    font-size: .54rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; padding: 2px 7px; border-radius: 0 0 6px 6px; line-height: 1.4;
}
.svc-state-current { background: var(--green-600); color: #fff; }
.svc-state-add     { background: var(--indigo);     color: #fff; }
.svc-state-remove  { background: var(--red);        color: #fff; }

.pay-method-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
.pay-method-item {
    border: 1.5px solid var(--neutral-200); border-radius: 12px; padding: 13px;
    cursor: pointer; user-select: none;
    display: flex; flex-direction: column; align-items: flex-start; gap: 4px;
    background: var(--neutral-0); transition: all .18s; position: relative;
}
.pay-method-item:hover  { border-color: var(--green-300); background: var(--green-50); }
.pay-method-item.active { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.08); }

.monthly-note {
    display: none; align-items: flex-start; gap: 9px;
    background: var(--blue-pale); border: 1px solid var(--blue-light);
    border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;
    font-size: .78rem; color: #1e40af; line-height: 1.55;
}
.monthly-note i { color: var(--blue); flex-shrink: 0; margin-top: 1px; }

.cost-card {
    background: linear-gradient(135deg, var(--indigo-pale), #f5f3ff);
    border: 1.5px solid var(--indigo-light); border-radius: 14px;
    padding: 18px 20px; margin-bottom: 20px;
}
.cost-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; font-size: .82rem; gap: 8px; }
.cost-row + .cost-row { border-top: 1px dashed var(--indigo-light); }
.cost-key { color: var(--neutral-500); display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
.cost-key i { font-size: .75rem; color: var(--indigo); opacity: .6; }
.cost-val { font-weight: 600; color: var(--neutral-900); text-align: right; word-break: break-all; }
.cost-total { border-top: 2px solid var(--indigo-light) !important; padding-top: 12px !important; margin-top: 6px; font-size: 1rem; }
.cost-total .cost-key { font-weight: 700; color: var(--neutral-900); }
.cost-total .cost-val { color: var(--indigo); font-size: 1.1rem; }
.cost-note {
    font-size: .7rem; color: var(--neutral-400);
    margin-top: 10px; padding-top: 10px;
    border-top: 1px dashed var(--indigo-light);
    display: flex; align-items: flex-start; gap: 5px; line-height: 1.5;
}
.cost-note i { flex-shrink: 0; color: var(--indigo); opacity: .6; margin-top: 1px; }

.btn-submit {
    display: flex; align-items: center; justify-content: center; gap: 9px;
    width: 100%; padding: 14px 16px; border-radius: 10px; border: none;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 50%, var(--green-500) 100%);
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .9rem; font-weight: 600;
    cursor: pointer; transition: all .22s;
    box-shadow: 0 4px 18px rgba(42,179,107,.28);
    min-height: 50px; text-align: center; line-height: 1.4;
}
.btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.38); }
.btn-submit:active:not(:disabled) { transform: translateY(0); }
.btn-submit:disabled { background: var(--neutral-200); color: var(--neutral-400); cursor: not-allowed; box-shadow: none; }
#submitBtnLabel { word-break: break-word; white-space: normal; max-width: 100%; }
.btn-submit-hint {
    text-align: center; margin-top: 10px;
    font-size: .72rem; color: var(--neutral-400);
    display: flex; align-items: center; justify-content: center; gap: 5px;
}

.history-item {
    padding: 15px 20px; border-bottom: 1px solid var(--neutral-100);
    transition: background .12s;
}
.history-item:last-child { border-bottom: none; }
.history-item:hover { background: var(--green-50); }
.history-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 8px; }
.history-room   { font-size: .85rem; font-weight: 600; color: var(--neutral-900); }
.history-date   { font-size: .72rem; color: var(--neutral-400); margin-top: 2px; }
.history-amount { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; color: var(--green-700); text-align: right; flex-shrink: 0; word-break: break-all; letter-spacing: -.015em; }
.history-amount-label { font-size: .65rem; color: var(--neutral-400); font-weight: 500; text-align: right; margin-bottom: 2px; }
.history-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
.history-badges { display: flex; flex-wrap: wrap; gap: 5px; align-items: center; }

.badge {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 3px 9px; border-radius: 100px;
    font-size: .68rem; font-weight: 600; white-space: nowrap; border: 1px solid transparent;
}
.badge-pending-admin   { background: var(--amber-pale); color: #92400e; border-color: var(--amber-light); }
.badge-pending-payment { background: var(--indigo-pale); color: var(--indigo); border-color: var(--indigo-light); }
.badge-approved  { background: var(--green-100); color: var(--green-700); border-color: var(--green-200); }
.badge-rejected  { background: var(--red-pale); color: #991b1b; border-color: var(--red-light); }
.badge-svc       { background: var(--neutral-100); color: var(--neutral-600); border-color: var(--neutral-200); }

.hist-actions { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.btn-hist {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 8px;
    font-size: .73rem; font-weight: 600; text-decoration: none;
    border: 1.5px solid var(--neutral-200); color: var(--neutral-600);
    background: var(--neutral-0); transition: all .15s; cursor: pointer;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-hist:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }
.btn-hist.pay   { border-color: var(--green-300); color: var(--green-700); background: var(--green-50); }
.btn-hist.pay:hover { background: var(--green-100); border-color: var(--green-500); }
.btn-hist.edit  { border-color: var(--indigo-light); color: var(--indigo); background: var(--indigo-pale); }
.btn-hist.edit:hover { border-color: var(--indigo); background: var(--indigo-light); }
.btn-hist.delete { border-color: var(--red-light); color: var(--red); background: var(--red-pale); padding: 5px 9px; }
.btn-hist.delete:hover { border-color: var(--red); background: #fecaca; }

.history-end-date {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .71rem; font-weight: 600; color: var(--green-700);
    background: var(--green-50); border: 1px solid var(--green-200);
    padding: 4px 10px; border-radius: 7px; margin-top: 6px;
}
.history-reject-reason {
    font-size: .71rem; color: #991b1b; background: var(--red-pale);
    border: 1px solid var(--red-light); border-radius: 7px;
    padding: 6px 10px; margin-top: 6px;
    display: flex; align-items: flex-start; gap: 5px; line-height: 1.4;
}
.history-reject-reason i { flex-shrink: 0; margin-top: 1px; }

.search-wrap { padding: 13px 20px; border-bottom: 1px solid var(--neutral-100); }
.search-inner { position: relative; }
.search-inner i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--neutral-400); font-size: .8rem; pointer-events: none; }
.search-input {
    width: 100%; padding: 9px 12px 9px 32px;
    border: 1.5px solid var(--neutral-200); border-radius: 8px;
    font-family: 'Instrument Sans', sans-serif; font-size: .8rem;
    color: var(--neutral-900); background: var(--neutral-0); outline: none; transition: border-color .2s;
}
.search-input:focus { border-color: var(--green-500); }

.no-room-card {
    background: linear-gradient(135deg, var(--green-50), #f0fff4);
    border-radius: 16px; border: 1.5px dashed var(--green-200);
    padding: 48px 24px; text-align: center;
}
.no-room-icon { font-size: 3rem; margin-bottom: 16px; opacity: .4; display: block; }
.no-room-title { font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 700; color: var(--neutral-700); margin-bottom: 8px; letter-spacing: -.015em; }
.no-room-sub   { font-size: .83rem; color: var(--neutral-500); }

.blocked-card {
    border-radius: 16px; border: 1.5px solid; padding: 32px 28px; text-align: center;
}
.blocked-card.danger  { background: linear-gradient(135deg, var(--red-pale), #fff5f5); border-color: var(--red-light); }
.blocked-card.warning { background: linear-gradient(135deg, var(--amber-pale), #fffdf0); border-color: var(--amber-light); }
.blocked-card-icon  {
    width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin: 0 auto 14px;
}
.blocked-card.danger  .blocked-card-icon { background: var(--red-pale); color: var(--red); }
.blocked-card.warning .blocked-card-icon { background: var(--amber-pale); color: var(--amber); }
.blocked-card-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; letter-spacing: -.015em; }
.blocked-card.danger  .blocked-card-title { color: #991b1b; }
.blocked-card.warning .blocked-card-title { color: #78350f; }
.blocked-card-desc { font-size: .82rem; line-height: 1.65; margin-bottom: 18px; }
.blocked-card.danger  .blocked-card-desc { color: #b91c1c; }
.blocked-card.warning .blocked-card-desc { color: #92400e; }
.btn-contact {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .78rem; font-weight: 600; padding: 9px 18px; border-radius: 9px;
    text-decoration: none; transition: all .18s; border: none; cursor: pointer;
    font-family: 'Instrument Sans', sans-serif;
}
.blocked-card.danger  .btn-contact { background: var(--red); color: #fff; box-shadow: 0 4px 14px rgba(220,38,38,.28); }
.blocked-card.danger  .btn-contact:hover { background: #b91c1c; }
.blocked-card.warning .btn-contact { background: var(--amber); color: #fff; box-shadow: 0 4px 14px rgba(245,158,11,.28); }
.blocked-card.warning .btn-contact:hover { background: #d97706; }

.warn-alert {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px; border-radius: 12px;
    background: var(--amber-pale); border: 1px solid rgba(245,158,11,.3);
    margin-bottom: 16px; font-size: .82rem; color: #78350f; line-height: 1.55;
}
.warn-alert i { flex-shrink: 0; margin-top: 2px; }
.warn-alert-title { font-weight: 600; margin-bottom: 3px; }

.empty-state { text-align: center; padding: 48px 20px; }
.empty-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: var(--green-50); border: 1.5px dashed var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: var(--green-400); margin: 0 auto 14px;
}
.empty-state h3 { font-size: .88rem; font-weight: 600; color: var(--neutral-600); margin-bottom: 5px; }
.empty-state p  { font-size: .8rem; color: var(--neutral-400); }

.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,31,24,.45); backdrop-filter: blur(6px);
    z-index: 9000; display: flex; align-items: center; justify-content: center;
    padding: 16px; opacity: 0; pointer-events: none; transition: opacity .22s;
}
.modal-overlay.open { opacity: 1; pointer-events: auto; }
.modal-box {
    background: var(--neutral-0); border-radius: 18px;
    box-shadow: var(--shadow-lg); width: 100%; max-width: 420px;
    overflow: hidden; transform: scale(.94) translateY(16px);
    transition: transform .25s ease, opacity .25s ease; opacity: 0;
}
.modal-overlay.open .modal-box { transform: scale(1) translateY(0); opacity: 1; }
.modal-header {
    padding: 24px 24px 0;
    display: flex; flex-direction: column; align-items: center;
    gap: 10px; text-align: center;
}
.modal-danger-icon {
    width: 52px; height: 52px; border-radius: 50%;
    background: var(--red-pale); display: flex; align-items: center;
    justify-content: center; font-size: 1.4rem; color: var(--red);
    border: 1.5px solid var(--red-light);
}
.modal-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); letter-spacing: -.015em; }
.modal-sub   { font-size: .82rem; color: var(--neutral-500); line-height: 1.5; }
.modal-info-box {
    margin: 16px 24px 0;
    background: var(--neutral-50); border: 1px solid var(--neutral-200);
    border-radius: 10px; padding: 12px 14px;
    font-size: .82rem; color: var(--neutral-700); line-height: 1.7;
}
.modal-info-box strong { color: var(--neutral-900); }
.modal-footer { padding: 20px 24px; display: flex; gap: 10px; }
.btn-cancel-m {
    flex: 1; padding: 11px; border-radius: 10px;
    border: 1.5px solid var(--neutral-200); background: var(--neutral-0);
    color: var(--neutral-600); font-family: 'Instrument Sans', sans-serif;
    font-size: .85rem; font-weight: 600; cursor: pointer; transition: all .15s;
}
.btn-cancel-m:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
.btn-danger-m {
    flex: 1; padding: 11px; border-radius: 10px; border: none;
    background: var(--red); color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .85rem; font-weight: 600; cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 14px rgba(220,38,38,.28);
}
.btn-danger-m:hover    { background: #b91c1c; box-shadow: 0 6px 18px rgba(220,38,38,.4); }
.btn-danger-m:disabled { background: var(--neutral-300); box-shadow: none; cursor: not-allowed; }

@media (max-width: 480px) {
    .hero-banner { padding: 22px 18px; }
    .hero-arc    { display: none; }
    .hero-title  { font-size: 1.25rem; }
    .room-hero-num { font-size: 1.55rem; }
    .room-hero-price-val { font-size: 1.1rem; }
    .o-card-body { padding: 14px; }
    .o-card-header { padding: 12px 14px; }
    .cost-card { padding: 14px; }
    .history-item { padding: 13px 14px; }
    .search-wrap { padding: 11px 14px; }
    .modal-box { border-radius: 14px; }
    .modal-header { padding: 18px 18px 0; }
    .modal-footer { padding: 16px 18px; }
    .modal-info-box { margin: 12px 18px 0; font-size: .76rem; }
}
</style>
</head>
<body>

<div class="bg-layer"><div class="bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-wrap">

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Perpanjang Sewa</div>
            <h1 class="hero-title">Perpanjangan<br><em>Sewa Kamar</em></h1>
            <p class="hero-sub">Perpanjang masa sewa kamar Anda dengan mudah dan cepat.</p>
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

    @if(isset($pendingRenewal) && $pendingRenewal)
    <div class="pending-banner">
        <div class="pending-banner-icon"><i class="bi bi-clock-fill"></i></div>
        <div class="pending-banner-body">
            <div class="pending-banner-title">Ada Perpanjangan Sedang Diproses</div>
            <div class="pending-banner-sub">
                Kamar {{ $pendingRenewal->room?->room_number }} &middot; {{ $pendingRenewal->duration_months }} bulan &middot;
                @if($pendingRenewal->status === 'pending_admin') Menunggu persetujuan admin
                @elseif($pendingRenewal->status === 'pending_payment') Menunggu pembayaran
                @else {{ $pendingRenewal->status }}
                @endif
                @if($pendingRenewal->new_end_date)
                &middot; Berakhir baru: <strong>{{ \Carbon\Carbon::parse($pendingRenewal->new_end_date)->format('d M Y') }}</strong>
                @endif
            </div>
        </div>
        <a href="{{ $pendingRenewal->status === 'pending_payment' ? route('tenant.renewal.invoice', $pendingRenewal->id) : route('tenant.renewal.show', $pendingRenewal->id) }}"
           class="btn-amber">
            <i class="bi bi-arrow-right-circle-fill"></i>
            {{ $pendingRenewal->status === 'pending_payment' ? 'Bayar Sekarang' : 'Lihat Detail' }}
        </a>
    </div>
    @endif

    <div class="rn-grid">
        <div>
            @if(!$activeBooking)
            <div class="no-room-card">
                <i class="bi bi-house-door no-room-icon"></i>
                <div class="no-room-title">Tidak Ada Kamar Aktif</div>
                <div class="no-room-sub">Perpanjangan hanya tersedia untuk penghuni aktif yang memiliki booking yang disetujui.</div>
            </div>

            @elseif($renewalBlocked && $renewalBlockReason === 'advance_booker')
            <div class="blocked-card danger">
                <div class="blocked-card-icon"><i class="bi bi-lock-fill"></i></div>
                <div class="blocked-card-title">Perpanjangan Tidak Tersedia</div>
                <div class="blocked-card-desc">
                    Kamar <strong>{{ $activeBooking->room?->room_number }}</strong> sudah tercatat memiliki
                    penghuni baru. Perpanjangan sewa tidak dapat dilakukan.
                    <br><br>
                    Silakan hubungi admin untuk informasi lebih lanjut mengenai perpindahan atau ketersediaan kamar lain.
                </div>
                <a href="https://wa.me/6281229302431" target="_blank" class="btn-contact">
                    <i class="bi bi-whatsapp"></i> Hubungi Admin
                </a>
            </div>

            @elseif($renewalBlocked && $renewalBlockReason === 'waiting_list')
            <div class="blocked-card warning">
                <div class="blocked-card-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="blocked-card-title">Kamar Dalam Daftar Tunggu</div>
                <div class="blocked-card-desc">
                    Kamar <strong>{{ $activeBooking->room?->room_number }}</strong> sudah memiliki calon penghuni
                    baru yang terdaftar dalam daftar tunggu. Perpanjangan sewa tidak dapat diproses.
                    <br><br>
                    Hubungi admin jika Anda ingin mengkonfirmasi status kamar atau mencari alternatif.
                </div>
                <a href="https://wa.me/6281229302431" target="_blank" rel="noopener noreferrer" class="btn-contact">
                    <i class="bi bi-whatsapp"></i> Hubungi Admin
                </a>
            </div>

            @elseif($renewalBlocked && $renewalBlockReason === 'too_late')
            <div class="blocked-card danger">
                <div class="blocked-card-icon"><i class="bi bi-clock-history"></i></div>
                <div class="blocked-card-title">Batas Waktu Pengajuan Terlampaui</div>
                <div class="blocked-card-desc">
                    Perpanjangan harus diajukan <strong>paling lambat 10 hari sebelum masa sewa berakhir</strong>.
                    @if($activeBooking->computed_end_date)
                        <br><br>
                        Masa sewa Anda berakhir pada <strong>{{ $activeBooking->computed_end_date->format('d M Y') }}</strong>
                        @if(!is_null($daysUntilEnd))
                            @if($daysUntilEnd < 0)
                                (sudah berakhir {{ abs($daysUntilEnd) }} hari lalu).
                            @else
                                (sisa {{ $daysUntilEnd }} hari — sudah melewati batas pengajuan).
                            @endif
                        @endif
                    @endif
                    <br><br>Silakan hubungi admin untuk penanganan lebih lanjut.
                </div>
                <a href="https://wa.me/6281229302431" target="_blank" rel="noopener noreferrer" class="btn-contact">
                    <i class="bi bi-whatsapp"></i> Hubungi Admin
                </a>
            </div>

            @else
            @if(!is_null($daysUntilEnd) && $daysUntilEnd >= 10 && $daysUntilEnd <= 30)
            <div class="warn-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <div class="warn-alert-title">Segera Perpanjang Sewa Anda!</div>
                    <div>Masa sewa Anda berakhir dalam <strong>{{ $daysUntilEnd }} hari</strong>
                    ({{ $activeBooking->computed_end_date?->format('d M Y') }}).
                    Perpanjangan <strong>harus diajukan paling lambat 10 hari sebelum berakhir</strong>.
                    Batas akhir pengajuan: <strong>{{ $activeBooking->computed_end_date?->copy()->subDays(10)->format('d M Y') }}</strong>.</div>
                </div>
            </div>
            @endif

            <div class="o-card">
                <div class="room-hero">
                    <div class="room-hero-inner">
                        <div>
                            <div class="room-hero-badge"><i class="bi bi-house-fill"></i> Kamar Aktif Saya</div>
                            <div class="room-hero-num">Kamar {{ $activeBooking->room?->room_number }}</div>
                            <div class="room-hero-name">{{ $activeBooking->room?->room_name }}</div>
                        </div>
                        <div class="room-hero-price-tag">
                            <div class="room-hero-price-label">Sewa / Bulan</div>
                            <div class="room-hero-price-val">Rp {{ number_format($roomPrice, 0, ',', '.') }}</div>
                            <div class="room-hero-price-period">per bulan</div>
                        </div>
                    </div>

                    @if($endDate)
                    @php
                        $checkIn        = \Carbon\Carbon::parse($activeBooking->check_in_date);
                        $totalDays      = max(1, $checkIn->diffInDays($endDate));
                        $elapsedDays    = $checkIn->diffInDays(now());
                        $pct            = min(100, round(($elapsedDays / $totalDays) * 100));
                        $daysLeft       = $endDate->isPast() ? 0 : (int) now()->diffInDays($endDate);
                        $endSoon        = $daysLeft <= 30 && $daysLeft > 0;
                        $deadlineDate   = $endDate->copy()->subDays(10);
                        $deadlinePast   = now()->gt($deadlineDate);
                        $daysToDeadline = (int) now()->diffInDays($deadlineDate, false);
                    @endphp
                    <div class="tenure-track">
                        <div class="tenure-header">
                            <span class="tenure-label">Masa Sewa Berjalan</span>
                            @if($endDate->isPast())
                                <span class="tenure-status expired"><i class="bi bi-exclamation-triangle"></i> Sudah Berakhir</span>
                            @elseif($endSoon)
                                <span class="tenure-status warn"><i class="bi bi-lightning-charge"></i> Sisa {{ $daysLeft }} hari</span>
                            @else
                                <span class="tenure-status ok"><i class="bi bi-check"></i> Sisa {{ $daysLeft }} hari</span>
                            @endif
                        </div>
                        <div class="tenure-bar-bg">
                            <div class="tenure-bar-fill" style="width:{{ $pct }}%"></div>
                        </div>
                        <div class="tenure-dates">
                            <span>Masuk: <strong>{{ $checkIn->format('d M Y') }}</strong></span>
                            <span>Berakhir: <strong>{{ $endDate->format('d M Y') }}</strong></span>
                        </div>
                        <div class="deadline-info">
                            <i class="bi bi-calendar-event"></i>
                            <span>
                                Batas pengajuan perpanjangan:
                                <strong style="color:{{ $deadlinePast ? '#f87171' : 'var(--green-300)' }}">
                                    {{ $deadlineDate->format('d M Y') }}
                                </strong>
                                @if($deadlinePast)
                                    <span style="color:#f87171"> — Sudah terlampaui</span>
                                @else
                                    <span style="color:rgba(255,255,255,.5)"> ({{ $daysToDeadline }} hari lagi)</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif
                </div>

                @if($endDate)
                <div class="renewal-start">
                    <div class="renewal-start-text">Perpanjangan dimulai dari:</div>
                    <div class="renewal-start-date">
                        <i class="bi bi-calendar-plus-fill" style="color:var(--green-500)"></i>
                        {{ $endDate->format('d M Y') }}
                    </div>
                </div>
                @endif
            </div>

            @if(!isset($pendingRenewal) || !$pendingRenewal)
            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-left">
                        <div class="o-card-header-icon" style="background:var(--indigo-pale);color:var(--indigo)">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <div class="o-card-header-title">Ajukan Perpanjangan</div>
                            <div class="o-card-header-sub">Pilih durasi dan layanan yang diinginkan</div>
                        </div>
                    </div>
                </div>
                <div class="o-card-body">
                    <form method="POST" action="{{ route('tenant.renewal.initiate') }}" id="renewForm">
                        @csrf

                        <div class="form-section-label" data-step="1">Durasi Perpanjangan</div>
                        <div class="dur-select-wrap">
                            <select name="renewal_months" id="renewalMonths" onchange="handleDurationChange(this.value)">
                                @foreach([1,2,3,4,5,6,9,12] as $m)
                                <option value="{{ $m }}" {{ old('renewal_months', 1) == $m ? 'selected' : '' }}>
                                    {{ $m }} Bulan
                                </option>
                                @endforeach
                            </select>
                            <div class="dur-select-arrow"><i class="bi bi-chevron-down"></i></div>
                        </div>
                        @error('renewal_months')
                        <div style="color:var(--red);font-size:.78rem;margin-top:-14px;margin-bottom:14px;display:flex;align-items:center;gap:4px">
                            <i class="bi bi-exclamation-circle-fill" style="font-size:.75rem"></i> {{ $message }}
                        </div>
                        @enderror

                        <div class="dur-preview" id="durPreview">
                            <div class="dur-preview-icon"><i class="bi bi-calendar-check-fill"></i></div>
                            <div>
                                <div class="dur-preview-label">Masa Sewa Baru Berakhir</div>
                                <div class="dur-preview-val" id="newEndDateVal">—</div>
                                <div class="dur-preview-sub" id="newEndDateSub"></div>
                            </div>
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-section-label" data-step="2">Layanan Tambahan</div>
                        <p style="font-size:.76rem;color:var(--neutral-400);margin-bottom:14px;line-height:1.5">
                            Centang untuk <strong style="color:var(--green-600)">mengaktifkan</strong>,
                            hapus centang untuk <strong style="color:var(--red)">menghapus</strong> layanan.
                        </p>
                        <div class="svc-grid">
                            @foreach($SERVICES as $key => $svc)
                            @php
                                $isCurrent    = in_array($key, $activeServices);
                                $isOldChecked = old('services') !== null
                                    ? in_array($key, (array) old('services'))
                                    : $isCurrent;
                            @endphp
                            <label class="svc-item {{ $isOldChecked ? 'active' : '' }} {{ $isCurrent && !$isOldChecked ? 'removing' : '' }}"
                                   id="svc-label-{{ $key }}"
                                   data-price="{{ $svc['price'] }}"
                                   data-current="{{ $isCurrent ? '1' : '0' }}"
                                   style="{{ ($isCurrent || $isOldChecked) ? 'padding-top:22px' : '' }}">
                                <span class="svc-state-tag
                                      {{ $isCurrent && $isOldChecked ? 'svc-state-current' : ($isCurrent && !$isOldChecked ? 'svc-state-remove' : ($isOldChecked ? 'svc-state-add' : '')) }}"
                                      id="svc-tag-{{ $key }}"
                                      style="{{ (!$isCurrent && !$isOldChecked) ? 'display:none' : '' }}">
                                    {{ $isCurrent && $isOldChecked ? 'Aktif' : ($isCurrent && !$isOldChecked ? 'Akan Dihapus' : 'Ditambahkan') }}
                                </span>
                                <input type="checkbox" name="services[]" value="{{ $key }}" class="svc-checkbox"
                                       id="svc-{{ $key }}" {{ $isOldChecked ? 'checked' : '' }}
                                       onchange="updateService('{{ $key }}', '{{ $isCurrent ? '1' : '0' }}', this.checked)">
                                <div class="svc-item-emoji">{{ $svc['icon'] }}</div>
                                <div>
                                    <div class="svc-item-name">{{ $svc['label'] }}</div>
                                    <div class="svc-item-price">+Rp {{ number_format($svc['price'], 0, ',', '.') }}/bln</div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-section-label" data-step="3">Metode Pembayaran</div>
                        <p style="font-size:.76rem;color:var(--neutral-400);margin-bottom:14px;line-height:1.5">
                            Pilih cara pembayaran untuk perpanjangan ini.
                        </p>
                        <div class="pay-method-grid">
                            <label class="pay-method-item active" id="pay-label-fullpay">
                                <input type="radio" name="payment_method" value="fullpay"
                                    {{ old('payment_method','fullpay') === 'fullpay' ? 'checked' : '' }}
                                    style="position:absolute;top:10px;right:10px;accent-color:var(--green-600)"
                                    onchange="selectPayMethod('fullpay')">
                                <div class="svc-item-emoji"></div>
                                <div>
                                    <div class="svc-item-name">Bayar Lunas</div>
                                    <div class="svc-item-price">1 invoice, bayar sekaligus</div>
                                </div>
                            </label>
                            <label class="pay-method-item" id="pay-label-monthly">
                                <input type="radio" name="payment_method" value="monthly"
                                    {{ old('payment_method') === 'monthly' ? 'checked' : '' }}
                                    style="position:absolute;top:10px;right:10px;accent-color:var(--green-600)"
                                    onchange="selectPayMethod('monthly')">
                                <div class="svc-item-emoji"></div>
                                <div>
                                    <div class="svc-item-name">Bayar Per Bulan</div>
                                    <div class="svc-item-price">Invoice otomatis tiap bulan</div>
                                </div>
                            </label>
                        </div>
                        <div id="monthlyNote" class="monthly-note"
                             style="display:{{ old('payment_method') === 'monthly' ? 'flex' : 'none' }}">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Invoice <strong>bulan pertama</strong> dikirim saat admin menyetujui.
                            Invoice bulan berikutnya dibuat <strong>otomatis 3 hari sebelum periode mulai</strong>.
                            Perpanjangan selesai setelah semua bulan terbayar.</span>
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-section-label" data-step="4">Ringkasan Biaya</div>
                        <div class="cost-card">
                            <div class="cost-row">
                                <span class="cost-key"><i class="bi bi-door-open-fill"></i> Sewa kamar / bulan</span>
                                <span class="cost-val">Rp {{ number_format($roomPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="cost-row" id="svcCostRow" style="display:none">
                                <span class="cost-key"><i class="bi bi-stars"></i> Layanan tambahan / bulan</span>
                                <span class="cost-val" id="svcCostVal">Rp 0</span>
                            </div>
                            <div class="cost-row">
                                <span class="cost-key"><i class="bi bi-calculator-fill"></i> Total / bulan</span>
                                <span class="cost-val" id="monthlyTotalVal">Rp {{ number_format($roomPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="cost-row">
                                <span class="cost-key"><i class="bi bi-calendar-range-fill"></i> Durasi</span>
                                <span class="cost-val"><span id="durLabelVal">1</span> bulan</span>
                            </div>
                            <div class="cost-row cost-total">
                                <span class="cost-key">Total Perpanjangan</span>
                                <span class="cost-val" id="grandTotalVal">Rp {{ number_format($roomPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="cost-note">
                                <i class="bi bi-info-circle-fill"></i>
                                Invoice resmi diterbitkan oleh admin setelah pengajuan disetujui. Jumlah di atas adalah estimasi.
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-send-fill"></i>
                            <span id="submitBtnLabel">Ajukan Perpanjangan 1 Bulan</span>
                        </button>
                        <div class="btn-submit-hint">
                            <i class="bi bi-clock"></i>
                            Admin mereview dalam 1×24 jam. Invoice terbit setelah disetujui.
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @endif
        </div>

        <div>
            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-left">
                        <div class="o-card-header-icon" style="background:var(--accent-pale);color:#92400e;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <div class="o-card-header-title">Riwayat Perpanjangan</div>
                            <div class="o-card-header-sub">Semua pengajuan Anda</div>
                        </div>
                    </div>
                </div>

                <div class="search-wrap">
                    <div class="search-inner">
                        <i class="bi bi-search"></i>
                        <input type="text" id="renewalSearch" class="search-input"
                               placeholder="Cari kamar, status, durasi..."
                               oninput="filterRenewals(this.value)">
                    </div>
                </div>

                <div id="renewalList">
                @forelse($renewals as $renewal)
                @php
                    $statusMap = [
                        'pending_admin'   => ['label' => 'Menunggu Admin', 'class' => 'badge-pending-admin'],
                        'pending_payment' => ['label' => 'Perlu Dibayar',  'class' => 'badge-pending-payment'],
                        'approved'        => ['label' => 'Disetujui',      'class' => 'badge-approved'],
                        'rejected'        => ['label' => 'Ditolak',        'class' => 'badge-rejected'],
                    ];
                    $st        = $statusMap[$renewal->status] ?? ['label' => $renewal->status, 'class' => ''];
                    $canEdit   = $renewal->status === 'pending_admin';
                    $canDelete = $renewal->status === 'pending_admin';
                    $searchText = strtolower(
                        'kamar ' . ($renewal->room?->room_number ?? '') . ' ' .
                        $renewal->duration_months . ' bulan ' .
                        $renewal->status . ' ' .
                        $renewal->created_at->format('d M Y')
                    );
                @endphp
                <div class="history-item" data-search="{{ $searchText }}">
                    <div class="history-header">
                        <div>
                            <div class="history-room">Kamar {{ $renewal->room?->room_number }} · {{ $renewal->duration_months }} bln</div>
                            <div class="history-date">{{ $renewal->created_at->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="history-amount-label">Total</div>
                            <div class="history-amount">Rp {{ number_format($renewal->amount ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="history-footer">
                        <div class="history-badges">
                            <span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span>
                            @if($renewal->additional_services && count($renewal->additional_services) > 0)
                            <span class="badge badge-svc">+{{ count($renewal->additional_services) }} layanan</span>
                            @endif
                        </div>
                        <div class="hist-actions">
                            <a href="{{ $renewal->status === 'pending_payment'
                                    ? route('tenant.renewal.invoice', $renewal->id)
                                    : route('tenant.renewal.show', $renewal->id) }}"
                               class="btn-hist {{ $renewal->status === 'pending_payment' ? 'pay' : '' }}">
                                @if($renewal->status === 'pending_payment')
                                    <i class="bi bi-credit-card-fill"></i> Bayar
                                @else
                                    <i class="bi bi-eye-fill"></i> Detail
                                @endif
                            </a>
                            @if($canEdit)
                            <a href="{{ route('tenant.renewal.edit', $renewal->id) }}" class="btn-hist edit">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                            @endif
                            @if($canDelete)
                            <button type="button" class="btn-hist delete"
                                    onclick="openDeleteModal(
                                        {{ $renewal->id }},
                                        'Kamar {{ $renewal->room?->room_number }}',
                                        {{ $renewal->duration_months }},
                                        'Rp {{ number_format($renewal->amount ?? 0, 0, ',', '.') }}'
                                    )">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    @if($renewal->new_end_date)
                    <div class="history-end-date">
                        <i class="bi bi-calendar-check-fill" style="color:var(--green-500)"></i>
                        Berakhir baru: <strong>{{ \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y') }}</strong>
                    </div>
                    @endif
                    @if($renewal->status === 'rejected' && $renewal->rejection_reason)
                    <div class="history-reject-reason">
                        <i class="bi bi-x-circle-fill"></i>{{ $renewal->rejection_reason }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <h3>Belum Ada Riwayat</h3>
                    <p>Perpanjangan yang Anda ajukan akan muncul di sini.</p>
                </div>
                @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="deleteModal" role="dialog" aria-modal="true">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-danger-icon"><i class="bi bi-trash3-fill"></i></div>
            <div class="modal-title">Batalkan Pengajuan?</div>
            <div class="modal-sub">Pengajuan akan dihapus permanen dan tidak bisa dikembalikan.</div>
        </div>
        <div class="modal-info-box" id="modalInfoBox"></div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel-m" onclick="closeDeleteModal()">
                <i class="bi bi-x-lg"></i> Batal
            </button>
            <form id="deleteForm" method="POST" style="flex:1;display:flex">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger-m" id="deleteBtnSubmit" style="width:100%">
                    <i class="bi bi-trash3-fill"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const ROOM_PRICE   = {{ (int) ($activeBooking?->room?->price ?? 0) }};
let selectedMonths = parseInt(document.getElementById('renewalMonths')?.value || 1);
let serviceTotal   = 0;

@if($endDate)
const CURRENT_END_DATE = new Date({{ $endDate->timestamp * 1000 }});
@else
const CURRENT_END_DATE = null;
@endif

const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

function formatDateID(d) {
    if (!d) return '—';
    return d.getDate() + ' ' + MONTHS_ID[d.getMonth()] + ' ' + d.getFullYear();
}
function getNewEndDate(months) {
    if (!CURRENT_END_DATE) return null;
    const d = new Date(CURRENT_END_DATE);
    d.setMonth(d.getMonth() + months);
    return d;
}
function updateEndPreview() {
    const newEnd = getNewEndDate(selectedMonths);
    const valEl  = document.getElementById('newEndDateVal');
    const subEl  = document.getElementById('newEndDateSub');
    const wrap   = document.getElementById('durPreview');
    if (valEl) valEl.textContent = formatDateID(newEnd);
    if (subEl && CURRENT_END_DATE) subEl.textContent = 'Dilanjutkan dari ' + formatDateID(CURRENT_END_DATE);
    if (wrap) { wrap.classList.add('highlight'); setTimeout(() => wrap.classList.remove('highlight'), 600); }
}
function handleDurationChange(val) {
    selectedMonths = parseInt(val);
    updateCosts();
    updateEndPreview();
}
function fmt(v) { return 'Rp ' + v.toLocaleString('id-ID'); }
function el(id) { return document.getElementById(id); }
function updateCosts() {
    const monthly = ROOM_PRICE + serviceTotal;
    const grand   = monthly * selectedMonths;
    if (el('svcCostVal'))      el('svcCostVal').textContent     = fmt(serviceTotal);
    if (el('monthlyTotalVal')) el('monthlyTotalVal').textContent = fmt(monthly);
    if (el('grandTotalVal'))   el('grandTotalVal').textContent   = fmt(grand);
    if (el('durLabelVal'))     el('durLabelVal').textContent     = selectedMonths;
    if (el('svcCostRow'))      el('svcCostRow').style.display    = serviceTotal > 0 ? 'flex' : 'none';
    const method = document.querySelector('input[name="payment_method"]:checked')?.value ?? 'fullpay';
    if (el('submitBtnLabel')) {
        if (method === 'monthly') {
            el('submitBtnLabel').textContent = 'Ajukan Perpanjangan ' + selectedMonths + ' Bulan · ' + fmt(monthly) + '/bln';
        } else {
            el('submitBtnLabel').textContent = 'Ajukan Perpanjangan ' + selectedMonths + ' Bulan (' + fmt(grand) + ')';
        }
    }
}
function selectPayMethod(method) {
    ['fullpay', 'monthly'].forEach(m => {
        const lbl = document.getElementById('pay-label-' + m);
        if (lbl) lbl.classList.toggle('active', m === method);
    });
    const note = document.getElementById('monthlyNote');
    if (note) note.style.display = method === 'monthly' ? 'flex' : 'none';
    updateCosts();
}
function updateService(key, isCurrent, checked) {
    const label = document.getElementById('svc-label-' + key);
    const tag   = document.getElementById('svc-tag-' + key);
    const price = parseInt(label?.dataset.price || 0);
    if (checked) {
        serviceTotal += price;
        label.classList.add('active'); label.classList.remove('removing');
        label.style.paddingTop = '22px';
        if (isCurrent === '1') { tag.textContent = 'Aktif'; tag.className = 'svc-state-tag svc-state-current'; }
        else { tag.textContent = 'Ditambahkan'; tag.className = 'svc-state-tag svc-state-add'; tag.style.display = ''; }
    } else {
        serviceTotal -= price;
        label.classList.remove('active');
        if (isCurrent === '1') {
            label.classList.add('removing'); tag.textContent = 'Akan Dihapus';
            tag.className = 'svc-state-tag svc-state-remove'; label.style.paddingTop = '22px';
        } else { label.classList.remove('removing'); tag.style.display = 'none'; label.style.paddingTop = ''; }
    }
    updateCosts();
}
function filterRenewals(query) {
    const q = query.toLowerCase().trim();
    const items = document.querySelectorAll('#renewalList .history-item');
    let shown = 0;
    items.forEach(item => {
        const visible = !q || (item.dataset.search || '').includes(q);
        item.style.display = visible ? '' : 'none';
        if (visible) shown++;
    });
    let noResult = document.getElementById('renewalNoResult');
    if (!noResult) {
        noResult = document.createElement('div');
        noResult.id = 'renewalNoResult';
        noResult.className = 'empty-state';
        noResult.innerHTML = '<div class="empty-icon"><i class="bi bi-search"></i></div><h3>Tidak Ditemukan</h3><p>Coba kata kunci lain.</p>';
        document.getElementById('renewalList').appendChild(noResult);
    }
    noResult.style.display = (shown === 0 && q) ? '' : 'none';
}
const RENEWAL_DELETE_BASE = '{{ url("tenant/perpanjangan") }}';
function openDeleteModal(renewalId, roomLabel, months, total) {
    document.getElementById('deleteForm').action = RENEWAL_DELETE_BASE + '/' + renewalId;
    document.getElementById('modalInfoBox').innerHTML =
        '<strong>Kamar:</strong> ' + roomLabel + '<br>' +
        '<strong>Durasi:</strong> ' + months + ' bulan<br>' +
        '<strong>Total:</strong> ' + total + '<br>' +
        '<strong>Status:</strong> Menunggu Admin';
    document.getElementById('deleteModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('deleteModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteModal(); });
document.getElementById('deleteForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('deleteBtnSubmit');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menghapus...'; }
});
document.addEventListener('DOMContentLoaded', () => {
    serviceTotal = 0;
    document.querySelectorAll('.svc-checkbox:checked').forEach(cb => {
        serviceTotal += parseInt(cb.closest('.svc-item')?.dataset.price || 0);
    });
    updateCosts();
    updateEndPreview();
});
</script>
</body>
</html>