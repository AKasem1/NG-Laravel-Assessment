@extends('layouts.admin')

@section('title', 'Add New Category')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Categories</a>
    </li>
    <li class="breadcrumb-item active">Add New Category</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-10">
        <!-- Page Header -->
        <div class="mb-4">
            <h2 class="mb-1" style="color: var(--dark-text);">
                <i class="bi bi-plus-circle me-2" style="color: var(--primary-cyan);"></i>
                Add New Category
            </h2>
            <p class="text-muted mb-0">Create a new category to organize your medical products</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
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
                                   value="{{ old('name') }}" 
                                   placeholder="Enter category name (e.g., Medications, Medical Devices...)"
                                   required
                                   maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="nameCounter">0</span>/100 characters
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Describe what products belong in this category (optional)..."
                                      maxlength="500">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="descCounter">0</span>/500 characters
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Preview -->
            <div class="card dashboard-card mb-4" id="previewCard" style="display: none;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-eye me-2" style="color: var(--primary-orange);"></i>
                        Preview
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
                                            <h5 class="mb-1" id="previewName">Category Name</h5>
                                            <small class="text-muted">New Category</small>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small mb-3" id="previewDescription" style="display: none;">
                                        Category description will appear here
                                    </p>
                                    
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded">
                                                <div class="h5 mb-0 text-primary">0</div>
                                                <small class="text-muted">Products</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded">
                                                <div class="h5 mb-0 text-success">$0</div>
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

            <!-- Suggested Categories -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb me-2" style="color: var(--primary-orange);"></i>
                        Suggested Medical Categories
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Click on any suggestion to use it:</p>
                    <div class="row g-2">
                        @php
                            $suggestions = [
                                ['name' => 'Prescription Medications', 'desc' => 'Prescription drugs and pharmaceuticals'],
                                ['name' => 'Over-the-Counter (OTC)', 'desc' => 'Non-prescription medications and supplements'],
                                ['name' => 'Medical Devices', 'desc' => 'Medical equipment and diagnostic devices'],
                                ['name' => 'Surgical Supplies', 'desc' => 'Surgical instruments and supplies'],
                                ['name' => 'First Aid & Emergency', 'desc' => 'First aid kits and emergency medical supplies'],
                                ['name' => 'Vitamins & Supplements', 'desc' => 'Nutritional supplements and vitamins'],
                                ['name' => 'Personal Care', 'desc' => 'Personal hygiene and care products'],
                                ['name' => 'Mobility Aids', 'desc' => 'Wheelchairs, walkers, and mobility equipment'],
                                ['name' => 'Diagnostic Equipment', 'desc' => 'Blood pressure monitors, thermometers, etc.'],
                                ['name' => 'Wound Care', 'desc' => 'Bandages, dressings, and wound care products'],
                                ['name' => 'Respiratory Care', 'desc' => 'Inhalers, nebulizers, and respiratory equipment'],
                                ['name' => 'Diabetic Supplies', 'desc' => 'Blood glucose monitors and diabetic care products']
                            ];
                        @endphp
                        
                        @foreach($suggestions as $suggestion)
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <button type="button" 
                                        class="btn btn-outline-info w-100 text-start suggestion-btn"
                                        data-name="{{ $suggestion['name'] }}"
                                        data-description="{{ $suggestion['desc'] }}">
                                    <strong>{{ $suggestion['name'] }}</strong>
                                    <br><small class="text-muted">{{ $suggestion['desc'] }}</small>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Categories
                        </a>
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                        </button>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-lg me-2"></i>Create Category
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Character counters
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
    const previewCard = document.getElementById('previewCard');
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    
    if (name) {
        previewCard.style.display = 'block';
        previewName.textContent = name;
        
        if (description) {
            previewDescription.textContent = description;
            previewDescription.style.display = 'block';
        } else {
            previewDescription.style.display = 'none';
        }
    } else {
        previewCard.style.display = 'none';
    }
}

// Suggestion buttons
document.querySelectorAll('.suggestion-btn').forEach(button => {
    button.addEventListener('click', function() {
        const name = this.getAttribute('data-name');
        const description = this.getAttribute('data-description');
        
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
        
        // Update counters and preview
        document.getElementById('nameCounter').textContent = name.length;
        document.getElementById('descCounter').textContent = description.length;
        updatePreview();
        
        // Scroll to form
        document.getElementById('name').scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById('name').focus();
    });
});

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

// Real-time field validation
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

// Form reset
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        document.getElementById('nameCounter').textContent = '0';
        document.getElementById('descCounter').textContent = '0';
        document.getElementById('previewCard').style.display = 'none';
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    } else {
        return false;
    }
});

// Initialize counters on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCounters();
    updatePreview();
});
</script>
@endpush

