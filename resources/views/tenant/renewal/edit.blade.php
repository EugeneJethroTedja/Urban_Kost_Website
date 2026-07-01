<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Perpanjangan #{{ $renewal->id }} — Urban Kost</title>
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
    --blue-light:  #dbeafe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-light: #fef3c7;
    --indigo:      #4f46e5;
    --indigo-pale: #eef2ff;
    --indigo-light:#e0e7ff;
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
.bg-layer {
    position: fixed; inset: 0; z-index: -1; overflow: hidden;
    background: var(--green-50);
}
.bg-layer::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%);
}
.bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}
.page-wrap {
    max-width: 760px;
    margin: 0 auto;
    padding: 32px 24px 64px;
}
@media (max-width: 768px) { .page-wrap { padding: 20px 16px 48px; } }
@media (max-width: 480px) { .page-wrap { padding: 16px 14px 48px; } }

.breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .73rem; color: var(--neutral-400);
    margin-bottom: 10px; flex-wrap: wrap;
}
.breadcrumb a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.breadcrumb a:hover { text-decoration: underline; }
.breadcrumb-sep { color: var(--neutral-200); font-size: .65rem; }
.page-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 4vw, 1.9rem);
    font-weight: 700;
    color: var(--neutral-900);
    letter-spacing: -.025em;
    margin-bottom: 4px;
    line-height: 1.15;
}
.page-sub {
    font-size: .85rem;
    color: var(--neutral-500);
    margin-bottom: 24px;
}

.alert-strip {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 13px 16px; border-radius: 10px;
    margin-bottom: 12px;
    font-size: .82rem; font-weight: 500;
    border: 1px solid; line-height: 1.55;
}
.alert-strip.success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip.warning { background: var(--amber-pale); border-color: rgba(245,158,11,.3); color: #78350f; }
.alert-strip.error   { background: var(--red-pale); border-color: rgba(220,38,38,.25); color: var(--red); }
.alert-strip.info    { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.alert-strip i { font-size: .95rem; flex-shrink: 0; margin-top: 2px; }

.edit-notice {
    display: flex; align-items: flex-start; gap: 12px;
    background: var(--amber-pale);
    border: 1px solid rgba(245,158,11,.3);
    border-radius: 12px; padding: 14px 16px;
    margin-bottom: 20px;
    font-size: .82rem; color: #78350f; line-height: 1.55;
}
.edit-notice i { font-size: 1rem; flex-shrink: 0; margin-top: 2px; }

.current-info {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 12px; padding: 16px 18px;
    background: var(--neutral-0);
    border-radius: 12px;
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
}
@media (max-width: 480px) { .current-info { grid-template-columns: 1fr; } }
.ci-label {
    font-size: .6rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .09em;
    color: var(--neutral-400); margin-bottom: 3px;
}
.ci-val { font-size: .85rem; font-weight: 600; color: var(--neutral-900); }
.ci-val.green { color: var(--green-600); }

.o-card {
    background: var(--neutral-0);
    border-radius: 16px;
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-bottom: 20px;
}
.o-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid var(--neutral-100);
    display: flex; align-items: center; gap: 9px;
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
}
.o-card-header-icon {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.o-card-header-title {
    font-size: .8rem; font-weight: 600;
    color: var(--neutral-700);
}
.o-card-header-sub {
    font-size: .7rem; color: var(--neutral-400); margin-top: 1px;
}
.card-body { padding: 22px; }

.form-section-label {
    font-size: .63rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--neutral-400); margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}
.form-section-label::before {
    content: attr(data-step);
    width: 18px; height: 18px; border-radius: 50%;
    background: var(--green-600); color: #fff;
    font-size: .6rem; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dur-select-wrap { position: relative; margin-bottom: 20px; }
.dur-select-wrap select {
    width: 100%; padding: 13px 46px 13px 16px;
    border: 1.5px solid var(--neutral-200);
    border-radius: 10px;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .9rem; font-weight: 600;
    color: var(--neutral-900); background: var(--neutral-0);
    appearance: none; -webkit-appearance: none;
    cursor: pointer; transition: border-color .2s, box-shadow .2s; outline: none;
}
.dur-select-wrap select:focus {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(42,179,107,.12);
}
.dur-select-wrap select:hover { border-color: var(--green-400); }
.dur-select-arrow {
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%);
    pointer-events: none; color: var(--green-600); font-size: 1rem;
}

.dur-preview {
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, var(--green-50), #f0fff4);
    border: 1.5px solid var(--green-200);
    border-radius: 12px; padding: 13px 16px;
    margin-bottom: 20px; transition: all .3s;
}
.dur-preview.highlight { border-color: var(--green-400); background: linear-gradient(135deg, var(--green-100), var(--green-50)); }
.dur-preview-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: var(--green-600); display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem; flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(29,148,88,.3);
}
.dur-preview-label { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .09em; color: var(--green-600); margin-bottom: 2px; }
.dur-preview-val { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; color: var(--green-800); }
.dur-preview-sub { font-size: .7rem; color: var(--green-600); margin-top: 1px; }

