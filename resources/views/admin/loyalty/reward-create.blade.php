<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Reward — Coffee Express Admin</title>
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
<body class="bg-cream text-gray-800">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-coffee to-brown text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-coffee shadow">
                    <i data-feather="coffee" class="w-6 h-6"></i>
                </span>
                <div>
                    <h1 class="text-xl font-extrabold">Coffee Express Admin</h1>
                    <p class="text-xs text-white/80">Create New Reward</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Dashboard</a>
                    <a href="{{ route('admin.loyalty.index') }}" class="px-3 py-2 rounded-lg bg-white/20 transition flex items-center gap-1">
                        <i data-feather="gift" class="w-4 h-4"></i>
                        Loyalty
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10 transition">Reports</a>
                </nav>
                <div class="h-6 w-px bg-white/20 hidden md:block"></div>
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

    <main class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('admin.loyalty.index') }}" class="hover:text-coffee transition">Loyalty</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <a href="{{ route('admin.loyalty.rewards') }}" class="hover:text-coffee transition">Rewards</a>
            <i data-feather="chevron-right" class="w-4 h-4"></i>
            <span class="text-coffee font-medium">Tambah Reward</span>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i data-feather="gift" class="w-6 h-6"></i>
                    Tambah Reward Baru
                </h1>
                <p class="text-white/80 mt-1">Buat reward baru untuk program loyalitas</p>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.loyalty.rewards.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                        <div class="flex items-start gap-3">
                            <i data-feather="alert-circle" class="text-red-600 w-5 h-5 mt-0.5"></i>
                            <div>
                                <p class="text-red-800 font-medium">Terdapat kesalahan:</p>
                                <ul class="text-red-700 text-sm mt-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Reward <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="e.g., Diskon 10% Semua Menu">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"
                                placeholder="Deskripsi singkat tentang reward ini...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Reward <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="rewardType" required onchange="toggleTypeFields()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="discount" {{ old('type') == 'discount' ? 'selected' : '' }}>💰 Diskon</option>
                                <option value="free_item" {{ old('type') == 'free_item' ? 'selected' : '' }}>☕ Free Item</option>
                                <option value="voucher" {{ old('type') == 'voucher' ? 'selected' : '' }}>🎟️ Voucher</option>
                                <option value="merchandise" {{ old('type') == 'merchandise' ? 'selected' : '' }}>🎁 Merchandise</option>
                            </select>
                        </div>

                        <!-- Points Required -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Poin Dibutuhkan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="points_required" value="{{ old('points_required') }}" min="1" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="e.g., 500">
                            <p class="text-xs text-gray-500 mt-1">Jumlah poin yang dibutuhkan untuk menukar reward ini</p>
                        </div>

                        <!-- Discount Fields -->
                        <div id="discountFields" class="space-y-4 hidden">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Persentase Diskon (%)
                                </label>
                                <input type="number" name="discount_percentage" value="{{ old('discount_percentage') }}" min="0" max="100"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="e.g., 10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nominal Diskon (Rp)
                                </label>
                                <input type="number" name="discount_amount" value="{{ old('discount_amount') }}" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="e.g., 25000">
                                <p class="text-xs text-gray-500 mt-1">Isi salah satu: persentase ATAU nominal</p>
                            </div>
                        </div>

                        <!-- Free Item Field -->
                        <div id="freeItemFields" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Menu Item
                            </label>
                            <select name="free_item_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">-- Pilih Menu --</option>
                                @foreach($menus ?? [] as $menu)
                                    <option value="{{ $menu->id }}" {{ old('free_item_id') == $menu->id ? 'selected' : '' }}>
                                        {{ $menu->name }} - Rp{{ number_format($menu->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Reward
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-purple-400 transition">
                                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                                <div id="imagePreview" class="hidden mb-4">
                                    <img id="previewImg" src="" alt="Preview" class="max-h-40 mx-auto rounded-lg">
                                </div>
                                <label for="imageInput" class="cursor-pointer">
                                    <i data-feather="upload-cloud" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                                    <p class="text-gray-600">Klik untuk upload gambar</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP (max 2MB)</p>
                                </label>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock" value="{{ old('stock', -1) }}" min="-1" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="-1 untuk unlimited">
                            <p class="text-xs text-gray-500 mt-1">Isi -1 untuk stok unlimited</p>
                        </div>

                        <!-- Max Redemption Per User -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Maks. Penukaran per User
                            </label>
                            <input type="number" name="max_redemption_per_user" value="{{ old('max_redemption_per_user', 0) }}" min="0" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                placeholder="0 untuk unlimited">
                            <p class="text-xs text-gray-500 mt-1">Isi 0 untuk tidak ada batas</p>
                        </div>

                        <!-- Validity Period -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Dari
                                </label>
                                <input type="date" name="valid_from" value="{{ old('valid_from') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Sampai
                                </label>
                                <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- Is Active -->
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <label for="is_active" class="text-gray-700 font-medium">
                                Aktifkan Reward
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.loyalty.rewards') }}" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2 shadow-lg">
                        <i data-feather="save" class="w-5 h-5"></i>
                        Simpan Reward
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips Card -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <h3 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                <i data-feather="info" class="w-5 h-5"></i>
                Tips Membuat Reward
            </h3>
            <ul class="text-sm text-blue-700 space-y-2">
                <li>• Buat nama reward yang menarik dan jelas</li>
                <li>• Sesuaikan poin dengan value reward</li>
                <li>• Gunakan gambar yang menarik untuk meningkatkan minat</li>
                <li>• Batasi stok untuk menciptakan urgensi</li>
                <li>• Pertimbangkan batas penukaran per user agar lebih adil</li>
            </ul>
        </div>
    </main>

    <script>
        feather.replace();

        // Toggle fields based on type
        function toggleTypeFields() {
            const type = document.getElementById('rewardType').value;
            const discountFields = document.getElementById('discountFields');
            const freeItemFields = document.getElementById('freeItemFields');

            // Hide all optional fields first
            discountFields.classList.add('hidden');
            freeItemFields.classList.add('hidden');

            // Show relevant fields based on type
            if (type === 'discount' || type === 'voucher') {
                discountFields.classList.remove('hidden');
            } else if (type === 'free_item') {
                freeItemFields.classList.remove('hidden');
            }
        }

        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleTypeFields();
        });
    </script>
</body>
</html>
