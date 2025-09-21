<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Admin Dashboard - Main overview page
     */
    public function dashboard()
    {
        // Key Statistics
        $stats = $this->getDashboardStats();
        
        // Recent Orders
        $recentOrders = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        // Recent Product Changes (Product Logs)
        $recentProductLogs = ProductLog::with(['product', 'changedBy'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Sales Chart Data (Last 7 days)
        $salesChartData = $this->getSalesChartData();

        // Top Selling Products
        $topSellingProducts = $this->getTopSellingProducts();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'lowStockProducts',
            'recentProductLogs',
            'salesChartData',
            'topSellingProducts'
        ));
    }

    /**
     * Product Logs Management
     */
    public function productLogs(Request $request)
    {
        $query = ProductLog::with(['product', 'changedBy']);

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by admin user
        if ($request->filled('changed_by')) {
            $query->where('changed_by', $request->changed_by);
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $productLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get data for filters
        $products = Product::withTrashed()->orderBy('name')->get();
        $adminUsers = User::where('is_active', true)->orderBy('name')->get();

        return view('admin.product-logs.index', compact('productLogs', 'products', 'adminUsers'));
    }

    /**
     * System Settings & Configuration
     */
    public function settings()
    {
        $settings = [
            'low_stock_threshold' => 10,
            'items_per_page' => 12,
            'auto_approve_orders' => false,
            'maintenance_mode' => false,
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update System Settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'low_stock_threshold' => 'required|integer|min:1',
            'items_per_page' => 'required|integer|min:5|max:50',
            'auto_approve_orders' => 'boolean',
            'maintenance_mode' => 'boolean',
        ]);

        // In a real app, you'd store these in a settings table or config
        // For now, we'll just return success
        
        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Admin Users Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->withCount('productLogs')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Analytics & Reports
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // Default 30 days
        
        $analyticsData = [
            'revenue' => $this->getRevenueAnalytics($period),
            'orders' => $this->getOrderAnalytics($period),
            'products' => $this->getProductAnalytics($period),
            'categories' => $this->getCategoryAnalytics(),
        ];

        return view('admin.analytics', compact('analyticsData', 'period'));
    }

    /**
     * Export Reports
     */
    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'orders');
        $format = $request->get('format', 'csv');
        
        // This would generate CSV/Excel exports
        // For now, return a placeholder response
        
        return back()->with('success', 'Report export initiated. You will receive an email when ready.');
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
    
        return [
            // Product Statistics
            'total_products' => Product::count(),
            'active_products' => Product::where('stock_quantity', '>', 0)->count(),
            'total_categories' => Category::count(),
            'low_stock_count' => Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count(),
            'out_of_stock_count' => Product::where('stock_quantity', '=', 0)->count(),
    
            // Order Statistics
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
    
            // Revenue Statistics
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'pending_revenue' => Order::where('status', 'pending')->sum('total_amount'),
            'today_revenue' => Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount'),
            'week_revenue' => Order::where('created_at', '>=', $thisWeek)->where('status', 'completed')->sum('total_amount'),
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)->where('status', 'completed')->sum('total_amount'),
    
            // Order Trends
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'week_orders' => Order::where('created_at', '>=', $thisWeek)->count(),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'yesterday_orders' => Order::whereDate('created_at', $today->copy()->subDay())->count(),
    
            // Customer Statistics (Using Customer Model)
            'total_registered_customers' => Customer::count(),
            'active_customers' => Customer::whereHas('orders')->count(), // Customers with at least one order
            'new_customers_today' => Customer::whereDate('created_at', $today)->count(),
            'new_customers_week' => Customer::where('created_at', '>=', $thisWeek)->count(),
            'new_customers_month' => Customer::where('created_at', '>=', $thisMonth)->count(),
    
            // Guest Customer Statistics (From Orders - No Login Required Checkout)
            'total_guest_customers' => Order::distinct('customer_name', 'phone')->count(),
            'repeat_guest_customers' => Order::select('customer_name', 'phone')
                ->groupBy('customer_name', 'phone')
                ->havingRaw('COUNT(*) > 1')
                ->get()
                ->count(),
            'new_guest_customers_today' => Order::whereDate('created_at', $today)
                ->distinct('customer_name', 'phone')
                ->count(),
    
            // Combined Customer Statistics
            'total_unique_customers' => Customer::count() + Order::distinct('customer_name', 'phone')
                ->whereNotIn('customer_name', Customer::pluck('name')->toArray())
                ->count(),
    
            // Admin Statistics
            'admin_users' => User::where('is_active', true)->count(),
            'super_admins' => User::where('role', 'super_admin')->where('is_active', true)->count(),
            'regular_admins' => User::where('role', 'admin')->where('is_active', true)->count(),
    
            // Performance Metrics
            'average_order_value' => Order::where('status', 'completed')->avg('total_amount'),
            'conversion_rate' => $this->calculateConversionRate(),
            'customer_retention_rate' => $this->calculateCustomerRetentionRate(),
    
            // Recent Activity Counts
            'recent_product_changes' => ProductLog::where('created_at', '>=', $today)->count(),
            'orders_needing_attention' => Order::where('status', 'pending')
                ->where('created_at', '<=', now()->subHours(24))
                ->count(),
        ];
    }
    

    /**
     * Get sales chart data for last 7 days
     */
    private function getSalesChartData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
            
            $data[] = [
                'date' => $date->format('M d'),
                'revenue' => (float) $revenue,
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }
        
        return $data;
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('products.name', 'products.id')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get revenue analytics for specified period
     */
    private function getRevenueAnalytics($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total' => Order::where('created_at', '>=', $startDate)->where('status', 'completed')->sum('total_amount'),
            'average' => Order::where('created_at', '>=', $startDate)->where('status', 'completed')->avg('total_amount'),
            'orders_count' => Order::where('created_at', '>=', $startDate)->count(),
        ];
    }

    /**
     * Get order analytics
     */
    private function getOrderAnalytics($days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total' => Order::where('created_at', '>=', $startDate)->count(),
            'pending' => Order::where('created_at', '>=', $startDate)->where('status', 'pending')->count(),
            'completed' => Order::where('created_at', '>=', $startDate)->where('status', 'completed')->count(),
            'cancelled' => Order::where('created_at', '>=', $startDate)->where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Get product analytics
     */
    private function getProductAnalytics($days)
    {
        return [
            'total' => Product::count(),
            'low_stock' => Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            'recently_added' => Product::where('created_at', '>=', Carbon::now()->subDays($days))->count(),
        ];
    }

    /**
     * Get category analytics
     */
    private function getCategoryAnalytics()
    {
        return Category::withCount('products')->orderBy('products_count', 'desc')->take(5)->get();
    }

    /**
 * Admin: View all customers (from orders)
 */
public function customers(Request $request)
{
    // Since we don't have customer registration, we'll extract unique customers from orders
    $query = Order::select('customer_name', 'phone', 'address')
        ->selectRaw('COUNT(*) as total_orders')
        ->selectRaw('SUM(total_amount) as total_spent')
        ->selectRaw('MAX(created_at) as last_order_date')
        ->selectRaw('MIN(created_at) as first_order_date');

    // Search by customer name or phone
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('customer_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
        });
    }

    // Filter by location (address contains)
    if ($request->filled('location')) {
        $query->where('address', 'LIKE', "%{$request->location}%");
    }

    // Group by customer identifiers
    $customers = $query->groupBy('customer_name', 'phone', 'address')
        ->orderBy('total_spent', 'desc')
        ->paginate(15);

    return view('admin.customers.index', compact('customers'));
}

