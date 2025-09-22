@extends('layouts.admin')

@section('title', 'Products Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h2 class="mb-1" style="color: var(--dark-text);">
                    <i class="bi bi-box me-2" style="color: var(--primary-orange);"></i>
                    Products Management
                </h2>
                <p class="text-muted mb-0">Manage your medical products, stock levels, and pricing</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info">
                    <i class="bi bi-tags me-2"></i>
                    <span class="d-none d-sm-inline">Manage Categories</span>
                    <span class="d-sm-none">Categories</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span class="d-none d-sm-inline">Add New Product</span>
                    <span class="d-sm-none">Add</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search products...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <select name="sort" class="form-select">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price Low-High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
                            <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock Low-High</option>
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">
                                <i class="bi bi-funnel me-1"></i>
                                <span class="d-none d-sm-inline">Filter</span>
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid/Table -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-grid me-2" style="color: var(--primary-orange);"></i>
                    Products
                    @if(isset($products))
                        <span class="badge bg-light text-dark ms-2">{{ $products->total() }} total</span>
                    @endif
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="view-mode" id="grid-view" checked>
                    <label class="btn btn-outline-secondary" for="grid-view">
                        <i class="bi bi-grid"></i>
                    </label>
                    <input type="radio" class="btn-check" name="view-mode" id="list-view">
                    <label class="btn btn-outline-secondary" for="list-view">
                        <i class="bi bi-list"></i>
                    </label>
                </div>
            </div>
            
            <div class="card-body">
                @if(isset($products) && $products->count() > 0)
                    <!-- Grid View -->
                    <div id="grid-container" class="row g-4">
                        @foreach($products as $product)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                <div class="card dashboard-card h-100">
                                    <!-- Product Image -->
                                    <div class="position-relative">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $product->name }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                                 style="height: 200px;">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Stock Status Badge -->
                                        <div class="position-absolute top-0 end-0 m-2">
                                            @if($product->stock_quantity <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($product->stock_quantity <= 10)
                                                <span class="badge bg-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-success">In Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-2">
                                            <span class="badge bg-light text-dark mb-2">
                                                {{ $product->category->name ?? 'No Category' }}
                                            </span>
                                        </div>
                                        
                                        <h6 class="card-title mb-2">{{ Str::limit($product->name, 40) }}</h6>
                                        
                                        <p class="card-text text-muted small mb-3 flex-grow-1">
                                            {{ Str::limit($product->description, 80) }}
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <div class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</div>
                                                <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- List View (Initially Hidden) -->
                    <div id="list-container" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">Image</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                @if($product->image)
                                                    <img src="{{ $product->image }}" 
                                                         class="rounded" 
                                                         alt="{{ $product->name }}"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $product->category->name ?? 'No Category' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>${{ number_format($product->price, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge {{ $product->stock_quantity <= 0 ? 'bg-danger' : ($product->stock_quantity <= 10 ? 'bg-warning' : 'bg-success') }}">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($product->stock_quantity <= 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($product->stock_quantity <= 10)
                                                    <span class="badge bg-warning">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success">In Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.products.show', $product) }}" 
                                                       class="btn btn-outline-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger"
                                                            title="Delete"
                                                            onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-3">No Products Found</h4>
                        <p class="text-muted mb-4">
                            @if(request()->has('search') || request()->has('category') || request()->has('status'))
                                Try adjusting your filters or search criteria.
                            @else
                                Start by adding your first medical product to the system.
                            @endif
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            @if(request()->hasAny(['search', 'category', 'status']))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                </a>
                            @endif
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Add First Product
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
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
                <p>Are you sure you want to delete the product:</p>
                <p><strong id="deleteProductName"></strong></p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    This action cannot be undone. The product will be permanently removed from the system.
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
                        <i class="bi bi-trash me-2"></i>Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// View Mode Toggle
document.addEventListener('DOMContentLoaded', function() {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const gridContainer = document.getElementById('grid-container');
    const listContainer = document.getElementById('list-container');

    gridView.addEventListener('change', function() {
        if (this.checked) {
            gridContainer.classList.remove('d-none');
            listContainer.classList.add('d-none');
        }
    });

    listView.addEventListener('change', function() {
        if (this.checked) {
            listContainer.classList.remove('d-none');
            gridContainer.classList.add('d-none');
        }
    });
});

// Delete Confirmation
function confirmDelete(productId, productName) {
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
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
