@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Partners']
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Partners & Brands</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Manage affiliated partner brand logos and links.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.partners.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span>Add Partner</span>
            </a>
        </div>
    </div>

    <!-- Partners Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($partners as $partner)
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between space-y-4">
                <div class="h-20 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-3 border border-gray-100 dark:border-gray-700/60 overflow-hidden">
                    @if($partner->logo)
                        <img src="{{ str_starts_with($partner->logo, 'http') ? $partner->logo : asset('storage/' . $partner->logo) }}" class="max-h-full max-w-full object-contain">
                    @else
                        <span class="text-xs font-bold text-gray-400">No Logo</span>
                    @endif
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $partner->name }}</h3>
                    @if($partner->website)
                        <a href="{{ $partner->website }}" target="_blank" class="text-[11px] text-primary hover:underline truncate block mt-0.5">
                            {{ $partner->website }}
                        </a>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800 text-xs">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $partner->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($partner->status) }}
                    </span>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.partners.edit', $partner->id) }}" class="p-1 text-gray-500 hover:text-primary rounded-lg">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner->id) }}" onsubmit="return confirm('Delete partner?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-gray-500 hover:text-rose-600 rounded-lg">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-400 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
                No partners found.
            </div>
        @endforelse
    </div>

</div>
@endsection
