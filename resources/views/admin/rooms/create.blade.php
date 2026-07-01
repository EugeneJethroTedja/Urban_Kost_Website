<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ isset($room) ? 'Edit Kamar — '.$room->room_number : 'Tambah Kamar' }} — Urban Kost</title>
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
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-200:   #fde68a;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-200:    #bfdbfe;
    --violet:      #7c3aed;
    --violet-pale: #ede9fe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-200:     #fecaca;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --r-card: 16px;
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
.page-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50); }
.page-bg::before {
    content: ''; position: absolute; inset: 0;
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
.page-wrap { max-width: 1000px; margin: 0 auto; padding: 32px 24px 64px; }

.page-title span { color: var(--green-600); }
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: .78rem; font-weight: 600; color: var(--neutral-600);
    background: var(--neutral-0); border: 1px solid var(--green-200);
    border-radius: 9px; padding: 8px 14px;
    text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s;
}
.btn-back:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

.flash-alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; margin-bottom: 14px; font-size: .8rem; font-weight: 500; border: 1px solid; }

.form-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

.uk-card { background: var(--neutral-0); border: 1px solid var(--green-100); border-radius: var(--r-card); box-shadow: var(--shadow-md); overflow: hidden; margin-bottom: 16px; }
.uk-card:last-child { margin-bottom: 0; }
.uk-card-head {
    display: flex; align-items: center; gap: 9px;
    padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
    font-size: .72rem; font-weight: 600; color: var(--neutral-600);
    text-transform: uppercase; letter-spacing: .07em;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.head-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .82rem; background: var(--green-100); color: var(--green-700); flex-shrink: 0; }
.uk-card-body { padding: 20px; }

.field-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
.field-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 14px; }
.field-group { margin-bottom: 14px; }

.form-label { display: block; font-size: .77rem; font-weight: 600; color: var(--neutral-700); margin-bottom: 5px; }
.form-label .req { color: var(--red); margin-left: 2px; }
.form-label .opt { color: var(--neutral-400); font-weight: 400; font-size: .7rem; margin-left: 4px; }

.form-control {
    width: 100%; padding: 10px 13px; font-size: .83rem;
    font-family: 'Instrument Sans', sans-serif; color: var(--neutral-900);
    background: var(--neutral-0); border: 1px solid var(--neutral-200);
    border-radius: 10px; outline: none; transition: border-color .15s, box-shadow .15s;
}
.form-control:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
.form-control.is-invalid { border-color: var(--red); }
.form-control::placeholder { color: var(--neutral-400); }
textarea.form-control { resize: vertical; min-height: 88px; }

.invalid-feedback { font-size: .72rem; color: var(--red); margin-top: 4px; font-weight: 600; display: flex; align-items: center; gap: 4px; }

.input-wrap { position: relative; }
.input-prefix { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--neutral-400); font-size: .85rem; pointer-events: none; }
.has-prefix { padding-left: 34px; }

.form-select {
    width: 100%; padding: 10px 34px 10px 13px;
    font-size: .83rem; font-family: 'Instrument Sans', sans-serif; color: var(--neutral-700);
    background: var(--neutral-0) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238fa89d' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
    border: 1px solid var(--neutral-200); border-radius: 10px; outline: none; appearance: none;
    transition: border-color .15s;
}
.form-select:focus { border-color: var(--green-400); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }

