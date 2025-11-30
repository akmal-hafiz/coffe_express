<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coffee Express - Premium Coffee Experience')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              espresso: '#2D1B12',
              caramel: '#C9A581',
              sage: '#8B9D83',
              cream: '#FAF8F5',
              coffee: '#6F4E37', // Added for compatibility
              gold: '#C6A664'    // Added for compatibility
            }
          }
        }
      }
    </script>
    <link rel="stylesheet" href="{{ asset('css/modern-2025.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    
    {{-- GSAP Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script defer src="{{ asset('js/gsap-animations.js') }}"></script>

    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;600;700;800;900&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-cream text-[#2C2C2C]">

    <!-- Navbar -->
    <nav class="modern-navbar sticky top-0 z-[1000] bg-white/95 backdrop-blur-md shadow-sm border-b border-coffee-200 transition-all duration-300">
      <div class="navbar-container container mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="navbar-logo flex items-center gap-2 text-2xl md:text-3xl font-extrabold text-coffee-700 tracking-tight hover:text-coffee-900 transition-colors">
          <div class="navbar-logo-icon">
            <i data-feather="coffee" class="w-8 h-8"></i>
          </div>
          <span>Coffee Express</span>
        </a>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
          <i data-feather="menu" class="w-6 h-6 text-coffee"></i>
        </button>

        <div class="flex items-center gap-4 md:gap-6">
          <!-- Navigation Links -->
          <ul class="navbar-links hidden md:flex items-center gap-6 text-sm md:text-base font-medium">
            <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ url('/') }}">Home</a></li>
            <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ url('menu') }}">Menu</a></li>
            <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ route('reviews.index') }}">Reviews</a></li>
            <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ url('contact') }}">Contact</a></li>

            @auth
              @if(Auth::user()->isAdmin())
                <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              @else
                <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ route('order.status') }}">My Orders</a></li>
                <li><a class="navbar-link hover:text-coffee transition-colors flex items-center gap-1" href="{{ route('loyalty.index') }}">
                  <i data-feather="gift" class="w-4 h-4"></i> Loyalty
                </a></li>
              @endif

              <!-- User Info Dropdown -->
              <li class="relative group">
                <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-coffee/10 transition-colors">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </div>
                  <span class="font-medium text-sm hidden lg:inline">{{ Auth::user()->name }}</span>
                  <i data-feather="chevron-down" class="w-4 h-4"></i>
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                  <div class="p-4 border-b border-gray-100">
                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                      <span class="inline-flex px-2 py-1 bg-coffee/10 text-coffee text-xs font-semibold rounded-full items-center gap-1">
                        <i data-feather="{{ Auth::user()->isAdmin() ? 'shield' : 'user' }}" class="w-3 h-3"></i>
                        {{ Auth::user()->isAdmin() ? 'Admin' : 'User' }}
                      </span>
                    </div>
                  </div>
                  <div class="p-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 rounded-lg transition text-sm">
                      <i data-feather="user" class="w-4 h-4"></i> Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-red-50 text-red-600 rounded-lg transition text-sm">
                        <i data-feather="log-out" class="w-4 h-4"></i> Logout
                      </button>
                    </form>
                  </div>
                </div>
              </li>
            @else
              <li><a class="navbar-link hover:text-coffee transition-colors" href="{{ route('login') }}">Login</a></li>
              <li><a class="navbar-cta px-4 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition-colors" href="{{ route('register') }}">Register</a></li>
            @endauth
          </ul>

          <!-- Cart Icon -->
          <button id="cart-icon" class="hidden md:block relative p-2 hover:bg-coffee/10 rounded-full transition-colors">
            <i data-feather="shopping-cart" class="w-6 h-6 text-coffee"></i>
            <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">0</span>
          </button>
        </div>
      </div>

      <!-- Mobile Menu Overlay -->
      <div id="mobile-menu" class="fixed inset-0 bg-black/50 z-50 hidden">
        <div class="fixed right-0 top-0 bottom-0 w-80 max-w-[85vw] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col" id="mobile-menu-panel">
          <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-coffee/5">
            <div class="flex items-center gap-2 text-coffee font-bold">
              <i data-feather="coffee" class="w-5 h-5"></i>
              <span>Menu</span>
            </div>
            <button id="mobile-menu-close" class="p-2 hover:bg-white rounded-full transition-colors shadow-sm">
              <i data-feather="x" class="w-5 h-5 text-gray-500"></i>
            </button>
          </div>
          <div class="flex-1 overflow-y-auto py-4 px-4 flex flex-col gap-2">
             <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
              <i data-feather="home" class="w-5 h-5"></i> Home
            </a>
            <a href="{{ url('menu') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
              <i data-feather="coffee" class="w-5 h-5"></i> Menu
            </a>
            <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
              <i data-feather="star" class="w-5 h-5"></i> Reviews
            </a>
            <a href="{{ url('contact') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-coffee/5 hover:text-coffee rounded-xl transition-all font-medium">
              <i data-feather="mail" class="w-5 h-5"></i> Contact
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer bg-[#2C2C2C] text-white py-12">
      <div class="container mx-auto px-4 footer-grid grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
           <div class="brand text-2xl font-bold text-[#D4A574] mb-4">Coffee Express</div>
           <p class="text-white/70 max-w-md">Premium coffee experience delivered to your doorstep. Taste the difference in every cup.</p>
        </div>
        <div>
          <h4 class="text-[#D4A574] text-lg font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li><a href="{{ url('/') }}" class="text-white/80 hover:text-[#D4A574] transition-colors">Home</a></li>
            <li><a href="{{ url('menu') }}" class="text-white/80 hover:text-[#D4A574] transition-colors">Menu</a></li>
            <li><a href="{{ url('contact') }}" class="text-white/80 hover:text-[#D4A574] transition-colors">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-[#D4A574] text-lg font-semibold mb-4">Contact</h4>
          <ul class="space-y-2 text-white/80">
            <li>Jl. Coffee Street No. 123</li>
            <li>Jakarta, Indonesia</li>
            <li>info@coffeeexpress.com</li>
          </ul>
        </div>
      </div>
      <div class="container mx-auto px-4 mt-8 pt-8 border-t border-white/10 text-center text-white/60">
        <p>&copy; {{ date('Y') }} Coffee Express. All rights reserved.</p>
      </div>
    </footer>

    <!-- Bottom Nav Spacer for Mobile -->
    <div class="md:hidden h-24"></div>

    <!-- Mobile Bottom Nav -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 safe-area-bottom">
      <div class="flex items-center justify-around px-2 py-3">
        <a href="{{ url('/') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors {{ request()->is('/') ? 'text-coffee' : 'text-gray-600 hover:text-coffee' }}">
          <i data-feather="home" class="w-5 h-5"></i>
          <span class="text-xs font-medium">Home</span>
        </a>
        <a href="{{ url('menu') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors {{ request()->is('menu') ? 'text-coffee' : 'text-gray-600 hover:text-coffee' }}">
          <i data-feather="coffee" class="w-5 h-5"></i>
          <span class="text-xs font-medium">Menu</span>
        </a>
        <button id="cart-icon-bottom" class="relative -mt-6 flex flex-col items-center gap-1 px-4 py-3 bg-coffee text-white rounded-2xl shadow-lg transition-transform hover:scale-105">
          <i data-feather="shopping-cart" class="w-6 h-6"></i>
          <span class="text-xs font-semibold">Cart</span>
          <span id="cart-count-bottom" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">0</span>
        </button>
        @auth
          <a href="{{ route('order.status') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('order.*') ? 'text-coffee' : 'text-gray-600 hover:text-coffee' }}">
            <i data-feather="package" class="w-5 h-5"></i>
            <span class="text-xs font-medium">Orders</span>
          </a>
          <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'text-coffee' : 'text-gray-600 hover:text-coffee' }}">
            <i data-feather="user" class="w-5 h-5"></i>
            <span class="text-xs font-medium">Profile</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-coffee">
            <i data-feather="log-in" class="w-5 h-5"></i>
            <span class="text-xs font-medium">Login</span>
          </a>
        @endauth
      </div>
    </nav>

    <script>
      feather.replace();

      // Mobile Menu Logic
      const mobileMenuBtn = document.getElementById('mobile-menu-btn');
      const mobileMenu = document.getElementById('mobile-menu');
      const mobileMenuPanel = document.getElementById('mobile-menu-panel');
      const mobileMenuClose = document.getElementById('mobile-menu-close');

      function openMobileMenu() {
        if(mobileMenu) {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
            mobileMenuPanel.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }
      }

      function closeMobileMenu() {
        if(mobileMenu) {
            mobileMenuPanel.classList.add('translate-x-full');
            setTimeout(() => {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = '';
            }, 300);
        }
      }

      if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
      if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);
      if (mobileMenu) {
        mobileMenu.addEventListener('click', (e) => {
          if (e.target === mobileMenu) closeMobileMenu();
        });
      }
    </script>
    <script defer src="{{ asset('js/cart.js') }}"></script>
    @stack('scripts')
</body>
</html>
