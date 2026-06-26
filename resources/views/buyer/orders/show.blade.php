@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item">
                <a href="{{ route('buyer.dashboard') }}" class="text-decoration-none">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('buyer.orders.index') }}" class="text-decoration-none">My Orders</a>
            </li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    {{-- Order Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-bag-check me-2 text-success"></i>{{ $order->order_number }}
            </h4>
            <div class="text-muted small mt-1">
                Placed on {{ $order->created_at->format('d M Y') }} at {{ $order->created_at->format('h:i A') }}
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="badge bg-{{ $order->statusColor() }} fs-6 px-3 py-2">
                {{ $order->statusLabel() }}
            </span>
            <a href="{{ route('buyer.orders.invoice', $order) }}"
               target="_blank"
               class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-text me-1"></i> Invoice
            </a>
        </div>
    </div>

    {{-- Status Timeline --}}
    @include('admin.orders._timeline', ['order' => $order])

    {{-- Tracking Card (shown when shipped or delivered with tracking info) --}}
    @if (in_array($order->status, ['shipped', 'delivered']) && $order->hasTracking())
        <div class="card border-0 shadow-sm mb-4"
             style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-left:4px solid #198754 !important">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="fw-bold mb-1">
                            <i class="bi bi-truck me-1 text-success"></i> Your order is on its way
                        </div>
                        <div class="d-flex flex-wrap gap-3 small">
                            @if ($order->courier)
                                <span>
                                    <span class="text-muted">Courier:</span>
                                    <strong class="ms-1">{{ $order->courier }}</strong>
                                </span>
                            @endif
                            @if ($order->tracking_number)
                                <span>
                                    <span class="text-muted">Tracking #:</span>
                                    <code class="ms-1 fw-bold text-success fs-6">{{ $order->tracking_number }}</code>
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('buyer.orders.tracking', $order) }}"
                       class="btn btn-success btn-sm">
                        <i class="bi bi-geo-alt me-1"></i> Track Order
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Status History Log --}}
    @if ($order->statusLogs->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-1 text-success"></i> Order Activity</span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                    {{ $order->statusLogs->count() }} update{{ $order->statusLogs->count() !== 1 ? 's' : '' }}
                </span>
            </div>
            <div class="card-body p-0">
                @foreach ($order->statusLogs->sortByDesc('id') as $log)
                    <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="pt-1">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-{{ $log->statusColor() }} text-white"
                                  style="width:28px;height:28px;font-size:0.65rem;flex-shrink:0">
                                <i class="bi bi-circle-fill" style="font-size:6px"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-baseline flex-wrap gap-1">
                                <span class="fw-semibold">{{ $log->statusLabel() }}</span>
                                <small class="text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                            @if ($log->remarks)
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-chat-text me-1"></i>{{ $log->remarks }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-4">

        {{-- Order Items --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-box me-1 text-success"></i>
                    Order Items ({{ $order->items->count() }})
                </div>
                <div class="card-body p-0">
                    @foreach ($order->items as $item)
                        <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex gap-3 align-items-start">
                                <div style="width:64px;height:64px;flex-shrink:0">
                                        <img src="{{ ($item->product && $item->product->image) ? Storage::url($item->product->image) : asset('images/category.jpg') }}"
                                             alt="{{ $item->product_name }}"
                                             class="rounded w-100 h-100"
                                             style="object-fit:cover"
                                             loading="lazy">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    @if ($item->seller)
                                        <div class="text-muted small">
                                            <i class="bi bi-person me-1"></i>{{ $item->seller->name }}
                                        </div>
                                    @endif
                                    <div class="text-muted small">
                                        {{ $item->quantity }} {{ $item->unit }}
                                        × ₹{{ number_format($item->unit_price, 2) }}
                                    </div>
                                    @if ($item->delivery_charges > 0)
                                        <div class="text-muted small">
                                            + ₹{{ number_format($item->delivery_charges, 2) }} delivery
                                        </div>
                                    @else
                                        <div class="text-success small">Free delivery</div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">
                                        ₹{{ number_format($item->subtotal, 2) }}
                                    </div>
                                    @if ($item->product)
                                        <a href="{{ route('buyer.products.show', $item->product->slug) }}"
                                           class="btn btn-link btn-sm p-0 text-success"
                                           style="font-size:0.78rem">
                                            View Product
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bill + Delivery --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-receipt me-1 text-success"></i> Bill Summary
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Delivery Charges</span>
                        @if ($order->delivery_charges > 0)
                            <span>₹{{ number_format($order->delivery_charges, 2) }}</span>
                        @else
                            <span class="text-success">Free</span>
                        @endif
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-success">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    @if ($order->payment)
                        <div class="text-muted small mt-2">
                            <i class="{{ $order->payment->methodIcon() }} me-1"></i>
                            {{ $order->payment->methodLabel() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-geo-alt me-1 text-success"></i> Delivery Details
                </div>
                <div class="card-body small">
                    <div class="fw-semibold">{{ $order->name }}</div>
                    <div class="text-muted">{{ $order->mobile }}</div>
                    <div class="text-muted">{{ $order->email }}</div>
                    <hr class="my-2">
                    <div>{{ $order->delivery_address }}</div>
                    <div class="text-muted">{{ $order->district }}, {{ $order->state }} — {{ $order->pin_code }}</div>
                    @if ($order->notes)
                        <hr class="my-2">
                        <div class="text-muted">
                            <i class="bi bi-chat-text me-1"></i>{{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    {{-- Payment --}}
    @php $payment = $order->payment; @endphp
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-credit-card me-1 text-success"></i> Payment
        </div>
        <div class="card-body">
            @if ($payment)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-{{ $payment->statusColor() }} fs-6 px-3">
                                {{ $payment->statusLabel() }}
                            </span>
                            <span class="text-muted small">via {{ $payment->methodLabel() }}</span>
                        </div>
                        @if ($payment->transaction_id)
                            <div class="text-muted small">Ref: <strong>{{ $payment->transaction_id }}</strong></div>
                        @endif
                        @if ($payment->payment_date)
                            <div class="text-muted small">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $payment->payment_date->format('d M Y, h:i A') }}
                            </div>
                        @endif
                        @if ($payment->remarks)
                            <div class="text-muted small mt-1">
                                <i class="bi bi-chat-text me-1"></i> {{ $payment->remarks }}
                            </div>
                        @endif
                    </div>
                    <div class="fw-bold fs-5 text-success">₹{{ number_format($payment->amount, 2) }}</div>
                </div>
                @if ($payment->isFailed())
                    <hr class="my-3">
                    <p class="text-danger small mb-2">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Your payment was not approved. Please re-submit.
                    </p>
                    <a href="{{ route('buyer.payment.create', $order) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Retry Payment
                    </a>
                @endif
            @else
                <p class="text-muted mb-3">No payment submitted for this order.</p>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="fw-semibold small mb-2 text-dark">
                                <i class="bi bi-bank text-success me-1"></i> Bank Transfer details
                            </div>
                            <table class="table table-sm table-borderless mb-0 small text-dark">
                                <tr>
                                    <td class="text-muted pe-3" style="width: 130px; padding: 0.15rem 0;">Holder Name</td>
                                    <td class="fw-semibold" style="padding: 0.15rem 0;">{{ \App\Models\Setting::get('payment_bank_account_holder') ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted" style="padding: 0.15rem 0;">Bank Name</td>
                                    <td class="fw-semibold" style="padding: 0.15rem 0;">{{ \App\Models\Setting::get('payment_bank_name') ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted" style="padding: 0.15rem 0;">Account Number</td>
                                    <td class="fw-semibold" style="padding: 0.15rem 0;">{{ \App\Models\Setting::get('payment_bank_account_number') ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted" style="padding: 0.15rem 0;">IFSC Code</td>
                                    <td class="fw-semibold" style="padding: 0.15rem 0;">{{ \App\Models\Setting::get('payment_bank_ifsc') ?: '—' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if (\App\Models\Setting::get('payment_upi_id'))
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <div class="fw-semibold small mb-2 text-dark">
                                <i class="bi bi-phone text-success me-1"></i> UPI details
                            </div>
                            <table class="table table-sm table-borderless mb-0 small text-dark">
                                <tr>
                                    <td class="text-muted pe-3" style="width: 130px; padding: 0.15rem 0;">UPI ID</td>
                                    <td class="fw-semibold text-success" style="padding: 0.15rem 0;">{{ \App\Models\Setting::get('payment_upi_id') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <a href="{{ route('buyer.payment.create', $order) }}" class="btn btn-success">
                    <i class="bi bi-credit-card me-1"></i> Make Payment
                </a>
            @endif
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('buyer.orders.index') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
        <a href="{{ route('buyer.orders.invoice', $order) }}"
           target="_blank"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Print Invoice
        </a>
    </div>

@endsection
