<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Status — Coffee Express</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    html,body{font-family:'Poppins',system-ui,Arial,sans-serif}
    @keyframes brewing {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    .brewing-animation {
      animation: brewing 1.5s ease-in-out infinite;
    }
  </style>
</head>
<body class="bg-cream text-[#2C2C2C] min-h-screen">
  <!-- Header -->
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-neutral-200/70">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brown text-white shadow">☕</span>
        <span class="text-xl font-extrabold tracking-tight text-coffee">Coffee Express</span>
      </a>
      <div class="flex items-center gap-4">
        <a href="{{ route('order.history') }}" class="text-sm font-medium text-brown hover:opacity-90 transition">Riwayat Pesanan</a>
        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="text-sm font-medium text-brown hover:opacity-90 transition">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-4xl mx-auto px-4 py-12">
    @if($order)
      <!-- Order Status Card -->
      <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-neutral-200">
        <!-- Status Header -->
        <div class="bg-gradient-to-r from-coffee to-brown text-white p-8 text-center">
          <div class="brewing-animation inline-block text-6xl mb-4">☕</div>
          <h1 class="text-3xl font-extrabold mb-2">
            @if($order->status === 'pending')
              Pesanan Diterima!
            @elseif($order->status === 'preparing')
              Sedang Diproses
            @elseif($order->status === 'ready')
              @if($order->pickup_option === 'pickup')
                Siap Diambil! 
              @else
                Dalam Perjalanan! 🚗
              @endif
            @else
              Selesai ✅
            @endif
          </h1>
          <p class="text-white/90">Order #{{ $order->id }}</p>
        </div>

        <!-- Order Details -->
        <div class="p-8">
          <!-- Progress Bar -->
          <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
              <div class="text-center flex-1">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 {{ $order->status === 'pending' || $order->status === 'preparing' || $order->status === 'ready' || $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                  <i data-feather="check" class="w-6 h-6"></i>
                </div>
                <p class="text-xs font-semibold">Diterima</p>
              </div>
              
              <div class="flex-1 h-1 {{ $order->status === 'preparing' || $order->status === 'ready' || $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}"></div>
              
              <div class="text-center flex-1">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 {{ $order->status === 'preparing' || $order->status === 'ready' || $order->status === 'completed' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                  <i data-feather="coffee" class="w-6 h-6"></i>
                </div>
                <p class="text-xs font-semibold">Diproses</p>
              </div>
              
              <div class="flex-1 h-1 {{ $order->status === 'ready' || $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}"></div>
              
              <div class="text-center flex-1">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 {{ $order->status === 'ready' || $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                  <i data-feather="{{ $order->pickup_option === 'pickup' ? 'shopping-bag' : 'truck' }}" class="w-6 h-6"></i>
                </div>
                <p class="text-xs font-semibold">{{ $order->pickup_option === 'pickup' ? 'Siap' : 'Dikirim' }}</p>
              </div>
              
              <div class="flex-1 h-1 {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}"></div>
              
              <div class="text-center flex-1">
                <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-2 {{ $order->status === 'completed' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                  <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
                <p class="text-xs font-semibold">Selesai</p>
              </div>
            </div>
          </div>

          <!-- Estimated Time -->
          @if($order->estimated_time && $order->status !== 'completed')
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-lg">
              <div class="flex items-center gap-3">
                <i data-feather="clock" class="text-yellow-600 w-6 h-6"></i>
                <div>
                  <p class="font-semibold text-yellow-800">Estimasi Waktu</p>
                  <p class="text-yellow-700">{{ $order->estimated_time }} menit lagi</p>
                </div>
              </div>
            </div>
          @endif

          <!-- Order Items -->
          <div class="mb-6">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
              <i data-feather="shopping-cart" class="w-5 h-5"></i>
              Pesanan Anda
            </h3>
            <div class="space-y-3">
              @foreach($order->items as $item)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                  <img src="{{ $item['image'] ?? asset('images/cappucino.webp') }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-lg object-cover">
                  <div class="flex-1">
                    <p class="font-semibold">{{ $item['name'] }}</p>
                    <p class="text-sm text-gray-600">{{ $item['qty'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                  </div>
                  <p class="font-bold text-coffee">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Order Info -->
          <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <h4 class="font-semibold mb-3 flex items-center gap-2">
                <i data-feather="user" class="w-5 h-5"></i>
                Informasi Pelanggan
              </h4>
              <div class="space-y-2 text-sm">
                <p><span class="text-gray-600">Nama:</span> <span class="font-medium">{{ $order->customer_name }}</span></p>
                <p><span class="text-gray-600">Telepon:</span> <span class="font-medium">{{ $order->phone }}</span></p>
                <p><span class="text-gray-600">Metode:</span> 
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $order->pickup_option === 'pickup' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ $order->pickup_option === 'pickup' ? '🏃 Pickup' : '🚗 Delivery' }}
                  </span>
                </p>
                @if($order->pickup_option === 'delivery' && $order->address)
                  <p><span class="text-gray-600">Alamat:</span> <span class="font-medium">{{ $order->address }}</span></p>
                @endif
              </div>
            </div>

            <div>
              <h4 class="font-semibold mb-3 flex items-center gap-2">
                <i data-feather="info" class="w-5 h-5"></i>
                Detail Pesanan
              </h4>
              <div class="space-y-2 text-sm">
                <p><span class="text-gray-600">Waktu Pesan:</span> <span class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span></p>
                <p><span class="text-gray-600">Pembayaran:</span> <span class="font-medium">{{ $order->payment_method }}</span></p>
                <p><span class="text-gray-600">Status:</span> 
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                    {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $order->status === 'preparing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'ready' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                    {{ ucfirst($order->status) }}
                  </span>
                </p>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t pt-4">
            <div class="flex justify-between items-center">
              <span class="text-lg font-semibold">Total Pembayaran</span>
              <span class="text-2xl font-bold text-coffee">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-6 flex gap-3">
            <a href="{{ route('home') }}" class="flex-1 text-center px-6 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
              Kembali ke Beranda
            </a>
            <a href="{{ route('order.history') }}" class="flex-1 text-center px-6 py-3 border-2 border-coffee text-coffee rounded-xl font-semibold hover:bg-coffee/5 transition">
              Lihat Riwayat
            </a>
          </div>
        </div>
      </div>

      <!-- Notification for Ready Orders -->
      @if($order->status === 'ready')
        <div class="mt-6 bg-green-50 border-2 border-green-500 rounded-2xl p-6 text-center">
          <div class="text-5xl mb-3">
            @if($order->pickup_option === 'pickup')
              🎉
            @else
              🚗
            @endif
          </div>
          <h2 class="text-2xl font-bold text-green-800 mb-2">
            @if($order->pickup_option === 'pickup')
              Kopi Anda Sudah Siap!
            @else
              Kopi Anda Sedang Dalam Perjalanan!
            @endif
          </h2>
          <p class="text-green-700">
            @if($order->pickup_option === 'pickup')
              Silakan datang ke toko untuk mengambil pesanan Anda ☕
            @else
              Driver kami sedang mengantar pesanan Anda. Mohon tunggu sebentar 🚗
            @endif
          </p>
        </div>
      @endif

    @else
      <!-- No Active Order -->
      <div class="bg-white rounded-3xl shadow-xl p-12 text-center">
        <div class="text-6xl mb-4">☕</div>
        <h2 class="text-2xl font-bold mb-3">Tidak Ada Pesanan Aktif</h2>
        <p class="text-gray-600 mb-6">Anda belum memiliki pesanan yang sedang diproses</p>
        <div class="flex gap-3 justify-center">
          <a href="{{ route('menu') }}" class="px-8 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
            Pesan Sekarang
          </a>
          <a href="{{ route('order.history') }}" class="px-8 py-3 border-2 border-coffee text-coffee rounded-xl font-semibold hover:bg-coffee/5 transition">
            Lihat Riwayat
          </a>
        </div>
      </div>
    @endif
  </main>

  <!-- 🔴 REALTIME: Pusher & Laravel Echo -->
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
        
        // Show SweetAlert notification
        Swal.fire({
          icon: getNotificationIcon(event.status),
          title: 'Order Update!',
          html: `<div class="text-left">
            <p class="text-lg font-semibold mb-2">${event.message}</p>
            <div class="text-sm text-gray-600">
              <p><strong>Status:</strong> ${getStatusText(event.status)}</p>
              ${event.estimated_time ? `<p><strong>Estimated Time:</strong> ${event.estimated_time} minutes</p>` : ''}
            </div>
          </div>`,
          confirmButtonColor: '#6F4E37',
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
      mainContent.style.transition = 'opacity 0.3s ease';

      // Wait for fade, then reload to show updated data
      setTimeout(() => {
        location.reload();
      }, 500);
    }

    console.log('🔴 REALTIME: Listening for updates on order.{{ $order->id }}');
    @endif
  </script>
</body>
</html>
