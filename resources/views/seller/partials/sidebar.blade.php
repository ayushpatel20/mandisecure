@php $active = $active ?? ''; @endphp

<li class="nav-item mb-1">
    <a href="{{ route('seller.dashboard') }}"
       class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> {{ __('nav.dashboard') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('seller.products.index') }}"
       class="nav-link {{ $active === 'products' ? 'active' : '' }}">
        <i class="bi bi-box-seam me-2"></i> {{ __('nav.my_products') }}
        @php $pending = Auth::user()->products()->pending()->count(); @endphp
        @if ($pending > 0)
            <span class="badge bg-warning text-dark ms-1">{{ $pending }}</span>
        @endif
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('seller.products.create') }}"
       class="nav-link {{ $active === 'add-product' ? 'active' : '' }}">
        <i class="bi bi-plus-circle me-2"></i> {{ __('nav.add_product') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('seller.orders.index') }}"
       class="nav-link {{ $active === 'orders' ? 'active' : '' }}">
        <i class="bi bi-receipt me-2"></i> {{ __('nav.orders') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('seller.payments.index') }}"
       class="nav-link {{ $active === 'payments' ? 'active' : '' }}">
        <i class="bi bi-cash-stack me-2"></i> {{ __('nav.payments') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('seller.profile.edit') }}" class="nav-link {{ $active === 'profile' ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i> {{ __('nav.profile') }}
    </a>
</li>
