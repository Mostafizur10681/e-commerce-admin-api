@extends('layouts.admin')

@section('content')
@php
    $productAttributes = [];
    if (!empty($product->attributes)) {
        $decoded = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
        if (is_array($decoded)) {
            foreach ($decoded as $k => $vals) {
                if (is_array($vals)) {
                    foreach ($vals as $v) {
                        $productAttributes[] = ['key' => $k, 'value' => $v];
                    }
                } else {
                    $productAttributes[] = ['key' => $k, 'value' => $vals];
                }
            }
        }
    }
    $mainImage = $product->image ?: ($product->images->first()->image_path ?? null);
    $mainImageUrl = $mainImage ? (str_starts_with($mainImage, 'http') || str_starts_with($mainImage, 'data:') ? $mainImage : asset('storage/' . $mainImage)) : null;
@endphp

<div x-data="productEditForm()" class="space-y-6 max-w-7xl mx-auto pb-16">

    <!-- Single File Replace Trigger Input -->
    <input 
        x-ref="replaceFileInput" 
        type="file" 
        accept="image/jpeg,image/png,image/webp,image/gif" 
        class="hidden" 
        @change="handleReplaceFile($event)"
    >

    <!-- Top Breadcrumbs & Page Header -->
    <div class="space-y-1">
        <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Dashboard</a>
            <span>&gt;</span>
            <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Products</a>
            <span>&gt;</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">Edit Product</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-1">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Product</h1>
                <span class="px-2.5 py-1 rounded-xl text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                    #{{ $product->sku ?: $product->id }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back to Products
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-medium space-y-1">
            <div class="font-bold flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Please fix the following errors:
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Edit Form Grid -->
    <form id="productEditForm" method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT COLUMN: FORM CARDS (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. PRODUCT IMAGES CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Product Images</h2>
                            <p class="text-[11px] text-slate-400">Manage uploaded images or add new gallery photos.</p>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">PNG, JPG, WEBP</span>
                    </div>

                    <!-- Compact Primary Preview Box -->
                    <div class="relative w-full h-52 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 overflow-hidden flex items-center justify-center p-3 transition-all">
                        <template x-if="primaryPreview">
                            <img :src="primaryPreview" alt="Preview" class="max-h-full max-w-full object-contain rounded-xl shadow-sm">
                        </template>
                        <template x-if="!primaryPreview">
                            <div class="flex flex-col items-center justify-center space-y-2 text-slate-400 dark:text-slate-500">
                                <div class="h-12 w-12 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center shadow-sm">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-medium">No image uploaded yet</p>
                            </div>
                        </template>
                    </div>

                    <!-- Existing Gallery Images (with instant delete) -->
                    @if($product->images && $product->images->count() > 0)
                        <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                                <span>Existing Gallery Images</span>
                                <span class="text-[10px] text-slate-400">Click to view • ✕ to delete</span>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach($product->images as $pImg)
                                    @php
                                        $url = str_starts_with($pImg->image_path, 'http') || str_starts_with($pImg->image_path, 'data:') ? $pImg->image_path : asset('storage/' . $pImg->image_path);
                                    @endphp
                                    <div class="existing-img-card relative h-16 w-16 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden cursor-pointer group bg-slate-100 dark:bg-slate-800 shrink-0 shadow-sm" @click="primaryPreview = '{{ $url }}'">
                                        <img src="{{ $url }}" class="h-full w-full object-cover">
                                        <button 
                                            type="button" 
                                            @click.stop="deleteExistingImage({{ $pImg->id }}, $el)" 
                                            class="absolute top-1 right-1 h-5 w-5 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[10px] font-bold shadow cursor-pointer transition-transform hover:scale-110"
                                            title="Delete image"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload More Images Dropzone -->
                    <div 
                        @dragover.prevent="$el.classList.add('border-emerald-500', 'bg-emerald-50/20')"
                        @dragleave.prevent="$el.classList.remove('border-emerald-500', 'bg-emerald-50/20')"
                        @drop.prevent="$el.classList.remove('border-emerald-500', 'bg-emerald-50/20'); handleFiles($event.dataTransfer.files);"
                        class="border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 rounded-2xl py-3.5 px-4 flex items-center justify-center gap-3 text-center cursor-pointer transition-all bg-slate-50/50 dark:bg-slate-950/40 group"
                        @click="$refs.fileInput.click()"
                    >
                        <input 
                            x-ref="fileInput" 
                            type="file" 
                            name="images[]" 
                            multiple 
                            accept="image/jpeg,image/png,image/webp,image/gif" 
                            class="hidden" 
                            @change="handleFiles($event.target.files)"
                        >
                        <div class="h-8 w-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Upload new images, or <span class="text-emerald-600 dark:text-emerald-400 font-bold underline decoration-emerald-500">click to browse</span>
                            </p>
                            <p class="text-[10px] text-slate-400">Multiple files supported</p>
                        </div>
                    </div>

                    <!-- Newly Selected Files Preview Strip -->
                    <template x-if="previewUrls.length > 0">
                        <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                                <span>New Files to Upload (<span x-text="previewUrls.length"></span>)</span>
                                <span class="text-[10px] text-slate-400">✕ to remove • ⇄ to replace</span>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                <template x-for="(item, idx) in previewUrls" :key="idx">
                                    <div 
                                        @click="primaryPreview = item.url" 
                                        class="relative h-16 w-16 rounded-xl border overflow-hidden cursor-pointer group bg-slate-100 dark:bg-slate-800 transition-all shrink-0 shadow-sm"
                                        :class="primaryPreview === item.url ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700'"
                                    >
                                        <img :src="item.url" class="h-full w-full object-cover">
                                        <button 
                                            type="button" 
                                            @click.stop="removePreview(idx)" 
                                            class="absolute top-1 right-1 h-5 w-5 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-[10px] font-bold shadow cursor-pointer transition-transform hover:scale-110"
                                            title="Delete image"
                                        >
                                            ✕
                                        </button>
                                        <button 
                                            type="button" 
                                            @click.stop="triggerReplace(idx)" 
                                            class="absolute bottom-1 right-1 h-5 w-5 rounded-full bg-slate-900/90 hover:bg-emerald-600 text-white flex items-center justify-center text-[9px] shadow cursor-pointer transition-all opacity-0 group-hover:opacity-100"
                                            title="Replace image"
                                        >
                                            ⇄
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- 2. PRODUCT INFORMATION CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Product Information</h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Product Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="name" 
                            required 
                            placeholder="Enter product title..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Category (Main Category) *</label>
                            <select 
                                name="category_id" 
                                x-model="categoryId" 
                                required 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                            >
                                <option value="">Select main category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sub Category</label>
                            <select 
                                name="sub_category_id" 
                                x-model="subCategoryId" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                            >
                                <option value="">Select sub category</option>
                                <template x-for="sub in filteredSubCategories" :key="sub.id">
                                    <option :value="sub.id" x-text="sub.name" :selected="sub.id == subCategoryId"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Brand</label>
                            <input 
                                type="text" 
                                name="brand" 
                                x-model="brand" 
                                placeholder="e.g. Shopia, Apex" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Color</label>
                            <input 
                                type="text" 
                                name="color" 
                                x-model="color" 
                                placeholder="e.g. Black, Red, Navy Blue" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">SKU (Auto)</label>
                                <span class="text-[10px] text-slate-400 font-medium">Auto-generated</span>
                            </div>
                            <input 
                                type="text" 
                                name="sku" 
                                x-model="sku" 
                                readonly 
                                tabindex="-1" 
                                placeholder="e.g. SKU-1002" 
                                class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-mono font-bold text-slate-700 dark:text-slate-300 cursor-not-allowed select-all focus:outline-none"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Short Description</label>
                        <textarea 
                            name="short_description" 
                            x-model="short_description" 
                            rows="2" 
                            placeholder="Brief summary of product features..." 
                            class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors resize-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Full Description</label>
                        <textarea 
                            name="description" 
                            rows="4" 
                            placeholder="Detailed product information, specifications, and details..." 
                            class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Additional Information</label>
                        <textarea 
                            name="additional_info" 
                            x-model="additional_info" 
                            rows="3" 
                            placeholder="Warranty, material origin, care guide, size chart notes, etc..." 
                            class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                        ></textarea>
                    </div>
                </div>

                <!-- 3. PRICING CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Pricing</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Regular Price ($ / ৳) *</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="price" 
                                x-model="price" 
                                required 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sale Price ($ / ৳)</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="sale_price" 
                                x-model="sale_price" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Cost Price ($ / ৳)</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="cost_price" 
                                value="{{ old('cost_price', $product->cost_price ?? 0) }}" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tax (%)</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="tax" 
                                value="{{ old('tax', $product->tax ?? 0) }}" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Discount (%)</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="discount" 
                                value="{{ old('discount', $product->discount ?? 0) }}" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>
                    </div>
                </div>

                <!-- 4. INVENTORY CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Inventory</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Stock Quantity *</label>
                            <input 
                                type="number" 
                                name="stock" 
                                x-model="stock" 
                                required 
                                min="0" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Unit</label>
                            <select 
                                name="unit" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                            >
                                <option value="">Select unit</option>
                                @foreach(['pcs', 'kg', 'g', 'liter', 'ml', 'set', 'pack', 'box', 'pair'] as $u)
                                    <option value="{{ $u }}" {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Stock Status</label>
                            <select 
                                name="stock_status" 
                                x-model="stock_status" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                            >
                                <option value="in-stock">In Stock</option>
                                <option value="out-of-stock">Out of Stock</option>
                                <option value="pre-order">Pre-Order</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 5. PRODUCT STATUS CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-3">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Product Status</h2>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                        <select 
                            name="status" 
                            x-model="status" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>

                <!-- 6. PRODUCT BADGES CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-3">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Product Badges</h2>
                    <div class="flex flex-wrap items-center gap-6 pt-1">
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="organic" value="1" {{ old('organic', $product->organic) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <span>Organic</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <span>Featured</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="best_seller" value="1" {{ old('best_seller', $product->best_seller) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <span>Best Seller</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="new_arrival" value="1" {{ old('new_arrival', $product->new_arrival) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <span>New Arrival</span>
                        </label>
                    </div>
                </div>

                <!-- 7. SEO META CARD -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">SEO</h2>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Meta Title</label>
                        <input 
                            type="text" 
                            name="meta_title" 
                            value="{{ old('meta_title', $product->meta_title) }}" 
                            placeholder="SEO meta title..." 
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Meta Description</label>
                        <textarea 
                            name="meta_description" 
                            rows="2" 
                            placeholder="Meta description for search engines..." 
                            class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                        >{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Meta Keywords</label>
                        <input 
                            type="text" 
                            name="meta_keywords" 
                            value="{{ old('meta_keywords', $product->meta_keywords) }}" 
                            placeholder="comma, separated, keywords" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors"
                        >
                    </div>
                </div>

                <!-- 8. PRODUCT ATTRIBUTES CARD (Dynamic Values from Attributes table) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Product Attributes</h2>
                        <span class="text-[11px] text-slate-400">Pulls values configured in Attributes menu</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        
                        <!-- Select Attribute -->
                        <div class="sm:col-span-5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Select Attribute</label>
                            <select 
                                x-model="selectedAttrKey" 
                                @change="onAttributeChange()"
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                            >
                                <option value="">Choose an attribute</option>
                                @foreach($attributes as $attr)
                                    <option value="{{ $attr->name }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dynamic Attribute Value (Select if predefined, else text input) -->
                        <div class="sm:col-span-5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Attribute Value</label>
                            
                            <!-- When selected attribute has stored predefined values -->
                            <template x-if="availableValues.length > 0">
                                <select 
                                    x-model="attrValue" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors cursor-pointer"
                                >
                                    <option value="">Select a value</option>
                                    <template x-for="val in availableValues" :key="val">
                                        <option :value="val" x-text="val"></option>
                                    </template>
                                </select>
                            </template>

                            <!-- When selected attribute has no predefined values or none selected -->
                            <template x-if="availableValues.length === 0">
                                <input 
                                    type="text" 
                                    x-model="attrValue" 
                                    @keydown.enter.prevent="addAttribute()" 
                                    :placeholder="selectedAttrKey ? 'Enter ' + selectedAttrKey + ' value...' : 'e.g. Red, Large, 5kg'" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 transition-colors"
                                >
                            </template>
                        </div>

                        <!-- Add Button -->
                        <div class="sm:col-span-2">
                            <button 
                                type="button" 
                                @click="addAttribute()" 
                                class="w-full px-3 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center justify-center gap-1 cursor-pointer"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Add
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Inputs for attributes submission -->
                    <template x-for="(attr, index) in attributesList" :key="index">
                        <input type="hidden" :name="'attributes[' + attr.key + '][]'" :value="attr.value">
                    </template>

                    <!-- Attributes Tag List -->
                    <div class="pt-2">
                        <template x-if="attributesList.length === 0">
                            <p class="text-xs text-slate-400 italic">No attributes added yet.</p>
                        </template>

                        <div class="flex flex-wrap gap-2">
                            <template x-for="(attr, index) in attributesList" :key="index">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-medium bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <strong x-text="attr.key + ':'"></strong>
                                    <span x-text="attr.value"></span>
                                    <button type="button" @click="removeAttribute(index)" class="hover:text-rose-500 ml-1 font-bold cursor-pointer">✕</button>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 9. ACTION BUTTONS -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button 
                        type="submit" 
                        @click="status = 'active'"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-lg shadow-emerald-600/20 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer"
                    >
                        Save & Publish
                    </button>

                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-slate-900 dark:bg-slate-800 text-white hover:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold rounded-2xl shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer"
                    >
                        Update Product
                    </button>

                    <a 
                        href="{{ route('admin.products.index') }}" 
                        class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-2xl shadow-lg shadow-rose-600/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
                    >
                        Cancel
                    </a>
                </div>

            </div>

            <!-- RIGHT COLUMN: LIVE PREVIEW CARD (5 Cols, Sticky) -->
            <div class="lg:col-span-5 lg:sticky lg:top-6 space-y-4">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Live Preview</div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-4 transition-all">
                    
                    <!-- Compact Preview Image Box -->
                    <div class="relative w-full h-44 sm:h-48 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-100 dark:border-slate-800 overflow-hidden flex items-center justify-center p-2">
                        <template x-if="primaryPreview">
                            <img :src="primaryPreview" alt="Live Preview" class="max-h-full max-w-full object-contain">
                        </template>
                        <template x-if="!primaryPreview">
                            <div class="flex flex-col items-center justify-center text-slate-300 dark:text-slate-700">
                                <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </template>
                    </div>

                    <!-- Title & Price -->
                    <div class="space-y-1 border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 
                            class="text-base font-bold text-slate-900 dark:text-white leading-snug break-words" 
                            x-text="name.trim() || 'Product Name'"
                        ></h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400" x-text="formattedPrice"></span>
                            <template x-if="originalPriceDisplay">
                                <span class="text-xs text-slate-400 line-through" x-text="originalPriceDisplay"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div class="bg-slate-50 dark:bg-slate-950/60 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                            <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Category</div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 truncate" x-text="categoryName"></div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-950/60 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                            <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Brand</div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 truncate" x-text="brand.trim() || '—'"></div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-950/60 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                            <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Color</div>
                            <div class="font-bold text-slate-800 dark:text-slate-200 truncate" x-text="color.trim() || '—'"></div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-950/60 p-2.5 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                            <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">Stock</div>
                            <div 
                                class="font-bold truncate"
                                :class="(stock > 0 && stock_status === 'in-stock') ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                                x-text="(stock > 0 && stock_status === 'in-stock') ? 'In Stock (' + stock + ')' : 'Out of stock'"
                            ></div>
                        </div>
                    </div>

                    <!-- Description Preview -->
                    <div class="space-y-1 text-xs">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Description</div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px] break-words line-clamp-3" x-text="short_description.trim() || 'No description provided.'"></p>
                    </div>

                </div>

            </div>

        </div>
    </form>

</div>

<script>
function productEditForm() {
    return {
        name: @json(old('name', $product->name)),
        price: @json(old('price', $product->price)),
        sale_price: @json(old('sale_price', $product->sale_price)),
        brand: @json(old('brand', $product->brand ?? '')),
        color: @json(old('color', $product->color ?? '')),
        sku: @json(old('sku', $product->sku ?? '')),
        randomSuffix: Math.random().toString(36).substring(2, 6).toUpperCase(),
        stock: @json(old('stock', $product->stock)),
        stock_status: @json(old('stock_status', $product->stock_status ?? 'in-stock')),
        status: @json(old('status', $product->status)),
        short_description: @json(old('short_description', $product->short_description ?? '')),
        additional_info: @json(old('additional_info', $product->additional_info ?? '')),
        categoryId: @json(old('category_id', $product->category_id)),
        subCategoryId: @json(old('sub_category_id', $product->sub_category_id ?? '')),

        updateSkuFromName() {
            if (this.sku && !this.sku.startsWith('SKU-')) return;
            if (!this.name || !this.name.trim()) {
                this.sku = 'SKU-' + this.randomSuffix;
                return;
            }
            let clean = this.name.trim()
                .toUpperCase()
                .replace(/[^A-Z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .substring(0, 24);
            
            this.sku = clean ? (clean + '-' + this.randomSuffix) : ('SKU-' + this.randomSuffix);
        },

        init() {
            if (!this.sku) {
                this.updateSkuFromName();
            }
        },
        
        categories: {
            @foreach($categories as $cat)
                '{{ $cat->id }}': @json($cat->name),
            @endforeach
        },

        subCategories: [
            @foreach($subCategories as $sub)
                { id: '{{ $sub->id }}', name: @json($sub->name), category_id: '{{ $sub->category_id }}' },
            @endforeach
        ],

        get filteredSubCategories() {
            if (!this.categoryId) return this.subCategories;
            return this.subCategories.filter(s => s.category_id == this.categoryId);
        },

        get categoryName() {
            return this.categories[this.categoryId] || '—';
        },

        primaryPreview: @json($mainImageUrl),
        previewUrls: [],
        dt: new DataTransfer(),
        replaceIndex: null,

        handleFiles(newFiles) {
            if (!newFiles || newFiles.length === 0) return;
            for (let i = 0; i < newFiles.length; i++) {
                const file = newFiles[i];
                this.dt.items.add(file);
                const url = URL.createObjectURL(file);
                this.previewUrls.push({ file, url, name: file.name });
            }
            this.$refs.fileInput.files = this.dt.files;
            if (this.previewUrls.length > 0) {
                this.primaryPreview = this.previewUrls[this.previewUrls.length - 1].url;
            }
        },

        removePreview(index) {
            this.previewUrls.splice(index, 1);
            const newDt = new DataTransfer();
            for (let i = 0; i < this.previewUrls.length; i++) {
                newDt.items.add(this.previewUrls[i].file);
            }
            this.dt = newDt;
            this.$refs.fileInput.files = this.dt.files;

            if (this.previewUrls.length > 0) {
                this.primaryPreview = this.previewUrls[this.previewUrls.length - 1].url;
            } else {
                this.primaryPreview = @json($mainImageUrl);
            }
        },

        triggerReplace(index) {
            this.replaceIndex = index;
            this.$refs.replaceFileInput.click();
        },

        handleReplaceFile(event) {
            const file = event.target.files[0];
            if (!file || this.replaceIndex === null) return;
            const url = URL.createObjectURL(file);
            this.previewUrls[this.replaceIndex] = { file, url, name: file.name };
            
            const newDt = new DataTransfer();
            for (let i = 0; i < this.previewUrls.length; i++) {
                newDt.items.add(this.previewUrls[i].file);
            }
            this.dt = newDt;
            this.$refs.fileInput.files = this.dt.files;
            
            this.primaryPreview = url;
            this.replaceIndex = null;
            event.target.value = '';
        },

        async deleteExistingImage(imageId, el) {
            if (!confirm('Are you sure you want to delete this image?')) return;
            try {
                const res = await fetch(`/admin/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    el.closest('.existing-img-card').remove();
                }
            } catch (e) {
                alert('Error deleting image');
            }
        },

        // Attributes Map from Database
        attributesData: {
            @foreach($attributes as $attr)
                @php
                    $attrVals = is_array($attr->values) ? $attr->values : (json_decode($attr->values, true) ?? []);
                @endphp
                '{{ addslashes($attr->name) }}': @json($attrVals),
            @endforeach
        },

        selectedAttrKey: '',
        attrValue: '',
        attributesList: @json($productAttributes),

        get availableValues() {
            return this.attributesData[this.selectedAttrKey] || [];
        },

        onAttributeChange() {
            this.attrValue = '';
            const vals = this.availableValues;
            if (vals && vals.length > 0) {
                this.attrValue = vals[0];
            }
        },

        addAttribute() {
            if (!this.selectedAttrKey || !this.attrValue || !String(this.attrValue).trim()) return;
            this.attributesList.push({
                key: this.selectedAttrKey,
                value: String(this.attrValue).trim()
            });
            const vals = this.availableValues;
            if (vals && vals.length > 0) {
                this.attrValue = vals[0];
            } else {
                this.attrValue = '';
            }
        },

        removeAttribute(index) {
            this.attributesList.splice(index, 1);
        },

        get formattedPrice() {
            const p = parseFloat(this.price);
            const sp = parseFloat(this.sale_price);
            if (sp && sp > 0 && p > sp) {
                return '$' + sp.toFixed(2);
            }
            return p && p > 0 ? '$' + p.toFixed(2) : '$0.00';
        },

        get originalPriceDisplay() {
            const p = parseFloat(this.price);
            const sp = parseFloat(this.sale_price);
            if (sp && sp > 0 && p > sp) {
                return '$' + p.toFixed(2);
            }
            return null;
        }
    };
}
</script>
@endsection
