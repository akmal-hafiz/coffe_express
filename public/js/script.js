document.addEventListener('DOMContentLoaded', function() {
  
  if (window.feather) {
    feather.replace();
  }

  const heroSlider = document.querySelector('.hero .slider');
  const heroSlides = document.querySelectorAll('.hero .slide');
  const heroNavDots = document.querySelectorAll('.hero .nav-dot');
  const heroPrevBtn = document.querySelector('.hero .slider-arrow.prev');
  const heroNextBtn = document.querySelector('.hero .slider-arrow.next');
  
  let currentHeroSlide = 0;
  let heroAutoplayInterval;

  function showHeroSlide(index) {
    heroSlides.forEach(slide => slide.classList.remove('active'));
    heroNavDots.forEach(dot => dot.classList.remove('active'));
    
    if (index >= heroSlides.length) {
      currentHeroSlide = 0;
    } else if (index < 0) {
      currentHeroSlide = heroSlides.length - 1;
    } else {
      currentHeroSlide = index;
    }
    
    heroSlides[currentHeroSlide].classList.add('active');
    heroNavDots[currentHeroSlide].classList.add('active');
  }

  function nextHeroSlide() {
    showHeroSlide(currentHeroSlide + 1);
  }

  function prevHeroSlide() {
    showHeroSlide(currentHeroSlide - 1);
  }

  function startHeroAutoplay() {
    heroAutoplayInterval = setInterval(nextHeroSlide, 5000);
  }

  function stopHeroAutoplay() {
    clearInterval(heroAutoplayInterval);
  }

  if (heroNextBtn) {
    heroNextBtn.addEventListener('click', () => {
      nextHeroSlide();
      stopHeroAutoplay();
      startHeroAutoplay();
    });
  }

  if (heroPrevBtn) {
    heroPrevBtn.addEventListener('click', () => {
      prevHeroSlide();
      stopHeroAutoplay();
      startHeroAutoplay();
    });
  }

  heroNavDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      showHeroSlide(index);
      stopHeroAutoplay();
      startHeroAutoplay();
    });
  });

  startHeroAutoplay();

  const modeToggle = document.getElementById('mode-toggle');
  const donutsProps = document.getElementById('donuts-props');
  const coffeeProps = document.getElementById('coffee-props');

  if (modeToggle) {
    modeToggle.addEventListener('change', function() {
      if (this.checked) {
        donutsProps.style.display = 'none';
        coffeeProps.style.display = 'grid';
        setTimeout(() => { if (window.feather) feather.replace(); }, 100);
      } else {
        donutsProps.style.display = 'grid';
        coffeeProps.style.display = 'none';
        setTimeout(() => { if (window.feather) feather.replace(); }, 100);
      }
    });
  }

  const storySlides = document.querySelectorAll('.story-slide');
  const storyDots = document.querySelectorAll('.story-dot');
  const storyPrevBtn = document.querySelector('.story-arrow.prev');
  const storyNextBtn = document.querySelector('.story-arrow.next');
  
  let currentStorySlide = 0;

  function showStorySlide(index) {
    storySlides.forEach(slide => slide.classList.remove('active'));
    storyDots.forEach(dot => dot.classList.remove('active'));
    
    if (index >= storySlides.length) {
      currentStorySlide = 0;
    } else if (index < 0) {
      currentStorySlide = storySlides.length - 1;
    } else {
      currentStorySlide = index;
    }
    
    storySlides[currentStorySlide].classList.add('active');
    storyDots[currentStorySlide].classList.add('active');
    
    setTimeout(() => { if (window.feather) feather.replace(); }, 100);
  }

  if (storyNextBtn) {
    storyNextBtn.addEventListener('click', () => {
      showStorySlide(currentStorySlide + 1);
    });
  }

  if (storyPrevBtn) {
    storyPrevBtn.addEventListener('click', () => {
      showStorySlide(currentStorySlide - 1);
    });
  }

  storyDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      showStorySlide(index);
    });
  });

  const revealElements = document.querySelectorAll('.reveal');

  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  });

  revealElements.forEach(element => {
    revealObserver.observe(element);
  });

  let lastScrollTop = 0;
  const navbar = document.querySelector('.navbar');
  
  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > lastScrollTop && scrollTop > 100) {
      navbar.style.transform = 'translateY(-100%)';
    } else {
      navbar.style.transform = 'translateY(0)';
    }
    
    lastScrollTop = scrollTop;
  });

  navbar.style.transition = 'transform 0.3s ease';
});
