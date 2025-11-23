<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Status — Coffee Express</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- ☕ Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
  
  <!-- ☕ Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            coffee: {
              50: '#fdf8f6',
              100: '#f8f1e5',
              200: '#f2e3d0',
              300: '#e8d0b3',
              700: '#6f4e37',
              900: '#3e2723',
            },
            cream: {
              100: '#fff8e7',
              200: '#fef3d9',
            }
          },
          animation: {
            'fade-in': 'fadeIn 0.5s ease-in',
            'slide-in': 'slideIn 0.5s ease-out',
            'steam': 'steam 2s ease-in-out infinite',
            'brewing': 'brewing 2s ease-in-out infinite',
            'wiggle': 'wiggle 1s ease-in-out infinite',
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0', transform: 'translateY(10px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            slideIn: {
              '0%': { transform: 'translateX(-100%)', opacity: '0' },
              '100%': { transform: 'translateX(0)', opacity: '1' },
            },
            steam: {
              '0%': { transform: 'translateY(0) scale(1)', opacity: '0.7' },
              '50%': { transform: 'translateY(-20px) scale(1.2)', opacity: '0.3' },
              '100%': { transform: 'translateY(-40px) scale(1.5)', opacity: '0' },
            },
            brewing: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-10px)' },
            },
            wiggle: {
              '0%, 100%': { transform: 'rotate(-3deg)' },
              '50%': { transform: 'rotate(3deg)' },
            },
          }
        }
      }
    }
  </script>
  
  <!-- ☕ SweetAlert2 for Notifications -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- ☕ Canvas Confetti for Celebrations -->
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
  
  <style>
    body { font-family: 'Poppins', sans-serif; }
    
    /* ☕ Steam Animation */
    .steam-container {
      position: relative;
      display: inline-block;
    }
    .steam {
      position: absolute;
      width: 8px;
      height: 8px;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 50%;
      animation: steam 2s ease-in-out infinite;
    }
    .steam:nth-child(1) { left: 10px; animation-delay: 0s; }
    .steam:nth-child(2) { left: 20px; animation-delay: 0.3s; }
    .steam:nth-child(3) { left: 30px; animation-delay: 0.6s; }
    
    /* ☕ Brewing Coffee Animation */
    @keyframes fillCup {
      0% { height: 0%; }
      100% { height: 80%; }
    }
    .coffee-cup-fill {
      animation: fillCup 3s ease-in-out infinite;
    }
    
    /* ☕ Smooth Transitions */
    .status-transition {
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>

<body class="bg-gradient-to-br from-coffee-50 via-cream-100 to-coffee-100 min-h-screen">
  
  <!-- ☕ Navbar -->
  <nav class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b-2 border-coffee-200">
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo with Coffee Icon -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold text-coffee-700 hover:text-coffee-900 transition-colors">
          <i data-feather="coffee" class="w-8 h-8"></i>
          <span>Coffee Express</span>
        </a>
        
        <!-- Nav Links -->
        <div class="hidden md:flex items-center gap-6">
          <a href="{{ route('home') }}" class="text-coffee-700 hover:text-coffee-900 transition-colors font-medium">Home</a>
          <a href="{{ route('menu') }}" class="text-coffee-700 hover:text-coffee-900 transition-colors font-medium">Menu</a>
          <a href="{{ route('order.status') }}" class="text-coffee-900 font-bold border-b-2 border-coffee-700">My Orders</a>
          <a href="{{ route('order.history') }}" class="text-coffee-700 hover:text-coffee-900 transition-colors font-medium">History</a>
        </div>
        
        <!-- User Dropdown -->
        @auth
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-coffee-100 px-4 py-2 rounded-full">
            <div class="w-8 h-8 rounded-full bg-coffee-700 text-white flex items-center justify-center font-bold">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="font-medium text-coffee-900">{{ Auth::user()->name }}</span>
          </div>
        </div>
        @endauth
      </div>
    </div>
  </nav>

  <!-- ☕ Main Content -->
  <main class="container mx-auto px-4 py-8">
    
    @if($order)
      <!-- Order Status Card -->
      <div class="max-w-4xl mx-auto">
        
        <!-- Status Header with Animation -->
        <div class="bg-gradient-to-r from-coffee-700 to-coffee-900 text-white rounded-3xl shadow-2xl p-8 mb-6 animate-fade-in">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold mb-2">Order #{{ $order->id }}</h1>
              <p class="text-coffee-100">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            <!-- Status Badge with Icon -->
            <div class="status-transition" id="status-badge">
              @if($order->status === 'pending')
                <div class="bg-blue-500 px-6 py-3 rounded-full flex items-center gap-2 animate-pulse-slow">
                  <i data-feather="clock" class="w-6 h-6"></i>
                  <span class="font-bold text-lg">Pending</span>
                </div>
              @elseif($order->status === 'preparing')
                <div class="bg-yellow-500 px-6 py-3 rounded-full flex items-center gap-2">
                  <i data-feather="coffee" class="w-6 h-6 animate-brewing"></i>
                  <span class="font-bold text-lg">Preparing</span>
                </div>
              @elseif($order->status === 'ready')
                <div class="bg-green-500 px-6 py-3 rounded-full flex items-center gap-2 animate-pulse">
                  <i data-feather="{{ $order->pickup_option === 'pickup' ? 'check-circle' : 'truck' }}" class="w-6 h-6"></i>
                  <span class="font-bold text-lg">Ready!</span>
                </div>
              @else
                <div class="bg-gray-500 px-6 py-3 rounded-full flex items-center gap-2">
                  <i data-feather="check-circle" class="w-6 h-6"></i>
                  <span class="font-bold text-lg">Completed</span>
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Brewing Animation (Only show when preparing) -->
        @if($order->status === 'preparing')
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-6 text-center animate-fade-in" id="brewing-section">
          <div class="steam-container inline-block mb-4">
            <i data-feather="coffee" class="w-32 h-32 text-coffee-700 animate-brewing" style="stroke-width: 1.5;"></i>
            <div class="steam"></div>
            <div class="steam"></div>
            <div class="steam"></div>
          </div>
          <h2 class="text-2xl font-bold text-coffee-900 mb-2">We're Brewing Your Coffee...</h2>
          <p class="text-coffee-700 text-lg">Please wait a moment while we prepare your perfect cup</p>
          
          <!-- Animated Progress Bar -->
          <div class="mt-6 bg-coffee-100 rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-coffee-700 to-coffee-900 h-full rounded-full animate-pulse" style="width: 60%"></div>
          </div>
        </div>
        @endif

        <!-- Order Details Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden animate-fade-in">
          
          <!-- Progress Steps -->
          <div class="p-8 bg-gradient-to-r from-cream-100 to-coffee-50">
            <div class="flex items-center justify-between relative">
              <!-- Progress Line -->
              <div class="absolute top-6 left-0 right-0 h-1 bg-coffee-200 -z-10"></div>
              <div class="absolute top-6 left-0 h-1 bg-coffee-700 transition-all duration-500 -z-10" 
                   style="width: {{ $order->status === 'pending' ? '0%' : ($order->status === 'preparing' ? '33%' : ($order->status === 'ready' ? '66%' : '100%')) }}"></div>
              
              <!-- Step 1: Received -->
              <div class="text-center flex-1 relative z-10">
                <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-2 transition-all duration-500 {{ in_array($order->status, ['pending', 'preparing', 'ready', 'completed']) ? 'bg-coffee-700 text-white scale-110 shadow-lg' : 'bg-coffee-200 text-coffee-400' }}">
                  <i data-feather="check" class="w-7 h-7"></i>
                </div>
                <p class="text-sm font-semibold text-coffee-900">Received</p>
              </div>
              
              <!-- Step 2: Preparing -->
              <div class="text-center flex-1 relative z-10">
                <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-2 transition-all duration-500 {{ in_array($order->status, ['preparing', 'ready', 'completed']) ? 'bg-yellow-500 text-white scale-110 shadow-lg' : 'bg-coffee-200 text-coffee-400' }}">
                  <i data-feather="coffee" class="w-7 h-7 {{ $order->status === 'preparing' ? 'animate-wiggle' : '' }}"></i>
                </div>
                <p class="text-sm font-semibold text-coffee-900">Preparing</p>
              </div>
              
              <!-- Step 3: Ready -->
              <div class="text-center flex-1 relative z-10">
                <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-2 transition-all duration-500 {{ in_array($order->status, ['ready', 'completed']) ? 'bg-green-500 text-white scale-110 shadow-lg' : 'bg-coffee-200 text-coffee-400' }}">
                  <i data-feather="{{ $order->pickup_option === 'pickup' ? 'shopping-bag' : 'truck' }}" class="w-7 h-7"></i>
                </div>
                <p class="text-sm font-semibold text-coffee-900">{{ $order->pickup_option === 'pickup' ? 'Ready' : 'Delivering' }}</p>
              </div>
              
              <!-- Step 4: Completed -->
              <div class="text-center flex-1 relative z-10">
                <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-2 transition-all duration-500 {{ $order->status === 'completed' ? 'bg-blue-500 text-white scale-110 shadow-lg' : 'bg-coffee-200 text-coffee-400' }}">
                  <i data-feather="check-circle" class="w-7 h-7"></i>
                </div>
                <p class="text-sm font-semibold text-coffee-900">Completed</p>
              </div>
            </div>
          </div>

          <!-- Estimated Time (Animated) -->
          @if($order->estimated_time && $order->status !== 'completed')
          <div class="mx-8 mt-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 p-5 rounded-xl shadow-md animate-slide-in" id="estimated-time">
            <div class="flex items-center gap-4">
              <div class="bg-yellow-500 p-3 rounded-full">
                <i data-feather="clock" class="text-white w-6 h-6"></i>
              </div>
              <div>
                <p class="font-bold text-yellow-900 text-lg">Estimated Time</p>
                <p class="text-yellow-700 text-xl font-semibold">{{ $order->estimated_time }} minutes remaining</p>
              </div>
            </div>
          </div>
          @endif

          <!-- Order Items -->
          <div class="p-8">
            <h3 class="font-bold text-2xl mb-6 flex items-center gap-3 text-coffee-900">
              <i data-feather="shopping-cart" class="w-6 h-6"></i>
              Your Order
            </h3>
            <div class="space-y-4">
              @foreach($order->items as $item)
              <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-coffee-50 to-cream-100 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <img src="{{ $item['image'] ?? asset('images/cappucino.webp') }}" 
                     alt="{{ $item['name'] }}" 
                     class="w-20 h-20 rounded-xl object-cover shadow-md">
                <div class="flex-1">
                  <p class="font-bold text-lg text-coffee-900">{{ $item['name'] }}</p>
                  <p class="text-coffee-700">{{ $item['qty'] }} × Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                </div>
                <p class="font-bold text-xl text-coffee-900">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
              </div>
              @endforeach
            </div>
            
            <!-- Total -->
            <div class="mt-6 pt-6 border-t-2 border-coffee-200 flex justify-between items-center">
              <span class="text-2xl font-bold text-coffee-900">Total</span>
              <span class="text-3xl font-bold text-coffee-700">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
          </div>

          <!-- Customer Info -->
          <div class="px-8 pb-8 grid md:grid-cols-2 gap-6">
            <div class="bg-coffee-50 p-6 rounded-xl">
              <h4 class="font-bold text-lg mb-4 flex items-center gap-2 text-coffee-900">
                <i data-feather="user" class="w-5 h-5"></i>
                Customer Info
              </h4>
              <div class="space-y-2 text-coffee-700">
                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                @if($order->address)
                <p><strong>Address:</strong> {{ $order->address }}</p>
                @endif
              </div>
            </div>
            
            <div class="bg-coffee-50 p-6 rounded-xl">
              <h4 class="font-bold text-lg mb-4 flex items-center gap-2 text-coffee-900">
                <i data-feather="info" class="w-5 h-5"></i>
                Order Details
              </h4>
              <div class="space-y-2 text-coffee-700">
                <p><strong>Pickup:</strong> {{ ucfirst($order->pickup_option) }}</p>
                <p><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Order Time:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
              </div>
            </div>
          </div>

        </div>

        <!-- Status Message -->
        @if($order->status === 'ready')
        <div class="mt-6 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-3xl shadow-2xl p-8 text-center animate-fade-in">
          <div class="flex items-center justify-center gap-4 mb-3">
            <i data-feather="{{ $order->pickup_option === 'pickup' ? 'check-circle' : 'truck' }}" class="w-12 h-12 animate-pulse"></i>
            <h2 class="text-3xl font-bold">Your Coffee is Ready!</h2>
          </div>
          <p class="text-xl">
            @if($order->pickup_option === 'pickup')
              You can pick it up now at our store
            @else
              Your coffee is on the way to you!
            @endif
          </p>
        </div>
        @endif

      </div>

    @else
      <!-- No Active Order -->
      <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl p-12 text-center animate-fade-in">
        <i data-feather="coffee" class="w-32 h-32 mx-auto mb-6 text-coffee-700" style="stroke-width: 1;"></i>
        <h2 class="text-3xl font-bold mb-4 text-coffee-900">No Active Orders</h2>
        <p class="text-coffee-700 text-lg mb-8">You don't have any orders in progress</p>
        <div class="flex gap-4 justify-center">
          <a href="{{ route('menu') }}" class="px-8 py-4 bg-gradient-to-r from-coffee-700 to-coffee-900 text-white rounded-xl font-bold hover:shadow-lg transition-all transform hover:scale-105">
            Order Now
          </a>
          <a href="{{ route('order.history') }}" class="px-8 py-4 border-2 border-coffee-700 text-coffee-700 rounded-xl font-bold hover:bg-coffee-50 transition-all">
            View History
          </a>
        </div>
      </div>
    @endif
    
  </main>

  <!-- ☕ REALTIME: Pusher & Laravel Echo -->
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
  
  <script>
    feather.replace();
    
    @if($order)
    // 🔴 REALTIME: Initialize Laravel Echo with Pusher
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '{{ env('PUSHER_APP_KEY') }}',
      cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
      forceTLS: true,
      encrypted: true,
      authEndpoint: '/broadcasting/auth',
      auth: {
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }
    });

    // 🔴 REALTIME: Listen to order updates on private channel
    window.Echo.private('order.{{ $order->id }}')
      .listen('.order.updated', (event) => {
        console.log('🔴 Order Update Received:', event);
        
        // ☕ Show celebration confetti if status is ready
        if (event.status === 'ready') {
          // Trigger confetti
          confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#6f4e37', '#f8f1e5', '#d2691e', '#8b4513']
          });
          
          // Show SweetAlert with celebration
          Swal.fire({
            icon: 'success',
            title: event.pickup_option === 'pickup' ? 'Your Coffee is Ready!' : 'On the Way!',
            html: `<div class="text-left">
              <p class="text-xl font-bold mb-3">${event.message}</p>
              <div class="text-lg text-gray-700">
                <p><strong>Order ID:</strong> #${event.order_id}</p>
                ${event.estimated_time ? `<p><strong>Estimated Time:</strong> ${event.estimated_time} minutes</p>` : ''}
              </div>
            </div>`,
            confirmButtonColor: '#6f4e37',
            confirmButtonText: 'Awesome!',
            showClass: {
              popup: 'animate__animated animate__bounceIn'
            },
            backdrop: `
              rgba(111,78,55,0.2)
              left top
              no-repeat
            `
          });
        } else {
          // Regular notification for other status changes
          Swal.fire({
            icon: getNotificationIcon(event.status),
            title: 'Order Update',
            html: `<div class="text-left">
              <p class="text-lg font-semibold mb-3">${event.message}</p>
              <div class="text-sm text-gray-600">
                <p><strong>Status:</strong> ${getStatusText(event.status)}</p>
                ${event.estimated_time ? `<p><strong>Estimated Time:</strong> ${event.estimated_time} minutes</p>` : ''}
              </div>
            </div>`,
            confirmButtonColor: '#6f4e37',
            confirmButtonText: 'OK',
            timer: 5000,
            timerProgressBar: true,
            showClass: {
              popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOutUp'
            }
          });
        }

        // Update page content with smooth animation
        updateOrderStatus(event);
      });

    // Helper: Get notification icon based on status
    function getNotificationIcon(status) {
      const icons = {
        'pending': 'info',
        'preparing': 'info',
        'ready': 'success',
        'completed': 'success'
      };
      return icons[status] || 'info';
    }

    // Helper: Get status text
    function getStatusText(status) {
      const texts = {
        'pending': 'Pending',
        'preparing': 'Preparing',
        'ready': 'Ready',
        'completed': 'Completed'
      };
      return texts[status] || status;
    }

    // Update order status on page with animation
    function updateOrderStatus(event) {
      // Fade out current content
      const mainContent = document.querySelector('main');
      mainContent.style.opacity = '0.5';
      mainContent.style.transition = 'opacity 0.5s ease';

      // Wait for fade, then reload to show updated data
      setTimeout(() => {
        location.reload();
      }, 800);
    }

    console.log('🔴 REALTIME: Listening for updates on order.{{ $order->id }}');
    @endif
  </script>
</body>
</html>
