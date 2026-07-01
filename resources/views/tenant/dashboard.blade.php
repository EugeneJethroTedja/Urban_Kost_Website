@php
    $unread        = auth()->user()->unreadNotifications->count();
    $userName      = auth()->user()->name ?? 'Pengguna';
    $userInitial   = strtoupper(substr($userName, 0, 1));
    $unpaidInv     = \App\Models\Invoice::where('user_id', auth()->id())->whereIn('status', ['unpaid','overdue'])->count();
    $activeBooking = \App\Models\Booking::where('user_id', auth()->id())->whereIn('status', ['approved','active'])->latest()->first();
    $activeRenewal = $activeBooking
        ? \App\Models\Renewal::where('user_id', auth()->id())->whereIn('status', ['pending_payment','pending_admin'])->latest()->first()
        : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Penghuni — Urban Kost</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-50:      #fff5f5;
    --red-100:     #fee2e2;
    --red-500:     #ef4444;
    --red-600:     #dc2626;
    --red-700:     #b91c1c;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-50:    #fffbeb;
    --amber-100:   #fef3c7;
    --amber-500:   #f59e0b;
    --amber-600:   #d97706;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-50:     #eff6ff;
    --blue-100:    #dbeafe;
    --blue-500:    #3b82f6;
    --blue-600:    #2563eb;
    --nav-h:       64px;
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
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
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
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%),
        radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
}
.tenant-bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}

.page-wrap {
    width: 100%;
    max-width: 1140px;
    margin: 0 auto;
    padding: 20px 16px 56px 16px;
}
@media (min-width: 640px)  { .page-wrap { padding: 28px 24px 64px 24px; } }
@media (min-width: 900px)  { .page-wrap { padding: 36px 32px 64px 32px; } }
@media (min-width: 1280px) { .page-wrap { padding: 40px 40px 64px 40px; } }

.alert-strip {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 10px;
    margin-bottom: 10px; font-size: .82rem; font-weight: 500;
    border: 1px solid; line-height: 1.55; flex-wrap: wrap;
}
.alert-strip.warn   { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
.alert-strip.danger { background: var(--red-pale);   border-color: rgba(220,38,38,.3);  color: var(--red-700); }
.alert-strip.info   { background: var(--green-50);   border-color: var(--green-200);    color: var(--green-800); }
.alert-strip > i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }
.alert-strip > span { flex: 1; min-width: 0; }
.alert-strip-action { flex-shrink: 0; align-self: center; }
@media (max-width: 480px) {
    .alert-strip { font-size: .78rem; padding: 10px 12px; }
    .alert-strip-action { width: 100%; margin-top: 6px; }
}
.btn-alert {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 8px;
    font-weight: 600; font-size: .76rem; text-decoration: none; transition: all .2s;
}
.btn-alert.warn   { background: var(--amber-500); color: #fff; }
.btn-alert.warn:hover   { background: var(--amber-600); }
.btn-alert.danger { background: var(--red-600); color: #fff; }
.btn-alert.danger:hover { background: var(--red-700); }

.hero-banner {
    position: relative; overflow: hidden; border-radius: 16px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 26px 20px; margin-bottom: 18px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
}
@media (min-width: 640px) { .hero-banner { padding: 32px 28px; border-radius: 20px; margin-bottom: 22px; } }
@media (min-width: 900px) { .hero-banner { padding: 36px 32px; margin-bottom: 28px; } }
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
    position: absolute; right: -100px; top: -100px;
    width: 380px; height: 380px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1); pointer-events: none; z-index: 1;
    display: none;
}
@media (min-width: 640px) { .hero-arc { display: block; } }
.hero-content { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: var(--r-pill);
    font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82); margin-bottom: 12px;
}
.hero-eyebrow .dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--green-300); flex-shrink: 0;
}
.hero-title {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(1.3rem, 6vw, 2.2rem);
    color: #fff; line-height: 1.15;
    letter-spacing: -.025em; margin-bottom: 8px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .85rem; color: rgba(255,255,255,.62); font-weight: 400; max-width: 400px; line-height: 1.6; }
