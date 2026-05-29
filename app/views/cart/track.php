<?php
/**
 * app/views/cart/track.php
 * Biến được truyền vào từ Controller::render() qua extract()
 *
 * @var array  $order   Thông tin đơn hàng (gồm items[], history[])
 * @var string $title   Tiêu đề trang
 */

// Đảm bảo $order luôn có giá trị mặc định (tránh lỗi nếu view bị include trực tiếp)
$order ??= [];

$statusLabel = ['cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_dong_hang'=>'Đang đóng hàng','dang_giao'=>'Đang giao hàng','da_giao'=>'Đã giao thành công','da_huy'=>'Đã hủy'];
$statusClass = ['cho_xac_nhan'=>'s-pending','da_xac_nhan'=>'s-confirmed','dang_dong_hang'=>'s-packing','dang_giao'=>'s-shipping','da_giao'=>'s-done','da_huy'=>'s-canceled'];
$trackSteps  = [
  ['cho_xac_nhan', 'fas fa-shopping-bag', 'Đặt hàng'],
  ['da_xac_nhan',  'fas fa-check-circle', 'Xác nhận'],
  ['dang_dong_hang','fas fa-box',         'Đóng hàng'],
  ['dang_giao',    'fas fa-truck',        'Đang giao'],
  ['da_giao',      'fas fa-home',         'Đã giao'],
];
$statusOrder = array_column($trackSteps, 0);
$curIdx      = array_search($order['trang_thai'], $statusOrder);
if ($curIdx === false) $curIdx = 0;
$ptLabel = ['cod'=>'Thanh toán khi nhận','chuyen_khoan'=>'Chuyển khoản','momo'=>'Ví MoMo','vnpay'=>'VNPay'];
?>

<div class="container"><div class="breadcrumb">
  <a href="<?= url() ?>">Trang chủ</a>
  <i class="fas fa-chevron-right"></i>
  <span>Theo dõi đơn hàng</span>
</div></div>

