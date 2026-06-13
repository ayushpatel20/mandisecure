@extends('layouts.app')

@section('title', 'My Profile — MandiSecure Seller')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'profile'])
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold">My Profile</h4>
        <small class="text-muted">Update your business information and account settings</small>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible py-2 mb-3" style="border-radius:10px;font-size:0.88rem">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- Profile Card --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm text-center p-4">
            <div style="width:90px;height:90px;border-radius:50%;margin:0 auto 1rem;
                        background:rgba(232,160,32,0.12);overflow:hidden;
                        display:flex;align-items:center;justify-content:center">
                @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                     alt="Profile" style="width:90px;height:90px;object-fit:cover">
                @else
                <span style="font-size:2.2rem;font-weight:700;color:#92600a">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </span>
                @endif
            </div>
            <div class="fw-bold" style="color:#0d1117">{{ Auth::user()->name }}</div>
            @if(Auth::user()->business_name)
            <div class="text-muted" style="font-size:0.82rem">{{ Auth::user()->business_name }}</div>
            @endif
            <div class="text-muted" style="font-size:0.78rem">{{ Auth::user()->email }}</div>
            <span class="badge mt-2" style="background:rgba(232,160,32,0.15);color:#92600a;
                  border:1px solid rgba(232,160,32,0.3)">
                <i class="bi bi-shop me-1"></i>Seller
            </span>
            @if(Auth::user()->gst_number)
            <div class="mt-2 p-2" style="background:#f8faf8;border-radius:8px;font-size:0.75rem;color:#6b7280">
                <i class="bi bi-file-earmark-text me-1"></i>GST: {{ Auth::user()->gst_number }}
            </div>
            @endif
        </div>
    </div>

    {{-- Edit Forms --}}
    <div class="col-lg-9">

        {{-- Business Info Form --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shop me-2 text-warning"></i>Business Information</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Contact Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Business Name *</label>
                            <input type="text" name="business_name"
                                   class="form-control @error('business_name') is-invalid @enderror"
                                   value="{{ old('business_name', Auth::user()->business_name) }}" required>
                            @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">GST Number <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="gst_number"
                                   class="form-control @error('gst_number') is-invalid @enderror"
                                   value="{{ old('gst_number', Auth::user()->gst_number) }}"
                                   placeholder="22AAAAA0000A1Z5" style="text-transform:uppercase">
                            @error('gst_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                            <label class="form-label fw-semibold small">Business Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                      rows="3" placeholder="Your business address">{{ old('address', Auth::user()->address) }}</textarea>
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

        {{-- Change Password --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2 text-warning"></i>Change Password</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('seller.profile.password') }}">
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
