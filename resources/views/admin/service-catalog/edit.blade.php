<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Layanan — {{ $service->label }} — Urban Kost Admin</title>
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
    --amber:       #f4a535;
    --amber-pale:  #fffbf0;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --card-r: 14px;
    --font-body: 'Instrument Sans', sans-serif;
    --font-display: 'Fraunces', serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: var(--font-body);
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh; font-size: 14px;
    -webkit-font-smoothing: antialiased;
}
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--green-50); }
::-webkit-scrollbar-thumb { background: var(--green-400); border-radius: 3px; }
body::before {
    content: '';
    position: fixed; inset: 0; z-index: -1;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.08) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.05) 0%, transparent 50%);
    pointer-events: none;
}

.page-wrap { max-width: 900px; margin: 0 auto; padding: 28px 24px 60px; }
@media (max-width: 640px) { .page-wrap { padding: 16px 12px 48px; } }

/* ── Hero Card ── */
.hero-card {
    position: relative; overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 24px 28px;
    margin-bottom: 22px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    display: flex;
    align-items: center;
    gap: 16px;
    min-height: 110px;
}
.hero-card::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-arc {
    position: absolute; right: -80px; top: -80px;
    width: 260px; height: 260px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1); pointer-events: none;
}
.hero-arc-2 {
    position: absolute; right: -20px; top: -20px;
    width: 150px; height: 150px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.06); pointer-events: none;
}
.hero-icon-box {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    position: relative; z-index: 1;
}
.hero-content { position: relative; z-index: 1; flex: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 2px 10px; border-radius: 100px;
    font-size: .6rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.8); margin-bottom: 6px;
}
.hero-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.1rem, 3vw, 1.45rem);
    font-weight: 700; color: #fff;
    line-height: 1.15; letter-spacing: -.025em;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.65); }
.hero-sub { font-size: .75rem; color: rgba(255,255,255,.5); margin-top: 4px; }
.hero-back {
    position: relative; z-index: 1; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 10px;
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.25);
    color: #fff; text-decoration: none; transition: all .15s;
}
.hero-back:hover { background: rgba(255,255,255,.22); color: #fff; }
@media (max-width: 480px) { .hero-arc, .hero-arc-2 { display: none; } }

/* ── Flash / Alert ── */
.flash {
    padding: 11px 15px; border-radius: 11px; margin-bottom: 16px;
    font-size: .8rem; font-weight: 500;
    display: flex; align-items: flex-start; gap: 9px;
}
.info-box {
    display: flex; gap: 10px; padding: 12px 15px;
    border-radius: 11px; font-size: .78rem;
    margin-bottom: 16px; line-height: 1.6;
    background: var(--amber-pale); border: 1px solid #fde68a; color: #92400e;
}
.info-box i { color: var(--amber); flex-shrink: 0; margin-top: 1px; }

/* ── Form Grid ── */
.form-grid { display: grid; grid-template-columns: 1fr 290px; gap: 18px; align-items: start; }
@media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
@media (min-width: 769px) { .form-sidebar { position: sticky; top: 20px; } }

/* ── Cards ── */
.o-card {
    background: var(--neutral-0);
    border-radius: var(--card-r);
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 16px;
}
.o-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; gap: 9px;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.o-card-header-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: var(--green-100);
    display: flex; align-items: center; justify-content: center;
    color: var(--green-700); font-size: .85rem; flex-shrink: 0;
}
.o-card-header-title {
    font-size: .68rem; font-weight: 600;
    color: var(--neutral-600);
    text-transform: uppercase; letter-spacing: .07em;
}
.o-card-body { padding: 20px; }
@media (max-width: 480px) { .o-card-body { padding: 14px; } }

