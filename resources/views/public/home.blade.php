@extends('public.layouts.app')

@section('title', 'MandiSecure — India\'s Premier Agricultural Marketplace')
@section('meta_description', 'MandiSecure connects verified farmers, sellers, and bulk buyers of coconut, vegetables, fruits, and masala across India. Transparent pricing, pan-India delivery, real-time market rates.')

@push('seo')
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
@endpush

@push('styles')
<style>
/* ─── Hero ─── */
.hero-section {
    min-height: 100vh;
    background:
        linear-gradient(155deg, rgba(6,23,16,0.68) 0%, rgba(14,59,31,0.55) 50%, rgba(14,66,36,0.65) 100%),
        url('{{ asset('images/hero.png') }}') center center / cover no-repeat;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    padding-top: 80px;
}

.hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        repeating-linear-gradient(
            0deg,
            transparent,
            transparent 60px,
            rgba(255,255,255,0.012) 60px,
            rgba(255,255,255,0.012) 61px
        ),
        repeating-linear-gradient(
            90deg,
            transparent,
            transparent 60px,
            rgba(255,255,255,0.012) 60px,
            rgba(255,255,255,0.012) 61px
        );
}

.hero-orb-1 {
    position: absolute;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(26,107,60,0.35) 0%, transparent 70%);
    top: -80px; right: -60px;
    animation: floatOrb 10s ease-in-out infinite;
}
.hero-orb-2 {
    position: absolute;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(232,160,32,0.15) 0%, transparent 70%);
    bottom: 60px; left: 5%;
    animation: floatOrb 14s ease-in-out infinite reverse;
}
@keyframes floatOrb {
    0%, 100% { transform: translateY(0) scale(1); }
    50%       { transform: translateY(-30px) scale(1.05); }
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(232,160,32,0.15);
    border: 1px solid rgba(232,160,32,0.35);
    color: var(--ms-gold);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 0.35rem 1rem;
    border-radius: 100px;
    margin-bottom: 1.25rem;
}

.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 5.5vw, 4.2rem);
    font-weight: 800;
    line-height: 1.1;
    color: #fff;
    margin-bottom: 1.25rem;
    letter-spacing: -0.02em;
}
.hero-title span { color: var(--ms-gold); }

.hero-subtitle {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.68);
    max-width: 540px;
    line-height: 1.75;
    margin-bottom: 2rem;
}

/* Hero Search */
.hero-search {
    background: rgba(255,255,255,0.96);
    border-radius: 14px;
    padding: 6px 6px 6px 20px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    max-width: 540px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    margin-bottom: 1.75rem;
}
.hero-search input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 0.95rem;
    background: transparent;
    color: var(--ms-dark);
    font-family: 'Inter', sans-serif;
}
.hero-search input::placeholder { color: #9ca3af; }
.hero-search button {
    background: var(--ms-green);
    color: #fff;
    border: none;
    padding: 0.6rem 1.3rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
}
.hero-search button:hover { background: var(--ms-green-dark); }

/* Hero stat badges */
.hero-stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(8px);
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.83rem;
    font-weight: 500;
}
.hero-stat-pill strong { color: var(--ms-gold); font-weight: 700; }

/* Hero illustration side */
.hero-cards-stack {
    position: relative;
    height: 460px;
}
.hero-card-item {
    position: absolute;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 1.2rem 1.5rem;
    color: #fff;
    width: 200px;
    animation: floatCard 6s ease-in-out infinite;
}
.hero-card-item:nth-child(2) { animation-delay: -2s; }
.hero-card-item:nth-child(3) { animation-delay: -4s; }
@keyframes floatCard {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-10px); }
}

/* ─── Categories Section ─── */
.cat-card {
    border-radius: 16px;
    overflow: hidden;
    border: none;
    cursor: pointer;
    transition: transform 0.25s, box-shadow 0.25s;
    text-decoration: none;
    display: block;
}
.cat-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,0.12) !important; }
.cat-card-body {
    padding: 1.75rem 1.5rem;
    position: relative;
    overflow: hidden;
}
.cat-icon-wrap {
    width: 60px; height: 60px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1rem;
}
.cat-card-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.cat-card-count { font-size: 0.82rem; font-weight: 500; opacity: 0.7; }
.cat-card-arrow {
    position: absolute; bottom: 1.2rem; right: 1.2rem;
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
    opacity: 0.5;
    transition: opacity 0.2s, transform 0.2s;
}
.cat-card:hover .cat-card-arrow { opacity: 1; transform: translateX(3px); }

