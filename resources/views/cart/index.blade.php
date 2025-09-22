@extends('layouts.app')

@section('title', 'Shopping Cart - MediCare')
@section('description',
    'Review your selected medical products and proceed to secure checkout. Professional healthcare
    supplies with fast delivery.')

    @push('styles')
        <style>
            /* Shopping Cart Styles */
            .cart-hero {
                padding: 40px 0;
                background: var(--gradient-soft);
                position: relative;
                overflow: hidden;
            }

            .cart-hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background:
                    radial-gradient(circle at 30% 20%, rgba(255, 139, 61, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(78, 205, 196, 0.08) 0%, transparent 50%);
                animation: float 8s ease-in-out infinite;
            }

            .page-title {
                font-size: clamp(2rem, 4vw, 3rem);
                font-weight: 800;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
            }

            /* Cart Container */
            .cart-container {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius-large);
                box-shadow: var(--shadow-soft);
                overflow: hidden;
            }

            .cart-header {
                background: var(--gradient-soft);
                padding: 2rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .cart-stats {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .stat-item {
                text-align: center;
            }

            .stat-number {
                font-size: 1.5rem;
                font-weight: 800;
                color: var(--primary-orange);
                display: block;
            }

            .stat-label {
                font-size: 0.9rem;
                color: var(--dark-text);
                opacity: 0.8;
                font-weight: 500;
            }

            /* Cart Items */
            .cart-items {
                padding: 2rem;
            }

            .cart-item {
                background: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: var(--border-radius);
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .cart-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 2px;
                background: var(--gradient-primary);
                transition: left 0.3s ease;
            }

            .cart-item:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-medium);
                border-color: rgba(255, 139, 61, 0.3);
            }

            .cart-item:hover::before {
                left: 0;
            }

            .item-image {
                width: 100px;
                height: 100px;
                border-radius: 12px;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                flex-shrink: 0;
            }

            .item-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 12px;
            }

            .item-placeholder {
                font-size: 2.5rem;
                color: var(--primary-cyan);
                opacity: 0.6;
            }

            .item-details {
                flex: 1;
                margin-left: 1.5rem;
                min-width: 0;
            }

            .item-category {
                font-size: 0.8rem;
                color: var(--primary-orange);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.3rem;
            }

            .item-name {
                font-size: 1.2rem;
                font-weight: 700;
                color: var(--dark-text);
                margin-bottom: 0.5rem;
                line-height: 1.3;
            }

            .item-description {
                color: rgba(30, 41, 59, 0.7);
                font-size: 0.9rem;
                line-height: 1.4;
                margin-bottom: 0.8rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .item-price {
                font-size: 1.3rem;
                font-weight: 800;
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Quantity Controls */
            .quantity-controls {
                display: flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.8);
                border: 2px solid rgba(255, 139, 61, 0.2);
                border-radius: 12px;
                overflow: hidden;
                margin: 1rem 0;
                width: fit-content;
                box-shadow: var(--shadow-soft);
            }

            .qty-btn {
                background: transparent;
                border: none;
                padding: 10px 12px;
                color: var(--primary-orange);
                font-size: 1.1rem;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s ease;
                min-width: 40px;
            }

            .qty-btn:hover {
                background: var(--gradient-soft);
                transform: scale(1.1);
            }

            .qty-btn:disabled {
                opacity: 0.4;
                cursor: not-allowed;
                transform: none;
            }

            .qty-display {
                padding: 10px 15px;
                border: none;
                background: transparent;
                text-align: center;
                font-weight: 600;
                color: var(--dark-text);
                min-width: 50px;
            }

            .item-actions {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-top: 1rem;
            }

            .btn-remove {
                background: rgba(220, 53, 69, 0.1);
                border: 1px solid rgba(220, 53, 69, 0.3);
                color: #dc3545;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .btn-remove:hover {
                background: #dc3545;
                color: white;
                transform: translateY(-2px);
            }

            .item-subtotal {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--dark-text);
                margin-left: auto;
            }

            /* Cart Summary */
            .cart-summary {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: var(--border-radius-large);
                padding: 2rem;
                box-shadow: var(--shadow-soft);
                position: sticky;
                top: 100px;
                height: fit-content;
            }

            .summary-title {
                font-size: 1.5rem;
                font-weight: 800;
                color: var(--dark-text);
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
            }

            .summary-title i {
                margin-right: 10px;
                color: var(--primary-cyan);
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                font-weight: 500;
            }

            .summary-row:last-child {
                border-bottom: none;
            }

            .summary-row.total {
                font-size: 1.2rem;
                font-weight: 800;
                padding: 16px 0;
                margin-top: 1rem;
                border-top: 2px solid var(--primary-orange);
                border-bottom: none;
            }

            .summary-row.total .amount {
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-size: 1.5rem;
            }

            .shipping-notice {
                background: rgba(40, 167, 69, 0.1);
                border: 1px solid rgba(40, 167, 69, 0.2);
                color: #28a745;
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                margin: 1rem 0;
                text-align: center;
            }

            .shipping-notice.warning {
                background: rgba(255, 193, 7, 0.1);
                border-color: rgba(255, 193, 7, 0.2);
                color: #ffc107;
            }

            /* Action Buttons */
            .cart-actions {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                margin-top: 2rem;
            }

            .btn-checkout {
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
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .btn-checkout:hover {
                transform: translateY(-3px);
                box-shadow: var(--shadow-strong);
                color: white;
                text-decoration: none;
            }

            .btn-continue {
                background: rgba(255, 255, 255, 0.8);
                border: 2px solid var(--primary-orange);
                color: var(--primary-orange);
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                text-align: center;
            }

            .btn-continue:hover {
                background: var(--primary-orange);
                color: white;
                transform: translateY(-2px);
                text-decoration: none;
            }

            /* Empty Cart State */
            .empty-cart {
                text-align: center;
                padding: 4rem 2rem;
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border-radius: var(--border-radius-large);
                border: 2px dashed rgba(255, 139, 61, 0.3);
                box-shadow: var(--shadow-soft);
            }

            .empty-icon {
                font-size: 5rem;
                color: var(--primary-cyan);
                margin-bottom: 1.5rem;
                opacity: 0.6;
            }

            .empty-title {
                font-size: 1.8rem;
                font-weight: 700;
                color: var(--dark-text);
                margin-bottom: 1rem;
            }

            .empty-description {
                color: rgba(30, 41, 59, 0.7);
                font-size: 1.1rem;
                margin-bottom: 2rem;
                max-width: 400px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Recommended Products */
            .recommended-products {
                margin-top: 3rem;
            }

            .recommended-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            /* Loading States */
            .loading-overlay {
                position: absolute;
                inset: 0;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(5px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10;
                border-radius: var(--border-radius);
            }

            .loading-spinner {
                width: 40px;
                height: 40px;
                border: 4px solid rgba(255, 139, 61, 0.2);
                border-top: 4px solid var(--primary-orange);
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            .custom-btn-primary {
                background: linear-gradient(135deg, #FF8B3D 0%, #4ECDC4 100%);
                border: none;
                color: white;
                border-radius: 12px;
                font-weight: 600;
                padding: 16px 32px;
                transition: all 0.3s ease;
            }

            .custom-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(255, 139, 61, 0.3);
                color: white;
            }

            .custom-btn-outline {
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid #FF8B3D;
                color: #FF8B3D;
                border-radius: 12px;
                font-weight: 600;
                padding: 14px 30px;
                transition: all 0.3s ease;
            }

            .custom-btn-outline:hover {
                background: #FF8B3D;
                color: white;
                transform: translateY(-2px);
            }


            /* Mobile Responsive */
            @media (max-width: 768px) {
                .cart-hero {
                    padding: 30px 0;
                    text-align: center;
                }

                .cart-item {
                    flex-direction: column;
                    text-align: center;
                }

                .item-details {
                    margin-left: 0;
                    margin-top: 1rem;
                }

                .item-actions {
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .cart-summary {
                    position: static;
                    margin-top: 2rem;
                }

                .cart-stats {
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .recommended-grid {
                    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                }
            }

            /* Animations */
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .cart-item {
                animation: slideIn 0.3s ease forwards;
            }

            .fade-out {
                animation: fadeOut 0.3s ease forwards;
            }

            @keyframes fadeOut {
                to {
                    opacity: 0;
                    transform: translateX(-20px);
                }
            }
        </style>
    @endpush

@section('content')
    <!-- Cart Hero -->
    <section class="cart-hero">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="page-title">Shopping Cart</h1>
                    <p class="lead mb-0">Review your selected medical products and proceed to secure checkout</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div id="cartContent">
            @if (session('cart') && count(session('cart')) > 0)
                <div class="row g-4">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <div class="cart-container">
                            <!-- Cart Header with Stats -->
                            <div class="cart-header">
                                <div class="cart-stats">
                                    <div class="stat-item">
                                        <span class="stat-number" id="itemCount">
                                            {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
                                        </span>
                                        <div class="stat-label">Items in Cart</div>
                                    </div>
                                    <div class="stat-item">
                                 
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number">
                                            {{ session('cart') ? count(array_unique(array_column(session('cart'), 'category'))) : 0 }}
                                        </span>
                                        <div class="stat-label">Categories</div>
                                    </div>
                                </div>
                            </div>


                            <!-- Cart Items List -->
                            <div class="cart-items">
                                @foreach (session('cart', []) as $id => $details)
                                    <div class="cart-item d-flex" data-id="{{ $id }}">
                                        <div class="item-image">
                                            @if (isset($details['image']) && $details['image'])
                                                <img src="{{ $details['image'] }}"
                                                    alt="{{ $details['name'] ?? 'Product' }}">
                                            @else
                                                <div class="item-placeholder">
                                                    <i class="bi bi-capsule"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="item-details">
                                            @if (isset($details['category']))
                                                <div class="item-category">{{ $details['category'] }}</div>
                                            @endif
                                            <h4 class="item-name">{{ $details['name'] ?? 'Unknown Product' }}</h4>
                                            @if (isset($details['description']) && $details['description'])
                                                <p class="item-description">{{ Str::limit($details['description'], 100) }}
                                                </p>
                                            @endif
                                            <div class="item-price">${{ number_format($details['price'] ?? 0, 2) }} each
                                            </div>

                                            <div class="item-actions">
                                                <!-- Quantity Controls -->
                                                <div class="quantity-controls">
                                                    <button class="qty-btn"
                                                        onclick="updateQuantity({{ $id }}, -1)"
                                                        {{ ($details['quantity'] ?? 1) <= 1 ? 'disabled' : '' }}>
                                                        −
                                                    </button>
                                                    <input type="number" class="qty-display"
                                                        value="{{ $details['quantity'] ?? 1 }}" min="1" readonly>
                                                    <button class="qty-btn"
                                                        onclick="updateQuantity({{ $id }}, 1)">
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Remove Button -->
                                                <button class="btn-remove" onclick="removeFromCart({{ $id }})">
                                                    <i class="bi bi-trash me-1"></i>Remove
                                                </button>

                                                <!-- Item Subtotal -->
                                                <div class="item-subtotal">
                                                    ${{ number_format(($details['price'] ?? 0) * ($details['quantity'] ?? 1), 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h3 class="summary-title">
                                <i class="bi bi-calculator"></i>
                                Order Summary
                            </h3>

                            <div class="summary-row">
                                <span>Subtotal ({{ array_sum(array_column(session('cart'), 'quantity')) }} items)</span>
                                <span class="amount"
                                    id="subtotalAmount">${{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, session('cart'))),2) }}</span>
                            </div>

                            @php
                                $cart = session('cart', []);
                                $subtotal = 0;
                                foreach ($cart as $item) {
                                    if (isset($item['price']) && isset($item['quantity'])) {
                                        $subtotal += $item['price'] * $item['quantity'];
                                    }
                                }
                                $shipping = $subtotal >= 75 ? 0 : 9.99;
                                $tax = $subtotal * 0.08;
                                $total = $subtotal + $shipping + $tax;
                            @endphp


                            <div class="summary-row">
                                <span>Shipping</span>
                                <span class="amount" id="shippingAmount">
                                    @if ($subtotal >= 75)
                                        <span class="text-success">FREE</span>
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>

                            <div class="summary-row">
                                <span>Estimated Tax</span>
                                <span class="amount" id="taxAmount">${{ number_format($tax, 2) }}</span>
                            </div>

                            <div class="summary-row total">
                                <span>Total</span>
                                <span class="amount" id="totalAmount">${{ number_format($total, 2) }}</span>
                            </div>

                            <!-- Shipping Notice -->
                            @if ($subtotal >= 75)
                                <div class="shipping-notice">
                                    <i class="bi bi-truck me-2"></i>
                                    Congratulations! You qualify for FREE shipping
                                </div>
                            @else
                                <div class="shipping-notice warning">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Add ${{ number_format(75 - $subtotal, 2) }} more for FREE shipping
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="cart-actions">
                                <a href="{{ route('checkout') }}" class="btn-checkout">
                                    <i class="bi bi-lock-fill"></i>
                                    Proceed to Secure Checkout
                                </a>
                                <a href="{{ route('products.index') }}" class="btn-continue">
                                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                                </a>
                            </div>

                            <!-- Trust Indicators -->
                            <div class="trust-indicators mt-3 pt-3 border-top">
                                <div class="row text-center g-2">
                                    <div class="col-4">
                                        <i class="bi bi-shield-check text-success fs-4"></i>
                                        <small class="d-block text-muted">Secure Payment</small>
                                    </div>
                                    <div class="col-4">
                                        <i class="bi bi-truck text-primary fs-4"></i>
                                        <small class="d-block text-muted">Fast Delivery</small>
                                    </div>
                                    <div class="col-4">
                                        <i class="bi bi-arrow-clockwise text-info fs-4"></i>
                                        <small class="d-block text-muted">Easy Returns</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="empty-cart">
                    <div class="empty-icon">
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <h2 class="empty-title">Your Cart is Empty</h2>
                    <p class="empty-description">
                        Looks like you haven't added any medical products to your cart yet.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('products.index') }}" class="btn btn-lg custom-btn-primary">
                            <i class="bi bi-grid me-2"></i>Browse Products
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-lg custom-btn-outline">
                            <i class="bi bi-house me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
<script>
    // Update cart quantity
    function updateQuantity(productId, change) {
        const cartItem = document.querySelector(`[data-id="${productId}"]`);
        if (!cartItem) return;
        
        const qtyDisplay = cartItem.querySelector('.qty-display');
        const currentQty = parseInt(qtyDisplay.value) || 1;
        const newQty = currentQty + change;
        
        if (newQty < 1) return;
        
        // Show loading
        cartItem.style.position = 'relative';
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
        cartItem.appendChild(loadingOverlay);
        
        // Make the request
        fetch(`/cart/update/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Update response:', data); // Debug log
            
            if (data.success) {
                // Update quantity display
                qtyDisplay.value = newQty;
                
                // Update button states
                const decreaseBtn = cartItem.querySelector('.qty-btn:first-child');
                if (decreaseBtn) {
                    decreaseBtn.disabled = newQty <= 1;
                }
                
                // Update item subtotal
                const priceText = cartItem.querySelector('.item-price').textContent;
                const price = parseFloat(priceText.replace('$', '').replace(' each', '')) || 0;
                const subtotalElement = cartItem.querySelector('.item-subtotal');
                if (subtotalElement) {
                    subtotalElement.textContent = '$' + (price * newQty).toFixed(2);
                }
                
                // Update cart totals
                updateCartTotals();
                updateCartCount();
                
                // Show success message
                if (typeof showModernToast === 'function') {
                    showModernToast('Cart updated successfully! 🛒', 'success');
                } else {
                    console.log('Cart updated successfully!');
                }
            } else {
                throw new Error(data.message || 'Failed to update cart');
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            
            // Reset quantity display on error
            qtyDisplay.value = currentQty;
            
            const errorMessage = error.message || 'Something went wrong. Please try again.';
            if (typeof showModernToast === 'function') {
                showModernToast(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        })
        .finally(() => {
            // Remove loading overlay
            if (loadingOverlay && loadingOverlay.parentNode) {
                loadingOverlay.parentNode.removeChild(loadingOverlay);
            }
        });
    }
    
    // Remove item from cart
    function removeFromCart(productId) {
        const cartItem = document.querySelector(`[data-id="${productId}"]`);
        if (!cartItem) return;
        
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
        }
        
        // Add fade out animation
        cartItem.classList.add('fade-out');
        
        fetch(`/cart/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Remove response:', data); // Debug log
            
            if (data.success) {
                // Remove item after animation
                setTimeout(() => {
                    if (cartItem.parentNode) {
                        cartItem.parentNode.removeChild(cartItem);
                    }
                    updateCartTotals();
                    updateCartCount();
                    
                    // Check if cart is empty
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload(); // Reload to show empty cart state
                    }
                }, 300);
                
                if (typeof showModernToast === 'function') {
                    showModernToast('Item removed from cart! 🗑️', 'success');
                } else {
                    console.log('Item removed from cart!');
                }
            } else {
                throw new Error(data.message || 'Failed to remove item');
            }
        })
        .catch(error => {
            console.error('Error removing item:', error);
            cartItem.classList.remove('fade-out');
            
            const errorMessage = error.message || 'Something went wrong. Please try again.';
            if (typeof showModernToast === 'function') {
                showModernToast(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        });
    }
    
    // Add to cart (for recommended products)
    function addToCart(productId) {
        const btn = event.target.closest('button');
        if (!btn) return;
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<div class="loading-spinner"></div> Adding...';
        btn.disabled = true;
    
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Add to cart response:', data); // Debug log
            
            if (data.success) {
                updateCartCount();
                
                // Visual feedback
                btn.style.background = '#28a745';
                btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Added!';
                
                setTimeout(() => {
                    btn.style.background = '';
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
                
                if (typeof showModernToast === 'function') {
                    showModernToast(`Item added to cart! 🛒`, 'success');
                } else {
                    console.log('Item added to cart!');
                }
            } else {
                throw new Error(data.message || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            
            const errorMessage = error.message || 'Something went wrong. Please try again.';
            if (typeof showModernToast === 'function') {
                showModernToast(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    
    // Update cart totals
    function updateCartTotals() {
        let subtotal = 0;
        let itemCount = 0;
        
        document.querySelectorAll('.cart-item').forEach(item => {
            const priceText = item.querySelector('.item-price')?.textContent || '$0.00 each';
            const price = parseFloat(priceText.replace('$', '').replace(' each', '')) || 0;
            const qtyInput = item.querySelector('.qty-display');
            const quantity = parseInt(qtyInput?.value || '0') || 0;
            
            subtotal += price * quantity;
            itemCount += quantity;
        });
        
        const shipping = subtotal >= 75 ? 0 : 9.99;
        const tax = subtotal * 0.08;
        const total = subtotal + shipping + tax;
        
        // Update display elements safely
        const itemCountEl = document.getElementById('itemCount');
        const subtotalEl = document.getElementById('subtotalAmount');
        const taxEl = document.getElementById('taxAmount');
        const totalEl = document.getElementById('totalAmount');
        const shippingEl = document.getElementById('shippingAmount');
        
        if (itemCountEl) itemCountEl.textContent = itemCount;
        if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toFixed(2);
        if (taxEl) taxEl.textContent = '$' + tax.toFixed(2);
        if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
        
        if (shippingEl) {
            if (subtotal >= 75) {
                shippingEl.innerHTML = '<span class="text-success">FREE</span>';
            } else {
                shippingEl.textContent = '$' + shipping.toFixed(2);
            }
        }
        
        // Update shipping notice
        const shippingNotice = document.querySelector('.shipping-notice');
        if (shippingNotice) {
            if (subtotal >= 75) {
                shippingNotice.className = 'shipping-notice';
                shippingNotice.innerHTML = '<i class="bi bi-truck me-2"></i>Congratulations! You qualify for FREE shipping';
            } else {
                shippingNotice.className = 'shipping-notice warning';
                const remaining = (75 - subtotal).toFixed(2);
                shippingNotice.innerHTML = `<i class="bi bi-info-circle me-2"></i>Add $${remaining} more for FREE shipping`;
            }
        }
    }
    
    // Update cart count in header
    function updateCartCount() {
        fetch('/cart/count', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count, #cartCount');
            cartCountElements.forEach(el => {
                if (el) el.textContent = data.count || 0;
            });
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
    }
    
    // Clear entire cart
    function clearCart() {
        if (!confirm('Are you sure you want to clear your entire cart?')) {
            return;
        }
        
        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
                if (typeof showModernToast === 'function') {
                    showModernToast('Cart cleared successfully! 🧹', 'success');
                }
            } else {
                throw new Error(data.message || 'Failed to clear cart');
            }
        })
        .catch(error => {
            console.error('Error clearing cart:', error);
            const errorMessage = error.message || 'Something went wrong. Please try again.';
            if (typeof showModernToast === 'function') {
                showModernToast(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        });
    }
    
    // Initialize cart functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Update cart count on page load
        updateCartCount();
        
        // Add smooth animations to existing items
        document.querySelectorAll('.cart-item').forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
        });
        
        console.log('Cart functionality initialized');
    });
    
    // Fallback toast function if showModernToast doesn't exist
    if (typeof showModernToast === 'undefined') {
        window.showModernToast = function(message, type) {
            console.log(`${type.toUpperCase()}: ${message}`);
            // You can replace this with a simple alert if needed
            // alert(message);
        };
    }
    </script>
    
@endpush
