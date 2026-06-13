@extends('layouts.app')

@section('title', 'Categories — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'categories'])
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Categories</h4>
            <small class="text-muted">Manage product categories</small>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th width="80">Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th width="100">Status</th>
                            <th width="160">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td class="text-muted small">{{ $category->id }}</td>
                                <td>
                                    @if ($category->image)
                                        <img src="{{ Storage::url($category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="rounded" width="48" height="48"
                                             style="object-fit:cover">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                             style="width:48px;height:48px">
                                            <i class="bi bi-tag text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td><code class="text-muted small">{{ $category->slug }}</code></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $category->products()->count() }}
                                    </span>
                                </td>
                                <td>
                                    @if ($category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.categories.destroy', $category) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer bg-white">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
