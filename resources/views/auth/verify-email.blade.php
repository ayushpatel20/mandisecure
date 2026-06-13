<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email — MandiSecure</title>
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
    </style>
</head>
<body>
<div class="container" style="max-width:520px">
    <div class="card p-4 p-md-5 text-center">

        <div class="status-icon" style="background:rgba(26,107,60,0.1)">
            <i class="bi bi-envelope-check-fill" style="color:var(--ms-green)"></i>
        </div>

        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:800;color:#0d1117">
            Verify Your Email Address
        </h2>

        <p style="color:#6b7280;font-size:0.92rem;line-height:1.7;margin-bottom:1.5rem">
            Thanks for registering, <strong style="color:#374151">{{ Auth::user()->name }}</strong>!
            Before you continue, please verify your email address by clicking the link we sent to
            <strong style="color:#374151">{{ Auth::user()->email }}</strong>.
        </p>

        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 text-start"
                 style="border-radius:10px;font-size:0.88rem">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div style="background:#f8faf8;border-radius:14px;padding:1.25rem;text-align:left;margin-bottom:1.5rem">
            <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#6b7280;margin-bottom:0.5rem">
                Didn't receive the email?
            </div>
            <p style="font-size:0.83rem;color:#6b7280;margin-bottom:0.75rem">
                Check your spam folder, or click below to receive a new verification link.
            </p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="btn w-100"
                        style="background:var(--ms-green);color:#fff;font-weight:700;border-radius:10px;padding:0.65rem">
                    <i class="bi bi-send me-2"></i>Resend Verification Email
                </button>
            </form>
        </div>

        <div class="d-flex align-items-center gap-3 p-3 mb-4"
             style="background:rgba(26,107,60,0.06);border-radius:10px;text-align:left">
            <i class="bi bi-envelope-fill" style="color:var(--ms-green);font-size:1.2rem;flex-shrink:0"></i>
            <div style="font-size:0.82rem;color:#374151">
                Need help? Email us at
                <a href="mailto:Headoffice@mandisecure.com"
                   style="color:var(--ms-green);font-weight:600;text-decoration:none">Headoffice@mandisecure.com</a>
            </div>
        </div>

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
