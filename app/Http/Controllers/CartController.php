<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = $this->getCart();
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

        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->input('quantity', 1);

        // Check stock availability
        if (!$product->hasStock($quantity)) {
            return back()->with('error', 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.');
        }

        $cart = $this->getCart();
        $productId = $product->id;

        // Check if product already in cart
        $existingIndex = collect($cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($existingIndex !== false) {
            // Update quantity if product exists
            $newQuantity = $cart[$existingIndex]['quantity'] + $quantity;
            
            // Check stock for new total quantity
            if (!$product->hasStock($newQuantity)) {
                return back()->with('error', 'Cannot add more items. Total would exceed available stock.');
            }
            
            $cart[$existingIndex]['quantity'] = $newQuantity;
        } else {
            // Add new product to cart
            $cart[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }

        $this->updateCart($cart);

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

        $itemIndex = collect($cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($itemIndex === false) {
            return response()->json(['error' => 'Product not found in cart'], 404);
        }

        if ($quantity == 0) {
            // Remove item if quantity is 0
            unset($cart[$itemIndex]);
            $cart = array_values($cart); // Re-index array
        } else {
            // Check stock availability
            if (!$product->hasStock($quantity)) {
                return response()->json([
                    'error' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
                ], 400);
            }

            $cart[$itemIndex]['quantity'] = $quantity;
        }

        $this->updateCart($cart);

        // Return updated cart data for AJAX
        $cartItems = $this->getCartItemsWithDetails();
        $total = $cartItems->sum('subtotal');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_total' => number_format($total, 2),
            'cart_count' => $cartItems->sum('quantity')
        ]);
    }

    /**
     * Remove product from cart
     */
    public function remove($productId)
    {
        $cart = $this->getCart();

        $cart = collect($cart)->filter(function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        })->values()->toArray();

        $this->updateCart($cart);

        return back()->with('success', 'Product removed from cart successfully!');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count for header display
     */
    public function count()
    {
        $cart = $this->getCart();
        $count = collect($cart)->sum('quantity');
        
        return response()->json(['count' => $count]);
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
     * Get cart items with product details
     */
    private function getCartItemsWithDetails()
    {
        $cart = $this->getCart();
        return collect($cart)->map(function ($item) {
            $product = Product::find($item['product_id']);
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'subtotal' => $product ? $product->price * $item['quantity'] : 0
            ];
        })->filter(function ($item) {
            return $item['product'] !== null;
        });
    }
}
