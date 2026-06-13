@extends('layouts.app')

@section('title', 'My Products — MandiSecure')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'products'])
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">My Products</h4>
            <small class="text-muted">Manage your product listings</small>
        </div>
        <a href="{{ route('seller.products.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Add Product
        </a>
    </div>

    {{-- Status filter tabs --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('seller.products.index') }}"
           class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All <span class="badge bg-white text-dark ms-1">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('seller.products.index', ['status' => 'pending']) }}"
           class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
            Pending <span class="badge bg-white text-dark ms-1">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('seller.products.index', ['status' => 'approved']) }}"
           class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
            Approved <span class="badge bg-white text-dark ms-1">{{ $counts['approved'] }}</span>
        </a>
        <a href="{{ route('seller.products.index', ['status' => 'rejected']) }}"
           class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            Rejected <span class="badge bg-white text-dark ms-1">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    @if ($counts['rejected'] > 0 && !request('status'))
        <div class="alert alert-warning d-flex gap-2 align-items-center mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                You have <strong>{{ $counts['rejected'] }}</strong> rejected product(s).
                Edit and resubmit them for admin review.
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th width="130">Status</th>
                            <th width="130">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}"
                                             alt="{{ $product->product_name }}"
                                             class="rounded" width="44" height="44"
                                             style="object-fit:cover">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:44px;height:44px">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $product->product_name }}</div>
                                    <small class="text-muted">{{ $product->unit }}</small>
                                </td>
                                <td>{{ $product->category->name ?? '—' }}</td>
                                <td>
                                    <div class="fw-semibold">₹{{ number_format($product->price, 2) }}</div>
                                    @if ($product->discount_price)
                                        <small class="text-success">
                                            ₹{{ number_format($product->discount_price, 2) }} offer
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    @if ($product->isApproved())
                                        <span class="badge bg-success px-2 py-1">
                                            <i class="bi bi-check-circle me-1"></i> Approved
                                        </span>
                                    @elseif ($product->isPending())
                                        <span class="badge bg-warning text-dark px-2 py-1">
                                            <i class="bi bi-clock me-1"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('seller.products.show', $product) }}"
                                       class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('seller.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('seller.products.destroy', $product) }}"
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
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                    @if (request('status'))
                                        No {{ request('status') }} products found.
                                    @else
                                        You haven't added any products yet.
                                        <div class="mt-2">
                                            <a href="{{ route('seller.products.create') }}" class="btn btn-success btn-sm">
                                                Add Your First Product
                                            </a>
                                        </div>
                                    @endif
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