.status-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
.status-opt { position: relative; }
.status-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.status-opt label {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 13px; border-radius: 10px;
    border: 1px solid var(--neutral-200); background: var(--neutral-0);
    font-size: .78rem; font-weight: 600; color: var(--neutral-500);
    cursor: pointer; transition: all .15s;
}
.status-opt label .sopt-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.status-opt.available input:checked + label { border-color: var(--green-400); background: var(--green-50); color: var(--green-800); }
.status-opt.occupied  input:checked + label { border-color: var(--red-200); background: var(--red-pale); color: var(--red); }
.status-opt.reserved  input:checked + label { border-color: var(--blue-200); background: var(--blue-pale); color: var(--blue); }
.status-opt.maintenance input:checked + label { border-color: var(--amber-200); background: var(--amber-pale); color: #92400e; }

.fac-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; }
.fac-opt { position: relative; }
.fac-opt input[type="checkbox"] { position: absolute; opacity: 0; width: 0; height: 0; }
.fac-opt label {
    display: flex; align-items: center; gap: 7px;
    padding: 8px 11px; border-radius: 9px;
    border: 1px solid var(--neutral-200); background: var(--neutral-0);
    font-size: .75rem; font-weight: 600; color: var(--neutral-500);
    cursor: pointer; transition: all .15s; user-select: none;
}
.fac-opt label i { font-size: .78rem; color: var(--neutral-400); flex-shrink: 0; transition: color .15s; }
.fac-opt input:checked + label { border-color: var(--green-400); background: var(--green-50); color: var(--green-700); }
.fac-opt input:checked + label i { color: var(--green-500); }
.fac-check-icon {
    width: 16px; height: 16px; border-radius: 4px;
    border: 1px solid var(--neutral-200); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; transition: all .15s;
}
.fac-opt input:checked + label .fac-check-icon { background: var(--green-500); border-color: var(--green-500); }
.fac-opt input:checked + label .fac-check-icon::after { content: '\2713'; font-size: .55rem; color: #fff; font-weight: 700; }

.upload-area {
    border: 2px dashed var(--neutral-200); border-radius: 12px;
    padding: 20px 16px; text-align: center;
    background: var(--neutral-50); cursor: pointer; transition: all .2s; position: relative;
}
.upload-area:hover { border-color: var(--green-300); background: var(--green-50); }
.upload-area.dragover { border-color: var(--green-400); background: var(--green-100); }
.upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-icon { font-size: 1.8rem; color: var(--neutral-300); margin-bottom: 8px; display: block; }
.upload-label { font-size: .78rem; font-weight: 600; color: var(--neutral-500); }
.upload-sub { font-size: .7rem; color: var(--neutral-400); margin-top: 3px; }

.summary-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--neutral-100); font-size: .77rem; }
.summary-row:last-child { border-bottom: none; }
.summary-lbl { color: var(--neutral-400); font-weight: 500; }
.summary-val { font-weight: 600; color: var(--neutral-900); }

.price-preview {
    background: var(--green-50); border: 1px solid var(--green-200);
    border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 14px;
}
.price-preview-lbl { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: var(--green-700); margin-bottom: 4px; }
.price-preview-val { font-family: 'Fraunces', serif; font-size: 1.6rem; font-weight: 700; color: var(--green-800); letter-spacing: -.03em; line-height: 1; }
.price-preview-sub { font-size: .7rem; color: var(--green-600); margin-top: 3px; }

.form-actions { display: flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
.btn-submit {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 26px; font-size: .83rem; font-weight: 600;
    font-family: 'Instrument Sans', sans-serif; color: #fff;
    background: linear-gradient(135deg, var(--green-600), var(--green-500));
    border: none; border-radius: 10px; cursor: pointer;
    box-shadow: 0 2px 8px rgba(42,179,107,.28); transition: all .2s;
}
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(42,179,107,.36); }
.btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 20px; font-size: .83rem; font-weight: 600;
    font-family: 'Instrument Sans', sans-serif; color: var(--neutral-600);
    background: var(--neutral-0); border: 1px solid var(--neutral-200);
    border-radius: 10px; cursor: pointer; text-decoration: none; transition: all .15s;
}
.btn-ghost:hover { border-color: var(--neutral-300); background: var(--neutral-50); color: var(--neutral-700); }

.hero-banner {
    position: relative; overflow: hidden; border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 28px 28px 24px; margin-bottom: 22px;
    box-shadow: 0 8px 40px rgba(14,63,39,.28), 0 2px 8px rgba(0,0,0,.08);
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
    background-size: 28px 28px; pointer-events: none;
}
.hero-arc {
    position: absolute; right: -80px; top: -80px;
    width: 280px; height: 280px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1); pointer-events: none;
}
.hero-content { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    padding: 4px 12px; border-radius: var(--r-pill);
    font-size: .65rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.8); margin-bottom: 10px;
}
.hero-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: 'Fraunces', serif; font-weight: 700;
    font-size: clamp(1.3rem, 3vw, 1.85rem);
    color: #fff; line-height: 1.15; letter-spacing: -.025em; margin-bottom: 6px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .84rem; color: rgba(255,255,255,.6); line-height: 1.6; max-width: 480px; }

.fade-up { opacity: 0; transform: translateY(14px); animation: fadeUp .4s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .04s; } .d2 { animation-delay: .09s; } .d3 { animation-delay: .14s; } .d4 { animation-delay: .18s; }