/* ─── Product Cards ─── */
.pub-product-card {
    border: 1px solid var(--ms-border);
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    transition: box-shadow 0.25s, transform 0.25s;
    height: 100%;
}
.pub-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(0,0,0,0.1);
}
.pub-product-img {
    width: 100%; height: 200px; object-fit: cover;
    background: var(--ms-surface);
}
.pub-product-img-placeholder {
    width: 100%; height: 200px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem;
}
.pub-product-body { padding: 1rem 1.1rem 1.25rem; }
.pub-product-category {
    font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: 0.08em; color: var(--ms-green); margin-bottom: 0.35rem;
}
.pub-product-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 700;
    color: var(--ms-dark); margin-bottom: 0.4rem;
    line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.pub-product-seller { font-size: 0.8rem; color: var(--ms-muted); margin-bottom: 0.75rem; }
.pub-product-price { font-size: 1.15rem; font-weight: 700; color: var(--ms-green); }
.pub-product-original { font-size: 0.82rem; color: var(--ms-muted); text-decoration: line-through; }
.pub-product-discount {
    font-size: 0.72rem; font-weight: 700; background: #dcfce7;
    color: #166534; padding: 0.15rem 0.45rem; border-radius: 4px;
}

/* ─── Why Choose ─── */
.why-feature-card {
    border-radius: 16px;
    padding: 1.75rem 1.5rem;
    background: #fff;
    border: 1px solid var(--ms-border);
    height: 100%;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
}
.why-feature-card:hover {
    border-color: var(--ms-green-light);
    box-shadow: 0 12px 36px rgba(26,107,60,0.08);
    transform: translateY(-4px);
}
.why-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: var(--ms-green-light);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: var(--ms-green);
    margin-bottom: 1rem;
}
.why-title { font-size: 1rem; font-weight: 700; color: var(--ms-dark); margin-bottom: 0.5rem; }
.why-desc { font-size: 0.88rem; color: var(--ms-muted); line-height: 1.65; }

/* ─── Stats Counter ─── */
.stats-section {
    background:
        linear-gradient(rgba(10,30,15,0.78), rgba(10,30,15,0.78)),
        url('{{ asset('images/tar.png') }}') center center / cover no-repeat;
    position: relative; overflow: hidden;
}
.stats-section::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.stat-card {
    text-align: center;
    padding: 2.5rem 1rem;
    position: relative;
    z-index: 1;
}
.stat-number {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.5rem, 5vw, 3.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.stat-number span { color: var(--ms-gold); }
.stat-label {
    font-size: 0.85rem;
    font-weight: 500;
    color: rgba(255,255,255,0.6);
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.stat-divider {
    width: 1px;
    height: 80px;
    background: rgba(255,255,255,0.12);
    align-self: center;
}

/* ─── Mission & Vision ─── */
.mv-card {
    border-radius: 20px;
    padding: 2.5rem 2rem;
    height: 100%;
    position: relative;
    overflow: hidden;
}
.mv-card-mission {
    background: linear-gradient(145deg, #0e3b1f, #1a6b3c);
    color: #fff;
}
.mv-card-vision {
    background: linear-gradient(145deg, #7c4c00, var(--ms-gold-dark));
    color: #fff;
}
.mv-card::after {
    content: '';
    position: absolute;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    bottom: -40px; right: -40px;
}

/* ─── Timeline ─── */
.timeline-item {
    display: flex; gap: 1.5rem; padding-bottom: 2.5rem; position: relative;
}
.timeline-item:last-child { padding-bottom: 0; }
.timeline-line {
    position: absolute;
    left: 20px; top: 44px; bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--ms-green), transparent);
}
.timeline-item:last-child .timeline-line { display: none; }
.timeline-dot {
    width: 42px; height: 42px; flex-shrink: 0;
    border-radius: 50%;
    background: var(--ms-green);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 800; font-size: 0.8rem;
    position: relative; z-index: 1;
    box-shadow: 0 0 0 6px rgba(26,107,60,0.15);
}
.timeline-content { flex: 1; padding-top: 0.4rem; }
.timeline-year {
    font-size: 0.75rem; font-weight: 700; color: var(--ms-green);
    text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.2rem;
}
.timeline-title { font-size: 1.05rem; font-weight: 700; color: var(--ms-dark); margin-bottom: 0.3rem; }
.timeline-desc { font-size: 0.88rem; color: var(--ms-muted); line-height: 1.65; }

/* ─── Core Values ─── */
.value-card {
    border-radius: 14px;
    padding: 1.5rem;
    height: 100%;
    border: 1px solid var(--ms-border);
    transition: transform 0.2s, box-shadow 0.2s;
    background: #fff;
}
.value-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.07);
}
.value-num {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--ms-green-light);
    line-height: 1;
    margin-bottom: 0.5rem;
}
.value-title { font-size: 1rem; font-weight: 700; color: var(--ms-dark); margin-bottom: 0.4rem; }
.value-desc { font-size: 0.85rem; color: var(--ms-muted); line-height: 1.6; }

