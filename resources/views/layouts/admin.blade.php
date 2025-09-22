<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - Medical E-Commerce</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Admin CSS -->
    <style>
        :root {
            --primary-orange: #FF8B3D;
            --primary-cyan: #4ECDC4;
            --light-gray: #F8F9FA;
            --dark-text: #2C3E50;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #F0F2F5;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* Sidebar Styles - COLLAPSIBLE */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-orange) 0%, var(--primary-cyan) 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-logo h4,
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            transform: translateX(-20px);
        }

        .sidebar.collapsed .nav-link {
            padding: 10px;
            justify-content: center;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Toggle Button */
        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: -15px;
            width: 30px;
            height: 30px;
            background: white;
            border: 2px solid var(--primary-orange);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-toggle:hover {
            background: var(--primary-orange);
            color: white;
            transform: scale(1.1);
        }

        .sidebar-toggle i {
            font-size: 14px;
            color: var(--primary-orange);
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover i {
            color: white;
        }

        /* Logo Section */
        .sidebar-logo {
            padding: 15px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .sidebar-logo img {
            height: 40px;
            width: auto;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo img {
            height: 30px;
            margin-bottom: 0;
        }

        .sidebar-logo h4 {
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        /* Navigation Section */
        .sidebar-nav {
            flex: 1;
            padding: 10px 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 0;
        }

        .nav-main {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .nav-main::-webkit-scrollbar {
            display: none;
        }

        /* Nav Items */
        .nav-item {
            margin-bottom: 3px;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        .nav-link i {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            font-size: 16px;
            text-align: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .nav-link span {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
            padding-left: 25px;
        }

        .sidebar.collapsed .nav-link:hover {
            transform: none;
            padding: 10px;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-left: 4px solid white;
            padding-left: 21px;
        }

        .sidebar.collapsed .nav-link.active {
            padding: 10px;
            border-left: none;
            border-bottom: 4px solid white;
        }

        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .nav-item {
            position: relative;
        }

        .sidebar.collapsed .nav-item::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 15px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark-text);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1002;
            pointer-events: none;
        }

        .sidebar.collapsed .nav-item:hover::after {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 10px);
        }

        .sidebar.collapsed .nav-item::before {
            content: '';
            position: absolute;
            left: calc(100% + 5px);
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 5px 8px 5px 0;
            border-color: transparent var(--dark-text) transparent transparent;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1002;
        }

        .sidebar.collapsed .nav-item:hover::before {
            opacity: 1;
            visibility: visible;
        }

        /* Bottom Actions */
        .nav-bottom {
            flex-shrink: 0;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .nav-bottom .nav-item {
            margin-bottom: 2px;
        }

        /* Logout Button */
        .nav-link.logout {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin: 0 10px 10px 10px;
            padding: 10px 15px;
        }

        .nav-link.logout:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: none;
            padding: 10px 15px;
        }

        .sidebar.collapsed .nav-link.logout {
            margin: 0 5px 10px 5px;
            padding: 10px;
        }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #F0F2F5;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header */
        .main-header {
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #E9ECEF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: var(--sidebar-width);
            }

            .main-content,
            .main-content.sidebar-collapsed {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: none;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        /* Rest of your existing styles remain the same */
        .dashboard-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .icon-orange {
            background: linear-gradient(135deg, #FF8B3D 0%, #FF6B1A 100%);
        }

        .icon-cyan {
            background: linear-gradient(135deg, #4ECDC4 0%, #26A69A 100%);
        }

        .icon-success {
            background: linear-gradient(135deg, #28A745 0%, #20C997 100%);
        }

        .icon-warning {
            background: linear-gradient(135deg, #FFC107 0%, #FF8C00 100%);
        }

        .icon-danger {
            background: linear-gradient(135deg, #DC3545 0%, #C82333 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-cyan) 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 139, 61, 0.4);
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            color: var(--primary-orange);
            font-weight: bold;
        }

        .content-area {
            padding: 20px 25px;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-light">

    <!-- Sidebar -->
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">

        <!-- Logo Section -->
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Medical E-Commerce Logo">
            <h4>Medical Admin</h4>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <!-- Main Navigation Items -->
            <div class="nav-main">
                <div class="nav-item" data-tooltip="Dashboard">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Products">
                    <a href="{{ route('admin.products.index') }}"
                        class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Products</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Categories">
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Categories</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Orders">
                    <a href="{{ route('admin.orders.index') }}"
                        class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check"></i>
                        <span>Orders</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Customers">
                    <a href="{{ route('admin.customers.index') }}"
                        class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Customers</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Product Logs">
                    <a href="{{ route('admin.product-logs.index') }}"
                        class="nav-link {{ request()->routeIs('admin.product-logs.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Product Logs</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Admin Users">
                    <a href="#" class="nav-link">
                        <i class="bi bi-person-gear"></i>
                        <span>Admin Users</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Analytics">
                    <a href="{{ route('admin.analytics.index') }}"
                        class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Analytics</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Settings">
                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Fixed Items -->
            <div class="nav-bottom">
                <div class="nav-item" data-tooltip="View Website">
                    <a href="{{ route('home') }}" class="nav-link" target="_blank">
                        <i class="bi bi-globe"></i>
                        <span>View Website</span>
                    </a>
                </div>

                <div class="nav-item" data-tooltip="Logout">
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-link logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>


        <!-- Page Content -->
        <div class="container-fluid px-4">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

        </div>
    </main>

    <!-- Mobile Overlay -->
    <div class="d-lg-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" id="sidebarOverlay"
        onclick="toggleSidebar()" style="display: none !important; z-index: 999;"></div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin JS -->
    <script>
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
            } else {
                sidebar.classList.add('show');
                overlay.style.display = 'block';
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Responsive table scroll indicator
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('.table-responsive');
            tables.forEach(function(table) {
                if (table.scrollWidth > table.clientWidth) {
                    table.classList.add('border-warning');
                    table.setAttribute('title', 'Scroll horizontally to see more columns');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
