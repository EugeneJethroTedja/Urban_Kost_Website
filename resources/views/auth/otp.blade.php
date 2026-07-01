<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi OTP — Urban Kost</title>
<link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
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
    --red:         #dc2626;
    --red-pale:    #fef2f2;
    --amber-pale:  #fffbeb;
    --shadow-xl: 0 24px 80px rgba(14,63,39,.2);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: 'Instrument Sans', sans-serif;
    min-height: 100vh;
    background: var(--green-50);
    display: flex; align-items: center; justify-content: center;
    padding: 24px 16px;
    position: relative;
    -webkit-font-smoothing: antialiased;
    color: var(--neutral-900);
}
body::before {
    content: '';
    position: fixed; inset: 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(42,179,107,.12) 0%, transparent 50%),
        radial-gradient(circle at 85% 85%, rgba(29,148,88,.08) 0%, transparent 50%),
        radial-gradient(circle at 70% 10%, rgba(78,201,138,.07) 0%, transparent 40%);
    pointer-events: none;
}
body::after {
    content: '';
    position: fixed; inset: 0;
    background-image:
        linear-gradient(rgba(42,179,107,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(42,179,107,.05) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

.otp-card {
    background: var(--neutral-0);
    border-radius: 20px;
    padding: 40px 36px 34px;
    width: 100%; max-width: 420px;
    box-shadow: var(--shadow-xl), 0 0 0 1px rgba(42,179,107,.12);
    position: relative; z-index: 1;
    animation: slideUp .55s cubic-bezier(.16,1,.3,1) both;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(28px) scale(.97); }
    to   { opacity: 1; transform: none; }
}
.otp-card::before {
    content: '';
    position: absolute; top: -50px; right: -50px;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(42,179,107,.07) 0%, transparent 70%);
    pointer-events: none;
}

.card-brand {
    display: flex; align-items: center; gap: 10px; margin-bottom: 28px;
}
.card-brand-mark {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .9rem;
    box-shadow: 0 4px 12px rgba(42,179,107,.3);
}
.card-brand-name {
    font-family: 'Fraunces', serif; font-weight: 700; font-size: 1.1rem;
    color: var(--neutral-900); letter-spacing: -.02em;
}

.otp-icon-wrap {
    width: 56px; height: 56px; border-radius: 15px;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.4rem; margin-bottom: 18px;
    box-shadow: 0 6px 20px rgba(42,179,107,.32), 0 0 0 6px rgba(42,179,107,.08);
    animation: pulse-icon 3s ease-in-out infinite;
}
@keyframes pulse-icon {
    0%,100% { box-shadow: 0 6px 20px rgba(42,179,107,.32), 0 0 0 6px rgba(42,179,107,.08); }
    50%      { box-shadow: 0 6px 24px rgba(42,179,107,.45), 0 0 0 10px rgba(42,179,107,.05); }
}

.otp-title {
    font-family: 'Fraunces', serif; font-weight: 700;
    font-size: 1.7rem; color: var(--neutral-900);
    letter-spacing: -.025em; line-height: 1.2; margin-bottom: 6px;
}
.otp-sub {
    font-size: .82rem; color: var(--neutral-500); line-height: 1.6; margin-bottom: 20px;
}
.otp-sub strong { color: var(--green-700); font-weight: 700; }

.back-link {
    display: inline-flex; align-items: center; gap: 5px;
    color: var(--neutral-500); text-decoration: none;
    font-size: .74rem; font-weight: 600; margin-bottom: 18px;
    transition: all .2s; width: fit-content;
}
.back-link:hover { color: var(--green-600); transform: translateX(-3px); }

.uk-alert {
    padding: 10px 13px; border-radius: 9px;
    font-size: .78rem; margin-bottom: 14px;
    display: flex; align-items: flex-start; gap: 8px;
    line-height: 1.5; border: 1px solid;
}
.uk-alert i { flex-shrink: 0; margin-top: 1px; }
.uk-alert-success { background: var(--green-50); border-color: var(--green-200); color: var(--green-800); }
.uk-alert-danger  { background: var(--red-pale); border-color: rgba(220,38,38,.2); color: #7d1a22; }

.divider { height: 1px; background: var(--neutral-100); margin-bottom: 22px; }

.otp-inputs {
    display: flex; gap: 8px; justify-content: center; margin-bottom: 10px;
}
.otp-digit {
    width: 52px; height: 60px; text-align: center;
    font-family: 'Fraunces', serif; font-size: 1.7rem; font-weight: 700;
    color: var(--neutral-900);
    background: var(--neutral-50);
    border: 1px solid var(--neutral-200); border-radius: 12px;
    outline: none; transition: all .2s;
    caret-color: var(--green-500);
}
.otp-digit:focus {
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(42,179,107,.12);
    background: var(--neutral-0);
    transform: scale(1.04);
}
.otp-digit.filled {
    border-color: var(--green-400);
    background: var(--green-50);
    color: var(--green-700);
}

.otp-progress {
    display: flex; justify-content: center; gap: 5px; margin-bottom: 18px;
}
.otp-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--neutral-200); transition: all .2s;
}
.otp-dot.active { background: var(--green-500); transform: scale(1.4); }

