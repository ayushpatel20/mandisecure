<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('login.title') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @if(in_array(app()->getLocale(), ['hi','kn','ta']))
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        body {
            background: linear-gradient(135deg, #198754 0%, #0d6efd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            @if(in_array(app()->getLocale(), ['hi','kn','ta']))
            font-family: 'Noto Sans', sans-serif;
            @endif
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .brand-logo { font-size: 2rem; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <div class="brand-logo text-white fw-bold">
                    <i class="bi bi-shield-check me-1"></i> MandiSecure
                </div>
                <p class="text-white-50 mt-1">{{ __('login.subtitle') }}</p>
            </div>

            <div class="card login-card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 text-center fw-semibold">{{ __('login.title') }}</h5>

                    @if(session('status'))
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('login.email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    placeholder="you@example.com"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('login.password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required
                                    placeholder="••••••••"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">{{ __('login.remember_me') }}</label>
                            </div>
                            <a href="{{ route('password.request') }}"
                               style="font-size:0.85rem;color:#1a6b3c;text-decoration:none;font-weight:500">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('login.sign_in') }}
                        </button>
                    </form>

                    <hr class="my-3">
                    <p class="text-center mb-0" style="font-size:0.88rem;color:#6b7280">
                        New to MandiSecure?
                        <a href="{{ route('register') }}" style="color:#1a6b3c;font-weight:600;text-decoration:none">
                            Create an account
                        </a>
                    </p>
                </div>
            </div>

            {{-- Language switcher on login page --}}
            <div class="text-center mt-3">
                <a href="{{ route('language.switch', 'en') }}"
                   class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-light' : 'btn-outline-light' }} me-1">EN</a>
                <a href="{{ route('language.switch', 'hi') }}"
                   class="btn btn-sm {{ app()->getLocale() === 'hi' ? 'btn-light' : 'btn-outline-light' }} me-1">HI</a>
                <a href="{{ route('language.switch', 'kn') }}"
                   class="btn btn-sm {{ app()->getLocale() === 'kn' ? 'btn-light' : 'btn-outline-light' }} me-1">KN</a>
                <a href="{{ route('language.switch', 'ta') }}"
                   class="btn btn-sm {{ app()->getLocale() === 'ta' ? 'btn-light' : 'btn-outline-light' }}">TA</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
