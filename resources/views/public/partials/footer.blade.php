<footer style="background:#0d1117;color:rgba(255,255,255,0.8)">

    {{-- Main Footer --}}
    <div class="container py-5">
        <div class="row g-5">

            {{-- Brand Column --}}
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:38px;height:38px;background:var(--ms-green);border-radius:9px;
                                display:flex;align-items:center;justify-content:center;font-size:1.1rem">
                        <i class="bi bi-tree-fill text-white"></i>
                    </div>
                    <div>
                        <div style="font-family:'Playfair Display',serif;font-weight:800;font-size:1.2rem;color:#fff">
                            MandiSecure
                        </div>
                        <div style="font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;
                                    color:rgba(255,255,255,0.45)">A Global Trades</div>
                    </div>
                </div>
                <p style="font-size:0.9rem;line-height:1.7;color:rgba(255,255,255,0.55);max-width:300px">
                    India's premier agricultural marketplace — connecting verified farmers, sellers, and
                    buyers across the nation and beyond.
                </p>

                {{-- Social Media --}}
                <div class="d-flex gap-2 mt-4">
                    @php
                    $socials = [
                        ['icon' => 'facebook',   'url' => 'https://facebook.com/mandisecure',   'label' => 'Facebook'],
                        ['icon' => 'twitter-x',  'url' => 'https://x.com/mandisecure',          'label' => 'X (Twitter)'],
                        ['icon' => 'instagram',  'url' => 'https://instagram.com/mandisecure',  'label' => 'Instagram'],
                        ['icon' => 'linkedin',   'url' => 'https://linkedin.com/company/mandisecure', 'label' => 'LinkedIn'],
                        ['icon' => 'youtube',    'url' => 'https://youtube.com/@mandisecure',   'label' => 'YouTube'],
                    ];
                    @endphp
                    @foreach($socials as $s)
                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['label'] }}"
                       style="width:36px;height:36px;border-radius:8px;
                              background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);
                              display:flex;align-items:center;justify-content:center;
                              color:rgba(255,255,255,0.55);text-decoration:none;font-size:0.9rem;
                              transition:background 0.2s,color 0.2s"
                       onmouseover="this.style.background='var(--ms-green)';this.style.color='#fff'"
                       onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.55)'">
                        <i class="bi bi-{{ $s['icon'] }}"></i>
                    </a>
                    @endforeach
                </div>

                {{-- WhatsApp CTA --}}
                <a href="https://wa.me/916366799332?text=Hello%2C%20I%20am%20interested%20in%20MandiSecure."
                   target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:1rem;
                          background:rgba(37,211,102,0.12);border:1px solid rgba(37,211,102,0.3);
                          color:rgba(37,211,102,0.9);text-decoration:none;font-size:0.82rem;font-weight:600;
                          padding:0.4rem 0.9rem;border-radius:8px;transition:background 0.2s"
                   onmouseover="this.style.background='rgba(37,211,102,0.2)'"
                   onmouseout="this.style.background='rgba(37,211,102,0.12)'">
                    <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>

            {{-- Company Links --}}
            <div class="col-sm-6 col-lg-2">
                <h6 style="color:#fff;font-family:'Inter',sans-serif;font-size:0.8rem;
                           font-weight:700;letter-spacing:0.12em;text-transform:uppercase;
                           margin-bottom:1.2rem">Company</h6>
                <ul class="list-unstyled" style="display:flex;flex-direction:column;gap:0.6rem">
                    @foreach([
                        ['label'=>'Home',       'route'=>'public.home'],
                        ['label'=>'About Us',   'route'=>'public.about'],
                        ['label'=>'Contact Us', 'route'=>'public.contact'],
                    ] as $link)
                    <li>
                        <a href="{{ route($link['route']) }}"
                           style="color:rgba(255,255,255,0.55);text-decoration:none;font-size:0.9rem;transition:color 0.2s"
                           onmouseover="this.style.color='var(--ms-gold)'"
                           onmouseout="this.style.color='rgba(255,255,255,0.55)'">
                            <i class="bi bi-chevron-right me-1" style="font-size:0.65rem"></i>{{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Categories --}}
            <div class="col-sm-6 col-lg-2">
                <h6 style="color:#fff;font-family:'Inter',sans-serif;font-size:0.8rem;
                           font-weight:700;letter-spacing:0.12em;text-transform:uppercase;
                           margin-bottom:1.2rem">Categories</h6>
                <ul class="list-unstyled" style="display:flex;flex-direction:column;gap:0.6rem">
                    @foreach(['Coconut', 'Vegetables', 'Fruits', 'Masala'] as $cat)
                    <li>
                        <a href="{{ route('login') }}"
                           style="color:rgba(255,255,255,0.55);text-decoration:none;font-size:0.9rem;transition:color 0.2s"
                           onmouseover="this.style.color='var(--ms-gold)'"
                           onmouseout="this.style.color='rgba(255,255,255,0.55)'">
                            <i class="bi bi-chevron-right me-1" style="font-size:0.65rem"></i>{{ $cat }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-lg-4">
                <h6 style="color:#fff;font-family:'Inter',sans-serif;font-size:0.8rem;
                           font-weight:700;letter-spacing:0.12em;text-transform:uppercase;
                           margin-bottom:1.2rem">Get In Touch</h6>
                <div style="display:flex;flex-direction:column;gap:1rem">
                    <div class="d-flex gap-3 align-items-start">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(26,107,60,0.3);
                                    flex-shrink:0;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-geo-alt-fill" style="color:var(--ms-gold);font-size:0.85rem"></i>
                        </div>
                        <div style="font-size:0.85rem;color:rgba(255,255,255,0.55);line-height:1.6">
                            Marasinganahalli Road,<br>
                            Hosakere, Karnataka, India<br>
                            <span style="font-size:0.78rem;opacity:0.7">Plus Code: J2P6+QJJ</span>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(26,107,60,0.3);
                                    flex-shrink:0;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-telephone-fill" style="color:var(--ms-gold);font-size:0.85rem"></i>
                        </div>
                        <a href="tel:+916366799332"
                           style="font-size:0.9rem;color:rgba(255,255,255,0.7);text-decoration:none"
                           onmouseover="this.style.color='#fff'"
                           onmouseout="this.style.color='rgba(255,255,255,0.7)'">+91 6366 799 332</a>
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(26,107,60,0.3);
                                    flex-shrink:0;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-envelope-fill" style="color:var(--ms-gold);font-size:0.85rem"></i>
                        </div>
                        <a href="mailto:Headoffice@mandisecure.com"
                           style="font-size:0.9rem;color:rgba(255,255,255,0.7);text-decoration:none"
                           onmouseover="this.style.color='#fff'"
                           onmouseout="this.style.color='rgba(255,255,255,0.7)'">Headoffice@mandisecure.com</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom Bar --}}
    <div style="border-top:1px solid rgba(255,255,255,0.06)">
        <div class="container py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div style="font-size:0.82rem;color:rgba(255,255,255,0.35)">
                &copy; {{ date('Y') }} Mandi Secure Private Limited. All rights reserved.
            </div>
            <div class="d-flex flex-wrap gap-3" style="font-size:0.82rem;color:rgba(255,255,255,0.35)">
                <a href="{{ route('public.privacy') }}" style="color:inherit;text-decoration:none"
                   onmouseover="this.style.color='rgba(255,255,255,0.7)'"
                   onmouseout="this.style.color='rgba(255,255,255,0.35)'">Privacy Policy</a>
                <a href="{{ route('public.terms') }}" style="color:inherit;text-decoration:none"
                   onmouseover="this.style.color='rgba(255,255,255,0.7)'"
                   onmouseout="this.style.color='rgba(255,255,255,0.35)'">Terms & Conditions</a>
                <a href="{{ route('public.refund') }}" style="color:inherit;text-decoration:none"
                   onmouseover="this.style.color='rgba(255,255,255,0.7)'"
                   onmouseout="this.style.color='rgba(255,255,255,0.35)'">Refund Policy</a>
                <a href="{{ route('public.shipping') }}" style="color:inherit;text-decoration:none"
                   onmouseover="this.style.color='rgba(255,255,255,0.7)'"
                   onmouseout="this.style.color='rgba(255,255,255,0.35)'">Shipping Policy</a>
                <a href="{{ route('login') }}" style="color:inherit;text-decoration:none"
                   onmouseover="this.style.color='rgba(255,255,255,0.7)'"
                   onmouseout="this.style.color='rgba(255,255,255,0.35)'">Partner Login</a>
            </div>
        </div>
    </div>

</footer>