.otp-error {
    text-align: center; font-size: .74rem; color: var(--red); margin-bottom: 12px;
}

.btn-verify {
    width: 100%; padding: 13px;
    background: linear-gradient(135deg, var(--green-500), var(--green-700));
    border: none; border-radius: 11px; color: #fff;
    font-family: 'Instrument Sans', sans-serif;
    font-size: .875rem; font-weight: 600; cursor: pointer;
    transition: all .25s; box-shadow: 0 4px 18px rgba(42,179,107,.3);
    display: flex; align-items: center; justify-content: center; gap: 7px;
    margin-bottom: 18px;
}
.btn-verify:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--green-600), var(--green-800));
    transform: translateY(-1px); box-shadow: 0 8px 24px rgba(42,179,107,.38);
}
.btn-verify:disabled { background: var(--neutral-200); color: var(--neutral-400); cursor: not-allowed; box-shadow: none; }

.footer-row {
    display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
}
.countdown-wrap { display: flex; align-items: center; gap: 6px; }
.countdown-label { font-size: .72rem; color: var(--neutral-400); }
.countdown-timer {
    font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 700;
    color: var(--green-600); min-width: 44px;
}
.countdown-timer.urgent { color: var(--red); }

.resend-wrap { font-size: .76rem; color: var(--neutral-500); }
.resend-btn {
    background: none; border: none; padding: 0;
    color: var(--green-700); font-weight: 700; font-size: .76rem;
    cursor: pointer; font-family: 'Instrument Sans', sans-serif;
    transition: all .2s;
}
.resend-btn:hover:not(:disabled) { text-decoration: underline; color: var(--green-800); }
.resend-btn:disabled { color: var(--neutral-400); cursor: not-allowed; }

.spam-note {
    margin-top: 16px; padding: 10px 13px;
    background: var(--green-50); border: 1px solid var(--green-100);
    border-radius: 10px; font-size: .72rem; color: var(--neutral-600);
    text-align: center; line-height: 1.55;
}

@media (max-width: 480px) {
    body { padding: 12px; }
    .otp-card { padding: 28px 20px 24px; border-radius: 16px; }
    .otp-digit { width: 44px; height: 52px; font-size: 1.45rem; border-radius: 10px; }
    .otp-inputs { gap: 6px; }
    .otp-title { font-size: 1.45rem; }
    .footer-row { flex-direction: column; align-items: flex-start; gap: 6px; }
}
</style>
</head>
<body>

