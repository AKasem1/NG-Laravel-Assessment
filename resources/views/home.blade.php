@extends('layouts.app')

@section('title', 'MediCare - Future of Healthcare Technology')
@section('description', 'Revolutionary medical supplies and healthcare solutions. Premium products, AI-powered recommendations, and next-generation healthcare technology delivered to your door.')

@push('styles')
<style>
    /* Hero Section with Parallax */
    .hero-modern {
        min-height: 100vh;
        background: var(--gradient-primary);
        position: relative;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(1deg); }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #FFFFFF 0%, rgba(255, 255, 255, 0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: slideInUp 1s ease-out;
    }

    .hero-subtitle {
        font-size: clamp(1.1rem, 2vw, 1.4rem);
        opacity: 0.95;
        margin-bottom: 2rem;
        font-weight: 500;
        animation: slideInUp 1s ease-out 0.2s both;
    }

    .hero-cta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        animation: slideInUp 1s ease-out 0.4s both;
    }

    .btn-hero {
        padding: 16px 32px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-hero-primary {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-hero-primary:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-4px);
        box-shadow: var(--shadow-strong);
    }

    .btn-hero-outline {
        background: transparent;
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
    }

    .btn-hero-outline::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transition: left 0.4s ease;
        z-index: -1;
    }

    .btn-hero-outline:hover {
        transform: translateY(-4px);
        border-color: white;
    }

    .btn-hero-outline:hover::before {
        left: 0;
    }

    /* Animated Stats */
    .stats-modern {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        margin-top: -80px;
        position: relative;
        z-index: 10;
        border-radius: var(--border-radius-large);
        box-shadow: var(--shadow-strong);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-item {
        text-align: center;
        padding: 2rem 1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
        line-height: 1;
    }

    .stat-label {
        font-size: 1rem;
        color: var(--dark-text);
        opacity: 0.8;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    /* Features Section */
    .features-modern {
        padding: 100px 0;
        position: relative;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        padding: 2.5rem;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: translateX(-100%);
        transition: transform 0.4s ease;
    }

    .feature-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-strong);
    }

    .feature-card:hover::before {
        transform: translateX(0);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-soft);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-orange);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        background: var(--gradient-primary);
        color: white;
        transform: scale(1.1) rotate(5deg);
    }

    /* Products Showcase */
    .products-showcase {
        padding: 100px 0;
        background: var(--gradient-soft);
    }

    .section-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        text-align: center;
        margin-bottom: 1rem;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .section-subtitle {
        text-align: center;
        font-size: 1.2rem;
        opacity: 0.8;
        margin-bottom: 4rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Category Cards */
    .category-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        padding: 0;
        height: 280px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .category-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .category-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-strong);
    }

    .category-card:hover::after {
        opacity: 0.9;
    }

    .category-image {
        height: 160px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-cyan);
        transition: all 0.4s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.1);
    }

    .category-content {
        padding: 1.5rem;
        position: relative;
        z-index: 2;
        transition: all 0.4s ease;
    }

    .category-card:hover .category-content {
        color: white;
    }

    .category-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .category-desc {
        opacity: 0.8;
        font-size: 0.95rem;
    }

    /* Trust Section */
    .trust-section {
        padding: 80px 0;
        background: rgba(30, 41, 59, 0.95);
        color: white;
        position: relative;
    }

    .trust-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--gradient-primary);
    }

    .trust-badge {
        text-align: center;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--border-radius);
        transition: all 0.3s ease;
        height: 100%;
    }

    .trust-badge:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-5px);
    }

    .trust-icon {
        font-size: 3rem;
        color: var(--primary-cyan);
        margin-bottom: 1rem;
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.6s ease;
    }

    .animate-on-scroll.animate {
        opacity: 1;
        transform: translateY(0);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .hero-cta {
            flex-direction: column;
        }
        
        .btn-hero {
            text-align: center;
        }
        
        .stats-modern {
            margin-top: -40px;
        }
        
        .feature-card,
        .category-card {
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-modern">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h1 class="hero-title">
                        The Future of
                        <span style="color: #4ECDC4;">Healthcare</span>
                        is Here
                    </h1>
                    <p class="hero-subtitle">
                        Experience next-generation medical solutions with AI-powered recommendations, 
                        premium quality products, and lightning-fast delivery. Your health deserves the best technology.
                    </p>
                    <div class="hero-cta">
                        <a href="{{ route('home') }}" class="btn btn-hero btn-hero-primary">                            <i class="bi bi-rocket-takeoff me-2"></i>
                            Explore Products
                        </a>
                        <a href="#features" class="btn btn-hero btn-hero-outline">
                            <i class="bi bi-play-circle me-2"></i>
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="text-center">
                    <div class="position-relative">
                        <div style="width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                            <i class="bi bi-heart-pulse" style="font-size: 8rem; color: rgba(255,255,255,0.8);"></i>
                        </div>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <div style="width: 100px; height: 100px; background: var(--primary-cyan); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: float 3s ease-in-out infinite;">
                                <i class="bi bi-shield-check" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="container my-5">
    <div class="stats-modern">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="15000">0</span>
                    <div class="stat-label">Premium Products</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="98">0</span>
                    <div class="stat-label">Customer Satisfaction</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="50">0</span>
                    <div class="stat-label">Countries Served</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="24">0</span>
                    <div class="stat-label">Hour Support</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-modern" id="features">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title animate-on-scroll">Why Choose MediCare?</h2>
                <p class="section-subtitle animate-on-scroll">
                    Revolutionary features that set us apart in the healthcare industry
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h4 class="mb-3">AI-Powered Recommendations</h4>
                    <p class="mb-0">Smart algorithms analyze your health needs and recommend the perfect products for your specific requirements.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h4 class="mb-3">Lightning Fast Delivery</h4>
                    <p class="mb-0">Express delivery within 24 hours for urgent medical supplies. Emergency orders processed instantly.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="mb-3">FDA Certified Quality</h4>
                    <p class="mb-0">All products are FDA certified and sourced from verified manufacturers ensuring the highest quality standards.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h4 class="mb-3">Expert Support 24/7</h4>
                    <p class="mb-0">Round-the-clock support from medical professionals and pharmacists for all your healthcare questions.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h4 class="mb-3">Smart Mobile App</h4>
                    <p class="mb-0">Advanced mobile app with prescription scanning, automatic refills, and health tracking capabilities.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h4 class="mb-3">Global Network</h4>
                    <p class="mb-0">Serving 50+ countries with local partnerships ensuring compliance with regional medical regulations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="products-showcase">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title animate-on-scroll">Explore Our Categories</h2>
                <p class="section-subtitle animate-on-scroll">
                    Comprehensive healthcare solutions across all medical specialties
                </p>
            </div>
        </div>
        <div class="row g-4">
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories->take(6) as $category)
                <div class="col-lg-4 col-md-6">
                    <div class="category-card animate-on-scroll">
                        <div class="category-image">
                            <i class="bi bi-{{ $loop->first ? 'heart-pulse' : ($loop->iteration == 2 ? 'bandaid' : ($loop->iteration == 3 ? 'thermometer' : ($loop->iteration == 4 ? 'capsule' : ($loop->iteration == 5 ? 'activity' : 'hospital')))) }}"></i>
                        </div>
                        <div class="category-content">
                            <h5 class="category-title">{{ $category->name }}</h5>
                            <p class="category-desc">Professional {{ strtolower($category->name) }} products and medical supplies</p>
                            <div class="mt-3">
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-modern">
                                    Explore Category <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Demo Categories if no categories exist yet -->
                <div class="col-lg-4 col-md-6">
                    <div class="category-card animate-on-scroll">
                        <div class="category-image">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <div class="category-content">
                            <h5 class="category-title">Cardiology</h5>
                            <p class="category-desc">Advanced cardiac care equipment and monitoring devices</p>
                            <div class="mt-3">
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-modern">
                                    Explore Category <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card animate-on-scroll">
                        <div class="category-image">
                            <i class="bi bi-bandaid"></i>
                        </div>
                        <div class="category-content">
                            <h5 class="category-title">Emergency Care</h5>
                            <p class="category-desc">Critical emergency medical supplies and first aid equipment</p>
                            <div class="mt-3">
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-modern">
                                    Explore Category <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card animate-on-scroll">
                        <div class="category-image">
                            <i class="bi bi-thermometer"></i>
                        </div>
                        <div class="category-content">
                            <h5 class="category-title">Diagnostics</h5>
                            <p class="category-desc">Precision diagnostic tools and laboratory equipment</p>
                            <div class="mt-3">
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-modern">
                                    Explore Category <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-primary-modern btn-lg">
                <i class="bi bi-grid me-2"></i>
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- Trust & Certifications -->
<section class="trust-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title text-white animate-on-scroll">Trusted by Healthcare Professionals</h2>
                <p class="section-subtitle text-white animate-on-scroll">
                    Certified, secure, and trusted by medical institutions worldwide
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="trust-badge animate-on-scroll">
                    <div class="trust-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h5 class="mb-2">FDA Certified</h5>
                    <p class="mb-0">All products meet strict FDA standards</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-badge animate-on-scroll">
                    <div class="trust-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h5 class="mb-2">HIPAA Compliant</h5>
                    <p class="mb-0">Your health data is secure and protected</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-badge animate-on-scroll">
                    <div class="trust-icon">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <h5 class="mb-2">ISO Certified</h5>
                    <p class="mb-0">International quality management standards</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-badge animate-on-scroll">
                    <div class="trust-icon">
                        <i class="bi bi-globe2"></i>
                    </div>
                    <h5 class="mb-2">Global Reach</h5>
                    <p class="mb-0">Serving healthcare facilities worldwide</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Animated Counter
function animateCounter() {
    const counters = document.querySelectorAll('[data-count]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.count);
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target + (counter.textContent.includes('%') ? '%' : '');
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current) + (counter.textContent.includes('%') ? '%' : '');
            }
        }, 16);
    });
}

// Scroll Animation Observer
function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

// Parallax Effect
function initParallax() {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.hero-modern::before');
        
        parallaxElements.forEach(element => {
            const speed = 0.5;
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    // Start counter animation after a delay
    setTimeout(animateCounter, 1000);
    
    // Initialize scroll animations
    initScrollAnimations();
    
    // Initialize parallax
    // initParallax();
    
    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush
