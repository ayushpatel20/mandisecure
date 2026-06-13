<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'payment'])->withCount('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->whereHas('payment', fn ($q) => $q->where('payment_status', $request->payment_status));
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->q . '%')
                  ->orWhereHas('buyer', fn ($b) =>
                      $b->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('email', 'like', '%' . $request->q . '%')
                  );
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total'      => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load([
            'buyer',
            'items.product.category',
            'items.seller',
            'payment',
            'statusLogs.changedBy',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'          => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'remarks'         => 'nullable|string|max:500',
            'tracking_number' => 'nullable|string|max:100',
            'courier'         => 'nullable|string|max:100',
        ]);

        $previousStatus = $order->status;

        $updateData = ['status' => $request->status];

        // Capture tracking when shipping
        if ($request->status === 'shipped') {
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['courier']         = $request->courier;
        }

        $adminId = Auth::id();

        DB::transaction(function () use ($order, $updateData, $request, $previousStatus, $adminId) {
            $order->update($updateData);
            $order->logStatus($request->status, $adminId, $request->remarks);

            // Restore stock when admin cancels a non-delivered order
            if ($request->status === 'cancelled'
                && $previousStatus !== 'cancelled'
                && $previousStatus !== 'delivered') {
                foreach ($order->items()->with('product')->get() as $item) {
                    $item->product?->increment('stock_quantity', $item->quantity);
                }
            }
        });

        OrderNotificationService::statusChanged($order, $previousStatus);

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status) . '.');
    }
}
