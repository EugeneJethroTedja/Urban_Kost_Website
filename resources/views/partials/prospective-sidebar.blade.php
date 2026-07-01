<style>
:root {
    --g50:#f0fdf4;--g100:#dcfce7;--g200:#bbf7d0;--g300:#86efac;
    --g400:#4ade80;--g500:#22c55e;--g600:#16a34a;--g700:#15803d;
    --g800:#166534;--g900:#14532d;
    --n0:#ffffff;--n50:#f9fafb;--n100:#f3f4f6;--n200:#e5e7eb;
    --n400:#9ca3af;--n500:#6b7280;--n600:#4b5563;--n900:#111827;
    --accent:#f4a535;--red:#dc2626;--red-pale:#fef2f2;
    --blue:#2563eb;--amber:#f59e0b;
    --nav-h:64px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

.pnav {
    position:sticky;top:0;z-index:999;
    height:var(--nav-h);
    background:rgba(255,255,255,.97);
    backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
    border-bottom:1px solid var(--g100);
    box-shadow:0 1px 0 var(--g100),0 2px 20px rgba(34,197,94,.07);
    transition:box-shadow .3s;
}
.pnav.elevated { box-shadow:0 4px 28px rgba(34,197,94,.13) }

.pnav-inner {
    max-width:1280px;margin:0 auto;
    height:100%;padding:0 24px;
    display:flex;align-items:center;gap:0;
    justify-content:space-between;
}

.pnav-brand {
    display:flex;align-items:center;gap:10px;
    text-decoration:none;flex-shrink:0;
}
.pnav-brandmark {
    width:36px;height:36px;border-radius:10px;
    background:linear-gradient(145deg,var(--g500),var(--g400));
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.9rem;
    box-shadow:0 3px 10px rgba(34,197,94,.35);
    flex-shrink:0;
}
.pnav-brandname {
    font-family:'Fraunces',serif;font-weight:700;font-size:1.12rem;
    color:var(--n900);letter-spacing:-.02em;
    white-space:nowrap;
}

.pnav-center {
    position:absolute;left:50%;transform:translateX(-50%);
    display:flex;align-items:center;gap:2px;
}
.pnav-links {
    display:flex;align-items:center;gap:2px;
    list-style:none;margin:0;padding:0;
    background:var(--n50);border:1px solid var(--n200);
    border-radius:12px;padding:4px;
}
.pnav-link {
    display:flex;align-items:center;gap:6px;
    padding:7px 14px;border-radius:8px;
    font-size:.78rem;font-weight:600;
    color:var(--n500);text-decoration:none;
    white-space:nowrap;
    transition:color .15s,background .15s;
}
.pnav-link:hover { color:var(--g600);background:var(--g50) }
.pnav-link.active {
    color:var(--g700);background:#fff;
    box-shadow:0 1px 4px rgba(34,197,94,.15);
}
.pnav-link i { font-size:.8rem;flex-shrink:0 }
.pnav-badge {
    display:inline-flex;align-items:center;justify-content:center;
    min-width:17px;height:17px;padding:0 4px;
    border-radius:999px;background:var(--red);
    color:#fff;font-size:.58rem;font-weight:800;
    line-height:1;flex-shrink:0;
}
.pnav-badge.amber { background:var(--accent) }

.pnav-actions {
    display:flex;align-items:center;gap:6px;
    flex-shrink:0;
}
.pnav-notif {
    position:relative;width:36px;height:36px;
    border-radius:9px;display:flex;align-items:center;justify-content:center;
    color:var(--n500);text-decoration:none;
    transition:background .15s,color .15s;
    font-size:.95rem;
}
.pnav-notif:hover { background:var(--g50);color:var(--g600) }
.pnav-notif-dot {
    position:absolute;top:7px;right:7px;
    width:7px;height:7px;border-radius:50%;
    background:var(--red);border:1.5px solid #fff;
}
.pnav-avatar {
    width:32px;height:32px;border-radius:50%;
    background:linear-gradient(145deg,var(--g500),var(--g400));
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.78rem;font-weight:800;
    flex-shrink:0;
    box-shadow:0 2px 8px rgba(34,197,94,.3);
}
.pnav-user {
    display:flex;align-items:center;gap:7px;
    padding:5px 11px 5px 6px;border-radius:9px;
    background:var(--g50);border:1px solid var(--g200);
    text-decoration:none;
}
.pnav-username {
    font-size:.78rem;font-weight:700;color:var(--g800);
    max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.pnav-logout {
    width:34px;height:34px;border-radius:9px;
    display:flex;align-items:center;justify-content:center;
    background:transparent;border:1px solid var(--n200);
    color:var(--n500);cursor:pointer;
    transition:all .15s;font-size:.9rem;
}
.pnav-logout:hover {
    border-color:var(--red);color:var(--red);background:var(--red-pale);
}

.pnav-hamburger {
    display:none;flex-direction:column;gap:5px;
    width:36px;height:36px;border:1px solid var(--n200);
    border-radius:9px;background:transparent;cursor:pointer;
    align-items:center;justify-content:center;
    transition:all .15s;flex-shrink:0;
}
.pnav-hamburger:hover { border-color:var(--g400);background:var(--g50) }
.pnav-hamburger span {
    display:block;width:16px;height:1.5px;
    background:var(--n600);border-radius:2px;
    transition:all .28s ease;
}
.pnav-hamburger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg)}
.pnav-hamburger.open span:nth-child(2){opacity:0;transform:scaleX(0)}
.pnav-hamburger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg)}

