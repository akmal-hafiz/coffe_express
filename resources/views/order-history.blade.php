<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.order_history.title', [], 'Riwayat Pesanan') }} — Coffee Express</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html,body{font-family:'Poppins',system-ui,Arial,sans-serif}
  </style>
</head>
<body class="bg-cream text-[#2C2C2C] min-h-screen">
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
        <!-- Navigation Links -->
        <a href="{{ route('menu') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">Menu</a>
        <a href="{{ route('order.status') }}" class="text-sm font-medium text-brown hover:opacity-90 transition">Status Pesanan</a>
        <a href="{{ route('reviews.index') }}" class="text-sm font-medium text-gray-600 hover:text-coffee transition">Reviews</a>

        <!-- Loyalty Points Badge -->
        <a href="{{ route('loyalty.index') }}" class="flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-full text-sm font-semibold hover:shadow-md transition">
          <i data-feather="star" class="w-4 h-4"></i>
          <span>{{ number_format(Auth::user()->loyalty_points ?? 0) }} pts</span>
        </a>

        <!-- Language Switcher -->
        <div class="flex items-center gap-1 border border-gray-200 rounded-lg overflow-hidden">
          <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs {{ app()->getLocale() == 'id' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">🇮🇩 ID</a>
          <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs {{ app()->getLocale() == 'en' ? 'bg-coffee text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">🇬🇧 EN</a>
        </div>

        <!-- User Info -->
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
    <!-- Page Header with Loyalty Summary -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-3xl font-extrabold text-coffee">Riwayat Pesanan</h1>
        <p class="text-gray-600 mt-1">Lihat semua pesanan Anda dan berikan ulasan</p>
      </div>

      <!-- Loyalty Quick Stats -->
      <div class="flex items-center gap-4">
        <a href="{{ route('loyalty.index') }}" class="flex items-center gap-3 bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
            <i data-feather="award" class="w-6 h-6 text-white"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500">Poin Loyalitas</p>
            <p class="text-xl font-bold text-coffee">{{ number_format(Auth::user()->loyalty_points ?? 0) }}</p>
          </div>
          <i data-feather="chevron-right" class="w-5 h-5 text-gray-400"></i>
        </a>

        <a href="{{ route('loyalty.rewards') }}" class="flex items-center gap-2 px-4 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
          <i data-feather="gift" class="w-5 h-5"></i>
          Tukar Reward
        </a>
      </div>
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

    @if($orders->count() > 0)
      <div class="space-y-4">
        @foreach($orders as $order)
          <div class="bg-white rounded-2xl shadow-md border border-neutral-200/70 overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <!-- Order Header -->
              <div class="flex items-start justify-between mb-4">
                <div>
                  <div class="flex items-center gap-2">
                    <p class="text-lg font-bold text-coffee">Order #{{ $order->id }}</p>
                    @if($order->status === 'completed' && !$order->hasReview())
                      <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Belum di-review</span>
                    @endif
                  </div>
                  <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-3">
                  <!-- Status Badge -->
                  <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold gap-1
                    {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $order->status === 'preparing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'ready' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                    @if($order->status === 'pending')
                      <i data-feather="clock" class="w-3 h-3"></i>
                    @elseif($order->status === 'preparing')
                      <i data-feather="coffee" class="w-3 h-3"></i>
                    @elseif($order->status === 'ready')
                      <i data-feather="check-circle" class="w-3 h-3"></i>
                    @elseif($order->status === 'completed')
                      <i data-feather="award" class="w-3 h-3"></i>
                    @endif
                    {{ ucfirst($order->status) }}
                  </span>

                  <!-- Points Earned Badge -->
                  @if($order->status === 'completed')
                    @php
                      $pointsEarned = \App\Models\LoyaltyPoint::where('order_id', $order->id)->where('type', 'earned')->first();
                    @endphp
                    @if($pointsEarned)
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 gap-1">
                        <i data-feather="star" class="w-3 h-3"></i>
                        +{{ $pointsEarned->points }} pts
                      </span>
                    @endif
                  @endif
                </div>
              </div>

              <!-- Order Items -->
              <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                  <div class="flex items-center gap-3">
                    <img src="{{ $item['image'] ?? asset('images/cappucino.webp') }}" alt="{{ $item['name'] }}" class="w-12 h-12 rounded-lg object-cover">
                    <div class="flex-1">
                      <p class="font-medium">{{ $item['name'] }}</p>
                      <p class="text-sm text-gray-600">{{ $item['qty'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    <p class="font-semibold text-coffee">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                  </div>
                @endforeach
              </div>

              <!-- Order Footer -->
              <div class="flex items-center justify-between pt-4 border-t">
                <div class="flex items-center gap-4 text-sm">
                  <span class="inline-flex items-center gap-1 text-gray-600">
                    <i data-feather="{{ $order->pickup_option === 'pickup' ? 'shopping-bag' : 'truck' }}" class="w-4 h-4"></i>
                    {{ $order->pickup_option === 'pickup' ? 'Pickup' : 'Delivery' }}
                  </span>
                  <span class="text-gray-400">•</span>
                  <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                </div>
                <div class="text-right">
                  <p class="text-sm text-gray-600">Total</p>
                  <p class="text-xl font-bold text-coffee">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
              </div>

              <!-- Review Section for Completed Orders -->
              @if($order->status === 'completed')
                <div class="mt-4 pt-4 border-t border-dashed">
                  @if($order->hasReview())
                    <!-- Show existing review -->
                    @php
                      $review = $order->review;
                    @endphp
                    <div class="bg-green-50 rounded-xl p-4">
                      <div class="flex items-start justify-between">
                        <div>
                          <p class="text-sm font-semibold text-green-800 mb-1">Review Anda</p>
                          <div class="flex items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                              @if($i <= $review->rating)
                                <i data-feather="star" class="w-4 h-4 fill-yellow-400 text-yellow-400"></i>
                              @else
                                <i data-feather="star" class="w-4 h-4 text-gray-300"></i>
                              @endif
                            @endfor
                            <span class="text-sm text-green-700 ml-2">{{ $review->rating_text }}</span>
                          </div>
                          @if($review->comment)
                            <p class="text-sm text-green-700">"{{ $review->comment }}"</p>
                          @endif
                        </div>
                        <div class="flex items-center gap-2">
                          @if($review->is_approved)
                            <span class="px-2 py-1 bg-green-200 text-green-800 text-xs font-medium rounded-full">Approved</span>
                          @else
                            <span class="px-2 py-1 bg-yellow-200 text-yellow-800 text-xs font-medium rounded-full">Pending</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  @else
                    <!-- Show review button -->
                    <div class="flex items-center justify-between bg-gradient-to-r from-coffee/5 to-brown/5 rounded-xl p-4">
                      <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-coffee/10 flex items-center justify-center">
                          <i data-feather="edit-3" class="w-5 h-5 text-coffee"></i>
                        </div>
                        <div>
                          <p class="font-semibold text-gray-800">Bagaimana pengalaman Anda?</p>
                          <p class="text-sm text-gray-500">Berikan ulasan untuk pesanan ini</p>
                        </div>
                      </div>
                      <a href="{{ route('reviews.create', $order->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition shadow-md hover:shadow-lg">
                        <i data-feather="star" class="w-4 h-4"></i>
                        Beri Review
                      </a>
                    </div>
                  @endif
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="mt-6">
        {{ $orders->links() }}
      </div>
    @else
      <div class="bg-white rounded-2xl shadow-md p-12 text-center">
        <div class="w-24 h-24 mx-auto mb-4 bg-cream rounded-full flex items-center justify-center">
          <i data-feather="package" class="w-12 h-12 text-coffee"></i>
        </div>
        <h2 class="text-2xl font-bold mb-3">Belum Ada Riwayat Pesanan</h2>
        <p class="text-gray-600 mb-6">Anda belum pernah melakukan pesanan. Yuk, pesan kopi favorit Anda!</p>
        <div class="flex items-center justify-center gap-4">
          <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
            <i data-feather="coffee" class="w-5 h-5"></i>
            Mulai Pesan
          </a>
          <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-coffee text-coffee rounded-xl font-semibold hover:bg-coffee/5 transition">
            <i data-feather="gift" class="w-5 h-5"></i>
            Lihat Rewards
          </a>
        </div>
      </div>
    @endif

    <!-- Bottom CTA - Loyalty Program -->
    <div class="mt-8 bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 rounded-2xl p-6 text-white">
      <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
            <i data-feather="gift" class="w-8 h-8"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold">Program Loyalitas Coffee Express</h3>
            <p class="text-white/90">Kumpulkan poin dari setiap pembelian dan tukarkan dengan reward menarik!</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="text-center px-4">
            <p class="text-3xl font-bold">{{ number_format(Auth::user()->loyalty_points ?? 0) }}</p>
            <p class="text-sm text-white/80">Poin Anda</p>
          </div>
          <a href="{{ route('loyalty.index') }}" class="px-6 py-3 bg-white text-orange-500 rounded-xl font-bold hover:bg-orange-50 transition">
            Lihat Detail →
          </a>
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
