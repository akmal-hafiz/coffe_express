<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rewards Management — Coffee Express Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <p class="text-xs text-white/80">Rewards Management</p>
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
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('admin.loyalty.index') }}" class="hover:text-coffee transition">Loyalty</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <span class="text-coffee font-medium">Rewards Management</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-coffee mb-2">Rewards Management</h1>
                <p class="text-gray-600">Kelola katalog rewards untuk program loyalitas</p>
            </div>
            <a href="{{ route('admin.loyalty.rewards.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition shadow-md">
                <i data-feather="plus" class="w-5 h-5"></i>
                Tambah Reward Baru
            </a>
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

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.loyalty.rewards') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama reward..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
                            <option value="">Semua Tipe</option>
                            <option value="discount" {{ request('type') == 'discount' ? 'selected' : '' }}>Diskon</option>
                            <option value="free_item" {{ request('type') == 'free_item' ? 'selected' : '' }}>Free Item</option>
                            <option value="voucher" {{ request('type') == 'voucher' ? 'selected' : '' }}>Voucher</option>
                            <option value="merchandise" {{ request('type') == 'merchandise' ? 'selected' : '' }}>Merchandise</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-3">
                        <button type="submit" class="px-6 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition flex items-center gap-2">
                            <i data-feather="search" class="w-4 h-4"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.loyalty.rewards') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                            <i data-feather="x" class="w-4 h-4"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rewards Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h2 class="text-xl font-bold flex items-center gap-2 text-gray-800">
                    <i data-feather="gift" class="w-5 h-5 text-purple-600"></i>
                    Daftar Rewards
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Reward</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Poin</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Berlaku</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($rewards as $reward)
                            @php
                                $typeColors = [
                                    'discount' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => '💰'],
                                    'free_item' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => '☕'],
                                    'voucher' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => '🎟️'],
                                    'merchandise' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => '🎁'],
                                ];
                                $typeInfo = $typeColors[$reward->type] ?? $typeColors['voucher'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 {{ $typeInfo['bg'] }} rounded-lg flex items-center justify-center text-2xl">
                                            {{ $typeInfo['icon'] }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $reward->name }}</p>
                                            <p class="text-sm text-gray-500 max-w-xs truncate">{{ $reward->description ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $typeInfo['bg'] }} {{ $typeInfo['text'] }}">
                                        {{ ucfirst(str_replace('_', ' ', $reward->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-purple-600">{{ number_format($reward->points_required) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($reward->discount_percentage)
                                        <span class="text-green-600 font-medium">{{ $reward->discount_percentage }}%</span>
                                    @elseif($reward->discount_amount)
                                        <span class="text-green-600 font-medium">Rp{{ number_format($reward->discount_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($reward->stock === -1)
                                        <span class="text-blue-600 font-medium">Unlimited</span>
                                    @elseif($reward->stock === 0)
                                        <span class="text-red-600 font-medium">Habis</span>
                                    @elseif($reward->stock <= 10)
                                        <span class="text-orange-600 font-medium">{{ $reward->stock }} (Low)</span>
                                    @else
                                        <span class="text-gray-700">{{ $reward->stock }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($reward->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                                            Non-aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($reward->valid_from || $reward->valid_until)
                                        @if($reward->valid_from)
                                            {{ $reward->valid_from->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                        <br>
                                        <span class="text-xs">s/d</span>
                                        <br>
                                        @if($reward->valid_until)
                                            {{ $reward->valid_until->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        <span class="text-gray-400">Tidak terbatas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.loyalty.rewards.edit', $reward) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                            <i data-feather="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.loyalty.rewards.destroy', $reward) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i data-feather="gift" class="w-16 h-16 mx-auto mb-2 text-gray-300"></i>
                                    <p class="text-lg font-medium">Belum ada reward</p>
                                    <p class="text-sm mb-4">Buat reward pertama untuk program loyalitas</p>
                                    <a href="{{ route('admin.loyalty.rewards.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition">
                                        <i data-feather="plus" class="w-4 h-4"></i>
                                        Tambah Reward
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($rewards->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $rewards->links() }}
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('admin.loyalty.index') }}" class="inline-flex items-center gap-2 text-coffee font-medium hover:underline">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Kembali ke Loyalty Dashboard
            </a>
        </div>
    </main>

    <script>
        feather.replace();

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Reward?',
                    text: "Reward yang sudah dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
