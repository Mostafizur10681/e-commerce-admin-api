<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::where('role', 'customer')->with(['orders.items', 'addresses', 'customerProfile'])->findOrFail($id);
        $totalSpent = Order::where('user_id', $customer->id)->where('status', '!=', 'cancelled')->sum('total');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:30|unique:users,phone',
            'password' => 'required|string|min:6',
            'status' => 'required|in:active,blocked',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'customer';

        User::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully!');
    }

    public function toggleBlock($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->status = $customer->status === 'blocked' ? 'active' : 'blocked';
        $customer->save();

        return back()->with('success', 'Customer status updated to ' . $customer->status);
    }
}
