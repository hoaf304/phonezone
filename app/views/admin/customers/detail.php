<?php // app/views/admin/customers/detail.php
$statusPill = ['cho_xac_nhan'=>'s-pending','da_xac_nhan'=>'s-confirmed','dang_giao'=>'s-shipping','da_giao'=>'s-done','da_huy'=>'s-canceled'];
$statusLabel= ['cho_xac_nhan'=>'Chờ XN','da_xac_nhan'=>'Đã XN','dang_dong_hang'=>'Đóng hàng','dang_giao'=>'Đang giao','da_giao'=>'Đã giao','da_huy'=>'Đã hủy'];
?>
<?php ob_start(); ?>
  <a href="<?= url('admin/khach-hang') ?>" class="topbar-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
<?php $topbarActions = ob_get_clean(); ?>

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.25rem;align-items:start">
  <!-- Profile card -->
  <div>
    <div class="form-card">
      <div style="padding:2rem;text-align:center;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:14px 14px 0 0">
        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-hover));margin:0 auto .75rem;display:flex;align-items:center;justify-content:center;font-size:1.75rem;font-weight:800;color:#fff;border:3px solid rgba(255,255,255,.2)">
          <?= mb_substr($customer['ho_ten'],0,1,'UTF-8') ?>
        </div>
        <div style="color:#fff;font-weight:700;font-size:1rem"><?= e($customer['ho_ten']) ?></div>
        <div style="color:rgba(255,255,255,.6);font-size:.78rem;margin-top:.2rem"><?= e($customer['email']) ?></div>
        <div style="margin-top:.75rem">
          <?= $customer['trang_thai']==='hoat_dong'
            ? '<span class="badge-status s-confirmed">Hoạt động</span>'
            : '<span class="badge-status s-canceled">Bị khóa</span>' ?>
        </div>
      </div>
      <div class="form-body" style="display:flex;flex-direction:column;gap:.6rem">
        <?php $infos = [
          ['fas fa-phone','SĐT', $customer['so_dien_thoai']??'—'],
          ['fas fa-calendar','Ngày đăng ký', format_date($customer['created_at'],'d/m/Y')],
          ['fas fa-sign-in-alt','Lần cuối đăng nhập','—'],
        ]; foreach ($infos as [$icon,$label,$val]): ?>
        <div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;padding:.4rem 0;border-bottom:1px solid var(--gray-100)">
          <i class="<?= $icon ?>" style="color:var(--accent);width:14px"></i>
          <span style="color:var(--gray-500)"><?= $label ?>:</span>
          <span style="font-weight:600;color:var(--primary);margin-left:auto"><?= e($val) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Stats -->
    <div class="form-card" style="margin-top:1rem">
      <div class="form-card-head"><i class="fas fa-chart-bar"></i><h3>Thống kê</h3></div>
      <div class="form-body" style="display:flex;flex-direction:column;gap:.6rem">
        <?php $statItems=[['Tổng đơn hàng',$stats['so_don'],'var(--accent)'],['Chi tiêu',format_price($stats['tong_chi']),'var(--danger)'],['Đơn lớn nhất',format_price($stats['don_lon_nhat']),'var(--success)']];
        foreach ($statItems as [$label,$val,$color]): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid var(--gray-100)">
          <span style="font-size:.82rem;color:var(--gray-500)"><?= $label ?></span>
          <strong style="color:<?= $color ?>"><?= $val ?></strong>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Orders -->
  <div class="table-card">
    <div class="table-head"><h3>Lịch sử đơn hàng</h3></div>
    <table>
      <thead><tr><th>Mã đơn</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Ngày đặt</th><th></th></tr></thead>
      <tbody>
      <?php if (empty($orders)): ?>
        <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--gray-400)">Chưa có đơn hàng</td></tr>
      <?php else: ?>
      <?php foreach ($orders as $dh): ?>
      <tr>
        <td><a href="<?= url('admin/don-hang/'.$dh['id']) ?>" class="order-code"><?= e($dh['ma_don']) ?></a></td>
        <td><span class="price-td"><?= format_price($dh['tong_tien']) ?></span></td>
        <td><span style="font-size:.78rem;color:var(--gray-600)"><?= strtoupper($dh['phuong_thuc_tt']) ?></span></td>
        <td><span class="badge-status <?= $statusPill[$dh['trang_thai']]??'s-pending' ?>"><?= $statusLabel[$dh['trang_thai']]??$dh['trang_thai'] ?></span></td>
        <td style="font-size:.78rem;color:var(--gray-400)"><?= format_date($dh['created_at'],'d/m/Y H:i') ?></td>
        <td><a href="<?= url('admin/don-hang/'.$dh['id']) ?>" class="act-btn view" style="display:inline-flex"><i class="fas fa-eye"></i></a></td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