.pnav-drawer {
    position:fixed;top:var(--nav-h);left:0;right:0;
    background:#fff;border-bottom:1px solid var(--g100);
    box-shadow:0 8px 32px rgba(34,197,94,.12);
    padding:12px 16px 16px;
    display:none;flex-direction:column;gap:4px;
    z-index:998;
    animation:drawerIn .22s ease;
}
@keyframes drawerIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
.pnav-drawer.open { display:flex }
.pnav-drawer .pnav-link-m {
    display:flex;align-items:center;gap:8px;
    padding:10px 14px;border-radius:10px;
    font-size:.85rem;font-weight:600;
    color:var(--n500);text-decoration:none;
    transition:color .15s,background .15s;
}
.pnav-drawer .pnav-link-m:hover,
.pnav-drawer .pnav-link-m.active { color:var(--g600);background:var(--g50) }
.pnav-drawer-sep { height:1px;background:var(--g100);margin:6px 0 }
.pnav-drawer-user {
    display:flex;align-items:center;gap:10px;
    padding:12px 14px;background:var(--g50);
    border-radius:10px;border:1px solid var(--g100);
    margin-bottom:4px;text-decoration:none;
}
.pnav-drawer-username { font-size:.85rem;font-weight:700;color:var(--n900) }
.pnav-drawer-email    { font-size:.72rem;color:var(--n500);margin-top:1px }
.pnav-drawer-logout {
    display:flex;align-items:center;justify-content:center;gap:7px;
    padding:9px 14px;border-radius:10px;border:1px solid var(--n200);
    background:#fff;color:var(--red);font-size:.82rem;font-weight:700;
    cursor:pointer;width:100%;transition:all .15s;
}
.pnav-drawer-logout:hover { background:var(--red-pale);border-color:var(--red) }

@media(max-width:1024px){
    .pnav-center { position:static;transform:none }
    .pnav-inner  { gap:12px }
}
@media(max-width:900px){
    .pnav-username { display:none }
    .pnav-user { padding:4px;border-radius:50%;background:transparent;border-color:transparent }
}
@media(max-width:768px){
    .pnav-center    { display:none }
    .pnav-hamburger { display:flex }
    .pnav-logout    { display:none }
}
@media(max-width:480px){
    .pnav-brandname { font-size:1rem }
    .pnav-inner     { padding:0 14px }
}
</style>

@php
    $unread      = auth()->user()->unreadNotifications->count();
    $unpaidNav   = isset($invoices) ? $invoices->whereIn('status',['unpaid','overdue'])->count() : 0;
    $activeWLNav = isset($waitingLists) ? $waitingLists->whereIn('status',['waiting','notified'])->count() : 0;
    $userName    = auth()->user()->name ?? 'Pengguna';
    $userInitial = strtoupper(substr($userName, 0, 1));
@endphp

