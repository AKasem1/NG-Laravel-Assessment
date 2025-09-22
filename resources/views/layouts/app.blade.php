<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MediCare - Premium Medical Supplies')</title>
    <meta name="description" content="@yield('description', 'Professional medical supplies and healthcare products. FDA certified, fast shipping, and trusted by healthcare professionals worldwide.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-cyan: #4ECDC4;
            --primary-orange: #FF8B3D;
            --dark-text: #1E293B;
            --light-bg: #F8FAFC;
            --border-radius: 16px;
            --border-radius-large: 24px;
            --gradient-primary: linear-gradient(135deg, #FF8B3D 0%, #4ECDC4 100%);
            --gradient-soft: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 8px 25px rgba(0, 0, 0, 0.1);
            --shadow-strong: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.6;
        }

        /* Top Header Bar */
        .top-header {
            background: var(--gradient-primary);
            color: white;
            padding: 8px 0;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .top-header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-info {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .header-info span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-info i {
            font-size: 0.9rem;
            opacity: 0.9;
        }

                /* Main Navigation */
                .main-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-soft);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark-text) !important;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: var(--primary-orange) !important;
        }

        .logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover .logo {
            transform: scale(1.1) rotate(5deg);
        }

        /* FIXED: Make navbar navigation horizontal */
        .navbar-nav {
            display: flex;
            flex-direction: row; /* Force horizontal layout */
            gap: 0.5rem;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        /* FIXED: Make navigation links display horizontally */
        .nav-link {
            font-weight: 600;
            color: var(--dark-text) !important;
            padding: 10px 20px !important;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            display: inline-block; /* Force inline display */
            white-space: nowrap; /* Prevent text wrapping */
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-soft);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover {
            color: var(--primary-orange) !important;
            transform: translateY(-2px);
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link.active {
            background: var(--gradient-soft);
            color: var(--primary-orange) !important;
        }

        
        .main-navbar .container-fluid > .d-flex {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            flex-wrap: nowrap; /* Prevent wrapping on larger screens */
        }

        .navbar-nav.d-none.d-lg-flex {
            display: flex !important;
            flex-direction: row !important;
            align-items: center;
            gap: 0.5rem;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 139, 61, 0.25);
            background: rgba(255, 255, 255, 0.95);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-cyan);
            font-size: 1.1rem;
        }

        /* Cart Button */
        .cart-btn {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
            color: white;
            text-decoration: none;
        }

        .cart-count {
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-orange);
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            position: absolute;
            top: -5px;
            right: -5px;
            animation: pulse 2s infinite;
        }

        /* Auth Buttons */
        .auth-btn {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid var(--primary-orange);
            color: var(--primary-orange);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .auth-btn:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: var(--shadow-medium);
        }

        /* Logout Button */
        .logout-btn {
            background: rgba(220, 53, 69, 0.1);
            border: 2px solid #dc3545;
            color: #dc3545;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: var(--shadow-medium);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid rgba(255, 255, 255, 0.8);
        }

        .user-avatar:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-medium);
        }

        .dropdown-menu-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-strong);
            padding: 0.5rem;
            min-width: 220px;
            margin-top: 0.5rem;
        }

        .dropdown-item-modern {
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--dark-text);
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .dropdown-item-modern:hover {
            background: var(--gradient-soft);
            color: var(--primary-orange);
            text-decoration: none;
            transform: translateX(4px);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Role Badge */
        .role-badge {
            background: var(--gradient-primary);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            position: absolute;
            bottom: -2px;
            right: -2px;
            min-width: 20px;
            text-align: center;
        }

        .role-badge.super-admin {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .top-header {
                text-align: center;
            }

            .header-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            .main-navbar .container-fluid {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-nav {
                flex-wrap: wrap;
                justify-content: center;
            }

            .search-container {
                order: 3;
                width: 100%;
                max-width: none;
            }

            .auth-actions {
                gap: 0.5rem !important;
            }
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
        }

        .modern-toast {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-strong);
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        }

        .modern-toast.success {
            border-left: 4px solid #28a745;
        }

        .modern-toast.error {
            border-left: 4px solid #dc3545;
        }

        .modern-toast.info {
            border-left: 4px solid var(--primary-cyan);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Top Header Bar -->
    <div class="top-header">
        <div class="container-fluid">
            <div class="header-info">
                <span>
                    <i class="bi bi-shield-check"></i>
                    FDA Certified Medical Supplies
                </span>
                <span>
                    <i class="bi bi-telephone"></i>
                    24/7 Emergency: +1-800-MEDICAL
                </span>
            </div>
            <div class="header-info">
                <span>
                    <i class="bi bi-truck"></i>
                    Free Express Shipping on Orders $75+
                </span>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="navbar-brand">
                    <div class="logo">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <span>MediCare</span>
                </a>

                <div class="navbar-nav d-none d-lg-flex">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-grid me-1"></i>Products
                    </a>
                    <a href="{{ route('home') }}#about" class="nav-link">
                        <i class="bi bi-info-circle me-1"></i>About
                    </a>
                    <a href="{{ route('home') }}#contact" class="nav-link">
                        <i class="bi bi-envelope me-1"></i>Contact
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="search-container d-none d-md-block">
                    <form action="{{ route('products.search') }}" method="GET">
                        <div class="position-relative">
                            <i class="bi bi-search search-icon"></i>
                            <input 
                                type="text" 
                                name="query" 
                                class="search-input" 
                                placeholder="Search medical products..."
                                value="{{ request('query') }}"
                            >
                        </div>
                    </form>
                </div>

                <!-- Right Side Actions -->
                <div class="d-flex align-items-center gap-3 auth-actions">
                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="cart-btn position-relative">
                        <i class="bi bi-cart3"></i>
                        <span class="d-none d-sm-inline">Cart</span>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>

                    <!-- Role-Aware Authentication Section -->
                    @auth('web')
                        {{-- Admin/Super Admin User is Logged In --}}
                        @if(Auth::user()->role && in_array(Auth::user()->role, ['admin', 'super_admin']))
                            <div class="user-dropdown dropdown">
                                <div class="user-avatar position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                    <div class="role-badge {{ Auth::user()->role === 'super_admin' ? 'super-admin' : '' }}">
                                        {{ Auth::user()->role === 'super_admin' ? 'SA' : 'A' }}
                                    </div>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern">
                                    <li>
                                        <a class="dropdown-item-modern" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i>
                                            Admin Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item-modern w-100 text-start">
                                                <i class="bi bi-box-arrow-right"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            {{-- Regular User (No Role/Customer) - Show Simple Logout --}}
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span class="d-none d-sm-inline">Logout</span>
                                </button>
                            </form>
                        @endif
                    @else
                        {{-- No User Logged In - Show Login Button --}}
                        <a href="{{ route('login') }}" class="auth-btn">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span class="d-none d-sm-inline">Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Info!</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Cart Count Update Function
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
                const cartCountElement = document.getElementById('cartCount');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count || 0;
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
        }

        // Modern Toast Function
        function showModernToast(message, type = 'info', duration = 5000) {
            const toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) return;

            const toast = document.createElement('div');
            toast.className = `modern-toast ${type}`;
            
            const icon = type === 'success' ? 'bi-check-circle' : 
                        type === 'error' ? 'bi-exclamation-triangle' : 
                        'bi-info-circle';
            
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi ${icon} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Auto remove after duration
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.animation = 'slideInRight 0.3s ease reverse';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Search suggestions (optional enhancement)
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Implement search suggestions here if needed
                }, 300);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
