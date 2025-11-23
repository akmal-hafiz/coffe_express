<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: { 
          extend: { 
            colors: { 
              espresso: '#2D1B12',
              caramel: '#C9A581',
              sage: '#8B9D83',
              cream: '#FAF8F5'
            } 
          } 
        }
      }
    </script>
    <link rel="stylesheet" href="{{ asset('css/modern-2025.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script defer src="{{ asset('js/api.js') }}"></script>

    <title>Coffee Express - Premium Coffee Experience</title>

    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
 
    <!--navbar-->

<nav class="modern-navbar">
  <div class="navbar-container">
    <a href="{{ url('/') }}" class="navbar-logo">
      <div class="navbar-logo-icon">
        <i data-feather="coffee" class="w-5 h-5"></i>
      </div>
      <span>Coffee Express</span>
    </a>

    <div class="flex items-center gap-6">
      <!-- Navigation Links -->
      <ul class="navbar-links">
        <li><a class="navbar-link" href="{{ url('/') }}#home">Home</a></li>
        <li><a class="navbar-link" href="{{ url('menu') }}">Menu</a></li>
        <li><a class="navbar-link" href="{{ url('contact') }}">Contact</a></li>

        @auth
          @if(Auth::user()->isAdmin())
            <li><a class="navbar-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          @else
            <li><a class="navbar-link" href="{{ route('order.status') }}">My Orders</a></li>
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
          
          <!-- Dropdown Menu -->
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
          <li><a class="navbar-link" href="{{ route('login') }}">Login</a></li>
          <li><a class="navbar-cta" href="{{ route('register') }}">Get Started</a></li>
        @endauth
      </ul>

      <!-- Cart Icon -->
      <button id="cart-icon" class="relative p-2 hover:bg-espresso/10 rounded-full transition-colors">
        <i data-feather="shopping-cart" class="w-6 h-6 text-espresso"></i>
        <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5" style="display: none; align-items: center; justify-content: center;">0</span>
      </button>
    </div>
  </div>
</nav>

    <!-- Order Notification Banner -->
    @auth
      @php
        $activeOrder = Auth::user()->orders()
          ->whereIn('status', ['pending', 'preparing', 'ready'])
          ->latest()
          ->first();
      @endphp
      
      @if($activeOrder)
        <div class="fixed bottom-6 right-6 z-[999] max-w-sm">
          <a href="{{ route('order.status') }}" class="block bg-gradient-to-r from-coffee to-brown text-white rounded-2xl shadow-2xl p-4 hover:shadow-3xl transition-all transform hover:scale-105">
            <div class="flex items-center gap-3">
              <div class="bg-white/20 p-2 rounded-full">
                @if($activeOrder->status === 'ready')
                  @if($activeOrder->pickup_option === 'pickup')
                    <i data-feather="check-circle" class="w-8 h-8"></i>
                  @else
                    <i data-feather="truck" class="w-8 h-8"></i>
                  @endif
                @else
                  <i data-feather="coffee" class="w-8 h-8"></i>
                @endif
              </div>
              <div class="flex-1">
                <p class="font-bold text-sm">
                  @if($activeOrder->status === 'pending')
                    Your order is being prepared
                  @elseif($activeOrder->status === 'preparing')
                    Your coffee is brewing...
                  @elseif($activeOrder->status === 'ready')
                    @if($activeOrder->pickup_option === 'pickup')
                      Your coffee is ready for pickup!
                    @else
                      Your coffee is on the way!
                    @endif
                  @endif
                </p>
                <p class="text-xs text-white/80">Click to view details</p>
              </div>
              <i data-feather="chevron-right" class="w-5 h-5"></i>
            </div>
          </a>
        </div>
      @endif
    @endauth


    <!--hero-->