.form-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--neutral-200) 20%, var(--neutral-200) 80%, transparent);
    margin: 20px 0;
}

.svc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
@media (max-width: 480px) { .svc-grid { grid-template-columns: 1fr; } }
.svc-item {
    border: 1.5px solid var(--neutral-200);
    border-radius: 12px; padding: 13px;
    cursor: pointer; transition: all .18s;
    display: flex; align-items: center; gap: 11px;
    background: var(--neutral-0); user-select: none; position: relative;
}
.svc-item:hover { border-color: var(--green-300); background: var(--green-50); }
.svc-item.active { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.08); }
.svc-item.removing { border-color: var(--red); background: var(--red-pale); }
.svc-item-emoji { font-size: 1.5rem; flex-shrink: 0; line-height: 1; }
.svc-item-name { font-size: .8rem; font-weight: 600; color: var(--neutral-900); line-height: 1.3; }
.svc-item-price { font-size: .7rem; color: var(--green-600); font-weight: 600; margin-top: 2px; }
.svc-checkbox { position: absolute; top: 10px; right: 10px; width: 17px; height: 17px; accent-color: var(--green-600); }
.svc-state-tag {
    position: absolute; top: -1px; left: 10px;
    font-size: .54rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; padding: 2px 7px;
    border-radius: 0 0 6px 6px; line-height: 1.4;
}
.svc-state-current { background: var(--green-600); color: #fff; }
.svc-state-add     { background: var(--indigo); color: #fff; }
.svc-state-remove  { background: var(--red); color: #fff; }

.pay-method-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
@media (max-width: 400px) { .pay-method-grid { grid-template-columns: 1fr; } }
.pay-method-item {
    border: 1.5px solid var(--neutral-200);
    border-radius: 12px; padding: 13px;
    cursor: pointer; user-select: none;
    display: flex; flex-direction: column; align-items: flex-start; gap: 4px;
    background: var(--neutral-0); transition: all .18s; position: relative;
}
.pay-method-item:hover { border-color: var(--green-300); background: var(--green-50); }
.pay-method-item.active { border-color: var(--green-500); background: var(--green-50); box-shadow: 0 0 0 3px rgba(42,179,107,.08); }
.pay-method-item .svc-item-emoji { font-size: 1.4rem; }
.pay-method-item .svc-item-name { font-size: .82rem; font-weight: 600; color: var(--neutral-900); }
.pay-method-item .svc-item-price { font-size: .7rem; color: var(--neutral-500); font-weight: 500; }

.monthly-note {
    align-items: flex-start; gap: 9px;
    background: var(--blue-pale);
    border: 1px solid var(--blue-light);
    border-radius: 10px; padding: 12px 14px;
    margin-bottom: 16px;
    font-size: .78rem; color: #1e40af; line-height: 1.55;
}
.monthly-note i { color: var(--blue); flex-shrink: 0; margin-top: 1px; }

.cost-card {
    background: linear-gradient(135deg, var(--indigo-pale), #f5f3ff);
    border: 1.5px solid var(--indigo-light);
    border-radius: 14px; padding: 18px 20px; margin-bottom: 20px;
}
.cost-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 5px 0; font-size: .82rem;
}
.cost-row + .cost-row { border-top: 1px dashed var(--indigo-light); }
.cost-key { color: var(--neutral-500); display: flex; align-items: center; gap: 6px; }
.cost-key i { font-size: .75rem; color: var(--indigo); opacity: .6; }
.cost-val { font-weight: 600; color: var(--neutral-900); }
.cost-total {
    border-top: 2px solid var(--indigo-light) !important;
    padding-top: 12px !important; margin-top: 6px;
    font-size: 1rem;
}
.cost-total .cost-key { font-weight: 700; color: var(--neutral-900); }
.cost-total .cost-val { color: var(--indigo); font-size: 1.1rem; }
.cost-note {
    font-size: .7rem; color: var(--neutral-400);
    margin-top: 10px; padding-top: 10px;
    border-top: 1px dashed var(--indigo-light);
    display: flex; align-items: flex-start; gap: 5px; line-height: 1.5;
}
.cost-note i { flex-shrink: 0; color: var(--indigo); opacity: .6; margin-top: 1px; }

.error-msg {
    color: var(--red); font-size: .78rem;
    margin-top: -14px; margin-bottom: 14px;
    display: flex; align-items: center; gap: 4px;
}

.btn-row { display: flex; gap: 10px; flex-wrap: wrap; }
.btn-submit {
    flex: 1; min-width: 160px;
    display: flex; align-items: center; justify-content: center; gap: 9px;
    padding: 13px 20px; border-radius: 10px; border: none;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 50%, var(--green-500) 100%);
    color: #fff; font-family: 'Instrument Sans', sans-serif;
    font-size: .88rem; font-weight: 600;
    cursor: pointer; transition: all .22s;
    box-shadow: 0 4px 18px rgba(42,179,107,.28);
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.38); }
.btn-cancel {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    padding: 13px 20px; border-radius: 10px;
    background: var(--neutral-0); border: 1.5px solid var(--neutral-200);
    color: var(--neutral-600); font-family: 'Instrument Sans', sans-serif;
    font-size: .88rem; font-weight: 600; text-decoration: none; transition: all .18s;
}
.btn-cancel:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
.btn-submit-hint {
    text-align: center; margin-top: 10px;
    font-size: .72rem; color: var(--neutral-400);
    display: flex; align-items: center; justify-content: center; gap: 5px;
}

.hero-banner {
    position: relative; overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 30px 28px;
    margin-bottom: 24px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
}
.hero-banner::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-arc {
    position: absolute; right: -80px; top: -80px;
    width: 300px; height: 300px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.1);
    pointer-events: none; z-index: 1;
}
.hero-content { position: relative; z-index: 2; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82); margin-bottom: 12px;
}
.hero-eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green-300); flex-shrink: 0; }
.hero-title {
    font-family: 'Fraunces', serif;
    font-weight: 700;
    font-size: clamp(1.3rem, 3.5vw, 1.9rem);
    color: #fff; line-height: 1.15; letter-spacing: -.025em; margin-bottom: 6px;
}
.hero-title em { font-style: italic; font-weight: 400; color: rgba(255,255,255,.72); }
.hero-sub { font-size: .85rem; color: rgba(255,255,255,.62); line-height: 1.6; }
.hero-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: rgba(255,255,255,.55);
    margin-bottom: 12px; flex-wrap: wrap;
}
.hero-breadcrumb a { color: rgba(255,255,255,.75); text-decoration: none; font-weight: 600; }
.hero-breadcrumb a:hover { color: #fff; }
.hero-breadcrumb-sep { font-size: .6rem; opacity: .5; }
@media (max-width: 480px) {
    .hero-banner { padding: 20px 16px; }
    .hero-arc    { display: none; }
    .hero-title  { font-size: 1.2rem; }
}
</style>
</head>
<body>

<div class="bg-layer"><div class="bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page-wrap">

    <div class="hero-banner">
        <div class="hero-arc"></div>
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Edit Perpanjangan Sewa</div>
            <h1 class="hero-title">Edit<br><em>Pengajuan Perpanjangan</em></h1>
            <p class="hero-sub">Ubah durasi atau layanan sebelum disetujui admin.</p>
        </div>
    </div>

    @foreach(['success','info','error','warning'] as $t)
    @if(session($t))
    <div class="alert-strip {{ $t === 'error' ? 'error' : $t }}">
        <i class="bi bi-{{ $t === 'success' ? 'check-circle-fill' : ($t === 'error' ? 'exclamation-circle-fill' : ($t === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill')) }}"></i>
        <span>{!! session($t) !!}</span>
    </div>
    @endif
    @endforeach

    <div class="edit-notice">
        <i class="bi bi-pencil-square"></i>
        <span>
            Anda sedang mengedit pengajuan <strong>#{{ $renewal->id }}</strong>.
            Perubahan hanya dapat dilakukan selama status masih <strong>Menunggu Admin</strong>.
            Admin akan mendapat notifikasi setelah Anda menyimpan.
        </span>
    </div>

    <div class="current-info">
        <div>
            <div class="ci-label">Kamar</div>
            <div class="ci-val">{{ $renewal->room?->room_number }}</div>
        </div>
        <div>
            <div class="ci-label">Durasi Saat Ini</div>
            <div class="ci-val">{{ $renewal->duration_months }} Bulan</div>
        </div>
        <div>
            <div class="ci-label">Tanggal Akhir Sewa Aktif</div>
            <div class="ci-val green">{{ $endDate ? $endDate->format('d M Y') : '—' }}</div>
        </div>
        <div>
            <div class="ci-label">Total Saat Ini</div>
            <div class="ci-val">Rp {{ number_format($renewal->amount, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="o-card">
        <div class="o-card-header">
            <div class="o-card-header-icon" style="background: var(--amber-pale); color: #92400e;">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <div class="o-card-header-title">Ubah Pengajuan Perpanjangan</div>
                <div class="o-card-header-sub">Perubahan akan dikirim ulang ke admin untuk direview</div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('tenant.renewal.update', $renewal->id) }}" id="editForm">
                @csrf
                @method('PUT')

                <div class="form-section-label" data-step="1">Durasi Perpanjangan</div>

                <div class="dur-select-wrap">
                    <select name="renewal_months" id="renewalMonths" onchange="handleDurationChange(this.value)">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ old('renewal_months', $renewal->duration_months) == $m ? 'selected' : '' }}>
                            {{ $m }} Bulan
                        </option>
                        @endfor
                    </select>
                    <div class="dur-select-arrow"><i class="bi bi-chevron-down"></i></div>
                </div>

                @error('renewal_months')
                <div class="error-msg">
                    <i class="bi bi-exclamation-circle-fill" style="font-size:.75rem"></i> {{ $message }}
                </div>
                @enderror

                <div class="dur-preview" id="durPreview">
                    <div class="dur-preview-icon"><i class="bi bi-calendar-check-fill"></i></div>
                    <div>
                        <div class="dur-preview-label">Masa Sewa Baru Berakhir</div>
                        <div class="dur-preview-val" id="newEndDateVal">—</div>
                        <div class="dur-preview-sub" id="newEndDateSub"></div>
                    </div>
                </div>

                <div class="form-divider"></div>

                <div class="form-section-label" data-step="2">Layanan Tambahan</div>
                <p style="font-size:.76rem;color:var(--neutral-400);margin-bottom:14px;line-height:1.5">
                    Centang untuk <strong style="color:var(--green-600)">mengaktifkan</strong>, hapus centang untuk
                    <strong style="color:var(--red)">menghapus</strong> layanan.
                </p>

                <div class="svc-grid">
                    @foreach($SERVICES as $key => $svc)
                    @php
                        $isCurrent    = in_array($key, $activeServices);
                        $isOldChecked = old('services') !== null
                            ? in_array($key, (array) old('services'))
                            : in_array($key, $renewalServices);
                    @endphp
                    <label class="svc-item {{ $isOldChecked ? 'active' : '' }} {{ $isCurrent && !$isOldChecked ? 'removing' : '' }}"
                           id="svc-label-{{ $key }}"
                           data-price="{{ $svc['price'] }}"
                           data-current="{{ $isCurrent ? '1' : '0' }}"
                           style="{{ ($isCurrent || $isOldChecked) ? 'padding-top:22px' : '' }}">
                        <span class="svc-state-tag
                              {{ $isCurrent && $isOldChecked ? 'svc-state-current' : ($isCurrent && !$isOldChecked ? 'svc-state-remove' : ($isOldChecked ? 'svc-state-add' : '')) }}"
                              id="svc-tag-{{ $key }}"
                              style="{{ (!$isCurrent && !$isOldChecked) ? 'display:none' : '' }}">
                            {{ $isCurrent && $isOldChecked ? 'Aktif' : ($isCurrent && !$isOldChecked ? 'Akan Dihapus' : 'Ditambahkan') }}
                        </span>
                        <input type="checkbox"
                               name="services[]"
                               value="{{ $key }}"
                               class="svc-checkbox"
                               id="svc-{{ $key }}"
                               {{ $isOldChecked ? 'checked' : '' }}
                               onchange="updateService('{{ $key }}', '{{ $isCurrent ? '1' : '0' }}', this.checked)">
                        <div class="svc-item-emoji">{{ $svc['icon'] ?? '' }}</div>
                        <div>
                            <div class="svc-item-name">{{ $svc['label'] }}</div>
                            <div class="svc-item-price">+Rp {{ number_format($svc['price'], 0, ',', '.') }}/bln</div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="form-divider"></div>

                <div class="form-section-label" data-step="3">Metode Pembayaran</div>
                <p style="font-size:.76rem;color:var(--neutral-400);margin-bottom:14px;line-height:1.5">
                    Pilih cara pembayaran untuk perpanjangan ini.
                </p>

                <div class="pay-method-grid">
                    <label class="pay-method-item {{ old('payment_method', $renewal->payment_method ?? 'fullpay') === 'fullpay' ? 'active' : '' }}"
                           id="pay-label-fullpay">
                        <input type="radio" name="payment_method" value="fullpay"
                            {{ old('payment_method', $renewal->payment_method ?? 'fullpay') === 'fullpay' ? 'checked' : '' }}
                            style="position:absolute;top:10px;right:10px;accent-color:var(--green-600)"
                            onchange="selectPayMethod('fullpay')">
                        <div class="svc-item-emoji"></div>
                        <div>
                            <div class="svc-item-name">Bayar Lunas</div>
                            <div class="svc-item-price">1 invoice, bayar sekaligus</div>
                        </div>
                    </label>
                    <label class="pay-method-item {{ old('payment_method', $renewal->payment_method ?? 'fullpay') === 'monthly' ? 'active' : '' }}"
                           id="pay-label-monthly">
                        <input type="radio" name="payment_method" value="monthly"
                            {{ old('payment_method', $renewal->payment_method ?? 'fullpay') === 'monthly' ? 'checked' : '' }}
                            style="position:absolute;top:10px;right:10px;accent-color:var(--green-600)"
                            onchange="selectPayMethod('monthly')">
                        <div class="svc-item-emoji"></div>
                        <div>
                            <div class="svc-item-name">Bayar Per Bulan</div>
                            <div class="svc-item-price">Invoice otomatis tiap bulan</div>
                        </div>
                    </label>
                </div>

                <div id="monthlyNote" class="monthly-note"
                     style="display:{{ old('payment_method', $renewal->payment_method ?? 'fullpay') === 'monthly' ? 'flex' : 'none' }}">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>
                        Invoice <strong>bulan pertama</strong> dikirim saat admin menyetujui.
                        Invoice bulan berikutnya dibuat <strong>otomatis 3 hari sebelum periode mulai</strong>.
                        Perpanjangan selesai setelah semua bulan terbayar.
                    </span>
                </div>

                <div class="form-divider"></div>

                <div class="form-section-label" data-step="4">Ringkasan Biaya</div>

                <div class="cost-card">
                    <div class="cost-row">
                        <span class="cost-key"><i class="bi bi-door-open-fill"></i> Sewa kamar / bulan</span>
                        <span class="cost-val">Rp {{ number_format($roomPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="cost-row" id="svcCostRow" style="display:none">
                        <span class="cost-key"><i class="bi bi-stars"></i> Layanan tambahan / bulan</span>
                        <span class="cost-val" id="svcCostVal">Rp 0</span>
                    </div>
                    <div class="cost-row">
                        <span class="cost-key"><i class="bi bi-calculator-fill"></i> Total / bulan</span>
                        <span class="cost-val" id="monthlyTotalVal">Rp {{ number_format($roomPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="cost-row">
                        <span class="cost-key"><i class="bi bi-calendar-range-fill"></i> Durasi</span>
                        <span class="cost-val"><span id="durLabelVal">{{ $renewal->duration_months }}</span> bulan</span>
                    </div>
                    <div class="cost-row cost-total">
                        <span class="cost-key">Total Perpanjangan</span>
                        <span class="cost-val" id="grandTotalVal">Rp {{ number_format($renewal->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="cost-note">
                        <i class="bi bi-info-circle-fill"></i>
                        Invoice akan diperbarui oleh admin setelah menyetujui perubahan ini.
                    </div>
                </div>

                <div class="btn-row">
                    <a href="{{ route('tenant.renewal.show', $renewal->id) }}" class="btn-cancel">
                        <i class="bi bi-x-lg"></i> Batal
                    </a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="submitBtnLabel">Simpan Perubahan</span>
                    </button>
                </div>

                <div class="btn-submit-hint">
                    <i class="bi bi-shield-check"></i>
                    Admin akan mendapat notifikasi dan mereview ulang pengajuan Anda.
                </div>

            </form>
        </div>
    </div>

</div>

<script>
const ROOM_PRICE   = {{ (int) $roomPrice }};
let selectedMonths = parseInt(document.getElementById('renewalMonths')?.value || {{ $renewal->duration_months }});
let serviceTotal   = 0;

@if($endDate)
const CURRENT_END_DATE = new Date({{ $endDate->timestamp * 1000 }});
@else
const CURRENT_END_DATE = null;
@endif

const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

function formatDateID(d) {
    if (!d) return '—';
    return d.getDate() + ' ' + MONTHS_ID[d.getMonth()] + ' ' + d.getFullYear();
}
function getNewEndDate(months) {
    if (!CURRENT_END_DATE) return null;
    const d = new Date(CURRENT_END_DATE);
    d.setMonth(d.getMonth() + months);
    return d;
}
function updateEndPreview() {
    const newEnd = getNewEndDate(selectedMonths);
    const valEl  = document.getElementById('newEndDateVal');
    const subEl  = document.getElementById('newEndDateSub');
    const wrap   = document.getElementById('durPreview');
    if (valEl) valEl.textContent = formatDateID(newEnd);
    if (subEl && CURRENT_END_DATE) subEl.textContent = 'Dilanjutkan dari ' + formatDateID(CURRENT_END_DATE);
    if (wrap) { wrap.classList.add('highlight'); setTimeout(() => wrap.classList.remove('highlight'), 600); }
}
function handleDurationChange(val) {
    selectedMonths = parseInt(val);
    updateCosts();
    updateEndPreview();
}
function fmt(v) { return 'Rp ' + v.toLocaleString('id-ID'); }
function el(id) { return document.getElementById(id); }
function updateCosts() {
    const monthly = ROOM_PRICE + serviceTotal;
    const grand   = monthly * selectedMonths;
    if (el('svcCostVal'))      el('svcCostVal').textContent     = fmt(serviceTotal);
    if (el('monthlyTotalVal')) el('monthlyTotalVal').textContent = fmt(monthly);
    if (el('grandTotalVal'))   el('grandTotalVal').textContent   = fmt(grand);
    if (el('durLabelVal'))     el('durLabelVal').textContent     = selectedMonths;
    if (el('svcCostRow'))      el('svcCostRow').style.display    = serviceTotal > 0 ? 'flex' : 'none';
    if (el('submitBtnLabel'))  el('submitBtnLabel').textContent  =
        'Simpan Perubahan (' + selectedMonths + ' Bulan · ' + fmt(grand) + ')';
}
function updateService(key, isCurrent, checked) {
    const label = document.getElementById('svc-label-' + key);
    const tag   = document.getElementById('svc-tag-' + key);
    const price = parseInt(label?.dataset.price || 0);
    if (checked) {
        serviceTotal += price;
        label.classList.add('active'); label.classList.remove('removing');
        label.style.paddingTop = '22px';
        if (isCurrent === '1') { tag.textContent = 'Aktif'; tag.className = 'svc-state-tag svc-state-current'; }
        else { tag.textContent = 'Ditambahkan'; tag.className = 'svc-state-tag svc-state-add'; tag.style.display = ''; }
    } else {
        serviceTotal -= price;
        label.classList.remove('active');
        if (isCurrent === '1') {
            label.classList.add('removing'); tag.textContent = 'Akan Dihapus';
            tag.className = 'svc-state-tag svc-state-remove'; label.style.paddingTop = '22px';
        } else {
            label.classList.remove('removing'); tag.style.display = 'none'; label.style.paddingTop = '';
        }
    }
    updateCosts();
}
function selectPayMethod(method) {
    document.querySelectorAll('.pay-method-item').forEach(el => el.classList.remove('active'));
    document.getElementById('pay-label-' + method).classList.add('active');
    document.getElementById('monthlyNote').style.display = method === 'monthly' ? 'flex' : 'none';
}
document.addEventListener('DOMContentLoaded', () => {
    serviceTotal = 0;
    document.querySelectorAll('.svc-checkbox:checked').forEach(cb => {
        serviceTotal += parseInt(cb.closest('.svc-item')?.dataset.price || 0);
    });
    updateCosts();
    updateEndPreview();
});
</script>
</body>
</html>