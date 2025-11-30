<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users Report</title>
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
            padding: 10px;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Coffee Express - Users Report</h1>
        <p>Generated at: {{ $generatedAt }}</p>
    </div>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-item"><strong>Total Users:</strong> {{ $summary['total_users'] }}</div>
        <div class="summary-item"><strong>Total Orders:</strong> {{ $summary['total_orders'] }}</div>
        <div class="summary-item"><strong>Total Revenue:</strong> Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        <div class="summary-item"><strong>Avg Orders/User:</strong> {{ $summary['avg_orders_per_user'] }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Total Orders</th>
                <th>Total Spent</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>{{ $user->orders_count }}</td>
                <td>Rp{{ number_format($user->orders_sum_total_price ?? 0, 0, ',', '.') }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
