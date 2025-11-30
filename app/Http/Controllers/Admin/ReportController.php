<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        // Summary statistics
        $stats = [
            'total_orders' => Order::count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_price'),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_reviews' => Review::count(),
            'avg_rating' => round(Review::approved()->avg('rating'), 1) ?: 0,
        ];

        // Revenue by month (last 6 months)
        $monthlyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('SUM(total_price) as revenue')
            ->selectRaw('COUNT(*) as orders')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Top selling items
        $topItems = $this->getTopSellingItems(10);

        return view('admin.reports.index', compact('stats', 'monthlyRevenue', 'topItems'));
    }

    /**
     * Export orders to Excel.
     */
    public function exportOrdersExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $query = Order::with('user');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $filename = 'orders_report_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Order ID', 'Customer', 'Phone', 'Type', 'Status', 'Total (Rp)', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->customer_name,
                    $order->phone,
                    ucfirst($order->pickup_option),
                    ucfirst($order->status),
                    $order->total_price,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export orders to PDF.
     */
    public function exportOrdersPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $query = Order::with('user');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary
        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->where('status', 'completed')->sum('total_price'),
            'completed' => $orders->where('status', 'completed')->count(),
            'pending' => $orders->where('status', 'pending')->count(),
            'preparing' => $orders->where('status', 'preparing')->count(),
            'ready' => $orders->where('status', 'ready')->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.orders-pdf', [
            'orders' => $orders,
            'summary' => $summary,
            'startDate' => $startDate ? Carbon::parse($startDate)->format('d M Y') : 'All time',
            'endDate' => $endDate ? Carbon::parse($endDate)->format('d M Y') : 'Present',
            'generatedAt' => Carbon::now()->format('d M Y H:i:s'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('orders_report_' . Carbon::now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Export revenue report to Excel.
     */
    public function exportRevenueExcel(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $groupBy = $request->input('group_by', 'day');

        $groupFormat = match ($groupBy) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $revenueData = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as period")
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->selectRaw('AVG(total_price) as average_order_value')
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        $filename = 'revenue_report_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($revenueData) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Period', 'Total Orders', 'Revenue (Rp)', 'Avg Order Value (Rp)']);

            foreach ($revenueData as $data) {
                fputcsv($file, [
                    $data->period,
                    $data->total_orders,
                    $data->total_revenue,
                    round($data->average_order_value, 0),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export revenue report to PDF.
     */
    public function exportRevenuePdf(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $groupBy = $request->input('group_by', 'day');

        // Get revenue data
        $groupFormat = match ($groupBy) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $revenueData = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as period")
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->selectRaw('AVG(total_price) as average_order_value')
            ->selectRaw("SUM(CASE WHEN pickup_option = 'pickup' THEN 1 ELSE 0 END) as pickup_orders")
            ->selectRaw("SUM(CASE WHEN pickup_option = 'delivery' THEN 1 ELSE 0 END) as delivery_orders")
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // Calculate summary
        $summary = [
            'total_orders' => $revenueData->sum('total_orders'),
            'total_revenue' => $revenueData->sum('total_revenue'),
            'average_order_value' => $revenueData->avg('average_order_value'),
            'total_pickup' => $revenueData->sum('pickup_orders'),
            'total_delivery' => $revenueData->sum('delivery_orders'),
        ];

        $pdf = Pdf::loadView('admin.reports.revenue-pdf', [
            'revenueData' => $revenueData,
            'summary' => $summary,
            'groupBy' => $groupBy,
            'startDate' => $startDate->format('d M Y'),
            'endDate' => $endDate->format('d M Y'),
            'generatedAt' => Carbon::now()->format('d M Y H:i:s'),
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('revenue_report_' . Carbon::now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Export users report to Excel.
     */
    public function exportUsersExcel(Request $request)
    {
        $users = User::where('role', '!=', 'admin')
            ->withCount('orders')
            ->withSum(['orders' => function ($query) {
                $query->where('status', 'completed');
            }], 'total_price')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'users_report_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Total Spent (Rp)', 'Registered']);

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '-',
                    $user->orders_count,
                    $user->orders_sum_total_price ?? 0,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export users report to PDF.
     */
    public function exportUsersPdf(Request $request)
    {
        $users = User::where('role', '!=', 'admin')
            ->withCount('orders')
            ->withSum(['orders' => function ($query) {
                $query->where('status', 'completed');
            }], 'total_price')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_users' => $users->count(),
            'total_orders' => $users->sum('orders_count'),
            'total_revenue' => $users->sum('orders_sum_total_price'),
            'avg_orders_per_user' => $users->count() > 0 ? round($users->sum('orders_count') / $users->count(), 1) : 0,
        ];

        $pdf = Pdf::loadView('admin.reports.users-pdf', [
            'users' => $users,
            'summary' => $summary,
            'generatedAt' => Carbon::now()->format('d M Y H:i:s'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('users_report_' . Carbon::now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Get top selling items.
     */
    protected function getTopSellingItems(int $limit = 10): array
    {
        $orders = Order::where('status', 'completed')->get();

        $itemCounts = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $name = $item['name'];
                if (!isset($itemCounts[$name])) {
                    $itemCounts[$name] = [
                        'name' => $name,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                $itemCounts[$name]['quantity'] += $item['qty'];
                $itemCounts[$name]['revenue'] += $item['price'] * $item['qty'];
            }
        }

        // Sort by quantity descending
        usort($itemCounts, function ($a, $b) {
            return $b['quantity'] - $a['quantity'];
        });

        return array_slice($itemCounts, 0, $limit);
    }

    /**
     * Daily report summary (for dashboard widget or API).
     */
    public function dailySummary(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $orders = Order::whereDate('created_at', $date)->get();

        $summary = [
            'date' => $date->format('Y-m-d'),
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'total_revenue' => $orders->where('status', 'completed')->sum('total_price'),
            'pickup_orders' => $orders->where('pickup_option', 'pickup')->count(),
            'delivery_orders' => $orders->where('pickup_option', 'delivery')->count(),
            'new_users' => User::whereDate('created_at', $date)->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json($summary);
        }

        return $summary;
    }
}
