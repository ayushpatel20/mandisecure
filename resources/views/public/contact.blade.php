@extends('public.layouts.app')

@section('title', __('contact.page_title') !== 'contact.page_title' ? __('contact.page_title') : 'Contact Us — MandiSecure')
@section('meta_description', 'Contact MandiSecure for buyer or seller enquiries, partnership opportunities, or support. Reach us by phone, email, or visit our office in Mandya, Karnataka.')

@push('seo')
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/contact') }}">
@endpush

@push('styles')
<style>
.contact-hero {
    min-height: 52vh;
    background:
        linear-gradient(rgba(6,23,16,0.78), rgba(6,23,16,0.78)),
        url('{{ asset('images/contact.png') }}') center center / cover no-repeat;
    position: relative; overflow: hidden;
    display: flex; align-items: center;
    padding-top: 80px;
}
.contact-hero::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        repeating-linear-gradient(0deg, transparent, transparent 60px,
            rgba(255,255,255,0.012) 60px, rgba(255,255,255,0.012) 61px),
        repeating-linear-gradient(90deg, transparent, transparent 60px,
            rgba(255,255,255,0.012) 60px, rgba(255,255,255,0.012) 61px);
}

.contact-form-wrap {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 24px 80px rgba(0,0,0,0.1);
    padding: 2.5rem;
    border: 1px solid var(--ms-border);
}

.form-label-ms {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--ms-dark);
    margin-bottom: 0.4rem;
    letter-spacing: 0.02em;
}

.form-control-ms {
    border: 1.5px solid var(--ms-border);
    border-radius: 10px;
    padding: 0.65rem 1rem;
    font-size: 0.93rem;
    font-family: 'Inter', sans-serif;
    color: var(--ms-dark);
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fff;
}
.form-control-ms:focus {
    border-color: var(--ms-green);
    box-shadow: 0 0 0 3px rgba(26,107,60,0.12);
    outline: none;
}
.form-control-ms::placeholder { color: #b0bec5; }

.btn-submit {
    background: var(--ms-green);
    color: #fff;
    border: none;
    padding: 0.8rem 2.5rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    display: inline-flex; align-items: center; gap: 0.5rem;
}
.btn-submit:hover {
    background: var(--ms-green-dark);
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(14,61,32,0.25);
}

.contact-info-block {
    display: flex; gap: 1rem; align-items: flex-start;
    padding: 1.25rem 0;
    border-bottom: 1px solid var(--ms-border);
}
.contact-info-block:last-child { border-bottom: none; }
.contact-info-icon {
    width: 48px; height: 48px; flex-shrink: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--ms-green), #2d9e5e);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.1rem;
    box-shadow: 0 6px 18px rgba(26,107,60,0.25);
}
.contact-info-label {
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.1em; color: var(--ms-muted); margin-bottom: 0.3rem;
}
.contact-info-value {
    font-weight: 600; color: var(--ms-dark); font-size: 0.93rem;
    line-height: 1.5;
}
.contact-info-value a {
    color: var(--ms-dark); text-decoration: none;
    transition: color 0.2s;
}
.contact-info-value a:hover { color: var(--ms-green); }

