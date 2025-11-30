<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews — Coffee Express</title>
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
    </style>
</head>
<body class="bg-cream min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-coffee to-brown text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="text-2xl">☕</span>
                    <span class="text-xl font-bold">Coffee Express</span>
                </a>
                <div class="flex items-center gap-6">
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors text-white">
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>
                    
                    <!-- Desktop Links -->
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="hover:text-cream transition">Home</a>
                        <a href="{{ route('menu') }}" class="hover:text-cream transition">Menu</a>
                        <a href="{{ route('reviews.index') }}" class="text-cream font-semibold">Reviews</a>
                        <a href="{{ route('contact') }}" class="hover:text-cream transition">Contact</a>
                        @auth
                            <a href="{{ route('order.status') }}" class="hover:text-cream transition">My Orders</a>
                            <a href="{{ route('loyalty.index') }}" class="hover:text-cream transition">Loyalty</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-white text-coffee px-4 py-2 rounded-lg font-semibold hover:bg-cream transition">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

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

    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-coffee mb-4">Customer Reviews</h1>
            <p class="text-gray-600 text-lg">See what our customers are saying about us</p>
        </div>

        <!-- Stats Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Average Rating -->
                <div class="text-center">
                    <div class="text-5xl font-bold text-coffee mb-2">
                        {{ number_format($averageRating ?? 0, 1) }}
                    </div>
                    <div class="flex justify-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($averageRating ?? 0))
                                <i data-feather="star" class="w-6 h-6 fill-yellow-400 text-yellow-400"></i>
                            @else
                                <i data-feather="star" class="w-6 h-6 text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-gray-500">Average Rating</p>
                </div>

                <!-- Total Reviews -->
                <div class="text-center">
                    <div class="text-5xl font-bold text-coffee mb-2">
                        {{ $totalReviews ?? 0 }}
                    </div>
                    <p class="text-gray-500">Total Reviews</p>
                </div>

                <!-- Rating Distribution -->
                <div>
                    <p class="text-gray-700 font-semibold mb-3 text-center">Rating Distribution</p>
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm text-gray-600 w-8">{{ $i }} ★</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-3">
                                <div class="bg-yellow-400 h-3 rounded-full" style="width: {{ $ratingDistribution[$i]['percentage'] ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-500 w-12 text-right">{{ $ratingDistribution[$i]['count'] ?? 0 }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="space-y-6">
            @forelse($reviews as $review)
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>

                        <div class="flex-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $review->user->name }}</h3>
                                    <div class="flex items-center gap-2">
                                        <div class="flex gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i data-feather="star" class="w-4 h-4 fill-yellow-400 text-yellow-400"></i>
                                                @else
                                                    <i data-feather="star" class="w-4 h-4 text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->rating_text }}</span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Order Info -->
                            @if($review->order)
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-coffee/10 text-coffee">
                                        <i data-feather="shopping-bag" class="w-3 h-3 mr-1"></i>
                                        Order #{{ $review->order->id }}
                                    </span>
                                </div>
                            @endif

                            <!-- Review Content -->
                            @if($review->comment)
                                <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                            @else
                                <p class="text-gray-400 italic">No comment provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                    <i data-feather="message-square" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Reviews Yet</h3>
                    <p class="text-gray-500 mb-6">Be the first to share your experience!</p>
                    @auth
                        <a href="{{ route('order.history') }}" class="inline-flex items-center gap-2 bg-coffee text-white px-6 py-3 rounded-xl font-semibold hover:bg-coffee/90 transition">
                            <i data-feather="coffee" class="w-5 h-5"></i>
                            Order Now & Review
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-coffee text-white px-6 py-3 rounded-xl font-semibold hover:bg-coffee/90 transition">
                            <i data-feather="log-in" class="w-5 h-5"></i>
                            Login to Review
                        </a>
                    @endauth
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @endif

        <!-- CTA Section -->
        @auth
            <div class="mt-12 bg-gradient-to-r from-coffee to-brown rounded-2xl p-8 text-white text-center">
                <h2 class="text-2xl font-bold mb-2">Share Your Experience!</h2>
                <p class="text-white/80 mb-6">Completed an order? We'd love to hear your feedback.</p>
                <a href="{{ route('order.history') }}" class="inline-flex items-center gap-2 bg-white text-coffee px-6 py-3 rounded-xl font-semibold hover:bg-cream transition">
                    <i data-feather="edit-3" class="w-5 h-5"></i>
                    Write a Review
                </a>
            </div>
        @endauth
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-coffee to-brown text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">☕ Coffee Express</p>
            <p class="text-white/70">Bringing quality coffee to your doorstep</p>
            <p class="text-white/50 text-sm mt-4">© {{ date('Y') }} Coffee Express. All rights reserved.</p>
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
