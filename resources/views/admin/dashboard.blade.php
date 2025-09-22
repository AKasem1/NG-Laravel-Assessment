@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1" style="color: var(--dark-text);">
                    <i class="bi bi-speedometer2 me-2" style="color: var(--primary-orange);"></i>
                    Admin Dashboard
                </h2>
                <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
            </div>
            <div>
                <button class="btn btn-primary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="card-icon icon-orange">
                    <i class="bi bi-box"></i>
                </div>
                <h3 class="mb-1">{{ $stats['total_products'] ?? 0 }}</h3>
                <p class="text-muted mb-2">Total Products</p>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i>
                    {{ $stats['active_products'] ?? 0 }} Active
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="card-icon icon-cyan">
                    <i class="bi bi-cart-check"></i>
                </div>
                <h3 class="mb-1">{{ $stats['total_orders'] ?? 0 }}</h3>
                <p class="text-muted mb-2">Total Orders</p>
                <small class="text-warning">
                    <i class="bi bi-clock"></i>
                    {{ $stats['pending_orders'] ?? 0 }} Pending
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="card-icon icon-success">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h3 class="mb-1">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                <p class="text-muted mb-2">Total Revenue</p>
                <small class="text-info">
                    <i class="bi bi-calendar-day"></i>
                    ${{ number_format($stats['today_revenue'] ?? 0, 2) }} Today
                </small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="card-icon icon-warning">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="mb-1">{{ $stats['total_unique_customers'] ?? 0 }}</h3>
                <p class="text-muted mb-2">Total Customers</p>
                <small class="text-primary">
                    <i class="bi bi-person-plus"></i>
                    {{ $stats['new_customers_today'] ?? 0 }} New Today
                </small>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="col-lg-8 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2" style="color: var(--primary-orange);"></i>
                    Sales Overview (Last 7 Days)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2" style="color: var(--primary-cyan);"></i>
                    Low Stock Alert
                </h5>
            </div>
            <div class="card-body">
                @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts as $product)
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                                    </div>
                                    <span class="badge bg-warning rounded-pill">{{ $product->stock_quantity }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.products.index') }}?filter=low_stock" class="btn btn-outline-warning btn-sm">
                            View All Low Stock
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0 mt-2">All products are well stocked!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2" style="color: var(--primary-orange);"></i>
                    Recent Orders
                </h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">
                    View All Orders
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->id }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                                <small class="text-muted">{{ $order->phone }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $order->orderItems->count() }} items</span>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">Processing</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <small>{{ $order->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0 mt-2">No orders yet. Start adding products to get orders!</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2" style="color: var(--primary-cyan);"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-plus-circle mb-2 d-block" style="font-size: 1.5rem;"></i>
                            Add New Product
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-tag mb-2 d-block" style="font-size: 1.5rem;"></i>
                            Add Category
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-clock mb-2 d-block" style="font-size: 1.5rem;"></i>
                            Pending Orders
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-graph-up mb-2 d-block" style="font-size: 1.5rem;"></i>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesData = @json($salesChartData ?? []);

new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: salesData.map(item => item.date),
        datasets: [{
            label: 'Revenue ($)',
            data: salesData.map(item => item.revenue),
            borderColor: '#FF8B3D',
            backgroundColor: 'rgba(255, 139, 61, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }, {
            label: 'Orders',
            data: salesData.map(item => item.orders),
            borderColor: '#4ECDC4',
            backgroundColor: 'rgba(78, 205, 196, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Revenue ($)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Orders'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});
</script>
@endpush
