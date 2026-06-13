<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::user();

        $stats = [
            'total_products'    => $seller->products()->count(),
            'pending_products'  => $seller->products()->pending()->count(),
            'approved_products' => $seller->products()->approved()->count(),
            'rejected_products' => $seller->products()->rejected()->count(),
        ];

        $latestProducts = $seller->products()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('stats', 'latestProducts'));
    }
}
