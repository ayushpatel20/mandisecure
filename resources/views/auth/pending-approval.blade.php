<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --ms-green: #1a6b3c; --ms-green-dark: #0e3d20; --ms-gold: #e8a020; }
        body {
            background: linear-gradient(155deg, #061710 0%, #0e3b1f 35%, #1a6b3c 70%, #0e4224 100%);
            min-height: 100vh; font-family: 'Inter', sans-serif;
            display: flex; align-items: center; padding: 2rem 0;
        }
        .card {
            background: #fff; border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.25); border: none;
        }
        .status-icon {
            width: 80px; height: 80px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; margin: 0 auto 1.5rem;
        }
        .step-item {
            display: flex; align-items: flex-start; gap: 1rem;
            padding: 0.75rem; border-radius: 10px; transition: background 0.2s;
        }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 700;
        }
    </style>
</head>
<body>
<div class="container" style="max-width:520px">
    <div class="card p-4 p-md-5 text-center">

        @if(Auth::user()->status === 'rejected')
        {{-- Rejected State --}}
        <div class="status-icon" style="background:rgba(220,38,38,0.1)">
            <i class="bi bi-x-circle-fill" style="color:#dc2626"></i>
        </div>
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:800;color:#0d1117">
            Application Not Approved
        </h2>
        <p style="color:#6b7280;font-size:0.92rem;line-height:1.7;margin-bottom:1.5rem">
            Unfortunately your seller application has not been approved at this time.
            Please contact our support team for more information or to appeal the decision.
        </p>
        <a href="mailto:Headoffice@mandisecure.com"
           class="btn w-100 mb-3"
           style="background:var(--ms-green);color:#fff;font-weight:700;border-radius:10px;padding:0.75rem">
            <i class="bi bi-envelope me-2"></i>Contact Support
        </a>

        @else
        {{-- Pending State --}}
        <div class="status-icon" style="background:rgba(232,160,32,0.12)">
            <i class="bi bi-hourglass-split" style="color:var(--ms-gold)"></i>
        </div>
        <div style="display:inline-block;background:rgba(232,160,32,0.12);border:1px solid rgba(232,160,32,0.3);
                    color:#92600a;font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;
                    padding:0.3rem 1rem;border-radius:100px;margin-bottom:1rem">
            Pending Review
        </div>
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:800;color:#0d1117">
            Application Submitted!
        </h2>
        <p style="color:#6b7280;font-size:0.92rem;line-height:1.7;margin-bottom:1.5rem">
            Thank you, <strong style="color:#374151">{{ Auth::user()->name }}</strong>!
            Your seller application for <strong style="color:#374151">{{ Auth::user()->business_name }}</strong>
            has been received and is under review by our team.
        </p>

        {{-- What happens next --}}
        <div style="background:#f8faf8;border-radius:14px;padding:1.25rem;text-align:left;margin-bottom:1.5rem">
            <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;
                        color:#6b7280;margin-bottom:0.75rem">What happens next?</div>
            <div class="step-item">
                <div class="step-num" style="background:rgba(26,107,60,0.1);color:var(--ms-green)">1</div>
                <div>
                    <div style="font-weight:600;font-size:0.88rem;color:#374151">Application Review</div>
                    <div style="font-size:0.8rem;color:#9ca3af">Our team verifies your business details (1–2 business days)</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num" style="background:rgba(232,160,32,0.12);color:var(--ms-gold)">2</div>
                <div>
                    <div style="font-weight:600;font-size:0.88rem;color:#374151">Email Notification</div>
                    <div style="font-size:0.8rem;color:#9ca3af">You'll receive an email once approved or for any queries</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num" style="background:rgba(26,107,60,0.1);color:var(--ms-green)">3</div>
                <div>
                    <div style="font-weight:600;font-size:0.88rem;color:#374151">Start Selling</div>
                    <div style="font-size:0.8rem;color:#9ca3af">List your products and reach buyers across India</div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 p-3 mb-4"
             style="background:rgba(26,107,60,0.06);border-radius:10px;text-align:left">
            <i class="bi bi-envelope-fill" style="color:var(--ms-green);font-size:1.2rem;flex-shrink:0"></i>
            <div style="font-size:0.82rem;color:#374151">
                Questions? Email us at
                <a href="mailto:Headoffice@mandisecure.com"
                   style="color:var(--ms-green);font-weight:600;text-decoration:none">Headoffice@mandisecure.com</a>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm"
                    style="color:#6b7280;font-size:0.85rem;border:1px solid #e5e7eb;border-radius:8px;padding:0.4rem 1rem">
                <i class="bi bi-box-arrow-right me-1"></i>Sign Out
            </button>
        </form>
    </div>

    <p class="text-center mt-3" style="font-size:0.78rem;color:rgba(255,255,255,0.45)">
        &copy; {{ date('Y') }} Mandi Secure Private Limited
    </p>
</div>
</body>
</html>
