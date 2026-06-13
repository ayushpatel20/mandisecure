<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['buyer_id' => Auth::id()]);
    }

    public function index()
    {
        $cart  = Cart::where('buyer_id', Auth::id())
                     ->with(['items.product.category', 'items.product.seller'])
                     ->first();

        $items = $cart ? $cart->items->filter(fn ($i) => $i->product !== null) : collect();

        $subtotal         = $items->sum(fn ($i) => $i->subtotal());
        $deliveryCharges  = $items->sum(fn ($i) => $i->product->delivery_charges ?? 0);
        $total            = $subtotal + $deliveryCharges;

        return view('buyer.cart.index', compact('cart', 'items', 'subtotal', 'deliveryCharges', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::approved()->findOrFail($request->product_id);

        // MOQ check
        if ($request->quantity < $product->minimum_order_quantity) {
            return back()->with('error',
                "Minimum order quantity for {$product->product_name} is {$product->minimum_order_quantity} {$product->unit}.");
        }

        // Stock check
        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error',
                "Only {$product->stock_quantity} {$product->unit} of {$product->product_name} available.");
        }

        $cart = $this->getOrCreateCart();

        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($item) {
            $newQty = $item->quantity + $request->quantity;

            if ($newQty > $product->stock_quantity) {
                return back()->with('error',
                    "Cannot add more. Only {$product->stock_quantity} {$product->unit} available (you already have {$item->quantity} in cart).");
            }

            $item->update(['quantity' => $newQty]);
            return back()->with('success', "{$product->product_name} quantity updated in cart.");
        }

        CartItem::create([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
        ]);

        return back()->with('success', "{$product->product_name} added to cart.");
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);

        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = $cartItem->product;

        if ($request->quantity < $product->minimum_order_quantity) {
            return back()->with('error',
                "Minimum order quantity is {$product->minimum_order_quantity} {$product->unit}.");
        }

        if ($request->quantity > $product->stock_quantity) {
            return back()->with('error',
                "Only {$product->stock_quantity} {$product->unit} available.");
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);
        $name = $cartItem->product->product_name ?? 'Item';
        $cartItem->delete();

        return back()->with('success', "{$name} removed from cart.");
    }

    public function clear()
    {
        $cart = Cart::where('buyer_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Cart cleared.');
    }

    private function authorizeCartItem(CartItem $item): void
    {
        if ($item->cart->buyer_id !== Auth::id()) {
            abort(403);
        }
    }
}
