<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private static array $indianStates = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
        'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu',
        'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry',
    ];

    public function index()
    {
        $cart  = Cart::where('buyer_id', Auth::id())
                     ->with(['items.product.seller'])
                     ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                             ->with('error', 'Your cart is empty.');
        }

        $items = $cart->items->filter(fn ($i) => $i->product !== null);

        if ($items->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                             ->with('error', 'Your cart is empty.');
        }

        $subtotal        = $items->sum(fn ($i) => $i->subtotal());
        $deliveryCharges = $items->sum(fn ($i) => $i->product->delivery_charges ?? 0);
        $total           = $subtotal + $deliveryCharges;

        $user   = Auth::user();
        $states = self::$indianStates;

        return view('buyer.checkout.index',
            compact('items', 'subtotal', 'deliveryCharges', 'total', 'user', 'states'));
    }

    public function place(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:120',
            'mobile'           => 'required|string|max:15|regex:/^[6-9]\d{9}$/',
            'email'            => 'required|email|max:120',
            'delivery_address' => 'required|string|max:500',
            'state'            => 'required|string|max:80',
            'district'         => 'required|string|max:80',
            'pin_code'         => 'required|string|size:6|regex:/^\d{6}$/',
            'notes'            => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('buyer_id', Auth::id())
                    ->with(['items.product'])
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                             ->with('error', 'Your cart is empty.');
        }

        $items = $cart->items->filter(fn ($i) => $i->product !== null);

        // Pre-flight validation for clear UX (catches common cases before DB lock)
        foreach ($items as $item) {
            $product = $item->product;

            if ($item->quantity < $product->minimum_order_quantity) {
                return redirect()->route('buyer.cart.index')
                    ->with('error',
                        "Minimum order quantity for {$product->product_name} is {$product->minimum_order_quantity} {$product->unit}.");
            }

            if ($item->quantity > $product->stock_quantity) {
                return redirect()->route('buyer.cart.index')
                    ->with('error',
                        "Insufficient stock for {$product->product_name}. Only {$product->stock_quantity} {$product->unit} available.");
            }
        }

        $subtotal        = $items->sum(fn ($i) => $i->subtotal());
        $deliveryCharges = $items->sum(fn ($i) => $i->product->delivery_charges ?? 0);
        $total           = $subtotal + $deliveryCharges;

        // Sort by product_id for consistent lock order — prevents deadlocks on concurrent checkouts
        $items = $items->sortBy(fn ($i) => $i->product->id)->values();

        $placedOrder = null;

        try {
            DB::transaction(function () use ($data, $items, $subtotal, $deliveryCharges, $total, $cart, &$placedOrder) {
                $order = Order::create([
                    'order_number'     => 'ORD-' . strtoupper(bin2hex(random_bytes(5))),
                    'buyer_id'         => Auth::id(),
                    'name'             => $data['name'],
                    'mobile'           => $data['mobile'],
                    'email'            => $data['email'],
                    'delivery_address' => $data['delivery_address'],
                    'state'            => $data['state'],
                    'district'         => $data['district'],
                    'pin_code'         => $data['pin_code'],
                    'subtotal'         => $subtotal,
                    'delivery_charges' => $deliveryCharges,
                    'total_amount'     => $total,
                    'status'           => 'pending',
                    'notes'            => $data['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    // Lock the product row to prevent race conditions on concurrent checkouts
                    $product = Product::lockForUpdate()->findOrFail($item->product->id);

                    // Guard: product may have been rejected/removed between cart add and checkout
                    if ($product->status !== 'approved') {
                        throw new \DomainException(
                            "{$product->product_name} is no longer available for purchase."
                        );
                    }

                    if ($item->quantity > $product->stock_quantity) {
                        throw new \DomainException(
                            "Insufficient stock for {$product->product_name}. Only {$product->stock_quantity} {$product->unit} available."
                        );
                    }

                    OrderItem::create([
                        'order_id'         => $order->id,
                        'product_id'       => $product->id,
                        'seller_id'        => $product->seller_id,
                        'product_name'     => $product->product_name,
                        'unit'             => $product->unit,
                        'unit_price'       => $product->discount_price ?? $product->price,
                        'quantity'         => $item->quantity,
                        'delivery_charges' => $product->delivery_charges ?? 0,
                        'subtotal'         => $item->subtotal(),
                    ]);

                    $product->decrement('stock_quantity', $item->quantity);
                }

                $cart->items()->delete();
                $order->logStatus('pending', Auth::id(), 'Order placed by buyer.');
                $placedOrder = $order;
            });
        } catch (\DomainException|\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
                ? 'A product in your cart is no longer available. It has been removed.'
                : $e->getMessage();
            return redirect()->route('buyer.cart.index')->with('error', $message);
        }

        // Send order confirmation emails outside the transaction
        OrderNotificationService::orderPlaced($placedOrder);

        return redirect()->route('buyer.payment.create', $placedOrder)
                         ->with('success', "Order {$placedOrder->order_number} placed! Please complete your payment.");
    }
}
