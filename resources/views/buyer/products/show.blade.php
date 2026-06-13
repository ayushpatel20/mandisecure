@extends('layouts.app')

@section('title', $product->product_name . ' — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'products'])
@endsection

@push('styles')
<style>
.product-gallery { position: sticky; top: 1rem; }
.product-main-image {
    width: 100%; height: 340px; object-fit: cover;
    border-radius: 12px; background: #f8f9fa;
}
.product-main-placeholder {
    width: 100%; height: 340px; border-radius: 12px;
    background: #f8f9fa; display: flex; align-items: center; justify-content: center;
}
.price-box { background: linear-gradient(135deg, #f8fff8, #e8f5e9); border-radius: 12px; }
.info-row { display: flex; justify-content: space-between;
            padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0; }
.info-row:last-child { border-bottom: none; }
.related-card { transition: box-shadow 0.2s, transform 0.15s; }
.related-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1)!important; transform: translateY(-2px); }
</style>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item">
                <a href="{{ route('buyer.dashboard') }}" class="text-decoration-none">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('buyer.products.index') }}" class="text-decoration-none">Products</a>
            </li>
            @if ($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('buyer.categories.show', $product->category->slug) }}"
                       class="text-decoration-none">{{ $product->category->name }}</a>
                </li>
            @endif
            <li class="breadcrumb-item active text-truncate" style="max-width:200px">
                {{ $product->product_name }}
            </li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- ── Left: Gallery ── --}}
        <div class="col-lg-5">
            <div class="product-gallery">

                {{-- Main image --}}
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                         alt="{{ $product->product_name }}"
                         class="product-main-image shadow-sm mb-3">
                @else
                    <div class="product-main-placeholder shadow-sm mb-3">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                @endif

                {{-- Thumbnail strip (single image shown as active) --}}
                <div class="d-flex gap-2">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                             alt="thumb"
                             class="rounded border border-success"
                             style="width:64px;height:64px;object-fit:cover;cursor:pointer">
                    @endif
                </div>

            </div>
        </div>

        {{-- ── Right: Product Info ── --}}
        <div class="col-lg-7">

            {{-- Title + category --}}
            <div class="mb-2">
                @if ($product->category)
                    <a href="{{ route('buyer.categories.show', $product->category->slug) }}"
                       class="badge bg-success text-decoration-none mb-2">
                        {{ $product->category->name }}
                    </a>
                @endif
                <h3 class="fw-bold mb-1">{{ $product->product_name }}</h3>
                <div class="text-muted small d-flex gap-3 flex-wrap">
                    @if ($product->seller)
                        <span><i class="bi bi-person-circle me-1"></i>{{ $product->seller->name }}</span>
                    @endif
                    @if ($product->location)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $product->location }}</span>
                    @endif
                    <span><i class="bi bi-clock me-1"></i>Listed {{ $product->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <hr class="my-3">

            {{-- Price box --}}
            <div class="price-box p-3 mb-3">
                @if ($product->discount_price)
                    @php
                        $saving = $product->price - $product->discount_price;
                        $pct    = round(($saving / $product->price) * 100);
                    @endphp
                    <div class="d-flex align-items-baseline gap-2 flex-wrap">
                        <span class="fs-2 fw-bold text-success">
                            ₹{{ number_format($product->discount_price, 2) }}
                        </span>
                        <span class="text-muted text-decoration-line-through">
                            ₹{{ number_format($product->price, 2) }}
                        </span>
                        <span class="badge bg-danger">{{ $pct }}% OFF</span>
                    </div>
                    <div class="text-success small fw-semibold">
                        You save ₹{{ number_format($saving, 2) }}
                    </div>
                @else
                    <span class="fs-2 fw-bold text-success">
                        ₹{{ number_format($product->price, 2) }}
                    </span>
                @endif
                <span class="text-muted small ms-1">per {{ $product->unit }}</span>

                @if ($product->wholesale_price)
                    <div class="mt-1 small text-muted">
                        Wholesale: <strong>₹{{ number_format($product->wholesale_price, 2) }}</strong>/{{ $product->unit }}
                        <span class="badge bg-secondary ms-1">Bulk</span>
                    </div>
                @endif
            </div>

            {{-- Key details table --}}
            <div class="mb-3">
                <div class="info-row">
                    <span class="text-muted small fw-semibold">Minimum Order Qty</span>
                    <span class="fw-semibold">{{ $product->minimum_order_quantity }} {{ $product->unit }}</span>
                </div>
                <div class="info-row">
                    <span class="text-muted small fw-semibold">Available Stock</span>
                    <span class="fw-semibold">
                        {{ $product->stock_quantity }} {{ $product->unit }}
                        @if ($product->stock_quantity <= 10)
                            <span class="badge bg-warning text-dark ms-1">Low Stock</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="text-muted small fw-semibold">Delivery Charges</span>
                    <span class="fw-semibold">
                        @if ($product->delivery_charges > 0)
                            ₹{{ number_format($product->delivery_charges, 2) }}
                        @else
                            <span class="text-success">Free Delivery</span>
                        @endif
                    </span>
                </div>
                @if ($product->expected_delivery_time)
                    <div class="info-row">
                        <span class="text-muted small fw-semibold">Expected Delivery</span>
                        <span class="fw-semibold">
                            <i class="bi bi-truck me-1 text-success"></i>
                            {{ $product->expected_delivery_time }}
                        </span>
                    </div>
                @endif
                @if ($product->location)
                    <div class="info-row">
                        <span class="text-muted small fw-semibold">Ships From</span>
                        <span class="fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-success"></i>
                            {{ $product->location }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- CTA Buttons --}}
            @if ($product->stock_quantity > 0)
                <form method="POST" action="{{ route('buyer.cart.add') }}" class="d-flex gap-2 flex-wrap mb-3 align-items-end">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label class="form-label small fw-semibold mb-1">
                            Quantity <span class="text-muted">(min: {{ $product->minimum_order_quantity }} {{ $product->unit }})</span>
                        </label>
                        <input type="number"
                               name="quantity"
                               class="form-control"
                               style="width:120px"
                               value="{{ $product->minimum_order_quantity }}"
                               min="{{ $product->minimum_order_quantity }}"
                               max="{{ $product->stock_quantity }}"
                               required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                    </button>
                    <a href="{{ route('buyer.cart.index') }}" class="btn btn-outline-success btn-lg px-3">
                        <i class="bi bi-cart3 me-1"></i> View Cart
                        @if ($cartCount > 0)
                            <span class="badge bg-danger ms-1">{{ $cartCount }}</span>
                        @endif
                    </a>
                </form>
            @else
                <div class="mb-3">
                    <button class="btn btn-secondary btn-lg px-5" disabled>
                        <i class="bi bi-x-circle me-1"></i> Out of Stock
                    </button>
                </div>
            @endif

            {{-- Seller Card --}}
            @if ($product->seller)
                <div class="card border-0 bg-light p-3 rounded-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:48px;height:48px;font-size:1.2rem">
                            {{ strtoupper(substr($product->seller->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $product->seller->name }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-shop me-1"></i>Verified Seller
                                @if ($product->location)
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-geo-alt me-1"></i>{{ $product->location }}
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('buyer.products.index', ['q' => $product->seller->name]) }}"
                           class="btn btn-sm btn-outline-success ms-auto">
                            View Products
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Description --}}
    @if ($product->description)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-file-text me-1"></i> Product Description
            </div>
            <div class="card-body">
                <p class="mb-0" style="line-height:1.8">{{ $product->description }}</p>
            </div>
        </div>
    @endif

    {{-- Related Products --}}
    @if ($related->isNotEmpty())
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Related Products</h5>
                @if ($product->category)
                    <a href="{{ route('buyer.categories.show', $product->category->slug) }}"
                       class="btn btn-sm btn-outline-success">
                        More in {{ $product->category->name }}
                    </a>
                @endif
            </div>
            <div class="row g-3">
                @foreach ($related as $rel)
                    @include('buyer.partials.product-card', ['product' => $rel])
                @endforeach
            </div>
        </div>
    @endif

@endsection
