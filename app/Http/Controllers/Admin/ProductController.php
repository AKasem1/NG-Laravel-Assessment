<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::with('category')->withTrashed()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'imgbb_url' => 'nullable|url', // Validate ImgBB URL
        ]);
    
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'image' => $request->imgbb_url,
        ]);
    
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }
    

    public function show(Product $product)
    {
        $product->load('category', 'productLogs.changedBy');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|url|max:500' // Expecting URL instead of file
        ]);

        // Store old values for logging
        $oldValues = $product->toArray();
        
        $data = $request->all();

        // Simply update the image URL (no file handling needed)
        $product->update($data);

        // Log the update
        $this->logProductChange($product, 'updated', $oldValues, $product->fresh()->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Store old values for logging
        $oldValues = $product->toArray();
        
        $product->delete(); // Soft delete

        // Log the deletion
        $this->logProductChange($product, 'deleted', $oldValues, null);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        // Log the restoration
        $this->logProductChange($product, 'restored', null, $product->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product restored successfully.');
    }

    /**
     * Log product changes as required by assessment
     */
    private function logProductChange(Product $product, $action, $oldValues = null, $newValues = null)
    {
        $changes = [];
        
        if ($action === 'updated' && $oldValues && $newValues) {
            foreach ($newValues as $key => $newValue) {
                if (isset($oldValues[$key]) && $oldValues[$key] != $newValue) {
                    $changes[$key] = [
                        'old' => $oldValues[$key],
                        'new' => $newValue
                    ];
                }
            }
        } elseif ($action === 'created') {
            $changes = $newValues;
        } elseif ($action === 'deleted') {
            $changes = $oldValues;
        }

        ProductLog::create([
            'product_id' => $product->id,
            'action' => $action,
            'changed_by' => Auth::id(),
            'changes' => $changes
        ]);
    }
}