.faq-item {
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}
.faq-question {
    padding: 1rem 1.25rem;
    font-weight: 600; font-size: 0.93rem;
    color: #fff;
    cursor: pointer;
    display: flex; justify-content: space-content; align-items: center;
    gap: 1rem;
    background: rgba(255,255,255,0.08);
    transition: background 0.2s;
}
.faq-question:hover { background: rgba(255,255,255,0.14); }
.faq-answer {
    padding: 0 1.25rem 1.1rem;
    font-size: 0.88rem; color: rgba(255,255,255,0.72); line-height: 1.7;
    border-top: 1px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.05);
    display: none;
}
.faq-item.open .faq-answer { display: block; }
.faq-item.open .faq-icon { transform: rotate(45deg); }
.faq-icon { transition: transform 0.25s; flex-shrink: 0; color: #e8a020; }

/* ─── 5G: Contact mobile overrides ─── */
@media (max-width: 575px) {
    .contact-hero { min-height: 40vh; }
    .contact-hero > .container { padding-top: 3rem !important; padding-bottom: 2.5rem !important; }
    .contact-form-wrap { padding: 1.5rem; border-radius: 14px; }
    .btn-submit { width: 100%; justify-content: center; }
    .contact-info-icon { width: 40px; height: 40px; font-size: 0.95rem; }
    .contact-info-block { padding: 0.9rem 0; }
    .faq-question { padding: 0.9rem 1rem; font-size: 0.88rem; }
    .faq-answer { padding: 0 1rem 0.9rem; }
}
</style>
@endpush

@section('content')

{{-- ─── Hero ─── --}}
<section class="contact-hero">
    <div class="container" style="position:relative;z-index:2;padding:5rem 0 4rem">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7">
                <div style="display:inline-flex;align-items:center;gap:0.5rem;
                             background:rgba(232,160,32,0.15);border:1px solid rgba(232,160,32,0.35);
                             color:var(--ms-gold);font-size:0.78rem;font-weight:600;letter-spacing:0.12em;
                             text-transform:uppercase;padding:0.35rem 1rem;border-radius:100px;margin-bottom:1.25rem">
                    <i class="bi bi-chat-dots-fill"></i> {{ __('contact.hero_eyebrow') }}
                </div>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2.2rem,5vw,3.6rem);
                           font-weight:800;color:#fff;line-height:1.1;margin-bottom:1rem">
                    {{ __('contact.hero_title') }}
                </h1>
                <p style="font-size:1rem;color:rgba(255,255,255,0.65);max-width:500px;
                           margin:0 auto;line-height:1.75">
                    {{ __('contact.hero_sub') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ─── Wave ─── --}}
<div style="line-height:0;background:#0e3b1f">
    <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%">
        <path d="M0,0 C360,56 1080,56 1440,0 L1440,56 L0,56 Z" fill="#f8faf8"/>
    </svg>
</div>

