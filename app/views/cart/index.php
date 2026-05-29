<?php
/**
 * @var array $items   Sản phẩm trong giỏ
 * @var array $totals  Tổng tiền
 * @var array $coupon  Mã giảm giá đang áp dụng
 */
$items ??= []; $totals ??= []; $coupon ??= null;
?>

<!-- Stepper -->
<div class="stepper">
  <div class="stepper-inner">
    <div class="step active"><div class="step-circle">1</div><span class="step-label">Giỏ hàng</span></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">2</div><span class="step-label">Địa chỉ & Thanh toán</span></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">3</div><span class="step-label">Xác nhận</span></div>
  </div>
</div>

<div class="cart-layout">

  <!-- ===== DANH SÁCH SẢN PHẨM ===== -->
  <div>
    <div class="cart-card">
      <div class="cart-card-head">
        <h2>Giỏ hàng của bạn (<?= count($items) ?> sản phẩm)</h2>
        <?php if (!empty($items)): ?>
          <a href="<?= url('gio-hang/xoa-het') ?>" class="filter-reset"
             onclick="return confirm('Xóa toàn bộ giỏ hàng?')">Xóa tất cả</a>
        <?php endif; ?>
      </div>

      <?php if (!empty($items)): ?>
        <?php foreach ($items as $item):
          $gia    = (int)($item['gia_khuyen_mai'] > 0 ? $item['gia_khuyen_mai'] : $item['gia_ban']);
          $giaBan = (int)$item['gia_ban'];
          $qty    = (int)$item['so_luong'];
          $pctGiam = $giaBan > $gia ? round(($giaBan - $gia) / $giaBan * 100) : 0;
        ?>
        <div class="cart-row" id="item-<?= $item['bien_the_id'] ?>">

          <!-- Ảnh sản phẩm -->
          <div class="cart-img-wrap">
            <?php if ($pctGiam >= 5): ?>
              <span class="cart-badge-sale">-<?= $pctGiam ?>%</span>
            <?php endif; ?>
            <?php if (!empty($item['hinh_chinh'])): ?>
              <img src="<?= img_url(e($item['hinh_chinh'])) ?>"
                   alt="<?= e($item['ten_san_pham']) ?>">
            <?php else: ?>
              <i class="fas fa-mobile-alt" style="font-size:1.8rem;color:var(--gray-300)"></i>
            <?php endif; ?>
          </div>

          <!-- Thông tin -->
          <div class="cart-info">
            <div class="item-brand"><?= e($item['hang_ten']) ?></div>
            <a href="<?= url('san-pham/' . e($item['slug_san_pham'])) ?>" class="item-name">
              <?= e($item['ten_san_pham']) ?>
            </a>
            <div class="item-variant-tag"><?= e($item['mau_sac']) ?> / <?= e($item['dung_luong']) ?></div>
          </div>

          <!-- Giá đơn -->
          <div class="cart-price-col">
            <div class="item-price"><?= format_price($gia) ?></div>
            <?php if ($giaBan > $gia): ?>
              <div class="item-price-old"><?= format_price($giaBan) ?></div>
            <?php endif; ?>
          </div>

          <!-- Số lượng -->
          <div class="qty-ctrl-cart">
            <button class="qty-btn" onclick="updateQty(<?= $item['bien_the_id'] ?>, -1)">−</button>
            <span class="qty-num" id="qty-<?= $item['bien_the_id'] ?>"><?= $qty ?></span>
            <button class="qty-btn" onclick="updateQty(<?= $item['bien_the_id'] ?>, 1)">+</button>
          </div>

          <!-- Thành tiền -->
          <div class="cart-subtotal" id="sub-<?= $item['bien_the_id'] ?>">
            <?= format_price($gia * $qty) ?>
          </div>

          <!-- Xóa -->
          <button class="cart-del-btn" onclick="removeItem(<?= $item['bien_the_id'] ?>)" title="Xóa khỏi giỏ">
            <i class="fas fa-trash-alt"></i>
          </button>

        </div>
        <?php endforeach; ?>

      <?php else: ?>
        <div class="cart-empty">
          <i class="fas fa-shopping-cart"></i>
          <h3>Giỏ hàng trống</h3>
          <p>Thêm sản phẩm vào giỏ để tiến hành mua hàng</p>
          <a href="<?= url('san-pham') ?>" class="btn btn-blue">
            <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Tiếp tục mua sắm -->
    <?php if (!empty($items)): ?>
    <a href="<?= url('san-pham') ?>"
       style="display:inline-flex;align-items:center;gap:.5rem;margin-top:1rem;color:var(--gray-500);font-size:.85rem;font-weight:500">
      <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
    </a>
    <?php endif; ?>
  </div>

  <!-- ===== SUMMARY ===== -->
  <?php if (!empty($items)): ?>
  <div>
    <div class="summary-card">
      <div class="summary-title">Tóm tắt đơn hàng</div>

      <!-- Mã giảm giá -->
      <?php if (empty($coupon)): ?>
      <div style="margin-bottom:1rem">
        <div style="font-size:.8rem;font-weight:600;color:var(--gray-600);margin-bottom:.4rem">
          <i class="fas fa-ticket-alt" style="color:var(--accent)"></i> Mã giảm giá
        </div>
        <div class="coupon-row">
          <input type="text" class="coupon-input" id="coupon-input"
                 placeholder="Nhập mã giảm giá..." style="text-transform:uppercase">
          <button class="coupon-btn" onclick="applyCoupon()">Áp dụng</button>
        </div>
        <div class="coupon-success" id="coupon-success"></div>
        <div class="coupon-error"   id="coupon-error"></div>
      </div>
      <?php else: ?>
      <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:.65rem .9rem;font-size:.82rem;color:#065f46;font-weight:600;margin-bottom:1rem;display:flex;justify-content:space-between;align-items:center">
        <span><i class="fas fa-tag"></i> Mã <strong><?= e($coupon['ma']) ?></strong> đã áp dụng!</span>
        <a href="<?= url('gio-hang/xoa-ma') ?>" style="color:var(--danger);font-size:.8rem">✕ Bỏ mã</a>
      </div>
      <?php endif; ?>

      <!-- Chi tiết tổng -->
      <div class="summary-rows">
        <div class="summary-row">
          <span>Tạm tính</span>
          <span id="tam-tinh"><?= format_price($totals['tam_tinh']) ?></span>
        </div>
        <?php if ($totals['giam_sp'] > 0): ?>
        <div class="summary-row">
          <span>Giảm giá sản phẩm</span>
          <span class="discount" id="giam-sp">−<?= format_price($totals['giam_sp']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($totals['giam_ma']) && $totals['giam_ma'] > 0): ?>
        <div class="summary-row">
          <span>Mã giảm giá</span>
          <span class="discount" id="giam-ma">−<?= format_price($totals['giam_ma']) ?></span>
        </div>
        <?php endif; ?>
        <div class="summary-row">
          <span>Phí vận chuyển</span>
          <span id="phi-ship"><?= $totals['phi_ship'] > 0 ? format_price($totals['phi_ship']) : '<span style="color:var(--success);font-weight:600">Miễn phí</span>' ?></span>
        </div>
        <div class="summary-row total">
          <span>Tổng thanh toán</span>
          <span class="val" id="tong-tien"><?= format_price($totals['tong']) ?></span>
        </div>
      </div>

      <a href="<?= url('dat-hang') ?>" class="btn-checkout" style="text-decoration:none;display:flex">
        <i class="fas fa-lock"></i> Tiến hành đặt hàng
      </a>
    </div>
  </div>
  <?php endif; ?>

