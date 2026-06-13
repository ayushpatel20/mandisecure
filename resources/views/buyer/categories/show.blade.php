@extends('layouts.app')

@section('title', $category->name . ' — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'categories'])
@endsection

@push('styles')
<style>
.product-card { transition: box-shadow 0.2s, transform 0.2s; }
.product-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.12)!important; transform: translateY(-3px); }
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
                <a href="{{ route('buyer.categories.index') }}" class="text-decoration-none">Categories</a>
            </li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Category Header --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="row g-0">
            @if ($category->image)
                <div class="col-md-3">
                    <img src="{{ Storage::url($category->image) }}"
                         alt="{{ $category->name }}"
                         class="img-fluid w-100 h-100"
                         style="object-fit:cover;max-height:160px">
                </div>
            @endif
            <div class="{{ $category->image ? 'col-md-9' : 'col-12' }}">
                <div class="card-body p-4 d-flex flex-column justify-content-center h-100">
                    <h4 class="fw-bold mb-1">{{ $category->name }}</h4>
                    @if ($category->description)
                        <p class="text-muted mb-2">{{ $category->description }}</p>
                    @endif
                    <div class="text-muted small">
                        <span class="badge bg-success">{{ $products->total() }} approved products</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('buyer.categories.show', $category->slug) }}"
                  id="catFilterForm">

                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="q" class="form-control"
                               placeholder="Search in {{ $category->name }}…"
                               value="{{ request('q') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="number" name="min_price" class="form-control form-control-sm"
                               placeholder="Min ₹" value="{{ request('min_price') }}" min="0">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="number" name="max_price" class="form-control form-control-sm"
                               placeholder="Max ₹" value="{{ request('max_price') }}" min="0">
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select form-select-sm"
                                onchange="document.getElementById('catFilterForm').submit()">
                            <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected':'' }}>Latest</option>
                            <option value="price_asc"  {{ request('sort') === 'price_asc'           ? 'selected':'' }}>Price ↑</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc'          ? 'selected':'' }}>Price ↓</option>
                            <option value="name_asc"   {{ request('sort') === 'name_asc'            ? 'selected':'' }}>A → Z</option>
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        @if (request()->hasAny(['q','min_price','max_price','sort']))
                            <a href="{{ route('buyer.categories.show', $category->slug) }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Page count + other categories sidebar --}}
    <div class="row g-4">

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <small class="text-muted">
                    Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                    of {{ $products->total() }} products
                </small>
            </div>

            @if ($products->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-search fs-1 d-block mb-3"></i>
                        <p>No products found in <strong>{{ $category->name }}</strong> with these filters.</p>
                        <a href="{{ route('buyer.categories.show', $category->slug) }}"
                           class="btn btn-outline-success btn-sm">Clear Filters</a>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($products as $product)
                        @include('buyer.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                @if ($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Other categories sidebar --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold small">Other Categories</div>
                <div class="card-body p-2">
                    @foreach ($categories as $cat)
                        <a href="{{ route('buyer.categories.show', $cat->slug) }}"
                           class="d-flex align-items-center gap-2 p-2 rounded text-decoration-none
                                  {{ $cat->slug === $category->slug ? 'bg-success text-white' : 'text-dark hover-bg' }}
                                  mb-1">
                            <i class="bi bi-tag {{ $cat->slug === $category->slug ? 'text-white' : 'text-success' }}"></i>
                            <span class="small">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

@endsection
