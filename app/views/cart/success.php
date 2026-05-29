<?php
/**
 * @var array $order  Thông tin đơn hàng vừa đặt (gồm items[])
 */
$order ??= [];
$statusLabels = [
  'cho_xac_nhan' => ['Chờ xác nhận','#b45309','#fef3c7'],
  'da_xac_nhan'  => ['Đã xác nhận', '#065f46','#d1fae5'],
  'dang_dong_hang'=>['Đang đóng hàng','#1e40af','#dbeafe'],
  'dang_giao'    => ['Đang giao',   '#7c3aed','#ede9fe'],
  'da_giao'      => ['Đã giao',     '#065f46','#d1fae5'],
  'da_huy'       => ['Đã hủy',      '#991b1b','#fee2e2'],
];
$ptLabel = [
  'cod'          => 'Thanh toán khi nhận hàng',
  'chuyen_khoan' => 'Chuyển khoản ngân hàng',
  'momo'         => 'Ví MoMo',
  'vnpay'        => 'VNPay',
  'zalopay'      => 'ZaloPay',
];
$trackSteps = [
  ['cho_xac_nhan', 'fas fa-check',    'Đã đặt hàng'],
  ['da_xac_nhan',  'fas fa-clock',    'Chờ xác nhận'],
  ['dang_dong_hang','fas fa-box',     'Đang xử lý'],
  ['dang_giao',    'fas fa-truck',    'Đang giao hàng'],
  ['da_giao',      'fas fa-home',     'Đã giao'],
];
$statusOrder = array_column($trackSteps, 0);
$curIdx      = array_search($order['trang_thai'], $statusOrder) ?: 0;
?>

<div class="success-wrap">
  <!-- Hero -->
  <div class="success-hero">
    <div class="check-circle"><i class="fas fa-check"></i></div>
    <div class="success-tag">✓ Đặt hàng thành công</div>
    <h1 class="success-title">Cảm ơn bạn đã mua sắm!</h1>
    <p class="success-sub">
      Đơn hàng đã được ghi nhận và đang chờ xác nhận.<br>
      Chúng tôi sẽ liên hệ trong vòng 30 phút.
    </p>
    <div class="order-code-box">
      <div class="order-code-lbl">Mã đơn hàng của bạn</div>
      <div class="order-code-val"><?= e($order['ma_don']) ?></div>
    </div>
    <div class="email-note">
      <i class="fas fa-envelope" style="color:var(--accent)"></i>
      Email xác nhận đã gửi đến <strong><?= e(Session::get('user_email', $order['ten_nguoi_nhan'])) ?></strong>
    </div>
    <div class="success-btns">
      <a href="<?= url('don-hang/' . e($order['ma_don'])) ?>" class="btn btn-blue btn-lg">
        <i class="fas fa-box"></i> Xem chi tiết đơn hàng
      </a>
      <a href="<?= url('san-pham') ?>" class="btn btn-outline btn-lg">
        <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
      </a>
    </div>
  </div>

  <!-- Chi tiết đơn -->
  <div class="order-detail">
    <div class="detail-head">
      <h3>Thông tin đơn hàng #<?= e($order['ma_don']) ?></h3>
      <span style="font-size:.82rem;color:var(--gray-400)"><?= format_date($order['created_at']) ?></span>
    </div>

    <div class="detail-grid">
      <div class="detail-col">
        <div class="detail-label">Địa chỉ giao hàng</div>
        <div class="detail-val">
          <strong><?= e($order['ten_nguoi_nhan']) ?></strong><br>
          <?= e($order['so_dien_thoai']) ?><br>
          <?= e($order['so_nha_duong']) ?>, <?= e($order['phuong_xa']) ?>,<br>
          <?= e($order['quan_huyen']) ?>, <?= e($order['tinh_thanh']) ?>
        </div>
      </div>
      <div class="detail-col">
        <div class="detail-label">Thanh toán</div>
        <div class="detail-val" style="margin-bottom:.75rem">
          <strong><?= $ptLabel[$order['phuong_thuc_tt']] ?? $order['phuong_thuc_tt'] ?></strong>
        </div>
        <div class="detail-label">Trạng thái</div>
        <div class="detail-val">
          <?php [$lbl,$tc,$bg] = $statusLabels[$order['trang_thai']] ?? ['Chờ xác nhận','#b45309','#fef3c7']; ?>
          <span style="background:<?= $bg ?>;color:<?= $tc ?>;font-size:.8rem;font-weight:700;padding:.25rem .75rem;border-radius:20px">
            <?= $lbl ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Sản phẩm trong đơn -->
    <?php if (!empty($order['items'])): ?>
    <div style="padding:1.25rem 1.5rem;border-top:1px solid var(--gray-100)">
      <div class="detail-label" style="margin-bottom:.75rem">Sản phẩm</div>
      <?php foreach ($order['items'] as $item): ?>
      <div style="display:flex;align-items:center;gap:1rem;padding:.6rem 0;border-bottom:1px solid var(--gray-100)">
        <div style="width:52px;height:52px;border-radius:8px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
          <?php if (!empty($item['hinh_anh'])): ?>
            <img src="<?= img_url(e($item['hinh_anh'])) ?>" style="width:100%;height:100%;object-fit:contain;padding:.2rem">
          <?php else: ?>
            <i class="fas fa-mobile-alt" style="color:var(--gray-300)"></i>
          <?php endif; ?>
        </div>
        <div style="flex:1">
          <div style="font-size:.9rem;font-weight:600;color:var(--primary)"><?= e($item['ten_san_pham']) ?></div>
          <div style="font-size:.78rem;color:var(--gray-400)"><?= e($item['mau_sac']) ?> / <?= e($item['dung_luong']) ?> × <?= $item['so_luong'] ?></div>
        </div>
        <div style="font-size:.9rem;font-weight:700;color:var(--danger)"><?= format_price($item['thanh_tien']) ?></div>
      </div>
      <?php endforeach; ?>

      <!-- Tổng đơn -->
      <div style="padding-top:.75rem;display:flex;flex-direction:column;gap:.4rem;align-items:flex-end">
        <?php if ($order['giam_gia'] > 0): ?>
          <div style="font-size:.85rem;color:var(--success)">Giảm giá: −<?= format_price($order['giam_gia']) ?></div>
        <?php endif; ?>
        <?php if ($order['phi_van_chuyen'] > 0): ?>
          <div style="font-size:.85rem;color:var(--gray-500)">Phí ship: +<?= format_price($order['phi_van_chuyen']) ?></div>
        <?php endif; ?>
        <div style="font-size:1.1rem;font-weight:800;color:var(--danger)">Tổng: <?= format_price($order['tong_tien']) ?></div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Track steps -->
    <div class="status-track">
      <div class="track-label">Hành trình đơn hàng</div>
      <div class="track-steps">
        <?php foreach ($trackSteps as $idx => [$st, $icon, $name]): ?>
          <div class="track-step <?= $idx < $curIdx ? 'done' : ($idx == $curIdx ? 'active' : '') ?>">
            <div class="track-dot"><i class="<?= $icon ?>"></i></div>
            <span class="track-name"><?= $name ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- /.order-detail -->
</div>
