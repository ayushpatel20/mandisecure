<section style="background:linear-gradient(155deg,#061710 0%,#0e3b1f 35%,#1a6b3c 70%,#0e4224 100%);
               padding:5rem 0 3rem;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;background-image:
        repeating-linear-gradient(0deg,transparent,transparent 60px,rgba(255,255,255,0.012) 60px,rgba(255,255,255,0.012) 61px),
        repeating-linear-gradient(90deg,transparent,transparent 60px,rgba(255,255,255,0.012) 60px,rgba(255,255,255,0.012) 61px)">
    </div>
    <div class="container" style="position:relative;z-index:2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7">
                <div style="width:64px;height:64px;background:rgba(232,160,32,0.15);border:1px solid rgba(232,160,32,0.35);
                            border-radius:18px;display:flex;align-items:center;justify-content:center;
                            margin:0 auto 1.25rem;font-size:1.6rem;color:var(--ms-gold)">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <div style="display:inline-block;background:rgba(232,160,32,0.15);border:1px solid rgba(232,160,32,0.35);
                            color:var(--ms-gold);font-size:0.75rem;font-weight:700;letter-spacing:0.12em;
                            text-transform:uppercase;padding:0.3rem 1rem;border-radius:100px;margin-bottom:1rem">
                    Legal Document
                </div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3rem);
                           font-weight:800;color:#fff;line-height:1.1;margin-bottom:0.75rem">
                    {{ $title }}
                </h1>
                <p style="color:rgba(255,255,255,0.5);font-size:0.85rem;margin-bottom:0">
                    <i class="bi bi-calendar3 me-1"></i> Last Updated: {{ $updated }}
                    &nbsp;|&nbsp;
                    <i class="bi bi-building me-1"></i> Mandi Secure Private Limited
                </p>
            </div>
        </div>
    </div>
</section>
<div style="line-height:0;background:#0e3b1f">
    <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%">
        <path d="M0,0 C360,40 1080,40 1440,0 L1440,40 L0,40 Z" fill="#fff"/>
    </svg>
</div>
