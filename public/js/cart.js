// Simple Cart for Coffee Express
// - Persists to localStorage (key: ce_cart)
// - Attaches to any `.add-to-cart` inside product <article data-*>
// - Floating button + drawer UI with Tailwind

(function () {
  const STORAGE_KEY = 'ce_cart';
  const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });

  function loadCart() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch { return []; }
  }
  function saveCart(cart) { localStorage.setItem(STORAGE_KEY, JSON.stringify(cart)); }
  function findIndex(cart, id) { return cart.findIndex(i => i.id === id); }
  function slugify(s) { return (s || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''); }

  let cart = loadCart();

  function subtotal() { return cart.reduce((sum, i) => sum + i.price * i.qty, 0); }
  function count() { return cart.reduce((sum, i) => sum + i.qty, 0); }

  function renderBadge() {
    // Update navbar cart count (Desktop)
    const navCount = document.getElementById('cart-count');
    const bottomCount = document.getElementById('cart-count-bottom');
    const c = count();

    if (navCount) {
      navCount.textContent = c;
      navCount.style.display = c > 0 ? 'flex' : 'none';
    }

    if (bottomCount) {
      bottomCount.textContent = c;
      bottomCount.style.display = c > 0 ? 'flex' : 'none';
    }
  }

  function openDrawer() {
    const overlay = document.getElementById('ce-cart-overlay');
    const panel = document.getElementById('ce-cart-panel');
    if (!overlay || !panel) return;
    overlay.classList.remove('hidden');
    // allow paint, then slide in
    requestAnimationFrame(() => {
      panel.classList.remove('translate-x-full');
      panel.classList.add('translate-x-0');
    });
  }
  function closeDrawer() {
    const overlay = document.getElementById('ce-cart-overlay');
    const panel = document.getElementById('ce-cart-panel');
    if (!overlay || !panel) return;
    panel.classList.add('translate-x-full');
    panel.classList.remove('translate-x-0');
    // wait for transition then hide overlay
    setTimeout(() => overlay.classList.add('hidden'), 300);
  }

  function renderDrawer() {
    const list = document.getElementById('ce-cart-items');
    const total = document.getElementById('ce-cart-total');
    if (!list || !total) return;

    list.innerHTML = '';
    if (cart.length === 0) {
      list.innerHTML = '<div class="text-center text-gray-500 py-10">Keranjang masih kosong.</div>';
    } else {
      cart.forEach(item => {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-3 py-3';
        row.innerHTML = `
          <img src="${item.image}" alt="${item.name}" class="h-12 w-12 rounded-md object-cover bg-gray-100"/>
          <div class="flex-1">
            <div class="font-medium">${item.name}</div>
            <div class="text-sm text-gray-500">${currency.format(item.price)}</div>
          </div>
          <div class="flex items-center gap-2">
            <button data-act="dec" data-id="${item.id}" class="px-2 py-1 rounded border text-gray-600 hover:bg-gray-50">-</button>
            <span class="w-6 text-center">${item.qty}</span>
            <button data-act="inc" data-id="${item.id}" class="px-2 py-1 rounded border text-gray-600 hover:bg-gray-50">+</button>
          </div>
          <div class="w-24 text-right font-medium">${currency.format(item.price * item.qty)}</div>
          <button data-act="remove" data-id="${item.id}" class="ml-2 text-red-500 hover:text-red-600">Hapus</button>
        `;
        list.appendChild(row);
      });
    }
    total.textContent = currency.format(subtotal());
    renderBadge();
  }

  function addItem(name, price, image, category = 'general') {
    const id = slugify(name);
    const idx = findIndex(cart, id);
    if (idx >= 0) cart[idx].qty += 1; else cart.push({ id, name, price, image, category, qty: 1 });
    saveCart(cart);
    renderDrawer();
    openDrawer(); // Auto open drawer when adding item
  }

  // Expose to global scope
  window.addToCart = addItem;


  function updateQty(id, delta) {
    const idx = findIndex(cart, id);
    if (idx < 0) return;
    cart[idx].qty += delta;
    if (cart[idx].qty <= 0) cart.splice(idx, 1);
    saveCart(cart);
    renderDrawer();
  }

  function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart(cart);
    renderDrawer();
  }

  function mountUI() {
    if (document.getElementById('ce-cart-overlay')) return; // already mounted
    const wrap = document.createElement('div');
    wrap.innerHTML = `
      <div id=\"ce-cart-overlay\" class=\"fixed inset-0 z-50 hidden\">
        <div class=\"absolute inset-0 bg-black/40 dark:bg-black/50 transition-opacity\" data-close=\"1\"></div>
        <aside id=\"ce-cart-panel\" class=\"absolute right-0 top-0 h-full w-full max-w-md bg-white dark:bg-[#1e1b1a] text-neutral-900 dark:text-neutral-100 shadow-2xl flex flex-col rounded-l-2xl transform translate-x-full transition-all duration-300 ease-in-out\">
          <header class=\"flex items-center justify-between border-b border-neutral-200/70 dark:border-neutral-700 p-4\">
            <h3 class=\"text-lg font-semibold\">Keranjang</h3>
            <button class=\"text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white transition-colors\" data-close=\"1\">Tutup</button>
          </header>
          <div id=\"ce-cart-items\" class=\"flex-1 overflow-y-auto p-4 divide-y divide-neutral-100 dark:divide-neutral-800\"></div>
          <footer class=\"border-t border-neutral-200/70 dark:border-neutral-700 p-4 bg-cream/40 dark:bg-[#171312]\">
            <div class=\"flex items-center justify-between mb-3\">
              <span class=\"text-gray-600 dark:text-gray-300\">Subtotal</span>
              <span id=\"ce-cart-total\" class=\"text-lg font-bold\">Rp0</span>
            </div>
            <button id=\"ce-checkout-btn\" class=\"w-full rounded-2xl bg-[#6B4F4F] px-5 py-3 text-white font-semibold shadow hover:bg-[#5a403f] active:scale-[.99] transition-all\">Lanjut ke Checkout \u2192</button>
            <p class=\"mt-2 text-xs text-gray-500 dark:text-gray-400\">Arahkan ke halaman checkout untuk konfirmasi pesanan.</p>
          </footer>
        </aside>
      </div>
    `;
    document.body.appendChild(wrap);

    // Event listeners for cart drawer
    document.getElementById('ce-cart-overlay')?.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof HTMLElement)) return;
      if (t.dataset.close === '1') closeDrawer();
    });
    document.getElementById('ce-checkout-btn')?.addEventListener('click', () => {
      window.location.href = '/checkout';
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDrawer(); });
  }

  function bindAddToCart() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const article = btn.closest('article');
        if (!article) return;
        const name = article.getAttribute('data-name') || article.querySelector('h3')?.textContent?.trim();
        const price = Number(article.getAttribute('data-price') || '0');
        const category = article.getAttribute('data-category') || 'other';
        const imgEl = article.querySelector('img');
        const image = imgEl ? imgEl.getAttribute('src') : '';
        if (!name || !price) { return; }
        addItem({ name, price, image, category });
        openDrawer();
      });
    });
  }

  function bindDrawerActions() {
    document.getElementById('ce-cart-overlay')?.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof HTMLElement)) return;
      const act = t.getAttribute('data-act');
      const id = t.getAttribute('data-id');
      if (!act || !id) return;
      if (act === 'inc') updateQty(id, +1);
      if (act === 'dec') updateQty(id, -1);
      if (act === 'remove') removeItem(id);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    mountUI();
    bindAddToCart();
    bindDrawerActions();
    renderDrawer();

    // Bind navbar cart icon
    const cartIcon = document.getElementById('cart-icon');
    if (cartIcon) {
      cartIcon.addEventListener('click', openDrawer);
    }

    // Bind bottom nav cart icon
    const bottomCartIcon = document.getElementById('cart-icon-bottom');
    if (bottomCartIcon) {
      bottomCartIcon.addEventListener('click', openDrawer);
    }
  });
})();
