<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tiket — Urban Kost</title>
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
            --accent:      #f4a535;
            --accent-pale: #fffbf0;
            --blue:        #2563eb;
            --blue-pale:   #eff6ff;
            --blue-border: #bfdbfe;
            --red:         #dc2626;
            --red-pale:    #fef2f2;
            --red-border:  #fca5a5;
            --amber:       #f59e0b;
            --amber-pale:  #fffbeb;
            --amber-border:#fde68a;
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

        .page-bg { position: fixed; inset: 0; z-index: -1; background: var(--green-50); }
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

        .page-wrap { max-width: 1280px; margin: 0 auto; padding: 32px 24px 64px; }

        .breadcrumb-bar {
            display: flex; align-items: center; gap: 6px;
            margin-bottom: 20px; font-size: .74rem; color: var(--neutral-400);
        }
        .breadcrumb-bar a { color: var(--green-600); text-decoration: none; font-weight: 600; transition: color .2s; }
        .breadcrumb-bar a:hover { color: var(--green-700); }
        .breadcrumb-bar i { font-size: .6rem; color: var(--neutral-200); }
        .breadcrumb-bar span { color: var(--neutral-700); font-weight: 600; }

        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 24px; gap: 12px; flex-wrap: wrap;
        }
        .btn-back {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--neutral-0); color: var(--neutral-600); font-weight: 600;
            font-size: .8rem; padding: 9px 18px; border-radius: 10px;
            border: 1px solid var(--neutral-200); text-decoration: none; transition: all .15s; white-space: nowrap;
        }
        .btn-back:hover { border-color: var(--green-300); color: var(--green-700); background: var(--green-50); }

        .hero-banner {
            position: relative; overflow: hidden; border-radius: 20px;
            background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
            padding: 36px 32px; margin-bottom: 24px;
            box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
        }
        .hero-banner::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
                radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
            pointer-events: none;
        }
        .hero-arc {
            position: absolute; right: -100px; top: -100px;
            width: 380px; height: 380px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,.1); pointer-events: none; z-index: 1;
        }
        .hero-inner { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
            padding: 5px 14px; border-radius: 100px;
            font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.82); margin-bottom: 12px;
        }
        .hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
        .hero-ticket-num { font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.65); margin-bottom: 6px; }
        .hero-subject {
            font-family: 'Fraunces', serif; font-size: clamp(1.1rem, 2.5vw, 1.6rem);
            font-weight: 700; color: #fff; letter-spacing: -.025em;
            line-height: 1.2; max-width: 560px;
        }
        .hero-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; }
        .hero-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.22);
            border-radius: 8px; padding: 4px 11px; font-size: .72rem; font-weight: 600;
            color: rgba(255,255,255,.9);
        }
        .hero-right { text-align: right; flex-shrink: 0; }
        .hero-priority-label { font-size: .66rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 6px; }
        .priority-pill { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; border-radius: 100px; font-size: .75rem; font-weight: 700; }
        .priority-low      { background: rgba(255,255,255,.2);    color: #fff;     border: 1px solid rgba(255,255,255,.35); }
        .priority-normal   { background: rgba(37,99,235,.35);     color: #dbeafe;  border: 1px solid rgba(96,165,250,.5); }
        .priority-high     { background: rgba(245,158,11,.35);    color: #fef3c7;  border: 1px solid rgba(245,158,11,.5); }
        .priority-urgent   { background: rgba(220,38,38,.35);     color: #fee2e2;  border: 1px solid rgba(220,38,38,.5); }
        .hero-status-badge { margin-top: 8px; display: flex; justify-content: flex-end; }
        .hs-open        { display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,.25); color: #fff; border: 1px solid rgba(255,255,255,.4); padding: 4px 12px; border-radius: 100px; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .hs-in-progress { display: inline-flex; align-items: center; gap: 5px; background: rgba(96,165,250,.3); color: #dbeafe; border: 1px solid rgba(96,165,250,.5); padding: 4px 12px; border-radius: 100px; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .hs-resolved    { display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,.2); color: #d1fae5; border: 1px solid rgba(255,255,255,.3); padding: 4px 12px; border-radius: 100px; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .hs-closed      { display: inline-flex; align-items: center; gap: 5px; background: rgba(0,0,0,.2); color: rgba(255,255,255,.7); border: 1px solid rgba(255,255,255,.15); padding: 4px 12px; border-radius: 100px; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }

        .detail-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

        .d-card {
            background: var(--neutral-0); border-radius: 16px;
            border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
            overflow: hidden; margin-bottom: 18px;
        }
        .d-card:last-child { margin-bottom: 0; }
        .d-card-head {
            padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
            background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
            display: flex; align-items: center; gap: 10px;
        }
        .d-card-icon {
            width: 32px; height: 32px; border-radius: 9px;
            background: var(--green-100); color: var(--green-700);
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .d-card-icon.blue   { background: var(--blue-pale);   color: var(--blue); }
        .d-card-icon.amber  { background: var(--amber-pale);  color: #92400e; }
        .d-card-icon.red    { background: var(--red-pale);    color: var(--red); }
        .d-card-title { font-size: .83rem; font-weight: 700; color: var(--neutral-800); }
        .d-card-sub   { font-size: .69rem; color: var(--neutral-400); margin-top: 1px; }
        .d-card-body  { padding: 20px; }

        .desc-box {
            background: var(--neutral-50); border: 1px solid var(--neutral-200);
            border-radius: 10px; padding: 16px; font-size: .84rem;
            color: var(--neutral-700); line-height: 1.7; margin-bottom: 14px;
        }
        .attach-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--blue-pale); color: var(--blue); font-weight: 600; font-size: .76rem;
            padding: 6px 14px; border-radius: 8px; border: 1px solid var(--blue-border);
            text-decoration: none; transition: all .15s;
        }
        .attach-btn:hover { background: #dbeafe; color: #1d4ed8; }

        .chat-wrap {
            padding: 20px; display: flex; flex-direction: column; gap: 18px;
            max-height: 520px; overflow-y: auto;
        }
        .chat-wrap::-webkit-scrollbar { width: 4px; }
        .chat-wrap::-webkit-scrollbar-track { background: transparent; }
        .chat-wrap::-webkit-scrollbar-thumb { background: var(--neutral-200); border-radius: 4px; }

        .msg-row { display: flex; gap: 10px; align-items: flex-start; }
        .msg-row.admin { flex-direction: row-reverse; }
        .msg-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .78rem; color: #fff; flex-shrink: 0;
        }
        .msg-avatar.tenant  { background: linear-gradient(135deg, var(--green-500), var(--green-700)); }
        .msg-avatar.admin-av { background: linear-gradient(135deg, var(--green-800), var(--green-600)); }
        .msg-body { max-width: 76%; }
        .msg-meta { display: flex; align-items: center; gap: 6px; margin-bottom: 5px; font-size: .69rem; color: var(--neutral-400); }
        .msg-row.admin .msg-meta { flex-direction: row-reverse; }
        .msg-name { font-weight: 700; color: var(--neutral-600); }
        .msg-role { display: inline-flex; align-items: center; padding: 1px 6px; border-radius: 100px; font-size: .6rem; font-weight: 700; }
        .msg-role.admin-role  { background: var(--green-100); color: var(--green-700); }
        .msg-role.tenant-role { background: var(--neutral-100); color: var(--neutral-600); }
        .msg-bubble { padding: 11px 14px; font-size: .82rem; line-height: 1.6; }
        .msg-row.tenant .msg-bubble {
            background: var(--neutral-100); border-radius: 4px 14px 14px 14px;
            color: var(--neutral-800);
        }
        .msg-row.admin .msg-bubble {
            background: linear-gradient(135deg, var(--green-800), var(--green-600));
            color: #fff; border-radius: 14px 4px 14px 14px;
        }
        .chat-empty { text-align: center; padding: 36px 20px; color: var(--neutral-400); }
        .chat-empty i { font-size: 1.8rem; display: block; color: var(--green-200); margin-bottom: 10px; }
        .chat-empty span { font-size: .8rem; }

        .reply-bar { padding: 14px 16px; border-top: 1px solid var(--neutral-100); background: var(--neutral-50); }
        .reply-inner { display: flex; gap: 8px; align-items: flex-end; }
        .reply-textarea {
            flex: 1; padding: 9px 13px; border: 1.5px solid var(--neutral-200);
            border-radius: 10px; font-size: .81rem; font-family: 'Instrument Sans', sans-serif;
            color: var(--neutral-800); resize: none; min-height: 60px; outline: none;
            transition: border-color .15s, box-shadow .15s; background: var(--neutral-0);
        }
        .reply-textarea:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
        .reply-send {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--green-500); color: #fff; border: none;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: .9rem; flex-shrink: 0; transition: all .2s;
            box-shadow: 0 4px 12px rgba(42,179,107,.3);
        }
        .reply-send:hover { background: var(--green-600); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(42,179,107,.4); }

        .reply-locked {
            padding: 10px 16px; background: var(--neutral-50); border-top: 1px solid var(--neutral-100);
            text-align: center; font-size: .74rem; color: var(--neutral-400); font-weight: 600;
        }

        .resolve-card {
            background: var(--neutral-0); border-radius: 16px;
            border: 2px solid var(--green-300); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 18px;
        }
        .resolve-head {
            padding: 14px 20px; border-bottom: 1px solid var(--green-200);
            background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
            display: flex; align-items: center; gap: 10px;
        }
        .resolve-head-icon {
            width: 32px; height: 32px; border-radius: 9px;
            background: var(--green-100); color: var(--green-700);
            display: flex; align-items: center; justify-content: center; font-size: .88rem;
        }
        .resolve-head-title { font-size: .83rem; font-weight: 700; color: var(--green-800); }
        .resolve-body { padding: 18px 20px; }
        .resolve-hint { font-size: .76rem; color: var(--neutral-500); margin-bottom: 14px; line-height: 1.55; display: flex; align-items: flex-start; gap: 6px; }
        .resolve-hint i { color: var(--green-600); margin-top: 2px; flex-shrink: 0; }
        .resolve-label { font-size: .73rem; font-weight: 700; color: var(--neutral-700); display: block; margin-bottom: 5px; }
        .resolve-label span { font-weight: 400; color: var(--neutral-400); }
        .resolve-textarea {
            width: 100%; padding: 9px 12px; border: 1.5px solid var(--neutral-200);
            border-radius: 9px; font-size: .8rem; font-family: 'Instrument Sans', sans-serif;
            color: var(--neutral-800); resize: vertical; min-height: 72px; outline: none;
            transition: border-color .15s; background: var(--neutral-0);
        }
        .resolve-textarea:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
        .btn-resolve {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 11px; background: var(--green-500); color: #fff;
            font-weight: 700; font-size: .84rem; font-family: 'Instrument Sans', sans-serif;
            border: none; border-radius: 10px; cursor: pointer; margin-top: 12px;
            box-shadow: 0 4px 12px rgba(42,179,107,.28); transition: all .2s;
        }
        .btn-resolve:hover { background: var(--green-600); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(42,179,107,.38); }

        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 9px 0; font-size: .79rem; border-bottom: 1px solid var(--neutral-100);
        }
        .info-row:last-child { border-bottom: none; }
        .ir-label { color: var(--neutral-500); font-weight: 500; }
        .ir-val   { font-weight: 700; color: var(--neutral-800); text-align: right; }

        .st-open        { display: inline-flex; align-items: center; gap: 3px; background: var(--green-50);    color: var(--green-700); padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--green-200); }
        .st-in-progress { display: inline-flex; align-items: center; gap: 3px; background: var(--blue-pale);  color: var(--blue);      padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--blue-border); }
        .st-resolved    { display: inline-flex; align-items: center; gap: 3px; background: var(--green-100);  color: var(--green-800); padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--green-200); }
        .st-closed      { display: inline-flex; align-items: center; gap: 3px; background: var(--neutral-100); color: var(--neutral-500); padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--neutral-200); }

        .pr-low    { display: inline-flex; align-items: center; gap: 3px; background: var(--green-50);   color: var(--green-700); padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--green-100); }
        .pr-normal { display: inline-flex; align-items: center; gap: 3px; background: var(--blue-pale);  color: var(--blue);      padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--blue-border); }
        .pr-high   { display: inline-flex; align-items: center; gap: 3px; background: var(--amber-pale); color: #92400e;          padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--amber-border); }
        .pr-urgent { display: inline-flex; align-items: center; gap: 3px; background: var(--red-pale);   color: var(--red);       padding: 3px 9px; border-radius: 100px; font-size: .65rem; font-weight: 700; border: 1px solid var(--red-border); }

        .assign-select {
            width: 100%; padding: 8px 12px; border: 1.5px solid var(--neutral-200);
            border-radius: 9px; font-size: .79rem; font-family: 'Instrument Sans', sans-serif;
            color: var(--neutral-700); background: var(--neutral-0); outline: none;
            margin-bottom: 8px; transition: border-color .15s; appearance: none; cursor: pointer;
        }
        .assign-select:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
        .btn-assign {
            width: 100%; padding: 8px; background: var(--green-50); color: var(--green-700);
            font-weight: 700; font-size: .78rem; font-family: 'Instrument Sans', sans-serif;
            border: 1.5px solid var(--green-300); border-radius: 9px; cursor: pointer; transition: all .15s;
        }
        .btn-assign:hover { background: var(--green-100); border-color: var(--green-400); }
        .assigned-note { display: flex; align-items: center; gap: 5px; font-size: .74rem; color: var(--green-700); font-weight: 600; margin-top: 10px; }

        .tenant-avatar {
            width: 52px; height: 52px; border-radius: 50%;
            background: linear-gradient(135deg, var(--green-500), var(--green-700));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.2rem; font-weight: 700;
            font-family: 'Fraunces', serif; margin: 0 auto 12px;
            box-shadow: 0 4px 12px rgba(42,179,107,.3);
        }
        .tenant-name-big  { font-size: .88rem; font-weight: 700; color: var(--neutral-800); text-align: center; }
        .tenant-email-sm  { font-size: .71rem; color: var(--neutral-400); text-align: center; margin-top: 3px; }

        .rating-wrap { display: flex; align-items: center; justify-content: center; gap: 3px; margin-top: 10px; font-size: .88rem; }

        .mttr-chip { display: inline-flex; align-items: center; gap: 4px; background: var(--green-50); border: 1px solid var(--green-200); border-radius: 7px; padding: 3px 9px; font-size: .72rem; font-weight: 700; color: var(--green-700); }
        .mttr-chip.pending { background: var(--amber-pale); border-color: var(--amber-border); color: #92400e; }

        .flash {
            display: flex; align-items: center; gap: 10px; padding: 12px 16px;
            border-radius: 10px; margin-bottom: 16px; font-size: .82rem; font-weight: 500; border: 1px solid;
        }
        .flash.success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
        .flash.error   { background: var(--red-pale); border-color: var(--red-border); color: var(--red); }

        .reveal { opacity: 0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease; }
        .reveal.in { opacity: 1; transform: none; }
        .d1 { transition-delay: .05s; } .d2 { transition-delay: .1s; } .d3 { transition-delay: .15s; } .d4 { transition-delay: .2s; }

        @media (max-width: 960px) { .detail-grid { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .page-wrap { padding: 16px 16px 48px; }
            .hero-banner { padding: 24px 20px; }
            .hero-arc { display: none; }
            .hero-inner { flex-direction: column; gap: 12px; }
            .hero-right { text-align: left; }
            .hero-status-badge { justify-content: flex-start; }
            .chat-wrap { max-height: 360px; }
            .d-card-body { padding: 14px; }
        }
        @media (max-width: 480px) {
            .page-wrap { padding: 14px 12px 48px; }
            .hero-subject { font-size: 1.1rem; }
            .hero-banner { padding: 20px 16px; }
        }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')
    <div class="page-wrap">

        <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.tickets.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="flash success reveal d1"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="flash error reveal d1"><i class="bi bi-exclamation-circle-fill"></i>{!! session('error') !!}</div>
    @endif

    @php
        $statusInfo = match($ticket->status) {
            'open'        => ['cls' => 'hs-open',        'txt' => 'Terbuka',   'icon' => 'bi-folder2-open'],
            'in_progress' => ['cls' => 'hs-in-progress', 'txt' => 'Ditangani', 'icon' => 'bi-gear-fill'],
            'resolved'    => ['cls' => 'hs-resolved',    'txt' => 'Selesai',   'icon' => 'bi-check-circle-fill'],
            'closed'      => ['cls' => 'hs-closed',      'txt' => 'Ditutup',   'icon' => 'bi-lock-fill'],
            default       => ['cls' => 'hs-open',        'txt' => ucfirst($ticket->status), 'icon' => 'bi-circle'],
        };
        $priorityInfo = match($ticket->priority ?? 'low') {
            'low'    => ['cls' => 'priority-low',    'txt' => 'Rendah',  'icon' => 'bi-arrow-down'],
            'normal' => ['cls' => 'priority-normal', 'txt' => 'Normal',  'icon' => 'bi-dash'],
            'high'   => ['cls' => 'priority-high',   'txt' => 'Tinggi',  'icon' => 'bi-arrow-up'],
            'urgent' => ['cls' => 'priority-urgent', 'txt' => 'Urgent',  'icon' => 'bi-exclamation-triangle-fill'],
            default  => ['cls' => 'priority-low',    'txt' => ucfirst($ticket->priority ?? 'low'), 'icon' => 'bi-circle'],
        };
    @endphp

    <div class="hero-banner reveal d2">
        <div class="hero-inner">
            <div>
                <div class="hero-eyebrow">
                    <span class="dot"></span>
                    <a href="{{ route('admin.tickets.index') }}" style="color:inherit;text-decoration:none">Tiket Keluhan</a>
                    &nbsp;/&nbsp; Detail
                </div>
                <div class="hero-ticket-num">{{ $ticket->ticket_number }}</div>
                <div class="hero-subject">{{ $ticket->subject }}</div>
                <div class="hero-meta">
                    <div class="hero-chip"><i class="bi bi-tag-fill"></i>{{ ucfirst($ticket->category) }}</div>
                    <div class="hero-chip"><i class="bi bi-person-fill"></i>{{ $ticket->user?->name }}</div>
                    <div class="hero-chip"><i class="bi bi-calendar3"></i>{{ $ticket->created_at->format('d M Y') }}</div>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-priority-label">Prioritas</div>
                <span class="priority-pill {{ $priorityInfo['cls'] }}">
                    <i class="bi {{ $priorityInfo['icon'] }}"></i>
                    {{ $priorityInfo['txt'] }}
                </span>
                <div class="hero-status-badge">
                    <span class="{{ $statusInfo['cls'] }}">
                        <i class="bi {{ $statusInfo['icon'] }}"></i>
                        {{ $statusInfo['txt'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-grid reveal d3">
        <div>
            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-file-text-fill"></i></div>
                    <div>
                        <div class="d-card-title">Deskripsi Keluhan</div>
                        <div class="d-card-sub">Laporan asli dari penghuni</div>
                    </div>
                </div>
                <div class="d-card-body">
                    <div class="desc-box">{{ $ticket->description }}</div>
                    @if($ticket->attachment)
                    <a href="{{ asset('storage/'.$ticket->attachment) }}" target="_blank" class="attach-btn">
                        <i class="bi bi-paperclip"></i> Lihat Lampiran
                    </a>
                    @endif
                </div>
            </div>

            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon blue"><i class="bi bi-chat-dots-fill"></i></div>
                    <div>
                        <div class="d-card-title">Percakapan</div>
                        <div class="d-card-sub">{{ $replies->count() }} pesan</div>
                    </div>
                </div>
                <div class="chat-wrap" id="chatWrap">
                    @forelse($replies as $reply)
                    @php $isAdmin = $reply->user?->role?->name !== 'tenant'; @endphp
                    <div class="msg-row {{ $isAdmin ? 'admin' : 'tenant' }}">
                        <div class="msg-avatar {{ $isAdmin ? 'admin-av' : 'tenant' }}">
                            {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="msg-body">
                            <div class="msg-meta">
                                <span class="msg-name">{{ $reply->user?->name }}</span>
                                <span class="msg-role {{ $isAdmin ? 'admin-role' : 'tenant-role' }}">
                                    {{ $isAdmin ? 'Admin' : 'Penghuni' }}
                                </span>
                                <span>{{ $reply->created_at->format('d M') }}</span>
                            </div>
                            <div class="msg-bubble">{!! nl2br(e($reply->message)) !!}</div>
                        </div>
                    </div>
                    @empty
                    <div class="chat-empty">
                        <i class="bi bi-chat-square-dots"></i>
                        <span>Belum ada balasan. Mulai percakapan dengan penghuni.</span>
                    </div>
                    @endforelse
                </div>

                @if(!in_array($ticket->status, ['resolved', 'closed']))
                <div class="reply-bar">
                    <form method="POST" action="{{ route('admin.tickets.reply', $ticket->id) }}">
                        @csrf
                        <div class="reply-inner">
                            <textarea name="message" class="reply-textarea"
                                placeholder="Tulis balasan ke penghuni..." required rows="2"></textarea>
                            <button type="submit" class="reply-send" title="Kirim">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="reply-locked">
                    <i class="bi bi-lock me-1"></i>
                    Tiket sudah {{ $ticket->status === 'resolved' ? 'diselesaikan' : 'ditutup' }} — balasan tidak dapat dikirim
                </div>
                @endif
            </div>

            @if(!in_array($ticket->status, ['resolved', 'closed']))
            <div class="resolve-card">
                <div class="resolve-head">
                    <div class="resolve-head-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="resolve-head-title">Tandai Sebagai Selesai</div>
                </div>
                <div class="resolve-body">
                    <div class="resolve-hint">
                        <i class="bi bi-bell-fill"></i>
                        Catatan penyelesaian akan dikirimkan sebagai notifikasi ke penghuni.
                    </div>
                    <form method="POST" action="{{ route('admin.tickets.resolve', $ticket->id) }}"
                          onsubmit="return confirm('Tandai tiket ini sebagai selesai?')">
                        @csrf
                        <label class="resolve-label">
                            Catatan Penyelesaian <span>(opsional)</span>
                        </label>
                        <textarea name="resolution_note" class="resolve-textarea"
                            placeholder="Jelaskan apa yang sudah diselesaikan..."></textarea>
                        <button type="submit" class="btn-resolve">
                            <i class="bi bi-check-circle-fill"></i>
                            Selesaikan Tiket
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div>
            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-info-circle-fill"></i></div>
                    <div>
                        <div class="d-card-title">Info Tiket</div>
                        <div class="d-card-sub">Metadata keluhan</div>
                    </div>
                </div>
                <div class="d-card-body">
                    <div class="info-row">
                        <span class="ir-label">Nomor</span>
                        <span class="ir-val" style="color:var(--green-700);font-size:.75rem">{{ $ticket->ticket_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">Status</span>
                        <span>
                            @php
                                $stMap = ['open' => 'st-open', 'in_progress' => 'st-in-progress', 'resolved' => 'st-resolved', 'closed' => 'st-closed'];
                                $stTxt = match($ticket->status) { 'open' => 'Terbuka', 'in_progress' => 'Ditangani', 'resolved' => 'Selesai', 'closed' => 'Ditutup', default => ucfirst($ticket->status) };
                                $stCls = $stMap[$ticket->status] ?? 'st-open';
                            @endphp
                            <span class="{{ $stCls }}">{{ $stTxt }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">Prioritas</span>
                        <span>
                            @php
                                $prMap = ['low' => 'pr-low', 'normal' => 'pr-normal', 'high' => 'pr-high', 'urgent' => 'pr-urgent'];
                                $prTxt = match($ticket->priority ?? 'low') { 'low' => 'Rendah', 'normal' => 'Normal', 'high' => 'Tinggi', 'urgent' => 'Urgent', default => ucfirst($ticket->priority ?? 'low') };
                                $prCls = $prMap[$ticket->priority ?? 'low'] ?? 'pr-low';
                            @endphp
                            <span class="{{ $prCls }}">{{ $prTxt }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">Kategori</span>
                        <span class="ir-val">{{ ucfirst($ticket->category) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">Dibuat</span>
                        <span class="ir-val" style="font-size:.75rem">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">MTTR</span>
                        <span>
                            @if($ticket->mttr_hours)
                                <span class="mttr-chip"><i class="bi bi-clock-fill"></i> {{ $ticket->mttr_hours }} jam</span>
                            @else
                                <span class="mttr-chip pending"><i class="bi bi-hourglass-split"></i> Belum selesai</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @if(!in_array($ticket->status, ['resolved', 'closed']))
            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon amber"><i class="bi bi-person-check-fill"></i></div>
                    <div>
                        <div class="d-card-title">Tugaskan ke Admin</div>
                        <div class="d-card-sub">Penanganan tiket</div>
                    </div>
                </div>
                <div class="d-card-body">
                    <form method="POST" action="{{ route('admin.tickets.assign', $ticket->id) }}">
                        @csrf
                        <select name="assigned_to" class="assign-select">
                            <option value="">— Pilih Admin —</option>
                            @foreach($admins as $a)
                            <option value="{{ $a->id }}" {{ $ticket->assigned_to === $a->id ? 'selected' : '' }}>
                                {{ $a->name }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-assign">
                            <i class="bi bi-person-check"></i> Tugaskan Sekarang
                        </button>
                    </form>
                    @if($ticket->assignee)
                    <div class="assigned-note">
                        <i class="bi bi-shield-check"></i>
                        Ditugaskan ke <strong>{{ $ticket->assignee->name }}</strong>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="d-card">
                <div class="d-card-head">
                    <div class="d-card-icon"><i class="bi bi-person-circle"></i></div>
                    <div>
                        <div class="d-card-title">Info Penghuni</div>
                        <div class="d-card-sub">Pelapor tiket ini</div>
                    </div>
                </div>
                <div class="d-card-body" style="padding-top:20px">
                    <div class="tenant-avatar">
                        {{ strtoupper(substr($ticket->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="tenant-name-big">{{ $ticket->user?->name }}</div>
                    <div class="tenant-email-sm">{{ $ticket->user?->email }}</div>

                    @if($ticket->rating)
                    <div class="rating-wrap">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $ticket->rating ? 'bi-star-fill' : 'bi-star' }}" style="color:{{ $i <= $ticket->rating ? 'var(--accent)' : 'var(--neutral-200)' }};font-size:.9rem"></i>
                        @endfor
                        <span style="font-size:.72rem;color:var(--neutral-400);margin-left:4px">{{ $ticket->rating }}/5</span>
                    </div>
                    @endif

                    <div style="border-top:1px solid var(--neutral-100);margin-top:14px;padding-top:12px">
                        @php
                            $userTickets = \App\Models\Ticket::where('user_id', $ticket->user_id)->get();
                            $resolved    = $userTickets->whereIn('status', ['resolved', 'closed'])->count();
                            $openCount   = $userTickets->whereIn('status', ['open', 'in_progress'])->count();
                        @endphp
                        <div class="info-row">
                            <span class="ir-label">Total Tiket</span>
                            <span class="ir-val">{{ $userTickets->count() }}</span>
                        </div>
                        <div class="info-row">
                            <span class="ir-label">Diselesaikan</span>
                            <span class="ir-val" style="color:var(--green-700)">{{ $resolved }}</span>
                        </div>
                        <div class="info-row">
                            <span class="ir-label">Masih Aktif</span>
                            <span class="ir-val" style="color:{{ $openCount > 0 ? 'var(--amber)' : 'var(--neutral-400)' }}">{{ $openCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold: 0.08 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

const cw = document.getElementById('chatWrap');
if (cw) cw.scrollTop = cw.scrollHeight;
</script>
</body>
</html>