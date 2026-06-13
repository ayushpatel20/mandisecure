@extends('public.layouts.app')

@section('title', 'Privacy Policy — MandiSecure')
@section('meta_description', 'Read the MandiSecure Privacy Policy to understand how we collect, use, and protect your personal information on our agricultural marketplace.')

@push('seo')
<meta property="og:title" content="Privacy Policy — MandiSecure">
<meta property="og:description" content="How MandiSecure collects, uses, and protects your personal information.">
<meta property="og:url" content="{{ url('/privacy-policy') }}">
<link rel="canonical" href="{{ url('/privacy-policy') }}">
@endpush

@section('content')
@include('public.legal._hero', ['title' => 'Privacy Policy', 'updated' => 'June 1, 2025', 'icon' => 'bi-shield-lock-fill'])

<section class="py-5" style="background:#fff">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @include('public.legal._toc', ['items' => [
                    '1. Information We Collect',
                    '2. How We Use Your Information',
                    '3. Information Sharing',
                    '4. Data Security',
                    '5. Cookies',
                    '6. Your Rights',
                    '7. Third-Party Links',
                    '8. Children\'s Privacy',
                    '9. Changes to This Policy',
                    '10. Contact Us',
                ]])

                <div class="legal-body">

                    <h2 id="s1">1. Information We Collect</h2>
                    <p>When you use MandiSecure — operated by <strong>Mandi Secure Private Limited</strong> — we collect information necessary to provide our agricultural marketplace services.</p>
                    <h4>1.1 Information You Provide</h4>
                    <ul>
                        <li><strong>Account Registration:</strong> Name, email address, phone number, and role (buyer/seller).</li>
                        <li><strong>Product Listings:</strong> Product name, category, description, price, quantity, and images (sellers).</li>
                        <li><strong>Orders & Payments:</strong> Delivery address, payment screenshots, transaction references.</li>
                        <li><strong>Contact Forms:</strong> Name, email, phone, inquiry type, and message content.</li>
                    </ul>
                    <h4>1.2 Automatically Collected Information</h4>
                    <ul>
                        <li>IP address, browser type, device information, and pages visited.</li>
                        <li>Session data and cookies used for authentication and language preferences.</li>
                        <li>Referrer URLs and navigation patterns within the platform.</li>
                    </ul>

                    <h2 id="s2">2. How We Use Your Information</h2>
                    <p>We use your information solely for legitimate business purposes:</p>
                    <ul>
                        <li>To create and manage your account on MandiSecure.</li>
                        <li>To process orders, payments, and facilitate trade between buyers and sellers.</li>
                        <li>To verify seller identity and product quality.</li>
                        <li>To send transactional communications (order confirmations, payment receipts).</li>
                        <li>To respond to your inquiries and provide customer support.</li>
                        <li>To improve platform features, performance, and user experience.</li>
                        <li>To detect and prevent fraud or misuse of the platform.</li>
                        <li>To comply with applicable Indian laws and regulations.</li>
                    </ul>
                    <p>We do <strong>not</strong> sell, rent, or lease your personal information to third parties for marketing purposes.</p>

                    <h2 id="s3">3. Information Sharing</h2>
                    <p>We may share your information in the following limited circumstances:</p>
                    <ul>
                        <li><strong>Between Buyers and Sellers:</strong> Order-relevant details (name, delivery address, contact) are shared between transacting parties to fulfil orders.</li>
                        <li><strong>Logistics Partners:</strong> Delivery name and address are shared with logistics partners for order fulfilment.</li>
                        <li><strong>Legal Compliance:</strong> When required by Indian law, court order, or government authority.</li>
                        <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, subject to appropriate confidentiality protections.</li>
                    </ul>

                    <h2 id="s4">4. Data Security</h2>
                    <p>We implement industry-standard security measures to protect your information:</p>
                    <ul>
                        <li>Passwords are hashed using bcrypt and never stored in plaintext.</li>
                        <li>CSRF tokens protect all state-changing form submissions.</li>
                        <li>HTTPS encryption is enforced in production for all data transmission.</li>
                        <li>File uploads are stored securely in non-public storage with access controls.</li>
                        <li>Session data is encrypted and stored server-side.</li>
                    </ul>
                    <p>While we take all reasonable precautions, no system is completely immune to security risks. You are responsible for maintaining the confidentiality of your account credentials.</p>

                    <h2 id="s5">5. Cookies</h2>
                    <p>MandiSecure uses the following cookies:</p>
                    <ul>
                        <li><strong>Session Cookie:</strong> Maintains your login session. Expires when you close your browser.</li>
                        <li><strong>XSRF-TOKEN:</strong> Laravel CSRF protection token. Required for form security.</li>
                        <li><strong>app_locale:</strong> Stores your preferred language (English, Hindi, Kannada, Tamil). Persists for 5 years or until cleared.</li>
                        <li><strong>remember_token:</strong> Enables "Remember Me" login functionality.</li>
                    </ul>
                    <p>We do not use advertising or third-party tracking cookies. You may disable cookies in your browser settings, but this will impact platform functionality.</p>

                    <h2 id="s6">6. Your Rights</h2>
                    <p>Under applicable Indian data protection laws, you have the right to:</p>
                    <ul>
                        <li>Access the personal information we hold about you.</li>
                        <li>Request correction of inaccurate or outdated information.</li>
                        <li>Request deletion of your account and associated data (subject to legal retention requirements).</li>
                        <li>Opt out of non-essential communications.</li>
                    </ul>
                    <p>To exercise these rights, contact us at <a href="mailto:Headoffice@mandisecure.com">Headoffice@mandisecure.com</a>.</p>

                    <h2 id="s7">7. Third-Party Links</h2>
                    <p>MandiSecure may contain links to third-party websites (such as Google Maps for location). We are not responsible for the privacy practices of those sites. We recommend reviewing the privacy policies of any third-party site you visit.</p>

                    <h2 id="s8">8. Children's Privacy</h2>
                    <p>MandiSecure is not intended for use by individuals under the age of 18. We do not knowingly collect personal information from minors. If you believe we have inadvertently collected such information, please contact us immediately.</p>

                    <h2 id="s9">9. Changes to This Policy</h2>
                    <p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated "Last Revised" date. Continued use of the platform after changes constitutes your acceptance of the revised policy.</p>

                    <h2 id="s10">10. Contact Us</h2>
                    @include('public.legal._contact')

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
