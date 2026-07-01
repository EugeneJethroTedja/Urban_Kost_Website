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

    .page-wrap { max-width: 1040px; margin: 0 auto; padding: 32px 24px 64px; }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--neutral-500); text-decoration: none; font-size: .85rem;
        font-weight: 500; margin-bottom: 20px; transition: color .15s; min-height: 44px;
    }
    .back-link:hover { color: var(--green-600); }

    .detail-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

    .o-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .ticket-header-top {
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
        padding: 26px 24px 22px; position: relative; overflow: hidden;
    }

    .ticket-top-row {
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; margin-bottom: 12px; position: relative; z-index: 1; flex-wrap: wrap;
    }
    .ticket-number-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.18); color: #fff; font-size: .72rem; font-weight: 700;
        padding: 4px 12px; border-radius: 50px; backdrop-filter: blur(6px); white-space: nowrap;
    }
    .header-action-group { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .btn-header-action {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.9); font-family: 'Instrument Sans', sans-serif;
        font-weight: 600; font-size: .75rem; padding: 6px 14px; border-radius: 50px;
        text-decoration: none; white-space: nowrap; min-height: 34px;
        box-shadow: 0 2px 10px rgba(0,0,0,.14);
        transition: background .15s, transform .15s, box-shadow .15s; border: none; cursor: pointer;
    }
    .btn-header-action:hover { background: #fff; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,.18); }
    .btn-header-edit { color: #92400e; }
    .btn-header-edit:hover { color: #78350f; }
    .btn-header-delete { color: var(--red); }
    .btn-header-delete:hover { color: #b91c1c; }

    .ticket-header-top h1 {
        font-family: 'Fraunces', serif; font-size: clamp(1.05rem, 3.5vw, 1.4rem);
        font-weight: 700; color: #fff; line-height: 1.35;
        position: relative; z-index: 1; margin-bottom: 12px; word-break: break-word;
    }
    .header-badges { display: flex; gap: 8px; flex-wrap: wrap; position: relative; z-index: 1; }
    .header-badges .badge {
        background: rgba(255,255,255,.2); color: #fff;
        backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,.28);
    }

    .ticket-desc-body { padding: 20px 24px; }
    .ticket-desc-text {
        background: var(--green-50); border-radius: 10px; padding: 16px 18px;
        font-size: .88rem; line-height: 1.7; color: var(--neutral-900);
        white-space: pre-wrap; border: 1px solid var(--green-100); word-break: break-word;
    }
    .ticket-meta-row {
        display: flex; align-items: center; gap: 6px; margin-top: 14px;
        font-size: .77rem; color: var(--neutral-500); flex-wrap: wrap;
    }
    .btn-attachment {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--green-50); border: 1.5px solid var(--green-200);
        color: var(--green-700); font-size: .8rem; font-weight: 600;
        padding: 8px 16px; border-radius: 50px; text-decoration: none;
        transition: background .2s, border-color .2s; margin-top: 14px; min-height: 42px;
    }
    .btn-attachment:hover { background: var(--green-100); border-color: var(--green-400); color: var(--green-700); }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: .68rem; font-weight: 700; }
    .badge-open     { background: #dbeafe; color: #1d4ed8; }
    .badge-progress { background: var(--amber-pale); color: #92400e; }
    .badge-resolved { background: var(--green-100); color: var(--green-700); }
    .badge-closed   { background: var(--neutral-100); color: var(--neutral-500); }
    .badge-urgent   { background: var(--red-pale); color: #b91c1c; }
    .badge-high     { background: #ffedd5; color: #c2410c; }
    .badge-normal   { background: var(--blue-pale); color: #1d4ed8; }
    .badge-low      { background: var(--green-50); color: var(--green-700); }

    .conversation-header {
        padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
        font-family: 'Fraunces', serif; font-weight: 700; font-size: .9rem;
        display: flex; align-items: center; gap: 8px;
    }
    .conversation-header i { color: var(--green-500); }

    .chat-body {
        padding: 18px 20px; display: flex; flex-direction: column;
        gap: 16px; max-height: 480px; overflow-y: auto;
    }
    .chat-body::-webkit-scrollbar { width: 4px; }
    .chat-body::-webkit-scrollbar-thumb { background: var(--green-200); border-radius: 2px; }

    .chat-row { display: flex; gap: 10px; align-items: flex-end; }
    .chat-row.me { flex-direction: row-reverse; }

    .chat-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Instrument Sans', sans-serif; font-weight: 700;
        font-size: .75rem; color: #fff; flex-shrink: 0;
    }
    .avatar-me    { background: linear-gradient(135deg, var(--green-600), var(--green-400)); }
    .avatar-admin { background: linear-gradient(135deg, #6366f1, #8b5cf6); }

    .chat-content { max-width: min(72%, calc(100% - 52px)); }
    .chat-meta {
        font-size: .7rem; color: var(--neutral-400); margin-bottom: 5px;
        display: flex; align-items: center; gap: 6px; font-weight: 500; flex-wrap: wrap;
    }
    .chat-row.me .chat-meta { justify-content: flex-end; }
    .chat-meta .name { font-weight: 700; color: var(--neutral-700); }

    .chat-bubble { padding: 11px 15px; font-size: .86rem; line-height: 1.6; border-radius: 16px; word-break: break-word; }
    .bubble-me    { background: linear-gradient(135deg, var(--green-700), var(--green-500)); color: #fff; border-bottom-right-radius: 4px; }
    .bubble-admin { background: var(--green-50); border: 1px solid var(--green-100); color: var(--neutral-900); border-bottom-left-radius: 4px; }

    .chat-empty { text-align: center; padding: 40px 20px; color: var(--neutral-400); font-size: .84rem; }
    .chat-empty i { font-size: 2rem; display: block; margin-bottom: 8px; opacity: .4; }

    .reply-form {
        padding: 14px 18px; border-top: 1px solid var(--neutral-100); background: var(--green-50);
    }
    .reply-inner { display: flex; gap: 10px; align-items: flex-end; }
    .reply-textarea {
        flex: 1; padding: 12px 15px; background: var(--neutral-0);
        border: 1.5px solid var(--neutral-200); border-radius: 14px;
        font-family: 'Instrument Sans', sans-serif; color: var(--neutral-900);
        resize: none; min-height: 44px; max-height: 140px; outline: none;
        transition: border-color .2s, box-shadow .2s;
        line-height: 1.5; font-size: max(.85rem, 16px);
    }
    .reply-textarea::placeholder { color: var(--neutral-400); }
    .reply-textarea:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
    .reply-send {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, var(--green-700), var(--green-500));
        border: none; border-radius: 50%; color: #fff; font-size: 1rem;
        cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 14px rgba(29,148,88,.35); transition: transform .2s;
    }
    .reply-send:hover { transform: scale(1.08); }

    .rating-card {
        background: var(--neutral-0); border-radius: 16px;
        border: 1px solid var(--green-200); box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 20px; text-align: center;
    }
    .rating-card.unrated { border-color: var(--amber); }
    .rating-head {
        background: linear-gradient(135deg, var(--green-800), var(--green-600));
        padding: 20px 16px; color: #fff;
    }
    .rating-head.unrated { background: linear-gradient(135deg, var(--amber), #f97316); }
    .rating-head .icon { font-size: 1.8rem; margin-bottom: 6px; display: block; }
    .rating-head h3 { font-family: 'Fraunces', serif; font-weight: 700; font-size: 1rem; }
    .rating-head p { font-size: .78rem; opacity: .72; margin-top: 3px; }
    .rating-body { padding: 18px 16px; }
    .stars-display { font-size: 1.5rem; letter-spacing: 3px; margin-bottom: 8px; }
    .stars-label { font-family: 'Fraunces', serif; font-weight: 700; font-size: .9rem; color: var(--neutral-900); }
    .rating-buttons { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; }
    .rating-btn {
        background: var(--green-50); border: 1.5px solid var(--green-100);
        border-radius: 12px; padding: 10px 6px; font-size: .72rem; font-weight: 700;
        font-family: 'Instrument Sans', sans-serif; cursor: pointer;
        transition: background .2s, border-color .2s, transform .2s;
        text-align: center; min-height: 70px;
    }
    .rating-btn:hover { background: var(--green-100); border-color: var(--green-400); transform: translateY(-2px); }
    .rating-label { display: block; font-size: .65rem; margin-top: 4px; }

    .info-card {
        background: var(--neutral-0); border-radius: 16px;
        border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
        overflow: hidden; margin-bottom: 16px;
    }
    .info-card-header {
        padding: 14px 18px; border-bottom: 1px solid var(--neutral-100);
        font-family: 'Fraunces', serif; font-weight: 700; font-size: .88rem;
        display: flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    }
    .info-card-header i { color: var(--green-500); }
    .info-row {
        padding: 11px 18px; border-bottom: 1px solid var(--neutral-100);
        display: flex; justify-content: space-between; align-items: center;
        font-size: .8rem; gap: 8px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-key { color: var(--neutral-400); font-weight: 500; flex-shrink: 0; }
    .info-val { font-weight: 700; font-size: .78rem; text-align: right; word-break: break-word; min-width: 0; }
    .info-val.green { color: var(--green-700); }

    .btn-close-ticket {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; background: var(--neutral-200); border: 1.5px solid var(--neutral-300);
        border-radius: 50px; padding: 12px; font-family: 'Instrument Sans', sans-serif;
        font-weight: 600; font-size: .82rem; color: var(--neutral-600); cursor: pointer;
        transition: border-color .2s, color .2s, background .2s; min-height: 46px;
    }
    .btn-close-ticket:hover { border-color: var(--neutral-400); color: var(--neutral-500); background: var(--neutral-100); }

    @media (max-width: 768px) {
        .detail-layout { grid-template-columns: 1fr; }
        .chat-body { max-height: 360px; }
    }
    @media (max-width: 540px) {
        .page-wrap { padding: 20px 14px 48px; }
        .ticket-desc-body { padding: 16px; }
        .ticket-header-top { padding: 18px 16px 14px; }
        .chat-body { padding: 14px; gap: 12px; }
        .reply-form { padding: 12px 14px; }
        .info-card-header, .info-row { padding: 10px 14px; }
        .rating-body { padding: 14px 12px; }
    }
    @media (max-width: 400px) {
        .chat-content { max-width: calc(100% - 46px); }
        .rating-btn { padding: 8px 4px; min-height: 60px; }
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
    @media (max-width: 580px) {
        .hero-banner { flex-direction: column; padding: 22px 20px; }
        .hero-right  { width: 100%; }
        .btn-hero    { width: 100%; justify-content: center; }
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-wrap">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('tenant.tickets.index') }}" class="btn-hero"
        style="background:var(--neutral-0);color:var(--neutral-600);border:1px solid var(--neutral-200);box-shadow:var(--shadow-sm);">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('tenant.tickets.index') }}" style="color:inherit;text-decoration:none">Tiket Keluhan</a>
                &nbsp;/&nbsp; Detail
            </div>
            <h1 class="hero-title">Detail <em>Tiket</em></h1>
            <p class="hero-sub">{{ $ticket->ticket_number }} — Riwayat dan percakapan tiket</p>
        </div>
    </div>

    <div class="detail-layout">
        <div>
            <div class="o-card">
                <div class="ticket-header-top">
                    <div class="ticket-top-row">
                        <div class="ticket-number-tag">
                            <i class="bi bi-ticket-fill"></i> {{ $ticket->ticket_number }}
                        </div>

                        @if(in_array($ticket->status, ['open', 'in_progress']))
                        <div class="header-action-group">
                            <a href="{{ route('tenant.tickets.edit', $ticket->id) }}"
                               class="btn-header-action btn-header-edit">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('tenant.tickets.destroy', $ticket->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus tiket ini? Tindakan tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-header-action btn-header-delete">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <h1>{{ $ticket->subject }}</h1>
                    <div class="header-badges">
                        {!! $ticket->status_badge !!}
                        {!! $ticket->priority_badge !!}
                        <span class="badge">{{ ucfirst($ticket->category) }}</span>
                    </div>
                </div>

                <div class="ticket-desc-body">
                    <div class="ticket-desc-text">{{ $ticket->description }}</div>
                    @if($ticket->attachment)
                    <a href="{{ asset('storage/'.$ticket->attachment) }}" target="_blank" class="btn-attachment">
                        <i class="bi bi-paperclip"></i> Lihat Lampiran
                    </a>
                    @endif
                    <div class="ticket-meta-row">
                        <i class="bi bi-clock"></i>
                        Dibuat {{ $ticket->created_at->format('d M Y') }}
                        @if($ticket->mttr_hours)
                            <span style="opacity:.5">&middot;</span>
                            Selesai dalam <strong style="color:var(--green-700)">{{ $ticket->mttr_hours }} jam</strong>
                        @endif
                    </div>
                </div>
            </div>

            <div class="o-card">
                <div class="conversation-header">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span style="font-family:'Fraunces',serif;font-weight:700">Percakapan</span>
                </div>
                <div class="chat-body" id="chatBody">
                    @forelse($replies as $reply)
                    @php $isMe = $reply->user_id === auth()->id(); @endphp
                    <div class="chat-row {{ $isMe ? 'me' : '' }}">
                        <div class="chat-avatar {{ $isMe ? 'avatar-me' : 'avatar-admin' }}">
                            {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="chat-content">
                            <div class="chat-meta">
                                <span class="name">{{ $isMe ? 'Anda' : ($reply->user?->name . ' (Admin)') }}</span>
                                <span>{{ $reply->created_at->format('d M') }}</span>
                            </div>
                            <div class="chat-bubble {{ $isMe ? 'bubble-me' : 'bubble-admin' }}">
                                {!! nl2br(e($reply->message)) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="chat-empty">
                        <i class="bi bi-chat-dots"></i>
                        Belum ada percakapan. Tulis pesan pertama Anda.
                    </div>
                    @endforelse
                </div>

                @if(!in_array($ticket->status, ['resolved','closed']))
                <div class="reply-form">
                    <form method="POST" action="{{ route('tenant.tickets.reply', $ticket->id) }}">
                        @csrf
                        <div class="reply-inner">
                            <textarea name="message" class="reply-textarea" rows="1"
                                placeholder="Tulis balasan Anda..." required
                                oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"></textarea>
                            <button type="submit" class="reply-send" aria-label="Kirim">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            @if(in_array($ticket->status, ['resolved','closed']))
            <div class="rating-card {{ $ticket->rating ? '' : 'unrated' }}">
                @if($ticket->rating)
                <div class="rating-head">
                    <span class="icon">&#10003;</span>
                    <h3>Tiket Diselesaikan</h3>
                    <p>Terima kasih atas penilaian Anda.</p>
                </div>
                <div class="rating-body">
                    <div class="stars-display">
                        @for($i=1;$i<=5;$i++){{ $i<=$ticket->rating ? '' : '' }}@endfor
                    </div>
                    <div class="stars-label">Penilaian: {{ $ticket->rating }}/5</div>
                </div>
                @else
                <div class="rating-head unrated">
                    <span class="icon">&#9733;</span>
                    <h3>Berikan Penilaian Anda</h3>
                    <p>Seberapa puas Anda dengan penanganan tiket ini?</p>
                </div>
                <div class="rating-body">
                    <form method="POST" action="{{ route('tenant.tickets.rate', $ticket->id) }}">
                        @csrf
                        <div class="rating-buttons">
                            @foreach([1 => ['Buruk'], 2 => ['Kurang'], 3 => ['Cukup'], 4 => ['Baik'], 5 => ['Sangat Baik']] as $v => [$label])
                            <button type="submit" name="rating" value="{{ $v }}" class="rating-btn">
                                {{ $v }}/5
                                <span class="rating-label">{{ $label }}</span>
                            </button>
                            @endforeach
                        </div>
                    </form>
                </div>
                @endif
            </div>
            @endif
        </div>

        <div>
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-info-circle-fill"></i> Info Tiket
                </div>
                <div class="info-row">
                    <span class="info-key">Nomor</span>
                    <span class="info-val green">{{ $ticket->ticket_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Status</span>
                    <span class="info-val">{!! $ticket->status_badge !!}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Prioritas</span>
                    <span class="info-val">{!! $ticket->priority_badge !!}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Kategori</span>
                    <span class="info-val">{{ ucfirst($ticket->category) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Dibuat</span>
                    <span class="info-val">{{ $ticket->created_at->format('d M Y') }}</span>
                </div>
                @if($ticket->resolved_at)
                <div class="info-row">
                    <span class="info-key">Selesai</span>
                    <span class="info-val">{{ $ticket->resolved_at->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">MTTR</span>
                    <span class="info-val green">{{ $ticket->mttr_hours }} jam</span>
                </div>
                @endif
            </div>

            @if(!in_array($ticket->status, ['resolved','closed']))
            <form method="POST"
                  action="{{ route('tenant.tickets.close', $ticket->id) }}"
                  onsubmit="return confirm('Tutup tiket ini? Pastikan masalah sudah benar-benar selesai.')">
                @csrf
                <button type="submit" class="btn-close-ticket">
                    <i class="bi bi-x-circle"></i> Tutup Tiket (Masalah Selesai)
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<script>
const chatBody = document.getElementById('chatBody');
if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;
</script>
</body>
</html>