/* ─── Network Section ─── */
.network-section {
    background:
        linear-gradient(rgba(13,17,23,0.78), rgba(13,17,23,0.78)),
        url('{{ asset('images/city.png') }}') center center / cover no-repeat;
    position: relative; overflow: hidden;
}
.network-tag {
    display: inline-block;
    padding: 0.4rem 0.9rem;
    border-radius: 100px;
    font-size: 0.82rem;
    font-weight: 500;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
    transition: all 0.2s;
    cursor: default;
}
.network-tag:hover {
    background: rgba(26,107,60,0.3);
    border-color: var(--ms-green);
    color: #fff;
}

/* ─── Contact CTA ─── */
.cta-section {
    background:
        radial-gradient(ellipse at 20% 50%, rgba(232,160,32,0.12) 0%, transparent 60%),
        linear-gradient(135deg, #061710 0%, #0e3b1f 100%);
    position: relative; overflow: hidden;
}
.cta-section::before {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.04);
    right: -200px; top: -200px;
}
.cta-section::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.04);
    right: -100px; top: -100px;
}
</style>
@endpush

@section('content')

{{-- ───────────────────────────── HERO ───────────────────────────── --}}
<section class="hero-section">
    <div class="hero-orb-1"></div>
    <div class="hero-orb-2"></div>
    <div class="container" style="position:relative;z-index:2;padding:5rem 0 4rem">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="hero-eyebrow">
                    <i class="bi bi-patch-check-fill"></i>
                    {{ __('home.hero_badge') }}
                </div>
                <h1 class="hero-title">
                    {{ __('home.hero_title') }}
                </h1>
                <p class="hero-subtitle">
                    {{ __('home.hero_sub') }}
                </p>

                {{-- Search --}}
                <form action="{{ route('login') }}" method="GET" class="hero-search">
                    <i class="bi bi-search" style="color:#9ca3af;font-size:1rem;flex-shrink:0"></i>
                    <input type="text" name="from" placeholder="{{ __('home.search_ph') }}"
                           style="min-width:0">
                    <button type="submit">{{ __('common.search') }}</button>
                </form>

                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="btn btn-ms-gold">
                        <i class="bi bi-cart3 me-2"></i>{{ __('home.btn_browse') }}
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-ms">
                        <i class="bi bi-shop me-2"></i>{{ __('home.btn_sell') }}
                    </a>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <div class="hero-stat-pill">
                        <i class="bi bi-box-seam-fill"></i>
                        <strong>{{ $stats['products'] }}+</strong> {{ __('home.stat_products') }}
                    </div>
                    <div class="hero-stat-pill">
                        <i class="bi bi-people-fill"></i>
                        <strong>{{ $stats['sellers'] }}+</strong> {{ __('home.stat_sellers') }}
                    </div>
                    <div class="hero-stat-pill">
                        <i class="bi bi-shield-check-fill"></i>
                        {{ __('home.stat_verified') }}
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-cards-stack">
                    {{-- Floating stat cards --}}
                    <div class="hero-card-item" style="top:40px;left:20px">
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.55);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem">
                            <i class="bi bi-graph-up-arrow me-1" style="color:var(--ms-gold)"></i>
                            {{ __('home.active_listings') }}
                        </div>
                        <div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:800;color:#fff">
                            {{ $stats['products'] }}<span style="color:var(--ms-gold)">+</span>
                        </div>
                        <div style="font-size:0.78rem;color:rgba(255,255,255,0.5)">{{ __('home.approved_products') }}</div>
                    </div>

                    <div class="hero-card-item" style="top:150px;right:10px;width:210px">
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.55);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem">
                            <i class="bi bi-collection-fill me-1" style="color:var(--ms-gold)"></i>
                            Categories
                        </div>
                        @foreach($categories->take(4) as $cat)
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span style="font-size:0.83rem;color:rgba(255,255,255,0.8)">{{ $cat->name }}</span>
                            <span style="font-size:0.72rem;color:var(--ms-gold);font-weight:600">
                                {{ $cat->approved_count }} items
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="hero-card-item" style="bottom:60px;left:50px;width:190px">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div style="width:32px;height:32px;border-radius:50%;background:rgba(232,160,32,0.2);
                                        display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-shield-check-fill" style="color:var(--ms-gold);font-size:0.8rem"></i>
                            </div>
                            <div>
                                <div style="font-size:0.78rem;font-weight:700;color:#fff">Secure Trade</div>
                                <div style="font-size:0.68rem;color:rgba(255,255,255,0.5)">100% verified</div>
                            </div>
                        </div>
                        <div style="font-size:0.75rem;color:rgba(255,255,255,0.45);margin-top:0.5rem">
                            All sellers are verified & quality assured
                        </div>
                    </div>

                    {{-- Decorative rings --}}
                    <div style="position:absolute;width:320px;height:320px;border-radius:50%;
                                border:1px dashed rgba(255,255,255,0.08);top:50%;left:50%;
                                transform:translate(-50%,-50%);pointer-events:none"></div>
                    <div style="position:absolute;width:220px;height:220px;border-radius:50%;
                                border:1px solid rgba(26,107,60,0.4);top:50%;left:50%;
                                transform:translate(-50%,-50%);pointer-events:none"></div>
                    <div style="position:absolute;width:90px;height:90px;border-radius:50%;
                                background:rgba(232,160,32,0.15);top:50%;left:50%;
                                transform:translate(-50%,-50%);display:flex;align-items:center;
                                justify-content:center;font-size:2rem;pointer-events:none">
                        🌾
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="d-flex justify-content-center mt-5 d-none d-lg-flex">
            <div style="display:flex;flex-direction:column;align-items:center;gap:0.4rem;
                        color:rgba(255,255,255,0.3);font-size:0.72rem;animation:floatOrb 3s ease-in-out infinite">
                <span style="letter-spacing:0.12em;text-transform:uppercase">Scroll</span>
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </div>
</section>

