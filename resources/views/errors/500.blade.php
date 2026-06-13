<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --ms-green: #1a6b3c; --ms-gold: #e8a020; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh; margin: 0;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(155deg, #1a0610 0%, #3b0e20 35%, #6b1a3c 70%, #420e24 100%);
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0;
            background-image:
                repeating-linear-gradient(0deg, transparent, transparent 60px, rgba(255,255,255,0.012) 60px, rgba(255,255,255,0.012) 61px),
                repeating-linear-gradient(90deg, transparent, transparent 60px, rgba(255,255,255,0.012) 60px, rgba(255,255,255,0.012) 61px);
        }
        .error-wrap {
            position: relative; z-index: 2; text-align: center;
            padding: 2rem; max-width: 560px; width: 100%;
        }
        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: clamp(6rem, 18vw, 10rem); font-weight: 800; line-height: 1;
            color: transparent; -webkit-text-stroke: 2px rgba(232,100,100,0.6);
            letter-spacing: -0.04em; margin-bottom: 0;
        }
        .error-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 4vw, 2.2rem); font-weight: 700;
            color: #fff; margin-bottom: 1rem;
        }
        .error-sub { color: rgba(255,255,255,0.6); font-size: 1rem; line-height: 1.7; margin-bottom: 2rem; }
        .btn-home {
            background: var(--ms-gold); color: #1a1a1a; border: none;
            padding: 0.75rem 2rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-home:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(232,160,32,0.35); color: #1a1a1a; }
        .btn-back {
            background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600; font-size: 0.95rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
            transition: background 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.14); color: #fff; }
        .brand {
            margin-bottom: 2rem; display: flex; align-items: center;
            justify-content: center; gap: 0.6rem;
        }
        .brand-icon {
            width: 40px; height: 40px; background: var(--ms-green); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-name { font-family: 'Playfair Display', serif; font-weight: 800; font-size: 1.25rem; color: #fff; }
    </style>
</head>
<body>
    <div class="error-wrap">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-tree-fill text-white"></i></div>
            <span class="brand-name">MandiSecure</span>
        </div>
        <div class="error-code">500</div>
        <h1 class="error-title">Server Error</h1>
        <p class="error-sub">
            Something went wrong on our end. Our team has been notified and
            is working to fix it. Please try again in a moment.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="/" class="btn-home">
                <i class="bi bi-house-fill"></i> Go to Homepage
            </a>
            <a href="javascript:location.reload()" class="btn-back">
                <i class="bi bi-arrow-clockwise"></i> Try Again
            </a>
        </div>
        <div style="margin-top:2rem;font-size:0.8rem;color:rgba(255,255,255,0.3)">
            Persistent issue? <a href="/contact" style="color:rgba(232,160,32,0.7);text-decoration:none">Contact Support</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
