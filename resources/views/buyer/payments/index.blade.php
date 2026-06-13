@extends('layouts.app')

@section('title', 'My Payments — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'payments'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2 text-success"></i>My Payments</h4>
            <small class="text-muted">{{ $payments->total() }} payment record{{ $payments->total() !== 1 ? 's' : '' }}</small>
        </div>
        <a href="{{ route('buyer.orders.index') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-bag me-1"></i> My Orders
        </a>
    </div>

    @if ($payments->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-cash-stack fs-1 d-block mb-3 text-success opacity-50"></i>
                <h5>No payment records yet</h5>
                <p class="mb-4">Your payment history will appear here after you place an order.</p>
                <a href="{{ route('buyer.products.index') }}" class="btn btn-success px-4">
                    <i class="bi bi-grid me-1"></i> Browse Products
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Ref / Notes</th>
                                <th class="pe-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('buyer.orders.show', $payment->order) }}"
                                           class="fw-semibold text-decoration-none text-success">
                                            {{ $payment->order->order_number }}
                                        </a>
                                        <div class="text-muted small">
                                            {{ $payment->order->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="{{ $payment->methodIcon() }} me-1 text-success"></i>
                                        {{ $payment->methodLabel() }}
                                    </td>
                                    <td class="fw-semibold text-success">
                                        ₹{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->statusColor() }}">
                                            {{ $payment->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        @if ($payment->payment_date)
                                            {{ $payment->payment_date->format('d M Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        @if ($payment->transaction_id)
                                            {{ $payment->transaction_id }}
                                        @elseif ($payment->remarks)
                                            {{ Str::limit($payment->remarks, 40) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="pe-3">
                                        @if ($payment->isFailed())
                                            <a href="{{ route('buyer.payment.create', $payment->order) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="bi bi-arrow-repeat me-1"></i> Retry
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($payments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    @endif

@endsection
