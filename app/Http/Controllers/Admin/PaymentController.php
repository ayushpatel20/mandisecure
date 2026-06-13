<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.buyer'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('q')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->q . '%')
                  ->orWhereHas('buyer', fn ($b) => $b->where('name', 'like', '%' . $request->q . '%'));
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        $stats = [
            'total'   => Payment::count(),
            'pending' => Payment::where('payment_status', 'pending')->count(),
            'paid'    => Payment::where('payment_status', 'paid')->count(),
            'failed'  => Payment::where('payment_status', 'failed')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load('order.items', 'order.buyer');

        return view('admin.payments.show', compact('payment'));
    }

    public function approve(Payment $payment)
    {
        $payment->update([
            'payment_status' => 'paid',
            'payment_date'   => now(),
        ]);

        $payment->order->update(['status' => 'confirmed']);
        $payment->order->logStatus('confirmed', Auth::id(), 'Payment approved by admin.');

        return back()->with('success', "Payment #{$payment->id} approved. Order confirmed.");
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['remarks' => 'nullable|string|max:500']);

        $payment->update([
            'payment_status' => 'failed',
            'remarks'        => $request->remarks,
        ]);

        return back()->with('success', "Payment #{$payment->id} rejected.");
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'remarks'        => 'nullable|string|max:500',
        ]);

        $data = ['payment_status' => $request->payment_status];

        if ($request->remarks !== null) {
            $data['remarks'] = $request->remarks;
        }

        if ($request->payment_status === 'paid' && !$payment->payment_date) {
            $data['payment_date'] = now();
        }

        $payment->update($data);

        if ($request->payment_status === 'paid') {
            $payment->order->update(['status' => 'confirmed']);
            $payment->order->logStatus('confirmed', Auth::id(), 'Payment marked paid by admin.');
        }

        return back()->with('success', 'Payment status updated.');
    }
}
