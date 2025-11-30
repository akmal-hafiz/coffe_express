{{-- Premium Hero Section - Awwwards Level Design --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-[#F5EBDD] via-[#FFF8E7] to-[#F5EBDD]" id="home">
  
  {{-- Animated Background Blobs --}}
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-20 -left-20 w-96 h-96 bg-gradient-to-br from-[#D4A574]/20 to-transparent rounded-full blur-3xl animate-blob"></div>
    <div class="absolute top-40 -right-20 w-96 h-96 bg-gradient-to-br from-[#2C1810]/10 to-transparent rounded-full blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-20 left-1/2 w-96 h-96 bg-gradient-to-br from-[#D4A574]/15 to-transparent rounded-full blur-3xl animate-blob animation-delay-4000"></div>
  </div>

  {{-- Main Content Container --}}
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="grid lg:grid-cols-2 gap-12 items-center min-h-screen py-20">
      
      {{-- Left Column - Typography & CTA --}}
      <div class="space-y-8 reveal-left">
        
        {{-- Main Heading --}}
        <div class="space-y-4">
          <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-bold text-[#2C1810] leading-[1.1] tracking-tight">
            <span class="block opacity-0 translate-y-8 hero-text-1">Crafted</span>
            <span class="block opacity-0 translate-y-8 hero-text-2">With <span class="bg-gradient-to-r from-[#D4A574] via-[#C6A664] to-[#D4A574] bg-clip-text text-transparent animate-gradient">Passion</span></span>
            <span class="block opacity-0 translate-y-8 hero-text-3 text-4xl sm:text-5xl lg:text-6xl mt-2">Served With Love</span>
          </h1>
        </div>

        {{-- Subtitle --}}
        <p class="text-lg sm:text-xl text-[#2C1810]/70 max-w-xl leading-relaxed opacity-0 hero-subtitle">
          Experience the perfect blend of artisanal coffee and modern ambiance. 
          Every cup tells a story of <span class="font-semibold text-[#D4A574]">quality</span> and <span class="font-semibold text-[#D4A574]">dedication</span>.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-wrap gap-4 opacity-0 hero-cta">
          <a href="{{ url('menu') }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-[#2C1810] to-[#3d2418] text-white rounded-[2rem] shadow-xl shadow-[#2C1810]/20 hover:shadow-2xl hover:shadow-[#2C1810]/30 transition-all duration-300 hover:scale-105 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-[#D4A574] to-[#C6A664] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <span class="relative font-semibold text-lg">Explore Menu</span>
            <svg class="relative w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>

          <a href="{{ url('contact') }}" class="group inline-flex items-center gap-3 px-8 py-4 bg-white/60 backdrop-blur-xl border-2 border-[#2C1810]/10 text-[#2C1810] rounded-[2rem] shadow-lg hover:shadow-xl hover:border-[#D4A574] transition-all duration-300 hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-semibold text-lg">Find Us</span>
          </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-3 gap-4 pt-8 opacity-0 hero-stats">
          <div class="group p-4 rounded-2xl bg-white/40 backdrop-blur-xl border border-white/60 hover:bg-white/60 transition-all duration-300 hover:scale-105">
            <div class="text-3xl font-bold text-[#2C1810] mb-1">15K+</div>
            <div class="text-sm text-[#2C1810]/60">Happy Customers</div>
          </div>
          <div class="group p-4 rounded-2xl bg-white/40 backdrop-blur-xl border border-white/60 hover:bg-white/60 transition-all duration-300 hover:scale-105">
            <div class="text-3xl font-bold text-[#2C1810] mb-1">4.9★</div>
            <div class="text-sm text-[#2C1810]/60">Average Rating</div>
          </div>
          <div class="group p-4 rounded-2xl bg-white/40 backdrop-blur-xl border border-white/60 hover:bg-white/60 transition-all duration-300 hover:scale-105">
            <div class="text-3xl font-bold text-[#2C1810] mb-1">50+</div>
            <div class="text-sm text-[#2C1810]/60">Coffee Variants</div>
          </div>
        </div>
      </div>

      {{-- Right Column - 3D Coffee Cup Visual --}}
      <div class="relative hidden lg:flex items-center justify-center h-full reveal-right">
        
        {{-- Main Coffee Cup Container with Parallax --}}
        <div class="relative w-full max-w-lg parallax-container">
          
          {{-- Glassmorphism Card Behind Cup --}}
          <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-white/10 backdrop-blur-2xl rounded-[3rem] border border-white/60 shadow-2xl shadow-[#D4A574]/20 transform rotate-6 scale-95"></div>
          
          {{-- Coffee Cup Image --}}
          <div class="relative z-10 p-12 coffee-float">
            <img src="{{ asset('images/cappucino.webp') }}" alt="Premium Coffee" class="w-full h-auto drop-shadow-2xl transform hover:scale-110 transition-transform duration-700 ease-out">
            
            {{-- Steam Animation --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-8 flex gap-2">
              <div class="w-1 h-12 bg-gradient-to-t from-[#D4A574]/40 to-transparent rounded-full animate-steam"></div>
              <div class="w-1 h-16 bg-gradient-to-t from-[#D4A574]/30 to-transparent rounded-full animate-steam animation-delay-1000"></div>
              <div class="w-1 h-14 bg-gradient-to-t from-[#D4A574]/35 to-transparent rounded-full animate-steam animation-delay-2000"></div>
            </div>
          </div>

          {{-- Floating Elements --}}
          <div class="absolute -top-8 -right-8 w-24 h-24 bg-gradient-to-br from-[#D4A574]/20 to-transparent rounded-full blur-xl animate-pulse"></div>
          <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-gradient-to-br from-[#2C1810]/10 to-transparent rounded-full blur-xl animate-pulse animation-delay-2000"></div>
          
          {{-- Coffee Bean Icons --}}
          <div class="absolute top-1/4 -left-12 text-4xl opacity-20 animate-bounce animation-delay-1000">☕</div>
          <div class="absolute bottom-1/4 -right-12 text-5xl opacity-15 animate-bounce animation-delay-3000">☕</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Scroll Indicator --}}
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 animate-bounce">
    <div class="flex flex-col items-center gap-2 text-[#2C1810]/60">
      <span class="text-sm font-medium tracking-wider uppercase">Scroll Down</span>
      <div class="w-6 h-10 rounded-full border-2 border-[#2C1810]/30 flex items-start justify-center p-2">
        <div class="w-1 h-3 bg-[#D4A574] rounded-full animate-scroll-indicator"></div>
      </div>
    </div>
  </div>
</section>

{{-- Custom Animations CSS --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

  .font-serif { font-family: 'Playfair Display', serif; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; }

  @keyframes blob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
  }

  @keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }

  @keyframes steam {
    0% { transform: translateY(0) scaleX(1); opacity: 0.6; }
    50% { transform: translateY(-20px) scaleX(1.2); opacity: 0.3; }
    100% { transform: translateY(-40px) scaleX(1.5); opacity: 0; }
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
  }

  @keyframes scroll-indicator {
    0% { transform: translateY(0); opacity: 1; }
    100% { transform: translateY(12px); opacity: 0; }
  }

  .animate-blob { animation: blob 7s infinite; }
  .animate-gradient { background-size: 200% 200%; animation: gradient 3s ease infinite; }
  .animate-steam { animation: steam 3s ease-in-out infinite; }
  .coffee-float { animation: float 6s ease-in-out infinite; }
  .animate-scroll-indicator { animation: scroll-indicator 1.5s ease-in-out infinite; }
  
  .animation-delay-1000 { animation-delay: 1s; }
  .animation-delay-2000 { animation-delay: 2s; }
  .animation-delay-3000 { animation-delay: 3s; }
  .animation-delay-4000 { animation-delay: 4s; }

  /* Reveal Animations */
  .reveal-left, .reveal-right { opacity: 0; }
  .reveal-left { transform: translateX(-50px); }
  .reveal-right { transform: translateX(50px); }
</style>

{{-- JavaScript for Scroll Animations --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Text Stagger Animation
    const heroTexts = document.querySelectorAll('.hero-text-1, .hero-text-2, .hero-text-3');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroCta = document.querySelector('.hero-cta');
    const heroStats = document.querySelector('.hero-stats');
    const revealLeft = document.querySelector('.reveal-left');
    const revealRight = document.querySelector('.reveal-right');

    setTimeout(() => {
      heroTexts.forEach((text, index) => {
        setTimeout(() => {
          text.style.opacity = '1';
          text.style.transform = 'translateY(0)';
          text.style.transition = 'all 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
        }, index * 200);
      });
    }, 300);

    setTimeout(() => {
      if (heroSubtitle) {
        heroSubtitle.style.opacity = '1';
        heroSubtitle.style.transition = 'all 0.8s ease-out';
      }
    }, 1000);

    setTimeout(() => {
      if (heroCta) {
        heroCta.style.opacity = '1';
        heroCta.style.transition = 'all 0.8s ease-out';
      }
    }, 1200);

    setTimeout(() => {
      if (heroStats) {
        heroStats.style.opacity = '1';
        heroStats.style.transition = 'all 0.8s ease-out';
      }
    }, 1400);

    // Reveal Animations
    setTimeout(() => {
      if (revealLeft) {
        revealLeft.style.opacity = '1';
        revealLeft.style.transform = 'translateX(0)';
        revealLeft.style.transition = 'all 1s cubic-bezier(0.16, 1, 0.3, 1)';
      }
      if (revealRight) {
        revealRight.style.opacity = '1';
        revealRight.style.transform = 'translateX(0)';
        revealRight.style.transition = 'all 1s cubic-bezier(0.16, 1, 0.3, 1)';
      }
    }, 100);

    // Parallax Effect for Coffee Cup
    const parallaxContainer = document.querySelector('.parallax-container');
    if (parallaxContainer) {
      document.addEventListener('mousemove', (e) => {
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        
        const moveX = (mouseX - 0.5) * 30;
        const moveY = (mouseY - 0.5) * 30;
        
        parallaxContainer.style.transform = `translate(${moveX}px, ${moveY}px)`;
        parallaxContainer.style.transition = 'transform 0.3s ease-out';
      });
    }
  });
</script>
