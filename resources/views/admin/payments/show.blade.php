@extends('layouts.app')

@section('title', 'Payment Details — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'payments'])
@endsection

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.payments.index') }}" class="text-decoration-none">Payments</a>
            </li>
            <li class="breadcrumb-item active">{{ $payment->order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-credit-card me-2 text-success"></i>
                Payment #{{ $payment->id }}
            </h4>
            <div class="text-muted small mt-1">
                For order <strong>{{ $payment->order->order_number }}</strong>
                · {{ $payment->created_at->format('d M Y, h:i A') }}
            </div>
        </div>
        <span class="badge bg-{{ $payment->statusColor() }} fs-6 px-3 py-2">
            {{ $payment->statusLabel() }}
        </span>
    </div>

    <div class="row g-4">

        {{-- Payment Details --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-info-circle me-1 text-success"></i> Payment Information
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted small fw-semibold" style="width:40%">Payment Method</td>
                            <td>
                                <i class="{{ $payment->methodIcon() }} me-1 text-success"></i>
                                <strong>{{ $payment->methodLabel() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">Amount</td>
                            <td class="fw-bold text-success fs-5">
                                ₹{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-semibold">Status</td>
                            <td>
                                <span class="badge bg-{{ $payment->statusColor() }}">
                                    {{ $payment->statusLabel() }}
                                </span>
                            </td>
                        </tr>
                        @if ($payment->transaction_id)
                            <tr>
                                <td class="text-muted small fw-semibold">Transaction ID</td>
                                <td>{{ $payment->transaction_id }}</td>
                            </tr>
                        @endif
                        @if ($payment->payment_date)
                            <tr>
                                <td class="text-muted small fw-semibold">Payment Date</td>
                                <td>{{ $payment->payment_date->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endif
                        @if ($payment->remarks)
                            <tr>
                                <td class="text-muted small fw-semibold">Remarks</td>
                                <td>{{ $payment->remarks }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Screenshot --}}
            @if ($payment->screenshot)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-image me-1 text-success"></i> Payment Screenshot
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ Storage::url($payment->screenshot) }}" target="_blank">
                            <img src="{{ Storage::url($payment->screenshot) }}"
                                 alt="Payment Screenshot"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height:500px">
                        </a>
                        <div class="mt-2">
                            <a href="{{ Storage::url($payment->screenshot) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-arrow-up-right-square me-1"></i> View Full Size
                            </a>
                        </div>
                    </div>
                </div>
            @else
                @if ($payment->payment_method !== 'cod')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        No payment screenshot uploaded.
                    </div>
                @endif
            @endif

            {{-- Admin Actions --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-sliders me-1 text-success"></i> Admin Actions
                </div>
                <div class="card-body">

                    {{-- Quick Approve/Reject (pending only) --}}
                    @if ($payment->isPending())
                        <div class="d-flex gap-2 flex-wrap mb-4">
                            <form method="POST" action="{{ route('admin.payments.approve', $payment) }}"
                                  onsubmit="return confirm('Approve this payment?')">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Approve Payment
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger"
                                    data-bs-toggle="collapse" data-bs-target="#rejectForm">
                                <i class="bi bi-x-circle me-1"></i> Reject Payment
                            </button>
                        </div>

                        <div class="collapse" id="rejectForm">
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}"
                                  class="card border-danger bg-danger bg-opacity-5 p-3 mb-4 rounded-3">
                                @csrf
                                <label class="form-label fw-semibold">Rejection Reason</label>
                                <textarea name="remarks" rows="3" class="form-control mb-2"
                                          placeholder="e.g. Screenshot unclear, amount mismatch, invalid UPI…"></textarea>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Confirm Rejection
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Manual Status Update --}}
                    <form method="POST" action="{{ route('admin.payments.update-status', $payment) }}">
                        @csrf @method('PATCH')
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Update Status</label>
                                <select name="payment_status" class="form-select form-select-sm">
                                    @foreach (\App\Models\Payment::$statuses as $val => $meta)
                                        <option value="{{ $val }}"
                                            {{ $payment->payment_status === $val ? 'selected' : '' }}>
                                            {{ $meta['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">
                                    Remarks <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <input type="text" name="remarks" class="form-control form-control-sm"
                                       value="{{ $payment->remarks }}"
                                       placeholder="Admin note…">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil me-1"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>

        {{-- Order Info + Buyer --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-bag me-1 text-success"></i> Order Details
                </div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order Number</span>
                        <span class="fw-semibold">{{ $payment->order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order Status</span>
                        <span class="badge bg-{{ $payment->order->statusColor() }}">
                            {{ $payment->order->statusLabel() }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₹{{ number_format($payment->order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Delivery</span>
                        <span>₹{{ number_format($payment->order->delivery_charges, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold border-top pt-2">
                        <span>Total</span>
                        <span class="text-success">₹{{ number_format($payment->order->total_amount, 2) }}</span>
                    </div>

                    <hr>

                    <div class="fw-semibold mb-1">{{ $payment->order->items->count() }} Item(s)</div>
                    @foreach ($payment->order->items as $item)
                        <div class="d-flex justify-content-between text-muted mb-1">
                            <span>{{ $item->product_name }}</span>
                            <span>{{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person me-1 text-success"></i> Buyer
                </div>
                <div class="card-body small">
                    <div class="fw-semibold fs-6 mb-1">{{ $payment->order->buyer->name ?? '—' }}</div>
                    <div class="text-muted mb-1">
                        <i class="bi bi-envelope me-1"></i>{{ $payment->order->buyer->email ?? '—' }}
                    </div>
                    <div class="text-muted mb-2">
                        <i class="bi bi-phone me-1"></i>{{ $payment->order->buyer->mobile ?? '—' }}
                    </div>
                    <hr class="my-2">
                    <div>{{ $payment->order->delivery_address }}</div>
                    <div class="text-muted">
                        {{ $payment->order->district }}, {{ $payment->order->state }} — {{ $payment->order->pin_code }}
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="mt-3">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Payments
        </a>
    </div>

@endsection
