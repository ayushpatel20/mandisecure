@extends('public.layouts.app')

@section('title', __('about.page_title') !== 'about.page_title' ? __('about.page_title') : 'About Us — MandiSecure')
@section('meta_description', 'Learn about MandiSecure — our mission to digitise India\'s agricultural supply chain, our founding story, leadership team, and core values.')

@push('seo')
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/about') }}">
@endpush

@push('styles')
<style>
.about-hero {
    min-height: 60vh;
    background:
        linear-gradient(rgba(6,23,16,0.72), rgba(14,59,31,0.72)),
        url('{{ asset('images/about.png') }}') center center / cover no-repeat;
    position: relative; overflow: hidden;
    display: flex; align-items: center;
    padding-top: 80px;
}
.about-hero::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        repeating-linear-gradient(0deg, transparent, transparent 60px,
            rgba(255,255,255,0.012) 60px, rgba(255,255,255,0.012) 61px),
        repeating-linear-gradient(90deg, transparent, transparent 60px,
            rgba(255,255,255,0.012) 60px, rgba(255,255,255,0.012) 61px);
}

.team-card {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--ms-border);
    background: #fff;
    transition: transform 0.25s, box-shadow 0.25s;
    text-align: center;
}
.team-card:hover { transform: translateY(-5px); box-shadow: 0 16px 48px rgba(0,0,0,0.1); }
.team-avatar {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: var(--ms-green-light);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem;
    margin: 1.5rem auto 1rem;
    border: 3px solid #fff;
    box-shadow: 0 4px 16px rgba(26,107,60,0.15);
}

.info-row {
    display: flex; gap: 1rem; align-items: flex-start;
    padding: 1.25rem 0; border-bottom: 1px solid var(--ms-border);
}
.info-row:last-child { border-bottom: none; }
.info-icon {
    width: 42px; height: 42px; flex-shrink: 0;
    border-radius: 10px; background: var(--ms-green-light);
    display: flex; align-items: center; justify-content: center;
    color: var(--ms-green); font-size: 1rem;
}

.about-pillar {
    text-align: center; padding: 2rem 1.5rem;
    border-radius: 16px;
    background: #fff;
    border: 1px solid var(--ms-border);
    height: 100%;
    transition: transform 0.2s, box-shadow 0.2s;
}
.about-pillar:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.07); }
.about-pillar-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: linear-gradient(135deg, var(--ms-green), #2d9e5e);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: #fff;
    margin: 0 auto 1.25rem;
    box-shadow: 0 8px 20px rgba(26,107,60,0.25);
}
</style>
@endpush

@section('content')

{{-- ─── Hero ─── --}}
<section class="about-hero">
    <div class="container" style="position:relative;z-index:2;padding:5rem 0 4rem">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="hero-eyebrow" style="display:inline-flex;align-items:center;gap:0.5rem;
                     background:rgba(232,160,32,0.15);border:1px solid rgba(232,160,32,0.35);
                     color:var(--ms-gold);font-size:0.78rem;font-weight:600;letter-spacing:0.12em;
                     text-transform:uppercase;padding:0.35rem 1rem;border-radius:100px;margin-bottom:1.25rem">
                    <i class="bi bi-building"></i> {{ __('about.hero_eyebrow') }}
                </div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2.2rem,5vw,3.8rem);
                           font-weight:800;color:#fff;line-height:1.1;margin-bottom:1rem">
                    {{ __('about.hero_title') }}
                </h1>
                <p style="font-size:1.05rem;color:rgba(255,255,255,0.65);max-width:560px;
                           margin:0 auto;line-height:1.75">
                    {{ __('about.hero_sub') }}
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
                    <span style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                                 color:rgba(255,255,255,0.8);font-size:0.8rem;font-weight:600;
                                 padding:0.3rem 0.9rem;border-radius:100px">
                        <i class="bi bi-calendar3 me-1"></i>{{ __('about.est_badge') }}
                    </span>
                    <span style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                                 color:rgba(255,255,255,0.8);font-size:0.8rem;font-weight:600;
                                 padding:0.3rem 0.9rem;border-radius:100px">
                        <i class="bi bi-geo-alt me-1"></i>{{ __('about.hq_badge') }}
                    </span>
                    <span style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                                 color:rgba(255,255,255,0.8);font-size:0.8rem;font-weight:600;
                                 padding:0.3rem 0.9rem;border-radius:100px">
                        <i class="bi bi-globe2 me-1"></i>{{ __('about.ops_badge') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Wave ─── --}}
<div style="line-height:0;background:#0e3b1f">
    <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%">
        <path d="M0,0 C360,56 1080,56 1440,0 L1440,56 L0,56 Z" fill="#fff"/>
    </svg>
</div>

