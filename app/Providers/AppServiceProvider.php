<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share cart item count with the panel layout only (admin/buyer/seller share layouts.app).
        // Scoping to 'layouts.app' fires once per page instead of once per @include partial.
        // Blade passes layout variables to all @include children automatically.
        View::composer('layouts.app', function ($view) {
            $cartCount = 0;

            if (Auth::check() && Auth::user()->isBuyer()) {
                $cartCount = CartItem::join('carts', 'carts.id', '=', 'cart_items.cart_id')
                    ->where('carts.buyer_id', Auth::id())
                    ->sum('cart_items.quantity');
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
