@extends('layouts.app')

@section('title', 'Received Orders — MandiSecure Seller')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Received Orders</h4>
            <small class="text-muted">Orders containing your products</small>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-2 mb-4">
        @foreach (['total' => ['label'=>'Total','color'=>'dark'], 'pending' => ['label'=>'Pending','color'=>'warning'], 'confirmed' => ['label'=>'Confirmed','color'=>'info'], 'processing' => ['label'=>'Processing','color'=>'primary'], 'shipped' => ['label'=>'Shipped','color'=>'secondary']] as $key => $meta)
        <div class="col-6 col-md">
            <div class="card border-0 shadow-sm text-center py-2">
                <div class="fs-5 fw-bold text-{{ $meta['color'] }}">{{ $stats[$key] }}</div>
                <div class="text-muted" style="font-size:0.72rem">{{ $meta['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('seller.orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Order number…" value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach (App\Models\Order::$statuses as $val => $meta)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if (request()->hasAny(['q','status']))
                        <a href="{{ route('seller.orders.index') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Orders --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($orders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt fs-1 d-block mb-3 opacity-50"></i>
                    <p>No orders received yet.</p>
                </div>
            @else
                @foreach ($orders as $order)
                    <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <span class="fw-bold text-success">{{ $order->order_number }}</span>
                                    <span class="badge bg-{{ $order->statusColor() }}">
                                        {{ $order->statusLabel() }}
                                    </span>
                                    @if ($order->payment)
                                        <span class="badge bg-{{ $order->payment->statusColor() }} bg-opacity-75">
                                            <i class="bi bi-credit-card me-1"></i>{{ $order->payment->statusLabel() }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted small d-flex gap-3 flex-wrap">
                                    <span><i class="bi bi-calendar me-1"></i>{{ $order->created_at->format('d M Y') }}</span>
                                    <span><i class="bi bi-person me-1"></i>{{ $order->buyer->name ?? '—' }}</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>{{ $order->district }}, {{ $order->state }}</span>
                                </div>
                            </div>
                            <div class="text-end d-flex gap-2 align-items-center">
                                <div class="text-muted small">{{ $order->my_items_count }} item(s)</div>
                                <a href="{{ route('seller.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection
