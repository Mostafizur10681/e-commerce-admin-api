<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\ProductImage;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use UploadImageTrait;

    public function index(Request $request)
    {
        $query = Product::with(['category', 'subCategory', 'images']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();
        $attributes = Attribute::all();

        return view('admin.products.create', compact('categories', 'subCategories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,draft',
            'stock_status' => 'nullable|in:in-stock,out-of-stock,pre-order',
            'featured' => 'nullable|boolean',
            'best_seller' => 'nullable|boolean',
            'new_arrival' => 'nullable|boolean',
            'organic' => 'nullable|boolean',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'attributes' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $data['featured'] = $request->has('featured');
        $data['best_seller'] = $request->has('best_seller');
        $data['new_arrival'] = $request->has('new_arrival');
        $data['organic'] = $request->has('organic');

        if (isset($data['attributes']) && is_array($data['attributes'])) {
            $data['attributes'] = json_encode($data['attributes']);
        }

        $product = Product::create($data);

        // Process uploaded images
        if ($request->hasFile('images')) {
            $isPrimary = true;
            foreach ($request->file('images') as $file) {
                $path = $this->uploadImage($file, 'products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                ]);
                if ($isPrimary) {
                    $product->update(['image' => $path]);
                    $isPrimary = false;
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'subCategory', 'images'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();
        $attributes = Attribute::all();

        return view('admin.products.edit', compact('product', 'categories', 'subCategories', 'brands', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,draft',
            'stock_status' => 'nullable|in:in-stock,out-of-stock,pre-order',
            'featured' => 'nullable|boolean',
            'best_seller' => 'nullable|boolean',
            'new_arrival' => 'nullable|boolean',
            'organic' => 'nullable|boolean',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'attributes' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $data['featured'] = $request->has('featured');
        $data['best_seller'] = $request->has('best_seller');
        $data['new_arrival'] = $request->has('new_arrival');
        $data['organic'] = $request->has('organic');

        if (isset($data['attributes']) && is_array($data['attributes'])) {
            $data['attributes'] = json_encode($data['attributes']);
        }

        $product->update($data);

        // Upload additional images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->uploadImage($file, 'products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        foreach ($product->images as $img) {
            $this->deleteImage($img->image_path);
            $img->delete();
        }

        if ($product->image) {
            $this->deleteImage($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        return back()->with('success', 'Product status updated to ' . $product->status);
    }
}
