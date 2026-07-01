<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Booking {{ $booking->booking_code }} — Urban Kost</title>
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
    --purple:      #7c3aed;
    --purple-lt:   #f5f3ff;
    --purple-line: #ddd6fe;
    --shadow-sm: 0 1px 4px rgba(15,31,24,.06);
    --shadow-md: 0 4px 20px rgba(15,31,24,.09);
    --shadow-lg: 0 12px 48px rgba(15,31,24,.13);
    --card-r: 16px;
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

.page-wrap { max-width: 1280px; margin: 0 auto; padding: 32px 24px 64px; }

.bc { display: flex; align-items: center; gap: 6px; font-size: .73rem; color: var(--neutral-400); margin-bottom: 20px; flex-wrap: wrap; }
.bc a { color: var(--green-600); text-decoration: none; font-weight: 600; }
.bc a:hover { text-decoration: underline; }
.bc i.sep { font-size: .55rem; color: var(--neutral-300); }

.page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; animation: fadeUp .3s ease both; }
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
.page-header-sub { font-size: .8rem; color: var(--neutral-500); margin-top: 4px; }
.header-btns { display: flex; gap: 8px; flex-wrap: wrap; }

.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px 16px; border-radius: 10px;
    font-size: .8rem; font-weight: 600;
    font-family: 'Instrument Sans', sans-serif;
    cursor: pointer; border: none; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.btn-back    { background: var(--neutral-0); border: 1px solid var(--green-200); color: var(--neutral-600); }
.btn-back:hover { background: var(--green-50); border-color: var(--green-300); color: var(--green-700); }
.btn-success { background: var(--green-600); color: #fff; }
.btn-success:hover { background: var(--green-700); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,148,88,.3); color: #fff; }
.btn-danger  { background: var(--red); color: #fff; }
.btn-danger:hover  { background: #b91c1c; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,38,38,.3); color: #fff; }
.btn-outline { background: transparent; border: 1.5px solid var(--neutral-200); color: var(--neutral-600); }
.btn-outline:hover { border-color: var(--neutral-400); color: var(--neutral-900); }
.btn-outline-success { background: transparent; border: 1.5px solid var(--green-300); color: var(--green-700); }
.btn-outline-success:hover { background: var(--green-50); border-color: var(--green-500); }
.btn-sm  { padding: 6px 13px; font-size: .76rem; border-radius: 8px; }
.btn-xs  { padding: 4px 10px; font-size: .7rem; border-radius: 7px; }
.btn-w100 { width: 100%; }
.btn[disabled], button:disabled { opacity: .5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }

.flash-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px; margin-bottom: 16px;
    font-size: .82rem; font-weight: 500; border: 1px solid;
}

.detail-grid { display: grid; grid-template-columns: 1fr 400px; gap: 20px; align-items: start; animation: fadeUp .3s .07s ease both; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.card {
    background: var(--neutral-0); border-radius: var(--card-r);
    border: 1px solid var(--green-100); box-shadow: var(--shadow-md);
    overflow: hidden; margin-bottom: 20px;
}

/* ── Booking header strip ── */
.booking-header {
    padding: 22px 24px;
    background: linear-gradient(135deg, var(--green-900), var(--green-600));
}
.booking-header-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.booking-code-display {
    font-family: 'Fraunces', serif; font-weight: 700; font-size: 1.5rem;
    letter-spacing: -.01em; color: #fff;
}
.booking-meta { font-size: .73rem; color: rgba(255,255,255,.6); margin-top: 4px; }
.booking-type-lbl { font-size: .62rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.5); margin-bottom: 6px; }

.info-grid { display: grid; grid-template-columns: repeat(2,1fr); }
@media (max-width: 480px) { .info-grid { grid-template-columns: 1fr; } }
.info-cell {
    padding: 12px 18px; border-bottom: 1px solid var(--green-50); border-right: 1px solid var(--green-50);
}
.info-cell:nth-child(even) { border-right: none; }
.info-cell-lbl { font-size: .62rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 3px; display: flex; align-items: center; gap: 4px; }
.info-cell-val { font-size: .86rem; font-weight: 700; color: var(--neutral-800); }

.calc-section { padding: 14px 18px; background: var(--green-50); border-top: 1px solid var(--green-100); }
.calc-row { display: flex; justify-content: space-between; align-items: center; font-size: .8rem; margin-bottom: 4px; }
.calc-row .lbl { color: var(--neutral-500); }
.calc-row .val { font-weight: 700; color: var(--neutral-700); }
.calc-total { display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 2px solid var(--green-200); margin-top: 8px; }
.calc-total .lbl { font-weight: 700; font-size: .9rem; color: var(--neutral-800); }
.calc-total .val { font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 700; color: var(--green-700); }

.message-block { padding: 14px 18px; border-top: 1px solid var(--green-100); }
.section-lbl {
    font-size: .62rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 8px;
    display: flex; align-items: center; gap: 5px;
}
.message-text {
    font-size: .84rem; color: var(--neutral-700); line-height: 1.65;
    background: var(--green-50); padding: 10px 13px;
    border-radius: 10px; border: 1px solid var(--green-100);
}

.service-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--green-100); border: 1px solid var(--green-200);
    color: var(--green-800); font-size: .72rem; font-weight: 700;
    padding: 4px 10px; border-radius: var(--r-pill); margin: 2px;
}

