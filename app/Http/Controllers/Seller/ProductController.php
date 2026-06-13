<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->products()->with('category')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(12)->withQueryString();

        $counts = [
            'all'      => Auth::user()->products()->count(),
            'pending'  => Auth::user()->products()->pending()->count(),
            'approved' => Auth::user()->products()->approved()->count(),
            'rejected' => Auth::user()->products()->rejected()->count(),
        ];

        return view('seller.products.index', compact('products', 'counts'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        $units      = Product::$units;

        return view('seller.products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['seller_id'] = Auth::id();
        $validated['status']    = 'pending';
        $validated['slug']      = $this->uniqueSlug($request->product_name);

        Product::create($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product submitted successfully. It will be visible after admin approval.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('seller.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        $units      = Product::$units;

        return view('seller.products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        if ($product->product_name !== $request->product_name) {
            $validated['slug'] = $this->uniqueSlug($request->product_name, $product->id);
        }

        // Reset to pending so admin re-reviews edited content
        $validated['status'] = 'pending';

        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated. It has been re-submitted for admin approval.');
    }

    public function destroy(Product $product)
    {
        $product->orderItems()->update(['product_id' => null]);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function validateProduct(Request $request, int $excludeId = 0): array
    {
        return $request->validate([
            'category_id'            => ['required', 'exists:categories,id'],
            'product_name'           => ['required', 'string', 'max:200'],
            'description'            => ['nullable', 'string'],
            'image'                  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'price'                  => ['required', 'numeric', 'min:0'],
            'wholesale_price'        => ['nullable', 'numeric', 'min:0'],
            'discount_price'         => ['nullable', 'numeric', 'min:0'],
            'stock_quantity'         => ['required', 'integer', 'min:0'],
            'unit'                   => ['required', 'string', 'in:' . implode(',', Product::$units)],
            'minimum_order_quantity' => ['required', 'integer', 'min:1'],
            'delivery_charges'       => ['required', 'numeric', 'min:0'],
            'expected_delivery_time' => ['nullable', 'string', 'max:100'],
            'location'               => ['nullable', 'string', 'max:200'],
        ]);
    }

    private function uniqueSlug(string $name, int $excludeId = 0): string
    {
        $base  = Str::slug($name);
        $slug  = $base;
        $count = 1;

        while (Product::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
