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

    // Add these methods to your existing HomeController

/**
 * Display products catalog page
 */
public function products(Request $request)
{
    $query = Product::with('category')->where('stock_quantity', '>=', 0);
    
    // Category filter
    if ($request->has('categories') && is_array($request->categories)) {
        $query->whereIn('category_id', $request->categories);
    }
    
    // Search filter
    if ($request->has('search') && $request->search) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }
    
    // Price filter
    if ($request->has('max_price') && $request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }
    
    // Stock filter
    if ($request->has('in_stock') && $request->in_stock) {
        $query->where('stock_quantity', '>', 0);
    }
    
    // Sort
    $sort = $request->get('sort', 'name');
    
    switch ($sort) {
        case 'price':
            $query->orderBy('price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
        case 'name':
            $query->orderBy('name', 'asc');
            break;
        case 'name_desc':
            $query->orderBy('name', 'desc');
            break;
        case 'newest':
            $query->orderBy('created_at', 'desc');
            break;
        default:
            $query->orderBy('name', 'asc');
    }
    
    $products = $query->paginate(12)->withQueryString();
    $categories = Category::all();
    
    return view('products.index', compact('products', 'categories'));
}

/**
 * Search products
 */
public function search(Request $request)
{
    $query = $request->get('q', '');
    
    if (!$query) {
        return redirect()->route('products.index');
    }
    
    $products = Product::with('category')
        ->where('stock_quantity', '>=', 0)
        ->where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('description', 'like', '%' . $query . '%');
        })
        ->paginate(12);
    
    $categories = Category::all();
    
    return view('products.index', compact('products', 'categories'))
           ->with('searchQuery', $query);
}

/**
 * Display products by category
 */
public function category(Request $request, $category)
{
    $categoryModel = Category::where('slug', $category)
                            ->orWhere('id', $category)
                            ->firstOrFail();
    
    $query = Product::with('category')
        ->where('category_id', $categoryModel->id)
        ->where('stock_quantity', '>=', 0);
    
    // Apply other filters
    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }
    
    if ($request->has('in_stock')) {
        $query->where('stock_quantity', '>', 0);
    }
    
    // Sort
    $sort = $request->get('sort', 'name');
    switch ($sort) {
        case 'price':
            $query->orderBy('price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
        case 'newest':
            $query->orderBy('created_at', 'desc');
            break;
        default:
            $query->orderBy('name', 'asc');
    }
    
    $products = $query->paginate(12)->withQueryString();
    $categories = Category::all();
    
    return view('products.index', compact('products', 'categories'))
           ->with('currentCategory', $categoryModel);
}


    /**
 * Display individual product details
 */
public function show(Product $product)
{
    // Load the product with its category
    $product->load('category');
    
    // Get related products from the same category
    $relatedProducts = collect();
    if ($product->category) {
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category->id)
            ->where('id', '!=', $product->id)
            ->where('stock_quantity', '>', 0)
            ->limit(4)
            ->get();
    }
    
    // If no related products from same category, get random products
    if ($relatedProducts->count() < 4) {
        $additionalProducts = Product::with('category')
            ->where('id', '!=', $product->id)
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4 - $relatedProducts->count())
            ->get();
            
        $relatedProducts = $relatedProducts->concat($additionalProducts);
    }
    
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
