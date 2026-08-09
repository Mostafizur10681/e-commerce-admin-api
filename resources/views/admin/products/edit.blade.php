@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Products', 'url' => route('admin.products.index')],
                ['label' => 'Edit ' . $product->name]
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Edit Product: {{ $product->name }}</h1>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <!-- Product Edit Form -->
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @method('PUT')

        <!-- LEFT 2 COLS: Information & Pricing -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Basic Information</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Product Title *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Main Category *</label>
                        <select name="category_id" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Sub Category</label>
                        <select name="sub_category_id" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                            <option value="">None / Optional</option>
                            @foreach($subCategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('sub_category_id', $product->sub_category_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Short Description</label>
                    <textarea name="short_description" rows="2" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Full Description</label>
                    <textarea name="description" rows="5" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Pricing & Inventory Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Pricing & Inventory</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Regular Price (৳) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Sale Price (৳)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Stock Status</label>
                        <select name="stock_status" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                            <option value="in-stock" {{ old('stock_status', $product->stock_status) === 'in-stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out-of-stock" {{ old('stock_status', $product->stock_status) === 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="pre-order" {{ old('stock_status', $product->stock_status) === 'pre-order' ? 'selected' : '' }}>Pre Order</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SEO & Metadata Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">SEO & Metadata</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: Images & Status -->
        <div class="space-y-6">

            <!-- Current Images & New Uploads -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Product Images</h3>

                @if($product->images->count() > 0 || $product->image)
                    <div>
                        <span class="text-[11px] font-semibold text-gray-500 block mb-2">Existing Images:</span>
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @if($product->image)
                                <div class="h-20 rounded-xl overflow-hidden border border-primary/50 bg-gray-50 relative">
                                    <img src="{{ str_starts_with($product->image, 'data:image') || str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="h-full w-full object-cover">
                                    <span class="absolute top-1 left-1 bg-primary text-white text-[8px] font-bold px-1 rounded">Main</span>
                                </div>
                            @endif
                            @foreach($product->images as $img)
                                @if($img->image_path !== $product->image)
                                    <div class="h-20 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50">
                                        <img src="{{ str_starts_with($img->image_path, 'data:image') || str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path) }}" class="h-full w-full object-cover">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="border-2 border-dashed border-gray-200 dark:border-gray-800 hover:border-primary rounded-2xl p-4 flex flex-col items-center justify-center cursor-pointer transition-colors text-center">
                        <svg class="h-6 w-6 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Upload More Images</span>
                        <input type="file" name="images[]" multiple class="hidden" accept="image/*">
                    </label>
                </div>
            </div>

            <!-- Status & Badges -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Status & Badges</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Publish Status</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active / Published</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive / Hidden</option>
                        <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="space-y-2.5 pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>Featured Product</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="best_seller" value="1" {{ old('best_seller', $product->best_seller) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>Best Seller</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="new_arrival" value="1" {{ old('new_arrival', $product->new_arrival) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>New Arrival</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="organic" value="1" {{ old('organic', $product->organic) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>Organic / Eco-Friendly</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-lg shadow-primary/20 transition-all cursor-pointer">
                        Update Product
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
