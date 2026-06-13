@extends('layouts.app')

@section('title', 'Seller Dashboard — MandiSecure')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Seller Dashboard</h4>
            <small class="text-muted">Welcome back, {{ Auth::user()->name }}</small>
        </div>
        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
            <i class="bi bi-shop me-1"></i> Seller
        </span>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('seller.products.index') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                        <i class="bi bi-box-seam fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">My Products</div>
                        <div class="fs-3 fw-bold text-dark">{{ $stats['total_products'] }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('seller.products.index', ['status' => 'pending']) }}"
               class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-clock-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="fs-3 fw-bold text-warning">{{ $stats['pending_products'] }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('seller.products.index', ['status' => 'approved']) }}"
               class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Approved</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['approved_products'] }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('seller.products.index', ['status' => 'rejected']) }}"
               class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="bi bi-x-circle-fill fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Rejected</div>
                        <div class="fs-3 fw-bold text-danger">{{ $stats['rejected_products'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Rejection notice --}}
    @if ($stats['rejected_products'] > 0)
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                You have <strong>{{ $stats['rejected_products'] }}</strong> rejected product(s).
                <a href="{{ route('seller.products.index', ['status' => 'rejected']) }}" class="alert-link">
                    View them
                </a> and edit to re-submit for approval.
            </div>
        </div>
    @endif

    {{-- Latest products --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-clock-history me-1"></i> My Latest Products</span>
            <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
        </div>
        <div class="card-body p-0">
            @if ($latestProducts->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                    <p class="mb-3">You haven't listed any products yet.</p>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Add Your First Product
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latestProducts as $product)
                                <tr>
                                    <td class="fw-semibold">{{ $product->product_name }}</td>
                                    <td>{{ $product->category->name ?? '—' }}</td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->stock_quantity }} {{ $product->unit }}</td>
                                    <td>
                                        @if ($product->isApproved())
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($product->isPending())
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.products.show', $product) }}"
                                           class="btn btn-sm btn-outline-secondary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-end">
                    <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Products
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
