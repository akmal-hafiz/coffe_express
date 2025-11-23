<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Menu - Coffee Express Admin</title>
  <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow">
            <i data-feather="plus-circle" class="w-6 h-6"></i>
          </span>
          <div>
            <h1 class="text-xl font-extrabold">Tambah Menu Baru</h1>
            <p class="text-xs text-white/80">Isi form di bawah untuk menambahkan menu</p>
          </div>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">
          <i data-feather="arrow-left" class="w-4 h-4"></i>
          <span class="hidden sm:inline">Kembali</span>
        </a>
      </div>
    </div>
  </header>

  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
      <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama Menu -->
        <div class="mb-6">
          <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
            Nama Menu <span class="text-red-500">*</span>
          </label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent @error('name') border-red-500 @enderror"
            placeholder="Contoh: Cappuccino">
          @error('name')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
          <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
            Deskripsi
          </label>
          <textarea id="description" name="description" rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent @error('description') border-red-500 @enderror"
            placeholder="Deskripsi singkat tentang menu ini...">{{ old('description') }}</textarea>
          @error('description')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <!-- Harga & Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <!-- Harga -->
          <div>
            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
              Harga <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
              <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent @error('price') border-red-500 @enderror"
                placeholder="25000">
            </div>
            @error('price')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>

          <!-- Kategori -->
          <div>
            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
              Kategori <span class="text-red-500">*</span>
            </label>
            <select id="category" name="category" required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent @error('category') border-red-500 @enderror">
              <option value="">Pilih Kategori</option>
              <option value="coffee" {{ old('category') === 'coffee' ? 'selected' : '' }}>☕ Coffee</option>
              <option value="non-coffee" {{ old('category') === 'non-coffee' ? 'selected' : '' }}>🥤 Non-Coffee</option>
            </select>
            @error('category')
              <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Upload Gambar -->
        <div class="mb-6">
          <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
            Gambar Menu
          </label>
          <div class="flex items-center gap-4">
            <label for="image" class="flex-1 cursor-pointer">
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-coffee transition">
                <i data-feather="upload" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
                <p class="text-sm text-gray-600">Klik untuk upload gambar</p>
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WEBP (Max: 2MB)</p>
              </div>
              <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
            </label>
          </div>
          @error('image')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
          
          <!-- Image Preview -->
          <div id="imagePreview" class="mt-4 hidden">
            <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
            <img id="preview" src="" alt="Preview" class="w-full max-w-xs rounded-lg shadow-md">
          </div>
        </div>

        <!-- Status Aktif -->
        <div class="mb-8">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" checked
              class="w-5 h-5 text-coffee border-gray-300 rounded focus:ring-coffee">
            <div>
              <span class="text-sm font-semibold text-gray-700">Menu Aktif</span>
              <p class="text-xs text-gray-500">Menu akan ditampilkan di halaman publik</p>
            </div>
          </label>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3">
          <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-coffee text-white rounded-lg hover:bg-coffee/90 shadow-lg hover:shadow-xl transition-all">
            <i data-feather="save" class="w-5 h-5"></i>
            <span>Simpan Menu</span>
          </button>
          <a href="{{ route('admin.menus.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i data-feather="x" class="w-5 h-5"></i>
            <span>Batal</span>
          </a>
        </div>
      </form>
    </div>
  </main>

  <script>
    feather.replace();

    // Image preview function
    function previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('preview').src = e.target.result;
          document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
      }
    }
  </script>
</body>
</html>
