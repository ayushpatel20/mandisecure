@extends('layouts.app')

@section('title', 'Payments — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'payments'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h4 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2 text-success"></i>Payments</h4>
        <a href="{{ route('admin.settings.payment') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-gear me-1"></i> Payment Settings
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-4 fw-bold text-dark">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-4 fw-bold text-warning">{{ $stats['pending'] }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-4 fw-bold text-success">{{ $stats['paid'] }}</div>
                <div class="text-muted small">Paid</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-4 fw-bold text-danger">{{ $stats['failed'] }}</div>
                <div class="text-muted small">Failed</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Order number or buyer name…"
                           value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach (\App\Models\Payment::$statuses as $val => $meta)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="method" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="">All Methods</option>
                        @foreach (\App\Models\Payment::$methods as $val => $meta)
                            <option value="{{ $val }}" {{ request('method') === $val ? 'selected' : '' }}>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if (request()->hasAny(['q','status','method']))
                        <a href="{{ route('admin.payments.index') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($payments->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cash-stack fs-1 d-block mb-3 opacity-50"></i>
                    <p>No payments found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order</th>
                                <th>Buyer</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                           class="fw-semibold text-decoration-none text-success">
                                            {{ $payment->order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold">{{ $payment->order->buyer->name ?? '—' }}</div>
                                        <div class="text-muted" style="font-size:0.75rem">
                                            {{ $payment->order->buyer->mobile ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="{{ $payment->methodIcon() }} me-1 text-success"></i>
                                        <span class="small">{{ $payment->methodLabel() }}</span>
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
                                            {{ $payment->created_at->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="pe-3">
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="{{ route('admin.payments.show', $payment) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if ($payment->isPending())
                                                <form method="POST"
                                                      action="{{ route('admin.payments.approve', $payment) }}"
                                                      onsubmit="return confirm('Approve this payment?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $payment->id }}">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Reject Modal --}}
                                @if ($payment->isPending())
                                    <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST"
                                                      action="{{ route('admin.payments.reject', $payment) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-muted small mb-3">
                                                            Rejecting payment for order
                                                            <strong>{{ $payment->order->order_number }}</strong>.
                                                        </p>
                                                        <label class="form-label fw-semibold">
                                                            Reason <span class="text-muted fw-normal">(optional)</span>
                                                        </label>
                                                        <textarea name="remarks" rows="3" class="form-control"
                                                                  placeholder="e.g. Invalid screenshot, amount mismatch…"></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            Confirm Rejection
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($payments->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $payments->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection
