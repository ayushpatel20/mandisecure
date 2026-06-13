@extends('layouts.app')

@section('title', $product->product_name . ' — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'products'])
@endsection

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">Product Details</h4>
            <small class="text-muted">Review and approve / reject</small>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left: image + approval panel --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center p-4">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->product_name }}"
                             class="img-fluid rounded mb-3"
                             style="max-height:220px;object-fit:cover">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width:160px;height:160px">
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-1">{{ $product->product_name }}</h5>
                    <p class="text-muted mb-3">{{ $product->category->name ?? '—' }}</p>

                    @if ($product->status === 'approved')
                        <span class="badge bg-success fs-6 px-3 py-2 mb-3 d-block">
                            <i class="bi bi-check-circle me-1"></i> Approved
                        </span>
                    @elseif ($product->status === 'pending')
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-3 d-block">
                            <i class="bi bi-clock me-1"></i> Pending Review
                        </span>
                    @else
                        <span class="badge bg-danger fs-6 px-3 py-2 mb-3 d-block">
                            <i class="bi bi-x-circle me-1"></i> Rejected
                        </span>
                    @endif

                    {{-- Approval actions --}}
                    @if ($product->isPending())
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                                @csrf
                                <button class="btn btn-success w-100">
                                    <i class="bi bi-check-lg me-1"></i> Approve Product
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                                @csrf
                                <textarea name="rejection_reason"
                                          class="form-control form-control-sm mb-2"
                                          rows="2"
                                          placeholder="Reason for rejection (optional)"
                                          maxlength="500">{{ old('rejection_reason') }}</textarea>
                                <button class="btn btn-outline-danger w-100">
                                    <i class="bi bi-x-lg me-1"></i> Reject Product
                                </button>
                            </form>
                        </div>
                    @elseif ($product->isApproved())
                        <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                            @csrf
                            <textarea name="rejection_reason"
                                      class="form-control form-control-sm mb-2"
                                      rows="2"
                                      placeholder="Reason for revoking approval (optional)"
                                      maxlength="500">{{ old('rejection_reason') }}</textarea>
                            <button class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-lg me-1"></i> Revoke Approval
                            </button>
                        </form>
                    @else
                        @if ($product->rejection_reason)
                            <div class="alert alert-danger small p-2 mb-2 text-start">
                                <strong>Rejection reason:</strong><br>
                                {{ $product->rejection_reason }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                            @csrf
                            <button class="btn btn-outline-success w-100">
                                <i class="bi bi-check-lg me-1"></i> Approve Product
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="btn btn-outline-primary flex-fill">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                      onsubmit="return confirm('Delete this product?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: product details --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Pricing & Stock</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="text-muted small">Selling Price</div>
                            <div class="fs-5 fw-bold text-success">₹{{ number_format($product->price, 2) }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Wholesale Price</div>
                            <div class="fs-5 fw-bold">
                                {{ $product->wholesale_price ? '₹' . number_format($product->wholesale_price, 2) : '—' }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Discount Price</div>
                            <div class="fs-5 fw-bold text-danger">
                                {{ $product->discount_price ? '₹' . number_format($product->discount_price, 2) : '—' }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Stock Quantity</div>
                            <div class="fs-5 fw-bold">{{ $product->stock_quantity }} {{ $product->unit }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Min Order Qty</div>
                            <div class="fs-5 fw-bold">{{ $product->minimum_order_quantity }} {{ $product->unit }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Delivery Charges</div>
                            <div class="fs-5 fw-bold">₹{{ number_format($product->delivery_charges, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Seller & Logistics</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="text-muted small">Seller</div>
                            <div class="fw-semibold">
                                {{ $product->seller->name ?? '<span class="text-muted">Admin</span>' }}
                                @if ($product->seller)
                                    <div class="text-muted small">{{ $product->seller->email }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Location</div>
                            <div class="fw-semibold">{{ $product->location ?: '—' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Expected Delivery</div>
                            <div class="fw-semibold">{{ $product->expected_delivery_time ?: '—' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Listed On</div>
                            <div class="fw-semibold">{{ $product->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($product->description)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Description</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $product->description }}</p>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
