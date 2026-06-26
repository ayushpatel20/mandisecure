@extends('layouts.app')

@section('title', 'Checkout — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'cart'])
@endsection

@section('content')

    <div class="mb-4">
        <h4 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2 text-success"></i>Checkout</h4>
        <small class="text-muted">Review your order and enter delivery details</small>
    </div>

    <form method="POST" action="{{ route('buyer.checkout.place') }}" id="checkoutForm">
        @csrf

        <div class="row g-4">

            {{-- Customer Information --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-person-fill me-1 text-success"></i> Customer Information
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile', $user->mobile) }}"
                                           placeholder="10-digit mobile" maxlength="10" required>
                                </div>
                                @error('mobile')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-geo-alt-fill me-1 text-success"></i> Delivery Address
                    </div>
                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
                                <textarea name="delivery_address" rows="3"
                                          class="form-control @error('delivery_address') is-invalid @enderror"
                                          placeholder="House no., Street, Area, Landmark…" required>{{ old('delivery_address', $user->address) }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                                <select name="state" class="form-select @error('state') is-invalid @enderror" required>
                                    <option value="">— Select State —</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state }}"
                                            {{ old('state') === $state ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">District <span class="text-danger">*</span></label>
                                <input type="text" name="district"
                                       class="form-control @error('district') is-invalid @enderror"
                                       value="{{ old('district') }}" placeholder="District" required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">PIN Code <span class="text-danger">*</span></label>
                                <input type="text" name="pin_code"
                                       class="form-control @error('pin_code') is-invalid @enderror"
                                       value="{{ old('pin_code') }}" placeholder="6-digit PIN" maxlength="6" required>
                                @error('pin_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Order Notes <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="notes" rows="2" class="form-control"
                                          placeholder="Any special instructions for your order…">{{ old('notes') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Payment & Bank Details --}}
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-bank text-success me-1"></i> Payment & Bank Details
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">You can make payments using Bank Transfer or UPI. Here are our official account details:</p>
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr>
                                <td class="text-muted pe-3" style="width: 140px; padding: 0.2rem 0;">Holder Name</td>
                                <td class="fw-semibold text-dark" style="padding: 0.2rem 0;">{{ \App\Models\Setting::get('payment_bank_account_holder') ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding: 0.2rem 0;">Bank Name</td>
                                <td class="fw-semibold text-dark" style="padding: 0.2rem 0;">{{ \App\Models\Setting::get('payment_bank_name') ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding: 0.2rem 0;">Account Number</td>
                                <td class="fw-semibold text-dark" style="padding: 0.2rem 0;">{{ \App\Models\Setting::get('payment_bank_account_number') ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" style="padding: 0.2rem 0;">IFSC Code</td>
                                <td class="fw-semibold text-dark" style="padding: 0.2rem 0;">{{ \App\Models\Setting::get('payment_bank_ifsc') ?: '—' }}</td>
                            </tr>
                            @if (\App\Models\Setting::get('payment_upi_id'))
                            <tr>
                                <td class="text-muted" style="padding: 0.2rem 0;">UPI ID</td>
                                <td class="fw-semibold text-success" style="padding: 0.2rem 0;">{{ \App\Models\Setting::get('payment_upi_id') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

            </div>

            {{-- Order Summary --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top:1rem">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-receipt me-1 text-success"></i> Order Summary
                    </div>
                    <div class="card-body p-0">

                        {{-- Items list --}}
                        @foreach ($items as $item)
                            @php $product = $item->product; @endphp
                            <div class="d-flex align-items-center gap-2 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <img src="{{ $product->image ? Storage::url($product->image) : asset('images/category.jpg') }}"
                                     alt="{{ $product->product_name }}"
                                     class="rounded"
                                     style="width:52px;height:52px;object-fit:cover;flex-shrink:0"
                                     loading="lazy">

                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold small text-truncate">{{ $product->product_name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">
                                        {{ $item->quantity }} {{ $product->unit }}
                                        × ₹{{ number_format($item->effectivePrice(), 2) }}
                                    </div>
                                    @if ($product->delivery_charges > 0)
                                        <div class="text-muted" style="font-size:0.72rem">
                                            + ₹{{ number_format($product->delivery_charges, 2) }} delivery
                                        </div>
                                    @else
                                        <div class="text-success" style="font-size:0.72rem">Free delivery</div>
                                    @endif
                                </div>

                                <div class="fw-semibold text-success small text-nowrap">
                                    ₹{{ number_format($item->subtotal(), 2) }}
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Delivery Charges</span>
                            @if ($deliveryCharges > 0)
                                <span>₹{{ number_format($deliveryCharges, 2) }}</span>
                            @else
                                <span class="text-success">Free</span>
                            @endif
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span class="fs-5">Total</span>
                            <span class="fs-5 text-success">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-bag-check me-1"></i> Place Order
                            </button>
                            <a href="{{ route('buyer.cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Cart
                            </a>
                        </div>

                        <p class="text-center text-muted small mt-3 mb-0">
                            <i class="bi bi-shield-check me-1 text-success"></i>
                            No payment required now — Cash on Delivery
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection
