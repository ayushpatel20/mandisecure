@extends('layouts.app')

@section('title', 'Payment Status — MandiSecure Seller')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'payments'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2 text-success"></i>Payment Status</h4>
            <small class="text-muted">Payment status for orders containing your products</small>
        </div>
    </div>

    @if ($orders->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-cash-stack fs-1 d-block mb-3 text-success opacity-50"></i>
                <h5>No orders yet</h5>
                <p>Once buyers purchase your products, payment status will appear here.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @foreach ($orders as $order)
                    @php $payment = $order->payment; @endphp
                    <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="row g-3 align-items-start">

                            {{-- Order & Buyer --}}
                            <div class="col-md-3">
                                <div class="fw-bold text-success small">{{ $order->order_number }}</div>
                                <div class="text-muted" style="font-size:0.75rem">
                                    {{ $order->created_at->format('d M Y') }}
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-person me-1"></i>
                                    {{ $order->buyer->name ?? '—' }}
                                </div>
                            </div>

                            {{-- Items (seller's only) --}}
                            <div class="col-md-4">
                                @foreach ($order->items as $item)
                                    <div class="small">
                                        <span class="fw-semibold">{{ $item->product_name }}</span>
                                        <span class="text-muted">
                                            — {{ $item->quantity }} {{ $item->unit }}
                                            × ₹{{ number_format($item->unit_price, 2) }}
                                        </span>
                                    </div>
                                @endforeach
                                <div class="text-muted small mt-1">
                                    Your share:
                                    <strong class="text-success">
                                        ₹{{ number_format($order->items->sum('subtotal'), 2) }}
                                    </strong>
                                </div>
                            </div>

                            {{-- Order Status --}}
                            <div class="col-md-2 text-center">
                                <div class="text-muted small mb-1">Order</div>
                                <span class="badge bg-{{ $order->statusColor() }}">
                                    {{ $order->statusLabel() }}
                                </span>
                            </div>

                            {{-- Payment Status --}}
                            <div class="col-md-3 text-center">
                                <div class="text-muted small mb-1">Payment</div>
                                @if ($payment)
                                    <span class="badge bg-{{ $payment->statusColor() }} fs-6 px-2">
                                        {{ $payment->statusLabel() }}
                                    </span>
                                    <div class="text-muted mt-1" style="font-size:0.72rem">
                                        <i class="{{ $payment->methodIcon() }} me-1"></i>
                                        {{ $payment->methodLabel() }}
                                    </div>
                                    @if ($payment->payment_date)
                                        <div class="text-muted" style="font-size:0.72rem">
                                            {{ $payment->payment_date->format('d M Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="badge bg-secondary bg-opacity-50">
                                        No Payment
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    @endif

@endsection
