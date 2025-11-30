<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class SimpleReportController extends Controller
{
    /**
     * Export users to CSV (Excel compatible)
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
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
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
                    number_format($user->orders_sum_total_price ?? 0, 0, ',', '.'),
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export orders to CSV (Excel compatible)
     */
    public function exportOrdersExcel(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $filename = 'orders_report_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Order ID', 'Customer', 'Phone', 'Type', 'Status', 'Total (Rp)', 'Date']);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->customer_name,
                    $order->phone,
                    ucfirst($order->pickup_option),
                    ucfirst($order->status),
                    number_format($order->total_price, 0, ',', '.'),
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
