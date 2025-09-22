@extends('layouts.admin')

@section('title', $product->name)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Products</a>
    </li>
    <li class="breadcrumb-item active">{{ Str::limit($product->name, 50) }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Page Header -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <div class="d-flex align-items-center mb-2">
                    <h2 class="mb-0 me-3" style="color: var(--dark-text);">{{ $product->name }}</h2>
                    @if($product->stock_quantity <= 0)
                        <span class="badge bg-danger fs-6">Out of Stock</span>
                    @elseif($product->stock_quantity <= 10)
                        <span class="badge bg-warning fs-6">Low Stock</span>
                    @else
                        <span class="badge bg-success fs-6">In Stock</span>
                    @endif
                </div>
                <p class="text-muted mb-0">
                    <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? 'No Category' }}
                    <span class="mx-2">•</span>
                    <i class="bi bi-calendar me-1"></i>Added {{ $product->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>
                    <span class="d-none d-sm-inline">Edit Product</span>
                    <span class="d-sm-none">Edit</span>
                </a>
                <button type="button" 
                        class="btn btn-outline-danger"
                        onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                    <i class="bi bi-trash me-2"></i>
                    <span class="d-none d-sm-inline">Delete</span>
                    <span class="d-sm-none">Del</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Product Image & Basic Info -->
    <div class="col-lg-5 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <!-- Product Image -->
                <div class="text-center mb-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 400px; width: auto;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm"
                             style="height: 300px;">
                            <div class="text-center">
                                <i class="bi bi-image text-muted mb-3" style="font-size: 4rem;"></i>
                                <p class="text-muted mb-0">No image available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('home') }}?product={{ $product->id }}" 
                           class="btn btn-outline-info w-100" 
                           target="_blank">
                            <i class="bi bi-eye me-2"></i>Preview
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="col-lg-7 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2" style="color: var(--primary-orange);"></i>
                    Product Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Description -->
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Description</h6>
                        <p class="mb-0">{{ $product->description }}</p>
                    </div>

                    <!-- Price & Stock -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Price</h6>
                        <div class="h3 text-primary mb-0">
                            ${{ number_format($product->price, 2) }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Stock Quantity</h6>
                        <div class="h3 mb-0">
                            <span class="badge {{ $product->stock_quantity <= 0 ? 'bg-danger' : ($product->stock_quantity <= 10 ? 'bg-warning' : 'bg-success') }} fs-5">
                                {{ $product->stock_quantity }} units
                            </span>
                        </div>
                    </div>

                    <!-- Category & Dates -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Category</h6>
                        <span class="badge bg-light text-dark fs-6">
                            {{ $product->category->name ?? 'No Category' }}
                        </span>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Last Updated</h6>
                        <small class="text-muted">
                            {{ $product->updated_at->format('M d, Y g:i A') }}
                            <br>{{ $product->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Statistics -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2" style="color: var(--primary-cyan);"></i>
                    Sales Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-orange mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-cart" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">{{ $product->orderItems->sum('quantity') ?? 0 }}</h4>
                            <small class="text-muted">Total Sold</small>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-cyan mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-currency-dollar" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">${{ number_format($product->orderItems->sum(fn($item) => $item->quantity * $item->price) ?? 0, 2) }}</h4>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-success mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-people" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">{{ $product->orderItems->pluck('order.customer_name')->unique()->count() ?? 0 }}</h4>
                            <small class="text-muted">Unique Buyers</small>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon icon-warning mx-auto mb-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-receipt" style="font-size: 1.2rem;"></i>
                            </div>
                            <h4 class="mb-1">{{ $product->orderItems->pluck('order_id')->unique()->count() ?? 0 }}</h4>
                            <small class="text-muted">Orders</small>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon" style="background: #6f42c1; width: 50px; height: 50px;" class="mx-auto mb-3">
                                <i class="bi bi-calculator" style="font-size: 1.2rem; color: white;"></i>
                            </div>
                            <h4 class="mb-1">${{ $product->orderItems->count() > 0 ? number_format($product->orderItems->sum(fn($item) => $item->quantity * $item->price) / $product->orderItems->sum('quantity'), 2) : '0.00' }}</h4>
                            <small class="text-muted">Avg. Price</small>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="card-icon" style="background: #e83e8c; width: 50px; height: 50px;" class="mx-auto mb-3">
                                <i class="bi bi-calendar" style="font-size: 1.2rem; color: white;"></i>
                            </div>
                            <h4 class="mb-1">{{ $product->created_at->diffInDays() }}</h4>
                            <small class="text-muted">Days Listed</small>
                        </div>
                    </div>
                </div>
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
                <a href="{{ route('admin.orders.index') }}?product={{ $product->id }}" class="btn btn-outline-primary btn-sm">
                    View All Orders
                </a>
            </div>
            <div class="card-body">
                @if($product->orderItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->orderItems->sortByDesc('created_at')->take(10) as $orderItem)
                                    <tr>
                                        <td>
                                            <strong>#{{ $orderItem->order->id }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $orderItem->order->customer_name }}</div>
                                                <small class="text-muted">{{ $orderItem->order->phone }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $orderItem->quantity }}</span>
                                        </td>
                                        <td>${{ number_format($orderItem->price, 2) }}</td>
                                        <td>
                                            <strong>${{ number_format($orderItem->quantity * $orderItem->price, 2) }}</strong>
                                        </td>
                                        <td>
                                            <small>{{ $orderItem->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            @switch($orderItem->order->status)
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
                                                    <span class="badge bg-secondary">{{ ucfirst($orderItem->order->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $orderItem->order) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-cart-x text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mb-2">No Orders Yet</h5>
                        <p class="text-muted mb-3">This product hasn't been ordered by any customers yet.</p>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-2"></i>View on Website
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Logs -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-journal-text me-2" style="color: var(--primary-cyan);"></i>
                    Product Change History
                </h5>
                <a href="{{ route('admin.product-logs.index') }}?product={{ $product->id }}" class="btn btn-outline-info btn-sm">
                    View All Logs
                </a>
            </div>
            <div class="card-body">
                @if(isset($productLogs) && $productLogs->count() > 0)
                    <div class="timeline">
                        @foreach($productLogs->take(5) as $log)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-light rounded-circle p-2" style="width: 40px; height: 40px;">
                                        <i class="bi bi-clock text-muted"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $log->action }}</h6>
                                            <p class="text-muted mb-1 small">{{ $log->details }}</p>
                                        </div>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <small class="text-muted">by {{ $log->user->name ?? 'System' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-journal text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">No change history available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the product:</p>
                <p><strong id="deleteProductName"></strong></p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    This action cannot be undone. The product will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(productId, productName) {
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush
