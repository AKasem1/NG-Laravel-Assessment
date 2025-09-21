<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Show checkout page
     * Requirement: No login required, collect full name, phone, delivery address
     */
    public function checkout()
    {
        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some products before checkout.');
        }

        // Get cart items with product details
        $cartItems = collect($cart)->map(function ($item) {
            $product = Product::find($item['product_id']);
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'subtotal' => $product ? $product->price * $item['quantity'] : 0
            ];
        })->filter(function ($item) {
            return $item['product'] !== null; // Remove items with deleted products
        });

        // Validate stock availability for all items
        $stockErrors = [];
        foreach ($cartItems as $item) {
            if (!$item['product']->hasStock($item['quantity'])) {
                $stockErrors[] = $item['product']->name . ' - Only ' . $item['product']->stock_quantity . ' items available';
            }
        }

        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                ->with('error', 'Stock issues found: ' . implode(', ', $stockErrors));
        }

        $total = $cartItems->sum('subtotal');

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process the order (no login required)
     */
    public function store(Request $request)
    {
        // Validation as per assessment requirements
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Start database transaction for order processing
        DB::beginTransaction();
        
        try {
            // Calculate total and validate stock again
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception('Product not found: ' . $item['product_id']);
                }

                if (!$product->hasStock($item['quantity'])) {
                    throw new \Exception($product->name . ' - Insufficient stock. Only ' . $product->stock_quantity . ' available.');
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Store price at time of order
                    'subtotal' => $subtotal
                ];

                // Reduce stock
                $product->reduceStock($item['quantity']);
            }

            // Create the order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Clear the cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Order placed successfully! Order ID: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('checkout')
                ->with('error', 'Order processing failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Order success page
     */
    public function success(Order $order)
    {
        $order->load('orderItems.product');
        return view('checkout.success', compact('order'));
    }

    /**
     * Order tracking (by order ID)
     */
    public function track(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('orders.track');
        }

        $request->validate([
            'order_id' => 'required|integer',
            'phone' => 'required|string'
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('phone', $request->phone)
            ->with('orderItems.product')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or phone number doesn\'t match.');
        }

        return view('orders.details', compact('order'));
    }

    /**
     * Admin: View all orders
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with('orderItems.product');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by customer name or phone
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics for admin dashboard
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Admin: View single order
     */
    public function adminShow(Order $order)
    {
        $order->load('orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Admin: Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // If order is cancelled, restore stock
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Get cart from session
     */
    private function getCart()
    {
        return Session::get('cart', []);
    }
}
