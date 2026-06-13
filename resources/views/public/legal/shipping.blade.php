@extends('public.layouts.app')

@section('title', 'Shipping Policy — MandiSecure')
@section('meta_description', 'Learn about MandiSecure\'s shipping, delivery timelines, logistics partners, and delivery terms for agricultural products across India.')

@section('content')

@include('public.legal._hero', [
    'title'   => 'Shipping Policy',
    'updated' => 'June 1, 2025',
    'icon'    => 'bi-truck',
])

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 legal-body">

                <p class="lead" style="color:var(--ms-muted);font-size:0.98rem;line-height:1.8">
                    MandiSecure partners with trusted logistics providers to deliver agricultural products
                    safely and efficiently across India and to select international destinations. This policy
                    outlines how shipping works on our platform.
                </p>

                @include('public.legal._toc', ['items' => [
                    'Shipping Coverage',
                    'Delivery Timelines',
                    'Shipping Charges',
                    'Packaging Standards',
                    'Cold Chain & Perishable Handling',
                    'Order Tracking',
                    'Failed Delivery & Re-delivery',
                    'Damaged Goods in Transit',
                    'International Shipping',
                    'Changes to This Policy',
                    'Contact Us',
                ]])

                {{-- Section 1 --}}
                <h2 id="s1">1. Shipping Coverage</h2>
                <p>MandiSecure currently ships to the following destinations:</p>
                <ul>
                    <li><strong>Domestic:</strong> All 28 states and 8 Union Territories of India, subject to seller availability and logistics partner coverage.</li>
                    <li><strong>International:</strong> Select countries in the Middle East, Southeast Asia, and the UK — available for verified export orders only. Contact us before placing an international order.</li>
                </ul>
                <p>
                    Certain remote pin codes (Andaman &amp; Nicobar Islands, Lakshadweep, high-altitude regions)
                    may incur additional charges or extended delivery times. The checkout page will display
                    serviceability for your specific address.
                </p>

                {{-- Section 2 --}}
                <h2 id="s2">2. Delivery Timelines</h2>
                <p>Estimated delivery timelines from the date of seller dispatch:</p>
                <ul>
                    <li><strong>Metro cities</strong> (Bengaluru, Mumbai, Delhi, Chennai, Hyderabad, Pune): 2–4 business days</li>
                    <li><strong>Tier-2 / Tier-3 cities:</strong> 3–6 business days</li>
                    <li><strong>Rural / remote areas:</strong> 5–10 business days</li>
                    <li><strong>International orders:</strong> 10–21 business days (subject to customs clearance)</li>
                </ul>
                <p>
                    Timelines are estimates and may be affected by public holidays, natural disasters, strikes,
                    or other force majeure events. MandiSecure will communicate significant delays proactively.
                </p>

                {{-- Section 3 --}}
                <h2 id="s3">3. Shipping Charges</h2>
                <ul>
                    <li><strong>Free shipping</strong> is offered on orders above ₹2,000 within metro areas where the seller has opted in.</li>
                    <li>For all other orders, shipping charges are calculated at checkout based on weight, volume, destination, and the seller's logistics setup.</li>
                    <li>Charges are displayed transparently before order confirmation — no hidden fees.</li>
                    <li><strong>Bulk / wholesale orders</strong> may qualify for negotiated freight rates. Contact the seller or our team directly.</li>
                </ul>

                {{-- Section 4 --}}
                <h2 id="s4">4. Packaging Standards</h2>
                <p>
                    Sellers on MandiSecure are required to adhere to our packaging guidelines to minimise
                    transit damage:
                </p>
                <ul>
                    <li>All produce must be packed in food-grade materials compliant with FSSAI standards.</li>
                    <li>Weight and quantity must match the declared amount at the time of dispatch.</li>
                    <li>Packages must be clearly labelled with product name, grade, weight, seller name,
                        and the order ID.</li>
                    <li>Perishable goods must use ventilated or insulated packaging as appropriate.</li>
                </ul>
                <p>
                    MandiSecure reserves the right to audit seller packaging standards and suspend listings
                    where standards are not met.
                </p>

                {{-- Section 5 --}}
                <h2 id="s5">5. Cold Chain & Perishable Handling</h2>
                <p>
                    For temperature-sensitive products (fresh vegetables, certain fruits, dairy-adjacent
                    agricultural products), MandiSecure works with logistics partners offering cold-chain
                    facilities. This service:
                </p>
                <ul>
                    <li>Is available in select corridors — check product listing for cold-chain eligibility.</li>
                    <li>May carry a premium shipping surcharge disclosed at checkout.</li>
                    <li>Requires the buyer to be present at the delivery address to receive and immediately store the goods.</li>
                </ul>
                <p>
                    MandiSecure and the seller are not liable for quality degradation caused by delays in
                    receiving the shipment on the buyer's end once delivery is attempted.
                </p>

                {{-- Section 6 --}}
                <h2 id="s6">6. Order Tracking</h2>
                <p>
                    Once the seller dispatches your order, you will receive an email and in-app notification
                    containing:
                </p>
                <ul>
                    <li>The logistics partner's name and tracking number.</li>
                    <li>A direct tracking URL.</li>
                    <li>Estimated delivery date.</li>
                </ul>
                <p>
                    You can also track your order from the <strong>My Orders</strong> section of your buyer
                    dashboard at any time.
                </p>

                {{-- Section 7 --}}
                <h2 id="s7">7. Failed Delivery & Re-delivery</h2>
                <p>
                    If a delivery attempt fails because the recipient is unavailable, the logistics partner
                    will typically make <strong>2–3 attempts</strong> before returning the package to the seller.
                </p>
                <ul>
                    <li>The buyer is responsible for ensuring someone is available at the delivery address or for rescheduling via the logistics partner's portal.</li>
                    <li>Re-delivery charges arising from multiple failed attempts may be borne by the buyer.</li>
                    <li>If a perishable shipment is returned due to failed delivery, a refund may not be possible. See our <a href="{{ route('public.refund') }}">Refund Policy</a> for details.</li>
                </ul>

                {{-- Section 8 --}}
                <h2 id="s8">8. Damaged Goods in Transit</h2>
                <p>
                    MandiSecure takes transit damage seriously. If your order arrives damaged:
                </p>
                <ol>
                    <li>Document the damage with photographs before accepting or opening the package further.</li>
                    <li>Note the damage on the delivery challan / POD if the logistics partner is present.</li>
                    <li>Raise a return / damage claim through your buyer dashboard within <strong>48 hours</strong> of delivery.</li>
                </ol>
                <p>
                    Claims submitted after 48 hours or without photographic evidence may not be eligible for
                    compensation. MandiSecure's dispute team will investigate and coordinate replacement or
                    refund as appropriate. See our <a href="{{ route('public.refund') }}">Refund Policy</a>.
                </p>

                {{-- Section 9 --}}
                <h2 id="s9">9. International Shipping</h2>
                <p>
                    For export orders, MandiSecure coordinates with licensed customs brokers and freight
                    forwarders. The buyer / importer is responsible for:
                </p>
                <ul>
                    <li>Compliance with the destination country's import regulations and phytosanitary requirements.</li>
                    <li>Payment of customs duties, import taxes, and any inspection fees at the destination port.</li>
                    <li>Obtaining any required import licences or permits before placing an order.</li>
                </ul>
                <p>
                    MandiSecure provides all necessary export documentation (phytosanitary certificate, certificate
                    of origin, commercial invoice, packing list) as required. Contact
                    <a href="mailto:Headoffice@mandisecure.com">Headoffice@mandisecure.com</a> to initiate an
                    international order.
                </p>

                {{-- Section 10 --}}
                <h2 id="s10">10. Changes to This Policy</h2>
                <p>
                    Mandi Secure Private Limited may update this Shipping Policy from time to time to reflect
                    changes in logistics partnerships, coverage, or regulations. Updated policies will be
                    posted on this page with a revised date. For material changes, registered users will be
                    notified via email.
                </p>

                {{-- Section 11 --}}
                <h2 id="s11">11. Contact Us</h2>
                <p>For shipping or delivery related queries, please reach us at:</p>
                @include('public.legal._contact')

            </div>
        </div>
    </div>
</section>

@endsection
