<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Management - Coffee Express Admin</title>
  <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            coffee: '#6F4E37',
            cream: '#F5EBDD',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-cream">
  <!-- Header -->
  <header class="bg-gradient-to-r from-coffee to-coffee/90 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow">
            <i data-feather="coffee" class="w-6 h-6"></i>
          </span>
          <div>
            <h1 class="text-xl font-extrabold">Menu Management</h1>
            <p class="text-xs text-white/80">Kelola menu Coffee Express</p>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Kembali ke Dashboard</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Message -->
    @if(session('success'))
      <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
        <i data-feather="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
        <div>
          <p class="text-green-800 font-semibold">Berhasil!</p>
          <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
      </div>
    @endif

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-coffee">Daftar Menu</h2>
        <p class="text-gray-600 text-sm">Total: {{ $menus->total() }} menu</p>
      </div>
      <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-coffee text-white rounded-lg hover:bg-coffee/90 shadow-lg hover:shadow-xl transition-all">
        <i data-feather="plus" class="w-5 h-5"></i>
        <span>Tambah Menu Baru</span>
      </a>
    </div>

    <!-- Menus Grid -->
    @if($menus->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($menus as $menu)
          <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
            <!-- Image -->
            <div class="relative h-48 bg-gray-200">
              @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-coffee/20 to-coffee/10">
                  <i data-feather="image" class="w-16 h-16 text-coffee/30"></i>
                </div>
              @endif
              
              <!-- Category Badge -->
              <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold {{ $menu->category === 'coffee' ? 'bg-coffee text-white' : 'bg-blue-500 text-white' }}">
                {{ $menu->category === 'coffee' ? 'Coffee' : 'Non-Coffee' }}
              </span>
              
              <!-- Status Badge -->
              @if(!$menu->is_active)
                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                  Inactive
                </span>
              @endif
            </div>

            <!-- Content -->
            <div class="p-5">
              <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $menu->name }}</h3>
              
              @if($menu->description)
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $menu->description }}</p>
              @endif
              
              <div class="flex items-center justify-between mb-4">
                <span class="text-2xl font-bold text-coffee">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                <span class="text-xs text-gray-500">ID: #{{ $menu->id }}</span>
              </div>

              <!-- Actions -->
              <div class="flex gap-2">
                <a href="{{ route('admin.menus.edit', $menu) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                  <i data-feather="edit-2" class="w-4 h-4"></i>
                  <span>Edit</span>
                </a>
                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                    <span>Hapus</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="bg-white rounded-lg shadow p-4">
        {{ $menus->links() }}
      </div>
    @else
      <!-- Empty State -->
      <div class="bg-white rounded-xl shadow-md p-12 text-center">
        <i data-feather="coffee" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Menu</h3>
        <p class="text-gray-600 mb-6">Mulai tambahkan menu pertama Anda!</p>
        <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition">
          <i data-feather="plus" class="w-5 h-5"></i>
          <span>Tambah Menu Baru</span>
        </a>
      </div>
    @endif
  </main>

  <script>
    feather.replace();
  </script>
</body>
</html>
