<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'seller'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        $sellers    = User::where('role', 'seller')->orderBy('name')->get();
        $units      = Product::$units;

        return view('admin.products.create', compact('categories', 'sellers', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'            => ['required', 'exists:categories,id'],
            'seller_id'              => ['nullable', 'exists:users,id'],
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
            'status'                 => ['required', 'in:pending,approved,rejected'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($request->product_name);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'seller']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        $sellers    = User::where('role', 'seller')->orderBy('name')->get();
        $units      = Product::$units;

        return view('admin.products.edit', compact('product', 'categories', 'sellers', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'            => ['required', 'exists:categories,id'],
            'seller_id'              => ['nullable', 'exists:users,id'],
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
            'status'                 => ['required', 'in:pending,approved,rejected'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        if ($product->product_name !== $request->product_name) {
            $validated['slug'] = $this->uniqueSlug($request->product_name, $product->id);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->orderItems()->update(['product_id' => null]);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);

        return back()->with('success', "Product \"{$product->product_name}\" has been approved.");
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $product->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "Product \"{$product->product_name}\" has been rejected.");
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