<section class="modern-hero" id="home">
      <!-- Background Image -->
      <div class="hero-background">
        <img src="{{ asset('images/pink1.png') }}" alt="Coffee Background">
      </div>
      
      <!-- Gradient Overlay -->
      <div class="hero-overlay"></div>
      
      <!-- Floating Shapes -->
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
      
      <!-- Hero Content -->
      <div class="hero-content">
        <div class="hero-badge">
          <i data-feather="award" class="w-4 h-4"></i>
          <span>Premium Coffee Since 2020</span>
        </div>
        
        <h1 class="hero-title">
          Crafted With <span class="hero-title-gradient">Passion</span><br>
          Served With Love
        </h1>
        
        <p class="hero-subtitle">
          Experience the perfect blend of artisanal coffee and modern ambiance. 
          Every cup tells a story of quality and dedication.
        </p>
        
        <div class="hero-buttons">
          <a href="{{ url('menu') }}" class="hero-btn hero-btn-primary">
            <span>Explore Menu</span>
            <i data-feather="arrow-right" class="w-5 h-5"></i>
          </a>
          <a href="{{ url('contact') }}" class="hero-btn hero-btn-secondary">
            <i data-feather="map-pin" class="w-5 h-5"></i>
            <span>Find Us</span>
          </a>
        </div>
      </div>
      
      <!-- Scroll Indicator -->
      <div class="hero-scroll">
        <div class="hero-scroll-icon"></div>
      </div>
</section>


    <!-- Value Props -->
<section class="value-props bg-cream">
      <div class="container">
        <!-- Toggle Switch -->
        <div class="toggle-container">
          <div class="toggle-switch">
            <input type="checkbox" id="mode-toggle" class="toggle-input">
            <label for="mode-toggle" class="toggle-label">
              <span class="toggle-text toggle-donuts">Beverages</span>
              <span class="toggle-text toggle-coffee">Coffee</span>
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <!-- Donuts Value Props -->
        <div class="value-grid" id="donuts-props">
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="sun"></i>
            </div>
            <h3>Fresh Daily</h3>
            <p>Diproduksi setiap pagi, selalu hangat dan lembut.</p>
          </div>
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="star"></i>
            </div>
            <h3>Premium Quality</h3>
            <p>Bahan berkualitas tinggi untuk rasa yang sempurna.</p>
          </div>
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="shield"></i>
            </div>
            <h3>Honest Ingredients</h3>
            <p>Tanpa pengawet berlebihan, rasa yang jujur.</p>
          </div>
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="heart"></i>
            </div>
            <h3>Made with Love</h3>
            <p>Setiap donat dibuat dengan cinta dan perhatian.</p>
          </div>
        </div>

        <!-- Coffee Value Props -->
        <div class="value-grid" id="coffee-props" style="display: none;">
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="coffee"></i>
            </div>
            <h3>Premium Beans</h3>
            <p>Kopi dari biji pilihan, sangrai sempurna.</p>
          </div>
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="zap"></i>
            </div>
            <h3>Perfect Brew</h3>
            <p>Teknik brewing yang tepat untuk cita rasa optimal.</p>
          </div>
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="award"></i>
            </div>
            <h3>Expert Barista</h3>
            <p>Barista berpengalaman yang memahami kopi.</p>
          </div>
          <div class="value-item">
            <div class="value-icon">
              <i data-feather="smile"></i>
            </div>
            <h3>Cozy Atmosphere</h3>
            <p>Tempat untuk bertemu, bercerita, dan berbahagia.</p>
          </div>
        </div>
      </div>
    </section>


    <!--story section-->
    <section class="story" id="story">
      <div class="container">
        <div class="story-slider">
          <div class="story-slide active">
            <div class="story-media">
              <div class="image-stack">
                <img src="{{ asset('images/forourstory1.jpg') }}" alt="Our kitchen 1">
                <img src="{{ asset('images/forourstory2.jpg') }}" alt="Our kitchen 2">
                <img src="{{ asset('images/forourstory3.jpg') }}" alt="Our kitchen 3">
              </div>
            </div>
            <div class="story-text">
              <h2>Our Story</h2>
              <p>Kami memadukan kopi pilihan dan minuman segar dengan pastry berkualitas, menghadirkan pengalaman hangat dan profesional. Setiap sajian diracik rapi, proporsional, dan konsisten agar nikmat dipandang dan sempurna di rasa.</p>
            </div>
          </div>

          <div class="story-slide">
            <div class="story-media">
              <img src="{{ asset('images/benner2.png') }}" alt="Signature beverages">
            </div>
            <div class="story-text">
              <h2>Signature Beverages</h2>
              <p>Dari latte creamy hingga tea-based yang menyegarkan, menu beverages kami dirancang agar cocok dengan foto tanpa background—bersih, proporsional, dan rapi di setiap kartu menu.</p>
            </div>
          </div>

          <div class="story-slide">
            <div class="story-media">
              <img src="{{ asset('images/benner3.png') }}" alt="Crafted Coffee">
            </div>
            <div class="story-text">
              <h2>Crafted Coffee</h2>
              <p>Barista berpengalaman menyajikan kopi dengan detail: suhu, takaran, dan rasa yang konsisten. Temani harimu dengan suasana nyaman dan pelayanan ramah.</p>
            </div>
          </div>

          <div class="story-controls">
            <button class="story-arrow prev" aria-label="Previous">
              <i data-feather="chevron-left"></i>
            </button>
            <div class="story-dots">
              <button class="story-dot active" data-index="0"></button>
              <button class="story-dot" data-index="1"></button>
              <button class="story-dot" data-index="2"></button>
            </div>
            <button class="story-arrow next" aria-label="Next">
              <i data-feather="chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </section>
  
    <!-- Featured Menu -->
    <section class="featured">
      <div class="container">
        <header class="section-header reveal">
          <h2>Featured Menu</h2>
          <p class="subtitle">Pilihan terbaik kopi dan minuman kami minggu ini.</p>
        </header>
        <div class="grid">
          <article class="card reveal">
            <div class="card-media"><img src="{{ asset('images/ekspreso-Photoroom.png') }}" alt="Espresso"></div>
            <div class="card-body">
              <h3>Espresso</h3>
              <p>Shot kopi pekat dengan karakter kuat.</p>
            </div>
          </article>
          <article class="card reveal">
            <div class="card-media"><img src="{{ asset('images/cappucino.webp') }}" alt="Latte"></div>
            <div class="card-body">
              <h3>Latte</h3>
              <p>Espresso dan susu creamy yang seimbang.</p>
            </div>
          </article>
          <article class="card reveal">
            <div class="card-media"><img src="{{ asset('images/matcha.webp') }}" alt="Matcha Latte"></div>
            <div class="card-body">
              <h3>Matcha Latte</h3>
              <p>Matcha lembut dengan susu pilihan.</p>
            </div>
          </article>
          <article class="card reveal">
            <div class="card-media"><img src="{{ asset('images/lychea.webp') }}" alt="Lychee Tea"></div>
            <div class="card-body">
              <h3>Lychee Tea</h3>
              <p>Segar, floral, dan menyejukkan.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

   

    <!-- Pickup & Delivery Info -->
    <section class="fulfillment">
      <div class="container">
        <header class="section-header reveal">
          <h2>Pickup & Delivery</h2>
          <p class="subtitle">Pilih cara menikmati kopi: ambil sendiri atau antar ke lokasi.</p>
        </header>
        <div class="grid">
          <div class="panel reveal">
            <h3>Pickup</h3>
            <p>Datang langsung ke outlet terdekat. Dapatkan nomor antrian otomatis setelah memesan.</p>
            <p class="hint">Nomor antrian akan muncul di layar kasir dan dikirim via SMS.</p>
          </div>
          <div class="panel reveal">
            <h3>Delivery</h3>
            <p>Masukkan lokasi Anda dan kami akan sarankan cabang terdekat untuk pengantaran cepat.</p>
            <div class="form-row">
