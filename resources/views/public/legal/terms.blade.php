@extends('public.layouts.app')

@section('title', 'Terms & Conditions — MandiSecure')
@section('meta_description', 'Read the Terms and Conditions governing your use of MandiSecure, India\'s premier agricultural B2B marketplace.')

@section('content')

@include('public.legal._hero', [
    'title'   => 'Terms & Conditions',
    'updated' => 'June 1, 2025',
    'icon'    => 'bi-file-earmark-text',
])

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 legal-body">

                <p class="lead" style="color:var(--ms-muted);font-size:0.98rem;line-height:1.8">
                    Please read these Terms and Conditions carefully before using the MandiSecure platform.
                    By accessing or using our services you agree to be bound by these terms. If you disagree,
                    please do not use the platform.
                </p>

                @include('public.legal._toc', ['items' => [
                    'Acceptance of Terms',
                    'Eligibility & Account Registration',
                    'Roles: Buyers, Sellers & Admins',
                    'Product Listings & Pricing',
                    'Orders & Transactions',
                    'Payments & Fees',
                    'Prohibited Conduct',
                    'Intellectual Property',
                    'Limitation of Liability',
                    'Amendments & Termination',
                    'Governing Law',
                    'Contact Us',
                ]])

                {{-- Section 1 --}}
                <h2 id="s1">1. Acceptance of Terms</h2>
                <p>
                    By creating an account, browsing the platform, or completing a transaction on MandiSecure
                    (owned and operated by <strong>Mandi Secure Private Limited</strong>), you confirm that you
                    have read, understood, and agree to these Terms and Conditions along with our Privacy Policy
                    and all applicable laws and regulations.
                </p>
                <p>
                    These Terms constitute a legally binding agreement between you and Mandi Secure Private Limited.
                    Continued use after any modification constitutes acceptance of the updated Terms.
                </p>

                {{-- Section 2 --}}
                <h2 id="s2">2. Eligibility & Account Registration</h2>
                <p>To use MandiSecure you must:</p>
                <ul>
                    <li>Be at least 18 years of age or the age of majority in your jurisdiction.</li>
                    <li>Provide accurate, current, and complete information during registration.</li>
                    <li>Maintain the security of your password and notify us immediately of any unauthorised access.</li>
                    <li>Not create duplicate or impersonation accounts.</li>
                </ul>
                <p>
                    We reserve the right to refuse registration or suspend any account that violates these
                    requirements or any applicable law.
                </p>

                {{-- Section 3 --}}
                <h2 id="s3">3. Roles: Buyers, Sellers & Admins</h2>
                <h4>Sellers</h4>
                <p>
                    Sellers must be verified agricultural producers, traders, or businesses. All product listings
                    must be accurate, lawful, and comply with Indian food safety, weights &amp; measures, and
                    agricultural regulations. Sellers are solely responsible for the quality, packaging, labelling,
                    and delivery of their goods.
                </p>
                <h4>Buyers</h4>
                <p>
                    Buyers are responsible for reviewing product specifications before placing orders. By submitting
                    an order the buyer accepts the listed price, quantity, and delivery terms.
                </p>
                <h4>Admins</h4>
                <p>
                    Platform administrators may approve, reject, suspend, or remove any listing, account, or
                    transaction at their sole discretion to maintain marketplace integrity.
                </p>

                {{-- Section 4 --}}
                <h2 id="s4">4. Product Listings & Pricing</h2>
                <ul>
                    <li>Prices are listed in Indian Rupees (INR) and are exclusive of applicable taxes unless stated otherwise.</li>
                    <li>MandiSecure does not guarantee the accuracy of seller-provided prices, stock levels, or descriptions.</li>
                    <li>Sellers must not list products that are counterfeit, prohibited, or hazardous under Indian law.</li>
                    <li>We reserve the right to remove listings that violate our policies without prior notice.</li>
                </ul>

                {{-- Section 5 --}}
                <h2 id="s5">5. Orders & Transactions</h2>
                <p>
                    An order is confirmed only after the seller accepts the buyer's purchase request. MandiSecure
                    acts as a marketplace facilitator and is not a party to the contract of sale between buyer
                    and seller. Disputes regarding product quality or delivery must be resolved between the
                    transacting parties, with MandiSecure providing mediation support where possible.
                </p>
                <p>
                    Cancellations are subject to the individual seller's cancellation policy displayed on the
                    listing page. See our <a href="{{ route('public.refund') }}">Refund Policy</a> for details.
                </p>

                {{-- Section 6 --}}
                <h2 id="s6">6. Payments & Fees</h2>
                <ul>
                    <li>All payments must be made through the platform's approved payment channels.</li>
                    <li>MandiSecure charges a platform fee as communicated in the Seller Agreement; this is
                        subject to change with 30 days' notice.</li>
                    <li>Sellers receive payment after successful delivery confirmation, subject to any applicable
                        hold period for dispute resolution.</li>
                    <li>Any taxes arising from the transaction are the responsibility of the transacting parties.</li>
                </ul>

                {{-- Section 7 --}}
                <h2 id="s7">7. Prohibited Conduct</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the platform for any unlawful, fraudulent, or deceptive activity.</li>
                    <li>Circumvent or manipulate fees, pricing, or payment systems.</li>
                    <li>Upload viruses or malicious code or interfere with the platform's operations.</li>
                    <li>Harvest or scrape user data without express written consent.</li>
                    <li>Post false reviews, ratings, or product information.</li>
                    <li>Use automated bots or scripts to access the platform without authorisation.</li>
                </ul>
                <p>Violation of these prohibitions may result in immediate account termination and legal action.</p>

                {{-- Section 8 --}}
                <h2 id="s8">8. Intellectual Property</h2>
                <p>
                    All content on MandiSecure — including logos, design, software, text, and graphics — is
                    the property of Mandi Secure Private Limited or its content suppliers and is protected by
                    Indian and international intellectual property laws. You may not copy, reproduce, distribute,
                    or create derivative works without our prior written consent.
                </p>
                <p>
                    By uploading product images or descriptions, you grant MandiSecure a non-exclusive,
                    royalty-free, worldwide licence to use such content for platform operation and marketing.
                </p>

                {{-- Section 9 --}}
                <h2 id="s9">9. Limitation of Liability</h2>
                <p>
                    To the maximum extent permitted by law, Mandi Secure Private Limited shall not be liable
                    for any indirect, incidental, special, or consequential damages, including loss of profits
                    or data, arising from your use of the platform or inability to use it. Our aggregate liability
                    for any claim shall not exceed the platform fees paid by you in the three months preceding
                    the claim.
                </p>
                <p>
                    The platform is provided "as is" without warranties of any kind, express or implied.
                </p>

                {{-- Section 10 --}}
                <h2 id="s10">10. Amendments & Termination</h2>
                <p>
                    We may update these Terms at any time. Material changes will be communicated via email or
                    in-platform notification at least 14 days before taking effect. Your continued use after
                    the effective date constitutes acceptance.
                </p>
                <p>
                    We may suspend or terminate your account at any time, with or without cause, with reasonable
                    notice except in cases of fraud or serious policy violations where immediate action is required.
                </p>

                {{-- Section 11 --}}
                <h2 id="s11">11. Governing Law</h2>
                <p>
                    These Terms are governed by and construed in accordance with the laws of India. Any disputes
                    shall be subject to the exclusive jurisdiction of the courts in <strong>Mandya, Karnataka</strong>.
                    Any unresolved disputes shall first be referred to arbitration under the Arbitration and
                    Conciliation Act, 1996.
                </p>

                {{-- Section 12 --}}
                <h2 id="s12">12. Contact Us</h2>
                <p>If you have any questions about these Terms, please contact us:</p>
                @include('public.legal._contact')

            </div>
        </div>
    </div>
</section>

@endsection
