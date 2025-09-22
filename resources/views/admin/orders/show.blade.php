@extends('layouts.admin')

@section('title', 'Order #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">Orders</a>
    </li>
    <li class="breadcrumb-item active">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <div class="d-flex align-items-center mb-2">
                    <div class="me-3">
                        @switch($order->status)
                            @case('pending')
                                <div class="card-icon icon-warning" style="width: 60px; height: 60px;">
                                    <i class="bi bi-clock" style="font-size: 1.5rem;"></i>
                                </div>
                                @break
                            @case('processing')
                                <div class="card-icon icon-cyan" style="width: 60px; height: 60px;">
                                    <i class="bi bi-gear" style="font-size: 1.5rem;"></i>
                                </div>
                                @break
                            @case('completed')
                                <div class="card-icon icon-success" style="width: 60px; height: 60px;">
                                    <i class="bi bi-check-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                @break
                            @case('cancelled')
                                <div class="card-icon" style="background: #dc3545; width: 60px; height: 60px;">
                                    <i class="bi bi-x-circle" style="font-size: 1.5rem; color: white;"></i>
                                </div>
                                @break
                        @endswitch
                    </div>
                    <div>
                        <h2 class="mb-1" style="color: var(--dark-text);">
                            Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar me-1"></i>{{ $order->created_at->format('F d, Y g:i A') }}
                            <span class="mx-2">•</span>
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
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-2"></i>
                        <span class="d-none d-sm-inline">Actions</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Status Updates</h6></li>
                        @if($order->status != 'pending')
                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'pending')">
                                <i class="bi bi-clock text-warning me-2"></i>Mark as Pending</a></li>
                        @endif
                        @if($order->status != 'processing')
                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                <i class="bi bi-gear text-info me-2"></i>Mark as Processing</a></li>
                        @endif
                        @if($order->status != 'completed')
                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                <i class="bi bi-check-circle text-success me-2"></i>Mark as Completed</a></li>
                        @endif
                        @if($order->status != 'cancelled')
                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                <i class="bi bi-x-circle text-danger me-2"></i>Mark as Cancelled</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Customer Contact</h6></li>
                        <li><a class="dropdown-item" href="tel:{{ $order->phone }}">
                            <i class="bi bi-telephone text-primary me-2"></i>Call Customer</a></li>
                        @if($order->email)
                            <li><a class="dropdown-item" href="mailto:{{ $order->email }}">
                                <i class="bi bi-envelope text-primary me-2"></i>Email Customer</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="printOrder()">
                            <i class="bi bi-printer me-2"></i>Print Order</a></li>
                        <li><a class="dropdown-item" href="#" onclick="downloadInvoice()">
                            <i class="bi bi-download me-2"></i>Download Invoice</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="card-icon icon-orange me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-box" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $order->orderItems->count() }}</h4>
                                <small class="text-muted">Unique Items</small>
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
                                <i class="bi bi-stack" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $order->orderItems->sum('quantity') }}</h4>
                                <small class="text-muted">Total Quantity</small>
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
                                <h4 class="mb-0">${{ number_format($order->total_amount, 2) }}</h4>
                                <small class="text-muted">Total Amount</small>
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
                                <i class="bi bi-calculator" style="font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">${{ $order->orderItems->sum('quantity') > 0 ? number_format($order->total_amount / $order->orderItems->sum('quantity'), 2) : '0.00' }}</h4>
                                <small class="text-muted">Avg per Item</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information & Order Items -->
    <div class="col-lg-4 mb-4">
        <!-- Customer Information -->
        <div class="card dashboard-card mb-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2" style="color: var(--primary-cyan);"></i>
                    Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Customer Name</h6>
                    <p class="mb-0 fw-semibold">{{ $order->customer_name }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Phone Number</h6>
                    <p class="mb-0">
                        <a href="tel:{{ $order->phone }}" class="text-decoration-none">
                            <i class="bi bi-telephone me-1"></i>{{ $order->phone }}
                        </a>
                    </p>
                </div>

                @if($order->email)
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Email Address</h6>
                        <p class="mb-0">
                            <a href="mailto:{{ $order->email }}" class="text-decoration-none">
                                <i class="bi bi-envelope me-1"></i>{{ $order->email }}
                            </a>
                        </p>
                    </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Delivery Address</h6>
                    <p class="mb-0">{{ $order->address }}</p>
                </div>

                @if($order->notes)
                    <div class="mb-0">
                        <h6 class="text-muted mb-1">Order Notes</h6>
                        <p class="mb-0 fst-italic">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2" style="color: var(--primary-orange);"></i>
                    Order Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle p-2" style="width: 40px; height: 40px;">
                                <i class="bi bi-cart-plus text-white" style="font-size: 0.9rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Order Placed</h6>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y g:i A') }}</small>
                        </div>
                    </div>

                    @if($order->status != 'pending')
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-info rounded-circle p-2" style="width: 40px; height: 40px;">
                                    <i class="bi bi-gear text-white" style="font-size: 0.9rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Order Processing</h6>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                    @endif

                    @if($order->status == 'completed')
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-success rounded-circle p-2" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-circle text-white" style="font-size: 0.9rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Order Completed</h6>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                    @endif

                    @if($order->status == 'cancelled')
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-danger rounded-circle p-2" style="width: 40px; height: 40px;">
                                    <i class="bi bi-x-circle text-white" style="font-size: 0.9rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Order Cancelled</h6>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="col-lg-8 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-box me-2" style="color: var(--primary-orange);"></i>
                    Order Items
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="items-view" id="detailed-view" checked>
                    <label class="btn btn-outline-secondary" for="detailed-view">
                        <i class="bi bi-list-ul"></i> Detailed
                    </label>
                    <input type="radio" class="btn-check" name="items-view" id="compact-view">
                    <label class="btn btn-outline-secondary" for="compact-view">
                        <i class="bi bi-list"></i> Compact
                    </label>
                </div>
            </div>
            <div class="card-body">
                <!-- Detailed View -->
                <div id="detailed-items">
                    @foreach($order->orderItems as $item)
                        <div class="row align-items-center mb-4 pb-3 border-bottom">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="img-fluid rounded"
                                         style="max-width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 80px; height: 80px; margin: 0 auto;">
                                        <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <h6 class="mb-1">
                                    @if($item->product)
                                        <a href="{{ route('admin.products.show', $item->product) }}" 
                                           class="text-decoration-none">{{ $item->product->name }}</a>
                                    @else
                                        {{ $item->product_name ?? 'Product Deleted' }}
                                    @endif
                                </h6>
                                @if($item->product && $item->product->category)
                                    <span class="badge bg-light text-dark mb-2">{{ $item->product->category->name }}</span>
                                @endif
                                @if($item->product && $item->product->description)
                                    <p class="text-muted small mb-0">{{ Str::limit($item->product->description, 80) }}</p>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="fw-bold">${{ number_format($item->price, 2) }}</div>
                                <small class="text-muted">per unit</small>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="fw-bold">{{ $item->quantity }}</div>
                                <small class="text-muted">qty</small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="h5 mb-0 text-primary">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                <small class="text-muted">total</small>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Compact View (Initially Hidden) -->
                <div id="compact-items" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded me-3"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">
                                                        @if($item->product)
                                                            {{ $item->product->name }}
                                                        @else
                                                            {{ $item->product_name ?? 'Product Deleted' }}
                                                        @endif
                                                    </div>
                                                    @if($item->product && $item->product->category)
                                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="fw-bold text-primary">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        <td>
                                            @if($item->product)
                                                <a href="{{ route('admin.products.show', $item->product) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Total -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $order->orderItems->sum('quantity') }} items):</span>
                            <span>${{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>Included</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <strong>Total:</strong>
                            <strong class="text-primary h5 mb-0">${{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="col-12">
        <div class="row g-4">
            <!-- Order History -->
            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-journal-text me-2" style="color: var(--primary-cyan);"></i>
                            Order History
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($orderHistory) && $orderHistory->count() > 0)
                            <div class="timeline">
                                @foreach($orderHistory as $history)
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-light rounded-circle p-2" style="width: 35px; height: 35px;">
                                                <i class="bi bi-clock text-muted" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $history->action }}</h6>
                                                    <p class="text-muted mb-1 small">{{ $history->details }}</p>
                                                </div>
                                                <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                                            </div>
                                            <small class="text-muted">by {{ $history->user->name ?? 'System' }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-journal text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">No history records available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Order Statistics -->
            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-person-lines-fill me-2" style="color: var(--primary-orange);"></i>
                            Customer Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            // Calculate customer stats (in real implementation, this would be from the controller)
                            $customerStats = [
                                'total_orders' => 1,
                                'total_spent' => $order->total_amount,
                                'avg_order_value' => $order->total_amount,
                                'first_order_date' => $order->created_at
                            ];
                        @endphp

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="mb-1 text-primary">{{ $customerStats['total_orders'] }}</h4>
                                    <small class="text-muted">Total Orders</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="mb-1 text-success">${{ number_format($customerStats['total_spent'], 2) }}</h4>
                                    <small class="text-muted">Total Spent</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="mb-1 text-info">${{ number_format($customerStats['avg_order_value'], 2) }}</h4>
                                    <small class="text-muted">Avg Order</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="mb-1 text-warning">{{ $customerStats['first_order_date']->diffInDays() }}</h4>
                                    <small class="text-muted">Days Ago</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6 class="mb-3">Customer Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.customers.index') }}?search={{ $order->customer_name }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-person me-2"></i>View Customer Profile
                                </a>
                                <a href="{{ route('admin.orders.index') }}?search={{ $order->customer_name }}" 
                                   class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-cart me-2"></i>View Customer Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
                    This will update the order status. Consider notifying the customer about this change.
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
// Items View Toggle
document.addEventListener('DOMContentLoaded', function() {
    const detailedView = document.getElementById('detailed-view');
    const compactView = document.getElementById('compact-view');
    const detailedItems = document.getElementById('detailed-items');
    const compactItems = document.getElementById('compact-items');

    if (detailedView && compactView && detailedItems && compactItems) {
        detailedView.addEventListener('change', function() {
            if (this.checked) {
                detailedItems.classList.remove('d-none');
                compactItems.classList.add('d-none');
            }
        });

        compactView.addEventListener('change', function() {
            if (this.checked) {
                compactItems.classList.remove('d-none');
                detailedItems.classList.add('d-none');
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

// Print Order
function printOrder() {
    const printContent = document.querySelector('.container-fluid').innerHTML;
    const originalContent = document.body.innerHTML;
    
    // Create print styles
    const printStyles = `
        <style>
            @media print {
                .btn, .dropdown, .modal, .breadcrumb { display: none !important; }
                .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
                .badge { color: #000 !important; }
                body { font-size: 12px; }
                h1, h2, h3, h4, h5, h6 { font-size: 1.2em; }
            }
        </style>
    `;
    
    document.body.innerHTML = printStyles + '<div class="container-fluid">' + printContent + '</div>';
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

// Download Invoice (placeholder)
function downloadInvoice() {
    // In a real application, this would generate and download a PDF invoice
    alert('Invoice download functionality would be implemented here.');
}
</script>
@endpush