/* ── Form Fields ── */
.fld-group { margin-bottom: 16px; }
.fld-group:last-child { margin-bottom: 0; }
.fld-label {
    display: block; font-size: .72rem; font-weight: 600;
    color: var(--neutral-600); margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .06em;
}
.fld-label .req { color: var(--red); margin-left: 2px; }
.fld-label .opt { font-weight: 400; text-transform: none; letter-spacing: 0; color: var(--neutral-400); font-size: .7rem; }
.fld-ctrl {
    width: 100%; padding: 10px 13px;
    border: 1.5px solid var(--neutral-200); border-radius: 9px;
    font-family: var(--font-body); font-size: .88rem;
    color: var(--neutral-900); background: var(--neutral-50);
    outline: none;
    transition: border-color .15s, box-shadow .15s, background .15s;
    -webkit-appearance: none; appearance: none; min-height: 42px;
}
.fld-ctrl:focus {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(42,179,107,.12);
    background: var(--neutral-0);
}
.fld-ctrl.err { border-color: var(--red); box-shadow: 0 0 0 3px rgba(220,38,38,.1); }
.fld-ctrl[readonly] {
    background: var(--neutral-100); color: var(--neutral-400);
    cursor: not-allowed; border-style: dashed;
}
.fld-err { font-size: .7rem; color: var(--red); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.fld-hint { font-size: .7rem; color: var(--neutral-400); margin-top: 4px; line-height: 1.5; display: flex; align-items: flex-start; gap: 4px; }
textarea.fld-ctrl { resize: vertical; min-height: 78px; }

.fld-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width: 480px) { .fld-row { grid-template-columns: 1fr; } }

.uk-hr { border: none; height: 1px; background: var(--neutral-100); margin: 16px 0; }

.icon-group { display: flex; gap: 0; }
.icon-preview {
    width: 42px; min-width: 42px;
    display: flex; align-items: center; justify-content: center;
    background: var(--neutral-100);
    border: 1.5px solid var(--neutral-200); border-right: none;
    border-radius: 9px 0 0 9px; font-size: 1.2rem; flex-shrink: 0;
}
.icon-group .fld-ctrl { border-radius: 0 9px 9px 0; }

