<?php
// app/views/product/detail.php
$giaHienThi   = $defaultVariant ? (int)($defaultVariant['gia_khuyen_mai'] ?: $defaultVariant['gia_ban']) : 0;
$giaBanGoc    = $defaultVariant ? (int)$defaultVariant['gia_ban'] : 0;
$pctGiam      = $giaBanGoc > 0 && $giaHienThi < $giaBanGoc
              ? discount_percent($giaBanGoc, $giaHienThi) : 0;
$tonKho       = $defaultVariant ? (int)$defaultVariant['ton_kho'] : 0;
$avgSao       = (float)($reviewStats['avg_sao'] ?? 0);
$tongDanhGia  = (int)($reviewStats['tong'] ?? 0);

// Group variants by màu / dung lượng
$mauSacs    = [];
$dungLuongs = [];
foreach ($variants as $v) {
    $mauSacs[$v['mau_sac']]       = $v['mau_sac'];
    $dungLuongs[$v['dung_luong']] = $v['dung_luong'];
}
$currentMau    = $defaultVariant['mau_sac']    ?? '';
$currentDung   = $defaultVariant['dung_luong'] ?? '';
?>

<!-- Breadcrumb -->
<div class="container">
  <div class="breadcrumb">
    <a href="<?= url() ?>">Trang chủ</a>
    <i class="fas fa-chevron-right"></i>
    <a href="<?= url('danh-muc/' . e($product['danh_muc_slug'])) ?>"><?= e($product['danh_muc_ten']) ?></a>
    <i class="fas fa-chevron-right"></i>
    <a href="<?= url('hang/' . e($product['hang_slug'])) ?>"><?= e($product['hang_ten']) ?></a>
    <i class="fas fa-chevron-right"></i>
    <span><?= e($product['ten']) ?></span>
  </div>
</div>

