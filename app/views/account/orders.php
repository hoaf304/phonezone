<?php
/**
 * app/views/account/orders.php
 * @var array  $user      Thông tin khách hàng
 * @var array  $orders    Danh sách đơn hàng
 * @var array  $pager     Phân trang
 * @var string $status    Trạng thái filter
 * @var string $activeTab Tab đang active
 */
$user ??= []; $orders ??= []; $pager ??= []; $status ??= '';
$statusLabel = ['cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_dong_hang'=>'Đang đóng hàng','dang_giao'=>'Đang giao','da_giao'=>'Đã giao','da_huy'=>'Đã hủy'];
$statusPill  = ['cho_xac_nhan'=>'pill-pending','da_xac_nhan'=>'pill-confirmed','dang_dong_hang'=>'pill-packing','dang_giao'=>'pill-shipping','da_giao'=>'pill-done','da_huy'=>'pill-canceled'];
?>

<div class="container"><div class="breadcrumb">
  <a href="<?= url() ?>">Trang chủ</a>
  <i class="fas fa-chevron-right"></i>
  <a href="<?= url('tai-khoan') ?>">Tài khoản</a>
  <i class="fas fa-chevron-right"></i>
  <span>Đơn hàng</span>
</div></div>

<div class="account-wrap">
  <?php include VIEW_PATH . 'account/_sidebar.php'; ?>

  <div>
    <div class="account-card">
      <div class="account-card-head">
        <h2><i class="fas fa-box"></i> Đơn hàng của tôi</h2>
        <span style="font-size:.82rem;color:var(--gray-500)">Tổng <strong><?= $pager['total'] ?></strong> đơn</span>
      </div>

      <!-- Status filter tabs -->
      <div style="padding:.75rem 1.5rem;border-bottom:1px solid var(--gray-100);display:flex;gap:.4rem;flex-wrap:wrap">
        <a href="<?= url('tai-khoan/don-hang') ?>"
           class="sort-btn <?= !$status ? 'active' : '' ?>">Tất cả</a>
        <?php foreach (['cho_xac_nhan','dang_giao','da_giao','da_huy'] as $st): ?>
          <a href="<?= url('tai-khoan/don-hang') ?>?status=<?= $st ?>"
             class="sort-btn <?= $status===$st?'active':'' ?>"><?= $statusLabel[$st] ?></a>
        <?php endforeach; ?>
      </div>

      <div class="account-card-body">
        <?php if (empty($orders)): ?>
          <div style="text-align:center;padding:3rem;color:var(--gray-400)">
            <i class="fas fa-box-open" style="font-size:3rem;margin-bottom:1rem;display:block"></i>
            <h3 style="margin-bottom:.5rem">Không có đơn hàng nào</h3>
            <p style="margin-bottom:1rem">Hãy mua sắm để có đơn hàng đầu tiên!</p>
            <a href="<?= url('san-pham') ?>" class="btn btn-blue"><i class="fas fa-shopping-bag"></i> Mua sắm ngay</a>
          </div>
        <?php else: ?>

          <?php foreach ($orders as $dh): ?>
          <div class="order-item">
            <!-- Header -->
            <div class="order-item-head">
              <a href="<?= url('don-hang/' . e($dh['ma_don'])) ?>" class="order-code-kh">
                <?= e($dh['ma_don']) ?>
              </a>
              <span class="order-date-kh">
                <i class="fas fa-calendar-alt"></i> <?= format_date($dh['created_at'],'d/m/Y H:i') ?>
              </span>
              <span class="status-pill <?= $statusPill[$dh['trang_thai']] ?? 'pill-pending' ?>">
                <?= $statusLabel[$dh['trang_thai']] ?? $dh['trang_thai'] ?>
              </span>
              <span class="order-total-kh"><?= format_price($dh['tong_tien']) ?></span>
            </div>

            <!-- Sản phẩm -->
            <div class="order-item-products">
              <?php foreach ($dh['items'] as $item): ?>
              <div class="order-product-row">
                <div class="order-product-img">
                  <?php if (!empty($item['hinh_anh'])): ?>
                    <img src="<?= img_url(e($item['hinh_anh'])) ?>" alt="">
                  <?php else: ?>
                    <i class="fas fa-mobile-alt" style="color:var(--gray-300);font-size:.9rem"></i>
                  <?php endif; ?>
                </div>
                <div style="flex:1">
                  <div class="order-product-name"><?= e($item['ten_san_pham']) ?></div>
                  <div class="order-product-variant"><?= e($item['mau_sac']) ?> / <?= e($item['dung_luong']) ?> × <?= $item['so_luong'] ?></div>
                </div>
                <div class="order-product-price"><?= format_price($item['thanh_tien']) ?></div>
              </div>
              <?php endforeach; ?>

              <?php if ($dh['tong_sp'] > 3): ?>
                <div style="font-size:.78rem;color:var(--gray-400);padding:.25rem 0">
                  +<?= $dh['tong_sp'] - 3 ?> sản phẩm khác...
                </div>
              <?php endif; ?>
            </div>

            <!-- Footer actions -->
            <div class="order-item-foot">
              <a href="<?= url('don-hang/' . e($dh['ma_don'])) ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> Xem chi tiết
              </a>
              <?php if ($dh['trang_thai'] === 'da_giao'): ?>
                <a href="<?= url('san-pham') ?>" class="btn btn-sm" style="background:var(--light);border:1px solid var(--gray-200)">
                  <i class="fas fa-redo"></i> Mua lại
                </a>
              <?php endif; ?>
              <?php if ($dh['trang_thai'] === 'cho_xac_nhan'): ?>
                <span style="font-size:.78rem;color:var(--gray-400)">
                  <i class="fas fa-info-circle"></i> Liên hệ <strong>1900 6789</strong> để hủy đơn
                </span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>

          <?= render_pagination($pager, url('tai-khoan/don-hang') . '?status=' . urlencode($status)) ?>

        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
