<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share cart item count with all views so the navbar badge always works
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check() && Auth::user()->isBuyer()) {
                $cart = Cart::where('buyer_id', Auth::id())->first();
                if ($cart) {
                    $cartCount = $cart->items()->sum('quantity');
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
