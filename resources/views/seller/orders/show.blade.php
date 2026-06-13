@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Seller')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item">
                <a href="{{ route('seller.orders.index') }}" class="text-decoration-none">Orders</a>
            </li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-receipt me-2 text-success"></i>{{ $order->order_number }}
            </h4>
            <div class="text-muted small mt-1">
                {{ $order->created_at->format('d M Y, h:i A') }}
            </div>
        </div>
        <span class="badge bg-{{ $order->statusColor() }} fs-6 px-3 py-2">
            {{ $order->statusLabel() }}
        </span>
    </div>

    {{-- Timeline --}}
    @include('admin.orders._timeline', ['order' => $order])

    <div class="row g-4">

        {{-- Your Items + Status Update --}}
        <div class="col-lg-8">

            {{-- Your Items --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-box me-1 text-success"></i>
                    Your Items in This Order ({{ $sellerItems->count() }})
                </div>
                <div class="card-body p-0">
                    @foreach ($sellerItems as $item)
                        <div class="d-flex gap-3 p-3 align-items-center {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div style="width:52px;height:52px;flex-shrink:0">
                                @if ($item->product && $item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}"
                                         class="rounded w-100 h-100" style="object-fit:cover"
                                         alt="{{ $item->product_name }}">
                                @else
                                    <div class="rounded bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $item->product_name }}</div>
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
                            <div class="fw-bold text-success">
                                ₹{{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Existing tracking info --}}
            @if (in_array($order->status, ['shipped', 'delivered']) && $order->hasTracking())
                <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #198754 !important">
                    <div class="card-body small">
                        <div class="fw-semibold mb-1">
                            <i class="bi bi-truck me-1 text-success"></i> Shipment Info Saved
                        </div>
                        @if ($order->courier)
                            <div><span class="text-muted">Courier:</span> <strong>{{ $order->courier }}</strong></div>
                        @endif
                        @if ($order->tracking_number)
                            <div><span class="text-muted">Tracking #:</span> <code class="text-success fw-bold">{{ $order->tracking_number }}</code></div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Update Status --}}
            @if (!in_array($order->status, ['delivered', 'cancelled']))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-sliders me-1 text-success"></i> Update Order Status
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Update the status to reflect your fulfilment progress.
                        </p>
                        <form method="POST" action="{{ route('seller.orders.update-status', $order) }}">
                            @csrf @method('PATCH')
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">New Status</label>
                                    <select name="status" class="form-select" id="sellerStatusSelect"
                                            onchange="toggleSellerTracking(this.value)">
                                        @foreach (['confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ $order->status === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Remarks <span class="text-muted fw-normal">(optional)</span>
                                    </label>
                                    <input type="text" name="remarks" class="form-control"
                                           placeholder="e.g. Packed and ready to dispatch">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Update
                                    </button>
                                </div>
                            </div>

                            {{-- Tracking fields — shown only when Shipped selected --}}
                            <div id="sellerTrackingFields" class="row g-3 mt-1"
                                 style="{{ $order->status === 'shipped' ? '' : 'display:none' }}">
                                <div class="col-12">
                                    <hr class="my-1">
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-truck me-1"></i>
                                        Enter courier details so the buyer can track their order.
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-semibold">Courier / Carrier</label>
                                    <input type="text" name="courier" class="form-control"
                                           value="{{ old('courier', $order->courier) }}"
                                           placeholder="e.g. BlueDart, DTDC, India Post">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-semibold">Tracking Number</label>
                                    <input type="text" name="tracking_number" class="form-control"
                                           value="{{ old('tracking_number', $order->tracking_number) }}"
                                           placeholder="AWB / Docket number">
                                </div>
                            </div>
                        </form>

                        <script>
                        function toggleSellerTracking(val) {
                            document.getElementById('sellerTrackingFields').style.display = (val === 'shipped') ? '' : 'none';
                        }
                        </script>
                    </div>
                </div>
            @endif

            {{-- Status History --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock-history me-1 text-success"></i> Status History
                </div>
                <div class="card-body p-0">
                    @forelse ($order->statusLogs as $log)
                        <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="mt-1">
                                <span class="rounded-pill d-inline-block bg-{{ $log->statusColor() }}"
                                      style="width:10px;height:10px"></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <span class="fw-semibold">{{ $log->statusLabel() }}</span>
                                    <small class="text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                @if ($log->changedBy)
                                    <div class="text-muted small">
                                        <i class="bi bi-person me-1"></i>{{ $log->changedBy->name }}
                                        <span class="badge bg-light text-secondary border ms-1">
                                            {{ ucfirst($log->changedBy->role) }}
                                        </span>
                                    </div>
                                @endif
                                @if ($log->remarks)
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-chat-text me-1"></i>{{ $log->remarks }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-muted text-center small">No status history yet.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Delivery + Payment --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-geo-alt me-1 text-success"></i> Ship To
                </div>
                <div class="card-body small">
                    <div class="fw-semibold">{{ $order->name }}</div>
                    <div class="text-muted">{{ $order->mobile }}</div>
                    <hr class="my-2">
                    <div>{{ $order->delivery_address }}</div>
                    <div class="text-muted">{{ $order->district }}, {{ $order->state }} — {{ $order->pin_code }}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-credit-card me-1 text-success"></i> Payment
                </div>
                <div class="card-body small">
                    @if ($order->payment)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-{{ $order->payment->statusColor() }}">
                                {{ $order->payment->statusLabel() }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Method</span>
                            <span>{{ $order->payment->methodLabel() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Amount</span>
                            <span class="fw-bold text-success">₹{{ number_format($order->payment->amount, 2) }}</span>
                        </div>
                    @else
                        <div class="text-muted text-center py-2">No payment submitted.</div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <div class="mt-3">
        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

@endsection
