@extends('layouts.app')

@section('title', $product->product_name . ' — MandiSecure')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'products'])
@endsection

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">Product Details</h4>
            <small class="text-muted">{{ $product->product_name }}</small>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left: image + status panel --}}
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
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-1">{{ $product->product_name }}</h5>
                    <p class="text-muted mb-3 small">{{ $product->category->name ?? '—' }}</p>

                    {{-- Status card --}}
                    @if ($product->isApproved())
                        <div class="alert alert-success text-start mb-0">
                            <div class="fw-semibold mb-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Product Approved
                            </div>
                            <small>Your product is live and visible to buyers.</small>
                        </div>
                    @elseif ($product->isPending())
                        <div class="alert alert-warning text-start mb-0">
                            <div class="fw-semibold mb-1">
                                <i class="bi bi-clock-fill me-1"></i> Pending Review
                            </div>
                            <small>
                                Your product has been submitted and is waiting for admin approval.
                                This usually takes 24–48 hours.
                            </small>
                        </div>
                    @else
                        <div class="alert alert-danger text-start mb-0">
                            <div class="fw-semibold mb-1">
                                <i class="bi bi-x-circle-fill me-1"></i> Product Rejected
                            </div>
                            <small>
                                Your product was not approved. Please review the listing,
                                make corrections, and re-submit.
                            </small>
                            @if ($product->rejection_reason)
                                <hr class="my-2">
                                <div class="fw-semibold" style="font-size:0.78rem">Admin feedback:</div>
                                <div style="font-size:0.78rem">{{ $product->rejection_reason }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('seller.products.edit', $product) }}"
                   class="btn btn-success flex-fill">
                    <i class="bi bi-pencil me-1"></i>
                    {{ $product->isRejected() ? 'Edit & Resubmit' : 'Edit Product' }}
                </a>
                <form method="POST"
                      action="{{ route('seller.products.destroy', $product) }}"
                      onsubmit="return confirm('Delete this product permanently?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger" title="Delete">
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
                            <div class="fs-5 fw-bold text-success">
                                ₹{{ number_format($product->price, 2) }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Wholesale Price</div>
                            <div class="fs-5 fw-bold">
                                {{ $product->wholesale_price
                                    ? '₹' . number_format($product->wholesale_price, 2)
                                    : '—' }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Discount Price</div>
                            <div class="fs-5 fw-bold text-danger">
                                {{ $product->discount_price
                                    ? '₹' . number_format($product->discount_price, 2)
                                    : '—' }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Stock Quantity</div>
                            <div class="fs-5 fw-bold">
                                {{ $product->stock_quantity }} {{ $product->unit }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Min. Order Qty</div>
                            <div class="fs-5 fw-bold">
                                {{ $product->minimum_order_quantity }} {{ $product->unit }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Delivery Charges</div>
                            <div class="fs-5 fw-bold">
                                ₹{{ number_format($product->delivery_charges, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Logistics</div>
                <div class="card-body">
                    <div class="row g-3">
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
                        <div class="col-sm-6">
                            <div class="text-muted small">Last Updated</div>
                            <div class="fw-semibold">{{ $product->updated_at->diffForHumans() }}</div>
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
