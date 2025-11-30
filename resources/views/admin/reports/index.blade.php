<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports — Coffee Express Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coffee: '#6F4E37',
                        cream: '#F5EBDD',
                        brown: '#6B4F4F',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-cream text-gray-800">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-coffee to-brown text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow">
                    <i data-feather="coffee" class="w-6 h-6"></i>
                </span>
                <div>
                    <h1 class="text-xl font-extrabold">Coffee Express Admin</h1>
                    <p class="text-xs text-white/80">Reports & Analytics</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Dashboard</a>
                    <a href="{{ route('admin.menus.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Menu</a>
                    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Reviews</a>
                    <a href="{{ route('admin.loyalty.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Loyalty</a>
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-lg bg-white/20 transition">Reports</a>
                </nav>
                <div class="h-6 w-px bg-white/20 hidden md:block"></div>
                <span class="text-sm">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium hover:text-white/80 transition flex items-center gap-1">
                        <i data-feather="log-out" class="w-4 h-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-coffee mb-2">Reports & Analytics</h1>
            <p class="text-gray-600">Generate and export comprehensive business reports</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Total Orders</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
                    </div>
                    <i data-feather="shopping-bag" class="w-8 h-8 text-blue-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Completed</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                    </div>
                    <i data-feather="check-circle" class="w-8 h-8 text-green-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Total Revenue</p>
                        <p class="text-lg font-bold text-yellow-600">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <i data-feather="dollar-sign" class="w-8 h-8 text-yellow-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
                    </div>
                    <i data-feather="users" class="w-8 h-8 text-purple-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-pink-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Total Reviews</p>
                        <p class="text-2xl font-bold text-pink-600">{{ $stats['total_reviews'] }}</p>
                    </div>
                    <i data-feather="message-square" class="w-8 h-8 text-pink-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Avg Rating</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['avg_rating'] }} ⭐</p>
                    </div>
                    <i data-feather="star" class="w-8 h-8 text-orange-200"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-800">
                    <i data-feather="trending-up" class="w-5 h-5 text-green-600"></i>
                    Monthly Revenue (Last 6 Months)
                </h3>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Selling Items -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-800">
                    <i data-feather="award" class="w-5 h-5 text-yellow-600"></i>
                    Top Selling Items
                </h3>
                <div class="space-y-3">
                    @forelse($topItems as $index => $item)
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">{{ $item['quantity'] }} sold • Rp{{ number_format($item['revenue'], 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    @php
                                        $maxQty = $topItems[0]['quantity'] ?? 1;
                                        $percentage = ($item['quantity'] / $maxQty) * 100;
                                    @endphp
                                    <div class="bg-coffee h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No sales data yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Orders Report -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-feather="shopping-bag" class="w-8 h-8"></i>
                        <h3 class="text-xl font-bold">Orders Report</h3>
                    </div>
                    <p class="text-blue-100 text-sm">Export detailed order information</p>
                </div>
                <div class="p-6">
                    <form id="ordersForm">
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="preparing">Preparing</option>
                                    <option value="ready">Ready</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="#" onclick="exportOrders('excel')" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                                <i data-feather="file-text" class="w-4 h-4"></i>
                                Excel
                            </a>
                            <a href="#" onclick="exportOrders('pdf')" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                                <i data-feather="file" class="w-4 h-4"></i>
                                PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Revenue Report -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-feather="dollar-sign" class="w-8 h-8"></i>
                        <h3 class="text-xl font-bold">Revenue Report</h3>
                    </div>
                    <p class="text-green-100 text-sm">Export revenue analytics and trends</p>
                </div>
                <div class="p-6">
                    <form id="revenueForm">
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Group By</label>
                                <select name="group_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="day">Daily</option>
                                    <option value="week">Weekly</option>
                                    <option value="month">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="#" onclick="exportRevenue('excel')" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                                <i data-feather="file-text" class="w-4 h-4"></i>
                                Excel
                            </a>
                            <a href="#" onclick="exportRevenue('pdf')" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                                <i data-feather="file" class="w-4 h-4"></i>
                                PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Report -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-feather="users" class="w-8 h-8"></i>
                        <h3 class="text-xl font-bold">Users Report</h3>
                    </div>
                    <p class="text-purple-100 text-sm">Export customer data and statistics</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4 mb-6">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-purple-800">
                                <strong>Includes:</strong><br>
                                • User profile information<br>
                                • Order history summary<br>
                                • Loyalty points balance<br>
                                • Customer tier status
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.reports.users.excel') }}" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <i data-feather="file-text" class="w-4 h-4"></i>
                            Excel
                        </a>
                        <a href="{{ route('admin.reports.users.pdf') }}" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                            <i data-feather="file" class="w-4 h-4"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-800">
                <i data-feather="activity" class="w-5 h-5 text-coffee"></i>
                Quick Export Options
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.reports.orders.excel') }}?status=completed" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Completed Orders</p>
                        <p class="text-xs text-gray-500">Export to Excel</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports.orders.excel') }}?date={{ date('Y-m-d') }}" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Today's Orders</p>
                        <p class="text-xs text-gray-500">Export to Excel</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports.revenue.excel') }}?group_by=month" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i data-feather="bar-chart-2" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Monthly Revenue</p>
                        <p class="text-xs text-gray-500">Export to Excel</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports.users.excel') }}" class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-feather="users" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">All Customers</p>
                        <p class="text-xs text-gray-500">Export to Excel</p>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <script>
        feather.replace();

        // Revenue Chart
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($monthlyRevenue->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'))),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($monthlyRevenue->pluck('revenue')),
                        backgroundColor: 'rgba(111, 78, 55, 0.8)',
                        borderColor: '#6F4E37',
                        borderWidth: 1,
                        borderRadius: 8,
                    }, {
                        label: 'Orders',
                        data: @json($monthlyRevenue->pluck('orders')),
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: '#22C55E',
                        borderWidth: 1,
                        borderRadius: 8,
                        yAxisID: 'y1',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' orders';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Export functions
        function exportOrders(format) {
            const form = document.getElementById('ordersForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();

            if (format === 'excel') {
                window.location.href = '{{ route("admin.reports.orders.excel") }}?' + params;
            } else {
                window.location.href = '{{ route("admin.reports.orders.pdf") }}?' + params;
            }
        }

        function exportRevenue(format) {
            const form = document.getElementById('revenueForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();

            if (format === 'excel') {
                window.location.href = '{{ route("admin.reports.revenue.excel") }}?' + params;
            } else {
                window.location.href = '{{ route("admin.reports.revenue.pdf") }}?' + params;
            }
        }

        // Set default dates
        const today = new Date();
        const thirtyDaysAgo = new Date(today);
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

        document.querySelectorAll('input[name="end_date"]').forEach(input => {
            input.value = today.toISOString().split('T')[0];
        });

        document.querySelectorAll('input[name="start_date"]').forEach(input => {
            input.value = thirtyDaysAgo.toISOString().split('T')[0];
        });
    </script>
</body>
</html>
