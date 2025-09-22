<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display cart contents
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = collect($cart)->map(function ($item, $productId) {
            $product = Product::with('category')->find($productId);
            return $product ? [
                'id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'image' => $product->image ?? null,
                'category' => $product->category->name ?? 'Medical Supplies',
                'description' => $product->description ?? null,
                'stock_quantity' => $product->stock_quantity
            ] : null;
        })->filter();

        // Convert to the format expected by the view
        $formattedCart = $cartItems->mapWithKeys(function ($item) {
            return [$item['id'] => $item];
        })->toArray();

        // Put cart in session format for view
        Session::put('cart', $formattedCart);

        // Get recommended products
        $recommendedProducts = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('cart.index', compact('recommendedProducts'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1'
        ]);

        $quantity = $request->input('quantity', 1);

        // Check stock availability
        if ($product->stock_quantity < $quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
                ], 400);
            }
            return back()->with('error', 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.');
        }

        $cart = $this->getCart();
        $productId = $product->id;

        // Check if product already in cart
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            
            // Check stock for new total quantity
            if ($product->stock_quantity < $newQuantity) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. Total would exceed available stock.'
                    ], 400);
                }
                return back()->with('error', 'Cannot add more items. Total would exceed available stock.');
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            // Add new product to cart
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'category' => $product->category->name ?? 'Medical Supplies',
                'description' => $product->description,
                'stock_quantity' => $product->stock_quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }

        $this->updateCart($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $product->name . ' added to cart successfully!',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return back()->with('success', $product->name . ' added to cart successfully!');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $quantity = $request->input('quantity');
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
        }

        if ($quantity == 0) {
            // Remove item if quantity is 0
            unset($cart[$productId]);
        } else {
            // Check stock availability
            if ($product->stock_quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
                ], 400);
            }

            $cart[$productId]['quantity'] = $quantity;
        }

        $this->updateCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Remove product from cart
     */
    public function remove($productId)
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->updateCart($cart);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart successfully!',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return back()->with('success', 'Product removed from cart successfully!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Session::forget('cart');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!'
            ]);
        }

        return back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count for header display
     */
    public function count()
    {
        return response()->json(['count' => $this->getCartCount()]);
    }

    /**
     * Quick add product to cart (for AJAX calls)
     */
    public function quickAdd(Request $request, Product $product)
    {
        return $this->add($request, $product);
    }

    /**
     * Proceed to checkout (redirect to login if needed)
     */
    public function proceedToCheckout()
    {
        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // For now, redirect directly to checkout (guest checkout)
        return redirect()->route('checkout');
    }

    /**
     * Get cart from session
     */
    private function getCart()
    {
        return Session::get('cart', []);
    }

    /**
     * Update cart in session
     */
    private function updateCart($cart)
    {
        Session::put('cart', $cart);
    }

    /**
     * Get total cart item count
     */
    private function getCartCount()
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }
}
