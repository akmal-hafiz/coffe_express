// Simpl fade-in scroll
(function() {
  const elements = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window) || elements.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });

  elements.forEach(el => observer.observe(el));
})();


