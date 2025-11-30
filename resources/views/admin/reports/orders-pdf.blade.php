<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orders Report - Coffee Express</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            background: linear-gradient(135deg, #6F4E37 0%, #6B4F4F 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 10px;
        }

        .summary-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #F5EBDD;
            border-radius: 8px;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #6F4E37;
            margin-bottom: 10px;
            border-bottom: 2px solid #6F4E37;
            padding-bottom: 5px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 16.66%;
            text-align: center;
            padding: 10px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #6F4E37;
        }

        .summary-label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #6F4E37;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #E5E0DA;
            font-size: 9px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #FAFAFA;
        }

        tr:hover {
            background-color: #F5EBDD;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #F3F4F6;
            color: #4B5563;
        }

        .status-preparing {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .status-ready {
            background-color: #D1FAE5;
            color: #059669;
        }

        .status-completed {
            background-color: #DBEAFE;
            color: #2563EB;
        }

        .pickup-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
        }

        .pickup-pickup {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .pickup-delivery {
            background-color: #D1FAE5;
            color: #166534;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-coffee {
            color: #6F4E37;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding: 10px;
            border-top: 1px solid #E5E0DA;
        }

        .page-break {
            page-break-after: always;
        }

        .items-list {
            font-size: 8px;
            color: #666;
        }

        .order-id {
            font-weight: bold;
            color: #6F4E37;
        }

        .price {
            font-weight: bold;
            color: #059669;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <h1>☕ Coffee Express</h1>
                    <p>Orders Report</p>
                </td>
                <td style="width: 40%; text-align: right;">
                    <p><strong>Period:</strong> {{ $startDate }} - {{ $endDate }}</p>
                    <p><strong>Generated:</strong> {{ $generatedAt }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Report Summary</div>
        <table style="width: 100%;">
            <tr>
                <td style="width: 16.66%; text-align: center; padding: 10px;">
                    <div class="summary-value">{{ $summary['total_orders'] }}</div>
                    <div class="summary-label">Total Orders</div>
                </td>
                <td style="width: 16.66%; text-align: center; padding: 10px;">
                    <div class="summary-value">Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                    <div class="summary-label">Total Revenue</div>
                </td>
                <td style="width: 16.66%; text-align: center; padding: 10px;">
                    <div class="summary-value">{{ $summary['completed'] }}</div>
                    <div class="summary-label">Completed</div>
                </td>
                <td style="width: 16.66%; text-align: center; padding: 10px;">
                    <div class="summary-value">{{ $summary['pending'] }}</div>
                    <div class="summary-label">Pending</div>
                </td>
                <td style="width: 16.66%; text-align: center; padding: 10px;">
                    <div class="summary-value">{{ $summary['preparing'] }}</div>
                    <div class="summary-label">Preparing</div>
                </td>
                <td style="width: 16.66%; text-align: center; padding: 10px;">
                    <div class="summary-value">{{ $summary['ready'] }}</div>
                    <div class="summary-label">Ready</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Order ID</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 15%;">Customer</th>
                <th style="width: 25%;">Items</th>
                <th style="width: 10%;">Total</th>
                <th style="width: 8%;">Type</th>
                <th style="width: 10%;">Payment</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="order-id">#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}<br><small>{{ $order->created_at->format('H:i') }}</small></td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong><br>
                        <small>{{ $order->phone }}</small>
                    </td>
                    <td class="items-list">
                        @foreach($order->items as $item)
                            {{ $item['qty'] }}x {{ $item['name'] }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="pickup-badge pickup-{{ $order->pickup_option }}">
                            {{ ucfirst($order->pickup_option) }}
                        </span>
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 30px;">
                        No orders found for the selected period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Coffee Express - Orders Report | Generated on {{ $generatedAt }} | Page {PAGE_NUM} of {PAGE_COUNT}</p>
    </div>
</body>
</html>
