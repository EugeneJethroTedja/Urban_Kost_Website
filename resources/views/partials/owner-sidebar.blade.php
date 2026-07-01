<style>
/* ═══════════════════════════════════════════════════════════════
   URBAN KOST – OWNER NAV  (.onav)
   Design tokens selaras dengan admin / prospective / tenant nav
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
.onav {
    position:sticky;top:0;z-index:999;
    height:var(--nav-h);
    background:#fff;
    border-bottom:1px solid var(--g100);
    box-shadow:0 1px 0 var(--g100),0 2px 20px rgba(34,197,94,.07);
    transition:box-shadow .3s;
    width:100%;overflow:hidden;
}
.onav.elevated { box-shadow:0 4px 28px rgba(34,197,94,.13) }

.onav-inner {
    max-width:1400px;margin:0 auto;
    height:100%;padding:0 24px;
    display:flex;align-items:center;gap:8px;
}

/* ── Brand ── */
.onav-brand {
    display:flex;align-items:center;gap:10px;
    text-decoration:none;flex-shrink:0;margin-right:8px;
}
.onav-brandmark {
    width:36px;height:36px;border-radius:10px;
    background:linear-gradient(145deg,var(--g500),var(--g400));
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.9rem;
    box-shadow:0 3px 10px rgba(34,197,94,.35);
    flex-shrink:0;
}
.onav-brandname {
    font-family:'Fraunces',serif;font-weight:700;font-size:1.12rem;
    color:var(--n900);letter-spacing:-.02em;white-space:nowrap;
}
.onav-brandtag {
    font-size:.58rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;
    color:var(--g700);background:var(--g100);
    border:1px solid var(--g200);
    padding:2px 7px;border-radius:4px;
    margin-left:2px;white-space:nowrap;
}

/* ── Center nav ── */
.onav-center {
    flex:1;display:flex;align-items:center;justify-content:center;
    min-width:0;overflow:visible;
}
.onav-links {
    display:flex;align-items:center;gap:1px;
    list-style:none;
    background:var(--n50);border:1px solid var(--n200);
    border-radius:10px;padding:3px;
    flex-wrap:nowrap;overflow-x:auto;scrollbar-width:none;
    -webkit-overflow-scrolling:touch;
}
.onav-links::-webkit-scrollbar{display:none}
.onav-link {
    display:flex;align-items:center;gap:5px;
    padding:6px 11px;border-radius:7px;
    font-size:.73rem;font-weight:650;
    color:var(--n500);text-decoration:none;
    white-space:nowrap;flex-shrink:0;
    transition:color .15s,background .15s;
}
.onav-link:hover  { color:var(--g600);background:var(--g50) }
.onav-link.active {
    color:var(--g700);background:#fff;
    box-shadow:0 1px 5px rgba(34,197,94,.15);
}
.onav-link i { font-size:.74rem;flex-shrink:0 }

.onav-sep {
    width:1px;height:14px;background:var(--n200);
    margin:0 2px;flex-shrink:0;align-self:center;
}

/* ── Badge ── */
.onav-badge {
    display:inline-flex;align-items:center;justify-content:center;
    min-width:15px;height:15px;padding:0 3px;
    border-radius:999px;background:var(--red);
    color:#fff;font-size:.52rem;font-weight:800;
    line-height:1;flex-shrink:0;
}
.onav-badge.amber { background:var(--amber) }
.onav-badge.green { background:var(--g600) }

/* ── Tax warning pill ── */
.onav-tax-warn {
    display:inline-flex;align-items:center;gap:4px;
    padding:3px 9px;border-radius:999px;
    background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.28);
    color:#92400e;font-size:.59rem;font-weight:700;
    animation:taxPulse 2s ease-in-out infinite;white-space:nowrap;
}
@keyframes taxPulse{0%,100%{opacity:1}50%{opacity:.6}}

