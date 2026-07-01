<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftarkan Penghuni — Urban Kost</title>
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

    .page-wrap{max-width:1100px;margin:0 auto;padding:32px 24px 64px}

    .hero-banner{position:relative;overflow:hidden;border-radius:20px;background:linear-gradient(135deg,var(--green-900) 0%,var(--green-800) 30%,var(--green-700) 65%,var(--green-500) 100%);padding:30px 32px;margin-bottom:24px;box-shadow:0 8px 40px rgba(14,63,39,.35)}
    .hero-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 80% 50%,rgba(134,217,174,.18) 0%,transparent 50%),radial-gradient(circle at 10% 80%,rgba(78,201,138,.12) 0%,transparent 40%);pointer-events:none}
    .hero-content{position:relative;z-index:1}
    .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.82);margin-bottom:12px}
    .hero-eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--green-300);flex-shrink:0}
    .hero-title{font-family:'Fraunces',serif;font-weight:700;font-size:clamp(1.3rem,3.5vw,1.85rem);color:#fff;line-height:1.15;letter-spacing:-.025em;margin-bottom:5px}
    .hero-title em{font-style:italic;font-weight:400;color:rgba(255,255,255,.72)}
    .hero-sub{font-size:.83rem;color:rgba(255,255,255,.60);line-height:1.6;max-width:480px}

    .btn-back{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:9px;background:var(--neutral-0);border:1px solid var(--neutral-200);color:var(--neutral-600);font-size:.8rem;font-weight:600;text-decoration:none;transition:all .15s;margin-bottom:16px}
    .btn-back:hover{border-color:var(--green-300);color:var(--green-700);background:var(--green-50)}

    .create-grid{display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start}

    .o-card{background:var(--neutral-0);border-radius:14px;border:1px solid var(--green-100);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:16px}
    .o-card:last-child{margin-bottom:0}
    .o-card-header{padding:13px 18px;border-bottom:1px solid var(--neutral-100);display:flex;align-items:center;gap:9px;font-family:'Fraunces',serif;font-size:.85rem;font-weight:700;color:var(--neutral-800);background:linear-gradient(135deg,var(--green-50),var(--neutral-0))}
    .o-card-header .head-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.8rem;background:var(--green-100);color:var(--green-700);flex-shrink:0}
    .o-card-body{padding:20px 18px}

    .alert-strip{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:10px;margin-bottom:12px;font-size:.82rem;font-weight:500;border:1px solid;line-height:1.55}
    .alert-strip.success{background:var(--green-50);border-color:var(--green-200);color:var(--green-800)}
    .alert-strip.error{background:var(--red-pale);border-color:rgba(220,38,38,.25);color:#991b1b}
    .alert-strip.warn{background:var(--amber-pale);border-color:rgba(245,158,11,.3);color:#78350f}
    .alert-strip i{flex-shrink:0;margin-top:2px}

    .info-banner{background:linear-gradient(135deg,var(--green-50),#f0faf4);border:1.5px solid var(--green-200);border-radius:11px;padding:14px 16px;margin-bottom:20px}
    .info-banner-title{display:flex;align-items:center;gap:7px;font-size:.78rem;font-weight:700;color:var(--green-700);margin-bottom:8px}
    .info-banner ul{padding-left:16px;margin:0}
    .info-banner ul li{font-size:.75rem;color:var(--neutral-600);line-height:1.6;margin-bottom:3px}
    .info-banner ul li:last-child{margin-bottom:0}
    .info-banner ul li strong{color:var(--neutral-800);font-weight:700}

    .fld-group{margin-bottom:16px}
    .fld-label{display:block;font-size:.67rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--neutral-400);margin-bottom:5px}
    .fld-required{color:var(--red)}
    .fld-ctrl{width:100%;padding:10px 13px;font-size:.83rem;font-family:'Instrument Sans',sans-serif;color:var(--neutral-900);background:var(--neutral-0);border:1.5px solid var(--neutral-200);border-radius:10px;outline:none;transition:border-color .15s,box-shadow .15s}
    .fld-ctrl:focus{border-color:var(--green-400);box-shadow:0 0 0 3px rgba(42,179,107,.12)}
    .fld-ctrl.err{border-color:var(--red)}
    .fld-ctrl::placeholder{color:var(--neutral-400)}
    .fld-err{font-size:.72rem;color:var(--red);margin-top:4px;font-weight:600;display:block}
    .fld-hint{font-size:.71rem;color:var(--neutral-400);margin-top:4px;line-height:1.4}

    .input-wrap{position:relative}
    .input-prefix{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--neutral-400);font-size:.82rem;pointer-events:none}
    .input-wrap .fld-ctrl{padding-left:34px}

    .fld-select{width:100%;padding:10px 34px 10px 13px;font-size:.83rem;font-family:'Instrument Sans',sans-serif;color:var(--neutral-700);background:var(--neutral-0) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238fa89d' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;border:1.5px solid var(--neutral-200);border-radius:10px;outline:none;appearance:none;transition:border-color .15s}
    .fld-select:focus{border-color:var(--green-400);box-shadow:0 0 0 3px rgba(42,179,107,.12)}

    .form-divider{display:flex;align-items:center;gap:10px;margin:18px 0}
    .form-divider::before,.form-divider::after{content:'';flex:1;height:1px;background:var(--neutral-100)}
    .form-divider span{font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--neutral-400)}

    .email-status{margin-top:7px}
    .est-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 11px;border-radius:8px;font-size:.73rem;font-weight:600}
    .est-badge.upgrade{background:var(--green-100);color:var(--green-800);border:1px solid var(--green-200)}
    .est-badge.new-user{background:var(--blue-pale);color:var(--blue);border:1px solid #bfdbfe}

    .form-actions{display:flex;gap:10px;margin-top:22px;flex-wrap:wrap}
    .btn-primary{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;font-size:.83rem;font-weight:700;font-family:'Instrument Sans',sans-serif;color:#fff;background:linear-gradient(135deg,var(--green-600),var(--green-500));border:none;border-radius:10px;cursor:pointer;box-shadow:0 2px 10px rgba(42,179,107,.3);transition:all .2s;text-decoration:none}
    .btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(42,179,107,.4);color:#fff}
    .btn-ghost{display:inline-flex;align-items:center;gap:7px;padding:11px 18px;font-size:.83rem;font-weight:600;font-family:'Instrument Sans',sans-serif;color:var(--neutral-600);background:var(--neutral-0);border:1.5px solid var(--neutral-200);border-radius:10px;cursor:pointer;text-decoration:none;transition:all .15s}
    .btn-ghost:hover{border-color:var(--neutral-300);background:var(--neutral-50);color:var(--neutral-700)}

    .flow-step{display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--neutral-100)}
    .flow-step:last-child{border-bottom:none}
    .flow-num{width:26px;height:26px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;margin-top:2px}
    .flow-text{font-size:.77rem;color:var(--neutral-600);line-height:1.55}
    .flow-text strong{color:var(--neutral-900);font-weight:700}

    .fade-up{opacity:0;transform:translateY(12px);animation:fadeUp .38s ease forwards}
    @keyframes fadeUp{to{opacity:1;transform:none}}
    .d1{animation-delay:.04s}.d2{animation-delay:.09s}.d3{animation-delay:.13s}.d4{animation-delay:.17s}

    /* Tablet */
    @media(max-width:900px){
        .create-grid{grid-template-columns:1fr}
        .page-wrap{padding:24px 20px 56px}
    }
    /* Mobile */
    @media(max-width:600px){
        .page-wrap{padding:16px 14px 48px}
        .hero-banner{padding:20px 18px}
        .hero-title{font-size:1.2rem}
        .o-card-body{padding:16px 14px}
        .o-card-header{padding:12px 14px}
        .form-actions{flex-direction:column}
        .btn-primary,.btn-ghost{justify-content:center;width:100%}
        .info-banner{padding:12px 14px}
    }
    /* Small mobile */
    @media(max-width:380px){
        .page-wrap{padding:12px 12px 40px}
        .hero-banner{padding:16px 14px}
        .hero-title{font-size:1.1rem}
        .hero-sub{font-size:.78rem}
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">

    <a href="{{ route('admin.tenants.index') }}" class="btn-back fade-up d1">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="hero-banner fade-up d1">
        <div class="hero-content">
            <div class="hero-eyebrow"><span class="dot"></span>Manajemen Penghuni</div>
            <h1 class="hero-title">Daftarkan <em>Penghuni Baru</em></h1>
            <p class="hero-sub">Buat akun baru atau upgrade Calon Penghuni menjadi Penghuni Aktif.</p>
        </div>
    </div>

    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
        <div class="alert-strip {{ $type === 'success' ? 'success' : ($type === 'error' ? 'error' : 'warn') }} fade-up d2">
            <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'exclamation-circle-fill' : 'exclamation-triangle-fill') }}"></i>
            <span>{!! session($type) !!}</span>
        </div>
        @endif
    @endforeach

    <div class="create-grid">

        <div class="fade-up d2">
            <div class="o-card">
                <div class="o-card-header">
                    <div class="head-icon"><i class="bi bi-person-plus-fill"></i></div>
                    Daftarkan / Upgrade ke Penghuni Aktif
                </div>
                <div class="o-card-body">

                    <div class="info-banner">
                        <div class="info-banner-title">
                            <i class="bi bi-info-circle-fill" style="color:var(--green-500)"></i>
                            Cara Kerja Sistem
                        </div>
                        <ul>
                            <li>Email <strong>belum terdaftar</strong> — akun baru dibuat sebagai Penghuni Aktif</li>
                            <li>Email <strong>sudah terdaftar sebagai Calon Penghuni</strong> — role otomatis diupgrade</li>
                            <li>Calon Penghuni dengan booking disetujui bisa langsung diupgrade di sini</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('admin.tenants.store') }}" id="tenantForm" novalidate>
                        @csrf

                        @if($prospectives->isNotEmpty())
                        <div class="fld-group">
                            <label class="fld-label">
                                Pilih dari Calon Penghuni
                                <span style="background:var(--green-100);color:var(--green-800);padding:1px 7px;border-radius:100px;font-size:.65rem;margin-left:4px;font-weight:600">{{ $prospectives->count() }}</span>
                            </label>
                            <select id="prospectiveSelect" class="fld-select" onchange="fillFromProspective(this)">
                                <option value="">— Atau isi manual di bawah —</option>
                                @foreach($prospectives as $p)
                                <option value="{{ $p->id }}"
                                    data-name="{{ $p->name }}"
                                    data-email="{{ $p->email }}"
                                    data-phone="{{ $p->phone }}">
                                    {{ $p->name }} — {{ $p->email }}
                                    @php $approvedCount = $p->bookings()->where('status','approved')->count(); @endphp
                                    @if($approvedCount > 0) ({{ $approvedCount }} booking disetujui) @endif
                                </option>
                                @endforeach
                            </select>
                            <div class="fld-hint">Pilih nama untuk mengisi form secara otomatis.</div>
                        </div>
                        <div class="form-divider"><span>atau isi manual</span></div>
                        @endif

                        <div class="fld-group">
                            <label class="fld-label" for="fieldName">Nama Lengkap <span class="fld-required">*</span></label>
                            <div class="input-wrap">
                                <i class="bi bi-person input-prefix"></i>
                                <input type="text" name="name" id="fieldName"
                                    class="fld-ctrl @error('name') err @enderror"
                                    value="{{ old('name') }}"
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
                                    value="{{ old('email') }}"
                                    placeholder="email@contoh.com"
                                    oninput="checkEmailStatus(this.value)"
                                    required>
                            </div>
                            @error('email')<span class="fld-err">{{ $message }}</span>@enderror
                            <div id="emailStatusBadge" class="email-status" style="display:none"></div>
                            <div class="fld-hint">Email Calon Penghuni yang sudah terdaftar akan otomatis diupgrade.</div>
                        </div>

                        <div class="fld-group">
                            <label class="fld-label" for="fieldPhone">No. HP / WhatsApp</label>
                            <div class="input-wrap">
                                <i class="bi bi-telephone input-prefix"></i>
                                <input type="text" name="phone" id="fieldPhone"
                                    class="fld-ctrl"
                                    value="{{ old('phone') }}"
                                    placeholder="0812-xxxx-xxxx">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary" id="submitBtn">
                                <i class="bi bi-person-check-fill"></i>
                                <span id="submitLabel">Daftarkan sebagai Penghuni Aktif</span>
                            </button>
                            <a href="{{ route('admin.tenants.index') }}" class="btn-ghost">
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
                    <div class="head-icon" style="background:#ede9fe;color:#7c3aed">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    Alur Pendaftaran
                </div>
                <div class="o-card-body" style="padding-top:12px;padding-bottom:12px">

                    <div style="display:flex;align-items:flex-start;gap:8px;padding:10px 12px;background:var(--amber-pale);border:1px solid #fde68a;border-radius:8px;margin-bottom:14px">
                        <i class="bi bi-info-circle-fill" style="color:#92400e;font-size:.85rem;margin-top:1px;flex-shrink:0"></i>
                        <p style="margin:0;font-size:.72rem;color:#78350f;line-height:1.5">
                            Pendaftaran manual ini digunakan ketika calon penghuni <strong>datang langsung ke lokasi</strong> dan meminta bantuan admin untuk didaftarkan ke sistem.
                        </p>
                    </div>

                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--neutral-400);margin-bottom:4px">
                        Penghuni Belum Registrasi
                    </div>
                    <p style="margin:0 0 10px;font-size:.72rem;color:var(--neutral-400);line-height:1.5">
                        Gunakan alur ini jika calon penghuni belum pernah mendaftar online sama sekali dan tidak memiliki akun di sistem.
                    </p>
                    <div class="flow-step">
                        <div class="flow-num" style="background:var(--blue-pale);color:var(--blue)">1</div>
                        <div class="flow-text">Isi nama, email, dan no. HP penghuni baru</div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-num" style="background:var(--blue-pale);color:var(--blue)">2</div>
                        <div class="flow-text">Klik <strong>Buat Akun Penghuni Baru</strong> — akun langsung aktif</div>
                    </div>
                    <div class="flow-step" style="border-bottom:none">
                        <div class="flow-num" style="background:linear-gradient(135deg,var(--green-500),var(--green-700));color:#fff">3</div>
                        <div class="flow-text">Penghuni dapat login dan akses <strong>dashboard Penghuni Aktif</strong></div>
                    </div>

                    <div style="height:1px;background:var(--neutral-100);margin:14px 0"></div>

                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--neutral-400);margin-bottom:4px">
                        Dari Calon Penghuni
                    </div>
                    <p style="margin:0 0 10px;font-size:.72rem;color:var(--neutral-400);line-height:1.5">
                        Gunakan alur ini jika calon penghuni sudah terdaftar di waitinglist atau pernah mengisi formulir online sebelumnya.
                    </p>
                    <div class="flow-step">
                        <div class="flow-num" style="background:var(--amber-pale);color:#92400e">1</div>
                        <div class="flow-text">Pilih nama dari dropdown <strong>Calon Penghuni</strong> di atas</div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-num" style="background:var(--amber-pale);color:#92400e">2</div>
                        <div class="flow-text">Data terisi otomatis — klik <strong>Upgrade ke Penghuni Aktif</strong></div>
                    </div>
                    <div class="flow-step" style="border-bottom:none">
                        <div class="flow-num" style="background:linear-gradient(135deg,var(--green-500),var(--green-700));color:#fff">3</div>
                        <div class="flow-text">Role diupgrade, penghuni langsung aktif tanpa membuat akun baru</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
