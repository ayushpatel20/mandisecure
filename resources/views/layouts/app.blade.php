<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; }

        /* Desktop sidebar */
        .sidebar { min-height: calc(100vh - 56px); background: #fff; border-right: 1px solid #dee2e6; }
        .sidebar .nav-link,
        .offcanvas-sidebar .nav-link {
            color: #495057; border-radius: 6px; margin-bottom: 2px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active,
        .offcanvas-sidebar .nav-link:hover,
        .offcanvas-sidebar .nav-link.active {
            background-color: #e9ecef; color: #0d6efd;
        }
        .sidebar .nav-link i,
        .offcanvas-sidebar .nav-link i { width: 20px; }

        /* Responsive main content padding */
        .main-content { padding: 1rem; }
        @media (min-width: 576px)  { .main-content { padding: 1.5rem; } }
        @media (min-width: 768px)  { .main-content { padding: 2rem; } }

        /* Username truncation in navbar */
        .navbar-username {
            display: inline-block;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }
        @media (min-width: 992px) { .navbar-username { max-width: 160px; } }

        /* Off-canvas sidebar */
        #sidebarOffcanvas { width: 260px; max-width: 82vw; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ─── Mobile sidebar drawer (hidden off-screen, triggered by hamburger) ─── --}}
<div class="offcanvas offcanvas-start offcanvas-sidebar"
     tabindex="-1"
     id="sidebarOffcanvas"
     aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header border-bottom py-2 bg-success bg-opacity-10">
        <div class="d-flex align-items-center gap-2" id="sidebarOffcanvasLabel">
            <i class="bi bi-shield-check text-success fs-5"></i>
            <span class="fw-bold" style="color:#1a6b3c">MandiSecure</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-2 pt-3">
        <ul class="nav flex-column">
            @yield('sidebar')
        </ul>
    </div>
</div>

{{-- ─── Top navbar ─────────────────────────────────────────────────────────── --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">

        {{-- Sidebar toggle — visible only on mobile (< md) --}}
        <button class="btn btn-sm btn-outline-light d-md-none me-1 py-1 px-2 flex-shrink-0"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarOffcanvas"
                aria-controls="sidebarOffcanvas"
                aria-label="Open navigation menu">
            <i class="bi bi-layout-sidebar fs-5"></i>
        </button>

        <a class="navbar-brand me-auto d-flex align-items-center gap-2" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="MandiSecure"
                 style="height:32px;width:auto;object-fit:contain;">
            <span class="d-none d-sm-inline">MandiSecure</span>
            <span class="d-sm-none">MS</span>
        </a>

        {{-- Cart icon: visible on all screen sizes without collapsing --}}
        @auth
            @if (Auth::user()->isBuyer())
                <a class="nav-link text-white position-relative me-1 flex-shrink-0"
                   href="{{ route('buyer.cart.index') }}"
                   title="My Cart">
                    <i class="bi bi-cart3 fs-5"></i>
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="font-size:0.65rem">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>
            @endif
        @endauth

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                {{-- Language switcher --}}
                <li class="nav-item dropdown me-lg-2">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-translate me-1"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width:120px">
                        <li><a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">{{ __('nav.english') }}</a></li>
                        <li><a class="dropdown-item {{ app()->getLocale() === 'hi' ? 'active' : '' }}" href="{{ route('language.switch', 'hi') }}">{{ __('nav.hindi') }}</a></li>
                        <li><a class="dropdown-item {{ app()->getLocale() === 'kn' ? 'active' : '' }}" href="{{ route('language.switch', 'kn') }}">{{ __('nav.kannada') }}</a></li>
                        <li><a class="dropdown-item {{ app()->getLocale() === 'ta' ? 'active' : '' }}" href="{{ route('language.switch', 'ta') }}">{{ __('nav.tamil') }}</a></li>
                    </ul>
                </li>

                {{-- User dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="navbar-username">{{ Auth::user()->name }}</span>
                        <span class="badge bg-warning text-dark ms-1 d-none d-lg-inline-block">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span>
                        </li>
                        <li>
                            <span class="dropdown-item-text text-muted small">
                                <i class="bi bi-person-badge me-1"></i>{{ ucfirst(Auth::user()->role) }}
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i> {{ __('nav.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

{{-- ─── Page body: sidebar + main ─────────────────────────────────────────── --}}
<div class="container-fluid">
    <div class="row">

        {{-- Desktop sidebar: hidden on mobile, shown on md+ --}}
        <nav class="col-md-2 col-lg-2 d-none d-md-block sidebar py-3 px-2">
            <ul class="nav flex-column">
                @yield('sidebar')
            </ul>
        </nav>

        <main class="col-12 col-md-10 col-lg-10 main-content">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
