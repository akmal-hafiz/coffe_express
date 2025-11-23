<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Show the order status page for authenticated user
     */
    public function status()
    {
        $order = Auth::user()->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->latest()
            ->first();

        return view('order-status', compact('order'));
    }

    /**
     * Store a new order from checkout
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'pickup_option' => 'required|in:pickup,delivery',
            'payment_method' => 'required|string',
            'items' => 'required|json',
            'total_price' => 'required|numeric|min:0',
        ]);

        // If delivery, address is required
        if ($validated['pickup_option'] === 'delivery' && empty($validated['address'])) {
            return back()->withErrors(['address' => 'Address is required for delivery orders.']);
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'items' => json_decode($validated['items'], true),
            'total_price' => $validated['total_price'],
            'pickup_option' => $validated['pickup_option'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Your order is being prepared ☕');
    }

    /**
     * Show user's order history
     */
    public function history()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('order-history', compact('orders'));
    }
}
