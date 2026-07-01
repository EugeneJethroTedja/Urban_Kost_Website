<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Saya — Urban Kost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --blue:        #2563eb;
            --blue-pale:   #eff6ff;
            --blue-line:   #bfdbfe;
            --red:         #dc2626;
            --red-pale:    #fef2f2;
            --red-line:    #fecaca;
            --amber:       #f59e0b;
            --amber-pale:  #fffbeb;
            --amber-line:  #fde68a;
            --ok:          #16a34a;
            --ok-pale:     #f0fdf4;
            --ok-line:     #bbf7d0;
            --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
            --shadow-md: 0 4px 20px rgba(15,31,24,.09);
            --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
            --safe-top:    env(safe-area-inset-top, 0px);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
            --safe-left:   env(safe-area-inset-left, 0px);
            --safe-right:  env(safe-area-inset-right, 0px);
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
            overflow-x: hidden;
            padding-left: var(--safe-left);
            padding-right: var(--safe-right);
            padding-bottom: var(--safe-bottom);
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

        .page { max-width: 1000px; margin: 0 auto; padding: 36px 24px 80px; }

        .hero-banner {
            position: relative; overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
            padding: 36px 32px;
            margin-bottom: 28px;
            box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 20px;
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
            width: 380px; height: 380px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,.1);
            pointer-events: none; z-index: 1;
        }
        .hero-left { position: relative; z-index: 1; flex: 1; min-width: 0; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            padding: 5px 14px; border-radius: 100px;
            font-size: .68rem; font-weight: 600;
            letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.82);
            margin-bottom: 14px;
        }
        .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
        .hero-title {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            color: #fff;
            line-height: 1.15;
            letter-spacing: -.025em;
            margin-bottom: 8px;
        }
        .hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
        .hero-sub { font-size: .88rem; color: rgba(255,255,255,.62); max-width: 380px; line-height: 1.6; }
        .hero-right {
            display: flex; flex-direction: column; align-items: flex-end;
            gap: 14px; position: relative; z-index: 1; flex-shrink: 0;
        }
        .hero-stats { display: flex; gap: 10px; flex-wrap: wrap; }
        .hero-stat {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px; padding: 10px 16px;
            text-align: center; min-width: 68px;
        }
        .hero-stat-num {
            font-family: 'Fraunces', serif;
            font-size: 1.5rem; font-weight: 700; color: #fff; line-height: 1;
        }
        .hero-stat-label { font-size: .6rem; color: rgba(255,255,255,.65); margin-top: 3px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; }

        .btn-new-booking {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 22px; border-radius: 11px;
            background: #fff; color: var(--green-600);
            font-size: .8rem; font-weight: 600;
            text-decoration: none; transition: all .22s;
            box-shadow: 0 3px 14px rgba(0,0,0,.12);
            border: none; cursor: pointer; white-space: nowrap;
        }
        .btn-new-booking:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.18); color: var(--green-700); }
        .btn-new-booking.disabled-look {
            background: rgba(255,255,255,.25); color: rgba(255,255,255,.5);
            cursor: not-allowed; box-shadow: none; transform: none; pointer-events: none;
        }

        .flash-box {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 13px 16px; border-radius: 10px;
            font-size: .82rem; margin-bottom: 14px;
            border: 1px solid;
        }

        .locked-banner {
            display: flex; align-items: flex-start; gap: 16px;
            background: var(--amber-pale); border: 1.5px solid var(--amber-line);
            border-radius: 16px; padding: 18px 22px; margin-bottom: 22px;
        }
        .locked-icon {
            width: 44px; height: 44px; border-radius: 11px;
            background: var(--amber); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .locked-title { font-size: .88rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 4px; }
        .locked-sub { font-size: .78rem; color: var(--neutral-600); line-height: 1.6; }

        .filter-bar { display: flex; gap: 7px; margin-bottom: 20px; flex-wrap: wrap; }
        .filter-tab {
            padding: 8px 16px; border-radius: 100px;
            font-size: .73rem; font-weight: 600;
            border: 1.5px solid var(--neutral-200); color: var(--neutral-500);
            background: var(--neutral-0); cursor: pointer; transition: all .2s;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap;
        }
        .filter-tab:hover { border-color: var(--green-500); color: var(--green-600); }
        .filter-tab.active { border-color: var(--green-500); color: var(--green-600); background: var(--green-100); }
        .ftab-count {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 20px; height: 20px; border-radius: 99px;
            background: var(--green-500); color: #fff; font-size: .6rem; font-weight: 700; padding: 0 5px;
        }
        .filter-tab:not(.active) .ftab-count { background: var(--neutral-200); color: var(--neutral-500); }

        .booking-card {
            background: var(--neutral-0);
            border-radius: 16px;
            border: 1px solid var(--green-100);
            box-shadow: var(--shadow-md);
            overflow: hidden; margin-bottom: 18px;
            transition: box-shadow .25s, transform .25s, border-color .25s;
        }
        .booking-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); border-color: var(--green-200); }
        .booking-card.status-pending      { border-left: 4px solid var(--amber); }
        .booking-card.status-approved     { border-left: 4px solid var(--green-500); }
        .booking-card.status-rejected     { border-left: 4px solid var(--red); }
        .booking-card.status-waiting_list { border-left: 4px solid var(--blue); }
        .booking-card.status-cancelled    { border-left: 4px solid var(--neutral-400); opacity: .82; }

        .card-header-strip {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid var(--green-100);
            background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
            flex-wrap: wrap; gap: 10px;
        }
        .card-header-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; flex: 1; min-width: 0; }
        .booking-code-big {
            font-family: 'Fraunces', serif;
            font-size: 1.05rem; font-weight: 700; color: var(--green-600); letter-spacing: .02em;
        }
        .type-pill {
            font-size: .6rem; font-weight: 700; letter-spacing: .06em;
            text-transform: uppercase; padding: 3px 10px; border-radius: 5px; white-space: nowrap; flex-shrink: 0;
        }
        .tp-regular { background: var(--green-100); color: var(--green-700); }
        .tp-advance  { background: var(--blue-pale); color: var(--blue); }
        .card-header-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .created-label { font-size: .68rem; color: var(--neutral-400); text-align: right; line-height: 1.5; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px; border-radius: 100px;
            font-size: .68rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
        }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .sb-pending      { background: var(--amber-pale); color: #92400e; }
        .sb-pending .status-dot      { background: var(--amber); }
        .sb-approved     { background: var(--green-100); color: var(--green-700); }
        .sb-approved .status-dot     { background: var(--green-500); }
        .sb-rejected     { background: var(--red-pale); color: var(--red); }
        .sb-rejected .status-dot     { background: var(--red); }
        .sb-waiting_list { background: var(--blue-pale); color: var(--blue); }
        .sb-waiting_list .status-dot { background: var(--blue); }
        .sb-cancelled    { background: var(--neutral-100); color: var(--neutral-500); }
        .sb-cancelled .status-dot    { background: var(--neutral-400); }

        .card-body-grid { display: grid; grid-template-columns: 130px 1fr; min-height: 180px; }
        .room-thumb {
            background: linear-gradient(145deg, var(--green-800), var(--green-500));
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.2); font-size: 2.2rem;
            position: relative; overflow: hidden; flex-shrink: 0;
        }
        .room-thumb img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .room-num-pill {
            position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%);
            background: rgba(0,0,0,.55); color: #fff;
            font-size: .6rem; font-weight: 700;
            padding: 3px 10px; border-radius: 6px; letter-spacing: .04em; white-space: nowrap;
        }

        .card-content { padding: 18px 20px; min-width: 0; overflow: hidden; }
        .room-name { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 2px; }
        .room-meta { font-size: .72rem; color: var(--neutral-400); margin-bottom: 14px; }

        .sect-title {
            font-size: .63rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .09em; color: var(--neutral-400);
            margin-bottom: 8px; display: flex; align-items: center; gap: 6px;
        }
        .sect-title i { color: var(--green-500); }
        .sect-title::after { content: ''; flex: 1; height: 1px; background: var(--green-100); }

        .info-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 8px; margin-bottom: 14px;
        }
        .info-cell {
            background: var(--green-50); border: 1px solid var(--green-100);
            border-radius: 9px; padding: 9px 12px; min-width: 0; overflow: hidden;
        }
        .info-cell-label {
            font-size: .6rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 3px;
            display: flex; align-items: center; gap: 4px;
        }
        .info-cell-label i { font-size: .65rem; color: var(--green-500); }
        .info-cell-val { font-size: .82rem; font-weight: 600; color: var(--neutral-800); line-height: 1.3; }
        .info-cell-val.highlight { color: var(--green-600); font-size: .9rem; font-weight: 700; }
        .info-cell-val.muted { color: var(--neutral-500); font-weight: 500; }

        .services-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
        .svc-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--green-100); border: 1px solid var(--green-200);
            border-radius: 7px; padding: 4px 10px;
            font-size: .7rem; font-weight: 600; color: var(--green-700);
        }

        .price-summary {
            background: var(--green-50); border: 1px solid var(--green-100);
            border-radius: 10px; padding: 12px 14px; margin-bottom: 14px;
        }
        .price-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            font-size: .72rem; color: var(--neutral-500); padding: 2px 0; gap: 8px;
        }
        .price-row span:first-child { flex: 1; min-width: 0; line-height: 1.5; }
        .price-row span:last-child { white-space: nowrap; flex-shrink: 0; font-weight: 600; }
        .price-row.total {
            border-top: 1px dashed var(--green-200);
            margin-top: 5px; padding-top: 7px;
            font-size: .85rem; font-weight: 700; color: var(--green-700);
        }
        .price-row.total span:last-child { font-family: 'Fraunces', serif; font-size: .95rem; }

        .invoice-box { border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; border: 1.5px solid; }
        .invoice-box.unpaid  { background: var(--amber-pale); border-color: var(--amber-line); }
        .invoice-box.paid    { background: var(--ok-pale);    border-color: var(--ok-line); }
        .invoice-box.overdue { background: var(--red-pale);   border-color: var(--red-line); }
        .invoice-box-title {
            font-size: .7rem; font-weight: 700; margin-bottom: 8px;
            display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
        }
        .invoice-row { display: flex; justify-content: space-between; align-items: flex-start; font-size: .72rem; color: var(--neutral-500); padding: 2px 0; gap: 8px; }
        .invoice-row span:first-child { flex: 1; min-width: 0; }
        .invoice-row strong { color: var(--neutral-800); white-space: nowrap; }

        .note-box {
            border-radius: 9px; padding: 10px 13px;
            font-size: .76rem; line-height: 1.6;
            display: flex; gap: 9px; align-items: flex-start;
            margin-bottom: 10px; border: 1px solid;
        }
        .note-box i { flex-shrink: 0; margin-top: 1px; }
        .note-rejected { background: var(--red-pale);   border-color: var(--red-line);  color: #7f1d1d; }
        .note-approved { background: var(--ok-pale);    border-color: var(--ok-line);   color: #14532d; }
        .note-warning  { background: var(--amber-pale); border-color: var(--amber-line);color: #78350f; }
        .note-info     { background: var(--green-50);   border-color: var(--green-200); color: var(--green-800); }

        .msg-box {
            border-radius: 9px; padding: 10px 13px;
            background: var(--neutral-50); border: 1px solid var(--neutral-200);
            font-size: .76rem; color: var(--neutral-500); line-height: 1.6; margin-bottom: 10px;
            display: flex; gap: 8px; align-items: flex-start;
        }

        .wl-box {
            margin-top: 2px; padding: 14px 16px; border-radius: 10px;
            border: 1.5px solid var(--blue-line);
            background: linear-gradient(135deg, var(--blue-pale), #f0f6ff);
            margin-bottom: 14px;
        }
        .wl-box-title {
            font-size: .72rem; font-weight: 700; color: var(--blue);
            margin-bottom: 10px; display: flex; align-items: center; gap: 7px; flex-wrap: wrap;
        }
        .wl-pos-pill   { background: var(--blue); color: #fff; font-size: .6rem; font-weight: 700; padding: 2px 9px; border-radius: 100px; }
        .wl-avail-pill { background: var(--green-500); color: #fff; font-size: .6rem; font-weight: 700; padding: 2px 9px; border-radius: 100px; }
        .wl-info-grid  { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-bottom: 10px; }
        .wl-cell { font-size: .68rem; color: var(--neutral-500); }
        .wl-cell strong { color: var(--neutral-800); display: block; font-size: .75rem; margin-bottom: 1px; }

        .wl-price-breakdown {
            margin-bottom: 10px; padding: 9px 12px;
            background: rgba(255,255,255,.7); border-radius: 8px; border: 1px solid var(--blue-line);
        }
        .wl-price-row { display: flex; justify-content: space-between; align-items: flex-start; font-size: .68rem; color: var(--neutral-500); padding: 2px 0; gap: 8px; }
        .wl-price-row span:first-child { flex: 1; min-width: 0; line-height: 1.5; }
        .wl-price-row span:last-child { white-space: nowrap; flex-shrink: 0; }
        .wl-price-row.total { border-top: 1px solid var(--blue-line); margin-top: 4px; padding-top: 5px; font-weight: 700; color: var(--neutral-900); font-size: .73rem; }

        .dp-progress-header { display: flex; justify-content: space-between; font-size: .67rem; color: var(--neutral-400); margin-bottom: 5px; }
        .dp-pct-label { font-size: .7rem; font-weight: 700; color: var(--blue); }
        .dp-track { height: 5px; border-radius: 100px; background: rgba(37,99,235,.12); overflow: hidden; }
        .dp-fill { height: 100%; border-radius: 100px; }
        .dp-fill.full { background: linear-gradient(90deg, var(--green-500), var(--green-400)); }
        .dp-fill.part { background: linear-gradient(90deg, var(--amber), #f97316); }

        .card-footer-actions {
            display: flex; align-items: center; justify-content: flex-end;
            gap: 8px; padding: 12px 20px;
            border-top: 1px solid var(--green-100);
            background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
            flex-wrap: wrap;
        }
        .uk-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 5px;
            padding: 7px 14px; border-radius: 9px; font-size: .72rem; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none; transition: all .18s;
            white-space: nowrap;
        }
        .uk-btn-green   { background: var(--green-500); color: #fff; box-shadow: 0 2px 10px rgba(42,179,107,.28); }
        .uk-btn-green:hover { background: var(--green-600); color: #fff; }
        .uk-btn-amber   { background: var(--amber); color: #fff; }
        .uk-btn-amber:hover { background: #d97706; color: #fff; }
        .uk-btn-outline { background: var(--green-50); border: 1px solid var(--green-200); color: var(--green-700); }
        .uk-btn-outline:hover { background: var(--green-100); border-color: var(--green-300); color: var(--green-800); }

        .expand-toggle {
            width: 100%; text-align: left;
            background: none; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 20px;
            font-size: .72rem; font-weight: 600; color: var(--neutral-500);
            border-top: 1px solid var(--green-100); transition: color .15s, background .15s;
        }
        .expand-toggle:hover { background: var(--green-50); color: var(--green-600); }
        .expand-toggle i { transition: transform .3s; flex-shrink: 0; }
        .expand-toggle.open i.bi-chevron-down { transform: rotate(180deg); }
        .expandable { display: none; padding: 0 20px 16px; }
        .expandable.open { display: block; }

        .empty-wrap {
            background: var(--neutral-0); border-radius: 16px;
            border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
            text-align: center; padding: 72px 24px;
        }
        .empty-icon-ring {
            width: 80px; height: 80px; border-radius: 50%;
            background: var(--green-100); border: 2px solid var(--green-300);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 1.8rem; color: var(--green-500);
        }
        .empty-title { font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 700; margin-bottom: 8px; color: var(--neutral-900); }
        .empty-sub { font-size: .82rem; color: var(--neutral-400); max-width: 300px; margin: 0 auto 22px; line-height: 1.65; }

        .pagi-wrap { margin-top: 24px; display: flex; justify-content: center; flex-wrap: wrap; gap: 4px; }
        .pagi-wrap .page-link { border-radius: 9px !important; border: 1px solid var(--green-200); font-size: .78rem; color: var(--neutral-600); padding: 7px 13px; }
        .pagi-wrap .page-item.active .page-link { background: var(--green-500); border-color: var(--green-500); color: #fff; }

        .legend-card {
            background: var(--neutral-0); border-radius: 16px;
            border: 1px solid var(--green-100); padding: 18px 24px; margin-top: 24px;
        }
        .legend-head { font-size: .63rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--neutral-400); margin-bottom: 12px; }
        .legend-grid { display: flex; flex-wrap: wrap; gap: 10px 24px; }
        .legend-item { display: flex; align-items: center; gap: 7px; font-size: .75rem; }
        .legend-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

        @media (max-width: 768px) {
            .page { padding: 20px 16px 64px; }
            .hero-banner { padding: 24px 22px; gap: 16px; }
            .hero-title { font-size: 1.6rem; }
            .hero-right { align-items: flex-start; }
            .card-body-grid { grid-template-columns: 100px 1fr; min-height: 160px; }
            .card-content { padding: 14px 16px; }
            .info-grid { grid-template-columns: repeat(2,1fr); gap: 7px; }
            .wl-info-grid { grid-template-columns: repeat(2,1fr); }
            .card-footer-actions { justify-content: flex-start; padding: 10px 16px; }
            .legend-grid { flex-direction: column; gap: 8px; }
        }
        @media (max-width: 480px) {
            .page { padding: 12px 12px 56px; }
            .hero-banner { flex-direction: column; align-items: flex-start; padding: 20px 18px; }
            .hero-title { font-size: 1.3rem; }
            .hero-arc { display: none; }
            .hero-stats { display: none; }
            .hero-right { width: 100%; }
            .btn-new-booking { width: 100%; justify-content: center; }
            .card-body-grid { grid-template-columns: 1fr; display: flex; flex-direction: column; }
            .room-thumb { min-height: 90px; max-height: 110px; width: 100%; }
            .card-content { padding: 12px 12px; }
            .info-grid { grid-template-columns: 1fr 1fr; gap: 6px; }
            .wl-info-grid { grid-template-columns: 1fr 1fr; }
            .card-footer-actions { flex-direction: column; align-items: stretch; padding: 10px 12px; gap: 7px; }
            .uk-btn { width: 100%; justify-content: center; padding: 10px 14px; }
        }
        @media (max-width: 360px) {
            .info-grid { grid-template-columns: 1fr; }
            .wl-info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="bg-layer"><div class="bg-grid"></div></div>

@include('partials.prospective-sidebar')

<div class="page">

    @foreach(['success','warning','error','info'] as $type)
    @if(session($type))
    @php
        $fc = match($type) { 'error' => 'red', 'success' => 'ok', 'warning' => 'amber', default => 'blue' };
        $fi = match($type) { 'success' => 'check-circle-fill', 'error' => 'exclamation-circle-fill', 'warning' => 'exclamation-triangle-fill', default => 'info-circle-fill' };
    @endphp
    <div class="flash-box" style="background:var(--{{ $fc }}-pale);border-color:var(--{{ $fc }}-line)">
        <i class="bi bi-{{ $fi }}" style="margin-top:2px;flex-shrink:0;color:var(--{{ $fc }})"></i>
        <div>{!! session($type) !!}</div>
    </div>
    @endif
    @endforeach

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-eyebrow"><span class="dot"></span> Riwayat Booking</div>
            <h1 class="hero-title">Booking <em>Saya</em></h1>
            <p class="hero-sub">Pantau semua riwayat dan status permohonan kamar.</p>
        </div>
        <div class="hero-right">
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ $counts['all'] }}</div>
                    <div class="hero-stat-label">Total</div>
                </div>
                @if($counts['pending'])
                <div class="hero-stat">
                    <div class="hero-stat-num" style="color:#fde68a">{{ $counts['pending'] }}</div>
                    <div class="hero-stat-label">Pending</div>
                </div>
                @endif
                @if($counts['approved'])
                <div class="hero-stat">
                    <div class="hero-stat-num" style="color:#86efac">{{ $counts['approved'] }}</div>
                    <div class="hero-stat-label">Disetujui</div>
                </div>
                @endif
                @if($counts['waiting_list'])
                <div class="hero-stat">
                    <div class="hero-stat-num" style="color:#93c5fd">{{ $counts['waiting_list'] }}</div>
                    <div class="hero-stat-label">Waiting List</div>
                </div>
                @endif
            </div>
            @if($canBook)
                <a href="{{ route('home') }}#kamar" class="btn-new-booking">
                    <i class="bi bi-plus-circle-fill"></i> Booking Baru
                </a>
            @else
                <span class="btn-new-booking disabled-look">
                    <i class="bi bi-lock-fill"></i> Booking Terkunci
                </span>
            @endif
        </div>
    </div>

    @if($hasActiveBooking)
    <div class="locked-banner">
        <div class="locked-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <div>
            <div class="locked-title">Booking Baru Tidak Tersedia</div>
            <div class="locked-sub">
                Anda sudah memiliki booking yang <strong>disetujui</strong> atau sedang dalam
                <strong>waiting list</strong>. Booking baru dapat dilakukan setelah proses selesai.
                @if($counts['waiting_list'] > 0)
                <br><br>
                Anda memiliki <strong>{{ $counts['waiting_list'] }} posisi waiting list</strong> aktif.
                <a href="{{ route('prospective.waiting-list.index') }}" style="color:var(--blue);font-weight:600;text-decoration:none">
                    Lihat &amp; bayar DP
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($counts['all'] > 0)
    <div class="filter-bar">
        <a href="{{ route('prospective.bookings') }}" class="filter-tab {{ !$statusFilter ? 'active' : '' }}">
            Semua <span class="ftab-count">{{ $counts['all'] }}</span>
        </a>
        @foreach([
            ['pending',      'hourglass-split', 'Pending'],
            ['approved',     'check-circle',    'Disetujui'],
            ['waiting_list', 'list-ol',         'Waiting List'],
            ['rejected',     'x-circle',        'Ditolak'],
            ['cancelled',    'slash-circle',    'Dibatalkan'],
        ] as [$key, $icon, $lbl])
        @if($counts[$key])
        <a href="{{ route('prospective.bookings') }}?status={{ $key }}"
           class="filter-tab {{ $statusFilter === $key ? 'active' : '' }}">
            <i class="bi bi-{{ $icon }}" style="font-size:.72rem"></i>
            {{ $lbl }} <span class="ftab-count">{{ $counts[$key] }}</span>
        </a>
        @endif
        @endforeach
    </div>
    @endif

    @if($bookings->isEmpty())
    <div class="empty-wrap">
        <div class="empty-icon-ring"><i class="bi bi-calendar-x"></i></div>
        <div class="empty-title">
            {{ $statusFilter ? 'Tidak Ada Booking dengan Status Ini' : 'Belum Ada Riwayat Booking' }}
        </div>
        <div class="empty-sub">
            {{ $statusFilter ? 'Coba pilih filter lain, atau ajukan booking baru.' : 'Pilih kamar yang tersedia dan ajukan booking Anda.' }}
        </div>
        @if($canBook)
        <a href="{{ route('home') }}#kamar" class="btn-new-booking" style="display:inline-flex;background:var(--green-500);color:#fff;margin-top:4px">
            <i class="bi bi-house-door-fill"></i> Lihat Kamar Tersedia
        </a>
        @endif
    </div>

    @else

    @foreach($bookings as $b)
    @php
        $statusMap = [
            'pending'      => ['label' => 'Menunggu Review', 'cls' => 'pending'],
            'approved'     => ['label' => 'Disetujui',       'cls' => 'approved'],
            'rejected'     => ['label' => 'Ditolak',         'cls' => 'rejected'],
            'waiting_list' => ['label' => 'Waiting List',    'cls' => 'waiting_list'],
            'cancelled'    => ['label' => 'Dibatalkan',      'cls' => 'cancelled'],
        ];
        $st  = $statusMap[$b->status] ?? ['label' => ucfirst($b->status), 'cls' => 'pending'];
        $wl  = $wlData[$b->id] ?? null;

        $endDate = null;
        if ($b->check_in_date && $b->duration_months) {
            $endDate = $b->check_in_date->copy()->addMonths($b->duration_months);
        }

        $pricePerMonth   = $b->room?->price ?? 0;
        $servicePerMonth = $b->service_amount ?? 0;
        $duration        = $b->duration_months ?? 1;
        $totalSewa       = ($pricePerMonth + $servicePerMonth) * $duration;

        $activeInvoice = null;
        if ($b->status === 'approved') {
            $activeInvoice = $b->invoices->whereIn('status', ['unpaid','overdue','pending_verification'])->first()
                          ?? $b->invoices->where('status','paid')->last();
        }
    @endphp

    <div class="booking-card status-{{ $st['cls'] }}">
        <div class="card-header-strip">
            <div class="card-header-left">
                <span class="booking-code-big">{{ $b->booking_code }}</span>
                <span class="type-pill {{ $b->booking_type === 'advance' ? 'tp-advance' : 'tp-regular' }}">
                    {{ $b->booking_type === 'advance' ? 'Advance' : 'Regular' }}
                </span>
                <span class="status-badge sb-{{ $st['cls'] }}">
                    <span class="status-dot"></span> {{ $st['label'] }}
                </span>
            </div>
            <div class="card-header-right">
                <div class="created-label">
                    Diajukan<br>
                    <strong style="color:var(--neutral-800)">{{ $b->created_at->format('d M Y') }}</strong><br>
                </div>
            </div>
        </div>

        <div class="card-body-grid">
            <div class="room-thumb">
                @if($b->room?->image_path)
                    <img src="{{ asset('storage/'.$b->room->image_path) }}" alt="Kamar {{ $b->room?->room_number }}">
                @else
                    <i class="bi bi-door-open"></i>
                @endif
                <div class="room-num-pill">No.{{ $b->room?->room_number ?? '?' }}</div>
            </div>

            <div class="card-content">
                <div class="room-name">{{ $b->room?->room_name ?? 'Kamar '.$b->room?->room_number }}</div>
                <div class="room-meta">
                    No. {{ $b->room?->room_number ?? '-' }}
                    @if($b->room?->floor) &middot; Lantai {{ $b->room->floor }} @endif
                    @if($b->room?->size) &middot; {{ $b->room->size }} @endif
                </div>

                <div class="sect-title"><i class="bi bi-person-fill"></i> Data Pemohon</div>
                <div class="info-grid">
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-person"></i> Nama Lengkap</div>
                        <div class="info-cell-val">{{ $b->full_name ?? '-' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-telephone"></i> No. HP / WA</div>
                        <div class="info-cell-val">{{ $b->phone ?? '-' }}</div>
                    </div>
                    @if($b->identity_number)
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-card-text"></i> No. KTP / KTM</div>
                        <div class="info-cell-val">{{ $b->identity_number }}</div>
                    </div>
                    @endif
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-envelope"></i> Email</div>
                        <div class="info-cell-val muted">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="sect-title"><i class="bi bi-calendar-check"></i> Detail Booking</div>
                <div class="info-grid">
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-calendar-event"></i> Tanggal Masuk</div>
                        <div class="info-cell-val highlight">{{ $b->check_in_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-clock-history"></i> Durasi Sewa</div>
                        <div class="info-cell-val highlight">{{ $duration }} bulan</div>
                    </div>
                    @if($endDate)
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-calendar-x"></i> Estimasi Berakhir</div>
                        <div class="info-cell-val">{{ $endDate->format('d M Y') }}</div>
                    </div>
                    @endif
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-door-closed"></i> Status Kamar</div>
                        <div class="info-cell-val">{{ ucfirst($b->room?->status ?? '-') }}</div>
                    </div>
                </div>

                @if(!empty($b->additional_services))
                <div class="sect-title"><i class="bi bi-grid"></i> Layanan Tambahan</div>
                <div class="services-wrap">
                    @foreach($b->additional_services as $key => $svc)
                    <span class="svc-chip">
                        <i class="bi bi-{{ $svc['icon'] ?? 'check-circle' }}"></i>
                        {{ $svc['label'] ?? $key }}
                        <span style="font-weight:500;opacity:.7">+Rp {{ number_format($svc['price'] ?? 0, 0, ',', '.') }}/bln</span>
                    </span>
                    @endforeach
                </div>
                @endif

                <div class="sect-title"><i class="bi bi-receipt"></i> Ringkasan Biaya</div>
                <div class="price-summary">
                    <div class="price-row">
                        <span>Sewa kamar (Rp {{ number_format($pricePerMonth,0,',','.') }} &times; {{ $duration }} bln)</span>
                        <span>Rp {{ number_format($pricePerMonth * $duration,0,',','.') }}</span>
                    </div>
                    @if($servicePerMonth > 0)
                    <div class="price-row">
                        <span>Layanan tambahan (Rp {{ number_format($servicePerMonth,0,',','.') }} &times; {{ $duration }} bln)</span>
                        <span>Rp {{ number_format($servicePerMonth * $duration,0,',','.') }}</span>
                    </div>
                    @endif
                    <div class="price-row total">
                        <span>Total Estimasi</span>
                        <span>Rp {{ number_format($totalSewa,0,',','.') }}</span>
                    </div>
                </div>

                @if($b->status === 'approved' && $activeInvoice)
                <div class="sect-title"><i class="bi bi-file-earmark-text"></i> Invoice</div>
                @php
                    $invStatus = $activeInvoice->status;
                    $invCls = match($invStatus) { 'paid' => 'paid', 'overdue' => 'overdue', default => 'unpaid' };
                    $invColor = match($invStatus) { 'paid' => 'var(--ok)', 'overdue' => 'var(--red)', default => 'var(--amber)' };
                    $invLabel = match($invStatus) { 'paid' => 'Lunas', 'overdue' => 'Jatuh Tempo', 'pending_verification' => 'Menunggu Verifikasi', default => 'Belum Dibayar' };
                @endphp
                <div class="invoice-box {{ $invCls }}">
                    <div class="invoice-box-title" style="color:{{ $invColor }}">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        {{ $activeInvoice->invoice_number }}
                        <span style="background:{{ $invColor }};color:#fff;font-size:.58rem;padding:2px 8px;border-radius:99px;font-weight:700;">
                            {{ $invLabel }}
                        </span>
                    </div>
                    <div class="invoice-row">
                        <span>Total Tagihan</span>
                        <strong>Rp {{ number_format($activeInvoice->total_amount,0,',','.') }}</strong>
                    </div>
                    @if($activeInvoice->due_date && $invStatus !== 'paid')
                    <div class="invoice-row">
                        <span>Batas Pembayaran</span>
                        <strong style="color:{{ $invStatus === 'overdue' ? 'var(--red)' : 'var(--amber)' }}">
                            {{ $activeInvoice->due_date->format('d M Y') }}
                        </strong>
                    </div>
                    @endif
                    @if($activeInvoice->period_start && $activeInvoice->period_end)
                    <div class="invoice-row">
                        <span>Periode Sewa</span>
                        <strong>{{ $activeInvoice->period_start->format('d M Y') }} – {{ $activeInvoice->period_end->format('d M Y') }}</strong>
                    </div>
                    @endif
                    @if($activeInvoice->paid_at)
                    <div class="invoice-row">
                        <span>Dibayar pada</span>
                        <strong style="color:var(--ok)">{{ $activeInvoice->paid_at->format('d M Y') }}</strong>
                    </div>
                    @endif
                </div>
                @endif

                @if($b->status === 'waiting_list' && $wl)
                <div class="sect-title"><i class="bi bi-list-ol"></i> Waiting List</div>
                <div class="wl-box">
                    <div class="wl-box-title">
                        <i class="bi bi-list-ol"></i>
                        Detail Waiting List
                        <span class="wl-pos-pill">#{{ $wl['wlEntry']->position }}</span>
                        @if($wl['wlEntry']->status === 'notified')
                        <span class="wl-avail-pill">Kamar Tersedia</span>
                        @endif
                    </div>
                    <div class="wl-info-grid">
                        <div class="wl-cell">
                            <strong>{{ $wl['wlEntry']->approved_at?->format('d M Y') ?? '-' }}</strong>
                            Masuk WL
                        </div>
                        <div class="wl-cell">
                            <strong style="color:{{ $wl['dpIsPaid'] ? 'var(--ok)' : 'var(--amber)' }}">
                                {{ $wl['dpIsPaid'] ? 'Lunas' : 'Belum Lunas' }}
                            </strong>
                            Status DP
                        </div>
                        <div class="wl-cell">
                            <strong>{{ $wl['wlEntry']->status === 'notified' ? 'Dinotifikasi' : ucfirst($wl['wlEntry']->status) }}</strong>
                            Status WL
                        </div>
                    </div>
                    <div class="wl-price-breakdown">
                        <div class="wl-price-row">
                            <span>Sewa kamar &times; {{ $wl['duration'] }} bln</span>
                            <span>Rp {{ number_format($wl['roomPrice'] * $wl['duration'],0,',','.') }}</span>
                        </div>
                        @if($wl['serviceAmount'] > 0)
                        <div class="wl-price-row">
                            <span>Layanan tambahan &times; {{ $wl['duration'] }} bln</span>
                            <span>Rp {{ number_format($wl['serviceAmount'] * $wl['duration'],0,',','.') }}</span>
                        </div>
                        @endif
                        <div class="wl-price-row total">
                            <span>Total Sewa</span>
                            <span>Rp {{ number_format($wl['totalSewa'],0,',','.') }}</span>
                        </div>
                        <div class="wl-price-row" style="margin-top:4px">
                            <span>DP 50%</span>
                            <span style="color:{{ $wl['dpIsPaid'] ? 'var(--ok)' : 'var(--amber)' }};font-weight:700">
                                Rp {{ number_format($wl['dpAmount'],0,',','.') }}
                                {{ $wl['dpIsPaid'] ? '' : '' }}
                            </span>
                        </div>
                        <div class="wl-price-row">
                            <span>Sisa Pelunasan</span>
                            <span style="color:{{ $wl['sisaIsPaid'] ? 'var(--ok)' : 'var(--neutral-600)' }};font-weight:700">
                                Rp {{ number_format($wl['sisaAmount'],0,',','.') }}
                                {{ $wl['sisaIsPaid'] ? '' : '' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="dp-progress-header">
                            <span>Progres Pembayaran DP</span>
                            <span class="dp-pct-label">{{ $wl['dpPct'] }}%</span>
                        </div>
                        <div class="dp-track">
                            <div class="dp-fill {{ $wl['dpIsPaid'] ? 'full' : 'part' }}" style="width:{{ $wl['dpPct'] }}%"></div>
                        </div>
                    </div>
                </div>
                @endif

                @if($b->admin_notes && in_array($b->status, ['rejected','cancelled','approved','waiting_list']))
                <div class="note-box {{ $b->status === 'rejected' ? 'note-rejected' : ($b->status === 'approved' ? 'note-approved' : 'note-warning') }}">
                    <i class="bi bi-chat-text-fill"></i>
                    <div><strong>Catatan Admin:</strong> {{ $b->admin_notes }}</div>
                </div>
                @endif

                @if($b->status === 'pending')
                <div class="note-box note-info">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>Permohonan Anda sedang dalam antrian review. Biasanya diproses dalam <strong>1×24 jam</strong>.</div>
                </div>
                @endif

            </div>
        </div>

        @if($b->message)
        <button class="expand-toggle" onclick="toggleExpand(this)">
            <span><i class="bi bi-chat-left-quote" style="margin-right:5px"></i>Pesan dari Pemohon</span>
            <i class="bi bi-chevron-down"></i>
        </button>
        <div class="expandable">
            <div class="msg-box">
                <i class="bi bi-chat-quote-fill" style="color:var(--neutral-400);flex-shrink:0;margin-top:1px"></i>
                <div>{{ $b->message }}</div>
            </div>
        </div>
        @endif

        <div class="card-footer-actions">
            @if($b->status === 'waiting_list' && $wl)
                <a href="{{ route('prospective.waiting-list.show', $wl['wlEntry']->id) }}" class="uk-btn uk-btn-outline">
                    <i class="bi bi-list-ol"></i> Detail WL
                </a>
            @endif
            @if($b->status === 'approved' && $activeInvoice && !in_array($activeInvoice->status, ['paid']))
            <a href="{{ route('prospective.invoices.index') }}" class="uk-btn uk-btn-amber">
                <i class="bi bi-receipt"></i> Lihat Invoice
            </a>
            @endif
            @if($b->room)
            <a href="{{ route('rooms.show', $b->room) }}" class="uk-btn uk-btn-outline">
                <i class="bi bi-eye"></i> Lihat Kamar
            </a>
            @endif
        </div>

    </div>
    @endforeach

    @if(method_exists($bookings,'links') && $bookings->hasPages())
    <div class="pagi-wrap">{{ $bookings->links() }}</div>
    @endif

    @endif

    <div class="legend-card">
        <div class="legend-head">Keterangan Status Booking</div>
        <div class="legend-grid">
            @foreach([
                ['amber',        'Menunggu Review', 'Permohonan diterima, belum direview'],
                ['green-500',    'Disetujui',       'Booking disetujui, invoice sudah diterbitkan'],
                ['blue',         'Waiting List',    'Booking advance — masuk antrian, siapkan DP'],
                ['red',          'Ditolak',         'Booking ditolak'],
                ['neutral-400',  'Dibatalkan',      'Booking telah dibatalkan'],
            ] as [$color, $label, $desc])
            <div class="legend-item">
                <span class="legend-dot" style="background:var(--{{ $color }})"></span>
                <strong style="font-size:.73rem">{{ $label }}</strong>
                <span style="color:var(--neutral-400)">— {{ $desc }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleExpand(btn) {
    btn.classList.toggle('open');
    const panel = btn.nextElementSibling;
    panel.classList.toggle('open');
}
</script>
</body>
</html>