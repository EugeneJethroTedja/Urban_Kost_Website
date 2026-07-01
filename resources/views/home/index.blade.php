<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Urban Kost — Hunian Premium Yogyakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --g50:  #f0faf4;
            --g100: #dcf5e7;
            --g200: #b8eacf;
            --g300: #86d9ae;
            --g400: #4ec98a;
            --g500: #2ab36b;
            --g600: #1d9458;
            --g700: #167745;
            --g800: #135a37;
            --g900: #0e3f27;
            --n0:   #ffffff;
            --n50:  #f4f8f6;
            --n100: #eaf2ed;
            --n200: #d4e6db;
            --n300: #a8c9b5;
            --n400: #6b9980;
            --n500: #4a7260;
            --n600: #2e4f3e;
            --n700: #1e3328;
            --n900: #0a1a12;
            --accent:      #f4a535;
            --accent-pale: #fffbf0;
            --blue:        #2563eb;
            --blue-pale:   #eff6ff;
            --red:         #dc2626;
            --red-pale:    #fef2f2;
            --amber:       #f59e0b;
            --amber-pale:  #fffbeb;
            --section-y: 88px;
            --sh-sm: 0 1px 4px rgba(10,26,18,.06);
            --sh-md: 0 4px 20px rgba(10,26,18,.09);
            --sh-lg: 0 12px 48px rgba(10,26,18,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: var(--g50);
            color: var(--n900);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--g50); }
        ::-webkit-scrollbar-thumb { background: var(--g400); border-radius: 3px; }

        .page-bg {
            position: fixed; inset: 0; z-index: -1;
            background: linear-gradient(160deg, var(--g50) 0%, #eef8f3 40%, var(--g100) 100%);
        }
        .page-bg::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(circle at 15% 15%, rgba(42,179,107,.09) 0%, transparent 50%),
                radial-gradient(circle at 85% 85%, rgba(22,119,69,.06) 0%, transparent 50%),
                radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
        }
        .page-bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(42,179,107,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(42,179,107,.035) 1px, transparent 1px);
            background-size: 64px 64px;
        }

        .site-nav {
            position: sticky;
            top: 0;
            z-index: 900;
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
            background: linear-gradient(135deg, var(--g800), var(--g500));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: .9rem; flex-shrink: 0;
        }
        .brand-name {
            font-family: 'Fraunces', serif;
            font-weight: 700; font-size: 1.2rem;
            color: var(--n900); letter-spacing: -.02em;
        }
        .nav-links {
            display: flex; align-items: center; gap: 2px;
            list-style: none; margin: 0; padding: 0; flex: 1;
        }
        .nav-links a {
            display: block; padding: 6px 14px;
            font-size: .875rem; font-weight: 500;
            color: var(--n500); text-decoration: none;
            border-radius: 8px; transition: color .2s, background .2s;
        }
        .nav-links a:hover { color: var(--g700); background: var(--g100); }
        .nav-actions { display: flex; align-items: center; gap: 8px; margin-left: auto; flex-shrink: 0; }
        .btn-ghost {
            padding: 8px 18px; border-radius: 8px;
            font-size: .875rem; font-weight: 500;
            color: var(--n600); background: transparent;
            border: 1px solid var(--g300);
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; transition: all .2s;
        }
        .btn-ghost:hover { border-color: var(--g500); color: var(--g700); background: var(--g50); }
        .btn-primary {
            padding: 8px 20px; border-radius: 8px;
            font-size: .875rem; font-weight: 600;
            color: #fff; background: var(--g600);
            border: 1px solid var(--g600);
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; transition: all .2s;
        }
        .btn-primary:hover { background: var(--g700); border-color: var(--g700); color: #fff; }

        .nav-hamburger {
            display: none; background: none;
            border: 1px solid var(--g200); border-radius: 8px;
            padding: 6px 10px; cursor: pointer;
            color: var(--n600); font-size: 1.1rem; margin-left: auto;
        }
        .nav-hamburger:hover { border-color: var(--g400); color: var(--g700); }

        .nav-drawer { display: none; position: fixed; inset: 0; z-index: 9990; }
        .nav-drawer-backdrop { position: absolute; inset: 0; background: rgba(10,26,18,.48); }
        .nav-drawer-panel {
            position: absolute; top: 0; right: 0; bottom: 0;
            width: min(320px, 85vw); background: var(--n0);
            display: flex; flex-direction: column;
            box-shadow: -8px 0 48px rgba(10,26,18,.2);
            transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .nav-drawer.open .nav-drawer-panel { transform: translateX(0); }
        .nav-drawer-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 20px; border-bottom: 1px solid var(--g100);
        }
        .nav-drawer-close {
            background: none; border: 1px solid var(--g200);
            border-radius: 8px; padding: 6px 10px;
            cursor: pointer; font-size: 1rem; color: var(--n600);
        }
        .nav-drawer-links { list-style: none; padding: 16px 0; margin: 0; flex: 1; overflow-y: auto; }
        .nav-drawer-links li a {
            display: flex; align-items: center; gap: 12px;
            padding: 13px 20px; font-size: .95rem; font-weight: 500;
            color: var(--n600); text-decoration: none; transition: all .2s;
        }
        .nav-drawer-links li a:hover { background: var(--g50); color: var(--g700); }
        .nav-drawer-links li a i { font-size: 1rem; color: var(--g500); }
        .nav-drawer-actions {
            padding: 16px 20px; border-top: 1px solid var(--g100);
            display: flex; flex-direction: column; gap: 10px;
        }
        .nav-drawer-actions .btn-ghost,
        .nav-drawer-actions .btn-primary { justify-content: center; padding: 12px 20px; font-size: .9rem; }

        .hero {
            min-height: calc(100vh - 64px);
            display: flex; align-items: center;
            position: relative; overflow: hidden; padding: 80px 0;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(circle at 75% 35%, rgba(78,201,138,.11) 0%, transparent 55%),
                radial-gradient(circle at 10% 80%, rgba(42,179,107,.06) 0%, transparent 40%);
            pointer-events: none;
        }
        .hero-arc {
            position: absolute; right: -160px; top: -160px;
            width: 560px; height: 560px; border-radius: 50%;
            border: 56px solid rgba(134,217,174,.1); pointer-events: none;
        }
        .hero-arc-2 {
            position: absolute; right: -60px; top: -60px;
            width: 340px; height: 340px; border-radius: 50%;
            border: 1px solid rgba(78,201,138,.14); pointer-events: none;
        }
        .hero-container {
            max-width: 1280px; margin: 0 auto; padding: 0 24px;
            display: grid; grid-template-columns: 1fr 400px;
            gap: 64px; align-items: center;
            position: relative; z-index: 1; width: 100%;
        }
        .hero-tag {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.75); border: 1px solid var(--g300);
            padding: 5px 14px; border-radius: 100px; margin-bottom: 26px;
            animation: fadeUp .6s ease both;
        }
        .hero-tag .dot { width: 6px; height: 6px; background: var(--g500); border-radius: 50%; flex-shrink: 0; }
        .hero-tag span { font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: var(--g800); }

        .hero-title {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: clamp(2rem, 5vw, 3.6rem);
            letter-spacing: -.025em; line-height: 1.1;
            margin-bottom: 22px;
            animation: fadeUp .6s ease .08s both;
        }
        .hero-title em { font-style: italic; font-weight: 400; color: var(--g600); }
        .hero-body {
            font-size: 1rem; color: var(--n500);
            max-width: 480px; line-height: 1.75; margin-bottom: 36px;
            animation: fadeUp .6s ease .16s both;
        }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; animation: fadeUp .6s ease .24s both; }
        .btn-hero-primary {
            padding: 13px 28px; background: var(--g600); color: #fff;
            border-radius: 10px; font-size: .9rem; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            transition: all .25s; box-shadow: 0 4px 16px rgba(22,119,69,.3);
        }
        .btn-hero-primary:hover { background: var(--g700); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 28px rgba(22,119,69,.38); }
        .btn-hero-secondary {
            padding: 13px 28px; background: var(--n0); color: var(--n600);
            border: 1px solid var(--g300); border-radius: 10px;
            font-size: .9rem; font-weight: 500;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            transition: all .25s;
        }
        .btn-hero-secondary:hover { border-color: var(--g500); color: var(--g700); background: var(--g50); }

        .hero-stats {
            display: flex; gap: 0; margin-top: 48px; padding-top: 32px;
            border-top: 1px solid var(--g200);
            animation: fadeUp .6s ease .32s both;
        }
        .hero-stat { flex: 1; padding-right: 20px; min-width: 0; }
        .hero-stat + .hero-stat { padding-left: 20px; border-left: 1px solid var(--g200); }
        .hero-stat:last-child { padding-right: 0; }
        .stat-num {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 700;
            color: var(--g700); line-height: 1; display: block;
        }
        .stat-lbl {
            font-size: .63rem; font-weight: 600; letter-spacing: .08em;
            text-transform: uppercase; color: var(--n400); margin-top: 5px; display: block;
        }

        .hero-panel-scene { position: relative; height: 380px; animation: fadeUp .6s ease .16s both; }
        .uk2-stack { position: relative; width: 100%; height: 340px; }
        .uk2-card {
            position: absolute; inset: 0; border-radius: 20px; overflow: hidden;
            transition: all .65s cubic-bezier(.4,0,.2,1); transform-origin: center bottom;
        }
        .uk2-active { z-index:10; transform:translateY(0) scale(1);     opacity:1;   box-shadow: 0 32px 80px rgba(10,26,18,.22); }
        .uk2-prev   { z-index:5;  transform:translateY(14px) scale(.96); opacity:.5;  box-shadow: 0 16px 40px rgba(10,26,18,.1); }
        .uk2-behind { z-index:1;  transform:translateY(26px) scale(.92); opacity:.25; }
        .uk2-hidden { z-index:0;  transform:translateY(40px) scale(.88); opacity:0; }
        .sc-0 { background: linear-gradient(145deg, var(--g900), var(--g600)); }
        .sc-1 { background: linear-gradient(145deg, var(--g800), var(--g400)); }
        .sc-2 { background: linear-gradient(145deg, var(--g800), var(--g500)); }
        .sc-3 { background: linear-gradient(145deg, var(--g700), var(--g300)); }
        .sc-4 { background: linear-gradient(145deg, var(--g900), var(--g700)); }
        .sc-5 { background: linear-gradient(145deg, var(--g800), var(--g600)); }
        .uk2-photo {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; object-position: center; display: block;
            transition: transform .8s ease;
        }
        .uk2-active .uk2-photo { transform: scale(1.03); }
        .uk2-photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,.1) 0%, rgba(0,0,0,.06) 40%, rgba(0,0,0,.58) 100%);
        }
        .uk2-pattern {
            position: absolute; inset: 0;
            background: radial-gradient(circle at 30% 70%, rgba(255,255,255,.07) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255,255,255,.05) 0%, transparent 40%);
            pointer-events: none;
        }
        .uk2-shape { position: absolute; border-radius: 50%; border: 1px solid rgba(255,255,255,.12); pointer-events: none; }
        .uk2-sh1 { width:180px; height:180px; right:-40px; top:-40px; }
        .uk2-sh2 { width:100px; height:100px; right:20px; top:20px; }
        .uk2-sh3 { width:240px; height:240px; left:-60px; bottom:-60px; border-width:2px; border-color:rgba(255,255,255,.06); }
        .uk2-icon-wrap {
            position: absolute; right:28px; top:50%; transform:translateY(-50%);
            width:72px; height:72px; border-radius:18px;
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
            display: flex; align-items: center; justify-content: center;
            font-size:2rem; backdrop-filter:blur(4px);
        }
        .uk2-progress { position:absolute; top:0; left:0; right:0; height:3px; background:rgba(255,255,255,.1); z-index:20; }
        .uk2-progress-bar { height:100%; background:rgba(255,255,255,.55); border-radius:0 2px 2px 0; width:0%; transition:width .1s linear; }
        .uk2-content { position:absolute; bottom:0; left:0; right:0; padding:26px 22px 20px; z-index:10; }
        .uk2-badge {
            display:inline-flex; align-items:center; gap:5px;
            background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2);
            padding:3px 10px; border-radius:100px;
            font-size:.6rem; font-weight:600; letter-spacing:.08em; text-transform:uppercase;
            color:#fff; margin-bottom:7px; backdrop-filter:blur(4px);
        }
        .uk2-bdot { width:4px; height:4px; border-radius:50%; background:var(--g300); flex-shrink:0; }
        .uk2-title {
            font-family:'Fraunces',serif; font-size:1rem; font-weight:700;
            color:#fff; line-height:1.25; margin-bottom:3px;
            text-shadow: 0 1px 4px rgba(0,0,0,.3);
        }
        .uk2-sub { font-size:.67rem; color:rgba(255,255,255,.72); text-shadow: 0 1px 3px rgba(0,0,0,.3); }
        .uk2-nav {
            position:absolute; bottom:-32px; left:0; right:0;
            display:flex; align-items:center; justify-content:space-between;
        }
        .uk2-dots { display:flex; gap:6px; align-items:center; }
        .uk2-dot {
            height:5px; width:5px; border-radius:100px;
            background:rgba(22,119,69,.28); border:none; padding:0;
            cursor:pointer; transition:all .3s;
        }
        .uk2-dot.uk2-dot-active { width:20px; background:var(--g600); }
        .uk2-arrows { display:flex; gap:6px; }
        .uk2-arw {
            width:32px; height:32px; border-radius:50%;
            background:var(--n0); border:1px solid var(--g200);
            color:var(--n600); font-size:1rem;
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; transition:all .2s;
            box-shadow: var(--sh-sm);
        }
        .uk2-arw:hover { border-color:var(--g500); color:var(--g700); transform:scale(1.05); }

        .float-chip {
            position:absolute; background:var(--n0); border-radius:12px;
            padding:10px 14px; box-shadow:0 8px 32px rgba(10,26,18,.1);
            border:1px solid var(--g200); z-index:20;
            animation: floatChip 3s ease-in-out infinite;
        }
        .float-chip-1 { top:20px; left:-18px; animation-delay:0s; }
        .float-chip-2 { bottom:54px; left:-22px; animation-delay:1.5s; }
        @keyframes floatChip { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-6px)} }
        .fchip-num { font-family:'Fraunces',serif; font-size:1.15rem; font-weight:700; color:var(--g600); line-height:1; }
        .fchip-lbl { font-size:.6rem; font-weight:600; letter-spacing:.07em; text-transform:uppercase; color:var(--n400); margin-top:3px; }

        .alerts-wrap { max-width: 1280px; margin: 0 auto; padding: 20px 24px 0; }
        .alert {
            padding: 12px 16px; border-radius: 10px; border: 1px solid;
            font-size: .875rem; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;
        }
        .alert-success { background: var(--g50); border-color: var(--g300); color: var(--g800); }
        .alert-danger  { background: var(--red-pale); border-color: #fecaca; color: #991b1b; }
        .alert-info    { background: var(--blue-pale); border-color: #bae6fd; color: #075985; }
        .alert-warning { background: var(--amber-pale); border-color: #fde68a; color: #92400e; }
        .btn-close { margin-left: auto; flex-shrink: 0; }

        .section { padding: var(--section-y) 0; }
        .section-white { background: rgba(255,255,255,.7); backdrop-filter: blur(8px); }
        .container-main { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        .section-header { margin-bottom: 48px; }
        .section-header.center { text-align: center; }
        .section-header.center .section-sub { margin: 0 auto; }
        .section-label {
            font-size: .65rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            color: var(--g600); display: block; margin-bottom: 10px;
        }
        .section-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 700;
            color: var(--n900); letter-spacing: -.02em; line-height: 1.15; margin-bottom: 14px;
        }
        .section-title em { font-style: italic; font-weight: 400; color: var(--g600); }
        .section-sub { font-size: .95rem; color: var(--n500); line-height: 1.75; max-width: 480px; }

        .rooms-section-wrap { position: relative; }
        .rooms-scroll-outer {
            position: relative;
            margin: 0 -24px;
            padding: 0 24px;
        }
        .rooms-track {
            display: flex;
            gap: 18px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 8px;
            padding-right: 24px;
        }
        .rooms-track::-webkit-scrollbar { display: none; }
        .rooms-track-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 40px; height: 40px; border-radius: 50%; z-index: 10;
            background: var(--n0); border: 1px solid var(--g200);
            color: var(--n600); font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s;
            box-shadow: 0 4px 16px rgba(10,26,18,.1);
        }
        .rooms-track-arrow:hover { border-color: var(--g500); color: var(--g700); box-shadow: 0 6px 24px rgba(10,26,18,.14); }
        .rooms-arrow-left  { left: -4px; }
        .rooms-arrow-right { right: -4px; }
        .rooms-scroll-fade-left {
            position: absolute; top: 0; left: 0; bottom: 8px;
            width: 48px; pointer-events: none; z-index: 5;
            background: linear-gradient(90deg, var(--g50), transparent);
        }
        .rooms-scroll-fade-right {
            position: absolute; top: 0; right: 0; bottom: 8px;
            width: 64px; pointer-events: none; z-index: 5;
            background: linear-gradient(270deg, var(--g50), transparent);
        }
        .section-white .rooms-scroll-fade-left  { background: linear-gradient(90deg, rgba(255,255,255,.7), transparent); }
        .section-white .rooms-scroll-fade-right { background: linear-gradient(270deg, rgba(255,255,255,.7), transparent); }

        .room-card {
            background: var(--n0); border-radius: 16px;
            border: 1px solid var(--g100); overflow: hidden;
            display: flex; flex-direction: column;
            transition: border-color .25s, box-shadow .3s, transform .3s;
            box-shadow: var(--sh-sm);
            flex: 0 0 300px;
            scroll-snap-align: start;
        }
        .room-card:hover { border-color: var(--g300); box-shadow: var(--sh-lg); transform: translateY(-4px); }

        .room-thumb { height: 210px; position: relative; background: var(--g100); overflow: hidden; }
        .rs-wrap { position: relative; width: 100%; height: 100%; }
        .rs-slide { position: absolute; inset: 0; opacity: 0; transition: opacity .45s ease; pointer-events: none; }
        .rs-slide.rs-active { opacity: 1; z-index: 1; pointer-events: auto; }
        .rs-slide img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .rs-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(145deg, var(--g800), var(--g500));
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.25); font-size: 3.5rem;
        }
        .rs-nav-btn {
            position: absolute; top: 50%; transform: translateY(-50%); z-index: 12;
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(0,0,0,.4); backdrop-filter: blur(4px);
            border: none; color: #fff; font-size: 1.1rem; line-height: 1;
            cursor: pointer; opacity: 0; transition: opacity .2s, background .2s;
            display: flex; align-items: center; justify-content: center; padding: 0;
        }
        .rs-nav-btn:hover { background: rgba(0,0,0,.62); }
        .room-thumb:hover .rs-nav-btn { opacity: 1; }
        .rs-prev { left: 8px; }
        .rs-next { right: 8px; }
        .rs-dots {
            position: absolute; bottom: 9px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 5px; z-index: 12; align-items: center;
        }
        .rs-dot {
            width: 6px; height: 6px; border-radius: 100px;
            background: rgba(255,255,255,.5); border: none; padding: 0;
            cursor: pointer; transition: all .25s; flex-shrink: 0;
        }
        .rs-dot.rs-dot-active { background: #fff; width: 16px; }
        .rs-count {
            position: absolute; bottom: 9px; right: 9px; z-index: 12;
            background: rgba(0,0,0,.4); backdrop-filter: blur(4px);
            color: #fff; font-size: .6rem; font-weight: 600;
            padding: 3px 7px; border-radius: 100px;
            display: flex; align-items: center; gap: 3px;
        }
        .room-badge {
            position: absolute; top: 12px; left: 12px;
            padding: 4px 11px; border-radius: 100px; z-index: 13;
            font-size: .63rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
        }
        .badge-av  { background: var(--g600); color: #fff; }
        .badge-res { background: var(--accent); color: #fff; }
        .badge-mnt { background: var(--n400); color: #fff; }
        .room-num {
            position: absolute; top: 12px; right: 12px; z-index: 13;
            background: rgba(255,255,255,.82); backdrop-filter: blur(6px);
            color: var(--n600); font-size: .63rem; font-weight: 600;
            padding: 3px 10px; border-radius: 100px;
        }
        .room-body { padding: 18px; display: flex; flex-direction: column; flex: 1; }
        .room-name {
            font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 700;
            color: var(--n900); margin-bottom: 5px;
        }
        .room-meta {
            display: flex; gap: 12px; font-size: .72rem;
            color: var(--n400); margin-bottom: 12px; flex-wrap: wrap;
        }
        .room-meta span { display: flex; align-items: center; gap: 4px; }
        .room-desc { font-size: .82rem; color: var(--n500); line-height: 1.65; margin-bottom: 13px; flex: 1; }
        .room-chips { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 16px; }
        .room-chip {
            background: var(--g50); border: 1px solid var(--g200);
            color: var(--g700); font-size: .62rem; font-weight: 600;
            padding: 3px 9px; border-radius: 100px;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .room-footer {
            display: flex; flex-direction: column; gap: 10px;
            align-items: stretch; padding-top: 13px;
            border-top: 1px solid var(--g100);
        }
        .room-price {
            font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 700;
            color: var(--g700); line-height: 1;
        }
        .room-price small { font-family: 'Instrument Sans'; font-size: .72rem; font-weight: 400; color: var(--n400); }
        .btn-room {
            padding: 8px 16px; border-radius: 8px; font-size: .76rem; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
            transition: all .2s; border: none; cursor: pointer;
        }
        .btn-room-book { background: var(--g600); color: #fff; box-shadow: 0 2px 10px rgba(22,119,69,.22); }
        .btn-room-book:hover { background: var(--g700); color: #fff; }
        .btn-room-adv { background: var(--n0); color: var(--accent); border: 1px solid rgba(244,165,53,.4); }
        .btn-room-adv:hover { background: var(--accent-pale); }
        .btn-room-dis { background: var(--g50); color: var(--n400); cursor: not-allowed; pointer-events: none; border: 1px solid var(--g100); }
        .btn-room-detail { background: var(--g50); border: 1px solid var(--g200); color: var(--n600); }
        .btn-room-detail:hover { border-color: var(--g400); color: var(--g700); background: var(--g100); }

        .tenant-end-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--amber-pale); border: 1px solid #fcd34d;
            border-radius: 8px; padding: 5px 10px;
            font-size: .68rem; font-weight: 600; color: #92400e;
            margin-bottom: 10px; width: 100%;
        }
        .tenant-end-badge i { color: var(--amber); flex-shrink: 0; }
        .wl-locked-badge {
            display: flex; align-items: center; gap: 6px;
            background: var(--red-pale); border: 1px solid #fca5a5;
            border-radius: 8px; padding: 6px 10px;
            font-size: .68rem; font-weight: 600; color: #991b1b; margin-bottom: 10px;
        }
        .wl-avail-badge {
            background: var(--g50); border: 1px solid var(--g200);
            border-radius: 8px; padding: 7px 10px; margin-bottom: 10px;
            font-size: .72rem; color: var(--g700);
            display: flex; align-items: center; gap: 6px;
        }

        .band {
            background: linear-gradient(135deg, var(--g900) 0%, var(--g800) 35%, var(--g700) 70%, var(--g600) 100%);
            position: relative; overflow: hidden;
        }
        .band::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 80% 50%, rgba(134,217,174,.12) 0%, transparent 50%);
            pointer-events: none;
        }
        .band-grid { display: grid; grid-template-columns: repeat(4,1fr); background: rgba(255,255,255,.06); gap: 1px; position: relative; z-index: 1; }
        .band-item { padding: 36px 26px; text-align: center; transition: background .2s; }
        .band-item:hover { background: rgba(255,255,255,.04); }
        .band-icon { font-size: 1.5rem; color: var(--g300); display: block; margin-bottom: 12px; }
        .band-title { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .band-desc { font-size: .77rem; color: rgba(255,255,255,.5); line-height: 1.6; }

        .fac-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
        .fac-card {
            padding: 28px 24px; background: var(--n0);
            border: 1px solid var(--g100); border-radius: 14px;
            transition: all .25s; box-shadow: var(--sh-sm);
        }
        .fac-card:hover { border-color: var(--g300); box-shadow: var(--sh-md); transform: translateY(-2px); }
        .fac-icon {
            width: 44px; height: 44px; border-radius: 11px;
            background: var(--g100); border: 1px solid var(--g200);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: var(--g700); margin-bottom: 14px;
        }
        .fac-title { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: var(--n900); margin-bottom: 8px; }
        .fac-desc { font-size: .82rem; color: var(--n500); line-height: 1.7; }

        .loc-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
        .loc-list { list-style: none; padding: 0; margin: 0 0 26px; }
        .loc-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 0; border-bottom: 1px solid var(--g100);
        }
        .loc-item:last-child { border-bottom: none; }
        .loc-dot {
            width: 34px; height: 34px; flex-shrink: 0; border-radius: 9px;
            background: var(--g50); border: 1px solid var(--g200);
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; color: var(--g600);
        }
        .loc-name { font-size: .875rem; font-weight: 600; color: var(--n900); }
        .loc-dist {
            margin-left: auto; flex-shrink: 0;
            font-size: .67rem; font-weight: 600; color: var(--g700);
            background: var(--g50); border: 1px solid var(--g200);
            padding: 3px 10px; border-radius: 100px;
        }
        .btn-map {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; background: var(--n0);
            border: 1px solid var(--g300); border-radius: 9px;
            font-size: .875rem; font-weight: 600; color: var(--n600);
            text-decoration: none; transition: all .2s;
        }
        .btn-map:hover { border-color: var(--g500); color: var(--g700); background: var(--g50); }
        .map-box {
            height: 400px; border: 1px solid var(--g200);
            border-radius: 16px; overflow: hidden;
            box-shadow: var(--sh-md);
        }
        .map-box iframe { width: 100%; height: 100%; border: 0; }

        .cta-box {
            background: linear-gradient(135deg, var(--g900) 0%, var(--g800) 30%, var(--g700) 65%, var(--g600) 100%);
            border-radius: 20px; padding: 60px;
            display: grid; grid-template-columns: 1fr auto;
            gap: 48px; align-items: center;
            position: relative; overflow: hidden;
            box-shadow: 0 8px 40px rgba(10,26,18,.28);
        }
        .cta-box::before {
            content: ''; position: absolute; right: -80px; top: -80px;
            width: 340px; height: 340px; border-radius: 50%;
            border: 48px solid rgba(255,255,255,.05); pointer-events: none;
        }
        .cta-box::after {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 80% 50%, rgba(134,217,174,.14) 0%, transparent 50%);
            pointer-events: none;
        }
        .cta-label {
            font-size: .66rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            color: var(--g300); display: block; margin-bottom: 12px; position: relative; z-index: 1;
        }
        .cta-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.4rem, 2.5vw, 2.1rem); font-weight: 700;
            color: #fff; letter-spacing: -.02em; line-height: 1.2; margin-bottom: 12px;
            position: relative; z-index: 1;
        }
        .cta-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.65); }
        .cta-desc { font-size: .9rem; color: rgba(255,255,255,.55); line-height: 1.7; max-width: 440px; position: relative; z-index: 1; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 26px; background: #fff; color: var(--g800);
            border-radius: 10px; font-size: .9rem; font-weight: 700;
            text-decoration: none; transition: all .25s;
            box-shadow: 0 4px 16px rgba(0,0,0,.1); margin-top: 26px;
            position: relative; z-index: 1;
        }
        .btn-cta:hover { background: var(--g50); color: var(--g900); transform: translateY(-2px); }
        .cta-stats { display: flex; flex-direction: column; gap: 12px; flex-shrink: 0; position: relative; z-index: 1; }
        .cta-stat-card {
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.14);
            border-radius: 12px; padding: 16px 24px; text-align: center; min-width: 130px;
        }
        .cta-stat-num { font-family: 'Fraunces', serif; font-size: 1.8rem; font-weight: 700; color: #fff; display: block; line-height: 1; }
        .cta-stat-lbl { font-size: .66rem; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: .1em; margin-top: 4px; display: block; }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 360px));
            gap: 18px; justify-content: center;
        }
        .contact-card {
            padding: 30px 24px; border: 1px solid var(--g200);
            border-radius: 14px; background: var(--n0);
            text-decoration: none; display: block; transition: all .25s; text-align: center;
            box-shadow: var(--sh-sm);
        }
        .contact-card:hover { border-color: var(--g400); box-shadow: var(--sh-md); transform: translateY(-3px); }
        .contact-icon {
            width: 50px; height: 50px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin: 0 auto 14px;
            background: var(--g100); border: 1px solid var(--g200); color: var(--g700);
        }
        .contact-name { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; color: var(--n900); margin-bottom: 4px; }
        .contact-val  { font-size: .8rem; color: var(--n400); }

        .site-footer { background: var(--g900); padding: 60px 0 28px; }
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
        .footer-link:hover { color: var(--g400); }
        .footer-copy { font-size: .73rem; color: rgba(255,255,255,.16); text-align: center; }

        #chatbot-toggle {
            position: fixed; bottom: 28px; right: 28px; z-index: 9999;
            width: 52px; height: 52px; border-radius: 50%;
            background: linear-gradient(135deg, var(--g800), var(--g500));
            border: none; color: #fff; font-size: 1.2rem;
            box-shadow: 0 4px 20px rgba(22,119,69,.4);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all .25s;
        }
        #chatbot-toggle:hover { background: linear-gradient(135deg, var(--g900), var(--g700)); transform: scale(1.07); }
        .bot-dot { position: absolute; top: 5px; right: 5px; width: 9px; height: 9px; background: var(--accent); border-radius: 50%; border: 2px solid #fff; }
        #chatbot-window {
            position: fixed; bottom: 92px; right: 28px; z-index: 9998;
            width: 356px; background: var(--n0); border-radius: 18px;
            border: 1px solid var(--g200); box-shadow: var(--sh-lg);
            display: none; flex-direction: column; overflow: hidden;
        }
        .bot-header {
            background: linear-gradient(135deg, var(--g900), var(--g700));
            padding: 16px 20px; display: flex; align-items: center; gap: 12px;
        }
        .bot-avatar {
            width: 34px; height: 34px; background: rgba(255,255,255,.14);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .bot-name { font-family: 'Fraunces', serif; font-size: .88rem; font-weight: 700; color: #fff; }
        .bot-status { font-size: .65rem; color: rgba(255,255,255,.55); display: flex; align-items: center; gap: 5px; }
        .bot-status::before { content: ''; width: 5px; height: 5px; background: var(--g300); border-radius: 50%; }
        .bot-close { margin-left: auto; background: none; border: none; color: rgba(255,255,255,.6); font-size: .9rem; cursor: pointer; }
        .bot-messages {
            min-height: 256px; max-height: 316px; overflow-y: auto;
            padding: 16px; display: flex; flex-direction: column; gap: 10px;
            background: var(--n50);
        }
        .bot-messages::-webkit-scrollbar { width: 3px; }
        .bot-messages::-webkit-scrollbar-thumb { background: var(--g200); }
        .msg { max-width: 84%; padding: 10px 14px; border-radius: 13px; font-size: .81rem; line-height: 1.55; animation: msgIn .25s ease; }
        @keyframes msgIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
        .msg-bot { background: var(--n0); color: var(--n900); border-bottom-left-radius: 3px; align-self: flex-start; border: 1px solid var(--g100); box-shadow: var(--sh-sm); }
        .msg-user { background: var(--g600); color: #fff; border-bottom-right-radius: 3px; align-self: flex-end; }
        .msg-time { font-size: .58rem; color: rgba(0,0,0,.25); margin-top: 4px; display: block; }
        .msg-user .msg-time { color: rgba(255,255,255,.4); }
        .bot-quick { padding: 10px 14px; display: flex; flex-wrap: wrap; gap: 5px; border-top: 1px solid var(--g100); background: var(--n0); }
        .bot-qbtn {
            background: var(--g50); border: 1px solid var(--g200);
            color: var(--g700); padding: 4px 11px; border-radius: 100px;
            font-size: .7rem; font-weight: 600; cursor: pointer; transition: all .2s;
        }
        .bot-qbtn:hover { background: var(--g600); color: #fff; border-color: var(--g600); }
        .bot-input-row { display: flex; gap: 7px; padding: 10px 14px; border-top: 1px solid var(--g100); background: var(--n0); }
        .bot-input {
            flex: 1; padding: 8px 13px; border: 1px solid var(--g200);
            border-radius: 8px; font-size: .81rem; font-family: 'Instrument Sans', sans-serif;
            outline: none; background: var(--g50); transition: border .2s;
        }
        .bot-input:focus { border-color: var(--g500); background: #fff; }
        .bot-send {
            width: 34px; height: 34px; background: var(--g600); border: none;
            border-radius: 8px; color: #fff; font-size: .85rem;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .bot-send:hover { background: var(--g700); }
        .typing-wrap { display: flex; gap: 4px; padding: 10px 14px; background: var(--n0); border: 1px solid var(--g100); border-radius: 13px; align-self: flex-start; }
        .typing-dot { width: 6px; height: 6px; background: var(--g400); border-radius: 50%; animation: tdot 1.2s ease infinite; }
        .typing-dot:nth-child(2){animation-delay:.2s}
        .typing-dot:nth-child(3){animation-delay:.4s}
        @keyframes tdot{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-7px)}}

        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.in { opacity: 1; transform: none; }
        .d1{transition-delay:.08s} .d2{transition-delay:.16s} .d3{transition-delay:.24s}
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:none} }

        @media(max-width:1024px) {
            .hero-container { grid-template-columns: 1fr; max-width: 680px; }
            .hero-panel-scene { height: auto; margin-top: 40px; }
            .uk2-stack { height: 280px; }
            .float-chip-1, .float-chip-2 { display: none; }
            .fac-grid { grid-template-columns: repeat(2,1fr); }
            .loc-layout { grid-template-columns: 1fr; gap: 32px; }
            .map-box { height: 300px; }
            .cta-box { grid-template-columns: 1fr; padding: 48px; }
            .cta-stats { flex-direction: row; flex-wrap: wrap; gap: 12px; }
            .cta-stat-card { min-width: 100px; padding: 13px 16px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
            .band-grid { grid-template-columns: repeat(2,1fr); }
        }
        @media(max-width:768px) {
            :root { --section-y: 60px; }
            .nav-links { display: none !important; }
            .nav-actions { display: none !important; }
            .nav-hamburger { display: flex; align-items: center; gap: 6px; }
            .hero { padding: 48px 0 60px; min-height: auto; }
            .hero-container { max-width: 100%; gap: 0; }
            .hero-title br { display: none; }
            .hero-stats { margin-top: 32px; padding-top: 22px; }
            .hero-stat { padding-right: 12px; }
            .hero-stat + .hero-stat { padding-left: 12px; }
            .uk2-stack { height: 240px; }
            .uk2-nav { position: relative; bottom: auto; margin-top: 16px; }
            .section-header { margin-bottom: 32px; }
            .room-card { flex: 0 0 272px; }
            .fac-grid { grid-template-columns: 1fr; gap: 12px; }
            .fac-card { padding: 20px 18px; }
            .band-grid { grid-template-columns: 1fr 1fr; }
            .band-item { padding: 22px 14px; }
            .contact-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .cta-box { padding: 30px 22px; grid-template-columns: 1fr; gap: 24px; border-radius: 16px; }
            .cta-stats { flex-direction: row; flex-wrap: wrap; }
            .cta-stat-card { flex: 1; min-width: calc(50% - 6px); }
            .footer-grid { grid-template-columns: 1fr; gap: 22px; }
            #chatbot-window { width: calc(100vw - 24px); right: 12px; bottom: 86px; border-radius: 14px; }
            #chatbot-toggle { right: 16px; bottom: 16px; width: 48px; height: 48px; font-size: 1.1rem; }
            .rs-nav-btn { opacity: 0.85 !important; }
            .rooms-track-arrow { display: none; }
        }
        @media(max-width:480px) {
            .container-main { padding: 0 16px; }
            .nav-inner { padding: 0 16px; }
            .hero-container { padding: 0 16px; }
            .alerts-wrap { padding: 14px 16px 0; }
            .footer-inner { padding: 0 16px; }
            .hero-tag { margin-bottom: 18px; }
            .hero-title { margin-bottom: 14px; }
            .hero-body { margin-bottom: 26px; font-size: .9rem; }
            .btn-hero-primary, .btn-hero-secondary { padding: 11px 20px; font-size: .85rem; width: 100%; justify-content: center; }
            .hero-stat { padding-right: 8px; }
            .hero-stat + .hero-stat { padding-left: 8px; }
            .stat-lbl { font-size: .57rem; letter-spacing: .04em; }
            .uk2-stack { height: 210px; }
            .band-grid { grid-template-columns: 1fr; }
            .band-item { padding: 18px 14px; }
            .loc-layout { gap: 22px; }
            .map-box { height: 240px; }
            .cta-box { padding: 22px 16px; }
            .cta-stats { flex-direction: column; }
            .cta-stat-card { min-width: auto; }
            #chatbot-window { width: calc(100vw - 16px); right: 8px; bottom: 78px; }
            #chatbot-toggle { right: 12px; bottom: 12px; }
            .room-card { flex: 0 0 248px; }
            .rooms-scroll-outer { margin: 0 -16px; padding: 0 16px; }
        }
    </style>
</head>
<body>
<div class="page-bg"><div class="page-bg-grid"></div></div>

<nav class="site-nav" id="siteNav">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}">
            <div class="brand-mark"><i class="bi bi-house-heart-fill"></i></div>
            <span class="brand-name">Urban Kost</span>
        </a>
        <ul class="nav-links">
            <li><a href="#kamar">Kamar</a></li>
            <li><a href="#fasilitas">Fasilitas</a></li>
            <li><a href="#lokasi">Lokasi</a></li>
            <li><a href="#kontak">Kontak</a></li>
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
            <li><a href="#kamar" class="drawer-link"><i class="bi bi-house-door"></i>Kamar</a></li>
            <li><a href="#fasilitas" class="drawer-link"><i class="bi bi-star"></i>Fasilitas</a></li>
            <li><a href="#lokasi" class="drawer-link"><i class="bi bi-geo-alt"></i>Lokasi</a></li>
            <li><a href="#kontak" class="drawer-link"><i class="bi bi-telephone"></i>Kontak</a></li>
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

<section class="hero">
    <div class="hero-arc"></div>
    <div class="hero-arc-2"></div>
    <div class="hero-container">
        <div>
            <div class="hero-tag">
                <span class="dot"></span>
                <span>Hunian Eksklusif &middot; Yogyakarta</span>
            </div>
            <h1 class="hero-title">Hunian Asri &amp;<br><em>Nyaman</em> di<br>Yogyakarta</h1>
            <p class="hero-body">Urban Kost menghadirkan pengalaman hunian dengan suasana hijau yang menenangkan. Fasilitas modern, lokasi strategis, dan sistem digital yang memudahkan kehidupan sehari-hari.</p>
            <div class="hero-actions">
                <a href="#kamar" class="btn-hero-primary"><i class="bi bi-eye"></i> Lihat Kamar</a>
                @guest
                <a href="{{ route('register') }}" class="btn-hero-secondary"><i class="bi bi-person-plus"></i> Daftar Gratis</a>
                @endguest
            </div>
            <div class="hero-stats">
                <div class="hero-stat"><span class="stat-num">{{ $totalRooms }}</span><span class="stat-lbl">Total Kamar</span></div>
                <div class="hero-stat"><span class="stat-num">{{ $availableCount }}</span><span class="stat-lbl">Tersedia</span></div>
            </div>
        </div>

        <div class="hero-panel-scene">
            @php
            $slides = [
                ['badge'=>'Eksterior','title'=>'Tampak Depan Urban Kost','sub'=>'Desain modern dengan halaman hijau yang luas','class'=>'sc-0','icon'=>'','image'=>'images/foto5.jpeg'],
                ['badge'=>'Taman','title'=>'Taman Asri dan Area Bersantai','sub'=>'Suasana hijau yang nyaman dan menenangkan','class'=>'sc-1','icon'=>'','image'=>'images/foto2.jpeg'],
                ['badge'=>'Akses','title'=>'Jalur Tangga Menuju Hunian','sub'=>'Akses tertata rapi menyatu dengan lanskap taman','class'=>'sc-2','icon'=>'','image'=>'images/foto3.jpeg'],
                ['badge'=>'Teras','title'=>'Area Santai Semi Outdoor','sub'=>'Tempat nyaman untuk bekerja maupun bersantai','class'=>'sc-3','icon'=>'','image'=>'images/foto4.jpeg'],
                ['badge'=>'Fasilitas','title'=>'Dapur Bersama Pencahayaan Alami','sub'=>'Ventilasi maksimal melalui roster dan skylight','class'=>'sc-4','icon'=>'','image'=>'images/foto6.jpeg'],
                ['badge'=>'Arsitektur','title'=>'Detail Fasad Industrial Modern','sub'=>'Perpaduan roster beton dan kusen kayu alami','class'=>'sc-5','icon'=>'','image'=>'images/foto1.jpeg'],
            ];
            @endphp
            <div class="float-chip float-chip-1">
                <div class="fchip-num">{{ $availableCount }}</div>
                <div class="fchip-lbl">Kamar Tersedia</div>
            </div>
            <div class="uk2-stack" id="uk2Stack">
                @foreach($slides as $idx => $sl)
                @php
                    $imgPath    = public_path($sl['image']);
                    $hasImage   = !empty($sl['image']) && file_exists($imgPath);
                    $slideClass = $idx === 0 ? 'uk2-active' : ($idx === 1 ? 'uk2-prev' : ($idx === 2 ? 'uk2-behind' : 'uk2-hidden'));
                @endphp
                <div class="uk2-card {{ $sl['class'] }} {{ $slideClass }}" data-idx="{{ $idx }}">
                    @if($hasImage)
                        <img src="{{ asset($sl['image']) }}" alt="{{ $sl['title'] }}" class="uk2-photo" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}">
                        <div class="uk2-photo-overlay"></div>
                    @else
                        <div class="uk2-pattern"></div>
                        <div class="uk2-shape uk2-sh1"></div>
                        <div class="uk2-shape uk2-sh2"></div>
                        <div class="uk2-shape uk2-sh3"></div>
                        <div class="uk2-icon-wrap">{{ $sl['icon'] }}</div>
                    @endif
                    @if($idx === 0)
                    <div class="uk2-progress"><div class="uk2-progress-bar" id="uk2Bar"></div></div>
                    @endif
                    <div class="uk2-content">
                        <div class="uk2-badge"><span class="uk2-bdot"></span>{{ $sl['badge'] }}</div>
                        <div class="uk2-title">{{ $sl['title'] }}</div>
                        <div class="uk2-sub">{{ $sl['sub'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="uk2-nav">
                <div class="uk2-dots" id="uk2Dots">
                    @foreach($slides as $idx => $sl)
                    <button class="uk2-dot {{ $idx === 0 ? 'uk2-dot-active' : '' }}" data-i="{{ $idx }}" aria-label="Slide {{ $idx+1 }}"></button>
                    @endforeach
                </div>
                <div class="uk2-arrows">
                    <button class="uk2-arw" id="uk2Prev" aria-label="Sebelumnya">&#8249;</button>
                    <button class="uk2-arw" id="uk2Next" aria-label="Berikutnya">&#8250;</button>
                </div>
            </div>
        </div>
    </div>
</section>

@if(session('success') || session('error') || session('info') || session('warning'))
<div class="alerts-wrap">
    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><span>{!! session('success') !!}</span><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill"></i><span>{!! session('error') !!}</span><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('info'))
    <div class="alert alert-info"><i class="bi bi-info-circle-fill"></i><span>{!! session('info') !!}</span><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle-fill"></i><span>{!! session('warning') !!}</span><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
</div>
@endif

<section class="section section-white" id="kamar">
    <div class="container-main">
        <div class="section-header reveal" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:32px">
            <div>
                <span class="section-label">Pilihan Kamar</span>
                <h2 class="section-title" style="margin-bottom:8px">Temukan Kamar <em>Idaman</em> Anda</h2>
                <p class="section-sub">Setiap kamar dirancang dengan cermat untuk kenyamanan maksimal.</p>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                <button class="rooms-track-arrow rooms-arrow-left" id="roomsLeft" aria-label="Scroll kiri" style="position:static;transform:none">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="rooms-track-arrow rooms-arrow-right" id="roomsRight" aria-label="Scroll kanan" style="position:static;transform:none">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        @if($rooms->isEmpty())
            <div style="text-align:center;padding:60px 0" class="reveal">
                <i class="bi bi-house-slash" style="font-size:3rem;color:var(--g200)"></i>
                <p style="margin-top:14px;color:var(--n400)">Belum ada kamar tersedia. Hubungi kami untuk informasi lebih lanjut.</p>
            </div>
        @else
            <div class="rooms-section-wrap reveal">
                <div class="rooms-scroll-outer">
                    <div class="rooms-scroll-fade-left" id="fadeLeft"></div>
                    <div class="rooms-track" id="roomsTrack">
                        @foreach($roomData as $i => $rd)
                        @php
                            $room          = $rd['room'];
                            $activeTenant  = $rd['active_tenant'];
                            $activeWL      = $rd['active_wl'];
                            $tenantEndDate = $rd['tenant_end_date'];
                            $activeWLValid = $activeWL;

                            if ($room->status === 'maintenance') {
                                $badgeLabel = 'Perbaikan'; $badgeClass = 'badge-mnt'; $badgeColor = 'var(--n400)';
                            } elseif ($room->status === 'occupied') {
                                if ($activeWLValid) { $badgeLabel = 'Terisi · WL Penuh'; $badgeClass = 'badge-res'; $badgeColor = '#dc2626'; }
                                else               { $badgeLabel = 'Terisi';             $badgeClass = 'badge-res'; $badgeColor = '#c0392b'; }
                            } elseif ($room->status === 'reserved') {
                                $badgeLabel = 'Dipesan'; $badgeClass = 'badge-res'; $badgeColor = 'var(--accent)';
                            } else {
                                $badgeLabel = 'Tersedia'; $badgeClass = 'badge-av'; $badgeColor = 'var(--g600)';
                            }

                            $isRoomOccupiedOrReserved = in_array($room->status, ['occupied', 'reserved']);
                            $canBookAdvance = $isRoomOccupiedOrReserved && !$activeWLValid;
                            $wlFull         = $isRoomOccupiedOrReserved && $activeWLValid;

                            $roomImgs = $room->images->isNotEmpty()
                                ? $room->images->sortByDesc('is_primary')->map(fn($img) => $img->url)->values()->all()
                                : ($room->image_path ? [asset('storage/'.$room->image_path)] : []);
                            $multiImg = count($roomImgs) > 1;
                        @endphp

                        <div class="room-card">
                            <div class="room-thumb" id="rthumb-{{ $room->id }}">
                                <div class="rs-wrap">
                                    @if(count($roomImgs) > 0)
                                        @foreach($roomImgs as $imgIdx => $imgUrl)
                                        <div class="rs-slide {{ $imgIdx === 0 ? 'rs-active' : '' }}">
                                            <img src="{{ $imgUrl }}" alt="{{ $room->room_name ?? 'Kamar '.$room->room_number }}"
                                                 loading="{{ $imgIdx === 0 && $i < 4 ? 'eager' : 'lazy' }}">
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="rs-slide rs-active">
                                            <div class="rs-placeholder"><i class="bi bi-house-door"></i></div>
                                        </div>
                                    @endif
                                </div>
                                <span class="room-badge {{ $badgeClass }}" style="background:{{ $badgeColor }}">{{ $badgeLabel }}</span>
                                <span class="room-num">No. {{ $room->room_number }}</span>
                                @if($multiImg)
                                <button class="rs-nav-btn rs-prev" onclick="rsNav({{ $room->id }}, -1, event)" aria-label="Foto sebelumnya">&#8249;</button>
                                <button class="rs-nav-btn rs-next" onclick="rsNav({{ $room->id }}, 1, event)" aria-label="Foto berikutnya">&#8250;</button>
                                <div class="rs-dots" id="rsdots-{{ $room->id }}">
                                    @foreach($roomImgs as $imgIdx => $imgUrl)
                                    <button class="rs-dot {{ $imgIdx === 0 ? 'rs-dot-active' : '' }}"
                                            onclick="rsGoTo({{ $room->id }}, {{ $imgIdx }}, event)" aria-label="Foto {{ $imgIdx + 1 }}"></button>
                                    @endforeach
                                </div>
                                <span class="rs-count" id="rscount-{{ $room->id }}">
                                    <i class="bi bi-images"></i> 1/{{ count($roomImgs) }}
                                </span>
                                @endif
                            </div>

                            <div class="room-body">
                                <div class="room-name">{{ $room->room_name ?? 'Kamar '.$room->room_number }}</div>
                                <div class="room-meta">
                                    @if($room->size)<span><i class="bi bi-arrows-fullscreen"></i>{{ $room->size }}</span>@endif
                                    @if($room->floor)<span><i class="bi bi-building"></i>Lantai {{ $room->floor }}</span>@endif
                                </div>
                                @if($room->description)
                                <p class="room-desc">{{ Str::limit($room->description, 80) }}</p>
                                @endif
                                @if($room->facilities)
                                <div class="room-chips">
                                    @foreach(array_slice($room->facilities, 0, 3) as $f)
                                        <span class="room-chip">{{ $f }}</span>
                                    @endforeach
                                    @if(count($room->facilities) > 3)
                                        <span class="room-chip">+{{ count($room->facilities)-3 }}</span>
                                    @endif
                                </div>
                                @endif

                                @if(in_array($room->status, ['occupied', 'reserved']) && ($activeTenant || $tenantEndDate))
                                    @if($tenantEndDate)
                                    <div class="tenant-end-badge">
                                        <i class="bi bi-calendar-x"></i>
                                        <span>Selesai:&nbsp;<strong>{{ $tenantEndDate->format('d M Y') }}</strong></span>
                                    </div>
                                    @endif
                                    @if($wlFull)
                                    <div class="wl-locked-badge">
                                        <i class="bi bi-lock-fill"></i>
                                        <span>Waiting list penuh (1/1)</span>
                                    </div>
                                    @else
                                    <div class="wl-avail-badge">
                                        <i class="bi bi-calendar-plus"></i>
                                        <span>Advance booking tersedia</span>
                                    </div>
                                    @endif
                                @elseif($room->status === 'reserved')
                                <div style="background:var(--blue-pale);border:1px solid #bfdbfe;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:.75rem">
                                    <div style="font-weight:600;color:#1e40af;margin-bottom:2px"><i class="bi bi-clock-history me-1"></i>Kamar sedang dipesan</div>
                                    <div style="color:#3b82f6">Menunggu konfirmasi pembayaran</div>
                                </div>
                                @endif

                                <div class="room-footer">
                                    <div class="room-price">{{ $room->formatted_price }}<small>/bln</small></div>
                                    <div style="display:flex;gap:8px">
                                        <a href="{{ route('rooms.show', $room->id) }}"
                                           class="btn-room btn-room-detail"
                                           style="flex:1;justify-content:center">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        @if($room->status === 'available')
                                            <a href="{{ route('booking.create', $room->id) }}"
                                               class="btn-room btn-room-book" style="flex:1;justify-content:center">
                                                <i class="bi bi-calendar-check"></i> Booking
                                            </a>
                                        @elseif($canBookAdvance)
                                            <a href="{{ route('booking.create', $room->id) }}"
                                               class="btn-room btn-room-adv" style="flex:1;justify-content:center">
                                                <i class="bi bi-calendar-plus"></i> Advance
                                            </a>
                                        @elseif($wlFull)
                                            <span class="btn-room btn-room-dis" style="flex:1;justify-content:center">
                                                <i class="bi bi-lock-fill"></i> WL Penuh
                                            </span>
                                        @elseif($room->status === 'maintenance')
                                            <span class="btn-room btn-room-dis" style="flex:1;justify-content:center">
                                                <i class="bi bi-tools"></i> Perbaikan
                                            </span>
                                        @else
                                            <span class="btn-room btn-room-dis" style="flex:1;justify-content:center">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="rooms-scroll-fade-right" id="fadeRight"></div>
                </div>
            </div>
        @endif
    </div>
</section>

<div class="band">
    <div class="container-main">
        <div class="band-grid">
            @foreach([
                ['bi-shield-check-fill','Keamanan 24 Jam','CCTV aktif dan akses terproteksi sepanjang waktu'],
                ['bi-wifi','Internet Cepat','Wifi yang stabil untuk kerja dan belajar'],
                ['bi-leaf-fill','Lingkungan Asri','Suasana hijau dan sejuk di tengah kota Yogyakarta dan memiliki taman yang indah'],
                ['bi-phone','Kelola Digital','Bayar tagihan dan ajukan tiket lewat platform kapan saja'],
            ] as [$ic,$ti,$de])
            <div class="band-item">
                <i class="bi {{ $ic }} band-icon"></i>
                <div class="band-title">{{ $ti }}</div>
                <div class="band-desc">{{ $de }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<section class="section" id="fasilitas">
    <div class="container-main">
        <div class="section-header center reveal">
            <span class="section-label">Fasilitas</span>
            <h2 class="section-title">Semua yang Anda <em>Butuhkan</em></h2>
            <p class="section-sub">Dirancang lengkap untuk mendukung aktivitas dan kenyamanan hidup sehari-hari Anda</p>
        </div>
        <div class="fac-grid">
            @foreach([
                ['bi-wifi','WiFi Super Cepat','Koneksi yang stabil dan aktif 24 jam, ideal untuk kuliah, bekerja remote.'],
                ['bi-thermometer-sun','AC & Ventilasi','Kamar premium dilengkapi AC di setiap kamarnya. Semua kamar memiliki sirkulasi udara yang optimal.'],
                ['bi-shield-check','Keamanan Penuh','CCTV dalam pemantauan 24 jam.'],
                ['bi-car-front','Parkir Luas','Area parkir motor dan mobil yang aman dan luas.'],
                ['bi-phone','Sistem Digital','Pembayaran online, notifikasi otomatis, laporan transparan, dan tiket keluhan dalam satu platform.'],
            ] as [$ic,$ti,$de])
            <div class="fac-card reveal">
                <div class="fac-icon"><i class="bi {{ $ic }}"></i></div>
                <div class="fac-title">{{ $ti }}</div>
                <div class="fac-desc">{!! $de !!}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section section-white" id="lokasi">
    <div class="container-main">
        <div class="loc-layout">
            <div class="reveal">
                <span class="section-label">Lokasi</span>
                <h2 class="section-title" style="margin-bottom:12px">Posisi <em>Strategis</em><br>di Yogyakarta</h2>
                <p class="section-sub" style="margin-bottom:26px">Dekat kampus ternama, pusat belanja, dan transportasi umum untuk mobilitas harian yang mudah.</p>
                <ul class="loc-list">
                    @foreach([
                        ['bi-mortarboard','UAJY (Babarsari)','± 4 km · ±10 mnt'],
                        ['bi-mortarboard','UAJY (Mrican)','± 3 km · ±8 mnt'],
                        ['bi-mortarboard','UGM','± 5 km · ±13 mnt'],
                        ['bi-mortarboard','UNY','± 3,5 km · ±9 mnt'],
                        ['bi-mortarboard','USD – Sanata Dharma','± 4 km · ±10 mnt'],
                        ['bi-shop','Indomaret / Alfamart','± 300 m · ±2 mnt'],
                        ['bi-bus-front','Halte Trans Jogja','± 400 m · ±3 mnt'],
                        ['bi-heart-pulse','RS Happy Land','± 1,5 km · ±4 mnt'],
                        ['bi-heart-pulse','RS Hardjolukito (TNI AU)','± 1,5 km · ±4 mnt'],
                        ['bi-heart-pulse','RS Rajawali Citra','± 2 km · ±5 mnt'],
                        ['bi-geo-alt','Jogja Expo Center','± 400 m · ±2 mnt'],
                    ] as [$ic,$pl,$ds])
                    <li class="loc-item">
                        <div class="loc-dot"><i class="bi {{ $ic }}"></i></div>
                        <span class="loc-name">{{ $pl }}</span>
                        <span class="loc-dist">{{ $ds }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="https://maps.app.goo.gl/nAUScQV4nqeWrFAv9" target="_blank" rel="noopener" class="btn-map">
                    <i class="bi bi-map-fill"></i> Buka Google Maps
                </a>
            </div>
            <div class="reveal d2">
                <div class="map-box">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.949623914962!2d110.39659119999999!3d-7.795158699999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5728ce3d29db%3A0x68f99f8f2a08dc26!2sUrbanKost.Jogja!5e0!3m2!1sid!2sid!4v1779105315439!5m2!1sid!2sid"
                        width="100%" height="100%"
                        style="border:0;border-radius:inherit;"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-main reveal">
        <div class="cta-box">
            <div>
                <span class="cta-label">Bergabung Bersama Kami</span>
                <h2 class="cta-title">Mulai Hidup <em>Lebih Nyaman</em><br>Hari Ini</h2>
                <p class="cta-desc">Penghuni telah merasakan kenyamanan dan ketenangan di Urban Kost. Jadilah bagian dari komunitas hunian eksklusif kami.</p>
                @guest
                <a href="{{ route('register') }}" class="btn-cta"><i class="bi bi-person-plus"></i> Daftar Sekarang — Gratis</a>
                @else
                <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn-cta"><i class="bi bi-speedometer2"></i> Menuju Dashboard</a>
                @endguest
            </div>
            <div class="cta-stats">
                <div class="cta-stat-card"><span class="cta-stat-num">{{ $totalRooms }}</span><span class="cta-stat-lbl">Total Kamar</span></div>
                <div class="cta-stat-card"><span class="cta-stat-num">{{ $availableCount }}</span><span class="cta-stat-lbl">Tersedia</span></div>
                <div class="cta-stat-card"><span class="cta-stat-num">98%</span><span class="cta-stat-lbl">Kepuasan</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section section-white" id="kontak">
    <div class="container-main">
        <div class="section-header center reveal">
            <span class="section-label">Hubungi Kami</span>
            <h2 class="section-title">Siap <em>Membantu</em> Anda</h2>
            <p class="section-sub">Tersedia Senin – Sabtu, pukul 08.00 – 20.00 WIB</p>
        </div>
        <div class="contact-grid">
            @foreach([
                ['bi-whatsapp','WhatsApp','0812-2930-2431','https://wa.me/6281229302431'],
                ['bi-instagram','Instagram','@urbankost.jogja','https://instagram.com/urbankost.jogja'],
            ] as [$ic,$nm,$vl,$hr])
            <div class="reveal d{{ $loop->index + 1 }}">
                <a href="{{ $hr }}" target="_blank" rel="noopener" class="contact-card">
                    <div class="contact-icon"><i class="bi {{ $ic }}"></i></div>
                    <div class="contact-name">{{ $nm }}</div>
                    <div class="contact-val">{{ $vl }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<footer class="site-footer" id="footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">Urban Kost</div>
                <p class="footer-desc">Hunian dengan suasana asri dan sistem manajemen digital terpadu untuk pengalaman hidup yang lebih nyaman di Yogyakarta.</p>
            </div>
            <div>
                <div class="footer-heading">Navigasi</div>
                <a class="footer-link" href="#kamar">Kamar</a>
                <a class="footer-link" href="#fasilitas">Fasilitas</a>
                <a class="footer-link" href="#lokasi">Lokasi</a>
                <a class="footer-link" href="#kontak">Kontak</a>
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

<button id="chatbot-toggle" title="Chat dengan kami">
    <i class="bi bi-chat-dots-fill" id="bot-icon"></i>
    <div class="bot-dot"></div>
</button>
<div id="chatbot-window">
    <div class="bot-header">
        <div class="bot-avatar"><i class="bi bi-house-heart-fill" style="color:var(--g300)"></i></div>
        <div>
            <div class="bot-name">Urban Kost</div>
            <div class="bot-status">Online sekarang</div>
        </div>
        <button class="bot-close" id="bot-close"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="bot-messages" id="bot-messages">
        <div class="msg msg-bot">
            Halo! Selamat datang di <strong>Urban Kost</strong>. Ada yang bisa kami bantu?
        </div>
    </div>
    <div class="bot-quick">
        <button class="bot-qbtn" onclick="sendQ('cara booking')">Booking</button>
        <button class="bot-qbtn" onclick="sendQ('fasilitas')">Fasilitas</button>
        <button class="bot-qbtn" onclick="sendQ('lokasi')">Lokasi</button>
        <button class="bot-qbtn" onclick="sendQ('kontak')">Kontak</button>
        <button class="bot-qbtn" onclick="sendQ('parkir motor')">Parkir</button>
        <button class="bot-qbtn" onclick="sendQ('peraturan')">Peraturan</button>
        <button class="bot-qbtn" onclick="sendQ('cara bayar')">Pembayaran</button>
    </div>
    <div class="bot-input-row">
        <input class="bot-input" type="text" id="bot-input" placeholder="Ketik pesan..." autocomplete="off">
        <button class="bot-send" id="bot-send"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('scroll', () => {
    document.getElementById('siteNav').classList.toggle('elevated', window.scrollY > 30);
}, { passive: true });

const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

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

(function() {
    const track = document.getElementById('roomsTrack');
    const btnL  = document.getElementById('roomsLeft');
    const btnR  = document.getElementById('roomsRight');
    const fadeL = document.getElementById('fadeLeft');
    const fadeR = document.getElementById('fadeRight');
    if (!track) return;
    const STEP = 320;
    function updateFades() {
        if (!fadeL || !fadeR) return;
        fadeL.style.opacity = track.scrollLeft > 10 ? '1' : '0';
        fadeR.style.opacity = track.scrollLeft < track.scrollWidth - track.clientWidth - 10 ? '1' : '0';
    }
    function updateButtons() {
        if (!btnL || !btnR) return;
        btnL.style.opacity = track.scrollLeft > 10 ? '1' : '0.35';
        btnR.style.opacity = track.scrollLeft < track.scrollWidth - track.clientWidth - 10 ? '1' : '0.35';
    }
    btnL?.addEventListener('click', () => { track.scrollBy({ left: -STEP, behavior: 'smooth' }); });
    btnR?.addEventListener('click', () => { track.scrollBy({ left:  STEP, behavior: 'smooth' }); });
    track.addEventListener('scroll', () => { updateFades(); updateButtons(); }, { passive: true });
    updateFades(); updateButtons();
})();

const RS = {};
function rsGoTo(id, idx, e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const s = RS[id]; if (!s) return;
    const thumb = document.getElementById('rthumb-' + id); if (!thumb) return;
    thumb.querySelectorAll('.rs-slide').forEach((sl, i) => sl.classList.toggle('rs-active', i === idx));
    const dotsEl = document.getElementById('rsdots-' + id);
    if (dotsEl) dotsEl.querySelectorAll('.rs-dot').forEach((d, i) => d.classList.toggle('rs-dot-active', i === idx));
    const cnt = document.getElementById('rscount-' + id);
    if (cnt) cnt.innerHTML = `<i class="bi bi-images"></i> ${idx + 1}/${s.total}`;
    s.cur = idx;
}
function rsNav(id, dir, e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const s = RS[id]; if (!s) return;
    rsGoTo(id, ((s.cur + dir) % s.total + s.total) % s.total, null);
}
function rsStart(id) {
    const s = RS[id]; if (!s || s.total <= 1) return;
    clearInterval(s.timer);
    s.timer = setInterval(() => rsGoTo(id, (s.cur + 1) % s.total, null), 4200);
}
(function initRoomSliders() {
    document.querySelectorAll('[id^="rthumb-"]').forEach(thumb => {
        const id     = parseInt(thumb.id.replace('rthumb-', ''));
        const slides = thumb.querySelectorAll('.rs-slide');
        if (slides.length <= 1) return;
        RS[id] = { cur: 0, total: slides.length, timer: null };
        let sx = 0, sy = 0;
        thumb.addEventListener('touchstart', e => { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true });
        thumb.addEventListener('touchend', e => {
            const dx = sx - e.changedTouches[0].clientX;
            const dy = Math.abs(sy - e.changedTouches[0].clientY);
            if (Math.abs(dx) > 35 && dy < 55) rsNav(id, dx > 0 ? 1 : -1, null);
        }, { passive: true });
        thumb.addEventListener('mouseenter', () => clearInterval(RS[id].timer));
        thumb.addEventListener('mouseleave', () => rsStart(id));
        rsStart(id);
    });
})();

(function(){
    const total = document.querySelectorAll('.uk2-card').length;
    if (!total) return;
    let cur = 0, timer, prog = 0, pt;
    const cards = document.querySelectorAll('.uk2-card');
    const dots  = document.querySelectorAll('.uk2-dot');
    const bar   = document.getElementById('uk2Bar');
    function cls(i) {
        const d = ((i - cur) % total + total) % total;
        return d===0 ? 'uk2-active' : d===1 ? 'uk2-prev' : d===2 ? 'uk2-behind' : 'uk2-hidden';
    }
    function render() {
        cards.forEach((c, i) => {
            const fixed = [...c.classList].filter(x => x.startsWith('sc-') || x === 'uk2-card').join(' ');
            c.className = fixed + ' ' + cls(i);
        });
        dots.forEach((d, i) => d.classList.toggle('uk2-dot-active', i === cur));
    }
    function startProg() {
        clearInterval(pt); prog = 0;
        if (bar) bar.style.width = '0%';
        pt = setInterval(() => { prog += 100/38; if (bar) bar.style.width = Math.min(prog, 100) + '%'; }, 100);
    }
    function goTo(n) { cur = ((n % total) + total) % total; render(); startProg(); }
    function startAuto() { clearInterval(timer); timer = setInterval(() => goTo(cur + 1), 3800); }
    document.getElementById('uk2Next')?.addEventListener('click', () => { clearInterval(timer); goTo(cur+1); startAuto(); });
    document.getElementById('uk2Prev')?.addEventListener('click', () => { clearInterval(timer); goTo(cur-1); startAuto(); });
    document.querySelectorAll('.uk2-dot').forEach(d => d.addEventListener('click', () => { clearInterval(timer); goTo(+d.dataset.i); startAuto(); }));
    const stack = document.getElementById('uk2Stack');
    if (stack) {
        let sx = 0, sy = 0;
        stack.addEventListener('touchstart', e => { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true });
        stack.addEventListener('touchend', e => {
            const dx = sx - e.changedTouches[0].clientX;
            const dy = Math.abs(sy - e.changedTouches[0].clientY);
            if (Math.abs(dx) > 40 && dy < 60) { clearInterval(timer); goTo(cur + (dx > 0 ? 1 : -1)); startAuto(); }
        }, { passive: true });
    }
    startProg(); startAuto();
})();

const toggle   = document.getElementById('chatbot-toggle');
const win      = document.getElementById('chatbot-window');
const closeBtn = document.getElementById('bot-close');
const input    = document.getElementById('bot-input');
const sendBtn  = document.getElementById('bot-send');
const msgs     = document.getElementById('bot-messages');
const csrf     = document.querySelector('meta[name="csrf-token"]').content;
let open = false;
const openBot  = () => { open = true;  win.style.display = 'flex'; win.style.flexDirection = 'column'; document.getElementById('bot-icon').className = 'bi bi-x-lg'; };
const closeBot = () => { open = false; win.style.display = 'none'; document.getElementById('bot-icon').className = 'bi bi-chat-dots-fill'; };
toggle.addEventListener('click', () => open ? closeBot() : openBot());
closeBtn.addEventListener('click', closeBot);
function sendQ(t) { addMsg(t, 'user'); getReply(t); }
function addMsg(text, who) {
    const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const d = document.createElement('div');
    d.className = `msg msg-${who}`;
    d.innerHTML = who === 'bot' ? `${text}<span class="msg-time">${now}</span>` : `<span>${text}</span><span class="msg-time">${now}</span>`;
    msgs.appendChild(d); msgs.scrollTop = msgs.scrollHeight;
}
function showTyping() { const d = document.createElement('div'); d.className = 'typing-wrap'; d.id = 'typing'; d.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>'; msgs.appendChild(d); msgs.scrollTop = msgs.scrollHeight; }
function hideTyping() { const t = document.getElementById('typing'); if (t) t.remove(); }
async function getReply(msg) {
    showTyping();
    try {
        const r = await fetch('{{ route("chatbot.respond") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ message: msg })
        });
        const d = await r.json(); hideTyping(); addMsg(d.message, 'bot');
    } catch { hideTyping(); addMsg('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot'); }
}
sendBtn.addEventListener('click', () => { const m = input.value.trim(); if (!m) return; addMsg(m, 'user'); input.value = ''; getReply(m); });
input.addEventListener('keypress', e => { if (e.key === 'Enter') sendBtn.click(); });
</script>
</body>
</html>