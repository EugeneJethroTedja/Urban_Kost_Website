<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Penghuni — Urban Kost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
    .hero-content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
    .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.82);margin-bottom:12px}
    .hero-eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--green-300);flex-shrink:0}
    .hero-title{font-family:'Fraunces',serif;font-weight:700;font-size:clamp(1.3rem,3.5vw,1.85rem);color:#fff;line-height:1.15;letter-spacing:-.025em;margin-bottom:5px}
    .hero-title em{font-style:italic;font-weight:400;color:rgba(255,255,255,.72)}
    .hero-sub{font-size:.83rem;color:rgba(255,255,255,.60);line-height:1.6}
    .btn-hero{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border-radius:10px;background:#fff;border:1px solid rgba(255,255,255,.15);color:var(--green-900);font-size:.8rem;font-weight:600;text-decoration:none;transition:all .2s;white-space:nowrap;flex-shrink:0}
    .btn-hero:hover{background:var(--green-50);color:var(--green-900)}

    .stats-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:20px}

    .stat-card{position:relative;overflow:hidden;border-radius:14px;padding:20px 18px;background:var(--neutral-0);border:1px solid var(--green-100);box-shadow:var(--shadow-md);transition:border-color .2s,box-shadow .25s,transform .2s}
    .stat-card:hover{border-color:var(--green-300);box-shadow:var(--shadow-lg);transform:translateY(-2px)}
    .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;border-radius:14px 14px 0 0}
    .stat-card.green::before{background:linear-gradient(90deg,var(--green-500),var(--green-400))}
    .stat-card.blue::before{background:linear-gradient(90deg,var(--blue),#60a5fa)}
    .stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.9rem;margin-bottom:12px}
    .stat-card.green .stat-icon{background:var(--green-100);color:var(--green-700)}
    .stat-card.blue .stat-icon{background:var(--blue-pale);color:var(--blue)}
    .stat-value{font-family:'Fraunces',serif;font-size:2rem;font-weight:700;color:var(--neutral-900);line-height:1;margin-bottom:3px;letter-spacing:-.03em}
    .stat-label{font-size:.67rem;font-weight:600;color:var(--neutral-400);text-transform:uppercase;letter-spacing:.08em}

    .alert-strip{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:10px;margin-bottom:12px;font-size:.82rem;font-weight:500;border:1px solid;line-height:1.55}
    .alert-strip.success{background:var(--green-50);border-color:var(--green-200);color:var(--green-800)}
    .alert-strip.error{background:var(--red-pale);border-color:rgba(220,38,38,.25);color:#991b1b}
    .alert-strip.warn{background:var(--amber-pale);border-color:rgba(245,158,11,.3);color:#78350f}
    .alert-strip i{flex-shrink:0;margin-top:2px}

    .o-card{background:var(--neutral-0);border-radius:14px;border:1px solid var(--green-100);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:16px}
    .o-card:last-child{margin-bottom:0}
    .o-card-header{padding:13px 18px;border-bottom:1px solid var(--neutral-100);display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,var(--green-50),var(--neutral-0))}
    .o-card-header-title{font-size:.7rem;font-weight:600;color:var(--neutral-600);display:flex;align-items:center;gap:7px;text-transform:uppercase;letter-spacing:.07em}
    .o-card-header-title i{color:var(--green-500)}
    .head-count{background:var(--green-100);color:var(--green-800);font-size:.62rem;font-weight:700;padding:2px 8px;border-radius:100px;border:1px solid var(--green-200)}

    .search-bar{display:flex;gap:8px;padding:13px 16px;border-bottom:1px solid var(--neutral-100);flex-wrap:wrap}
    .search-wrap{position:relative;flex:1;min-width:180px}
    .search-icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--neutral-400);font-size:.82rem;pointer-events:none}
    .search-input{width:100%;padding:8px 13px 8px 34px;font-size:.8rem;font-family:'Instrument Sans',sans-serif;color:var(--neutral-900);background:var(--neutral-0);border:1.5px solid var(--neutral-200);border-radius:9px;outline:none;transition:border-color .15s}
    .search-input:focus{border-color:var(--green-400);box-shadow:0 0 0 3px rgba(42,179,107,.1)}
    .search-select{padding:8px 32px 8px 11px;font-size:.8rem;font-family:'Instrument Sans',sans-serif;color:var(--neutral-700);background:var(--neutral-0) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238fa89d' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;border:1.5px solid var(--neutral-200);border-radius:9px;outline:none;appearance:none;cursor:pointer;min-width:130px;transition:border-color .15s}
    .search-select:focus{border-color:var(--green-400)}
    .btn-filter{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;font-size:.78rem;font-weight:600;font-family:'Instrument Sans',sans-serif;color:var(--neutral-600);background:var(--neutral-0);border:1.5px solid var(--neutral-200);border-radius:9px;cursor:pointer;text-decoration:none;transition:all .15s}
    .btn-filter:hover{border-color:var(--green-300);color:var(--green-600);background:var(--green-50)}

    .upgrade-banner{background:var(--neutral-0);border:1.5px solid var(--green-200);border-radius:14px;overflow:hidden;margin-bottom:16px;box-shadow:var(--shadow-sm)}
    .upgrade-banner-head{display:flex;align-items:center;gap:10px;padding:12px 16px;border-bottom:1px solid var(--green-100);background:linear-gradient(135deg,var(--green-50),var(--neutral-0))}
    .upgrade-banner-title{font-size:.8rem;font-weight:700;color:var(--green-800);font-family:'Fraunces',serif}
    .upgrade-banner-badge{margin-left:auto;font-size:.62rem;font-weight:700;padding:3px 9px;border-radius:100px;background:var(--green-500);color:#fff}

    .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
    .dtbl{width:100%;border-collapse:collapse}
    .dtbl thead th{padding:9px 16px;font-size:.62rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--neutral-400);background:var(--neutral-50);border-bottom:1px solid var(--neutral-100);white-space:nowrap;text-align:left}
    .dtbl tbody tr{border-bottom:1px solid var(--neutral-100);transition:background .12s}
    .dtbl tbody tr:last-child{border-bottom:none}
    .dtbl tbody tr:hover td{background:var(--green-50)}
    .dtbl tbody td{padding:11px 16px;font-size:.8rem;vertical-align:middle;color:var(--neutral-600)}

    .avatar-circle{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--green-500),var(--green-400));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;box-shadow:0 2px 6px rgba(42,179,107,.28);flex-shrink:0}
    .tenant-name{font-weight:600;color:var(--neutral-900);font-size:.82rem}
    .tenant-email{font-size:.7rem;color:var(--neutral-400);margin-top:1px}
    .room-chip{display:inline-flex;align-items:center;gap:4px;background:var(--neutral-100);border-radius:6px;padding:3px 9px;font-size:.7rem;font-weight:600;color:var(--neutral-700)}
    .badge-status{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:100px;font-size:.63rem;font-weight:600}
    .badge-status::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block}
    .badge-on{background:var(--green-100);color:var(--green-700)}
    .badge-off{background:var(--red-pale);color:var(--red)}
    .role-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;font-size:.65rem;font-weight:600;background:var(--neutral-100);color:var(--neutral-600);border:1px solid var(--neutral-200)}
    .tbl-actions{display:flex;gap:4px;align-items:center}
    .tbl-action{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;border:1px solid var(--neutral-200);color:var(--neutral-500);text-decoration:none;font-size:.76rem;transition:all .15s;background:var(--neutral-0);cursor:pointer}
    .tbl-action:hover{border-color:var(--green-300);color:var(--green-600);background:var(--green-50)}
    .tbl-action.edit:hover{border-color:#93c5fd;color:var(--blue);background:var(--blue-pale)}
    .tbl-action.danger:hover{border-color:rgba(220,38,38,.4);color:var(--red);background:var(--red-pale)}
    .btn-upgrade{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;font-size:.72rem;font-weight:600;font-family:'Instrument Sans',sans-serif;color:#fff;background:linear-gradient(135deg,var(--green-600),var(--green-500));border:none;border-radius:7px;cursor:pointer;box-shadow:0 1px 4px rgba(42,179,107,.25);transition:all .15s}
    .btn-upgrade:hover{transform:translateY(-1px);box-shadow:0 3px 10px rgba(42,179,107,.35)}

    .empty-state{text-align:center;padding:40px 20px;color:var(--neutral-400)}
    .empty-state i{font-size:2.2rem;display:block;opacity:.2;margin-bottom:10px}
    .empty-state p{font-size:.82rem;line-height:1.6}
    .empty-state a{color:var(--green-600);font-weight:600;text-decoration:none}
    .pagination-wrap{display:flex;justify-content:flex-end;padding:12px 16px;border-top:1px solid var(--neutral-100)}

    .fade-up{opacity:0;transform:translateY(12px);animation:fadeUp .38s ease forwards}
    @keyframes fadeUp{to{opacity:1;transform:none}}
    .d1{animation-delay:.04s}.d2{animation-delay:.09s}.d3{animation-delay:.13s}.d4{animation-delay:.17s}

    @media(max-width:768px){
        .page-wrap{padding:20px 16px 48px}
        .hero-banner{padding:22px 20px}
        .hide-mobile{display:none}
        .dtbl thead th,.dtbl tbody td{padding:9px 12px}
        .stats-grid{gap:10px}
    }
    @media(max-width:480px){
        .page-wrap{padding:16px 12px 40px}
        .hero-banner{padding:18px 16px}
        .hero-title{font-size:1.25rem}
        .stat-value{font-size:1.6rem}
        .search-bar{gap:6px}
        .search-select{min-width:110px}
    }
    @media(max-width:360px){
        .stats-grid{grid-template-columns:1fr}
    }
    </style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

<div class="page-wrap">

    <div class="hero-banner fade-up d1">
        <div class="hero-content">
            <div>
                <div class="hero-eyebrow"><span class="dot"></span>Manajemen Penghuni</div>
                <h1 class="hero-title">Manajemen <em>Penghuni</em></h1>
                <p class="hero-sub">Kelola data penghuni aktif dan calon penghuni.</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="btn-hero">
                <i class="bi bi-person-plus-fill"></i>Daftarkan / Upgrade Penghuni
            </a>
        </div>
    </div>

    @foreach(['success','error','warning'] as $type)
        @if(session($type))
        <div class="alert-strip {{ $type === 'success' ? 'success' : ($type === 'error' ? 'error' : 'warn') }} fade-up d2">
            <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'exclamation-circle-fill' : 'exclamation-triangle-fill') }}"></i>
            <span>{!! session($type) !!}</span>
        </div>
        @endif
    @endforeach

    @php
        $readyToUpgrade = \App\Models\User::whereHas('role', fn($q) => $q->where('name','prospective_tenant'))
            ->whereHas('bookings', fn($q) => $q->where('status','approved'))
            ->with(['bookings' => fn($q) => $q->where('status','approved')->with('room')])
            ->where('is_active', true)
            ->get();
    @endphp

    <div class="stats-grid fade-up d2">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ $tenants->total() }}</div>
            <div class="stat-label">Penghuni Aktif</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-person-plus-fill"></i></div>
            <div class="stat-value">{{ \App\Models\User::whereHas('role', fn($q) => $q->where('name','prospective_tenant'))->count() }}</div>
            <div class="stat-label">Calon Penghuni</div>
        </div>
    </div>

    @if($readyToUpgrade->isNotEmpty())
    <div class="upgrade-banner fade-up d3">
        <div class="upgrade-banner-head">
            <div style="width:28px;height:28px;border-radius:8px;background:var(--green-100);color:var(--green-700);display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0">
                <i class="bi bi-arrow-up-circle-fill"></i>
            </div>
            <div class="upgrade-banner-title">Calon Penghuni Siap Diupgrade</div>
            <span class="upgrade-banner-badge">{{ $readyToUpgrade->count() }} siap</span>
        </div>
        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="hide-mobile">Email</th>
                        <th class="hide-mobile">Kamar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($readyToUpgrade as $u)
                    @php $approvedBooking = $u->bookings->first(); @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="avatar-circle">{{ strtoupper(substr($u->name,0,1)) }}</div>
                                <div>
                                    <div class="tenant-name">{{ $u->name }}</div>
                                    <div class="tenant-email">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mobile"><span style="font-size:.75rem;color:var(--neutral-500)">{{ $u->email }}</span></td>
                        <td class="hide-mobile">
                            @if($approvedBooking)
                            <span class="room-chip"><i class="bi bi-house-fill" style="color:var(--green-500)"></i>{{ $approvedBooking->room?->room_number }}</span>
                            @else<span style="color:var(--neutral-400)">—</span>@endif
                        </td>
                        <td><span class="role-badge">{{ $u->role?->display_name }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.tenants.upgrade', $u->id) }}"
                                  onsubmit="return confirm('Upgrade {{ $u->name }} menjadi Penghuni Aktif?')">
                                @csrf
                                <button class="btn-upgrade">
                                    <i class="bi bi-arrow-up-circle-fill"></i>
                                    <span class="hide-mobile">Upgrade</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="o-card fade-up d3">
        <form method="GET" action="{{ route('admin.tenants.index') }}" class="search-bar">
            <div class="search-wrap">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class="search-input"
                       placeholder="Cari nama atau email penghuni..."
                       value="{{ request('search') }}">
            </div>
            <select name="status" class="search-select">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn-filter">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.tenants.index') }}" class="btn-filter" style="text-decoration:none">
                <i class="bi bi-x-lg"></i> Reset
            </a>
            @endif
        </form>

        <div class="o-card-header" style="border-bottom:none;border-top:1px solid var(--neutral-100)">
            <span class="o-card-header-title">
                <i class="bi bi-people-fill"></i>Penghuni Aktif
                <span class="head-count">{{ $tenants->total() }}</span>
            </span>
        </div>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>Penghuni</th>
                        <th class="hide-mobile">No. HP</th>
                        <th>Status</th>
                        <th class="hide-mobile">Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $t)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="avatar-circle">{{ strtoupper(substr($t->name,0,1)) }}</div>
                                <div>
                                    <div class="tenant-name">{{ $t->name }}</div>
                                    <div class="tenant-email">{{ $t->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mobile">
                            <span style="font-size:.78rem;color:var(--neutral-600)">{{ $t->phone ?? '—' }}</span>
                        </td>
                        <td>
                            @if($t->is_active)
                                <span class="badge-status badge-on">Aktif</span>
                            @else
                                <span class="badge-status badge-off">Nonaktif</span>
                            @endif
                        </td>
                        <td class="hide-mobile">
                            <span style="font-size:.75rem;color:var(--neutral-400)">{{ $t->created_at->format('d M Y') }}</span>
                        </td>
                        <td>
                            <div class="tbl-actions">
                                <a href="{{ route('admin.tenants.show', $t->id) }}" class="tbl-action" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.tenants.edit', $t->id) }}" class="tbl-action edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="tbl-action danger" title="Hapus"
                                    onclick="confirmDeleteTenant({{ $t->id }}, '{{ addslashes($t->name) }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                <form id="del-{{ $t->id }}" method="POST"
                                      action="{{ route('admin.tenants.destroy', $t->id) }}" style="display:none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>Belum ada penghuni terdaftar.<br>
                                <a href="{{ route('admin.tenants.create') }}">Daftarkan penghuni pertama</a></p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tenants->hasPages())
        <div class="pagination-wrap">{{ $tenants->links() }}</div>
        @endif
    </div>

</div>

<script>
function confirmDeleteTenant(id, name) {
    if (confirm('Yakin ingin menghapus penghuni "' + name + '"?\n\nSemua data terkait akan ikut terhapus.')) {
        document.getElementById('del-' + id).submit();
    }
}
</script>

@stack('scripts')
</body>
</html>