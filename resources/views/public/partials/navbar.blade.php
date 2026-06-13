<nav class="ms-navbar" id="msNavbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">

            {{-- Brand --}}
            <a href="{{ route('public.home') }}" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="MandiSecure" class="brand-logo">
                <div>
                    <div class="navbar-brand-text">MandiSecure</div>
                    <span class="brand-sub">{{ __('nav.tagline') }}</span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="{{ route('public.home') }}"
                   class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}">
                    {{ __('nav.home') }}
                </a>
                <a href="{{ route('public.about') }}"
                   class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}">
                    {{ __('nav.about') }}
                </a>
                <a href="{{ route('public.contact') }}"
                   class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}">
                    {{ __('nav.contact') }}
                </a>

                {{-- Language Switcher --}}
                <div class="dropdown ms-2">
                    <button class="btn btn-link nav-link dropdown-toggle p-0 px-2" type="button"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-translate me-1"></i> {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width:130px">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                               href="{{ route('language.switch', 'en') }}">
                                {{ __('nav.english') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'hi' ? 'active' : '' }}"
                               href="{{ route('language.switch', 'hi') }}">
                                {{ __('nav.hindi') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'kn' ? 'active' : '' }}"
                               href="{{ route('language.switch', 'kn') }}">
                                {{ __('nav.kannada') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === 'ta' ? 'active' : '' }}"
                               href="{{ route('language.switch', 'ta') }}">
                                {{ __('nav.tamil') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="ms-3 d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ Auth::user()->dashboardRoute() }}" class="btn btn-ms-primary btn-sm">
                            <i class="bi bi-speedometer2 me-1"></i> {{ __('nav.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline-ms btn btn-sm">{{ __('nav.login') }}</a>
                        <a href="{{ route('public.contact') }}" class="btn btn-ms-gold btn-sm">
                            {{ __('nav.start_selling') }} <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Mobile Toggle --}}
            <button class="d-lg-none btn btn-link p-1" id="mobileMenuBtn"
                    style="color:rgba(255,255,255,0.9);font-size:1.4rem;"
                    aria-label="Menu">
                <i class="bi bi-list" id="mobileMenuIcon"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="d-lg-none" style="display:none!important">
            <div class="d-flex flex-column gap-1 pt-2 pb-1">
                <a href="{{ route('public.home') }}"
                   class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}">
                    <i class="bi bi-house me-2"></i>{{ __('nav.home') }}
                </a>
                <a href="{{ route('public.about') }}"
                   class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}">
                    <i class="bi bi-info-circle me-2"></i>{{ __('nav.about') }}
                </a>
                <a href="{{ route('public.contact') }}"
                   class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}">
                    <i class="bi bi-envelope me-2"></i>{{ __('nav.contact') }}
                </a>

                {{-- Mobile Language Switcher --}}
                <div class="d-flex gap-2 mt-1">
                    <a href="{{ route('language.switch', 'en') }}"
                       class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-light' : 'btn-outline-light' }}">EN</a>
                    <a href="{{ route('language.switch', 'hi') }}"
                       class="btn btn-sm {{ app()->getLocale() === 'hi' ? 'btn-light' : 'btn-outline-light' }}">HI</a>
                    <a href="{{ route('language.switch', 'kn') }}"
                       class="btn btn-sm {{ app()->getLocale() === 'kn' ? 'btn-light' : 'btn-outline-light' }}">KN</a>
                    <a href="{{ route('language.switch', 'ta') }}"
                       class="btn btn-sm {{ app()->getLocale() === 'ta' ? 'btn-light' : 'btn-outline-light' }}">TA</a>
                </div>

                <hr style="border-color:rgba(255,255,255,0.2);margin:0.4rem 0">
                @auth
                    <a href="{{ Auth::user()->dashboardRoute() }}"
                       class="btn btn-ms-primary mt-1 w-100">{{ __('nav.dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-ms btn mt-1 w-100">{{ __('nav.login') }}</a>
                    <a href="{{ route('public.contact') }}" class="btn btn-ms-gold mt-2 w-100">
                        {{ __('nav.start_selling') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn  = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        const icon = document.getElementById('mobileMenuIcon');
        let open = false;
        btn.addEventListener('click', function () {
            open = !open;
            menu.style.display = open ? 'block' : 'none';
            icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
        });
    });
</script>
