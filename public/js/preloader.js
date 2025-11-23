// Minimal preloader controller
(function() {
  const preloader = document.querySelector('.preloader');
  if (!preloader) return;

  function hide() {
    preloader.classList.add('hidden');
    setTimeout(() => preloader.parentNode && preloader.parentNode.removeChild(preloader), 600);
  }

  // Ensure minimum visible time ~1s
  const start = Date.now();
  window.addEventListener('load', () => {
    const elapsed = Date.now() - start;
    const remaining = Math.max(1000 - elapsed, 0);
    setTimeout(hide, remaining + 200); // 1.2s total feel
  });
})();


