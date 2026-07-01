<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Booking Manual — Urban Kost</title>
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
    --red:         #dc2626;
    --red-lt:      #fef2f2;
    --red-line:    #fecaca;
    --blue:        #2563eb;
    --blue-lt:     #eff6ff;
    --blue-line:   #bfdbfe;
    --amber:       #f59e0b;
    --amber-lt:    #fffbeb;
    --amber-line:  #fde68a;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --card-r: 14px;
    --r-pill: 999px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
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

.page-wrap { max-width: 1080px; margin: 0 auto; padding: 32px 24px 64px; }

.bc { display: flex; align-items: center; gap: 6px; font-size: .73rem; color: var(--neutral-400); margin-bottom: 20px; flex-wrap: wrap; }
.bc a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.bc a:hover { text-decoration: underline; }
.bc .sep { font-size: .55rem; color: var(--neutral-300); }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
.page-header-title {
    font-family: 'Fraunces', serif;
    font-weight: 700; font-size: clamp(1.4rem, 3vw, 1.9rem);
    color: var(--neutral-900); letter-spacing: -.025em; line-height: 1.15;
    display: flex; align-items: center; gap: 10px;
}
.page-header-title .title-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--green-600), var(--green-400));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .95rem; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(42,179,107,.3);
}
.page-header-sub { font-size: .82rem; color: var(--neutral-500); margin-top: 5px; }

.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--neutral-0); border: 1px solid var(--green-200);
    color: var(--neutral-600); font-size: .8rem; font-weight: 600;
    padding: 9px 16px; border-radius: 10px; cursor: pointer;
    text-decoration: none; transition: all .15s;
}
.btn-back:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-700); }

.flash {
    padding: 11px 14px; border-radius: 10px; margin-bottom: 14px;
    font-size: .8rem; font-weight: 500; display: flex; align-items: flex-start; gap: 9px;
    border: 1px solid;
}

.form-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
@media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }

.card {
    background: var(--neutral-0); border-radius: var(--card-r);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 16px;
}
.card-head {
    padding: 13px 18px; border-bottom: 1px solid var(--green-100);
    font-family: 'Fraunces', serif;
    font-size: .88rem; font-weight: 700;
    display: flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    color: var(--neutral-800);
}
.card-head i { color: var(--green-500); font-size: .9rem; }
.card-body { padding: 20px; }

.sect-lbl {
    font-size: .65rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .08em; color: var(--neutral-400); margin-bottom: 12px;
    display: flex; align-items: center; gap: 6px;
}
.sect-lbl::after { content: ''; flex: 1; height: 1px; background: var(--neutral-100); }

