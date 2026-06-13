<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_buyers'      => User::where('role', 'buyer')->count(),
            'total_sellers'     => User::where('role', 'seller')->count(),
            'total_categories'  => Category::count(),
            'total_products'    => Product::count(),
            'pending_products'  => Product::pending()->count(),
            'approved_products' => Product::approved()->count(),
            'rejected_products' => Product::rejected()->count(),
        ];

        $latestProducts = Product::with(['category', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestProducts'));
    }
}
