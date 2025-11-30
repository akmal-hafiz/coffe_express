<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards Catalog — Coffee Express</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        .reward-card { transition: all 0.3s ease; }
        .reward-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-cream min-h-screen">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-neutral-200/70">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brown text-white shadow">
                    <i data-feather="coffee" class="w-5 h-5"></i>
                </span>
                <span class="text-xl font-extrabold tracking-tight text-coffee">Coffee Express</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('menu') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">Menu</a>
                <a href="{{ route('order.history') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">My Orders</a>
                <a href="{{ route('loyalty.index') }}" class="text-sm font-medium text-coffee transition">Loyalty</a>

                <!-- Points Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-full text-sm font-semibold">
                    <i data-feather="star" class="w-4 h-4"></i>
                    <span>{{ number_format($user->loyalty_points ?? 0) }} pts</span>
                </div>

                <!-- Language Switcher -->
                <div class="flex items-center gap-1 border border-gray-200 rounded-lg overflow-hidden">
                    <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs {{ app()->getLocale() == 'id' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">🇮🇩</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs {{ app()->getLocale() == 'en' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">🇬🇧</a>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-600 transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('loyalty.index') }}" class="hover:text-coffee transition">Loyalty</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <span class="text-coffee font-medium">Rewards Catalog</span>
        </nav>

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

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-coffee">Rewards Catalog</h1>
                <p class="text-gray-600 mt-1">Tukarkan poin Anda dengan reward menarik</p>
            </div>

            <!-- Points Summary Card -->
            <div class="flex items-center gap-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-2xl px-6 py-4">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="award" class="w-7 h-7"></i>
                </div>
                <div>
                    <p class="text-white/80 text-sm">Poin Tersedia</p>
                    <p class="text-3xl font-bold">{{ number_format($user->loyalty_points ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Rewards Grid -->
        @if($rewards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rewards as $reward)
                    @php
                        $canRedeem = ($user->loyalty_points ?? 0) >= $reward->points_required;
                        $typeColors = [
                            'discount' => ['bg' => 'from-green-500 to-emerald-500', 'icon' => '💰', 'label' => 'Diskon'],
                            'free_item' => ['bg' => 'from-orange-500 to-amber-500', 'icon' => '☕', 'label' => 'Free Item'],
                            'voucher' => ['bg' => 'from-blue-500 to-cyan-500', 'icon' => '🎟️', 'label' => 'Voucher'],
                            'merchandise' => ['bg' => 'from-purple-500 to-pink-500', 'icon' => '🎁', 'label' => 'Merchandise'],
                        ];
                        $typeInfo = $typeColors[$reward->type] ?? $typeColors['voucher'];
                    @endphp
                    <div class="reward-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 {{ !$canRedeem ? 'opacity-75' : '' }}">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r {{ $typeInfo['bg'] }} p-6 text-white relative">
                            <div class="absolute top-4 right-4 text-4xl">{{ $typeInfo['icon'] }}</div>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold">
                                {{ $typeInfo['label'] }}
                            </span>
                            <h3 class="text-xl font-bold mt-3 pr-12">{{ $reward->name }}</h3>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $reward->description ?? 'Tukarkan poin Anda untuk mendapatkan reward ini.' }}
                            </p>

                            <!-- Reward Details -->
                            <div class="space-y-2 mb-4">
                                @if($reward->discount_percentage)
                                    <div class="flex items-center gap-2 text-sm">
                                        <i data-feather="percent" class="w-4 h-4 text-green-500"></i>
                                        <span class="text-gray-700">Diskon {{ $reward->discount_percentage }}%</span>
                                    </div>
                                @endif
                                @if($reward->discount_amount)
                                    <div class="flex items-center gap-2 text-sm">
                                        <i data-feather="dollar-sign" class="w-4 h-4 text-green-500"></i>
                                        <span class="text-gray-700">Potongan Rp{{ number_format($reward->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($reward->stock !== -1)
                                    <div class="flex items-center gap-2 text-sm">
                                        <i data-feather="package" class="w-4 h-4 text-blue-500"></i>
                                        <span class="text-gray-700">Stok: {{ $reward->stock > 0 ? $reward->stock . ' tersisa' : 'Habis' }}</span>
                                    </div>
                                @endif
                                @if($reward->valid_until)
                                    <div class="flex items-center gap-2 text-sm">
                                        <i data-feather="calendar" class="w-4 h-4 text-orange-500"></i>
                                        <span class="text-gray-700">Berlaku sampai {{ $reward->valid_until->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Points Required -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">Poin Dibutuhkan</p>
                                    <p class="text-2xl font-bold text-purple-600">{{ number_format($reward->points_required) }}</p>
                                </div>

                                @if($canRedeem && ($reward->stock === -1 || $reward->stock > 0))
                                    <form action="{{ route('loyalty.redeem', $reward) }}" method="POST" onsubmit="return confirm('Yakin ingin menukarkan {{ number_format($reward->points_required) }} poin untuk {{ $reward->name }}?');">
                                        @csrf
                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r {{ $typeInfo['bg'] }} text-white font-semibold rounded-xl hover:shadow-lg transition transform hover:scale-105">
                                            <span class="flex items-center gap-2">
                                                <i data-feather="gift" class="w-4 h-4"></i>
                                                Tukar
                                            </span>
                                        </button>
                                    </form>
                                @elseif($reward->stock === 0)
                                    <button disabled class="px-6 py-3 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @else
                                    <div class="text-right">
                                        <button disabled class="px-6 py-3 bg-gray-100 text-gray-400 font-semibold rounded-xl cursor-not-allowed">
                                            Poin Kurang
                                        </button>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Butuh {{ number_format($reward->points_required - ($user->loyalty_points ?? 0)) }} lagi
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($rewards->hasPages())
                <div class="mt-8">
                    {{ $rewards->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                    <i data-feather="gift" class="w-12 h-12 text-purple-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Reward</h2>
                <p class="text-gray-600 mb-6">Reward akan segera tersedia. Terus kumpulkan poin Anda!</p>
                <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
                    <i data-feather="coffee" class="w-5 h-5"></i>
                    Pesan Sekarang
                </a>
            </div>
        @endif

        <!-- How to Earn Points -->
        <div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-coffee mb-6 flex items-center gap-2">
                <i data-feather="help-circle" class="w-6 h-6"></i>
                Cara Mendapatkan Poin
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4">
                    <div class="w-16 h-16 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-3xl">🛒</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Belanja</h3>
                    <p class="text-sm text-gray-600">1 poin setiap Rp1.000 pembelian</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-16 h-16 mx-auto mb-3 bg-yellow-100 rounded-full flex items-center justify-center">
                        <span class="text-3xl">⭐</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Review</h3>
                    <p class="text-sm text-gray-600">Bonus poin untuk setiap review</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-16 h-16 mx-auto mb-3 bg-pink-100 rounded-full flex items-center justify-center">
                        <span class="text-3xl">🎂</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Birthday</h3>
                    <p class="text-sm text-gray-600">Poin spesial di hari ulang tahun</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-16 h-16 mx-auto mb-3 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-3xl">📢</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Promo</h3>
                    <p class="text-sm text-gray-600">Bonus poin dari promo khusus</p>
                </div>
            </div>
        </div>

        <!-- Back to Loyalty -->
        <div class="mt-8 text-center">
            <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 text-coffee font-medium hover:underline">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Kembali ke Loyalty Dashboard
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="max-w-6xl mx-auto px-4 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Coffee Express. All rights reserved.</p>
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>
