<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('comment', 'like', "%{$s}%")
                  ->orWhere('author_name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::where('role', 'customer')->get();
        return view('admin.reviews.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'author_name' => 'required|string|max:255',
            'author_designation' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'status' => 'required|in:approved,pending,rejected',
        ]);

        Review::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Review added successfully!');
    }

    public function toggleStatus($id)
    {
        $review = Review::findOrFail($id);
        $review->status = $review->status === 'approved' ? 'pending' : 'approved';
        $review->save();

        return back()->with('success', 'Review status updated to ' . $review->status);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully!');
    }
}
