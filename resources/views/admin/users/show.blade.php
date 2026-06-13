@extends('layouts.app')

@section('title', $user->name . ' — User Details')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'users'])
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    <div>
        <h4 class="mb-0 fw-bold">User Details</h4>
        <small class="text-muted">{{ $user->email }}</small>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible py-2 mb-3" style="border-radius:10px;font-size:0.88rem">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible py-2 mb-3" style="border-radius:10px;font-size:0.88rem">
    {{ session('error') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                {{-- Avatar --}}
                <div style="width:80px;height:80px;border-radius:50%;margin:0 auto 1rem;
                            background:{{ $user->role === 'seller' ? 'rgba(232,160,32,0.15)' : 'rgba(26,107,60,0.1)' }};
                            display:flex;align-items:center;justify-content:center;
                            font-size:2rem;font-weight:700;
                            color:{{ $user->role === 'seller' ? '#92600a' : '#1a6b3c' }}">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt=""
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover">
                    @else
                        {{ strtoupper(substr($user->name,0,1)) }}
                    @endif
                </div>

                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                @if($user->business_name)
                <div style="font-size:0.85rem;color:#6b7280;margin-bottom:0.5rem">
                    <i class="bi bi-building me-1"></i>{{ $user->business_name }}
                </div>
                @endif
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($user->role === 'seller')
                    <span class="badge" style="background:rgba(232,160,32,0.15);color:#92600a;border:1px solid rgba(232,160,32,0.3)">
                        <i class="bi bi-shop me-1"></i>Seller
                    </span>
                    @else
                    <span class="badge" style="background:rgba(26,107,60,0.1);color:#1a6b3c;border:1px solid rgba(26,107,60,0.2)">
                        <i class="bi bi-cart3 me-1"></i>Buyer
                    </span>
                    @endif
                    @php
                    $sc = ['active'=>'success','pending'=>'warning','blocked'=>'danger','rejected'=>'secondary'];
                    @endphp
                    <span class="badge bg-{{ $sc[$user->status] ?? 'secondary' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>

                <div style="font-size:0.82rem;color:#6b7280">
                    Joined {{ $user->created_at->format('d M Y') }}
                </div>
            </div>

            {{-- Actions --}}
            <div class="card-footer bg-transparent border-top p-3">
                <div class="d-flex flex-column gap-2">
                    @if($user->role === 'seller' && $user->status === 'pending')
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                        @csrf
                        <button class="btn btn-success w-100" onclick="return confirm('Approve this seller?')">
                            <i class="bi bi-check-circle me-2"></i>Approve Seller
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                        @csrf
                        <button class="btn btn-outline-warning w-100" onclick="return confirm('Reject this seller?')">
                            <i class="bi bi-x-circle me-2"></i>Reject Application
                        </button>
                    </form>
                    @endif

                    @if($user->status === 'blocked')
                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                        @csrf
                        <button class="btn btn-outline-success w-100" onclick="return confirm('Unblock this user?')">
                            <i class="bi bi-unlock me-2"></i>Unblock User
                        </button>
                    </form>
                    @elseif($user->status === 'active')
                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                        @csrf
                        <button class="btn btn-outline-danger w-100" onclick="return confirm('Block this user?')">
                            <i class="bi bi-slash-circle me-2"></i>Block User
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold">Account Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3" style="font-size:0.9rem">
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Full Name</div>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Email Address</div>
                        <div class="fw-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Mobile</div>
                        <div class="fw-semibold">{{ $user->mobile ?? '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Account Status</div>
                        <div class="fw-semibold">{{ ucfirst($user->status) }}</div>
                    </div>
                    @if($user->business_name)
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Business Name</div>
                        <div class="fw-semibold">{{ $user->business_name }}</div>
                    </div>
                    @endif
                    @if($user->gst_number)
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">GST Number</div>
                        <div class="fw-semibold">{{ $user->gst_number }}</div>
                    </div>
                    @endif
                    @if($user->address)
                    <div class="col-12">
                        <div class="text-muted small mb-1">Address</div>
                        <div class="fw-semibold">{{ $user->address }}</div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Email Verified</div>
                        <div class="fw-semibold">
                            @if($user->email_verified_at)
                            <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Verified</span>
                            @else
                            <span class="text-muted"><i class="bi bi-clock me-1"></i>Not verified</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Registered</div>
                        <div class="fw-semibold">{{ $user->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        @if($user->role === 'seller')
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold">Seller Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center" style="font-size:0.9rem">
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-success">{{ $user->products()->count() }}</div>
                        <div class="text-muted small">Total Products</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-primary">{{ $user->products()->where('status','approved')->count() }}</div>
                        <div class="text-muted small">Approved</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-warning">{{ $user->products()->where('status','pending')->count() }}</div>
                        <div class="text-muted small">Pending</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($user->role === 'buyer')
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-bold">Buyer Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center" style="font-size:0.9rem">
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-success">{{ $user->orders()->count() }}</div>
                        <div class="text-muted small">Total Orders</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-primary">{{ $user->orders()->where('status','delivered')->count() }}</div>
                        <div class="text-muted small">Delivered</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-warning">{{ $user->orders()->where('status','pending')->count() }}</div>
                        <div class="text-muted small">Pending</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
