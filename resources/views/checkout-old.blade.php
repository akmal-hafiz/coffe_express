<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout Order — Coffee Express</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'media',
      theme: {
        extend: {
          colors: {
            coffee: '#6F4E37',
            cream: '#F5EBDD',
            brown: '#6B4F4F',
            ink: '#1f1b18'
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    html,body{font-family:'Poppins',system-ui,Arial,sans-serif}
  </style>
</head>
<body class="bg-cream text-[#2C2C2C]">
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-neutral-200/70">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <a href="{{ url('/') }}" class="flex items-center gap-2">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brown text-white shadow">☕</span>
        <span class="text-xl font-extrabold tracking-tight text-coffee">Coffee Express</span>
      </a>
      <a href="{{ url('/') }}" class="text-sm font-medium text-brown hover:opacity-90 transition">Kembali ke Beranda</a>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-6">Checkout Order</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left: Forms -->
      <div class="space-y-6 lg:col-span-2">
        <!-- Ringkasan Pesanan -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70 overflow-hidden">
          <header class="px-5 py-4 border-b border-coffee bg-coffee text-white">
            <h2 class="text-lg font-semibold">Ringkasan Pesanan</h2>
          </header>
          <div class="p-5">
            <div id="summary-items" class="divide-y divide-neutral-200/70"></div>
            <div class="flex items-center justify-between pt-4">
              <span class="text-neutral-600">Total</span>
              <strong id="summary-total" class="text-xl font-bold">Rp0</strong>
            </div>
          </div>
        </section>

        <!-- Kustomisasi Minuman -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70">
          <header class="px-5 py-4 border-b border-neutral-200/70 bg-cream/60">
            <h2 class="text-lg font-semibold">Kustomisasi Minuman</h2>
          </header>
          <div class="p-5 grid md:grid-cols-2 gap-6">
            <!-- Ice -->
            <fieldset>
              <legend class="font-semibold mb-2">Es</legend>
              <div class="space-y-2">
                <label class="flex items-center gap-2">
                  <input type="radio" name="ice" value="normal" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown" checked>
                  <span>Normal Ice</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="ice" value="extra" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown">
                  <span>Extra Ice ❄️</span>
                </label>
              </div>
            </fieldset>
            <!-- Milk -->
            <fieldset>
              <legend class="font-semibold mb-2">Jenis Susu</legend>
              <div class="space-y-2">
                <label class="flex items-center gap-2">
                  <input type="radio" name="milk" value="full-cream" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown" checked>
                  <span>Full Cream</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="milk" value="almond" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown">
                  <span>Almond</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="milk" value="oat" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown">
                  <span>Oat Milk</span>
                </label>
              </div>
            </fieldset>
            <!-- Sugar -->
            <fieldset class="md:col-span-2">
              <legend class="font-semibold mb-2">Gula</legend>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="flex items-center gap-2">
                  <input type="radio" name="sugar" value="normal" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown" checked>
                  <span>Normal Sugar</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="sugar" value="extra" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown">
                  <span>Extra Sugar</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="sugar" value="less" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown">
                  <span>Less Sugar</span>
                </label>
              </div>
            </fieldset>
          </div>
        </section>

        <!-- Metode Pengambilan -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70">
          <header class="px-5 py-4 border-b border-neutral-200/70 bg-cream/60">
            <h2 class="text-lg font-semibold">Metode Pengambilan</h2>
          </header>
          <div class="p-5 space-y-4">
            <div class="grid gap-3 sm:grid-cols-2">
              <label class="flex items-center gap-3 rounded-xl border border-neutral-200/70 px-4 py-3 hover:bg-neutral-50 transition">
                <input type="radio" name="fulfillment" value="pickup" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown" checked>
                <span class="font-medium">🏪 Pickup (Ambil di gerai)</span>
              </label>
              <label class="flex items-center gap-3 rounded-xl border border-neutral-200/70 px-4 py-3 hover:bg-neutral-50 transition">
                <input type="radio" name="fulfillment" value="delivery" class="h-4 w-4 text-brown border-gray-300 focus:ring-2 focus:ring-brown">
                <span class="font-medium">🏍️ Delivery (Kirim ke lokasi)</span>
              </label>
            </div>
            <div id="address-wrap" class="transition-opacity duration-300 opacity-0 pointer-events-none h-0 overflow-hidden">
              <label class="block text-sm mb-2">Alamat Pengantaran</label>
              <input id="address-input" type="text" placeholder="Tulis alamat lengkap" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brown focus:border-transparent bg-white" />
            </div>
          </div>
        </section>

        <!-- Metode Pembayaran -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70">
          <header class="px-5 py-4 border-b border-neutral-200/70 bg-cream/60">
            <h2 class="text-lg font-semibold">Metode Pembayaran</h2>
          </header>
          <div id="pay-grid" class="p-5 grid grid-cols-2 gap-4">
            <!-- OVO -->
            <button type="button" data-method="ovo" class="pay-card rounded-2xl border border-neutral-200/70 px-4 py-3 flex items-center gap-3 hover:shadow-md transition">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#4B2A7B] text-white text-xs font-bold">OVO</span>
              <span class="font-medium">OVO</span>
            </button>
            <!-- GoPay -->
            <button type="button" data-method="gopay" class="pay-card rounded-2xl border border-neutral-200/70 px-4 py-3 flex items-center gap-3 hover:shadow-md transition">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#00AEEF] text-white text-xs font-bold">G</span>
              <span class="font-medium">GoPay</span>
            </button>
            <!-- DANA -->
            <button type="button" data-method="dana" class="pay-card rounded-2xl border border-neutral-200/70 px-4 py-3 flex items-center gap-3 hover:shadow-md transition">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#1087FF] text-white text-xs font-bold">D</span>
              <span class="font-medium">DANA</span>
            </button>
            <!-- ShopeePay -->
            <button type="button" data-method="shopeepay" class="pay-card rounded-2xl border border-neutral-200/70 px-4 py-3 flex items-center gap-3 hover:shadow-md transition">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#EE4D2D] text-white text-xs font-bold">S</span>
              <span class="font-medium">ShopeePay</span>
            </button>
          </div>
        </section>
      </div>

      <!-- Right: Action -->
      <aside class="h-max rounded-2xl shadow-lg bg-white border border-neutral-200/70 p-5 sticky top-24">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-neutral-600">Total Bayar</span>
            <strong id="summary-total-side" class="text-2xl font-extrabold">Rp0</strong>
          </div>
          <button id="confirm-btn" class="w-full mt-2 rounded-2xl bg-[#6B4F4F] hover:bg-[#5a403f] text-white font-semibold px-5 py-3 shadow transition-all">Konfirmasi Pembayaran</button>
          <p class="text-xs text-neutral-500">Dengan menekan tombol di atas, kamu menyetujui syarat dan ketentuan yang berlaku.</p>
        </div>
      </aside>
    </div>
  </main>

  <!-- Success Modal -->
  <div id="success-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" data-close="1"></div>
    <div class="absolute inset-0 grid place-items-center p-6">
      <div class="w-full max-w-md rounded-2xl bg-white text-center p-6 shadow-2xl border border-neutral-200/70">
        <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-green-100 text-green-600 grid place-items-center">✓</div>
        <h3 class="text-xl font-bold mb-2">Pesanan kamu sudah diproses!</h3>
        <p class="text-neutral-600">Terima kasih ☕</p>
        <div class="mt-5 flex justify-center gap-3">
          <a href="{{ url('/') }}" class="rounded-xl bg-brown text-white px-4 py-2 hover:bg-[#5a403f]">Kembali ke Beranda</a>
          <button id="close-modal" class="rounded-xl border border-neutral-300 px-4 py-2 hover:bg-neutral-50">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <script defer src="{{ asset('js/checkout.js') }}"></script>
</body>
</html>
