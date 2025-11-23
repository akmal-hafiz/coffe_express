<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Checkout Order — Coffee Express</title>
  <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Google Maps API -->
  <script>
    // Global error handler for Google Maps
    window.gm_authFailure = function() {
      console.error('Google Maps authentication failed');
      const mapContainer = document.getElementById('map-container');
      if (mapContainer) {
        mapContainer.innerHTML = `
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <p class="text-yellow-800 font-semibold">⚠️ Google Maps API Key Issue</p>
            <p class="text-sm text-yellow-700 mt-1">Silakan masukkan alamat secara manual</p>
          </div>
        `;
      }
    };
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIuRacxDvjo-JzAzMGzlWGZ8jzzCFzOGI&libraries=places"></script>
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
    #map {
      height: 400px;
      width: 100%;
      border-radius: 12px;
      margin-top: 16px;
    }
    .pac-container {
      z-index: 9999;
      font-family: 'Poppins', sans-serif;
      border-radius: 8px;
      margin-top: 4px;
    }
  </style>
</head>
<body class="bg-cream text-[#2C2C2C]">
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-neutral-200/70">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-brown text-white shadow">
          <i data-feather="coffee" class="w-5 h-5"></i>
        </span>
        <span class="text-xl font-extrabold tracking-tight text-coffee">Coffee Express</span>
      </a>
      <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="text-sm font-medium text-brown hover:opacity-90 transition">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-6">Checkout Order</h1>

    <form id="checkout-form" action="{{ route('orders.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      @csrf
      
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

        <!-- Customer Information -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70">
          <header class="px-5 py-4 border-b border-neutral-200/70 bg-cream/60">
            <h2 class="text-lg font-semibold">Informasi Pelanggan</h2>
          </header>
          <div class="p-5 space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
              <input type="text" name="customer_name" value="{{ Auth::user()->name }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
              @error('customer_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
              <input type="tel" name="phone" required placeholder="08xxxxxxxxxx"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent">
              @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </section>

        <!-- Metode Pengambilan -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70">
          <header class="px-5 py-4 border-b border-neutral-200/70 bg-cream/60">
            <h2 class="text-lg font-semibold">Metode Pengambilan</h2>
          </header>
          <div class="p-5 space-y-4">
            <fieldset class="space-y-3">
              <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-coffee transition">
                <input type="radio" name="pickup_option" value="pickup" class="h-5 w-5 text-coffee" checked onchange="toggleAddress()">
                <div>
                  <div class="font-semibold flex items-center gap-2">
                    <i data-feather="shopping-bag" class="w-4 h-4"></i>
                    Pickup
                  </div>
                  <div class="text-sm text-gray-600">Ambil sendiri di toko</div>
                </div>
              </label>
              
              <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-coffee transition">
                <input type="radio" name="pickup_option" value="delivery" class="h-5 w-5 text-coffee" onchange="toggleAddress()">
                <div>
                  <div class="font-semibold flex items-center gap-2">
                    <i data-feather="truck" class="w-4 h-4"></i>
                    Delivery
                  </div>
                  <div class="text-sm text-gray-600">Antar ke alamat Anda</div>
                </div>
              </label>
            </fieldset>

            <div id="address-wrap" class="hidden">
              <label class="block text-sm font-medium mb-2">Alamat Pengiriman <span class="text-red-500">*</span></label>
              <div class="flex gap-2">
                <input type="text" id="address-input" name="address" placeholder="Ketik alamat (contoh: Monas Jakarta)..."
                  class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee focus:border-transparent"
                  autocomplete="off">
                <button type="button" id="search-btn" onclick="searchAddress()" 
                  class="px-4 py-2 bg-coffee text-white rounded-lg hover:bg-coffee/90 transition flex items-center gap-2">
                  <i data-feather="search" class="w-4 h-4"></i>
                  Cari
                </button>
              </div>
              @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
              <p class="text-xs text-gray-500 mt-1">💡 Tip: Ketik nama tempat atau alamat, lalu klik "Cari" atau pilih dari dropdown</p>
              
              <!-- Google Maps Container -->
              <div id="map-container" class="mt-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3 flex items-start gap-2">
                  <i data-feather="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                  <p class="text-sm text-blue-800">Klik dan drag pin merah untuk menentukan lokasi pengiriman yang tepat</p>
                </div>
                <div id="map"></div>
                <div class="mt-3 text-sm text-gray-600">
                  <p><strong>Alamat terpilih:</strong></p>
                  <p id="selected-address" class="text-coffee-700 font-medium">-</p>
                </div>
              </div>
              
              <!-- Hidden inputs for coordinates -->
              <input type="hidden" id="latitude" name="latitude">
              <input type="hidden" id="longitude" name="longitude">
            </div>
          </div>
        </section>

        <!-- Metode Pembayaran -->
        <section class="rounded-2xl shadow-md bg-white border border-neutral-200/70">
          <header class="px-5 py-4 border-b border-neutral-200/70 bg-cream/60">
            <h2 class="text-lg font-semibold">Metode Pembayaran</h2>
          </header>
          <div class="p-5">
            <fieldset class="space-y-3">
              <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-coffee transition">
                <input type="radio" name="payment_method" value="Cash" class="h-5 w-5 text-coffee" checked>
                <span class="font-semibold">💵 Cash</span>
              </label>
              
              <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-coffee transition">
                <input type="radio" name="payment_method" value="E-Wallet" class="h-5 w-5 text-coffee">
                <span class="font-semibold">📱 E-Wallet (GoPay, OVO, Dana)</span>
              </label>
              
              <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-coffee transition">
                <input type="radio" name="payment_method" value="Debit Card" class="h-5 w-5 text-coffee">
                <span class="font-semibold">💳 Debit Card</span>
              </label>
            </fieldset>
          </div>
        </section>
      </div>

      <!-- Right: Summary Sidebar -->
      <aside class="lg:col-span-1">
        <div class="sticky top-24 rounded-2xl shadow-md bg-white border border-neutral-200/70 p-5">
          <h3 class="text-lg font-bold mb-4">Total Pembayaran</h3>
          <div class="space-y-3 mb-6">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span id="summary-total-side" class="font-semibold">Rp0</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Biaya Layanan</span>
              <span class="font-semibold">Rp0</span>
            </div>
            <hr>
            <div class="flex justify-between text-lg font-bold">
              <span>Total</span>
              <span id="final-total" class="text-coffee">Rp0</span>
            </div>
          </div>
          
          <button type="submit" class="w-full rounded-xl bg-coffee text-white px-6 py-3 font-semibold shadow-lg hover:bg-coffee/90 active:scale-[.98] transition-all">
            Bayar Sekarang
          </button>
          
          <p class="text-xs text-gray-500 text-center mt-4">
            Dengan melanjutkan, Anda menyetujui syarat & ketentuan kami
          </p>
        </div>
      </aside>

      <!-- Hidden inputs for cart data -->
      <input type="hidden" name="items" id="items-json">
      <input type="hidden" name="total_price" id="total-price-input">
    </form>
  </main>

  <script>
    // Load cart from localStorage
    const STORAGE_KEY = 'ce_cart';
    const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });

    function loadCart() {
      try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
      } catch {
        return [];
      }
    }

    function renderSummary() {
      const itemsWrap = document.getElementById('summary-items');
      const totalEl = document.getElementById('summary-total');
      const totalSideEl = document.getElementById('summary-total-side');
      const finalTotalEl = document.getElementById('final-total');
      const cart = loadCart();

      let total = 0;
      itemsWrap.innerHTML = '';

      if (cart.length === 0) {
        itemsWrap.innerHTML = '<div class="text-center text-gray-500 py-6">Keranjang kosong. <a href="{{ route("menu") }}" class="text-coffee underline">Pilih menu</a></div>';
      } else {
        cart.forEach(item => {
          const line = document.createElement('div');
          line.className = 'flex items-center gap-3 py-3';
          const lineTotal = item.price * item.qty;
          total += lineTotal;
          line.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="h-12 w-12 rounded-lg object-cover bg-neutral-100"/>
            <div class="flex-1">
              <div class="font-medium">${item.name}</div>
              <div class="text-sm text-neutral-500">${item.qty} x ${currency.format(item.price)}</div>
            </div>
            <div class="font-semibold">${currency.format(lineTotal)}</div>
          `;
          itemsWrap.appendChild(line);
        });
      }

      totalEl.textContent = currency.format(total);
      totalSideEl.textContent = currency.format(total);
      finalTotalEl.textContent = currency.format(total);

      // Set hidden inputs
      document.getElementById('items-json').value = JSON.stringify(cart);
      document.getElementById('total-price-input').value = total;
    }

    function toggleAddress() {
      const deliveryRadio = document.querySelector('input[name="pickup_option"][value="delivery"]');
      const addressWrap = document.getElementById('address-wrap');
      const addressField = document.querySelector('input[name="address"]');
      
      if (deliveryRadio.checked) {
        addressWrap.classList.remove('hidden');
        addressField.required = true;
      } else {
        addressWrap.classList.add('hidden');
        addressField.required = false;
      }
    }

    // Form submission with SweetAlert
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
      const cart = loadCart();
      if (cart.length === 0) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Keranjang Kosong',
          text: 'Silakan pilih menu terlebih dahulu!',
          confirmButtonColor: '#6F4E37'
        });
        return false;
      }
    });

    // Initialize
    renderSummary();
    toggleAddress();

    // Show success message if redirected from order creation
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Pesanan Berhasil!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#6F4E37'
      }).then(() => {
        // Clear cart after successful order
        localStorage.removeItem(STORAGE_KEY);
        window.location.href = '{{ route("order.status") }}';
      });
    @endif
    
    // Replace feather icons
    feather.replace();
    
    // ========================================
    // GOOGLE MAPS INTEGRATION
    // ========================================
    let map, marker, geocoder, autocomplete;
    
    // Initialize Google Maps
    function initMap() {
      // Check if Google Maps is loaded
      if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.error('Google Maps API failed to load');
        document.getElementById('map-container').innerHTML = `
          <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
            <i data-feather="alert-circle" class="w-8 h-8 text-red-600 mx-auto mb-2"></i>
            <p class="text-red-800 font-semibold">Google Maps gagal dimuat</p>
            <p class="text-sm text-red-600 mt-1">Silakan refresh halaman atau masukkan alamat secara manual</p>
          </div>
        `;
        feather.replace();
        return;
      }
      
      // Default location: Jakarta
      const defaultLocation = { lat: -6.2088, lng: 106.8456 };
      
      // Initialize geocoder
      geocoder = new google.maps.Geocoder();
      
      // Create map
      map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 13,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
        zoomControl: true,
        styles: [
          {
            featureType: 'poi',
            elementType: 'labels',
            stylers: [{ visibility: 'off' }]
          }
        ]
      });
      
      // Create draggable marker
      marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        title: 'Lokasi Pengiriman'
      });
      
      // Initialize autocomplete
      const input = document.getElementById('address-input');
      
      // Check if Places library is loaded
      if (typeof google.maps.places === 'undefined') {
        console.error('Google Places library not loaded');
        return;
      }
      
      try {
        autocomplete = new google.maps.places.Autocomplete(input, {
          componentRestrictions: { country: 'id' },
          fields: ['formatted_address', 'geometry', 'name', 'address_components'],
          types: ['geocode', 'establishment']
        });
        
        // Bind autocomplete to map bounds
        autocomplete.bindTo('bounds', map);
      } catch (error) {
        console.error('Error initializing autocomplete:', error);
      }
      
      // When place is selected from autocomplete
      autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        
        if (!place.geometry) {
          return;
        }
        
        // Update map and marker
        map.setCenter(place.geometry.location);
        map.setZoom(17);
        marker.setPosition(place.geometry.location);
        
        // Update address and coordinates
        updateAddress(place.geometry.location);
      });
      
      // When marker is dragged
      marker.addListener('dragend', function(event) {
        updateAddress(event.latLng);
      });
      
      // Click on map to move marker
      map.addListener('click', function(event) {
        marker.setPosition(event.latLng);
        updateAddress(event.latLng);
      });
      
      // Try to get user's current location
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            const userLocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            map.setCenter(userLocation);
            marker.setPosition(userLocation);
            updateAddress(new google.maps.LatLng(userLocation.lat, userLocation.lng));
          },
          function() {
            console.log('Geolocation permission denied');
          }
        );
      }
    }
    
    // Update address from coordinates
    function updateAddress(location) {
      const lat = location.lat();
      const lng = location.lng();
      
      // Update hidden inputs
      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = lng;
      
      // Reverse geocode to get address
      geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
          const address = results[0].formatted_address;
          document.getElementById('address-input').value = address;
          document.getElementById('selected-address').textContent = address;
        }
      });
    }
    
    // Manual search function
    function searchAddress() {
      const input = document.getElementById('address-input').value;
      
      if (!input || input.trim() === '') {
        Swal.fire({
          icon: 'warning',
          title: 'Alamat Kosong',
          text: 'Silakan ketik alamat yang ingin dicari',
          confirmButtonColor: '#6F4E37'
        });
        return;
      }
      
      if (!geocoder) {
        console.error('Geocoder not initialized');
        return;
      }
      
      // Show loading
      const searchBtn = document.getElementById('search-btn');
      const originalHTML = searchBtn.innerHTML;
      searchBtn.innerHTML = '<i data-feather="loader" class="w-4 h-4 animate-spin"></i> Mencari...';
      searchBtn.disabled = true;
      feather.replace();
      
      // Geocode the address
      geocoder.geocode({ 
        address: input,
        componentRestrictions: { country: 'id' }
      }, function(results, status) {
        // Reset button
        searchBtn.innerHTML = originalHTML;
        searchBtn.disabled = false;
        feather.replace();
        
        if (status === 'OK' && results[0]) {
          const location = results[0].geometry.location;
          
          // Update map and marker
          map.setCenter(location);
          map.setZoom(17);
          marker.setPosition(location);
          
          // Update address and coordinates
          updateAddress(location);
          
          // Success feedback
          Swal.fire({
            icon: 'success',
            title: 'Lokasi Ditemukan!',
            text: results[0].formatted_address,
            timer: 2000,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Alamat Tidak Ditemukan',
            text: 'Coba gunakan nama tempat yang lebih spesifik (contoh: Monas Jakarta)',
            confirmButtonColor: '#6F4E37'
          });
        }
      });
    }
    
    // Allow Enter key to search
    document.addEventListener('DOMContentLoaded', function() {
      const addressInput = document.getElementById('address-input');
      if (addressInput) {
        addressInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
          }
        });
      }
    });
    
    // Initialize map when delivery is selected
    document.addEventListener('DOMContentLoaded', function() {
      // Check if delivery is already selected on page load
      const deliveryRadio = document.querySelector('input[name="pickup_option"][value="delivery"]');
      if (deliveryRadio && deliveryRadio.checked) {
        initMap();
      }
      
      // Initialize map when delivery option is selected
      document.querySelectorAll('input[name="pickup_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
          if (this.value === 'delivery' && !map) {
            // Small delay to ensure map container is visible
            setTimeout(() => {
              initMap();
            }, 100);
          }
        });
      });
    });
  </script>
</body>
</html>
