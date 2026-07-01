<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Riwayat Booking — Urban Kost</title>
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
    --amber:       #f59e0b;
    --amber-pale:  #fffbeb;
    --amber-200:   #fde68a;
    --amber-600:   #d97706;
    --blue:        #2563eb;
    --blue-pale:   #eff6ff;
    --blue-200:    #bfdbfe;
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --red-200:     #fecaca;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
    --r-card:    16px;
    --r-pill:    999px;
    --safe-top:    env(safe-area-inset-top, 0px);
    --safe-bottom: env(safe-area-inset-bottom, 0px);
    --safe-left:   env(safe-area-inset-left, 0px);
    --safe-right:  env(safe-area-inset-right, 0px);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--green-50);
    color: var(--neutral-900);
    min-height: 100vh;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    padding-left: var(--safe-left);
    padding-right: var(--safe-right);
    padding-bottom: var(--safe-bottom);
}
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--green-50); }
::-webkit-scrollbar-thumb { background: var(--green-400); border-radius: 3px; }
.page-bg {
    position: fixed; inset: 0; z-index: -1; overflow: hidden;
    background: var(--green-50);
}
.page-bg::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.10) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.07) 0%, transparent 50%),
        radial-gradient(circle at 70% 10%, rgba(78,201,138,.06) 0%, transparent 40%);
}
.page-bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
}
.page {
    max-width: 1000px; margin: 0 auto;
    padding: 36px 24px 80px;
    padding-left:  max(24px, var(--safe-left));
    padding-right: max(24px, var(--safe-right));
}
.page-hero {
    position: relative; overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 30%, var(--green-700) 65%, var(--green-500) 100%);
    padding: 36px 32px;
    margin-bottom: 28px;
    box-shadow: 0 8px 40px rgba(14,63,39,.35), 0 2px 8px rgba(0,0,0,.1);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 20px;
}
.page-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 80% 50%, rgba(134,217,174,.18) 0%, transparent 50%),
        radial-gradient(circle at 10% 80%, rgba(78,201,138,.12) 0%, transparent 40%);
    pointer-events: none;
}
.hero-left { position: relative; z-index: 1; flex: 1; min-width: 0; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    padding: 5px 14px; border-radius: 100px;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82);
    margin-bottom: 14px;
}
.hero-eyebrow .dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--green-300); flex-shrink: 0;
}
.hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.5rem, 4vw, 2.2rem); font-weight: 700;
    color: #fff; line-height: 1.15;
    letter-spacing: -.025em; margin-bottom: 8px;
}
.hero-sub { font-size: .88rem; color: rgba(255,255,255,.65); line-height: 1.5; max-width: 400px; }
.hero-right {
    display: flex; flex-direction: column; align-items: flex-end;
    gap: 14px; position: relative; z-index: 1; flex-shrink: 0;
}
.hero-stats { display: flex; gap: 10px; flex-wrap: wrap; }
.hero-stat {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 12px; padding: 10px 16px;
    text-align: center; min-width: 68px;
}
.hero-stat-num {
    font-family: 'Fraunces', serif;
    font-size: 1.5rem; font-weight: 700;
    color: #fff; line-height: 1;
}
.hero-stat-label {
    font-size: .6rem; color: rgba(255,255,255,.65);
    margin-top: 3px; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
}
.filter-bar {
    display: flex; gap: 7px; margin-bottom: 20px; flex-wrap: wrap;
}
.filter-tab {
    padding: 8px 16px; border-radius: var(--r-pill);
    font-size: .75rem; font-weight: 600;
    border: 1px solid var(--neutral-200); color: var(--neutral-500);
    background: var(--neutral-0); cursor: pointer; transition: all .2s;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    white-space: nowrap;
}
.filter-tab:hover { border-color: var(--green-500); color: var(--green-600); }
.filter-tab.active { border-color: var(--green-500); color: var(--green-600); background: var(--green-100); }
.ftab-count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 20px; height: 20px; border-radius: 99px;
    background: var(--green-500); color: #fff; font-size: .6rem; font-weight: 700; padding: 0 5px;
}
.filter-tab:not(.active) .ftab-count { background: var(--neutral-200); color: var(--neutral-500); }
.booking-card {
    background: var(--neutral-0);
    border-radius: var(--r-card);
    border: 1px solid var(--green-100);
    box-shadow: var(--shadow-sm);
    overflow: hidden; margin-bottom: 18px;
    transition: box-shadow .25s, transform .25s, border-color .25s;
    animation: slideUp .35s ease both;
    min-width: 0; width: 100%;
}
.booking-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: var(--green-200); }
.booking-card.status-penghuni { border-left: 4px solid var(--green-500); }
.booking-card.status-pending  { border-left: 4px solid var(--amber); }
.booking-card.status-calon    { border-left: 4px solid var(--blue); }
.booking-card.status-nonaktif { border-left: 4px solid var(--neutral-200); opacity: .88; }
.booking-card.status-rejected { border-left: 4px solid var(--red); }
@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.card-header-strip {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px 14px 18px;
    border-bottom: 1px solid var(--neutral-100);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    flex-wrap: wrap; gap: 10px;
}
.card-header-left {
    display: flex; align-items: center; gap: 12px;
    flex-wrap: wrap; flex: 1; min-width: 0;
}
.booking-code-big {
    font-family: 'Fraunces', serif;
    font-size: 1.05rem; font-weight: 700; color: var(--green-600);
    letter-spacing: .02em; word-break: break-all;
}
.type-pill {
    font-size: .6rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; padding: 3px 10px; border-radius: 6px;
    white-space: nowrap; flex-shrink: 0;
}
.tp-advance { background: var(--blue-pale); color: var(--blue); }
.tp-regular { background: var(--green-100); color: var(--green-700); }
.card-header-right {
    display: flex; align-items: center; gap: 10px; flex-shrink: 0;
}
.created-label { font-size: .68rem; color: var(--neutral-400); text-align: right; line-height: 1.5; }
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: var(--r-pill);
    font-size: .68rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
}
.status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.sb-penghuni  { background: var(--green-100);  color: var(--green-700); }
.sb-penghuni .status-dot  { background: var(--green-500); }
.sb-pending   { background: var(--amber-pale); color: #92400e; }
.sb-pending .status-dot   { background: var(--amber); }
.sb-calon     { background: var(--blue-pale);  color: var(--blue); }
.sb-calon .status-dot     { background: var(--blue); }
.sb-rejected  { background: var(--red-pale);   color: var(--red); }
.sb-rejected .status-dot  { background: var(--red); }
.sb-nonaktif  { background: var(--neutral-100); color: var(--neutral-500); }
.sb-nonaktif .status-dot  { background: var(--neutral-400); }
.card-body-grid {
    display: grid;
    grid-template-columns: 130px 1fr;
    min-height: 180px;
}
.room-thumb {
    background: linear-gradient(145deg, var(--green-900), var(--green-600));
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.2); font-size: 2.2rem;
    position: relative; overflow: hidden; flex-shrink: 0;
}
.room-thumb img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.room-num-pill {
    position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%);
    background: rgba(0,0,0,.55); color: #fff;
    font-size: .6rem; font-weight: 700;
    padding: 3px 10px; border-radius: 6px; letter-spacing: .04em; white-space: nowrap;
}
.card-content { padding: 18px 20px; min-width: 0; overflow: hidden; }
.room-name { font-size: 1rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 2px; word-break: break-word; }
.room-meta { font-size: .72rem; color: var(--neutral-400); margin-bottom: 14px; }
.sect-title {
    font-size: .63rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .09em; color: var(--neutral-400);
    margin-bottom: 8px; display: flex; align-items: center; gap: 6px;
}
.sect-title i { color: var(--green-500); font-size: .65rem; }
.sect-title::after { content: ''; flex: 1; height: 1px; background: var(--neutral-100); }
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 8px; margin-bottom: 14px;
}
.info-cell {
    background: var(--green-50); border: 1px solid var(--green-100);
    border-radius: 9px; padding: 9px 12px; min-width: 0; overflow: hidden;
}
.info-cell-label {
    font-size: .6rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 3px;
    display: flex; align-items: center; gap: 4px;
}
.info-cell-label i { font-size: .65rem; color: var(--green-500); }
.info-cell-val {
    font-size: .82rem; font-weight: 600; color: var(--neutral-700);
    line-height: 1.3; word-break: break-word; overflow-wrap: break-word;
}
.info-cell-val.highlight { color: var(--green-700); font-size: .9rem; font-weight: 700; }
.timeline-wrap {
    background: var(--green-50); border: 1px solid var(--green-100);
    border-radius: 10px; padding: 12px 14px; margin-bottom: 14px;
}
.timeline-row { display: flex; align-items: center; gap: 0; }
.tl-point { text-align: center; flex: 1; position: relative; }
.tl-dot {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 6px; font-size: .78rem;
}
.tl-dot.start { background: var(--green-100); color: var(--green-600); }
.tl-dot.end   { background: var(--blue-pale);  color: var(--blue); }
.tl-dot.ended { background: var(--neutral-100); color: var(--neutral-400); }
.tl-date { font-size: .74rem; font-weight: 700; color: var(--neutral-800); }
.tl-type { font-size: .65rem; color: var(--neutral-400); margin-top: 2px; }
.tl-line {
    flex: 1; height: 2px;
    background: linear-gradient(90deg, var(--green-500), var(--blue));
    border-radius: 2px; margin: 0 8px 20px; align-self: center;
    max-width: 80px; position: relative;
}
.tl-line.ended { background: var(--neutral-200); }
.tl-dur {
    position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
    white-space: nowrap; background: var(--neutral-0);
    border: 1px solid var(--neutral-200); border-radius: 50px;
    padding: 1px 8px; font-size: .63rem; font-weight: 700;
    color: var(--neutral-500); box-shadow: var(--shadow-sm);
}
.expiry-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .68rem; font-weight: 600;
    padding: 4px 10px; border-radius: var(--r-pill); border: 1px solid;
    margin-top: 10px;
}
.expiry-chip.urgent { background: var(--red-pale);   border-color: var(--red-200);   color: var(--red); }
.expiry-chip.warn   { background: var(--amber-pale); border-color: var(--amber-200); color: #92400e; }
.expiry-chip.ok     { background: var(--green-100);  border-color: var(--green-200); color: var(--green-700); }
.note-box {
    border-radius: 9px; padding: 10px 13px;
    font-size: .76rem; line-height: 1.6;
    display: flex; gap: 9px; align-items: flex-start;
    margin-bottom: 10px; word-break: break-word;
    border: 1px solid;
}
.note-box i { flex-shrink: 0; margin-top: 2px; }
.note-rejected { background: var(--red-pale);   border-color: var(--red-200);   color: #7f1d1d; }
.note-approved { background: var(--green-100);  border-color: var(--green-200); color: var(--green-800); }
.note-info     { background: var(--blue-pale);  border-color: var(--blue-200);  color: #1e3a5f; }
.note-warning  { background: var(--amber-pale); border-color: var(--amber-200); color: #78350f; }
.card-footer-actions {
    display: flex; align-items: center; justify-content: flex-end;
    gap: 8px; padding: 12px 20px;
    border-top: 1px solid var(--neutral-100);
    background: linear-gradient(135deg, var(--green-50), var(--neutral-0));
    flex-wrap: wrap;
}
.uk-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    padding: 7px 14px; border-radius: 9px; font-size: .72rem; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .18s; white-space: nowrap;
}
.uk-btn-green { background: var(--green-500); color: #fff; box-shadow: 0 2px 8px rgba(42,179,107,.25); }
.uk-btn-green:hover { background: var(--green-600); color: #fff; }
.uk-btn-amber { background: var(--amber); color: #fff; }
.uk-btn-amber:hover { background: var(--amber-600); color: #fff; }
.uk-btn-outline { background: transparent; border: 1px solid var(--green-200); color: var(--neutral-600); }
.uk-btn-outline:hover { border-color: var(--green-400); color: var(--green-700); background: var(--green-50); }
.submitted-meta {
    display: flex; align-items: center; gap: 5px;
    font-size: .71rem; color: var(--neutral-400); font-weight: 500;
}
.submitted-meta i { font-size: .7rem; }
.empty-wrap {
    background: var(--neutral-0); border-radius: var(--r-card);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-sm);
    text-align: center; padding: 72px 24px;
}
.empty-icon-ring {
    width: 80px; height: 80px; border-radius: 50%;
    background: var(--green-100); border: 2px solid var(--green-300);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 1.8rem; color: var(--green-600);
}
.empty-title { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700; margin-bottom: 8px; letter-spacing: -.01em; }
.empty-sub { font-size: .8rem; color: var(--neutral-400); max-width: 300px; margin: 0 auto 22px; line-height: 1.65; }
.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green-500); color: #fff;
    font-weight: 600; font-size: .88rem; padding: 12px 26px;
    border-radius: var(--r-pill); text-decoration: none; border: none; cursor: pointer;
    box-shadow: 0 4px 16px rgba(42,179,107,.28); transition: transform .2s, box-shadow .2s;
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(42,179,107,.35); color: #fff; }
.pagi-wrap { margin-top: 24px; display: flex; justify-content: center; flex-wrap: wrap; gap: 4px; }
.legend-card {
    background: var(--neutral-0); border-radius: var(--r-card);
    border: 1px solid var(--green-100); padding: 18px 24px; margin-top: 24px;
    box-shadow: var(--shadow-sm);
}
.legend-head {
    font-size: .63rem; font-weight: 600; letter-spacing: .09em;
    text-transform: uppercase; color: var(--neutral-400); margin-bottom: 12px;
}
.legend-grid { display: flex; flex-wrap: wrap; gap: 10px 24px; }
.legend-item { display: flex; align-items: center; gap: 7px; font-size: .75rem; }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
@media (max-width: 1024px) {
    .page { max-width: 100%; padding: 28px 20px 72px; }
    .info-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
}
@media (max-width: 768px) {
    .page { padding: 20px 16px 64px; }
    .page-hero { padding: 24px 22px; gap: 16px; }
    .hero-title { font-size: 1.6rem; }
    .hero-right { align-items: flex-start; }
    .card-header-strip { padding: 12px 16px; gap: 8px; }
    .card-header-left { gap: 8px; }
    .booking-code-big { font-size: .95rem; }
    .created-label { font-size: .64rem; }
    .card-body-grid { grid-template-columns: 100px 1fr; min-height: 160px; }
    .card-content { padding: 14px 16px; }
    .room-name { font-size: .92rem; }
    .info-grid { grid-template-columns: repeat(2, 1fr); gap: 7px; }
    .info-cell { padding: 8px 10px; }
    .info-cell-val { font-size: .78rem; }
    .info-cell-val.highlight { font-size: .84rem; }
    .card-footer-actions { justify-content: flex-start; padding: 10px 16px; }
    .legend-grid { flex-direction: column; gap: 8px; }
    .legend-item { font-size: .72rem; }
    .tl-line { max-width: 50px; }
}
@media (max-width: 640px) {
    .page { padding: 16px 12px 60px; }
    .page-hero { flex-direction: column; align-items: flex-start; padding: 20px 18px; gap: 14px; }
    .hero-title { font-size: 1.4rem; }
    .hero-right { width: 100%; flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: wrap; }
    .hero-stats { gap: 8px; }
    .hero-stat { padding: 8px 12px; min-width: 56px; }
    .hero-stat-num { font-size: 1.25rem; }
    .filter-bar {
        flex-wrap: nowrap; overflow-x: auto; padding-bottom: 4px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .filter-bar::-webkit-scrollbar { display: none; }
    .filter-tab { flex-shrink: 0; }
    .card-header-strip { flex-direction: column; align-items: flex-start; padding: 12px 14px; gap: 6px; }
    .card-header-right { width: 100%; justify-content: space-between; }
    .card-header-left { width: 100%; }
    .created-label { text-align: left; }
    .card-body-grid { grid-template-columns: 80px 1fr; min-height: 140px; }
    .card-content { padding: 12px 14px; }
    .card-footer-actions { flex-direction: column; align-items: stretch; padding: 10px 14px; gap: 7px; }
    .uk-btn { justify-content: center; width: 100%; padding: 10px 14px; font-size: .76rem; }
    .tl-line { max-width: 30px; }
}
@media (max-width: 480px) {
    .page { padding: 12px 10px 56px; }
    .hero-stats { display: none; }
    .page-hero { padding: 18px 16px; }
    .hero-title { font-size: 1.25rem; }
    .hero-sub { font-size: .75rem; }
    .hero-right { width: 100%; }
    .card-body-grid { display: flex; flex-direction: column; }
    .room-thumb { min-height: 90px; max-height: 110px; border-radius: 0; width: 100%; }
    .card-content { padding: 12px 12px; }
    .info-grid { grid-template-columns: 1fr 1fr; gap: 6px; }
    .info-cell { padding: 7px 9px; }
    .info-cell-label { font-size: .58rem; }
    .info-cell-val { font-size: .76rem; }
    .info-cell-val.highlight { font-size: .82rem; }
    .booking-code-big { font-size: .88rem; }
    .card-header-left { gap: 6px; }
    .type-pill { font-size: .56rem; padding: 2px 8px; }
    .status-badge { font-size: .62rem; padding: 4px 10px; }
    .sect-title { font-size: .6rem; }
    .note-box { font-size: .72rem; padding: 9px 11px; }
    .legend-card { padding: 14px 16px; }
    .legend-item { font-size: .68rem; }
}
@media (max-width: 360px) {
    .page { padding: 10px 8px 52px; }
    .page-hero { padding: 14px 12px; border-radius: 14px; }
    .hero-title { font-size: 1.1rem; }
    .booking-card { border-radius: 12px; margin-bottom: 12px; }
    .info-grid { grid-template-columns: 1fr; }
    .card-header-strip { padding: 10px 12px; }
    .booking-code-big { font-size: .8rem; }
    .room-name { font-size: .85rem; }
    .filter-tab { padding: 6px 12px; font-size: .68rem; }
}
@media (max-height: 500px) and (orientation: landscape) {
    .page-hero { padding: 14px 20px; }
    .hero-title { font-size: 1.2rem; }
    .hero-stats { display: none; }
    .room-thumb { min-height: 60px; }
}
@media print {
    .page-bg { display: none; }
    .booking-card { break-inside: avoid; box-shadow: none; }
    .card-footer-actions { display: none; }
    .filter-bar { display: none; }
}

.hero-title em {
    font-style: italic;
    font-weight: 400;
    color: rgba(255,255,255,.72);
}
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.tenant-sidebar')

<div class="page">

    <div class="page-hero">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                Riwayat Booking
            </div>
            <h1 class="hero-title">Riwayat <em>Booking</em></h1>
            <p class="hero-sub">Semua riwayat pemesanan dan hunian kamar Anda</p>
        </div>
        <div class="hero-right">
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">{{ $allCount }}</div>
                    <div class="hero-stat-label">Total</div>
                </div>
                @if($countPenghuni)
                <div class="hero-stat">
                    <div class="hero-stat-num" style="color:#86d9ae">{{ $countPenghuni }}</div>
                    <div class="hero-stat-label">Aktif</div>
                </div>
                @endif
                @if($countPending)
                <div class="hero-stat">
                    <div class="hero-stat-num" style="color:#fde68a">{{ $countPending }}</div>
                    <div class="hero-stat-label">Pending</div>
                </div>
                @endif
                @if($countNonaktif)
                <div class="hero-stat">
                    <div class="hero-stat-num" style="color:rgba(255,255,255,.5)">{{ $countNonaktif }}</div>
                    <div class="hero-stat-label">Selesai</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($allCount > 0)
    <div class="filter-bar">
        <a href="{{ route('tenant.bookings') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
            Semua <span class="ftab-count">{{ $allCount }}</span>
        </a>
        @if($countPenghuni)
        <a href="{{ route('tenant.bookings') }}?status=penghuni"
           class="filter-tab {{ request('status') === 'penghuni' ? 'active' : '' }}">
            <i class="bi bi-house-check" style="font-size:.72rem"></i>
            Aktif <span class="ftab-count">{{ $countPenghuni }}</span>
        </a>
        @endif
        @if($countPending)
        <a href="{{ route('tenant.bookings') }}?status=pending"
           class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">
            <i class="bi bi-hourglass-split" style="font-size:.72rem"></i>
            Pending <span class="ftab-count">{{ $countPending }}</span>
        </a>
        @endif
        @if($countNonaktif)
        <a href="{{ route('tenant.bookings') }}?status=nonaktif"
           class="filter-tab {{ request('status') === 'nonaktif' ? 'active' : '' }}">
            <i class="bi bi-house-x" style="font-size:.72rem"></i>
            Selesai <span class="ftab-count">{{ $countNonaktif }}</span>
        </a>
        @endif
        @if($countRejected)
        <a href="{{ route('tenant.bookings') }}?status=rejected"
           class="filter-tab {{ request('status') === 'rejected' ? 'active' : '' }}">
            <i class="bi bi-x-circle" style="font-size:.72rem"></i>
            Ditolak <span class="ftab-count">{{ $countRejected }}</span>
        </a>
        @endif
    </div>
    @endif

    @forelse ($bookings as $b)
    @php
        $latestPaidInv = ($paidInvMap[$b->id] ?? collect())->first();
        $tStatus       = $tStatusMap[$b->id] ?? 'nonaktif';

        $statusLabel = match($tStatus) {
            'penghuni' => 'Penghuni Aktif',
            'pending'  => 'Menunggu Review',
            'calon'    => 'Calon Penghuni',
            'rejected' => 'Ditolak',
            default    => 'Sewa Selesai',
        };

        $checkOutDate = ($b->check_in_date && $b->duration_months)
            ? $b->check_in_date->copy()->addMonths($b->duration_months)->format('d M Y')
            : (($latestPaidInv && $latestPaidInv->period_end)
                ? $latestPaidInv->period_end->format('d M Y')
                : ($b->check_out_date ? $b->check_out_date->format('d M Y') : '—'));

        $isAdvance = method_exists($b, 'isAdvance')
            ? $b->isAdvance()
            : ($b->booking_type === 'advance');

        $daysLeft = null;
        if ($tStatus === 'penghuni' && $b->check_in_date && $b->duration_months) {
            $endDate  = $b->check_in_date->copy()->addMonths($b->duration_months)->startOfDay();
            $daysLeft = (int) now()->startOfDay()->diffInDays($endDate, false);
        }
    @endphp

    <div class="booking-card status-{{ $tStatus }}" style="animation-delay:{{ $loop->index * 0.06 }}s">
        <div class="card-header-strip">
            <div class="card-header-left">
                <span class="booking-code-big">{{ $b->booking_code }}</span>
                @if($isAdvance)
                    <span class="type-pill tp-advance">Advance</span>
                @else
                    <span class="type-pill tp-regular">Regular</span>
                @endif
                <span class="status-badge sb-{{ $tStatus }}">
                    <span class="status-dot"></span> {{ $statusLabel }}
                </span>
            </div>
            <div class="card-header-right">
                <div class="created-label">
                    Diajukan<br>
                    <strong style="color:var(--neutral-700)">{{ $b->created_at->format('d M Y') }}</strong><br>
                </div>
            </div>
        </div>

        <div class="card-body-grid">
            <div class="room-thumb">
                @if($b->room && $b->room->image_path)
                    <img src="{{ asset('storage/'.$b->room->image_path) }}" alt="Foto kamar {{ $b->room->room_number }}">
                @else
                    <i class="bi bi-door-open"></i>
                @endif
                <div class="room-num-pill">No.{{ $b->room ? $b->room->room_number : '?' }}</div>
            </div>

            <div class="card-content">
                <div class="room-name">{{ ($b->room && $b->room->room_name) ? $b->room->room_name : 'Kamar '.($b->room ? $b->room->room_number : '-') }}</div>
                <div class="room-meta">
                    No. {{ $b->room ? $b->room->room_number : '-' }}
                    @if($b->room && $b->room->floor) &middot; Lantai {{ $b->room->floor }} @endif
                    @if($b->room && $b->room->size) &middot; {{ $b->room->size }} @endif
                    @if($b->room && ($b->room->formatted_price ?? $b->room->price))
                        &middot; <strong style="color:var(--green-600)">
                            {{ $b->room->formatted_price ?? 'Rp '.number_format($b->room->price, 0, ',', '.') }}/bln
                        </strong>
                    @endif
                </div>

                <div class="sect-title"><i class="bi bi-calendar2-range"></i> Periode Hunian</div>
                <div class="timeline-wrap">
                    <div class="timeline-row">
                        <div class="tl-point">
                            <div class="tl-dot start"><i class="bi bi-box-arrow-in-right"></i></div>
                            <div class="tl-date">{{ $b->check_in_date ? $b->check_in_date->format('d M Y') : '—' }}</div>
                            <div class="tl-type">Check-in</div>
                        </div>
                        <div class="tl-line {{ in_array($tStatus, ['nonaktif','rejected']) ? 'ended' : '' }}">
                            <div class="tl-dur">{{ $b->duration_months }} bln</div>
                        </div>
                        <div class="tl-point">
                            <div class="tl-dot {{ $tStatus === 'nonaktif' ? 'ended' : 'end' }}">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                            <div class="tl-date">{{ $checkOutDate }}</div>
                            <div class="tl-type">
                                @if($tStatus === 'penghuni')
                                    <span style="color:var(--green-600);font-weight:700">Aktif</span>
                                @elseif($tStatus === 'nonaktif')
                                    Berakhir
                                @else
                                    Check-out
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($tStatus === 'penghuni' && $daysLeft !== null)
                        @if($daysLeft <= 7)
                            <div class="expiry-chip urgent">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Berakhir dalam {{ $daysLeft }} hari — segera perpanjang
                            </div>
                        @elseif($daysLeft <= 30)
                            <div class="expiry-chip warn">
                                <i class="bi bi-clock-history"></i>
                                {{ $daysLeft }} hari lagi berakhir
                            </div>
                        @else
                            <div class="expiry-chip ok">
                                <i class="bi bi-check-circle-fill"></i>
                                Aktif &bull; {{ $daysLeft }} hari tersisa
                            </div>
                        @endif
                    @endif
                </div>

                <div class="sect-title"><i class="bi bi-info-circle"></i> Detail Booking</div>
                <div class="info-grid">
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-calendar-event"></i> Tanggal Masuk</div>
                        <div class="info-cell-val highlight">
                            {{ $b->check_in_date ? $b->check_in_date->format('d M Y') : '-' }}
                        </div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-clock-history"></i> Durasi Sewa</div>
                        <div class="info-cell-val highlight">{{ $b->duration_months ?? '-' }} bulan</div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-person"></i> Nama</div>
                        <div class="info-cell-val">{{ $b->full_name ?? auth()->user()->name ?? '-' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><i class="bi bi-door-closed"></i> Status Kamar</div>
                        <div class="info-cell-val">{{ $b->room ? ucfirst($b->room->status) : '-' }}</div>
                    </div>
                </div>

                @if($tStatus === 'penghuni' && $activeBooking && $activeBooking->id === $b->id)
                <div class="note-box note-approved">
                    <i class="bi bi-house-heart-fill"></i>
                    <div>Anda sedang aktif menghuni kamar ini.
                        @if($activeRenewal)
                            Perpanjangan sewa sedang diproses.
                            <a href="{{ route('tenant.renewal.invoice', $activeRenewal->id) }}" style="color:var(--green-700);font-weight:700;text-decoration:none">Lihat status</a>
                        @else
                            <a href="{{ route('tenant.renewal.index') }}" style="color:var(--green-700);font-weight:700;text-decoration:none">Ajukan perpanjangan</a>
                        @endif
                    </div>
                </div>
                @elseif($tStatus === 'pending')
                <div class="note-box note-info">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>Permohonan sedang dalam antrian review admin. Biasanya diproses dalam <strong>1&times;24 jam</strong>.</div>
                </div>
                @elseif($tStatus === 'rejected' && $b->admin_notes)
                <div class="note-box note-rejected">
                    <i class="bi bi-chat-text-fill"></i>
                    <div><strong>Alasan penolakan:</strong> {{ $b->admin_notes }}</div>
                </div>
                @elseif($tStatus === 'nonaktif')
                <div class="note-box note-warning">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>Masa sewa untuk booking ini telah berakhir. Terima kasih sudah menghuni Urban Kost.</div>
                </div>
                @endif

            </div>
        </div>

        <div class="card-footer-actions">
            <div class="submitted-meta" style="margin-right:auto">
                <i class="bi bi-clock-history"></i>
                Diajukan {{ $b->created_at->format('d M Y') }} 
            </div>
            @if($tStatus === 'penghuni' && $unpaidInv > 0)
            <a href="{{ route('tenant.invoices.index') }}" class="uk-btn uk-btn-amber">
                <i class="bi bi-receipt"></i> Lihat Tagihan
            </a>
            @endif
            @if($tStatus === 'penghuni' && !$activeRenewal)
            <a href="{{ route('tenant.renewal.index') }}" class="uk-btn uk-btn-green">
                <i class="bi bi-arrow-repeat"></i> Perpanjang
            </a>
            @endif
            @if($b->room)
            <a href="{{ route('rooms.show', $b->room) }}" class="uk-btn uk-btn-outline">
                <i class="bi bi-eye"></i> Lihat Kamar
            </a>
            @endif
        </div>
    </div>
    @empty

    <div class="empty-wrap">
        <div class="empty-icon-ring"><i class="bi bi-calendar-x"></i></div>
        <div class="empty-title">Belum Ada Riwayat Booking</div>
        <div class="empty-sub">Riwayat booking Anda akan muncul di sini setelah melakukan pemesanan kamar.</div>
        <a href="{{ route('home') }}" class="btn-primary">
            <i class="bi bi-search"></i> Cari Kamar
        </a>
    </div>

    @endforelse

    @if(isset($bookings) && method_exists($bookings, 'hasPages') && $bookings->hasPages())
    <div class="pagi-wrap">{{ $bookings->links() }}</div>
    @endif

    <div class="legend-card">
        <div class="legend-head">Keterangan Status Hunian</div>
        <div class="legend-grid">
            @foreach([
                ['green-500',   'Penghuni Aktif', 'Sedang aktif menghuni kamar'],
                ['amber',       'Menunggu',       'Booking pending atau calon penghuni'],
                ['neutral-400', 'Sewa Selesai',   'Masa sewa sudah berakhir'],
                ['red',         'Ditolak',        'Booking ditolak oleh admin'],
            ] as [$color, $label, $desc])
            <div class="legend-item">
                <span class="legend-dot" style="background:var(--{{ $color }})"></span>
                <strong style="font-size:.73rem">{{ $label }}</strong>
                <span style="color:var(--neutral-400)">— {{ $desc }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
</body>
</html>