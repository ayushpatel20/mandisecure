<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('buyer_id', Auth::id())
                       ->withCount('items')
                       ->with('payment')
                       ->latest()
                       ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'items.seller', 'payment', 'statusLogs.changedBy']);

        return view('buyer.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.seller', 'payment']);

        return view('buyer.orders.invoice', compact('order'));
    }

    public function tracking(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'items.seller', 'statusLogs.changedBy']);

        return view('buyer.orders.tracking', compact('order'));
    }
}
