@php $active = $active ?? ''; @endphp

<li class="nav-item mb-1">
    <a href="{{ route('buyer.dashboard') }}"
       class="nav-link {{ $active === 'dashboard' ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> {{ __('nav.dashboard') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('buyer.products.index') }}"
       class="nav-link {{ $active === 'products' ? 'active' : '' }}">
        <i class="bi bi-grid me-2"></i> {{ __('nav.browse_products') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('buyer.categories.index') }}"
       class="nav-link {{ $active === 'categories' ? 'active' : '' }}">
        <i class="bi bi-tag me-2"></i> {{ __('nav.categories') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('buyer.cart.index') }}"
       class="nav-link {{ $active === 'cart' ? 'active' : '' }}">
        <i class="bi bi-cart3 me-2"></i> {{ __('nav.my_cart') }}
        @if ($cartCount > 0)
            <span class="badge bg-danger ms-1">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
        @endif
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('buyer.orders.index') }}"
       class="nav-link {{ $active === 'orders' ? 'active' : '' }}">
        <i class="bi bi-bag me-2"></i> {{ __('nav.my_orders') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('buyer.payments.index') }}"
       class="nav-link {{ $active === 'payments' ? 'active' : '' }}">
        <i class="bi bi-cash-stack me-2"></i> {{ __('nav.my_payments') }}
    </a>
</li>

<li class="nav-item mb-1">
    <a href="{{ route('buyer.profile.edit') }}" class="nav-link {{ $active === 'profile' ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i> {{ __('nav.profile') }}
    </a>
</li>
