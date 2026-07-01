<style>
.pm-section { margin-bottom: 24px; }

.pm-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 4px;
}
@media (max-width: 480px) {
    .pm-grid { grid-template-columns: 1fr; gap: 8px; }
}

.pm-card {
    position: relative;
    border: 1.5px solid var(--neutral-200);
    border-radius: var(--radius-md);
    padding: 16px 16px 14px 46px;
    cursor: pointer;
    background: var(--neutral-0);
    transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
    user-select: none;
    display: block;
}
.pm-card:hover {
    border-color: var(--green-300);
    background: var(--green-50);
}
.pm-card.is-selected {
    border-color: var(--green-500);
    background: var(--green-50);
    box-shadow: 0 0 0 3px rgba(42,179,107,.1);
}
.pm-card.is-advance {
    border-color: var(--accent);
    background: var(--accent-pale);
    box-shadow: 0 0 0 3px rgba(244,165,53,.1);
}

.pm-radio {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 17px;
    height: 17px;
    accent-color: var(--green-500);
    cursor: pointer;
    flex-shrink: 0;
}

.pm-title  { font-size: .82rem; font-weight: 700; color: var(--neutral-800); margin-bottom: 4px; }
.pm-desc   { font-size: .72rem; color: var(--neutral-500); line-height: 1.55; }
.pm-badge  {
    display: inline-block;
    margin-top: 8px;
    font-size: .62rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
    padding: 2px 8px; border-radius: 100px;
    background: var(--green-100); color: var(--green-700);
}
.pm-badge-adv { background: rgba(244,165,53,.2); color: #92400e; }

.pm-detail {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height .35s ease, opacity .25s ease, margin .25s ease;
    border-radius: var(--radius-sm);
    margin-top: 0;
    margin-bottom: 0;
}
.pm-detail.is-open {
    max-height: 320px;
    opacity: 1;
    margin-top: 8px;
    margin-bottom: 16px;
}
.pm-detail-inner {
    background: var(--green-50);
    border: 1.5px solid var(--green-200);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
}
.pm-detail-title {
    font-size: .68rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
    color: var(--green-700); margin-bottom: 10px;
}
.pm-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    font-size: .78rem;
    border-bottom: 1px dashed var(--neutral-100);
}
.pm-detail-row:last-of-type { border-bottom: none; }
.pm-detail-row .lbl { color: var(--neutral-500); }
.pm-detail-row .val { font-weight: 700; color: var(--neutral-800); }
.pm-detail-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
    margin-top: 6px;
    border-top: 1.5px solid var(--green-300);
    font-size: .82rem;
}
.pm-detail-total .lbl { font-weight: 600; color: var(--neutral-700); }
.pm-detail-total .val { font-family: 'Fraunces', serif; font-weight: 700; color: var(--green-600); font-size: .95rem; }
.pm-detail-note {
    font-size: .65rem; color: var(--neutral-400); margin-top: 8px; line-height: 1.5;
}
</style>

<hr class="uk-hr">
<div class="pm-section">
    <div class="section-label">
        Metode Pembayaran <span class="fld-required">*</span>
    </div>

    <div class="pm-grid">
        <label class="pm-card {{ old('payment_method','lump_sum') === 'lump_sum' ? ($isAdvance ? 'is-advance' : 'is-selected') : '' }}" id="pmCardLumpSum">
            <input type="radio" name="payment_method" value="lump_sum"
                   class="pm-radio" id="pmLumpSum"
                   onchange="onPmChange()"
                   {{ old('payment_method','lump_sum') === 'lump_sum' ? 'checked' : '' }}
                   required>
            <div class="pm-title">Bayar Lunas</div>
            <div class="pm-desc">
                @if($isAdvance)
                    DP 50% dari total semua bulan saat booking disetujui. Sisa dilunasi ketika kamar tersedia.
                @else
                    Bayar total sewa sekaligus. Satu invoice untuk seluruh durasi sewa.
                @endif
            </div>
            <span class="pm-badge">Satu kali bayar</span>
        </label>

        <label class="pm-card {{ old('payment_method') === 'monthly' ? ($isAdvance ? 'is-advance' : 'is-selected') : '' }}" id="pmCardMonthly">
            <input type="radio" name="payment_method" value="monthly"
                   class="pm-radio" id="pmMonthly"
                   onchange="onPmChange()"
                   {{ old('payment_method') === 'monthly' ? 'checked' : '' }}>
            <div class="pm-title">Bayar Bulanan</div>
            <div class="pm-desc">
                @if($isAdvance)
                    DP 50% bulan pertama saat booking disetujui. Bulan berikutnya ditagih otomatis tiap bulan.
                @else
                    Bayar per bulan. Invoice dikirim otomatis 5 hari sebelum jatuh tempo.
                @endif
            </div>
            <span class="pm-badge {{ $isAdvance ? 'pm-badge-adv' : '' }}">Per bulan</span>
        </label>
    </div>

    @error('payment_method')
        <span class="fld-err" style="display:block;margin-top:4px;margin-bottom:8px">{{ $message }}</span>
    @enderror

    <div class="pm-detail" id="pmDetailLump">
        <div class="pm-detail-inner">
            <div class="pm-detail-title">Rincian — Bayar Lunas</div>
            @if($isAdvance)
            <div class="pm-detail-row">
                <span class="lbl">DP sekarang (50% grand total)</span>
                <span class="val" id="dlDP">–</span>
            </div>
            <div class="pm-detail-row">
                <span class="lbl">Sisa (saat kamar tersedia)</span>
                <span class="val" id="dlSisa">–</span>
            </div>
            @endif
            <div class="pm-detail-total">
                <span class="lbl">Total Tagihan</span>
                <span class="val" id="dlTotal">–</span>
            </div>
            <div class="pm-detail-note">
                @if($isAdvance)
                    Invoice DP jatuh tempo 7 hari. Sisa ditagih saat admin mengirim notifikasi kamar tersedia.
                @else
                    Invoice tunggal jatuh tempo 7 hari setelah booking disetujui.
                @endif
            </div>
        </div>
    </div>

    <div class="pm-detail" id="pmDetailMonthly">
        <div class="pm-detail-inner">
            <div class="pm-detail-title">Rincian — Bayar Bulanan</div>
            @if($isAdvance)
            <div class="pm-detail-row">
                <span class="lbl">DP sekarang (50% bulan pertama)</span>
                <span class="val" id="dmDP">–</span>
            </div>
            <div class="pm-detail-row">
                <span class="lbl">Sisa bulan ke-1 (saat kamar tersedia)</span>
                <span class="val" id="dmSisa">–</span>
            </div>
            <div class="pm-detail-row">
                <span class="lbl">Bulan ke-2 dan seterusnya</span>
                <span class="val" id="dmMonthly">–</span>
            </div>
            @else
            <div class="pm-detail-row">
                <span class="lbl">Invoice bulan ke-1 (jatuh tempo +7 hari)</span>
                <span class="val" id="dmFirst">–</span>
            </div>
            <div class="pm-detail-row">
                <span class="lbl">Bulan ke-2 dan seterusnya</span>
                <span class="val" id="dmMonthly">–</span>
            </div>
            @endif
            <div class="pm-detail-total">
                <span class="lbl">Total sewa {{ $isAdvance ? '(semua bulan)' : 'keseluruhan' }}</span>
                <span class="val" id="dmTotal">–</span>
            </div>
            <div class="pm-detail-note">
                Invoice bulanan dikirim otomatis 5 hari sebelum tanggal tagihan. Jatuh tempo 7 hari setelah diterbitkan.
            </div>
        </div>
    </div>
