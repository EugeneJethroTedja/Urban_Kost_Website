<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Invoice — Urban Kost</title>
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
.page-bg {
    position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50);
}
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
.page-wrap { max-width: 1100px; margin: 0 auto; padding: 90px 28px 56px; width: 100%; }
.breadcrumb-bar {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 20px; font-size: .75rem; color: var(--neutral-500);
}
.breadcrumb-bar a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { color: var(--green-700); }
.breadcrumb-bar i { font-size: .65rem; color: var(--neutral-400); }
.page-header { margin-bottom: 24px; }
.page-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 700; color: var(--neutral-900);
    letter-spacing: -.03em; line-height: 1.1;
}
.page-title em { font-style: italic; font-weight: 400; color: var(--green-600); }
.page-subtitle { font-size: .78rem; color: var(--neutral-500); font-weight: 400; margin-top: 4px; }
.form-grid {
    display: grid; grid-template-columns: 1fr 360px;
    gap: 20px; align-items: start;
}
.form-card {
    background: var(--neutral-0); border-radius: 16px;
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 16px;
}
.form-card:last-child { margin-bottom: 0; }
.card-head {
    padding: 14px 20px; border-bottom: 1px solid var(--neutral-100);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    display: flex; align-items: center; gap: 10px;
}
.card-head-icon {
    width: 32px; height: 32px; border-radius: 9px;
    background: var(--green-100); color: var(--green-700);
    display: flex; align-items: center; justify-content: center; font-size: .88rem;
}
.card-head-title {
    font-size: .72rem; font-weight: 600; color: var(--neutral-600);
    text-transform: uppercase; letter-spacing: .07em;
}
.card-head-sub { font-size: .68rem; color: var(--neutral-400); margin-top: 2px; }
.card-body { padding: 20px; }
.field-group { display: grid; gap: 14px; }
.field-group.col-2 { grid-template-columns: 1fr 1fr; }
.field-wrap { display: flex; flex-direction: column; gap: 5px; }
.field-label { font-size: .74rem; font-weight: 600; color: var(--neutral-700); display: flex; align-items: center; gap: 4px; }
.field-label .req { color: var(--red); font-size: .8rem; }
.form-input, .form-select-custom, .form-textarea {
    width: 100%; padding: 9px 13px;
    border: 1px solid var(--green-200); border-radius: 10px;
    font-size: .82rem; font-family: 'Instrument Sans', sans-serif;
    color: var(--neutral-900); background: #fff;
    transition: border-color .15s, box-shadow .15s; outline: none; appearance: none;
}
.form-input:focus, .form-select-custom:focus, .form-textarea:focus {
    border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.12);
}
.form-input.is-invalid, .form-select-custom.is-invalid { border-color: var(--red); }
.form-input::placeholder, .form-textarea::placeholder { color: var(--neutral-400); }
.form-textarea { resize: vertical; min-height: 80px; line-height: 1.6; }
.form-hint { font-size: .69rem; color: var(--neutral-400); }
.invalid-msg { font-size: .69rem; color: var(--red); font-weight: 600; margin-top: 3px; }
.select-wrapper { position: relative; }
.select-wrapper::after {
    content: '\F282'; font-family: 'bootstrap-icons';
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: var(--neutral-400); pointer-events: none; font-size: .8rem;
}
.select-wrapper .form-select-custom { padding-right: 32px; cursor: pointer; }
.info-alert {
    background: var(--blue-pale); border: 1px solid #bfdbfe;
    border-radius: 10px; padding: 11px 14px;
    display: flex; align-items: flex-start; gap: 9px; margin-bottom: 18px;
}
.info-alert i { color: var(--blue); margin-top: 1px; font-size: .85rem; flex-shrink: 0; }
.info-alert-text { font-size: .75rem; color: #1e3a5f; line-height: 1.55; }
.preview-card {
    background: var(--neutral-0); border-radius: 16px;
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; position: sticky; top: 24px;
}
.preview-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-500) 100%);
    color: #fff;
}
.preview-header-title {
    font-family: 'Fraunces', serif;
    font-size: 1rem; font-weight: 700; letter-spacing: -.02em;
}
.preview-header-sub { font-size: .7rem; opacity: .75; margin-top: 2px; }
.preview-body { padding: 18px; }
.preview-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid var(--neutral-100); font-size: .78rem;
}
.preview-row:last-of-type { border-bottom: none; }
.preview-row-label { color: var(--neutral-500); }
.preview-row-val { font-weight: 700; color: var(--neutral-900); }
.preview-total {
    margin-top: 14px; padding: 16px;
    background: linear-gradient(135deg, var(--green-50), var(--green-100));
    border: 1px solid var(--green-200); border-radius: 12px; text-align: center;
}
.preview-total-label {
    font-size: .65rem; font-weight: 600; color: var(--green-700);
    letter-spacing: .08em; text-transform: uppercase;
}
.preview-total-amount {
    font-family: 'Fraunces', serif;
    font-size: 2rem; font-weight: 700; color: var(--green-700);
    letter-spacing: -.04em; line-height: 1.1; margin-top: 4px;
}
.preview-tax {
    font-size: .68rem; color: var(--neutral-400); margin-top: 8px;
    display: flex; align-items: center; justify-content: center; gap: 5px;
}
.preview-tax-badge {
    background: var(--amber-pale); color: #92400e;
    font-size: .62rem; font-weight: 600; padding: 2px 7px; border-radius: 100px;
}
.wa-preview {
    margin-top: 16px; padding-top: 14px;
    border-top: 1px dashed var(--neutral-200);
}
.wa-preview-label {
    font-size: .63rem; font-weight: 600; letter-spacing: .07em;
    text-transform: uppercase; color: var(--neutral-400);
    margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
}
.wa-preview-label i { color: #25D366; }
.wa-bubble {
    background: #dcf8c6; border-radius: 12px 12px 2px 12px;
    padding: 12px 14px; font-size: .72rem; line-height: 1.65; color: #1a1a1a;
    box-shadow: 0 1px 3px rgba(0,0,0,.1); white-space: pre-line; word-break: break-word;
}
.wa-time { font-size: .62rem; color: var(--neutral-400); text-align: right; margin-top: 5px; }
.notif-preview {
    margin-top: 14px; padding: 12px 14px;
    background: var(--neutral-50); border: 1px solid var(--neutral-200); border-radius: 10px;
}
.notif-preview-label {
    font-size: .63rem; font-weight: 600; letter-spacing: .07em;
    text-transform: uppercase; color: var(--neutral-400);
    margin-bottom: 8px; display: flex; align-items: center; gap: 5px;
}
.notif-preview-label i { color: var(--blue); }
.notif-bubble { display: flex; gap: 10px; }
.notif-icon-box {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--green-100); display: flex; align-items: center;
    justify-content: center; font-size: 1rem; flex-shrink: 0;
}
.notif-text { flex: 1; }
.notif-title { font-size: .76rem; font-weight: 600; color: var(--neutral-900); }
.notif-body { font-size: .68rem; color: var(--neutral-500); margin-top: 3px; line-height: 1.5; }
.actions-bar { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
.btn-submit {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green-500); color: #fff;
    font-weight: 600; font-size: .84rem;
    padding: 11px 24px; border-radius: 10px; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(42,179,107,.32); transition: all .2s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-submit:hover { background: var(--green-600); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(42,179,107,.4); }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--neutral-0); color: var(--neutral-600);
    font-weight: 600; font-size: .82rem;
    padding: 11px 20px; border-radius: 10px;
    border: 1px solid var(--neutral-200); text-decoration: none; transition: all .15s;
}
.btn-cancel:hover { border-color: var(--neutral-400); color: var(--neutral-900); background: var(--neutral-50); }
.fade-up { opacity: 0; transform: translateY(12px); animation: fadeUp .4s ease forwards; }
@keyframes fadeUp { to { opacity: 1; transform: none; } }
.d1 { animation-delay: .04s; } .d2 { animation-delay: .08s; } .d3 { animation-delay: .14s; }
@media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }
@media (max-width: 560px) { .field-group.col-2 { grid-template-columns: 1fr; } }
@media (max-width: 768px) { .page-wrap { padding: 16px 14px 40px; } .card-body { padding: 16px; } }

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
@media (max-width: 768px) {
    .hero-banner { flex-direction: column; padding: 22px 20px; }
    .hero-right { width: 100%; }
    .btn-hero { width: 100%; justify-content: center; }
}
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

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="hero-banner fade-up d1">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('admin.invoices.index') }}" style="color:inherit;text-decoration:none">Invoice</a>
                &nbsp;/&nbsp; Buat Baru
            </div>
            <h1 class="hero-title">Buat Invoice <em>Manual</em></h1>
            <p class="hero-sub">Invoice tambahan di luar tagihan otomatis (denda, biaya tambahan, dan sejenisnya).</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.invoices.store') }}" id="invoiceForm">
        @csrf
        <div class="form-grid fade-up d2">

            <div>
                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-tag-fill"></i></div>
                        <div>
                            <div class="card-head-title">Judul &amp; Keterangan</div>
                            <div class="card-head-sub">Akan tampil di notifikasi penghuni</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-wrap">
                            <label class="field-label">Judul Invoice <span class="req">*</span></label>
                            <input type="text" name="invoice_title" id="invoiceTitle"
                                class="form-input @error('invoice_title') is-invalid @enderror"
                                value="{{ old('invoice_title', 'Invoice Lainnya') }}"
                                placeholder="Denda keterlambatan, biaya listrik, dll."
                                oninput="updatePreview()" required>
                            <div class="form-hint">Jelaskan singkat tujuan invoice. Akan tampil di notifikasi penghuni.</div>
                            @error('invoice_title')<div class="invalid-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="field-wrap" style="margin-top:14px">
                            <label class="field-label">Catatan / Keterangan</label>
                            <textarea name="notes" id="invoiceNotes" class="form-textarea"
                                placeholder="Opsional — Penjelasan rinci untuk penghuni..."
                                oninput="updatePreview()">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div class="card-head-title">Data Penghuni</div>
                            <div class="card-head-sub">Pilih penghuni penerima tagihan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-alert">
                            <i class="bi bi-whatsapp"></i>
                            <div class="info-alert-text">
                                Notifikasi in-app <strong>dan WhatsApp</strong> akan dikirim otomatis saat invoice disimpan.
                            </div>
                        </div>
                        <div class="field-group">
                            <div class="field-wrap">
                                <label class="field-label">Penghuni <span class="req">*</span></label>
                                <div class="select-wrapper">
                                    <select name="user_id" id="userSelect"
                                        class="form-select-custom @error('user_id') is-invalid @enderror"
                                        required onchange="fillTenantRoom(this)">
                                        <option value="">— Pilih penghuni —</option>
                                        @foreach($tenants as $t)
                                        @php $ab = $t->bookings->whereIn('status',['approved','active'])->first(); @endphp
                                        <option value="{{ $t->id }}"
                                            data-room="{{ $ab?->room_id }}"
                                            data-roomnum="{{ $ab?->room?->room_number }}"
                                            data-price="{{ $ab?->room?->price ?? 0 }}"
                                            data-phone="{{ $t->phone ? $t->phone : 'Tidak ada nomor' }}"
                                            data-name="{{ $t->name }}"
                                            {{ old('user_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }} — {{ $t->email }}
                                            @if($ab) · Kamar {{ $ab->room?->room_number }} @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-hint" id="tenantPhone">Pilih penghuni untuk melihat info kontak.</div>
                                @error('user_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">ID Kamar <span class="req">*</span></label>
                                <input type="number" name="room_id" id="roomId"
                                    class="form-input @error('room_id') is-invalid @enderror"
                                    value="{{ old('room_id') }}" placeholder="Otomatis terisi" required>
                                @error('room_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <div class="card-head-title">Rincian Tagihan</div>
                            <div class="card-head-sub">Nominal yang akan ditagihkan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-group col-2">
                            <div class="field-wrap">
                                <label class="field-label">Jumlah Pokok (Rp) <span class="req">*</span></label>
                                <input type="number" name="base_amount" id="baseAmount"
                                    class="form-input @error('base_amount') is-invalid @enderror"
                                    value="{{ old('base_amount') }}" min="0" required
                                    oninput="calcTotal()" placeholder="0">
                                @error('base_amount')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Biaya Tambahan (Rp)</label>
                                <input type="number" name="service_amount" id="serviceAmount"
                                    class="form-input"
                                    value="{{ old('service_amount', 0) }}" min="0"
                                    oninput="calcTotal()" placeholder="0">
                                <div class="form-hint">Opsional</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-calendar3"></i></div>
                        <div>
                            <div class="card-head-title">Periode &amp; Jatuh Tempo</div>
                            <div class="card-head-sub">Rentang waktu tagihan berlaku</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-group col-2">
                            <div class="field-wrap">
                                <label class="field-label">Periode Mulai <span class="req">*</span></label>
                                <input type="date" name="period_start"
                                    class="form-input @error('period_start') is-invalid @enderror"
                                    value="{{ old('period_start', date('Y-m-01')) }}"
                                    required onchange="autoSetPeriod(this)">
                                @error('period_start')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Periode Akhir <span class="req">*</span></label>
                                <input type="date" name="period_end" id="periodEnd"
                                    class="form-input @error('period_end') is-invalid @enderror"
                                    value="{{ old('period_end', date('Y-m-t')) }}" required>
                                @error('period_end')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Jatuh Tempo <span class="req">*</span></label>
                                <input type="date" name="due_date" id="dueDate"
                                    class="form-input @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}"
                                    required oninput="updatePreview()">
                                @error('due_date')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-send-fill"></i>Simpan &amp; Kirim Notifikasi
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn-cancel">
                        <i class="bi bi-x-lg"></i>Batal
                    </a>
                </div>
            </div>

            <div class="fade-up d3">
                <div class="preview-card">
                    <div class="preview-header">
                        <div class="preview-header-title">Pratinjau Tagihan</div>
                        <div class="preview-header-sub">Diperbarui otomatis saat Anda mengetik</div>
                    </div>
                    <div class="preview-body">
                        <div class="preview-row">
                            <div class="preview-row-label">Jumlah Pokok</div>
                            <div class="preview-row-val" id="previewBase">Rp 0</div>
                        </div>
                        <div class="preview-row">
                            <div class="preview-row-label">Biaya Tambahan</div>
                            <div class="preview-row-val" id="previewService">Rp 0</div>
                        </div>
                        <div class="preview-total">
                            <div class="preview-total-label">Total Tagihan</div>
                            <div class="preview-total-amount" id="previewTotal">Rp 0</div>
                        </div>

                        <div class="wa-preview">
                            <div class="wa-preview-label">
                                <i class="bi bi-whatsapp"></i>Pratinjau Pesan WhatsApp
                            </div>
                            <div class="wa-bubble" id="waPreview">Halo <strong>[Nama Penghuni]</strong>,

                            <strong>Invoice Lainnya</strong>

                            Admin Urban Kost menerbitkan tagihan tambahan.

                            No. Invoice : [Auto]
                            Kamar       : -
                            Total       : Rp 0
                            Jatuh Tempo : -

                            Silakan bayar melalui aplikasi.

                            Terima kasih.</div>
                        </div>

                        <div class="notif-preview">
                            <div class="notif-preview-label">
                                <i class="bi bi-bell-fill"></i>Pratinjau Notifikasi In-App
                            </div>
                            <div class="notif-bubble">
                                <div class="notif-icon-box"><i class="bi bi-receipt" style="color:var(--green-600)"></i></div>
                                <div class="notif-text">
                                    <div class="notif-title" id="notifTitle">Invoice Lainnya — [No. Invoice]</div>
                                    <div class="notif-body" id="notifBody">Invoice tambahan senilai Rp 0 telah diterbitkan admin.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
function fillTenantRoom(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('roomId').value = opt.dataset.room || '';
    document.getElementById('baseAmount').value = '';
    document.getElementById('tenantPhone').textContent = 'Kontak: ' + (opt.dataset.phone || '-');
    calcTotal(); updatePreview();
}
function autoSetPeriod(input) {
    const start = new Date(input.value);
    if (isNaN(start)) return;
    const end = new Date(start.getFullYear(), start.getMonth() + 1, 0);
    document.getElementById('periodEnd').value = end.toISOString().slice(0,10);
    updatePreview();
}
function calcTotal() {
    const base  = parseFloat(document.getElementById('baseAmount').value)   || 0;
    const svc   = parseFloat(document.getElementById('serviceAmount').value) || 0;
    const total = base + svc;
    const tax   = Math.round(total * 0.005);
    document.getElementById('previewBase').textContent    = fmt(base);
    document.getElementById('previewService').textContent = fmt(svc);
    document.getElementById('previewTotal').textContent   = fmt(total);
    document.getElementById('previewTax').textContent     = fmt(tax);
    updatePreview();
}
function updatePreview() {
    const title  = document.getElementById('invoiceTitle').value || 'Invoice Lainnya';
    const notes  = document.getElementById('invoiceNotes').value;
    const total  = document.getElementById('previewTotal').textContent;
    const due    = document.getElementById('dueDate').value;
    const dueFmt = due ? new Date(due).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-';
    const sel    = document.getElementById('userSelect');
    const opt    = sel.options[sel.selectedIndex];
    const room   = opt?.dataset?.roomnum || '-';
    const name   = opt?.dataset?.name    || '[Nama Penghuni]';
    let wa = 'Halo ' + name + ',\n\n';
    wa += title + '\n\n';
    wa += 'Admin Urban Kost menerbitkan tagihan tambahan.\n\n';
    wa += 'No. Invoice : [Auto-generate]\n';
    wa += 'Kamar       : ' + room + '\n';
    wa += 'Total       : ' + total + '\n';
    wa += 'Jatuh Tempo : ' + dueFmt + '\n';
    if (notes.trim()) wa += 'Keterangan  : ' + notes.trim() + '\n';
    wa += '\nSilakan lakukan pembayaran dan upload bukti transfer melalui aplikasi.\n\nTerima kasih.';
    document.getElementById('waPreview').textContent = wa;
    document.getElementById('notifTitle').textContent = title + ' — [No. Invoice]';
    document.getElementById('notifBody').textContent =
        'Invoice tambahan senilai ' + total + ' untuk kamar ' + room +
        ' telah diterbitkan admin. Jatuh tempo: ' + dueFmt +
        (notes.trim() ? '. Ket: ' + notes.trim().slice(0,60) + '...' : '');
}
calcTotal(); updatePreview();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>