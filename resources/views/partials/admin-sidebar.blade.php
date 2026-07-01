<style>
/* ═══════════════════════════════════════════════════════════════
   URBAN KOST – ADMIN NAV  (.anav)
   Design tokens selaras dengan owner / prospective / tenant nav
   ═══════════════════════════════════════════════════════════════ */
:root {
    --g50:#f0fdf4;--g100:#dcfce7;--g200:#bbf7d0;--g300:#86efac;
    --g400:#4ade80;--g500:#22c55e;--g600:#16a34a;--g700:#15803d;
    --g800:#166534;--g900:#14532d;
    --n0:#ffffff;--n50:#f9fafb;--n100:#f3f4f6;--n200:#e5e7eb;
    --n400:#9ca3af;--n500:#6b7280;--n600:#4b5563;--n900:#111827;
    --red:#dc2626;--red-pale:#fef2f2;
    --amber:#f59e0b;--blue:#2563eb;
    --nav-h:64px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

/* ── Bar ── */
.anav {
    position:sticky;top:0;z-index:999;
    height:var(--nav-h);
    background:#fff;
    border-bottom:1px solid var(--g100);
    box-shadow:0 1px 0 var(--g100),0 2px 20px rgba(34,197,94,.07);
    transition:box-shadow .3s;
    width:100%;overflow:hidden;
}
.anav.elevated { box-shadow:0 4px 28px rgba(34,197,94,.13) }

.anav-inner {
    max-width:1400px;margin:0 auto;
    height:100%;padding:0 24px;
    display:flex;align-items:center;gap:8px;
}

/* ── Brand ── */
.anav-brand {
    display:flex;align-items:center;gap:10px;
    text-decoration:none;flex-shrink:0;margin-right:8px;
}
.anav-brandmark {
    width:36px;height:36px;border-radius:10px;
    background:linear-gradient(145deg,var(--g500),var(--g400));
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.9rem;
    box-shadow:0 3px 10px rgba(34,197,94,.35);
    flex-shrink:0;
}
.anav-brandname {
    font-family:'Fraunces',serif;font-weight:700;font-size:1.12rem;
    color:var(--n900);letter-spacing:-.02em;white-space:nowrap;
}
.anav-brandtag {
    font-size:.58rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;
    color:var(--g700);background:var(--g100);
    border:1px solid var(--g200);
    padding:2px 7px;border-radius:4px;
    margin-left:2px;white-space:nowrap;
}

/* ── Center nav ── */
.anav-center {
    flex:1;display:flex;align-items:center;justify-content:center;
    min-width:0;overflow:visible;
}
.anav-links {
    display:flex;align-items:center;gap:1px;
    list-style:none;
    background:var(--n50);border:1px solid var(--n200);
    border-radius:10px;padding:3px;
    flex-wrap:nowrap;overflow-x:auto;scrollbar-width:none;
    -webkit-overflow-scrolling:touch;
}
.anav-links::-webkit-scrollbar{display:none}
.anav-link {
    display:flex;align-items:center;gap:5px;
    padding:6px 11px;border-radius:7px;
    font-size:.73rem;font-weight:650;
    color:var(--n500);text-decoration:none;
    white-space:nowrap;flex-shrink:0;
    transition:color .15s,background .15s;
}
.anav-link:hover  { color:var(--g600);background:var(--g50) }
.anav-link.active {
    color:var(--g700);background:#fff;
    box-shadow:0 1px 5px rgba(34,197,94,.15);
}
.anav-link i { font-size:.74rem;flex-shrink:0 }

.anav-sep {
    width:1px;height:14px;background:var(--n200);
    margin:0 2px;flex-shrink:0;align-self:center;
}

/* ── Badge ── */
.anav-badge {
    display:inline-flex;align-items:center;justify-content:center;
    min-width:15px;height:15px;padding:0 3px;
    border-radius:999px;background:var(--red);
    color:#fff;font-size:.52rem;font-weight:800;
    line-height:1;flex-shrink:0;
}
.anav-badge.amber { background:var(--amber) }
.anav-badge.blue  { background:var(--blue) }

/* ── Actions ── */
.anav-actions {
    display:flex;align-items:center;gap:5px;flex-shrink:0;margin-left:8px;
}
.anav-notif {
    position:relative;width:34px;height:34px;
    border-radius:9px;display:flex;align-items:center;justify-content:center;
    color:var(--n500);text-decoration:none;
    transition:background .15s,color .15s;font-size:.88rem;
    border:1px solid var(--n200);flex-shrink:0;
}
.anav-notif:hover { background:var(--g50);color:var(--g600);border-color:var(--g200) }
.anav-notif-dot {
    position:absolute;top:6px;right:6px;
    width:6px;height:6px;border-radius:50%;
    background:var(--red);border:1.5px solid #fff;
}
.anav-avatar {
    width:28px;height:28px;border-radius:50%;
    background:linear-gradient(145deg,var(--g500),var(--g400));
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.68rem;font-weight:800;flex-shrink:0;
    box-shadow:0 2px 6px rgba(34,197,94,.35);
}
.anav-user {
    display:flex;align-items:center;gap:6px;
    padding:4px 10px 4px 5px;border-radius:9px;
    background:var(--g50);border:1px solid var(--g200);
    cursor:pointer;transition:background .15s,border-color .15s;
    text-decoration:none;flex-shrink:0;
}
.anav-user:hover { background:var(--g100);border-color:var(--g400) }
.anav-username {
    font-size:.73rem;font-weight:700;color:var(--g800);
    max-width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.anav-username small {
    display:block;font-size:.58rem;font-weight:500;
    color:var(--g600);line-height:1;margin-top:1px;
}
.anav-logout {
    width:34px;height:34px;border-radius:9px;
    display:flex;align-items:center;justify-content:center;
    background:transparent;border:1px solid var(--n200);
    color:var(--n500);cursor:pointer;
    transition:all .15s;font-size:.88rem;flex-shrink:0;
}
.anav-logout:hover { border-color:var(--red);color:var(--red);background:var(--red-pale) }

/* ── Hamburger ── */
.anav-hamburger {
    display:none;flex-direction:column;gap:5px;
    width:36px;height:36px;border:1px solid var(--n200);
    border-radius:9px;background:transparent;cursor:pointer;
    align-items:center;justify-content:center;
    transition:all .15s;flex-shrink:0;
}
.anav-hamburger:hover { border-color:var(--g400);background:var(--g50) }
.anav-hamburger span {
    display:block;width:16px;height:1.5px;
    background:var(--n600);border-radius:2px;transition:all .28s ease;
}
.anav-hamburger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg)}
.anav-hamburger.open span:nth-child(2){opacity:0;transform:scaleX(0)}
.anav-hamburger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg)}

