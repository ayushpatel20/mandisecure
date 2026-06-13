<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::approved()
            ->with(['category', 'seller'])
            ->latest();

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($sub) use ($term) {
                $sub->where('product_name', 'LIKE', "%{$term}%")
                    ->orWhere('description',   'LIKE', "%{$term}%")
                    ->orWhere('location',       'LIKE', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->reorder('price', 'asc'),
            'price_desc' => $query->reorder('price', 'desc'),
            'name_asc'   => $query->reorder('product_name', 'asc'),
            default      => null,
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $locations  = Product::approved()
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->limit(200)
            ->pluck('location');

        $priceRange = [
            'min' => Product::approved()->min('price') ?? 0,
            'max' => Product::approved()->max('price') ?? 10000,
        ];

        return view('buyer.products.index',
            compact('products', 'categories', 'locations', 'priceRange'));
    }

    public function show(string $slug)
    {
        $product = Product::approved()
            ->with(['category', 'seller'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::approved()
            ->with(['category', 'seller'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('buyer.products.show', compact('product', 'related'));
    }
}
