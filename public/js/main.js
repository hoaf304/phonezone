// ============================================================
// PHONEZONE – main.js
// ============================================================

const BASE = document.querySelector('meta[name="base-url"]')?.content
           || window.location.origin + '/phonezone';

// ---- Thêm vào giỏ hàng (AJAX) ----
function addToCart(btn) {
  const bienTheId = btn.dataset.id;
  const ten       = btn.dataset.ten || 'Sản phẩm';
  if (!bienTheId || bienTheId == '0') {
    window.location.href = btn.closest('.product-card')
      ?.querySelector('a')?.href || BASE + '/gio-hang';
    return;
  }

  const orig = btn.innerHTML;
  btn.disabled  = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';

  fetch(BASE + '/gio-hang/them', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ bien_the_id: bienTheId, so_luong: 1, csrf_token: getCSRF() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      updateCartBadge(data.cart_count);
      showToast('✅ ' + ten + ' đã thêm vào giỏ!', 'success');
      btn.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
      setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 2000);
    } else {
      showToast('❌ ' + (data.message || 'Có lỗi xảy ra'), 'error');
      btn.innerHTML = orig; btn.disabled = false;
    }
  })
  .catch(() => {
    showToast('❌ Mất kết nối, thử lại!', 'error');
    btn.innerHTML = orig; btn.disabled = false;
  });
}

// ---- So sánh ----
function addToCompare(btn) {
  const id = btn.dataset.id;
  fetch(BASE + '/so-sanh/them', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ san_pham_id: id, csrf_token: getCSRF() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      showToast('✅ Đã thêm vào so sánh (' + data.count + '/3)', 'info');
      btn.style.background    = 'var(--accent-light)';
      btn.style.color         = 'var(--accent)';
      btn.style.borderColor   = 'var(--accent)';
    } else {
      showToast('⚠️ ' + (data.message || 'Chỉ so sánh tối đa 3 SP'), 'warning');
    }
  });
}

// ---- Yêu thích ----
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.wishlist-btn');
  if (!btn) return;
  const id = btn.dataset.id;
  const icon = btn.querySelector('i');

  fetch(BASE + '/yeu-thich/toggle', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ san_pham_id: id, csrf_token: getCSRF() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      if (data.liked) {
        icon.classList.replace('far','fas');
        icon.style.color = 'var(--danger)';
        showToast('❤️ Đã thêm vào yêu thích', 'success');
      } else {
        icon.classList.replace('fas','far');
        icon.style.color = '';
        showToast('💔 Đã xóa khỏi yêu thích', 'info');
      }
    } else if (data.redirect) {
      window.location.href = BASE + '/dang-nhap';
    }
  });
});

// ---- Update badge giỏ hàng ----
function updateCartBadge(count) {
  const badge = document.getElementById('cart-badge');
  if (!badge) return;
  badge.textContent    = count;
  badge.style.display  = count > 0 ? 'inline-block' : 'none';
  // Animation nhỏ
  badge.animate([{transform:'scale(1.5)'},{transform:'scale(1)'}], {duration:300});
}

// ---- Toast notification ----
function showToast(msg, type = 'success') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px';
    document.body.appendChild(container);
  }

  const colors = { success:'#10b981', error:'#ef4444', info:'#3b82f6', warning:'#f59e0b' };
  const toast = document.createElement('div');
  toast.style.cssText = `
    background:#1e293b; color:#fff; padding:12px 18px; border-radius:12px;
    box-shadow:0 8px 24px rgba(0,0,0,0.3); font-size:.9rem; font-weight:500;
    border-left:4px solid ${colors[type]||colors.success};
    animation: slideIn .3s ease; max-width:320px;
  `;
  toast.textContent = msg;

  if (!document.getElementById('toast-style')) {
    const st = document.createElement('style');
    st.id = 'toast-style';
    st.textContent = `@keyframes slideIn{from{transform:translateX(110%);opacity:0}to{transform:none;opacity:1}}
                      @keyframes slideOut{to{transform:translateX(110%);opacity:0}}`;
    document.head.appendChild(st);
  }

  container.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideOut .3s ease forwards';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ---- CSRF token ----
function getCSRF() {
  return document.querySelector('meta[name="csrf-token"]')?.content
      || document.querySelector('input[name="csrf_token"]')?.value
      || '';
}

// ---- Search: submit khi nhấn Enter ----
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.querySelector('.search-input');
  if (searchInput) {
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') this.closest('form').submit();
    });
  }

  // Smooth scroll cho anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
  });

  // Active wishlist buttons (nếu đã login + đã thích)
  const likedIds = window.__likedIds || [];
  document.querySelectorAll('.wishlist-btn').forEach(btn => {
    if (likedIds.includes(Number(btn.dataset.id))) {
      const icon = btn.querySelector('i');
      icon.classList.replace('far','fas');
      icon.style.color = 'var(--danger)';
    }
  });
});