</div>

<script>
const PM_IS_ADVANCE = {{ $isAdvance ? 'true' : 'false' }};

function onPmChange() {
    const isLump = document.getElementById('pmLumpSum')?.checked;
    const cardLump    = document.getElementById('pmCardLumpSum');
    const cardMonthly = document.getElementById('pmCardMonthly');
    const detLump     = document.getElementById('pmDetailLump');
    const detMonthly  = document.getElementById('pmDetailMonthly');
    cardLump?.classList.remove('is-selected', 'is-advance');
    cardMonthly?.classList.remove('is-selected', 'is-advance');
    if (isLump) {
        cardLump?.classList.add(PM_IS_ADVANCE ? 'is-advance' : 'is-selected');
        detLump?.classList.add('is-open');
        detMonthly?.classList.remove('is-open');
    } else {
        cardMonthly?.classList.add(PM_IS_ADVANCE ? 'is-advance' : 'is-selected');
        detMonthly?.classList.add('is-open');
        detLump?.classList.remove('is-open');
    }
    updatePmDetails();
}

function updatePmDetails() {
    const dur = parseInt(document.getElementById('durationSelect')?.value || 1);
    let svcTotal = 0;
    document.querySelectorAll('.service-checkbox:checked').forEach(cb => {
        svcTotal += parseInt(cb.closest('[data-price]')?.dataset.price || 0);
    });
    const perMonth   = (typeof ROOM_PRICE !== 'undefined' ? ROOM_PRICE : 0) + svcTotal;
    const grandTotal = perMonth * dur;
    const dpLump     = Math.round(grandTotal * 0.5);
    const sisaLump   = grandTotal - dpLump;
    const dpMonthly  = Math.round(perMonth * 0.5);
    const sisaMn     = perMonth - dpMonthly;
    const rp = v => 'Rp\u00a0' + v.toLocaleString('id-ID');
    const el = id => document.getElementById(id);
    if (el('dlDP'))      el('dlDP').textContent      = rp(dpLump);
    if (el('dlSisa'))    el('dlSisa').textContent     = rp(sisaLump);
    if (el('dlTotal'))   el('dlTotal').textContent    = rp(grandTotal);
    if (el('dmDP'))      el('dmDP').textContent       = rp(dpMonthly);
    if (el('dmSisa'))    el('dmSisa').textContent     = rp(sisaMn);
    if (el('dmFirst'))   el('dmFirst').textContent    = rp(perMonth);
    if (el('dmMonthly')) el('dmMonthly').textContent  = rp(perMonth) + '/bln';
    if (el('dmTotal'))   el('dmTotal').textContent    = rp(grandTotal);
}

(function() {
    const _orig = typeof calcBookingTotal === 'function' ? calcBookingTotal : null;
    window.calcBookingTotal = function() {
        if (_orig) _orig();
        updatePmDetails();
    };
})();

document.addEventListener('DOMContentLoaded', function () {
    onPmChange();
    updatePmDetails();
});
</script>