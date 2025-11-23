<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan — Coffee Express</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    html,body{font-family:'Poppins',system-ui,Arial,sans-serif}
  </style>
</head>
<body class="bg-cream text-[#2C2C2C] min-h-screen">
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
        <a href="{{ route('order.status') }}" class="text-sm font-medium text-brown hover:opacity-90 transition">Status Pesanan</a>
        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="text-sm font-medium text-brown hover:opacity-90 transition">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold mb-6">Riwayat Pesanan</h1>

    @if($orders->count() > 0)
      <div class="space-y-4">
        @foreach($orders as $order)
          <div class="bg-white rounded-2xl shadow-md border border-neutral-200/70 overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <p class="text-sm text-gray-600">Order #{{ $order->id }}</p>
                  <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                  {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                  {{ $order->status === 'preparing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                  {{ $order->status === 'ready' ? 'bg-green-100 text-green-800' : '' }}
                  {{ $order->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                  {{ ucfirst($order->status) }}
                </span>
              </div>

              <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                  <div class="flex items-center gap-3">
                    <img src="{{ $item['image'] ?? asset('images/cappucino.webp') }}" alt="{{ $item['name'] }}" class="w-12 h-12 rounded-lg object-cover">
                    <div class="flex-1">
                      <p class="font-medium">{{ $item['name'] }}</p>
                      <p class="text-sm text-gray-600">{{ $item['qty'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="flex items-center justify-between pt-4 border-t">
                <div class="flex items-center gap-4 text-sm">
                  <span class="inline-flex items-center gap-1">
                    <i data-feather="{{ $order->pickup_option === 'pickup' ? 'shopping-bag' : 'truck' }}" class="w-4 h-4"></i>
                    {{ $order->pickup_option === 'pickup' ? 'Pickup' : 'Delivery' }}
                  </span>
                  <span class="text-gray-600">{{ $order->payment_method }}</span>
                </div>
                <div class="text-right">
                  <p class="text-sm text-gray-600">Total</p>
                  <p class="text-xl font-bold text-coffee">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
              </div>
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
        <div class="text-6xl mb-4">📦</div>
        <h2 class="text-2xl font-bold mb-3">Belum Ada Riwayat Pesanan</h2>
        <p class="text-gray-600 mb-6">Anda belum pernah melakukan pesanan</p>
        <a href="{{ route('menu') }}" class="inline-block px-8 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
          Mulai Pesan
        </a>
      </div>
    @endif
  </main>

  <script>
    feather.replace();
  </script>
</body>
</html>
