@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Orders']
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Order Management</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Track and fulfill customer orders and payments.</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ !request('status') ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            All ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ request('status') === 'pending' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Pending ({{ $statusCounts['pending'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ request('status') === 'processing' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Processing ({{ $statusCounts['processing'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ request('status') === 'shipped' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Shipped ({{ $statusCounts['shipped'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ request('status') === 'delivered' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Delivered ({{ $statusCounts['delivered'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ request('status') === 'cancelled' ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Cancelled ({{ $statusCounts['cancelled'] }})
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <svg class="absolute top-2.5 left-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by order number, customer phone..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                >
            </div>

            <div>
                <select name="payment_status" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                    <option value="">All Payment Statuses</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Payment Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'payment_status', 'status']))
                    <a href="{{ route('admin.orders.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5">Order Number</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Items</th>
                        <th class="px-5 py-3.5">Total Amount</th>
                        <th class="px-5 py-3.5">Payment</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($orders as $order)
                        @php
                            $s = strtolower($order->status);
                            $badgeClasses = match($s) {
                                'delivered', 'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400',
                                'shipped', 'out-for-delivery' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400',
                                'processing' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400',
                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-400',
                                default => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-gray-900 dark:text-white hover:text-primary font-mono text-sm">
                                    #{{ $order->order_number }}
                                </a>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $order->created_at ? $order->created_at->format('M d, Y H:i') : '' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold text-gray-900 dark:text-white block">{{ $order->customer_name ?: ($order->user->name ?? 'Guest') }}</span>
                                <span class="text-[11px] text-gray-500 font-mono">{{ $order->customer_phone ?: ($order->user->phone ?? 'N/A') }}</span>
                            </td>

                            <td class="px-5 py-4 font-semibold text-gray-700 dark:text-gray-300">
                                {{ $order->items->count() }} Products ({{ $order->items->sum('quantity') }} items)
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-bold text-gray-900 dark:text-white text-sm">৳{{ number_format($order->total, 2) }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                    {{ ucfirst($order->payment_status ?: 'Pending') }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClasses }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-800 hover:bg-primary hover:text-white text-gray-700 dark:text-gray-300 text-xs font-bold rounded-lg transition-colors">
                                    <span>Details</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
