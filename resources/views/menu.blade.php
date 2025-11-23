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
    <title>Menu</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <script defer src="{{ asset('js/cursor.js') }}"></script>
    <script defer src="{{ asset('js/reveal.js') }}"></script>
    <script defer src="{{ asset('js/parallax.js') }}"></script>
    <script defer src="{{ asset('js/preloader.js') }}"></script>
    <script defer src="{{ asset('js/api.js') }}"></script>
  </head>
  <body class="page-fade-in bg-cream text-[#2C2C2C]">
  <div class="preloader" aria-hidden="true">
    <div>
      <div class="bean"></div>
      <div class="steam"></div>
    </div>
  </div>
  <div class="cursor" aria-hidden="true"></div>
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
              <span class="inline-block mt-2 px-2 py-1 bg-coffee/10 text-coffee text-xs font-semibold rounded-full">
                {{ Auth::user()->isAdmin() ? '👑 Admin' : '👤 User' }}
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
      <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5" style="display: none; align-items: center; justify-content: center;">0</span>
    </button>
  </div>
</nav>

<header class="hero-menu relative text-center" role="banner">
<div class="hero-content px-6" data-parallax>
<h1 class="text-4xl md:text-5xl font-extrabold drop-shadow">Our Premium Menu</h1>
<p class="mt-3 text-white/95">Discover carefully curated beverages and coffee crafted with passion.</p>
<div class="hero-buttons mt-5 flex flex-wrap justify-center gap-3">
<a href="#beverages" class="btn btn-secondary rounded-lg border border-white/80 text-white px-5 py-3 hover:bg-white hover:text-coffee">Beverages</a>
<a href="#coffee" class="btn btn-primary rounded-lg bg-coffee text-white px-5 py-3 shadow hover:bg-coffee/90">Coffee</a>
      </div>
    </div>
  </header>

  <main>
    <section id="beverages" class="container" aria-labelledby="beverages-title" style="padding:60px 0;">
      <header class="section-header reveal">
        <h2 id="beverages-title">Beverages</h2>
        <p>Refreshing selections with a premium twist.</p>
      </header>
      <div class="menu container mt-6">
        <!-- Original Hardcoded Menus -->
        <article class="menu-item reveal" data-name="Matcha Latte" data-price="28000" data-category="beverages">
          <figure class="item-media">
            <img src="{{ asset('images/matcha.webp') }}" alt="Matcha Latte">
            <figcaption class="item-overlay">
              <p>Creamy matcha with silky milk.</p>
              <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Matcha Latte</h3>
        </article>
        <article class="menu-item reveal" data-name="Lychee Tea" data-price="24000" data-category="beverages">
          <figure class="item-media">
            <img src="{{ asset('images/lychea.webp') }}" alt="Lychee Tea">
            <figcaption class="item-overlay">
              <p>Light, floral, and refreshing.</p>
              <a href="#" class="btn btn-primary add-to-cart">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Lychee Tea</h3>
        </article>
        <article class="menu-item reveal" data-name="Taro" data-price="26000" data-category="beverages">
          <figure class="item-media">
            <img src="{{ asset('images/matcha.webp') }}" alt="Taro">
            <figcaption class="item-overlay">
              <p>Sweet taro with creamy finish.</p>
              <a href="#" class="btn btn-primary add-to-cart">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Taro</h3>
        </article>
        <article class="menu-item reveal" data-name="Red Velvet" data-price="27000" data-category="beverages">
          <figure class="item-media">
            <img src="{{ asset('images/lychea.webp') }}" alt="Red Velvet">
            <figcaption class="item-overlay">
              <p>Decadent red velvet-inspired drink.</p>
              <a href="#" class="btn btn-primary add-to-cart">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Red Velvet</h3>
        </article>

        <!-- Dynamic Menus from Database -->
        @foreach($nonCoffeeMenus as $menu)
          <article class="menu-item reveal" data-name="{{ $menu->name }}" data-price="{{ $menu->price }}" data-category="beverages">
            <figure class="item-media">
              @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
              @else
                <img src="{{ asset('images/matcha.webp') }}" alt="{{ $menu->name }}">
              @endif
              <figcaption class="item-overlay">
                <p>{{ $menu->description ?: 'Delicious beverage' }}</p>
                <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
              </figcaption>
            </figure>
            <h3>{{ $menu->name }}</h3>
          </article>
        @endforeach
      </div>
    </section>

    <section id="coffee" class="container" aria-labelledby="coffee-title" style="padding:60px 0;">
      <header class="section-header reveal">
        <h2 id="coffee-title">Coffee</h2>
        <p>Classic favorites brewed to perfection.</p>
      </header>
      <div class="coffee container mt-6">
        <!-- Original Hardcoded Menus -->
        <article class="coffee-item reveal" data-name="Espresso" data-price="18000" data-category="coffee">
          <figure class="item-media">
            <img src="{{ asset('images/ekspreso-Photoroom.png') }}" alt="Espresso">
            <figcaption class="item-overlay">
              <p>Bold and intense single shot.</p>
              <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Espresso</h3>
        </article>
        <article class="coffee-item reveal" data-name="Cappuccino" data-price="22000" data-category="coffee">
          <figure class="item-media">
            <img src="{{ asset('images/cappucino.webp') }}" alt="Cappuccino">
            <figcaption class="item-overlay">
              <p>Espresso with silky foam.</p>
              <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Cappuccino</h3>
        </article>
        <article class="coffee-item reveal" data-name="Latte" data-price="23000" data-category="coffee">
          <figure class="item-media">
            <img src="{{ asset('images/cappucino.webp') }}" alt="Latte">
            <figcaption class="item-overlay">
              <p>Smooth espresso with steamed milk.</p>
              <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Latte</h3>
        </article>
        <article class="coffee-item reveal" data-name="Mocha" data-price="25000" data-category="coffee">
          <figure class="item-media">
            <img src="{{ asset('images/cappucino.webp') }}" alt="Mocha">
            <figcaption class="item-overlay">
              <p>Chocolatey espresso indulgence.</p>
              <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
            </figcaption>
          </figure>
          <h3>Mocha</h3>
        </article>

        <!-- Dynamic Menus from Database -->
        @foreach($coffeeMenus as $menu)
          <article class="coffee-item reveal" data-name="{{ $menu->name }}" data-price="{{ $menu->price }}" data-category="coffee">
            <figure class="item-media">
              @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
              @else
                <img src="{{ asset('images/cappucino.webp') }}" alt="{{ $menu->name }}">
              @endif
              <figcaption class="item-overlay">
                <p>{{ $menu->description ?: 'Premium coffee' }}</p>
                <a href="#" class="btn btn-primary add-to-cart rounded-md bg-coffee text-white px-3 py-2 hover:bg-coffee/90">Add to Cart</a>
              </figcaption>
            </figure>
            <h3>{{ $menu->name }}</h3>
          </article>
        @endforeach
      </div>
    </section>
  </main>

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
