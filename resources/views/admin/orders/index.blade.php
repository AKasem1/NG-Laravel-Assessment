@extends('layouts.admin')

@section('title', 'Orders Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h2 class="mb-1" style="color: var(--dark-text);">
                    <i class="bi bi-cart-check me-2" style="color: var(--primary-orange);"></i>
                    Orders Management
                </h2>
                <p class="text-muted mb-0">Track and manage customer orders and medical product deliveries</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-info">
                    <i class="bi bi-people me-2"></i>
                    <span class="d-none d-sm-inline">View Customers</span>
                    <span class="d-sm-none">Customers</span>
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-graph-up me-2"></i>
                    <span class="d-none d-sm-inline">Analytics</span>
                    <span class="d-sm-none">Stats</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="card-icon icon-orange me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-cart-plus" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $stats['total_orders'] ?? 0 }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="card-icon icon-warning me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-clock" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $stats['pending_orders'] ?? 0 }}</h4>
                                <small class="text-muted">Pending Orders</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="card-icon icon-success me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-currency-dollar" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="card-icon icon-cyan me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calendar-day" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">${{ number_format($stats['today_revenue'] ?? 0, 2) }}</h4>
                                <small class="text-muted">Today's Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search orders, customers...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="date_range" class="form-select">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select name="sort" class="form-select">
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                            <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Highest Amount</option>
                            <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Lowest Amount</option>
                            <option value="customer_asc" {{ request('sort') == 'customer_asc' ? 'selected' : '' }}>Customer A-Z</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">
                                <i class="bi bi-funnel me-1"></i>
                                <span class="d-none d-sm-inline">Filter</span>
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2" style="color: var(--primary-orange);"></i>
                    Orders
                    @if(isset($orders))
                        <span class="badge bg-light text-dark ms-2">{{ $orders->total() }} total</span>
                    @endif
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="view-mode" id="table-view" checked>
                    <label class="btn btn-outline-secondary" for="table-view">
                        <i class="bi bi-table"></i>
                    </label>
                    <input type="radio" class="btn-check" name="view-mode" id="card-view">
                    <label class="btn btn-outline-secondary" for="card-view">
                        <i class="bi bi-grid"></i>
                    </label>
                </div>
            </div>
            
            <div class="card-body">
                @if(isset($orders) && $orders->count() > 0)
                    <!-- Table View -->
                    <div id="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="order-row" data-status="{{ $order->status }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        @switch($order->status)
                                                            @case('pending')
                                                                <div class="bg-warning rounded-circle" style="width: 8px; height: 8px;"></div>
                                                                @break
                                                            @case('processing')
                                                                <div class="bg-info rounded-circle" style="width: 8px; height: 8px;"></div>
                                                                @break
                                                            @case('completed')
                                                                <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                                                @break
                                                            @case('cancelled')
                                                                <div class="bg-danger rounded-circle" style="width: 8px; height: 8px;"></div>
                                                                @break
                                                        @endswitch
                                                    </div>
                                                    <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-telephone me-1"></i>{{ $order->phone }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-light text-dark me-2">{{ $order->orderItems->count() }}</span>
                                                    <small class="text-muted">
                                                        {{ $order->orderItems->sum('quantity') }} total qty
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold text-primary">${{ number_format($order->total_amount, 2) }}</div>
                                                    <small class="text-muted">
                                                        Avg: ${{ $order->orderItems->count() > 0 ? number_format($order->total_amount / $order->orderItems->sum('quantity'), 2) : '0.00' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm dropdown-toggle 
                                                        @switch($order->status)
                                                            @case('pending') btn-outline-warning @break
                                                            @case('processing') btn-outline-info @break
                                                            @case('completed') btn-outline-success @break
                                                            @case('cancelled') btn-outline-danger @break
                                                            @default btn-outline-secondary
                                                        @endswitch" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown">
                                                        {{ ucfirst($order->status) }}
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($order->status != 'pending')
                                                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'pending')">
                                                                <i class="bi bi-clock text-warning me-2"></i>Pending</a></li>
                                                        @endif
                                                        @if($order->status != 'processing')
                                                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                                                <i class="bi bi-gear text-info me-2"></i>Processing</a></li>
                                                        @endif
                                                        @if($order->status != 'completed')
                                                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                                                <i class="bi bi-check-circle text-success me-2"></i>Completed</a></li>
                                                        @endif
                                                        @if($order->status != 'cancelled')
                                                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                                                <i class="bi bi-x-circle text-danger me-2"></i>Cancelled</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                    <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                                       class="btn btn-outline-info" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                                            data-bs-toggle="dropdown" title="More Actions">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                                                            <i class="bi bi-eye me-2"></i>View Details</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="printOrder({{ $order->id }})">
                                                            <i class="bi bi-printer me-2"></i>Print Order</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-primary" href="tel:{{ $order->phone }}">
                                                            <i class="bi bi-telephone me-2"></i>Call Customer</a></li>
                                                        <li><a class="dropdown-item text-primary" href="mailto:{{ $order->email ?? '' }}">
                                                            <i class="bi bi-envelope me-2"></i>Email Customer</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card View (Initially Hidden) -->
                    <div id="card-container" class="d-none">
                        <div class="row g-4">
                            @foreach($orders as $order)
                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <!-- Order Header -->
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="mb-1">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h6>
                                                    <small class="text-muted">{{ $order->created_at->format('M d, Y g:i A') }}</small>
                                                </div>
                                                <span class="badge 
                                                    @switch($order->status)
                                                        @case('pending') bg-warning @break
                                                        @case('processing') bg-info @break
                                                        @case('completed') bg-success @break
                                                        @case('cancelled') bg-danger @break
                                                        @default bg-secondary
                                                    @endswitch">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>

                                            <!-- Customer Info -->
                                            <div class="mb-3">
                                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                                <small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $order->phone }}
                                                </small>
                                            </div>

                                            <!-- Order Summary -->
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="text-center p-2 bg-light rounded">
                                                        <div class="fw-bold text-primary">{{ $order->orderItems->count() }}</div>
                                                        <small class="text-muted">Items</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-2 bg-light rounded">
                                                        <div class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('admin.orders.show', $order) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                    <i class="bi bi-gear me-1"></i>Status
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'pending')">
                                                        <i class="bi bi-clock text-warning me-2"></i>Pending</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                                        <i class="bi bi-gear text-info me-2"></i>Processing</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                                        <i class="bi bi-check-circle text-success me-2"></i>Completed</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                                        <i class="bi bi-x-circle text-danger me-2"></i>Cancelled</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-3">No Orders Found</h4>
                        <p class="text-muted mb-4">
                            @if(request()->hasAny(['search', 'status', 'date_range']))
                                No orders match your current filters. Try adjusting your search criteria.
                            @else
                                No orders have been placed yet. Orders will appear here once customers start purchasing medical products.
                            @endif
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            @if(request()->hasAny(['search', 'status', 'date_range']))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                </a>
                            @endif
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box me-2"></i>Manage Products
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="bi bi-arrow-repeat text-primary me-2"></i>
                    Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update the order status to:</p>
                <p class="fw-bold" id="newStatusText"></p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    This will update the order status and may trigger customer notifications.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Cancel
                </button>
                <form id="statusForm" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="statusInput">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// View Mode Toggle
document.addEventListener('DOMContentLoaded', function() {
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');
    const tableContainer = document.getElementById('table-container');
    const cardContainer = document.getElementById('card-container');

    if (tableView && cardView && tableContainer && cardContainer) {
        tableView.addEventListener('change', function() {
            if (this.checked) {
                tableContainer.classList.remove('d-none');
                cardContainer.classList.add('d-none');
            }
        });

        cardView.addEventListener('change', function() {
            if (this.checked) {
                cardContainer.classList.remove('d-none');
                tableContainer.classList.add('d-none');
            }
        });
    }
});

// Update Order Status
function updateOrderStatus(orderId, status) {
    const statusText = {
        'pending': 'Pending',
        'processing': 'Processing', 
        'completed': 'Completed',
        'cancelled': 'Cancelled'
    };
    
    document.getElementById('newStatusText').textContent = statusText[status];
    document.getElementById('statusInput').value = status;
    document.getElementById('statusForm').action = `/admin/orders/${orderId}/status`;
    
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    statusModal.show();
}

// Print Order (placeholder)
function printOrder(orderId) {
    // In a real application, this would open a print dialog or generate a PDF
    window.open(`/admin/orders/${orderId}?print=1`, '_blank');
}

// Auto-submit search form on enter
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
}

// Status filter highlighting
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.querySelector('select[name="status"]');
    if (statusFilter && statusFilter.value) {
        const rows = document.querySelectorAll('.order-row');
        rows.forEach(row => {
            if (row.dataset.status !== statusFilter.value) {
                row.style.opacity = '0.6';
            }
        });
    }
});
</script>
@endpush
