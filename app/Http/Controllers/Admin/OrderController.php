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
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pickup option
        if ($request->filled('pickup_option')) {
            $query->where('pickup_option', $request->pickup_option);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

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

        // Chart data: Revenue last 7 days
        $revenueLast7Days = [];
        $datesLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $datesLast7Days[] = $date->format('d M');
            $revenueLast7Days[] = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }

        $chartData = [
            'revenue' => [
                'labels' => $datesLast7Days,
                'data' => $revenueLast7Days,
            ],
            'orders' => [
                'labels' => ['Pending', 'Preparing', 'Ready', 'Completed'],
                'data' => [
                    $stats['pending'],
                    $stats['preparing'],
                    $stats['ready'],
                    $stats['completed'],
                ],
            ],
        ];

        return view('admin.dashboard', compact('orders', 'stats', 'userStats', 'revenueStats', 'recentUsers', 'chartData'));
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