.hero-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
    border-radius: var(--r-pill); padding: 5px 13px;
    font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.9);
}
.hero-stats {
    display: flex; gap: 20px; flex-wrap: wrap; margin-top: 18px;
    padding-top: 16px; border-top: 1px solid rgba(255,255,255,.12);
}
@media (min-width: 640px) { .hero-stats { gap: 28px; } }
.hero-stat-num {
    font-family: 'Fraunces', serif;
    font-size: 1.5rem; font-weight: 700; color: #fff; line-height: 1;
}
@media (min-width: 640px) { .hero-stat-num { font-size: 1.7rem; } }
.hero-stat-lbl {
    font-size: .67rem; color: rgba(255,255,255,.55);
    font-weight: 500; margin-top: 3px;
    text-transform: uppercase; letter-spacing: .07em;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px; margin-bottom: 18px;
}
@media (min-width: 640px)  { .stats-grid { gap: 16px; margin-bottom: 22px; } }
@media (min-width: 900px)  { .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; } }
@media (max-width: 639px) {
    .stat-card.c-blue { grid-column: span 2; }
}

.stat-card {
    position: relative; overflow: hidden; border-radius: var(--r-card);
    padding: 16px 14px; background: var(--neutral-0);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    transition: border-color .25s, box-shadow .3s, transform .25s; cursor: default;
}
@media (min-width: 640px) { .stat-card { padding: 18px 16px; } }
@media (min-width: 900px) { .stat-card { padding: 20px 18px; } }
.stat-card:hover { border-color: var(--green-300); box-shadow: var(--shadow-lg); transform: translateY(-3px); }
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: var(--r-card) var(--r-card) 0 0;
}
.stat-card.c-green::before { background: linear-gradient(90deg, var(--green-400), var(--green-500)); }
.stat-card.c-red::before   { background: linear-gradient(90deg, #f87171, var(--red-600)); }
.stat-card.c-amber::before { background: linear-gradient(90deg, #fcd34d, var(--amber-500)); }
.stat-card.c-blue::before  { background: linear-gradient(90deg, #60a5fa, var(--blue-600)); }
.stat-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; margin-bottom: 12px; flex-shrink: 0;
}
@media (min-width: 640px) { .stat-icon-wrap { width: 42px; height: 42px; font-size: 1rem; margin-bottom: 14px; } }
.stat-value {
    font-family: 'Fraunces', serif;
    font-size: 1.7rem; font-weight: 700;
    color: var(--neutral-900); line-height: 1;
    margin-bottom: 4px; letter-spacing: -.025em;
}
@media (min-width: 640px) { .stat-value { font-size: 2rem; } }
.stat-label {
    font-size: .65rem; font-weight: 600;
    color: var(--neutral-400); text-transform: uppercase; letter-spacing: .07em;
    line-height: 1.4;
}
.stat-action {
    display: inline-flex; align-items: center; gap: 4px;
    margin-top: 10px; padding: 5px 12px; border-radius: 7px;
    font-size: .72rem; font-weight: 600; text-decoration: none;
    border: 1px solid; transition: all .2s; width: fit-content;
}
.stat-action.green { color: var(--green-600); border-color: var(--green-200); background: var(--green-50); }
.stat-action.green:hover { background: var(--green-100); }
.stat-action.red   { color: var(--red-600);   border-color: var(--red-100);   background: var(--red-50); }
.stat-action.red:hover   { background: var(--red-100); }
.stat-action.amber { color: var(--amber-600); border-color: var(--amber-100); background: var(--amber-50); }
.stat-action.amber:hover { background: var(--amber-100); }
.stat-action.blue  { color: var(--blue-600);  border-color: var(--blue-100);  background: var(--blue-50); }
.stat-action.blue:hover  { background: var(--blue-100); }

.main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
}
@media (min-width: 1100px) {
    .main-grid { grid-template-columns: 1fr 280px; gap: 20px; }
}

.section-hd {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 12px; flex-wrap: wrap; gap: 8px;
}
.section-hd h2 {
    font-family: 'Fraunces', serif;
    font-size: 1rem; font-weight: 700;
    color: var(--neutral-900); letter-spacing: -.02em;
    display: flex; align-items: center; gap: 8px;
}
@media (min-width: 640px) { .section-hd h2 { font-size: 1.1rem; } }
.section-hd h2 i { color: var(--green-500); font-size: .9rem; }
.section-hd a {
    font-size: .73rem; font-weight: 600; color: var(--green-600);
    text-decoration: none; display: flex; align-items: center; gap: 4px;
    padding: 6px 13px; border-radius: 8px;
    border: 1px solid var(--green-200); background: var(--green-50); transition: all .2s;
    white-space: nowrap;
}
.section-hd a:hover { background: var(--green-100); border-color: var(--green-300); }

.o-card {
    background: var(--neutral-0); border-radius: var(--r-card);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md); overflow: hidden;
}
.o-card-header {
    padding: 12px 16px; border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
@media (min-width: 640px) { .o-card-header { padding: 14px 20px; } }
.o-card-header-title {
    font-size: .7rem; font-weight: 600; color: var(--neutral-600);
    display: flex; align-items: center; gap: 7px;
    text-transform: uppercase; letter-spacing: .07em;
}
.o-card-header-title i { color: var(--green-500); }

.table-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
}
.btable {
    width: 100%; border-collapse: collapse;
    min-width: 460px;
}
.btable th {
    padding: 9px 12px; font-size: .62rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .07em;
    color: var(--neutral-400); background: var(--neutral-50);
    border-bottom: 1px solid var(--neutral-100); text-align: left;
    white-space: nowrap;
}
.btable td {
    padding: 12px; border-bottom: 1px solid var(--neutral-100);
    font-size: .8rem; color: var(--neutral-600); vertical-align: middle;
}
@media (min-width: 640px) {
    .btable th { padding: 10px 14px; font-size: .63rem; }
    .btable td { padding: 13px 14px; }
}
.btable tr:last-child td { border-bottom: none; }
.btable tr:hover td { background: var(--green-50); transition: background .15s; }
.brow { display: flex; flex-direction: column; gap: 2px; }
.brow-code {
    font-weight: 700; font-size: .75rem; color: var(--green-700);
    background: var(--green-50); border: 1px solid var(--green-200);
    padding: 2px 8px; border-radius: 5px; display: inline-block; margin-bottom: 2px;
}
.brow-advance {
    font-size: .68rem; color: var(--accent); font-weight: 600;
    display: inline-flex; align-items: center; gap: 3px;
}
.broom-num   { font-weight: 700; color: var(--neutral-900); font-size: .82rem; }
.broom-name  { font-size: .71rem; color: var(--neutral-400); margin-top: 1px; }
.broom-price { font-size: .72rem; color: var(--green-600); font-weight: 600; margin-top: 2px; }
.bdate-main  { font-size: .8rem; font-weight: 600; color: var(--neutral-700); }
.bdate-end   { font-size: .71rem; color: var(--neutral-400); margin-top: 1px; }
.bdur-chip {
    margin-top: 4px; display: inline-flex; align-items: center; gap: 4px;
    background: var(--neutral-50); border: 1px solid var(--neutral-200);
    border-radius: 5px; padding: 2px 8px;
    font-size: .71rem; font-weight: 600; color: var(--neutral-600);
}
.bdur-chip i { font-size: .65rem; }

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: var(--r-pill);
    font-size: .69rem; font-weight: 600; white-space: nowrap; line-height: 1.3; border: 1px solid;
}
.badge i { font-size: .65rem; }
.badge-penghuni  { background: var(--green-50);    color: var(--green-700);  border-color: var(--green-200); }
.badge-calon     { background: var(--blue-50);     color: var(--blue-600);   border-color: var(--blue-100); }
.badge-nonaktif  { background: var(--neutral-100); color: var(--neutral-500); border-color: var(--neutral-200); }
.badge-pending   { background: var(--amber-50);    color: var(--amber-600);  border-color: var(--amber-100); }
.badge-rejected  { background: var(--red-50);      color: var(--red-700);    border-color: var(--red-100); }
.badge-approved  { background: var(--blue-50);     color: var(--blue-600);   border-color: var(--blue-100); }
.badge-secondary { background: var(--neutral-100); color: var(--neutral-600); border-color: var(--neutral-200); }
.badge-success   { background: var(--green-50);    color: var(--green-700);  border-color: var(--green-200); }
.badge-danger    { background: var(--red-50);      color: var(--red-700);    border-color: var(--red-100); }
.badge-warning   { background: var(--amber-50);    color: var(--amber-600);  border-color: var(--amber-100); }
.badge-info      { background: var(--blue-50);     color: var(--blue-600);   border-color: var(--blue-100); }

.empty-state { text-align: center; padding: 48px 24px; }
.empty-icon {
    width: 52px; height: 52px; border-radius: 50%;
    background: var(--green-50); border: 2px dashed var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: var(--green-300); margin: 0 auto 12px;
}
.empty-state h3 {
    font-family: 'Fraunces', serif;
    font-size: .92rem; font-weight: 700; color: var(--neutral-500); margin-bottom: 5px;
}
.empty-state p { font-size: .78rem; color: var(--neutral-400); }

.sidebar-stack { display: flex; flex-direction: column; gap: 14px; }

@media (min-width: 640px) and (max-width: 1099px) {
    .sidebar-stack {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        align-items: start;
    }
}

.profile-body { padding: 16px; }
@media (min-width: 640px) { .profile-body { padding: 18px 20px; } }
.profile-top { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.profile-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, var(--green-600), var(--green-400));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.1rem; font-weight: 700; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(42,179,107,.3);
}
.profile-name  { font-family: 'Fraunces', serif; font-weight: 700; font-size: .9rem; color: var(--neutral-900); }
.profile-email {
    font-size: .72rem; color: var(--neutral-500); margin-top: 2px;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%;
}
.profile-role {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--green-50); color: var(--green-700);
    font-size: .67rem; font-weight: 600; padding: 3px 9px;
    border-radius: var(--r-pill); margin-top: 6px; border: 1px solid var(--green-200);
}
.profile-rows { border-top: 1px solid var(--neutral-100); padding-top: 2px; }
.profile-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid var(--neutral-50);
}
.profile-row:last-child { border-bottom: none; }
.profile-key { font-size: .74rem; color: var(--neutral-500); display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
.profile-key i { color: var(--green-400); font-size: .8rem; }
.profile-val {
    font-weight: 600; font-size: .75rem; color: var(--neutral-900);
    text-align: right; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    max-width: 55%; margin-left: 8px;
}

.quick-body { padding: 10px; display: flex; flex-direction: column; gap: 7px; }
@media (min-width: 640px) { .quick-body { padding: 12px; } }
.quick-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 14px; border-radius: 10px;
    text-decoration: none; font-size: .8rem; font-weight: 600;
    border: 1px solid; transition: all .2s; cursor: pointer;
    width: 100%;
}
.quick-btn i { font-size: 1rem; flex-shrink: 0; }
.quick-btn-label { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.quick-btn-badge {
    background: var(--red-600); color: #fff;
    font-size: .6rem; padding: 2px 7px; border-radius: var(--r-pill); font-weight: 700;
    flex-shrink: 0;
}
.quick-btn.danger  { color: var(--red-600);   border-color: var(--red-100);    background: var(--red-50); }
.quick-btn.danger:hover  { background: var(--red-100); border-color: var(--red-500); }
.quick-btn.outline { color: var(--neutral-600); border-color: var(--neutral-200); background: #fff; }
.quick-btn.outline:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-700); }
.quick-btn.info    { color: var(--blue-600);   border-color: var(--blue-100);   background: var(--blue-50); }
.quick-btn.info:hover    { background: var(--blue-100); }

