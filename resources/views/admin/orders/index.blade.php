@extends('layouts.app')

@section('title', 'Orders — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'orders'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h4 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>All Orders</h4>
    </div>

    {{-- Stats --}}
    <div class="row g-2 mb-4">
        @foreach (['total' => ['label'=>'Total','color'=>'dark'], 'pending' => ['label'=>'Pending','color'=>'warning'], 'confirmed' => ['label'=>'Confirmed','color'=>'info'], 'processing' => ['label'=>'Processing','color'=>'primary'], 'shipped' => ['label'=>'Shipped','color'=>'secondary'], 'delivered' => ['label'=>'Delivered','color'=>'success'], 'cancelled' => ['label'=>'Cancelled','color'=>'danger']] as $key => $meta)
        <div class="col-6 col-md-3 col-xl">
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
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Order number, buyer name or email…"
                           value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2">
                    <select name="payment_status" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="">All Payments</option>
                        @foreach (App\Models\Payment::$statuses as $val => $meta)
                            <option value="{{ $val }}" {{ request('payment_status') === $val ? 'selected' : '' }}>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if (request()->hasAny(['q','status','payment_status']))
                        <a href="{{ route('admin.orders.index') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($orders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt fs-1 d-block mb-3 opacity-50"></i>
                    <p>No orders found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order</th>
                                <th>Buyer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Order Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th class="pe-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="fw-semibold text-decoration-none text-success">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold">{{ $order->buyer->name ?? '—' }}</div>
                                        <div class="text-muted" style="font-size:0.72rem">
                                            {{ $order->buyer->mobile ?? '' }}
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $order->items_count }}</td>
                                    <td class="fw-semibold text-success">
                                        ₹{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->statusColor() }}">
                                            {{ $order->statusLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($order->payment)
                                            <span class="badge bg-{{ $order->payment->statusColor() }} bg-opacity-75">
                                                {{ $order->payment->statusLabel() }}
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>
                                    <td class="pe-3">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection
