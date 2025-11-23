<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: { extend: { colors: { coffee: '#6F4E37', cream: '#F5EBDD', gold: '#C6A664' } } }
      }
    </script>
    <title>Contact Us</title>
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,400;0,700;0,800;1,200;1,400;1,700;1,800&display=swap" rel="stylesheet"/>
    <script defer src="{{ asset('js/api.js') }}"></script>
</head>
<body class="bg-cream text-[#2C2C2C]">
<nav class="navbar px-6 md:px-10 py-4 sticky top-0 bg-white/95 backdrop-blur-md shadow-sm border-b border-coffee-200 z-[1000] flex items-center justify-between transition-all duration-300">
  <a href="{{ url('/') }}" class="Logo flex items-center gap-2 text-2xl md:text-3xl font-extrabold text-coffee-700 tracking-tight hover:text-coffee-900 transition-colors">
    <i data-feather="coffee" class="w-8 h-8"></i>
    <span>Coffee Express</span>
  </a>

  <div class="flex items-center gap-6">
    <!-- Navigation Links -->
    <ul class="flex items-center gap-6 text-sm md:text-base">
      <li><a class="hover:text-coffee transition-colors" href="{{ url('/') }}#home">Home</a></li>
      <li><a class="hover:text-coffee transition-colors" href="{{ url('menu') }}">Menu</a></li>
      <li><a class="hover:text-coffee transition-colors" href="{{ url('contact') }}">Contact Us</a></li>

      @auth
        @if(Auth::user()->isAdmin())
          <li><a class="hover:text-coffee transition-colors font-semibold" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
        @else
          <li><a class="hover:text-coffee transition-colors" href="{{ route('order.status') }}">My Orders</a></li>
        @endif
        
        <!-- User Info Dropdown -->
        <li class="relative group">
          <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-coffee/10 transition-colors">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-coffee to-brown flex items-center justify-center text-white font-bold text-sm">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="font-medium text-sm">{{ Auth::user()->name }}</span>
            <i data-feather="chevron-down" class="w-4 h-4"></i>
          </button>
          
          <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
            <div class="p-4 border-b border-gray-100">
              <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
              <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
              <span class="inline-flex mt-2 px-2 py-1 bg-coffee/10 text-coffee text-xs font-semibold rounded-full items-center gap-1">
                <i data-feather="{{ Auth::user()->isAdmin() ? 'shield' : 'user' }}" class="w-3 h-3"></i>
                {{ Auth::user()->isAdmin() ? 'Admin' : 'User' }}
              </span>
            </div>
            <div class="p-2">
              <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 rounded-lg transition text-sm">
                <i data-feather="user" class="w-4 h-4"></i>
                Profile Settings
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-red-50 text-red-600 rounded-lg transition text-sm">
                  <i data-feather="log-out" class="w-4 h-4"></i>
                  Logout
                </button>
              </form>
            </div>
          </div>
        </li>
      @else
        <li><a class="hover:text-coffee transition-colors" href="{{ route('login') }}">Login</a></li>
        <li><a class="bg-coffee text-white px-4 py-2 rounded-lg hover:bg-coffee/90 transition-colors" href="{{ route('register') }}">Register</a></li>
      @endauth
    </ul>

    <!-- Cart Icon -->
    <button id="cart-icon" class="relative p-2 hover:bg-coffee/10 rounded-full transition-colors">
      <i data-feather="shopping-cart" class="w-6 h-6 text-coffee"></i>
      <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">0</span>
    </button>
  </div>
</nav>

  <section class="contact-section" style="padding: 80px 0;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
      <div class="section-header" style="text-align: center; margin-bottom: 60px;">
        <h2 style="font-size: 2.5rem; font-weight: 700; color: #6F4E37; margin-bottom: 16px;">Contact Us</h2>
        <p style="font-size: 1.1rem; color: #666;">Tanya lokasi cabang kami di Jakarta atau kirim pesan langsung.</p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; align-items: start;">
        <div>
          <form class="contact-form" action="#" method="post" style="background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,.08);">
            <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 20px; color: #6F4E37;">Send us a Message</h3>
            <div style="display: grid; gap: 16px;">
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <input type="text" name="name" placeholder="Nama" required style="padding: 14px 16px; border-radius: 8px; border: 2px solid #e5e7eb; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6F4E37'" onblur="this.style.borderColor='#e5e7eb'">
                <input type="email" name="email" placeholder="Email" required style="padding: 14px 16px; border-radius: 8px; border: 2px solid #e5e7eb; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6F4E37'" onblur="this.style.borderColor='#e5e7eb'">
              </div>
              <input type="text" name="subject" placeholder="Subjek" style="padding: 14px 16px; border-radius: 8px; border: 2px solid #e5e7eb; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6F4E37'" onblur="this.style.borderColor='#e5e7eb'">
              <textarea name="message" placeholder="Pesan Anda" rows="6" style="padding: 14px 16px; border-radius: 8px; border: 2px solid #e5e7eb; font-size: 1rem; resize: vertical; transition: all 0.3s;" onfocus="this.style.borderColor='#6F4E37'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
              <button class="btn btn-primary" type="submit" style="padding: 14px 32px; background: #6F4E37; color: white; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s; border: none;" onmouseover="this.style.background='#5a3d2b'" onmouseout="this.style.background='#6F4E37'">Kirim Pesan</button>
            </div>
          </form>
          
          <div style="margin-top: 30px; padding: 30px; background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,.08);">
            <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 20px; color: #6F4E37;">Contact Info</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <div style="display: flex; align-items: start; gap: 12px;">
                <i data-feather="map-pin" style="color: #6F4E37; min-width: 20px;"></i>
                <div>
                  <strong style="display: block; margin-bottom: 4px;">Address</strong>
                  <p style="color: #666; line-height: 1.6;">Jl. Coffee Street No. 123<br>Jakarta, Indonesia</p>
                </div>
              </div>
              <div style="display: flex; align-items: start; gap: 12px;">
                <i data-feather="phone" style="color: #6F4E37; min-width: 20px;"></i>
                <div>
                  <strong style="display: block; margin-bottom: 4px;">Phone</strong>
                  <p style="color: #666;">(021) 1234-5678</p>
                </div>
              </div>
              <div style="display: flex; align-items: start; gap: 12px;">
                <i data-feather="mail" style="color: #6F4E37; min-width: 20px;"></i>
                <div>
                  <strong style="display: block; margin-bottom: 4px;">Email</strong>
                  <p style="color: #666;">info@coffeeexpress.com</p>
                </div>
              </div>
              <div style="display: flex; align-items: start; gap: 12px;">
                <i data-feather="clock" style="color: #6F4E37; min-width: 20px;"></i>
                <div>
                  <strong style="display: block; margin-bottom: 4px;">Opening Hours</strong>
                  <p style="color: #666;">Mon - Fri: 07:00 - 22:00<br>Sat - Sun: 08:00 - 23:00</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div>
          <div id="map" style="height: 600px; width: 100%; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.08);"></div>
        </div>
      </div>
    </div>
  </section>

  <script>
    // Initialize Leaflet map centered on Jakarta
    const map = L.map('map').setView([-6.200000, 106.816666], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const locations = [
      { name: 'DG Donuts - Jakarta Pusat', coords: [-6.186486, 106.834091] },
      { name: 'DG Donuts - Jakarta Selatan', coords: [-6.260718, 106.781616] },
      { name: 'DG Donuts - Jakarta Barat', coords: [-6.168329, 106.758849] },
      { name: 'DG Donuts - Jakarta Timur', coords: [-6.211544, 106.900611] }
    ];

    locations.forEach(loc => {
      L.marker(loc.coords).addTo(map).bindPopup(`<b>${loc.name}</b>`);
    });
  </script>
  <footer class="site-footer" style="background: #2C2C2C; color: white; padding: 60px 0 30px;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 50px; margin-bottom: 40px;">
        <div>
          <div class="brand" style="font-size: 1.8rem; font-weight: 800; color: #D4A574; margin-bottom: 16px;">Coffee Express</div>
          <p style="color: rgba(255,255,255,0.7); max-width: 420px;">Kopi berkualitas, dibuat dengan sepenuh hati. Temani harimu dengan cita rasa yang konsisten dan suasana yang hangat.</p>
          <div style="display: flex; gap: 16px; margin-top: 20px;">
            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='#D4A574'; this.style.transform='translateY(-4px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'"><i data-feather="facebook"></i></a>
            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='#D4A574'; this.style.transform='translateY(-4px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'"><i data-feather="instagram"></i></a>
            <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='#D4A574'; this.style.transform='translateY(-4px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'"><i data-feather="twitter"></i></a>
          </div>
        </div>
        <div>
          <h4 style="color: #D4A574; margin-bottom: 20px; font-size: 1.2rem;">Quick Links</h4>
          <ul style="display: flex; flex-direction: column; gap: 12px;">
            <li><a href="{{ url('/') }}" style="color: rgba(255,255,255,0.8); transition: all 0.3s;" onmouseover="this.style.color='#D4A574'; this.style.paddingLeft='8px'" onmouseout="this.style.color='rgba(255,255,255,0.8)'; this.style.paddingLeft='0'">Home</a></li>
            <li><a href="{{ url('menu') }}" style="color: rgba(255,255,255,0.8); transition: all 0.3s;" onmouseover="this.style.color='#D4A574'; this.style.paddingLeft='8px'" onmouseout="this.style.color='rgba(255,255,255,0.8)'; this.style.paddingLeft='0'">Menu</a></li>
            <li><a href="{{ url('contact') }}" style="color: rgba(255,255,255,0.8); transition: all 0.3s;" onmouseover="this.style.color='#D4A574'; this.style.paddingLeft='8px'" onmouseout="this.style.color='rgba(255,255,255,0.8)'; this.style.paddingLeft='0'">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 style="color: #D4A574; margin-bottom: 20px; font-size: 1.2rem;">Contact</h4>
          <ul style="display: flex; flex-direction: column; gap: 12px; color: rgba(255,255,255,0.8);">
            <li>Jl. Coffee Street No. 123</li>
            <li>Jakarta, Indonesia</li>
            <li>Phone: (021) 1234-5678</li>
            <li>Email: info@coffeeexpress.com</li>
          </ul>
        </div>
      </div>
      <div style="text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6);">
        <p>&copy; 2024 Coffee Express. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <script>
    feather.replace();
  </script>
  <script defer src="{{ asset('js/cart.js') }}"></script>
</body>
</html>
