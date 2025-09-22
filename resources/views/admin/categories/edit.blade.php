@extends('layouts.admin')

@section('title', 'Edit Category - ' . $category->name)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Categories</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.categories.show', $category) }}" class="text-decoration-none">{{ Str::limit($category->name, 30) }}</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h2 class="mb-1" style="color: var(--dark-text);">
                        <i class="bi bi-pencil me-2" style="color: var(--primary-cyan);"></i>
                        Edit Category
                    </h2>
                    <p class="text-muted mb-0">Update category information</p>
                </div>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye me-2"></i>View Category
                    </a>
                    @if($category->products->count() == 0)
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm"
                                onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2" style="color: var(--primary-cyan);"></i>
                        Category Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   placeholder="Enter category name..."
                                   required
                                   maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="nameCounter">{{ strlen($category->name) }}</span>/100 characters
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Describe what products belong in this category..."
                                      maxlength="500">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="descCounter">{{ strlen($category->description ?? '') }}</span>/500 characters
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2" style="color: var(--primary-orange);"></i>
                        Category Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-cyan mx-auto mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-box" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">{{ $category->products->count() }}</h4>
                                <small class="text-muted">Products</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-success mx-auto mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-currency-dollar" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">${{ number_format($category->products->sum('price'), 2) }}</h4>
                                <small class="text-muted">Total Value</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-warning mx-auto mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-calculator" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">${{ $category->products->count() > 0 ? number_format($category->products->avg('price'), 2) : '0.00' }}</h4>
                                <small class="text-muted">Avg. Price</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="card-icon icon-orange mx-auto mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-calendar" style="font-size: 1.2rem;"></i>
                                </div>
                                <h4 class="mb-1">{{ $category->created_at->diffInDays() }}</h4>
                                <small class="text-muted">Days Old</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Preview -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-eye me-2" style="color: var(--primary-orange);"></i>
                        Live Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="card-icon icon-cyan me-3" style="width: 50px; height: 50px;">
                                            <i class="bi bi-tag" style="font-size: 1.2rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1" id="previewName">{{ $category->name }}</h5>
                                            <small class="text-muted">Updated {{ $category->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small mb-3" id="previewDescription" 
                                       @if(!$category->description) style="display: none;" @endif>
                                        {{ $category->description ?? 'Category description will appear here' }}
                                    </p>
                                    
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded">
                                                <div class="h5 mb-0 text-primary">{{ $category->products->count() }}</div>
                                                <small class="text-muted">Products</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded">
                                                <div class="h5 mb-0 text-success">${{ number_format($category->products->sum('price'), 0) }}</div>
                                                <small class="text-muted">Total Value</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in Category -->
            @if($category->products->count() > 0)
                <div class="card dashboard-card mb-4">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-box me-2" style="color: var(--primary-cyan);"></i>
                            Products in This Category
                        </h5>
                        <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="btn btn-outline-primary btn-sm">
                            View All Products
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products->take(5) as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
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
                                                    <div>
                                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                                        <small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><strong>${{ number_format($product->price, 2) }}</strong></td>
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
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($category->products->count() > 5)
                            <div class="text-center mt-3">
                                <p class="text-muted mb-2">Showing 5 of {{ $category->products->count() }} products</p>
                                <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" class="btn btn-outline-info">
                                    View All {{ $category->products->count() }} Products
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Category
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-grid me-2"></i>All Categories
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Changes
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-check-lg me-2"></i>Update Category
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($category->products->count() == 0)
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
@endif
@endsection

@push('scripts')
<script>
// Store original values for reset
const originalValues = {
    name: '{{ $category->name }}',
    description: `{{ $category->description ?? '' }}`
};

// Character counters and live preview
function updateCounters() {
    const nameField = document.getElementById('name');
    const descField = document.getElementById('description');
    const nameCounter = document.getElementById('nameCounter');
    const descCounter = document.getElementById('descCounter');
    
    nameField.addEventListener('input', function() {
        nameCounter.textContent = this.value.length;
        updatePreview();
    });
    
    descField.addEventListener('input', function() {
        descCounter.textContent = this.value.length;
        updatePreview();
    });
}

// Live preview
function updatePreview() {
    const name = document.getElementById('name').value.trim();
    const description = document.getElementById('description').value.trim();
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    
    previewName.textContent = name || 'Category Name';
    
    if (description) {
        previewDescription.textContent = description;
        previewDescription.style.display = 'block';
    } else {
        previewDescription.textContent = 'Category description will appear here';
        previewDescription.style.display = 'none';
    }
}

// Reset form to original values
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will discard any unsaved modifications.')) {
        document.getElementById('name').value = originalValues.name;
        document.getElementById('description').value = originalValues.description;
        
        // Update counters and preview
        document.getElementById('nameCounter').textContent = originalValues.name.length;
        document.getElementById('descCounter').textContent = originalValues.description.length;
        updatePreview();
        
        // Remove validation classes
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }
}

// Delete confirmation
function confirmDelete(categoryId, categoryName) {
    document.getElementById('deleteCategoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/categories/${categoryId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const nameField = document.getElementById('name');
    
    if (!nameField.value.trim()) {
        e.preventDefault();
        nameField.classList.add('is-invalid');
        nameField.focus();
        nameField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    
    nameField.classList.remove('is-invalid');
});

// Real-time validation
document.getElementById('name').addEventListener('blur', function() {
    if (!this.value.trim()) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

document.getElementById('name').addEventListener('input', function() {
    if (this.classList.contains('is-invalid') && this.value.trim()) {
        this.classList.remove('is-invalid');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCounters();
    updatePreview();
});
</script>
@endpush
