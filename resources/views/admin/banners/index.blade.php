@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Banners']
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Homepage Banners & Sliders</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Manage promotional banners, hero sliders, and marketing graphics.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Add Banner</span>
            </a>
        </div>
    </div>

    <!-- Banners Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($banners as $banner)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between">
                <div class="h-44 overflow-hidden relative bg-gray-100 dark:bg-gray-800">
                    <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}" class="h-full w-full object-cover">
                    <span class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold shadow-md {{ $banner->status === 'active' ? 'bg-green-500 text-white' : 'bg-gray-700 text-white' }}">
                        {{ ucfirst($banner->status) }}
                    </span>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">{{ $banner->title }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $banner->subtitle ?: 'No subtitle provided.' }}</p>
                    </div>

                    @if($banner->link)
                        <div class="text-[11px] text-primary truncate font-mono">
                            {{ $banner->link }}
                        </div>
                    @endif

                    <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <span class="text-gray-400 text-[10px] font-mono">{{ $banner->created_at ? $banner->created_at->format('M d, Y') : '' }}</span>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="p-1.5 text-gray-500 hover:text-primary rounded-lg">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" onsubmit="return confirm('Delete banner?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 rounded-lg">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-gray-400 bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800">
                No banners or slider graphics uploaded.
            </div>
        @endforelse
    </div>

</div>
@endsection
