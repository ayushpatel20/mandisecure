<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orderIds = OrderItem::where('seller_id', Auth::id())
                             ->distinct()
                             ->pluck('order_id');

        $query = Order::whereIn('id', $orderIds)
                      ->with(['buyer', 'payment'])
                      ->withCount(['items as my_items_count' => fn ($q) => $q->where('seller_id', Auth::id())])
                      ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('order_number', 'like', '%' . $request->q . '%');
        }

        $orders = $query->paginate(20)->withQueryString();

        $statusCounts = DB::table('orders')
            ->whereIn('id', $orderIds)
            ->selectRaw("COUNT(*) as total, SUM(status = 'pending') as pending, SUM(status = 'confirmed') as confirmed, SUM(status = 'processing') as processing, SUM(status = 'shipped') as shipped")
            ->first();

        $stats = [
            'total'      => $statusCounts->total      ?? 0,
            'pending'    => $statusCounts->pending     ?? 0,
            'confirmed'  => $statusCounts->confirmed   ?? 0,
            'processing' => $statusCounts->processing  ?? 0,
            'shipped'    => $statusCounts->shipped      ?? 0,
        ];

        return view('seller.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $sellerItems = $order->items()->where('seller_id', Auth::id())->with('product')->get();

        if ($sellerItems->isEmpty()) {
            abort(403, 'This order does not contain your products.');
        }

        $order->load(['buyer', 'payment', 'statusLogs.changedBy']);

        return view('seller.orders.show', compact('order', 'sellerItems'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $hasItems = $order->items()->where('seller_id', Auth::id())->exists();
        if (!$hasItems) {
            abort(403);
        }

        abort_if(
            in_array($order->status, ['delivered', 'cancelled']),
            422,
            'This order can no longer be updated.'
        );

        $request->validate([
            'status'          => 'required|in:confirmed,processing,shipped',
            'remarks'         => 'nullable|string|max:300',
            'tracking_number' => 'nullable|string|max:100',
            'courier'         => 'nullable|string|max:100',
        ]);

        $previousStatus = $order->status;

        $updateData = ['status' => $request->status];

        if ($request->status === 'shipped') {
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['courier']         = $request->courier;
        }

        $sellerId = Auth::id();

        DB::transaction(function () use ($order, $updateData, $request, $sellerId) {
            $order->update($updateData);
            $order->logStatus($request->status, $sellerId, $request->remarks);
        });

        OrderNotificationService::statusChanged($order, $previousStatus);

        return back()->with('success', 'Order marked as ' . ucfirst($request->status) . '.');
    }
}
