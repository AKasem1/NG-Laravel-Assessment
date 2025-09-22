@extends('layouts.admin')

@section('title', 'Add New Product')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Products</a>
    </li>
    <li class="breadcrumb-item active">Add New Product</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div>
                        <h2 class="mb-1" style="color: var(--dark-text);">
                            <i class="bi bi-plus-circle me-2" style="color: var(--primary-cyan);"></i>
                            Add New Product
                        </h2>
                        <p class="text-muted mb-0">Add a new medical product to your inventory</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            <span class="d-none d-sm-inline">Back to Products</span>
                            <span class="d-sm-none">Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                id="productForm">
                @csrf

                <!-- Hidden field to store ImgBB URL -->
                <input type="hidden" name="imgbb_url" id="imgbb_url" value="">

                <!-- Basic Information -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2" style="color: var(--primary-cyan);"></i>
                            Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter product name..." required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="nameCounter">0</span>/255 characters
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="category_id" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Describe the product, its uses, and benefits..." maxlength="1000">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="descCounter">0</span>/1000 characters
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing and Inventory -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-currency-dollar me-2" style="color: var(--primary-orange);"></i>
                            Pricing & Inventory
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price') }}" placeholder="0.00"
                                        step="0.01" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label">Stock Quantity <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                    id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}"
                                    placeholder="0" min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Image Upload -->
                <!-- Product Image Upload -->
                <div class="card dashboard-card mb-4">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-image me-2" style="color: var(--primary-cyan);"></i>
                            Product Image
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image" accept="image/*" onchange="uploadImageToImgBB(this)">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Supported formats: JPG, PNG, GIF. Max size: 5MB
                                </div>

                                <!-- Upload Status -->
                                <div id="uploadStatus" class="mt-2" style="display: none;">
                                    <div id="uploadProgress" class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="spinner-border spinner-border-sm text-primary me-2"
                                                role="status">
                                                <span class="visually-hidden">Uploading...</span>
                                            </div>
                                            <small class="text-muted">Uploading image to cloud storage...</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Success Message -->
                                <div id="uploadSuccess" class="alert alert-success mt-2" style="display: none;">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Image uploaded successfully to cloud storage!
                                </div>

                                <!-- Error Message -->
                                <div id="uploadError" class="alert alert-danger mt-2" style="display: none;">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    <span id="uploadErrorText">Failed to upload image. Please try again.</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Image Preview - FIXED LAYOUT -->
                                <label class="form-label">Preview</label>
                                <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                    style="height: 250px; overflow: hidden; position: relative;">

                                    <!-- Default Placeholder -->
                                    <div id="imagePreview" class="text-center text-muted"
                                        style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                                        <div>
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p class="mb-0 mt-2">Image preview will appear here</p>
                                        </div>
                                    </div>

                                    <!-- Uploaded Image -->
                                    <img id="previewImg" src="" alt="Product Preview"
                                        style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Actions -->
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info" id="submitBtn">
                                    <i class="bi bi-check-lg me-2"></i>Add Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ImgBB Upload Configuration
        const imgbbApiKey = '{{ config('services.imgbb.key') }}';

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

        // Live preview update
        function updatePreview() {
            const name = document.getElementById('name').value.trim() || 'Product Name';
            const category = document.getElementById('category_id');
            const categoryText = category.selectedOptions[0]?.text || 'Category';
            const description = document.getElementById('description').value.trim() ||
                'Product description will appear here';
            const price = document.getElementById('price').value || '0.00';
            const stock = document.getElementById('stock_quantity').value || '0';

            document.getElementById('previewName').textContent = name;
            document.getElementById('previewCategory').textContent = categoryText;
            document.getElementById('previewDescription').textContent = description;
            document.getElementById('previewPrice').textContent = '$' + parseFloat(price).toFixed(2);
            document.getElementById('previewStock').textContent = stock;
        }

        // Upload image to ImgBB cloud storage
        // Upload image to ImgBB cloud storage - FIXED VERSION
        function uploadImageToImgBB(input) {
            const file = input.files[0];
            const uploadStatus = document.getElementById('uploadStatus');
            const uploadSuccess = document.getElementById('uploadSuccess');
            const uploadError = document.getElementById('uploadError');
            const submitBtn = document.getElementById('submitBtn');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const previewProductImg = document.getElementById('previewProductImg');
            const previewPlaceholder = document.getElementById('previewPlaceholder');
            const imgbbUrlField = document.getElementById('imgbb_url');

            // Reset previous states
            uploadStatus.style.display = 'none';
            uploadSuccess.style.display = 'none';
            uploadError.style.display = 'none';

            if (!file) {
                // Reset preview if no file selected
                previewImg.style.display = 'none';
                imagePreview.style.display = 'flex';
                previewProductImg.style.display = 'none';
                if (previewPlaceholder) previewPlaceholder.style.display = 'flex';
                imgbbUrlField.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showUploadError('Please select a valid image file (JPG, PNG, GIF, or WebP).');
                input.value = '';
                return;
            }

            // Validate file size (5MB max)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                showUploadError('Image file size must be less than 5MB.');
                input.value = '';
                return;
            }

            // Show local preview immediately while uploading
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                imagePreview.style.display = 'none';

                // Update product preview too
                previewProductImg.src = e.target.result;
                previewProductImg.style.display = 'block';
                if (previewPlaceholder) previewPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);

            // Show upload progress
            uploadStatus.style.display = 'block';
            submitBtn.disabled = true;

            // Prepare form data
            const formData = new FormData();
            formData.append('key', imgbbApiKey);
            formData.append('image', file);

            // Upload to ImgBB
            fetch('https://api.imgbb.com/1/upload', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store the ImgBB URL
                        imgbbUrlField.value = data.data.url;

                        // Show success message
                        uploadStatus.style.display = 'none';
                        uploadSuccess.style.display = 'block';

                        // Update with final ImgBB URL (replace local preview)
                        previewImg.src = data.data.url;
                        previewProductImg.src = data.data.url;

                        console.log('Image uploaded successfully:', data.data.url);

                        // Auto-hide success message
                        setTimeout(() => {
                            uploadSuccess.style.display = 'none';
                        }, 5000);
                    } else {
                        throw new Error(data.error?.message || 'Upload failed');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    showUploadError('Failed to upload image: ' + error.message);

                    // Clear the file input and reset preview
                    input.value = '';
                    imgbbUrlField.value = '';
                    previewImg.style.display = 'none';
                    imagePreview.style.display = 'flex';
                    previewProductImg.style.display = 'none';
                    if (previewPlaceholder) previewPlaceholder.style.display = 'flex';
                })
                .finally(() => {
                    uploadStatus.style.display = 'none';
                    submitBtn.disabled = false;
                });
        }


        // Show upload error
        function showUploadError(message) {
            const uploadError = document.getElementById('uploadError');
            const uploadErrorText = document.getElementById('uploadErrorText');

            uploadErrorText.textContent = message;
            uploadError.style.display = 'block';

            // Hide error after 5 seconds
            setTimeout(() => {
                uploadError.style.display = 'none';
            }, 5000);
        }

        // Reset form
        // Reset form - UPDATED VERSION
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                document.getElementById('productForm').reset();
                document.getElementById('imgbb_url').value = '';

                // Reset counters
                document.getElementById('nameCounter').textContent = '0';
                document.getElementById('descCounter').textContent = '0';

                // Reset image previews
                const previewImg = document.getElementById('previewImg');
                const imagePreview = document.getElementById('imagePreview');
                const previewProductImg = document.getElementById('previewProductImg');
                const previewPlaceholder = document.getElementById('previewPlaceholder');

                previewImg.style.display = 'none';
                imagePreview.style.display = 'flex';
                previewProductImg.style.display = 'none';
                if (previewPlaceholder) previewPlaceholder.style.display = 'flex';

                // Reset upload messages
                document.getElementById('uploadSuccess').style.display = 'none';
                document.getElementById('uploadError').style.display = 'none';
                document.getElementById('uploadStatus').style.display = 'none';

                // Reset preview
                updatePreview();

                // Remove validation classes
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }
        }


        // Form validation before submit
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const requiredFields = ['name', 'category_id', 'price', 'stock_quantity'];
            let isValid = true;

            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                document.querySelector('.is-invalid').focus();
                document.querySelector('.is-invalid').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }
        });

        // Real-time field validation
        document.addEventListener('DOMContentLoaded', function() {
            const fields = ['name', 'price', 'stock_quantity', 'category_id'];

            fields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                field.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });

                field.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                    updatePreview();
                });
            });

            // Initialize
            updateCounters();
            updatePreview();
        });

        // Auto-hide success messages
        setTimeout(() => {
            const successAlert = document.getElementById('uploadSuccess');
            if (successAlert && successAlert.style.display !== 'none') {
                successAlert.style.display = 'none';
            }
        }, 10000); // Hide after 10 seconds
    </script>
@endpush
