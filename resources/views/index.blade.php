@extends('layouts.app')

@section('title', 'Coffee Express - Premium Coffee Experience')

@section('content')
    <!--hero-->
    @include('partials.hero-premium')

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

    <!-- Promo Section -->
    <section class="promo py-20 bg-cream/50">
      <div class="container mx-auto px-4">
        <header class="section-header reveal text-center mb-12">
          <h2 class="text-4xl md:text-5xl font-bold text-espresso mb-4">Promo di Coffee Express</h2>
          <p class="text-lg text-gray-600">Temukan berbagai promo menarik di sini!</p>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
           @forelse($promos as $promo)
           <div class="group rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 reveal cursor-pointer">
             <img src="{{ asset('images/' . $promo->image) }}" alt="{{ $promo->title }}" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500">
             @if($promo->description)
             <div class="p-4 bg-white">
               <p class="text-sm text-gray-600">{{ $promo->description }}</p>
             </div>
             @endif
           </div>
           @empty
           <div class="col-span-3 text-center py-12">
             <p class="text-gray-500">No promos available at the moment.</p>
           </div>
           @endforelse
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

    {{-- Premium Bestsellers Bento Grid --}}
    @include('partials.bestsellers-bento')

    <!-- News Section -->
    <section class="news py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 reveal">
          <h2 class="text-4xl md:text-5xl font-bold text-espresso">Coffee Express News</h2>
          <p class="text-lg text-gray-600 md:text-right mt-4 md:mt-0">Dapatkan berita terbaru dan informasi<br>menarik dari kami!</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          @forelse($news as $item)
          <article class="group cursor-pointer reveal">
            <div class="rounded-2xl overflow-hidden mb-4 aspect-[4/3]">
              <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
            </div>
            <h3 class="font-bold text-xl text-espresso mb-2 group-hover:text-caramel transition-colors leading-tight">{{ $item->title }}</h3>
            <p class="text-xs text-gray-400 mt-2">{{ $item->published_at ? $item->published_at->format('F d, Y') : '' }} - {{ $item->author }}</p>
          </article>
          @empty
          <div class="col-span-4 text-center py-12">
            <p class="text-gray-500">No news available at the moment.</p>
          </div>
          @endforelse
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
@endsection

@push('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
    
    <!-- 🔴 REALTIME: Pusher & Laravel Echo -->
    @auth
      @if($activeOrder)
      <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
      
      <script>
        // Initialize Feather Icons
        feather.replace();

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
      </script>
      @endif
    @endauth
@endpush
