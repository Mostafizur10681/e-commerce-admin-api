@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Newsletter Subscribers']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Newsletter Subscriptions</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Manage subscribed audience emails for marketing and promotional broadcasts.</p>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5">Email Address</th>
                        <th class="px-5 py-3.5">Subscribed Date</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-gray-900 dark:text-white text-sm">
                                {{ $sub->email }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 font-mono text-[11px]">
                                {{ $sub->created_at ? $sub->created_at->format('F d, Y h:i A') : 'N/A' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form method="POST" action="{{ route('admin.subscriptions.destroy', $sub->id) }}" onsubmit="return confirm('Remove this email?');">
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
                            <td colspan="3" class="px-5 py-12 text-center text-gray-400">No newsletter subscribers recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
