@extends('layouts.app')

@section('title', 'My Orders — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bi bi-bag me-2 text-success"></i>My Orders</h4>
            <small class="text-muted">{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }} placed</small>
        </div>
        <a href="{{ route('buyer.products.index') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-grid me-1"></i> Browse Products
        </a>
    </div>

    @if ($orders->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-bag-x fs-1 d-block mb-3 text-success opacity-50"></i>
                <h5>No orders yet</h5>
                <p class="mb-4">Start shopping and your orders will appear here.</p>
                <a href="{{ route('buyer.products.index') }}" class="btn btn-success px-4">
                    <i class="bi bi-grid me-1"></i> Browse Products
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @foreach ($orders as $order)
                    <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="fw-bold">{{ $order->order_number }}</span>
                                    <span class="badge bg-{{ $order->statusColor() }}">
                                        {{ $order->statusLabel() }}
                                    </span>
                                    @if ($order->payment)
                                        <span class="badge bg-{{ $order->payment->statusColor() }} bg-opacity-75">
                                            <i class="bi bi-credit-card me-1"></i>
                                            {{ $order->payment->statusLabel() }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-50">
                                            <i class="bi bi-credit-card me-1"></i> No Payment
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted small d-flex flex-wrap gap-3">
                                    <span>
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                    </span>
                                    <span>
                                        <i class="bi bi-box me-1"></i>
                                        {{ $order->items_count }} item{{ $order->items_count !== 1 ? 's' : '' }}
                                    </span>
                                    <span>
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $order->district }}, {{ $order->state }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="fw-bold text-success fs-5">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </div>
                                <a href="{{ route('buyer.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-success mt-1">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </a>
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
