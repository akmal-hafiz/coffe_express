<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loyalty Management — Coffee Express Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .chart-container { position: relative; height: 250px; width: 100%; }
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
                    <p class="text-xs text-white/80">Loyalty Management</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Dashboard</a>
                    <a href="{{ route('admin.menus.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Menu</a>
                    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Reviews</a>
                    <a href="{{ route('admin.loyalty.index') }}" class="px-3 py-2 rounded-lg bg-white/20 transition flex items-center gap-1">
                        <i data-feather="gift" class="w-4 h-4"></i>
                        Loyalty
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Reports</a>
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
            <h1 class="text-3xl font-bold text-coffee mb-2">Loyalty Program Management</h1>
            <p class="text-gray-600">Kelola poin, rewards, dan tier membership</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center gap-3">
                    <i data-feather="check-circle" class="text-green-600 w-5 h-5"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center gap-3">
                    <i data-feather="alert-circle" class="text-red-600 w-5 h-5"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Poin Diberikan</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_points_issued'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i data-feather="plus-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Poin Ditukar</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_points_redeemed'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i data-feather="minus-circle" class="w-6 h-6 text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Rewards Aktif</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_active_rewards'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i data-feather="gift" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Penukaran</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_redemptions'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i data-feather="repeat" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Pending Redemptions</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_redemptions'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i data-feather="clock" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.loyalty.rewards') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3 group">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition">
                    <i data-feather="gift" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Kelola Rewards</p>
                    <p class="text-xs text-gray-500">Buat & edit rewards</p>
                </div>
            </a>
            <a href="{{ route('admin.loyalty.redemptions') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3 group">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition">
                    <i data-feather="check-square" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Redemptions</p>
                    <p class="text-xs text-gray-500">Kelola penukaran</p>
                </div>
            </a>
            <a href="{{ route('admin.loyalty.tiers') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3 group">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center group-hover:bg-yellow-200 transition">
                    <i data-feather="award" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Kelola Tiers</p>
                    <p class="text-xs text-gray-500">Atur level membership</p>
                </div>
            </a>
            <a href="{{ route('admin.loyalty.transactions') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3 group">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition">
                    <i data-feather="list" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Transaksi Poin</p>
                    <p class="text-xs text-gray-500">Lihat semua transaksi</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Tier Distribution Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i data-feather="pie-chart" class="w-5 h-5 text-coffee"></i>
                        Distribusi Member per Tier
                    </h2>
                    <div class="chart-container">
                        <canvas id="tierChart"></canvas>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i data-feather="activity" class="w-5 h-5 text-blue-600"></i>
                            Transaksi Poin Terbaru
                        </h2>
                        <a href="{{ route('admin.loyalty.transactions') }}" class="text-coffee text-sm font-medium hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Poin</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentTransactions ?? [] as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-coffee/10 flex items-center justify-center text-coffee font-bold text-xs">
                                                    {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-medium">{{ $transaction->user->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $typeColors = [
                                                    'earned' => 'bg-green-100 text-green-700',
                                                    'redeemed' => 'bg-blue-100 text-blue-700',
                                                    'bonus' => 'bg-purple-100 text-purple-700',
                                                    'expired' => 'bg-red-100 text-red-700',
                                                    'adjustment' => 'bg-yellow-100 text-yellow-700',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $typeColors[$transaction->type] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold {{ $transaction->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">
                                            {{ $transaction->description }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $transaction->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            Belum ada transaksi poin
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Redemptions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i data-feather="gift" class="w-5 h-5 text-purple-600"></i>
                            Penukaran Terbaru
                        </h2>
                        <a href="{{ route('admin.loyalty.redemptions') }}" class="text-coffee text-sm font-medium hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentRedemptions ?? [] as $redemption)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i data-feather="gift" class="w-5 h-5 text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $redemption->reward->name ?? 'Unknown Reward' }}</p>
                                        <p class="text-sm text-gray-500">{{ $redemption->user->name ?? 'Unknown' }} • {{ $redemption->code }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'used' => 'bg-green-100 text-green-700',
                                            'expired' => 'bg-red-100 text-red-700',
                                            'cancelled' => 'bg-gray-100 text-gray-700',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$redemption->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($redemption->status) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $redemption->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                <p>Belum ada penukaran</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Manual Points Form -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i data-feather="plus" class="w-5 h-5 text-green-600"></i>
                        Berikan Poin Manual
                    </h2>
                    <form action="{{ route('admin.loyalty.award-points') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User</label>
                                <select name="user_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                                    <option value="">-- Pilih User --</option>
                                    @foreach($topUsers ?? [] as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Poin</label>
                                <input type="number" name="points" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent" placeholder="100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <input type="text" name="description" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent" placeholder="Bonus dari admin">
                            </div>
                            <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                                <i data-feather="plus-circle" class="w-4 h-4"></i>
                                Berikan Poin
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Top Users -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i data-feather="users" class="w-5 h-5 text-orange-600"></i>
                        Top Users by Points
                    </h2>
                    <div class="space-y-3">
                        @forelse($topUsers ?? [] as $index => $user)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                    {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-orange-400 text-orange-900' : 'bg-gray-100 text-gray-600')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ $user->loyalty_tier ?? 'bronze' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-coffee">{{ number_format($user->loyalty_points) }}</p>
                                    <p class="text-xs text-gray-400">poin</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500">
                                <p>Belum ada data user</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tier Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i data-feather="layers" class="w-5 h-5 text-purple-600"></i>
                        Tier Summary
                    </h2>
                    <div class="space-y-3">
                        @foreach($tiers ?? [] as $tier)
                            @php
                                $tierColors = [
                                    'bronze' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => '🥉'],
                                    'silver' => ['bg' => 'bg-gray-200', 'text' => 'text-gray-700', 'icon' => '🥈'],
                                    'gold' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => '⭐'],
                                    'platinum' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => '👑'],
                                ];
                                $colors = $tierColors[$tier->name] ?? $tierColors['bronze'];
                            @endphp
                            <div class="flex items-center justify-between p-3 {{ $colors['bg'] }} rounded-xl">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">{{ $colors['icon'] }}</span>
                                    <span class="font-semibold capitalize {{ $colors['text'] }}">{{ $tier->name }}</span>
                                </div>
                                <span class="font-bold {{ $colors['text'] }}">{{ $tierDistribution[$tier->name] ?? 0 }} users</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        feather.replace();

        // Tier Distribution Chart
        const tierCtx = document.getElementById('tierChart');
        if (tierCtx) {
            const tierData = @json($tierDistribution ?? []);
            const labels = Object.keys(tierData);
            const data = Object.values(tierData);

            new Chart(tierCtx, {
                type: 'doughnut',
                data: {
                    labels: labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#CD7F32', // Bronze
                            '#C0C0C0', // Silver
                            '#FFD700', // Gold
                            '#E5E4E2', // Platinum
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
