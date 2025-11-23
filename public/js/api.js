// Simple API client for Coffee Express frontend
(function(){
  function apiBase() {
    if (window.API_BASE) return window.API_BASE; // allow manual override
    // Detect project subfolder, e.g. /coffe_express
    const parts = window.location.pathname.split('/').filter(Boolean);
    const root = parts.length ? '/' + parts[0] : '';
    // Our Laravel public is under /<root>/public
    return root + '/public/api';
  }

  const API = {
    async get(path){ const r = await fetch(apiBase() + (path.startsWith('/')? path : '/' + path), { credentials:'same-origin' }); return r.json(); },
    async post(path, body){ const r = await fetch(apiBase() + (path.startsWith('/')? path : '/' + path), { method:'POST', headers:{'Content-Type':'application/json'}, credentials:'same-origin', body: JSON.stringify(body||{}) }); return r.json(); },
    async del(path, body){ const r = await fetch(apiBase() + (path.startsWith('/')? path : '/' + path), { method:'DELETE', headers:{'Content-Type':'application/json'}, credentials:'same-origin', body: JSON.stringify(body||{}) }); return r.json(); },
  };

  let PRICE_MAP = {
    'Espresso': 20000,
    'Latte': 25000,
    'Cappuccino': 25000,
    'Mocha': 28000,
    'Matcha Latte': 25000,
    'Lychee Tea': 22000,
    'Taro': 22000,
    'Red Velvet': 23000
  };

  async function refreshBadge(){
    try {
      const data = await API.get('/cart/count');
      const el = document.getElementById('ce-cart-count');
      if (el) el.textContent = data.count ?? 0;
    } catch {}
  }



  async function hydratePrices(){
    try{
      const data = await API.get('/products');
      if (data && Array.isArray(data.items)){
        // Build map by name
        for (const p of data.items){
          if (p && p.name) PRICE_MAP[p.name] = Number(p.price||0);
        }
      }
    }catch(e){}
  }


  // User menu injection
  async function injectUserMenu(){
    const navbar = document.querySelector('nav.navbar .nav ul');
    if (!navbar) return;
    const li = document.createElement('li');
    li.innerHTML = `<a href="#" id="ce-user-menu">👤</a>`;
    navbar.appendChild(li);
    let loggedIn = false; let userName='';
    try{
      const res = await fetch((window.API_BASE||'/api') + '/me', { credentials:'same-origin' });
      if (res.ok){ const u = await res.json(); loggedIn = true; userName = u.name || u.email; }
    }catch(e){}
    const menu = document.createElement('div');
    menu.id='ce-user-dropdown';
    menu.style.cssText='position:absolute;right:16px;top:48px;background:#fff;border:1px solid #eee;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.12);padding:8px;display:none;z-index:9999';
    menu.innerHTML = loggedIn
      ? `<div style="padding:6px 8px;font-weight:600">${userName}</div><a href="/dashboard" style="display:block;padding:6px 8px;text-decoration:none;color:#111">Dashboard</a>`
      : `<a href="/login" style="display:block;padding:6px 8px;text-decoration:none;color:#111">Login</a><a href="/register" style="display:block;padding:6px 8px;text-decoration:none;color:#111">Register</a>`;
    document.body.appendChild(menu);
    document.getElementById('ce-user-menu').addEventListener('click', (e)=>{ e.preventDefault(); menu.style.display = (menu.style.display==='block'?'none':'block'); });
    document.addEventListener('click', (e)=>{ if (!e.target.closest('#ce-user-menu') && !e.target.closest('#ce-user-dropdown')) menu.style.display='none'; });
  }

  document.addEventListener('DOMContentLoaded', async ()=>{
    injectUserMenu();
  });
})();
