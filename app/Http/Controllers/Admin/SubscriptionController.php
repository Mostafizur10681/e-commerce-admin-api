<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('email', 'like', "%{$s}%");
        }

        $subscriptions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscriber email removed successfully!');
    }
}