{{-- ─── Wave Divider ─── --}}
<div style="line-height:0;background:#0e3b1f">
    <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%">
        <path d="M0,0 C360,56 1080,56 1440,0 L1440,56 L0,56 Z" fill="#fff"/>
    </svg>
</div>

{{-- ───────────────────────── CATEGORIES ───────────────────────── --}}
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.55), rgba(255,255,255,0.55)), url('{{ asset('images/nxth.jpg') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="text-center mb-5">
            <div class="section-eyebrow">{{ __('home.cat_eyebrow') }}</div>
            <h2 class="section-title">{{ __('home.cat_title') }}</h2>
            <div class="divider-leaf mx-auto"></div>
            <p class="section-subtitle mx-auto">
                {{ __('home.cat_sub') }}
            </p>
        </div>

        <div class="row g-4">
            @php
            $catConfig = [
                'Coconut'    => ['emoji'=>'🥥','bg'=>'linear-gradient(145deg,#fff8e1,#fff3cd)','iconBg'=>'#fef9c3','color'=>'#92400e','badge'=>'#d97706'],
                'Vegetables' => ['emoji'=>'🥬','bg'=>'linear-gradient(145deg,#f0fdf4,#dcfce7)','iconBg'=>'#d1fae5','color'=>'#065f46','badge'=>'#059669'],
                'Fruits'     => ['emoji'=>'🍎','bg'=>'linear-gradient(145deg,#fff1f2,#ffe4e6)','iconBg'=>'#fecdd3','color'=>'#9f1239','badge'=>'#e11d48'],
                'Masala'     => ['emoji'=>'🌶','bg'=>'linear-gradient(145deg,#fff7ed,#ffedd5)','iconBg'=>'#fed7aa','color'=>'#9a3412','badge'=>'#ea580c'],
            ];
            @endphp

            @foreach($categories as $cat)
            @php $cfg = $catConfig[$cat->name] ?? ['emoji'=>'🌿','bg'=>'linear-gradient(145deg,#f0fdf4,#dcfce7)','iconBg'=>'#d1fae5','color'=>'#065f46','badge'=>'#059669']; @endphp
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('login') }}" class="cat-card shadow-sm" style="background:{{ $cfg['bg'] }}">
                    <div class="cat-card-body">
                        <div class="cat-icon-wrap" style="background:{{ $cfg['iconBg'] }}">
                            @if($cat->image)
                                <img src="{{ Storage::url($cat->image) }}"
                                     alt="{{ $cat->name }}"
                                     style="width:2.6rem;height:2.6rem;object-fit:cover;border-radius:10px;">
                            @else
                                <span style="font-size:1.6rem">{{ $cfg['emoji'] }}</span>
                            @endif
                        </div>
                        <div class="cat-card-name" style="color:{{ $cfg['color'] }}">{{ $cat->name }}</div>
                        <div class="cat-card-count" style="color:{{ $cfg['color'] }}">
                            {{ $cat->approved_count }} {{ $cat->approved_count === 1 ? __('home.cat_product') : __('home.cat_products') }}
                        </div>
                        <div class="cat-card-arrow" style="background:{{ $cfg['iconBg'] }};color:{{ $cfg['badge'] }}">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── Section Divider ─── --}}
