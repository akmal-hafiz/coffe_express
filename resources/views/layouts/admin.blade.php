<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard') — Coffee Express</title>
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
        <a href="{{ route('admin.dashboard') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow hover:bg-gray-100 transition">
          <i data-feather="coffee" class="w-6 h-6"></i>
        </a>
        <div>
          <h1 class="text-xl font-extrabold">Coffee Express Admin</h1>
          <p class="text-xs text-white/80">Dashboard Management</p>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <nav class="hidden md:flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">Dashboard</a>
            <a href="{{ route('admin.menus.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.menus.*') ? 'bg-white/20' : '' }}">Menu</a>
            <a href="{{ route('admin.promos.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.promos.*') ? 'bg-white/20' : '' }}">Promos</a>
            <a href="{{ route('admin.news.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.news.*') ? 'bg-white/20' : '' }}">News</a>
            <a href="{{ route('admin.contacts.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('admin.contacts.*') ? 'bg-white/20' : '' }}">Messages</a>
        </nav>

        {{-- Back to Home Button --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white text-coffee hover:bg-gray-100 shadow-sm transition text-sm font-bold" title="Back to Homepage">
            <i data-feather="home" class="w-4 h-4"></i>
            <span class="hidden md:inline">Back to Home</span>
        </a>

        <div class="h-6 w-px bg-white/20 hidden md:block"></div>

        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="text-sm font-medium hover:text-white/80 transition flex items-center gap-1">
            <i data-feather="log-out" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Logout</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-8">
    @if(session('success'))
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center gap-3">
          <i data-feather="check-circle" class="text-green-600 w-5 h-5"></i>
          <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
      </div>
    @endif

    @yield('content')
  </main>

  <script>
    feather.replace();
  </script>
  @stack('scripts')
</body>
</html>
