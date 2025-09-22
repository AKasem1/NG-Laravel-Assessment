@extends('layouts.admin')

@section('title', 'Categories Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h2 class="mb-1" style="color: var(--dark-text);">
                    <i class="bi bi-tags me-2" style="color: var(--primary-cyan);"></i>
                    Categories Management
                </h2>
                <p class="text-muted mb-0">Organize your medical products by categories</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-box me-2"></i>
                    <span class="d-none d-sm-inline">View Products</span>
                    <span class="d-sm-none">Products</span>
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-info">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span class="d-none d-sm-inline">Add New Category</span>
                    <span class="d-sm-none">Add</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-3">
                    <div class="col-lg-6 col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search categories...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <select name="sort" class="form-select">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Most Products</option>
                            <option value="products_asc" {{ request('sort') == 'products_asc' ? 'selected' : '' }}>Least Products</option>
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">
                                <i class="bi bi-funnel me-1"></i>
                                <span class="d-none d-sm-inline">Filter</span>
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12 per page</option>
                            <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24 per page</option>
                            <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48 per page</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-grid me-2" style="color: var(--primary-cyan);"></i>
                    Categories
                    @if(isset($categories))
                        <span class="badge bg-light text-dark ms-2">{{ $categories->total() }} total</span>
                    @endif
                </h5>
            </div>
            
            <div class="card-body">
                @if(isset($categories) && $categories->count() > 0)
                    <div class="row g-4">
                        @foreach($categories as $category)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                <div class="card dashboard-card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <!-- Category Header -->
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="card-icon icon-cyan me-3" style="width: 50px; height: 50px;">
                                                <i class="bi bi-tag" style="font-size: 1.2rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">{{ $category->name }}</h5>
                                                <small class="text-muted">
                                                    Created {{ $category->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <!-- Description -->
                                        @if($category->description)
                                            <p class="text-muted small mb-3 flex-grow-1">
                                                {{ Str::limit($category->description, 100) }}
                                            </p>
                                        @else
                                            <p class="text-muted small mb-3 flex-grow-1 fst-italic">
                                                No description provided
                                            </p>
                                        @endif
                                        
                                        <!-- Stats -->
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="h5 mb-0 text-primary">{{ $category->products_count ?? 0 }}</div>
                                                    <small class="text-muted">Products</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="h5 mb-0 text-success">
                                                        ${{ number_format($category->products->sum('price') ?? 0, 0) }}
                                                    </div>
                                                    <small class="text-muted">Total Value</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('admin.categories.show', $category) }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" 
                                               class="btn btn-outline-secondary btn-sm"
                                               title="View Products">
                                                <i class="bi bi-box"></i>
                                            </a>
                                            @if($category->products_count == 0)
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-outline-secondary btn-sm"
                                                        title="Cannot delete - has products"
                                                        disabled>
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $categories->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bi bi-tags text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-3">No Categories Found</h4>
                        <p class="text-muted mb-4">
                            @if(request()->has('search'))
                                No categories match your search criteria. Try different keywords or clear the search.
                            @else
                                Start organizing your medical products by creating categories like "Medications", "Medical Devices", "Supplements", etc.
                            @endif
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            @if(request()->has('search'))
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Search
                                </a>
                            @endif
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-info">
                                <i class="bi bi-plus-lg me-2"></i>Create First Category
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    @if(isset($categories) && $categories->count() > 0)
        <div class="col-12 mt-4">
            <div class="card dashboard-card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2" style="color: var(--primary-orange);"></i>
                        Category Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-cyan mx-auto mb-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-tags" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">{{ $categories->total() }}</h4>
                                <small class="text-muted">Total Categories</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-orange mx-auto mb-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-box" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">{{ $categories->sum('products_count') }}</h4>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-success mx-auto mb-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-graph-up" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">{{ number_format($categories->avg('products_count') ?? 0, 1) }}</h4>
                                <small class="text-muted">Avg Products/Category</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-warning mx-auto mb-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">{{ $categories->where('products_count', 0)->count() }}</h4>
                                <small class="text-muted">Empty Categories</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category:</p>
                <p><strong id="deleteCategoryName"></strong></p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    This action cannot be undone. The category will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Delete Confirmation
function confirmDelete(categoryId, categoryName) {
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/categories/${categoryId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Auto-submit search form on enter
document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        this.form.submit();
    }
});
</script>
@endpush