const prospectiveEmails = {
    @foreach($prospectives as $p)
    "{{ $p->email }}": { name: "{{ addslashes($p->name) }}", phone: "{{ $p->phone }}" },
    @endforeach
};

function fillFromProspective(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt.value) { clearForm(); return; }
    document.getElementById('fieldName').value  = opt.dataset.name  || '';
    document.getElementById('fieldEmail').value = opt.dataset.email || '';
    document.getElementById('fieldPhone').value = opt.dataset.phone || '';
    updateEmailBadge(opt.dataset.email);
}

function clearForm() {
    ['fieldName','fieldEmail','fieldPhone'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('emailStatusBadge').style.display = 'none';
    document.getElementById('submitLabel').textContent = 'Daftarkan sebagai Penghuni Aktif';
}

function checkEmailStatus(email) { updateEmailBadge(email.toLowerCase().trim()); }

function updateEmailBadge(email) {
    const badge = document.getElementById('emailStatusBadge');
    const label = document.getElementById('submitLabel');
    email = (email || '').toLowerCase().trim();
    if (!email) { badge.style.display = 'none'; return; }
    if (prospectiveEmails[email]) {
        badge.style.display = 'block';
        badge.innerHTML = `<span class="est-badge upgrade"><i class="bi bi-arrow-up-circle-fill"></i>Email ditemukan — akan diupgrade ke Penghuni Aktif</span>`;
        label.textContent = 'Upgrade ke Penghuni Aktif';
    } else {
        badge.style.display = 'block';
        badge.innerHTML = `<span class="est-badge new-user"><i class="bi bi-person-plus-fill"></i>Email baru — akun Penghuni Aktif baru akan dibuat</span>`;
        label.textContent = 'Buat Akun Penghuni Baru';
    }
}

const initialEmail = document.getElementById('fieldEmail').value;
if (initialEmail) updateEmailBadge(initialEmail);
</script>

@stack('scripts')
</body>
</html>