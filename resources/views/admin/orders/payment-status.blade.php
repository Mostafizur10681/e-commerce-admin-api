@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Orders', 'url' => route('admin.orders.index')],
            ['label' => 'Payment Statuses']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Payment Status Configuration</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Manage available payment status badges across the system.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">Add Payment Status</h3>
            <form method="POST" action="{{ route('admin.orders.payment-status.store') }}" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Paid, Pending, Refunded" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Status Flag</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all cursor-pointer">
                    Save Payment Status
                </button>
            </form>
        </div>

        <!-- Table List -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-3.5">Name</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($statuses as $status)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="px-5 py-4 font-bold text-gray-900 dark:text-white">{{ $status->name }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $status->status === 'active' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($status->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right text-gray-400 font-mono text-[11px]">
                                    {{ $status->created_at ? $status->created_at->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-400">No payment statuses configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