<div class="otp-card">
    <div class="card-brand">
        <div class="card-brand-mark"><i class="bi bi-house-heart-fill"></i></div>
        <div class="card-brand-name">Urban Kost</div>
    </div>

    <a href="{{ route('login') }}" class="back-link">
        <i class="bi bi-arrow-left-short" style="font-size:1rem"></i> Ganti email
    </a>

    <div class="otp-icon-wrap"><i class="bi bi-shield-lock-fill"></i></div>
    <h1 class="otp-title">Verifikasi OTP</h1>
    <p class="otp-sub">Kode dikirim ke <strong>{{ $email }}</strong></p>

    @if(session('success'))
    <div class="uk-alert uk-alert-success">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="uk-alert uk-alert-danger">
        <i class="bi bi-exclamation-circle-fill"></i><span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <form method="POST" action="{{ route('otp.verify.submit') }}" id="otpForm">
        @csrf
        <input type="hidden" name="otp" id="otpCombined">

        <div class="otp-inputs">
            @for($i = 1; $i <= 6; $i++)
            <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]"
                   inputmode="numeric" id="d{{ $i }}" autocomplete="off">
            @endfor
        </div>

        <div class="otp-progress">
            @for($i = 1; $i <= 6; $i++)
            <div class="otp-dot" id="dot{{ $i }}"></div>
            @endfor
        </div>

        @error('otp')
        <div class="otp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
        @enderror

        <button type="submit" class="btn-verify" id="submitBtn" disabled>
            <i class="bi bi-shield-check"></i> Verifikasi &amp; Masuk
        </button>
    </form>

    <div class="footer-row">
        <div class="countdown-wrap">
            <span class="countdown-label">Berlaku:</span>
            <span class="countdown-timer" id="timer">10:00</span>
        </div>
        <div class="resend-wrap">
            Tidak menerima kode?&nbsp;
            <form method="POST" action="{{ route('otp.resend') }}" style="display:inline">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn" disabled>Kirim ulang</button>
            </form>
        </div>
    </div>

    <div class="spam-note">
        Cek folder <strong>Spam / Junk</strong> jika tidak ada di Kotak Masuk &middot; Berlaku <strong>10 menit</strong>
    </div>
</div>

<script>
const digits    = document.querySelectorAll('.otp-digit');
const dots      = document.querySelectorAll('.otp-dot');
const combined  = document.getElementById('otpCombined');
const submitBtn = document.getElementById('submitBtn');

function updateProgress() {
    const otp = [...digits].map(d => d.value).join('');
    dots.forEach((dot, i) => dot.classList.toggle('active', i < otp.length));
    combined.value = otp;
    submitBtn.disabled = otp.length < 6;
}

digits.forEach((input, idx) => {
    input.addEventListener('input', e => {
        const val = e.target.value.replace(/\D/g, '');
        e.target.value = val;
        e.target.classList.toggle('filled', !!val);
        if (val && idx < digits.length - 1) digits[idx + 1].focus();
        updateProgress();
    });
    input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !e.target.value && idx > 0) {
            digits[idx - 1].focus();
            digits[idx - 1].value = '';
            digits[idx - 1].classList.remove('filled');
            updateProgress();
        }
    });
    input.addEventListener('paste', e => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        if (pasted.length >= 6) {
            for (let i = 0; i < 6; i++) {
                digits[i].value = pasted[i] || '';
                digits[i].classList.toggle('filled', !!pasted[i]);
            }
            updateProgress();
            digits[5].focus();
        }
    });
});

digits[5].addEventListener('input', () => {
    const otp = [...digits].map(d => d.value).join('');
    if (otp.length === 6) setTimeout(() => document.getElementById('otpForm').submit(), 350);
});

let seconds = 600;
const timerEl   = document.getElementById('timer');
const resendBtn = document.getElementById('resendBtn');
const countdown = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
        clearInterval(countdown);
        timerEl.textContent = '00:00';
        timerEl.classList.add('urgent');
        resendBtn.disabled = false;
        return;
    }
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;
    if (seconds <= 60) timerEl.classList.add('urgent');
}, 1000);

setTimeout(() => { resendBtn.disabled = false; }, 120000);
digits[0].focus();
</script>
</body>
</html>