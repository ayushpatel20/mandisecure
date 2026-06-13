<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Order Received</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#1f2937">
@php
  $myItems = $order->items->where('seller_id', $seller->id);
  $myTotal = $myItems->sum('subtotal');
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 16px">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08)">

        {{-- Header --}}
        <tr>
          <td style="background:#1a6b3c;padding:28px 32px;text-align:center">
            <div style="color:#ffffff;font-size:22px;font-weight:700">🛡 MandiSecure</div>
            <div style="color:rgba(255,255,255,0.75);font-size:13px;margin-top:4px">New Order Alert</div>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="padding:32px">

            <p style="margin:0 0 16px;font-size:15px">
              Hi <strong>{{ $seller->name }}</strong>,
            </p>
            <p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.6">
              You have received a new order on MandiSecure. Please review the details and begin processing.
            </p>

            {{-- Order number --}}
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;margin-bottom:28px">
              <tr>
                <td style="padding:16px 20px">
                  <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px">Order</div>
                  <div style="font-size:20px;font-weight:700;color:#1a6b3c">{{ $order->order_number }}</div>
                  <div style="font-size:13px;color:#6b7280;margin-top:4px">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                  </div>
                </td>
                <td align="right" style="padding:16px 20px">
                  <div style="font-size:12px;color:#6b7280">Your revenue</div>
                  <div style="font-size:20px;font-weight:700;color:#1a6b3c">₹{{ number_format($myTotal, 2) }}</div>
                </td>
              </tr>
            </table>

            {{-- Your items --}}
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;color:#6b7280;letter-spacing:0.08em;margin-bottom:12px">
              Your Items in This Order
            </div>
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;margin-bottom:24px">
              @foreach ($myItems as $item)
              <tr style="{{ !$loop->last ? 'border-bottom:1px solid #e5e7eb' : '' }}">
                <td style="padding:12px 16px">
                  <div style="font-weight:600;font-size:14px">{{ $item->product_name }}</div>
                  <div style="font-size:12px;color:#6b7280;margin-top:2px">
                    {{ $item->quantity }} {{ $item->unit }} × ₹{{ number_format($item->unit_price, 2) }}
                  </div>
                </td>
                <td align="right" style="padding:12px 16px;font-weight:700;color:#1a6b3c;white-space:nowrap">
                  ₹{{ number_format($item->subtotal, 2) }}
                </td>
              </tr>
              @endforeach
            </table>

            {{-- Ship to --}}
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;color:#6b7280;letter-spacing:0.08em;margin-bottom:12px">
              Ship To
            </div>
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f9fafb;border-radius:8px;margin-bottom:28px">
              <tr>
                <td style="padding:16px">
                  <div style="font-weight:600;font-size:14px">{{ $order->name }}</div>
                  <div style="font-size:13px;color:#6b7280;margin-top:4px">{{ $order->delivery_address }}</div>
                  <div style="font-size:13px;color:#6b7280">{{ $order->district }}, {{ $order->state }} — {{ $order->pin_code }}</div>
                  <div style="font-size:13px;color:#6b7280">📞 {{ $order->mobile }}</div>
                </td>
              </tr>
            </table>

            {{-- CTA --}}
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center">
                  <a href="{{ route('seller.orders.show', $order) }}"
                     style="display:inline-block;background:#1a6b3c;color:#fff;font-weight:700;font-size:14px;padding:12px 32px;border-radius:8px;text-decoration:none">
                    View Order in Dashboard
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
