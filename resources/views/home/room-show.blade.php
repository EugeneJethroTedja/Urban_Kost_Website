<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $room->room_name ?? 'Kamar '.$room->room_number }} — Urban Kost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
            --neutral-300: #c4d4cc;
            --neutral-400: #8fa89d;
            --neutral-500: #5f7a6e;
            --neutral-600: #3e5549;
            --neutral-700: #2d4038;
            --neutral-900: #0f1f18;
            --accent:      #f4a535;
            --accent-pale: #fffbf0;
            --accent-dark: #a16207;
            --red:         #dc2626;
            --sh-sm: 0 1px 4px rgba(10,26,18,.06);
            --sh-md: 0 4px 20px rgba(10,26,18,.09);
            --sh-lg: 0 12px 48px rgba(10,26,18,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--neutral-50);
            color: var(--neutral-900);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .site-nav {
            position: sticky; top: 0; z-index: 900;
            background: #ffffff;
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #e5e7eb;
        }
        .site-nav.elevated {
            background: rgba(255,255,255,.98);
            box-shadow: 0 2px 24px rgba(10,26,18,.08);
        }
        .nav-inner {
            display: flex; align-items: center;
            max-width: 1280px; margin: 0 auto;
            padding: 0 24px; height: 64px; gap: 24px;
        }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 0; }
        .brand-mark {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--green-800), var(--green-500));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: .9rem; flex-shrink: 0;
        }
        .brand-name {
            font-family: 'Fraunces', serif;
            font-weight: 700; font-size: 1.2rem;
            color: var(--neutral-900); letter-spacing: -.02em;
        }
        .nav-links {
            display: flex; align-items: center; gap: 2px;
            list-style: none; margin: 0; padding: 0; flex: 1;
        }
        .nav-links a {
            display: block; padding: 6px 14px;
            font-size: .875rem; font-weight: 500;
            color: var(--neutral-500); text-decoration: none;
            border-radius: 8px; transition: color .2s, background .2s;
        }
        .nav-links a:hover { color: var(--green-700); background: var(--green-100); }
        .nav-actions { display: flex; align-items: center; gap: 8px; margin-left: auto; flex-shrink: 0; }
        .btn-ghost {
            padding: 8px 18px; border-radius: 8px;
            font-size: .875rem; font-weight: 500;
            color: var(--neutral-600); background: transparent;
            border: 1px solid var(--green-300);
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; transition: all .2s;
        }
        .btn-ghost:hover { border-color: var(--green-500); color: var(--green-700); background: var(--green-50); }
        .btn-primary {
            padding: 8px 20px; border-radius: 8px;
            font-size: .875rem; font-weight: 600;
            color: #fff; background: var(--green-600);
            border: 1px solid var(--green-600);
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; transition: all .2s;
        }
        .btn-primary:hover { background: var(--green-700); border-color: var(--green-700); color: #fff; }

        .nav-hamburger {
            display: none; background: none;
            border: 1px solid var(--green-200); border-radius: 8px;
            padding: 6px 10px; cursor: pointer;
            color: var(--neutral-600); font-size: 1.1rem; margin-left: auto;
        }
        .nav-hamburger:hover { border-color: var(--green-400); color: var(--green-700); }

        .nav-drawer { display: none; position: fixed; inset: 0; z-index: 9990; }
        .nav-drawer-backdrop { position: absolute; inset: 0; background: rgba(10,26,18,.48); }
        .nav-drawer-panel {
            position: absolute; top: 0; right: 0; bottom: 0;
            width: min(320px, 85vw); background: var(--neutral-0);
            display: flex; flex-direction: column;
            box-shadow: -8px 0 48px rgba(10,26,18,.2);
            transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .nav-drawer.open .nav-drawer-panel { transform: translateX(0); }
        .nav-drawer-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 20px; border-bottom: 1px solid var(--green-100);
        }
        .nav-drawer-close {
            background: none; border: 1px solid var(--green-200);
            border-radius: 8px; padding: 6px 10px;
            cursor: pointer; font-size: 1rem; color: var(--neutral-600);
        }
        .nav-drawer-links { list-style: none; padding: 16px 0; margin: 0; flex: 1; overflow-y: auto; }
        .nav-drawer-links li a {
            display: flex; align-items: center; gap: 12px;
            padding: 13px 20px; font-size: .95rem; font-weight: 500;
            color: var(--neutral-600); text-decoration: none; transition: all .2s;
        }
        .nav-drawer-links li a:hover { background: var(--green-50); color: var(--green-700); }
        .nav-drawer-links li a i { font-size: 1rem; color: var(--green-500); }
        .nav-drawer-actions {
            padding: 16px 20px; border-top: 1px solid var(--green-100);
            display: flex; flex-direction: column; gap: 10px;
        }
        .nav-drawer-actions .btn-ghost,
        .nav-drawer-actions .btn-primary { justify-content: center; padding: 12px 20px; font-size: .9rem; }

        .breadcrumb-bar {
            background: var(--neutral-0);
            border-bottom: 1px solid var(--neutral-100);
        }
        .breadcrumb-inner {
            max-width: 1280px; margin: 0 auto; padding: 10px 24px;
            display: flex; align-items: center; gap: 5px;
        }
        .bc-link {
            font-size: .7rem; font-weight: 500; color: var(--neutral-400);
            text-decoration: none; transition: color .15s;
        }
        .bc-link:hover { color: var(--green-600); }
        .bc-sep { color: var(--neutral-300); font-size: .65rem; margin: 0 1px; }
        .bc-current { font-size: .7rem; font-weight: 600; color: var(--neutral-700); }

        .gallery-hero {
            background: var(--neutral-0);
            border-bottom: 1px solid var(--neutral-100);
            padding: 24px 0 0;
        }
        .gallery-hero-inner {
            max-width: 1280px; margin: 0 auto; padding: 0 24px;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 260px 130px;
            gap: 6px;
            border-radius: 14px;
            overflow: hidden;
        }
        .gallery-main-slot {
            grid-row: 1 / 3;
            position: relative; overflow: hidden;
            background: linear-gradient(160deg, var(--green-800), var(--green-500));
            cursor: zoom-in;
        }
        .gallery-main-slot img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .5s ease;
        }
        .gallery-main-slot:hover img { transform: scale(1.02); }
        .gallery-sub-slot {
            position: relative; overflow: hidden;
            background: linear-gradient(160deg, var(--green-700), var(--green-400));
            cursor: pointer;
        }
        .gallery-sub-slot img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .4s ease;
        }
        .gallery-sub-slot:hover img { transform: scale(1.04); filter: brightness(1.05); }
        .gallery-sub-slot.more-overlay::after {
            content: attr(data-more);
            position: absolute; inset: 0;
            background: rgba(15,31,24,.55);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-family: 'Fraunces', serif;
            font-size: 1.4rem; font-weight: 600;
        }
        .gallery-placeholder-main {
            width: 100%; height: 100%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 10px;
        }
        .gallery-placeholder-main i { font-size: 2.8rem; color: rgba(255,255,255,.25); }
        .gallery-placeholder-main span { font-size: .75rem; color: rgba(255,255,255,.35); letter-spacing: .05em; }
        .status-badge {
            position: absolute; top: 14px; left: 14px; z-index: 2;
            padding: 5px 12px; border-radius: 6px;
            font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        }
        .badge-available   { background: var(--green-600); color: #fff; }
        .badge-occupied    { background: #7c3aed; color: #fff; }
        .badge-reserved    { background: var(--accent); color: #fff; }
        .badge-maintenance { background: var(--neutral-500); color: #fff; }
        .gallery-count-badge {
            position: absolute; bottom: 14px; right: 14px; z-index: 2;
            background: rgba(15,31,24,.6); color: rgba(255,255,255,.9);
            font-size: .65rem; font-weight: 600; padding: 4px 10px; border-radius: 5px;
            display: flex; align-items: center; gap: 5px;
        }
        .gallery-single { height: 440px; }
        .gallery-nav-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 38px; height: 38px; border-radius: 50%;
            background: rgba(255,255,255,.2); backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,.25); color: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 5; font-size: 1rem; transition: background .15s;
        }
        .gallery-nav-btn:hover { background: rgba(255,255,255,.3); }
        .gallery-nav-btn.prev { left: 12px; }
        .gallery-nav-btn.next { right: 12px; }

        .page-layout {
            max-width: 1280px; margin: 0 auto;
            padding: 0 24px 80px;
            display: grid;
            grid-template-columns: 1fr 348px;
            gap: 32px;
            align-items: start;
        }

        .content-col { padding-top: 28px; min-width: 0; }

        .room-title-row {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 16px;
            margin-bottom: 12px;
        }
        .room-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.35rem, 3.5vw, 1.9rem);
            font-weight: 700; color: var(--neutral-900);
            letter-spacing: -.03em; line-height: 1.2;
        }
        .room-number-badge {
            padding: 5px 12px; background: var(--green-50);
            border: 1px solid var(--green-200); border-radius: 6px;
            font-size: .75rem; font-weight: 600; color: var(--green-700);
            white-space: nowrap; flex-shrink: 0;
        }
        .room-meta {
            display: flex; align-items: center; gap: 16px;
            flex-wrap: wrap; margin-bottom: 24px;
        }
        .meta-item {
            display: flex; align-items: center; gap: 6px;
            font-size: .8rem; color: var(--neutral-500); font-weight: 500;
        }
        .meta-item i { color: var(--green-500); font-size: .85rem; }

        .stats-strip {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1px; background: var(--neutral-200);
            border-radius: 12px; overflow: hidden;
            border: 1px solid var(--neutral-200);
            margin-bottom: 28px;
        }
        .stat-cell {
            background: var(--neutral-0); padding: 16px 18px;
            display: flex; flex-direction: column; gap: 3px;
        }
        .stat-cell-label {
            font-size: .6rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--neutral-400);
        }
        .stat-cell-val {
            font-family: 'Fraunces', serif;
            font-size: 1.05rem; font-weight: 700; color: var(--neutral-900);
            line-height: 1.2;
        }
        .stat-cell-val.price-val { color: var(--green-600); }

        .section-block { margin-bottom: 28px; }
        .section-header {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 12px; padding-bottom: 10px;
            border-bottom: 1px solid var(--neutral-100);
        }
        .section-title {
            font-size: .7rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--green-600);
        }
        .desc-body {
            font-size: .875rem; color: var(--neutral-500);
            line-height: 1.85; white-space: pre-line;
        }
        .fac-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 8px;
        }
        .fac-item {
            display: flex; align-items: center; gap: 9px;
            padding: 10px 13px;
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-radius: 8px;
            font-size: .775rem; font-weight: 600; color: var(--neutral-700);
            transition: border-color .15s, background .15s;
        }
        .fac-item:hover { border-color: var(--green-300); background: var(--green-50); }
        .fac-item i { font-size: .9rem; color: var(--green-500); flex-shrink: 0; }
        .notice-block {
            display: flex; gap: 12px; padding: 14px 16px;
            border-radius: 10px; margin-top: 20px; line-height: 1.65;
            font-size: .8rem;
        }
        .notice-info {
            background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af;
        }
        .notice-warn {
            background: var(--accent-pale); border: 1px solid rgba(244,165,53,.35);
            color: var(--accent-dark);
        }
        .notice-block i { font-size: .95rem; flex-shrink: 0; margin-top: 1px; }

        .sidebar-col { padding-top: 28px; }
        .booking-card {
            background: var(--neutral-0);
            border: 1px solid var(--neutral-200);
            border-radius: 16px;
            overflow: hidden;
            position: sticky; top: 80px;
        }
        .booking-card-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--neutral-100);
        }
        .price-row {
            display: flex; align-items: baseline; gap: 6px; margin-bottom: 2px;
        }
        .price-amount {
            font-family: 'Fraunces', serif;
            font-size: 1.75rem; font-weight: 700;
            color: var(--green-700); letter-spacing: -.03em; line-height: 1;
        }
        .price-period {
            font-size: .78rem; color: var(--neutral-400); font-weight: 500;
        }
        .price-note {
            font-size: .7rem; color: var(--neutral-400); margin-top: 4px;
        }
        .booking-card-status {
            margin-top: 12px; display: flex; align-items: center; gap: 8px;
            font-size: .78rem; font-weight: 600;
        }
        .status-indicator {
            width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
        }
        .si-available   { background: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.18); }
        .si-occupied    { background: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.15); }
        .si-reserved    { background: var(--accent); box-shadow: 0 0 0 3px rgba(244,165,53,.18); }
        .si-maintenance { background: var(--neutral-400); }

        .booking-card-body { padding: 18px 22px; }

        .tenant-block {
            background: var(--neutral-50); border: 1px solid var(--neutral-200);
            border-radius: 10px; padding: 14px 16px; margin-bottom: 16px;
        }
        .tenant-block-title {
            font-size: .6rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--neutral-400); margin-bottom: 10px;
        }
        .tenant-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 6px 0; font-size: .775rem; gap: 10px;
        }
        .tenant-row + .tenant-row { border-top: 1px solid var(--neutral-100); }
        .tenant-label { color: var(--neutral-500); font-weight: 500; flex-shrink: 0; }
        .tenant-val { font-weight: 600; color: var(--neutral-800); text-align: right; word-break: break-word; }
        .tenant-val small { display: block; font-size: .65rem; font-weight: 400; color: var(--neutral-400); }

        .tenure-bar { margin-top: 12px; }
        .tenure-bar-dates {
            display: flex; justify-content: space-between;
            font-size: .62rem; color: var(--neutral-400); margin-bottom: 5px;
        }
        .tenure-track {
            height: 5px; background: var(--neutral-200);
            border-radius: 100px; overflow: hidden;
        }
        .tenure-fill {
            height: 100%; background: var(--green-500);
            border-radius: 100px; transition: width .6s ease;
        }
        .tenure-remaining {
            font-size: .65rem; color: var(--neutral-500);
            text-align: center; margin-top: 5px; font-weight: 500;
        }

        .wl-block {
            border-radius: 8px; padding: 10px 13px; margin-bottom: 14px;
            display: flex; align-items: flex-start; gap: 9px; font-size: .76rem;
        }
        .wl-block.wl-open {
            background: var(--green-50); border: 1px solid var(--green-200);
        }
        .wl-block.wl-full {
            background: #fef2f2; border: 1px solid #fecaca;
        }
        .wl-block i { font-size: .9rem; flex-shrink: 0; margin-top: 1px; }
        .wl-block.wl-open i { color: var(--green-500); }
        .wl-block.wl-full i { color: var(--red); }
        .wl-title { font-weight: 700; margin-bottom: 1px; font-size: .75rem; }
        .wl-block.wl-open .wl-title { color: var(--green-700); }
        .wl-block.wl-full .wl-title { color: #991b1b; }
        .wl-desc { font-size: .7rem; line-height: 1.5; }
        .wl-block.wl-open .wl-desc { color: var(--green-600); }
        .wl-block.wl-full .wl-desc { color: #b91c1c; }

        .amenities-strip { margin-bottom: 16px; }
        .amenity-row {
            display: flex; align-items: center; gap: 9px;
            padding: 6px 0; font-size: .775rem; color: var(--neutral-600);
        }
        .amenity-row + .amenity-row { border-top: 1px solid var(--neutral-100); }
        .amenity-row i { color: var(--green-500); font-size: .8rem; width: 13px; flex-shrink: 0; }

        .cta-stack { display: flex; flex-direction: column; gap: 8px; }
        .btn-cta {
            width: 100%; padding: 12px 16px; border-radius: 9px;
            font-family: 'Instrument Sans', sans-serif; font-size: .85rem; font-weight: 600;
            text-align: center; text-decoration: none; cursor: pointer; border: none;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: all .2s; line-height: 1;
        }
        .btn-cta-primary { background: var(--green-600); color: #fff; }
        .btn-cta-primary:hover { background: var(--green-700); color: #fff; transform: translateY(-1px); }
        .btn-cta-secondary {
            background: var(--neutral-0); color: var(--accent-dark);
            border: 1px solid rgba(244,165,53,.5);
            flex-direction: column; gap: 2px;
        }
        .btn-cta-secondary:hover { background: var(--accent-pale); color: var(--accent-dark); }
        .btn-cta-secondary .sub-line { font-size: .62rem; font-weight: 400; opacity: .8; }
        .btn-cta-disabled {
            background: var(--neutral-100); color: var(--neutral-400);
            border: 1px solid var(--neutral-200); cursor: not-allowed;
        }
        .btn-cta-wa {
            background: transparent; color: var(--neutral-500); border: none;
            font-size: .76rem; font-weight: 500; gap: 6px;
            padding: 8px; text-decoration: none;
        }
        .btn-cta-wa:hover { color: #16a34a; }
        .btn-cta-wa i { color: #25d366; }

        .similar-section {
            max-width: 1280px; margin: 0 auto;
            padding: 48px 24px 80px;
            border-top: 1px solid var(--neutral-100);
        }
        .similar-heading {
            display: flex; align-items: baseline; gap: 10px; margin-bottom: 24px;
        }
        .similar-heading-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.1rem, 3vw, 1.35rem);
            font-weight: 700; color: var(--neutral-900); letter-spacing: -.025em;
        }
        .similar-heading-title em { font-style: italic; font-weight: 400; color: var(--green-600); }
        .similar-heading-count {
            font-size: .72rem; color: var(--neutral-400); font-weight: 500;
        }
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .sim-card {
            background: var(--neutral-0); border: 1px solid var(--neutral-200);
            border-radius: 12px; overflow: hidden; text-decoration: none;
            display: block; transition: all .2s;
        }
        .sim-card:hover {
            border-color: var(--green-300);
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(15,31,24,.08);
        }
        .sim-thumb {
            height: 140px; overflow: hidden; position: relative;
            background: linear-gradient(150deg, var(--green-800), var(--green-500));
        }
        .sim-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
        .sim-card:hover .sim-thumb img { transform: scale(1.04); }
        .sim-thumb-empty {
            width: 100%; height: 100%; display: flex; align-items: center;
            justify-content: center; color: rgba(255,255,255,.22); font-size: 1.8rem;
        }
        .sim-status {
            position: absolute; top: 10px; left: 10px;
            padding: 3px 9px; border-radius: 5px;
            font-size: .58rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        }
        .sim-body { padding: 14px 16px; }
        .sim-name {
            font-family: 'Fraunces', serif; font-size: .9rem;
            font-weight: 700; color: var(--neutral-900); margin-bottom: 5px;
        }
        .sim-meta {
            display: flex; gap: 10px; margin-bottom: 10px;
        }
        .sim-meta span {
            display: flex; align-items: center; gap: 4px;
            font-size: .7rem; color: var(--neutral-400); font-weight: 500;
        }
        .sim-meta i { font-size: .75rem; }
        .sim-price {
            font-family: 'Fraunces', serif; font-size: .95rem;
            font-weight: 700; color: var(--green-700);
        }
        .sim-price small {
            font-family: 'Instrument Sans'; font-size: .65rem;
            font-weight: 400; color: var(--neutral-400);
        }

        .alerts-wrap {
            max-width: 1280px; margin: 0 auto; padding: 12px 24px 0;
        }
        .alert-strip {
            padding: 11px 15px; border-radius: 9px; border: 1px solid;
            font-size: .82rem; display: flex; align-items: flex-start;
            gap: 9px; margin-bottom: 8px; line-height: 1.5;
        }
        .alert-ok  { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
        .alert-err { background: #fff5f5; border-color: #fecaca; color: #991b1b; }

        .lightbox {
            position: fixed; inset: 0; z-index: 9990;
            background: rgba(0,0,0,.92); display: none;
            align-items: center; justify-content: center; padding: 20px;
        }
        .lightbox.open { display: flex; }
        .lightbox-img { max-width: 100%; max-height: 88vh; border-radius: 10px; object-fit: contain; }
        .lightbox-close {
            position: absolute; top: 16px; right: 16px; width: 36px; height: 36px;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
            border-radius: 50%; color: #fff; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; z-index: 2;
        }
        .lightbox-prev, .lightbox-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
            color: #fff; cursor: pointer; display: flex; align-items: center;
            justify-content: center; font-size: 1.1rem; z-index: 2;
        }
        .lightbox-prev { left: 20px; }
        .lightbox-next { right: 20px; }

        .site-footer { background: var(--green-900); padding: 60px 0 28px; }
        .footer-inner { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 44px; padding-bottom: 44px;
            border-bottom: 1px solid rgba(255,255,255,.07); margin-bottom: 24px;
        }
        .footer-brand-name { font-family: 'Fraunces', serif; font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 10px; }
        .footer-desc { font-size: .81rem; color: rgba(255,255,255,.32); line-height: 1.75; }
        .footer-heading { font-size: .65rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.22); margin-bottom: 14px; }
        .footer-link { display: block; font-size: .82rem; color: rgba(255,255,255,.42); text-decoration: none; padding: 4px 0; transition: color .2s; }
        .footer-link:hover { color: var(--green-400); }
        .footer-copy { font-size: .73rem; color: rgba(255,255,255,.16); text-align: center; }

        @media (max-width: 1024px) {
            .page-layout { grid-template-columns: 1fr; padding: 0 24px 60px; gap: 0; }
            .sidebar-col { padding-top: 0; }
            .booking-card { position: static; border-radius: 14px; }
            .gallery-grid { grid-template-rows: 220px 110px; }
            .similar-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .similar-section { padding: 32px 24px 60px; }
            .alerts-wrap { padding: 12px 24px 0; }
            .breadcrumb-inner { padding: 10px 24px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
        }
        @media (max-width: 768px) {
            .nav-links { display: none !important; }
            .nav-actions { display: none !important; }
            .nav-hamburger { display: flex; align-items: center; gap: 6px; }
            .nav-inner { padding: 0 20px; }
            .gallery-grid { grid-template-columns: 1fr; grid-template-rows: 240px; }
            .gallery-sub-slot { display: none; }
            .gallery-main-slot { grid-row: 1; border-radius: 12px; }
            .gallery-hero { padding: 16px 0 0; }
            .gallery-hero-inner { padding: 0 20px; }
            .page-layout { padding: 0 20px 56px; }
            .content-col { padding-top: 22px; }
            .stats-strip { grid-template-columns: repeat(3, 1fr); }
            .fac-grid { grid-template-columns: repeat(2, 1fr); }
            .alerts-wrap { padding: 10px 20px 0; }
            .similar-section { padding: 28px 20px 56px; }
            .breadcrumb-inner { padding: 10px 20px; }
            .footer-grid { grid-template-columns: 1fr; gap: 22px; }
        }
        @media (max-width: 480px) {
            .nav-inner { padding: 0 16px; }
            .page-layout { padding: 0 16px 48px; }
            .gallery-hero-inner { padding: 0 16px; }
            .similar-grid { grid-template-columns: 1fr; }
            .alerts-wrap { padding: 10px 16px 0; }
            .similar-section { padding: 24px 16px 48px; }
            .breadcrumb-inner { padding: 10px 16px; }
            .stats-strip { grid-template-columns: 1fr; }
            .similar-heading { flex-direction: column; gap: 2px; }
            .footer-inner { padding: 0 16px; }
        }
    </style>
</head>
<body>

<nav class="site-nav" id="siteNav">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}">
            <div class="brand-mark"><i class="bi bi-house-heart-fill"></i></div>
            <span class="brand-name">Urban Kost</span>
        </a>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}#kamar">Kamar</a></li>
            <li><a href="{{ route('home') }}#fasilitas">Fasilitas</a></li>
            <li><a href="{{ route('home') }}#lokasi">Lokasi</a></li>
            <li><a href="{{ route('home') }}#kontak">Kontak</a></li>
        </ul>
        <div class="nav-actions">
            @auth
                <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn-primary">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            @endauth
        </div>
        <button class="nav-hamburger" id="navHamburger" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>
    </div>
</nav>

<div class="nav-drawer" id="navDrawer" role="dialog" aria-modal="true" aria-label="Menu navigasi">
    <div class="nav-drawer-backdrop" id="navBackdrop"></div>
    <div class="nav-drawer-panel">
        <div class="nav-drawer-header">
            <a class="brand" href="{{ route('home') }}">
                <div class="brand-mark"><i class="bi bi-house-heart-fill"></i></div>
                <span class="brand-name">Urban Kost</span>
            </a>
            <button class="nav-drawer-close" id="navDrawerClose" aria-label="Tutup menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <ul class="nav-drawer-links">
            <li><a href="{{ route('home') }}#kamar" class="drawer-link"><i class="bi bi-house-door"></i>Kamar</a></li>
            <li><a href="{{ route('home') }}#fasilitas" class="drawer-link"><i class="bi bi-star"></i>Fasilitas</a></li>
            <li><a href="{{ route('home') }}#lokasi" class="drawer-link"><i class="bi bi-geo-alt"></i>Lokasi</a></li>
            <li><a href="{{ route('home') }}#kontak" class="drawer-link"><i class="bi bi-telephone"></i>Kontak</a></li>
        </ul>
        <div class="nav-drawer-actions">
            @auth
                <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn-primary">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary"><i class="bi bi-person-plus"></i> Daftar Gratis</a>
            @endauth
        </div>
    </div>
</div>

@if(session('success') || session('error'))
<div class="alerts-wrap">
    @if(session('success'))
    <div class="alert-strip alert-ok">
        <i class="bi bi-check-circle-fill" style="margin-top:1px;flex-shrink:0"></i>
        <span>{!! session('success') !!}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert-strip alert-err">
        <i class="bi bi-exclamation-circle-fill" style="margin-top:1px;flex-shrink:0"></i>
        <span>{!! session('error') !!}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
</div>
@endif

@php
    $statusLabel = match($room->status) {
        'available'   => ['label' => 'Tersedia',  'badge' => 'badge-available',   'siClass' => 'si-available',   'text' => 'var(--green-700)'],
        'occupied'    => ['label' => 'Terisi',    'badge' => 'badge-occupied',    'siClass' => 'si-occupied',    'text' => '#6d28d9'],
        'reserved'    => ['label' => 'Reservasi', 'badge' => 'badge-reserved',    'siClass' => 'si-reserved',    'text' => 'var(--accent-dark)'],
        'maintenance' => ['label' => 'Perbaikan', 'badge' => 'badge-maintenance', 'siClass' => 'si-maintenance', 'text' => 'var(--neutral-500)'],
        default       => ['label' => ucfirst($room->status), 'badge' => 'badge-maintenance', 'siClass' => 'si-maintenance', 'text' => 'var(--neutral-500)'],
    };

    $currentBooking = $room->bookings->whereIn('status', ['approved','active'])->first();
    $currentTenant  = $currentBooking?->user ?? null;
    $tenantCheckIn  = $currentBooking?->check_in_date ?? null;
    $tenantDuration = $currentBooking?->duration_months ?? null;

    if ($currentBooking) {
        if (!empty($currentBooking->end_date)) {
            $tenantEndDate = \Carbon\Carbon::parse($currentBooking->end_date);
        } elseif ($tenantCheckIn && $tenantDuration) {
            $tenantEndDate = $tenantCheckIn->copy()->addMonths($tenantDuration);
        } else {
            $tenantEndDate = null;
        }
    } else {
        $tenantEndDate = null;
    }

    $progressPct = 0;
    if ($tenantCheckIn && $tenantEndDate) {
        $totalDays   = max(1, $tenantCheckIn->diffInDays($tenantEndDate));
        $elapsedDays = $tenantCheckIn->diffInDays(now());
        $progressPct = min(100, round(($elapsedDays / $totalDays) * 100));
    }
    $daysLeft   = $tenantEndDate ? ($tenantEndDate->isPast() ? 0 : (int) now()->diffInDays($tenantEndDate)) : null;
    $isOccupied = in_array($room->status, ['occupied','reserved']);
    $wlIsFull   = $isOccupied && $waitingCount > 0;

    $facIconMap = [
        'ac'          => 'bi-thermometer-sun',
        'wifi'        => 'bi-wifi',
        'kamar mandi' => 'bi-droplet-fill',
        'lemari'      => 'bi-box-seam',
        'meja'        => 'bi-lamp',
        'kursi'       => 'bi-lamp',
        'kasur'       => 'bi-moon-stars',
        'spring bed'  => 'bi-moon-stars',
        'tv'          => 'bi-tv',
        'kulkas'      => 'bi-snow',
        'parkir'      => 'bi-car-front',
        'dapur'       => 'bi-cup-hot',
        'laundry'     => 'bi-water',
    ];
    $allImages = $room->images->isNotEmpty() ? $room->images->pluck('url')->values()->all() : ($room->image_path ? [asset('storage/'.$room->image_path)] : []);
@endphp

<div class="gallery-hero">
    <div class="gallery-hero-inner">
        @if(count($allImages) >= 3)
        <div class="gallery-grid">
            <div class="gallery-main-slot" id="galleryMain">
                <span class="status-badge {{ $statusLabel['badge'] }}">{{ $statusLabel['label'] }}</span>
                <img src="{{ $allImages[0] }}" id="galleryImg" alt="{{ $room->room_name ?? 'Kamar '.$room->room_number }}" onclick="openLightbox(0)">
                @if(count($allImages) > 1)
                    <button class="gallery-nav-btn prev" onclick="galleryPrev()" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                    <button class="gallery-nav-btn next" onclick="galleryNext()" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                    <span class="gallery-count-badge" id="galleryCounter"><i class="bi bi-images"></i> <span id="galleryCountTxt">1 / {{ count($allImages) }}</span></span>
                @endif
            </div>
            <div class="gallery-sub-slot" onclick="galleryGoTo(1); openLightbox(1)">
                <img src="{{ $allImages[1] }}" alt="Foto 2">
            </div>
            <div class="gallery-sub-slot {{ count($allImages) > 3 ? 'more-overlay' : '' }}"
                 @if(count($allImages) > 3) data-more="+{{ count($allImages) - 3 }} foto" @endif
                 onclick="openLightbox(2)">
                <img src="{{ $allImages[2] }}" alt="Foto 3">
            </div>
        </div>
        @elseif(count($allImages) >= 1)
        <div class="gallery-main-slot gallery-single" style="border-radius:14px;overflow:hidden" id="galleryMain">
            <span class="status-badge {{ $statusLabel['badge'] }}">{{ $statusLabel['label'] }}</span>
            <img src="{{ $allImages[0] }}" id="galleryImg" alt="{{ $room->room_name ?? 'Kamar '.$room->room_number }}" onclick="openLightbox(0)" style="height:100%;object-fit:cover">
            @if(count($allImages) > 1)
                <button class="gallery-nav-btn prev" onclick="galleryPrev()" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <button class="gallery-nav-btn next" onclick="galleryNext()" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                <span class="gallery-count-badge" id="galleryCounter"><i class="bi bi-images"></i> <span id="galleryCountTxt">1 / {{ count($allImages) }}</span></span>
            @endif
        </div>
        @else
        <div class="gallery-main-slot gallery-single" style="border-radius:14px;overflow:hidden">
            <span class="status-badge {{ $statusLabel['badge'] }}">{{ $statusLabel['label'] }}</span>
            <div class="gallery-placeholder-main">
                <i class="bi bi-house-door-fill"></i>
                <span>Foto belum tersedia</span>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="page-layout">

    <div class="content-col">

        <div class="room-title-row">
            <h1 class="room-title">{{ $room->room_name ?? 'Kamar '.$room->room_number }}</h1>
            <span class="room-number-badge">No. {{ $room->room_number }}</span>
        </div>

        <div class="room-meta">
            @if($room->floor)
                <span class="meta-item"><i class="bi bi-building"></i> Lantai {{ $room->floor }}</span>
            @endif
            @if($room->size)
                <span class="meta-item"><i class="bi bi-arrows-fullscreen"></i> {{ $room->size }}</span>
            @endif
            <span class="meta-item" style="color:{{ $statusLabel['text'] }}">
                <i class="bi bi-circle-fill" style="font-size:.4rem"></i> {{ $statusLabel['label'] }}
            </span>
        </div>

        <div class="stats-strip">
            <div class="stat-cell">
                <span class="stat-cell-label">Harga</span>
                <span class="stat-cell-val price-val">{{ $room->formatted_price }}</span>
            </div>
            <div class="stat-cell">
                <span class="stat-cell-label">Lantai</span>
                <span class="stat-cell-val">{{ $room->floor ? 'Lantai '.$room->floor : '—' }}</span>
            </div>
            <div class="stat-cell">
                <span class="stat-cell-label">Ukuran</span>
                <span class="stat-cell-val">{{ $room->size ?: '—' }}</span>
            </div>
        </div>

        @if($room->description)
        <div class="section-block">
            <div class="section-header">
                <span class="section-title">Deskripsi</span>
            </div>
            <p class="desc-body">{{ $room->description }}</p>
        </div>
        @endif

        @if($room->facilities && count($room->facilities) > 0)
        <div class="section-block">
            <div class="section-header">
                <span class="section-title">Fasilitas Kamar</span>
            </div>
            <div class="fac-grid">
                @foreach($room->facilities as $fac)
                    @php
                        $facLower = strtolower($fac);
                        $icon = 'bi-check2-circle';
                        foreach ($facIconMap as $key => $ico) {
                            if (str_contains($facLower, $key)) { $icon = $ico; break; }
                        }
                    @endphp
                    <div class="fac-item"><i class="bi {{ $icon }}"></i>{{ $fac }}</div>
                @endforeach
            </div>
        </div>
        @endif

        @if($isOccupied)
        <div class="notice-block {{ $wlIsFull ? 'notice-warn' : 'notice-info' }}">
            <i class="bi bi-{{ $wlIsFull ? 'exclamation-circle-fill' : 'info-circle-fill' }}"></i>
            <div>
                <strong>Kamar ini sedang {{ $room->status === 'reserved' ? 'dipesan' : 'ditempati' }}.</strong>
                @if(!$wlIsFull)
                    Anda masih bisa melakukan <em>Advance Booking</em> untuk masuk ke Waiting List.
                @else
                    Waiting list sudah penuh. Silakan cek kamar lain yang tersedia.
                @endif
            </div>
        </div>
        @endif

    </div>

    <div class="sidebar-col">
        <div class="booking-card">

            <div class="booking-card-header">
                <div class="price-row">
                    <span class="price-amount">{{ $room->formatted_price }}</span>
                    <span class="price-period">/ bulan</span>
                </div>
                <p class="price-note">Air &amp; listrik sudah termasuk</p>
                <div class="booking-card-status" style="color:{{ $statusLabel['text'] }}">
                    <span class="status-indicator {{ $statusLabel['siClass'] }}"></span>
                    {{ $statusLabel['label'] }}
                </div>
            </div>

            <div class="booking-card-body">

                @if($isOccupied && $currentTenant)
                <div class="tenant-block">
                    <div class="tenant-block-title">Penghuni Saat Ini</div>
                    <div class="tenant-row">
                        <span class="tenant-label">Nama</span>
                        <span class="tenant-val">{{ $currentTenant->name }}</span>
                    </div>
                    @if($tenantCheckIn)
                    <div class="tenant-row">
                        <span class="tenant-label">Mulai</span>
                        <span class="tenant-val">
                            {{ $tenantCheckIn->format('d M Y') }}
                            <small>{{ $tenantCheckIn->diffForHumans() }}</small>
                        </span>
                    </div>
                    @endif
                    @if($tenantDuration)
                    <div class="tenant-row">
                        <span class="tenant-label">Durasi</span>
                        <span class="tenant-val">{{ $tenantDuration }} bulan</span>
                    </div>
                    @endif
                    @if($tenantEndDate)
                    <div class="tenant-row">
                        <span class="tenant-label">Berakhir</span>
                        <span class="tenant-val" style="color:{{ $tenantEndDate->isPast() ? 'var(--red)' : ($daysLeft <= 30 ? 'var(--accent-dark)' : 'var(--green-700)') }}">
                            {{ $tenantEndDate->format('d M Y') }}
                        </span>
                    </div>
                    <div class="tenure-bar">
                        <div class="tenure-bar-dates">
                            <span>{{ $tenantCheckIn->format("d M 'y") }}</span>
                            <span>{{ $tenantEndDate->format("d M 'y") }}</span>
                        </div>
                        <div class="tenure-track">
                            <div class="tenure-fill" style="width:{{ $progressPct }}%"></div>
                        </div>
                        @if(!$tenantEndDate->isPast())
                        <div class="tenure-remaining">Sisa <strong>{{ $daysLeft }} hari</strong> ({{ round($daysLeft/30, 1) }} bln)</div>
                        @else
                        <div class="tenure-remaining" style="color:var(--red)">Masa sewa telah berakhir</div>
                        @endif
                    </div>
                    @endif
                </div>

                @if($wlIsFull)
                <div class="wl-block wl-full">
                    <i class="bi bi-lock-fill"></i>
                    <div>
                        <div class="wl-title">Waiting List Penuh (1/1)</div>
                        <div class="wl-desc">Sudah ada yang mendaftar. Tidak bisa mendaftar lagi.</div>
                    </div>
                </div>
                @else
                <div class="wl-block wl-open">
                    <i class="bi bi-calendar-plus-fill"></i>
                    <div>
                        <div class="wl-title">Waiting List Kosong (0/1)</div>
                        <div class="wl-desc">
                            Belum ada pendaftar.@if($tenantEndDate && !$tenantEndDate->isPast()) Kamar tersedia mulai <strong>{{ $tenantEndDate->format('d M Y') }}</strong>.@endif
                        </div>
                    </div>
                </div>
                @endif

                @elseif($isOccupied && !$currentTenant)
                <div class="notice-block notice-warn" style="margin-bottom:14px">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Kamar sedang ditempati. Info penghuni tidak tersedia.</span>
                </div>
                @endif

                <div class="amenities-strip">
                    <div class="amenity-row"><i class="bi bi-check-circle-fill"></i> Air &amp; Listrik inklusif</div>
                    <div class="amenity-row"><i class="bi bi-wifi"></i> WiFi 100 Mbps</div>
                    <div class="amenity-row"><i class="bi bi-shield-check"></i> Keamanan 24 jam</div>
                    <div class="amenity-row"><i class="bi bi-car-front"></i> Parkir motor &amp; mobil</div>
                    <div class="amenity-row"><i class="bi bi-credit-card"></i> Pembayaran via Midtrans</div>
                </div>

                <div class="cta-stack">
                    @if($room->status === 'available')
                        <a href="{{ route('booking.create', $room->id) }}" class="btn-cta btn-cta-primary">
                            <i class="bi bi-calendar-check-fill"></i> Booking Sekarang
                        </a>
                    @elseif($isOccupied && !$wlIsFull)
                        <a href="{{ route('booking.create', $room->id) }}" class="btn-cta btn-cta-secondary">
                            <span style="display:flex;align-items:center;gap:7px"><i class="bi bi-calendar-plus-fill"></i> Advance Booking</span>
                            @if($tenantEndDate && !$tenantEndDate->isPast())
                                <span class="sub-line">mulai {{ $tenantEndDate->format('d M Y') }}</span>
                            @endif
                        </a>
                    @elseif($isOccupied && $wlIsFull)
                        <div class="btn-cta btn-cta-disabled">
                            <i class="bi bi-lock-fill"></i> Waiting List Penuh
                        </div>
                    @else
                        <div class="btn-cta btn-cta-disabled">
                            <i class="bi bi-tools"></i> Sedang Perbaikan
                        </div>
                    @endif

                    <a href="https://wa.me/6281229302431?text=Halo,%20saya%20tertarik%20dengan%20Kamar%20No.{{ $room->room_number }}%20di%20Urban%20Kost"
                       target="_blank" class="btn-cta btn-cta-wa">
                        <i class="bi bi-whatsapp"></i> Tanya via WhatsApp
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

@if($similarRooms->isNotEmpty())
<div class="similar-section">
    <div class="similar-heading">
        <h2 class="similar-heading-title">Kamar <em>Lainnya</em></h2>
        <span class="similar-heading-count">{{ $similarRooms->count() }} kamar tersedia</span>
    </div>
    <div class="similar-grid">
        @foreach($similarRooms as $sim)
        @php
            $simBadge = match($sim->status) {
                'available' => ['class' => 'badge-available', 'label' => 'Tersedia'],
                'occupied', 'reserved' => ['class' => 'badge-occupied', 'label' => 'Terisi'],
                default => ['class' => 'badge-maintenance', 'label' => 'Perbaikan'],
            };
        @endphp
        <a href="{{ route('rooms.show', $sim->id) }}" class="sim-card">
            <div class="sim-thumb">
                @if($sim->primaryImageUrl)
                    <img src="{{ $sim->primaryImageUrl }}" alt="{{ $sim->room_name }}">
                @else
                    <div class="sim-thumb-empty"><i class="bi bi-house-door-fill"></i></div>
                @endif
                <span class="sim-status {{ $simBadge['class'] }}">{{ $simBadge['label'] }}</span>
            </div>
            <div class="sim-body">
                <div class="sim-name">{{ $sim->room_name ?? 'Kamar '.$sim->room_number }}</div>
                <div class="sim-meta">
                    @if($sim->size)<span><i class="bi bi-arrows-fullscreen"></i>{{ $sim->size }}</span>@endif
                    @if($sim->floor)<span><i class="bi bi-building"></i>Lantai {{ $sim->floor }}</span>@endif
                </div>
                <div class="sim-price">{{ $sim->formatted_price }}<small>/bulan</small></div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@if(count($allImages) > 0)
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
    <button class="lightbox-prev" onclick="event.stopPropagation(); lightboxNav(-1)" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
    <img class="lightbox-img" id="lightboxImg" src="{{ $allImages[0] }}" alt="{{ $room->room_name ?? 'Kamar' }}" onclick="event.stopPropagation()">
    <button class="lightbox-next" onclick="event.stopPropagation(); lightboxNav(1)" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
</div>
@endif

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">Urban Kost</div>
                <p class="footer-desc">Hunian dengan suasana asri dan sistem manajemen digital terpadu untuk pengalaman hidup yang lebih nyaman di Yogyakarta.</p>
            </div>
            <div>
                <div class="footer-heading">Navigasi</div>
                <a class="footer-link" href="{{ route('home') }}#kamar">Kamar</a>
                <a class="footer-link" href="{{ route('home') }}#fasilitas">Fasilitas</a>
                <a class="footer-link" href="{{ route('home') }}#lokasi">Lokasi</a>
                <a class="footer-link" href="{{ route('home') }}#kontak">Kontak</a>
            </div>
            <div>
                <div class="footer-heading">Akun</div>
                @guest
                <a class="footer-link" href="{{ route('login') }}">Masuk</a>
                <a class="footer-link" href="{{ route('register') }}">Daftar</a>
                @else
                <a class="footer-link" href="{{ route(auth()->user()->getDashboardRoute()) }}">Dashboard</a>
                @endguest
            </div>
            <div>
                <div class="footer-heading">Alamat</div>
                <p style="font-size:.8rem;color:rgba(255,255,255,.32);line-height:1.8;margin:0">
                    Jln. Ontoseno, RT.3/RW.13, Sokowaten, Plumbon, Banguntapan, Bantul, Yogyakarta, Daerah Istimewa Yogyakarta 55198<br>0812-2930-2431
                </p>
            </div>
        </div>
        <p class="footer-copy">&copy; {{ date('Y') }} Urban Kost Yogyakarta</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('scroll', () => {
    document.getElementById('siteNav').classList.toggle('elevated', window.scrollY > 30);
}, { passive: true });

(function() {
    const hamburger = document.getElementById('navHamburger');
    const drawer    = document.getElementById('navDrawer');
    const backdrop  = document.getElementById('navBackdrop');
    const closeBtn  = document.getElementById('navDrawerClose');
    const links     = document.querySelectorAll('.drawer-link');
    function openDrawer()  { drawer.style.display = 'block'; requestAnimationFrame(() => drawer.classList.add('open')); document.body.style.overflow = 'hidden'; }
    function closeDrawer() { drawer.classList.remove('open'); document.body.style.overflow = ''; setTimeout(() => { drawer.style.display = 'none'; }, 300); }
    hamburger?.addEventListener('click', openDrawer);
    backdrop?.addEventListener('click', closeDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    links.forEach(l => l.addEventListener('click', closeDrawer));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
})();

const galleryImages = @json($allImages);
let galleryCurrent = 0;
let lightboxCurrent = 0;

function galleryGoTo(idx) {
    if (!galleryImages.length) return;
    const img = document.getElementById('galleryImg');
    const txt = document.getElementById('galleryCountTxt');
    if (img) {
        img.style.opacity = '0';
        setTimeout(() => { img.src = galleryImages[idx]; img.style.opacity = '1'; }, 160);
    }
    if (txt) txt.textContent = (idx + 1) + ' / ' + galleryImages.length;
    galleryCurrent = idx;
}
function galleryNext() { galleryGoTo((galleryCurrent + 1) % galleryImages.length); }
function galleryPrev() { galleryGoTo((galleryCurrent - 1 + galleryImages.length) % galleryImages.length); }

const gMain = document.getElementById('galleryMain');
if (gMain) {
    let sx = 0;
    gMain.addEventListener('touchstart', e => { sx = e.touches[0].clientX; }, { passive: true });
    gMain.addEventListener('touchend', e => {
        const dx = sx - e.changedTouches[0].clientX;
        if (Math.abs(dx) > 40) dx > 0 ? galleryNext() : galleryPrev();
    }, { passive: true });
}

function openLightbox(idx) {
    lightboxCurrent = idx !== undefined ? idx : galleryCurrent;
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightboxImg');
    if (lb && lbImg) {
        lbImg.src = galleryImages[lightboxCurrent];
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
}
function closeLightbox() {
    const lb = document.getElementById('lightbox');
    if (lb) { lb.classList.remove('open'); document.body.style.overflow = ''; }
}
function lightboxNav(dir) {
    lightboxCurrent = (lightboxCurrent + dir + galleryImages.length) % galleryImages.length;
    const lbImg = document.getElementById('lightboxImg');
    if (lbImg) lbImg.src = galleryImages[lightboxCurrent];
}
document.addEventListener('keydown', e => {
    const lb = document.getElementById('lightbox');
    const isOpen = lb && lb.classList.contains('open');
    if (e.key === 'Escape') { if (isOpen) closeLightbox(); }
    if (e.key === 'ArrowRight') isOpen ? lightboxNav(1)  : galleryNext();
    if (e.key === 'ArrowLeft')  isOpen ? lightboxNav(-1) : galleryPrev();
});
</script>
</body>
</html>