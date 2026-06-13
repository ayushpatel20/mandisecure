<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'available_products' => Product::approved()->count(),
            'total_categories'   => Category::where('status', true)->count(),
            'total_sellers'      => User::where('role', 'seller')->where('status', 'active')->count(),
        ];

        // Featured = approved products with a discount price (special offer)
        $featuredProducts = Product::approved()
            ->whereNotNull('discount_price')
            ->with(['category', 'seller'])
            ->latest()
            ->take(6)
            ->get();

        // Fall back to latest approved if no discounted products exist
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::approved()
                ->with(['category', 'seller'])
                ->latest()
                ->take(6)
                ->get();
        }

        $latestProducts = Product::approved()
            ->with(['category', 'seller'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('status', true)
            ->withCount(['products as approved_products_count' => fn ($q) => $q->where('status', 'approved')])
            ->orderBy('name')
            ->get();

        return view('buyer.dashboard',
            compact('stats', 'featuredProducts', 'latestProducts', 'categories'));
    }
}
