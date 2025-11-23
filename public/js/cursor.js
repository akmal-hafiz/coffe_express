// Interactive circular cursor and hover amplification for interactive elements
(function() {
  const cursorEl = document.querySelector('.cursor');
  if (!cursorEl) return;

  let mouseX = 0, mouseY = 0;
  let currentX = 0, currentY = 0;
  const lerp = (a, b, n) => (1 - n) * a + n * b;

  function onMouseMove(e) {
    mouseX = e.clientX;
    mouseY = e.clientY;
  }

  function animate() {
    currentX = lerp(currentX, mouseX, 0.2);
    currentY = lerp(currentY, mouseY, 0.2);
    cursorEl.style.transform = `translate(${currentX}px, ${currentY}px)`;
    requestAnimationFrame(animate);
  }

  // Enlarge cursor on interactive elements
  const interactiveSelectors = [
    'a', 'button', '.btn', '.menu-item', '.coffee-item', '.bevereges-item'
  ];
  function addHoverListeners() {
    const nodes = document.querySelectorAll(interactiveSelectors.join(','));
    nodes.forEach(el => {
      el.addEventListener('mouseenter', () => cursorEl.classList.add('cursor--active'));
      el.addEventListener('mouseleave', () => cursorEl.classList.remove('cursor--active'));
    });
  }

  window.addEventListener('mousemove', onMouseMove);
  window.addEventListener('DOMContentLoaded', () => {
    addHoverListeners();
    animate();
  });
})();


