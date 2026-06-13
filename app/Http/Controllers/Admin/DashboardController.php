<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userStats = DB::table('users')
            ->selectRaw("COUNT(*) as total, SUM(role = 'buyer') as buyers, SUM(role = 'seller') as sellers")
            ->first();

        $productStats = DB::table('products')
            ->selectRaw("COUNT(*) as total, SUM(status = 'pending') as pending, SUM(status = 'approved') as approved, SUM(status = 'rejected') as rejected")
            ->first();

        $stats = [
            'total_users'       => $userStats->total,
            'total_buyers'      => $userStats->buyers,
            'total_sellers'     => $userStats->sellers,
            'total_categories'  => Category::count(),
            'total_products'    => $productStats->total,
            'pending_products'  => $productStats->pending,
            'approved_products' => $productStats->approved,
            'rejected_products' => $productStats->rejected,
        ];

        $latestProducts = Product::with(['category', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestProducts'));
    }
}
