<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --ms-green: #1a6b3c; --ms-green-dark: #0e3d20; }
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
        .form-control:focus { border-color: var(--ms-green); box-shadow: 0 0 0 3px rgba(26,107,60,0.12); }
        .input-group-text {
            background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 9px 0 0 9px;
            color: var(--ms-green); cursor: pointer;
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
                <i class="bi bi-key"></i>
            </div>
            <div style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:800">Set New Password</div>
            <p style="margin-top:0.5rem;margin-bottom:0;color:rgba(255,255,255,0.7);font-size:0.88rem">
                Choose a strong password for your account
            </p>
        </div>

        <div class="p-4">

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:0.85rem;border-radius:9px">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $email ?? '') }}" required>
                    </div>
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="npwd"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min 8 characters" required>
                        <span class="input-group-text" onclick="togglePwd('npwd','npwdIcon')">
                            <i id="npwdIcon" class="bi bi-eye"></i>
                        </span>
                    </div>
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="cpwd"
                               class="form-control" placeholder="Repeat password" required>
                        <span class="input-group-text" onclick="togglePwd('cpwd','cpwdIcon')">
                            <i id="cpwdIcon" class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3 p-3" style="background:#f8faf8;border-radius:9px;font-size:0.82rem;color:#6b7280">
                    <strong style="color:#374151">Password requirements:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <li>Minimum 8 characters</li>
                        <li>Mix of letters and numbers recommended</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-submit w-100">
                    <i class="bi bi-check-lg me-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>
    <p class="text-center mt-3" style="font-size:0.78rem;color:rgba(255,255,255,0.45)">
        &copy; {{ date('Y') }} Mandi Secure Private Limited
    </p>
</div>
<script>
function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
</body>
</html>
