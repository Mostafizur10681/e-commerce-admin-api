@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Categories', 'url' => route('admin.categories.index')],
                ['label' => 'Sub Categories']
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Sub Categories</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Manage second-level category classifications.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.sub-categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Add Sub Category</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.sub-categories.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <svg class="absolute top-2.5 left-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search sub categories..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                >
            </div>

            <div>
                <select name="category_id" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    <option value="">All Parent Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('admin.sub-categories.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sub Categories Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5">Sub Category</th>
                        <th class="px-5 py-3.5">Parent Category</th>
                        <th class="px-5 py-3.5">Slug</th>
                        <th class="px-5 py-3.5">Products</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($subCategories as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shrink-0 flex items-center justify-center">
                                        @if($sub->image)
                                            <img src="{{ str_starts_with($sub->image, 'http') ? $sub->image : asset('storage/' . $sub->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $sub->name }}</h4>
                                        <p class="text-[11px] text-gray-400 truncate max-w-xs">{{ $sub->description ?: 'No description' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 font-semibold text-gray-700 dark:text-gray-300">
                                {{ $sub->category->name ?? 'Uncategorized' }}
                            </td>

                            <td class="px-5 py-4 font-mono text-gray-500">{{ $sub->slug }}</td>
                            <td class="px-5 py-4 font-semibold text-primary">{{ $sub->products_count }} Items</td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $sub->is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $sub->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.sub-categories.edit', $sub->id) }}" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.sub-categories.destroy', $sub->id) }}" onsubmit="return confirm('Delete this sub category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:bg-rose-50 dark:hover:bg-rose-950 hover:text-rose-600 transition-colors" title="Delete">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">No sub categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
