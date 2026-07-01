<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        --blue-line:   #bfdbfe;
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --red-line:    #fecaca;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --amber-line:  #fde68a;
        --purple:      #7c3aed;
        --purple-pale: #f5f3ff;
        --purple-line: #ddd6fe;
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

    .dash-page { max-width: 1180px; margin: 0 auto; padding: 28px 20px 72px; }

    .flash-msg {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 16px; border-radius: 10px; border: 1px solid;
        font-size: .82rem; margin-bottom: 10px;
        font-family: 'Instrument Sans', sans-serif;
    }
    .flash-success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
    .flash-error   { background: var(--red-pale); border-color: var(--red-line); color: #7f1d1d; }
    .flash-warning { background: var(--amber-pale); border-color: var(--amber-line); color: #78350f; }
    .flash-info    { background: var(--blue-pale); border-color: var(--blue-line); color: #1e3a5f; }

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
    .hero-banner-dots {
        position: absolute; inset: 0; opacity: .03;
        background-image: radial-gradient(circle, #fff 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
    }
    .hero-arc {
        position: absolute; right: -100px; top: -100px;
        width: 380px; height: 380px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.1);
        pointer-events: none; z-index: 1;
    }
    .hero-content { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; flex-wrap: wrap; }
    .hero-left { flex: 1; min-width: 0; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        padding: 5px 14px; border-radius: var(--r-pill);
        font-size: .68rem; font-weight: 600;
        letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82);
        margin-bottom: 14px;
    }
    .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-greeting { font-size: .82rem; color: rgba(255,255,255,.42); font-weight: 500; margin-bottom: 4px; }
    .hero-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: clamp(1.5rem, 4vw, 2.1rem);
        color: #fff;
        line-height: 1.15;
        letter-spacing: -.025em;
        margin-bottom: 16px;
    }
    .hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
    .hero-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .hero-chip {
        display: flex; align-items: center; gap: 6px;
        font-size: .73rem; font-weight: 500; color: rgba(255,255,255,.68);
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
        padding: 5px 12px; border-radius: var(--r-pill);
    }
    .hero-chip i { color: var(--green-400); font-size: .78rem; }
    .hero-chip.chip-alert-green { background: rgba(78,201,138,.2); border-color: rgba(78,201,138,.35); color: #b8eacf; }
    .hero-chip.chip-alert-red { background: rgba(220,38,38,.15); border-color: rgba(220,38,38,.3); color: #fca5a5; }
    .hero-right { display: flex; flex-direction: column; gap: 8px; align-items: flex-end; flex-shrink: 0; }
    .hero-stat {
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
        border-radius: 12px; padding: 12px 18px; text-align: center; min-width: 100px;
    }
    .hero-stat-num {
        font-family: 'Fraunces', serif; font-size: 1.5rem; font-weight: 700; color: #fff;
        line-height: 1; display: block;
    }
    .hero-stat-lbl {
        font-size: .62rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase;
        color: rgba(255,255,255,.4); margin-top: 4px; display: block;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }
    @media (min-width: 900px) { .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 14px; } }

    .stat-card {
        position: relative; overflow: hidden;
        border-radius: var(--r-card);
        padding: 18px;
        background: var(--neutral-0);
        border: 1px solid var(--neutral-200);
        box-shadow: var(--shadow-sm);
        display: flex; flex-direction: column; gap: 12px;
        transition: transform .22s, box-shadow .22s, border-color .22s;
        cursor: default;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        border-radius: var(--r-card) var(--r-card) 0 0;
    }
    .sc-green::before  { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
    .sc-amber::before  { background: linear-gradient(90deg, var(--amber), #fbbf24); }
    .sc-red::before    { background: linear-gradient(90deg, var(--red), #ef4444); }
    .sc-blue::before   { background: linear-gradient(90deg, var(--blue), #3b82f6); }
    .sc-teal::before   { background: linear-gradient(90deg, var(--green-600), var(--green-400)); }
    .sc-purple::before { background: linear-gradient(90deg, var(--purple), #a78bfa); }
    .stat-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .stat-icon-wrap {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
    }
    .sc-green .stat-icon-wrap  { background: var(--green-100); color: var(--green-600); }
    .sc-amber .stat-icon-wrap  { background: var(--amber-pale); color: var(--amber); }
    .sc-red   .stat-icon-wrap  { background: var(--red-pale); color: var(--red); }
    .sc-blue  .stat-icon-wrap  { background: var(--blue-pale); color: var(--blue); }
    .sc-teal  .stat-icon-wrap  { background: var(--green-100); color: var(--green-600); }
    .sc-purple .stat-icon-wrap { background: var(--purple-pale); color: var(--purple); }
    .stat-trend {
        font-size: .66rem; font-weight: 700; padding: 2px 7px;
        border-radius: var(--r-pill); flex-shrink: 0;
    }
    .trend-up   { background: var(--green-100); color: var(--green-600); }
    .trend-warn { background: var(--amber-pale); color: #92400e; }
    .trend-bad  { background: var(--red-pale); color: var(--red); }
    .trend-ok   { background: var(--green-50); color: var(--green-600); }
    .trend-neu  { background: var(--neutral-100); color: var(--neutral-500); }
    .stat-label {
        font-size: .63rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        color: var(--neutral-400); margin-bottom: 4px;
    }
    .stat-value {
        font-family: 'Fraunces', serif; font-size: 1.8rem; color: var(--neutral-900);
        line-height: 1; font-weight: 700; letter-spacing: -.02em;
    }
    .stat-hint { font-size: .68rem; color: var(--neutral-400); margin-top: 2px; line-height: 1.4; }
    .stat-hint.red   { color: var(--red); font-weight: 700; }
    .stat-hint.green { color: var(--green-600); font-weight: 700; }
    .stat-hint.amber { color: var(--amber); font-weight: 700; }

    .dash-grid { display: grid; grid-template-columns: 1fr 320px; gap: 22px; }
    @media (max-width: 1100px) { .dash-grid { grid-template-columns: 1fr; } }

    .sec-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .sec-title { display: flex; align-items: center; gap: 8px; font-size: .93rem; font-weight: 700; color: var(--neutral-900); }
    .sec-title-icon {
        width: 28px; height: 28px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: .78rem; flex-shrink: 0;
    }
    .sti-green  { background: var(--green-100); color: var(--green-600); }
    .sti-amber  { background: var(--amber-pale); color: var(--amber); }
    .sti-red    { background: var(--red-pale); color: var(--red); }
    .sti-purple { background: var(--purple-pale); color: var(--purple); }
    .sti-blue   { background: var(--blue-pale); color: var(--blue); }
    .sec-tag {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .62rem; font-weight: 800; padding: 2px 8px;
        border-radius: var(--r-pill);
    }
    .sec-tag-red   { background: var(--red); color: #fff; }
    .sec-tag-amber { background: var(--amber); color: #fff; }
    .sec-tag-green { background: var(--green-500); color: #fff; }
    .sec-link {
        font-size: .75rem; font-weight: 700; color: var(--green-600);
        text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
        transition: gap .18s;
    }
    .sec-link:hover { gap: 7px; color: var(--green-700); }

    .o-card {
        background: var(--neutral-0);
        border-radius: var(--r-card);
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        transition: box-shadow .22s;
    }
    .o-card:hover { box-shadow: var(--shadow-md); }

    .inv-card {
        background: var(--neutral-0); border-radius: var(--r-card);
        border: 1px solid var(--neutral-200); box-shadow: var(--shadow-sm);
        overflow: hidden; margin-bottom: 10px;
        transition: box-shadow .22s, transform .22s;
        border-left-width: 4px;
    }
    .inv-card:hover { box-shadow: var(--shadow-md); transform: translateX(2px); }
    .inv-card.ov  { border-left-color: var(--red); }
    .inv-card.unp { border-left-color: var(--amber); }
    .inv-card.pv  { border-left-color: var(--blue); }
    .inv-inner { padding: 16px; }
    .inv-row   { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
    .inv-num   { font-family: monospace; font-size: .75rem; font-weight: 700; color: var(--neutral-900); letter-spacing: .02em; }
    .inv-room-period { font-size: .7rem; color: var(--neutral-400); margin-top: 3px; }
    .inv-due {
        font-size: .72rem; margin-top: 6px; display: flex; align-items: center; gap: 5px;
    }
    .inv-due.ov   { color: var(--red); font-weight: 700; }
    .inv-due.warn { color: var(--amber); font-weight: 700; }
    .inv-due.norm { color: var(--neutral-500); }
    .inv-due.pv   { color: var(--blue); font-weight: 600; }
    .inv-amount {
        font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 700;
        line-height: 1; text-align: right;
    }
    .inv-amount.ov  { color: var(--red); }
    .inv-amount.unp { color: var(--amber); }
    .inv-amount.pv  { color: var(--blue); }
    .inv-pill {
        display: inline-block; font-size: .6rem; font-weight: 800;
        padding: 2px 8px; border-radius: 4px; margin-top: 4px; letter-spacing: .04em;
    }
    .ip-ov  { background: var(--red-pale); color: var(--red); }
    .ip-unp { background: var(--amber-pale); color: #92400e; }
    .ip-pv  { background: var(--blue-pale); color: var(--blue); }
    .inv-actions { display: flex; gap: 7px; margin-top: 12px; }

    .wl-card {
        background: var(--neutral-0); border-radius: var(--r-card);
        border: 1px solid var(--neutral-200); box-shadow: var(--shadow-sm);
        overflow: hidden; margin-bottom: 12px;
        border-left-width: 4px;
        transition: box-shadow .22s, transform .22s;
    }
    .wl-card:hover { box-shadow: var(--shadow-md); transform: translateX(2px); }
    .wl-card.wl-waiting  { border-left-color: var(--amber); }
    .wl-card.wl-notified { border-left-color: var(--green-500); }
    .wl-header { padding: 16px 18px 12px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .wl-pos {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 800; color: #fff; flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
    }
    .pos-1 { background: linear-gradient(145deg, var(--green-500), var(--green-400)); }
    .pos-2 { background: linear-gradient(145deg, var(--amber), #fbbf24); }
    .pos-n { background: linear-gradient(145deg, var(--neutral-600), var(--neutral-500)); }
    .wl-room-name {
        font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 700;
        color: var(--neutral-900); line-height: 1.2;
    }
    .wl-room-meta { font-size: .7rem; color: var(--neutral-400); margin-top: 3px; }
    .wl-price {
        font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700;
        color: var(--green-600); white-space: nowrap; text-align: right;
    }
    .wl-price span { font-size: .65rem; font-weight: 400; color: var(--neutral-400); }
    .wl-alert-box {
        margin: 0 18px 12px; padding: 10px 14px; border-radius: 10px;
        font-size: .75rem; line-height: 1.55; display: flex; align-items: flex-start; gap: 10px;
    }
    .wl-alert-room  { background: var(--green-50); border: 1px solid var(--green-200); color: var(--green-800); }
    .wl-alert-dp    { background: var(--amber-pale); border: 1px solid var(--amber-line); color: #78350f; }
    .wl-alert-box i { flex-shrink: 0; font-size: .9rem; margin-top: 1px; }
    .dp-wrap { padding: 0 18px 12px; }
    .dp-bar-head {
        display: flex; justify-content: space-between;
        font-size: .68rem; font-weight: 700; color: var(--neutral-600); margin-bottom: 6px;
    }
    .dp-bar-head .paid { color: var(--green-600); }
    .dp-track { height: 7px; border-radius: var(--r-pill); background: var(--neutral-100); overflow: hidden; }
    .dp-fill {
        height: 100%; border-radius: var(--r-pill);
        background: linear-gradient(90deg, var(--green-500), var(--green-400));
        transition: width .5s ease;
    }
    .dp-fill.partial { background: linear-gradient(90deg, var(--amber), #fbbf24); }
    .wl-info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 0 18px 12px; }
    .wl-info-cell { background: var(--neutral-50); border: 1px solid var(--neutral-200); border-radius: 8px; padding: 9px 10px; }
    .cell-lbl {
        font-size: .6rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .08em; color: var(--neutral-400); margin-bottom: 3px;
    }
    .cell-val { font-size: .8rem; font-weight: 700; color: var(--neutral-900); }
    .cell-val.green { color: var(--green-600); }
    .cell-val.amber { color: var(--amber); }
    .cell-val.red   { color: var(--red); }
    .wl-footer {
        padding: 11px 18px; border-top: 1px solid var(--neutral-100);
        background: var(--neutral-50); display: flex; gap: 7px; flex-wrap: wrap; align-items: center;
    }

    .bk-table { width: 100%; border-collapse: collapse; }
    .bk-table th {
        padding: 11px 16px; font-size: .64rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em; color: var(--neutral-400);
        border-bottom: 1px solid var(--neutral-100); text-align: left;
        background: var(--neutral-50); white-space: nowrap;
    }
    .bk-table td {
        padding: 13px 16px; font-size: .79rem;
        border-bottom: 1px solid var(--neutral-100); vertical-align: middle; color: var(--neutral-600);
    }
    .bk-table tr:last-child td { border-bottom: none; }
    .bk-table tr:hover td { background: var(--green-50); }
    .bk-code { font-family: monospace; font-weight: 700; color: var(--green-600); font-size: .76rem; letter-spacing: .02em; }
    .room-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--neutral-100); border: 1px solid var(--neutral-200);
        padding: 3px 9px; border-radius: 6px;
        font-weight: 700; font-size: .74rem; color: var(--neutral-900);
    }
    .room-tag i { color: var(--green-500); }
    .bk-type {
        display: inline-block; font-size: .64rem; font-weight: 800;
        padding: 2px 8px; border-radius: 4px; letter-spacing: .04em;
    }
    .bkt-adv { background: var(--blue-pale); color: var(--blue); }
    .bkt-reg { background: var(--green-100); color: var(--green-700); }
    .bkt-wl  { background: var(--amber-pale); color: #92400e; }

    .badge-st {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: var(--r-pill);
        font-size: .67rem; font-weight: 700;
    }
    .badge-st::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
    .bs-pending::before    { background: var(--amber); }
    .bs-approved::before   { background: var(--green-600); }
    .bs-rejected::before   { background: var(--red); }
    .bs-wl::before         { background: var(--blue); }
    .bs-cancelled::before  { background: var(--neutral-400); }
    .bs-pending    { background: var(--amber-pale); color: #92400e; }
    .bs-approved   { background: var(--green-100); color: var(--green-600); }
    .bs-rejected   { background: var(--red-pale); color: var(--red); }
    .bs-wl         { background: var(--blue-pale); color: var(--blue); }
    .bs-cancelled  { background: var(--neutral-100); color: var(--neutral-500); }

    .badge-wl {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px; border-radius: var(--r-pill); font-size: .7rem; font-weight: 700;
    }
    .bwl-waiting  { background: var(--amber-pale); color: #92400e; border: 1px solid var(--amber-line); }
    .bwl-notified { background: var(--green-100); color: var(--green-700); border: 1px solid var(--green-200); }
    .bwl-cancelled { background: var(--neutral-100); color: var(--neutral-500); border: 1px solid var(--neutral-200); }

    .uk-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 9px;
        font-size: .75rem; font-weight: 700; border: none;
        cursor: pointer; text-decoration: none;
        transition: all .18s; white-space: nowrap;
        font-family: 'Instrument Sans', sans-serif;
    }
    .uk-btn-green {
        background: linear-gradient(145deg, var(--green-500), var(--green-400));
        color: #fff; box-shadow: 0 2px 8px rgba(42,179,107,.28);
    }
    .uk-btn-green:hover { background: linear-gradient(145deg, var(--green-600), var(--green-500)); color: #fff; transform: translateY(-1px); }
    .uk-btn-outline { background: transparent; border: 1.5px solid var(--neutral-200); color: var(--neutral-600); }
    .uk-btn-outline:hover { border-color: var(--green-400); color: var(--green-600); background: var(--green-50); }
    .uk-btn-amber { background: var(--amber); color: #fff; }
    .uk-btn-amber:hover { background: #d97706; color: #fff; }
    .uk-btn-red   { background: var(--red); color: #fff; }
    .uk-btn-red:hover { background: #b91c1c; color: #fff; }
    .uk-btn-red-outline { background: transparent; border: 1.5px solid var(--red-line); color: var(--red); }
    .uk-btn-red-outline:hover { background: var(--red-pale); }
    .uk-btn-blue  { background: var(--blue); color: #fff; }
    .uk-btn-blue:hover { background: #1d4ed8; color: #fff; }
    .uk-btn-sm    { padding: 5px 11px; font-size: .7rem; border-radius: 7px; }

    .side-card {
        background: var(--neutral-0); border-radius: var(--r-card);
        border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 18px;
    }
    .side-card-head {
        padding: 13px 16px; border-bottom: 1px solid var(--neutral-100);
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
        display: flex; align-items: center; gap: 8px;
    }
    .side-card-title { font-size: .82rem; font-weight: 700; color: var(--neutral-900); }

    .urgbox {
        padding: 14px 16px; border-bottom: 1px solid var(--neutral-100);
        transition: background .18s;
    }
    .urgbox:hover { background: var(--green-50); }
    .urgbox:last-child { border-bottom: none; }
    .urgbox-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
    .urgbox-num { font-family: monospace; font-size: .74rem; font-weight: 700; color: var(--neutral-900); }
    .urgbox-meta { font-size: .67rem; color: var(--neutral-400); margin-top: 2px; }
    .urgbox-amount { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; }
    .urgbox-amount.ov  { color: var(--red); }
    .urgbox-amount.unp { color: var(--amber); }

    .aroom {
        display: flex; gap: 12px; padding: 12px 16px;
        border-bottom: 1px solid var(--neutral-100);
        align-items: center; transition: background .18s;
    }
    .aroom:hover { background: var(--green-50); }
    .aroom:last-child { border-bottom: none; }
    .aroom-thumb {
        width: 56px; height: 56px; border-radius: 9px; flex-shrink: 0;
        background: linear-gradient(145deg, var(--green-700), var(--green-500));
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.2); font-size: 1.3rem; position: relative; overflow: hidden;
    }
    .aroom-thumb img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .aroom-num  { font-size: .62rem; font-weight: 700; color: var(--neutral-900); }
    .aroom-name { font-size: .78rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 2px; }
    .aroom-price { font-family: 'Fraunces', serif; font-size: .9rem; font-weight: 700; color: var(--green-600); margin-bottom: 5px; }
    .aroom-price small { font-size: .62rem; font-weight: 400; color: var(--neutral-400); }

    .step-item { display: flex; gap: 12px; align-items: flex-start; padding: 10px 16px; }
    .step-item + .step-item { border-top: 1px solid var(--neutral-100); }
    .step-num {
        width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 800;
    }
    .sn1 { background: var(--green-100); color: var(--green-600); }
    .sn2 { background: var(--blue-pale); color: var(--blue); }
    .sn3 { background: var(--purple-pale); color: var(--purple); }
    .sn4 { background: var(--green-100); color: var(--green-700); }
    .step-title { font-size: .78rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 2px; }
    .step-desc  { font-size: .7rem; color: var(--neutral-500); line-height: 1.5; }

    .empty-state {
        text-align: center; padding: 44px 24px;
        display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .empty-icon  { font-size: 2.2rem; color: var(--green-200); }
    .empty-title { font-weight: 700; font-size: .88rem; color: var(--neutral-600); }
    .empty-sub   { font-size: .76rem; color: var(--neutral-400); max-width: 240px; line-height: 1.55; }

    .penalty-box {
        background: var(--red-pale); border: 1px solid var(--red-line);
        border-radius: 8px; padding: 10px 14px;
        font-size: .75rem; color: #7f1d1d; display: flex; gap: 8px;
        margin-bottom: 14px; align-items: flex-start;
    }

    @media (max-width: 768px) {
        .dash-page { padding: 16px 14px 56px; }
        .hero-banner { padding: 22px 20px; }
        .hero-right { display: none; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .hero-title  { font-size: 1.6rem; }
        .wl-info-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-value { font-size: 1.5rem; }
        .bk-table thead { display: none; }
        .bk-table tr { display: block; border-bottom: 1px solid var(--neutral-100); padding: 10px 0; }
        .bk-table td { display: flex; justify-content: space-between; border: none; padding: 4px 16px; }
        .bk-table td::before { content: attr(data-label); font-size: .65rem; font-weight: 700; color: var(--neutral-400); text-transform: uppercase; }
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.prospective-sidebar')

<div class="dash-page">

@foreach(['success','warning','error','info'] as $t)
@if(session($t))
<div class="flash-msg flash-{{ $t === 'error' ? 'error' : $t }}">
    <i class="bi bi-{{ $t === 'success' ? 'check-circle-fill' : ($t === 'error' ? 'x-circle-fill' : ($t === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill')) }} flex-shrink-0 mt-1"></i>
    <div>{!! session($t) !!}</div>
</div>
@endif
@endforeach

@php
    $totalBookings    = $bookings->count();
    $pendingBookings  = $bookings->where('status','pending')->count();
    $approvedBookings = $bookings->where('status','approved')->count();
    $activeWL         = isset($waitingLists) ? $waitingLists->whereIn('status',['waiting','notified'])->count() : 0;
    $notifiedWL       = isset($waitingLists) ? $waitingLists->where('status','notified')->count() : 0;
    $unpaidInvoices   = isset($invoices) ? $invoices->whereIn('status',['unpaid','overdue'])->count() : 0;
    $overdueInvoices  = isset($invoices) ? $invoices->where('status','overdue')->count() : 0;
    $pendingVerif     = isset($invoices) ? $invoices->where('status','pending_verification')->count() : 0;
    $totalTagihan     = isset($invoices) ? $invoices->whereIn('status',['unpaid','overdue'])->sum('total_amount') : 0;
    $dpBelumBayar     = isset($waitingLists) ? $waitingLists->whereIn('status',['waiting','notified'])->filter(fn($w)=>!$w->isDpPaid())->count() : 0;
    $availableRooms   = isset($rooms) ? $rooms->where('status','available')->count() : 0;
    $bestWL           = isset($waitingLists) ? $waitingLists->whereIn('status',['waiting','notified'])->sortBy('position')->first() : null;
@endphp

<div class="hero-banner">
    <div class="hero-banner-dots"></div>
    <div class="hero-content">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Dashboard Calon Penghuni
            </div>
            <h1 class="hero-title">
                Selamat Datang,<br><em>{{ $user->name }}</em>
            </h1>
            <div class="hero-chips">
                <div class="hero-chip"><i class="bi bi-envelope-fill"></i>{{ $user->email }}</div>
                @if($user->phone)
                <div class="hero-chip"><i class="bi bi-telephone-fill"></i>{{ $user->phone }}</div>
                @endif
                <div class="hero-chip"><i class="bi bi-calendar3"></i>Bergabung {{ $user->created_at?->format('M Y') }}</div>
                @if($notifiedWL > 0)
                <div class="hero-chip chip-alert-green">
                    <i class="bi bi-bell-fill" style="color:var(--green-400)"></i>
                    {{ $notifiedWL }} kamar tersedia untuk Anda
                </div>
                @endif
                @if($overdueInvoices > 0)
                <div class="hero-chip chip-alert-red">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444"></i>
                    {{ $overdueInvoices }} tagihan melewati jatuh tempo
                </div>
                @endif
            </div>
        </div>
        <div class="hero-right">
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $totalBookings }}</span>
                <span class="hero-stat-lbl">Total Booking</span>
            </div>
            <div class="hero-stat" style="background:rgba(42,179,107,.14);border-color:rgba(78,201,138,.25)">
                <span class="hero-stat-num" style="color:var(--green-300)">{{ $availableRooms }}</span>
                <span class="hero-stat-lbl" style="color:rgba(134,217,174,.6)">Kamar Tersedia</span>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid">

    <div class="stat-card sc-green">
        <div class="stat-top">
            <div class="stat-icon-wrap"><i class="bi bi-calendar-check-fill"></i></div>
            @if($pendingBookings > 0)
            <span class="stat-trend trend-warn">{{ $pendingBookings }} pending</span>
            @elseif($approvedBookings > 0)
            <span class="stat-trend trend-ok">{{ $approvedBookings }} aktif</span>
            @else
            <span class="stat-trend trend-neu">kosong</span>
            @endif
        </div>
        <div>
            <div class="stat-label">Total Booking</div>
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-hint">{{ $pendingBookings }} menunggu &middot; {{ $approvedBookings }} disetujui</div>
        </div>
    </div>

    <div class="stat-card {{ $overdueInvoices > 0 ? 'sc-red' : ($unpaidInvoices > 0 ? 'sc-amber' : 'sc-green') }}">
        <div class="stat-top">
            <div class="stat-icon-wrap"
                 style="{{ $overdueInvoices > 0 ? 'background:var(--red-pale);color:var(--red)' : ($unpaidInvoices > 0 ? 'background:var(--amber-pale);color:var(--amber)' : 'background:var(--green-100);color:var(--green-600)') }}">
                <i class="bi bi-{{ $overdueInvoices > 0 ? 'exclamation-triangle-fill' : ($unpaidInvoices > 0 ? 'receipt' : 'check-circle-fill') }}"></i>
            </div>
            @if($overdueInvoices > 0)
            <span class="stat-trend trend-bad">{{ $overdueInvoices }} terlambat</span>
            @elseif($unpaidInvoices > 0)
            <span class="stat-trend trend-warn">belum bayar</span>
            @else
            <span class="stat-trend trend-ok">lunas semua</span>
            @endif
        </div>
        <div>
            <div class="stat-label">Tagihan Belum Bayar</div>
            <div class="stat-value">{{ $unpaidInvoices }}</div>
            <div class="stat-hint {{ $overdueInvoices > 0 ? 'red' : ($unpaidInvoices > 0 ? '' : 'green') }}">
                @if($overdueInvoices > 0){{ $overdueInvoices }} tagihan melewati jatuh tempo
                @elseif($unpaidInvoices > 0)Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                @else Semua tagihan telah lunas
                @endif
            </div>
        </div>
    </div>

    <div class="stat-card sc-teal">
        <div class="stat-top">
            <div class="stat-icon-wrap">
                <i class="bi bi-house-check-fill"></i>
            </div>
            <span class="stat-trend {{ $availableRooms > 0 ? 'trend-ok' : 'trend-neu' }}">
                {{ $availableRooms > 0 ? 'tersedia' : 'penuh' }}
            </span>
        </div>
        <div>
            <div class="stat-label">Kamar Tersedia</div>
            <div class="stat-value">{{ $availableRooms }}</div>
            <div class="stat-hint">bisa langsung booking sekarang</div>
        </div>
    </div>

</div>

<div class="dash-grid">
    <div>
        @php
            $invList = isset($invoices)
                ? $invoices->whereIn('status',['unpaid','overdue','pending_verification'])
                           ->sortByDesc(fn($i) => $i->status === 'overdue' ? 2 : ($i->status === 'unpaid' ? 1 : 0))
                : collect();
        @endphp

        @if($invList->count() > 0)
        <div class="sec-head">
            <div class="sec-title">
                <div class="sec-title-icon sti-purple"><i class="bi bi-receipt-cutoff"></i></div>
                Tagihan &amp; Pembayaran
                @if($unpaidInvoices > 0)
                    <span class="sec-tag sec-tag-red">{{ $unpaidInvoices }} belum bayar</span>
                @endif
            </div>
            <a href="{{ route('prospective.invoices.index') }}" class="sec-link">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @foreach($invList->take(3) as $inv)
        @php
            $iOv = $inv->status === 'overdue' || (isset($inv->due_date) && $inv->due_date?->isPast() && $inv->status === 'unpaid');
            $iPv = $inv->status === 'pending_verification';
            $dl  = isset($inv->due_date) ? now()->diffInDays($inv->due_date, false) : null;
            $cls = $iOv ? 'ov' : ($iPv ? 'pv' : 'unp');
        @endphp
        <div class="inv-card {{ $cls }}">
            <div class="inv-inner">
                <div class="inv-row">
                    <div style="min-width:0">
                        <div class="inv-num">{{ $inv->invoice_number }}</div>
                        <div class="inv-room-period">
                            <i class="bi bi-door-closed me-1"></i>Kamar {{ $inv->room?->room_number }}
                            @if($inv->period_start) &middot; {{ $inv->period_start->format('M Y') }} @endif
                        </div>
                        <div class="inv-due {{ $iOv ? 'ov' : ($dl !== null && $dl <= 3 && !$iPv ? 'warn' : ($iPv ? 'pv' : 'norm')) }}">
                            @if($iOv)
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Terlambat {{ $inv->due_date?->diffInDays(now()) }} hari
                            @elseif($iPv)
                                <i class="bi bi-hourglass-split"></i> Menunggu verifikasi admin
                            @elseif($dl !== null && $dl <= 3)
                                <i class="bi bi-clock-fill"></i>
                                Jatuh tempo {{ $dl <= 0 ? 'hari ini' : 'dalam '.$dl.' hari' }}
                            @else
                                <i class="bi bi-calendar3"></i> Jatuh tempo: {{ $inv->due_date?->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div class="inv-amount {{ $cls }}">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</div>
                        <span class="inv-pill ip-{{ $cls }}">
                            {{ $iOv ? 'Terlambat' : ($iPv ? 'Diverifikasi' : 'Belum Bayar') }}
                        </span>
                    </div>
                </div>
                @if(!$iPv)
                <div class="inv-actions">
                    <a href="{{ route('prospective.invoices.pay-midtrans', $inv->id) }}"
                       class="uk-btn uk-btn-sm {{ $iOv ? 'uk-btn-red' : 'uk-btn-amber' }}">
                        <i class="bi bi-credit-card-fill"></i>
                        {{ $iOv ? 'Bayar Sekarang' : 'Bayar via Midtrans' }}
                    </a>
                    <a href="{{ route('prospective.invoices.show', $inv->id) }}"
                       class="uk-btn uk-btn-outline uk-btn-sm">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </div>
                @else
                <div class="inv-actions">
                    <a href="{{ route('prospective.invoices.show', $inv->id) }}"
                       class="uk-btn uk-btn-outline uk-btn-sm">
                        <i class="bi bi-eye"></i> Cek Status Verifikasi
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif

        @if(isset($waitingLists) && $waitingLists->count() > 0)
        <div class="sec-head" style="margin-top:{{ $invList->count() > 0 ? '26px' : '0' }}">
            <div class="sec-title">
                <div class="sec-title-icon sti-amber"><i class="bi bi-list-stars"></i></div>
                Posisi Waiting List
                @if($notifiedWL > 0)
                    <span class="sec-tag sec-tag-green">{{ $notifiedWL }} kamar tersedia</span>
                @endif
            </div>
            <a href="{{ route('prospective.waiting-list.index') }}" class="sec-link">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @foreach($waitingLists->whereIn('status',['waiting','notified'])->take(3) as $wl)
        @php
            $dpPaid    = $wl->isDpPaid();
            $remaining = $wl->remainingAmount();
            $dpPct     = $wl->dp_amount > 0 ? min(100, round(($wl->dp_paid / $wl->dp_amount) * 100)) : 0;
        @endphp
        <div class="wl-card wl-{{ $wl->status }}">
            <div class="wl-header">
                <div style="display:flex;align-items:flex-start;gap:10px;min-width:0">
                    <div class="wl-pos {{ $wl->position == 1 ? 'pos-1' : ($wl->position == 2 ? 'pos-2' : 'pos-n') }}">
                        #{{ $wl->position }}
                    </div>
                    <div style="min-width:0">
                        <div class="wl-room-name">Kamar {{ $wl->room?->room_number }}
                            @if($wl->room?->room_name)
                                <span style="font-family:'Instrument Sans',sans-serif;font-size:.8rem;font-weight:400;color:var(--neutral-500)"> &mdash; {{ $wl->room->room_name }}</span>
                            @endif
                        </div>
                        <div class="wl-room-meta">
                            @if($wl->room?->floor)<i class="bi bi-layers"></i> Lt.{{ $wl->room->floor }} @endif
                            @if($wl->room?->size)&middot; {{ $wl->room->size }} @endif
                            @if($wl->booking) &middot; <span style="font-family:monospace;color:var(--green-600)">{{ $wl->booking->booking_code }}</span> @endif
                        </div>
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div class="wl-price">Rp {{ number_format($wl->room?->price ?? 0, 0, ',', '.') }}<span>/bln</span></div>
                    <div style="margin-top:5px">
                        <span class="badge-wl bwl-{{ $wl->status }}">
                            {{ $wl->status === 'waiting' ? 'Antri' : 'Tersedia' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($wl->status === 'notified')
            <div class="wl-alert-box wl-alert-room">
                <i class="bi bi-house-check-fill" style="color:var(--green-600)"></i>
                <div>
                    <strong>Kamar Siap Ditempati.</strong> Segera lunasi sisa
                    <strong>Rp {{ number_format($remaining,0,',','.') }}</strong> untuk resmi menghuni.
                    @if($wl->notified_at) <br><span style="opacity:.75">Notifikasi: {{ $wl->notified_at->format('d M Y') }}</span> @endif
                </div>
            </div>
            @elseif(!$dpPaid)
            <div class="wl-alert-box wl-alert-dp">
                <i class="bi bi-exclamation-triangle-fill" style="color:var(--amber)"></i>
                <div>
                    <strong>DP Belum Dibayar.</strong> Posisi Anda baru aman setelah membayar DP
                    <strong>Rp {{ number_format($wl->dp_amount,0,',','.') }}</strong>.
                </div>
            </div>
            @endif

            <div class="dp-wrap">
                <div class="dp-bar-head">
                    <span>DP 30%{{ $dpPaid ? ' (Lunas)' : '' }}</span>
                    <span>Rp {{ number_format($wl->dp_paid ?? 0,0,',','.') }} / Rp {{ number_format($wl->dp_amount ?? 0,0,',','.') }}</span>
                </div>
                <div class="dp-track">
                    <div class="dp-fill {{ !$dpPaid ? 'partial' : '' }}" style="width:{{ $dpPct }}%"></div>
                </div>
            </div>

            <div class="wl-info-grid">
                <div class="wl-info-cell">
                    <div class="cell-lbl">Target DP</div>
                    <div class="cell-val green">Rp {{ number_format($wl->dp_amount ?? 0,0,',','.') }}</div>
                </div>
                <div class="wl-info-cell">
                    <div class="cell-lbl">Sisa Bayar</div>
                    <div class="cell-val {{ $remaining > 0 ? 'amber' : 'green' }}">Rp {{ number_format($remaining,0,',','.') }}</div>
                </div>
                <div class="wl-info-cell">
                    <div class="cell-lbl">Masuk WL</div>
                    <div class="cell-val">{{ $wl->approved_at?->format('d M Y') ?? '-' }}</div>
                </div>
            </div>

            <div class="wl-footer">
                <a href="{{ route('prospective.waiting-list.show', $wl->id) }}" class="uk-btn uk-btn-outline uk-btn-sm">
                    <i class="bi bi-eye"></i> Detail
                </a>
                @if(!$dpPaid)
                    <a href="{{ route('prospective.waiting-list.pay-dp', $wl->id) }}" class="uk-btn uk-btn-amber uk-btn-sm">
                        <i class="bi bi-credit-card-fill"></i> Bayar DP
                    </a>
                @else
                    <span class="uk-btn uk-btn-sm" style="background:var(--green-100);color:var(--green-600);cursor:default">
                        <i class="bi bi-check-circle-fill"></i> DP Lunas
                    </span>
                @endif
                @if($wl->status === 'notified' && $dpPaid && $remaining > 0)
                    <a href="{{ route('prospective.waiting-list.pay-dp', $wl->id) }}" class="uk-btn uk-btn-blue uk-btn-sm">
                        <i class="bi bi-wallet2"></i> Lunasi Sisa
                    </a>
                @endif
                <button class="uk-btn uk-btn-red-outline uk-btn-sm ms-auto"
                        data-bs-toggle="modal" data-bs-target="#cancelModal{{ $wl->id }}">
                    <i class="bi bi-x-circle"></i> Batalkan
                </button>
            </div>
        </div>
        @endforeach
        @endif

        <div class="sec-head" style="margin-top:{{ ($invList->count() > 0 || (isset($waitingLists) && $waitingLists->count() > 0)) ? '26px' : '0' }}">
            <div class="sec-title">
                <div class="sec-title-icon sti-green"><i class="bi bi-calendar-check-fill"></i></div>
                Riwayat Booking
            </div>
            <a href="{{ route('prospective.bookings') }}" class="sec-link">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="o-card">
            @if($bookings->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-calendar-plus"></i></div>
                <div class="empty-title">Belum ada booking</div>
                <div class="empty-sub">Pilih kamar yang tersedia dan ajukan booking untuk memulai.</div>
                <a href="{{ route('home') }}#kamar" class="uk-btn uk-btn-green uk-btn-sm" style="margin-top:4px">
                    <i class="bi bi-house-door-fill"></i> Cari Kamar
                </a>
            </div>
            @else
            <div style="overflow-x:auto">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kamar</th>
                            <th>Tipe</th>
                            <th>Check-in</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings->take(8) as $b)
                        <tr>
                            <td data-label="Kode">
                                <span class="bk-code">{{ $b->booking_code }}</span>
                            </td>
                            <td data-label="Kamar">
                                <span class="room-tag">
                                    <i class="bi bi-door-closed"></i>{{ $b->room?->room_number ?? '-' }}
                                </span>
                            </td>
                            <td data-label="Tipe">
                                @if($b->booking_type === 'advance')
                                    <span class="bk-type bkt-adv">Advance</span>
                                @elseif($b->status === 'waiting_list')
                                    <span class="bk-type bkt-wl">Waiting List</span>
                                @else
                                    <span class="bk-type bkt-reg">Regular</span>
                                @endif
                            </td>
                            <td data-label="Check-in" style="white-space:nowrap">
                                {{ $b->check_in_date?->format('d M Y') ?? '-' }}
                            </td>
                            <td data-label="Status">
                                @php
                                    $sc = ['pending'=>'bs-pending','approved'=>'bs-approved','rejected'=>'bs-rejected','waiting_list'=>'bs-wl','cancelled'=>'bs-cancelled'][$b->status] ?? 'bs-pending';
                                    $sl = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak','waiting_list'=>'Waiting List','cancelled'=>'Dibatalkan'][$b->status] ?? $b->status;
                                @endphp
                                <span class="badge-st {{ $sc }}">{{ $sl }}</span>
                                @if($b->status === 'approved' && $b->invoice)
                                <div style="margin-top:4px">
                                    <a href="{{ route('prospective.invoices.show', $b->invoice->id) }}"
                                       style="font-size:.65rem;color:var(--purple);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                                        <i class="bi bi-receipt"></i>Invoice
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($bookings->count() > 8)
            <div style="padding:12px 16px;text-align:center;border-top:1px solid var(--neutral-100)">
                <a href="{{ route('prospective.bookings') }}" class="sec-link" style="font-size:.78rem">
                    Lihat {{ $bookings->count() - 8 }} booking lainnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @endif
            @endif
        </div>

    </div>
    <div>
        @php
            $urgInv = isset($invoices)
                ? $invoices->whereIn('status',['unpaid','overdue'])->sortByDesc(fn($i) => $i->status === 'overdue')->take(3)
                : collect();
        @endphp
        @if($urgInv->count() > 0)
        <div class="side-card" style="border-top:3px solid var(--red)">
            <div class="side-card-head" style="background:var(--red-pale)">
                <div class="sec-title-icon sti-red" style="width:24px;height:24px;border-radius:6px">
                    <i class="bi bi-exclamation-circle-fill" style="font-size:.7rem"></i>
                </div>
                <div class="side-card-title" style="color:var(--red)">Tagihan Perlu Dibayar</div>
                <span class="sec-tag sec-tag-red ms-auto" style="font-size:.6rem">{{ $urgInv->count() }}</span>
            </div>
            @foreach($urgInv as $inv)
            @php $iov = $inv->status === 'overdue' || $inv->due_date?->isPast(); @endphp
            <div class="urgbox">
                <div class="urgbox-top">
                    <div>
                        <div class="urgbox-num">{{ $inv->invoice_number }}</div>
                        <div class="urgbox-meta">
                            Kamar {{ $inv->room?->room_number }}
                            @if($inv->period_start) &middot; {{ $inv->period_start->format('M Y') }} @endif
                        </div>
                    </div>
                    <div class="urgbox-amount {{ $iov ? 'ov' : 'unp' }}">
                        Rp {{ number_format($inv->total_amount,0,',','.') }}
                    </div>
                </div>
                @if($iov)
                <div style="font-size:.68rem;color:var(--red);font-weight:700;margin-bottom:7px">
                    <i class="bi bi-clock-fill me-1"></i>Terlambat {{ $inv->due_date?->diffInDays(now()) }} hari
                </div>
                @else
                <div style="font-size:.68rem;color:var(--neutral-400);margin-bottom:7px">
                    <i class="bi bi-calendar3 me-1"></i>Jatuh tempo: {{ $inv->due_date?->format('d M Y') }}
                </div>
                @endif
                <a href="{{ route('prospective.invoices.pay-midtrans', $inv->id) }}"
                   class="uk-btn uk-btn-sm {{ $iov ? 'uk-btn-red' : 'uk-btn-amber' }}"
                   style="width:100%;justify-content:center">
                    <i class="bi bi-credit-card-fill"></i> Bayar Sekarang
                </a>
            </div>
            @endforeach
            @if(isset($invoices) && $invoices->whereIn('status',['unpaid','overdue'])->count() > 3)
            <div style="padding:10px 16px;text-align:center;border-top:1px solid var(--neutral-100)">
                <a href="{{ route('prospective.invoices.index') }}" class="sec-link" style="font-size:.72rem">
                    +{{ $invoices->whereIn('status',['unpaid','overdue'])->count() - 3 }} tagihan lagi <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>
        @endif

        @php
            $dpItems = isset($waitingLists)
                ? $waitingLists->whereIn('status',['waiting','notified'])->filter(fn($w)=>!$w->isDpPaid())
                : collect();
        @endphp
        @if($dpItems->count() > 0)
        <div class="side-card" style="border-top:3px solid var(--amber)">
            <div class="side-card-head" style="background:var(--amber-pale)">
                <div class="sec-title-icon" style="width:24px;height:24px;border-radius:6px;background:var(--amber-line);color:var(--amber)">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:.7rem"></i>
                </div>
                <div class="side-card-title" style="color:#92400e">DP Waiting List</div>
            </div>
            @foreach($dpItems as $wl)
            <div class="urgbox">
                <div class="urgbox-top">
                    <div>
                        <div class="urgbox-num" style="font-family:'Instrument Sans',sans-serif;font-size:.78rem">
                            Kamar {{ $wl->room?->room_number }} &middot; Posisi #{{ $wl->position }}
                        </div>
                        <div class="urgbox-meta">
                            @if($wl->status === 'notified')
                                <span style="color:var(--green-600);font-weight:700">Kamar tersedia</span>
                            @else
                                Menunggu antrian
                            @endif
                        </div>
                    </div>
                    <div class="urgbox-amount" style="color:var(--amber)">
                        Rp {{ number_format($wl->dp_amount,0,',','.') }}
                    </div>
                </div>
                <a href="{{ route('prospective.waiting-list.pay-dp', $wl->id) }}"
                   class="uk-btn uk-btn-amber uk-btn-sm"
                   style="width:100%;justify-content:center">
                    <i class="bi bi-credit-card-fill"></i> Bayar DP
                </a>
            </div>
            @endforeach
        </div>
        @endif

        @if(isset($rooms) && $rooms->where('status','available')->count() > 0)
        <div class="side-card">
            <div class="side-card-head">
                <div class="sec-title-icon sti-green" style="width:24px;height:24px;border-radius:6px">
                    <i class="bi bi-house-door-fill" style="font-size:.7rem"></i>
                </div>
                <div class="side-card-title">Kamar Tersedia</div>
                <a href="{{ route('home') }}#kamar" class="sec-link ms-auto" style="font-size:.72rem">
                    Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @foreach($rooms->where('status','available')->take(3) as $room)
            <div class="aroom">
                <div class="aroom-thumb">
                    @if($room->image_path)
                        <img src="{{ asset('storage/'.$room->image_path) }}" alt="{{ $room->room_name }}">
                    @else
                        <i class="bi bi-house-door"></i>
                    @endif
                </div>
                <div style="flex:1;min-width:0">
                    <div class="aroom-name">{{ $room->room_name ?? 'Kamar '.$room->room_number }}</div>
                    <div class="aroom-num">
                        No.{{ $room->room_number }}
                        @if($room->floor) &middot; Lt.{{ $room->floor }} @endif
                        @if($room->size) &middot; {{ $room->size }} @endif
                    </div>
                    <div class="aroom-price">
                        Rp {{ number_format($room->price, 0, ',', '.') }}<small>/bln</small>
                    </div>
                    <a href="{{ route('booking.create', $room->id) }}"
                       class="uk-btn uk-btn-green uk-btn-sm" style="padding:4px 10px;font-size:.68rem">
                        <i class="bi bi-calendar-plus"></i> Booking
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="side-card">
            <div class="side-card-head">
                <div class="sec-title-icon sti-green" style="width:24px;height:24px;border-radius:6px">
                    <i class="bi bi-signpost-2-fill" style="font-size:.7rem"></i>
                </div>
                <div class="side-card-title">Alur Menjadi Penghuni</div>
            </div>
            <div class="step-item">
                <div class="step-num sn1"><i class="bi bi-calendar-check-fill" style="font-size:.7rem"></i></div>
                <div>
                    <div class="step-title">1. Ajukan Booking</div>
                    <div class="step-desc">Pilih kamar, isi formulir dan layanan tambahan.</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num sn2"><i class="bi bi-shield-check-fill" style="font-size:.7rem"></i></div>
                <div>
                    <div class="step-title">2. Peninjauan Admin</div>
                    <div class="step-desc">Admin menyetujui dan menerbitkan invoice tagihan.</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num sn3"><i class="bi bi-receipt-cutoff" style="font-size:.7rem"></i></div>
                <div>
                    <div class="step-title">3. Bayar Invoice</div>
                    <div class="step-desc">Bayar via QRIS atau Transfer Bank melalui Midtrans.</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num sn4"><i class="bi bi-house-check-fill" style="font-size:.7rem"></i></div>
                <div>
                    <div class="step-title">4. Menjadi Penghuni</div>
                    <div class="step-desc">Akun diperbarui otomatis setelah pembayaran dikonfirmasi.</div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@if(isset($waitingLists))
@foreach($waitingLists->whereIn('status',['waiting','notified']) as $wl)
@php $dpPaid = $wl->isDpPaid(); $penalty = $dpPaid ? round($wl->dp_paid * 0.20) : 0; @endphp
<div class="modal fade" id="cancelModal{{ $wl->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:var(--shadow-lg)">
            <div class="modal-header" style="background:var(--red-pale);border-bottom:1px solid var(--red-line);padding:18px 22px">
                <h5 class="modal-title" style="font-size:.92rem;font-weight:700;color:var(--red);font-family:'Instrument Sans',sans-serif">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Batalkan Posisi Waiting List
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:22px">
                <p style="font-size:.82rem;color:var(--neutral-600);margin-bottom:14px;font-family:'Instrument Sans',sans-serif">
                    Anda akan membatalkan posisi waiting list untuk
                    <strong>Kamar {{ $wl->room?->room_number }}</strong>.
                </p>
                @if($dpPaid && $penalty > 0)
                <div class="penalty-box">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>Denda 20%</strong> dari DP yang telah dibayar:
                        <div style="font-size:1.1rem;font-weight:800;color:var(--red);margin-top:4px;font-family:'Fraunces',serif">
                            Rp {{ number_format($penalty, 0, ',', '.') }}
                        </div>
                        <div style="font-size:.7rem;margin-top:2px;opacity:.75">Sisa DP (80%) diproses refund secara manual.</div>
                    </div>
                </div>
                @else
                <div style="background:var(--green-50);border:1px solid var(--green-200);border-radius:8px;padding:10px 14px;font-size:.78rem;color:var(--green-800);margin-bottom:14px;font-family:'Instrument Sans',sans-serif">
                    <i class="bi bi-info-circle me-1"></i>Tidak ada denda karena DP belum dibayar.
                </div>
                @endif
                <form method="POST" action="{{ route('prospective.waiting-list.cancel', $wl->id) }}" id="cancelForm{{ $wl->id }}">
                    @csrf
                    <label style="font-size:.78rem;font-weight:700;color:var(--neutral-900);display:block;margin-bottom:6px;font-family:'Instrument Sans',sans-serif">
                        Alasan Pembatalan <span style="color:var(--red)">*</span>
                    </label>
                    <textarea name="cancellation_reason" rows="3"
                        style="width:100%;border:1.5px solid var(--neutral-200);border-radius:8px;
                               padding:10px 12px;font-size:.8rem;font-family:'Instrument Sans',sans-serif;
                               resize:none;outline:none;transition:border-color .18s"
                        placeholder="Jelaskan alasan pembatalan Anda..."
                        required
                        onfocus="this.style.borderColor='var(--green-400)'"
                        onblur="this.style.borderColor='var(--neutral-200)'"></textarea>
                </form>
            </div>
            <div class="modal-footer" style="padding:14px 22px;border-top:1px solid var(--neutral-100);gap:8px">
                <button type="button" class="uk-btn uk-btn-outline" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="cancelForm{{ $wl->id }}" class="uk-btn uk-btn-red">
                    <i class="bi bi-x-circle-fill"></i> Ya, Batalkan
                    @if($penalty > 0)(Denda Rp {{ number_format($penalty,0,',','.') }})@endif
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>