// Checkout page script: render cart summary, handle options, fulfillment toggle, payment selection, and modal
(function(){
  const STORAGE_KEY = 'ce_cart';
  const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });

  function loadCart(){ try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch { return []; } }

  function renderSummary(){
    const itemsWrap = document.getElementById('summary-items');
    const totalEl = document.getElementById('summary-total');
    const totalSideEl = document.getElementById('summary-total-side');
    const cart = loadCart();

    let total = 0;
    itemsWrap.innerHTML = '';

    if(cart.length === 0){
      itemsWrap.innerHTML = '<div class="text-center text-gray-500 py-6">Keranjang kosong. Silakan pilih menu dulu.</div>';
    } else {
      cart.forEach(item => {
        const line = document.createElement('div');
        line.className = 'flex items-center gap-3 py-3';
        const lineTotal = item.price * item.qty;
        total += lineTotal;
        line.innerHTML = `
          <img src="${item.image}" alt="${item.name}" class="h-12 w-12 rounded-lg object-cover bg-neutral-100"/>
          <div class="flex-1">
            <div class="font-medium">${item.name}</div>
            <div class="text-sm text-neutral-500">${item.qty} x ${currency.format(item.price)}</div>
          </div>
          <div class="font-semibold">${currency.format(lineTotal)}</div>
        `;
        itemsWrap.appendChild(line);
      });
    }

    totalEl.textContent = currency.format(total);
    totalSideEl.textContent = currency.format(total);
  }

  function bindFulfillment(){
    const radios = document.querySelectorAll('input[name="fulfillment"]');
    const wrap = document.getElementById('address-wrap');
    radios.forEach(r => {
      r.addEventListener('change', () => {
        if ((document.querySelector('input[name="fulfillment"]:checked')||{}).value === 'delivery'){
          wrap.classList.remove('opacity-0','pointer-events-none','h-0');
          wrap.classList.add('opacity-100');
          wrap.style.height = 'auto';
        } else {
          wrap.classList.add('opacity-0','pointer-events-none');
          wrap.classList.remove('opacity-100');
          wrap.style.height = '0px';
        }
      });
    });
  }

  function bindPayments(){
    const cards = Array.from(document.querySelectorAll('.pay-card'));
    let selected = null;
    cards.forEach(card => {
      card.addEventListener('click', ()=>{
        cards.forEach(c => c.classList.remove('ring-2','ring-brown'));
        card.classList.add('ring-2','ring-brown');
        selected = card.getAttribute('data-method');
      });
    });
    return () => selected;
  }

  function bindModal(){
    const modal = document.getElementById('success-modal');
    function open(){ modal.classList.remove('hidden'); }
    function close(){ modal.classList.add('hidden'); }
    modal.addEventListener('click', (e)=>{ const t = e.target; if (t instanceof HTMLElement && (t.dataset.close === '1')) close(); });
    document.getElementById('close-modal')?.addEventListener('click', close);
    return { open, close };
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    renderSummary();
    bindFulfillment();
    const getSelectedPayment = bindPayments();
    const modal = bindModal();

    document.getElementById('confirm-btn')?.addEventListener('click', ()=>{
      // Optionally validate: require payment method
      const pay = getSelectedPayment();
      if (!pay){
        alert('Pilih metode pembayaran terlebih dahulu.');
        return;
      }
      modal.open();
      // Optional: clear cart after success
      // localStorage.removeItem(STORAGE_KEY);
    });
  });
})();