.table-footer-actions {
    padding: 14px 16px; border-top: 1px solid var(--neutral-100);
    display: flex; gap: 10px; flex-wrap: wrap;
}
@media (min-width: 640px) { .table-footer-actions { padding: 15px 20px; } }
.btn-primary-sm {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 9px;
    font-size: .8rem; font-weight: 600;
    background: var(--green-500); color: #fff;
    text-decoration: none; box-shadow: 0 2px 10px rgba(42,179,107,.28);
    transition: all .2s; border: none; white-space: nowrap;
}
.btn-primary-sm:hover { background: var(--green-600); color: #fff; transform: translateY(-1px); }
.btn-ghost-sm {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 9px;
    font-size: .8rem; font-weight: 600;
    border: 1px solid var(--green-200); color: var(--green-700);
    background: var(--green-50); text-decoration: none; transition: all .2s;
    white-space: nowrap;
}
.btn-ghost-sm:hover { background: var(--green-100); border-color: var(--green-300); }
@media (max-width: 380px) {
    .btn-primary-sm, .btn-ghost-sm { padding: 8px 13px; font-size: .75rem; }
    .table-footer-actions { gap: 8px; }
}
</style>
</head>
<body>

<div class="tenant-bg"><div class="tenant-bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-wrap">

    @if(isset($renewalInquiry) && $renewalInquiry)
    <div class="alert-strip info">
        <i class="bi bi-bell-fill"></i>
        <span>
            Ada calon penghuni yang ingin menempati kamar <strong>{{ $renewalInquiry->room?->room_number }}</strong>
            mulai <strong>{{ $renewalInquiry->check_in_date?->format('d F Y') }}</strong>.
            Apakah Anda akan memperpanjang sewa?
        </span>
    </div>
    @endif

    @if(isset($expiringBooking) && $expiringBooking)
    <div class="alert-strip {{ $daysUntilExpiry <= 3 ? 'danger' : 'warn' }}">
        <i class="bi bi-{{ $daysUntilExpiry <= 3 ? 'exclamation-triangle-fill' : 'clock-history' }}"></i>
        <span>
            Masa sewa kamar <strong>{{ $expiringBooking->room?->room_number }}</strong> berakhir pada
            <strong>{{ \Carbon\Carbon::parse($expiringBooking->end_date)->format('d M Y') }}</strong>
            &mdash; {{ $daysUntilExpiry === 0 ? 'hari ini' : $daysUntilExpiry . ' hari lagi' }}.
            Segera ajukan perpanjangan agar tidak kehilangan kamar Anda.
        </span>
        <div class="alert-strip-action">
            <a href="{{ route('tenant.renewal.index') }}" class="btn-alert {{ $daysUntilExpiry <= 3 ? 'danger' : 'warn' }}">
                <i class="bi bi-arrow-repeat"></i> Perpanjang
            </a>
        </div>
    </div>
    @endif

    @if(isset($latestUnpaidInvoice) && $latestUnpaidInvoice)
    <div class="alert-strip {{ $latestUnpaidInvoice->status === 'overdue' ? 'danger' : 'warn' }}">
        <i class="bi bi-receipt-cutoff"></i>
        <span>
            {{ $latestUnpaidInvoice->status === 'overdue' ? 'Tagihan melewati jatuh tempo' : 'Tagihan menunggu pembayaran' }} &mdash;
            {{ $latestUnpaidInvoice->invoice_number }} &middot; {{ $latestUnpaidInvoice->formatted_total }}
            &middot; Jatuh tempo: {{ $latestUnpaidInvoice->due_date?->format('d M Y') }}
        </span>
        <div class="alert-strip-action">
            <a href="{{ route('tenant.invoices.show', $latestUnpaidInvoice->id) }}" class="btn-alert danger">
                <i class="bi bi-credit-card"></i> Bayar
            </a>
        </div>
    </div>
    @endif

    <div class="hero-banner">
        <div class="hero-banner-dots"></div>
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Dashboard Penghuni
            </div>
            <h1 class="hero-title">
                Selamat Datang,<br><em>{{ $user->name }}</em>
            </h1>
            <p class="hero-sub">Pantau status hunian dan kelola kebutuhan Anda.</p>
            <div class="hero-badges">
                <div class="hero-badge">Penghuni Aktif</div>
                @if($activeBooking)
                <div class="hero-badge">Kamar {{ $activeBooking->room?->room_number }}</div>
                @endif
            </div>
            @if($activeBooking)
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ $invoiceStats['unpaid'] ?? 0 }}</div>
                    <div class="hero-stat-lbl">Tagihan Aktif</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ $ticketStats['open'] ?? 0 }}</div>
                    <div class="hero-stat-lbl">Tiket Terbuka</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ $ticketStats['resolved'] ?? 0 }}</div>
                    <div class="hero-stat-lbl">Tiket Selesai</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card {{ ($invoiceStats['unpaid'] ?? 0) > 0 ? 'c-red' : 'c-green' }}">
            <div class="stat-icon-wrap" style="background:{{ ($invoiceStats['unpaid'] ?? 0) > 0 ? 'var(--red-50)' : 'var(--green-100)' }};color:{{ ($invoiceStats['unpaid'] ?? 0) > 0 ? 'var(--red-600)' : 'var(--green-600)' }}">
                <i class="bi bi-receipt{{ ($invoiceStats['unpaid'] ?? 0) > 0 ? '-cutoff' : '' }}"></i>
            </div>
            <div class="stat-value" style="color:{{ ($invoiceStats['unpaid'] ?? 0) > 0 ? 'var(--red-600)' : 'var(--green-600)' }}">
                {{ $invoiceStats['unpaid'] ?? 0 }}
            </div>
            <div class="stat-label">Tagihan Belum Dibayar</div>
            <a href="{{ route('tenant.invoices.index') }}" class="stat-action {{ ($invoiceStats['unpaid'] ?? 0) > 0 ? 'red' : 'green' }}">
                <i class="bi bi-arrow-right-short"></i> Lihat Tagihan
            </a>
        </div>

        <div class="stat-card {{ ($ticketStats['open'] ?? 0) > 0 ? 'c-red' : 'c-green' }}">
            <div class="stat-icon-wrap" style="background:{{ ($ticketStats['open'] ?? 0) > 0 ? 'var(--red-50)' : 'var(--green-100)' }};color:{{ ($ticketStats['open'] ?? 0) > 0 ? 'var(--red-600)' : 'var(--green-600)' }}">
                <i class="bi bi-headset"></i>
            </div>
            <div class="stat-value" style="color:{{ ($ticketStats['open'] ?? 0) > 0 ? 'var(--red-600)' : 'var(--green-600)' }}">
                {{ $ticketStats['open'] ?? 0 }}
            </div>
            <div class="stat-label">Tiket Aktif</div>
            <a href="{{ route('tenant.tickets.index') }}" class="stat-action {{ ($ticketStats['open'] ?? 0) > 0 ? 'red' : 'green' }}">
                <i class="bi bi-arrow-right-short"></i> Lihat Tiket
            </a>
        </div>

        <div class="stat-card c-blue">
            <div class="stat-icon-wrap" style="background:var(--blue-50);color:var(--blue-600)">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value" style="color:var(--blue-600)">{{ $ticketStats['resolved'] ?? 0 }}</div>
            <div class="stat-label">Tiket Selesai</div>
            <a href="{{ route('tenant.tickets.create') }}" class="stat-action blue">
                <i class="bi bi-plus"></i> Buat Tiket
            </a>
        </div>
    </div>

    <div class="main-grid">

        <div style="min-width:0">
            <div class="section-hd">
                <h2><i class="bi bi-calendar-check-fill"></i>Riwayat Booking</h2>
                <a href="{{ route('tenant.bookings') }}">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="o-card">
                <div class="table-scroll">
                    @php
                        $bookingIds = collect($bookings)->pluck('id')->toArray();
                        $paidInvoicesByBooking = \App\Models\Invoice::where('user_id', auth()->id())
                            ->whereIn('booking_id', $bookingIds)
                            ->where('status', 'paid')
                            ->orderBy('period_end', 'desc')
                            ->get()
                            ->groupBy('booking_id');
                    @endphp
                    <table class="btable">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Kamar</th>
                                <th>Tanggal Masuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                            @php
                                $latestPaidInvoice = isset($paidInvoicesByBooking[$b->id]) ? $paidInvoicesByBooking[$b->id]->first() : null;
                                if ($latestPaidInvoice && $latestPaidInvoice->period_end) {
                                    $tenantStatus = $latestPaidInvoice->period_end->gte(now()) ? 'penghuni' : 'nonaktif';
                                } elseif (in_array($b->status, ['rejected', 'cancelled'])) {
                                    $tenantStatus = 'nonaktif';
                                } elseif ($b->status === 'pending') {
                                    $tenantStatus = 'pending';
                                } else {
                                    $tenantStatus = 'calon';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="brow">
                                        <span class="brow-code">{{ $b->booking_code }}</span>
                                        @if($b->isAdvance())
                                            <span class="brow-advance">Advance</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="brow">
                                        <div class="broom-num">{{ $b->room?->room_number }}</div>
                                        <div class="broom-name">{{ $b->room?->room_name }}</div>
                                        <div class="broom-price">{{ $b->room?->formatted_price }}/bln</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="brow">
                                        <div class="bdate-main">{{ $b->check_in_date?->format('d M Y') }}</div>
                                        @if($latestPaidInvoice && $latestPaidInvoice->period_end)
                                            <div class="bdate-end">s/d {{ $latestPaidInvoice->period_end->format('d M Y') }}</div>
                                        @endif
                                        <span class="bdur-chip">
                                            <i class="bi bi-clock"></i> {{ $b->duration_months }} bulan
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="brow">
                                        @if($tenantStatus === 'penghuni')
                                            <span class="badge badge-penghuni">Penghuni</span>
                                        @elseif($tenantStatus === 'nonaktif')
                                            <span class="badge badge-nonaktif">Nonaktif</span>
                                        @elseif($tenantStatus === 'pending')
                                            <span class="badge badge-pending">Menunggu</span>
                                        @else
                                            <span class="badge badge-calon">Calon Penghuni</span>
                                        @endif
                                        <div style="margin-top:5px">
                                            @if($b->status === 'active')
                                                <span class="badge badge-penghuni"><i class="bi bi-check-circle-fill"></i> Diterima</span>
                                            @elseif(in_array($b->status, ['completed','done']))
                                                <span class="badge badge-secondary"><i class="bi bi-archive-fill"></i> Selesai</span>
                                            @else
                                                {!! $b->status_badge !!}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                                        <h3>Belum ada riwayat booking</h3>
                                        <p>Riwayat booking Anda akan muncul di sini setelah melakukan pemesanan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer-actions">
                    <a href="{{ route('tenant.tickets.create') }}" class="btn-primary-sm">
                        <i class="bi bi-plus-circle-fill"></i>Buat Tiket Keluhan
                    </a>
                    <a href="{{ route('tenant.bookings') }}" class="btn-ghost-sm">
                        <i class="bi bi-list-ul"></i>Semua Booking
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-stack" style="min-width:0">

            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-person-circle"></i>Profil Saya
                    </span>
                    <a href="{{ route('profile.show') }}"
                       style="font-size:.7rem;font-weight:600;color:var(--green-600);text-decoration:none;display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:7px;border:1px solid var(--green-200);background:var(--green-50);transition:all .2s;flex-shrink:0"
                       onmouseover="this.style.background='var(--green-100)'"
                       onmouseout="this.style.background='var(--green-50)'">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </a>
                </div>
                <div class="profile-body">
                    <div class="profile-top">
                        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div style="min-width:0;flex:1">
                            <div class="profile-name">{{ $user->name }}</div>
                            <div class="profile-email">{{ $user->email }}</div>
                            <div class="profile-role">{{ $user->role?->display_name }}</div>
                        </div>
                    </div>
                    <div class="profile-rows">
                        <div class="profile-row">
                            <span class="profile-key"><i class="bi bi-telephone-fill"></i> No. HP</span>
                            <span class="profile-val">{{ $user->phone ?? '-' }}</span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-key"><i class="bi bi-calendar-fill"></i> Bergabung</span>
                            <span class="profile-val">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <span class="o-card-header-title">
                        <i class="bi bi-grid-fill"></i>Aksi Cepat
                    </span>
                </div>
                <div class="quick-body">
                    <a href="{{ route('tenant.invoices.index') }}"
                       class="quick-btn {{ ($invoiceStats['unpaid'] ?? 0) > 0 ? 'danger' : 'outline' }}">
                        <i class="bi bi-receipt"></i>
                        <span class="quick-btn-label">
                            {{ ($invoiceStats['unpaid'] ?? 0) > 0
                                ? $invoiceStats['unpaid'].' Tagihan Belum Dibayar'
                                : 'Lihat Tagihan' }}
                        </span>
                        @if(($invoiceStats['unpaid'] ?? 0) > 0)
                            <span class="quick-btn-badge">!</span>
                        @endif
                    </a>
                    <a href="{{ route('tenant.tickets.create') }}" class="quick-btn outline">
                        <i class="bi bi-plus-circle"></i>
                        <span class="quick-btn-label">Buat Tiket Keluhan</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="quick-btn info">
                        <i class="bi bi-bell"></i>
                        <span class="quick-btn-label">Notifikasi</span>
                        @if($unread > 0)
                            <span class="quick-btn-badge">{{ $unread }}</span>
                        @endif
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>