<input type="text" class="input rounded-md border border-neutral-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-coffee/30" placeholder="Masukkan alamat Anda" aria-label="Alamat">
<button class="btn btn-primary rounded-md bg-coffee text-white px-4 py-3 hover:bg-coffee/90">Cari Cabang</button>
            </div>
            <p class="hint">Kami menggunakan jarak terdekat untuk estimasi waktu.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
      <div class="container inner">
        <header class="section-header reveal">
          <h2>Join Our Coffee Community</h2>
          <p class="subtitle">Dapatkan update menu, promo, dan event terbaru.</p>
        </header>
        <div class="form">
<input type="email" class="input email rounded-md border border-neutral-200 px-4 py-3 w-full max-w-sm focus:outline-none focus:ring-2 focus:ring-coffee/30" placeholder="Email Anda" aria-label="Email">
<button class="btn btn-primary rounded-md bg-coffee text-white px-5 py-3 hover:bg-coffee/90">Subscribe</button>
        </div>
      </div>
    </section>

    <!-- Rich Testimonials with Avatars -->
    <section class="testimonials">
      <div class="container wrap">
        <header class="section-header reveal">
          <h2>What Customers Say</h2>
          <p class="subtitle">Cerita singkat dari pelanggan kami.</p>
        </header>
        <div class="testimonial-list">
          <div class="testimonial reveal">
            <div class="stars">★★★★★</div>
            <p class="quote">“Kopi terbaik! Barista ramah dan suasananya cozy untuk kerja.”</p>
            <div class="author"><img class="avatar" src="https://source.unsplash.com/80x80/?portrait,woman" alt="Rani"> Rani S.</div>
          </div>
          <div class="testimonial reveal">
            <div class="stars">★★★★★</div>
            <p class="quote">“Latte-nya creamy, aromanya mantap. Tempat favorit buat meeting.”</p>
            <div class="author"><img class="avatar" src="https://source.unsplash.com/80x80/?portrait,man" alt="Dimas"> Dimas P.</div>
          </div>
          <div class="testimonial reveal">
            <div class="stars">★★★★☆</div>
            <p class="quote">“Pengantaran cepat, kemasan rapi. Pasti repeat order lagi.”</p>
            <div class="author"><img class="avatar" src="https://source.unsplash.com/80x80/?portrait,asian" alt="Ayu"> Ayu K.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA: App Download / Nearest Shop -->
    <section class="cta" style="--cta-bg:url('https://source.unsplash.com/1600x900/?coffee,barista');">
      <div class="inner">
        <h2>Order Faster, Sip Better</h2>
        <p>Download aplikasi kami atau kunjungi gerai terdekat untuk menikmati kopi favoritmu tanpa antri.</p>
        <div class="actions">
          <a href="#" class="btn btn-light">Download App</a>
          <a href="{{ url('contact') }}" class="btn btn-outline">Find Nearest Shop</a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="container footer-grid">
        <div>
          <div class="brand">Coffee Express</div>
          <p class="subtitle" style="max-width:420px">Kopi berkualitas, dibuat dengan sepenuh hati. Temani harimu dengan cita rasa yang konsisten dan suasana yang hangat.</p>
        </div>
        <div>
          <h4>Quick Links</h4>
          <ul>
            <li><a href="{{ url('/') }}#home">Home</a></li>
            <li><a href="{{ url('menu') }}">Menu</a></li>
            <li><a href="{{ url('contact') }}">Contact</a></li>
            <li><a href="{{ url('coffe') }}">Location</a></li>
          </ul>
        </div>
        <div>
          <h4>Follow Us</h4>
          <div class="social">
            <a href="#" aria-label="Instagram">&#9733;</a>
            <a href="#" aria-label="Facebook">f</a>
            <a href="#" aria-label="Twitter">t</a>
          </div>
        </div>
      </div>
    </footer>

