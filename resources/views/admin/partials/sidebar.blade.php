@php $active = $active ?? ''; @endphp

<li class="nav-item mb-1">
    <a href="{{ route('admin.dashboard') }}"
       class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> {{ __('nav.dashboard') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('admin.categories.index') }}"
       class="nav-link {{ $active === 'categories' ? 'active' : '' }}">
        <i class="bi bi-tag me-2"></i> {{ __('nav.categories') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('admin.products.index') }}"
       class="nav-link {{ $active === 'products' ? 'active' : '' }}">
        <i class="bi bi-box-seam me-2"></i> {{ __('nav.products') }}
        @php $pending = \App\Models\Product::pending()->count(); @endphp
        @if ($pending > 0)
            <span class="badge bg-warning text-dark ms-1">{{ $pending }}</span>
        @endif
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('admin.payments.index') }}"
       class="nav-link {{ $active === 'payments' ? 'active' : '' }}">
        <i class="bi bi-cash-stack me-2"></i> {{ __('nav.payments') }}
        @php $pendingPay = \App\Models\Payment::where('payment_status','pending')->count(); @endphp
        @if ($pendingPay > 0)
            <span class="badge bg-danger ms-1">{{ $pendingPay }}</span>
        @endif
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('admin.orders.index') }}"
       class="nav-link {{ $active === 'orders' ? 'active' : '' }}">
        <i class="bi bi-receipt me-2"></i> {{ __('nav.orders') }}
        @php $pendingOrders = \App\Models\Order::where('status','pending')->count(); @endphp
        @if ($pendingOrders > 0)
            <span class="badge bg-info text-dark ms-1">{{ $pendingOrders }}</span>
        @endif
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ $active === 'users' ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> {{ __('nav.users') }}
        @php $pendingSellers = \App\Models\User::where('role','seller')->where('status','pending')->count(); @endphp
        @if($pendingSellers > 0)
            <span class="badge bg-warning text-dark ms-1">{{ $pendingSellers }}</span>
        @endif
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('admin.settings.payment') }}"
       class="nav-link {{ $active === 'settings' ? 'active' : '' }}">
        <i class="bi bi-gear me-2"></i> {{ __('nav.settings') }}
    </a>
</li>