/* ── Actions ── */
.onav-actions {
    display:flex;align-items:center;gap:5px;flex-shrink:0;margin-left:8px;
}
.onav-notif {
    position:relative;width:34px;height:34px;
    border-radius:9px;display:flex;align-items:center;justify-content:center;
    color:var(--n500);text-decoration:none;
    transition:background .15s,color .15s;font-size:.88rem;
    border:1px solid var(--n200);flex-shrink:0;
}
.onav-notif:hover { background:var(--g50);color:var(--g600);border-color:var(--g200) }
.onav-notif-dot {
    position:absolute;top:6px;right:6px;
    width:6px;height:6px;border-radius:50%;
    background:var(--red);border:1.5px solid #fff;
}
.onav-avatar {
    width:28px;height:28px;border-radius:50%;
    background:linear-gradient(145deg,var(--g500),var(--g400));
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.68rem;font-weight:800;flex-shrink:0;
    box-shadow:0 2px 6px rgba(34,197,94,.35);
}
.onav-user {
    display:flex;align-items:center;gap:6px;
    padding:4px 10px 4px 5px;border-radius:9px;
    background:var(--g50);border:1px solid var(--g200);
    transition:background .15s,border-color .15s;
    text-decoration:none;flex-shrink:0;
}
.onav-user:hover { background:var(--g100);border-color:var(--g400) }
.onav-username {
    font-size:.73rem;font-weight:700;color:var(--g800);
    max-width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.onav-username small {
    display:block;font-size:.58rem;font-weight:500;
    color:var(--g600);line-height:1;margin-top:1px;
}
.onav-logout {
    width:34px;height:34px;border-radius:9px;
    display:flex;align-items:center;justify-content:center;
    background:transparent;border:1px solid var(--n200);
    color:var(--n500);cursor:pointer;
    transition:all .15s;font-size:.88rem;flex-shrink:0;
}
.onav-logout:hover { border-color:var(--red);color:var(--red);background:var(--red-pale) }

/* ── Hamburger ── */
.onav-hamburger {
    display:none;flex-direction:column;gap:5px;
    width:36px;height:36px;border:1px solid var(--n200);
    border-radius:9px;background:transparent;cursor:pointer;
    align-items:center;justify-content:center;
    transition:all .15s;flex-shrink:0;
}
.onav-hamburger:hover { border-color:var(--g400);background:var(--g50) }
.onav-hamburger span {
    display:block;width:16px;height:1.5px;
    background:var(--n600);border-radius:2px;transition:all .28s ease;
}
.onav-hamburger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg)}
.onav-hamburger.open span:nth-child(2){opacity:0;transform:scaleX(0)}
.onav-hamburger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg)}

/* ── Drawer ── */
.onav-drawer {
    position:fixed;top:var(--nav-h);left:0;right:0;
    background:#fff;border-bottom:1px solid var(--g100);
    box-shadow:0 8px 32px rgba(34,197,94,.10);
    display:none;flex-direction:column;
    z-index:998;
    max-height:calc(100dvh - var(--nav-h));
    overflow-y:auto;overflow-x:hidden;
    overscroll-behavior:contain;
    -webkit-overflow-scrolling:touch;
    padding:14px 18px 32px;gap:3px;
    animation:oNavDrawerIn .22s ease;
}
@keyframes oNavDrawerIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
.onav-drawer.open { display:flex }

