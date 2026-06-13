@extends('layouts.app')

@section('title', 'Admin Dashboard — MandiSecure')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Admin Dashboard</h4>
            <small class="text-muted">Welcome back, {{ Auth::user()->name }}</small>
        </div>
        <span class="badge bg-danger fs-6 px-3 py-2">
            <i class="bi bi-shield-lock me-1"></i> Administrator
        </span>
    </div>

    {{-- User Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-people-fill fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Users</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_users'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-cart-check-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Buyers</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_buyers'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-shop fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Sellers</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_sellers'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-tag-fill fs-4 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Categories</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_categories'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                        <i class="bi bi-box-seam fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Products</div>
                        <div class="fs-3 fw-bold">{{ $stats['total_products'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('admin.products.index', ['status' => 'pending']) }}"
               class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-clock-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Approval</div>
                        <div class="fs-3 fw-bold text-warning">{{ $stats['pending_products'] }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Approved</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['approved_products'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="bi bi-x-circle-fill fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Rejected</div>
                        <div class="fs-3 fw-bold text-danger">{{ $stats['rejected_products'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Products --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-clock-history me-1"></i> Latest Products</span>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>
        <div class="card-body p-0">
            @if ($latestProducts->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    No products yet.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latestProducts as $product)
                                <tr>
                                    <td class="fw-semibold">{{ $product->product_name }}</td>
                                    <td>{{ $product->category->name ?? '—' }}</td>
                                    <td>{{ $product->seller->name ?? 'Admin' }}</td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>
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
                                           class="btn btn-sm btn-outline-secondary">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
