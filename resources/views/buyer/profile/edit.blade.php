@extends('layouts.app')

@section('title', 'My Profile — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'profile'])
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold">My Profile</h4>
        <small class="text-muted">Update your account information and password</small>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible py-2 mb-3" style="border-radius:10px;font-size:0.88rem">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- Profile Photo Card --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm text-center p-4">
            <div style="width:90px;height:90px;border-radius:50%;margin:0 auto 1rem;
                        background:rgba(26,107,60,0.1);overflow:hidden;
                        display:flex;align-items:center;justify-content:center">
                @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                     alt="Profile" style="width:90px;height:90px;object-fit:cover">
                @else
                <span style="font-size:2.2rem;font-weight:700;color:#1a6b3c">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </span>
                @endif
            </div>
            <div class="fw-bold" style="color:#0d1117">{{ Auth::user()->name }}</div>
            <div class="text-muted" style="font-size:0.82rem">{{ Auth::user()->email }}</div>
            <span class="badge mt-2" style="background:rgba(26,107,60,0.1);color:#1a6b3c;
                  border:1px solid rgba(26,107,60,0.2)">
                <i class="bi bi-cart3 me-1"></i>Buyer
            </span>
        </div>
    </div>

    {{-- Edit Forms --}}
    <div class="col-lg-9">

        {{-- Profile Info Form --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2 text-success"></i>Personal Information</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('buyer.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Full Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Mobile Number *</label>
                            <input type="tel" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                   value="{{ old('mobile', Auth::user()->mobile) }}" required>
                            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" class="form-control bg-light"
                                   value="{{ Auth::user()->email }}" disabled>
                            <div class="form-text">Email cannot be changed. Contact support if needed.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Delivery Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                      rows="3" placeholder="Your default delivery address">{{ old('address', Auth::user()->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg,image/webp">
                            <div class="form-text">Max 2MB — JPG, PNG, WEBP</div>
                            @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password Form --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Change Password</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('buyer.profile.password') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Current Password *</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Current password" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">New Password *</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 8 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Confirm New Password *</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" placeholder="Repeat new password" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