<nav class="pnav" id="pNav">
    <div class="pnav-inner">

        <a href="{{ route('home') }}" class="pnav-brand">
            <div class="pnav-brandmark"><i class="bi bi-house-heart-fill"></i></div>
            <span class="pnav-brandname">Urban Kost</span>
        </a>

        <div class="pnav-center">
            <ul class="pnav-links">
                <li>
                    <a href="{{ route('prospective.dashboard') }}"
                       class="pnav-link {{ request()->routeIs('prospective.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('prospective.bookings') }}"
                       class="pnav-link {{ request()->routeIs('prospective.bookings*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check-fill"></i>Riwayat Booking
                    </a>
                </li>
                <li>
                    <a href="{{ route('prospective.waiting-list.index') }}"
                       class="pnav-link {{ request()->routeIs('prospective.waiting-list*') ? 'active' : '' }}">
                        <i class="bi bi-list-ol"></i>Riwayat Waiting List
                        @if($activeWLNav > 0)
                            <span class="pnav-badge amber">{{ $activeWLNav }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('prospective.invoices.index') }}"
                       class="pnav-link {{ request()->routeIs('prospective.invoices*') ? 'active' : '' }}">
                        <i class="bi bi-receipt-cutoff"></i>Tagihan
                        @if($unpaidNav > 0)
                            <span class="pnav-badge">{{ $unpaidNav }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" class="pnav-link">
                        <i class="bi bi-house-fill"></i>Beranda
                    </a>
                </li>
            </ul>
        </div>

        <div class="pnav-actions">

            <a href="{{ route('notifications.index') }}" class="pnav-notif" title="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                @if($unread > 0)<span class="pnav-notif-dot"></span>@endif
            </a>

            <a href="{{ route('profile.show') }}" class="pnav-user">
                <div class="pnav-avatar">{{ $userInitial }}</div>
                <span class="pnav-username">{{ Str::words($userName, 1, '') }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="pnav-logout" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>

            <button class="pnav-hamburger" id="pNavHamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</nav>

<div class="pnav-drawer" id="pNavDrawer">

    <a href="{{ route('profile.show') }}" class="pnav-drawer-user">
        <div class="pnav-avatar" style="width:38px;height:38px;font-size:.88rem">{{ $userInitial }}</div>
        <div style="flex:1;min-width:0">
            <div class="pnav-drawer-username">{{ $userName }}</div>
            <div class="pnav-drawer-email">{{ auth()->user()->email }}</div>
        </div>
        @if($unread > 0)
        <a href="{{ route('notifications.index') }}"
           style="width:24px;height:24px;border-radius:50%;background:var(--red);
                  display:flex;align-items:center;justify-content:center;
                  color:#fff;font-size:.62rem;font-weight:800;
                  text-decoration:none;flex-shrink:0">
            {{ $unread > 9 ? '9+' : $unread }}
        </a>
        @endif
    </a>

    <a href="{{ route('prospective.dashboard') }}"
       class="pnav-link-m {{ request()->routeIs('prospective.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>Dashboard
    </a>
    <a href="{{ route('prospective.bookings') }}"
       class="pnav-link-m {{ request()->routeIs('prospective.bookings*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check-fill"></i>Riwayat Booking
    </a>
    <a href="{{ route('prospective.waiting-list.index') }}"
       class="pnav-link-m {{ request()->routeIs('prospective.waiting-list*') ? 'active' : '' }}">
        <i class="bi bi-list-ol"></i>Riwayat Waiting List
        @if($activeWLNav > 0)
            <span class="pnav-badge amber" style="margin-left:auto">{{ $activeWLNav }}</span>
        @endif
    </a>
    <a href="{{ route('prospective.invoices.index') }}"
       class="pnav-link-m {{ request()->routeIs('prospective.invoices*') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i>Tagihan
        @if($unpaidNav > 0)
            <span class="pnav-badge" style="margin-left:auto">{{ $unpaidNav }}</span>
        @endif
    </a>
    <a href="{{ route('home') }}" class="pnav-link-m">
        <i class="bi bi-house-fill"></i>Beranda
    </a>

    <div class="pnav-drawer-sep"></div>

    <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="pnav-drawer-logout">
            <i class="bi bi-box-arrow-right"></i> Keluar dari Akun
        </button>
    </form>
</div>

<script>
(function(){
    const nav = document.getElementById('pNav');
    const btn = document.getElementById('pNavHamburger');
    const drw = document.getElementById('pNavDrawer');
    let open  = false;

    window.addEventListener('scroll', () =>
        nav?.classList.toggle('elevated', window.scrollY > 20)
    );

    btn?.addEventListener('click', () => {
        open = !open;
        btn.classList.toggle('open', open);
        drw.classList.toggle('open', open);
    });

    document.addEventListener('click', e => {
        if (open && !nav.contains(e.target) && !drw.contains(e.target)) {
            open = false;
            btn.classList.remove('open');
            drw.classList.remove('open');
        }
    });
})();
</script>