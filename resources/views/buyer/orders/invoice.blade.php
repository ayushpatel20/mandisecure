<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} — MandiSecure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fff; color: #212529; }
        .invoice-wrap { max-width: 800px; margin: 2rem auto; padding: 2rem; }
        .brand-header { border-bottom: 3px solid #198754; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
        .brand-name { font-size: 1.8rem; font-weight: 800; color: #198754; letter-spacing: 1px; }
        .invoice-title { font-size: 1.1rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 2px; }
        .section-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
                         letter-spacing: 0.08em; color: #6c757d; border-bottom: 1px solid #dee2e6;
                         padding-bottom: 0.3rem; margin-bottom: 0.75rem; }
        .items-table th { background: #f8f9fa; font-size: 0.78rem; text-transform: uppercase;
                          letter-spacing: 0.05em; color: #6c757d; }
        .items-table td, .items-table th { padding: 0.6rem 0.75rem; }
        .total-row { background: #f0faf4; font-weight: 700; font-size: 1.1rem; }
        .status-badge { font-size: 0.9rem; padding: 0.4rem 1rem; border-radius: 20px; }
        .footer-note { border-top: 1px solid #dee2e6; padding-top: 1rem; margin-top: 2rem;
                       font-size: 0.78rem; color: #6c757d; text-align: center; }
        .no-print { }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .invoice-wrap { margin: 0; padding: 1rem; max-width: 100%; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body>

<div class="invoice-wrap">

    {{-- Print / Download Button --}}
    <div class="d-flex justify-content-end mb-3 no-print gap-2">
        <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
            ← Back to Order
        </a>
        <button onclick="window.print()" class="btn btn-success btn-sm">
            🖨 Print / Save as PDF
        </button>
    </div>

    {{-- Header --}}
    <div class="brand-header d-flex justify-content-between align-items-start">
        <div>
            <div class="brand-name">MandiSecure</div>
            <div class="text-muted small">Agricultural Marketplace Platform</div>
        </div>
        <div class="text-end">
            <div class="invoice-title">Tax Invoice</div>
            <div class="fw-bold mt-1">{{ $order->order_number }}</div>
            <div class="text-muted small">
                Date: {{ $order->created_at->format('d M Y') }}
            </div>
            @if ($order->payment && $order->payment->payment_date)
                <div class="text-muted small">
                    Paid: {{ $order->payment->payment_date->format('d M Y') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Bill To + Order Status --}}
    <div class="row g-4 mb-4">

        <div class="col-sm-6">
            <div class="section-label">Bill To</div>
            <div class="fw-semibold">{{ $order->name }}</div>
            <div class="text-muted small">{{ $order->mobile }}</div>
            <div class="text-muted small">{{ $order->email }}</div>
        </div>

        <div class="col-sm-6">
            <div class="section-label">Ship To</div>
            <div class="small">{{ $order->delivery_address }}</div>
            <div class="text-muted small">{{ $order->district }}, {{ $order->state }}</div>
            <div class="text-muted small">PIN: {{ $order->pin_code }}</div>
        </div>

    </div>

    {{-- Payment Summary --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6">
            <div class="section-label">Payment</div>
            @if ($order->payment)
                <div class="d-flex gap-2 align-items-center">
                    <span>{{ $order->payment->methodLabel() }}</span>
                    <span class="badge bg-{{ $order->payment->statusColor() }}">
                        {{ $order->payment->statusLabel() }}
                    </span>
                </div>
                @if ($order->payment->transaction_id)
                    <div class="text-muted small">Ref: {{ $order->payment->transaction_id }}</div>
                @endif
            @else
                <span class="text-muted small">Payment pending</span>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="section-label">Order Status</div>
            <span class="badge bg-{{ $order->statusColor() }} status-badge">
                {{ $order->statusLabel() }}
            </span>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="section-label">Items</div>
    <table class="table items-table border mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Seller</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Unit Price</th>
                <th class="text-end">Delivery</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $i => $item)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ $item->product_name }}</div>
                        <div class="text-muted small">{{ $item->unit }}</div>
                    </td>
                    <td class="small text-muted">{{ $item->seller->name ?? '—' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-end">
                        @if ($item->delivery_charges > 0)
                            ₹{{ number_format($item->delivery_charges, 2) }}
                        @else
                            <span class="text-success">Free</span>
                        @endif
                    </td>
                    <td class="text-end fw-semibold">₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-light">
                <td colspan="6" class="text-end fw-semibold">Subtotal</td>
                <td class="text-end fw-semibold">₹{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr class="table-light">
                <td colspan="6" class="text-end fw-semibold">Delivery Charges</td>
                <td class="text-end">
                    @if ($order->delivery_charges > 0)
                        ₹{{ number_format($order->delivery_charges, 2) }}
                    @else
                        <span class="text-success">Free</span>
                    @endif
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="6" class="text-end">Total Amount</td>
                <td class="text-end text-success">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Footer --}}
    <div class="footer-note">
        <div class="mb-1">
            Thank you for shopping with <strong>MandiSecure</strong> — Your trusted agricultural marketplace.
        </div>
        <div>
            This is a computer-generated invoice and does not require a signature.
            For support, visit MandiSecure platform.
        </div>
        <div class="mt-2 text-muted" style="font-size:0.68rem">
            Invoice generated on {{ now()->format('d M Y, h:i A') }}
        </div>
    </div>

</div>

</body>
</html>