.fld-group { margin-bottom: 16px; }
.fld-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width: 520px) { .fld-row { grid-template-columns: 1fr; } }
.fld-label { display: block; font-size: .72rem; font-weight: 600; color: var(--neutral-600); margin-bottom: 5px; text-transform: uppercase; letter-spacing: .05em; }
.fld-label .opt { font-weight: 400; text-transform: none; letter-spacing: 0; color: var(--neutral-400); font-size: .68rem; }
.fld-ctrl {
    width: 100%; padding: 9px 13px; border: 1.5px solid var(--neutral-200);
    border-radius: 9px; font-family: 'Instrument Sans', sans-serif;
    font-size: .8rem; color: var(--neutral-900); background: var(--neutral-0);
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.fld-ctrl:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.fld-ctrl.err { border-color: var(--red); background: var(--red-lt); }
.fld-err { font-size: .68rem; color: var(--red); margin-top: 3px; display: flex; align-items: center; gap: 3px; }
.fld-hint { font-size: .68rem; color: var(--neutral-400); margin-top: 4px; line-height: 1.5; }
textarea.fld-ctrl { resize: vertical; min-height: 70px; }

.room-preview {
    display: none; margin-top: 10px; padding: 12px 14px;
    background: var(--green-50); border: 1.5px solid var(--green-200);
    border-radius: 10px; font-size: .8rem;
}
.room-preview.show { display: flex; gap: 12px; align-items: flex-start; }
.room-preview-icon {
    width: 38px; height: 38px; border-radius: 9px; flex-shrink: 0;
    background: var(--green-600); display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px;
}
.room-preview-name  { font-weight: 700; font-size: .82rem; color: var(--green-800); }
.room-preview-meta  { font-size: .72rem; color: var(--neutral-500); margin-top: 2px; }
.room-preview-price { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; color: var(--green-700); margin-top: 4px; }
.room-status-dot {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: var(--r-pill);
}
.dot-available { background: var(--green-100); color: var(--green-800); }
.dot-occupied  { background: var(--amber-lt); color: #92400e; }
.dot-reserved  { background: var(--blue-lt); color: #1e40af; }

.user-search-wrap { position: relative; }
.user-result-list {
    position: absolute; top: 100%; left: 0; right: 0; z-index: 99;
    background: var(--neutral-0); border: 1.5px solid var(--green-300);
    border-radius: 10px; box-shadow: var(--shadow-md);
    max-height: 220px; overflow-y: auto; display: none;
}
.user-result-list.show { display: block; }
.user-result-item {
    padding: 10px 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;
    border-bottom: 1px solid var(--neutral-100); transition: background .1s;
}
.user-result-item:last-child { border-bottom: none; }
.user-result-item:hover { background: var(--green-50); }
.user-avatar {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    background: var(--green-600); display: flex; align-items: center;
    justify-content: center; font-size: .72rem; font-weight: 700; color: #fff;
}
.user-name { font-weight: 700; font-size: .8rem; color: var(--neutral-900); }
.user-meta { font-size: .68rem; color: var(--neutral-400); margin-top: 1px; }
.user-selected-badge {
    display: none; margin-top: 8px; padding: 10px 12px;
    background: var(--green-50); border: 1.5px solid var(--green-200);
    border-radius: 9px; align-items: center; gap: 10px;
}
.user-selected-badge.show { display: flex; }

.service-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 8px; margin-top: 6px; }
.service-chip-wrap { position: relative; }
.service-chip-input { position: absolute; opacity: 0; width: 0; height: 0; }
.service-chip-label {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 12px; border-radius: 9px;
    border: 1.5px solid var(--neutral-200); background: var(--neutral-0);
    cursor: pointer; transition: all .15s;
    font-size: .72rem; font-weight: 600; color: var(--neutral-600);
}
.service-chip-label:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }
.service-chip-input:checked + .service-chip-label {
    border-color: var(--green-500); background: var(--green-50); color: var(--green-800);
    box-shadow: 0 0 0 3px rgba(42,179,107,.1);
}
.service-chip-icon {
    width: 26px; height: 26px; border-radius: 6px; flex-shrink: 0;
    background: var(--green-100); display: flex; align-items: center;
    justify-content: center; color: var(--green-700); font-size: 13px;
}
.service-chip-input:checked + .service-chip-label .service-chip-icon { background: var(--green-200); }
.service-chip-price { font-size: .62rem; font-weight: 500; color: var(--neutral-400); margin-left: auto; white-space: nowrap; }

.type-toggle { display: flex; gap: 8px; margin-top: 6px; }
.type-option { flex: 1; position: relative; }
.type-radio { position: absolute; opacity: 0; width: 0; height: 0; }
.type-label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px; padding: 14px 10px; border-radius: 10px;
    border: 1.5px solid var(--neutral-200); background: var(--neutral-0);
    cursor: pointer; transition: all .15s; text-align: center;
}
.type-label:hover { border-color: var(--green-400); background: var(--green-50); }
.type-radio:checked + .type-label { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.type-icon  { font-size: 20px; margin-bottom: 2px; }
.type-name  { font-size: .72rem; font-weight: 700; color: var(--neutral-700); }
.type-desc  { font-size: .62rem; color: var(--neutral-400); line-height: 1.4; }
.type-radio:checked + .type-label .type-name { color: var(--green-800); }

.toggle-wrap {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 10px;
    border: 1.5px solid var(--neutral-200); cursor: pointer;
    transition: all .15s; background: var(--neutral-0);
}
.toggle-wrap:hover { border-color: var(--green-400); background: var(--green-50); }
.toggle-wrap.active { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.1); }
.toggle-checkbox { width: 18px; height: 18px; border-radius: 5px; accent-color: var(--green-600); flex-shrink: 0; margin-top: 1px; }
.toggle-text-title { font-size: .8rem; font-weight: 700; color: var(--neutral-900); }
.toggle-text-desc  { font-size: .68rem; color: var(--neutral-500); margin-top: 2px; line-height: 1.5; }

.cost-preview { padding: 14px; background: var(--green-50); border-radius: 10px; border: 1px solid var(--green-200); }
.cost-row   { display: flex; justify-content: space-between; font-size: .72rem; padding: 3px 0; color: var(--neutral-600); }
.cost-row .val { font-weight: 700; color: var(--neutral-700); }
.cost-total { display: flex; justify-content: space-between; font-size: .85rem; font-weight: 700; padding-top: 8px; border-top: 1.5px solid var(--green-300); margin-top: 6px; color: var(--green-800); }
.cost-dp    { display: flex; justify-content: space-between; font-size: .72rem; margin-top: 4px; color: var(--amber); }
.cost-dp .val { font-weight: 700; }

