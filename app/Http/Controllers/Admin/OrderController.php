<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Events\OrderUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display admin dashboard with all orders
     */
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(20);

        // Order statistics
        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        // User statistics
        $userStats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'new_users_today' => User::where('role', 'user')
                ->whereDate('created_at', today())
                ->count(),
            'new_users_week' => User::where('role', 'user')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        // Revenue statistics
        $revenueStats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total_price'),
            'today_revenue' => Order::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('total_price'),
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        // Recent registered users
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('orders', 'stats', 'userStats', 'revenueStats', 'recentUsers'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed',
        ]);

        $order->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        // 🔴 REALTIME: Broadcast order update to user
        event(new OrderUpdated($order));

        return back()->with('success', 'Order status updated successfully! User notified in realtime.');
    }

    /**
     * Update estimated completion time
     */
    public function updateEstimatedTime(Request $request, Order $order)
    {
        $validated = $request->validate([
            'estimated_time' => 'required|integer|min:1|max:120',
        ]);

        $order->update([
            'estimated_time' => $validated['estimated_time'],
        ]);

        // 🔴 REALTIME: Broadcast estimated time update to user
        $message = "⏱️ Estimated time updated: {$validated['estimated_time']} minutes remaining";
        event(new OrderUpdated($order, $message));

        return back()->with('success', 'Estimated time updated successfully! User notified in realtime.');
    }

    /**
     * Delete an order
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted successfully!');
    }
}