<div style="max-width:900px;margin:0 auto;padding:1.5rem 5% 3rem">

  <!-- Header -->
  <div style="background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:16px;padding:2rem;color:#fff;margin-bottom:1.5rem">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem">
      <div>
        <div style="font-size:.78rem;color:rgba(255,255,255,.6);margin-bottom:.4rem">MÃ ĐƠN HÀNG</div>
        <div style="font-size:1.5rem;font-weight:800;font-family:monospace;letter-spacing:2px"><?= e($order['ma_don']) ?></div>
        <div style="font-size:.82rem;color:rgba(255,255,255,.6);margin-top:.4rem">
          Đặt lúc: <?= format_date($order['created_at']) ?>
        </div>
      </div>
      <span class="badge-status <?= $statusClass[$order['trang_thai']] ?? 's-pending' ?>" style="font-size:.85rem;padding:.4rem 1rem">
        <?= $statusLabel[$order['trang_thai']] ?? $order['trang_thai'] ?>
      </span>
    </div>
  </div>

  <!-- Track steps -->
  <?php if ($order['trang_thai'] !== 'da_huy'): ?>
  <div style="background:#fff;border-radius:16px;border:1px solid var(--gray-200);padding:2rem;margin-bottom:1.5rem">
    <div style="font-size:.82rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1.5rem">Hành trình đơn hàng</div>
    <div class="track-steps">
      <?php foreach ($trackSteps as $idx => [$st, $icon, $name]): ?>
        <div class="track-step <?= $idx < $curIdx ? 'done' : ($idx === $curIdx ? 'active' : '') ?>">
          <div class="track-dot"><i class="<?= $icon ?>"></i></div>
          <span class="track-name"><?= $name ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">
    <!-- Địa chỉ -->
    <div style="background:#fff;border-radius:14px;border:1px solid var(--gray-200);padding:1.25rem">
      <div style="font-size:.75rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem">
        <i class="fas fa-map-marker-alt" style="color:var(--accent)"></i> Địa chỉ giao hàng
      </div>
      <div style="font-weight:700;color:var(--primary)"><?= e($order['ten_nguoi_nhan']) ?></div>
      <div style="font-size:.85rem;color:var(--gray-600);margin-top:.25rem"><?= e($order['so_dien_thoai']) ?></div>
      <div style="font-size:.85rem;color:var(--gray-600);line-height:1.6;margin-top:.4rem">
        <?= e($order['so_nha_duong']) ?>, <?= e($order['phuong_xa']) ?>,<br>
        <?= e($order['quan_huyen']) ?>, <?= e($order['tinh_thanh']) ?>
      </div>
    </div>

    <!-- Thanh toán -->
    <div style="background:#fff;border-radius:14px;border:1px solid var(--gray-200);padding:1.25rem">
      <div style="font-size:.75rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem">
        <i class="fas fa-credit-card" style="color:var(--accent)"></i> Thanh toán & Tổng tiền
      </div>
      <div style="font-weight:600;color:var(--primary);margin-bottom:.5rem">
        <?= $ptLabel[$order['phuong_thuc_tt']] ?? $order['phuong_thuc_tt'] ?>
      </div>
      <?php if ($order['giam_gia'] > 0): ?>
        <div style="font-size:.82rem;color:var(--success)">Tiết kiệm: −<?= format_price($order['giam_gia']) ?></div>
      <?php endif; ?>
      <div style="font-size:1.2rem;font-weight:800;color:var(--danger);margin-top:.5rem">
        <?= format_price($order['tong_tien']) ?>
      </div>
    </div>
  </div>

  <!-- Sản phẩm -->
  <div style="background:#fff;border-radius:14px;border:1px solid var(--gray-200);overflow:hidden;margin-bottom:1.25rem">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--gray-100);font-weight:700">
      <i class="fas fa-box" style="color:var(--accent)"></i> Sản phẩm đặt hàng
    </div>
    <?php foreach ($order['items'] as $item): ?>
    <div style="display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid var(--gray-100)">
      <div style="width:60px;height:60px;border-radius:10px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
        <?php if (!empty($item['hinh_anh'])): ?>
          <img src="<?= img_url(e($item['hinh_anh'])) ?>" style="width:100%;height:100%;object-fit:contain;padding:.2rem">
        <?php else: ?>
          <i class="fas fa-mobile-alt" style="color:var(--gray-300)"></i>
        <?php endif; ?>
      </div>
      <div style="flex:1">
        <div style="font-weight:600;color:var(--primary)"><?= e($item['ten_san_pham']) ?></div>
        <div style="font-size:.78rem;color:var(--gray-400)"><?= e($item['mau_sac']) ?> / <?= e($item['dung_luong']) ?> × <?= $item['so_luong'] ?></div>
      </div>
      <div style="font-weight:700;color:var(--danger)"><?= format_price($item['thanh_tien']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Lịch sử -->
  <?php if (!empty($order['history'])): ?>
  <div style="background:#fff;border-radius:14px;border:1px solid var(--gray-200);padding:1.25rem">
    <div style="font-weight:700;margin-bottom:1rem"><i class="fas fa-history" style="color:var(--accent)"></i> Lịch sử cập nhật</div>
    <?php foreach (array_reverse($order['history']) as $h): ?>
    <div style="display:flex;gap:.75rem;padding:.6rem 0;border-bottom:1px solid var(--gray-100)">
      <div style="width:8px;height:8px;border-radius:50%;background:var(--accent);margin-top:.45rem;flex-shrink:0"></div>
      <div>
        <div style="font-size:.88rem;font-weight:600;color:var(--primary)"><?= $statusLabel[$h['trang_thai']] ?? $h['trang_thai'] ?></div>
        <?php if ($h['ghi_chu']): ?><div style="font-size:.78rem;color:var(--gray-500)"><?= e($h['ghi_chu']) ?></div><?php endif; ?>
        <div style="font-size:.75rem;color:var(--gray-400)"><?= format_date($h['created_at']) ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Actions -->
  <div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
    <a href="<?= url('san-pham') ?>" class="btn btn-outline btn-lg">
      <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
    </a>
    <?php if (Session::has('user_id')): ?>
      <a href="<?= url('tai-khoan/don-hang') ?>" class="btn btn-blue btn-lg">
        <i class="fas fa-box"></i> Tất cả đơn hàng
      </a>
    <?php endif; ?>
  </div>

</div>