/**
 * Admin: View single customer details
 */
public function customerDetails(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string'
    ]);

    $customerName = $request->name;
    $customerPhone = $request->phone;

    // Get all orders for this customer
    $orders = Order::where('customer_name', $customerName)
        ->where('phone', $customerPhone)
        ->with('orderItems.product')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    if ($orders->count() === 0) {
        return back()->with('error', 'Customer not found.');
    }

    // Customer statistics
    $customerStats = [
        'total_orders' => $orders->total(),
        'total_spent' => Order::where('customer_name', $customerName)->where('phone', $customerPhone)->sum('total_amount'),
        'completed_orders' => Order::where('customer_name', $customerName)->where('phone', $customerPhone)->where('status', 'completed')->count(),
        'pending_orders' => Order::where('customer_name', $customerName)->where('phone', $customerPhone)->where('status', 'pending')->count(),
        'cancelled_orders' => Order::where('customer_name', $customerName)->where('phone', $customerPhone)->where('status', 'cancelled')->count(),
        'first_order' => Order::where('customer_name', $customerName)->where('phone', $customerPhone)->orderBy('created_at', 'asc')->first(),
        'last_order' => Order::where('customer_name', $customerName)->where('phone', $customerPhone)->orderBy('created_at', 'desc')->first(),
    ];

    $customerInfo = [
        'name' => $customerName,
        'phone' => $customerPhone,
        'address' => $orders->first()->address ?? 'N/A'
    ];

    return view('admin.customers.details', compact('orders', 'customerStats', 'customerInfo'));
}

/**
 * Admin: Customer analytics and insights
 */
public function customerAnalytics()
{
    $analytics = [
        'total_unique_customers' => Order::distinct('customer_name', 'phone')->count(),
        'repeat_customers' => Order::select('customer_name', 'phone')
            ->groupBy('customer_name', 'phone')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count(),
        'top_customers' => Order::select('customer_name', 'phone')
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as total_spent')
            ->groupBy('customer_name', 'phone')
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->get(),
        'customer_locations' => Order::select('address')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('address')
            ->orderBy('order_count', 'desc')
            ->take(10)
            ->get(),
        'new_customers_this_month' => Order::select('customer_name', 'phone')
            ->whereMonth('created_at', now()->month)
            ->distinct()
            ->count(),
    ];

    return view('admin.customers.analytics', compact('analytics'));
}

/**
 * Export customer data
 */
public function exportCustomers(Request $request)
{
    $format = $request->get('format', 'csv');
    
    // Get customer data
    $customers = Order::select('customer_name', 'phone', 'address')
        ->selectRaw('COUNT(*) as total_orders')
        ->selectRaw('SUM(total_amount) as total_spent')
        ->selectRaw('MAX(created_at) as last_order_date')
        ->groupBy('customer_name', 'phone', 'address')
        ->orderBy('total_spent', 'desc')
        ->get();

    // For now, return success message
    // In real implementation, generate CSV/Excel file
    
    return back()->with('success', 'Customer data export initiated. You will receive an email when ready.');
}

}