<!-- ===== DETAIL ===== -->
<div class="detail-wrap">

  <!-- GALLERY -->
  <div class="gallery">
    <div class="main-img" id="main-img-wrap">
      <?php if ($pctGiam >= 5): ?>
        <span class="img-badge">-<?= $pctGiam ?>%</span>
      <?php endif; ?>
      <?php if (!empty($images)): ?>
        <img src="<?= img_url(e($images[0]['url'])) ?>" alt="<?= e($product['ten']) ?>" id="main-img">
      <?php elseif (!empty($product['hinh_chinh'])): ?>
        <img src="<?= img_url(e($product['hinh_chinh'])) ?>" alt="<?= e($product['ten']) ?>" id="main-img">
      <?php else: ?>
        <i class="fas fa-mobile-alt" style="font-size:6rem;color:var(--gray-300)"></i>
      <?php endif; ?>
    </div>

    <!-- Thumbnails -->
    <?php if (!empty($images)): ?>
    <div class="thumb-list">
      <?php foreach ($images as $i => $img): ?>
        <div class="thumb <?= $i === 0 ? 'active' : '' ?>"
             onclick="switchImg('<?= img_url(e($img['url'])) ?>', this)">
          <img src="<?= img_url(e($img['url'])) ?>" alt="<?= e($product['ten']) ?>">
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- INFO -->
  <div class="product-info-panel">
    <div>
      <span class="brand-tag"><?= e($product['hang_ten']) ?></span>
    </div>
    <h1 class="product-title-main"><?= e($product['ten']) ?></h1>

    <!-- Rating -->
    <div class="rating-row-detail">
      <?= render_stars($avgSao) ?>
      <?php if ($tongDanhGia > 0): ?>
        <span class="rating-num"><?= $avgSao ?></span>
        <span style="color:var(--gray-300)">|</span>
        <a href="#tab-reviews" style="color:var(--gray-500);font-size:.85rem"><?= $tongDanhGia ?> đánh giá</a>
        <span style="color:var(--gray-300)">|</span>
      <?php endif; ?>
      <?php if ($tonKho > 0): ?>
        <span class="stock-ok"><i class="fas fa-check-circle"></i> Còn hàng (<?= $tonKho ?> chiếc)</span>
      <?php else: ?>
        <span class="stock-out"><i class="fas fa-times-circle"></i> Hết hàng</span>
      <?php endif; ?>
    </div>

    <!-- Giá -->
    <div class="price-block" id="price-block">
      <div>
        <span class="price-main" id="price-current"><?= format_price($giaHienThi) ?></span>
        <?php if ($pctGiam >= 5): ?>
          <span class="price-old-detail" id="price-old"><?= format_price($giaBanGoc) ?></span>
        <?php endif; ?>
      </div>
      <?php if ($pctGiam >= 5): ?>
        <div class="price-save" id="price-save">
          ✓ Tiết kiệm <?= format_price($giaBanGoc - $giaHienThi) ?> (<?= $pctGiam ?>%)
        </div>
      <?php endif; ?>
    </div>

    <!-- Chọn màu -->
    <?php if (count($mauSacs) > 1): ?>
    <div class="variant-section">
      <div class="variant-label">Màu sắc: <strong id="label-mau"><?= e($currentMau) ?></strong></div>
      <div class="color-list">
        <?php foreach ($mauSacs as $mau): ?>
          <div class="color-btn <?= $mau === $currentMau ? 'active' : '' ?>"
               data-mau="<?= e($mau) ?>" onclick="selectVariant('mau', this)">
            <?= e($mau) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Chọn dung lượng -->
    <?php if (count($dungLuongs) > 1): ?>
    <div class="variant-section">
      <div class="variant-label">Dung lượng: <strong id="label-dung"><?= e($currentDung) ?></strong></div>
      <div class="storage-list">
        <?php foreach ($dungLuongs as $dung): ?>
          <div class="storage-btn <?= $dung === $currentDung ? 'active' : '' ?>"
               data-dung="<?= e($dung) ?>" onclick="selectVariant('dung', this)">
            <?= e($dung) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Số lượng -->
    <div class="qty-row">
      <span class="qty-label">Số lượng:</span>
      <div class="qty-ctrl">
        <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
        <input type="number" class="qty-input" id="qty" value="1" min="1" max="<?= $tonKho ?>">
        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
      </div>
      <span style="font-size:.82rem;color:var(--gray-400)">Tối đa <?= $tonKho ?> sp</span>
    </div>

    <!-- Nút hành động -->
    <div class="action-btns">
      <?php if ($tonKho > 0): ?>
        <button class="btn-buy" onclick="buyNow()">
          <i class="fas fa-bolt"></i> Mua ngay
        </button>
        <button class="btn-cart-main" id="btn-add-cart" onclick="addToCartDetail()">
          <i class="fas fa-cart-plus"></i> Thêm vào giỏ
        </button>
      <?php else: ?>
        <button class="btn-cart-main" disabled style="opacity:.5;cursor:not-allowed;flex:2">
          <i class="fas fa-times"></i> Hết hàng
        </button>
      <?php endif; ?>
      <button class="btn-wish-main <?= '' ?>" id="btn-wish"
              data-id="<?= $product['id'] ?>" onclick="toggleWishDetail(this)">
        <i class="far fa-heart"></i>
      </button>
    </div>

    <!-- Chính sách -->
    <div class="policy-list">
      <div class="policy-item"><i class="fas fa-shield-alt"></i> Bảo hành chính hãng 12 tháng</div>
      <div class="policy-item"><i class="fas fa-truck"></i> Giao hàng toàn quốc 24h</div>
      <div class="policy-item"><i class="fas fa-sync-alt"></i> Đổi trả miễn phí 30 ngày</div>
      <div class="policy-item"><i class="fas fa-credit-card"></i> Trả góp 0% lãi suất</div>
    </div>

  </div><!-- /.product-info-panel -->
</div><!-- /.detail-wrap -->

<!-- ===== TABS ===== -->
<div class="tabs-wrap">
  <div class="tab-list">
    <div class="tab-btn active" onclick="switchTab('mo-ta',this)">Mô tả sản phẩm</div>
    <div class="tab-btn" onclick="switchTab('thong-so',this)">Thông số kỹ thuật</div>
    <div class="tab-btn" onclick="switchTab('danh-gia',this)" id="tab-reviews">
      Đánh giá (<?= $tongDanhGia ?>)
    </div>
  </div>

  <!-- Tab: Mô tả -->
  <div class="tab-pane active" id="pane-mo-ta">
    <?php if (!empty($product['mo_ta_chi_tiet'])): ?>
      <div style="line-height:1.8;color:var(--gray-700)"><?= $product['mo_ta_chi_tiet'] ?></div>
    <?php else: ?>
      <p style="color:var(--gray-500)"><?= e($product['mo_ta_ngan'] ?? 'Chưa có mô tả.') ?></p>
    <?php endif; ?>
  </div>

  <!-- Tab: Thông số -->
  <div class="tab-pane" id="pane-thong-so">
    <?php if (!empty($specs)): ?>
      <table class="specs-table">
        <?php foreach ($specs as $spec): ?>
          <tr>
            <td><?= e($spec['ten_thong_so']) ?></td>
            <td><?= e($spec['gia_tri']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p style="color:var(--gray-500)">Chưa có thông số kỹ thuật.</p>
    <?php endif; ?>
  </div>

  <!-- Tab: Đánh giá -->
  <div class="tab-pane" id="pane-danh-gia">
    <!-- Tổng quan -->
    <?php if ($tongDanhGia > 0): ?>
    <div class="review-summary">
      <div style="text-align:center">
        <div class="big-score"><?= $avgSao ?></div>
        <div class="score-stars"><?= str_repeat('★', round($avgSao)) . str_repeat('☆', 5 - round($avgSao)) ?></div>
        <div class="score-count"><?= $tongDanhGia ?> đánh giá</div>
      </div>
      <div class="bar-list">
        <?php foreach ([5,4,3,2,1] as $s):
          $cnt = (int)($reviewStats["sao$s"] ?? 0);
          $pct = $tongDanhGia > 0 ? round($cnt / $tongDanhGia * 100) : 0;
        ?>
        <div class="bar-row">
          <span class="bar-stars"><?= $s ?> ★</span>
          <div class="bar-track"><div class="bar-fill" style="width:<?= $pct ?>%"></div></div>
          <span class="bar-count"><?= $cnt ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Danh sách đánh giá -->
    <div class="review-list">
      <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $r): ?>
        <div class="review-item">
          <div class="review-header">
            <span class="reviewer-name"><?= e($r['ten_hien_thi'] ?? $r['ho_ten']) ?></span>
            <span class="review-stars"><?= str_repeat('★', (int)$r['so_sao']) ?></span>
            <span class="review-date"><?= format_date($r['created_at'], 'd/m/Y') ?></span>
          </div>
          <?php if (!empty($r['tieu_de'])): ?>
            <strong style="font-size:.9rem;color:var(--primary);display:block;margin-bottom:.25rem">
              <?= e($r['tieu_de']) ?>
            </strong>
          <?php endif; ?>
          <div class="review-text"><?= e($r['noi_dung']) ?></div>
          <?php if ($r['don_hang_id']): ?>
            <span class="review-tag">✓ Đã mua tại PhoneZone</span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color:var(--gray-500);padding:1rem 0">Chưa có đánh giá. Hãy là người đầu tiên!</p>
      <?php endif; ?>
    </div>

    <!-- Form đánh giá -->
    <?php if (Session::has('user_id')): ?>
    <div class="review-form">
      <h4>Viết đánh giá của bạn</h4>
      <form action="<?= url('danh-gia') ?>" method="POST">
        <?= Session::csrfField() ?>
        <input type="hidden" name="san_pham_id" value="<?= $product['id'] ?>">

        <div class="form-group">
          <label class="form-label">Số sao</label>
          <div class="star-picker" id="star-picker">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="far fa-star" data-val="<?= $i ?>" onclick="rateStar(<?= $i ?>)"></i>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="so_sao" id="input-sao" value="5">
        </div>
        <div class="form-group">
          <label class="form-label">Tiêu đề</label>
          <input type="text" name="tieu_de" class="form-input" placeholder="Tóm tắt đánh giá...">
        </div>
        <div class="form-group">
          <label class="form-label">Nội dung</label>
          <textarea name="noi_dung" class="form-textarea" rows="4"
                    placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
        </div>
        <button type="submit" class="btn btn-blue">
          <i class="fas fa-paper-plane"></i> Gửi đánh giá
        </button>
      </form>
    </div>
    <?php else: ?>
      <div style="background:var(--light);border-radius:12px;padding:1.25rem;margin-top:1rem;text-align:center">
        <a href="<?= url('dang-nhap') ?>" style="color:var(--accent);font-weight:600">Đăng nhập</a>
        để viết đánh giá
      </div>
    <?php endif; ?>
  </div><!-- /#pane-danh-gia -->
</div><!-- /.tabs-wrap -->

<!-- ===== SẢN PHẨM LIÊN QUAN ===== -->
<?php if (!empty($related)): ?>
<div class="related-section">
  <div class="section-header">
    <h2 class="section-title">Sản phẩm <span>liên quan</span></h2>
    <a href="<?= url('danh-muc/' . e($product['danh_muc_slug'])) ?>" class="link-more">
      Xem thêm <i class="fas fa-arrow-right"></i>
    </a>
  </div>
  <div class="product-grid">
    <?php foreach ($related as $sp): ?>
      <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- JSON biến thể (dùng cho JS) -->