</div>

<!-- CSS bổ sung cho cart row mới -->
<style>
.cart-row {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.1rem 1.5rem;
  border-bottom: 1px solid var(--gray-100);
  transition: background .2s;
  flex-wrap: nowrap; /* KHÔNG wrap */
}
.cart-row:last-child { border-bottom: none; }
.cart-row:hover { background: var(--light); }

.cart-img-wrap {
  width: 80px; height: 80px;
  min-width: 80px; /* QUAN TRỌNG: không bị co lại */
  border-radius: 10px;
  border: 1px solid var(--gray-200);
  background: var(--gray-100);
  display: flex; align-items: center; justify-content: center;
  overflow: hidden; flex-shrink: 0;
  position: relative;
}
.cart-img-wrap img {
  width: 100%; height: 100%;
  object-fit: contain; padding: .4rem;
}
.cart-badge-sale {
  position: absolute; top: 4px; left: 4px;
  background: var(--danger); color: #fff;
  font-size: .62rem; font-weight: 700;
  padding: .15rem .4rem; border-radius: 5px; z-index: 1;
}

.cart-info {
  flex: 1;
  min-width: 0; /* cho phép text truncate */
}
.cart-info .item-brand { font-size: .72rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: .06em; margin-bottom: .2rem; }
.cart-info .item-name {
  display: block; font-size: .9rem; font-weight: 700;
  color: var(--primary); line-height: 1.4; margin-bottom: .3rem;
  /* Giới hạn 2 dòng */
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.cart-info .item-name:hover { color: var(--accent); }
.cart-info .item-variant-tag {
  display: inline-block; font-size: .75rem; color: var(--gray-500);
  background: var(--gray-100); padding: .15rem .55rem; border-radius: 6px;
}

.cart-price-col { text-align: right; min-width: 100px; flex-shrink: 0; }
.item-price     { font-size: .95rem; font-weight: 800; color: var(--danger); white-space: nowrap; }
.item-price-old { font-size: .75rem; color: var(--gray-400); text-decoration: line-through; white-space: nowrap; }

.cart-subtotal {
  font-size: .95rem; font-weight: 700; color: var(--primary);
  min-width: 100px; text-align: right; flex-shrink: 0; white-space: nowrap;
}

.cart-del-btn {
  width: 32px; height: 32px;
  border: 1px solid var(--gray-200); border-radius: 8px;
  background: #fff; color: var(--gray-400);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; font-size: .82rem; flex-shrink: 0;
  transition: all .2s;
}
.cart-del-btn:hover { background: #fee2e2; border-color: var(--danger); color: var(--danger); }

@media (max-width: 640px) {
  .cart-row { padding: .85rem 1rem; gap: .6rem; }
  .cart-img-wrap { width: 64px; height: 64px; min-width: 64px; }
  .cart-price-col, .cart-subtotal { min-width: 80px; font-size: .82rem; }
}
</style>

<script>
const BASE = '<?= APP_URL ?>';
function fmtVND(n) { return new Intl.NumberFormat('vi-VN').format(n) + '₫'; }

function updateQty(btId, delta) {
  const el  = document.getElementById('qty-' + btId);
  const qty = Math.max(1, parseInt(el.textContent) + delta);
  el.textContent = qty;
  fetch(BASE + '/gio-hang/cap-nhat', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({bien_the_id: btId, so_luong: qty, csrf_token: getCSRF()})
  })
  .then(r=>r.json())
  .then(data=>{
    if (data.success) { updateCartBadge(data.cart_count); refreshTotals(data.totals, data.giam_ma||0); }
  });
}

function removeItem(btId) {
  fetch(BASE + '/gio-hang/xoa/' + btId, {
    method:'GET', headers:{'X-Requested-With':'XMLHttpRequest'}
  })
  .then(r=>r.json())
  .then(data=>{
    if (data.success) {
      const row = document.getElementById('item-' + btId);
      if (row) { row.style.animation='fadeOut .3s ease forwards'; setTimeout(()=>row.remove(), 300); }
      updateCartBadge(data.cart_count);
      refreshTotals(data.totals, 0);
      if (data.cart_count === 0) setTimeout(()=>location.reload(), 350);
    }
  });
}

function applyCoupon() {
  const ma  = document.getElementById('coupon-input').value.trim().toUpperCase();
  if (!ma) return;
  const btn = document.querySelector('.coupon-btn');
  btn.textContent = '...'; btn.disabled = true;

  fetch(BASE + '/gio-hang/kiem-tra-ma', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({ma, csrf_token: getCSRF()})
  })
  .then(r=>r.json())
  .then(data=>{
    btn.textContent = 'Áp dụng'; btn.disabled = false;
    const ok  = document.getElementById('coupon-success');
    const err = document.getElementById('coupon-error');
    if (data.success) {
      ok.textContent = data.message;
      ok.classList.add('show');
      err.classList.remove('show');
      // Cập nhật tổng ngay
      if (data.tong) {
        if(document.getElementById('tong-tien')) document.getElementById('tong-tien').textContent = fmtVND(data.tong);
      }
      setTimeout(() => location.reload(), 1500);
    } else {
      err.textContent = data.message;
      err.classList.add('show');
      ok.classList.remove('show');
      // Highlight lỗi
      const inp = document.getElementById('coupon-input');
      inp.style.borderColor = 'var(--danger)';
      setTimeout(() => inp.style.borderColor = '', 2500);
    }
  })
  .catch(() => { btn.textContent = 'Áp dụng'; btn.disabled = false; });
}

function refreshTotals(t, giamMa) {
  const el = id => document.getElementById(id);
  if(el('tam-tinh'))  el('tam-tinh').textContent  = fmtVND(t.tam_tinh);
  if(el('giam-sp'))   el('giam-sp').textContent   = '−' + fmtVND(t.giam_sp);
  if(el('giam-ma') && giamMa > 0) el('giam-ma').textContent = '−' + fmtVND(giamMa);
  if(el('phi-ship'))  el('phi-ship').innerHTML    = t.phi_ship > 0 ? fmtVND(t.phi_ship) : '<span style="color:var(--success);font-weight:600">Miễn phí</span>';
  if(el('tong-tien')) el('tong-tien').textContent = fmtVND(t.tong);
}
</script>
<style>
@keyframes fadeOut { to { opacity:0; transform:scale(.97); } }
</style>
