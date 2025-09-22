@extends('layouts.app')

@section('title', 'Secure Checkout - MediCare')
@section('description', 'Complete your medical supplies order with our secure, encrypted checkout process. Fast, safe, and reliable healthcare delivery.')

@push('styles')
<style>
    /* Checkout Styles */
    .checkout-hero {
        padding: 40px 0;
        background: var(--gradient-soft);
        position: relative;
        overflow: hidden;
    }

    .checkout-hero::before {
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

    /* Checkout Progress Stepper */
    .checkout-stepper {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
    }

    .stepper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .stepper::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, var(--primary-orange) 0%, var(--primary-orange) 33%, #e9ecef 33%, #e9ecef 100%);
        z-index: 1;
        transition: all 0.5s ease;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        background: white;
        padding: 0 1rem;
        min-width: 120px;
    }

    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        border: 3px solid #e9ecef;
        background: white;
        color: #6c757d;
    }

    .step.active .step-number {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(255, 139, 61, 0.2);
    }

    .step.completed .step-number {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }

    .step-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6c757d;
        text-align: center;
    }

    .step.active .step-label {
        color: var(--primary-orange);
    }

    .step.completed .step-label {
        color: #28a745;
    }

    /* Form Container */
    .checkout-form {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius-large);
        padding: 2.5rem;
        box-shadow: var(--shadow-soft);
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--gradient-soft);
    }

    .section-title i {
        margin-right: 10px;
        color: var(--primary-cyan);
        font-size: 1.2rem;
    }

    /* Modern Form Inputs */
    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-floating .form-control {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 1rem 1rem 1rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        min-height: calc(3.5rem + 2px);
    }

    .form-floating .form-control:focus {
        background: rgba(255, 255, 255, 0.95);
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 0.2rem rgba(255, 139, 61, 0.25);
        transform: translateY(-2px);
    }

    .form-floating .form-control:focus + label,
    .form-floating .form-control:not(:placeholder-shown) + label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        color: var(--primary-orange);
    }

    .form-floating label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem 1rem;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
        color: rgba(30, 41, 59, 0.7);
        font-weight: 500;
    }

    /* Checkbox and Radio Styling */
    .form-check {
        margin-bottom: 1rem;
        padding-left: 2rem;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-left: -2rem;
        border: 2px solid rgba(255, 139, 61, 0.3);
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background: var(--gradient-primary);
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 0.2rem rgba(255, 139, 61, 0.25);
    }

    .form-check-label {
        font-weight: 500;
        color: var(--dark-text);
        margin-left: 0.5rem;
    }

    /* Payment Method Cards */
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .payment-method {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .payment-method::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 2px;
        background: var(--gradient-primary);
        transition: left 0.3s ease;
    }

    .payment-method:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-medium);
        border-color: rgba(255, 139, 61, 0.4);
    }

    .payment-method:hover::before {
        left: 0;
    }

    .payment-method.selected {
        border-color: var(--primary-orange);
        background: rgba(255, 139, 61, 0.1);
        transform: translateY(-4px);
        box-shadow: var(--shadow-medium);
    }

    .payment-method.selected::before {
        left: 0;
    }

    .payment-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary-cyan);
    }

    .payment-method.selected .payment-icon {
        color: var(--primary-orange);
    }

    .payment-name {
        font-weight: 600;
        color: var(--dark-text);
    }

    /* Order Summary Sidebar */
    .order-summary {
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

    .summary-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .summary-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-soft);
    }

    .item-image-small {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .item-image-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-placeholder-small {
        color: var(--primary-cyan);
        font-size: 1.5rem;
        opacity: 0.6;
    }

    .item-details-small {
        flex: 1;
        min-width: 0;
    }

    .item-name-small {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
        line-height: 1.3;
    }

    .item-quantity-price {
        font-size: 0.85rem;
        color: rgba(30, 41, 59, 0.7);
    }

    .item-subtotal-small {
        font-weight: 700;
        color: var(--primary-orange);
        font-size: 0.9rem;
    }

    /* Summary Totals */
    .summary-totals {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-weight: 500;
    }

    .summary-row.total {
        font-size: 1.2rem;
        font-weight: 800;
        padding: 16px 0;
        margin-top: 0.5rem;
        border-top: 2px solid var(--primary-orange);
        color: var(--dark-text);
    }

    .summary-row.total .amount {
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.4rem;
    }

    /* Security Badges */
    .security-badges {
        display: flex;
        justify-content: space-around;
        padding: 1.5rem;
        background: rgba(40, 167, 69, 0.1);
        border-radius: 12px;
        margin: 2rem 0;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .security-badge {
        text-align: center;
        color: #28a745;
    }

    .security-badge i {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .security-badge small {
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* Action Buttons */
    .checkout-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(255, 139, 61, 0.3);
        color: var(--primary-orange);
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255, 139, 61, 0.1);
        border-color: var(--primary-orange);
        transform: translateY(-2px);
        text-decoration: none;
        color: var(--primary-orange);
    }

    .btn-place-order {
        background: var(--gradient-primary);
        border: none;
        color: white;
        padding: 16px 32px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-width: 200px;
    }

    .btn-place-order:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-strong);
        color: white;
    }

    .btn-place-order:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .checkout-hero {
            padding: 30px 0;
            text-align: center;
        }
        
        .stepper {
            flex-direction: column;
            gap: 1rem;
        }
        
        .stepper::before {
            display: none;
        }
        
        .checkout-form {
            padding: 1.5rem;
        }
        
        .order-summary {
            position: static;
            margin-top: 2rem;
        }
        
        .checkout-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .payment-methods {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<!-- Checkout Hero -->
<section class="checkout-hero">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="page-title">Secure Checkout</h1>
                <p class="lead mb-0">Complete your medical supplies order with our encrypted, secure checkout process</p>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
    <!-- Checkout Progress Stepper -->
    <div class="checkout-stepper">
        <div class="stepper">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">Shipping Info</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label">Payment Method</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Order Review</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <form id="checkoutForm" class="checkout-form">
                @csrf
                
                <!-- Step 1: Shipping Information -->
                <div class="checkout-step" id="step1">
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-person"></i>
                            Personal Information
                        </h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="firstName" name="first_name" placeholder="First Name" required>
                                    <label for="firstName">First Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Last Name" required>
                                    <label for="lastName">Last Name *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                    <label for="email">Email Address *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
                                    <label for="phone">Phone Number *</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-geo-alt"></i>
                            Shipping Address
                        </h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Street Address" required>
                                    <label for="address">Street Address *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                                    <label for="city">City *</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-control" id="state" name="state" required>
                                        <option value="">Choose...</option>
                                        <option value="AL">Alabama</option>
                                        <option value="CA">California</option>
                                        <option value="FL">Florida</option>
                                        <option value="NY">New York</option>
                                        <option value="TX">Texas</option>
                                        <!-- Add more states -->
                                    </select>
                                    <label for="state">State *</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="zipCode" name="zip_code" placeholder="ZIP Code" required>
                                    <label for="zipCode">ZIP Code *</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Options -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-truck"></i>
                            Shipping Method
                        </h3>
                        <div class="shipping-options">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_method" id="standardShipping" value="standard" checked>
                                <label class="form-check-label" for="standardShipping">
                                    <strong>Standard Shipping (5-7 business days)</strong> - FREE for orders over $75
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_method" id="expressShipping" value="express">
                                <label class="form-check-label" for="expressShipping">
                                    <strong>Express Shipping (2-3 business days)</strong> - $9.99
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_method" id="overnightShipping" value="overnight">
                                <label class="form-check-label" for="overnightShipping">
                                    <strong>Overnight Delivery</strong> - $19.99
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Continue Button -->
                    <div class="text-end">
                        <button type="button" class="btn btn-primary-modern btn-lg" onclick="nextStep(2)">
                            Continue to Payment <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Payment Method -->
                <div class="checkout-step d-none" id="step2">
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-credit-card"></i>
                            Payment Method
                        </h3>
                        
                        <!-- Payment Method Selection -->
                        <div class="payment-methods">
                            <div class="payment-method selected" data-method="card">
                                <i class="bi bi-credit-card payment-icon"></i>
                                <div class="payment-name">Credit Card</div>
                            </div>
                            <div class="payment-method" data-method="paypal">
                                <i class="bi bi-paypal payment-icon"></i>
                                <div class="payment-name">PayPal</div>
                            </div>
                            <div class="payment-method" data-method="apple">
                                <i class="bi bi-apple payment-icon"></i>
                                <div class="payment-name">Apple Pay</div>
                            </div>
                            <div class="payment-method" data-method="google">
                                <i class="bi bi-google payment-icon"></i>
                                <div class="payment-name">Google Pay</div>
                            </div>
                        </div>

                        <!-- Credit Card Form -->
                        <div id="cardPaymentForm">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="cardNumber" name="card_number" placeholder="Card Number" maxlength="19">
                                        <label for="cardNumber">Card Number *</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="cardName" name="card_name" placeholder="Name on Card">
                                        <label for="cardName">Name on Card *</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="cardExpiry" name="card_expiry" placeholder="MM/YY" maxlength="5">
                                        <label for="cardExpiry">MM/YY *</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="cardCvc" name="card_cvc" placeholder="CVC" maxlength="4">
                                        <label for="cardCvc">CVC *</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badges -->
                    <div class="security-badges">
                        <div class="security-badge">
                            <i class="bi bi-shield-lock-fill"></i>
                            <small>SSL Encrypted</small>
                        </div>
                        <div class="security-badge">
                            <i class="bi bi-patch-check-fill"></i>
                            <small>PCI Compliant</small>
                        </div>
                        <div class="security-badge">
                            <i class="bi bi-bank"></i>
                            <small>Bank Level Security</small>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="checkout-actions">
                        <button type="button" class="btn-back" onclick="nextStep(1)">
                            <i class="bi bi-arrow-left me-2"></i>Back to Shipping
                        </button>
                        <button type="button" class="btn btn-primary-modern btn-lg" onclick="nextStep(3)">
                            Review Order <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Order Review -->
                <div class="checkout-step d-none" id="step3">
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-clipboard-check"></i>
                            Review Your Order
                        </h3>
                        
                        <!-- Order Summary Display -->
                        <div class="order-review-summary" id="orderReviewSummary">
                            <!-- Will be populated via JavaScript -->
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="agreeTerms" name="agree_terms" required>
                            <label class="form-check-label" for="agreeTerms">
                                I agree to the <a href="#" class="text-decoration-none">Terms & Conditions</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Final Action Buttons -->
                    <div class="checkout-actions">
                        <button type="button" class="btn-back" onclick="nextStep(2)">
                            <i class="bi bi-arrow-left me-2"></i>Back to Payment
                        </button>
                        <button type="submit" class="btn-place-order" id="placeOrderBtn">
                            <i class="bi bi-lock-fill me-2"></i>
                            Place Secure Order
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <div class="order-summary">
                <h3 class="summary-title">
                    <i class="bi bi-receipt"></i>
                    Order Summary
                </h3>

                <!-- Cart Items -->
                <div class="summary-items">
                    @if(session('cart') && count(session('cart')) > 0)
                        @foreach(session('cart') as $id => $details)
                            <div class="summary-item">
                                <div class="item-image-small">
                                    @if($details['image'])
                                        <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}">
                                    @else
                                        <i class="bi bi-capsule item-placeholder-small"></i>
                                    @endif
                                </div>
                                <div class="item-details-small">
                                    <div class="item-name-small">{{ Str::limit($details['name'], 25) }}</div>
                                    <div class="item-quantity-price">Qty: {{ $details['quantity'] }} × ${{ number_format($details['price'], 2) }}</div>
                                </div>
                                <div class="item-subtotal-small">
                                    ${{ number_format($details['price'] * $details['quantity'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Totals -->
                <div class="summary-totals">
                    @php
                        $subtotal = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart', [])));
                        $shipping = $subtotal >= 75 ? 0 : 9.99;
                        $tax = $subtotal * 0.08;
                        $total = $subtotal + $shipping + $tax;
                    @endphp

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="amount" id="summarySubtotal">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="amount" id="summaryShipping">
                            @if($subtotal >= 75)
                                <span class="text-success">FREE</span>
                            @else
                                ${{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span class="amount" id="summaryTax">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="amount" id="summaryTotal">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="trust-indicators mt-4 pt-3 border-top">
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <i class="bi bi-shield-check text-success fs-5"></i>
                            <small class="d-block text-muted">Secure Checkout</small>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-truck text-primary fs-5"></i>
                            <small class="d-block text-muted">Fast Shipping</small>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-arrow-clockwise text-info fs-5"></i>
                            <small class="d-block text-muted">Easy Returns</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;

// Step Navigation
function nextStep(step) {
    if (step < currentStep) {
        // Going back - allow without validation
        showStep(step);
        return;
    }

    if (validateStep(currentStep)) {
        showStep(step);
    }
}

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.checkout-step').forEach(el => {
        el.classList.add('d-none');
    });

    // Show target step
    document.getElementById(`step${step}`).classList.remove('d-none');
    document.getElementById(`step${step}`).classList.add('fade-in');

    // Update stepper
    document.querySelectorAll('.step').forEach((el, index) => {
        el.classList.remove('active', 'completed');
        if (index + 1 < step) {
            el.classList.add('completed');
        } else if (index + 1 === step) {
            el.classList.add('active');
        }
    });

    // Update stepper progress line
    const progressPercent = ((step - 1) / 2) * 100;
    const stepperLine = document.querySelector('.stepper::before');
    if (stepperLine) {
        stepperLine.style.background = `linear-gradient(to right, var(--primary-orange) 0%, var(--primary-orange) ${progressPercent}%, #e9ecef ${progressPercent}%, #e9ecef 100%)`;
    }

    currentStep = step;

    // Scroll to top of form
    document.querySelector('.checkout-form').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

// Form Validation
function validateStep(step) {
    let isValid = true;

    if (step === 1) {
        // Validate personal info and shipping
        const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipCode'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Email validation
        const email = document.getElementById('email');
        if (email.value && !isValidEmail(email.value)) {
            email.classList.add('is-invalid');
            isValid = false;
        }
    } else if (step === 2) {
        // Validate payment method
        const selectedMethod = document.querySelector('.payment-method.selected');
        if (selectedMethod && selectedMethod.dataset.method === 'card') {
            const cardFields = ['cardNumber', 'cardName', 'cardExpiry', 'cardCvc'];
            cardFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        }
    } else if (step === 3) {
        // Validate terms agreement
        const agreeTerms = document.getElementById('agreeTerms');
        if (!agreeTerms.checked) {
            showModernToast('Please agree to the Terms & Conditions to continue.', 'error');
            isValid = false;
        }
    }

    if (!isValid) {
        showModernToast('Please fill in all required fields correctly.', 'error');
    }

    return isValid;
}

// Email validation helper
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Payment method selection
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(m => {
            m.classList.remove('selected');
        });
        this.classList.add('selected');

        // Show/hide card form
        const cardForm = document.getElementById('cardPaymentForm');
        if (this.dataset.method === 'card') {
            cardForm.style.display = 'block';
        } else {
            cardForm.style.display = 'none';
        }
    });
});

// Card number formatting
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) formattedValue += ' ';
        formattedValue += value[i];
    }
    e.target.value = formattedValue;
});

// Card expiry formatting
document.getElementById('cardExpiry').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});

// Form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!validateStep(3)) return;

    const submitBtn = document.getElementById('placeOrderBtn');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<div class="loading-spinner"></div> Processing...';
    submitBtn.disabled = true;

    // Simulate order processing
    setTimeout(() => {
        // Redirect to order success page
        window.location.href = '{{ route("order.success", ":orderId") }}'.replace(':orderId', 'temp-order-id');
    }, 2000);
});

// Initialize checkout
document.addEventListener('DOMContentLoaded', function() {
    // Check if cart is empty
    @if(!session('cart') || count(session('cart')) === 0)
        window.location.href = '{{ route("cart.index") }}';
    @endif
});
</script>
@endpush
