@extends('layouts.app')

@section('title', 'Categories — MandiSecure')

@section('sidebar')
    @include('buyer.partials.sidebar', ['active' => 'categories'])
@endsection

@push('styles')
<style>
.cat-card { transition: box-shadow 0.2s, transform 0.2s; cursor: pointer; border-radius: 16px; }
.cat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.12)!important; transform: translateY(-4px); }
.cat-img-wrap {
    height: 140px; overflow: hidden; border-radius: 12px 12px 0 0;
    background: #f0f9f0; display: flex; align-items: center; justify-content: center;
}
</style>
@endpush

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Browse Categories</h4>
            <small class="text-muted">Find products by category</small>
        </div>
        <a href="{{ route('buyer.products.index') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-grid me-1"></i> All Products
        </a>
    </div>

    <div class="row g-3">
        @forelse ($categories as $category)
            <div class="col-sm-6 col-md-4 col-xl-3">
                <a href="{{ route('buyer.categories.show', $category->slug) }}"
                   class="text-decoration-none">
                    <div class="card border-0 shadow-sm cat-card h-100">

                        {{-- Category Image --}}
                        <div class="cat-img-wrap">
                            @if ($category->image)
                                <img src="{{ Storage::url($category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="w-100 h-100"
                                     style="object-fit:cover">
                            @else
                                <i class="bi bi-tag fs-1 text-success"></i>
                            @endif
                        </div>

                        <div class="card-body text-center py-3">
                            <h6 class="fw-bold text-dark mb-1">{{ $category->name }}</h6>
                            @if ($category->description)
                                <p class="text-muted small mb-2" style="
                                    overflow:hidden;display:-webkit-box;
                                    -webkit-line-clamp:2;-webkit-box-orient:vertical">
                                    {{ $category->description }}
                                </p>
                            @endif
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                                {{ $category->approved_products_count }}
                                product{{ $category->approved_products_count !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        <div class="card-footer bg-success text-white text-center small fw-semibold py-2 rounded-bottom">
                            <i class="bi bi-arrow-right me-1"></i> Browse {{ $category->name }}
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-tag fs-1 d-block mb-2"></i>
                        No categories available yet.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

@endsection
