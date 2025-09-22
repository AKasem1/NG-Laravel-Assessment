@extends('layouts.app')

@section('title', isset($searchQuery) ? 'Search Results for "' . $searchQuery . '"' : (isset($currentCategory) ?
    $currentCategory->name . ' Products' : 'Medical Products Catalog'))
@section('description', isset($currentCategory) ? 'Browse premium ' . strtolower($currentCategory->name) . ' medical
    products and supplies' : 'Explore our comprehensive catalog of premium medical products and healthcare supplies')

    @push('styles')
        <style>
            /* Products Catalog Styles */
            .catalog-header {
                padding: 60px 0 40px;
                background: var(--gradient-soft);
                position: relative;
                overflow: hidden;
            }

            .catalog-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background:
                    radial-gradient(circle at 30% 20%, rgba(255, 139, 61, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(78, 205, 196, 0.05) 0%, transparent 50%);
                animation: float 8s ease-in-out infinite;
            }

            .page-title {
                font-size: clamp(2rem, 4vw, 3.5rem);
                font-weight: 800;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
            }

            .page-subtitle {
                font-size: 1.2rem;
                opacity: 0.8;
                max-width: 600px;
                margin: 0 auto;
            }

            /* Advanced Filters Sidebar */
            .filters-sidebar {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius);
                padding: 2rem;
                height: fit-content;
                position: sticky;
                top: 100px;
                box-shadow: var(--shadow-soft);
            }

            .filter-section {
                margin-bottom: 2rem;
                padding-bottom: 1.5rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .filter-section:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }

            .filter-title {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--dark-text);
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
            }

            .filter-title i {
                margin-right: 8px;
                color: var(--primary-orange);
            }

            .price-range-container {
                position: relative;
                margin: 1rem 0;
            }

            .price-range {
                -webkit-appearance: none;
                appearance: none;
                width: 100%;
                height: 6px;
                background: linear-gradient(to right, var(--primary-orange), var(--primary-cyan));
                border-radius: 3px;
                outline: none;
            }

            .price-range::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                width: 20px;
                height: 20px;
                background: white;
                border: 3px solid var(--primary-orange);
                border-radius: 50%;
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
            }

            .price-range::-webkit-slider-thumb:hover {
                transform: scale(1.2);
                box-shadow: 0 4px 12px rgba(255, 139, 61, 0.4);
            }

            .category-filter .form-check {
                margin-bottom: 0.8rem;
            }

            .form-check-input {
                border-radius: 4px;
                border: 2px solid #e9ecef;
                transition: all 0.3s ease;
            }

            .form-check-input:checked {
                background: var(--gradient-primary);
                border-color: var(--primary-orange);
                box-shadow: 0 2px 8px rgba(255, 139, 61, 0.3);
            }

            .form-check-label {
                font-weight: 500;
                color: var(--dark-text);
                margin-left: 8px;
            }

            /* Toolbar */
            .products-toolbar {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius);
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow-soft);
            }

            .results-info {
                color: var(--dark-text);
                font-weight: 600;
            }

            .view-toggle {
                display: flex;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 8px;
                padding: 4px;
                gap: 4px;
            }

            .view-btn {
                padding: 8px 12px;
                border: none;
                background: transparent;
                border-radius: 6px;
                color: var(--dark-text);
                transition: all 0.3s ease;
                opacity: 0.6;
            }

            .view-btn.active {
                background: var(--gradient-primary);
                color: white;
                opacity: 1;
                box-shadow: 0 2px 8px rgba(255, 139, 61, 0.3);
            }

            .sort-select {
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                padding: 10px 15px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .sort-select:focus {
                border-color: var(--primary-orange);
                box-shadow: 0 0 0 3px rgba(255, 139, 61, 0.1);
            }

            /* Modern Product Cards */
            .product-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 2rem;
                margin-bottom: 3rem;
            }

            .product-card-modern {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius);
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                height: 100%;
                display: flex;
                flex-direction: column;
                box-shadow: var(--shadow-soft);
            }

            .product-card-modern::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 2px;
                background: var(--gradient-primary);
                transition: left 0.4s ease;
            }

            .product-card-modern:hover {
                transform: translateY(-8px);
                box-shadow: var(--shadow-strong);
                border-color: rgba(255, 139, 61, 0.3);
            }

            .product-card-modern:hover::before {
                left: 0;
            }

            .product-image-container {
                height: 220px;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                position: relative;
            }

            .product-image-container::after {
                content: '';
                position: absolute;
                inset: 0;
                background: rgba(255, 139, 61, 0.05);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .product-card-modern:hover .product-image-container::after {
                opacity: 1;
            }

            .product-image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .product-card-modern:hover .product-image-container img {
                transform: scale(1.1);
            }

            .product-placeholder {
                font-size: 3rem;
                color: var(--primary-cyan);
                opacity: 0.6;
            }

            .product-content {
                padding: 1.5rem;
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .product-category {
                font-size: 0.85rem;
                color: var(--primary-orange);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5rem;
            }

            .product-title {
                font-size: 1.2rem;
                font-weight: 700;
                color: var(--dark-text);
                margin-bottom: 0.8rem;
                line-height: 1.3;
                flex: 1;
            }

            .product-description {
                color: rgba(30, 41, 59, 0.7);
                font-size: 0.9rem;
                line-height: 1.5;
                margin-bottom: 1rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .product-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: auto;
            }

            .product-price {
                font-size: 1.5rem;
                font-weight: 800;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .stock-status {
                font-size: 0.8rem;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 6px;
                background: rgba(40, 167, 69, 0.1);
                color: #28a745;
            }

            .stock-low {
                background: rgba(255, 193, 7, 0.1);
                color: #ffc107;
            }

            .stock-out {
                background: rgba(220, 53, 69, 0.1);
                color: #dc3545;
            }

            .product-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .btn-add-cart {
                flex: 1;
                background: var(--gradient-primary);
                border: none;
                color: white;
                padding: 10px 16px;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .btn-add-cart:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-medium);
                color: white;
            }

            .btn-add-cart:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            .btn-view-details {
                padding: 10px 16px;
                background: rgba(255, 255, 255, 0.8);
                border: 2px solid var(--primary-orange);
                color: var(--primary-orange);
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-view-details:hover {
                background: var(--primary-orange);
                color: white;
                transform: translateY(-2px);
            }

            /* Pagination */
            .pagination-modern {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border-radius: var(--border-radius);
                padding: 1.5rem;
                box-shadow: var(--shadow-soft);
            }

            .pagination-modern .pagination {
                margin-bottom: 0;
                justify-content: center;
            }

            .pagination-modern .page-link {
                border: none;
                background: transparent;
                color: var(--dark-text);
                font-weight: 600;
                border-radius: 8px;
                margin: 0 2px;
                transition: all 0.3s ease;
            }

            .pagination-modern .page-link:hover {
                background: var(--gradient-soft);
                color: var(--primary-orange);
            }

            .pagination-modern .page-item.active .page-link {
                background: var(--gradient-primary);
                color: white;
                box-shadow: 0 2px 8px rgba(255, 139, 61, 0.3);
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border-radius: var(--border-radius);
                border: 2px dashed rgba(255, 139, 61, 0.3);
            }

            .empty-icon {
                font-size: 4rem;
                color: var(--primary-cyan);
                margin-bottom: 1rem;
                opacity: 0.6;
            }

            .btn-outline-secondary {
                border-color: var(--primary-orange);
                color: var(--primary-orange);
                border-radius: 12px;
                padding: 12px 24px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-outline-secondary:hover {
                background: var(--primary-orange);
                border-color: var(--primary-orange);
                color: white;
                transform: translateY(-2px);
            }


            /* Mobile Responsive */
            @media (max-width: 768px) {
                .catalog-header {
                    padding: 40px 0 30px;
                    text-align: center;
                }

                .filters-sidebar {
                    position: static;
                    margin-bottom: 2rem;
                    padding: 1.5rem;
                }

                .product-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 1.5rem;
                }

                .products-toolbar {
                    flex-direction: column;
                    gap: 1rem;
                }

                .view-toggle {
                    order: -1;
                    justify-content: center;
                }
            }

            /* Loading States */
            .loading-overlay {
                position: absolute;
                inset: 0;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(5px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
            }

            .loading-spinner {
                width: 40px;
                height: 40px;
                border: 4px solid rgba(255, 139, 61, 0.2);
                border-top: 4px solid var(--primary-orange);
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush

@section('content')
    <!-- Catalog Header -->
    <section class="catalog-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="page-title">
                        @if (isset($searchQuery))
                            Search Results
                        @elseif(isset($currentCategory))
                            {{ $currentCategory->name }}
                        @else
                            Medical Products
                        @endif
                    </h1>
                    <p class="page-subtitle">
                        @if (isset($searchQuery))
                            Showing results for "{{ $searchQuery }}"
                        @elseif(isset($currentCategory))
                            Professional {{ strtolower($currentCategory->name) }} products and medical supplies
                        @else
                            Discover our comprehensive range of premium medical products and healthcare solutions
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="filters-sidebar">
                    <form id="filtersForm" method="GET">
                        @if (isset($searchQuery))
                            <input type="hidden" name="q" value="{{ $searchQuery }}">
                        @endif
                        @if (isset($currentCategory))
                            <input type="hidden" name="category"
                                value="{{ $currentCategory->slug ?? $currentCategory->id }}">
                        @endif

                        <!-- Price Range -->
                        <div class="filter-section">
                            <div class="filter-title">
                                <i class="bi bi-currency-dollar"></i>
                                Price Range
                            </div>
                            <div class="price-range-container">
                                <input type="range" class="price-range" name="max_price" min="0" max="1000"
                                    value="{{ request('max_price', 1000) }}" id="priceRange">
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-muted">$0</span>
                                    <span class="text-muted">$<span
                                            id="priceValue">{{ request('max_price', 1000) }}</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        @if (!isset($currentCategory) && $categories->count() > 0)
                            <div class="filter-section">
                                <div class="filter-title">
                                    <i class="bi bi-tags"></i>
                                    Categories
                                </div>
                                <div class="category-filter">
                                    @foreach ($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="{{ $category->id }}" id="cat{{ $category->id }}"
                                                {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock Status -->
                        <div class="filter-section">
                            <div class="filter-title">
                                <i class="bi bi-box"></i>
                                Availability
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="in_stock" value="1"
                                    id="inStock" {{ request('in_stock') ? 'checked' : '' }}>
                                <label class="form-check-label" for="inStock">
                                    In Stock Only
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="bi bi-funnel me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Clear All
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-lg-9 col-md-8">
                <!-- Toolbar -->
                <div class="products-toolbar d-flex flex-wrap justify-content-between align-items-center">
                    <div class="results-info">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of
                        {{ $products->total() }} products
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <!-- View Toggle -->
                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>

                        <!-- Sort -->
                        <select class="sort-select" name="sort" onchange="updateSort(this.value)">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A
                            </option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low-High
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price
                                High-Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="productsContainer" class="position-relative">
                    @if ($products->count() > 0)
                        <div class="product-grid" id="productGrid">
                            @foreach ($products as $product)
                                <div class="product-card-modern">
                                    <div class="product-image-container">
                                        @if ($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="bi bi-capsule"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-content">
                                        @if ($product->category)
                                            <div class="product-category">{{ $product->category->name }}</div>
                                        @endif

                                        <h3 class="product-title">{{ $product->name }}</h3>

                                        @if ($product->description)
                                            <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                                        @endif

                                        <div class="product-footer">
                                            <div class="product-price">${{ number_format($product->price, 2) }}</div>
                                            <div
                                                class="stock-status {{ $product->stock_quantity < 5 ? ($product->stock_quantity == 0 ? 'stock-out' : 'stock-low') : '' }}">
                                                @if ($product->stock_quantity == 0)
                                                    Out of Stock
                                                @elseif($product->stock_quantity < 5)
                                                    {{ $product->stock_quantity }} left
                                                @else
                                                    In Stock
                                                @endif
                                            </div>
                                        </div>

                                        <div class="product-actions">
                                            @if ($product->stock_quantity > 0)
                                                <button class="btn btn-add-cart"
                                                    onclick="addToCart({{ $product->id }})">
                                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                                </button>
                                            @else
                                                <button class="btn btn-add-cart" disabled>
                                                    <i class="bi bi-x-circle me-2"></i>Out of Stock
                                                </button>
                                            @endif
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="btn-view-details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="pagination-modern mt-4">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h3>No Products Found</h3>
                            <p class="text-muted mb-3">
                                @if (isset($searchQuery))
                                    Sorry, we couldn't find any products matching "{{ $searchQuery }}".
                                @else
                                    No products are currently available in this category.
                                @endif
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                @if (isset($searchQuery))
                                    <a href="{{ route('products.index') }}" class="btn btn-primary-modern">
                                        <i class="bi bi-grid me-2"></i>Browse All Products
                                    </a>
                                @endif
                                <a href="{{ route('home') }}" class="btn btn-outline-modern">
                                    <i class="bi bi-house me-2"></i>Back to Home
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add to Cart function for product listing
    function addToCart(productId) {
        const btn = event.target.closest('.btn-add-cart');
        if (!btn) return;
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<div class="loading-spinner"></div> Adding...';
        btn.disabled = true;
    
        fetch('/cart/add/' + productId, {  // Use direct URL construction instead of route template
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                quantity: 1 
            })
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Add to cart success:', data); // Debug log
            
            if (data.success) {
                updateCartCount();
                showModernToast(`${data.message || 'Item added to cart!'} 🛒`, 'success');
                
                // Visual feedback
                btn.style.background = '#28a745';
                btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Added to Cart!';
                
                setTimeout(() => {
                    btn.style.background = '';
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            showModernToast(error.message || 'Something went wrong. Please try again.', 'error');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    
    // Update cart count in header
    function updateCartCount() {
        fetch('/cart/count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count, #cartCount, [data-cart-count]');
            cartCountElements.forEach(el => {
                if (el) el.textContent = data.count || 0;
            });
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
    }
    
    // Sort Update
    function updateSort(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
    }
    
    // Price Range Slider
    document.getElementById('priceRange')?.addEventListener('input', function() {
        const priceValue = document.getElementById('priceValue');
        if (priceValue) {
            priceValue.textContent = this.value;
        }
    });
    
    // View Toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            const grid = document.getElementById('productGrid');
            
            if (grid) {
                if (view === 'list') {
                    grid.style.gridTemplateColumns = '1fr';
                    grid.querySelectorAll('.product-card-modern').forEach(card => {
                        card.style.flexDirection = 'row';
                        card.style.height = 'auto';
                        const img = card.querySelector('.product-image-container');
                        if (img) img.style.height = '120px';
                    });
                } else {
                    grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
                    grid.querySelectorAll('.product-card-modern').forEach(card => {
                        card.style.flexDirection = 'column';
                        card.style.height = '100%';
                        const img = card.querySelector('.product-image-container');
                        if (img) img.style.height = '220px';
                    });
                }
            }
        });
    });
    
    // AJAX Filtering (Optional Enhancement)
    document.getElementById('filtersForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        
        const url = new URL(window.location);
        params.forEach((value, key) => {
            url.searchParams.set(key, value);
        });
        
        window.location.href = url.toString();
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
        console.log('Products page JavaScript initialized');
    });
    
    // Fallback toast function
    if (typeof showModernToast === 'undefined') {
        window.showModernToast = function(message, type) {
            console.log(`${type.toUpperCase()}: ${message}`);
        };
    }
    </script>
@endpush
