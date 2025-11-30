<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #6F4E37;
        }
        .info {
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #6F4E37;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .summary-item {
            padding: 8px;
            background-color: white;
            border-radius: 3px;
        }
        .summary-item strong {
            display: block;
            color: #666;
            font-size: 10px;
            margin-bottom: 4px;
        }
        .summary-item span {
            font-size: 16px;
            font-weight: bold;
            color: #6F4E37;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Coffee Express - Revenue Report</h1>
        <p>Period: {{ $startDate }} - {{ $endDate }}</p>
        <p style="font-size: 10px; color: #666;">Generated at: {{ $generatedAt }}</p>
    </div>

    <div class="summary">
        <h3 style="margin-top: 0;">Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>Total Orders</strong>
                <span>{{ number_format($summary['total_orders']) }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Revenue</strong>
                <span>Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <strong>Average Order Value</strong>
                <span>Rp{{ number_format($summary['average_order_value'], 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <strong>Pickup Orders</strong>
                <span>{{ number_format($summary['total_pickup']) }}</span>
            </div>
            <div class="summary-item">
                <strong>Delivery Orders</strong>
                <span>{{ number_format($summary['total_delivery']) }}</span>
            </div>
        </div>
    </div>

    <h3>Revenue Breakdown (Grouped by {{ ucfirst($groupBy) }})</h3>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Total Orders</th>
                <th>Revenue</th>
                <th>Avg Order Value</th>
                <th>Pickup</th>
                <th>Delivery</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueData as $data)
            <tr>
                <td>{{ $data->period }}</td>
                <td>{{ number_format($data->total_orders) }}</td>
                <td>Rp{{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($data->average_order_value, 0, ',', '.') }}</td>
                <td>{{ number_format($data->pickup_orders) }}</td>
                <td>{{ number_format($data->delivery_orders) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
