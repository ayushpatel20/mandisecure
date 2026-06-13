<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ms-green: #1a6b3c; --ms-green-dark: #0e3d20;
            --ms-gold: #e8a020; --ms-dark: #0d1117;
        }
        body {
            background: linear-gradient(155deg, #061710 0%, #0e3b1f 35%, #1a6b3c 70%, #0e4224 100%);
            min-height: 100vh; font-family: 'Inter', sans-serif;
            padding: 2rem 0;
        }
        .reg-card {
            background: #fff; border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .reg-header {
            background: linear-gradient(135deg, var(--ms-green-dark), var(--ms-green));
            padding: 2rem; text-align: center; color: #fff;
        }
        .brand-icon {
            width: 52px; height: 52px; background: rgba(255,255,255,0.15);
            border-radius: 14px; display: flex; align-items: center;
            justify-content: center; font-size: 1.5rem; margin: 0 auto 0.75rem;
        }
        .nav-tabs { border-bottom: 2px solid #e5e7eb; }
        .nav-tabs .nav-link {
            border: none; border-bottom: 3px solid transparent; border-radius: 0;
            font-weight: 600; color: #6b7280; padding: 0.9rem 1.5rem;
            transition: all 0.2s;
        }
        .nav-tabs .nav-link.active {
            color: var(--ms-green); border-bottom-color: var(--ms-green);
            background: transparent;
        }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #374151; }
        .form-control, .form-select {
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
        .input-group:focus-within .input-group-text { border-color: var(--ms-green); }
        .btn-register {
            background: var(--ms-green); color: #fff; border: none;
            font-weight: 700; padding: 0.75rem; border-radius: 10px;
            font-size: 1rem; transition: background 0.2s, transform 0.15s;
        }
        .btn-register:hover { background: var(--ms-green-dark); color: #fff; transform: translateY(-1px); }
        .btn-register-gold {
            background: var(--ms-gold); color: #fff; border: none;
            font-weight: 700; padding: 0.75rem; border-radius: 10px;
            font-size: 1rem; transition: background 0.2s, transform 0.15s;
        }
        .btn-register-gold:hover { background: #c07a10; color: #fff; transform: translateY(-1px); }
        .divider { border-top: 1px solid #e5e7eb; margin: 1.25rem 0; }
        .password-toggle { cursor: pointer; }
    </style>
</head>
<body>
<div class="container" style="max-width:580px">

    {{-- Brand Header --}}
    <div class="reg-header">
        <div class="brand-icon"><i class="bi bi-tree-fill"></i></div>
        <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:800">MandiSecure</div>
        <div style="font-size:0.72rem;letter-spacing:0.15em;text-transform:uppercase;
                    color:rgba(255,255,255,0.55)">A Global Trades</div>
        <p style="margin-top:0.75rem;margin-bottom:0;color:rgba(255,255,255,0.75);font-size:0.9rem">
            Create your account and join India's premier agri marketplace
        </p>
    </div>

    <div class="reg-card">
        <div class="p-0">

            {{-- Tabs --}}
            <ul class="nav nav-tabs nav-fill px-3 pt-2" id="regTab">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#buyerTab">
                        <i class="bi bi-cart3 me-2"></i>Register as Buyer
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sellerTab">
                        <i class="bi bi-shop me-2"></i>Register as Seller
                    </button>
                </li>
            </ul>

            <div class="tab-content p-4">

                {{-- ── BUYER TAB ── --}}
                <div class="tab-pane fade show active" id="buyerTab">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:36px;height:36px;border-radius:9px;background:rgba(26,107,60,0.1);
                                    display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-cart3" style="color:var(--ms-green)"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--ms-dark)">Buyer Account</div>
                            <div style="font-size:0.78rem;color:#6b7280">Browse and purchase agricultural products</div>
                        </div>
                    </div>

                    @if($errors->has('buyer_*') || ($errors->any() && old('_form') === 'buyer'))
                    <div class="alert alert-danger py-2 mb-3" style="font-size:0.85rem;border-radius:9px">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register.buyer') }}">
                        @csrf
                        <input type="hidden" name="_form" value="buyer">

                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Your full name" required>
                            </div>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="tel" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                       value="{{ old('mobile') }}" placeholder="+91 XXXXX XXXXX" required>
                            </div>
                            @error('mobile')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="you@example.com" required>
                            </div>
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="bpwd"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Min 8 chars" required>
                                    <span class="input-group-text password-toggle"
                                          onclick="togglePwd('bpwd','bpwdIcon')">
                                        <i id="bpwdIcon" class="bi bi-eye"></i>
                                    </span>
                                </div>
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Confirm Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation"
                                           class="form-control" placeholder="Repeat password" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register w-100">
                            <i class="bi bi-person-plus me-2"></i>Create Buyer Account
                        </button>
                    </form>
                </div>

                {{-- ── SELLER TAB ── --}}
                <div class="tab-pane fade" id="sellerTab">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:36px;height:36px;border-radius:9px;background:rgba(232,160,32,0.12);
                                    display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-shop" style="color:var(--ms-gold)"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--ms-dark)">Seller Account</div>
                            <div style="font-size:0.78rem;color:#6b7280">List products &amp; reach buyers nationwide</div>
                        </div>
                    </div>

                    <div class="alert py-2 mb-3" style="background:rgba(232,160,32,0.1);border:1px solid rgba(232,160,32,0.3);
                                border-radius:9px;font-size:0.82rem;color:#92600a">
                        <i class="bi bi-info-circle me-1"></i>
                        Seller accounts require admin approval before you can list products. You'll be notified once approved.
                    </div>

                    @if($errors->has('business_name') || ($errors->any() && old('_form') === 'seller'))
                    <div class="alert alert-danger py-2 mb-3" style="font-size:0.85rem;border-radius:9px">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register.seller') }}">
                        @csrf
                        <input type="hidden" name="_form" value="seller">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Owner / Contact Name *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="Your name" required>
                                </div>
                                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Business Name *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" name="business_name"
                                           class="form-control @error('business_name') is-invalid @enderror"
                                           value="{{ old('business_name') }}" placeholder="Your business" required>
                                </div>
                                @error('business_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">GST Number <span class="text-muted fw-normal">(optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                                <input type="text" name="gst_number"
                                       class="form-control @error('gst_number') is-invalid @enderror"
                                       value="{{ old('gst_number') }}" placeholder="22AAAAA0000A1Z5"
                                       style="text-transform:uppercase">
                            </div>
                            @error('gst_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Mobile Number *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="tel" name="mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile') }}" placeholder="+91 XXXXX XXXXX" required>
                                </div>
                                @error('mobile')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Email Address *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="you@business.com" required>
                                </div>
                                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="spwd"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Min 8 chars" required>
                                    <span class="input-group-text password-toggle"
                                          onclick="togglePwd('spwd','spwdIcon')">
                                        <i id="spwdIcon" class="bi bi-eye"></i>
                                    </span>
                                </div>
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Confirm Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation"
                                           class="form-control" placeholder="Repeat password" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register-gold w-100">
                            <i class="bi bi-shop me-2"></i>Submit Seller Application
                        </button>
                    </form>
                </div>

            </div>{{-- tab-content --}}

            <div class="divider mx-4"></div>
            <p class="text-center pb-4" style="font-size:0.88rem;color:#6b7280">
                Already have an account?
                <a href="{{ route('login') }}" style="color:var(--ms-green);font-weight:600;text-decoration:none">
                    Sign In
                </a>
            </p>
        </div>
    </div>

    <p class="text-center mt-3" style="font-size:0.78rem;color:rgba(255,255,255,0.45)">
        &copy; {{ date('Y') }} Mandi Secure Private Limited
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        ico.className = 'bi bi-eye';
    }
}

// Re-open seller tab if seller form had errors
@if(old('_form') === 'seller' && $errors->any())
document.addEventListener('DOMContentLoaded', function() {
    new bootstrap.Tab(document.querySelector('[data-bs-target="#sellerTab"]')).show();
});
@endif
</script>
</body>
</html>