<script>
const VARIANTS   = <?= json_encode($variants, JSON_UNESCAPED_UNICODE) ?>;
const BASE_URL   = '<?= APP_URL ?>';
let   selectedMau  = '<?= e($currentMau) ?>';
let   selectedDung = '<?= e($currentDung) ?>';
let   currentVariantId = <?= $defaultVariant['id'] ?? 0 ?>;

function getVariant(mau, dung) {
  return VARIANTS.find(v => v.mau_sac === mau && v.dung_luong === dung) || null;
}

function selectVariant(type, el) {
  // Update selection
  if (type === 'mau') {
    document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    selectedMau = el.dataset.mau;
    const lbl = document.getElementById('label-mau');
    if (lbl) lbl.textContent = selectedMau;
  } else {
    document.querySelectorAll('.storage-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    selectedDung = el.dataset.dung;
    const lbl = document.getElementById('label-dung');
    if (lbl) lbl.textContent = selectedDung;
  }

  const v = getVariant(selectedMau, selectedDung);
  if (!v) return;
  currentVariantId = v.id;

  // Update price
  const gia    = v.gia_khuyen_mai > 0 ? v.gia_khuyen_mai : v.gia_ban;
  const giaBan = v.gia_ban;
  const pct    = giaBan > 0 && gia < giaBan ? Math.round((giaBan - gia) / giaBan * 100) : 0;

  document.getElementById('price-current').textContent = formatVND(gia);
  const oldEl  = document.getElementById('price-old');
  const saveEl = document.getElementById('price-save');
  if (oldEl)  { oldEl.textContent  = pct >= 5 ? formatVND(giaBan) : ''; }
  if (saveEl) { saveEl.textContent = pct >= 5 ? `✓ Tiết kiệm ${formatVND(giaBan - gia)} (${pct}%)` : ''; }
}

function formatVND(n) {
  return new Intl.NumberFormat('vi-VN').format(n) + '₫';
}

function changeQty(d) {
  const inp = document.getElementById('qty');
  const max = parseInt(inp.max) || 99;
  inp.value = Math.min(max, Math.max(1, parseInt(inp.value || 1) + d));
}

function addToCartDetail() {
  if (!currentVariantId) { alert('Vui lòng chọn biến thể sản phẩm!'); return; }
  const qty = parseInt(document.getElementById('qty').value) || 1;
  const btn = document.getElementById('btn-add-cart');
  const orig = btn.innerHTML;
  btn.disabled  = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';

  fetch(BASE_URL + '/gio-hang/them', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ bien_the_id: currentVariantId, so_luong: qty, csrf_token: getCSRF() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      updateCartBadge(data.cart_count);
      showToast('✅ Đã thêm vào giỏ hàng!', 'success');
      btn.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
      setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 2000);
    } else {
      showToast('❌ ' + (data.message || 'Lỗi!'), 'error');
      btn.innerHTML = orig; btn.disabled = false;
    }
  });
}

function buyNow() {
  if (!currentVariantId) { alert('Vui lòng chọn biến thể!'); return; }
  const qty = parseInt(document.getElementById('qty').value) || 1;
  fetch(BASE_URL + '/gio-hang/them', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ bien_the_id: currentVariantId, so_luong: qty, csrf_token: getCSRF() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) window.location.href = BASE_URL + '/dat-hang';
    else showToast('❌ ' + (data.message || 'Lỗi!'), 'error');
  });
}

function toggleWishDetail(btn) {
  const id = btn.dataset.id;
  fetch(BASE_URL + '/yeu-thich/toggle', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ san_pham_id: id, csrf_token: getCSRF() })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      const icon = btn.querySelector('i');
      if (data.liked) { icon.classList.replace('far','fas'); btn.classList.add('active'); }
      else             { icon.classList.replace('fas','far'); btn.classList.remove('active'); }
    } else if (data.redirect) window.location.href = BASE_URL + '/dang-nhap';
  });
}

function switchImg(src, thumb) {
  document.getElementById('main-img').src = src;
  document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
}

function switchTab(id, el) {
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('pane-' + id).classList.add('active');
  el.classList.add('active');
}

function rateStar(val) {
  document.getElementById('input-sao').value = val;
  document.querySelectorAll('#star-picker i').forEach((el, i) => {
    el.classList.toggle('fas', i < val);
    el.classList.toggle('far', i >= val);
    el.classList.toggle('active', i < val);
    el.style.color = i < val ? '#fbbf24' : '';
  });
}
// Init stars at 5
rateStar(5);
</script>