<!-- 🔴 REALTIME: Pusher & Laravel Echo -->
@auth
  @if($activeOrder)
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
  @endif
@endauth

<script>
  // Initialize Feather Icons
  feather.replace();

  @auth
    @if($activeOrder)
    // 🔴 REALTIME: Initialize Laravel Echo for homepage notifications
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '{{ env('PUSHER_APP_KEY') }}',
      cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
      forceTLS: true,
      encrypted: true,
      authEndpoint: '/broadcasting/auth',
      auth: {
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }
    });

    // 🔴 REALTIME: Listen to active order updates
    window.Echo.private('order.{{ $activeOrder->id }}')
      .listen('.order.updated', (event) => {
        console.log('🔴 Homepage: Order Update Received:', event);
        
        // Show toast notification
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: event.status === 'ready' ? 'success' : 'info',
          title: event.message,
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        });

        // Update notification banner text with animation
        updateNotificationBanner(event);
      });

    // Update notification banner content
    function updateNotificationBanner(event) {
      const banner = document.querySelector('.fixed.bottom-6.right-6 a');
      if (banner) {
        // Fade out
        banner.style.opacity = '0';
        banner.style.transition = 'opacity 0.3s ease';
        
        setTimeout(() => {
          // Update content
          const messageEl = banner.querySelector('p.font-bold');
          if (messageEl) {
            messageEl.textContent = event.message;
          }
          
          // Update icon based on status
          const iconContainer = banner.querySelector('.bg-white\\/20');
          if (iconContainer) {
            let iconName = 'coffee';
            if (event.status === 'ready') {
              iconName = event.pickup_option === 'pickup' ? 'check-circle' : 'truck';
            }
            iconContainer.innerHTML = `<i data-feather="${iconName}" class="w-8 h-8"></i>`;
            feather.replace();
          }
          
          // Fade in
          banner.style.opacity = '1';
        }, 300);
      }
    }

    console.log('🔴 REALTIME: Homepage listening for updates on order.{{ $activeOrder->id }}');
    @endif
  @endauth
</script>
<script src="{{ asset('js/script.js') }}"></script>
<script defer src="{{ asset('js/cart.js') }}"></script>
</body>
</html>