.alert-box { display: flex; gap: 10px; padding: 12px 14px; border-radius: 10px; font-size: .8rem; margin-bottom: 12px; border: 1px solid; }
.alert-box i { flex-shrink: 0; margin-top: 1px; }
.alert-info    { background: var(--blue-lt); border-color: var(--blue-line); color: #1e40af; }
.alert-warning { background: var(--amber-lt); border-color: var(--amber-line); color: #92400e; }

.submit-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px; }
.btn-submit {
    flex: 1; min-width: 140px; display: flex; align-items: center; justify-content: center;
    gap: 6px; padding: 11px 18px; border-radius: 10px;
    font-size: .82rem; font-weight: 700;
    font-family: 'Instrument Sans', sans-serif; cursor: pointer; border: none; transition: all .15s;
}
.btn-submit.primary { background: var(--green-600); color: #fff; }
.btn-submit.primary:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(29,148,88,.3); }
.btn-submit.secondary { background: var(--neutral-0); border: 1.5px solid var(--neutral-200); color: var(--neutral-600); }
.btn-submit.secondary:hover { border-color: var(--neutral-400); color: var(--neutral-900); }

.uk-hr { border: none; height: 1px; background: var(--neutral-100); margin: 18px 0; }

.summary-row {
    display: flex; justify-content: space-between; font-size: .72rem; padding: 7px 0;
    border-bottom: 1px solid var(--neutral-100);
}
.summary-row:last-child { border-bottom: none; }
.summary-key { color: var(--neutral-500); }
.summary-val { font-weight: 700; color: var(--neutral-700); }

.hero-banner {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 55%, var(--green-500) 100%);
    border-radius: 18px;
    padding: 36px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(14,63,39,.25);
}
.hero-banner::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(78,201,138,.18) 0%, transparent 55%),
        radial-gradient(circle at 10% 90%, rgba(42,179,107,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-label {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: .65rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: rgba(255,255,255,.7);
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
    padding: 4px 12px; border-radius: var(--r-pill);
    margin-bottom: 10px;
}
.hero-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--green-300); flex-shrink: 0;
    box-shadow: 0 0 6px var(--green-300);
}
.hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.6rem, 4vw, 2.2rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.03em; line-height: 1.1;
    margin-bottom: 6px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub {
    font-size: .82rem; color: rgba(255,255,255,.65);
}
.hero-actions {
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center; flex-shrink: 0;
}

.hero-banner .btn-back {
    background: rgba(255,255,255,.12);
    border-color: rgba(255,255,255,.25);
    color: rgba(255,255,255,.85);
}
.hero-banner .btn-back:hover {
    background: rgba(255,255,255,.22);
    border-color: rgba(255,255,255,.4);
    color: #fff;
}
@media (max-width: 640px) {
    .hero-banner { padding: 24px 20px; }
    .hero-actions { width: 100%; }
    .hero-actions a { flex: 1; justify-content: center; }
}

@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
.page-header { animation: fadeUp .3s ease both; }
.form-grid   { animation: fadeUp .3s .06s ease both; }

@media (max-width: 640px) { .page-wrap { padding: 16px 12px 48px; } }
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

@php
    $roomsJson = $rooms->map(fn($r) => [
        'id'     => $r->id,
        'number' => $r->room_number,
        'name'   => $r->room_name ?? 'Kamar ' . $r->room_number,
        'price'  => $r->price,
        'floor'  => $r->floor,
        'size'   => $r->size,
        'status' => $r->status,
    ])->keyBy('id');
    $usersJson = $users->map(fn($u) => [
        'id'    => $u->id,
        'name'  => $u->name,
        'email' => $u->email,
        'phone' => $u->phone,
        'role'  => $u->role?->display_name ?? $u->role?->name,
    ]);
@endphp

