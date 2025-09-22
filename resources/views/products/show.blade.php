@extends('layouts.app')

@section('title', $product->name . ' - Premium Medical Product')
@section('description', $product->description ? Str::limit(strip_tags($product->description), 155) : 'Premium ' .
    $product->name . ' medical product. High-quality healthcare supplies with fast delivery.')

    @push('styles')
        <style>
            /* Product Details Styles */
            .product-hero {
                padding: 40px 0;
                background: var(--gradient-soft);
                position: relative;
                overflow: hidden;
            }

            .product-hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background:
                    radial-gradient(circle at 30% 20%, rgba(255, 139, 61, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(78, 205, 196, 0.08) 0%, transparent 50%);
                animation: float 6s ease-in-out infinite;
            }

            /* Breadcrumb Modern */
            .breadcrumb-modern {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border-radius: 50px;
                padding: 12px 24px;
                margin-bottom: 0;
                box-shadow: var(--shadow-soft);
                display: inline-flex;
            }

            .breadcrumb-modern .breadcrumb-item {
                font-weight: 500;
                font-size: 0.9rem;
            }

            .breadcrumb-modern .breadcrumb-item+.breadcrumb-item::before {
                content: "›";
                color: var(--primary-orange);
                font-weight: bold;
            }

            .breadcrumb-modern a {
                color: var(--primary-orange);
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .breadcrumb-modern a:hover {
                color: var(--primary-cyan);
            }

            /* Product Image Gallery */
            .product-gallery {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius-large);
                padding: 2rem;
                box-shadow: var(--shadow-soft);
                position: sticky;
                top: 100px;
            }

            .main-image-container {
                height: 400px;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                border-radius: var(--border-radius);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                position: relative;
                margin-bottom: 1rem;
                cursor: zoom-in;
            }

            .main-image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }

            .main-image-container:hover img {
                transform: scale(1.1);
            }

            .image-placeholder {
                font-size: 5rem;
                color: var(--primary-cyan);
                opacity: 0.4;
            }

            .thumbnail-gallery {
                display: flex;
                gap: 0.5rem;
                overflow-x: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .thumbnail-gallery::-webkit-scrollbar {
                display: none;
            }

            .thumbnail-item {
                flex: 0 0 80px;
                height: 80px;
                border-radius: 12px;
                overflow: hidden;
                cursor: pointer;
                border: 2px solid transparent;
                transition: all 0.3s ease;
                background: #f8fafc;
            }

            .thumbnail-item.active {
                border-color: var(--primary-orange);
                box-shadow: 0 0 0 2px rgba(255, 139, 61, 0.2);
            }

            .thumbnail-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Product Information */
            .product-info {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius-large);
                padding: 2.5rem;
                box-shadow: var(--shadow-soft);
            }

            .product-category-badge {
                display: inline-flex;
                align-items: center;
                background: var(--gradient-soft);
                color: var(--primary-orange);
                padding: 8px 16px;
                border-radius: 25px;
                font-size: 0.85rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 1rem;
                border: 1px solid rgba(255, 139, 61, 0.2);
            }

            .product-title {
                font-size: clamp(1.8rem, 4vw, 2.5rem);
                font-weight: 800;
                color: var(--dark-text);
                margin-bottom: 1rem;
                line-height: 1.2;
            }

            .product-rating {
                display: flex;
                align-items: center;
                margin-bottom: 1.5rem;
                gap: 1rem;
            }

            .stars {
                display: flex;
                gap: 2px;
            }

            .star {
                color: #ffc107;
                font-size: 1.2rem;
            }

            .star.empty {
                color: #e9ecef;
            }

            .rating-text {
                color: var(--dark-text);
                font-weight: 500;
            }

            .product-price {
                display: flex;
                align-items: baseline;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .current-price {
                font-size: 2.5rem;
                font-weight: 800;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .original-price {
                font-size: 1.5rem;
                color: #6c757d;
                text-decoration: line-through;
            }

            .price-save {
                background: #28a745;
                color: white;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 600;
            }

            /* Stock Status */
            .stock-indicator {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1.5rem;
                padding: 12px 20px;
                border-radius: 12px;
                font-weight: 600;
                background: rgba(40, 167, 69, 0.1);
                color: #28a745;
                border: 1px solid rgba(40, 167, 69, 0.2);
            }

            .stock-low {
                background: rgba(255, 193, 7, 0.1);
                color: #ffc107;
                border-color: rgba(255, 193, 7, 0.2);
            }

            .stock-out {
                background: rgba(220, 53, 69, 0.1);
                color: #dc3545;
                border-color: rgba(220, 53, 69, 0.2);
            }

            /* Quantity Selector */
            .quantity-selector {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .quantity-input {
                display: flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.8);
                border: 2px solid rgba(255, 139, 61, 0.2);
                border-radius: 12px;
                overflow: hidden;
                box-shadow: var(--shadow-soft);
            }

            .qty-btn {
                background: transparent;
                border: none;
                padding: 12px 16px;
                color: var(--primary-orange);
                font-size: 1.2rem;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .qty-btn:hover {
                background: var(--gradient-soft);
            }

            .qty-btn:disabled {
                opacity: 0.4;
                cursor: not-allowed;
            }

            .qty-value {
                padding: 12px 20px;
                border: none;
                background: transparent;
                text-align: center;
                font-weight: 600;
                width: 80px;
                color: var(--dark-text);
            }

            /* Action Buttons */
            .product-actions {
                display: flex;
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .btn-add-to-cart {
                flex: 1;
                background: var(--gradient-primary);
                border: none;
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.1rem;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
                min-height: 56px;
            }

            .btn-add-to-cart:hover {
                transform: translateY(-3px);
                box-shadow: var(--shadow-strong);
                color: white;
            }

            .btn-add-to-cart:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            .btn-wishlist {
                background: rgba(255, 255, 255, 0.8);
                border: 2px solid rgba(255, 139, 61, 0.3);
                color: var(--primary-orange);
                padding: 16px;
                border-radius: 12px;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .btn-wishlist:hover {
                background: var(--gradient-primary);
                color: white;
                border-color: transparent;
                transform: translateY(-3px);
            }

            /* Product Features */
            .product-features {
                background: var(--gradient-soft);
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .feature-list {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .feature-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: var(--dark-text);
                font-weight: 500;
            }

            .feature-item i {
                color: var(--primary-cyan);
                font-size: 1.1rem;
            }

            /* Specifications */
            .specifications-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius-large);
                padding: 2rem;
                margin-top: 2rem;
                box-shadow: var(--shadow-soft);
            }

            .spec-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .spec-table th,
            .spec-table td {
                padding: 12px 16px;
                text-align: left;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .spec-table th {
                background: var(--gradient-soft);
                font-weight: 600;
                color: var(--dark-text);
                width: 30%;
            }

            .spec-table tr:last-child th,
            .spec-table tr:last-child td {
                border-bottom: none;
            }

            /* Add this to your existing styles */

            /* Related Products - FIXED STYLES */
            .related-products {
                margin-top: 4rem;
            }

            .related-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 2rem;
            }

            /* Product Card for Related Products */
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
                height: 200px;
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
                margin-bottom: 1rem;
            }

            .product-price {
                font-size: 1.4rem;
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

            .stock-status.stock-low {
                background: rgba(255, 193, 7, 0.1);
                color: #ffc107;
            }

            .stock-status.stock-out {
                background: rgba(220, 53, 69, 0.1);
                color: #dc3545;
            }

            .product-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: auto;
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
                text-decoration: none;
            }

            /* Mobile Responsive for Related Products */
            @media (max-width: 768px) {
                .related-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 1.5rem;
                }

                .product-image-container {
                    height: 180px;
                }
            }

            @media (max-width: 480px) {
                .related-grid {
                    grid-template-columns: 1fr;
                }
            }


            /* Tabs */
            .product-tabs {
                margin-top: 2rem;
            }

            .nav-tabs-modern {
                border: none;
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                padding: 8px;
                display: flex;
                gap: 4px;
            }

            .nav-tabs-modern .nav-link {
                border: none;
                border-radius: 8px;
                padding: 12px 24px;
                color: var(--dark-text);
                font-weight: 600;
                transition: all 0.3s ease;
                background: transparent;
            }

            .nav-tabs-modern .nav-link.active {
                background: var(--gradient-primary);
                color: white;
                box-shadow: var(--shadow-soft);
            }

            .tab-content-modern {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border-radius: 12px;
                padding: 2rem;
                margin-top: 1rem;
                box-shadow: var(--shadow-soft);
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .product-hero {
                    padding: 20px 0;
                }

                .product-gallery {
                    position: static;
                    margin-bottom: 2rem;
                }

                .main-image-container {
                    height: 300px;
                }

                .product-actions {
                    flex-direction: column;
                }

                .btn-wishlist {
                    align-self: center;
                    width: fit-content;
                }

                .feature-list {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

@section('content')
    <!-- Product Hero -->
    <section class="product-hero">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb breadcrumb-modern">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index') }}">Products</a>
                    </li>
                    @if ($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.category', $product->category->slug ?? $product->category->id) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Details -->
    <div class="container my-5">
        <div class="row g-4">
            <!-- Product Gallery -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <div class="main-image-container" id="mainImageContainer">
                        @if ($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" id="mainImage">
                        @else
                            <div class="image-placeholder">
                                <i class="bi bi-capsule"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Gallery (if multiple images) -->
                    @if ($product->image)
                        <div class="thumbnail-gallery">
                            <div class="thumbnail-item active" onclick="changeMainImage('{{ $product->image }}')">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}">
                            </div>
=                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Category Badge -->
                    @if ($product->category)
                        <div class="product-category-badge">
                            <i class="bi bi-tag-fill me-2"></i>
                            {{ $product->category->name }}
                        </div>
                    @endif

                    <!-- Product Title -->
                    <h1 class="product-title">{{ $product->name }}</h1>

                    <!-- Rating (Placeholder) -->
                    <div class="product-rating">
                        <div class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill star {{ $i <= 4 ? '' : 'empty' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-text">4.8 (127 reviews)</span>
                    </div>

                    <!-- Price -->
                    <div class="product-price">
                        <span class="current-price">${{ number_format($product->price, 2) }}</span>
                        @if ($product->price > 50)
                            <span class="original-price">${{ number_format($product->price * 1.2, 2) }}</span>
                            <span class="price-save">Save 17%</span>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div
                        class="stock-indicator {{ $product->stock_quantity < 5 ? ($product->stock_quantity == 0 ? 'stock-out' : 'stock-low') : '' }}">
                        <i class="bi bi-{{ $product->stock_quantity > 0 ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                        @if ($product->stock_quantity == 0)
                            <span>Out of Stock</span>
                        @elseif($product->stock_quantity < 5)
                            <span>Only {{ $product->stock_quantity }} left in stock - Order soon!</span>
                        @else
                            <span>✓ In Stock - Ready to ship</span>
                        @endif
                    </div>

                    <!-- Product Description -->
                    @if ($product->description)
                        <div class="product-description mb-4">
                            <p class="lead">{{ $product->description }}</p>
                        </div>
                    @endif

                    <!-- Key Features -->
                    <div class="product-features">
                        <h5 class="mb-3">
                            <i class="bi bi-shield-check me-2" style="color: var(--primary-cyan);"></i>
                            Key Features
                        </h5>
                        <ul class="feature-list">
                            <li class="feature-item">
                                <i class="bi bi-patch-check-fill"></i>
                                <span>FDA Approved</span>
                            </li>
                            <li class="feature-item">
                                <i class="bi bi-truck"></i>
                                <span>Express Delivery</span>
                            </li>
                            <li class="feature-item">
                                <i class="bi bi-shield-lock"></i>
                                <span>Secure Packaging</span>
                            </li>
                            <li class="feature-item">
                                <i class="bi bi-award"></i>
                                <span>Premium Quality</span>
                            </li>
                            <li class="feature-item">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span>30-Day Returns</span>
                            </li>
                            <li class="feature-item">
                                <i class="bi bi-headset"></i>
                                <span>24/7 Support</span>
                            </li>
                        </ul>
                    </div>

                    @if ($product->stock_quantity > 0)
                        <!-- Quantity Selector -->
                        <div class="quantity-selector">
                            <label for="quantity" class="fw-bold">Quantity:</label>
                            <div class="quantity-input">
                                <button class="qty-btn" onclick="changeQuantity(-1)" id="decreaseBtn">−</button>
                                <input type="number" class="qty-value" id="quantity" value="1" min="1"
                                    max="{{ $product->stock_quantity }}" readonly>
                                <button class="qty-btn" onclick="changeQuantity(1)" id="increaseBtn">+</button>
                            </div>
                            <small class="text-muted">Max: {{ $product->stock_quantity }}</small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="product-actions">
                            <button class="btn btn-add-to-cart" onclick="addToCartFromDetails({{ $product->id }})">
                                <i class="bi bi-cart-plus me-2"></i>
                                <span>Add to Cart</span>
                            </button>
                            <button class="btn-wishlist" onclick="toggleWishlist({{ $product->id }})">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    @else
                        <!-- Out of Stock Actions -->
                        <div class="product-actions">
                            <button class="btn btn-add-to-cart" disabled>
                                <i class="bi bi-x-circle me-2"></i>
                                Out of Stock
                            </button>
                            <button class="btn-outline-primary" onclick="notifyWhenAvailable({{ $product->id }})">
                                <i class="bi bi-bell me-2"></i>
                                Notify When Available
                            </button>
                        </div>
                    @endif

                    <!-- Shipping Info -->
                    <div class="shipping-info mt-3 p-3 bg-light rounded">
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <i class="bi bi-truck text-primary fs-4 mb-2 d-block"></i>
                                <small class="text-muted">Free shipping on orders over $75</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-clock text-primary fs-4 mb-2 d-block"></i>
                                <small class="text-muted">Delivered in 2-3 business days</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-shield-check text-primary fs-4 mb-2 d-block"></i>
                                <small class="text-muted">30-day money back guarantee</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="product-tabs">
            <ul class="nav nav-tabs nav-tabs-modern" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description" type="button" role="tab">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab"
                        data-bs-target="#specifications" type="button" role="tab">
                        Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button" role="tab">
                        Reviews (127)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                        type="button" role="tab">
                        Shipping & Returns
                    </button>
                </li>
            </ul>

            <div class="tab-content tab-content-modern" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <h5 class="mb-3">Product Description</h5>
                    <p>{{ $product->description ?: 'This premium medical product is designed to meet the highest standards of quality and safety. Manufactured using state-of-the-art technology and rigorously tested to ensure optimal performance.' }}
                    </p>

                    <h6 class="mt-4 mb-3">Benefits:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">✓ Professional-grade quality and reliability</li>
                        <li class="mb-2">✓ Meets all FDA safety and efficacy standards</li>
                        <li class="mb-2">✓ Suitable for professional and personal use</li>
                        <li class="mb-2">✓ Easy to use with clear instructions included</li>
                        <li class="mb-2">✓ Long shelf life and proper storage guidelines</li>
                    </ul>
                </div>

                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <h5 class="mb-3">Technical Specifications</h5>
                    <table class="spec-table">
                        <tr>
                            <th>Product Category</th>
                            <td>{{ $product->category->name ?? 'Medical Supplies' }}</td>
                        </tr>
                        <tr>
                            <th>Stock Keeping Unit</th>
                            <td>{{ 'SKU-' . str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <th>Availability</th>
                            <td>{{ $product->stock_quantity > 0 ? 'In Stock (' . $product->stock_quantity . ' units)' : 'Out of Stock' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Certification</th>
                            <td>FDA Approved, ISO Certified</td>
                        </tr>
                        <tr>
                            <th>Shelf Life</th>
                            <td>24 months from manufacture date</td>
                        </tr>
                        <tr>
                            <th>Storage Conditions</th>
                            <td>Store in cool, dry place away from direct sunlight</td>
                        </tr>
                    </table>
                </div>

                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <h5 class="mb-3">Customer Reviews</h5>
                    <div class="text-center py-5">
                        <i class="bi bi-star-fill text-warning" style="font-size: 3rem;"></i>
                        <h3 class="mt-3">4.8 out of 5</h3>
                        <p class="text-muted">Based on 127 customer reviews</p>
                        <button class="btn btn-outline-primary mt-3">
                            <i class="bi bi-pencil me-2"></i>Write a Review
                        </button>
                    </div>
                </div>

                <div class="tab-pane fade" id="shipping" role="tabpanel">
                    <h5 class="mb-3">Shipping & Returns</h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6><i class="bi bi-truck me-2 text-primary"></i>Shipping Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">• Free standard shipping on orders over $75</li>
                                <li class="mb-2">• Express shipping available for $9.99</li>
                                <li class="mb-2">• Same-day delivery in select cities</li>
                                <li class="mb-2">• International shipping available</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="bi bi-arrow-clockwise me-2 text-primary"></i>Return Policy</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">• 30-day money-back guarantee</li>
                                <li class="mb-2">• Free returns on defective items</li>
                                <li class="mb-2">• Items must be in original packaging</li>
                                <li class="mb-2">• Refund processed within 5-7 business days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if (isset($relatedProducts) && $relatedProducts->count() > 0)
            <section class="related-products">
                <h2 class="section-title">Related Products</h2>
                <div class="related-grid">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="product-card-modern">
                            <div class="product-image-container">
                                @if ($relatedProduct->image)
                                    <img src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}"
                                        loading="lazy">
                                @else
                                    <div class="product-placeholder">
                                        <i class="bi bi-capsule"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="product-content">
                                @if ($relatedProduct->category)
                                    <div class="product-category">{{ $relatedProduct->category->name }}</div>
                                @endif

                                <h5 class="product-title">{{ Str::limit($relatedProduct->name, 40) }}</h5>

                                @if ($relatedProduct->description)
                                    <p class="product-description">{{ Str::limit($relatedProduct->description, 60) }}</p>
                                @endif

                                <div class="product-footer">
                                    <div class="product-price">${{ number_format($relatedProduct->price, 2) }}</div>
                                    <div
                                        class="stock-status {{ $relatedProduct->stock_quantity < 5 ? ($relatedProduct->stock_quantity == 0 ? 'stock-out' : 'stock-low') : '' }}">
                                        @if ($relatedProduct->stock_quantity == 0)
                                            Out of Stock
                                        @elseif($relatedProduct->stock_quantity < 5)
                                            {{ $relatedProduct->stock_quantity }} left
                                        @else
                                            In Stock
                                        @endif
                                    </div>
                                </div>

                                <div class="product-actions">
                                    @if ($relatedProduct->stock_quantity > 0)
                                        <button class="btn btn-add-cart" onclick="addToCart({{ $relatedProduct->id }})">
                                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    @else
                                        <button class="btn btn-add-cart" disabled>
                                            <i class="bi bi-x-circle me-2"></i>Out of Stock
                                        </button>
                                    @endif
                                    <a href="{{ route('products.show', $relatedProduct->id) }}" class="btn-view-details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
@endsection

@push('scripts')
<script>
    // Quantity Management
    let currentQuantity = 1;
    const maxQuantity = {{ $product->stock_quantity }};
    
    function changeQuantity(delta) {
        const newQuantity = currentQuantity + delta;
        
        if (newQuantity >= 1 && newQuantity <= maxQuantity) {
            currentQuantity = newQuantity;
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantityInput.value = currentQuantity;
            }
            
            // Update button states
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            if (decreaseBtn) decreaseBtn.disabled = currentQuantity <= 1;
            if (increaseBtn) increaseBtn.disabled = currentQuantity >= maxQuantity;
        }
    }
    
    function addToCartFromDetails(productId) {
        const btn = event.target.closest('.btn-add-to-cart');
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
                quantity: currentQuantity 
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
                showModernToast(`${currentQuantity} item(s) added to cart! 🛒`, 'success');
                
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
    
    // Image Gallery Functions
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(thumb => {
            thumb.classList.remove('active');
        });
        if (event.target.closest('.thumbnail-item')) {
            event.target.closest('.thumbnail-item').classList.add('active');
        }
    }
    
    // Wishlist Toggle
    function toggleWishlist(productId) {
        const btn = event.target.closest('.btn-wishlist');
        if (!btn) return;
        
        const icon = btn.querySelector('i');
        if (!icon) return;
        
        if (icon.classList.contains('bi-heart')) {
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');
            btn.style.background = '#dc3545';
            btn.style.color = 'white';
            showModernToast('Added to wishlist ❤️', 'success');
        } else {
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');
            btn.style.background = '';
            btn.style.color = '';
            showModernToast('Removed from wishlist', 'success');
        }
    }
    
    // Notify When Available
    function notifyWhenAvailable(productId) {
        showModernToast('We\'ll notify you when this product is back in stock! 📧', 'success');
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
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial quantity button states
        changeQuantity(0);
        
        // Auto-focus main image on mobile
        if (window.innerWidth <= 768) {
            const mainImageContainer = document.getElementById('mainImageContainer');
            if (mainImageContainer) {
                mainImageContainer.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        }
        
        updateCartCount();
        console.log('Product details JavaScript initialized');
    });
    
    // Fallback toast function
    if (typeof showModernToast === 'undefined') {
        window.showModernToast = function(message, type) {
            console.log(`${type.toUpperCase()}: ${message}`);
        };
    }
    </script>
    
@endpush