{{-- ─── Story ─── --}}
<section class="py-5" style="background:#fff">
    <div class="container py-3">
        <div class="row align-items-center gy-5">
            <div class="col-lg-5">
                <div class="section-eyebrow">{{ __('about.story_eyebrow') }}</div>
                <h2 class="section-title">{{ __('about.story_title') }}</h2>
                <div class="divider-leaf"></div>
                <p style="color:var(--ms-body);line-height:1.8;margin-bottom:1.25rem">
                    {{ __('about.story_p1') }}
                </p>
                <p style="color:var(--ms-muted);line-height:1.8;margin-bottom:1.25rem">
                    {{ __('about.story_p2') }}
                </p>
                <p style="color:var(--ms-muted);line-height:1.8;margin-bottom:0">
                    {{ __('about.story_p3') }}
                </p>
            </div>
            <div class="col-lg-7">
                <div style="background:var(--ms-surface);border-radius:20px;padding:2rem">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-building-fill"></i></div>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:var(--ms-muted);
                                        text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.25rem">
                                {{ __('common.name') }}
                            </div>
                            <div style="font-weight:600;color:var(--ms-dark)">Mandi Secure Private Limited</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:var(--ms-muted);
                                        text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.25rem">
                                {{ __('about.info_hq') ?? __('common.address') }}
                            </div>
                            <div style="font-weight:600;color:var(--ms-dark)">
                                No. 712, Koppa-Maddur Rd, Hosakere,<br>
                                Maddur, Besagarahalli, Mandya,<br>
                                Karnataka &mdash; 571419
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:var(--ms-muted);
                                        text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.25rem">
                                {{ __('common.email') }}
                            </div>
                            <a href="mailto:Headoffice@mandisecure.com"
                               style="font-weight:600;color:var(--ms-green);text-decoration:none">
                                Headoffice@mandisecure.com
                            </a>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:var(--ms-muted);
                                        text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.25rem">
                                {{ __('common.mobile') }}
                            </div>
                            <a href="tel:+916366799332"
                               style="font-weight:600;color:var(--ms-green);text-decoration:none">
                                +91 6366 799 332
                            </a>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-translate"></i></div>
                        <div>
                            <div style="font-size:0.75rem;font-weight:700;color:var(--ms-muted);
                                        text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.3rem">
                                {{ __('nav.language') }}
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(['English','हिंदी','ಕನ್ನಡ','தமிழ்'] as $lang)
                                <span style="background:var(--ms-green-light);color:var(--ms-green);
                                             font-size:0.8rem;font-weight:600;padding:0.2rem 0.65rem;
                                             border-radius:100px">{{ $lang }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Leadership ─── --}}
<section class="py-5" style="background: linear-gradient(rgba(6,23,16,0.80), rgba(6,23,16,0.80)), url('{{ asset('images/lead.png') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="text-center mb-5">
            <div class="section-eyebrow" style="color:#fff;background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.3)">{{ __('about.team_eyebrow') }}</div>
            <h2 class="section-title" style="color:#fff;">{{ __('about.team_title') }}</h2>
            <div class="divider-leaf mx-auto"></div>
            <p class="section-subtitle mx-auto" style="color:rgba(255,255,255,0.75)">
                {{ __('about.team_sub') }}
            </p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
            $team = [
                ['name'=>'Managing Director',   'role'=>'Founder & CEO',                'emoji'=>'👨‍💼'],
                ['name'=>'Operations Director', 'role'=>'Head of Operations',           'emoji'=>'👩‍💼'],
                ['name'=>'Technology Head',     'role'=>'Chief Technology Officer',     'emoji'=>'👨‍💻'],
                ['name'=>'Trade Relations',     'role'=>'Head of Seller Relations',     'emoji'=>'🤝'],
            ];
            @endphp
            @foreach($team as $t)
            <div class="col-sm-6 col-lg-3">
                <div class="team-card">
                    <div class="team-avatar">{{ $t['emoji'] }}</div>
                    <div style="padding:0 1.25rem 1.5rem">
                        <div style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;
                                    color:var(--ms-dark);margin-bottom:0.25rem">{{ $t['name'] }}</div>
                        <div style="font-size:0.8rem;color:var(--ms-green);font-weight:600">{{ $t['role'] }}</div>
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <a href="#" style="color:var(--ms-muted);font-size:0.9rem;
                                               transition:color 0.2s"
                               onmouseover="this.style.color='var(--ms-green)'"
                               onmouseout="this.style.color='var(--ms-muted)'">
                                <i class="bi bi-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── CTA ─── --}}
<section style="background:linear-gradient(135deg,#0e3b1f,#1a6b3c);padding:4rem 0">
    <div class="container text-center" style="position:relative">
        <h2 class="section-title light mt-2">{{ __('about.cta_title') }}</h2>
        <p style="color:rgba(255,255,255,0.65);max-width:500px;margin:0 auto 2rem;line-height:1.7">
            {{ __('about.cta_sub') }}
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('public.contact') }}" class="btn btn-ms-gold">
                <i class="bi bi-send me-2"></i>{{ __('about.cta_contact') }}
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-ms">
                {{ __('about.cta_browse') }}
            </a>
        </div>
    </div>
</section>

@endsection
