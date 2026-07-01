<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Invoice — Urban Kost</title>
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
    --blue-100:    #dbeafe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-100:     #fee2e2;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-100:   #fef3c7;
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
.page-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; background: var(--green-50); }
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
.page-header { margin-bottom: 20px; }
.page-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 2.5vw, 1.85rem);
    font-weight: 700; color: var(--neutral-900);
    letter-spacing: -.03em; line-height: 1.1;
}
.page-title em { font-style: italic; font-weight: 400; color: var(--amber); }
.page-subtitle { font-size: .78rem; color: var(--neutral-500); font-weight: 400; margin-top: 4px; }
.warn-bar {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 18px; background: var(--amber-pale);
    border: 1px solid var(--amber-100); border-radius: 12px;
    margin-bottom: 20px; font-size: .8rem; color: #92400e; font-weight: 500;
}
.warn-bar i { font-size: .95rem; flex-shrink: 0; color: var(--amber); }
.form-grid {
    display: grid; grid-template-columns: 1fr 320px;
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
.form-input, .form-textarea {
    width: 100%; padding: 9px 13px;
    border: 1px solid var(--green-200); border-radius: 10px;
    font-size: .82rem; font-family: 'Instrument Sans', sans-serif;
    color: var(--neutral-900); background: #fff;
    transition: border-color .15s, box-shadow .15s; outline: none;
}
.form-input:focus, .form-textarea:focus {
    border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.12);
}
.form-input.is-invalid { border-color: var(--red); }
.form-input::placeholder, .form-textarea::placeholder { color: var(--neutral-400); }
.form-textarea { resize: vertical; min-height: 80px; line-height: 1.6; }
.form-hint { font-size: .69rem; color: var(--neutral-400); }
.invalid-msg { font-size: .69rem; color: var(--red); font-weight: 600; margin-top: 3px; }
.tenant-info-box {
    background: var(--green-50); border: 1px solid var(--green-200);
    border-radius: 12px; padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
}
.tenant-avatar-sm {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.1rem; font-weight: 700;
    font-family: 'Fraunces', serif; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(42,179,107,.3);
}
.tenant-info-name { font-size: .85rem; font-weight: 600; color: var(--neutral-900); }
.tenant-info-email { font-size: .72rem; color: var(--neutral-400); margin-top: 2px; }
.tenant-info-room { font-size: .72rem; color: var(--green-700); font-weight: 600; margin-top: 3px; }
.summary-card {
    background: var(--neutral-0); border-radius: 16px;
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; position: sticky; top: 24px;
}
.summary-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-500) 100%);
    color: #fff;
}
.summary-header-title {
    font-family: 'Fraunces', serif;
    font-size: 1rem; font-weight: 700; letter-spacing: -.02em;
}
.summary-header-sub { font-size: .7rem; opacity: .75; margin-top: 2px; }
.summary-body { padding: 18px; }
.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid var(--neutral-100); font-size: .78rem;
}
.summary-row:last-of-type { border-bottom: none; }
.summary-row-label { color: var(--neutral-500); }
.summary-row-val { font-weight: 700; color: var(--neutral-900); }
.total-box {
    margin-top: 14px; padding: 16px;
    background: linear-gradient(135deg, var(--green-50), var(--green-100));
    border: 1px solid var(--green-200); border-radius: 12px; text-align: center;
}
.total-label {
    font-size: .65rem; font-weight: 600; color: var(--green-700);
    letter-spacing: .08em; text-transform: uppercase;
}
.total-amount {
    font-family: 'Fraunces', serif;
    font-size: 2rem; font-weight: 700; color: var(--green-700);
    letter-spacing: -.04em; line-height: 1.1; margin-top: 4px;
}
.change-notice {
    margin-top: 16px; padding: 12px 14px;
    background: var(--blue-pale); border: 1px solid var(--blue-100);
    border-radius: 10px; font-size: .74rem; color: var(--blue); line-height: 1.55;
}
.actions-bar { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
.btn-update {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green-500); color: #fff;
    font-weight: 600; font-size: .84rem;
    padding: 11px 24px; border-radius: 10px; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(42,179,107,.32); transition: all .2s;
    font-family: 'Instrument Sans', sans-serif;
}
.btn-update:hover { background: var(--green-600); transform: translateY(-2px); }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--neutral-0); color: var(--neutral-600);
    font-weight: 600; font-size: .82rem;
    padding: 11px 20px; border-radius: 10px;
    border: 1px solid var(--neutral-200); text-decoration: none; transition: all .15s;
}
.btn-cancel:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
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
                &nbsp;/&nbsp;
                <a href="{{ route('admin.invoices.show', $invoice->id) }}" style="color:inherit;text-decoration:none">{{ $invoice->invoice_number }}</a>
                &nbsp;/&nbsp; Edit
            </div>
            <h1 class="hero-title">Edit <em>Invoice</em></h1>
            <p class="hero-sub">{{ $invoice->invoice_number }} — Perbarui detail tagihan yang belum dibayar</p>
        </div>
    </div>

    <div class="warn-bar fade-up d1">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>
            Perubahan nominal atau jatuh tempo akan mengirim notifikasi in-app ke penghuni.
            Invoice yang sudah dalam proses pembayaran <strong>tidak dapat</strong> diedit.
        </span>
    </div>

    <form method="POST" action="{{ route('admin.invoices.update', $invoice->id) }}" id="editForm">
        @csrf @method('PUT')
        <div class="form-grid fade-up d2">

            <div>
                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div class="card-head-title">Penghuni</div>
                            <div class="card-head-sub">Tidak dapat diubah setelah invoice dibuat</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tenant-info-box">
                            <div class="tenant-avatar-sm">
                                {{ strtoupper(substr($invoice->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="tenant-info-name">{{ $invoice->user?->name }}</div>
                                <div class="tenant-info-email">{{ $invoice->user?->email }}</div>
                                <div class="tenant-info-room">
                                    <i class="bi bi-door-open" style="margin-right:4px"></i>Kamar {{ $invoice->room?->room_number }}
                                    @if($invoice->user?->phone)
                                    &nbsp;&middot;&nbsp;<i class="bi bi-telephone" style="margin-right:4px"></i>{{ $invoice->user->phone }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $invoice->user_id }}">
                        <input type="hidden" name="room_id" value="{{ $invoice->room_id }}">
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-tag-fill"></i></div>
                        <div>
                            <div class="card-head-title">Keterangan</div>
                            <div class="card-head-sub">Jelaskan alasan perubahan invoice</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-wrap">
                            <label class="field-label">Judul Invoice</label>
                            <input type="text" name="invoice_title" class="form-input"
                                value="{{ old('invoice_title', $invoice->notes ? explode('|', $invoice->notes)[0] ?? '' : '') }}"
                                placeholder="Keterangan singkat perubahan...">
                        </div>
                        <div class="field-wrap" style="margin-top:14px">
                            <label class="field-label">Catatan</label>
                            <textarea name="notes" class="form-textarea"
                                placeholder="Alasan perubahan atau keterangan tambahan...">{{ old('notes', $invoice->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <div class="card-head-title">Nominal Tagihan</div>
                            <div class="card-head-sub">Nominal saat ini: {{ $invoice->formatted_total }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-group col-2">
                            <div class="field-wrap">
                                <label class="field-label">Jumlah Pokok (Rp) <span class="req">*</span></label>
                                <input type="number" name="base_amount" id="baseAmount"
                                    class="form-input @error('base_amount') is-invalid @enderror"
                                    value="{{ old('base_amount', $invoice->base_amount) }}" min="0" required
                                    oninput="calcTotal()">
                                @error('base_amount')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Biaya Tambahan (Rp)</label>
                                <input type="number" name="service_amount" id="serviceAmount"
                                    class="form-input"
                                    value="{{ old('service_amount', $invoice->service_amount) }}" min="0"
                                    oninput="calcTotal()">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="card-head">
                        <div class="card-head-icon"><i class="bi bi-calendar3"></i></div>
                        <div>
                            <div class="card-head-title">Periode &amp; Jatuh Tempo</div>
                            <div class="card-head-sub">
                                Saat ini: {{ $invoice->period_start?->format('d M Y') }} – {{ $invoice->period_end?->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-group col-2">
                            <div class="field-wrap">
                                <label class="field-label">Periode Mulai <span class="req">*</span></label>
                                <input type="date" name="period_start"
                                    class="form-input @error('period_start') is-invalid @enderror"
                                    value="{{ old('period_start', $invoice->period_start?->format('Y-m-d')) }}" required>
                                @error('period_start')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Periode Akhir <span class="req">*</span></label>
                                <input type="date" name="period_end"
                                    class="form-input @error('period_end') is-invalid @enderror"
                                    value="{{ old('period_end', $invoice->period_end?->format('Y-m-d')) }}" required>
                                @error('period_end')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="field-wrap">
                                <label class="field-label">Jatuh Tempo <span class="req">*</span></label>
                                <input type="date" name="due_date"
                                    class="form-input @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}" required>
                                @error('due_date')<div class="invalid-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <button type="submit" class="btn-update">
                        <i class="bi bi-pencil-square"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn-cancel">
                        <i class="bi bi-x-lg"></i>Batal
                    </a>
                </div>
            </div>

            <div class="fade-up d3">
                <div class="summary-card">
                    <div class="summary-header">
                        <div class="summary-header-title">Perbandingan Nilai</div>
                        <div class="summary-header-sub">Sebelum vs sesudah perubahan</div>
                    </div>
                    <div class="summary-body">
                        <div style="font-size:.63rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--neutral-400);margin-bottom:10px">Nilai Lama</div>
                        <div class="summary-row">
                            <div class="summary-row-label">Pokok</div>
                            <div class="summary-row-val" style="color:var(--neutral-400);text-decoration:line-through">
                                Rp {{ number_format($invoice->base_amount,0,',','.') }}
                            </div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-row-label">Tambahan</div>
                            <div class="summary-row-val" style="color:var(--neutral-400);text-decoration:line-through">
                                Rp {{ number_format($invoice->service_amount,0,',','.') }}
                            </div>
                        </div>
                        <div class="summary-row" style="margin-bottom:14px">
                            <div class="summary-row-label"><strong>Total Lama</strong></div>
                            <div class="summary-row-val" style="color:var(--neutral-400);text-decoration:line-through">
                                <strong>{{ $invoice->formatted_total }}</strong>
                            </div>
                        </div>
                        <div style="font-size:.63rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--green-600);margin-bottom:10px;margin-top:4px">Nilai Baru</div>
                        <div class="summary-row">
                            <div class="summary-row-label">Pokok</div>
                            <div class="summary-row-val" id="newBase">Rp {{ number_format($invoice->base_amount,0,',','.') }}</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-row-label">Tambahan</div>
                            <div class="summary-row-val" id="newService">Rp {{ number_format($invoice->service_amount,0,',','.') }}</div>
                        </div>
                        <div class="total-box">
                            <div class="total-label">Total Baru</div>
                            <div class="total-amount" id="newTotal">{{ $invoice->formatted_total }}</div>
                        </div>
                        <div class="change-notice" style="margin-top:16px">
                            <strong>Notifikasi otomatis:</strong> Jika nominal atau jatuh tempo berubah, penghuni
                            <strong>{{ $invoice->user?->name }}</strong> akan menerima notifikasi in-app secara otomatis.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
function calcTotal() {
    const base  = parseFloat(document.getElementById('baseAmount').value)   || 0;
    const svc   = parseFloat(document.getElementById('serviceAmount').value) || 0;
    const total = base + svc;
    document.getElementById('newBase').textContent    = fmt(base);
    document.getElementById('newService').textContent = fmt(svc);
    document.getElementById('newTotal').textContent   = fmt(total);
}
calcTotal();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>