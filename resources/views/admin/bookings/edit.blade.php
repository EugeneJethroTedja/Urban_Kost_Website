<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Booking {{ $booking->booking_code }} — Urban Kost</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,300;1,9..144,400;1,9..144,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
    --neutral-300: #c2d4cc;
    --neutral-400: #8fa89d;
    --neutral-500: #5f7a6e;
    --neutral-600: #3e5549;
    --neutral-700: #2d4038;
    --neutral-900: #0f1f18;
    --red:         #dc2626;
    --red-lt:      #fef2f2;
    --red-line:    #fecaca;
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

.page-wrap { max-width: 980px; margin: 0 auto; padding: 32px 24px 64px; }

.bc { display: flex; align-items: center; gap: 6px; font-size: .73rem; color: var(--neutral-400); margin-bottom: 20px; flex-wrap: wrap; }
.bc a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.bc a:hover { text-decoration: underline; }
.bc i.sep { font-size: .55rem; color: var(--neutral-300); }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; animation: fadeUp .3s ease both; }
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
.page-header-sub { font-size: .8rem; color: var(--neutral-500); margin-top: 5px; }
.header-btns { display: flex; gap: 8px; flex-wrap: wrap; }

.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: 10px;
    font-size: .8rem; font-weight: 600;
    font-family: 'Instrument Sans', sans-serif;
    cursor: pointer; border: none; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.btn-back    { background: var(--neutral-0); border: 1px solid var(--green-200); color: var(--neutral-600); }
