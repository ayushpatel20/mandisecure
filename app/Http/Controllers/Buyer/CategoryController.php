<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)
            ->withCount(['products as approved_products_count' => fn ($q) => $q->where('status', 'approved')])
            ->orderBy('name')
            ->get();

        return view('buyer.categories.index', compact('categories'));
    }

    public function show(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $query = Product::approved()
            ->with(['seller'])
            ->where('category_id', $category->id)
            ->latest();

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($sub) use ($term) {
                $sub->where('product_name', 'LIKE', "%{$term}%")
                    ->orWhere('description',   'LIKE', "%{$term}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->reorder('price', 'asc'),
            'price_desc' => $query->reorder('price', 'desc'),
            'name_asc'   => $query->reorder('product_name', 'asc'),
            default      => null,
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();

        return view('buyer.categories.show', compact('category', 'products', 'categories'));
    }
}
