{{-- Premium Menu Page Content with Scrollable Pills & Glass Tiles --}}
<main class="min-h-screen bg-gradient-to-b from-[#F5EBDD] via-white to-[#F5EBDD] pt-24 pb-32">
  
  {{-- Hero Header --}}
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    <div class="text-center max-w-3xl mx-auto">
      <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl font-bold text-[#2C1810] mb-4">
        Our <span class="bg-gradient-to-r from-[#D4A574] to-[#C6A664] bg-clip-text text-transparent">Menu</span>
      </h1>
      <p class="text-lg text-[#2C1810]/70">
        Handcrafted beverages made with passion and premium ingredients
      </p>
    </div>
  </div>

  {{-- Scrollable Category Pills (Sticky) --}}
  {{-- Category Pills --}}
  <div class="mb-8 bg-[#F5EBDD] py-4 z-40 relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative">
        {{-- Pills Container --}}
        <div class="overflow-x-auto scrollbar-hide" id="category-pills">
          <div class="flex gap-3 px-4 py-2 min-w-max">
            <button onclick="filterMenu('all')" class="category-btn active flex items-center gap-2 px-6 py-3 rounded-full border transition-all duration-200 font-semibold text-sm whitespace-nowrap shadow-sm bg-[#2C1810] text-white border-[#2C1810]" data-category="all">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
              </svg>
              <span>All Menu</span>
              <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-white/20 text-white">{{ $coffeeMenus->count() + $nonCoffeeMenus->count() }}</span>
            </button>
            
            <button onclick="filterMenu('coffee')" class="category-btn flex items-center gap-2 px-6 py-3 rounded-full border transition-all duration-200 font-semibold text-sm whitespace-nowrap shadow-sm bg-white text-[#2C1810] border-[#E5E5E5] hover:bg-[#F5EBDD]" data-category="coffee">
              <span class="text-xl">☕</span>
              <span>Coffee</span>
              <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-[#F3F4F6] text-[#2C1810]">{{ $coffeeMenus->count() }}</span>
            </button>
            
            <button onclick="filterMenu('beverages')" class="category-btn flex items-center gap-2 px-6 py-3 rounded-full border transition-all duration-200 font-semibold text-sm whitespace-nowrap shadow-sm bg-white text-[#2C1810] border-[#E5E5E5] hover:bg-[#F5EBDD]" data-category="beverages">
              <span class="text-xl">🍵</span>
              <span>Beverages</span>
              <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-[#F3F4F6] text-[#2C1810]">{{ $nonCoffeeMenus->count() }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Menu Grid - Glass Tiles --}}
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="menu-grid">
      
      {{-- Coffee Items --}}
      @foreach($coffeeMenus as $menu)
      <div class="menu-card glass-tile" data-category="coffee" data-name="{{ strtolower($menu->name) }}">
        <div class="relative group">
          {{-- Image Container --}}
          <div class="relative aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-white/40 to-white/10 backdrop-blur-xl border border-white/60 mb-4">
            <img 
              src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/cappucino.webp') }}" 
              alt="{{ $menu->name }}"
              class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            >
            
            {{-- Floating Badge --}}
            <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-[#2C1810]/80 backdrop-blur-md text-white text-xs font-bold">
              ☕ Coffee
            </div>
            
            {{-- Hover Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#2C1810]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
              <button 
                onclick="addToCart('{{ $menu->name }}', {{ $menu->price }}, '{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/cappucino.webp') }}', 'coffee')"
                class="w-full py-3 bg-white text-[#2C1810] rounded-xl font-semibold hover:bg-[#D4A574] hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add to Cart
              </button>
            </div>
          </div>
          
          {{-- Info --}}
          <div class="space-y-2">
            <h3 class="font-serif text-xl font-bold text-[#2C1810] group-hover:text-[#D4A574] transition-colors">
              {{ $menu->name }}
            </h3>
            @if($menu->description)
            <p class="text-sm text-[#2C1810]/60 line-clamp-2">{{ $menu->description }}</p>
            @endif
            <div class="flex items-center justify-between pt-2">
              <span class="text-2xl font-bold text-[#D4A574]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
              <div class="flex items-center gap-1 text-sm text-[#2C1810]/60">
                <svg class="w-4 h-4 text-yellow-500 fill-current" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>4.8</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach

      {{-- Beverages Items --}}
      @foreach($nonCoffeeMenus as $menu)
      <div class="menu-card glass-tile" data-category="beverages" data-name="{{ strtolower($menu->name) }}">
        <div class="relative group">
          {{-- Image Container --}}
          <div class="relative aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-white/40 to-white/10 backdrop-blur-xl border border-white/60 mb-4">
            <img 
              src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/matcha.webp') }}" 
              alt="{{ $menu->name }}"
              class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            >
            
            {{-- Floating Badge --}}
            <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-gradient-to-r from-[#90C695] to-[#7AB87F] text-white text-xs font-bold">
              🍵 Beverage
            </div>
            
            {{-- Hover Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#2C1810]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
              <button 
                onclick="addToCart('{{ $menu->name }}', {{ $menu->price }}, '{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/matcha.webp') }}', 'beverages')"
                class="w-full py-3 bg-white text-[#2C1810] rounded-xl font-semibold hover:bg-[#D4A574] hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add to Cart
              </button>
            </div>
          </div>
          
          {{-- Info --}}
          <div class="space-y-2">
            <h3 class="font-serif text-xl font-bold text-[#2C1810] group-hover:text-[#D4A574] transition-colors">
              {{ $menu->name }}
            </h3>
            @if($menu->description)
            <p class="text-sm text-[#2C1810]/60 line-clamp-2">{{ $menu->description }}</p>
            @endif
            <div class="flex items-center justify-between pt-2">
              <span class="text-2xl font-bold text-[#D4A574]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
              <div class="flex items-center gap-1 text-sm text-[#2C1810]/60">
                <svg class="w-4 h-4 text-yellow-500 fill-current" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>4.7</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>

    {{-- Empty State --}}
    <div id="empty-state" class="hidden text-center py-20">
      <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-[#2C1810]/5 flex items-center justify-center">
        <svg class="w-12 h-12 text-[#2C1810]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <h3 class="font-serif text-2xl font-bold text-[#2C1810] mb-2">No items found</h3>
      <p class="text-[#2C1810]/60">Try selecting a different category</p>
    </div>
  </div>
</main>

{{-- Styles --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&display=swap');
  
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }



  .glass-tile {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .menu-card {
    transition: all 0.3s ease;
  }

  .menu-card.hidden {
    display: none;
  }
</style>

{{-- JavaScript for Filtering & Animations --}}
<script>
  function filterMenu(category) {
    const cards = document.querySelectorAll('.menu-card');
    const pills = document.querySelectorAll('.category-btn');
    const emptyState = document.getElementById('empty-state');
    let visibleCount = 0;

    // Update active pill
    const activeClasses = ['bg-[#2C1810]', 'text-white', 'border-[#2C1810]'];
    const inactiveClasses = ['bg-white', 'text-[#2C1810]', 'border-[#E5E5E5]', 'hover:bg-[#F5EBDD]'];

    pills.forEach(pill => {
      const badge = pill.querySelector('span:last-child');
      
      if (pill.dataset.category === category) {
        pill.classList.add(...activeClasses);
        pill.classList.remove(...inactiveClasses);
        // Update badge for active
        badge.classList.remove('bg-[#F3F4F6]', 'text-[#2C1810]');
        badge.classList.add('bg-white/20', 'text-white');
      } else {
        pill.classList.remove(...activeClasses);
        pill.classList.add(...inactiveClasses);
        // Update badge for inactive
        badge.classList.remove('bg-white/20', 'text-white');
        badge.classList.add('bg-[#F3F4F6]', 'text-[#2C1810]');
      }
    });

    // Filter cards with stagger animation
    cards.forEach((card, index) => {
      const cardCategory = card.dataset.category;
      
      if (category === 'all' || cardCategory === category) {
        card.classList.remove('hidden');
        card.style.animationDelay = `${index * 0.05}s`;
        visibleCount++;
      } else {
        card.classList.add('hidden');
      }
    });

    // Show/hide empty state
    if (visibleCount === 0) {
      emptyState.classList.remove('hidden');
    } else {
      emptyState.classList.add('hidden');
    }
  }

  // Smooth scroll for pills
  document.addEventListener('DOMContentLoaded', function() {
    const pillsContainer = document.getElementById('category-pills');
    let isDown = false;
    let startX;
    let scrollLeft;

    pillsContainer.addEventListener('mousedown', (e) => {
      isDown = true;
      startX = e.pageX - pillsContainer.offsetLeft;
      scrollLeft = pillsContainer.scrollLeft;
    });

    pillsContainer.addEventListener('mouseleave', () => {
      isDown = false;
    });

    pillsContainer.addEventListener('mouseup', () => {
      isDown = false;
    });

    pillsContainer.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - pillsContainer.offsetLeft;
      const walk = (x - startX) * 2;
      pillsContainer.scrollLeft = scrollLeft - walk;
    });
  });
</script>
