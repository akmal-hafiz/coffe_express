/**
 * GSAP Advanced Animations for Coffee Express
 * Premium micro-interactions and scroll-triggered animations
 */

// Initialize GSAP
gsap.registerPlugin(ScrollTrigger);

// Hero Section Animations
function initHeroAnimations() {
    const heroTimeline = gsap.timeline({
        defaults: { ease: "power3.out", duration: 1 }
    });

    // Stagger text animation with elastic effect
    heroTimeline
        .from(".hero-text-1, .hero-text-2, .hero-text-3", {
            y: 100,
            opacity: 0,
            stagger: 0.2,
            ease: "back.out(1.7)"
        })
        .from(".hero-subtitle", {
            y: 30,
            opacity: 0,
            duration: 0.8
        }, "-=0.4")
        .from(".hero-cta", {
            y: 30,
            opacity: 0,
            duration: 0.8
        }, "-=0.6")
        .from(".hero-stats", {
            y: 30,
            opacity: 0,
            duration: 0.8
        }, "-=0.6");

    // Coffee cup parallax with mouse movement
    const coffeeContainer = document.querySelector('.parallax-container');
    if (coffeeContainer) {
        document.addEventListener('mousemove', (e) => {
            const mouseX = e.clientX / window.innerWidth - 0.5;
            const mouseY = e.clientY / window.innerHeight - 0.5;

            gsap.to(coffeeContainer, {
                x: mouseX * 40,
                y: mouseY * 40,
                rotation: mouseX * 5,
                duration: 0.5,
                ease: "power2.out"
            });
        });
    }

    // Floating animation for coffee cup
    gsap.to('.coffee-float', {
        y: -20,
        duration: 3,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut"
    });
}

// Bento Grid Scroll Animations
function initBentoAnimations() {
    const bentoCards = document.querySelectorAll('.bento-card');

    bentoCards.forEach((card, index) => {
        // Set initial state to avoid FOUC
        gsap.set(card, { autoAlpha: 0 });

        gsap.fromTo(card,
            {
                autoAlpha: 0,
                y: 60,
                scale: 0.9,
                rotation: index % 2 === 0 ? -5 : 5
            },
            {
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    end: "top 60%",
                    toggleActions: "play none none reverse"
                },
                autoAlpha: 1,
                y: 0,
                scale: 1,
                rotation: 0,
                duration: 0.8,
                ease: "back.out(1.4)",
                delay: index * 0.1
            }
        );

        // Hover tilt effect
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                scale: 1.05,
                y: -10,
                boxShadow: "0 20px 40px rgba(44, 24, 16, 0.2)",
                duration: 0.3,
                ease: "power2.out"
            });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                scale: 1,
                y: 0,
                boxShadow: "0 10px 20px rgba(44, 24, 16, 0.1)",
                duration: 0.3,
                ease: "power2.out"
            });
        });
    });
}

// Menu Pills Scroll Animation
function initMenuPillsAnimations() {
    const pills = document.querySelectorAll('.category-pill');

    gsap.from(pills, {
        scrollTrigger: {
            trigger: '#category-pills',
            start: "top 80%"
        },
        x: -50,
        opacity: 0,
        stagger: 0.1,
        duration: 0.6,
        ease: "power3.out"
    });
}

// Glass Tile Cards Animation
function initGlassTileAnimations() {
    const tiles = document.querySelectorAll('.glass-tile');

    tiles.forEach((tile, index) => {
        gsap.from(tile, {
            scrollTrigger: {
                trigger: tile,
                start: "top 90%",
                toggleActions: "play none none reverse"
            },
            y: 40,
            opacity: 0,
            scale: 0.95,
            duration: 0.6,
            ease: "power2.out",
            delay: (index % 4) * 0.1 // Stagger by row
        });

        // Magnetic hover effect
        tile.addEventListener('mouseenter', function (e) {
            const img = this.querySelector('img');
            gsap.to(img, {
                scale: 1.1,
                duration: 0.6,
                ease: "power2.out"
            });
        });

        tile.addEventListener('mouseleave', function () {
            const img = this.querySelector('img');
            gsap.to(img, {
                scale: 1,
                duration: 0.6,
                ease: "power2.out"
            });
        });
    });
}

// Button Ripple Effect
function initButtonAnimations() {
    const buttons = document.querySelectorAll('button, .hero-btn, a[class*="btn"]');

    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple-effect');

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            gsap.fromTo(ripple,
                {
                    scale: 0,
                    opacity: 0.6
                },
                {
                    scale: 2,
                    opacity: 0,
                    duration: 0.6,
                    ease: "power2.out",
                    onComplete: () => ripple.remove()
                }
            );
        });
    });
}

// Scroll Progress Indicator
function initScrollProgress() {
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #D4A574, #C6A664);
    z-index: 9999;
    transform-origin: left;
  `;
    document.body.appendChild(progressBar);

    gsap.to(progressBar, {
        scaleX: 1,
        ease: "none",
        scrollTrigger: {
            start: "top top",
            end: "bottom bottom",
            scrub: 0.3
        }
    });
}

// Parallax Sections
function initParallaxSections() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');

    parallaxElements.forEach(element => {
        gsap.to(element, {
            y: -100,
            ease: "none",
            scrollTrigger: {
                trigger: element,
                start: "top bottom",
                end: "bottom top",
                scrub: 1
            }
        });
    });
}

// Confetti Animation for Add to Cart
function triggerConfetti(button) {
    const confettiCount = 30;
    const buttonRect = button.getBoundingClientRect();
    const centerX = buttonRect.left + buttonRect.width / 2;
    const centerY = buttonRect.top + buttonRect.height / 2;

    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.style.cssText = `
      position: fixed;
      width: 8px;
      height: 8px;
      background: ${['#D4A574', '#C6A664', '#2C1810', '#FFB6C1'][Math.floor(Math.random() * 4)]};
      border-radius: 50%;
      left: ${centerX}px;
      top: ${centerY}px;
      pointer-events: none;
      z-index: 9999;
    `;
        document.body.appendChild(confetti);

        const angle = (Math.PI * 2 * i) / confettiCount;
        const velocity = 100 + Math.random() * 100;

        gsap.to(confetti, {
            x: Math.cos(angle) * velocity,
            y: Math.sin(angle) * velocity - 100,
            opacity: 0,
            duration: 1 + Math.random(),
            ease: "power2.out",
            onComplete: () => confetti.remove()
        });
    }
}

// Initialize all animations when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Check if GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded. Please include GSAP library.');
        return;
    }

    initHeroAnimations();
    initBentoAnimations();
    initMenuPillsAnimations();
    initGlassTileAnimations();
    initButtonAnimations();
    initScrollProgress();
    initParallaxSections();

    // Add confetti to all "Add to Cart" buttons
    document.addEventListener('click', function (e) {
        if (e.target.closest('.add-to-cart') || e.target.closest('[onclick*="addToCart"]')) {
            triggerConfetti(e.target.closest('button') || e.target);
        }
    });

    console.log('🎨 GSAP Animations Initialized');
});

// Ripple effect CSS
const style = document.createElement('style');
style.textContent = `
  .ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    pointer-events: none;
  }
`;
document.head.appendChild(style);