<div class="page-wrap">
    @foreach(['success','warning','error','info'] as $type)
    @if(session($type))
    @php
        $flashStyles = [
            'success' => ['bg'=>'var(--green-50)','border'=>'var(--green-200)','color'=>'var(--green-800)','icon'=>'check-circle-fill'],
            'error'   => ['bg'=>'var(--red-lt)','border'=>'var(--red-line)','color'=>'#991b1b','icon'=>'exclamation-circle-fill'],
            'warning' => ['bg'=>'var(--amber-lt)','border'=>'var(--amber-line)','color'=>'#92400e','icon'=>'exclamation-triangle-fill'],
            'info'    => ['bg'=>'var(--blue-lt)','border'=>'var(--blue-line)','color'=>'#1e40af','icon'=>'info-circle-fill'],
        ];
        $fs = $flashStyles[$type];
    @endphp
    <div class="flash" style="background:{{ $fs['bg'] }};border-color:{{ $fs['border'] }};color:{{ $fs['color'] }}">
        <i class="bi bi-{{ $fs['icon'] }}"></i>
        <span>{!! session($type) !!}</span>
    </div>
    @endif
    @endforeach

    @if($errors->any())
    <div class="flash" style="background:var(--red-lt);border-color:var(--red-line);color:#991b1b">
        <i class="bi bi-x-circle-fill"></i>
        <ul style="margin:0;padding-left:1.1rem">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif
    <a href="{{ route('admin.bookings.index') }}" class="btn-back" style="margin-bottom: 16px;">
        <i class="bi bi-arrow-left"></i>Kembali
    </a>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-label">
                <span class="hero-dot"></span> ADMIN PANEL
            </div>
            <h1 class="hero-title">Buat <em>Booking Manual</em></h1>
            <p class="hero-sub">Bookingkan kamar secara langsung atas nama pengguna tanpa proses pengajuan mandiri</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.bookings.manual.store') }}" id="manualBookingForm">
    @csrf

    <div class="form-grid">
        <div>
            <div class="card">
                <div class="card-head">
                    <i class="bi bi-person-badge-fill"></i>
                    1. Pilih Pengguna
                </div>
                <div class="card-body">
                    <div class="alert-box alert-info" style="margin-bottom:14px">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>Admin bisa memilih pengguna yang sudah terdaftar, atau buat akun baru terlebih dahulu di <a href="{{ route('admin.tenants.create') }}" style="color:#1e40af;font-weight:700">menu Penghuni</a>.</div>
                    </div>
                    <div class="fld-group user-search-wrap">
                        <label class="fld-label">Cari Nama / Email Pengguna <span style="color:var(--red)">*</span></label>
                        <input type="text" id="userSearch" class="fld-ctrl {{ $errors->has('user_id') ? 'err' : '' }}"
                               placeholder="Ketik nama atau email..." autocomplete="off">
                        <input type="hidden" name="user_id" id="userIdInput" value="{{ old('user_id') }}">
                        @error('user_id')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                        <div class="user-result-list" id="userResultList"></div>
                        <div class="user-selected-badge {{ old('user_id') ? 'show' : '' }}" id="userSelectedBadge">
                            <div class="user-avatar" id="userAvatar">?</div>
                            <div>
                                <div class="user-name" id="userSelectedName">—</div>
                                <div class="user-meta" id="userSelectedMeta">—</div>
                            </div>
                            <button type="button" id="clearUser"
                                    style="margin-left:auto;background:none;border:none;cursor:pointer;color:var(--neutral-400);font-size:15px"
                                    title="Hapus pilihan">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="uk-hr"></div>
                    <div class="sect-lbl"><i class="bi bi-person-lines-fill"></i>Data Pemohon</div>
                    <div class="fld-row">
                        <div class="fld-group">
                            <label class="fld-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
                            <input type="text" name="full_name" id="fullNameInput"
                                   class="fld-ctrl {{ $errors->has('full_name') ? 'err' : '' }}"
                                   value="{{ old('full_name') }}" placeholder="Sesuai KTP / identitas">
                            @error('full_name')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                        </div>
                        <div class="fld-group">
                            <label class="fld-label">No. HP / WhatsApp <span style="color:var(--red)">*</span></label>
                            <input type="text" name="phone" id="phoneInput"
                                   class="fld-ctrl {{ $errors->has('phone') ? 'err' : '' }}"
                                   value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                            @error('phone')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="fld-group">
                        <label class="fld-label">No. KTP / KTM <span class="opt">(opsional)</span></label>
                        <input type="text" name="identity_number" class="fld-ctrl"
                               value="{{ old('identity_number') }}" placeholder="NIK / NIM / nomor identitas lainnya">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <i class="bi bi-door-closed-fill"></i>
                    2. Kamar &amp; Jadwal
                </div>
                <div class="card-body">
                    <div class="fld-group room-select-wrap">
                        <label class="fld-label">Pilih Kamar <span style="color:var(--red)">*</span></label>
                        <select name="room_id" id="roomSelect" class="fld-ctrl {{ $errors->has('room_id') ? 'err' : '' }}">
                            <option value="">— Pilih kamar —</option>
                            @foreach($rooms->where('status','available') as $r)
                            <option value="{{ $r->id }}" data-status="available" data-price="{{ $r->price }}"
                                    {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                Kamar {{ $r->room_number }}{{ $r->room_name ? ' — '.$r->room_name : '' }}
                                (Tersedia &middot; Rp {{ number_format($r->price,0,',','.') }}/bln)
                            </option>
                            @endforeach
                            @foreach($rooms->whereIn('status',['occupied','reserved']) as $r)
                            <option value="{{ $r->id }}" data-status="{{ $r->status }}" data-price="{{ $r->price }}"
                                    {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                Kamar {{ $r->room_number }}{{ $r->room_name ? ' — '.$r->room_name : '' }}
                                ({{ $r->status === 'occupied' ? 'Terisi' : 'Dipesan' }} &middot; Rp {{ number_format($r->price,0,',','.') }}/bln)
                            </option>
                            @endforeach
                            @foreach($rooms->where('status','maintenance') as $r)
                            <option value="{{ $r->id }}" data-status="maintenance" disabled>
                                Kamar {{ $r->room_number }} (Perbaikan — tidak bisa dibooking)
                            </option>
                            @endforeach
                        </select>
                        @error('room_id')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                        <div class="room-preview" id="roomPreview">
                            <div class="room-preview-icon"><i class="bi bi-door-closed-fill"></i></div>
                            <div>
                                <div class="room-preview-name" id="prevRoomName">—</div>
                                <div class="room-preview-meta" id="prevRoomMeta">—</div>
                                <div class="room-preview-price" id="prevRoomPrice">—</div>
                            </div>
                            <span class="room-status-dot" id="prevRoomStatus" style="margin-left:auto;align-self:flex-start">—</span>
                        </div>
                    </div>

                    <div class="fld-group">
                        <label class="fld-label">Tipe Booking <span style="color:var(--red)">*</span></label>
                        <div class="type-toggle">
                            <div class="type-option">
                                <input type="radio" name="booking_type" id="typeRegular" value="regular"
                                       class="type-radio" {{ old('booking_type','regular') === 'regular' ? 'checked' : '' }}>
                                <label for="typeRegular" class="type-label">
                                    <span class="type-icon"></span>
                                    <span class="type-name">Regular</span>
                                    <span class="type-desc">Kamar kosong, langsung bisa masuk</span>
                                </label>
                            </div>
                            <div class="type-option">
                                <input type="radio" name="booking_type" id="typeAdvance" value="advance"
                                       class="type-radio" {{ old('booking_type') === 'advance' ? 'checked' : '' }}>
                                <label for="typeAdvance" class="type-label">
                                    <span class="type-icon"></span>
                                    <span class="type-name">Advance / WL</span>
                                    <span class="type-desc">Kamar masih terisi, masuk waiting list</span>
                                </label>
                            </div>
                        </div>
                        <div class="fld-hint" id="advanceHint" style="display:none">
                            <i class="bi bi-info-circle"></i>
                            Booking advance akan masuk <strong>Waiting List</strong>. Calon penghuni diminta membayar DP 50% untuk mengamankan posisi.
                        </div>
                        @error('booking_type')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                    </div>

                    <div class="card" style="margin-bottom:0">
                        <div class="card-head">
                            <i class="bi bi-credit-card-fill"></i>
                            3. Metode Pembayaran
                        </div>
                        <div class="card-body">
                            <div class="type-toggle">
                                <div class="type-option">
                                    <input type="radio" name="payment_method" id="payLumpsum" value="lump_sum" class="type-radio" checked>
                                    <label for="payLumpsum" class="type-label">
                                        <span class="type-icon"></span>
                                        <span class="type-name">Bayar Lunas</span>
                                        <span class="type-desc">1 invoice, bayar sekaligus</span>
                                    </label>
                                </div>
                                <div class="type-option">
                                    <input type="radio" name="payment_method" id="payMonthly" value="monthly" class="type-radio">
                                    <label for="payMonthly" class="type-label">
                                        <span class="type-icon"></span>
                                        <span class="type-name">Bayar Per Bulan</span>
                                        <span class="type-desc">Invoice otomatis tiap bulan</span>
                                    </label>
                                </div>
                            </div>
                            <div id="monthlyHint" style="display:none;margin-top:10px" class="alert-box alert-info">
                                <i class="bi bi-info-circle-fill"></i>
                                <div>Invoice bulan pertama diterbitkan saat approve. Bulan berikutnya otomatis via scheduler H-5 sebelum periode.</div>
                            </div>
                        </div>
                    </div>

                    <div style="height:16px"></div>

                    <div class="fld-row">
                        <div class="fld-group">
                            <label class="fld-label">Tanggal Masuk <span style="color:var(--red)">*</span></label>
                            <input type="date" name="check_in_date" id="checkInDate"
                                   class="fld-ctrl {{ $errors->has('check_in_date') ? 'err' : '' }}"
                                   value="{{ old('check_in_date', now()->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}">
                            @error('check_in_date')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                        </div>
                        <div class="fld-group">
                            <label class="fld-label">Durasi Sewa <span style="color:var(--red)">*</span></label>
                            <select name="duration_months" id="durationSelect" class="fld-ctrl {{ $errors->has('duration_months') ? 'err' : '' }}">
                                @foreach([1,2,3,4,5,6,9,12] as $m)
                                <option value="{{ $m }}" {{ old('duration_months',1) == $m ? 'selected' : '' }}>
                                    {{ $m }} bulan{{ $m == 12 ? ' (1 tahun)' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('duration_months')<span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($additionalServices))
            <div class="card">
                <div class="card-head">
                    <i class="bi bi-grid-fill"></i>
                    4. Layanan Tambahan <span style="font-weight:400;color:var(--neutral-400);font-size:.72rem">(opsional)</span>
                </div>
                <div class="card-body">
                    <div class="service-grid">
                        @foreach($additionalServices as $key => $svc)
                        <div class="service-chip-wrap">
                            <input type="checkbox" name="services[]" value="{{ $key }}"
                                   id="svc_{{ $key }}" class="service-chip-input svc-check"
                                   data-price="{{ $svc['price'] }}"
                                   {{ in_array($key, old('services', [])) ? 'checked' : '' }}>
                            <label for="svc_{{ $key }}" class="service-chip-label">
                                <div class="service-chip-icon"><i class="bi bi-{{ $svc['icon'] ?? 'plus-circle' }}"></i></div>
                                <span>{{ $svc['label'] }}</span>
                                <span class="service-chip-price">+Rp {{ number_format($svc['price'],0,',','.') }}/bln</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-head">
                    <i class="bi bi-chat-left-text-fill"></i>
                    {{ empty($additionalServices) ? '4' : '5' }}. Pesan &amp; Catatan
                </div>
                <div class="card-body">
                    <div class="fld-group">
                        <label class="fld-label">Pesan / Keterangan <span class="opt">(opsional — terlihat oleh pengguna)</span></label>
                        <textarea name="message" class="fld-ctrl" rows="2"
                                  placeholder="Contoh: Booking dibuat atas permintaan langsung...">{{ old('message') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-head"><i class="bi bi-calculator-fill"></i>Estimasi Biaya</div>
                <div class="card-body">
                    <div class="cost-preview" id="costPreview">
                        <div class="cost-row"><span>Sewa kamar</span><span class="val" id="costRoom">Rp 0</span></div>
                        <div class="cost-row" id="costServiceRow" style="display:none">
                            <span>Layanan tambahan</span>
                            <span class="val" id="costService" style="color:var(--blue)">+Rp 0</span>
                        </div>
                        <div class="cost-total"><span>Total</span><span id="costTotal">Rp 0</span></div>
                        <div class="cost-dp" id="costDpRow" style="display:none">
                            <span>DP 50% (Advance/WL)</span><span class="val" id="costDp">Rp 0</span>
                        </div>
                    </div>
                    <div class="fld-hint" style="margin-top:8px">
                        <i class="bi bi-info-circle"></i> Pilih kamar dan durasi untuk melihat estimasi.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><i class="bi bi-lightning-charge-fill"></i>Opsi Proses</div>
                <div class="card-body">
                    <label class="toggle-wrap" id="autoApproveWrap">
                        <input type="checkbox" name="auto_approve" value="1"
                               class="toggle-checkbox" id="autoApproveCheck"
                               {{ old('auto_approve') ? 'checked' : '' }}>
                        <div>
                            <div class="toggle-text-title">Langsung Approve</div>
                            <div class="toggle-text-desc">Booking langsung disetujui tanpa perlu review manual. Invoice / DP akan otomatis diterbitkan setelah submit.</div>
                        </div>
                    </label>
                    <div id="autoApproveInfo" style="margin-top:10px;display:none">
                        <div class="alert-box alert-info" style="margin-bottom:0">
                            <i class="bi bi-bolt-fill"></i>
                            <div id="autoApproveInfoText">—</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><i class="bi bi-list-check"></i>Ringkasan</div>
                <div class="card-body">
                    <div class="summary-row"><span class="summary-key">Pengguna</span><span class="summary-val" id="sumUser">—</span></div>
                    <div class="summary-row"><span class="summary-key">Kamar</span><span class="summary-val" id="sumRoom">—</span></div>
                    <div class="summary-row"><span class="summary-key">Tipe</span><span class="summary-val" id="sumType">Regular</span></div>
                    <div class="summary-row"><span class="summary-key">Masuk</span><span class="summary-val" id="sumDate">—</span></div>
                    <div class="summary-row"><span class="summary-key">Durasi</span><span class="summary-val" id="sumDuration">1 bulan</span></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="submit-row">
                        <button type="submit" class="btn-submit primary" onclick="return confirmSubmit()">
                            <i class="bi bi-plus-circle-fill"></i>Buat Booking
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn-submit secondary">
                            <i class="bi bi-x"></i>Batal
                        </a>
                    </div>
                    <p style="font-size:.68rem;color:var(--neutral-400);margin-top:10px;text-align:center">
                        <i class="bi bi-shield-check"></i>
                        Booking ini akan tercatat atas nama admin dan ditandai sebagai booking manual.
                    </p>
                </div>
            </div>
        </div>
    </div>
    </form>

</div>

<script>
const ROOMS  = @json($roomsJson);
const USERS  = @json($usersJson);
const ROOM_MIN_DATES = @json($roomMinDates);

const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
let selRoom = null, selUser = null;

function calcCost() {
    if (!selRoom) return;
    const price    = selRoom.price || 0;
    const duration = parseInt(document.getElementById('durationSelect').value) || 1;
    const isAdv    = document.getElementById('typeAdvance').checked;
    const isMonthly = document.querySelector('input[name="payment_method"]:checked')?.value === 'monthly';
    let svcTotal = 0;
    document.querySelectorAll('.svc-check:checked').forEach(cb => { svcTotal += parseInt(cb.dataset.price) || 0; });
    const sewa  = price * duration;
    const svc   = svcTotal * duration;
    const total = sewa + svc;
    const dp    = isMonthly ? Math.round((price + svcTotal) * 0.5) : Math.round(total * 0.5);
    document.getElementById('costRoom').textContent  = fmt(sewa);
    document.getElementById('costTotal').textContent = fmt(total);
    const svcRow = document.getElementById('costServiceRow');
    if (svc > 0) { document.getElementById('costService').textContent = '+' + fmt(svc); svcRow.style.display = 'flex'; }
    else { svcRow.style.display = 'none'; }
    const dpRow = document.getElementById('costDpRow');
    if (isAdv) { document.getElementById('costDp').textContent = fmt(dp); dpRow.style.display = 'flex'; }
    else { dpRow.style.display = 'none'; }
    const infoEl = document.getElementById('autoApproveInfoText');
    const autoOn = document.getElementById('autoApproveCheck').checked;
    if (autoOn && infoEl) {
        infoEl.innerHTML = isAdv
            ? 'Booking advance &rarr; masuk <strong>Waiting List</strong> dan invoice DP <strong>' + fmt(dp) + '</strong> langsung diterbitkan.'
            : 'Booking regular &rarr; langsung disetujui dan invoice <strong>' + fmt(total) + '</strong> langsung diterbitkan.';
    }
}

document.getElementById('roomSelect').addEventListener('change', function() {
    const id  = this.value;
    const opt = this.options[this.selectedIndex];
    if (!id) { selRoom = null; document.getElementById('roomPreview').classList.remove('show'); document.getElementById('sumRoom').textContent = '—'; calcCost(); return; }
    selRoom = ROOMS[id] || null;
    const status = opt.dataset.status || '';
    const prev = document.getElementById('roomPreview');
    prev.classList.add('show');
    document.getElementById('prevRoomName').textContent = selRoom ? (selRoom.name || 'Kamar ' + selRoom.number) : opt.text;
    document.getElementById('prevRoomMeta').textContent = [selRoom?.floor ? 'Lantai ' + selRoom.floor : null, selRoom?.size || null].filter(Boolean).join(' · ') || '—';
    document.getElementById('prevRoomPrice').textContent = fmt(selRoom?.price || 0) + ' /bulan';
    document.getElementById('sumRoom').textContent = 'Kamar ' + (selRoom?.number || '—');
    const statusDot = document.getElementById('prevRoomStatus');
    const statusMap = { available: ['dot-available','Tersedia'], occupied: ['dot-occupied','Terisi'], reserved: ['dot-reserved','Dipesan'] };
    const [cls, lbl] = statusMap[status] || ['dot-reserved', status];
    statusDot.className = 'room-status-dot ' + cls;
    statusDot.textContent = lbl;
    if (status === 'occupied' || status === 'reserved') { document.getElementById('typeAdvance').checked = true; updateBookingType(); }
    else if (status === 'available') { document.getElementById('typeRegular').checked = true; updateBookingType(); }
    const today = new Date().toISOString().split('T')[0];
    const minDate = ROOM_MIN_DATES[id] || today;
    const checkInInput = document.getElementById('checkInDate');
    checkInInput.min = minDate;
    if (checkInInput.value && checkInInput.value < minDate) { checkInInput.value = minDate; }
    calcCost();
});

function updateBookingType() {
    const isAdv = document.getElementById('typeAdvance').checked;
    document.getElementById('advanceHint').style.display = isAdv ? 'block' : 'none';
    document.getElementById('sumType').textContent = isAdv ? 'Advance / WL' : 'Regular';
    calcCost();
}
document.getElementById('typeRegular').addEventListener('change', updateBookingType);
document.getElementById('typeAdvance').addEventListener('change', updateBookingType);
document.getElementById('durationSelect').addEventListener('change', function() { document.getElementById('sumDuration').textContent = this.value + ' bulan'; calcCost(); });
document.getElementById('checkInDate').addEventListener('change', function() {
    const d = new Date(this.value);
    document.getElementById('sumDate').textContent = this.value ? d.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
});
document.querySelectorAll('.svc-check').forEach(cb => cb.addEventListener('change', calcCost));

const autoCheck = document.getElementById('autoApproveCheck');
const autoInfo  = document.getElementById('autoApproveInfo');
const autoWrap  = document.getElementById('autoApproveWrap');
autoCheck.addEventListener('change', function() {
    autoInfo.style.display = this.checked ? 'block' : 'none';
    autoWrap.classList.toggle('active', this.checked);
    calcCost();
});

const userSearch     = document.getElementById('userSearch');
const userResultList = document.getElementById('userResultList');
const userIdInput    = document.getElementById('userIdInput');
const userBadge      = document.getElementById('userSelectedBadge');
const userAvatar     = document.getElementById('userAvatar');
const userSelName    = document.getElementById('userSelectedName');
const userSelMeta    = document.getElementById('userSelectedMeta');
const fullNameInput  = document.getElementById('fullNameInput');
const phoneInput     = document.getElementById('phoneInput');

function initials(name) { return (name || '?').split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase(); }

function selectUser(u) {
    selUser = u;
    userIdInput.value     = u.id;
    userSearch.value      = u.name;
    userAvatar.textContent= initials(u.name);
    userSelName.textContent = u.name;
    userSelMeta.textContent = (u.role || '') + (u.email ? ' · ' + u.email : '');
    userBadge.classList.add('show');
    userResultList.classList.remove('show');
    if (!fullNameInput.value) fullNameInput.value = u.name;
    if (!phoneInput.value && u.phone) phoneInput.value = u.phone;
    document.getElementById('sumUser').textContent = u.name;
}

userSearch.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    if (!q) { userResultList.classList.remove('show'); return; }
    const matches = USERS.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)).slice(0, 8);
    if (!matches.length) { userResultList.classList.remove('show'); return; }
    userResultList.innerHTML = matches.map(u => `
        <div class="user-result-item" onclick="selectUser(${JSON.stringify(u).replace(/"/g,'&quot;')})">
            <div class="user-avatar">${initials(u.name)}</div>
            <div><div class="user-name">${u.name}</div><div class="user-meta">${u.role || ''} · ${u.email || ''}</div></div>
        </div>
    `).join('');
    userResultList.classList.add('show');
});

document.addEventListener('click', e => {
    if (!userSearch.contains(e.target) && !userResultList.contains(e.target)) userResultList.classList.remove('show');
});

document.getElementById('clearUser').addEventListener('click', () => {
    selUser = null; userIdInput.value = ''; userSearch.value = '';
    userBadge.classList.remove('show'); document.getElementById('sumUser').textContent = '—'; userSearch.focus();
});

document.querySelectorAll('input[name="payment_method"]').forEach(r => {
    r.addEventListener('change', function() {
        document.getElementById('monthlyHint').style.display = this.value === 'monthly' ? 'flex' : 'none';
        calcCost();
    });
});

function confirmSubmit() {
    if (!userIdInput.value) { alert('Pilih pengguna terlebih dahulu.'); userSearch.focus(); return false; }
    if (!document.getElementById('roomSelect').value) { alert('Pilih kamar terlebih dahulu.'); document.getElementById('roomSelect').focus(); return false; }
    const autoOn = document.getElementById('autoApproveCheck').checked;
    const isAdv  = document.getElementById('typeAdvance').checked;
    let msg = 'Buat booking manual untuk ' + (selUser?.name || 'pengguna ini') + '?';
    if (autoOn) { msg += isAdv ? '\n\nBooking akan LANGSUNG DISETUJUI dan masuk Waiting List. Invoice DP akan diterbitkan.' : '\n\nBooking akan LANGSUNG DISETUJUI dan Invoice akan diterbitkan.'; }
    return confirm(msg);
}
calcCost();
updateBookingType();
</script>
</body>
</html>