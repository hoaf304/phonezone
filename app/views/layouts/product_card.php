<?php
/**
 * app/views/layouts/product_card.php
 * Được include trong vòng lặp foreach, $sp là từng sản phẩm.
 *
 * @var array{
 *   id: int,
 *   ten: string,
 *   slug: string,
 *   hang_ten: string,
 *   hang_slug: string,
 *   hinh_chinh: string|null,
 *   mo_ta_ngan: string|null,
 *   gia_ban: int,
 *   gia_khuyen_mai: int|null,
 *   bien_the_id: int|null,
 *   pct_giam: int|null,
 *   avg_sao: float|null,
 *   so_danh_gia: int|null,
 *   ban_chay: int,
 *   created_at: string
 * } $sp
 */
$sp ??= [];

$giaBan     = (int)($sp['gia_ban']         ?? 0);
$giaKM      = (int)($sp['gia_khuyen_mai']  ?? 0);
$giaHienThi = $giaKM > 0 ? $giaKM : $giaBan;
$pctGiam    = isset($sp['pct_giam']) ? (int)$sp['pct_giam']
            : ($giaKM > 0 ? discount_percent($giaBan, $giaKM) : 0);
$avgSao     = (float)($sp['avg_sao']     ?? 0);
$soDanhGia  = (int)($sp['so_danh_gia']   ?? 0);
$isNew      = isset($sp['created_at']) && (time() - strtotime($sp['created_at'])) < 30 * 86400;
?>
<div class="product-card">

  <!-- Badges -->
  <div class="badge-container">
    <?php if ($pctGiam >= 5): ?>
      <span class="badge badge-sale">-<?= $pctGiam ?>%</span>
    <?php endif; ?>
    <?php if ($isNew && $pctGiam < 5): ?>
      <span class="badge badge-new">MỚI</span>
    <?php endif; ?>
    <?php if (!empty($sp['ban_chay'])): ?>
      <span class="badge badge-hot">🔥 HOT</span>
    <?php endif; ?>
  </div>

  <!-- Nút yêu thích -->
  <button class="wishlist-btn" data-id="<?= $sp['id'] ?>" title="Yêu thích">
    <i class="<?= Session::has('user_id') ? 'far' : 'far' ?> fa-heart"></i>
  </button>

  <!-- Ảnh sản phẩm -->
  <a href="<?= url('san-pham/' . e($sp['slug'])) ?>">
    <div class="product-image">
      <?php if (!empty($sp['hinh_chinh'])): ?>
        <img src="<?= img_url(e($sp['hinh_chinh'])) ?>"
             alt="<?= e($sp['ten']) ?>" loading="lazy">
      <?php else: ?>
        <i class="fas fa-mobile-alt no-img"></i>
      <?php endif; ?>
    </div>
  </a>

  <!-- Thông tin -->
  <div class="product-info">
    <div class="product-brand"><?= e($sp['hang_ten'] ?? '') ?></div>

    <a href="<?= url('san-pham/' . e($sp['slug'])) ?>">
      <div class="product-title"><?= e($sp['ten']) ?></div>
    </a>

    <?php if (!empty($sp['mo_ta_ngan'])): ?>
      <div class="product-specs"><?= e($sp['mo_ta_ngan']) ?></div>
    <?php endif; ?>

    <!-- Rating -->
    <?php if ($soDanhGia > 0): ?>
    <div class="rating-row">
      <?= render_stars($avgSao) ?>
      <span class="rating-count">(<?= $soDanhGia ?>)</span>
    </div>
    <?php endif; ?>

    <!-- Giá -->
    <div class="price-row">
      <span class="price-current"><?= format_price($giaHienThi) ?></span>
      <?php if ($giaKM > 0): ?>
        <span class="price-old"><?= format_price($giaBan) ?></span>
      <?php endif; ?>
    </div>

    <!-- Actions -->
    <div class="product-actions">
      <button class="btn-cart"
              data-id="<?= $sp['bien_the_id'] ?? 0 ?>"
              data-ten="<?= e($sp['ten']) ?>"
              onclick="addToCart(this)">
        <i class="fas fa-cart-plus"></i> Thêm giỏ
      </button>
      <button class="btn-compare" data-id="<?= $sp['id'] ?>"
              onclick="addToCompare(this)" title="So sánh">
        <i class="fas fa-exchange-alt"></i>
      </button>
    </div>
  </div>

</div>
