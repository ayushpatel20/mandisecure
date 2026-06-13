@extends('layouts.app')

@section('title', 'My Cart — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'cart'])
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bi bi-cart3 me-2 text-success"></i>My Cart</h4>
            <small class="text-muted">
                {{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }} in cart
            </small>
        </div>
        @if ($items->isNotEmpty())
            <form method="POST" action="{{ route('buyer.cart.clear') }}"
                  onsubmit="return confirm('Clear all items from cart?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Clear Cart
                </button>
            </form>
        @endif
    </div>

    @if ($items->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-cart-x fs-1 d-block mb-3 text-success opacity-50"></i>
                <h5>Your cart is empty</h5>
                <p class="mb-4">Browse our marketplace and add products you like.</p>
                <a href="{{ route('buyer.products.index') }}" class="btn btn-success px-4">
                    <i class="bi bi-grid me-1"></i> Browse Products
                </a>
            </div>
        </div>
    @else

        <div class="row g-4">

            {{-- Cart Items --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">

                        @foreach ($items as $item)
                            @php $product = $item->product; @endphp
                            <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                                {{-- Row 1: image + product details --}}
                                <div class="d-flex gap-3 align-items-start">
                                    <a href="{{ route('buyer.products.show', $product->slug) }}" class="flex-shrink-0">
                                        @if ($product->image)
                                            <img src="{{ Storage::url($product->image) }}"
                                                 alt="{{ $product->product_name }}"
                                                 class="rounded"
                                                 style="width:72px;height:72px;object-fit:cover">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                 style="width:72px;height:72px">
                                                <i class="bi bi-image fs-3 text-muted"></i>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex-grow-1 min-w-0">
                                        <a href="{{ route('buyer.products.show', $product->slug) }}"
                                           class="text-decoration-none text-dark">
                                            <h6 class="fw-bold mb-1 text-truncate">{{ $product->product_name }}</h6>
                                        </a>
                                        <div class="text-muted small d-flex gap-2 flex-wrap mb-1">
                                            <span><i class="bi bi-person me-1"></i>{{ $product->seller->name ?? '—' }}</span>
                                            <span><i class="bi bi-tag me-1"></i>{{ $product->category->name ?? '—' }}</span>
                                        </div>
                                        <div class="small">
                                            <span class="fw-semibold text-success">
                                                ₹{{ number_format($item->effectivePrice(), 2) }}
                                            </span>
                                            <span class="text-muted">/ {{ $product->unit }}</span>
                                            @if ($product->discount_price)
                                                <span class="text-muted text-decoration-line-through ms-1">
                                                    ₹{{ number_format($product->price, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($product->delivery_charges > 0)
                                            <div class="text-muted small">
                                                <i class="bi bi-truck me-1"></i>
                                                ₹{{ number_format($product->delivery_charges, 2) }} delivery
                                            </div>
                                        @else
                                            <div class="text-success small">
                                                <i class="bi bi-truck me-1"></i> Free delivery
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Row 2: quantity control + subtotal (always on its own row for all screen sizes) --}}
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2"
                                     style="border-top:1px dashed #e9ecef">
                                    <div>
                                        <form method="POST"
                                              action="{{ route('buyer.cart.update', $item) }}"
                                              class="d-flex align-items-center gap-2">
                                            @csrf @method('PATCH')
                                            <label class="text-muted small mb-0">Qty</label>
                                            <input type="number"
                                                   name="quantity"
                                                   class="form-control form-control-sm text-center"
                                                   style="width:68px"
                                                   value="{{ $item->quantity }}"
                                                   min="{{ $product->minimum_order_quantity }}"
                                                   max="{{ $product->stock_quantity }}"
                                                   onchange="this.form.submit()">
                                        </form>
                                        <div class="text-muted mt-1" style="font-size:0.7rem">
                                            MOQ: {{ $product->minimum_order_quantity }} · Stock: {{ $product->stock_quantity }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">
                                            ₹{{ number_format($item->subtotal(), 2) }}
                                        </div>
                                        <form method="POST" action="{{ route('buyer.cart.remove', $item) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-link btn-sm text-danger p-0 mt-1"
                                                    onclick="return confirm('Remove this item?')">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('buyer.products.index') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-receipt me-1"></i> Order Summary
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Delivery Charges</span>
                            @if ($deliveryCharges > 0)
                                <span class="fw-semibold">₹{{ number_format($deliveryCharges, 2) }}</span>
                            @else
                                <span class="text-success fw-semibold">Free</span>
                            @endif
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-success">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('buyer.checkout.index') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-credit-card me-1"></i> Proceed to Checkout
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1 text-success"></i>
                                Secure checkout
                            </small>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    @endif

@endsection