{{-- ─── Contact Main ─── --}}
<section class="py-5" style="background:var(--ms-surface)">
    <div class="container py-3">

        @if(session('success'))
        <div class="alert-ms-success d-flex align-items-center gap-2 mb-4 p-3"
             style="border-radius:12px">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div>
                <div class="fw-semibold" style="color:#065f46">{{ __('contact.success_title') }}</div>
                <div style="font-size:0.88rem;color:#065f46;opacity:0.85">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        <div class="row g-5 align-items-start">

            {{-- Form --}}
            <div class="col-lg-7">
                <div class="contact-form-wrap">
                    <div class="mb-4">
                        <div class="section-eyebrow">{{ __('contact.form_title') }}</div>
                        <h2 class="section-title" style="font-size:1.8rem;margin-bottom:0">{{ __('contact.form_sub') }}</h2>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-ms-success mb-4" role="alert" style="border-radius:10px;">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger mb-4" role="alert" style="border-radius:10px;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the errors below.
                        </div>
                    @endif

                    <form action="{{ route('public.contact.send') }}" method="POST" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-ms" for="name">{{ __('contact.name_label') }} <span style="color:#e11d48">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control-ms w-100 @error('name') border-danger @enderror"
                                       placeholder="{{ __('contact.name_ph') }}"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div style="font-size:0.78rem;color:#e11d48;margin-top:0.3rem">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-ms" for="email">{{ __('contact.email_label') }} <span style="color:#e11d48">*</span></label>
                                <input type="email" id="email" name="email"
                                       class="form-control-ms w-100 @error('email') border-danger @enderror"
                                       placeholder="{{ __('contact.email_ph') }}"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div style="font-size:0.78rem;color:#e11d48;margin-top:0.3rem">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-ms" for="mobile">{{ __('contact.mobile_label') }}</label>
                                <input type="tel" id="mobile" name="mobile"
                                       class="form-control-ms w-100"
                                       placeholder="{{ __('contact.mobile_ph') }}"
                                       value="{{ old('mobile') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-ms" for="inquiry_type">{{ __('contact.type_label') }}</label>
                                <select id="inquiry_type" name="subject"
                                        class="form-control-ms w-100 @error('subject') border-danger @enderror">
                                    <option value="" disabled {{ !old('subject') ? 'selected' : '' }}>{{ __('contact.type_ph') }}</option>
                                    <option value="Register as Seller"        {{ old('subject') === 'Register as Seller' ? 'selected' : '' }}>{{ __('contact.type_sell') }}</option>
                                    <option value="Bulk Purchase / Wholesale" {{ old('subject') === 'Bulk Purchase / Wholesale' ? 'selected' : '' }}>{{ __('contact.type_buy') }}</option>
                                    <option value="Export Inquiry"            {{ old('subject') === 'Export Inquiry' ? 'selected' : '' }}>{{ __('contact.type_export') }}</option>
                                    <option value="Partnership"               {{ old('subject') === 'Partnership' ? 'selected' : '' }}>{{ __('contact.type_partner') }}</option>
                                    <option value="Technical Support"         {{ old('subject') === 'Technical Support' ? 'selected' : '' }}>{{ __('contact.type_support') }}</option>
                                    <option value="General Inquiry"           {{ old('subject') === 'General Inquiry' ? 'selected' : '' }}>{{ __('contact.type_other') }}</option>
                                </select>
                                @error('subject')
                                    <div style="font-size:0.78rem;color:#e11d48;margin-top:0.3rem">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label-ms" for="message">{{ __('contact.message_label') }} <span style="color:#e11d48">*</span></label>
                                <textarea id="message" name="message" rows="5"
                                          class="form-control-ms w-100 @error('message') border-danger @enderror"
                                          placeholder="{{ __('contact.message_ph') }}"
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div style="font-size:0.78rem;color:#e11d48;margin-top:0.3rem">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-submit">
                                    <i class="bi bi-send-fill"></i>
                                    {{ __('contact.send_btn') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-5">
                <div class="section-eyebrow">{{ __('contact.info_title') }}</div>
                <h2 class="section-title" style="font-size:1.8rem;margin-bottom:1.5rem">{{ __('contact.info_title') }}</h2>

                <div style="background:#fff;border-radius:16px;padding:0.5rem 1.5rem;border:1px solid var(--ms-border);box-shadow:0 4px 20px rgba(0,0,0,0.05)">
                    <div class="contact-info-block">
                        <div class="contact-info-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="contact-info-label">{{ __('contact.info_phone') }}</div>
                            <div class="contact-info-value">
                                <a href="tel:+919740912429 ">+91 9740 912 429 </a>
                            </div>
                            <div style="font-size:0.78rem;color:var(--ms-muted);margin-top:0.2rem">
                                {{ __('contact.hours_val') }}
                            </div>
                        </div>
                    </div>
                    <div class="contact-info-block">
                        <div class="contact-info-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="contact-info-label">{{ __('contact.info_email') }}</div>
                            <div class="contact-info-value">
                                <a href="mailto:Headoffice@mandisecure.com">Headoffice@mandisecure.com</a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-info-block">
                        <div class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="contact-info-label">{{ __('contact.info_hq') }}</div>
                            <div class="contact-info-value">
                                No. 712, Koppa-Maddur Rd,<br>
                                Besagara Halli Cross, Hosakere, Maddur, Mandya, Karnataka<br>
                                <span style="font-size:0.82rem;color:var(--ms-muted)">Pin-Code : 571419 </span>
                            </div>
                        </div>
                    </div>
                    <div class="contact-info-block">
                        <div class="contact-info-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="contact-info-label">{{ __('contact.info_hours') }}</div>
                            <div class="contact-info-value">{{ __('contact.hours_val') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-column gap-2">
                    <a href="{{ route('login') }}"
                       style="background:var(--ms-green-light);color:var(--ms-green);text-decoration:none;
                              border-radius:10px;padding:0.8rem 1.1rem;font-weight:600;font-size:0.88rem;
                              display:flex;align-items:center;gap:0.75rem;transition:background 0.2s"
                       onmouseover="this.style.background='#b6e3cc'"
                       onmouseout="this.style.background='var(--ms-green-light)'">
                        <i class="bi bi-box-arrow-in-right" style="font-size:1rem"></i>
                        {{ __('nav.login') }}
                    </a>
                    <a href="{{ route('public.about') }}"
                       style="background:#f3f4f6;color:var(--ms-dark);text-decoration:none;
                              border-radius:10px;padding:0.8rem 1.1rem;font-weight:600;font-size:0.88rem;
                              display:flex;align-items:center;gap:0.75rem;transition:background 0.2s"
                       onmouseover="this.style.background='#e5e7eb'"
                       onmouseout="this.style.background='#f3f4f6'">
                        <i class="bi bi-info-circle" style="font-size:1rem"></i>
                        {{ __('nav.about') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ─── FAQ ─── --}}
<section class="py-5" style="background: linear-gradient(rgba(6,23,16,0.82), rgba(6,23,16,0.82)), url('{{ asset('images/faq.png') }}') center center / cover no-repeat;">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="section-eyebrow" style="color:#fff;background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.3)">{{ __('contact.faq_title') }}</div>
                    <h2 class="section-title" style="color:#fff;">{{ __('contact.faq_title') }}</h2>
                    <div class="divider-leaf mx-auto"></div>
                    <p class="section-subtitle mx-auto" style="color:rgba(255,255,255,0.75)">{{ __('contact.faq_sub') }}</p>
                </div>

                @php
                $faqs = [
                    ['q'=> __('contact.faq1_q'), 'a'=> __('contact.faq1_a')],
                    ['q'=> __('contact.faq2_q'), 'a'=> __('contact.faq2_a')],
                    ['q'=> __('contact.faq3_q'), 'a'=> __('contact.faq3_a')],
                    ['q'=> __('contact.faq4_q'), 'a'=> __('contact.faq4_a')],
                    ['q'=> __('contact.faq5_q'), 'a'=> __('contact.faq5_a')],
                    ['q'=> __('contact.faq6_q'), 'a'=> __('contact.faq6_a')],
                ];
                @endphp

                <div id="faqContainer">
                    @foreach($faqs as $i => $faq)
                    <div class="faq-item" id="faq-{{ $i }}">
                        <div class="faq-question" onclick="toggleFaq({{ $i }})">
                            <span style="flex:1">{{ $faq['q'] }}</span>
                            <i class="bi bi-plus-lg faq-icon" style="font-size:1rem"></i>
                        </div>
                        <div class="faq-answer">{{ $faq['a'] }}</div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ─── Map placeholder ─── --}}
<div style="background: linear-gradient(rgba(6,23,16,0.80), rgba(6,23,16,0.80)), url('{{ asset('images/loc.png') }}') center center / cover no-repeat; padding:2rem 0">
    <div class="container">
        <div style="background:rgba(0,0,0,0.45);border-radius:16px;border:1px solid rgba(255,255,255,0.25);
                    overflow:hidden;height:300px;display:flex;align-items:center;justify-content:center;
                    position:relative;backdrop-filter:blur(2px)">
            <div style="position:relative;z-index:1;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--ms-green);
                            display:flex;align-items:center;justify-content:center;
                            margin:0 auto 1rem;box-shadow:0 8px 24px rgba(26,107,60,0.55);
                            animation:floatOrb 4s ease-in-out infinite">
                    <i class="bi bi-geo-alt-fill" style="color:#fff;font-size:1.5rem"></i>
                </div>
                <div style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.15rem;
                            color:#fff;margin-bottom:0.3rem;text-shadow:0 2px 8px rgba(0,0,0,0.6)">Mandi Secure Private Limited</div>
                <div style="font-size:0.88rem;color:rgba(255,255,255,0.90);text-shadow:0 1px 4px rgba(0,0,0,0.5)">
                    No. 712, Koppa-Maddur Rd, Besagara Halli Cross, Hosakere, Maddur, Mandya, Karnataka – 571419
                </div>
                <a href="https://maps.google.com/?q=12.636974,77.011528" target="_blank"
                   class="btn btn-ms-primary mt-3" style="padding:0.45rem 1.1rem;font-size:0.85rem">
                    <i class="bi bi-map me-1"></i> {{ __('contact.map_title') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleFaq(id) {
    const item = document.getElementById('faq-' + id);
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="contact"]');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-danger');
                isValid = false;
            } else {
                field.classList.remove('border-danger');
            }
        });

        const emailField = form.querySelector('input[type="email"]');
        if (emailField && emailField.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value.trim())) {
                emailField.classList.add('border-danger');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            // Show dynamic alert on top if it doesn't exist
            let alertBox = form.previousElementSibling;
            if (!alertBox || !alertBox.classList.contains('alert-danger')) {
                const newAlert = document.createElement('div');
                newAlert.className = 'alert alert-danger mb-4';
                newAlert.style.borderRadius = '10px';
                newAlert.role = 'alert';
                newAlert.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Please fill in all required fields correctly.';
                form.parentNode.insertBefore(newAlert, form);
            }
            return;
        }

        // Show spinner & disable submit button
        const btn = form.querySelector('.btn-submit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...';
        }
    });

    // Clear validation styling on input
    form.querySelectorAll('.form-control-ms').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-danger');
        });
    });
});
</script>
@endpush
