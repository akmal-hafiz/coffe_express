<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Profile Settings — Coffee Express</title>
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
<body class="bg-cream text-[#2C2C2C] min-h-screen">
  <!-- Header -->
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-neutral-200/70">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brown text-white shadow">☕</span>
        <span class="text-xl font-extrabold tracking-tight text-coffee">Coffee Express</span>
      </a>
      <div class="flex items-center gap-4">
        <a href="{{ route('home') }}" class="text-sm font-medium text-brown hover:opacity-90 transition">← Back to Home</a>
        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
      </div>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-extrabold text-coffee mb-2">Profile Settings</h1>
      <p class="text-gray-600">Manage your account information and preferences</p>
    </div>

    <!-- Success Message -->
    @if(session('status') === 'profile-updated')
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center gap-3">
          <i data-feather="check-circle" class="text-green-600 w-5 h-5"></i>
          <p class="text-green-800 font-medium">Profile updated successfully!</p>
        </div>
      </div>
    @endif

    @if(session('status') === 'password-updated')
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center gap-3">
          <i data-feather="check-circle" class="text-green-600 w-5 h-5"></i>
          <p class="text-green-800 font-medium">Password updated successfully!</p>
        </div>
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-neutral-200">
          <!-- User Avatar -->
          <div class="bg-gradient-to-br from-coffee to-brown p-8 text-center">
            <div class="w-24 h-24 mx-auto rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white text-4xl font-bold mb-4 border-4 border-white/30">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h3 class="text-xl font-bold text-white mb-1">{{ Auth::user()->name }}</h3>
            <p class="text-sm text-white/80">{{ Auth::user()->email }}</p>
            <span class="inline-block mt-3 px-3 py-1 bg-white/20 backdrop-blur text-white text-xs font-semibold rounded-full border border-white/30">
              {{ Auth::user()->isAdmin() ? '👑 Admin' : '👤 User' }}
            </span>
          </div>

          <!-- Stats -->
          <div class="p-6 border-b border-gray-100">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Member Since</span>
                <span class="text-sm font-semibold text-coffee">{{ Auth::user()->created_at->format('M Y') }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Total Orders</span>
                <span class="text-sm font-semibold text-coffee">{{ Auth::user()->orders()->count() }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Account Status</span>
                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                  <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                  Active
                </span>
              </div>
            </div>
          </div>

          <!-- Quick Links -->
          <div class="p-6">
            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Quick Links</h4>
            <div class="space-y-2">
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-coffee/5 rounded-lg transition text-sm">
                  <i data-feather="bar-chart-2" class="w-4 h-4 text-coffee"></i>
                  Admin Dashboard
                </a>
              @else
                <a href="{{ route('order.status') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-coffee/5 rounded-lg transition text-sm">
                  <i data-feather="shopping-bag" class="w-4 h-4 text-coffee"></i>
                  My Orders
                </a>
                <a href="{{ route('order.history') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-coffee/5 rounded-lg transition text-sm">
                  <i data-feather="clock" class="w-4 h-4 text-coffee"></i>
                  Order History
                </a>
              @endif
              <a href="{{ route('menu') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-coffee/5 rounded-lg transition text-sm">
                <i data-feather="coffee" class="w-4 h-4 text-coffee"></i>
                Browse Menu
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Update Profile Information -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-neutral-200">
          <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-coffee/5 to-brown/5">
            <h2 class="text-xl font-bold flex items-center gap-2">
              <i data-feather="user" class="w-5 h-5 text-coffee"></i>
              Profile Information
            </h2>
            <p class="text-sm text-gray-600 mt-1">Update your account's profile information and email address.</p>
          </div>
          <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
              @csrf
              @method('PATCH')

              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-coffee focus:border-transparent transition">
                @error('name')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-coffee focus:border-transparent transition">
                @error('email')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                  <div class="mt-2 text-sm">
                    <p class="text-gray-800">
                      Your email address is unverified.
                      <button form="send-verification" class="underline text-coffee hover:text-brown">
                        Click here to re-send the verification email.
                      </button>
                    </p>
                  </div>
                @endif
              </div>

              <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition flex items-center gap-2">
                  <i data-feather="save" class="w-4 h-4"></i>
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-neutral-200">
          <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-coffee/5 to-brown/5">
            <h2 class="text-xl font-bold flex items-center gap-2">
              <i data-feather="lock" class="w-5 h-5 text-coffee"></i>
              Update Password
            </h2>
            <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
          </div>
          <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
              @csrf
              @method('PUT')

              <!-- Current Password -->
              <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-coffee focus:border-transparent transition">
                @error('current_password', 'updatePassword')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- New Password -->
              <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <input type="password" id="password" name="password" autocomplete="new-password"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-coffee focus:border-transparent transition">
                @error('password', 'updatePassword')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Confirm Password -->
              <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-coffee focus:border-transparent transition">
                @error('password_confirmation', 'updatePassword')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-coffee text-white rounded-xl font-semibold hover:bg-coffee/90 transition flex items-center gap-2">
                  <i data-feather="shield" class="w-4 h-4"></i>
                  Update Password
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-200">
          <div class="px-6 py-4 border-b border-red-100 bg-gradient-to-r from-red-50 to-red-100">
            <h2 class="text-xl font-bold flex items-center gap-2 text-red-800">
              <i data-feather="alert-triangle" class="w-5 h-5"></i>
              Delete Account
            </h2>
            <p class="text-sm text-red-700 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
          </div>
          <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">
              Before deleting your account, please download any data or information that you wish to retain.
            </p>
            <button onclick="confirmDelete()" class="px-6 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition flex items-center gap-2">
              <i data-feather="trash-2" class="w-4 h-4"></i>
              Delete Account
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="fixed inset-0 bg-black/50 z-50 p-4" style="display: none; align-items: center; justify-content: center;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
      <h3 class="text-xl font-bold text-red-800 mb-4 flex items-center gap-2">
        <i data-feather="alert-triangle" class="w-6 h-6"></i>
        Are you sure?
      </h3>
      <p class="text-gray-600 mb-6">
        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
      </p>
      
      <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')
        
        <div class="mb-4">
          <label for="delete_password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <input type="password" id="delete_password" name="password" required
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
          @error('password', 'userDeletion')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3">
          <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition">
            Delete Account
          </button>
          <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    feather.replace();

    function confirmDelete() {
      const modal = document.getElementById('delete-modal');
      modal.style.display = 'flex';
    }

    function closeDeleteModal() {
      const modal = document.getElementById('delete-modal');
      modal.style.display = 'none';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeDeleteModal();
    });

    // Show success message with SweetAlert
    @if(session('status') === 'profile-updated')
      Swal.fire({
        icon: 'success',
        title: 'Profile Updated!',
        text: 'Your profile information has been updated successfully.',
        confirmButtonColor: '#6F4E37',
        timer: 3000
      });
    @endif

    @if(session('status') === 'password-updated')
      Swal.fire({
        icon: 'success',
        title: 'Password Updated!',
        text: 'Your password has been changed successfully.',
        confirmButtonColor: '#6F4E37',
        timer: 3000
      });
    @endif
  </script>
</body>
</html>
