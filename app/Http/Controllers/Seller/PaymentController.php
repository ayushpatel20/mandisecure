<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        // Find all distinct order IDs that contain this seller's products
        $orderIds = OrderItem::where('seller_id', Auth::id())
                             ->distinct()
                             ->pluck('order_id');

        $orders = Order::whereIn('id', $orderIds)
                       ->with([
                           'payment',
                           'buyer:id,name,mobile',
                           'items' => fn ($q) => $q->where('seller_id', Auth::id()),
                       ])
                       ->latest()
                       ->paginate(20);

        return view('seller.payments.index', compact('orders'));
    }
}
