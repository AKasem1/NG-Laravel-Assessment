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
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|url|max:500' // Expecting URL instead of file
        ]);

        $data = $request->all();

        // If no image URL provided, you could set a default placeholder
        if (empty($data['image'])) {
            $data['image'] = null; // or set a default placeholder URL
        }

        $product = Product::create($data);

        // Log the creation
        $this->logProductChange($product, 'created', null, $product->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
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
     * API endpoint for image upload (for future cloud integration)
     * This method can be called via AJAX from the form
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // TODO: Implement cloud upload logic here
            // Example for future implementation:
            // $imageUrl = $this->uploadToCloud($request->file('image'));
            
            // For now, return a placeholder response
            $imageUrl = 'https://via.placeholder.com/300x300?text=Product+Image';
            
            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
                'message' => 'Image uploaded successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file provided'
        ], 400);
    }

    /**
     * Future method for cloud upload integration
     */
    private function uploadToCloud($file)
    {
        // TODO: Implement actual cloud upload logic
        // Examples:
        // - AWS S3: return Storage::disk('s3')->put('products', $file);
        // - Cloudinary: return cloudinary()->upload($file->getRealPath())->getSecurePath();
        // - Other cloud providers...
        
        return 'https://example-cloud-storage.com/products/' . $file->hashName();
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
