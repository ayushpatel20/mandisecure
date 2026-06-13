<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ms-green: #1a6b3c;
            --ms-gold:  #e8a020;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(155deg, #0d2d1a 0%, #1a6b3c 50%, #0e4224 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            color: #fff; text-align: center;
        }
        .err-num {
            font-family: 'Playfair Display', serif;
            font-size: clamp(5rem, 20vw, 10rem);
            font-weight: 800;
            line-height: 1;
            color: transparent;
            -webkit-text-stroke: 3px var(--ms-gold);
            letter-spacing: -0.02em;
        }
        .err-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(232,160,32,0.15);
            border: 2px solid rgba(232,160,32,0.4);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: var(--ms-gold);
            margin: 0 auto 1.5rem;
        }
        .btn-home {
            background: var(--ms-gold); color: #fff; border: none;
            font-weight: 600; padding: 0.65rem 1.6rem;
            border-radius: 8px; text-decoration: none;
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.95rem; transition: background 0.2s;
        }
        .btn-home:hover { background: #c07a10; color: #fff; }
        .btn-back {
            background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.25); font-weight: 500;
            padding: 0.65rem 1.6rem; border-radius: 8px; text-decoration: none;
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.95rem; transition: background 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.18); color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="err-icon"><i class="bi bi-clock-history"></i></div>
                <div class="err-num">419</div>
                <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;margin:1.25rem 0 0.75rem">
                    Session Expired
                </h1>
                <p style="color:rgba(255,255,255,0.65);max-width:420px;margin:0 auto 2rem;line-height:1.7">
                    Your session has timed out or the page security token has expired.
                    Please go back and try again — it only takes a moment.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="javascript:history.back()" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Go Back
                    </a>
                    <a href="/" class="btn-home">
                        <i class="bi bi-house-fill"></i> Home
                    </a>
                </div>
                <div style="margin-top:3rem;font-size:0.82rem;color:rgba(255,255,255,0.35)">
                    <a href="/" style="color:rgba(255,255,255,0.45);text-decoration:none">
                        <i class="bi bi-tree-fill me-1" style="color:var(--ms-gold)"></i>MandiSecure
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