.toggle-wrap { display: flex; align-items: center; gap: 10px; padding: 8px 0; }
.toggle-switch { position: relative; width: 46px; height: 26px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-track {
    position: absolute; inset: 0;
    background: #cbd5e1; border-radius: 26px;
    cursor: pointer; transition: background .2s;
}
.toggle-track::after {
    content: ''; position: absolute;
    width: 18px; height: 18px; background: #fff; border-radius: 50%;
    top: 4px; left: 4px; transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .toggle-track { background: var(--green-500); }
.toggle-switch input:checked + .toggle-track::after { transform: translateX(20px); }
.toggle-label { font-size: .82rem; font-weight: 600; color: var(--neutral-700); cursor: pointer; user-select: none; }

.emoji-row { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
.emoji-btn {
    width: 34px; height: 34px; border-radius: 8px;
    border: 1.5px solid var(--neutral-200); background: var(--neutral-0);
    cursor: pointer; font-size: .9rem; color: var(--neutral-600);
    display: flex; align-items: center; justify-content: center;
    transition: all .15s; -webkit-tap-highlight-color: transparent;
}
.emoji-btn:hover { border-color: var(--green-400); background: var(--green-50); color: var(--green-700); }
.emoji-btn:active { transform: scale(.9); }

.preview-svc {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px;
    border: 1.5px solid var(--neutral-200); border-radius: 10px;
}
.preview-cb { width: 17px; height: 17px; accent-color: var(--green-500); flex-shrink: 0; }
.preview-name { font-size: .82rem; font-weight: 600; color: var(--neutral-700); }
.preview-price { font-size: .72rem; font-weight: 700; color: var(--green-600); font-family: var(--font-display); }

.meta-row {
    display: flex; justify-content: space-between;
    font-size: .75rem; padding: 8px 0;
    border-bottom: 1px solid var(--neutral-100); gap: 8px;
}
.meta-row:last-child { border-bottom: none; }
.meta-lbl { color: var(--neutral-400); flex-shrink: 0; }
.meta-val { font-weight: 600; color: var(--neutral-700); text-align: right; }

.danger-zone {
    border: 1.5px solid #fca5a5; border-radius: var(--card-r);
    padding: 14px 16px; margin-top: 4px;
    background: var(--neutral-0);
}
.danger-zone-title {
    font-size: .68rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .08em; color: #991b1b; margin-bottom: 10px;
    display: flex; align-items: center; gap: 6px;
}
.danger-zone-title i { color: var(--red); }

.btn {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 6px; padding: 10px 18px; border-radius: 10px;
    font-size: .82rem; font-weight: 600; font-family: var(--font-body);
    cursor: pointer; border: none; transition: all .15s;
    text-decoration: none; white-space: nowrap; min-height: 42px;
    -webkit-tap-highlight-color: transparent;
}
.btn-primary {
    background: linear-gradient(135deg, var(--green-600), var(--green-700));
    color: #fff; box-shadow: 0 3px 12px rgba(29,148,88,.3);
}
.btn-primary:hover { background: linear-gradient(135deg, var(--green-700), var(--green-800)); transform: translateY(-1px); box-shadow: 0 5px 16px rgba(29,148,88,.35); color: #fff; }
.btn-primary:active { transform: none; }
.btn-outline { background: var(--neutral-0); border: 1.5px solid var(--neutral-200); color: var(--neutral-600); }
.btn-outline:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
.btn-danger { background: var(--red); color: #fff; }
.btn-danger:hover { background: #b91c1c; color: #fff; }
.btn-sm { padding: 8px 14px; font-size: .78rem; min-height: 38px; }
.btn-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 20px; }
@media (max-width: 480px) {
    .btn-row { flex-direction: column; }
    .btn-row .btn { width: 100%; justify-content: center; }
}

.guide-list { list-style: none; padding: 0; margin: 0; }
.guide-list li {
    font-size: .75rem; color: var(--neutral-500);
    padding: 7px 0; border-bottom: 1px solid var(--neutral-100);
    line-height: 1.55; display: flex; gap: 8px; align-items: flex-start;
}
.guide-list li:last-child { border-bottom: none; }
.guide-list li::before { content: '→'; color: var(--green-500); flex-shrink: 0; font-size: .72rem; margin-top: 1px; }

.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45); backdrop-filter: blur(4px);
    z-index: 9999; display: none; align-items: flex-end;
    justify-content: center; padding: 0;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: var(--neutral-0);
    border-radius: 18px 18px 0 0;
    padding: 24px 20px 32px; width: 100%; max-width: 460px;
    box-shadow: 0 -8px 40px rgba(0,0,0,.15);
    animation: slideUp .25s ease;
}
@media (min-width: 480px) {
    .modal-overlay { align-items: center; padding: 16px; }
    .modal-box { border-radius: 18px; padding: 28px; animation: fadeScale .2s ease; }
}
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
@keyframes fadeScale { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
.modal-title {
    font-family: var(--font-display);
    font-size: 1.1rem; font-weight: 700;
    color: var(--neutral-900); margin-bottom: 8px;
    display: flex; align-items: center; gap: 8px;
}
.modal-body { font-size: .82rem; color: var(--neutral-500); line-height: 1.6; margin-bottom: 18px; }
.modal-actions { display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
@media (max-width: 480px) {
    .modal-actions { flex-direction: column-reverse; }
    .modal-actions .btn, .modal-actions form, .modal-actions form button { width: 100%; justify-content: center; }
}

@keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.hero-card { animation: fadeUp .3s ease both; }
.form-grid  { animation: fadeUp .3s .08s ease both; }
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: var(--neutral-0); color: var(--neutral-600);
    border: 1px solid var(--neutral-200);
    font-size: .8rem; font-weight: 600;
    text-decoration: none; transition: all .15s;
}
.btn-back:hover {
    border-color: var(--green-300);
    color: var(--green-700);
    background: var(--green-50);
}
</style>
</head>
<body>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.service-catalog.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="hero-card">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Katalog Layanan
            </div>
            <div class="hero-title">
                Edit <em>{{ $service->label }}</em>
            </div>
            <div class="hero-sub">Perubahan harga hanya berlaku untuk booking baru</div>
        </div>
    </div>

    @if($errors->any())
    <div class="flash" style="background:var(--red-pale);border:1px solid #fca5a5;color:#991b1b">
        <i class="bi bi-x-circle-fill" style="flex-shrink:0;margin-top:1px"></i>
        <ul style="margin:0;padding-left:1.1rem">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    @foreach(['success','error','warning'] as $type)
    @if(session($type))
    <div class="flash" style="
        background:{{ $type==='success'?'var(--green-50)':($type==='error'?'var(--red-pale)':'var(--amber-pale)') }};
        border:1px solid {{ $type==='success'?'var(--green-200)':($type==='error'?'#fca5a5':'#fde68a') }};
        color:{{ $type==='success'?'var(--green-800)':($type==='error'?'#991b1b':'#92400e') }}">
        <i class="bi bi-{{ $type==='success'?'check-circle-fill':'exclamation-triangle-fill' }}" style="flex-shrink:0;margin-top:1px"></i>
        <span>{!! session($type) !!}</span>
    </div>
    @endif
    @endforeach

    @if($activeBookingsCount > 0)
    <div class="info-box">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>Layanan ini digunakan di <strong>{{ $activeBookingsCount }} booking aktif</strong>.
        Perubahan harga hanya berlaku untuk booking baru. Key tidak bisa diubah.</div>
    </div>
    @endif

    <div class="form-grid">
        <div>
            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-pencil-fill"></i></div>
                    <span class="o-card-header-title">Detail Layanan</span>
                </div>
                <div class="o-card-body">
                    <form method="POST" action="{{ route('admin.service-catalog.update', $service->id) }}" id="editForm">
                        @csrf @method('PUT')

                        <div class="fld-group">
                            <label class="fld-label">Key <span class="req">*</span></label>
                            @if($activeBookingsCount > 0)
                                <input type="text" class="fld-ctrl" value="{{ $service->key }}" readonly>
                                <input type="hidden" name="key" value="{{ $service->key }}">
                                <div class="fld-hint"><i class="bi bi-lock-fill"></i> Key terkunci — masih dipakai di booking aktif</div>
                            @else
                                <input type="text" name="key"
                                       class="fld-ctrl {{ $errors->has('key') ? 'err' : '' }}"
                                       value="{{ old('key', $service->key) }}" required autocomplete="off">
                                @error('key')
                                    <span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                                @enderror
                            @endif
                        </div>

                        <div class="fld-group">
                            <label class="fld-label">Nama Layanan <span class="req">*</span></label>
                            <input type="text" name="label" id="labelInput"
                                   class="fld-ctrl {{ $errors->has('label') ? 'err' : '' }}"
                                   value="{{ old('label', $service->label) }}" required
                                   oninput="updatePreview()">
                            @error('label')
                                <span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fld-row">
                            <div class="fld-group" style="margin-bottom:0">
                                <label class="fld-label">Harga/Bulan (Rp) <span class="req">*</span></label>
                                <input type="number" name="price" id="priceInput"
                                       class="fld-ctrl {{ $errors->has('price') ? 'err' : '' }}"
                                       value="{{ old('price', $service->price) }}" min="0" step="1000" required
                                       inputmode="numeric" oninput="updatePreview()">
                                @error('price')
                                    <span class="fld-err"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fld-group" style="margin-bottom:0">
                                <label class="fld-label">Urutan Tampil</label>
                                <input type="number" name="sort_order" class="fld-ctrl"
                                       value="{{ old('sort_order', $service->sort_order) }}" min="0" inputmode="numeric">
                                <div class="fld-hint"><i class="bi bi-info-circle"></i> Angka kecil = tampil lebih awal</div>
                            </div>
                        </div>

                        <div class="uk-hr"></div>

                        <div class="fld-group">
                            <label class="fld-label">Ikon Emoji <span class="opt">(opsional)</span></label>
                            <div class="icon-group">
                                <div class="icon-preview" id="iconPreview">{{ old('icon', $service->icon) ?: '' }}</div>
                                <input type="text" name="icon" id="iconInput"
                                       class="fld-ctrl" value="{{ old('icon', $service->icon) }}"
                                       placeholder="Paste emoji..." maxlength="10"
                                       oninput="onIconChange(this.value)">
                            </div>
                            <div class="emoji-row">
                                @foreach([
                                    ['bi-snow','❄️'],['bi-brush','🧹'],['bi-wifi','🌐'],
                                    ['bi-lightning-charge','💡'],['bi-droplet','🪣'],['bi-basket','🧺'],
                                    ['bi-p-circle','🅿️'],['bi-flower1','🪴'],['bi-lock','🔒'],
                                    ['bi-water','🚿'],['bi-tv','📺'],['bi-cup-hot','☕'],
                                ] as [$icon, $val])
                                <button type="button" class="emoji-btn" onclick="setEmoji('{{ $val }}')" title="{{ $val }}">
                                    <i class="bi {{ $icon }}"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="fld-group">
                            <label class="fld-label">Deskripsi <span class="opt">(opsional)</span></label>
                            <textarea name="description" class="fld-ctrl" rows="2"
                                      placeholder="Keterangan singkat...">{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="fld-group">
                            <label class="fld-label">Status</label>
                            <div class="toggle-wrap">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_active" value="1" id="toggleActive"
                                           {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                           onchange="document.getElementById('toggleLbl').textContent = this.checked ? 'Aktif' : 'Nonaktif'">
                                    <span class="toggle-track"></span>
                                </label>
                                <span class="toggle-label" id="toggleLbl">
                                    {{ old('is_active', $service->is_active) ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.service-catalog.index') }}" class="btn btn-outline">
                                <i class="bi bi-x-lg" style="font-size:.8rem"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if($activeBookingsCount === 0)
            <div class="danger-zone">
                <div class="danger-zone-title">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Zona Berbahaya
                </div>
                <p style="font-size:.75rem;color:var(--neutral-500);margin-bottom:12px">
                    Hapus layanan ini secara permanen. Tindakan tidak bisa dibatalkan.
                </p>
                <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal()">
                    <i class="bi bi-trash3-fill"></i> Hapus Layanan Ini
                </button>
            </div>
            @endif
        </div>

        <div class="form-sidebar">
            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-eye-fill"></i></div>
                    <span class="o-card-header-title">Preview di Form Booking</span>
                </div>
                <div class="o-card-body">
                    <div class="preview-svc">
                        <input type="checkbox" class="preview-cb" disabled>
                        <div>
                            <div class="preview-name" id="previewName">
                                {{ $service->icon }} {{ $service->label }}
                            </div>
                            <div class="preview-price" id="previewPrice">
                                +Rp {{ number_format($service->price, 0, ',', '.') }}/bln
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="o-card">
                <div class="o-card-header">
                    <div class="o-card-header-icon"><i class="bi bi-info-circle-fill"></i></div>
                    <span class="o-card-header-title">Info Layanan</span>
                </div>
                <div class="o-card-body">
                    <div class="meta-row">
                        <span class="meta-lbl">Dibuat</span>
                        <span class="meta-val">{{ $service->created_at->locale('id')->isoFormat('D MMM Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-lbl">Diperbarui</span>
                        <span class="meta-val">{{ $service->updated_at->locale('id')->isoFormat('D MMM Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-lbl">Booking Aktif</span>
                        <span class="meta-val" style="color:{{ $activeBookingsCount > 0 ? 'var(--amber)' : 'var(--green-600)' }}">
                            {{ $activeBookingsCount }} booking
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-lbl">Status</span>
                        <span class="meta-val" style="color:{{ $service->is_active ? 'var(--green-600)' : 'var(--neutral-400)' }}">
                            {{ $service->is_active ? ' Aktif' : ' Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($activeBookingsCount === 0)
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-title">
            <i class="bi bi-exclamation-triangle-fill" style="color:var(--red)"></i>
            Hapus Layanan
        </div>
        <div class="modal-body">
            Yakin hapus layanan <strong>{{ $service->icon }} {{ $service->label }}</strong>?<br>
            Tindakan ini tidak bisa dibatalkan.
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeDeleteModal()">Batal</button>
            <form method="POST" action="{{ route('admin.service-catalog.destroy', $service->id) }}" style="margin:0;display:flex">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center">
                    <i class="bi bi-trash3-fill"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function onIconChange(val) {
    document.getElementById('iconPreview').textContent = val || '';
    updatePreview();
}
function setEmoji(em) {
    document.getElementById('iconInput').value = em;
    document.getElementById('iconPreview').textContent = em;
    updatePreview();
}
function updatePreview() {
    const icon  = document.getElementById('iconInput').value || '';
    const label = document.getElementById('labelInput').value || 'Nama Layanan';
    const price = parseInt(document.getElementById('priceInput').value) || 0;
    document.getElementById('previewName').textContent  = (icon ? icon + ' ' : '') + label;
    document.getElementById('previewPrice').textContent = '+Rp ' + price.toLocaleString('id-ID') + '/bln';
}
function openDeleteModal()  { document.getElementById('deleteModal')?.classList.add('open'); }
function closeDeleteModal() { document.getElementById('deleteModal')?.classList.remove('open'); }
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteModal(); });
</script>
</body>
</html>