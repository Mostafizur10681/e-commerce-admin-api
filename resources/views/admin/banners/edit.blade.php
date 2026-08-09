@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Banners', 'url' => route('admin.banners.index')],
            ['label' => 'Edit ' . $banner->title]
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Edit Banner: {{ $banner->title }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Banner Title *</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Subtitle / Description</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Target Link</label>
                    <input type="text" name="link" value="{{ old('link', $banner->link) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>
            </div>

            @if($banner->image)
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Current Banner Image</label>
                    <div class="h-28 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50">
                        <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}" class="h-full w-full object-cover">
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Change Banner Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Publish Status</label>
                <select name="status" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Active / Visible</option>
                    <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Inactive / Hidden</option>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('admin.banners.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20">Update Banner</button>
            </div>
        </form>
    </div>

</div>
@endsection
