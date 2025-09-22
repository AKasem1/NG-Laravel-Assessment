@extends('layouts.admin')

@section('title', 'Edit Product - ' . $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Products</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">{{ Str::limit($product->name, 30) }}</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h2 class="mb-1" style="color: var(--dark-text);">
                        <i class="bi bi-pencil me-2" style="color: var(--primary-orange);"></i>
                        Edit Product
                    </h2>
                    <p class="text-muted mb-0">Update product information and inventory</p>
                </div>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye me-2"></i>View Product
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger btn-sm"
                            onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2" style="color: var(--primary-orange);"></i>
                        Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   placeholder="Enter product name..."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" 
                                        name="category_id" 
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('admin.categories.create') }}" 
                                   class="btn btn-outline-info" 
                                   title="Add New Category"
                                   target="_blank">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                            </div>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp">
                            <div class="form-text">
                                Supported formats: JPEG, PNG, JPG, WebP. Max size: 2MB
                                <br><small class="text-muted">Leave empty to keep current image</small>
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter detailed product description..."
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Image Display -->
            @if($product->image)
                <div class="card dashboard-card mb-4" id="currentImageCard">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-image me-2" style="color: var(--primary-cyan);"></i>
                            Current Image
                        </h5>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCurrentImage()">
                            <i class="bi bi-trash me-1"></i>Remove
                        </button>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;"
                             id="currentImage">
                        <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                    </div>
                </div>
            @endif

            <!-- Pricing & Inventory -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-dollar me-2" style="color: var(--primary-cyan);"></i>
                        Pricing & Inventory
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', number_format($product->price, 2, '.', '')) }}" 
                                       step="0.01" 
                                       min="0" 
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="stock_quantity" class="form-label">
                                Stock Quantity <span class="text-danger">*</span>
                                <span class="badge {{ $product->stock_quantity <= 0 ? 'bg-danger' : ($product->stock_quantity <= 10 ? 'bg-warning' : 'bg-success') }} ms-2">
                                    Current: {{ $product->stock_quantity }}
                                </span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('stock_quantity') is-invalid @enderror" 
                                       id="stock_quantity" 
                                       name="stock_quantity" 
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                       min="0" 
                                       placeholder="0"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="quickStock(10)">+10</button>
                                <button class="btn btn-outline-secondary" type="button" onclick="quickStock(50)">+50</button>
                                <button class="btn btn-outline-secondary" type="button" onclick="quickStock(100)">+100</button>
                            </div>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2" style="color: var(--primary-orange);"></i>
                        Product Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-cart text-primary mb-2" style="font-size: 1.5rem;"></i>
                                <h4 class="mb-1">{{ $product->orderItems->sum('quantity') ?? 0 }}</h4>
                                <small class="text-muted">Total Sold</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-currency-dollar text-success mb-2" style="font-size: 1.5rem;"></i>
                                <h4 class="mb-1">${{ number_format($product->orderItems->sum(fn($item) => $item->quantity * $item->price) ?? 0, 2) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-people text-info mb-2" style="font-size: 1.5rem;"></i>
                                <h4 class="mb-1">{{ $product->orderItems->pluck('order.customer_name')->unique()->count() ?? 0 }}</h4>
                                <small class="text-muted">Unique Buyers</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-calendar text-warning mb-2" style="font-size: 1.5rem;"></i>
                                <h4 class="mb-1">{{ $product->created_at->diffInDays() }}</h4>
                                <small class="text-muted">Days Listed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Image Preview -->
            <div class="card dashboard-card mb-4" id="imagePreviewCard" style="display: none;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-image me-2" style="color: var(--primary-orange);"></i>
                        New Image Preview
                    </h5>
                </div>
                <div class="card-body text-center">
                    <img id="imagePreview" src="" alt="New Product Preview" class="img-fluid rounded" style="max-height: 300px;">
                </div>
            </div>

            <!-- Actions -->
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Product
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-grid me-2"></i>All Products
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Changes
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
// Store original values for reset
const originalValues = {
    name: '{{ $product->name }}',
    category_id: '{{ $product->category_id }}',
    description: `{{ $product->description }}`,
    price: '{{ number_format($product->price, 2, '.', '') }}',
    stock_quantity: '{{ $product->stock_quantity }}'
};

// Image Preview for new upload
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewCard = document.getElementById('imagePreviewCard');
    const previewImg = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewCard.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewCard.style.display = 'none';
    }
});

// Remove current image
function removeCurrentImage() {
    const currentImageCard = document.getElementById('currentImageCard');
    const removeInput = document.getElementById('removeImageInput');
    
    if (currentImageCard) {
        currentImageCard.style.display = 'none';
        removeInput.value = '1';
    }
}

// Quick stock addition
function quickStock(amount) {
    const stockInput = document.getElementById('stock_quantity');
    const currentStock = parseInt(stockInput.value) || 0;
    stockInput.value = currentStock + amount;
}

// Reset form to original values
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will discard any unsaved modifications.')) {
        document.getElementById('name').value = originalValues.name;
        document.getElementById('category_id').value = originalValues.category_id;
        document.getElementById('description').value = originalValues.description;
        document.getElementById('price').value = originalValues.price;
        document.getElementById('stock_quantity').value = originalValues.stock_quantity;
        document.getElementById('image').value = '';
        
        // Hide preview and show current image if exists
        document.getElementById('imagePreviewCard').style.display = 'none';
        const currentImageCard = document.getElementById('currentImageCard');
        const removeInput = document.getElementById('removeImageInput');
        if (currentImageCard) {
            currentImageCard.style.display = 'block';
            removeInput.value = '0';
        }
        
        // Remove validation classes
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }
}

// Delete confirmation
function confirmDelete(productId, productName) {
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'category_id', 'description', 'price', 'stock_quantity'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});

// Real-time validation
document.querySelectorAll('input, select, textarea').forEach(field => {
    field.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    field.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') && this.value.trim()) {
            this.classList.remove('is-invalid');
        }
    });
});

// Price formatting
document.getElementById('price').addEventListener('input', function() {
    let value = parseFloat(this.value);
    if (!isNaN(value)) {
        this.value = value.toFixed(2);
    }
});
</script>
@endpush
