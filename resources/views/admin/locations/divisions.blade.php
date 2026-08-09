@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Divisions']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Divisions of Bangladesh</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Manage top-level administrative geography for order deliveries.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Add Division</h3>
            <form method="POST" action="{{ route('admin.locations.divisions.store') }}" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Division Name (English) *</label>
                    <input type="text" name="name" required placeholder="e.g. Dhaka, Chittagong" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Division Name (Bangla)</label>
                    <input type="text" name="bn_name" placeholder="e.g. ঢাকা" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all cursor-pointer">
                    Save Division
                </button>
            </form>
        </div>

        <!-- Table List -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3.5">Division Name</th>
                            <th class="px-5 py-3.5">Bangla Name</th>
                            <th class="px-5 py-3.5">Districts</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($divisions as $div)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="px-5 py-4 font-bold text-gray-900 dark:text-white">{{ $div->name }}</td>
                                <td class="px-5 py-4 font-semibold text-gray-700 dark:text-gray-300">{{ $div->bn_name ?: '-' }}</td>
                                <td class="px-5 py-4 font-semibold text-primary">
                                    <a href="{{ route('admin.locations.districts', ['division_id' => $div->id]) }}" class="hover:underline">
                                        {{ $div->districts_count }} Districts &rarr;
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.locations.divisions.destroy', $div->id) }}" onsubmit="return confirm('Delete division?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 rounded-lg">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-400">No divisions recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
