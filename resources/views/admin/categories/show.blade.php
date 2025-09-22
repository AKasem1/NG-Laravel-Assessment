@extends('layouts.admin')

@section('title', $category->name)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Categories</a>
    </li>
    <li class="breadcrumb-item active">{{ Str::limit($category->name, 50) }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <div class="d-flex align-items-center mb-2">
                    <div class="card-icon icon-cyan me-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-tag" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h2 class="mb-1" style="color: var(--dark-text);">{{ $category->name }}</h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar me-1"></i>Created {{ $category->created_at->format('M d, Y') }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clock me-1"></i>Updated {{ $category->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>
                    <span class="d-none d-sm-inline">Edit Category</span>
                    <span class="d-sm-none">Edit</span>
                </a>
                @if($category->products->count() == 0)
                    <button type="button" 
                            class="btn btn-outline-danger"
                            onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')">
                        <i class="bi bi-trash me-2"></i>
                        <span class="d-none d-sm-inline">Delete</span>
                        <span class="d-sm-none">Del</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Category Overview -->
    <div class="col-lg-4 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2" style="color: var(--primary-cyan);"></i>
                    Category Details
                </h5>
            </div>
            <div class="card-body">
                <!-- Description -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Description</h6>
                    @if($category->description)
                        <p class="mb-0">{{ $category->description }}</p>
                    @else
                        <p class="text-muted fst-italic mb-0">No description provided</p>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-1 text-primary">{{ $category->products->count() }}</h4>
                            <small class="text-muted">Total Products</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="mb-1 text-success">${{ number_format($category->products->sum('price'), 2) }}</h4>
                            <small class="text-muted">Total Value</small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Category
                    </a>
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-outline-info">
                        <i class="bi bi-plus-lg me-2"></i>Add Product to Category
                    </a>
                    @if($category->products->count() > 0)
                        <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="btn btn-outline-secondary">
                            <i class="bi bi-box me-2"></i>View All Products
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="col-lg-8 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2" style="color: var(--primary-orange);"></i>
                    Category Analytics
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-cyan mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-box" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">{{ $category->products->count() }}</h4>
                            <small class="text-muted">Total Products</small>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-success mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-currency-dollar" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">${{ number_format($category->products->sum('price'), 2) }}</h4>
                            <small class="text-muted">Total Value</small>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-warning mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calculator" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">${{ $category->products->count() > 0 ? number_format($category->products->avg('price'), 2) : '0.00' }}</h4>
                            <small class="text-muted">Avg. Price</small>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-orange mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-check-circle" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">{{ $category->products->where('stock_quantity', '>', 0)->count() }}</h4>
                            <small class="text-muted">In Stock</small>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon" style="background: #dc3545; width: 50px; height: 50px;" class="mx-auto mb-3">
                                <i class="bi bi-exclamation-triangle" style="font-size: 1.2rem; color: white;"></i>
                            </div>
                            <h4 class="mb-1">{{ $category->products->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count() }}</h4>
                            <small class="text-muted">Low Stock</small>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon" style="background: #6c757d; width: 50px; height: 50px;" class="mx-auto mb-3">
                                <i class="bi bi-x-circle" style="font-size: 1.2rem; color: white;"></i>
                            </div>
                            <h4 class="mb-1">{{ $category->products->where('stock_quantity', 0)->count() }}</h4>
                            <small class="text-muted">Out of Stock</small>
                        </div>
                    </div>
                </div>

                <!-- Stock Status Chart -->
                @if($category->products->count() > 0)
                    <div class="mt-4">
                        <h6 class="mb-3">Stock Distribution</h6>
                        <canvas id="stockChart" height="100"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Products in Category -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-box me-2" style="color: var(--primary-cyan);"></i>
                    Products in This Category
                    @if($category->products->count() > 0)
                        <span class="badge bg-light text-dark ms-2">{{ $category->products->count() }} products</span>
                    @endif
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Add Product
                    </a>
                    @if($category->products->count() > 0)
                        <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="btn btn-outline-primary btn-sm">
                            View All Products
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <!-- View Toggle -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <small class="text-muted">Showing {{ $category->products->count() }} products</small>
                        </div>
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

                    <!-- Grid View -->
                    <div id="grid-container" class="row g-4">
                        @foreach($category->products as $product)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="card dashboard-card h-100">
                                    <!-- Product Image -->
                                    <div class="position-relative">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
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
                                                    onclick="confirmProductDelete('{{ $product->id }}', '{{ $product->name }}')">
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
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $product)
                                        <tr>
                                            <td>
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" 
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
                                                            onclick="confirmProductDelete('{{ $product->id }}', '{{ $product->name }}')">
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
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bi bi-box text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-3">No Products in This Category</h4>
                        <p class="text-muted mb-4">Start adding medical products to organize them under "{{ $category->name }}".</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn btn-info">
                                <i class="bi bi-plus-lg me-2"></i>Add First Product
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-box me-2"></i>View All Products
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    @if($category->products->count() > 0)
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2" style="color: var(--primary-orange);"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Recently Added Products</h6>
                            @php $recentProducts = $category->products->sortByDesc('created_at')->take(3); @endphp
                            @if($recentProducts->count() > 0)
                                @foreach($recentProducts as $product)
                                    <div class="d-flex align-items-center mb-3">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 class="rounded me-3" 
                                                 alt="{{ $product->name }}"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $product->name }}</h6>
                                            <small class="text-muted">Added {{ $product->created_at->diffForHumans() }}</small>
                                        </div>
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted fst-italic">No products added recently</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Low Stock Alerts</h6>
                            @php $lowStockProducts = $category->products->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->take(3); @endphp
                            @if($lowStockProducts->count() > 0)
                                @foreach($lowStockProducts as $product)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-exclamation-triangle text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $product->name }}</h6>
                                            <small class="text-warning">Only {{ $product->stock_quantity }} left in stock</small>
                                        </div>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted fst-italic">All products are well stocked</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Category Confirmation Modal -->
@if($category->products->count() == 0)
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete Category
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
@endif

<!-- Delete Product Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete Product
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
                <form id="deleteProductForm" method="POST" class="d-inline">
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

    if (gridView && listView && gridContainer && listContainer) {
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
    }
});

// Delete Category Confirmation
function confirmDelete(categoryId, categoryName) {
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/categories/${categoryId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Delete Product Confirmation
function confirmProductDelete(productId, productName) {
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteProductForm').action = `/admin/products/${productId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
    deleteModal.show();
}

// Stock Distribution Chart
@if($category->products->count() > 0)
document.addEventListener('DOMContentLoaded', function() {
    const stockCtx = document.getElementById('stockChart');
    if (stockCtx) {
        const inStock = {{ $category->products->where('stock_quantity', '>', 10)->count() }};
        const lowStock = {{ $category->products->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count() }};
        const outOfStock = {{ $category->products->where('stock_quantity', 0)->count() }};
        
        new Chart(stockCtx, {
            type: 'doughnut',
            data: {
                labels: ['In Stock', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [inStock, lowStock, outOfStock],
                    backgroundColor: [
                        '#28a745',  // Green for in stock
                        '#ffc107',  // Yellow for low stock  
                        '#dc3545'   // Red for out of stock
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
@endif
</script>
@endpush
