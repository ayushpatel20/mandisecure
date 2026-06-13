<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Mobile — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --ms-green: #1a6b3c; --ms-green-dark: #0e3d20; --ms-gold: #e8a020; }
        body {
            background: linear-gradient(155deg, #061710 0%, #0e3b1f 35%, #1a6b3c 70%, #0e4224 100%);
            min-height: 100vh; font-family: 'Inter', sans-serif;
            display: flex; align-items: center; justify-content: center; padding: 2rem;
        }
        .otp-card {
            background: #fff; border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.25);
            width: 100%; max-width: 420px; overflow: hidden;
        }
        .otp-header {
            background: linear-gradient(135deg, var(--ms-green-dark), var(--ms-green));
            padding: 2rem; text-align: center; color: #fff;
        }
        .otp-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; margin: 0 auto 0.75rem;
        }
        .otp-body { padding: 2rem; }

        /* Individual digit boxes */
        .digit-group { display: flex; gap: 0.5rem; justify-content: center; margin: 1.5rem 0; }
        .digit-box {
            width: 48px; height: 56px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1.5rem; font-weight: 700;
            text-align: center; color: #0d1117;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .digit-box:focus {
            border-color: var(--ms-green);
            box-shadow: 0 0 0 3px rgba(26,107,60,0.15);
        }
        .digit-box.filled { border-color: var(--ms-green); }
        .digit-box.error  { border-color: #e11d48; }

        .btn-verify {
            background: var(--ms-green); color: #fff; border: none;
            padding: 0.8rem; border-radius: 10px; font-weight: 700;
            font-size: 0.95rem; width: 100%; cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-verify:hover { background: var(--ms-green-dark); transform: translateY(-1px); }
        .btn-verify:disabled { background: #9ca3af; cursor: not-allowed; transform: none; }

        .btn-resend {
            background: none; border: none; color: var(--ms-green);
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            padding: 0; text-decoration: underline;
        }
        .btn-resend:disabled { color: #9ca3af; cursor: not-allowed; text-decoration: none; }

        .countdown { font-size: 0.85rem; color: #6b7280; }
        .countdown.urgent { color: #e11d48; font-weight: 600; }
    </style>
</head>
<body>

<div class="otp-card">

    <div class="otp-header">
        <div class="otp-icon"><i class="bi bi-phone-fill"></i></div>
        <h1 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:800;margin-bottom:0.25rem">
            Verify Your Mobile
        </h1>
        <p style="font-size:0.88rem;opacity:0.8;margin:0">
            OTP sent to <strong>{{ $masked }}</strong>
        </p>
    </div>

    <div class="otp-body">

        @if(session('otp_resent'))
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.88rem">
            <i class="bi bi-check-circle-fill text-success"></i>
            {{ session('otp_resent') }}
        </div>
        @endif

        @if($errors->has('code'))
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.88rem">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first('code') }}
        </div>
        @endif

        @if($errors->has('mobile'))
        <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.88rem">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first('mobile') }}
            <a href="{{ route('register') }}" class="ms-auto fw-semibold" style="color:var(--ms-green)">Register again</a>
        </div>
        @endif

        <p style="text-align:center;font-size:0.88rem;color:#6b7280;margin-bottom:0">
            Enter the 6-digit OTP. Valid for <strong>5 minutes</strong>.
        </p>

        <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
            @csrf
            <input type="hidden" name="code" id="hiddenCode">

            <div class="digit-group" id="digitGroup">
                @for($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" pattern="[0-9]"
                       maxlength="1" class="digit-box"
                       id="d{{ $i }}" autocomplete="off">
                @endfor
            </div>

            <div class="text-center mb-4">
                <span class="countdown" id="countdown">Expires in <strong id="timer">5:00</strong></span>
            </div>

            <button type="submit" class="btn-verify" id="verifyBtn" disabled>
                <i class="bi bi-shield-check me-1"></i> Verify &amp; Create Account
            </button>
        </form>

        <div class="d-flex align-items-center justify-content-between mt-3">
            <form action="{{ route('otp.resend') }}" method="POST" id="resendForm">
                @csrf
                <button type="submit" class="btn-resend" id="resendBtn"
                    {{ $resends <= 0 ? 'disabled' : '' }}>
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Resend OTP
                    @if($resends > 0)
                        <span style="color:#6b7280;font-weight:400">({{ $resends }} left)</span>
                    @else
                        <span style="color:#e11d48;font-weight:400">(no attempts left)</span>
                    @endif
                </button>
            </form>
            <a href="{{ route('register') }}" style="font-size:0.82rem;color:#6b7280;text-decoration:none">
                <i class="bi bi-arrow-left me-1"></i>Start over
            </a>
        </div>

    </div>
</div>

<script>
(function () {
    const digits   = Array.from(document.querySelectorAll('.digit-box'));
    const hidden   = document.getElementById('hiddenCode');
    const verifyBtn = document.getElementById('verifyBtn');
    const timerEl  = document.getElementById('timer');
    const cntEl    = document.getElementById('countdown');

    // ── Digit input behaviour ─────────────────────────────────────────
    digits.forEach((box, i) => {
        box.addEventListener('input', () => {
            // Allow only digits
            box.value = box.value.replace(/\D/g, '').slice(-1);
            if (box.value) {
                box.classList.add('filled');
                if (i < 5) digits[i + 1].focus();
            } else {
                box.classList.remove('filled');
            }
            syncHidden();
        });

        box.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !box.value && i > 0) {
                digits[i - 1].focus();
                digits[i - 1].value = '';
                digits[i - 1].classList.remove('filled');
                syncHidden();
            }
            // Allow paste on first box
        });

        box.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach((ch, j) => {
                if (digits[j]) {
                    digits[j].value = ch;
                    digits[j].classList.add('filled');
                }
            });
            syncHidden();
            const next = digits[Math.min(pasted.length, 5)];
            if (next) next.focus();
        });
    });

    function syncHidden() {
        const code = digits.map(d => d.value).join('');
        hidden.value = code;
        verifyBtn.disabled = code.length < 6;
    }

    // Mark error boxes on validation failure
    @if($errors->has('code'))
    digits.forEach(d => d.classList.add('error'));
    @endif

    // Auto-focus first box
    digits[0].focus();

    // ── 5-minute countdown ────────────────────────────────────────────
    let seconds = 300;
    const tick = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(tick);
            timerEl.textContent = '0:00';
            cntEl.classList.add('urgent');
            cntEl.innerHTML = '<strong>OTP expired.</strong> Please resend or start over.';
            verifyBtn.disabled = true;
            return;
        }
        const m = Math.floor(seconds / 60);
        const s = String(seconds % 60).padStart(2, '0');
        timerEl.textContent = `${m}:${s}`;
        if (seconds <= 60) cntEl.classList.add('urgent');
    }, 1000);
})();
</script>

</body>
</html>
