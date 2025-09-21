<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with medical products
     * Requirements: Show list of products (name, image, price) with "Add to Cart" button
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('stock_quantity', '>', 0);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting options
        $sortBy = $request->get('sort_by', 'name'); // Default sort by name
        $sortDirection = $request->get('sort_direction', 'asc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', $sortDirection);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        // Pagination
        $products = $query->paginate(12)->withQueryString();

        // Get all categories for filter dropdown
        $categories = Category::withCount('products')->having('products_count', '>', 0)->get();

        // Get price range for filter
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('home', compact('products', 'categories', 'priceRange'));
    }

    /**
     * Display single product details
     */
    public function show(Product $product)
    {
        $product->load('category');
        
        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock_quantity', '>', 0)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * API endpoint for AJAX search suggestions
     */
    public function searchSuggestions(Request $request)
    {
        $term = $request->get('term', '');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $suggestions = Product::where('name', 'LIKE', "%{$term}%")
            ->where('stock_quantity', '>', 0)
            ->take(5)
            ->pluck('name')
            ->toArray();

        return response()->json($suggestions);
    }

    /**
     * Filter products via AJAX
     */
    public function filter(Request $request)
    {
        $query = Product::with('category')->where('stock_quantity', '>', 0);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'name');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Return HTML partial for AJAX updates
        return view('partials.products-grid', compact('products'))->render();
    }

    /**
     * Get categories for AJAX dropdown
     */
    public function categories()
    {
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    /**
     * Quick add to cart from product grid (AJAX)
     */
    public function quickAddToCart(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        // Check stock
        if (!$product->hasStock($quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock_quantity . ' items available.'
            ], 400);
        }

        // Use CartController logic
        $cartController = new CartController();
        $result = $cartController->add($request, $product);

        if ($result->getStatusCode() === 302) { // Redirect response
            return response()->json([
                'success' => true,
                'message' => $product->name . ' added to cart successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add product to cart.'
        ], 400);
    }
}