<div style="height:1px;background:var(--ms-border);margin:0 2rem"></div>

{{-- ───────────────────────── FEATURED PRODUCTS ───────────────────────── --}}
<section class="py-5" style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('{{ asset('images/category.jpg') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3">
            <div>
                <div class="section-eyebrow" style="color:#fff;background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.35)">{{ __('home.prod_eyebrow') }}</div>
                <h2 class="section-title mb-0" style="color:#fff;">{{ __('home.prod_title') }}</h2>
                <div class="divider-leaf mt-2 mb-0"></div>
            </div>
            <a href="{{ route('login') }}" class="btn btn-ms-primary">
                {{ __('common.view_all') }} <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        @if($featuredProducts->isNotEmpty())
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="pub-product-card">
                    {{-- Image --}}
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="pub-product-img">
                    @else
                        <div class="pub-product-img-placeholder">
                            @php
                            $emojis = ['🥥'=>'Coconut','🥬'=>'Vegetables','🍎'=>'Fruits','🌶'=>'Masala','🌿'=>''];
                            $em = array_search($product->category->name ?? '', $emojis) ?: '🌿';
                            @endphp
                            <span>{{ $em }}</span>
                        </div>
                    @endif

                    <div class="pub-product-body">
                        <div class="pub-product-category">
                            {{ $product->category->name ?? 'General' }}
                        </div>
                        <div class="pub-product-name">{{ $product->name }}</div>
                        <div class="pub-product-seller">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ $product->seller->name ?? 'Verified Seller' }}
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="pub-product-price">
                                ₹{{ number_format($product->discount_price ?? $product->price, 2) }}
                                <span style="font-size:0.78rem;font-weight:400;color:var(--ms-muted)">
                                    / {{ $product->unit }}
                                </span>
                            </div>
                            @if($product->discount_price && $product->discount_price < $product->price)
                                <div class="pub-product-original">₹{{ number_format($product->price, 2) }}</div>
                                @php
                                $pct = round((($product->price - $product->discount_price) / $product->price) * 100);
                                @endphp
                                <div class="pub-product-discount">{{ $pct }}% OFF</div>
                            @endif
                        </div>
                        <a href="{{ route('login') }}"
                           class="btn btn-ms-primary w-100 mt-3" style="padding:0.5rem">
                            <i class="bi bi-cart-plus me-1"></i> {{ __('common.buy_now') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem;margin-bottom:1rem">🌱</div>
            <p>{{ __('home.prod_empty') }}</p>
        </div>
        @endif
    </div>
</section>

{{-- ───────────────────────── WHY CHOOSE US ───────────────────────── --}}
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.45), rgba(255,255,255,0.45)), url('{{ asset('images/why.png') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="row align-items-center gy-5">
            <div class="col-lg-4">
                <div class="section-eyebrow">{{ __('home.why_eyebrow') }}</div>
                <h2 class="section-title">{{ __('home.why_title') }}</h2>
                <div class="divider-leaf"></div>
                <p class="section-subtitle">
                    {{ __('home.why_sub') }}
                </p>
                <a href="{{ route('public.about') }}" class="btn btn-ms-primary mt-2">
                    {{ __('common.learn_more') }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    @php
                    $features = [
                        ['icon'=>'bi-patch-check-fill',  'title'=> __('home.feat1_title'), 'desc'=> __('home.feat1_desc')],
                        ['icon'=>'bi-award-fill',        'title'=> __('home.feat2_title'), 'desc'=> __('home.feat2_desc')],
                        ['icon'=>'bi-truck',             'title'=> __('home.feat3_title'), 'desc'=> __('home.feat3_desc')],
                        ['icon'=>'bi-shield-lock-fill',  'title'=> __('home.feat4_title'), 'desc'=> __('home.feat4_desc')],
                        ['icon'=>'bi-currency-rupee',   'title'=> __('home.feat5_title'), 'desc'=> __('home.feat5_desc')],
                        ['icon'=>'bi-headset',           'title'=> __('home.feat6_title'), 'desc'=> __('home.feat6_desc')],
                    ];
                    @endphp
                    @foreach($features as $f)
                    <div class="col-sm-6">
                        <div class="why-feature-card">
                            <div class="why-icon">
                                <i class="bi {{ $f['icon'] }}"></i>
                            </div>
                            <div class="why-title">{{ $f['title'] }}</div>
                            <div class="why-desc">{{ $f['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────────────────────── IMPORT & EXPORT NETWORK ───────────────────────── --}}
<section class="network-section py-5">
    <div class="container py-4">
        <div class="row align-items-center gy-5">
            <div class="col-lg-5">
                <div class="section-eyebrow light">{{ __('home.net_eyebrow') }}</div>
                <h2 class="section-title light">{{ __('home.net_title') }}</h2>
                <div class="divider-leaf gold"></div>
                <p class="section-subtitle light">
                    {{ __('home.net_sub') }}
                </p>
                <div class="row g-3 mt-2">
                    @foreach([['36+', __('home.states')],['200+', __('home.cities')],['10+', __('home.trade_routes')],['24/7', __('home.support_247')]] as $s)
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
                                    border-radius:12px;padding:1rem;text-align:center">
                            <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;
                                        color:var(--ms-gold);line-height:1">{{ $s[0] }}</div>
                            <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.25rem">{{ $s[1] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-7">
                <h6 style="color:rgba(255,255,255,0.45);font-size:0.75rem;letter-spacing:0.12em;
                           text-transform:uppercase;margin-bottom:1.25rem">
                    {{ __('home.net_regions') }}
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['Karnataka','Kerala','Tamil Nadu','Andhra Pradesh','Telangana','Maharashtra',
                              'Goa','Gujarat','Madhya Pradesh','Rajasthan','Delhi NCR','West Bengal',
                              'Odisha','Punjab','Haryana','Uttarakhand','Himachal Pradesh','Bihar',
                              'Jharkhand','Assam','Meghalaya','Manipur','Tripura','Nagaland'] as $state)
                    <span class="network-tag">{{ $state }}</span>
                    @endforeach
                </div>
                <div class="mt-4 d-flex align-items-center gap-3"
                     style="background:rgba(232,160,32,0.1);border:1px solid rgba(232,160,32,0.25);
                            border-radius:12px;padding:1rem 1.25rem">
                    <i class="bi bi-globe2" style="font-size:1.5rem;color:var(--ms-gold);flex-shrink:0"></i>
                    <div>
                        <div style="color:#fff;font-weight:600;font-size:0.9rem">{{ __('home.net_expanding') }}</div>
                        <div style="color:rgba(255,255,255,0.5);font-size:0.82rem;margin-top:0.15rem">
                            {{ __('home.net_global') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────────────────────── STATISTICS ───────────────────────── --}}
<section class="stats-section py-2">
    <div class="container">
        <div class="row" id="statsRow">
            @foreach([
                ['num'=> $stats['products'],   'suffix'=>'+','label'=> __('home.stats_products'),   'icon'=>'bi-box-seam'],
                ['num'=> $stats['sellers'],    'suffix'=>'+','label'=> __('home.stats_sellers'),    'icon'=>'bi-shop'],
                ['num'=> $stats['buyers'],     'suffix'=>'+','label'=> __('home.stats_buyers'),     'icon'=>'bi-people'],
                ['num'=> $stats['categories'], 'suffix'=>'', 'label'=> __('home.stats_categories'), 'icon'=>'bi-grid'],
            ] as $i => $s)
            <div class="col-6 col-md-3">
                <div class="stat-card {{ $i < 3 ? 'd-flex d-md-block' : '' }}">
                    <i class="bi {{ $s['icon'] }}" style="color:rgba(255,255,255,0.2);font-size:2rem;
                                                          display:block;margin-bottom:0.5rem"></i>
                    <div class="stat-number">
                        <span data-count="{{ $s['num'] }}" class="count-el">0</span>{{ $s['suffix'] }}
                    </div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── Wave ─── --}}
<div style="line-height:0;background:linear-gradient(135deg, #0e3b1f 0%, #1a6b3c 100%)">
    <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%">
        <path d="M0,48 C480,0 960,0 1440,48 L1440,0 L0,0 Z" fill="#fff"/>
    </svg>
</div>

{{-- ───────────────────────── MISSION & VISION ───────────────────────── --}}
<section class="py-5" style="background: linear-gradient(rgba(255,255,255,0.82), rgba(255,255,255,0.82)), url('{{ asset('images/back.png') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="text-center mb-5">
            <div class="section-eyebrow">{{ __('home.mv_eyebrow') }}</div>
            <h2 class="section-title">{{ __('home.mv_title') }}</h2>
            <div class="divider-leaf mx-auto"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mv-card mv-card-mission">
                    <div style="display:inline-flex;align-items:center;gap:0.5rem;
                                background:rgba(255,255,255,0.1);border-radius:8px;
                                padding:0.4rem 0.9rem;margin-bottom:1.25rem">
                        <i class="bi bi-bullseye" style="color:var(--ms-gold)"></i>
                        <span style="font-size:0.75rem;font-weight:700;letter-spacing:0.12em;
                                     text-transform:uppercase;color:rgba(255,255,255,0.8)">{{ __('home.mis_label') }}</span>
                    </div>
                    <h3 style="font-size:1.5rem;color:#fff;margin-bottom:1rem">
                        {{ __('home.mis_title') }}
                    </h3>
                    <p style="color:rgba(255,255,255,0.7);line-height:1.8;font-size:0.95rem;margin-bottom:0">
                        {{ __('home.mis_text') }}
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mv-card mv-card-vision">
                    <div style="display:inline-flex;align-items:center;gap:0.5rem;
                                background:rgba(255,255,255,0.1);border-radius:8px;
                                padding:0.4rem 0.9rem;margin-bottom:1.25rem">
                        <i class="bi bi-eye-fill" style="color:#fff"></i>
                        <span style="font-size:0.75rem;font-weight:700;letter-spacing:0.12em;
                                     text-transform:uppercase;color:rgba(255,255,255,0.8)">{{ __('home.vis_label') }}</span>
                    </div>
                    <h3 style="font-size:1.5rem;color:#fff;margin-bottom:1rem">
                        {{ __('home.vis_title') }}
                    </h3>
                    <p style="color:rgba(255,255,255,0.7);line-height:1.8;font-size:0.95rem;margin-bottom:0">
                        {{ __('home.vis_text') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────────────────────── COMPANY HISTORY ───────────────────────── --}}
<section class="py-5" style="background: linear-gradient(rgba(245,247,244,0.72), rgba(245,247,244,0.72)), url('{{ asset('images/step.png') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="row gy-5 align-items-start">
            <div class="col-lg-4">
                <div class="section-eyebrow">{{ __('home.hist_eyebrow') }}</div>
                <h2 class="section-title">{{ __('home.hist_title') }}</h2>
                <div class="divider-leaf"></div>
                <p class="section-subtitle">
                    {{ __('home.hist_sub') }}
                </p>
            </div>
            <div class="col-lg-8">
                <div class="position-relative">
                    @php
                    $history = [
                        ['year'=>'2020','title'=> __('home.hist1_title'),'desc'=> __('home.hist1_desc')],
                        ['year'=>'2021','title'=> __('home.hist2_title'),'desc'=> __('home.hist2_desc')],
                        ['year'=>'2022','title'=> __('home.hist3_title'),'desc'=> __('home.hist3_desc')],
                        ['year'=>'2023','title'=> __('home.hist4_title'),'desc'=> __('home.hist4_desc')],
                        ['year'=>'2024','title'=> __('home.hist5_title'),'desc'=> __('home.hist5_desc')],
                        ['year'=>'2025','title'=> __('home.hist6_title'),'desc'=> __('home.hist6_desc')],
                    ];
                    @endphp
                    @foreach($history as $h)
                    <div class="timeline-item">
                        <div class="timeline-line"></div>
                        <div class="timeline-dot">{{ substr($h['year'], 2) }}</div>
                        <div class="timeline-content">
                            <div class="timeline-year">{{ $h['year'] }}</div>
                            <div class="timeline-title">{{ $h['title'] }}</div>
                            <div class="timeline-desc">{{ $h['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────────────────────── CORE VALUES ───────────────────────── --}}
<section class="py-5" style="background: url('{{ asset('images/m1.png') }}') center center / cover no-repeat; position:relative;">
<div style="position:absolute;inset:0;background:rgba(255,255,255,0.15);z-index:0"></div>
    <div class="container py-3" style="position:relative;z-index:1">
        <div class="text-center mb-5">
            <div class="section-eyebrow">{{ __('home.val_eyebrow') }}</div>
            <h2 class="section-title">{{ __('home.val_title') }}</h2>
            <div class="divider-leaf mx-auto"></div>
            <p class="section-subtitle mx-auto">
                {{ __('home.val_sub') }}
            </p>
        </div>
        <div class="row g-4">
            @php
            $values = [
                ['num'=>'01','title'=> __('home.val1'),'desc'=> __('home.val1_desc')],
                ['num'=>'02','title'=> __('home.val2'),'desc'=> __('home.val2_desc')],
                ['num'=>'03','title'=> __('home.val3'),'desc'=> __('home.val3_desc')],
                ['num'=>'04','title'=> __('home.val4'),'desc'=> __('home.val4_desc')],
                ['num'=>'05','title'=> __('home.val5'),'desc'=> __('home.val5_desc')],
                ['num'=>'06','title'=> __('home.val6'),'desc'=> __('home.val6_desc')],
            ];
            @endphp
            @foreach($values as $v)
            <div class="col-sm-6 col-lg-4">
                <div class="value-card">
                    <div class="value-num">{{ $v['num'] }}</div>
                    <div class="value-title">{{ $v['title'] }}</div>
                    <div class="value-desc">{{ $v['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ───────────────────────── CONTACT CTA ───────────────────────── --}}
<section class="cta-section py-5">
    <div class="container py-4" style="position:relative;z-index:2">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <div class="section-eyebrow light">{{ __('home.cta_eyebrow') }}</div>
                <h2 class="section-title light" style="margin-bottom:1rem">
                    {{ __('home.cta_title') }}
                </h2>
                <p class="section-subtitle light" style="margin-bottom:0">
                    {{ __('home.cta_sub') }}
                </p>
            </div>
            <div class="col-lg-5">
                <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);
                            border-radius:20px;padding:2rem;backdrop-filter:blur(8px)">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(232,160,32,0.15);
                                        flex-shrink:0;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-telephone-fill" style="color:var(--ms-gold)"></i>
                            </div>
                            <div>
                                <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.1em">{{ __('home.cta_call') }}</div>
                                <a href="tel:+916366799332" style="color:#fff;text-decoration:none;font-weight:600;font-size:0.95rem">
                                    +91 6366 799 332
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(232,160,32,0.15);
                                        flex-shrink:0;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-envelope-fill" style="color:var(--ms-gold)"></i>
                            </div>
                            <div>
                                <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.1em">{{ __('home.cta_email_lbl') }}</div>
                                <a href="mailto:Headoffice@mandisecure.com" style="color:#fff;text-decoration:none;font-weight:600;font-size:0.9rem">
                                    Headoffice@mandisecure.com
                                </a>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:rgba(232,160,32,0.15);
                                        flex-shrink:0;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-geo-alt-fill" style="color:var(--ms-gold)"></i>
                            </div>
                            <div>
                                <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.1em">{{ __('home.cta_addr_lbl') }}</div>
                                <div style="color:rgba(255,255,255,0.7);font-size:0.85rem;line-height:1.5">
                                    No. 712, Koppa-Maddur Rd, Hosakere,<br>
                                    Maddur, Mandya, Karnataka — 571419
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 pt-1">
                            <a href="{{ route('public.contact') }}" class="btn btn-ms-gold flex-fill text-center">
                                <i class="bi bi-send me-1"></i> {{ __('nav.contact') }}
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-ms flex-fill text-center">
                                {{ __('nav.login') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Counter animation on scroll
document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.count-el');
    if (!counters.length) return;

    const animate = (el) => {
        const target = parseInt(el.dataset.count, 10);
        const duration = 1800;
        const step = Math.ceil(target / (duration / 16));
        let current = 0;
        const tick = () => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current < target) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animate(e.target);
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.3 });

    counters.forEach(c => observer.observe(c));
});
</script>
@endpush
