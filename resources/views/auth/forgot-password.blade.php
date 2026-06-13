<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — MandiSecure</title>
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
        .auth-card {
            background: #fff; border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.25); overflow: hidden;
        }
        .auth-header {
            background: linear-gradient(135deg, var(--ms-green-dark), var(--ms-green));
            padding: 2rem; text-align: center; color: #fff;
        }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #374151; }
        .form-control {
            border: 1.5px solid #e5e7eb; border-radius: 9px;
            padding: 0.65rem 1rem; font-size: 0.9rem; transition: border 0.2s;
        }
        .form-control:focus {
            border-color: var(--ms-green);
            box-shadow: 0 0 0 3px rgba(26,107,60,0.12);
        }
        .input-group-text {
            background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 9px 0 0 9px;
            color: var(--ms-green);
        }
        .input-group .form-control { border-radius: 0 9px 9px 0; border-left: none; }
        .btn-submit {
            background: var(--ms-green); color: #fff; border: none; font-weight: 700;
            padding: 0.75rem; border-radius: 10px; font-size: 1rem; transition: background 0.2s;
        }
        .btn-submit:hover { background: var(--ms-green-dark); color: #fff; }
    </style>
</head>
<body>
<div class="container" style="max-width:460px">
    <div class="auth-card">
        <div class="auth-header">
            <div style="width:52px;height:52px;background:rgba(255,255,255,0.15);border-radius:14px;
                        display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 0.75rem">
                <i class="bi bi-shield-lock"></i>
            </div>
            <div style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:800">Forgot Password?</div>
            <p style="margin-top:0.5rem;margin-bottom:0;color:rgba(255,255,255,0.7);font-size:0.88rem">
                Enter your email and we'll send a reset link
            </p>
        </div>

        <div class="p-4">

            @if(session('status'))
            <div class="alert py-2 mb-3" style="background:#ecfdf5;border:1px solid #6ee7b7;
                        color:#065f46;border-radius:9px;font-size:0.88rem">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:0.85rem;border-radius:9px">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <p style="font-size:0.88rem;color:#6b7280;margin-bottom:1.5rem;line-height:1.6">
                No problem! Enter the email address associated with your account and we'll email you a link to reset your password.
            </p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    </div>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-submit w-100">
                    <i class="bi bi-send me-2"></i>Send Reset Link
                </button>
            </form>

            <div style="border-top:1px solid #e5e7eb;margin-top:1.5rem;padding-top:1.25rem;text-align:center">
                <a href="{{ route('login') }}" style="color:var(--ms-green);font-size:0.88rem;
                           font-weight:600;text-decoration:none">
                    <i class="bi bi-arrow-left me-1"></i>Back to Login
                </a>
            </div>
        </div>
    </div>
    <p class="text-center mt-3" style="font-size:0.78rem;color:rgba(255,255,255,0.45)">
        &copy; {{ date('Y') }} Mandi Secure Private Limited
    </p>
</div>
</body>
</html>
