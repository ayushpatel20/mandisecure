@extends('layouts.app')

@section('title', 'Complete Payment — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'payments'])
@endsection

@push('styles')
<style>
.method-card { cursor: pointer; border: 2px solid #dee2e6; transition: border-color 0.15s, box-shadow 0.15s; }
.method-card:hover { border-color: #198754; box-shadow: 0 2px 10px rgba(25,135,84,0.15); }
.method-card.selected { border-color: #198754; background: #f0faf4; }
.method-details { display: none; }
.method-details.active { display: block; }
</style>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item">
                <a href="{{ route('buyer.orders.index') }}" class="text-decoration-none">My Orders</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('buyer.orders.show', $order) }}" class="text-decoration-none">
                    {{ $order->order_number }}
                </a>
            </li>
            <li class="breadcrumb-item active">Payment</li>
        </ol>
    </nav>

    @if ($failedPayment)
        <div class="alert alert-warning border-0 mb-4">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Your previous payment was <strong>rejected</strong>.
            @if ($failedPayment->remarks)
                Reason: {{ $failedPayment->remarks }}.
            @endif
            Please re-submit with valid payment proof.
        </div>
    @endif

    <div class="row g-4">

        {{-- Payment Form --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-credit-card me-1 text-success"></i> Choose Payment Method
                </div>
                <div class="card-body">

                    <form method="POST"
                          action="{{ route('buyer.payment.store', $order) }}"
                          enctype="multipart/form-data"
                          id="paymentForm">
                        @csrf

                        {{-- Method Selection --}}
                        <div class="row g-3 mb-4">

                            <div class="col-12 col-sm-4">
                                <label class="method-card rounded-3 p-3 text-center w-100 d-block {{ old('payment_method') === 'upi' ? 'selected' : '' }}"
                                       for="method_upi">
                                    <input type="radio" name="payment_method" id="method_upi"
                                           value="upi" class="d-none"
                                           {{ old('payment_method') === 'upi' ? 'checked' : '' }}>
                                    <i class="bi bi-phone fs-2 text-success d-block mb-1"></i>
                                    <span class="fw-semibold">UPI</span>
                                </label>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label class="method-card rounded-3 p-3 text-center w-100 d-block {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}"
                                       for="method_bank">
                                    <input type="radio" name="payment_method" id="method_bank"
                                           value="bank_transfer" class="d-none"
                                           {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                    <i class="bi bi-bank fs-2 text-success d-block mb-1"></i>
                                    <span class="fw-semibold">Bank Transfer</span>
                                </label>
                            </div>

                            <div class="col-12 col-sm-4">
                                <label class="method-card rounded-3 p-3 text-center w-100 d-block {{ old('payment_method') === 'cod' ? 'selected' : '' }}"
                                       for="method_cod">
                                    <input type="radio" name="payment_method" id="method_cod"
                                           value="cod" class="d-none"
                                           {{ old('payment_method') === 'cod' ? 'checked' : '' }}>
                                    <i class="bi bi-cash-coin fs-2 text-success d-block mb-1"></i>
                                    <span class="fw-semibold">Cash on Delivery</span>
                                </label>
                            </div>

                        </div>

                        @error('payment_method')
                            <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror

                        {{-- UPI Details --}}
                        <div id="upi_details" class="method-details {{ old('payment_method') === 'upi' ? 'active' : '' }}">
                            <div class="card bg-light border-0 rounded-3 p-4 mb-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-phone me-1 text-success"></i> UPI Payment Details
                                </h6>
                                <div class="text-center mb-3">
                                    <div class="fs-5 fw-bold text-success border border-success rounded-3 px-4 py-2 d-inline-block">
                                        {{ $settings['upi_id'] }}
                                    </div>
                                    <div class="text-muted small mt-1">Send payment to this UPI ID</div>
                                </div>
                                <div class="alert alert-info py-2 small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Transfer <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                    and upload the payment screenshot below.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">UPI Transaction ID <span class="text-muted fw-normal">(optional)</span></label>
                                <input type="text" name="transaction_id" class="form-control"
                                       value="{{ old('transaction_id') }}"
                                       placeholder="e.g. UPI123456789012">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Payment Screenshot <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="screenshot"
                                       class="form-control @error('screenshot') is-invalid @enderror"
                                       accept="image/*">
                                <div class="form-text">JPG, PNG or WebP. Max 3 MB.</div>
                                @error('screenshot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Bank Transfer Details --}}
                        <div id="bank_details" class="method-details {{ old('payment_method') === 'bank_transfer' ? 'active' : '' }}">
                            <div class="card bg-light border-0 rounded-3 p-4 mb-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-bank me-1 text-success"></i> Bank Account Details
                                </h6>
                                <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted small fw-semibold" style="width:45%">Account Holder</td>
                                        <td class="fw-semibold">{{ $settings['bank_account_holder'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small fw-semibold">Account Number</td>
                                        <td class="fw-semibold">{{ $settings['bank_account_number'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small fw-semibold">IFSC Code</td>
                                        <td class="fw-semibold">{{ $settings['bank_ifsc'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted small fw-semibold">Bank Name</td>
                                        <td class="fw-semibold">{{ $settings['bank_name'] }}</td>
                                    </tr>
                                </table>
                                </div>
                                <div class="alert alert-info py-2 small mt-3 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Transfer <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                    and upload the bank transfer receipt below.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Transaction Reference <span class="text-muted fw-normal">(optional)</span></label>
                                <input type="text" name="transaction_id" class="form-control"
                                       value="{{ old('transaction_id') }}"
                                       placeholder="UTR or reference number">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Transfer Receipt / Screenshot <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="screenshot"
                                       class="form-control @error('screenshot') is-invalid @enderror"
                                       accept="image/*">
                                <div class="form-text">JPG, PNG or WebP. Max 3 MB.</div>
                                @error('screenshot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- COD Details --}}
                        <div id="cod_details" class="method-details {{ old('payment_method') === 'cod' ? 'active' : '' }}">
                            <div class="card bg-success bg-opacity-10 border-success border-opacity-25 rounded-3 p-4 mb-3">
                                <h6 class="fw-bold mb-2 text-success">
                                    <i class="bi bi-cash-coin me-1"></i> Cash on Delivery
                                </h6>
                                <p class="mb-2">Pay <strong>₹{{ number_format($order->total_amount, 2) }}</strong> in cash when your order is delivered.</p>
                                <ul class="small text-muted mb-0 ps-3">
                                    <li>No advance payment required</li>
                                    <li>Keep exact change ready</li>
                                    <li>Order will be confirmed immediately</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="bi bi-check-circle me-1"></i> Confirm Payment
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-top" style="top:1rem">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-receipt me-1 text-success"></i> Order Summary
                </div>
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Order</span>
                        <span class="fw-semibold">{{ $order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Items</span>
                        <span>{{ $order->items()->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Delivery</span>
                        @if ($order->delivery_charges > 0)
                            <span>₹{{ number_format($order->delivery_charges, 2) }}</span>
                        @else
                            <span class="text-success">Free</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between py-3 fw-bold fs-5">
                        <span>Total Payable</span>
                        <span class="text-success">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                <div class="card-footer bg-light small text-muted">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $order->district }}, {{ $order->state }}
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios    = document.querySelectorAll('input[name="payment_method"]');
    const cards     = document.querySelectorAll('.method-card');
    const submitBtn = document.getElementById('submitBtn');

    function activateMethod(val) {
        document.querySelectorAll('.method-details').forEach(d => d.classList.remove('active'));
        cards.forEach(c => c.classList.remove('selected'));
        if (val) {
            const det = document.getElementById(val + '_details');
            if (det) det.classList.add('active');
            const lbl = document.querySelector('[for="method_' + (val === 'bank_transfer' ? 'bank' : val) + '"]');
            if (lbl) lbl.classList.add('selected');
            submitBtn.disabled = false;
        }
    }

    // Init on page load (e.g. after validation error)
    const checked = document.querySelector('input[name="payment_method"]:checked');
    if (checked) activateMethod(checked.value);

    radios.forEach(r => r.addEventListener('change', () => activateMethod(r.value)));

    cards.forEach(card => {
        card.addEventListener('click', function () {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                activateMethod(radio.value);
            }
        });
    });
});
</script>
@endpush
