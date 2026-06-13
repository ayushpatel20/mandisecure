@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">Orders</a>
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
                Placed {{ $order->created_at->format('d M Y, h:i A') }}
            </div>
        </div>
        <span class="badge bg-{{ $order->statusColor() }} fs-6 px-3 py-2">
            {{ $order->statusLabel() }}
        </span>
    </div>

    {{-- Status Timeline --}}
    @include('admin.orders._timeline', ['order' => $order])

    <div class="row g-4 mt-1">

        {{-- Left column: items + status update --}}
        <div class="col-lg-8">

            {{-- Items --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-box me-1 text-success"></i>
                    Order Items ({{ $order->items->count() }})
                </div>
                <div class="card-body p-0">
                    @foreach ($order->items as $item)
                        <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex gap-3 align-items-start">
                                <div style="width:56px;height:56px;flex-shrink:0">
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
                                        Seller: <strong>{{ $item->seller->name ?? '—' }}</strong>
                                        @if ($item->product?->category)
                                            · {{ $item->product->category->name }}
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        {{ $item->quantity }} {{ $item->unit }}
                                        × ₹{{ number_format($item->unit_price, 2) }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">₹{{ number_format($item->subtotal, 2) }}</div>
                                    @if ($item->delivery_charges > 0)
                                        <div class="text-muted" style="font-size:0.72rem">
                                            + ₹{{ number_format($item->delivery_charges, 2) }} delivery
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="p-3 border-top bg-light d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-success">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Tracking info (when shipped or delivered) --}}
            @if (in_array($order->status, ['shipped', 'delivered']) && $order->hasTracking())
                <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #198754 !important">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">
                            <i class="bi bi-truck me-1 text-success"></i> Shipment Tracking
                        </div>
                        <div class="row g-2 small">
                            @if ($order->courier)
                                <div class="col-sm-6">
                                    <span class="text-muted">Courier:</span>
                                    <strong class="ms-1">{{ $order->courier }}</strong>
                                </div>
                            @endif
                            @if ($order->tracking_number)
                                <div class="col-sm-6">
                                    <span class="text-muted">Tracking #:</span>
                                    <code class="ms-1 text-success fw-bold">{{ $order->tracking_number }}</code>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Update Status --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-sliders me-1 text-success"></i> Update Order Status
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}"
                          id="adminStatusForm">
                        @csrf @method('PATCH')
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">New Status</label>
                                <select name="status" class="form-select" id="adminStatusSelect"
                                        onchange="toggleTrackingFields(this.value)">
                                    @foreach (App\Models\Order::$statuses as $val => $meta)
                                        <option value="{{ $val }}"
                                            {{ $order->status === $val ? 'selected' : '' }}>
                                            {{ $meta['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">
                                    Remarks <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <input type="text" name="remarks" class="form-control"
                                       placeholder="e.g. Package dispatched">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Update
                                </button>
                            </div>
                        </div>

                        {{-- Tracking fields — shown only when Shipped is selected --}}
                        <div id="trackingFields" class="row g-3 mt-1"
                             style="{{ $order->status === 'shipped' ? '' : 'display:none' }}">
                            <div class="col-12">
                                <hr class="my-1">
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-truck me-1"></i>
                                    Enter shipment details so the buyer can track their order.
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
                </div>
            </div>

            <script>
            function toggleTrackingFields(val) {
                document.getElementById('trackingFields').style.display = (val === 'shipped') ? '' : 'none';
            }
            </script>

            {{-- Status History Log --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock-history me-1 text-success"></i> Status History
                </div>
                <div class="card-body p-0">
                    @forelse ($order->statusLogs as $log)
                        <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="mt-1">
                                <span class="badge bg-{{ $log->statusColor() }} rounded-pill"
                                      style="width:10px;height:10px;display:inline-block;padding:0"></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <span class="fw-semibold">{{ $log->statusLabel() }}</span>
                                    <small class="text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                @if ($log->changedBy)
                                    <div class="text-muted small">
                                        <i class="bi bi-person me-1"></i>
                                        {{ $log->changedBy->name }}
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
                        <div class="p-3 text-muted small text-center">No status history recorded.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right column: buyer + delivery + payment --}}
        <div class="col-lg-4">

            {{-- Buyer Info --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person me-1 text-success"></i> Buyer
                </div>
                <div class="card-body small">
                    <div class="fw-semibold fs-6">{{ $order->buyer->name ?? '—' }}</div>
                    <div class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $order->buyer->email ?? '—' }}</div>
                    <div class="text-muted"><i class="bi bi-phone me-1"></i>{{ $order->buyer->mobile ?? '—' }}</div>
                </div>
            </div>

            {{-- Delivery --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-geo-alt me-1 text-success"></i> Delivery Address
                </div>
                <div class="card-body small">
                    <div class="fw-semibold">{{ $order->name }}</div>
                    <div class="text-muted">{{ $order->mobile }}</div>
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

            {{-- Payment --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-credit-card me-1 text-success"></i> Payment
                </div>
                <div class="card-body small">
                    @if ($order->payment)
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Method</span>
                            <span>{{ $order->payment->methodLabel() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-{{ $order->payment->statusColor() }}">
                                {{ $order->payment->statusLabel() }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Amount</span>
                            <span class="fw-bold text-success">₹{{ number_format($order->payment->amount, 2) }}</span>
                        </div>
                        @if ($order->payment->transaction_id)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ref</span>
                                <span>{{ $order->payment->transaction_id }}</span>
                            </div>
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('admin.payments.show', $order->payment) }}"
                               class="btn btn-sm btn-outline-success w-100">
                                <i class="bi bi-eye me-1"></i> View Payment Details
                            </a>
                        </div>
                    @else
                        <div class="text-muted text-center py-2">No payment submitted.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

@endsection
