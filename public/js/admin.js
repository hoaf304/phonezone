// ============================================================
// PHONEZONE – admin.js
// ============================================================

// Sidebar toggle on mobile
document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('sidebar-toggle');
  if (toggleBtn) toggleBtn.style.display = 'flex';

  // Auto-close sidebar when clicking outside on mobile
  document.addEventListener('click', function (e) {
    const sidebar = document.getElementById('admin-sidebar');
    if (!sidebar) return;
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
      if (!sidebar.contains(e.target) && e.target !== document.getElementById('sidebar-toggle')) {
        sidebar.classList.remove('open');
      }
    }
  });

  // Confirm delete forms
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function (e) {
      if (!confirm(this.dataset.confirm || 'Bạn chắc chắn?')) e.preventDefault();
    });
  });
});

// CSRF helper (reuse from main.js)
function getCSRF() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

// Toast (admin version)
function showToast(msg, type = 'success') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px';
    document.body.appendChild(container);
  }
  const colors = { success: '#10b981', error: '#ef4444', info: '#3b82f6', warning: '#f59e0b' };
  const toast = document.createElement('div');
  toast.style.cssText = `background:#1e293b;color:#fff;padding:12px 18px;border-radius:10px;
    box-shadow:0 8px 24px rgba(0,0,0,.25);font-size:.88rem;font-weight:500;
    border-left:4px solid ${colors[type]};max-width:300px`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

// Quick status update via AJAX (used in order list)
function quickStatus(orderId, status) {
  if (!confirm('Cập nhật trạng thái đơn hàng?')) return;
  fetch(`${document.querySelector('meta[name="base-url"]').content}/admin/don-hang/${orderId}/trang-thai`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ trang_thai: status, ghi_chu: '', csrf_token: getCSRF() })
  }).then(r => r.json()).then(d => {
    if (d.success) { showToast('✅ Đã cập nhật!', 'success'); setTimeout(() => location.reload(), 800); }
    else showToast('❌ ' + (d.message || 'Lỗi!'), 'error');
  });
}
