<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penghuni {{ $tenant->name }} — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    <style>
    :root {
        --green-50:#f0faf4;--green-100:#dcf5e7;--green-200:#b8eacf;
        --green-300:#86d9ae;--green-400:#4ec98a;--green-500:#2ab36b;
        --green-600:#1d9458;--green-700:#167745;--green-800:#135a37;--green-900:#0e3f27;
        --neutral-0:#ffffff;--neutral-50:#f8faf9;--neutral-100:#f0f4f2;
        --neutral-200:#e1e9e5;--neutral-400:#8fa89d;--neutral-500:#5f7a6e;
        --neutral-600:#3e5549;--neutral-700:#2d4038;--neutral-900:#0f1f18;
        --accent:#f4a535;--accent-pale:#fffbf0;
        --blue:#2563eb;--blue-pale:#eff6ff;
        --red:#dc2626;--red-pale:#fef2f2;
        --amber:#f59e0b;--amber-pale:#fffbeb;
        --shadow-sm:0 1px 4px rgba(15,31,24,.06);
        --shadow-md:0 4px 20px rgba(15,31,24,.09);
        --shadow-lg:0 12px 48px rgba(15,31,24,.13);
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'Instrument Sans',sans-serif;background:var(--green-50);color:var(--neutral-900);line-height:1.6;-webkit-font-smoothing:antialiased;min-height:100vh}
    ::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:var(--green-50)}::-webkit-scrollbar-thumb{background:var(--green-400);border-radius:3px}
    .page-bg{position:fixed;inset:0;z-index:-1;overflow:hidden;background:var(--green-50)}
    .page-bg::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 15% 15%,rgba(42,179,107,.08) 0%,transparent 50%),radial-gradient(circle at 85% 80%,rgba(29,148,88,.06) 0%,transparent 50%)}
    .page-bg-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(42,179,107,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(42,179,107,.04) 1px,transparent 1px);background-size:60px 60px}
    .page-wrap{max-width:1280px;margin:0 auto;padding:32px 24px 64px}
    .hero-banner{position:relative;overflow:hidden;border-radius:20px;background:linear-gradient(135deg,var(--green-900) 0%,var(--green-800) 30%,var(--green-700) 65%,var(--green-500) 100%);padding:32px 32px;margin-bottom:28px;box-shadow:0 8px 40px rgba(14,63,39,.35)}
    .hero-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(134,217,174,.18) 0%,transparent 50%),radial-gradient(circle at 10% 80%,rgba(78,201,138,.12) 0%,transparent 40%);pointer-events:none}
    .hero-arc{position:absolute;right:-100px;top:-100px;width:380px;height:380px;border-radius:50%;border:1px solid rgba(255,255,255,.1);pointer-events:none}
    .hero-content{position:relative;z-index:1;display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px}
    .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.82);margin-bottom:14px}
    .hero-eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--green-300);flex-shrink:0}
    .hero-title{font-family:'Fraunces',serif;font-weight:700;font-size:clamp(1.4rem,4vw,2rem);color:#fff;line-height:1.15;letter-spacing:-.025em;margin-bottom:6px}
    .hero-title em{font-style:italic;font-weight:400;color:rgba(255,255,255,.72)}
    .hero-sub{font-size:.85rem;color:rgba(255,255,255,.60);max-width:400px;line-height:1.6}
    .btn-hero-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .92);
        border: 1px solid rgba(255, 255, 255, .6);
        color: var(--neutral-700);
        font-size: .78rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .btn-hero-back:hover {
        background: #fff;
        color: var(--neutral-900);
    }
    .edit-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start}
    @media(max-width:900px){.edit-grid{grid-template-columns:1fr}}
    .o-card{background:var(--neutral-0);border-radius:16px;border:1px solid var(--green-100);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:16px}
    .o-card:last-child{margin-bottom:0}
    .o-card-header{padding:14px 20px;border-bottom:1px solid var(--neutral-100);display:flex;align-items:center;gap:9px;font-family:'Fraunces',serif;font-size:.85rem;font-weight:700;color:var(--neutral-800);background:linear-gradient(135deg,var(--green-50),var(--neutral-0))}
    .o-card-header .head-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.85rem;background:var(--green-100);color:var(--green-700);flex-shrink:0}
    .o-card-body{padding:22px 20px}
    .alert-strip{display:flex;align-items:flex-start;gap:10px;padding:13px 16px;border-radius:10px;margin-bottom:12px;font-size:.82rem;font-weight:500;border:1px solid;line-height:1.55}
    .alert-strip.success{background:var(--green-50);border-color:var(--green-200);color:var(--green-800)}
    .alert-strip.error{background:var(--red-pale);border-color:rgba(220,38,38,.25);color:#991b1b}
    .alert-strip.warn{background:var(--amber-pale);border-color:rgba(245,158,11,.3);color:#78350f}
    .alert-strip i{font-size:.95rem;flex-shrink:0;margin-top:2px}
    .fld-group{margin-bottom:18px}
    .fld-label{display:block;font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--neutral-400);margin-bottom:6px}
    .fld-required{color:var(--red)}
    .fld-ctrl{width:100%;padding:10px 14px;font-size:.83rem;font-family:'Instrument Sans',sans-serif;color:var(--neutral-900);background:var(--neutral-0);border:1.5px solid var(--neutral-200);border-radius:10px;outline:none;transition:border-color .15s,box-shadow .15s}
    .fld-ctrl:focus{border-color:var(--green-400);box-shadow:0 0 0 3px rgba(42,179,107,.12)}
    .fld-ctrl.err{border-color:var(--red)}
    .fld-ctrl::placeholder{color:var(--neutral-400)}
    .fld-err{font-size:.72rem;color:var(--red);margin-top:4px;font-weight:600;display:block}
    .fld-hint{font-size:.72rem;color:var(--neutral-400);margin-top:5px;line-height:1.4}
    .input-wrap{position:relative}
    .input-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--neutral-400);font-size:.85rem;pointer-events:none}
    .input-wrap .fld-ctrl{padding-left:36px}
    .fld-select{width:100%;padding:10px 36px 10px 14px;font-size:.83rem;font-family:'Instrument Sans',sans-serif;color:var(--neutral-700);background:var(--neutral-0) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238fa89d' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;border:1.5px solid var(--neutral-200);border-radius:10px;outline:none;appearance:none;transition:border-color .15s}
    .fld-select:focus{border-color:var(--green-400);box-shadow:0 0 0 3px rgba(42,179,107,.12)}
    .form-divider{display:flex;align-items:center;gap:10px;margin:20px 0}
    .form-divider::before,.form-divider::after{content:'';flex:1;height:1px;background:var(--neutral-100)}
    .form-divider span{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--neutral-400)}
    .toggle-group{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--neutral-50);border:1.5px solid var(--neutral-200);border-radius:10px;gap:12px}
    .toggle-label-wrap{flex:1}
    .toggle-label-title{font-size:.82rem;font-weight:700;color:var(--neutral-800)}
    .toggle-label-desc{font-size:.72rem;color:var(--neutral-500);margin-top:2px}
    .toggle-switch{position:relative;width:42px;height:24px;flex-shrink:0}
    .toggle-switch input{opacity:0;width:0;height:0}
    .toggle-track{position:absolute;inset:0;background:var(--neutral-400);border-radius:100px;cursor:pointer;transition:background .2s}
    .toggle-track::before{content:'';position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.15)}
    .toggle-switch input:checked + .toggle-track{background:var(--green-500)}
    .toggle-switch input:checked + .toggle-track::before{transform:translateX(18px)}
    .form-actions{display:flex;gap:10px;margin-top:24px;flex-wrap:wrap}
    .btn-primary{display:inline-flex;align-items:center;gap:8px;padding:11px 26px;font-size:.83rem;font-weight:700;font-family:'Instrument Sans',sans-serif;color:#fff;background:linear-gradient(135deg,var(--green-600),var(--green-500));border:none;border-radius:10px;cursor:pointer;box-shadow:0 2px 10px rgba(42,179,107,.3);transition:all .2s;text-decoration:none}
    .btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(42,179,107,.4);color:#fff}
    .btn-ghost{display:inline-flex;align-items:center;gap:7px;padding:11px 20px;font-size:.83rem;font-weight:600;font-family:'Instrument Sans',sans-serif;color:var(--neutral-600);background:var(--neutral-0);border:1.5px solid var(--neutral-200);border-radius:10px;cursor:pointer;text-decoration:none;transition:all .15s}
    .btn-ghost:hover{border-color:var(--neutral-300);background:var(--neutral-50);color:var(--neutral-700)}
    .profile-mini{display:flex;flex-direction:column;align-items:center;padding:24px 18px 18px;text-align:center}
    .profile-avatar-lg{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--green-500),var(--green-700));display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;font-weight:700;margin-bottom:12px;box-shadow:0 4px 14px rgba(42,179,107,.25)}
    .profile-mini-name{font-family:'Fraunces',serif;font-size:1rem;font-weight:700;color:var(--neutral-900)}
    .profile-mini-email{font-size:.73rem;color:var(--neutral-400);margin-top:2px}
    .profile-mini-role{margin-top:8px;display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:.68rem;font-weight:600;background:var(--green-100);color:var(--green-800);border:1px solid var(--green-200)}
    .quick-stat{display:flex;justify-content:space-between;align-items:center;padding:9px 18px;border-bottom:1px solid var(--neutral-100);font-size:.77rem}
    .quick-stat:last-child{border-bottom:none}
    .qs-label{color:var(--neutral-500);font-weight:600}
    .qs-value{font-weight:700;color:var(--neutral-800)}
    .danger-zone{background:var(--red-pale);border:1.5px solid rgba(220,38,38,.25);border-radius:12px;padding:16px 18px;margin-top:16px}
    .danger-title{display:flex;align-items:center;gap:6px;font-size:.75rem;font-weight:700;color:var(--red);margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em}
    .danger-desc{font-size:.73rem;color:#7f1d1d;line-height:1.5;margin-bottom:12px}
    .btn-danger{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:9px 14px;font-size:.78rem;font-weight:700;font-family:'Instrument Sans',sans-serif;color:#fff;background:var(--red);border:none;border-radius:8px;cursor:pointer;transition:all .15s}
    .btn-danger:hover{background:#b91c1c;transform:translateY(-1px)}
    .fade-up{opacity:0;transform:translateY(14px);animation:fadeUp .4s ease forwards}
    @keyframes fadeUp{to{opacity:1;transform:none}}
    .d1{animation-delay:.04s}.d2{animation-delay:.09s}.d3{animation-delay:.14s}
    @media(max-width:600px){.page-wrap{padding:16px 14px 48px}.o-card-body{padding:16px 14px}.form-actions{flex-direction:column}.btn-primary,.btn-ghost{justify-content:center}}
    @media(max-width:480px){.hero-arc{display:none}.hero-banner{padding:22px 18px}}
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">
    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="btn-hero-back" style="margin-bottom: 16px;">
        <i class="bi bi-arrow-left"></i>Kembali
    </a>
    <div class="hero-banner fade-up d1">
        <div class="hero-content">
            <div>
                <div class="hero-eyebrow"><span class="dot"></span>Manajemen Penghuni</div>
                <h1 class="hero-title">Edit <em>{{ $tenant->name }}</em></h1>
                <p class="hero-sub">Perbarui data profil dan status penghuni.</p>
            </div>
        </div>
    </div>

    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
        <div class="alert-strip {{ $type === 'success' ? 'success' : ($type === 'error' ? 'error' : 'warn') }} fade-up d1">
            <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'exclamation-circle-fill' : 'exclamation-triangle-fill') }}"></i>
            <span>{!! session($type) !!}</span>
        </div>
        @endif
    @endforeach

    <div class="edit-grid">

        <div class="fade-up d2">
            <div class="o-card">
                <div class="o-card-header">
                    <div class="head-icon"><i class="bi bi-pencil-fill"></i></div>
                    Perbarui Data Penghuni
                </div>
                <div class="o-card-body">

                    <form method="POST" action="{{ route('admin.tenants.update', $tenant->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="fld-group">
                            <label class="fld-label" for="fieldName">Nama Lengkap <span class="fld-required">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-person input-prefix"></i>
                                <input type="text" name="name" id="fieldName"
                                    class="fld-ctrl @error('name') err @enderror"
                                    value="{{ old('name', $tenant->name) }}"
                                    placeholder="Nama lengkap penghuni" required>
                            </div>
                            @error('name')<span class="fld-err">{{ $message }}</span>@enderror
                        </div>

                        <div class="fld-group">
                            <label class="fld-label" for="fieldEmail">Email <span class="fld-required">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-envelope input-prefix"></i>
                                <input type="email" name="email" id="fieldEmail"
                                    class="fld-ctrl @error('email') err @enderror"
                                    value="{{ old('email', $tenant->email) }}"
                                    placeholder="email@contoh.com" required>
                            </div>
                            @error('email')<span class="fld-err">{{ $message }}</span>@enderror
                            <div class="fld-hint">Mengubah email akan mempengaruhi login penghuni.</div>
                        </div>

                        <div class="fld-group">
                            <label class="fld-label" for="fieldPhone">No. HP / WhatsApp</label>
                            <div class="input-wrap">
                                <i class="bi bi-telephone input-prefix"></i>
                                <input type="text" name="phone" id="fieldPhone"
                                    class="fld-ctrl"
                                    value="{{ old('phone', $tenant->phone) }}"
                                    placeholder="0812-xxxx-xxxx">
                            </div>
                        </div>

                        <div class="form-divider"><span>pengaturan akun</span></div>

                        <div class="fld-group">
                            <label class="fld-label" for="fieldRole">Role / Status <span class="fld-required">*</span></label>
                            <select name="role_id" id="fieldRole" class="fld-select">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $tenant->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="fld-hint">Mengubah role akan mempengaruhi hak akses penghuni di sistem.</div>
                        </div>

                        <div class="fld-group">
                            <label class="fld-label">Status Akun</label>
                            <div class="toggle-group">
                                <div class="toggle-label-wrap">
                                    <div class="toggle-label-title">Akun Aktif</div>
                                    <div class="toggle-label-desc">Penghuni dapat login dan mengakses dashboard</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}>
                                    <span class="toggle-track"></span>
                                </label>
                            </div>
                        </div>

                        <div class="fld-group">
                            <label class="fld-label" for="fieldNotes">Catatan Admin</label>
                            <textarea name="admin_notes" id="fieldNotes"
                                class="fld-ctrl"
                                rows="3"
                                placeholder="Catatan internal mengenai penghuni ini...">{{ old('admin_notes', $tenant->admin_notes ?? '') }}</textarea>
                            <div class="fld-hint">Catatan ini hanya terlihat oleh admin.</div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check-lg"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="btn-ghost">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="fade-up d3" style="display:flex;flex-direction:column;gap:16px">

            <div class="o-card">
                <div class="o-card-header">
                    <div class="head-icon"><i class="bi bi-person-circle"></i></div>
                    Profil Saat Ini
                </div>
                <div class="profile-mini">
                    <div class="profile-avatar-lg">{{ strtoupper(substr($tenant->name, 0, 1)) }}</div>
                    <div class="profile-mini-name">{{ $tenant->name }}</div>
                    <div class="profile-mini-email">{{ $tenant->email }}</div>
                    <div class="profile-mini-role">
                        <i class="bi bi-shield-check-fill"></i>
                        {{ $tenant->role?->display_name ?? '—' }}
                    </div>
                </div>
                <div class="quick-stat">
                    <span class="qs-label">Status</span>
                    <span class="qs-value" style="color:{{ $tenant->is_active ? 'var(--green-600)' : 'var(--red)' }}">
                        {{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="quick-stat">
                    <span class="qs-label">Bergabung</span>
                    <span class="qs-value">{{ $tenant->created_at->format('d M Y') }}</span>
                </div>
                <div class="quick-stat">
                    <span class="qs-label">Total Booking</span>
                    <span class="qs-value">{{ $tenant->bookings?->count() ?? 0 }}</span>
                </div>
            </div>

            <div class="danger-zone">
                <div class="danger-title">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Hapus Penghuni
                </div>
                <div class="danger-desc">
                    Menghapus penghuni akan menghapus semua data terkait termasuk riwayat booking. Aksi ini <strong>tidak bisa dibatalkan</strong>.
                </div>
                <button type="button" class="btn-danger" onclick="confirmDelete()">
                    <i class="bi bi-trash3-fill"></i>
                    Hapus Penghuni Ini
                </button>
            </div>

            <form id="deleteForm" method="POST"
                  action="{{ route('admin.tenants.destroy', $tenant->id) }}"
                  style="display:none">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>

</div>

<script>
function confirmDelete() {
    if (confirm('Yakin ingin menghapus penghuni "{{ addslashes($tenant->name) }}"?\n\nSemua data terkait akan ikut terhapus dan tidak bisa dikembalikan.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

@stack('scripts')
</body>
</html>