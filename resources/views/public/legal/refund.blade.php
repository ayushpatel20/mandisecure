@extends('public.layouts.app')

@section('title', 'Refund Policy — MandiSecure')
@section('meta_description', 'Understand MandiSecure\'s cancellation, return, and refund policy for agricultural product transactions.')

@section('content')

@include('public.legal._hero', [
    'title'   => 'Refund Policy',
    'updated' => 'June 1, 2025',
    'icon'    => 'bi-arrow-counterclockwise',
])

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 legal-body">

                <p class="lead" style="color:var(--ms-muted);font-size:0.98rem;line-height:1.8">
                    MandiSecure is committed to fair and transparent trade. This policy explains when and how
                    cancellations, returns, and refunds are processed for transactions on our platform.
                </p>

                @include('public.legal._toc', ['items' => [
                    'Scope of This Policy',
                    'Order Cancellation',
                    'Returns & Rejected Goods',
                    'Refund Eligibility',
                    'Refund Processing',
                    'Dispute Resolution',
                    'Platform Fee Refunds',
                    'Exceptions & Non-Refundable Items',
                    'Changes to This Policy',
                    'Contact Us',
                ]])

                {{-- Section 1 --}}
                <h2 id="s1">1. Scope of This Policy</h2>
                <p>
                    This Refund Policy applies to all buyers and sellers transacting on the MandiSecure
                    marketplace (operated by <strong>Mandi Secure Private Limited</strong>). It covers
                    agricultural produce, processed goods, and other products listed on the platform.
                </p>
                <p>
                    Because MandiSecure operates as a marketplace facilitator, the primary responsibility for
                    honouring returns and refunds lies with the seller. MandiSecure facilitates resolution and,
                    where appropriate, releases or withholds payments accordingly.
                </p>

                {{-- Section 2 --}}
                <h2 id="s2">2. Order Cancellation</h2>
                <h4>Buyer-Initiated Cancellation</h4>
                <ul>
                    <li><strong>Before seller acceptance:</strong> Orders can be cancelled at no cost by the buyer at any time before the seller confirms the order.</li>
                    <li><strong>After seller acceptance but before dispatch:</strong> Cancellation is subject to the seller's approval. A partial platform fee may be retained.</li>
                    <li><strong>After dispatch:</strong> Cancellation is not permitted. The buyer must await delivery and then raise a return request if applicable.</li>
                </ul>
                <h4>Seller-Initiated Cancellation</h4>
                <p>
                    If a seller cancels a confirmed order due to stock unavailability or any other reason, the
                    buyer receives a full refund including all platform convenience fees. Repeated seller
                    cancellations may result in account penalties or suspension.
                </p>

                {{-- Section 3 --}}
                <h2 id="s3">3. Returns & Rejected Goods</h2>
                <p>A return request may be raised by the buyer within <strong>48 hours of delivery</strong> under the following conditions:</p>
                <ul>
                    <li>Product received is significantly different from the listing description (variety, grade, or weight discrepancy &gt; 5%).</li>
                    <li>Product is damaged, spoiled, or unfit for consumption on arrival (supported by photographic evidence).</li>
                    <li>Wrong product delivered.</li>
                </ul>
                <p>
                    Return requests must be submitted via the buyer dashboard with photographic/video evidence.
                    Perishable goods (fresh vegetables, fruits, coconut) are assessed on a case-by-case basis
                    given their short shelf life.
                </p>
                <p>
                    Goods returned must be in their original packaging where possible, and the buyer is
                    responsible for maintaining their condition until pickup by the seller or logistics partner.
                </p>

                {{-- Section 4 --}}
                <h2 id="s4">4. Refund Eligibility</h2>
                <p>Refunds are issued in the following scenarios:</p>
                <ul>
                    <li>Seller confirms the return and accepts responsibility.</li>
                    <li>MandiSecure's dispute team independently verifies the buyer's claim after review.</li>
                    <li>Delivery failed and the product was not received by the buyer (confirmed by logistics).</li>
                    <li>Payment was debited but the order was not confirmed due to a technical error.</li>
                </ul>
                <p>Refunds are <strong>not</strong> issued for:</p>
                <ul>
                    <li>Change-of-mind returns for fresh produce after delivery.</li>
                    <li>Price fluctuations after the order is placed.</li>
                    <li>Natural quality degradation of perishables due to buyer-side handling.</li>
                    <li>Orders where the buyer has not raised a claim within the 48-hour window.</li>
                </ul>

                {{-- Section 5 --}}
                <h2 id="s5">5. Refund Processing</h2>
                <p>
                    Once a refund is approved, the amount is credited back to the original payment method within
                    the following timelines:
                </p>
                <ul>
                    <li><strong>UPI / Net Banking:</strong> 2–5 business days</li>
                    <li><strong>Credit / Debit Card:</strong> 5–7 business days</li>
                    <li><strong>Wallet / BNPL:</strong> 3–5 business days</li>
                    <li><strong>NEFT / RTGS (bulk orders):</strong> 5–7 business days</li>
                </ul>
                <p>
                    MandiSecure will send a confirmation email when the refund is initiated. Delays beyond
                    these timelines should first be checked with your bank before contacting us.
                </p>

                {{-- Section 6 --}}
                <h2 id="s6">6. Dispute Resolution</h2>
                <p>
                    If a buyer and seller cannot agree on a return or refund, either party may escalate to
                    MandiSecure's dispute team via the order dashboard or by emailing
                    <a href="mailto:Headoffice@mandisecure.com">Headoffice@mandisecure.com</a> with the order ID
                    and supporting evidence.
                </p>
                <p>
                    MandiSecure will review all submitted evidence and issue a binding decision within
                    <strong>7 business days</strong>. Our decision regarding platform-mediated disputes is final
                    and will be communicated to both parties by email.
                </p>

                {{-- Section 7 --}}
                <h2 id="s7">7. Platform Fee Refunds</h2>
                <p>
                    Platform convenience fees are generally non-refundable once an order has been confirmed.
                    Exceptions apply when:
                </p>
                <ul>
                    <li>The order was cancelled before seller acceptance.</li>
                    <li>A technical error resulted in a duplicate payment.</li>
                    <li>The seller cancelled a confirmed order (buyer's fee is refunded in full).</li>
                </ul>

                {{-- Section 8 --}}
                <h2 id="s8">8. Exceptions & Non-Refundable Items</h2>
                <p>The following categories have specific or no-refund conditions:</p>
                <ul>
                    <li><strong>Custom / contractual bulk orders</strong> — governed by the individual contract signed between buyer and seller.</li>
                    <li><strong>Highly perishable goods</strong> — coconut milk, fresh-cut produce, and similar items where claims must be supported by delivery-time photo evidence.</li>
                    <li><strong>Export orders</strong> — subject to export regulations; contact us before placing export orders to understand the applicable refund framework.</li>
                </ul>

                {{-- Section 9 --}}
                <h2 id="s9">9. Changes to This Policy</h2>
                <p>
                    Mandi Secure Private Limited reserves the right to modify this Refund Policy at any time.
                    Changes will be posted on this page with an updated date. For significant changes, registered
                    users will be notified via email. Your continued use of the platform after such changes
                    constitutes acceptance of the revised policy.
                </p>

                {{-- Section 10 --}}
                <h2 id="s10">10. Contact Us</h2>
                <p>For refund queries or to escalate a dispute, please reach us at:</p>
                @include('public.legal._contact')

            </div>
        </div>
    </div>
</section>

@endsection
