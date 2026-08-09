<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = Wishlist::with(['user', 'product.images'])->latest()->paginate(15);
        return view('admin.wishlist.index', compact('wishlists'));
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return redirect()->route('admin.wishlist.index')->with('success', 'Wishlist item removed successfully!');
    }
}
