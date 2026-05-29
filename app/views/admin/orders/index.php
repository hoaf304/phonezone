<?php // app/views/admin/orders/index.php
$statusClass = ['cho_xac_nhan'=>'s-pending','da_xac_nhan'=>'s-confirmed','dang_dong_hang'=>'s-packing','dang_giao'=>'s-shipping','da_giao'=>'s-done','da_huy'=>'s-canceled'];
$ptLabel     = ['cod'=>'COD','chuyen_khoan'=>'CK','momo'=>'MoMo','vnpay'=>'VNPay'];
$ptClass     = ['cod'=>'pay-cod','chuyen_khoan'=>'pay-bank','momo'=>'pay-momo','vnpay'=>'pay-vnpay'];
?>

<!-- Status Tabs -->
<div class="status-tabs">
  <a href="<?= url('admin/don-hang') ?>" class="status-tab <?= !$status ? 'active' : '' ?>">
    Tất cả <span class="tab-count"><?= array_sum($countByStatus) ?></span>
  </a>
  <?php foreach ($statusLabel as $key => $lbl): ?>
  <a href="<?= url('admin/don-hang') ?>?status=<?= $key ?>" class="status-tab <?= $status===$key?'active':'' ?>">
    <?= $lbl ?> <span class="tab-count"><?= $countByStatus[$key] ?? 0 ?></span>
  </a>
  <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="admin-filter-bar">
  <form method="GET" action="<?= url('admin/don-hang') ?>" style="display:contents">
    <input type="hidden" name="status" value="<?= e($status) ?>">
    <div class="admin-search">
      <i class="fas fa-search"></i>
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Mã đơn, tên KH, SĐT...">
    </div>
    <select name="pt" class="filter-select" onchange="this.form.submit()">
      <option value="">Tất cả thanh toán</option>
      <?php foreach ($ptLabel as $k=>$v): ?>
        <option value="<?= $k ?>" <?= $pt===$k?'selected':'' ?>><?= $v ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="topbar-btn"><i class="fas fa-search"></i> Lọc</button>
    <a href="<?= url('admin/don-hang') ?>" class="topbar-btn">Reset</a>
    <span style="margin-left:auto;font-size:.82rem;color:#64748b">
      <strong><?= number_format($pager['total']) ?></strong> đơn hàng
    </span>
  </form>
</div>

<!-- Table -->
<div class="table-card">
  <table>
    <thead><tr>
      <th>Mã đơn</th><th>Khách hàng</th><th>Sản phẩm</th>
      <th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Ngày đặt</th><th>Thao tác</th>
    </tr></thead>
    <tbody>
    <?php if (empty($orders)): ?>
      <tr><td colspan="8" style="text-align:center;padding:2rem;color:#94a3b8">Không có đơn hàng nào</td></tr>
    <?php else: ?>
    <?php foreach ($orders as $dh): ?>
    <tr>
      <td><a href="<?= url('admin/don-hang/' . $dh['id']) ?>" class="order-code"><?= e($dh['ma_don']) ?></a></td>
      <td>
        <div class="customer-cell">
          <div class="name"><?= e($dh['ten_kh'] ?? $dh['ten_nguoi_nhan']) ?></div>
          <div class="phone"><?= e($dh['so_dien_thoai']) ?></div>
        </div>
      </td>
      <td style="font-size:.8rem;color:#64748b"><?= $dh['so_sp'] ?> sản phẩm</td>
      <td><span class="price-td"><?= format_price($dh['tong_tien']) ?></span></td>
      <td>
        <span class="pay-badge <?= $ptClass[$dh['phuong_thuc_tt']] ?? 'pay-cod' ?>">
          <?= $ptLabel[$dh['phuong_thuc_tt']] ?? $dh['phuong_thuc_tt'] ?>
        </span>
      </td>
      <td><span class="badge-status <?= $statusClass[$dh['trang_thai']] ?? 's-pending' ?>"><?= $statusLabel[$dh['trang_thai']] ?? $dh['trang_thai'] ?></span></td>
      <td style="font-size:.78rem;color:#94a3b8"><?= format_date($dh['created_at'],'d/m/Y H:i') ?></td>
      <td>
        <div class="actions-td">
          <a href="<?= url('admin/don-hang/' . $dh['id']) ?>" class="act-btn view" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<?= render_pagination($pager, url('admin/don-hang') . '?status=' . urlencode($status) . '&q=' . urlencode($q)) ?>
