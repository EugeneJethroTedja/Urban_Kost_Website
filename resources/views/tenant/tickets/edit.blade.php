<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tiket — Urban Kost</title>
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
        --red:         #dc2626;
        --red-pale:    #fef2f2;
        --amber:       #f59e0b;
        --amber-pale:  #fffbeb;
        --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
        --shadow-md: 0 4px 20px rgba(15,31,24,.09);
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

    .page-wrap { max-width: 760px; margin: 0 auto; padding: 32px 24px 64px; }

    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--neutral-500); text-decoration: none; font-size: .85rem;
        font-weight: 500; margin-bottom: 16px; transition: color .15s;
    }
    .back-link:hover { color: var(--green-600); }

    .ticket-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--green-50); border: 1.5px solid var(--green-200);
        color: var(--green-700); font-size: .75rem; font-weight: 700;
        padding: 4px 12px; border-radius: 50px; margin-bottom: 16px;
    }

    .page-header { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }
    .page-header-icon {
        width: 50px; height: 50px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--green-900), var(--green-600));
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.3rem;
        box-shadow: 0 6px 20px rgba(14,63,39,.3);
    }
    .page-header h1 {
        font-family: 'Fraunces', serif;
        font-size: clamp(1.2rem, 4vw, 1.7rem);
        font-weight: 700; color: var(--neutral-900);
        line-height: 1.15; letter-spacing: -.025em;
    }
    .page-header p { font-size: .85rem; color: var(--neutral-500); margin-top: 3px; }

    .notice-strip {
        background: var(--amber-pale); border: 1px solid rgba(245,158,11,.3);
        border-radius: 10px; padding: 12px 16px; margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
        font-size: .82rem; color: #78350f; font-weight: 500; line-height: 1.55;
    }
    .notice-strip i { color: var(--amber); flex-shrink: 0; margin-top: 1px; }

    .o-card {
        background: var(--neutral-0);
        border-radius: 16px;
        border: 1px solid var(--green-100);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .o-card-head {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, var(--green-500) 100%);
        position: relative; overflow: hidden;
    }

    .o-card-head h2 {
        font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700;
        color: #fff; position: relative; z-index: 1;
        display: flex; align-items: center; gap: 9px;
    }
    .o-card-head p { color: rgba(255,255,255,.65); font-size: .78rem; margin-top: 4px; position: relative; z-index: 1; }
    .o-card-body { padding: 24px; }

    .field-group { margin-bottom: 20px; }
    .field-label {
        display: block; font-weight: 600;
        font-size: .82rem; color: var(--neutral-700); margin-bottom: 7px;
    }
    .field-label .req { color: var(--red); margin-left: 3px; }
    .field-label .opt { color: var(--neutral-400); font-weight: 400; font-size: .75rem; margin-left: 4px; }

    .form-control {
        width: 100%; padding: 12px 15px;
        background: var(--green-50);
        border: 1.5px solid var(--neutral-200);
        border-radius: 10px;
        font-family: 'Instrument Sans', sans-serif;
        font-size: max(.85rem, 16px);
        color: var(--neutral-900);
        transition: border-color .2s, box-shadow .2s, background .2s;
        outline: none; appearance: none;
    }
    .form-control::placeholder { color: var(--neutral-400); }
    .form-control:focus {
        border-color: var(--green-400);
        background: var(--neutral-0);
        box-shadow: 0 0 0 3px rgba(42,179,107,.1);
    }
    .form-control.is-invalid { border-color: var(--red); }

    select.form-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238fa89d' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 38px; cursor: pointer;
    }
    textarea.form-control { resize: vertical; min-height: 130px; line-height: 1.6; }
    .invalid-feedback { font-size: .75rem; color: var(--red); margin-top: 5px; font-weight: 600; }

    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    .attachment-existing {
        background: var(--green-50); border: 1.5px solid var(--green-200);
        border-radius: 10px; padding: 12px 16px; margin-bottom: 10px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .attachment-info {
        display: flex; align-items: center; gap: 8px;
        font-size: .82rem; font-weight: 600; color: var(--green-700);
    }
    .attachment-view { font-size: .75rem; color: var(--green-600); text-decoration: underline; text-underline-offset: 2px; }
    .attachment-existing label {
        display: flex; align-items: center; gap: 6px;
        font-size: .78rem; color: var(--red); font-weight: 600; cursor: pointer;
    }
    .attachment-existing input[type=checkbox] { accent-color: var(--red); }

    .upload-area {
        background: var(--green-50); border: 2px dashed var(--green-200);
        border-radius: 10px; padding: 24px 16px; text-align: center;
        position: relative; transition: border-color .2s, background .2s; cursor: pointer; min-height: 100px;
    }
    .upload-area:hover { border-color: var(--green-500); background: var(--neutral-0); }
    .upload-area input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-icon { font-size: 1.8rem; color: var(--green-400); margin-bottom: 8px; display: block; }
    .upload-text { font-size: .84rem; color: var(--neutral-500); font-weight: 500; }
    .upload-sub { font-size: .73rem; color: var(--neutral-400); margin-top: 4px; }

    .action-bar { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 8px; }
    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--green-700), var(--green-500));
        color: #fff; font-family: 'Instrument Sans', sans-serif; font-weight: 600;
        font-size: .88rem; padding: 12px 28px; border-radius: 50px; border: none;
        cursor: pointer; transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 16px rgba(29,148,88,.35); min-height: 46px;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(29,148,88,.45); }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: 8px;
        background: transparent; color: var(--neutral-500);
        font-family: 'Instrument Sans', sans-serif; font-weight: 600;
        font-size: .88rem; padding: 12px 22px; border-radius: 50px;
        border: 1.5px solid var(--neutral-200); cursor: pointer;
        text-decoration: none; transition: border-color .2s, color .2s, background .2s; min-height: 46px;
    }
    .btn-cancel:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }

    @media (max-width: 580px) {
        .field-row { grid-template-columns: 1fr; }
        .o-card-body { padding: 18px 16px; }
        .page-wrap { padding: 20px 14px 48px; }
        .action-bar { flex-direction: column; }
        .btn-submit, .btn-cancel { justify-content: center; }
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
        <a href="{{ route('tenant.tickets.show', $ticket->id) }}" class="btn-hero"
        style="background:var(--neutral-0);color:var(--neutral-600);border:1px solid var(--neutral-200);box-shadow:var(--shadow-sm);">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('tenant.tickets.index') }}" style="color:inherit;text-decoration:none">Tiket</a>
                &nbsp;/&nbsp;
                <a href="{{ route('tenant.tickets.show', $ticket->id) }}" style="color:inherit;text-decoration:none">{{ $ticket->ticket_number }}</a>
                &nbsp;/&nbsp; Edit
            </div>
            <h1 class="hero-title">Edit <em>Tiket</em></h1>
            <p class="hero-sub">Perbarui informasi tiket selama masih berstatus Open.</p>
        </div>
    </div>

    <div class="notice-strip">
        <i class="bi bi-info-circle-fill"></i>
        <span>Tiket hanya dapat diedit atau dihapus selama statusnya masih <strong>Open</strong>. Setelah admin mulai menangani, perubahan tidak diperbolehkan.</span>
    </div>

    <div class="o-card">
        <div class="o-card-head">
            <h2><i class="bi bi-pencil-square"></i> Formulir Edit Tiket</h2>
            <p>Ubah kolom yang perlu diperbaiki, lalu simpan.</p>
        </div>
        <div class="o-card-body">
            <form method="POST"
                  action="{{ route('tenant.tickets.update', $ticket->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field-group">
                    <label class="field-label">Subjek <span class="req">*</span></label>
                    <input type="text" name="subject"
                        class="form-control @error('subject') is-invalid @enderror"
                        value="{{ old('subject', $ticket->subject) }}"
                        placeholder="Ringkasan singkat masalah Anda..." required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label">Kategori <span class="req">*</span></label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(['maintenance' => 'Perbaikan / Maintenance', 'payment' => 'Pembayaran', 'facility' => 'Fasilitas', 'complaint' => 'Keluhan Umum', 'other' => 'Lainnya'] as $v => $l)
                            <option value="{{ $v }}" {{ old('category', $ticket->category) === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Prioritas <span class="req">*</span></label>
                        <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                            @foreach(['low' => 'Rendah', 'normal' => 'Normal', 'high' => 'Tinggi', 'urgent' => 'Urgent'] as $v => $l)
                            <option value="{{ $v }}" {{ old('priority', $ticket->priority) === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Deskripsi Lengkap <span class="req">*</span></label>
                    <textarea name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Jelaskan masalah secara detail..." required>{{ old('description', $ticket->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Lampiran <span class="opt">(opsional)</span></label>

                    @if($ticket->attachment)
                    <div class="attachment-existing">
                        <div class="attachment-info">
                            <i class="bi bi-paperclip"></i>
                            <span>Lampiran saat ini</span>
                            <a href="{{ asset('storage/' . $ticket->attachment) }}"
                               target="_blank" class="attachment-view">Lihat file</a>
                        </div>
                        <label>
                            <input type="checkbox" name="remove_attachment" value="1">
                            Hapus lampiran ini
                        </label>
                    </div>
                    <p style="font-size:.75rem;color:var(--neutral-500);margin-bottom:8px">Upload file baru di bawah untuk mengganti lampiran yang ada.</p>
                    @endif

                    <div class="upload-area">
                        <input type="file" name="attachment"
                            class="@error('attachment') is-invalid @enderror"
                            accept="image/jpeg,image/png,application/pdf"
                            id="fileInput" onchange="updateFileLabel(this)">
                        <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                        <div class="upload-text" id="fileLabel">Klik atau seret file ke sini</div>
                        <div class="upload-sub">JPG / PNG / PDF — Maks. 3 MB</div>
                    </div>
                    @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="action-bar">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-floppy-fill"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('tenant.tickets.show', $ticket->id) }}" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    if (input.files && input.files.length > 0) {
        label.textContent = input.files[0].name;
        label.style.color = 'var(--green-700)';
        label.style.fontWeight = '600';
    } else {
        label.textContent = 'Klik atau seret file ke sini';
        label.style.color = '';
        label.style.fontWeight = '';
    }
}
</script>
</body>
</html>