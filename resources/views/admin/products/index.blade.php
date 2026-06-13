@extends('layouts.app')

@section('title', 'Products — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'products'])
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Products</h4>
            <small class="text-muted">Manage and approve seller products</small>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Add Product
        </a>
    </div>

    {{-- Status summary badges --}}
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <a href="{{ route('admin.products.index') }}"
           class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All ({{ $productCounts['all'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}"
           class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
            Pending ({{ $productCounts['pending'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'approved']) }}"
           class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
            Approved ({{ $productCounts['approved'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'rejected']) }}"
           class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            Rejected ({{ $productCounts['rejected'] }})
        </a>
    </div>

    {{-- Category filter --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="input-group" style="max-width:300px">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-secondary" type="submit">Filter</button>
            @if (request('category_id'))
                <a href="{{ route('admin.products.index', ['status' => request('status')]) }}"
                   class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th width="60">Image</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th width="110">Status</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="text-muted small">{{ $product->id }}</td>
                                <td>
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}"
                                             alt="{{ $product->product_name }}"
                                             class="rounded" width="44" height="44"
                                             style="object-fit:cover">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:44px;height:44px">
                                            <i class="bi bi-box-seam text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $product->product_name }}</div>
                                    <small class="text-muted">{{ $product->unit }}</small>
                                </td>
                                <td>{{ $product->category->name ?? '—' }}</td>
                                <td>
                                    @if ($product->seller)
                                        {{ $product->seller->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">₹{{ number_format($product->price, 2) }}</div>
                                    @if ($product->discount_price)
                                        <small class="text-success">₹{{ number_format($product->discount_price, 2) }}</small>
                                    @endif
                                </td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    @if ($product->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($product->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if ($product->isPending())
                                        <form method="POST" action="{{ route('admin.products.approve', $product) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.products.reject', $product) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($products->hasPages())
            <div class="card-footer bg-white">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