/* ── Drawer ── */
.anav-drawer {
    position:fixed;top:var(--nav-h);left:0;right:0;
    background:#fff;border-bottom:1px solid var(--g100);
    box-shadow:0 8px 32px rgba(34,197,94,.10);
    display:none;flex-direction:column;
    z-index:998;
    max-height:calc(100dvh - var(--nav-h));overflow:hidden;
    animation:aNavDrawerIn .22s ease;
}
@keyframes aNavDrawerIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
.anav-drawer.open { display:flex }
.anav-drawer-scroll {
    flex:1;overflow-y:auto;padding:12px 16px 8px;
    display:flex;flex-direction:column;gap:3px;
    -webkit-overflow-scrolling:touch;
}
.anav-drawer-footer {
    padding:10px 16px 16px;border-top:1px solid var(--n100);background:#fff;flex-shrink:0;
}
.anav-drawer-sep { height:1px;background:var(--n100);margin:6px 0 }
.anav-drawer-label {
    font-size:.62rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;
    color:var(--n400);padding:4px 10px 2px;
}
.anav-link-m {
    display:flex;align-items:center;gap:8px;
    padding:10px 13px;border-radius:9px;
    font-size:.84rem;font-weight:650;
    color:var(--n500);text-decoration:none;
    transition:color .15s,background .15s;min-height:44px;
}
.anav-link-m:hover,.anav-link-m.active { color:var(--g700);background:var(--g50) }
.anav-drawer-user {
    display:flex;align-items:center;gap:10px;
    padding:12px 13px;background:var(--g50);
    border-radius:10px;border:1px solid var(--g100);
    margin-bottom:6px;cursor:pointer;
    transition:background .15s;text-decoration:none;
}
.anav-drawer-user:hover { background:var(--g100) }
.anav-drawer-username { font-size:.85rem;font-weight:700;color:var(--n900) }
.anav-drawer-role     { font-size:.7rem;color:var(--n500);margin-top:1px }
.anav-drawer-logout {
    display:flex;align-items:center;justify-content:center;gap:7px;
    padding:11px 13px;border-radius:9px;
    border:1px solid var(--red);background:var(--red-pale);
    color:var(--red);font-size:.85rem;font-weight:700;
    cursor:pointer;width:100%;transition:all .15s;min-height:44px;
}
.anav-drawer-logout:hover { background:var(--red);color:#fff }

/* ── Responsive ── */
@media(max-width:1200px){
    .anav-link { padding:6px 9px;font-size:.70rem }
    .anav-inner { padding:0 16px }
}
@media(max-width:960px){
    .anav-username { display:none }
    .anav-user { padding:4px }
}
@media(max-width:768px){
    .anav-center,.anav-notif,.anav-user,.anav-logout { display:none }
    .anav-hamburger { display:flex }
    .anav-inner { padding:0 14px;gap:0;justify-content:space-between }
}
@media(max-width:480px){
    .anav-brandname { font-size:1rem }
    .anav-inner { padding:0 12px }
}
</style>

@php
    $unread       = auth()->user()->unreadNotifications->count();
    $adminName    = auth()->user()->name ?? 'Admin';
    $adminInitial = strtoupper(substr($adminName, 0, 1));

    $pendingBook    = \App\Models\Booking::where('status', 'pending')->count();
    $pendingRenewal = \App\Models\Renewal::whereIn('status', ['pending_admin', 'pending_payment'])->count();
    $pendingPay     = \App\Models\Invoice::where('status', 'pending_verification')->count();
    $openTkt        = \App\Models\Ticket::whereIn('status', ['open', 'in_progress'])->count();
@endphp

<nav class="anav" id="aNav">
    <div class="anav-inner">

        <a href="{{ route('admin.dashboard') }}" class="anav-brand">
            <div class="anav-brandmark"><i class="bi bi-shield-lock-fill"></i></div>
            <span class="anav-brandname">Urban Kost</span>
            <span class="anav-brandtag">Admin</span>
        </a>

        <div class="anav-center">
            <ul class="anav-links">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="anav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                </li>

                <li class="anav-sep" aria-hidden="true"></li>

                <li>
                    <a href="{{ route('admin.rooms.index') }}"
                       class="anav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>Kamar
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tenants.index') }}"
                       class="anav-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>Penghuni
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="anav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>Daftar Booking
                        @if($pendingBook > 0)
                            <span class="anav-badge">{{ $pendingBook }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.renewals.index') }}"
                       class="anav-link {{ request()->routeIs('admin.renewals.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-repeat"></i>Perpanjangan Sewa
                        @if($pendingRenewal > 0)
                            <span class="anav-badge amber">{{ $pendingRenewal }}</span>
                        @endif
                    </a>
                </li>

                <li class="anav-sep" aria-hidden="true"></li>

                <li>
                    <a href="{{ route('admin.invoices.index') }}"
                       class="anav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i>Invoice & Pembayaran
                        @if($pendingPay > 0)
                            <span class="anav-badge amber">{{ $pendingPay }}</span>
                        @endif
                    </a>
                </li>

                <li class="anav-sep" aria-hidden="true"></li>

                <li>
                    <a href="{{ route('admin.service-catalog.index') }}"
                       class="anav-link {{ request()->routeIs('admin.service-catalog.*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap-fill"></i>Layanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="anav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <i class="bi bi-headset"></i>Keluhan & Laporan Penghuni
                        @if($openTkt > 0)
                            <span class="anav-badge">{{ $openTkt }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}"
                       class="anav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i>Laporan & Analitik
                    </a>
                </li>
            </ul>
        </div>

        <div class="anav-actions">
            <a href="{{ route('notifications.index') }}" class="anav-notif" title="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                @if($unread > 0)<span class="anav-notif-dot"></span>@endif
            </a>

            <a href="{{ route('home') }}" class="anav-notif" title="Lihat Website">
                <i class="bi bi-house-fill"></i>
            </a>

            <a href="{{ route('profile.show') }}" class="anav-user" title="Lihat Profil">
                <div class="anav-avatar">{{ $adminInitial }}</div>
                <span class="anav-username">
                    {{ Str::words($adminName, 1, '') }}
                    <small>Administrator</small>
                </span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="anav-logout" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <button class="anav-hamburger" id="aNavHamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</nav>

<div class="anav-drawer" id="aNavDrawer">
    <div class="anav-drawer-scroll">
        <a href="{{ route('profile.show') }}" class="anav-drawer-user">
            <div class="anav-avatar" style="width:38px;height:38px;font-size:.9rem;flex-shrink:0">{{ $adminInitial }}</div>
            <div style="min-width:0">
                <div class="anav-drawer-username">{{ $adminName }}</div>
                <div class="anav-drawer-role">Administrator</div>
            </div>
            @if($unread > 0)
            <a href="{{ route('notifications.index') }}"
               style="margin-left:auto;flex-shrink:0;width:26px;height:26px;border-radius:50%;
                      background:var(--red);display:flex;align-items:center;
                      justify-content:center;color:#fff;font-size:.6rem;
                      font-weight:800;text-decoration:none">
                {{ $unread }}
            </a>
            @endif
        </a>

        <div class="anav-drawer-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}"
           class="anav-link-m {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>

        <div class="anav-drawer-sep"></div>
        <div class="anav-drawer-label">Manajemen</div>
        <a href="{{ route('admin.rooms.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>Kamar
        </a>
        <a href="{{ route('admin.tenants.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>Penghuni
        </a>
        <a href="{{ route('admin.bookings.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>Daftar Booking
            @if($pendingBook > 0)
                <span class="anav-badge" style="margin-left:auto">{{ $pendingBook }}</span>
            @endif
        </a>
        <a href="{{ route('admin.renewals.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.renewals.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat"></i>Perpanjangan Sewa
            @if($pendingRenewal > 0)
                <span class="anav-badge amber" style="margin-left:auto">{{ $pendingRenewal }}</span>
            @endif
        </a>

        <div class="anav-drawer-sep"></div>
        <div class="anav-drawer-label">Keuangan</div>
        <a href="{{ route('admin.invoices.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>Invoice & Pembayaran
            @if($pendingPay > 0)
                <span class="anav-badge amber" style="margin-left:auto">{{ $pendingPay }}</span>
            @endif
        </a>

        <div class="anav-drawer-sep"></div>
        <div class="anav-drawer-label">Layanan & Laporan</div>
        <a href="{{ route('admin.service-catalog.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.service-catalog.*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i>Layanan
        </a>
        <a href="{{ route('admin.tickets.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
            <i class="bi bi-headset"></i>Keluhan & Laporan Penghuni
            @if($openTkt > 0)
                <span class="anav-badge" style="margin-left:auto">{{ $openTkt }}</span>
            @endif
        </a>
        <a href="{{ route('admin.reports.index') }}"
           class="anav-link-m {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i>Laporan & Analitik
        </a>
        <a href="{{ route('notifications.index') }}"
           class="anav-link-m {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i>Notifikasi
            @if($unread > 0)
                <span class="anav-badge" style="margin-left:auto">{{ $unread }}</span>
            @endif
        </a>
        <a href="{{ route('home') }}" class="anav-link-m">
            <i class="bi bi-house"></i>Lihat Website
        </a>
    </div>

    <div class="anav-drawer-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="anav-drawer-logout">
                <i class="bi bi-box-arrow-right"></i>Keluar dari Akun
            </button>
        </form>
    </div>
</div>

<script>
(function(){
    const nav = document.getElementById('aNav');
    const btn = document.getElementById('aNavHamburger');
    const drw = document.getElementById('aNavDrawer');
    let open  = false;

    window.addEventListener('scroll', () =>
        nav?.classList.toggle('elevated', window.scrollY > 20), { passive:true });

    btn?.addEventListener('click', () => {
        open = !open;
        btn.classList.toggle('open', open);
        drw.classList.toggle('open', open);
        document.body.style.overflow = open ? 'hidden' : '';
    });

    document.addEventListener('click', e => {
        if (open && !nav.contains(e.target) && !drw.contains(e.target)) {
            open = false;
            btn.classList.remove('open');
            drw.classList.remove('open');
            document.body.style.overflow = '';
        }
    });

    drw?.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('click', () => {
            if (!el.classList.contains('anav-drawer-user')) {
                open = false;
                btn.classList.remove('open');
                drw.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    });
})();
</script>