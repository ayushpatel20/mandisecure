<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MandiSecure — A Global Trades')</title>
    <meta name="description" content="@yield('meta_description', 'MandiSecure — India\'s premier agricultural marketplace connecting verified farmers, sellers, and buyers for coconut, vegetables, fruits, masala and more.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="MandiSecure">
    <meta property="og:title"       content="@yield('title', 'MandiSecure — A Global Trades')">
    <meta property="og:description" content="@yield('meta_description', 'India\'s premier agricultural marketplace — verified farmers, transparent pricing, pan-India delivery.')">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="{{ asset('images/og-cover.jpg') }}">
    <meta property="og:locale"      content="{{ app()->getLocale() }}">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:site"        content="@MandiSecure">
    <meta name="twitter:title"       content="@yield('title', 'MandiSecure — A Global Trades')">
    <meta name="twitter:description" content="@yield('meta_description', 'India\'s premier agricultural marketplace.')">
    <meta name="twitter:image"       content="{{ asset('images/og-cover.jpg') }}">

    @stack('seo')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @if(in_array(app()->getLocale(), ['hi','kn','ta']))
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&family=Noto+Sans+Kannada:wght@400;600;700&family=Noto+Sans+Tamil:wght@400;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ms-green:       #1a6b3c;
            --ms-green-dark:  #0e3d20;
            --ms-green-mid:   #155e33;
            --ms-green-light: #d4f0e0;
            --ms-gold:        #e8a020;
            --ms-gold-dark:   #c07a10;
            --ms-gold-light:  #fff3d0;
            --ms-dark:        #0d1117;
            --ms-body:        #374151;
            --ms-muted:       #6b7280;
            --ms-border:      #e5e7eb;
            --ms-surface:     #f8faf8;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 16px;
            line-height: 1.65;
            color: var(--ms-body);
            background: #fff;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        /* ─── Navbar ─── */
        .ms-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            padding: 1rem 0;
            transition: background 0.35s ease, box-shadow 0.35s ease, padding 0.35s ease;
        }

        .ms-navbar.scrolled {
            background: rgba(255,255,255,0.97) !important;
            box-shadow: 0 2px 24px rgba(0,0,0,0.08);
            padding: 0.55rem 0;
        }

        .ms-navbar.scrolled .nav-link { color: var(--ms-body) !important; }
        .ms-navbar.scrolled .nav-link:hover { color: var(--ms-green) !important; }
        .ms-navbar.scrolled .navbar-brand-text { color: var(--ms-dark) !important; }
        .ms-navbar.scrolled .brand-sub { color: var(--ms-muted) !important; }

        .ms-navbar .nav-link {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            font-size: 0.92rem;
            letter-spacing: 0.01em;
            padding: 0.4rem 0.8rem !important;
            transition: color 0.2s;
        }

        .ms-navbar .nav-link:hover,
        .ms-navbar .nav-link.active { color: var(--ms-gold) !important; }

        .ms-navbar.scrolled .nav-link.active { color: var(--ms-green) !important; }

        .navbar-brand { display: flex; align-items: center; gap: 0.6rem; text-decoration: none; }
        .brand-logo {
            height: 46px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.35));
        }
        .navbar-brand-text {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: #fff;
            line-height: 1.1;
            letter-spacing: 0.01em;
        }
        .brand-sub {
            font-family: 'Inter', sans-serif;
            font-size: 0.62rem;
            font-weight: 400;
            color: rgba(255,255,255,0.65);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            display: block;
            margin-top: 1px;
        }

        /* ─── Buttons ─── */
        .btn-ms-primary {
            background: var(--ms-green);
            color: #fff;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-ms-primary:hover {
            background: var(--ms-green-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(14,61,32,0.25);
        }

        .btn-ms-gold {
            background: var(--ms-gold);
            color: #fff;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-ms-gold:hover {
            background: var(--ms-gold-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(192,122,16,0.3);
        }

        .btn-outline-ms {
            border: 2px solid rgba(255,255,255,0.5);
            color: #fff;
            font-weight: 600;
            padding: 0.55rem 1.3rem;
            border-radius: 8px;
            background: transparent;
            transition: all 0.2s;
        }
        .btn-outline-ms:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        /* ─── Section Headings ─── */
        .section-eyebrow {
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--ms-green);
            margin-bottom: 0.5rem;
        }
        .section-eyebrow.light { color: var(--ms-gold); }

        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--ms-dark);
        }
        .section-title.light { color: #fff; }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--ms-muted);
            max-width: 560px;
            line-height: 1.7;
        }
        .section-subtitle.light { color: rgba(255,255,255,0.75); }

        /* ─── Utilities ─── */
        .text-ms-green { color: var(--ms-green) !important; }
        .text-ms-gold  { color: var(--ms-gold)  !important; }
        .bg-ms-green   { background: var(--ms-green) !important; }
        .bg-ms-dark    { background: var(--ms-dark)  !important; }

        .divider-leaf {
            display: inline-block;
            width: 48px;
            height: 3px;
            background: var(--ms-green);
            border-radius: 2px;
            margin-bottom: 1.5rem;
        }
        .divider-leaf.gold { background: var(--ms-gold); }

        /* ─── Toast / Flash ─── */
        .alert-ms-success {
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .ms-navbar { padding: 0.75rem 0; }
            .ms-navbar .navbar-collapse {
                background: rgba(14, 61, 32, 0.98);
                border-radius: 12px;
                padding: 1rem;
                margin-top: 0.5rem;
            }
        }
    </style>

    <!-- Legal page typography -->
    <style>
        .legal-body h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem; font-weight: 700;
            color: var(--ms-dark); margin-top: 2.25rem; margin-bottom: 0.75rem;
            padding-top: 0.5rem; border-top: 2px solid var(--ms-green-light);
        }
        .legal-body h4 {
            font-size: 1rem; font-weight: 700; color: var(--ms-dark);
            margin-top: 1.25rem; margin-bottom: 0.5rem;
        }
        .legal-body p, .legal-body li {
            font-size: 0.93rem; color: var(--ms-body); line-height: 1.8;
        }
        .legal-body ul, .legal-body ol { padding-left: 1.5rem; margin-bottom: 1rem; }
        .legal-body li { margin-bottom: 0.35rem; }
        .legal-body a { color: var(--ms-green); }
        .legal-body strong { color: var(--ms-dark); }
    </style>

    @stack('styles')
</head>
<body>

    @include('public.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('public.partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sticky navbar on scroll
        const nav = document.querySelector('.ms-navbar');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        }, { passive: true });
    </script>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/916366799332?text=Hello%2C%20I%20am%20interested%20in%20MandiSecure%20agricultural%20marketplace."
       target="_blank" rel="noopener"
       style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
              width:56px;height:56px;border-radius:50%;
              background:#25D366;color:#fff;
              display:flex;align-items:center;justify-content:center;
              box-shadow:0 4px 20px rgba(37,211,102,0.45);
              text-decoration:none;font-size:1.6rem;
              transition:transform 0.2s,box-shadow 0.2s"
       onmouseover="this.style.transform='scale(1.1)';this.style.boxShadow='0 8px 30px rgba(37,211,102,0.6)'"
       onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 20px rgba(37,211,102,0.45)'"
       aria-label="Chat on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    @stack('scripts')
</body>
</html>
