// Lightweight parallax for hero backgrounds
(function() {
  const heroes = document.querySelectorAll('[data-parallax]');
  if (!heroes.length) return;

  let ticking = false;
  const speed = 0.25; // gentle effect

  function onScroll() {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        heroes.forEach(el => {
          const rect = el.getBoundingClientRect();
          const offset = rect.top; // distance from viewport top
          const translate = Math.round(offset * speed);
          el.style.transform = `translateY(${translate}px)`;
        });
        ticking = false;
      });
      ticking = true;
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('load', onScroll);
})();


