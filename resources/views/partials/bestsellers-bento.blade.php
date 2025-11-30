{{-- Premium Bestsellers Section - Bento Grid Layout --}}
<section class="relative py-24 overflow-hidden bg-gradient-to-b from-[#F5EBDD] to-white">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Section Header --}}
    <div class="text-center mb-16 reveal-up">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 backdrop-blur-xl border border-white/40 shadow-lg mb-4">
        <svg class="w-5 h-5 text-[#D4A574]" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <span class="text-sm font-semibold text-[#2C1810]">Bestsellers</span>
      </div>
      <h2 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-[#2C1810] mb-4">
        Customer <span class="bg-gradient-to-r from-[#D4A574] to-[#C6A664] bg-clip-text text-transparent">Favorites</span>
      </h2>
      <p class="text-lg text-[#2C1810]/70 max-w-2xl mx-auto">
        Discover our most loved coffee creations, handpicked by thousands of satisfied customers
      </p>
    </div>

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[280px]">
      
      {{-- Large Featured Card (Spans 2 columns & 2 rows) --}}
      <div class="lg:col-span-2 lg:row-span-2 group relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#2C1810] to-[#3d2418] shadow-2xl shadow-[#2C1810]/20 hover:shadow-3xl transition-all duration-500 bento-card reveal-scale">
        <div class="absolute inset-0 bg-gradient-to-br from-[#D4A574]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        
        {{-- Content --}}
        <div class="relative h-full p-8 flex flex-col justify-between">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 mb-4">
              <span class="text-xs font-bold text-white">🔥 MOST POPULAR</span>
            </div>
            <h3 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">
              Signature Cappuccino
            </h3>
            <p class="text-white/80 text-lg mb-6">
              Rich espresso topped with velvety microfoam and a perfect latte art
            </p>
            <div class="flex items-center gap-4 mb-6">
              <div class="flex items-center gap-1">
                <svg class="w-5 h-5 text-[#D4A574] fill-current" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-white font-bold">4.9</span>
              </div>
              <span class="text-white/60">•</span>
              <span class="text-white/80">8,234 sold</span>
            </div>
          </div>
          
          <div class="flex items-center justify-between">
            <div>
              <span class="text-white/60 text-sm block">Starting from</span>
              <span class="text-[#D4A574] text-3xl font-bold">Rp 35K</span>
            </div>
            <button class="group/btn px-6 py-3 bg-white text-[#2C1810] rounded-2xl font-semibold hover:bg-[#D4A574] hover:text-white transition-all duration-300 hover:scale-105 shadow-lg">
              Order Now
              <svg class="inline-block w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </button>
          </div>
        </div>

        {{-- Floating Coffee Image --}}
        <div class="absolute right-0 bottom-0 w-64 h-64 opacity-40 group-hover:opacity-50 transition-opacity duration-500 pointer-events-none">
          <img src="{{ asset('images/cappucino.webp') }}" alt="Cappuccino" class="w-full h-full object-contain transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700">
        </div>
      </div>

      {{-- Medium Card 1 - Espresso --}}
      <div class="lg:col-span-1 lg:row-span-1 group relative overflow-hidden rounded-[2rem] bg-white shadow-xl hover:shadow-2xl transition-all duration-500 bento-card reveal-scale">
        <div class="absolute inset-0 bg-gradient-to-br from-[#D4A574]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative h-full p-6 flex flex-col justify-between">
          <div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#2C1810] to-[#3d2418] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <span class="text-3xl">☕</span>
            </div>
            <h3 class="font-serif text-2xl font-bold text-[#2C1810] mb-2">Espresso</h3>
            <p class="text-[#2C1810]/60 text-sm">Bold & intense shot</p>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-[#D4A574] text-xl font-bold">Rp 25K</span>
            <button class="w-10 h-10 rounded-full bg-[#2C1810] text-white flex items-center justify-center hover:bg-[#D4A574] transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      {{-- Medium Card 2 - Matcha Latte --}}
      <div class="lg:col-span-1 lg:row-span-1 group relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#90C695] to-[#7AB87F] shadow-xl hover:shadow-2xl transition-all duration-500 bento-card reveal-scale">
        <div class="relative h-full p-6 flex flex-col justify-between">
          <div>
            <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/20 backdrop-blur-xl mb-3">
              <span class="text-xs font-bold text-white">🍵 NEW</span>
            </div>
            <h3 class="font-serif text-2xl font-bold text-white mb-2">Matcha Latte</h3>
            <p class="text-white/80 text-sm">Premium Japanese matcha</p>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-white text-xl font-bold">Rp 38K</span>
            <button class="w-10 h-10 rounded-full bg-white text-[#7AB87F] flex items-center justify-center hover:scale-110 transition-transform">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="absolute right-2 bottom-2 w-32 h-32 opacity-40 group-hover:opacity-50 transition-opacity pointer-events-none">
          <img src="{{ asset('images/matcha.webp') }}" alt="Matcha" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-700">
        </div>
      </div>

      {{-- Wide Card - Stats --}}
      <div class="lg:col-span-2 lg:row-span-1 group relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#D4A574] to-[#C6A664] shadow-xl hover:shadow-2xl transition-all duration-500 bento-card reveal-scale">
        <div class="relative h-full p-6 flex items-center justify-around">
          <div class="text-center">
            <div class="text-4xl font-bold text-white mb-1">15K+</div>
            <div class="text-white/80 text-sm">Orders This Month</div>
          </div>
          <div class="w-px h-16 bg-white/30"></div>
          <div class="text-center">
            <div class="text-4xl font-bold text-white mb-1">4.9★</div>
            <div class="text-white/80 text-sm">Customer Rating</div>
          </div>
          <div class="w-px h-16 bg-white/30"></div>
          <div class="text-center">
            <div class="text-4xl font-bold text-white mb-1">50+</div>
            <div class="text-white/80 text-sm">Menu Variants</div>
          </div>
        </div>
      </div>

      {{-- Tall Card - Lychee Tea --}}
      <div class="lg:col-span-1 lg:row-span-2 group relative overflow-hidden rounded-[2rem] bg-gradient-to-b from-[#FFB6C1] to-[#FFA0B4] shadow-xl hover:shadow-2xl transition-all duration-500 bento-card reveal-scale">
        <div class="relative h-full p-6 flex flex-col justify-between">
          <div>
            <h3 class="font-serif text-3xl font-bold text-white mb-3">Lychee Rose Tea</h3>
            <p class="text-white/90 text-sm mb-4">Refreshing floral notes with sweet lychee</p>
            <div class="flex items-center gap-2 mb-4">
              <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full bg-white border-2 border-[#FFB6C1] flex items-center justify-center text-xs">👤</div>
                <div class="w-8 h-8 rounded-full bg-white border-2 border-[#FFB6C1] flex items-center justify-center text-xs">👤</div>
                <div class="w-8 h-8 rounded-full bg-white border-2 border-[#FFB6C1] flex items-center justify-center text-xs">👤</div>
              </div>
              <span class="text-white/90 text-sm">+2.3K loved this</span>
            </div>
          </div>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-white text-2xl font-bold">Rp 32K</span>
              <button class="px-4 py-2 bg-white text-[#FFB6C1] rounded-xl font-semibold hover:scale-105 transition-transform">
                Add to Cart
              </button>
            </div>
          </div>
        </div>
        
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-40 h-40 opacity-40 group-hover:opacity-50 transition-opacity pointer-events-none">
          <img src="{{ asset('images/lychea.webp') }}" alt="Lychee Tea" class="w-full h-full object-contain transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700">
        </div>
      </div>

      {{-- Small Card - View All --}}
      <div class="lg:col-span-1 lg:row-span-1 group relative overflow-hidden rounded-[2rem] bg-white border-2 border-dashed border-[#2C1810]/20 hover:border-[#D4A574] shadow-lg hover:shadow-xl transition-all duration-500 bento-card reveal-scale cursor-pointer">
        <a href="{{ url('menu') }}" class="relative h-full p-6 flex flex-col items-center justify-center text-center">
          <div class="w-16 h-16 rounded-full bg-[#2C1810]/5 group-hover:bg-[#D4A574]/10 flex items-center justify-center mb-4 transition-colors">
            <svg class="w-8 h-8 text-[#2C1810] group-hover:text-[#D4A574] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </div>
          <h3 class="font-serif text-xl font-bold text-[#2C1810] mb-2">View All Menu</h3>
          <p class="text-[#2C1810]/60 text-sm">Explore 50+ variants</p>
        </a>
      </div>

    </div>
  </div>
</section>

{{-- Custom Animations handled by GSAP in public/js/gsap-animations.js --}}
<style>
  .bento-card {
    visibility: hidden; /* Prevent FOUC, GSAP will handle visibility */
  }
</style>
