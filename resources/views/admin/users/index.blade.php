@extends('layouts.app')

@section('title', 'User Management — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'users'])
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold">User Management</h4>
        <small class="text-muted">Manage buyers, sellers and their account status</small>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @php
    $statCards = [
        ['label'=>'Total Users',    'val'=>$counts['total'],   'icon'=>'people-fill',    'color'=>'primary'],
        ['label'=>'Buyers',         'val'=>$counts['buyers'],  'icon'=>'cart-fill',      'color'=>'success'],
        ['label'=>'Sellers',        'val'=>$counts['sellers'], 'icon'=>'shop',           'color'=>'warning'],
        ['label'=>'Pending Approval','val'=>$counts['pending'],'icon'=>'hourglass-split','color'=>'info'],
        ['label'=>'Blocked',        'val'=>$counts['blocked'], 'icon'=>'slash-circle',   'color'=>'danger'],
    ];
    @endphp
    @foreach($statCards as $c)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle bg-{{ $c['color'] }} bg-opacity-10 p-2">
                    <i class="bi bi-{{ $c['icon'] }} text-{{ $c['color'] }}"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.72rem">{{ $c['label'] }}</div>
                    <div class="fw-bold fs-5">{{ $c['val'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible py-2 mb-3" role="alert" style="border-radius:10px;font-size:0.88rem">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, email, mobile, business…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="buyer"  {{ request('role') === 'buyer'  ? 'selected' : '' }}>Buyer</option>
                    <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="blocked"  {{ request('status') === 'blocked'  ? 'selected' : '' }}>Blocked</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request('search') || request('role') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($users->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people text-muted" style="font-size:2.5rem"></i>
            <p class="text-muted mt-2 mb-0">No users found matching your criteria.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.88rem">
                <thead style="background:#f8fafc;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280">
                    <tr>
                        <th class="ps-4 py-3">User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Mobile</th>
                        <th>Registered</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:38px;height:38px;border-radius:50%;flex-shrink:0;
                                        background:{{ $user->role === 'seller' ? 'rgba(232,160,32,0.15)' : 'rgba(26,107,60,0.1)' }};
                                        display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;
                                        color:{{ $user->role === 'seller' ? '#92600a' : '#1a6b3c' }}">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="color:#0d1117">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:0.78rem">{{ $user->email }}</div>
                                @if($user->business_name)
                                <div style="font-size:0.75rem;color:#6b7280"><i class="bi bi-building me-1"></i>{{ $user->business_name }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->role === 'seller')
                        <span class="badge" style="background:rgba(232,160,32,0.15);color:#92600a;border:1px solid rgba(232,160,32,0.3)">
                            <i class="bi bi-shop me-1"></i>Seller
                        </span>
                        @else
                        <span class="badge" style="background:rgba(26,107,60,0.1);color:#1a6b3c;border:1px solid rgba(26,107,60,0.2)">
                            <i class="bi bi-cart3 me-1"></i>Buyer
                        </span>
                        @endif
                    </td>
                    <td>
                        @php
                        $statusCfg = [
                            'active'   => ['bg-success', 'check-circle-fill', 'Active'],
                            'pending'  => ['bg-warning text-dark', 'hourglass-split', 'Pending'],
                            'blocked'  => ['bg-danger', 'slash-circle', 'Blocked'],
                            'rejected' => ['bg-secondary', 'x-circle', 'Rejected'],
                        ];
                        [$cls, $ico, $lbl] = $statusCfg[$user->status] ?? ['bg-secondary','question-circle','Unknown'];
                        @endphp
                        <span class="badge {{ $cls }}">
                            <i class="bi bi-{{ $ico }} me-1"></i>{{ $lbl }}
                        </span>
                    </td>
                    <td style="color:#6b7280">{{ $user->mobile ?? '—' }}</td>
                    <td style="color:#6b7280">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="pe-4 text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="btn btn-sm btn-outline-secondary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($user->role === 'seller' && $user->status === 'pending')
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Approve Seller"
                                        onclick="return confirm('Approve {{ $user->name }}?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning" title="Reject"
                                        onclick="return confirm('Reject {{ $user->name }}?')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                            @endif
                            @if($user->status === 'blocked')
                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Unblock"
                                        onclick="return confirm('Unblock {{ $user->name }}?')">
                                    <i class="bi bi-unlock"></i>
                                </button>
                            </form>
                            @elseif($user->status !== 'blocked')
                            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Block"
                                        onclick="return confirm('Block {{ $user->name }}?')">
                                    <i class="bi bi-slash-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $users->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
