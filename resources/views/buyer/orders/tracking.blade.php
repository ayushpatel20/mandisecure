@extends('layouts.app')

@section('title', 'Track Order ' . $order->order_number . ' — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

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
            <li class="breadcrumb-item active">Tracking</li>
        </ol>
    </nav>

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-geo-alt me-2 text-success"></i>Order Tracking
            </h4>
            <div class="text-muted small mt-1">{{ $order->order_number }}</div>
        </div>
        <span class="badge bg-{{ $order->statusColor() }} fs-6 px-3 py-2">
            {{ $order->statusLabel() }}
        </span>
    </div>

    {{-- Shipment info card --}}
    @if ($order->hasTracking())
        <div class="card border-0 shadow-sm mb-4"
             style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-left:4px solid #198754 !important">
            <div class="card-body">
                <div class="fw-bold mb-3 text-success">
                    <i class="bi bi-truck me-2"></i>Shipment Details
                </div>
                <div class="row g-3">
                    @if ($order->courier)
                        <div class="col-sm-6 col-md-4">
                            <div class="small text-muted mb-1">Courier / Carrier</div>
                            <div class="fw-semibold fs-6">{{ $order->courier }}</div>
                        </div>
                    @endif
                    @if ($order->tracking_number)
                        <div class="col-sm-6 col-md-4">
                            <div class="small text-muted mb-1">Tracking Number</div>
                            <code class="fw-bold text-success fs-5 d-block">{{ $order->tracking_number }}</code>
                        </div>
                    @endif
                    @if ($order->courier && $order->tracking_number)
                        <div class="col-md-4 d-flex align-items-end">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Use the tracking number on the courier's website for real-time updates.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <i class="bi bi-hourglass-split text-muted" style="font-size:2rem"></i>
                <div class="fw-semibold mt-2">Tracking not available yet</div>
                <p class="text-muted small mt-1 mb-0">
                    @if ($order->status === 'pending' || $order->status === 'confirmed')
                        Your order is being prepared. Tracking details will appear once it ships.
                    @elseif ($order->status === 'processing')
                        Your order is being packed. Tracking details will appear once it ships.
                    @elseif ($order->status === 'delivered')
                        This order has been delivered.
                    @else
                        Tracking details have not been provided for this order.
                    @endif
                </p>
            </div>
        </div>
    @endif

    {{-- Progress timeline --}}
    @include('admin.orders._timeline', ['order' => $order])

    {{-- Status history --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-clock-history me-1 text-success"></i> Delivery Updates
        </div>
        <div class="card-body p-0">
            @forelse ($order->statusLogs->sortByDesc('id') as $log)
                <div class="d-flex gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="pt-1 flex-shrink-0">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center
                                     bg-{{ $log->statusColor() }} text-white"
                              style="width:30px;height:30px;font-size:0.65rem">
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
            @empty
                <div class="p-3 text-center text-muted small">No updates yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Delivery address --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-geo-alt me-1 text-success"></i> Delivering To
        </div>
        <div class="card-body small">
            <div class="fw-semibold">{{ $order->name }}</div>
            <div class="text-muted">{{ $order->mobile }}</div>
            <hr class="my-2">
            <div>{{ $order->delivery_address }}</div>
            <div class="text-muted">{{ $order->district }}, {{ $order->state }} — {{ $order->pin_code }}</div>
        </div>
    </div>

    {{-- Items summary --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-box me-1 text-success"></i> Items ({{ $order->items->count() }})
        </div>
        <div class="card-body p-0">
            @foreach ($order->items as $item)
                <div class="d-flex gap-3 p-3 align-items-center {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div style="width:48px;height:48px;flex-shrink:0">
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
                        <div class="fw-semibold small">{{ $item->product_name }}</div>
                        <div class="text-muted" style="font-size:0.78rem">
                            {{ $item->quantity }} {{ $item->unit }}
                            @if ($item->seller)· by {{ $item->seller->name }}@endif
                        </div>
                    </div>
                    <div class="fw-bold text-success small">
                        ₹{{ number_format($item->subtotal, 2) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Order
        </a>
        <a href="{{ route('buyer.orders.invoice', $order) }}" target="_blank"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Invoice
        </a>
    </div>

@endsection
