@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Products', 'url' => route('admin.products.index')],
                ['label' => 'Add Product']
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Add New Product</h1>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                Back to List
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-medium">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Product Form -->
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- LEFT 2 COLS: Information & Pricing -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Basic Information</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Product Title *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Premium Cotton Panjabi" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Main Category *</label>
                        <select name="category_id" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Sub Category</label>
                        <select name="sub_category_id" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                            <option value="">None / Optional</option>
                            @foreach($subCategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('sub_category_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Brand name" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Unit (e.g. pcs, kg)</label>
                        <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" placeholder="pcs" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Short Description</label>
                    <textarea name="short_description" rows="2" placeholder="Brief summary of product features..." class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('short_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Full Description</label>
                    <textarea name="description" rows="5" placeholder="Detailed product specifications and materials..." class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Pricing & Inventory Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Pricing & Inventory</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Regular Price (৳) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0.00" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Sale Price (৳)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="0.00" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', 10) }}" required placeholder="0" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" placeholder="e.g. SK-PANJ-001" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Stock Status</label>
                        <select name="stock_status" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                            <option value="in-stock" {{ old('stock_status') === 'in-stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out-of-stock" {{ old('stock_status') === 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="pre-order" {{ old('stock_status') === 'pre-order' ? 'selected' : '' }}>Pre Order</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SEO & Metadata Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">SEO & Metadata</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="SEO Title" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Meta Description</label>
                    <textarea name="meta_description" rows="2" placeholder="SEO Description" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('meta_description') }}</textarea>
                </div>
            </div>

        </div>

        <!-- RIGHT 1 COL: Images, Status & Badges -->
        <div class="space-y-6">

            <!-- Product Images Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Product Images</h3>

                <div x-data="{
                    images: [],
                    handleFiles(event) {
                        const files = event.target.files;
                        this.images = [];
                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.images.push(e.target.result);
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                }" class="space-y-3">
                    <label class="border-2 border-dashed border-gray-200 dark:border-gray-800 hover:border-primary rounded-2xl p-6 flex flex-col items-center justify-center cursor-pointer transition-colors text-center">
                        <svg class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Click to upload product images</span>
                        <span class="text-[10px] text-gray-400 mt-0.5">PNG, JPG, WEBP up to 5MB</span>
                        <input type="file" name="images[]" multiple @change="handleFiles" class="hidden" accept="image/*">
                    </label>

                    <template x-if="images.length > 0">
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            <template x-for="(img, idx) in images" :key="idx">
                                <div class="relative h-20 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50">
                                    <img :src="img" class="h-full w-full object-cover">
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Status & Badges Card -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Status & Badges</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Publish Status</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active / Published</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive / Hidden</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="space-y-2.5 pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>Featured Product</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="best_seller" value="1" {{ old('best_seller') ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>Best Seller</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="new_arrival" value="1" {{ old('new_arrival', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>New Arrival</span>
                    </label>

                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="organic" value="1" {{ old('organic') ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                        <span>Organic / Eco-Friendly</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-lg shadow-primary/20 transition-all cursor-pointer">
                        Save & Publish Product
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
