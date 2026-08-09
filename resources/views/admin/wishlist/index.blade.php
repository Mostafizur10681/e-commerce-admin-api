@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Wishlist']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Customer Wishlists</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">View items saved to wishlists by registered customers.</p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Saved Product</th>
                        <th class="px-5 py-3.5">Price</th>
                        <th class="px-5 py-3.5">Stock</th>
                        <th class="px-5 py-3.5 text-right">Added On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($wishlists as $item)
                        @php
                            $p = $item->product;
                            $img = $p ? ($p->image ?: ($p->images->first()->image_path ?? '')) : '';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-5 py-4 font-bold text-gray-900 dark:text-white">
                                {{ $item->user->name ?? 'Guest/Anonymous' }}
                                <span class="text-gray-400 font-mono block text-[11px] font-normal">{{ $item->user->email ?? '' }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 shrink-0 flex items-center justify-center">
                                        @if($img)
                                            <img src="{{ str_starts_with($img, 'data:image') || str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $p->name ?? 'Deleted product' }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-4 font-bold text-gray-900 dark:text-white">
                                ৳{{ $p ? number_format($p->price, 2) : '0.00' }}
                            </td>

                            <td class="px-5 py-4">
                                @if($p && $p->stock > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">In Stock</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Out of Stock</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right text-gray-400 font-mono text-[11px]">
                                {{ $item->created_at ? $item->created_at->format('M d, Y') : '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400">No wishlist records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($wishlists->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                {{ $wishlists->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
