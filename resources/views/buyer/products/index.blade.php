@extends('layouts.app')

@section('title', 'Browse Products — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'products'])
@endsection

@push('styles')
<style>
.product-card { transition: box-shadow 0.2s, transform 0.2s; }
.product-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.12) !important; transform: translateY(-3px); }
.filter-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase;
                letter-spacing: 0.04em; color: #6c757d; }
</style>
@endpush

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Browse Products</h4>
            <small class="text-muted">
                {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found
                @if (request('q'))
                    for <strong>"{{ request('q') }}"</strong>
                @endif
            </small>
        </div>
        @if (request()->hasAny(['q','category','min_price','max_price','location','sort']))
            <a href="{{ route('buyer.products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Clear Filters
            </a>
        @endif
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('buyer.products.index') }}" id="filterForm">

                {{-- Search row --}}
                <div class="input-group mb-3">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="q" id="searchInput"
                           class="form-control form-control-lg border-start-0 ps-0"
                           placeholder="Search products, descriptions, locations…"
                           value="{{ request('q') }}"
                           autocomplete="off">
                    <button class="btn btn-success px-4" type="submit">Search</button>
                </div>

                {{-- Filter row --}}
                <div class="row g-2 align-items-end">

                    {{-- Category --}}
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="filter-label mb-1">Category</div>
                        <select name="category" class="form-select form-select-sm"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}"
                                    {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Min Price --}}
                    <div class="col-6 col-md-2 col-lg-2">
                        <div class="filter-label mb-1">Min Price (₹)</div>
                        <input type="number" name="min_price" class="form-control form-control-sm"
                               placeholder="{{ $priceRange['min'] }}"
                               value="{{ request('min_price') }}" min="0" step="1">
                    </div>

                    {{-- Max Price --}}
                    <div class="col-6 col-md-2 col-lg-2">
                        <div class="filter-label mb-1">Max Price (₹)</div>
                        <input type="number" name="max_price" class="form-control form-control-sm"
                               placeholder="{{ $priceRange['max'] }}"
                               value="{{ request('max_price') }}" min="0" step="1">
                    </div>

                    {{-- Location --}}
                    <div class="col-6 col-md-3 col-lg-3">
                        <div class="filter-label mb-1">Location</div>
                        <input type="text" name="location" class="form-control form-control-sm"
                               placeholder="City, State…"
                               value="{{ request('location') }}"
                               list="locationList">
                        <datalist id="locationList">
                            @foreach ($locations as $loc)
                                <option value="{{ $loc }}">
                            @endforeach
                        </datalist>
                    </div>

                    {{-- Sort --}}
                    <div class="col-6 col-md-2 col-lg-2">
                        <div class="filter-label mb-1">Sort By</div>
                        <select name="sort" class="form-select form-select-sm"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected':'' }}>Latest</option>
                            <option value="price_asc"  {{ request('sort') === 'price_asc'           ? 'selected':'' }}>Price: Low → High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc'          ? 'selected':'' }}>Price: High → Low</option>
                            <option value="name_asc"   {{ request('sort') === 'name_asc'            ? 'selected':'' }}>Name A→Z</option>
                        </select>
                    </div>

                    {{-- Apply --}}
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="bi bi-funnel me-1"></i> Apply
                        </button>
                    </div>

                </div>

                {{-- Active filter tags --}}
                @php
                    $activeFilters = array_filter([
                        'Category' => request('category'),
                        'Min ₹'   => request('min_price'),
                        'Max ₹'   => request('max_price'),
                        'Location' => request('location'),
                    ]);
                @endphp
                @if (!empty($activeFilters))
                    <div class="mt-2 d-flex flex-wrap gap-1">
                        @foreach ($activeFilters as $label => $val)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">
                                {{ $label }}: {{ $val }}
                            </span>
                        @endforeach
                    </div>
                @endif

            </form>
        </div>
    </div>

    {{-- Product Grid --}}
    @if ($products->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                <h5>No products found</h5>
                <p class="mb-3">Try adjusting your search or filters.</p>
                <a href="{{ route('buyer.products.index') }}" class="btn btn-outline-success">
                    Clear All Filters
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($products as $product)
                @include('buyer.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    @endif

@endsection