.onav-drawer-sep { height:1px;background:var(--n100);margin:6px 0;flex-shrink:0 }
.onav-drawer-label {
    font-size:.62rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;
    color:var(--n400);padding:4px 10px 2px;flex-shrink:0;
}
.onav-drawer-user {
    display:flex;align-items:center;gap:10px;
    padding:12px 13px;background:var(--g50);
    border-radius:10px;border:1px solid var(--g100);
    margin-bottom:6px;text-decoration:none;flex-shrink:0;
}
.onav-drawer-user:hover { background:var(--g100) }
.onav-drawer-username { font-size:.85rem;font-weight:700;color:var(--n900) }
.onav-drawer-role     { font-size:.7rem;color:var(--n500);margin-top:1px }
.onav-drawer-notif-badge {
    width:26px;height:26px;border-radius:50%;
    background:var(--red);
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.6rem;font-weight:800;
    text-decoration:none;flex-shrink:0;
    box-shadow:0 2px 6px rgba(220,38,38,.3);
}
.onav-link-m {
    display:flex;align-items:center;gap:8px;
    padding:10px 13px;border-radius:9px;
    font-size:.84rem;font-weight:650;
    color:var(--n500);text-decoration:none;
    transition:color .15s,background .15s;min-height:44px;flex-shrink:0;
}
.onav-link-m:hover,.onav-link-m.active { color:var(--g700);background:var(--g50) }
.onav-link-m i { color:var(--g500);flex-shrink:0 }
.onav-link-m .onav-badge { margin-left:auto }
.onav-drawer-logout {
    display:flex;align-items:center;justify-content:center;gap:7px;
    padding:11px 13px;border-radius:9px;
    border:1px solid var(--red);background:var(--red-pale);
    color:var(--red);font-size:.85rem;font-weight:700;
    cursor:pointer;width:100%;transition:all .15s;min-height:44px;
    flex-shrink:0;margin-top:4px;
}
.onav-drawer-logout:hover { background:var(--red);color:#fff }

/* ── Responsive ── */
@media(max-width:1200px){
    .onav-link { padding:6px 9px;font-size:.70rem }
    .onav-inner { padding:0 16px }
}
@media(max-width:960px){
    .onav-username { display:none }
    .onav-user { padding:4px }
}
@media(max-width:768px){
    .onav-center,.onav-notif,.onav-user,.onav-logout { display:none }
    .onav-tax-warn { display:none }
    .onav-hamburger { display:flex }
    .onav-inner { padding:0 14px;gap:0;justify-content:space-between }
}
@media(max-width:480px){
    .onav-brandname { font-size:1rem }
    .onav-inner { padding:0 12px }
    .onav-brandtag { display:none }
}
</style>

@php
    $oUnread       = auth()->user()->unreadNotifications->count();
    $oAdminName    = auth()->user()->name ?? 'Owner';
    $oAdminInitial = strtoupper(substr($oAdminName, 0, 1));
    $oPendingBook  = \App\Models\Booking::where('status','pending')->count();
    $oOpenTkt      = \App\Models\Ticket::whereIn('status',['open','in_progress'])->count();
    $oYtdRev       = \App\Models\Invoice::where('status','paid')->whereYear('period_start', now()->year)->sum('total_amount');
    $oTaxWarn      = $oYtdRev >= 400000000;
@endphp

<nav class="onav" id="oNav">
    <div class="onav-inner">

        <a href="{{ route('owner.dashboard') }}" class="onav-brand">
            <div class="onav-brandmark"><i class="bi bi-building-fill-check"></i></div>
            <span class="onav-brandname">Urban Kost</span>
            <span class="onav-brandtag">Owner</span>
        </a>

        <div class="onav-center">
            <ul class="onav-links">
                <li>
                    <a href="{{ route('owner.dashboard') }}"
                       class="onav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                </li>

                <li class="onav-sep" aria-hidden="true"></li>

                <li>
                    <a href="{{ route('owner.admins.index') }}"
                       class="onav-link {{ request()->routeIs('owner.admins.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>Admin
                        @if($oPendingBook > 0)
                            <span class="onav-badge green">{{ $oPendingBook }}</span>
                        @endif
                    </a>
                </li>

                <li class="onav-sep" aria-hidden="true"></li>

                <li>
                    <a href="{{ route('owner.analytics') }}"
                       class="onav-link {{ request()->routeIs('owner.analytics') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i>Laporan &amp; Analitik
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.tax.index') }}"
                       class="onav-link {{ request()->routeIs('owner.tax.*') ? 'active' : '' }}">
                        <i class="bi bi-calculator"></i>Pajak
                        @if($oTaxWarn)
                            <span class="onav-badge amber"></span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <div class="onav-actions">
            <a href="{{ route('notifications.index') }}" class="onav-notif" title="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                @if($oUnread > 0)<span class="onav-notif-dot"></span>@endif
            </a>
            <a href="{{ route('home') }}" class="onav-notif" title="Lihat Website">
                <i class="bi bi-house-fill"></i>
            </a>

            @if($oTaxWarn)
            <span class="onav-tax-warn"> Batas Pajak</span>
            @endif

            <a href="{{ route('profile.show') }}" class="onav-user" style="text-decoration:none">
                <div class="onav-avatar">{{ $oAdminInitial }}</div>
                <span class="onav-username">
                    {{ Str::words($oAdminName, 1, '') }}
                    <small>Owner</small>
                </span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="onav-logout" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <button class="onav-hamburger" id="oNavHamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

<div class="onav-drawer" id="oNavDrawer">
    <a href="{{ route('profile.show') }}" class="onav-drawer-user">
        <div class="onav-avatar" style="width:38px;height:38px;font-size:.9rem;flex-shrink:0">{{ $oAdminInitial }}</div>
        <div style="min-width:0">
            <div class="onav-drawer-username">{{ $oAdminName }}</div>
            <div class="onav-drawer-role">Owner</div>
        </div>
        @if($oUnread > 0)
            <a href="{{ route('notifications.index') }}" class="onav-drawer-notif-badge"
               style="margin-left:auto" onclick="event.stopPropagation()">
                {{ $oUnread > 99 ? '99+' : $oUnread }}
            </a>
        @endif
    </a>

    <div class="onav-drawer-label">Menu Utama</div>
    <a href="{{ route('owner.dashboard') }}"
       class="onav-link-m {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>Dashboard
    </a>

    <div class="onav-drawer-sep"></div>
    <div class="onav-drawer-label">Manajemen Admin</div>
    <a href="{{ route('owner.admins.index') }}"
       class="onav-link-m {{ request()->routeIs('owner.admins.index') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i>Admin
    </a>

    <div class="onav-drawer-sep"></div>
    <div class="onav-drawer-label">Analitik & Laporan</div>
    <a href="{{ route('owner.analytics') }}"
       class="onav-link-m {{ request()->routeIs('owner.analytics') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i>Laporan &amp; Analitik
    </a>
    <a href="{{ route('owner.tax.index') }}"
       class="onav-link-m {{ request()->routeIs('owner.tax.*') ? 'active' : '' }}">
        <i class="bi bi-calculator"></i>Pajak
        @if($oTaxWarn)
            <span class="onav-badge amber"></span>
        @endif
    </a>

    <div class="onav-drawer-sep"></div>
    <div class="onav-drawer-label">Operasional</div>
    <a href="{{ route('notifications.index') }}"
       class="onav-link-m {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i>Notifikasi
        @if($oUnread > 0)
            <span class="onav-badge" style="margin-left:auto">{{ $oUnread > 99 ? '99+' : $oUnread }}</span>
        @endif
    </a>
    <a href="{{ route('home') }}" class="onav-link-m">
        <i class="bi bi-house"></i>Lihat Website
    </a>
    <a href="{{ route('profile.show') }}" class="onav-link-m">
        <i class="bi bi-person-circle"></i>Profil Saya
    </a>

    <div class="onav-drawer-sep"></div>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="onav-drawer-logout">
            <i class="bi bi-box-arrow-right"></i>Keluar dari Akun
        </button>
    </form>
</div>

<script>
(function(){
    const nav = document.getElementById('oNav');
    const btn = document.getElementById('oNavHamburger');
    const drw = document.getElementById('oNavDrawer');
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

    drw?.querySelectorAll('a.onav-link-m').forEach(link => {
        link.addEventListener('click', () => {
            open = false;
            btn.classList.remove('open');
            drw.classList.remove('open');
            document.body.style.overflow = '';
        });
    });
})();
</script>