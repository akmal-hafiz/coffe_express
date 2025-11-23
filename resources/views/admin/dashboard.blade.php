<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard — Coffee Express</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    html,body{font-family:'Poppins',system-ui,Arial,sans-serif}
  </style>
</head>
<body class="bg-cream text-[#2C2C2C]">
  <!-- Header -->
  <header class="sticky top-0 z-40 bg-gradient-to-r from-coffee to-brown text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow">
          <i data-feather="coffee" class="w-6 h-6"></i>
        </span>
        <div>
          <h1 class="text-xl font-extrabold">Coffee Express Admin</h1>
          <p class="text-xs text-white/80">Dashboard Management</p>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">
          <i data-feather="menu" class="w-4 h-4"></i>
          <span class="hidden sm:inline">Kelola Menu</span>
        </a>
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
    <!-- User & Revenue Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <!-- Total Users -->
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-purple-100 mb-1">Total Users</p>
            <p class="text-3xl font-bold">{{ $userStats['total_users'] }}</p>
            <p class="text-xs text-purple-100 mt-1">+{{ $userStats['new_users_today'] }} today</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
            <i data-feather="users" class="w-6 h-6"></i>
          </div>
        </div>
      </div>

      <!-- New Users This Week -->
      <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-indigo-100 mb-1">New This Week</p>
            <p class="text-3xl font-bold">{{ $userStats['new_users_week'] }}</p>
            <p class="text-xs text-indigo-100 mt-1">registered users</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
            <i data-feather="user-plus" class="w-6 h-6"></i>
          </div>
        </div>
      </div>

      <!-- Total Revenue -->
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-green-100 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold">Rp{{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-green-100 mt-1">{{ $revenueStats['total_orders'] }} orders</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
            <i data-feather="dollar-sign" class="w-6 h-6"></i>
          </div>
        </div>
      </div>

      <!-- Today's Revenue -->
      <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-orange-100 mb-1">Today's Revenue</p>
            <p class="text-2xl font-bold">Rp{{ number_format($revenueStats['today_revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-orange-100 mt-1">{{ $revenueStats['today_orders'] }} orders</p>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
            <i data-feather="trending-up" class="w-6 h-6"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Status Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-gray-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-3xl font-bold text-gray-700">{{ $stats['pending'] }}</p>
          </div>
          <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
            <i data-feather="clock" class="w-6 h-6 text-gray-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-yellow-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Preparing</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['preparing'] }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
            <i data-feather="coffee" class="w-6 h-6 text-yellow-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Ready</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['ready'] }}</p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Completed</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['completed'] }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <i data-feather="award" class="w-6 h-6 text-blue-600"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center gap-3">
          <i data-feather="check-circle" class="text-green-600 w-5 h-5"></i>
          <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
      </div>
    @endif

    <!-- Recent Registered Users -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
        <h2 class="text-xl font-bold flex items-center gap-2 text-gray-800">
          <i data-feather="user-check" class="w-5 h-5 text-purple-600"></i>
          Recent Registered Users
        </h2>
      </div>
      <div class="p-6">
        @if($recentUsers->count() > 0)
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($recentUsers as $user)
              <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-1 text-xs text-gray-500">
                  <i data-feather="calendar" class="w-3 h-3"></i>
                  <span>{{ $user->created_at->diffForHumans() }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-gray-500 text-center py-4">No users registered yet</p>
        @endif
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-coffee/5 to-brown/5">
        <h2 class="text-xl font-bold flex items-center gap-2">
          <i data-feather="list" class="w-5 h-5"></i>
          All Orders
        </h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order ID</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Est. Time</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                  <div class="text-xs text-gray-500">{{ $order->created_at->format('d M, H:i') }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                  <div class="text-xs text-gray-500">{{ $order->phone }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm">
                    @foreach($order->items as $item)
                      <div class="text-gray-900">{{ $item['qty'] }}x {{ $item['name'] }}</div>
                    @endforeach
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-bold text-coffee">Rp{{ number_format($order->total_price, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $order->pickup_option === 'pickup' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ $order->pickup_option === 'pickup' ? 'Pickup' : 'Delivery' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="status-form">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" 
                      class="text-xs font-semibold rounded-full px-3 py-1 border-0 cursor-pointer
                      {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                      {{ $order->status === 'preparing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                      {{ $order->status === 'ready' ? 'bg-green-100 text-green-800' : '' }}
                      {{ $order->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                      <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                      <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                      <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                  </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button onclick="openTimeModal({{ $order->id }}, {{ $order->estimated_time ?? 0 }})" 
                    class="text-sm text-coffee hover:text-coffee/80 font-medium flex items-center gap-1">
                    <i data-feather="clock" class="w-4 h-4"></i>
                    {{ $order->estimated_time ? $order->estimated_time . ' min' : 'Set Time' }}
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <button onclick="viewOrder({{ $order->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                    <i data-feather="eye" class="w-4 h-4"></i>
                  </button>
                  <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                      <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                  <i data-feather="coffee" class="w-16 h-16 mx-auto mb-2 text-gray-400"></i>
                  <p>No orders yet</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
      </div>
    </div>
  </main>

  <!-- Estimated Time Modal -->
  <div id="time-modal" class="hidden fixed inset-0 bg-black/50 z-50 p-4" style="display: none; align-items: center; justify-content: center;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
      <h3 class="text-xl font-bold mb-4">Set Estimated Time</h3>
      <form id="time-form" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Estimated completion time (minutes)</label>
          <input type="number" name="estimated_time" id="estimated-time-input" min="1" max="120" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
        </div>
        <div class="flex gap-3">
          <button type="submit" class="flex-1 px-6 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition">
            Save
          </button>
          <button type="button" onclick="closeTimeModal()" class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    feather.replace();

    // Estimated Time Modal
    function openTimeModal(orderId, currentTime) {
      const modal = document.getElementById('time-modal');
      modal.style.display = 'flex';
      modal.classList.remove('hidden');
      document.getElementById('time-form').action = `/admin/orders/${orderId}/estimated-time`;
      document.getElementById('estimated-time-input').value = currentTime || 15;
    }

    function closeTimeModal() {
      const modal = document.getElementById('time-modal');
      modal.style.display = 'none';
      modal.classList.add('hidden');
    }

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#6F4E37',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // Status change confirmation
    document.querySelectorAll('.status-form select').forEach(select => {
      select.addEventListener('change', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const newStatus = this.value;
        
        Swal.fire({
          title: 'Update Status?',
          text: `Change status to ${newStatus}?`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#6F4E37',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, update it!'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          } else {
            location.reload();
          }
        });
      });
    });

    // Auto refresh every 30 seconds
    setInterval(() => {
      location.reload();
    }, 30000);
  </script>
</body>
</html>
