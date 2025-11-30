<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loyalty Program — Coffee Express</title>
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
        .tier-card { transition: all 0.3s ease; }
        .tier-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-cream min-h-screen">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-neutral-200/70">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i data-feather="menu" class="w-6 h-6 text-coffee"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brown text-white shadow">
                        <i data-feather="coffee" class="w-5 h-5"></i>
                    </span>
                    <span class="text-xl font-extrabold tracking-tight text-coffee">Coffee Express</span>
                </a>
            </div>
            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('menu') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">Menu</a>
                <a href="{{ route('order.history') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">My Orders</a>
                <a href="{{ route('reviews.index') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">Reviews</a>

                <!-- Language Switcher -->
                <div class="flex items-center gap-1 border border-gray-200 rounded-lg overflow-hidden">
                    <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs {{ app()->getLocale() == 'id' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">🇮🇩 ID</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs {{ app()->getLocale() == 'en' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">🇬🇧 EN</a>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-600 transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="fixed inset-0 z-50 hidden">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity opacity-0" id="mobile-menu-backdrop"></div>
  
  <!-- Menu Panel -->
  <div class="absolute right-0 top-0 h-full w-[280px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col" id="mobile-menu-panel">
    <!-- Header -->
    <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-coffee/5">
      <div class="flex items-center gap-2 text-coffee font-bold">
        <i data-feather="coffee" class="w-5 h-5"></i>
        <span>Menu</span>
      </div>
      <button id="close-mobile-menu" class="p-2 hover:bg-white rounded-full transition-colors shadow-sm">
        <i data-feather="x" class="w-5 h-5 text-gray-500"></i>
      </button>
    </div>
    
    <!-- Links -->
    <div class="flex-1 overflow-y-auto py-4">
      <nav class="flex flex-col px-4 gap-2">
        <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
          <i data-feather="home" class="w-5 h-5"></i> Home
        </a>
        <a href="{{ url('menu') }}" class="flex items-center gap-3 px-4 py-3 bg-coffee text-white rounded-xl shadow-md transition-all font-medium">
          <i data-feather="coffee" class="w-5 h-5"></i> Menu
        </a>
        <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
          <i data-feather="star" class="w-5 h-5"></i> Reviews
        </a>
        <a href="{{ url('contact') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
          <i data-feather="mail" class="w-5 h-5"></i> Contact
        </a>
        
        <div class="my-2 border-t border-gray-100"></div>
        
        @auth
          <a href="{{ route('order.status') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
            <i data-feather="package" class="w-5 h-5"></i> My Orders
          </a>
          <a href="{{ route('loyalty.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
            <i data-feather="gift" class="w-5 h-5"></i> Loyalty Program
          </a>
          <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
            <i data-feather="user" class="w-5 h-5"></i> Profile
          </a>
          <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-all font-medium">
              <i data-feather="log-out" class="w-5 h-5"></i> Logout
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
            <i data-feather="log-in" class="w-5 h-5"></i> Login
          </a>
          <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
            <i data-feather="user-plus" class="w-5 h-5"></i> Register
          </a>
        @endauth
      </nav>
    </div>
  </div>
</div>

<!-- Bottom Navigation Bar (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 safe-area-bottom">
  <div class="flex items-center justify-around px-2 py-3">
    <!-- Home -->
    <a href="{{ url('/') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-coffee">
      <i data-feather="home" class="w-5 h-5"></i>
      <span class="text-xs font-medium">Home</span>
    </a>

    <!-- Menu -->
    <a href="{{ url('menu') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors text-coffee">
      <i data-feather="coffee" class="w-5 h-5"></i>
      <span class="text-xs font-medium">Menu</span>
    </a>

    <!-- Cart (Center - Elevated) -->
    <button id="cart-icon-bottom" class="relative -mt-6 flex flex-col items-center gap-1 px-4 py-3 bg-coffee text-white rounded-2xl shadow-lg transition-transform hover:scale-105">
      <i data-feather="shopping-cart" class="w-6 h-6"></i>
      <span class="text-xs font-semibold">Cart</span>
      <span id="cart-count-bottom" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">0</span>
    </button>

    @auth
      <!-- Orders -->
      <a href="{{ route('order.status') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors {{ request()->is('order-status') ? 'text-coffee' : 'text-gray-600 hover:text-coffee' }}">
        <i data-feather="package" class="w-5 h-5"></i>
        <span class="text-xs font-medium">Orders</span>
      </a>

      <!-- Profile -->
      <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors {{ request()->is('profile*') ? 'text-coffee' : 'text-gray-600 hover:text-coffee' }}">
        <i data-feather="user" class="w-5 h-5"></i>
        <span class="text-xs font-medium">Profile</span>
      </a>
    @else
      <!-- Login -->
      <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-coffee">
        <i data-feather="log-in" class="w-5 h-5"></i>
        <span class="text-xs font-medium">Login</span>
      </a>

      <!-- Register -->
      <a href="{{ route('register') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-coffee">
        <i data-feather="user-plus" class="w-5 h-5"></i>
        <span class="text-xs font-medium">Register</span>
      </a>
    @endauth
  </div>
</nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
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

        <!-- Hero Section - Points Overview -->
        <div class="bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Points Display -->
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center backdrop-blur">
                            <i data-feather="award" class="w-12 h-12"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm font-medium mb-1">Total Poin Anda</p>
                            <p class="text-5xl font-extrabold">{{ number_format($user->loyalty_points ?? 0) }}</p>
                            <p class="text-white/80 text-sm mt-1">Poin</p>
                        </div>
                    </div>

                    <!-- Current Tier -->
                    <div class="bg-white/20 backdrop-blur rounded-2xl p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl" style="background-color: {{ $currentTier->badge_color ?? '#CD7F32' }}20;">
                                @if(($currentTier->name ?? 'bronze') == 'platinum')
                                    👑
                                @elseif(($currentTier->name ?? 'bronze') == 'gold')
                                    ⭐
                                @elseif(($currentTier->name ?? 'bronze') == 'silver')
                                    🥈
                                @else
                                    🥉
                                @endif
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Tier Saat Ini</p>
                                <p class="text-2xl font-bold capitalize">{{ $currentTier->name ?? 'Bronze' }}</p>
                                @if($currentTier && $currentTier->discount_percentage > 0)
                                    <p class="text-sm text-white/90">{{ $currentTier->discount_percentage }}% diskon semua pesanan</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Progress to Next Tier -->
                    @if($nextTier && $pointsToNextTier)
                        <div class="bg-white/20 backdrop-blur rounded-2xl p-6 min-w-[250px]">
                            <p class="text-white/80 text-sm mb-2">Progress ke {{ ucfirst($nextTier->name) }}</p>
                            <div class="w-full bg-white/30 rounded-full h-4 mb-2">
                                <div class="bg-white rounded-full h-4 transition-all duration-500" style="width: {{ $progressPercentage ?? 0 }}%"></div>
                            </div>
                            <p class="text-sm">
                                <span class="font-bold">{{ number_format($pointsToNextTier) }}</span> poin lagi
                            </p>
                        </div>
                    @else
                        <div class="bg-white/20 backdrop-blur rounded-2xl p-6">
                            <div class="flex items-center gap-3">
                                <span class="text-4xl">🎉</span>
                                <div>
                                    <p class="font-bold text-lg">Tier Tertinggi!</p>
                                    <p class="text-sm text-white/80">Anda sudah di level maksimal</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('loyalty.rewards') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i data-feather="gift" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Tukar Reward</p>
                    <p class="text-xs text-gray-500">{{ $availableRewards->count() ?? 0 }} tersedia</p>
                </div>
            </a>
            <a href="{{ route('loyalty.points-history') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i data-feather="list" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Riwayat Poin</p>
                    <p class="text-xs text-gray-500">Lihat transaksi</p>
                </div>
            </a>
            <a href="{{ route('loyalty.redemption-history') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i data-feather="check-square" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Voucher Saya</p>
                    <p class="text-xs text-gray-500">{{ $activeRedemptions->count() ?? 0 }} aktif</p>
                </div>
            </a>
            <a href="{{ route('loyalty.tiers') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center gap-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i data-feather="award" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Info Tier</p>
                    <p class="text-xs text-gray-500">Lihat benefits</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Tiers -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i data-feather="layers" class="w-5 h-5 text-coffee"></i>
                        Membership Tiers
                    </h2>
                    <div class="space-y-3">
                        @foreach($allTiers ?? [] as $tier)
                            @php
                                $isCurrentTier = ($currentTier->name ?? 'bronze') == $tier->name;
                                $tierColors = [
                                    'bronze' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-300'],
                                    'silver' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'],
                                    'gold' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300'],
                                    'platinum' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-300'],
                                ];
                                $colors = $tierColors[$tier->name] ?? $tierColors['bronze'];
                            @endphp
                            <div class="tier-card p-4 rounded-xl border-2 {{ $isCurrentTier ? $colors['border'] . ' ' . $colors['bg'] : 'border-gray-200 bg-gray-50' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">
                                            @if($tier->name == 'platinum') 👑
                                            @elseif($tier->name == 'gold') ⭐
                                            @elseif($tier->name == 'silver') 🥈
                                            @else 🥉
                                            @endif
                                        </span>
                                        <span class="font-bold capitalize {{ $isCurrentTier ? $colors['text'] : 'text-gray-700' }}">{{ $tier->name }}</span>
                                    </div>
                                    @if($isCurrentTier)
                                        <span class="px-2 py-1 bg-white rounded-full text-xs font-semibold {{ $colors['text'] }}">Tier Anda</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-1">Min. {{ number_format($tier->min_points) }} poin</p>
                                @if($tier->discount_percentage > 0)
                                    <p class="text-sm font-medium {{ $colors['text'] }}">{{ $tier->discount_percentage }}% diskon</p>
                                @endif
                                @if($tier->points_multiplier > 100)
                                    <p class="text-xs text-gray-500">{{ $tier->points_multiplier / 100 }}x poin</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column - Rewards & History -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Available Rewards -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i data-feather="gift" class="w-5 h-5 text-purple-600"></i>
                            Reward Tersedia
                        </h2>
                        <a href="{{ route('loyalty.rewards') }}" class="text-coffee text-sm font-medium hover:underline">Lihat Semua →</a>
                    </div>

                    @if(($availableRewards ?? collect())->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(($availableRewards ?? collect())->take(4) as $reward)
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white text-xl">
                                            @if($reward->type == 'discount') 💰
                                            @elseif($reward->type == 'free_item') ☕
                                            @elseif($reward->type == 'voucher') 🎟️
                                            @else 🎁
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800">{{ $reward->name }}</h3>
                                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($reward->description, 50) }}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-bold text-purple-600">{{ number_format($reward->points_required) }} pts</span>
                                                @if(($user->loyalty_points ?? 0) >= $reward->points_required)
                                                    <a href="{{ route('loyalty.reward.show', $reward) }}" class="px-3 py-1 bg-purple-600 text-white text-xs font-semibold rounded-lg hover:bg-purple-700 transition">
                                                        Tukar
                                                    </a>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg">
                                                        Poin Kurang
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i data-feather="gift" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            <p>Belum ada reward tersedia</p>
                        </div>
                    @endif
                </div>

                <!-- Active Vouchers -->
                @if(($activeRedemptions ?? collect())->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <i data-feather="tag" class="w-5 h-5 text-green-600"></i>
                            Voucher Aktif
                        </h2>
                        <div class="space-y-3">
                            @foreach($activeRedemptions as $redemption)
                                <div class="border-2 border-dashed border-green-300 bg-green-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white">
                                                <i data-feather="check" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $redemption->reward->name ?? 'Reward' }}</p>
                                                <p class="text-xs text-gray-500">Kode: <span class="font-mono font-bold text-green-600">{{ $redemption->code }}</span></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Berlaku sampai</p>
                                            <p class="text-sm font-medium text-gray-700">{{ $redemption->expires_at ? $redemption->expires_at->format('d M Y') : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Transactions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i data-feather="activity" class="w-5 h-5 text-blue-600"></i>
                            Transaksi Terakhir
                        </h2>
                        <a href="{{ route('loyalty.points-history') }}" class="text-coffee text-sm font-medium hover:underline">Lihat Semua →</a>
                    </div>

                    @if(($recentTransactions ?? collect())->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTransactions as $transaction)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                                            {{ $transaction->points > 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                            <i data-feather="{{ $transaction->points > 0 ? 'plus' : 'minus' }}"
                                               class="w-5 h-5 {{ $transaction->points > 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $transaction->description }}</p>
                                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <span class="font-bold {{ $transaction->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }} pts
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            <p>Belum ada transaksi poin</p>
                            <a href="{{ route('menu') }}" class="inline-block mt-3 text-coffee font-medium hover:underline">
                                Mulai pesan untuk dapat poin →
                            </a>
                        </div>
                    @endif
                </div>

                <!-- How It Works -->
                <div class="bg-gradient-to-br from-coffee to-brown rounded-2xl p-6 text-white">
                    <h2 class="text-xl font-bold mb-4">Cara Mendapatkan Poin</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-3xl mb-2">🛒</div>
                            <h3 class="font-semibold mb-1">Pesan Kopi</h3>
                            <p class="text-sm text-white/80">Dapat 1 poin setiap Rp1.000 pembelian</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-3xl mb-2">⭐</div>
                            <h3 class="font-semibold mb-1">Review Pesanan</h3>
                            <p class="text-sm text-white/80">Bonus poin untuk setiap review</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-3xl mb-2">🎂</div>
                            <h3 class="font-semibold mb-1">Bonus Birthday</h3>
                            <p class="text-sm text-white/80">Poin spesial di hari ulang tahun</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="max-w-6xl mx-auto px-4 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Coffee Express. All rights reserved.</p>
        </div>
    </footer>

  <!-- Spacer for bottom nav on mobile -->
<div class="md:hidden h-24"></div>

<script>
  // Mobile Menu Logic
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const closeMobileMenuBtn = document.getElementById('close-mobile-menu');
  const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
  const mobileMenuPanel = document.getElementById('mobile-menu-panel');

  function openMobileMenu() {
    mobileMenu.classList.remove('hidden');
    // Trigger reflow
    void mobileMenu.offsetWidth;
    mobileMenuBackdrop.classList.remove('opacity-0');
    mobileMenuPanel.classList.remove('translate-x-full');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileMenu() {
    mobileMenuBackdrop.classList.add('opacity-0');
    mobileMenuPanel.classList.add('translate-x-full');
    setTimeout(() => {
      mobileMenu.classList.add('hidden');
      document.body.style.overflow = '';
    }, 300);
  }

  if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
  if (closeMobileMenuBtn) closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
  if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
</script>

<script defer src="{{ asset('js/cart.js') }}"></script>

    <script>
        feather.replace();
    </script>
</body>
</html>
