<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $query = Wishlist::with(['user', 'product.images']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', function ($uq) use ($s) {
                    $uq->where('name', 'like', "%{$s}%")
                       ->orWhere('email', 'like', "%{$s}%")
                       ->orWhere('phone', 'like', "%{$s}%");
                })->orWhereHas('product', function ($pq) use ($s) {
                    $pq->where('name', 'like', "%{$s}%")
                       ->orWhere('sku', 'like', "%{$s}%");
                });
            });
        }

        if ($request->filled('stock_status')) {
            $stockStatus = $request->stock_status;
            if ($stockStatus === 'in-stock') {
                $query->whereHas('product', function ($pq) {
                    $pq->where('stock', '>', 0);
                });
            } elseif ($stockStatus === 'out-of-stock') {
                $query->whereHas('product', function ($pq) {
                    $pq->where('stock', '<=', 0);
                });
            }
        }

        $perPage = $request->input('per_page', 10);
        $wishlists = $query->latest()->paginate($perPage)->withQueryString();

        $stats = [
            'total' => Wishlist::count(),
            'unique_customers' => Wishlist::distinct('user_id')->count('user_id'),
            'in_stock' => Wishlist::whereHas('product', function ($q) { $q->where('stock', '>', 0); })->count(),
            'out_of_stock' => Wishlist::whereHas('product', function ($q) { $q->where('stock', '<=', 0); })->count(),
        ];

        return view('admin.wishlist.index', compact('wishlists', 'stats'));
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return redirect()->route('admin.wishlist.index')->with('success', 'Wishlist item removed successfully!');
    }
}
