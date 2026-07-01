<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Kamar {{ $room->room_number }} – Urban Kost</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

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
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
        --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
        --radius-sm: 8px;
        --radius-md: 14px;
        --radius-lg: 20px;
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
            radial-gradient(circle at 15% 15%, rgba(42,179,107,.08) 0%, transparent 50%),
            radial-gradient(circle at 85% 80%, rgba(29,148,88,.06) 0%, transparent 50%);
    }
    .page-bg-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(42,179,107,.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(42,179,107,.04) 1px, transparent 1px);
        background-size: 60px 60px;
    }

    /* NAV */
    .uk-nav {
        position: sticky; top: 0; z-index: 900;
        background: rgba(240,250,244,.96);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--green-100);
        transition: box-shadow .3s;
    }
    .uk-nav.elevated { box-shadow: var(--shadow-md); }
    .uk-nav-inner {
        display: flex; align-items: center;
        max-width: 1280px; margin: 0 auto;
        padding: 0 24px; height: 60px; gap: 20px;
    }
    .uk-brand { display: flex; align-items: center; gap: 9px; text-decoration: none; flex-shrink: 0; }
    .uk-brand-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, var(--green-500), var(--green-700));
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .9rem; box-shadow: 0 4px 12px rgba(42,179,107,.3);
    }
    .uk-brand-name {
        font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700;
        color: var(--neutral-900); letter-spacing: -.02em;
    }
    .btn-nav-back {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: var(--radius-sm);
        font-size: .75rem; font-weight: 600;
        color: var(--neutral-500); background: var(--neutral-0);
        border: 1px solid var(--neutral-200); text-decoration: none; transition: all .2s;
    }
    .btn-nav-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

    /* HERO */
    .hero-banner {
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 36px 24px 32px;
    }
    .hero-banner::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
        pointer-events: none;
    }
    .hero-arc { position: absolute; right: -80px; top: -80px; width: 320px; height: 320px; border-radius: 50%; border: 1px solid rgba(255,255,255,.1); pointer-events: none; }
    .hero-inner { max-width: 1280px; margin: 0 auto; position: relative; z-index: 1; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        padding: 5px 14px; border-radius: 100px;
        font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
        color: rgba(255,255,255,.82); margin-bottom: 12px;
    }
    .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
    .hero-title {
        font-family: 'Fraunces', serif; font-size: clamp(1.4rem, 4vw, 2rem);
        font-weight: 700; color: #fff; letter-spacing: -.025em; line-height: 1.15; margin-bottom: 6px;
    }
    .hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
    .hero-sub { font-size: .85rem; color: rgba(255,255,255,.60); max-width: 420px; line-height: 1.6; }

    /* ALERTS */
    .alerts-wrap { max-width: 1280px; margin: 0 auto; padding: 16px 24px 0; }
    .uk-alert {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 16px; border-radius: var(--radius-sm);
        border: 1px solid; font-size: .82rem; margin-bottom: 8px;
    }
    .uk-alert-danger  { background: var(--red-pale); border-color: rgba(220,38,38,.25); color: #991b1b; }
    .uk-alert-warning { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }

    /* TENANT INFO BANNER (advance booking – ditampilkan di atas, setelah hero) */
    .tenant-info-banner-wrap {
        max-width: 1280px; margin: 20px auto 0;
        padding: 0 24px;
    }
    .tenant-info-banner {
        background: linear-gradient(135deg, var(--accent-pale), #fef3c7);
        border: 1.5px solid rgba(244,165,53,.4);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .tib-header {
        padding: 12px 22px;
        border-bottom: 1px solid rgba(244,165,53,.25);
        display: flex; align-items: center; gap: 8px;
        font-size: .75rem; font-weight: 700; color: #92400e;
        background: rgba(255,255,255,.35);
    }
    .tib-body {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
        padding: 16px 22px;
    }
    @media (min-width: 640px) {
        .tib-body { grid-template-columns: 1fr 1fr; gap: 14px; padding: 18px 22px; }
    }
    @media (min-width: 900px) {
        .tib-body { grid-template-columns: repeat(4, 1fr); gap: 0; padding: 0; }
    }
    .tib-col {
        display: flex; flex-direction: column;
        padding: 14px 18px;
        border-right: 1px solid rgba(244,165,53,.2);
    }
    .tib-col:last-child { border-right: none; }
    @media (max-width: 899px) {
        .tib-col { border-right: none; border-bottom: 1px solid rgba(244,165,53,.2); padding: 12px 0; }
        .tib-col:last-child { border-bottom: none; }
    }
    @media (max-width: 639px) {
        .tib-body { display: flex; flex-direction: column; gap: 0; }
    }
    .tib-lbl {
        font-size: .62rem; font-weight: 700; letter-spacing: .09em;
        text-transform: uppercase; color: #b45309; margin-bottom: 4px;
    }
    .tib-val {
        font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700;
        color: #78350f; line-height: 1.25;
    }
    .tib-sub { font-size: .65rem; color: #b45309; margin-top: 2px; }
    .tib-bar-wrap {
        padding: 10px 18px 14px;
        border-top: 1px solid rgba(244,165,53,.2);
        background: rgba(255,255,255,.25);
    }
    .tib-bar-labels {
        display: flex; justify-content: space-between;
        font-size: .58rem; font-weight: 600; color: #92400e; margin-bottom: 5px;
    }
    .tib-bar-track { height: 5px; background: rgba(244,165,53,.2); border-radius: 100px; overflow: hidden; }
    .tib-bar-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--amber), #f97316); }
    .tib-bar-remain { font-size: .63rem; color: #92400e; text-align: center; margin-top: 5px; font-weight: 600; }
    .tib-checkin-row {
        padding: 10px 22px 14px;
        display: flex; align-items: center; gap: 12px;
        background: rgba(255,255,255,.4);
        border-top: 1px solid rgba(244,165,53,.2);
        flex-wrap: wrap;
    }
    .tib-checkin-icon {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--green-500); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(42,179,107,.3);
    }
    .tib-checkin-label { font-size: .65rem; color: var(--green-700); font-weight: 600; }
    .tib-checkin-date {
        font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700;
        color: var(--green-600); line-height: 1.2;
    }
    .tib-checkin-note { font-size: .62rem; color: var(--green-600); }

    /* PROSES BANNER */
    .proses-banner-wrap {
        max-width: 1280px; margin: 20px auto 0;
        padding: 0 24px;
    }
    .proses-banner {
        background: var(--neutral-0); border: 1px solid var(--green-100);
        border-radius: var(--radius-lg); box-shadow: var(--shadow-md); overflow: hidden;
    }
    .proses-banner-header {
        padding: 13px 24px; border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; gap: 8px;
        font-size: .75rem; font-weight: 700; color: var(--neutral-700);
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .proses-banner-header.adv-header { background: linear-gradient(135deg, var(--accent-pale), #fef3c7); }
    .proses-banner-header.adv-header span { color: #92400e; }
    .proses-steps-row {
        display: flex; flex-direction: row; align-items: stretch;
        padding: 20px 24px; gap: 0;
        overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none;
    }
    .proses-steps-row::-webkit-scrollbar { display: none; }
    .proses-step {
        display: flex; flex-direction: column; align-items: center;
        text-align: center; flex: 1; min-width: 120px;
        position: relative; padding: 0 8px;
    }
    .proses-step:not(:last-child)::after {
        content: ''; position: absolute; top: 18px;
        left: calc(50% + 18px); right: -1px;
        width: calc(100% - 36px - 16px); height: 2px;
        background: var(--green-100); z-index: 0;
    }
    .proses-step.adv-step:not(:last-child)::after { background: rgba(244,165,53,.25); }
    .proses-step-num {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--green-500); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 700; flex-shrink: 0;
        position: relative; z-index: 1;
        box-shadow: 0 2px 8px rgba(42,179,107,.3); margin-bottom: 10px;
    }
    .proses-step.adv-step .proses-step-num { background: var(--accent); box-shadow: 0 2px 8px rgba(244,165,53,.35); }
    .proses-step-txt { font-size: .72rem; color: var(--neutral-500); line-height: 1.5; max-width: 130px; }
    .proses-step-txt strong { color: var(--neutral-800); display: block; margin-bottom: 1px; }

    @media (max-width: 600px) {
        .proses-steps-row { flex-direction: column; gap: 0; padding: 16px 18px; }
        .proses-step {
            flex-direction: row; text-align: left; align-items: flex-start;
            min-width: unset; padding: 10px 0;
            border-bottom: 1px solid var(--neutral-100);
        }
        .proses-step:last-child { border-bottom: none; }
        .proses-step::after { display: none !important; }
        .proses-step-num { margin-bottom: 0; margin-right: 12px; flex-shrink: 0; }
        .proses-step-txt { max-width: 100%; }
    }

    /* MAIN GRID */
    .uk-main { max-width: 1280px; margin: 0 auto; padding: 24px 24px 32px; }
    .uk-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        align-items: start;
    }
    .right-panel { display: flex; flex-direction: column; gap: 0; }
    .room-card-mobile  { display: block; }
    .room-card-desktop { display: none; }

    @media (min-width: 1024px) {
        .uk-grid { grid-template-columns: 1fr 400px; gap: 28px; }
        .right-panel { position: sticky; top: 76px; }
        .room-card-mobile  { display: none; }
        .room-card-desktop { display: block; }
    }

    /* CARDS */
    .o-card {
        background: var(--neutral-0); border-radius: var(--radius-lg);
        border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 20px;
    }
    .o-card:last-child { margin-bottom: 0; }
    .o-card-header {
        padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
        display: flex; align-items: center; gap: 8px;
        font-size: .75rem; font-weight: 700; color: var(--neutral-700);
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .o-card-header-adv { background: linear-gradient(135deg, var(--accent-pale), #fef3c7); }
    .o-card-header-adv span { color: #92400e; }
    .o-card-body { padding: 20px; }

    /* FORM FIELDS */
    .fld-label {
        display: block; font-size: .68rem; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        color: var(--neutral-400); margin-bottom: 6px;
    }
    .fld-required { color: var(--red); }
    .fld-optional { font-weight: 400; text-transform: none; letter-spacing: 0; color: var(--neutral-400); }
    .fld-ctrl {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid var(--neutral-200); border-radius: var(--radius-sm);
        font-family: 'Instrument Sans', sans-serif; font-size: .875rem;
        color: var(--neutral-900); background: var(--neutral-0);
        transition: border-color .2s, box-shadow .2s; outline: none;
        appearance: none; -webkit-appearance: none;
    }
    .fld-ctrl:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
    .fld-ctrl.err { border-color: var(--red); }
    .fld-ctrl[readonly] { background: var(--neutral-50); color: var(--neutral-400); cursor: not-allowed; border-style: dashed; }
    .fld-hint { font-size: .7rem; color: var(--neutral-400); margin-top: 4px; }
    .fld-err  { font-size: .7rem; color: var(--red); margin-top: 4px; display: block; }
    .fld-group { margin-bottom: 16px; }
    .fld-group:last-child { margin-bottom: 0; }
    .fld-row { display: grid; gap: 14px; grid-template-columns: 1fr; }
    @media (min-width: 520px) { .fld-row { grid-template-columns: 1fr 1fr; } }

    .locked-badge {
        display: flex; align-items: center; gap: 10px;
        background: var(--accent-pale); border: 1.5px solid rgba(244,165,53,.4);
        border-radius: var(--radius-sm); padding: 10px 13px; margin-bottom: 8px;
    }
    .locked-badge-lbl { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #92400e; }
    .locked-badge-val { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: #78350f; }
    .locked-badge-sub { font-size: .62rem; color: #b45309; }

    /* SERVICES */
    .services-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
    @media (max-width: 400px) { .services-grid { grid-template-columns: 1fr; } }
    .svc-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; border: 1.5px solid var(--neutral-200);
        border-radius: var(--radius-sm); cursor: pointer;
        transition: all .15s; background: var(--neutral-0); user-select: none;
    }
    .svc-item:hover { border-color: var(--green-300); background: var(--green-50); }
    .svc-item.checked { border-color: var(--green-400); background: var(--green-50); }
    .svc-cb { width: 15px; height: 15px; accent-color: var(--green-500); flex-shrink: 0; cursor: pointer; }
    .svc-name { font-size: .75rem; font-weight: 600; color: var(--neutral-700); line-height: 1.3; }
    .svc-price { font-size: .68rem; font-weight: 700; color: var(--green-600); }

    /* ESTIMASI BOX */
    .est-box { background: var(--green-50); border: 1.5px solid var(--green-200); border-radius: var(--radius-md); padding: 16px; }
    .est-row { display: flex; justify-content: space-between; align-items: center; font-size: .78rem; margin-bottom: 7px; }
    .est-row .lbl { color: var(--neutral-500); }
    .est-row .val { font-weight: 600; }
    .est-total-row { border-top: 1px solid var(--green-200); padding-top: 11px; margin-top: 4px; display: flex; justify-content: space-between; align-items: center; }
    .est-total-lbl { font-weight: 700; font-size: .85rem; }
    .est-total-val { font-family: 'Fraunces', serif; font-size: 1.25rem; font-weight: 700; color: var(--green-600); }
    .est-note { font-size: .62rem; color: var(--neutral-400); margin-top: 8px; }

    .info-box { display: flex; align-items: flex-start; gap: 10px; border-radius: var(--radius-sm); padding: 12px 14px; font-size: .78rem; margin-bottom: 14px; }
    .info-green  { background: var(--green-50); border: 1.5px solid var(--green-200); color: var(--green-700); }
    .info-red    { background: var(--red-pale); border: 1.5px solid rgba(220,38,38,.25); color: #991b1b; }
    .info-blue   { background: var(--blue-pale); border: 1.5px solid rgba(37,99,235,.2); color: #1e40af; }

    /* ROOM PHOTO CAROUSEL */
    .room-photo {
        height: 190px; overflow: hidden; position: relative;
        background: linear-gradient(145deg, var(--green-300), var(--green-700)); flex-shrink: 0;
    }
    .rp-wrap { position: absolute; inset: 0; width: 100%; height: 100%; }
    .rp-slide { position: absolute; inset: 0; opacity: 0; transition: opacity .45s ease; pointer-events: none; }
    .rp-slide.rp-active { opacity: 1; z-index: 1; pointer-events: auto; }
    .rp-slide img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .rp-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.25); font-size: 3rem; }
    .rp-btn {
        position: absolute; top: 50%; transform: translateY(-50%); z-index: 12;
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(0,0,0,.45); backdrop-filter: blur(4px);
        border: none; color: #fff; font-size: 1rem;
        cursor: pointer; opacity: 0; transition: opacity .2s, background .2s;
        display: flex; align-items: center; justify-content: center; padding: 0;
    }
    .rp-btn:hover { background: rgba(0,0,0,.7); }
    .room-photo:hover .rp-btn { opacity: 1; }
    .rp-prev { left: 8px; } .rp-next { right: 8px; }
    .rp-dots { position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); display: flex; gap: 5px; z-index: 12; }
    .rp-dot { width: 6px; height: 6px; border-radius: 100px; background: rgba(255,255,255,.5); border: none; padding: 0; cursor: pointer; transition: all .25s; }
    .rp-dot.rp-dot-active { background: #fff; width: 16px; }
    .rp-count { position: absolute; top: 8px; left: 8px; z-index: 12; background: rgba(0,0,0,.46); backdrop-filter: blur(4px); color: #fff; font-size: .6rem; font-weight: 700; padding: 3px 8px; border-radius: 100px; }
    .rp-progress { position: absolute; top: 0; left: 0; right: 0; height: 2px; background: rgba(255,255,255,.15); z-index: 13; }
    .rp-progress-bar { height: 100%; background: rgba(255,255,255,.65); border-radius: 0 1px 1px 0; width: 0%; transition: width .1s linear; }
    .status-pill { position: absolute; top: 8px; right: 8px; z-index: 13; padding: 4px 11px; border-radius: 100px; font-size: .58rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.2); }
    .pill-av  { background: rgba(42,179,107,.85); color: #fff; }
    .pill-occ { background: rgba(220,38,38,.85);  color: #fff; }
    .pill-res { background: rgba(244,165,53,.85); color: #fff; }
    .pill-mnt { background: rgba(95,122,110,.85); color: #fff; }
    @media (hover: none) { .rp-btn { opacity: .85 !important; } }
    @media (max-width: 768px) { .rp-btn { opacity: .8 !important; width: 26px; height: 26px; font-size: .9rem; } }

    /* ROOM INFO */
    .room-info { padding: 16px; }
    .room-name { font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 700; margin-bottom: 3px; color: var(--neutral-900); }
    .room-price { font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 700; color: var(--green-600); line-height: 1; }
    .room-price small { font-family: 'Instrument Sans', sans-serif; font-size: .7rem; font-weight: 400; color: var(--neutral-400); }
    .room-meta { display: flex; gap: 10px; flex-wrap: wrap; font-size: .7rem; color: var(--neutral-400); margin: 8px 0 10px; }
    .room-meta span { display: flex; align-items: center; gap: 4px; }
    .fac-chips { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 10px; }
    .fac-chip { background: var(--green-50); border: 1px solid var(--green-100); color: var(--green-700); font-size: .58rem; font-weight: 600; padding: 3px 8px; border-radius: 100px; }
    .amenity-bar { background: var(--green-50); border: 1px solid var(--green-100); border-radius: var(--radius-sm); padding: 7px 11px; font-size: .68rem; color: var(--green-700); display: flex; flex-wrap: wrap; gap: 6px; }

    /* PAYMENT METHOD */
    .pm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 4px; }
    @media (max-width: 480px) { .pm-grid { grid-template-columns: 1fr; gap: 8px; } }
    .pm-card {
        position: relative; border: 1.5px solid var(--neutral-200); border-radius: var(--radius-md);
        padding: 13px 13px 12px 42px; cursor: pointer; background: var(--neutral-0);
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
        user-select: none; display: block;
    }
    .pm-card:hover { border-color: var(--green-300); background: var(--green-50); }
    .pm-card.is-selected { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
    .pm-card.is-advance  { border-color: var(--accent); background: var(--accent-pale); box-shadow: 0 0 0 3px rgba(244,165,53,.1); }
    .pm-radio { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; accent-color: var(--green-500); cursor: pointer; flex-shrink: 0; }
    .pm-title  { font-size: .8rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 3px; }
    .pm-desc   { font-size: .68rem; color: var(--neutral-500); line-height: 1.5; }
    .pm-badge  { display: inline-block; margin-top: 7px; font-size: .6rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; padding: 2px 7px; border-radius: 100px; background: var(--green-100); color: var(--green-700); }
    .pm-badge-adv { background: rgba(244,165,53,.2); color: #92400e; }
    .pm-detail { overflow: hidden; max-height: 0; opacity: 0; transition: max-height .35s ease, opacity .25s ease, margin .25s ease; border-radius: var(--radius-sm); margin-top: 0; margin-bottom: 0; }
    .pm-detail.is-open { max-height: 300px; opacity: 1; margin-top: 8px; margin-bottom: 12px; }
    .pm-detail-inner { background: var(--green-50); border: 1.5px solid var(--green-200); border-radius: var(--radius-sm); padding: 12px 14px; }
    .pm-detail-title { font-size: .65rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--green-700); margin-bottom: 8px; }
    .pm-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; font-size: .75rem; border-bottom: 1px dashed var(--neutral-100); }
    .pm-detail-row:last-of-type { border-bottom: none; }
    .pm-detail-row .lbl { color: var(--neutral-500); }
    .pm-detail-row .val { font-weight: 700; color: var(--neutral-800); }
    .pm-detail-total { display: flex; justify-content: space-between; align-items: center; padding-top: 9px; margin-top: 5px; border-top: 1.5px solid var(--green-300); font-size: .8rem; }
    .pm-detail-total .lbl { font-weight: 600; color: var(--neutral-700); }
    .pm-detail-total .val { font-family: 'Fraunces', serif; font-weight: 700; color: var(--green-600); font-size: .92rem; }
    .pm-detail-note { font-size: .63rem; color: var(--neutral-400); margin-top: 7px; line-height: 1.5; }

    /* TERMS */
    .terms-card { border: 1.5px solid var(--neutral-200); border-radius: var(--radius-md); overflow: hidden; }
    .terms-card.terms-error { border-color: var(--red); }
    .terms-card-head { background: linear-gradient(135deg, var(--green-900), var(--green-700)); padding: 12px 16px; font-size: .75rem; font-weight: 700; color: #fff; }
    .terms-body { max-height: 220px; overflow-y: auto; padding: 14px 16px; background: var(--neutral-0); font-size: .72rem; line-height: 1.7; color: var(--neutral-600); }
    .terms-body::-webkit-scrollbar { width: 4px; }
    .terms-body::-webkit-scrollbar-thumb { background: var(--green-300); border-radius: 4px; }
    .terms-article { margin-bottom: 14px; }
    .terms-article:last-child { margin-bottom: 0; }
    .terms-article-title { font-size: .67rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--neutral-800); margin-bottom: 6px; padding-bottom: 5px; border-bottom: 1px solid var(--neutral-100); }
    .terms-article ol { padding-left: 1.1rem; }
    .terms-article ol li { margin-bottom: 4px; }
    .terms-scroll-hint { padding: 7px 16px; background: var(--neutral-50); border-top: 1px solid var(--neutral-100); font-size: .63rem; color: var(--neutral-400); display: flex; align-items: center; gap: 6px; }
    .terms-agree-row { padding: 13px 16px; background: var(--green-50); border-top: 1.5px solid var(--green-200); display: flex; align-items: flex-start; gap: 11px; }
    .terms-agree-cb { width: 16px; height: 16px; flex-shrink: 0; accent-color: var(--green-500); cursor: pointer; margin-top: 2px; }
    .terms-agree-label { font-size: .75rem; font-weight: 600; color: var(--neutral-700); line-height: 1.5; cursor: pointer; }
    .terms-agree-label span { color: var(--green-600); }

    /* WAITING LIST BOX */
    .wl-box { display: flex; align-items: flex-start; gap: 8px; border-radius: var(--radius-sm); padding: 11px 13px; font-size: .75rem; }
    .wl-full  { background: var(--red-pale); border: 1px solid rgba(220,38,38,.2); }
    .wl-full .wl-title { color: var(--red); }
    .wl-full .wl-sub { color: #b91c1c; }
    .wl-open  { background: var(--green-50); border: 1px solid var(--green-200); }
    .wl-open .wl-title { color: var(--green-500); }
    .wl-open .wl-sub { color: var(--green-600); }
    .wl-title { font-weight: 700; }
    .wl-sub   { font-size: .67rem; margin-top: 2px; }

    /* VERIFY BOX */
    .verify-box { background: var(--green-50); border: 1.5px solid var(--green-300); border-radius: var(--radius-md); padding: 14px 20px; }
    .verify-title { font-size: .78rem; font-weight: 700; color: var(--green-800); margin-bottom: 3px; }
    .verify-body  { font-size: .72rem; color: var(--green-700); line-height: 1.6; }
    .verify-body strong { color: var(--green-800); }

    /* SUBMIT AREA — dirapikan */
    .submit-area-wrap {
        max-width: 1280px;
        margin: 0 auto 60px;
        padding: 0 24px;
    }
    .submit-strip {
        border-top: 1px solid var(--green-100);
        padding-top: 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    .submit-strip .verify-box {
        width: 100%;
        max-width: 600px;
        text-align: center;
    }
    .submit-strip .verify-body { text-align: center; }
    .submit-btn-row {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        max-width: 600px;
    }
    @media (max-width: 600px) {
        .submit-btn-row {
            flex-direction: column;
            align-items: stretch;
        }
    }

    /* BUTTONS */
    .btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 13px 36px; border-radius: var(--radius-sm);
        font-family: 'Instrument Sans', sans-serif; font-size: .9rem; font-weight: 700;
        background: linear-gradient(135deg, var(--green-500), var(--green-700));
        color: #fff; border: none; cursor: pointer;
        box-shadow: 0 4px 20px rgba(42,179,107,.35);
        transition: all .25s; text-decoration: none;
        white-space: nowrap; flex: 1; max-width: 280px;
    }
    .btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.45); color: #fff; }
    .btn-primary:disabled { opacity: .45; cursor: not-allowed; transform: none; box-shadow: none; }

    .btn-advance {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 13px 36px; border-radius: var(--radius-sm);
        font-family: 'Instrument Sans', sans-serif; font-size: .9rem; font-weight: 700;
        background: var(--neutral-0); color: var(--accent);
        border: 1.5px solid rgba(244,165,53,.5); cursor: pointer;
        transition: all .25s; text-decoration: none; white-space: nowrap;
        flex: 1; max-width: 280px;
    }
    .btn-advance:hover:not(:disabled) { background: var(--accent-pale); border-color: var(--accent); color: var(--accent); }
    .btn-advance:disabled { opacity: .45; cursor: not-allowed; }

    .btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 12px 28px; border-radius: var(--radius-sm);
        font-family: 'Instrument Sans', sans-serif; font-size: .85rem; font-weight: 600;
        background: var(--neutral-0); color: var(--neutral-500);
        border: 1px solid var(--neutral-200); text-decoration: none; transition: all .2s;
        white-space: nowrap; flex-shrink: 0;
    }
    .btn-ghost:hover { border-color: var(--neutral-300); color: var(--neutral-700); }

    .btn-primary-inline {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 13px 24px; border-radius: var(--radius-sm);
        font-family: 'Instrument Sans', sans-serif; font-size: .85rem; font-weight: 700;
        background: linear-gradient(135deg, var(--green-500), var(--green-700));
        color: #fff; border: none; cursor: pointer;
        box-shadow: 0 4px 16px rgba(42,179,107,.3); transition: all .25s; text-decoration: none;
    }
    .btn-primary-inline:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(42,179,107,.4); color: #fff; }

    @media (max-width: 600px) {
        .btn-primary, .btn-advance { max-width: 100%; padding: 14px 20px; }
        .btn-ghost { width: 100%; }
    }

    /* REVEAL ANIMATION */
    .reveal { opacity: 0; transform: translateY(12px); transition: opacity .5s ease, transform .5s ease; }
    .reveal.in { opacity: 1; transform: none; }
    .d1 { transition-delay: .08s; } .d2 { transition-delay: .16s; } .d3 { transition-delay: .24s; }

    /* FOOTER */
    .uk-footer { background: var(--neutral-900); padding: 20px 24px; text-align: center; }
    .uk-footer p { font-size: .7rem; color: rgba(255,255,255,.2); margin: 0; }

    /* RESPONSIVE PADDING */
    @media (max-width: 480px) {
        .uk-nav-inner { padding: 0 14px; }
        .hero-banner { padding: 22px 16px; }
        .hero-arc { display: none; }
        .hero-title { font-size: 1.25rem; }
        .proses-banner-wrap, .uk-main, .submit-area-wrap, .tenant-info-banner-wrap { padding-left: 14px; padding-right: 14px; }
        .o-card-body { padding: 15px; }
        .services-grid { grid-template-columns: 1fr; }
        .est-total-val { font-size: 1.05rem; }
    }
    @media (min-width: 900px) {
        .uk-main, .proses-banner-wrap, .submit-area-wrap, .tenant-info-banner-wrap { padding-left: 36px; padding-right: 36px; }
    }
    @media (min-width: 1280px) {
        .uk-main, .proses-banner-wrap, .submit-area-wrap, .tenant-info-banner-wrap { padding-left: 48px; padding-right: 48px; }
    }

    .fld-ctrl.ok { border-color: var(--green-400); background: #f9fffe; }

    @keyframes vShake {
        0%,100% { transform: translateX(0); }
        20%,60%  { transform: translateX(-5px); }
        40%,80%  { transform: translateX(5px); }
    }
    .fld-ctrl.shake { animation: vShake .35s ease; }

    .fld-counter {
        display: block; text-align: right; font-size: .65rem;
        color: var(--neutral-400); margin-top: 2px; transition: color .2s;
    }
    .fld-counter.warn { color: var(--amber); }
    .fld-counter.ok   { color: var(--green-500); }

    @keyframes btnPulse { 0%,100%{box-shadow:0 4px 20px rgba(42,179,107,.35)} 50%{box-shadow:0 4px 28px rgba(42,179,107,.6)} }
    #submitBtn:not(:disabled).ready { animation: btnPulse 2s ease infinite; }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

<nav class="uk-nav" id="ukNav">
    <div class="uk-nav-inner">
        <a href="{{ route('home') }}" class="uk-brand">
            <div class="uk-brand-icon"><i class="bi bi-house-heart-fill"></i></div>
            <span class="uk-brand-name">Urban Kost</span>
        </a>
        <a href="{{ route('rooms.show', $room->id) }}" class="btn-nav-back">
            <i class="bi bi-arrow-left"></i>Kembali
        </a>
    </div>
</nav>

<div class="hero-banner">
    <div class="hero-inner">
        <div class="hero-eyebrow">
            <span class="dot"></span>
            {{ $isAdvance ? 'Advance Booking' : 'Booking Kamar' }}
        </div>
        <h1 class="hero-title">
            {{ $isAdvance ? 'Daftar Waiting List' : 'Booking' }}<br>
            <em>Kamar {{ $room->room_number }}</em>
        </h1>
        <p class="hero-sub">
            {{ $isAdvance
                ? 'Kamar sedang ditempati — daftarkan diri Anda ke waiting list'
                : 'Isi formulir untuk mengajukan permohonan booking kamar' }}
        </p>
    </div>
</div>

@if(session('error') || session('warning') || $errors->any())
<div class="alerts-wrap">
    @if(session('error'))
        <div class="uk-alert uk-alert-danger"><i class="bi bi-exclamation-circle-fill"></i><span>{!! session('error') !!}</span></div>
    @endif
    @if(session('warning'))
        <div class="uk-alert uk-alert-warning"><i class="bi bi-exclamation-triangle-fill"></i><span>{!! session('warning') !!}</span></div>
    @endif
    @if($errors->any())
        <div class="uk-alert uk-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            <ul style="margin:0;padding-left:1.1rem">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif
</div>
@endif

@php
    $activeBooking  = $room->bookings->first();
    $tenantName     = $activeBooking?->user?->name ?? null;
    $checkInDate    = $activeBooking?->check_in_date
                        ? \Carbon\Carbon::parse($activeBooking->check_in_date) : null;
    $durationMonths = $activeBooking?->duration_months ?? null;

    $progressPct = 0; $daysLeft = null;
    if ($checkInDate && $tenantEndDate) {
        $totalDays   = max(1, $checkInDate->diffInDays($tenantEndDate));
        $elapsedDays = $checkInDate->diffInDays(now());
        $progressPct = min(100, round(($elapsedDays / $totalDays) * 100));
        $daysLeft    = $tenantEndDate->isPast() ? 0 : (int) now()->diffInDays($tenantEndDate);
    }

    if ($isAdvance && $minCheckInDate instanceof \Carbon\Carbon) {
        $minCheckIn = $minCheckInDate->format('Y-m-d');
        $defaultCheckIn = $minCheckIn;
    } else {
        $minCheckIn = now()->format('Y-m-d');
        $defaultCheckIn = $minCheckIn;
    }

    $defaultCheckInLabel = \Carbon\Carbon::parse($defaultCheckIn)->translatedFormat('d F Y');
    $oldServices = old('services', []);

    $roomImgs = [];
    if ($room->images && $room->images->isNotEmpty()) {
        $roomImgs = $room->images->map(fn($img) => $img->url)->values()->all();
    } elseif ($room->image_path) {
        $roomImgs = [asset('storage/' . $room->image_path)];
    }
    $multiRoomImg = count($roomImgs) > 1;

    $termsHasError = $errors->has('agree_terms');

    $pillClass = match($room->status) {
        'available'   => 'pill-av',
        'occupied'    => 'pill-occ',
        'reserved'    => 'pill-res',
        'maintenance' => 'pill-mnt',
        default       => 'pill-mnt',
    };
    $pillLabel = match($room->status) {
        'available'   => 'Tersedia',
        'occupied'    => 'Terisi',
        'reserved'    => 'Reservasi',
        'maintenance' => 'Perbaikan',
        default       => ucfirst($room->status),
    };
@endphp

{{-- ============================================================
     TENANT INFO BANNER — tampil di atas (advance booking saja)
     Muncul sebelum proses steps, full-width horizontal layout
     ============================================================ --}}
@if($isAdvance && $activeBooking && !($waitingListFull) && !$userAlreadyInWaitingList)
<div class="tenant-info-banner-wrap reveal">
    <div class="tenant-info-banner">
        <div class="tib-header">
            <i class="bi bi-person-fill" style="color:#b45309"></i>
            <span>Penghuni Saat Ini — Kamar {{ $room->room_number }}</span>
            @if($tenantEndDate && !$tenantEndDate->isPast() && $daysLeft !== null)
            <span style="margin-left:auto;background:rgba(244,165,53,.2);color:#92400e;font-size:.6rem;font-weight:700;padding:2px 10px;border-radius:100px;letter-spacing:.05em">
                Sisa {{ $daysLeft }} hari
            </span>
            @endif
        </div>
        <div class="tib-body">
            @if($tenantName)
            <div class="tib-col">
                <div class="tib-lbl">Penghuni</div>
                <div class="tib-val">{{ $tenantName }}</div>
            </div>
            @endif
            @if($checkInDate)
            <div class="tib-col">
                <div class="tib-lbl">Check-in</div>
                <div class="tib-val">{{ $checkInDate->format('d M Y') }}</div>
                <div class="tib-sub">{{ $checkInDate->diffForHumans() }}</div>
            </div>
            @endif
            @if($durationMonths)
            <div class="tib-col">
                <div class="tib-lbl">Durasi Sewa</div>
                <div class="tib-val">{{ $durationMonths }} bulan</div>
            </div>
            @endif
            @if($tenantEndDate)
            <div class="tib-col">
                <div class="tib-lbl">Selesai Sewa</div>
                <div class="tib-val" style="color:{{ $tenantEndDate->isPast() ? 'var(--red)' : ($daysLeft !== null && $daysLeft <= 30 ? 'var(--amber)' : '#166534') }}">
                    {{ $tenantEndDate->format('d M Y') }}
                </div>
                <div class="tib-sub" style="color:{{ $tenantEndDate->isPast() ? 'var(--red)' : '#b45309' }}">
                    {{ $tenantEndDate->isPast() ? 'Sudah berakhir' : $tenantEndDate->diffForHumans() }}
                </div>
            </div>
            @endif
        </div>
        @if($checkInDate && $tenantEndDate)
        <div class="tib-bar-wrap">
            <div class="tib-bar-labels">
                <span>{{ $checkInDate->format('d M Y') }}</span>
                <span>{{ $tenantEndDate->format('d M Y') }}</span>
            </div>
            <div class="tib-bar-track">
                <div class="tib-bar-fill" style="width:{{ $progressPct }}%"></div>
            </div>
            @if(!$tenantEndDate->isPast() && $daysLeft !== null)
            <div class="tib-bar-remain">Progress sewa: {{ $progressPct }}% &nbsp;·&nbsp; Sisa <strong>{{ $daysLeft }} hari</strong> ({{ round($daysLeft/30,1) }} bln)</div>
            @elseif($tenantEndDate->isPast())
            <div class="tib-bar-remain" style="color:var(--red)">Masa sewa sudah berakhir</div>
            @endif
        </div>
        @endif
        @if($tenantEndDate && !$tenantEndDate->isPast())
        <div class="tib-checkin-row">
            <div class="tib-checkin-icon"><i class="bi bi-calendar2-check-fill"></i></div>
            <div>
                <div class="tib-checkin-label">Estimasi check-in Anda</div>
                <div class="tib-checkin-date">{{ \Carbon\Carbon::parse($defaultCheckIn)->translatedFormat('d F Y') }}</div>
                <div class="tib-checkin-note">2 hari setelah penghuni lama selesai (persiapan kamar)</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- PROSES STEPS BANNER --}}
@if(!($isAdvance && $waitingListFull) && !$userAlreadyInWaitingList)
<div class="proses-banner-wrap reveal">
    <div class="proses-banner">
        @if($isAdvance)
        <div class="proses-banner-header adv-header">
            <i class="bi bi-clock-history" style="color:#b45309"></i>
            <span>Alur Advance Booking (Kamar Sedang Ditempati)</span>
        </div>
        <div class="proses-steps-row">
            <div class="proses-step adv-step">
                <div class="proses-step-num">1</div>
                <div class="proses-step-txt"><strong>Kirim Permohonan</strong>Admin review dalam 1×24 jam</div>
            </div>
            <div class="proses-step adv-step">
                <div class="proses-step-num">2</div>
                <div class="proses-step-txt"><strong>Masuk Waiting List</strong>Maks. 1 orang di antrean</div>
            </div>
            <div class="proses-step adv-step">
                <div class="proses-step-num">3</div>
                <div class="proses-step-txt"><strong>Bayar DP</strong>Kunci posisi di waiting list</div>
            </div>
            <div class="proses-step adv-step">
                <div class="proses-step-num">4</div>
                <div class="proses-step-txt"><strong>Kamar Tersedia</strong>Persiapan 2 hari setelah penghuni lama</div>
            </div>
            <div class="proses-step adv-step">
                <div class="proses-step-num">5</div>
                <div class="proses-step-txt"><strong>Lunasi Sisa</strong>Notifikasi WhatsApp, kamar aktif</div>
            </div>
        </div>
        @else
        <div class="proses-banner-header">
            <i class="bi bi-list-check" style="color:var(--green-600)"></i>
            Alur Proses Booking
        </div>
        <div class="proses-steps-row">
            <div class="proses-step">
                <div class="proses-step-num">1</div>
                <div class="proses-step-txt"><strong>Isi Formulir</strong>Lengkapi data diri dan pilih durasi sewa</div>
            </div>
            <div class="proses-step">
                <div class="proses-step-num">2</div>
                <div class="proses-step-txt"><strong>Review Admin</strong>Diproses dalam 1×24 jam</div>
            </div>
            <div class="proses-step">
                <div class="proses-step-num">3</div>
                <div class="proses-step-txt"><strong>Bayar Tagihan</strong>Via Midtrans setelah disetujui</div>
            </div>
            <div class="proses-step">
                <div class="proses-step-num">4</div>
                <div class="proses-step-txt"><strong>Kamar Aktif</strong>Kunci diserahkan setelah pembayaran</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- ROOM CARD: mobile/tablet only (di atas form, tersembunyi di desktop) --}}
<div class="room-card-mobile proses-banner-wrap" style="margin-top:20px">
    <div class="o-card reveal" style="overflow:hidden;margin-bottom:0">
        <div class="room-photo" id="rpPhotoMobile">
            <div class="rp-wrap">
                @if(count($roomImgs) > 0)
                    @foreach($roomImgs as $imgIdx => $imgUrl)
                    <div class="rp-slide {{ $imgIdx === 0 ? 'rp-active' : '' }}">
                        <img src="{{ $imgUrl }}" alt="{{ $room->room_name ?? 'Kamar '.$room->room_number }}" loading="{{ $imgIdx === 0 ? 'eager' : 'lazy' }}">
                    </div>
                    @endforeach
                @else
                    <div class="rp-slide rp-active">
                        <div class="rp-placeholder"><i class="bi bi-house-door-fill"></i></div>
                    </div>
                @endif
            </div>
            <span class="status-pill {{ $pillClass }}">{{ $pillLabel }}</span>
            @if($multiRoomImg)
                <div class="rp-progress"><div class="rp-progress-bar" id="rpBarM"></div></div>
                <button class="rp-btn rp-prev" onclick="rpNavM(-1,event)" aria-label="Foto sebelumnya">&#8249;</button>
                <button class="rp-btn rp-next" onclick="rpNavM(1,event)"  aria-label="Foto berikutnya">&#8250;</button>
                <div class="rp-dots" id="rpDotsM">
                    @foreach($roomImgs as $imgIdx => $imgUrl)
                    <button class="rp-dot {{ $imgIdx === 0 ? 'rp-dot-active' : '' }}" onclick="rpGoToM({{ $imgIdx }},event)" aria-label="Foto {{ $imgIdx + 1 }}"></button>
                    @endforeach
                </div>
                <span class="rp-count" id="rpCountM">1/{{ count($roomImgs) }}</span>
            @endif
        </div>
        <div class="room-info">
            <div class="room-name">{{ $room->room_name ?? 'Kamar '.$room->room_number }}</div>
            <div class="room-price">{{ $room->formatted_price }}<small>/bulan</small></div>
            <div class="room-meta">
                @if($room->floor)<span><i class="bi bi-building"></i>Lantai {{ $room->floor }}</span>@endif
                @if($room->size)<span><i class="bi bi-arrows-fullscreen"></i>{{ $room->size }}</span>@endif
                <span><i class="bi bi-hash"></i>No. {{ $room->room_number }}</span>
            </div>
            @if($room->facilities && count($room->facilities) > 0)
            <div class="fac-chips">
                @foreach(array_slice($room->facilities, 0, 5) as $f)
                    <span class="fac-chip">{{ $f }}</span>
                @endforeach
                @if(count($room->facilities) > 5)<span class="fac-chip">+{{ count($room->facilities)-5 }}</span>@endif
            </div>
            @endif
            <div class="amenity-bar">
                <span><i class="bi bi-check-circle-fill" style="color:var(--green-500)"></i> Air & Listrik</span>
                <span><i class="bi bi-wifi" style="color:var(--green-500)"></i> WiFi 100 Mbps</span>
                <span><i class="bi bi-shield-check-fill" style="color:var(--green-500)"></i> 24 Jam</span>
            </div>
        </div>
    </div>
    @if($isAdvance)
    <div class="o-card reveal" style="margin-top:16px;margin-bottom:0">
        <div class="o-card-header">Status Waiting List</div>
        <div class="o-card-body" style="padding:12px 16px">
            @if($waitingListFull)
            <div class="wl-box wl-full"><div><div class="wl-title">Terisi (1/1)</div><div class="wl-sub">Tidak bisa mendaftar.</div></div></div>
            @else
            <div class="wl-box wl-open"><div><div class="wl-title">Kosong (0/1) — Bisa Daftar</div><div class="wl-sub">Belum ada calon penghuni di waiting list kamar ini.</div></div></div>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- MAIN GRID --}}
<div class="uk-main">
    <div class="uk-grid">

        {{-- KOLOM KIRI: Form --}}
        <div class="left-panel">

            @if($isAdvance && $waitingListFull)
            <div class="o-card reveal">
                <div class="o-card-header">Waiting List Penuh</div>
                <div class="o-card-body">
                    <div class="info-box info-red" style="margin-bottom:20px">
                        <i class="bi bi-x-circle-fill"></i>
                        <div>
                            <strong style="display:block;margin-bottom:3px">Pendaftaran Ditutup</strong>
                            Kamar ini sudah memiliki satu calon penghuni di waiting list. Anda dapat mendaftar setelah posisi kosong.
                        </div>
                    </div>
                    <div style="text-align:center;padding:6px 0">
                        <p style="font-size:.8rem;color:var(--neutral-400);margin-bottom:14px">Cari kamar lain yang tersedia:</p>
                        <a href="{{ route('home') }}#kamar" class="btn-primary-inline">Lihat Kamar Lain</a>
                    </div>
                </div>
            </div>

            @elseif($userAlreadyInWaitingList)
            <div class="o-card reveal">
                <div class="o-card-header">Anda Sudah di Waiting List</div>
                <div class="o-card-body">
                    <div class="info-box info-blue" style="margin-bottom:20px">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong style="display:block;margin-bottom:3px">Permohonan Tercatat</strong>
                            Anda sudah berada di waiting list untuk kamar ini. Pantau status antrean melalui halaman berikut.
                        </div>
                    </div>
                    <a href="{{ route('prospective.waiting-list.index') }}" class="btn-primary-inline">Lihat Status Waiting List</a>
                </div>
            </div>

            @else
            <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">
                @if($isAdvance)<input type="hidden" name="is_advance" value="1">@endif

                {{-- Data Diri --}}
                <div class="o-card reveal d1">
                    <div class="o-card-header">Data Diri Pemohon</div>
                    <div class="o-card-body">
                        <div class="fld-row" style="margin-bottom:16px">
                            <div class="fld-group" style="margin-bottom:0">
                                <label class="fld-label">Nama Lengkap <span class="fld-required">*</span></label>
                                <input type="text" name="full_name"
                                    class="fld-ctrl @error('full_name') err @enderror"
                                    value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                    placeholder="Nama sesuai KTP/KTM" required>
                                @error('full_name')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                            <div class="fld-group" style="margin-bottom:0">
                                <label class="fld-label">No. HP / WhatsApp <span class="fld-required">*</span></label>
                                <input type="text" name="phone"
                                    class="fld-ctrl @error('phone') err @enderror"
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                    placeholder="08xxxxxxxxxx" required>
                                @error('phone')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="fld-row">
                            <div class="fld-group">
                                <label class="fld-label">No. KTP / KTM <span class="fld-optional">(opsional)</span></label>
                                <input type="text" name="identity_number" class="fld-ctrl"
                                    value="{{ old('identity_number') }}" placeholder="Nomor identitas">
                                <span class="fld-counter" id="nikCounter">0 / 16 digit</span>
                            </div>
                            <div class="fld-group">
                                <label class="fld-label">Nomor Darurat <span class="fld-required">*</span></label>
                                <input type="text" name="emergency_contact"
                                    class="fld-ctrl @error('emergency_contact') err @enderror"
                                    value="{{ old('emergency_contact') }}"
                                    placeholder="08xx (orang tua / keluarga)" maxlength="50" required>
                                @error('emergency_contact')<span class="fld-err">{{ $message }}</span>@enderror
                                <span class="fld-hint">Dihubungi hanya saat terjadi keadaan darurat.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="o-card reveal d2">
                    <div class="o-card-header">Jadwal & Durasi</div>
                    <div class="o-card-body">
                        <div class="fld-row">
                            <div class="fld-group">
                                <label class="fld-label">Tanggal Masuk <span class="fld-required">*</span></label>
                                @if($isAdvance && $tenantEndDate && !$tenantEndDate->isPast())
                                <div class="locked-badge">
                                    <div>
                                        <div class="locked-badge-lbl">Paling awal</div>
                                        <div class="locked-badge-val">{{ $defaultCheckInLabel }}</div>
                                        <div class="locked-badge-sub">2 hari setelah penghuni lama selesai</div>
                                    </div>
                                </div>
                                @endif
                                <input type="date" name="check_in_date" id="advCheckIn"
                                    class="fld-ctrl @error('check_in_date') err @enderror"
                                    value="{{ old('check_in_date', $defaultCheckIn) }}"
                                    min="{{ $minCheckIn }}" required>
                                @error('check_in_date')<span class="fld-err">{{ $message }}</span>@enderror
                                @if(!$isAdvance)
                                    <span class="fld-hint">Minimal: hari ini</span>
                                @elseif($tenantEndDate && !$tenantEndDate->isPast())
                                    <span class="fld-hint">Tidak bisa memilih sebelum {{ $defaultCheckInLabel }}</span>
                                @endif
                            </div>
                            <div class="fld-group">
                                <label class="fld-label">Durasi Sewa <span class="fld-required">*</span></label>
                                <select name="duration_months" id="durationSelect"
                                        class="fld-ctrl @error('duration_months') err @enderror"
                                        required onchange="calcBookingTotal()">
                                    @foreach([1,2,3,4,5,6,9,12] as $m)
                                        <option value="{{ $m }}" {{ old('duration_months') == $m ? 'selected' : '' }}>
                                            {{ $m }} bulan{{ $m == 12 ? ' (1 tahun)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('duration_months')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="o-card reveal d3">
                    <div class="o-card-header">Layanan Tambahan <span style="font-weight:400;font-size:.68rem;text-transform:none;letter-spacing:0;color:var(--neutral-400)">(opsional · per bulan)</span></div>
                    <div class="o-card-body">
                        <div class="services-grid">
                            @foreach($additionalServices as $key => $svc)
                            <label class="svc-item {{ in_array($key, $oldServices) ? 'checked' : '' }}" data-price="{{ $svc['price'] }}">
                                <input type="checkbox" name="services[]" value="{{ $key }}"
                                    class="svc-cb service-checkbox" onchange="calcBookingTotal()"
                                    {{ in_array($key, $oldServices) ? 'checked' : '' }}>
                                <div>
                                    <div class="svc-name">{{ $svc['label'] }}</div>
                                    <div class="svc-price">+Rp {{ number_format($svc['price'],0,',','.') }}/bln</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <div class="est-box">
                            <div class="est-row"><span class="lbl">Harga kamar/bulan</span><span class="val">Rp {{ number_format($room->price,0,',','.') }}</span></div>
                            <div class="est-row" id="est-services-row" style="display:none">
                                <span class="lbl">Layanan tambahan/bulan</span>
                                <span class="val" style="color:var(--green-600)" id="est-services">Rp 0</span>
                            </div>
                            <div class="est-row"><span class="lbl">Total/bulan</span><span class="val" id="est-monthly">Rp {{ number_format($room->price,0,',','.') }}</span></div>
                            <div class="est-row"><span class="lbl">Durasi sewa</span><span class="val" id="est-duration">1 bulan</span></div>
                            <div class="est-total-row">
                                <span class="est-total-lbl">Total Estimasi</span>
                                <span class="est-total-val" id="est-total">Rp {{ number_format($room->price,0,',','.') }}</span>
                            </div>
                            <p class="est-note">Estimasi. Invoice resmi diterbitkan setelah booking disetujui.</p>
                        </div>
                    </div>
                </div>

                <div class="o-card reveal">
                    <div class="o-card-header">Pesan / Catatan <span style="font-weight:400;font-size:.68rem;text-transform:none;letter-spacing:0;color:var(--neutral-400)">(opsional)</span></div>
                    <div class="o-card-body">
                        <textarea name="message" class="fld-ctrl" rows="3"
                                placeholder="Informasi tambahan untuk admin...">{{ old('message') }}</textarea>
                    </div>
                </div>

            </form>
            @endif
        </div>

        {{-- KOLOM KANAN: Room card + Payment + Terms --}}
        <div class="right-panel">

            <div class="room-card-desktop">
            <div class="o-card reveal d2" style="overflow:hidden;margin-bottom:20px">
                <div class="room-photo" id="rpPhoto">
                    <div class="rp-wrap">
                        @if(count($roomImgs) > 0)
                            @foreach($roomImgs as $imgIdx => $imgUrl)
                            <div class="rp-slide {{ $imgIdx === 0 ? 'rp-active' : '' }}">
                                <img src="{{ $imgUrl }}" alt="{{ $room->room_name ?? 'Kamar '.$room->room_number }}" loading="{{ $imgIdx === 0 ? 'eager' : 'lazy' }}">
                            </div>
                            @endforeach
                        @else
                            <div class="rp-slide rp-active">
                                <div class="rp-placeholder"><i class="bi bi-house-door-fill"></i></div>
                            </div>
                        @endif
                    </div>
                    <span class="status-pill {{ $pillClass }}">{{ $pillLabel }}</span>
                    @if($multiRoomImg)
                        <div class="rp-progress"><div class="rp-progress-bar" id="rpBar"></div></div>
                        <button class="rp-btn rp-prev" onclick="rpNav(-1, event)" aria-label="Foto sebelumnya">&#8249;</button>
                        <button class="rp-btn rp-next" onclick="rpNav(1, event)"  aria-label="Foto berikutnya">&#8250;</button>
                        <div class="rp-dots" id="rpDots">
                            @foreach($roomImgs as $imgIdx => $imgUrl)
                            <button class="rp-dot {{ $imgIdx === 0 ? 'rp-dot-active' : '' }}" onclick="rpGoTo({{ $imgIdx }}, event)" aria-label="Foto {{ $imgIdx + 1 }}"></button>
                            @endforeach
                        </div>
                        <span class="rp-count" id="rpCount">1/{{ count($roomImgs) }}</span>
                    @endif
                </div>
                <div class="room-info">
                    <div class="room-name">{{ $room->room_name ?? 'Kamar '.$room->room_number }}</div>
                    <div class="room-price">{{ $room->formatted_price }}<small>/bulan</small></div>
                    <div class="room-meta">
                        @if($room->floor)<span><i class="bi bi-building"></i>Lantai {{ $room->floor }}</span>@endif
                        @if($room->size)<span><i class="bi bi-arrows-fullscreen"></i>{{ $room->size }}</span>@endif
                        <span><i class="bi bi-hash"></i>No. {{ $room->room_number }}</span>
                    </div>
                    @if($room->facilities && count($room->facilities) > 0)
                    <div class="fac-chips">
                        @foreach(array_slice($room->facilities, 0, 5) as $f)
                            <span class="fac-chip">{{ $f }}</span>
                        @endforeach
                        @if(count($room->facilities) > 5)
                            <span class="fac-chip">+{{ count($room->facilities)-5 }}</span>
                        @endif
                    </div>
                    @endif
                    <div class="amenity-bar">
                        <span><i class="bi bi-check-circle-fill" style="color:var(--green-500)"></i> Air & Listrik</span>
                        <span><i class="bi bi-wifi" style="color:var(--green-500)"></i> WiFi 100 Mbps</span>
                        <span><i class="bi bi-shield-check-fill" style="color:var(--green-500)"></i> 24 Jam</span>
                    </div>
                </div>
            </div>

            @if($isAdvance)
            <div class="o-card reveal d3" style="margin-bottom:20px">
                <div class="o-card-header">Status Waiting List</div>
                <div class="o-card-body" style="padding:12px 16px">
                    @if($waitingListFull)
                    <div class="wl-box wl-full">
                        <div>
                            <div class="wl-title">Terisi (1/1)</div>
                            <div class="wl-sub">Tidak bisa mendaftar. Periksa kamar lain yang tersedia.</div>
                        </div>
                    </div>
                    @else
                    <div class="wl-box wl-open">
                        <div>
                            <div class="wl-title">Kosong (0/1) — Bisa Daftar</div>
                            <div class="wl-sub">Belum ada calon penghuni di waiting list kamar ini.</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            </div>{{-- /room-card-desktop --}}

            @if(!($isAdvance && $waitingListFull) && !$userAlreadyInWaitingList)
            <div class="o-card reveal" style="margin-bottom:20px">
                <div class="o-card-header">Metode Pembayaran <span class="fld-required" style="margin-left:2px">*</span></div>
                <div class="o-card-body">
                    <div class="pm-grid">
                        <label class="pm-card {{ old('payment_method','lump_sum') === 'lump_sum' ? ($isAdvance ? 'is-advance' : 'is-selected') : '' }}" id="pmCardLumpSum">
                            <input type="radio" name="payment_method" value="lump_sum"
                                   class="pm-radio" id="pmLumpSum" onchange="onPmChange()"
                                   {{ old('payment_method','lump_sum') === 'lump_sum' ? 'checked' : '' }}
                                   required form="bookingForm">
                            <div class="pm-title">Bayar Lunas</div>
                            <div class="pm-desc">
                                @if($isAdvance)DP 50% dari total semua bulan saat disetujui. Sisa dilunasi ketika kamar tersedia.
                                @else Bayar total sewa sekaligus. Satu invoice untuk seluruh durasi.@endif
                            </div>
                            <span class="pm-badge">Satu kali bayar</span>
                        </label>
                        <label class="pm-card {{ old('payment_method') === 'monthly' ? ($isAdvance ? 'is-advance' : 'is-selected') : '' }}" id="pmCardMonthly">
                            <input type="radio" name="payment_method" value="monthly"
                                   class="pm-radio" id="pmMonthly" onchange="onPmChange()"
                                   {{ old('payment_method') === 'monthly' ? 'checked' : '' }}
                                   form="bookingForm">
                            <div class="pm-title">Bayar Bulanan</div>
                            <div class="pm-desc">
                                @if($isAdvance)DP 50% bulan pertama saat disetujui. Bulan berikutnya ditagih otomatis tiap bulan.
                                @else Bayar per bulan. Invoice dikirim otomatis 5 hari sebelum jatuh tempo.@endif
                            </div>
                            <span class="pm-badge {{ $isAdvance ? 'pm-badge-adv' : '' }}">Per bulan</span>
                        </label>
                    </div>

                    @error('payment_method')
                        <span class="fld-err" style="display:block;margin-top:4px;margin-bottom:6px">{{ $message }}</span>
                    @enderror

                    <div class="pm-detail" id="pmDetailLump">
                        <div class="pm-detail-inner">
                            <div class="pm-detail-title">Rincian — Bayar Lunas</div>
                            @if($isAdvance)
                            <div class="pm-detail-row"><span class="lbl">DP sekarang (50% grand total)</span><span class="val" id="dlDP">–</span></div>
                            <div class="pm-detail-row"><span class="lbl">Sisa (saat kamar tersedia)</span><span class="val" id="dlSisa">–</span></div>
                            @endif
                            <div class="pm-detail-total">
                                <span class="lbl">Total Tagihan</span><span class="val" id="dlTotal">–</span>
                            </div>
                            <div class="pm-detail-note">
                                @if($isAdvance)Invoice DP jatuh tempo 7 hari. Sisa ditagih saat admin mengirim notifikasi kamar tersedia.
                                @else Invoice tunggal jatuh tempo 7 hari setelah booking disetujui.@endif
                            </div>
                        </div>
                    </div>

                    <div class="pm-detail" id="pmDetailMonthly">
                        <div class="pm-detail-inner">
                            <div class="pm-detail-title">Rincian — Bayar Bulanan</div>
                            @if($isAdvance)
                            <div class="pm-detail-row"><span class="lbl">DP sekarang (50% bulan pertama)</span><span class="val" id="dmDP">–</span></div>
                            <div class="pm-detail-row"><span class="lbl">Sisa bulan ke-1 (saat kamar tersedia)</span><span class="val" id="dmSisa">–</span></div>
                            <div class="pm-detail-row"><span class="lbl">Bulan ke-2 dan seterusnya</span><span class="val" id="dmMonthly">–</span></div>
                            @else
                            <div class="pm-detail-row"><span class="lbl">Invoice bulan ke-1 (jatuh tempo +7 hari)</span><span class="val" id="dmFirst">–</span></div>
                            <div class="pm-detail-row"><span class="lbl">Bulan ke-2 dan seterusnya</span><span class="val" id="dmMonthly">–</span></div>
                            @endif
                            <div class="pm-detail-total">
                                <span class="lbl">Total sewa keseluruhan</span><span class="val" id="dmTotal">–</span>
                            </div>
                            <div class="pm-detail-note">Invoice bulanan dikirim otomatis 5 hari sebelum tanggal tagihan. Jatuh tempo 7 hari setelah diterbitkan.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="o-card reveal" style="margin-bottom:0">
                <div class="o-card-header">Syarat & Ketentuan</div>
                <div class="o-card-body" style="padding:0">
                    <div class="terms-card {{ $termsHasError ? 'terms-error' : '' }}" style="border:none;border-radius:0">
                        <div class="terms-card-head">Syarat & Ketentuan Sewa Kamar – Urban Kost</div>
                        <div class="terms-body" id="termsBody">
                            <div class="terms-article">
                                <div class="terms-article-title">Pasal 1 — Kewajiban Pembayaran</div>
                                <ol>
                                    <li>Pembayaran sewa wajib dilunasi sesuai invoice yang diterbitkan pada tanggal jatuh tempo yang tertera.</li>
                                    <li>Keterlambatan pembayaran melewati tanggal jatuh tempo dikenakan <strong>denda sebesar 2% per minggu</strong> dari total tagihan yang belum terbayar, dihitung sejak hari pertama keterlambatan.</li>
                                    @if($isAdvance)
                                    <li>Untuk Advance Booking, Penyewa wajib membayar <strong>Uang Muka (DP)</strong> dalam waktu <strong>3×24 jam</strong> setelah permohonan disetujui sebagai pengunci posisi waiting list.</li>
                                    @endif
                                    <li>Urban Kost berhak mencabut hak hunian apabila pembayaran tidak dilakukan tanpa konfirmasi dalam batas waktu yang telah ditetapkan.</li>
                                </ol>
                            </div>
                            <div class="terms-article">
                                <div class="terms-article-title">Pasal 2 — Larangan Pembatalan & Keluar Sepihak</div>
                                <ol>
                                    <li>Setelah pembayaran pertama diproses dan Penyewa telah menghuni kamar, <strong>tidak ada pembatalan atau pengunduran diri sepihak yang diizinkan</strong>.</li>
                                    <li>Penyewa yang meninggalkan kamar sebelum masa sewa berakhir tanpa persetujuan tertulis dari Urban Kost tetap berkewajiban melunasi <strong>seluruh sisa tagihan</strong>.</li>
                                    <li>Pemberitahuan tidak memperpanjang sewa wajib disampaikan minimal <strong>10 hari kalender</strong> sebelum masa sewa berakhir.</li>
                                    <li>Penyewa yang tidak hadir pada tanggal check-in yang disepakati tanpa pemberitahuan dianggap membatalkan booking, dan seluruh pembayaran tidak akan dikembalikan.</li>
                                </ol>
                            </div>
                            <div class="terms-article">
                                <div class="terms-article-title">Pasal 3 — Ketentuan Refund & Waiting List</div>
                                <ol>
                                    <li>Seluruh pembayaran yang telah dilakukan bersifat <strong>tidak dapat dikembalikan (non-refundable)</strong>.</li>
                                    <li>Satu-satunya jalur refund hanya berlaku untuk pembatalan pada status <strong>Waiting List</strong>, sebelum kamar ditetapkan kepada Penyewa.</li>
                                    <li>Refund waiting list dikenakan <strong>denda 10% dari nilai DP</strong>. Sisa DP dikembalikan dalam <strong>7 hari kerja</strong>.</li>
                                    <li>Pembatalan waiting list sebelum DP dibayar tidak dikenakan denda apapun.</li>
                                </ol>
                            </div>
                            <div class="terms-article">
                                <div class="terms-article-title">Pasal 4 — Tata Tertib & Larangan Hunian</div>
                                <ol>
                                    <li>Tamu lawan jenis hanya diizinkan di area ruang tunggu dan taman.</li>
                                    <li>Dilarang membawa siapapun menginap di kamar tanpa izin tertulis dari Urban Kost.</li>
                                    <li>Jam malam berlaku pukul <strong>22.00 – 05.00 WIB</strong>. Selama jam malam, Penyewa wajib menjaga ketenangan.</li>
                                    <li>Dilarang membawa, menyimpan, atau mengonsumsi minuman beralkohol, narkotika, dan zat berbahaya lainnya di seluruh area Urban Kost.</li>
                                    <li>Dilarang membawa hewan peliharaan dalam bentuk apapun ke dalam area Urban Kost.</li>
                                    <li>Penyewa wajib menjaga kebersihan kamar dan fasilitas bersama. Kerusakan akibat kelalaian wajib diganti sepenuhnya.</li>
                                </ol>
                            </div>
                            <div class="terms-article">
                                <div class="terms-article-title">Pasal 5 — Sanksi & Ketentuan Akhir</div>
                                <ol>
                                    <li>Pelanggaran memberikan hak kepada Urban Kost untuk memberikan teguran, menjatuhkan denda, hingga <strong>mengeluarkan Penyewa dari hunian secara sepihak</strong> tanpa kewajiban mengembalikan pembayaran.</li>
                                    <li>Pemalsuan data identitas merupakan tindak pidana dan dapat diproses sesuai hukum yang berlaku.</li>
                                    <li>Segala perselisihan diselesaikan melalui musyawarah antara Penyewa dan pihak Urban Kost.</li>
                                    <li>Dengan menyetujui formulir ini, Penyewa dianggap telah membaca, memahami, dan terikat atas seluruh ketentuan di atas.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="terms-scroll-hint" id="termsScrollHint">
                            <i class="bi bi-arrow-down-circle"></i>
                            <span>Gulir ke bawah untuk membaca seluruh syarat & ketentuan</span>
                        </div>
                        <div class="terms-agree-row">
                            <input type="checkbox" name="agree_terms" id="agreeTerms"
                                   class="terms-agree-cb" value="1"
                                   {{ old('agree_terms') ? 'checked' : '' }}
                                   form="bookingForm">
                            <label for="agreeTerms" class="terms-agree-label">
                                Saya telah membaca, memahami, dan <span>menyetujui seluruh Syarat & Ketentuan</span>
                                di atas. Saya bersedia mematuhi semua ketentuan yang berlaku selama masa sewa.
                            </label>
                        </div>
                    </div>
                    @if($termsHasError)
                    <div style="padding:8px 16px 12px">
                        <span class="fld-err" style="display:flex;align-items:center;gap:5px">
                            <i class="bi bi-exclamation-circle"></i>
                            Anda wajib menyetujui Syarat & Ketentuan untuk melanjutkan.
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- /right-panel --}}

    </div>
</div>

{{-- SUBMIT AREA --}}
@if(!($isAdvance && $waitingListFull) && !$userAlreadyInWaitingList)
<div class="submit-area-wrap reveal">
    <div class="submit-strip">
        <div class="verify-box">
            <div class="verify-title">Proses Verifikasi Permohonan</div>
            <div class="verify-body">
                Permohonan akan diverifikasi admin dalam <strong>maksimal 1×24 jam</strong>.
                Informasi status dikirim ke <strong>nomor WhatsApp terdaftar</strong>. Pastikan nomor aktif.
            </div>
        </div>
        <div class="submit-btn-row">
            @if($isAdvance)
                <button type="submit" id="submitBtn" class="btn-advance" disabled form="bookingForm">
                    <i class="bi bi-clock-history"></i> Kirim Advance Booking
                </button>
            @else
                <button type="submit" id="submitBtn" class="btn-primary" disabled form="bookingForm">
                    <i class="bi bi-send-fill"></i> Kirim Permohonan
                </button>
            @endif
            <a href="{{ route('rooms.show', $room->id) }}" class="btn-ghost">
                <i class="bi bi-x"></i> Batal
            </a>
        </div>
    </div>
</div>
@endif

<footer class="uk-footer" style="margin-top:40px">
    <p>&copy; {{ date('Y') }} Urban Kost Yogyakarta</p>
</footer>

<script>
window.addEventListener('scroll', () => {
    document.getElementById('ukNav').classList.toggle('elevated', window.scrollY > 30);
});

const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold: 0.06 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

function makeCarousel(photoId, dotsId, countId, barId, goToFn, navFn) {
    const photo = document.getElementById(photoId);
    if (!photo) return;
    const slides = photo.querySelectorAll('.rp-slide');
    const total  = slides.length;
    if (total <= 1) return;
    const dotsEl  = dotsId  ? document.getElementById(dotsId)  : null;
    const countEl = countId ? document.getElementById(countId) : null;
    const bar     = barId   ? document.getElementById(barId)   : null;
    let cur=0, autoTimer, progTimer, prog=0;
    const INTERVAL=4000, PROG_STEP=100/(INTERVAL/100);
    function goTo(idx){
        slides[cur].classList.remove('rp-active');
        if(dotsEl) dotsEl.querySelectorAll('.rp-dot')[cur]?.classList.remove('rp-dot-active');
        cur=((idx%total)+total)%total;
        slides[cur].classList.add('rp-active');
        if(dotsEl) dotsEl.querySelectorAll('.rp-dot')[cur]?.classList.add('rp-dot-active');
        if(countEl) countEl.textContent=(cur+1)+'/'+total;
        resetProgress();
    }
    function resetProgress(){ clearInterval(progTimer); prog=0; if(bar) bar.style.width='0%'; progTimer=setInterval(()=>{ prog=Math.min(100,prog+PROG_STEP); if(bar) bar.style.width=prog+'%'; },100); }
    function startAuto(){ clearInterval(autoTimer); autoTimer=setInterval(()=>goTo(cur+1),INTERVAL); }
    function stopAuto(){ clearInterval(autoTimer); clearInterval(progTimer); if(bar) bar.style.width='0%'; }
    if(goToFn) window[goToFn]=function(idx,e){ if(e){e.preventDefault();e.stopPropagation();} stopAuto(); goTo(idx); startAuto(); };
    if(navFn)  window[navFn] =function(dir,e){ if(e){e.preventDefault();e.stopPropagation();} stopAuto(); goTo(cur+dir); startAuto(); };
    photo.addEventListener('mouseenter', stopAuto);
    photo.addEventListener('mouseleave', startAuto);
    let sx=0,sy=0;
    photo.addEventListener('touchstart',e=>{ sx=e.touches[0].clientX; sy=e.touches[0].clientY; stopAuto(); },{passive:true});
    photo.addEventListener('touchend',  e=>{ const dx=sx-e.changedTouches[0].clientX; const dy=Math.abs(sy-e.changedTouches[0].clientY); if(Math.abs(dx)>35&&dy<55) goTo(cur+(dx>0?1:-1)); startAuto(); },{passive:true});
    photo.setAttribute('tabindex','0');
    photo.addEventListener('keydown',e=>{ if(e.key==='ArrowLeft'){stopAuto();goTo(cur-1);startAuto();} if(e.key==='ArrowRight'){stopAuto();goTo(cur+1);startAuto();} });
    resetProgress(); startAuto();
}
makeCarousel('rpPhoto',       'rpDots',  'rpCount',  'rpBar',  'rpGoTo',  'rpNav');
makeCarousel('rpPhotoMobile', 'rpDotsM', 'rpCountM', 'rpBarM', 'rpGoToM', 'rpNavM');

const ROOM_PRICE    = {{ (int) ($room->price ?? 0) }};
const PM_IS_ADVANCE = {{ $isAdvance ? 'true' : 'false' }};

function calcBookingTotal(){
    const durSelect = document.getElementById('durationSelect');
    if (!durSelect) return;
    const duration = parseInt(durSelect.value) || 1;
    let serviceTotal = 0;
    document.querySelectorAll('.service-checkbox:checked').forEach(cb => {
        serviceTotal += parseInt(cb.closest('[data-price]')?.dataset.price || 0);
    });
    const monthly = ROOM_PRICE + serviceTotal, total = monthly * duration;
    const fmt = v => 'Rp ' + v.toLocaleString('id-ID');
    const el  = id => document.getElementById(id);
    if (el('est-services'))     el('est-services').textContent     = fmt(serviceTotal);
    if (el('est-monthly'))      el('est-monthly').textContent      = fmt(monthly);
    if (el('est-duration'))     el('est-duration').textContent     = duration + ' bulan';
    if (el('est-total'))        el('est-total').textContent        = fmt(total);
    if (el('est-services-row')) el('est-services-row').style.display = serviceTotal > 0 ? 'flex' : 'none';
    document.querySelectorAll('.svc-item').forEach(item => {
        const cb = item.querySelector('.service-checkbox');
        item.classList.toggle('checked', !!cb?.checked);
    });
    updatePmDetails();
}

function onPmChange(){
    const isLump = document.getElementById('pmLumpSum')?.checked;
    const cardLump    = document.getElementById('pmCardLumpSum');
    const cardMonthly = document.getElementById('pmCardMonthly');
    const detLump     = document.getElementById('pmDetailLump');
    const detMonthly  = document.getElementById('pmDetailMonthly');
    cardLump?.classList.remove('is-selected','is-advance');
    cardMonthly?.classList.remove('is-selected','is-advance');
    if (isLump) {
        cardLump?.classList.add(PM_IS_ADVANCE ? 'is-advance' : 'is-selected');
        detLump?.classList.add('is-open'); detMonthly?.classList.remove('is-open');
    } else {
        cardMonthly?.classList.add(PM_IS_ADVANCE ? 'is-advance' : 'is-selected');
        detMonthly?.classList.add('is-open'); detLump?.classList.remove('is-open');
    }
    updatePmDetails();
}

function updatePmDetails(){
    const dur = parseInt(document.getElementById('durationSelect')?.value || 1);
    let svcTotal = 0;
    document.querySelectorAll('.service-checkbox:checked').forEach(cb => {
        svcTotal += parseInt(cb.closest('[data-price]')?.dataset.price || 0);
    });
    const perMonth   = ROOM_PRICE + svcTotal;
    const grandTotal = perMonth * dur;
    const dpLump     = Math.round(grandTotal * 0.5), sisaLump = grandTotal - dpLump;
    const dpMonthly  = Math.round(perMonth   * 0.5), sisaMn   = perMonth - dpMonthly;
    const rp  = v => 'Rp\u00a0' + v.toLocaleString('id-ID');
    const el  = id => document.getElementById(id);
    if (el('dlDP'))      el('dlDP').textContent      = rp(dpLump);
    if (el('dlSisa'))    el('dlSisa').textContent    = rp(sisaLump);
    if (el('dlTotal'))   el('dlTotal').textContent   = rp(grandTotal);
    if (el('dmDP'))      el('dmDP').textContent      = rp(dpMonthly);
    if (el('dmSisa'))    el('dmSisa').textContent    = rp(sisaMn);
    if (el('dmFirst'))   el('dmFirst').textContent   = rp(perMonth);
    if (el('dmMonthly')) el('dmMonthly').textContent = rp(perMonth) + '/bln';
    if (el('dmTotal'))   el('dmTotal').textContent   = rp(grandTotal);
}

(function(){
    const body = document.getElementById('termsBody');
    const hint = document.getElementById('termsScrollHint');
    if (!body || !hint) return;
    body.addEventListener('scroll', () => {
        if (body.scrollHeight - body.scrollTop <= body.clientHeight + 10) hint.style.display = 'none';
    }, { passive: true });
})();

const RULES = {
    full_name: {
        required: true, minLen: 3, maxLen: 100, label: 'Nama Lengkap',
        messages: {
            required: 'Nama lengkap wajib diisi.',
            minLen:   'Nama minimal 3 karakter.',
            maxLen:   'Nama terlalu panjang (maks. 100 karakter).',
            pattern:  'Nama hanya boleh mengandung huruf dan spasi.',
        },
    },
    phone: {
        required: true, pattern: /^08\d{8,12}$/, label: 'No. HP / WhatsApp',
        messages: { required: 'Nomor HP wajib diisi.', pattern: 'Harus diawali 08 dan terdiri dari 10–14 digit angka.' },
        sanitize: v => v.replace(/[\s\-().+]/g, ''), inputmode: 'tel',
    },
    identity_number: {
        required: false, label: 'No. KTP / KTM', pattern: /^\d{9,16}$/,
        messages: { pattern: 'Nomor identitas harus 9–16 digit angka.' },
        onlyDigits: true, maxlength: 16, sanitize: v => v.replace(/\s/g, ''),
    },
    emergency_contact: {
        required: true, pattern: /^08\d{8,12}$/, label: 'Nomor Darurat',
        messages: { required: 'Nomor darurat wajib diisi.', pattern: 'Harus diawali 08 dan terdiri dari 10–14 digit angka.' },
        sanitize: v => v.replace(/[\s\-().+]/g, ''), inputmode: 'tel',
    },
    check_in_date: {
        required: true, label: 'Tanggal Masuk',
        messages: { required: 'Tanggal masuk wajib diisi.', min: 'Tanggal masuk tidak boleh sebelum tanggal minimum.' },
    },
};

function fieldErr(name, msg) {
    const input = document.querySelector(`[name="${name}"]`);
    if (!input) return;
    input.classList.remove('shake');
    void input.offsetWidth;
    if (msg) {
        input.classList.add('err'); input.classList.remove('ok');
        void input.offsetWidth; input.classList.add('shake');
        let errEl = input.parentElement.querySelector('.fld-err[data-v]');
        if (!errEl) {
            errEl = document.createElement('span');
            errEl.className = 'fld-err'; errEl.setAttribute('data-v', '1');
            const counter = input.parentElement.querySelector('.fld-counter');
            (counter || input).after(errEl);
        }
        errEl.textContent = msg;
    } else {
        input.classList.remove('err'); input.classList.add('ok'); input.classList.remove('shake');
        input.parentElement.querySelector('.fld-err[data-v]')?.remove();
    }
}

function validateField(name, rawValue) {
    const rule = RULES[name];
    if (!rule) return true;
    const val = (rule.sanitize ? rule.sanitize(rawValue) : rawValue).trim();
    if (rule.required && !val)           { fieldErr(name, rule.messages.required); return false; }
    if (!val && !rule.required)          { fieldErr(name, null); return true; }
    if (rule.minLen && val.length < rule.minLen) { fieldErr(name, rule.messages.minLen); return false; }
    if (rule.maxLen && val.length > rule.maxLen) { fieldErr(name, rule.messages.maxLen); return false; }
    if (rule.pattern && !rule.pattern.test(val)) { fieldErr(name, rule.messages.pattern); return false; }
    if (name === 'check_in_date') {
        const input = document.querySelector('[name="check_in_date"]');
        if (input?.min && val < input.min) { fieldErr(name, rule.messages.min); return false; }
    }
    fieldErr(name, null); return true;
}

function validateAll() {
    let ok = true;
    Object.keys(RULES).forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (!input) return;
        if (!validateField(name, input.value)) ok = false;
    });
    const terms = document.getElementById('agreeTerms');
    if (terms && !terms.checked) {
        ok = false;
        document.querySelector('.terms-card')?.classList.add('terms-error');
    } else {
        document.querySelector('.terms-card')?.classList.remove('terms-error');
    }
    return ok;
}

function updateNikCounter(val) {
    const counter = document.getElementById('nikCounter');
    if (!counter) return;
    const len = val.replace(/\D/g,'').length;
    counter.textContent = `${len} / 16 digit`;
    counter.className = 'fld-counter ' + (len === 0 ? '' : len < 10 ? 'warn' : 'ok');
}

function syncSubmitBtn() {
    const btn = document.getElementById('submitBtn');
    if (!btn) return;
    const terms = document.getElementById('agreeTerms');
    let ready = !!(terms?.checked);
    if (ready) {
        Object.keys(RULES).forEach(name => {
            const rule  = RULES[name];
            if (!rule.required) return;
            const input = document.querySelector(`[name="${name}"]`);
            if (!input) return;
            const val = (rule.sanitize ? rule.sanitize(input.value) : input.value).trim();
            if (!val) ready = false;
        });
    }
    btn.disabled = !ready;
    btn.classList.toggle('ready', ready);
}

function attachLiveValidation() {
    Object.keys(RULES).forEach(name => {
        const rule  = RULES[name];
        const input = document.querySelector(`[name="${name}"]`);
        if (!input) return;
        if (rule.inputmode) input.setAttribute('inputmode', rule.inputmode);
        if (rule.maxlength) input.setAttribute('maxlength', String(rule.maxlength));
        if (rule.onlyDigits) input.setAttribute('inputmode', 'numeric');
        if (rule.onlyDigits) {
            input.addEventListener('input', () => {
                const stripped = input.value.replace(/\D/g, '');
                if (input.value !== stripped) input.value = stripped;
                updateNikCounter(input.value); validateField(name, input.value); syncSubmitBtn();
            });
            input.addEventListener('keydown', e => {
                const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Enter'];
                if (!allowed.includes(e.key) && !/^\d$/.test(e.key) && !e.ctrlKey && !e.metaKey) e.preventDefault();
            });
            input.addEventListener('paste', e => {
                e.preventDefault();
                const pasted  = (e.clipboardData || window.clipboardData).getData('text');
                const digits  = pasted.replace(/\D/g, '').slice(0, rule.maxlength || 16);
                const start   = input.selectionStart, end = input.selectionEnd;
                const current = input.value;
                input.value = (current.slice(0, start) + digits + current.slice(end)).slice(0, rule.maxlength || 16);
                updateNikCounter(input.value); validateField(name, input.value); syncSubmitBtn();
            });
        } else {
            input.addEventListener('input', () => { validateField(name, input.value); syncSubmitBtn(); });
        }
        input.addEventListener('blur', () => { validateField(name, input.value); syncSubmitBtn(); });
    });
    document.getElementById('agreeTerms')?.addEventListener('change', () => {
        const terms = document.getElementById('agreeTerms');
        if (terms.checked) document.querySelector('.terms-card')?.classList.remove('terms-error');
        syncSubmitBtn();
    });
}

document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
    if (!validateAll()) {
        e.preventDefault();
        const firstErr = document.querySelector('.fld-ctrl.err');
        const termsErr = document.querySelector('.terms-card.terms-error');
        (firstErr || termsErr)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    calcBookingTotal(); onPmChange(); attachLiveValidation();
    Object.keys(RULES).forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input?.value) validateField(name, input.value);
    });
    const nikInput = document.querySelector('[name="identity_number"]');
    if (nikInput) updateNikCounter(nikInput.value);
    syncSubmitBtn();
    @if($errors->has('agree_terms'))
    setTimeout(() => {
        document.querySelector('.terms-card')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 400);
    @endif
});
</script>
</body>
</html>