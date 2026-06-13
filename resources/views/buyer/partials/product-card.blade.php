<div class="col-sm-6 col-xl-4">
    <div class="card h-100 border-0 shadow-sm product-card">

        {{-- Clickable image + title area --}}
        <a href="{{ route('buyer.products.show', $product->slug) }}" class="text-decoration-none">

            {{-- Image --}}
            <div class="position-relative" style="height:180px;overflow:hidden">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                         alt="{{ $product->product_name }}"
                         class="w-100 h-100 card-img-top"
                         style="object-fit:cover">
                @else
                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                @endif

                {{-- Category badge --}}
                <span class="position-absolute top-0 start-0 m-2 badge bg-success bg-opacity-90">
                    {{ $product->category->name ?? '' }}
                </span>

                {{-- Discount badge --}}
                @if ($product->discount_price)
                    @php
                        $pct = round((($product->price - $product->discount_price) / $product->price) * 100);
                    @endphp
                    <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                        {{ $pct }}% OFF
                    </span>
                @endif
            </div>

            <div class="card-body d-flex flex-column p-3">
                <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $product->product_name }}</h6>

                {{-- Seller + Location --}}
                <div class="text-muted small mb-2 d-flex gap-2 flex-wrap">
                    @if ($product->seller)
                        <span><i class="bi bi-person me-1"></i>{{ $product->seller->name }}</span>
                    @endif
                    @if ($product->location)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $product->location }}</span>
                    @endif
                </div>

                {{-- Price --}}
                <div class="mb-2">
                    @if ($product->discount_price)
                        <span class="fs-5 fw-bold text-success">
                            ₹{{ number_format($product->discount_price, 2) }}
                        </span>
                        <span class="text-muted text-decoration-line-through ms-1 small">
                            ₹{{ number_format($product->price, 2) }}
                        </span>
                    @else
                        <span class="fs-5 fw-bold text-success">
                            ₹{{ number_format($product->price, 2) }}
                        </span>
                    @endif
                    <span class="text-muted small">/ {{ $product->unit }}</span>
                </div>

                {{-- Stock + MOQ --}}
                <div class="text-muted small mt-auto d-flex justify-content-between">
                    <span>
                        <i class="bi bi-boxes me-1"></i>
                        {{ $product->stock_quantity }} {{ $product->unit }} available
                    </span>
                    <span>MOQ: {{ $product->minimum_order_quantity }}</span>
                </div>
            </div>
        </a>

        {{-- Add to Cart button — outside the link so it does not trigger navigation --}}
        <div class="card-footer bg-white border-0 px-3 pb-3 pt-0">
            @if ($product->stock_quantity > 0)
                <form method="POST" action="{{ route('buyer.cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="{{ $product->minimum_order_quantity }}">
                    <button type="submit" class="btn btn-outline-success btn-sm w-100">
                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                    </button>
                </form>
            @else
                <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                    <i class="bi bi-x-circle me-1"></i> Out of Stock
                </button>
            @endif
        </div>

        {{-- Delivery footer --}}
        @if ($product->expected_delivery_time)
            <div class="card-footer bg-light border-0 text-muted small py-2 px-3">
                <i class="bi bi-truck me-1"></i> {{ $product->expected_delivery_time }} delivery
            </div>
        @endif

    </div>
</div>