.btn-back:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-700); }
.btn-primary { background: linear-gradient(145deg, var(--green-500), var(--green-400)); color: #fff; box-shadow: 0 2px 10px rgba(42,179,107,.28); }
.btn-primary:hover { background: linear-gradient(145deg, var(--green-600), var(--green-500)); color: #fff; transform: translateY(-1px); }
.btn-ghost { background: var(--neutral-0); border: 1px solid var(--neutral-200); color: var(--neutral-600); }
.btn-ghost:hover { border-color: var(--neutral-400); color: var(--neutral-900); }

.flash {
    padding: 11px 14px; border-radius: 10px; margin-bottom: 16px;
    font-size: .8rem; font-weight: 500; display: flex; align-items: flex-start; gap: 9px; border: 1px solid;
}

.edit-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; animation: fadeUp .3s .07s ease both; }
@media (max-width: 900px) { .edit-grid { grid-template-columns: 1fr; } }

.card {
    background: var(--neutral-0); border-radius: var(--card-r);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 16px;
}
.card-head {
    padding: 13px 18px; border-bottom: 1px solid var(--green-100);
    font-family: 'Fraunces', serif;
    font-size: .88rem; font-weight: 700; color: var(--neutral-800);
    display: flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.card-head i { color: var(--green-500); }
.card-body { padding: 20px; }

/* ── Booking ID strip — hijau untuk semua tipe ── */
.booking-id-strip {
    padding: 16px 18px;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    background: linear-gradient(135deg, var(--green-900), var(--green-600));
}
.booking-code {
    font-family: 'Fraunces', serif; font-weight: 700; font-size: 1.3rem;
    color: #fff; letter-spacing: -.01em;
}
.booking-type-tag {
    font-size: .62rem; font-weight: 600; letter-spacing: .1em;
    text-transform: uppercase; color: rgba(255,255,255,.5);
    display: block; margin-bottom: 3px;
}

.section-lbl {
    font-size: .65rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 8px;
    display: flex; align-items: center; gap: 5px;
}
.section-lbl::after { content: ''; flex: 1; height: 1px; background: var(--neutral-100); }

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
.fld-ctrl.err { border-color: var(--red); }
.fld-ctrl[readonly] { background: var(--neutral-50); color: var(--neutral-500); cursor: not-allowed; border-style: dashed; }
.fld-err  { font-size: .68rem; color: var(--red); margin-top: 3px; }
.fld-hint { font-size: .68rem; color: var(--neutral-400); margin-top: 3px; }
textarea.fld-ctrl { resize: vertical; }

.uk-hr { border: none; height: 1px; background: var(--neutral-100); margin: 18px 0; }

.hero-banner {
    position: relative; overflow: hidden; border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 36px 32px; margin-bottom: 24px;
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
.hero-right { position: relative; z-index: 1; flex-shrink: 0; display: flex; gap: 8px; flex-wrap: wrap; }
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
.btn-hero-white {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; font-size: .8rem; font-weight: 600;
    color: var(--green-900); background: rgba(255,255,255,.92);
    border: none; border-radius: 10px; cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,.12); transition: all .2s;
    text-decoration: none; white-space: nowrap;
}
.btn-hero-white:hover { background: #fff; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(0,0,0,.16); color: var(--green-900); }
.btn-hero-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; font-size: .8rem; font-weight: 600;
    color: #fff; background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25); border-radius: 10px;
    cursor: pointer; transition: all .2s; text-decoration: none; white-space: nowrap;
}
.btn-hero-ghost:hover { background: rgba(255,255,255,.25); color: #fff; }
@media (max-width: 640px) {
    .hero-banner { flex-direction: column; padding: 22px 20px; }
    .hero-right  { width: 100%; }
    .btn-hero-white, .btn-hero-ghost { width: 100%; justify-content: center; }
}

.status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .7rem; font-weight: 700; padding: 5px 12px; border-radius: var(--r-pill); white-space: nowrap;
}
.status-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
.sp-pending      { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
.sp-pending .dot { background: #ca8a04; }
.sp-approved     { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-300); }
.sp-approved .dot { background: var(--green-500); }
.sp-active       { background: var(--green-600); color: #fff; border: 1px solid var(--green-700); }
.sp-active .dot  { background: #bbf7d0; }
.sp-waiting_list { background: var(--amber-lt); color: #92400e; border: 1px solid var(--amber-line); }
.sp-waiting_list .dot { background: var(--amber); }
.sp-rejected     { background: var(--red-lt); color: #991b1b; border: 1px solid var(--red-line); }
.sp-rejected .dot { background: var(--red); }
.sp-cancelled    { background: var(--neutral-100); color: var(--neutral-500); border: 1px solid var(--neutral-200); }
.sp-cancelled .dot { background: var(--neutral-400); }
.sp-completed    { background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
.sp-completed .dot { background: #a855f7; }

.info-row { display: flex; justify-content: space-between; align-items: center; font-size: .75rem; padding: 8px 0; border-bottom: 1px solid var(--neutral-100); }
.info-row:last-child { border-bottom: none; }
.info-lbl { color: var(--neutral-400); }
.info-val { font-weight: 700; color: var(--neutral-700); }

/* ── Alert boxes — tidak ada biru ── */
.alert-box { display: flex; gap: 10px; padding: 12px 14px; border-radius: 10px; font-size: .8rem; margin-bottom: 14px; border: 1px solid; }
.alert-box i { flex-shrink: 0; margin-top: 1px; }
.alert-info      { background: var(--green-50);  border-color: var(--green-200);  color: var(--green-800); }
.alert-warning   { background: var(--amber-lt);  border-color: var(--amber-line); color: #92400e; }
.alert-secondary { background: var(--neutral-100); border-color: var(--neutral-200); color: var(--neutral-600); }

.btn-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 20px; }

.rules-list { font-size: .75rem; color: var(--neutral-500); }
.rules-list p { font-weight: 700; margin-bottom: 5px; }
.rules-list ul { padding-left: 1rem; line-height: 2; margin-bottom: 12px; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
@media (max-width: 640px) { .page-wrap { padding: 16px 12px 48px; } }
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

@php
    $spMap = [
        'pending'      => 'sp-pending',
        'approved'     => 'sp-approved',
        'active'       => 'sp-active',
        'waiting_list' => 'sp-waiting_list',
        'rejected'     => 'sp-rejected',
        'cancelled'    => 'sp-cancelled',
        'completed'    => 'sp-completed',
    ];
    $spLabel = [
        'pending'      => 'Menunggu',
        'approved'     => 'Disetujui',
        'active'       => 'Menempati',
        'waiting_list' => 'Antrian',
        'rejected'     => 'Ditolak',
        'cancelled'    => 'Dibatalkan',
        'completed'    => 'Selesai',
    ];
@endphp

<div class="page-wrap">
    <div style="margin-bottom: 16px;">
    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if($errors->any())
<div class="flash" style="background:var(--red-lt);border-color:var(--red-line);color:#991b1b">
    <i class="bi bi-x-circle-fill"></i>
    <ul style="margin:0;padding-left:1.1rem">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

@foreach(['success','error','warning','info'] as $type)
@if(session($type))
    @php
        $flashStyles = [
            'success' => ['bg'=>'var(--green-50)','border'=>'var(--green-200)','color'=>'var(--green-800)','icon'=>'check-circle-fill'],
            'error'   => ['bg'=>'var(--red-lt)','border'=>'var(--red-line)','color'=>'#991b1b','icon'=>'x-circle-fill'],
            'warning' => ['bg'=>'var(--amber-lt)','border'=>'var(--amber-line)','color'=>'#92400e','icon'=>'exclamation-triangle-fill'],
            'info'    => ['bg'=>'var(--green-50)','border'=>'var(--green-200)','color'=>'var(--green-800)','icon'=>'info-circle-fill'],
        ];
        $fs = $flashStyles[$type];
    @endphp
    <div class="flash" style="background:{{ $fs['bg'] }};border-color:{{ $fs['border'] }};color:{{ $fs['color'] }}">
        <i class="bi bi-{{ $fs['icon'] }}"></i>
        <span>{!! session($type) !!}</span>
    </div>
    @endif
    @endforeach

    @if($isReadOnly)
    <div class="alert-box alert-secondary" style="margin-bottom:16px">
        <i class="bi bi-lock-fill"></i>
        <div>Booking dengan status <strong>{{ $booking->status }}</strong> hanya bisa dilihat, tidak bisa diedit.</div>
    </div>
    @endif

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('admin.bookings.index') }}" style="color:inherit;text-decoration:none">Booking</a>
                &nbsp;/&nbsp;
                <a href="{{ route('admin.bookings.show', $booking->id) }}" style="color:inherit;text-decoration:none">{{ $booking->booking_code }}</a>
                &nbsp;/&nbsp; Edit
            </div>
            <h1 class="hero-title">Edit <em>Booking</em></h1>
            <p class="hero-sub">
                {{ $booking->booking_code }}
                &nbsp;·&nbsp; {{ $booking->booking_type === 'advance' ? 'Advance Booking' : 'Regular Booking' }}
                &nbsp;·&nbsp; Diajukan {{ $booking->created_at->format('d M Y') }} 
            </p>
        </div>
        <div class="hero-right">
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-hero-ghost">
                <i class="bi bi-eye"></i> Lihat Detail
            </a>
        </div>
    </div>
    <div class="edit-grid">
        <div>
            <div class="card">
                <div class="booking-id-strip">
                    <div>
                        <span class="booking-type-tag">{{ $booking->booking_type === 'advance' ? 'Advance Booking' : 'Regular Booking' }}</span>
                        <div class="booking-code">{{ $booking->booking_code }}</div>
                    </div>
                    <span class="status-pill {{ $spMap[$booking->status] ?? 'sp-cancelled' }}">
                        <span class="dot"></span>{{ $spLabel[$booking->status] ?? ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}">
                        @csrf @method('PUT')

                        <div class="section-lbl"><i class="bi bi-person-fill"></i>Data Pemohon</div>
                        <div class="fld-row">
                            <div class="fld-group">
                                <label class="fld-label">Nama Lengkap</label>
                                <input type="text" name="full_name"
                                       class="fld-ctrl {{ $errors->has('full_name') ? 'err' : '' }}"
                                       value="{{ old('full_name', $booking->full_name) }}"
                                       {{ $isReadOnly ? 'readonly' : '' }}>
                                @error('full_name')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                            <div class="fld-group">
                                <label class="fld-label">No. HP / WhatsApp</label>
                                <input type="text" name="phone"
                                       class="fld-ctrl {{ $errors->has('phone') ? 'err' : '' }}"
                                       value="{{ old('phone', $booking->phone) }}"
                                       {{ $isReadOnly ? 'readonly' : '' }}>
                                @error('phone')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="fld-group">
                            <label class="fld-label">No. KTP / KTM <span class="opt">(opsional)</span></label>
                            <input type="text" name="identity_number" class="fld-ctrl"
                                   value="{{ old('identity_number', $booking->identity_number) }}"
                                   {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>

                        <div class="uk-hr"></div>

                        <div class="section-lbl"><i class="bi bi-calendar-event-fill"></i>Jadwal &amp; Durasi</div>
                        @if(in_array($booking->status, ['pending', 'under_review']))
                        <div class="fld-row">
                            <div class="fld-group">
                                <label class="fld-label">Tanggal Masuk</label>
                                <input type="date" name="check_in_date"
                                       class="fld-ctrl {{ $errors->has('check_in_date') ? 'err' : '' }}"
                                       value="{{ old('check_in_date', $booking->check_in_date?->format('Y-m-d')) }}">
                                @error('check_in_date')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                            <div class="fld-group">
                                <label class="fld-label">Durasi Sewa</label>
                                <select name="duration_months" class="fld-ctrl {{ $errors->has('duration_months') ? 'err' : '' }}">
                                    @foreach([1,2,3,4,5,6,9,12] as $m)
                                    <option value="{{ $m }}" {{ old('duration_months', $booking->duration_months) == $m ? 'selected' : '' }}>
                                        {{ $m }} bulan{{ $m == 12 ? ' (1 tahun)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('duration_months')<span class="fld-err">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="fld-hint" style="margin-top:-10px;margin-bottom:14px">
                            <i class="bi bi-info-circle"></i> Tanggal dan durasi bisa diubah selama status masih <strong>Pending</strong>.
                        </div>
                        @else
                        <div class="fld-row">
                            <div class="fld-group">
                                <label class="fld-label">Tanggal Masuk</label>
                                <input type="text" class="fld-ctrl" value="{{ $booking->check_in_date?->format('d M Y') ?? '—' }}" readonly>
                            </div>
                            <div class="fld-group">
                                <label class="fld-label">Durasi Sewa</label>
                                <input type="text" class="fld-ctrl" value="{{ $booking->duration_months }} bulan" readonly>
                            </div>
                        </div>
                        <div class="fld-hint" style="margin-top:-10px;margin-bottom:14px">
                            <i class="bi bi-lock-fill"></i> Jadwal &amp; durasi terkunci — booking sudah diproses.
                        </div>
                        @endif

                        <div class="uk-hr"></div>

                        <div class="section-lbl"><i class="bi bi-chat-left-text-fill"></i>Pesan &amp; Catatan</div>
                        <div class="fld-group">
                            <label class="fld-label">Pesan dari Penyewa <span class="opt">(opsional)</span></label>
                            <textarea name="message" class="fld-ctrl" rows="2"
                                      {{ $isReadOnly ? 'readonly' : '' }}>{{ old('message', $booking->message) }}</textarea>
                        </div>
                        <div class="fld-group">
                            <label class="fld-label">Catatan Admin <span class="opt">(internal)</span></label>
                            <textarea name="admin_notes" class="fld-ctrl" rows="3"
                                      placeholder="Catatan internal — tidak dilihat penyewa..."
                                      {{ $isReadOnly ? 'readonly' : '' }}>{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                            <span class="fld-hint">Hanya bisa dilihat oleh admin.</span>
                        </div>

                        @if(!$isReadOnly)
                        <div class="btn-row">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle-fill"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-ghost">
                                <i class="bi bi-x"></i>Batal
                            </a>
                        </div>
                        @else
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-ghost">
                            <i class="bi bi-arrow-left"></i>Kembali ke Detail
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-head"><i class="bi bi-info-circle-fill"></i>Info Booking</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-lbl">Kode</span>
                        <span class="info-val" style="font-family:'Fraunces',serif">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Status</span>
                        <span class="status-pill {{ $spMap[$booking->status] ?? 'sp-cancelled' }}" style="font-size:.68rem">
                            <span class="dot"></span>{{ $spLabel[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Tipe</span>
                        <span class="info-val">{{ $booking->booking_type === 'advance' ? 'Advance' : 'Regular' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Penyewa</span>
                        <span class="info-val">{{ $booking->user?->name ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Kamar</span>
                        <span class="info-val">No. {{ $booking->room?->room_number ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Metode Bayar</span>
                        <span class="info-val">{{ $booking->payment_method === 'monthly' ? 'Per Bulan' : 'Lunas' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Dibuat</span>
                        <span class="info-val">{{ $booking->created_at->format('d M Y') }}</span>
                    </div>
                    @if($booking->invoices->count() > 0)
                    <div style="margin-top:10px;padding:10px 12px;background:var(--green-50);border:1px solid var(--green-100);border-radius:8px;font-size:.72rem;color:var(--green-700)">
                        <i class="bi bi-receipt" style="margin-right:4px"></i>
                        Terdapat <strong>{{ $booking->invoices->count() }} invoice</strong> terkait.
                        Perubahan tanggal/durasi tidak mempengaruhi invoice yang sudah dibuat.
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-head"><i class="bi bi-shield-fill"></i>Aturan Edit</div>
                <div class="card-body">
                    <div class="rules-list">
                        <p style="color:var(--green-700)">Bisa diedit:</p>
                        <ul style="color:var(--neutral-500)">
                            <li>Nama, No. HP, No. Identitas</li>
                            <li>Pesan dari penyewa</li>
                            <li>Catatan admin (internal)</li>
                            @if(in_array($booking->status, ['pending', 'under_review']))
                            <li style="color:var(--green-600);font-weight:700">Tanggal masuk &amp; durasi (status pending)</li>
                            @endif
                        </ul>
                        <p style="color:var(--red)">Tidak bisa diubah di sini:</p>
                        <ul style="color:var(--neutral-500)">
                            <li>Status booking</li>
                            <li>Kamar yang dipesan</li>
                            <li>Approve / Tolak / Batalkan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>