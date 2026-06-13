@extends('layouts.app')

@section('title', 'Edit Product — MandiSecure')

@section('sidebar')
    @include('seller.partials.sidebar', ['active' => 'products'])
@endsection

@section('content')
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('seller.products.show', $product) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">Edit Product</h4>
            <small class="text-muted">{{ $product->product_name }}</small>
        </div>
    </div>

    <div class="alert alert-warning d-flex gap-2 align-items-start mb-4">
        <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
        <div>
            <strong>Note:</strong> Saving changes will reset this product to
            <span class="badge bg-warning text-dark">Pending</span> status and it will require
            admin re-approval before being visible to buyers.
        </div>
    </div>

    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- Main column --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Basic Information</div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label for="product_name" class="form-label fw-semibold">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="product_name" name="product_name"
                                   class="form-control @error('product_name') is-invalid @enderror"
                                   value="{{ old('product_name', $product->product_name) }}" required>
                            @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">— Select Category —</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="image" class="form-label fw-semibold">Product Image</label>
                            @if ($product->image)
                                <div class="mb-2 d-flex align-items-center gap-2">
                                    <img src="{{ Storage::url($product->image) }}"
                                         alt="{{ $product->product_name }}"
                                         class="rounded" style="height:70px;object-fit:cover">
                                    <small class="text-muted">Current image. Upload a new one to replace.</small>
                                </div>
                            @endif
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror"
                                   onchange="previewImage(this)">
                            <div class="form-text">JPEG, PNG, WebP. Max 2MB.</div>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <img id="imagePreview" src="#" alt="Preview"
                                 class="mt-2 rounded d-none" style="max-height:130px">
                        </div>

                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Pricing</div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label for="price" class="form-label fw-semibold">
                                    Selling Price (₹) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" id="price" name="price" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $product->price) }}" required>
                                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="wholesale_price" class="form-label fw-semibold">Wholesale (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" id="wholesale_price" name="wholesale_price"
                                           step="0.01" min="0"
                                           class="form-control @error('wholesale_price') is-invalid @enderror"
                                           value="{{ old('wholesale_price', $product->wholesale_price) }}">
                                    @error('wholesale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="discount_price" class="form-label fw-semibold">Discount (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" id="discount_price" name="discount_price"
                                           step="0.01" min="0"
                                           class="form-control @error('discount_price') is-invalid @enderror"
                                           value="{{ old('discount_price', $product->discount_price) }}">
                                    @error('discount_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Stock & Unit</div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label for="stock_quantity" class="form-label fw-semibold">
                                    Stock Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="stock_quantity" name="stock_quantity" min="0"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="unit" class="form-label fw-semibold">
                                    Unit <span class="text-danger">*</span>
                                </label>
                                <select id="unit" name="unit"
                                        class="form-select @error('unit') is-invalid @enderror" required>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit }}"
                                            {{ old('unit', $product->unit) === $unit ? 'selected' : '' }}>
                                            {{ ucfirst($unit) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="minimum_order_quantity" class="form-label fw-semibold">
                                    Min. Order Qty <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="minimum_order_quantity"
                                       name="minimum_order_quantity" min="1"
                                       class="form-control @error('minimum_order_quantity') is-invalid @enderror"
                                       value="{{ old('minimum_order_quantity', $product->minimum_order_quantity) }}" required>
                                @error('minimum_order_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Delivery & Location</div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label for="delivery_charges" class="form-label fw-semibold">
                                    Delivery Charges (₹) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" id="delivery_charges" name="delivery_charges"
                                           step="0.01" min="0"
                                           class="form-control @error('delivery_charges') is-invalid @enderror"
                                           value="{{ old('delivery_charges', $product->delivery_charges) }}" required>
                                    @error('delivery_charges') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="expected_delivery_time" class="form-label fw-semibold">
                                    Expected Delivery
                                </label>
                                <input type="text" id="expected_delivery_time" name="expected_delivery_time"
                                       class="form-control @error('expected_delivery_time') is-invalid @enderror"
                                       value="{{ old('expected_delivery_time', $product->expected_delivery_time) }}"
                                       placeholder="e.g. 1-2 days">
                                @error('expected_delivery_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="location" class="form-label fw-semibold">Location</label>
                                <input type="text" id="location" name="location"
                                       class="form-control @error('location') is-invalid @enderror"
                                       value="{{ old('location', $product->location) }}"
                                       placeholder="e.g. Nashik, Maharashtra">
                                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Side column --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Current Status</div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            @if ($product->isApproved())
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Approved
                                </span>
                            @elseif ($product->isPending())
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="bi bi-clock me-1"></i> Pending Review
                                </span>
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Rejected
                                </span>
                            @endif
                        </div>
                        <p class="text-muted small mb-0">
                            After saving, status will change to
                            <span class="badge bg-warning text-dark">Pending</span>
                            for admin re-review.
                        </p>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-send me-1"></i>
                        {{ $product->isRejected() ? 'Save & Resubmit' : 'Save Changes' }}
                    </button>
                    <a href="{{ route('seller.products.show', $product) }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>

        </div>
    </form>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
