@extends('layouts.app')

@section('title', 'Buyer Dashboard — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'dashboard'])
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Welcome, {{ Auth::user()->name }}</h4>
            <small class="text-muted">Discover fresh produce from verified sellers</small>
        </div>
        <span class="badge bg-success fs-6 px-3 py-2">
            <i class="bi bi-cart me-1"></i> Buyer
        </span>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <a href="{{ route('buyer.products.index') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-box-seam fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Available Products</div>
                        <div class="fs-3 fw-bold text-dark">{{ $stats['available_products'] }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <a href="{{ route('buyer.categories.index') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-tag-fill fs-4 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Categories</div>
                        <div class="fs-3 fw-bold text-dark">{{ $stats['total_categories'] }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-shop fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Verified Sellers</div>
                        <div class="fs-3 fw-bold text-dark">{{ $stats['total_sellers'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Shop by Category --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-tag me-1"></i> Shop by Category</span>
            <a href="{{ route('buyer.categories.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse ($categories as $cat)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('buyer.categories.show', $cat->slug) }}"
                           class="text-decoration-none">
                            <div class="card border h-100 text-center p-3 category-card">
                                @if ($cat->image)
                                    <img src="{{ Storage::url($cat->image) }}"
                                         alt="{{ $cat->name }}"
                                         class="rounded-circle mx-auto mb-2"
                                         style="width:64px;height:64px;object-fit:cover">
                                @else
                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center
                                                justify-content-center mx-auto mb-2"
                                         style="width:64px;height:64px">
                                        <i class="bi bi-tag fs-3 text-success"></i>
                                    </div>
                                @endif
                                <div class="fw-semibold text-dark small">{{ $cat->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem">
                                    {{ $cat->approved_products_count }} products
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-muted text-center py-3">No categories available.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Featured Products --}}
    @if ($featuredProducts->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="bi bi-star-fill text-warning me-1"></i> Featured Products
                </span>
                <a href="{{ route('buyer.products.index') }}" class="btn btn-sm btn-outline-warning">
                    Browse All
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($featuredProducts as $product)
                        @include('buyer.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Latest Arrivals --}}
    @if ($latestProducts->isNotEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="bi bi-clock-history me-1"></i> Latest Arrivals
                </span>
                <a href="{{ route('buyer.products.index') }}" class="btn btn-sm btn-outline-success">
                    View All
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($latestProducts as $product)
                        @include('buyer.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
.category-card { transition: box-shadow 0.2s, transform 0.2s; }
.category-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.12) !important; transform: translateY(-2px); }
</style>
@endpush