/* ── Status pills ── */
.status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .72rem; font-weight: 700;
    padding: 5px 12px; border-radius: var(--r-pill); white-space: nowrap;
}
.status-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
.sp-pending      { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
.sp-pending .dot { background: #ca8a04; }
.sp-approved     { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-300); }
.sp-approved .dot { background: var(--green-500); }
.sp-active       { background: var(--green-600); color: #fff; border: 1px solid var(--green-700); box-shadow: 0 0 0 3px rgba(29,148,88,.15); }
.sp-active .dot  { background: #bbf7d0; }
.sp-waiting_list { background: var(--amber-lt); color: #92400e; border: 1px solid var(--amber-line); }
.sp-waiting_list .dot { background: var(--amber); }
.sp-rejected     { background: var(--red-lt); color: #991b1b; border: 1px solid var(--red-line); }
.sp-rejected .dot { background: var(--red); }
.sp-cancelled    { background: var(--neutral-100); color: var(--neutral-500); border: 1px solid var(--neutral-200); }
.sp-cancelled .dot { background: var(--neutral-400); }
.sp-completed    { background: var(--purple-lt); color: #6b21a8; border: 1px solid var(--purple-line); }
.sp-completed .dot { background: var(--purple); }
.sp-under_review { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.sp-under_review .dot { background: #f97316; }

/* ── Waiting list card ── */
.wl-card-header {
    padding: 14px 20px;
    background: var(--green-50);
    border-bottom: 1px solid var(--green-100);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
}
.finance-row {
    padding: 14px 20px;
    border-bottom: 1px solid var(--green-100);
    background: var(--green-50);
    display: grid; grid-template-columns: repeat(3,1fr); gap: 12px;
}
.finance-cell-lbl { font-size: .62rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--neutral-400); margin-bottom: 4px; }
.finance-cell-val { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 700; }

.notify-section { padding: 16px 20px; }
.warn-box { display: flex; gap: 10px; padding: 12px 14px; border-radius: 10px; margin-bottom: 12px; font-size: .8rem; border: 1px solid; }

/* ── Action cards ── */
.action-card { border: 1.5px solid var(--green-200); }
.action-card-header {
    padding: 13px 18px; border-bottom: 1px solid var(--green-100);
    font-family: 'Fraunces', serif; font-size: .88rem; font-weight: 700;
    border-radius: var(--card-r) var(--card-r) 0 0;
    display: flex; align-items: center; gap: 7px;
}
.form-group { margin-bottom: 14px; }
.form-label { display: block; font-size: .75rem; font-weight: 600; color: var(--neutral-700); margin-bottom: 5px; }
.form-control {
    width: 100%; padding: 8px 12px; border-radius: 9px;
    border: 1.5px solid var(--neutral-200);
    font-family: 'Instrument Sans', sans-serif; font-size: .8rem;
    background: var(--neutral-0); color: var(--neutral-900); outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.form-control:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(42,179,107,.12); }
textarea.form-control { resize: vertical; }
.input-group-sm { display: flex; align-items: stretch; }
.input-group-sm .prefix {
    padding: 8px 10px; background: var(--neutral-100); border: 1.5px solid var(--neutral-200);
    border-right: none; border-radius: 9px 0 0 9px; font-size: .8rem; color: var(--neutral-500); font-weight: 600;
    display: flex; align-items: center;
}
.input-group-sm .form-control { border-radius: 0 9px 9px 0; }
.form-hint { font-size: .7rem; color: var(--neutral-400); margin-top: 4px; }

/* ── Alert boxes — semua pakai green/amber/red, tidak ada biru ── */
.alert-box { display: flex; gap: 10px; padding: 12px 14px; border-radius: 10px; margin-bottom: 14px; font-size: .8rem; border: 1px solid; }
.alert-box i { flex-shrink: 0; margin-top: 1px; }
.alert-box.info    { background: var(--green-50);  border-color: var(--green-200); color: var(--green-800); }
.alert-box.success { background: var(--green-50);  border-color: var(--green-200); color: var(--green-800); }
.alert-box.warning { background: var(--amber-lt);  border-color: var(--amber-line); color: #92400e; }
.alert-box.danger  { background: var(--red-lt);    border-color: var(--red-line);  color: #991b1b; }

/* ── Room card (sidebar) ── */
.room-image-wrap {
    height: 130px; overflow: hidden; position: relative;
    background: linear-gradient(145deg, var(--green-900), var(--green-600));
}
.room-image-wrap img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
.room-status-badge {
    position: absolute; top: 10px; right: 10px;
    font-size: .62rem; font-weight: 700; padding: 3px 10px; border-radius: var(--r-pill); color: #fff;
}
.room-body { padding: 14px 16px; }
.room-name  { font-family: 'Fraunces', serif; font-weight: 700; font-size: .95rem; color: var(--neutral-800); }
.room-meta  { font-size: .72rem; color: var(--neutral-400); margin: 3px 0 8px; }
.room-price { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--green-700); }
.room-price small { font-size: .7rem; font-weight: 400; color: var(--neutral-400); }
.facility-chip {
    display: inline-block; font-size: .62rem; background: var(--green-100);
    border: 1px solid var(--green-200); color: var(--green-800);
    padding: 2px 7px; border-radius: var(--r-pill); margin: 2px 1px;
}

/* ── Status info boxes ── */
.status-info-box {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px; border-radius: 12px; margin-bottom: 14px;
}
.status-info-icon  { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
.status-info-title { font-family: 'Fraunces', serif; font-weight: 700; font-size: .88rem; }
.status-info-desc  { font-size: .78rem; margin-top: 2px; line-height: 1.5; }

.notes-text {
    font-size: .84rem; line-height: 1.65; color: var(--neutral-700);
    background: var(--green-50); padding: 12px 14px; border-radius: 10px; border: 1px solid var(--green-100);
}
.notes-meta { font-size: .72rem; color: var(--neutral-400); margin-top: 8px; }

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

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
@media (max-width: 768px) {
    .info-grid { grid-template-columns: 1fr; }
    .info-cell { border-right: none; }
    .finance-row { grid-template-columns: 1fr; }
    .booking-code-display { font-size: 1.2rem; }
}
@media (max-width: 640px) { .page-wrap { padding: 16px 12px 48px; } }
</style>
</head>
<body>

<div class="page-bg"><div class="page-bg-grid"></div></div>

@include('partials.admin-sidebar')

@php
    $isAdvance   = $booking->booking_type === 'advance';
    $isPending   = in_array($booking->status, ['pending', 'under_review']);
    $isApproved  = $booking->status === 'approved';
    $isWL        = $booking->status === 'waiting_list';
    $isRejected  = $booking->status === 'rejected';
    $isCancelled = $booking->status === 'cancelled';
    $isActive    = $booking->status === 'active';
    $isCompleted = in_array($booking->status, ['completed', 'done']);
    $isWillRenew = $booking->renewal_status === 'will_renew';

    $totalSewa       = ($booking->room?->price ?? 0) * ($booking->duration_months ?? 1);
    $servicePerMonth = $booking->service_amount ?? 0;
    $totalService    = $servicePerMonth * ($booking->duration_months ?? 1);
    $grandTotal      = $totalSewa + $totalService;
    $paymentMethod   = $booking->payment_method ?? 'lump_sum';
    $dpSuggested     = $paymentMethod === 'monthly'
        ? (int) round((($booking->room?->price ?? 0) + ($booking->service_amount ?? 0)) * 0.50)
        : (int) round($grandTotal * 0.50);

    $wlEntry = $booking->waitingList ?? null;
    $invoice = $booking->invoices?->first() ?? null;

    $dpInvoice = $remainingInvoice = null;
    if ($wlEntry) {
        $dpInvoice = \App\Models\Invoice::where('invoice_type','waiting_list_dp')
            ->where(function($q) use($wlEntry,$booking){
                $q->where('waiting_list_id',$wlEntry->id)
                  ->orWhere('booking_id',$booking->id)
                  ->orWhere(function($q2) use($wlEntry){
                      $q2->where('user_id',$wlEntry->user_id)->where('room_id',$wlEntry->room_id);
                  });
            })->latest()->first();
        $remainingInvoice = \App\Models\Invoice::where('invoice_type','waiting_list_remaining')
            ->where(function($q) use($wlEntry,$booking){
                $q->where('waiting_list_id',$wlEntry->id)
                  ->orWhere('booking_id',$booking->id)
                  ->orWhere(function($q2) use($wlEntry){
                      $q2->where('user_id',$wlEntry->user_id)->where('room_id',$wlEntry->room_id);
                  });
            })->latest()->first();
    }

    $dpIsPaid    = $dpInvoice?->status === 'paid';
    $dpIsPending = $dpInvoice?->status === 'pending_verification';
    $dpIsUnpaid  = $dpInvoice && !$dpIsPaid && !$dpIsPending;
    $remIsPaid   = $remainingInvoice?->status === 'paid';
    $remIsPending= $remainingInvoice?->status === 'pending_verification';

    $canNotify = true; $checkInDate = null; $wlTenantName = null;
    if (!empty($oldTenantLeasePeriod)) {
        $canNotify    = $oldTenantLeasePeriod['is_expired'];
        $checkInDate  = $oldTenantLeasePeriod['end_date'];
        $wlTenantName = $oldTenantLeasePeriod['tenant_name'];
    }

    $sisa = $grandTotal - ($wlEntry?->dp_amount ?? 0);

    $statusMap = [
        'pending'      => ['sp-pending',      'Menunggu Review'],
        'under_review' => ['sp-under_review',  'Sedang Direview'],
        'approved'     => ['sp-approved',      'Disetujui'],
        'active'       => ['sp-active',        'Sedang Menempati'],
        'waiting_list' => ['sp-waiting_list',  'Dalam Antrian'],
        'rejected'     => ['sp-rejected',      'Ditolak'],
        'cancelled'    => ['sp-cancelled',     'Dibatalkan'],
        'completed'    => ['sp-completed',     'Selesai'],
        'done'         => ['sp-completed',     'Selesai'],
    ];
    [$spClass,$spLabel] = $statusMap[$booking->status] ?? ['sp-cancelled', ucfirst($booking->status)];
@endphp

<div class="page-wrap">

    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @foreach(['success','warning','error','info'] as $type)
    @if(session($type))
    @php
        $flashStyles = [
            'success' => ['bg'=>'var(--green-50)','border'=>'var(--green-200)','color'=>'var(--green-800)','icon'=>'check-circle-fill'],
            'error'   => ['bg'=>'var(--red-lt)','border'=>'var(--red-line)','color'=>'#991b1b','icon'=>'exclamation-circle-fill'],
            'warning' => ['bg'=>'var(--amber-lt)','border'=>'var(--amber-line)','color'=>'#92400e','icon'=>'exclamation-triangle-fill'],
            'info'    => ['bg'=>'var(--green-50)','border'=>'var(--green-200)','color'=>'var(--green-800)','icon'=>'info-circle-fill'],
        ];
        $fs = $flashStyles[$type];
    @endphp
    <div class="flash-alert" style="background:{{ $fs['bg'] }};border-color:{{ $fs['border'] }};color:{{ $fs['color'] }}">
        <i class="bi bi-{{ $fs['icon'] }}"></i>
        <span>{!! session($type) !!}</span>
    </div>
    @endif
    @endforeach

    <div class="hero-banner">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="dot"></span>
                <a href="{{ route('admin.bookings.index') }}" style="color:inherit;text-decoration:none">Detail Booking</a>
            </div>
            <h1 class="hero-title">Detail <em>{{ $booking->booking_code }}</em></h1>
            <p class="hero-sub">
                {{ $booking->booking_type === 'advance' ? 'Advance Booking' : 'Regular Booking' }}
                &nbsp;·&nbsp; Diajukan {{ $booking->created_at->format('d M Y') }}
            </p>
        </div>
        <div class="hero-right">
            @if($booking->user)
            <a href="{{ route('admin.tenants.show', $booking->user_id) }}" class="btn-hero-ghost">
                <i class="bi bi-person"></i> Profil Pengguna
            </a>
            @endif
            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn-hero-white">
                <i class="bi bi-pencil-fill"></i> Edit
            </a>
        </div>
    </div>

    <div class="detail-grid">
        <div>
            <div class="card">
                <div class="booking-header">
                    <div class="booking-header-top">
                        <div>
                            <div class="booking-type-lbl">{{ $isAdvance ? 'Advance Booking' : 'Regular Booking' }}</div>
                            <div class="booking-code-display">{{ $booking->booking_code }}</div>
                            <div class="booking-meta">Diajukan {{ $booking->created_at->format('d M Y') }}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
                            <span class="status-pill {{ $spClass }}">
                                <span class="dot"></span>{{ $spLabel }}
                            </span>
                            @if($isAdvance)
                            <span style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.85);font-size:.62rem;font-weight:600;padding:3px 9px;border-radius:var(--r-pill)">
                                <i class="bi bi-clock-history" style="margin-right:3px"></i>Advance
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="info-grid">
                    @foreach([
                        ['bi-person-fill',       'Nama Pemohon',   $booking->full_name],
                        ['bi-telephone-fill',    'No. HP',         $booking->phone ?? '-'],
                        ['bi-envelope-fill',     'Email',          $booking->user?->email ?? '-'],
                        ['bi-card-text',         'No. Identitas',  $booking->identity_number ?? '-'],
                        ['bi-telephone-fill',    'Kontak Darurat', $booking->emergency_contact ?? '-'],
                        ['bi-door-closed-fill',  'Kamar',          ($booking->room?->room_number ?? '-').' ('.($booking->room?->room_name ?? ' ') . ')'],
                        ['bi-building',          'Lantai / Ukuran',($booking->room?->floor ? 'Lt.'.$booking->room->floor : '-').' / '.($booking->room?->size ?? '-')],
                        ['bi-calendar-event-fill','Tanggal Masuk', $booking->check_in_date?->format('d M Y') ?? '-'],
                        ['bi-clock-history',     'Durasi Sewa',    ($booking->duration_months ?? '-').' bulan'],
                    ] as [$ic,$lbl,$val])
                    <div class="info-cell">
                        <div class="info-cell-lbl"><i class="bi {{ $ic }}"></i>{{ $lbl }}</div>
                        <div class="info-cell-val">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>

                @if($booking->additional_services && count($booking->additional_services) > 0)
                <div style="padding:12px 18px;border-top:1px solid var(--green-100)">
                    <div class="section-lbl"><i class="bi bi-plus-circle-fill"></i>Layanan Tambahan</div>
                    <div>
                        @foreach($booking->additional_services as $key => $svc)
                        <span class="service-chip">{{ $svc['label'] ?? $key }} &mdash; Rp {{ number_format($svc['price'] ?? 0,0,',','.') }}/bln</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="calc-section">
                    <div class="section-lbl"><i class="bi bi-calculator"></i>Kalkulasi Biaya</div>
                    <div class="calc-row">
                        <span class="lbl">Sewa kamar (Rp {{ number_format($booking->room?->price ?? 0,0,',','.') }} &times; {{ $booking->duration_months }} bln)</span>
                        <span class="val">Rp {{ number_format($totalSewa,0,',','.') }}</span>
                    </div>
                    @if($totalService > 0)
                    <div class="calc-row">
                        <span class="lbl">Layanan tambahan (Rp {{ number_format($servicePerMonth,0,',','.') }} &times; {{ $booking->duration_months }} bln)</span>
                        <span class="val" style="color:var(--green-600)">+Rp {{ number_format($totalService,0,',','.') }}</span>
                    </div>
                    @endif
                    <div class="calc-total">
                        <span class="lbl">Total</span>
                        <span class="val">Rp {{ number_format($grandTotal,0,',','.') }}</span>
                    </div>
                    @if($isAdvance)
                    <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:.77rem">
                        <span style="color:var(--neutral-400)">DP 50% (jika masuk WL)</span>
                        <span style="font-weight:700;color:var(--amber)">Rp {{ number_format($dpSuggested,0,',','.') }}</span>
                    </div>
                    @endif
                </div>

                @if($booking->message)
                <div class="message-block">
                    <div class="section-lbl"><i class="bi bi-chat-left-text"></i>Pesan dari Pemohon</div>
                    <div class="message-text">{{ $booking->message }}</div>
                </div>
                @endif
            </div>

            @if($invoice && !$wlEntry)
            @php
                $invIsPaid = $invoice->status === 'paid';
                $invLabel  = match($invoice->status){
                    'paid'                 => 'Lunas',
                    'unpaid'               => 'Belum Dibayar',
                    'pending_verification' => 'Menunggu Verifikasi',
                    'overdue'              => 'Terlambat',
                    default                => $invoice->status,
                };
                $invBadgeBg = match($invoice->status){
                    'paid'                 => 'var(--green-600)',
                    'unpaid'               => 'var(--amber)',
                    'pending_verification' => 'var(--green-500)',
                    'overdue'              => 'var(--red)',
                    default                => 'var(--neutral-500)',
                };
                $invHeaderBg = $invIsPaid ? 'var(--green-50)' : 'var(--amber-lt)';
                $invHeaderColor = $invIsPaid ? 'var(--green-700)' : '#92400e';
                $invIconColor   = $invIsPaid ? 'var(--green-600)' : 'var(--amber)';
            @endphp
            <div class="card">
                <div style="padding:14px 18px;background:{{ $invHeaderBg }};border-bottom:1px solid var(--green-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                    <div style="display:flex;align-items:center;gap:8px">
                        <i class="bi bi-receipt" style="color:{{ $invIconColor }};font-size:1rem"></i>
                        <span style="font-family:'Fraunces',serif;font-weight:700;font-size:.9rem;color:{{ $invHeaderColor }}">Invoice: {{ $invoice->invoice_number }}</span>
                    </div>
                    <span style="background:{{ $invBadgeBg }};color:#fff;font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:var(--r-pill)">
                        {{ $invLabel }}
                    </span>
                </div>
                <div style="padding:16px 18px">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;font-size:.82rem;margin-bottom:14px">
                        <div>
                            <div style="font-size:.65rem;color:var(--neutral-400);font-weight:600;margin-bottom:2px">Total Tagihan</div>
                            <div style="font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--green-700)">Rp {{ number_format($invoice->total_amount,0,',','.') }}</div>
                        </div>
                        <div>
                            <div style="font-size:.65rem;color:var(--neutral-400);font-weight:600;margin-bottom:2px">Jatuh Tempo</div>
                            <div style="font-weight:700;color:{{ $invoice->isOverdue() ? 'var(--red)' : 'var(--neutral-900)' }}">
                                {{ $invoice->due_date?->format('d M Y') }}
                                @php $invDaysLeft = $invoice->due_date ? now()->startOfDay()->diffInDays($invoice->due_date->startOfDay(), false) : null; @endphp
                                @if($invDaysLeft === 0)<span style="background:var(--amber);color:#fff;font-size:.58rem;padding:1px 6px;border-radius:var(--r-pill);margin-left:4px">Hari ini</span>
                                @elseif($invoice->isOverdue())<span style="background:var(--red);color:#fff;font-size:.58rem;padding:1px 6px;border-radius:var(--r-pill);margin-left:4px">Terlambat</span>@endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:.65rem;color:var(--neutral-400);font-weight:600;margin-bottom:2px">Periode</div>
                            <div style="font-weight:600;font-size:.78rem">{{ $invoice->period_start?->format('d M Y') }} &mdash; {{ $invoice->period_end?->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:.65rem;color:var(--neutral-400);font-weight:600;margin-bottom:2px">Dibuat</div>
                            <div style="font-weight:600;font-size:.78rem">{{ $invoice->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    @if(!$invIsPaid)
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye"></i>Lihat Invoice
                        </a>
                    </div>
                    @else
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye"></i>Lihat Invoice
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if($wlEntry)
            @php
                $wlBadgeBg = match($wlEntry->status){
                    'waiting'   => 'var(--amber-lt)',
                    'notified'  => 'var(--green-100)',
                    'cancelled' => 'var(--neutral-100)',
                    'completed' => 'var(--green-100)',
                    default     => 'var(--neutral-100)',
                };
                $wlBadgeColor = match($wlEntry->status){
                    'waiting'   => '#854d0e',
                    'notified'  => 'var(--green-800)',
                    'cancelled' => 'var(--neutral-600)',
                    'completed' => 'var(--green-800)',
                    default     => 'var(--neutral-600)',
                };
                $wlBadgeLabel = match($wlEntry->status){
                    'waiting'   => 'Menunggu',
                    'notified'  => 'Kamar Tersedia',
                    'cancelled' => 'Dibatalkan',
                    'completed' => 'Selesai',
                    default     => $wlEntry->status,
                };
            @endphp
            <div class="card" style="border:1.5px solid var(--green-200)">
                <div class="wl-card-header">
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                        <i class="bi bi-list-ol" style="color:var(--green-600);font-size:1rem"></i>
                        <span style="font-family:'Fraunces',serif;font-weight:700;font-size:.9rem;color:var(--green-800)">Waiting List &mdash; Posisi #{{ $wlEntry->position }}</span>
                        <span style="background:{{ $wlBadgeBg }};color:{{ $wlBadgeColor }};font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:var(--r-pill);border:1px solid {{ $wlEntry->status === 'waiting' ? 'var(--amber-line)' : 'var(--green-200)' }}">
                            {{ $wlBadgeLabel }}
                        </span>
                    </div>
                    <span style="font-size:.7rem;color:var(--green-600);font-weight:600">
                        Masuk WL: {{ $wlEntry->approved_at?->format('d M Y') ?? '-' }}
                    </span>
                </div>

                <div class="finance-row">
                    <div>
                        <div class="finance-cell-lbl">Total Sewa</div>
                        <div class="finance-cell-val" style="color:var(--green-700)">Rp {{ number_format($grandTotal,0,',','.') }}</div>
                    </div>
                    <div>
                        <div class="finance-cell-lbl">DP (50%)</div>
                        <div class="finance-cell-val" style="color:var(--amber)">Rp {{ number_format($wlEntry->dp_amount,0,',','.') }}</div>
                    </div>
                    <div>
                        <div class="finance-cell-lbl">Sisa Pelunasan</div>
                        <div class="finance-cell-val" style="color:var(--purple)">Rp {{ number_format($sisa,0,',','.') }}</div>
                    </div>
                </div>

                @if(in_array($wlEntry->status,['waiting','notified']) && !$remIsPaid)
                <div class="notify-section">
                    <div class="section-lbl" style="color:var(--green-700)"><i class="bi bi-bell-fill"></i>Kirim Notifikasi Kamar Tersedia</div>
                    @if(!$canNotify)
                    <div class="warn-box" style="background:var(--red-lt);border-color:var(--red-line);color:#991b1b">
                        <i class="bi bi-lock-fill" style="flex-shrink:0;color:var(--red);margin-top:1px"></i>
                        <div>
                            <div style="font-weight:700;margin-bottom:4px">Tanggal Check-in Belum Tiba</div>
                            <div>Calon penghuni <strong>{{ $wlTenantName ?? '-' }}</strong> baru bisa check-in mulai
                                <strong>{{ $checkInDate ? $checkInDate->format('d M Y') : 'belum diketahui' }}</strong>.</div>
                            @if($checkInDate && !$checkInDate->isPast())
                            <div style="margin-top:4px;font-size:.72rem;color:#b91c1c">Sisa: <strong>{{ now()->diffForHumans($checkInDate,['parts'=>2]) }}</strong></div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($canNotify)
                        @if($dpIsPaid)
                        <div class="alert-box success"><i class="bi bi-check-circle-fill" style="flex-shrink:0"></i>
                            <span>DP sudah lunas (Rp {{ number_format($dpInvoice->total_amount,0,',','.') }}). Klik tombol di bawah untuk menerbitkan <strong>invoice pelunasan</strong> (Rp {{ number_format($sisa,0,',','.') }}) ke <strong>{{ $wlEntry->user?->name }}</strong>.</span>
                        </div>
                        @elseif($dpIsPending)
                        <div class="alert-box info"><i class="bi bi-hourglass-split" style="flex-shrink:0"></i>
                            <span>Invoice DP sedang diverifikasi. Anda tetap bisa mengirim notifikasi.</span>
                        </div>
                        @elseif($dpInvoice)
                        <div class="alert-box warning"><i class="bi bi-exclamation-triangle" style="flex-shrink:0"></i>
                            <span>Invoice DP belum dibayar. Invoice pelunasan akan dihitung dari total sewa penuh (Rp {{ number_format($grandTotal,0,',','.') }}).</span>
                        </div>
                        @else
                        <div class="alert-box info"><i class="bi bi-info-circle" style="flex-shrink:0"></i>
                            <span>Klik tombol ini saat kamar sudah benar-benar kosong. Sistem akan menerbitkan invoice pelunasan dan mengirim notifikasi ke <strong>{{ $wlEntry->user?->name }}</strong>.</span>
                        </div>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('admin.waiting-list.notify-available', $wlEntry->id) }}" style="margin:0">
                        @csrf
                        @if($canNotify)
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Konfirmasi: kamar sudah kosong dan siap ditempati?\nInvoice pelunasan akan diterbitkan dan notifikasi dikirim ke {{ $wlEntry->user?->name }}.')">
                            <i class="bi bi-bell-fill"></i>
                            {{ $wlEntry->status === 'notified' ? 'Kirim Ulang Notifikasi' : 'Beritahu Kamar Tersedia' }}
                        </button>
                        @else
                        <button type="button" class="btn btn-outline" disabled>
                            <i class="bi bi-lock-fill"></i>Belum Bisa Dikirim
                            @if($checkInDate)<span style="font-size:.72rem;font-weight:400;opacity:.8">(mulai {{ $checkInDate->format('d M Y') }})</span>@endif
                        </button>
                        @endif
                    </form>
                </div>
                @elseif($remIsPaid)
                <div style="padding:16px 20px">
                    <div class="alert-box success" style="margin-bottom:0">
                        <i class="bi bi-house-check-fill" style="font-size:1.1rem;flex-shrink:0"></i>
                        <div>
                            <div style="font-weight:700">Proses Selesai</div>
                            <div style="font-size:.78rem;margin-top:2px">{{ $booking->user?->name }} resmi menjadi Penghuni Aktif kamar {{ $booking->room?->room_number }}.</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            @if($booking->admin_notes)
            <div class="card">
                <div style="padding:14px 18px">
                    <div class="section-lbl"><i class="bi bi-chat-left-quote"></i>Catatan Admin</div>
                    <div class="notes-text">{{ $booking->admin_notes }}</div>
                    @if($booking->reviewed_by)
                    <div class="notes-meta">
                        Direview oleh: {{ \App\Models\User::find($booking->reviewed_by)?->name ?? '-' }} &middot;
                        {{ $booking->reviewed_at?->format('d M Y') }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <div>
            @if($booking->room)
            @php
                $roomBadge = match($booking->room->status){
                    'available'   => ['var(--green-500)','Tersedia'],
                    'occupied'    => ['var(--green-800)','Terisi'],
                    'reserved'    => ['var(--amber)','Dipesan'],
                    'maintenance' => ['var(--neutral-500)','Perbaikan'],
                    default       => ['var(--neutral-500)',$booking->room->status],
                };
            @endphp
            <div class="card">
                <div class="room-image-wrap">
                    @if($booking->room->image_path)
                    <img src="{{ asset('storage/'.$booking->room->image_path) }}" alt="{{ $booking->room->room_name }}">
                    @else
                    <div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,.2);font-size:3rem">
                        <i class="bi bi-house-door"></i>
                    </div>
                    @endif
                    <span class="room-status-badge" style="background:{{ $roomBadge[0] }}">{{ $roomBadge[1] }}</span>
                </div>
                <div class="room-body">
                    <div class="room-name">{{ $booking->room->room_name ?? 'Kamar '.$booking->room->room_number }}</div>
                    <div class="room-meta">
                        No. {{ $booking->room->room_number }}
                        @if($booking->room->floor) &middot; Lantai {{ $booking->room->floor }} @endif
                        @if($booking->room->size) &middot; {{ $booking->room->size }} @endif
                    </div>
                    <div class="room-price">Rp {{ number_format($booking->room->price,0,',','.') }} <small>/bulan</small></div>
                    @if($booking->room->facilities)
                    <div style="margin-top:8px">
                        @foreach(array_slice($booking->room->facilities,0,5) as $f)
                        <span class="facility-chip">{{ $f }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($isPending && $isWillRenew)
            <div class="card action-card" style="border-color:var(--amber-line)">
                <div class="action-card-header" style="background:var(--amber-lt);color:#92400e">
                    <i class="bi bi-exclamation-triangle-fill" style="color:var(--amber)"></i>
                    Booking Ini Tidak Perlu Diproses
                </div>
                <div style="padding:14px 16px;font-size:.8rem;color:#78350f">
                    <p style="margin-bottom:8px">Penghuni aktif kamar ini telah menyatakan ingin <strong>memperpanjang sewa</strong>. Advance booking ini akan dibatalkan otomatis.</p>
                    <p>Proses perpanjangan via menu
                        <a href="{{ route('admin.renewals.index') }}" style="color:var(--amber);font-weight:700">
                            <i class="bi bi-arrow-right-circle"></i> Renewals
                        </a>
                    </p>
                </div>
            </div>

            @elseif($isPending)
            <div class="card action-card" style="margin-bottom:14px">
                <div class="action-card-header" style="background:var(--green-50);color:var(--green-800)">
                    <i class="bi bi-{{ $isAdvance ? 'list-ol' : 'check-circle-fill' }}" style="color:var(--green-600)"></i>
                    {{ $isAdvance ? 'Setujui ke Waiting List' : 'Setujui Booking' }}
                </div>
                <div style="padding:14px 16px">
                    @if($isAdvance)
                        @if(isset($existingWaitingList) && $existingWaitingList)
                        <div class="alert-box warning">
                            <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0"></i>
                            <div>Kamar ini sudah memiliki waiting list aktif atas nama <strong>{{ $existingWaitingList->user?->name }}</strong>. Tidak bisa approve advance booking baru sekarang.</div>
                        </div>
                        @else
                        <div class="alert-box info">
                            <i class="bi bi-info-circle-fill" style="flex-shrink:0"></i>
                            <div>
                                <strong>Advance Booking</strong> &mdash; jika disetujui, calon penghuni masuk Waiting List dan diminta membayar DP
                                <strong>Rp {{ number_format($dpSuggested,0,',','.') }}</strong> (50%).
                            </div>
                        </div>
                        @endif
                    @else
                    <div class="alert-box info">
                        <i class="bi bi-info-circle-fill" style="flex-shrink:0"></i>
                        @php
                            $invoicePreview = $booking->payment_method === 'monthly'
                                ? 'Rp ' . number_format(($booking->room?->price ?? 0) + ($booking->service_amount ?? 0), 0, ',', '.')  . ' (bulan pertama)'
                                : 'Rp ' . number_format($grandTotal, 0, ',', '.');
                        @endphp
                        <div>Jika disetujui, invoice senilai <strong>{{ $invoicePreview }}</strong> akan diterbitkan otomatis.
                            <span style="font-size:.72rem;color:var(--green-600);display:block;margin-top:3px">
                                Metode: <strong>{{ $booking->payment_method === 'monthly' ? 'Bayar Bulanan' : 'Bayar Lunas' }}</strong>
                            </span>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ $isAdvance ? route('admin.bookings.approve-waiting-list', $booking->id) : route('admin.bookings.approve', $booking->id) }}">
                        @csrf
                        @if($isAdvance && !(isset($existingWaitingList) && $existingWaitingList))
                        <div class="form-group">
                            <label class="form-label">Nominal DP <span style="color:var(--neutral-400);font-weight:400;font-size:.72rem">(default 50% = Rp {{ number_format($dpSuggested,0,',','.') }})</span></label>
                            <div class="input-group-sm">
                                <span class="prefix">Rp</span>
                                <input type="number" name="dp_amount" class="form-control" value="{{ $dpSuggested }}" min="0">
                            </div>
                            <div class="form-hint">Kosongkan untuk pakai default 50%.</div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="form-label">Catatan untuk Pemohon</label>
                            <textarea name="admin_notes" class="form-control" rows="2" placeholder="Opsional — pesan ke pemohon"></textarea>
                        </div>
                        @if(!(isset($existingWaitingList) && $existingWaitingList))
                        <button type="submit" class="btn btn-success btn-w100"
                                onclick="return confirm('{{ $isAdvance ? 'Setujui advance booking dan masukkan ke Waiting List?' : 'Setujui booking dan terbitkan invoice otomatis?' }}')">
                            <i class="bi bi-{{ $isAdvance ? 'list-ol' : 'check-circle-fill' }}"></i>
                            {{ $isAdvance ? 'Setujui ke Waiting List' : 'Setujui Terbitkan Invoice' }}
                        </button>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card action-card" style="border-color:var(--red-line)">
                <div class="action-card-header" style="background:var(--red-lt);color:#991b1b">
                    <i class="bi bi-x-circle-fill" style="color:var(--red)"></i>Tolak Booking
                </div>
                <div style="padding:14px 16px">
                    <form method="POST" action="{{ route('admin.bookings.reject', $booking->id) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Alasan Penolakan <span style="color:var(--red)">*</span></label>
                            <textarea name="admin_notes" class="form-control" rows="2"
                                      placeholder="Jelaskan alasan penolakan kepada pemohon..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-w100"
                                onclick="return confirm('Tolak booking ini? Pemohon akan dinotifikasi.')">
                            <i class="bi bi-x-circle"></i>Tolak Booking
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($isApproved)
            <div class="status-info-box" style="background:var(--green-50);border:1px solid var(--green-200)">
                <i class="bi bi-check-circle-fill status-info-icon" style="color:var(--green-600)"></i>
                <div>
                    <div class="status-info-title" style="color:var(--green-800)">Booking Disetujui</div>
                    <div class="status-info-desc" style="color:var(--green-700)">Invoice sudah diterbitkan. Menunggu pembayaran dari calon penghuni.</div>
                </div>
            </div>
            @elseif($isWL)
            <div class="status-info-box" style="background:var(--amber-lt);border:1px solid var(--amber-line)">
                <i class="bi bi-list-ol status-info-icon" style="color:var(--amber)"></i>
                <div>
                    <div class="status-info-title" style="color:#92400e">Dalam Antrian (Waiting List)</div>
                    <div class="status-info-desc" style="color:#92400e">
                        @if($dpIsPaid)DP sudah lunas. Klik "Beritahu Kamar Tersedia" saat kamar kosong.
                        @elseif($dpInvoice)Menunggu pembayaran / verifikasi DP.
                        @else Invoice DP akan segera diterbitkan.@endif
                    </div>
                </div>
            </div>

            @if($isAdvance && !$dpIsPaid)
            <div class="card action-card" style="border-color:var(--red-line);margin-bottom:14px">
                <div class="action-card-header" style="background:var(--red-lt);color:#991b1b">
                    <i class="bi bi-trash-fill" style="color:var(--red)"></i>Batalkan &amp; Hapus Booking
                </div>
                <div style="padding:14px 16px">
                    <div class="alert-box warning" style="margin-bottom:12px">
                        <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0"></i>
                        <div>Gunakan jika calon penghuni tidak kunjung membayar DP. Invoice DP yang belum lunas akan dihapus dan posisi waiting list dibatalkan.</div>
                    </div>
                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-w100"
                                onclick="return confirm('Hapus advance booking {{ $booking->booking_code }}?\nWaiting list akan dibatalkan dan invoice DP dihapus.')">
                            <i class="bi bi-trash-fill"></i>Hapus Advance Booking
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @elseif($isActive)
            <div class="status-info-box" style="background:var(--green-600);border:1px solid var(--green-700)">
                <i class="bi bi-house-check-fill status-info-icon" style="color:rgba(255,255,255,.9)"></i>
                <div>
                    <div class="status-info-title" style="color:#fff">Sedang Menempati</div>
                    <div class="status-info-desc" style="color:rgba(255,255,255,.75)">{{ $booking->user?->name }} resmi menghuni kamar {{ $booking->room?->room_number }}.</div>
                </div>
            </div>
            @elseif($isCompleted)
            <div class="status-info-box" style="background:var(--purple-lt);border:1px solid var(--purple-line)">
                <i class="bi bi-trophy-fill status-info-icon" style="color:var(--purple)"></i>
                <div>
                    <div class="status-info-title" style="color:#6b21a8">Selesai</div>
                    <div class="status-info-desc" style="color:var(--purple)">Masa sewa telah berakhir dan proses booking selesai.</div>
                </div>
            </div>
            @elseif($isRejected)
            <div class="status-info-box" style="background:var(--red-lt);border:1px solid var(--red-line)">
                <i class="bi bi-x-circle-fill status-info-icon" style="color:var(--red)"></i>
                <div>
                    <div class="status-info-title" style="color:#991b1b">Booking Ditolak</div>
                    <div class="status-info-desc" style="color:#b91c1c">Pemohon sudah dinotifikasi dan bisa mengajukan booking baru.</div>
                </div>
            </div>
            @elseif($isCancelled)
            <div class="status-info-box" style="background:var(--neutral-100);border:1px solid var(--neutral-200)">
                <i class="bi bi-slash-circle status-info-icon" style="color:var(--neutral-400)"></i>
                <div>
                    <div class="status-info-title" style="color:var(--neutral-600)">Booking Dibatalkan</div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
</body>
</html>