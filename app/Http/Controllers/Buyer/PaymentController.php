<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        $this->authorizeOrder($order);

        // Already has a successful payment — show order instead
        if ($order->payment && $order->payment->isPaid()) {
            return redirect()->route('buyer.orders.show', $order)
                             ->with('success', 'This order is already paid.');
        }

        $settingRows = Setting::whereIn('key', [
            'payment_upi_id', 'payment_bank_account_holder',
            'payment_bank_account_number', 'payment_bank_ifsc', 'payment_bank_name',
        ])->pluck('value', 'key');

        $settings = [
            'upi_id'               => $settingRows->get('payment_upi_id')               ?? 'mandisecure@ybl',
            'bank_account_holder'  => $settingRows->get('payment_bank_account_holder')  ?? 'MandiSecure Pvt Ltd',
            'bank_account_number'  => $settingRows->get('payment_bank_account_number')  ?? '—',
            'bank_ifsc'            => $settingRows->get('payment_bank_ifsc')            ?? '—',
            'bank_name'            => $settingRows->get('payment_bank_name')            ?? '—',
        ];

        $failedPayment = $order->payment && $order->payment->isFailed()
                         ? $order->payment
                         : null;

        return view('buyer.payments.create', compact('order', 'settings', 'failedPayment'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment && $order->payment->isPaid()) {
            return redirect()->route('buyer.orders.show', $order)
                             ->with('error', 'This order is already paid.');
        }

        $request->validate([
            'payment_method' => 'required|in:upi,bank_transfer,cod',
            'transaction_id' => 'nullable|string|max:100',
            'screenshot'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $method = $request->payment_method;

        // UPI and bank transfer require a screenshot
        if (in_array($method, ['upi', 'bank_transfer'])) {
            $request->validate([
                'screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            ]);
        }

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')
                                      ->store('payments', 'public');
        }

        // Delete old failed payment screenshot if re-submitting
        if ($order->payment && $order->payment->isFailed()) {
            if ($order->payment->screenshot) {
                Storage::disk('public')->delete($order->payment->screenshot);
            }
            $order->payment->delete();
        }

        $payment = Payment::create([
            'order_id'       => $order->id,
            'payment_method' => $method,
            'transaction_id' => $request->transaction_id,
            'amount'         => $order->total_amount,
            'payment_status' => $method === 'cod' ? 'pending' : 'pending',
            'payment_date'   => $method === 'cod' ? now() : null,
            'screenshot'     => $screenshotPath,
        ]);

        // COD: confirm the order immediately
        if ($method === 'cod') {
            $order->update(['status' => 'confirmed']);
            $order->logStatus('confirmed', Auth::id(), 'Cash on Delivery');
            return redirect()->route('buyer.orders.show', $order)
                             ->with('success', 'Order confirmed! Pay cash on delivery.');
        }

        return redirect()->route('buyer.orders.show', $order)
                         ->with('success', 'Payment details submitted. We will verify and confirm your order shortly.');
    }

    public function index()
    {
        $payments = Payment::whereHas('order', fn ($q) => $q->where('buyer_id', Auth::id()))
                           ->with('order')
                           ->latest()
                           ->paginate(15);

        return view('buyer.payments.index', compact('payments'));
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }
    }
}