@media (max-width: 860px) { .form-grid { grid-template-columns: 1fr; } }
@media (max-width: 580px) { .field-row { grid-template-columns: 1fr; } }
@media (max-width: 660px) { .field-row-3 { grid-template-columns: 1fr 1fr; } }
@media (max-width: 420px) { .field-row-3 { grid-template-columns: 1fr; } }
@media (max-width: 600px) {
    .page-wrap { padding: 16px 12px 48px; }
    .uk-card-body { padding: 14px; }
    .fac-grid { grid-template-columns: repeat(2, 1fr); }
    .status-grid { grid-template-columns: 1fr 1fr; }
    .form-actions { flex-direction: column; }
    .btn-submit, .btn-ghost { justify-content: center; }
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <a href="{{ route('admin.rooms.index') }}" class="btn-back fade-up d1" style="margin-bottom: 16px;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="hero-banner fade-up d1">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span> Manajemen Kamar
            </div>
            <h1 class="hero-title">
                @if(isset($room))
                    Edit Kamar <em>{{ $room->room_number }}</em>
                @else
                    Tambah <em>Kamar Baru</em>
                @endif
            </h1>
            <p class="hero-sub">
                {{ isset($room) ? 'Perbarui informasi, harga, dan fasilitas kamar.' : 'Isi detail kamar baru yang akan ditambahkan ke sistem.' }}
            </p>
        </div>
    </div>

    @foreach(['success','error','warning'] as $type)
    @if(session($type))
    <div class="flash-alert fade-up d1" style="
        background:{{ $type==='success'?'var(--green-100)':($type==='error'?'var(--red-pale)':'var(--amber-pale)') }};
        border-color:{{ $type==='success'?'var(--green-200)':($type==='error'?'var(--red-200)':'var(--amber-200)') }};
        color:{{ $type==='success'?'var(--green-800)':($type==='error'?'var(--red)':'#92400e') }};
    ">
        <i class="bi bi-{{ $type==='success'?'check-circle-fill':($type==='error'?'exclamation-circle-fill':'exclamation-triangle-fill') }}"></i>
        {!! session($type) !!}
    </div>
    @endif
    @endforeach

    <form method="POST"
          action="{{ isset($room) ? route('admin.rooms.update', $room->id) : route('admin.rooms.store') }}"
          enctype="multipart/form-data"
          id="roomForm"
          novalidate>
        @csrf
        @if(isset($room)) @method('PUT') @endif

        <div class="form-grid">
            <div>
                <div class="uk-card fade-up d2">
                    <div class="uk-card-head">
                        <div class="head-icon"><i class="bi bi-door-open-fill"></i></div>
                        Identitas Kamar
                    </div>
                    <div class="uk-card-body">
                        <div class="field-row">
                            <div>
                                <label class="form-label" for="room_number">
                                    Nomor Kamar <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-hash input-prefix"></i>
                                    <input type="text" name="room_number" id="room_number"
                                        class="form-control has-prefix @error('room_number') is-invalid @enderror"
                                        value="{{ old('room_number', $room->room_number ?? '') }}"
                                        placeholder="A-101" required>
                                </div>
                                @error('room_number')
                                <div class="invalid-feedback"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label" for="room_name">
                                    Nama Kamar <span class="opt">(opsional)</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="bi bi-tag input-prefix"></i>
                                    <input type="text" name="room_name" id="room_name"
                                        class="form-control has-prefix"
                                        value="{{ old('room_name', $room->room_name ?? '') }}"
                                        placeholder="Kamar Standar A1">
                                </div>
                            </div>
                        </div>

                        <div class="field-row-3">
                            <div>
                                <label class="form-label" for="floor">Lantai</label>
                                <div class="input-wrap">
                                    <i class="bi bi-layers-fill input-prefix"></i>
                                    <input type="number" name="floor" id="floor"
                                        class="form-control has-prefix"
                                        value="{{ old('floor', $room->floor ?? 1) }}"
                                        min="1" max="20">
                                </div>
                            </div>
                            <div>
                                <label class="form-label" for="size">Ukuran</label>
                                <div class="input-wrap">
                                    <i class="bi bi-aspect-ratio input-prefix"></i>
                                    <input type="text" name="size" id="size"
                                        class="form-control has-prefix"
                                        value="{{ old('size', $room->size ?? '') }}"
                                        placeholder="3x4 m">
                                </div>
                            </div>
                            <div>
                                <label class="form-label" for="priceInput">
                                    Harga / Bulan <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <span class="input-prefix">Rp</span>
                                    <input type="number" name="price" id="priceInput"
                                        class="form-control has-prefix @error('price') is-invalid @enderror"
                                        value="{{ old('price', $room->price ?? '') }}"
                                        placeholder="800000" min="0"
                                        oninput="updatePricePreview(this.value)"
                                        required>
                                </div>
                                @error('price')
                                <div class="invalid-feedback"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="form-label" for="description">
                                Deskripsi <span class="opt">(opsional)</span>
                            </label>
                            <textarea name="description" id="description"
                                class="form-control"
                                placeholder="Deskripsi singkat tentang kamar, keunggulan, lokasi, dll...">{{ old('description', $room->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="uk-card fade-up d3">
                    <div class="uk-card-head">
                        <div class="head-icon" style="background:var(--blue-pale);color:var(--blue)">
                            <i class="bi bi-toggles"></i>
                        </div>
                        Status Kamar
                    </div>
                    <div class="uk-card-body">
                        @php
                            $currentStatus = old('status', $room->status ?? 'available');
                            $statuses = [
                                'available'   => ['label'=>'Tersedia',  'icon'=>'bi-door-open-fill','dot'=>'var(--green-500)','cls'=>'available'],
                                'occupied'    => ['label'=>'Terisi',    'icon'=>'bi-people-fill',  'dot'=>'var(--red)',       'cls'=>'occupied'],
                                'reserved'    => ['label'=>'Dipesan',   'icon'=>'bi-bookmark-fill','dot'=>'var(--blue)',      'cls'=>'reserved'],
                                'maintenance' => ['label'=>'Perbaikan', 'icon'=>'bi-tools',        'dot'=>'var(--amber)',     'cls'=>'maintenance'],
                            ];
                        @endphp
                        <div class="status-grid">
                            @foreach($statuses as $val => $cfg)
                            <div class="status-opt {{ $cfg['cls'] }}">
                                <input type="radio" name="status" id="status_{{ $val }}"
                                    value="{{ $val }}"
                                    {{ $currentStatus === $val ? 'checked' : '' }} required>
                                <label for="status_{{ $val }}">
                                    <span class="sopt-dot" style="background:{{ $cfg['dot'] }}"></span>
                                    <i class="bi {{ $cfg['icon'] }}" style="font-size:.8rem"></i>
                                    {{ $cfg['label'] }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="uk-card fade-up d3">
                    <div class="uk-card-head">
                        <div class="head-icon" style="background:var(--violet-pale);color:var(--violet)">
                            <i class="bi bi-stars"></i>
                        </div>
                        Fasilitas Kamar
                        <span id="facCount" style="margin-left:auto;font-size:.65rem;font-weight:600;color:var(--green-700);background:var(--green-100);padding:2px 9px;border-radius:var(--r-pill);border:1px solid var(--green-200)">
                            0 dipilih
                        </span>
                    </div>
                    <div class="uk-card-body">
                        @php
                            $allFacilities = [
                                'AC'            => 'bi-wind',
                                'Shower'        => 'bi-droplet-fill',
                                'Sprei 2 Set'   => 'bi-layers-fill',
                                'Kasur'         => 'bi-moon-fill', 
                                'Bantal'        => 'bi-circle-fill',
                                'Guling'        => 'bi-record-circle-fill',
                                'Kursi Belajar' => 'bi-person-workspace',
                                'Kaca'          => 'bi-square',
                                'Lemari'        => 'bi-archive-fill',
                                'Meja Belajar'  => 'bi-layout-text-window',
                                'Smart TV'      => 'bi-tv-fill',
                            ];
                            $selectedFacilities = old('facilities', $room->facilities ?? []);
                        @endphp
                        <div class="fac-grid">
                            @foreach($allFacilities as $f => $icon)
                            <div class="fac-opt">
                                <input class="fac-checkbox" type="checkbox"
                                    name="facilities[]"
                                    value="{{ $f }}"
                                    id="f_{{ Str::slug($f) }}"
                                    {{ in_array($f, $selectedFacilities) ? 'checked' : '' }}
                                    onchange="updateFacCount()">
                                <label for="f_{{ Str::slug($f) }}">
                                    <span class="fac-check-icon"></span>
                                    <i class="bi {{ $icon }}"></i>
                                    {{ $f }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="fade-up d4">
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-{{ isset($room) ? 'check-circle-fill' : 'plus-circle-fill' }}"></i>
                            {{ isset($room) ? 'Simpan Perubahan' : 'Tambah Kamar' }}
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn-ghost">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-card fade-up d2">
                    <div class="uk-card-head">
                        <div class="head-icon"><i class="bi bi-cash-coin"></i></div>
                        Pratinjau Harga
                    </div>
                    <div class="uk-card-body">
                        <div class="price-preview">
                            <div class="price-preview-lbl">Harga Sewa</div>
                            <div class="price-preview-val" id="pricePreview">
                                @if(old('price', $room->price ?? null))
                                    Rp {{ number_format(old('price', $room->price), 0, ',', '.') }}
                                @else
                                    Rp —
                                @endif
                            </div>
                            <div class="price-preview-sub">per bulan</div>
                        </div>
                        <div>
                            <div class="summary-row">
                                <span class="summary-lbl">Per hari (est.)</span>
                                <span class="summary-val" id="pricePerDay">—</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-lbl">Per tahun</span>
                                <span class="summary-val" id="pricePerYear">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-card fade-up d3">
                    <div class="uk-card-head">
                        <div class="head-icon" style="background:var(--blue-pale);color:var(--blue)">
                            <i class="bi bi-image-fill"></i>
                        </div>
                        Foto Kamar
                    </div>
                    <div class="uk-card-body">
                        <div class="upload-area" id="uploadArea">
                            <input type="file" name="images[]" id="imageInput"
                                accept="image/*" multiple
                                onchange="previewImages(event)">
                            <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                            <div class="upload-label">Klik atau drag foto ke sini</div>
                            <div class="upload-sub">Maks. 5 foto &middot; JPG, PNG, WebP &middot; maks. 2 MB/foto</div>
                        </div>
                        <div id="imagePreviewGrid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:12px"></div>
                        <div id="imagePreviewCount" style="font-size:.7rem;color:var(--neutral-400);margin-top:6px;text-align:center"></div>

                        @if(isset($room) && $room->images->isNotEmpty())
                        <div style="margin-top:14px">
                            <div style="font-size:.72rem;font-weight:600;color:var(--neutral-600);margin-bottom:8px">
                                Foto saat ini ({{ $room->images->count() }}/5)
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                                @foreach($room->images as $img)
                                <div style="position:relative">
                                    <img src="{{ $img->url }}"
                                        style="width:100%;height:72px;object-fit:cover;border-radius:8px;border:2px solid {{ $img->is_primary ? 'var(--green-400)' : 'var(--neutral-200)' }}">
                                    @if($img->is_primary)
                                    <span style="position:absolute;top:3px;left:3px;background:var(--green-500);color:#fff;font-size:.52rem;font-weight:700;padding:2px 5px;border-radius:4px">Utama</span>
                                    @endif
                                    @if(!$img->is_primary)
                                    <button type="submit" form="form-primary-{{ $img->id }}" title="Jadikan foto utama"
                                        style="position:absolute;bottom:3px;left:3px;width:22px;height:22px;border-radius:4px;background:rgba(0,0,0,.45);border:none;color:#fff;font-size:.65rem;cursor:pointer;display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-star"></i>
                                    </button>
                                    @endif
                                    <button type="submit" form="form-delete-{{ $img->id }}" title="Hapus foto"
                                        onclick="return confirm('Hapus foto ini?')"
                                        style="position:absolute;bottom:3px;right:3px;width:22px;height:22px;border-radius:4px;background:rgba(220,38,38,.75);border:none;color:#fff;font-size:.65rem;cursor:pointer;display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @if($room->images->count() >= 5)
                            <div style="font-size:.72rem;color:#92400e;background:var(--amber-pale);border:1px solid var(--amber-200);border-radius:8px;padding:8px 10px;margin-top:10px">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Sudah mencapai batas 5 foto. Hapus foto lama untuk menambah baru.
                            </div>
                            @else
                            <div style="font-size:.7rem;color:var(--neutral-400);margin-top:6px">
                                Masih bisa tambah {{ 5 - $room->images->count() }} foto lagi.
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div class="uk-card fade-up d4">
                    <div class="uk-card-head">
                        <div class="head-icon" style="background:var(--violet-pale);color:var(--violet)">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        Ringkasan
                    </div>
                    <div class="uk-card-body" style="padding-top:14px;padding-bottom:14px">
                        <div class="summary-row">
                            <span class="summary-lbl">No. Kamar</span>
                            <span class="summary-val" id="sumRoomNum">—</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-lbl">Lantai</span>
                            <span class="summary-val" id="sumFloor">—</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-lbl">Ukuran</span>
                            <span class="summary-val" id="sumSize">—</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-lbl">Fasilitas</span>
                            <span class="summary-val" id="sumFac">0 item</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(isset($room) && $room->images->isNotEmpty())
        @foreach($room->images as $img)
            @if(!$img->is_primary)
            <form id="form-primary-{{ $img->id }}"
                  method="POST"
                  action="{{ route('admin.rooms.images.primary', [$room->id, $img->id]) }}"
                  style="display:none">
                @csrf @method('PATCH')
            </form>
            @endif
            <form id="form-delete-{{ $img->id }}"
                  method="POST"
                  action="{{ route('admin.rooms.images.destroy', [$room->id, $img->id]) }}"
                  style="display:none">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endif

</div>

<script>
function formatRupiah(n) {
    if (!n || isNaN(n)) return 'Rp —';
    return 'Rp ' + parseInt(n).toLocaleString('id-ID');
}
function updatePricePreview(val) {
    const n = parseInt(val) || 0;
    document.getElementById('pricePreview').textContent  = n ? formatRupiah(n) : 'Rp —';
    document.getElementById('pricePerDay').textContent   = n ? 'Rp ' + Math.round(n/30).toLocaleString('id-ID') : '—';
    document.getElementById('pricePerYear').textContent  = n ? 'Rp ' + (n*12).toLocaleString('id-ID') : '—';
}

document.getElementById('room_number').addEventListener('input', function() {
    document.getElementById('sumRoomNum').textContent = this.value || '—';
});
document.getElementById('floor').addEventListener('input', function() {
    document.getElementById('sumFloor').textContent = this.value ? 'Lantai ' + this.value : '—';
});
document.getElementById('size').addEventListener('input', function() {
    document.getElementById('sumSize').textContent = this.value || '—';
});

function updateFacCount() {
    const checked = document.querySelectorAll('.fac-checkbox:checked').length;
    document.getElementById('facCount').textContent = checked + ' dipilih';
    document.getElementById('sumFac').textContent   = checked + ' item';
}

function previewImages(e) {
    const files = Array.from(e.target.files).slice(0, 5);
    const grid  = document.getElementById('imagePreviewGrid');
    const count = document.getElementById('imagePreviewCount');
    grid.innerHTML = '';
    files.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative';
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.style.cssText = 'width:100%;height:72px;object-fit:cover;border-radius:8px;border:1.5px solid var(--neutral-200)';
            if (idx === 0) {
                const badge = document.createElement('span');
                badge.textContent = 'Utama';
                badge.style.cssText = 'position:absolute;top:4px;left:4px;background:var(--green-500);color:#fff;font-size:.55rem;font-weight:700;padding:2px 6px;border-radius:4px';
                wrap.appendChild(badge);
            }
            wrap.appendChild(img);
            grid.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
    count.textContent = files.length + ' foto dipilih' + (files.length === 5 ? ' (batas maksimal)' : '');
}

const uploadArea = document.getElementById('uploadArea');
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const dropped = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')).slice(0, 5);
    if (dropped.length) {
        const dt = new DataTransfer();
        dropped.forEach(f => dt.items.add(f));
        document.getElementById('imageInput').files = dt.files;
        previewImages({ target: { files: dropped } });
    }
});

(function init() {
    updateFacCount();
    updatePricePreview(document.getElementById('priceInput').value);
    const rn = document.getElementById('room_number').value;
    const fl = document.getElementById('floor').value;
    const sz = document.getElementById('size').value;
    if (rn) document.getElementById('sumRoomNum').textContent = rn;
    if (fl) document.getElementById('sumFloor').textContent   = 'Lantai ' + fl;
    if (sz) document.getElementById('sumSize').textContent    = sz;
})();
</script>

@stack('scripts')
</body>
</html>