<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Update</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#1f2937">
@php
  $statusColors = [
    'processing' => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1d4ed8', 'badge' => '#1d4ed8'],
    'shipped'    => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#15803d', 'badge' => '#1a6b3c'],
    'delivered'  => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#15803d', 'badge' => '#1a6b3c'],
    'cancelled'  => ['bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#b91c1c', 'badge' => '#dc2626'],
  ];
  $sc = $statusColors[$order->status] ?? ['bg' => '#f9fafb', 'border' => '#e5e7eb', 'text' => '#374151', 'badge' => '#374151'];

  $statusMessages = [
    'processing' => 'Great news! Your order is now being processed.',
    'shipped'    => 'Your order is on its way!',
    'delivered'  => 'Your order has been delivered. We hope you enjoy your purchase!',
    'cancelled'  => 'Unfortunately, your order has been cancelled.',
  ];
  $message = $statusMessages[$order->status] ?? 'Your order status has been updated.';
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 16px">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08)">

        {{-- Header --}}
        <tr>
          <td style="background:#1a6b3c;padding:28px 32px;text-align:center">
            <div style="color:#ffffff;font-size:22px;font-weight:700">🛡 MandiSecure</div>
            <div style="color:rgba(255,255,255,0.75);font-size:13px;margin-top:4px">Order Update</div>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="padding:32px">

            <p style="margin:0 0 20px;font-size:15px">Hi <strong>{{ $order->name }}</strong>,</p>

            {{-- Status card --}}
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:{{ $sc['bg'] }};border:1px solid {{ $sc['border'] }};border-radius:8px;margin-bottom:28px">
              <tr>
                <td style="padding:20px 24px">
                  <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#6b7280;margin-bottom:6px">
                    Order Status Updated
                  </div>
                  <div style="font-size:22px;font-weight:700;color:{{ $sc['text'] }}">
                    {{ $order->statusLabel() }}
                  </div>
                  <p style="margin:12px 0 0;font-size:14px;color:#374151;line-height:1.6">
                    {{ $message }}
                  </p>
                </td>
              </tr>
            </table>

            {{-- Tracking info (when shipped or delivered) --}}
            @if (in_array($order->status, ['shipped', 'delivered']) && ($order->tracking_number || $order->courier))
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;margin-bottom:28px">
              <tr>
                <td style="padding:16px 20px">
                  <div style="font-size:13px;font-weight:700;text-transform:uppercase;color:#6b7280;letter-spacing:0.08em;margin-bottom:10px">
                    Tracking Information
                  </div>
                  @if ($order->courier)
                  <div style="font-size:13px;color:#374151;margin-bottom:6px">
                    <strong>Courier:</strong> {{ $order->courier }}
                  </div>
                  @endif
                  @if ($order->tracking_number)
                  <div style="font-size:13px;color:#374151">
                    <strong>Tracking Number:</strong>
                    <span style="font-family:monospace;background:#dcfce7;padding:2px 8px;border-radius:4px;font-size:14px;font-weight:700;color:#166534">
                      {{ $order->tracking_number }}
                    </span>
                  </div>
                  @endif
                </td>
              </tr>
            </table>
            @endif

            {{-- Order ref --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;border-radius:8px;margin-bottom:28px">
              <tr>
                <td style="padding:14px 18px">
                  <div style="font-size:12px;color:#6b7280">Order Number</div>
                  <div style="font-size:16px;font-weight:700;color:#111827;margin-top:2px">
                    {{ $order->order_number }}
                  </div>
                  <div style="font-size:12px;color:#9ca3af;margin-top:2px">
                    Placed {{ $order->created_at->format('d M Y') }}
                    · Total ₹{{ number_format($order->total_amount, 2) }}
                  </div>
                </td>
              </tr>
            </table>

            {{-- CTA --}}
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center">
                  <a href="{{ route('buyer.orders.show', $order) }}"
                     style="display:inline-block;background:#1a6b3c;color:#fff;font-weight:700;font-size:14px;padding:12px 32px;border-radius:8px;text-decoration:none">
                    View Order Details
                  </a>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f9fafb;padding:20px 32px;text-align:center;border-top:1px solid #e5e7eb">
            <p style="margin:0;font-size:12px;color:#9ca3af">
              © {{ date('Y') }} Mandi Secure Private Limited<br>
              <a href="mailto:Headoffice@mandisecure.com" style="color:#6b7280;text-decoration:none">Headoffice@mandisecure.com</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
