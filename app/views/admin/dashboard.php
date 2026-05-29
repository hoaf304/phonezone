<?php // app/views/admin/dashboard.php
$statusLabel = ['cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_dong_hang'=>'Đóng hàng','dang_giao'=>'Đang giao','da_giao'=>'Đã giao','da_huy'=>'Đã hủy'];
$statusClass = ['cho_xac_nhan'=>'s-pending','da_xac_nhan'=>'s-confirmed','dang_dong_hang'=>'s-packing','dang_giao'=>'s-shipping','da_giao'=>'s-done','da_huy'=>'s-canceled'];
$ptLabel     = ['cod'=>'COD','chuyen_khoan'=>'Chuyển khoản','momo'=>'MoMo','vnpay'=>'VNPay'];
$ptClass     = ['cod'=>'pay-cod','chuyen_khoan'=>'pay-bank','momo'=>'pay-momo','vnpay'=>'pay-vnpay'];
?>

<!-- Topbar actions -->
<?php ob_start(); ?>
  <a href="<?= url('admin/san-pham/them') ?>" class="topbar-btn primary">
    <i class="fas fa-plus"></i> Thêm sản phẩm
  </a>
<?php $topbarActions = ob_get_clean(); ?>

<!-- KPI CARDS -->
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon orange"><i class="fas fa-wallet"></i></div>
    <div class="kpi-body">
      <div class="kpi-label">Doanh thu hôm nay</div>
      <div class="kpi-value"><?= format_price($doanhThuHomNay) ?></div>
      <div class="kpi-trend up"><i class="fas fa-shopping-bag"></i> <?= $donHomNay ?> đơn hàng</div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon blue"><i class="fas fa-shopping-bag"></i></div>
    <div class="kpi-body">
      <div class="kpi-label">Đơn hàng hôm nay</div>
      <div class="kpi-value"><?= $donHomNay ?></div>
      <div class="kpi-trend up"><i class="fas fa-arrow-up"></i> Tổng <?= Database::fetchScalar("SELECT COUNT(*) FROM don_hang") ?> đơn</div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon green"><i class="fas fa-user-plus"></i></div>
    <div class="kpi-body">
      <div class="kpi-label">Khách hàng mới (tháng)</div>
      <div class="kpi-value"><?= $khachMoiThang ?></div>
      <div class="kpi-trend up"><i class="fas fa-users"></i> Tổng <?= Database::fetchScalar("SELECT COUNT(*) FROM khach_hang") ?> KH</div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon red"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="kpi-body">
      <div class="kpi-label">Sắp hết hàng (≤5)</div>
      <div class="kpi-value"><?= $sapHetHang ?></div>
      <div class="kpi-trend down"><a href="<?= url('admin/san-pham?filter=low_stock') ?>" style="color:inherit">Xem ngay →</a></div>
    </div>
  </div>
</div>

<!-- CHARTS -->
<div class="charts-row">
  <div class="chart-card">
    <div class="chart-head">
      <div class="chart-title">Doanh thu 6 tháng gần nhất</div>
    </div>
    <canvas id="revenueChart" height="90"></canvas>
  </div>
  <div class="chart-card">
    <div class="chart-head"><div class="chart-title">Trạng thái đơn hàng</div></div>
    <canvas id="statusChart" height="180"></canvas>
  </div>
</div>

<!-- TABLES -->
<div class="tables-row">
  <!-- Đơn hàng mới nhất -->
  <div class="table-card">
    <div class="table-head">
      <h3>Đơn hàng mới nhất</h3>
      <a href="<?= url('admin/don-hang') ?>" class="see-all">Xem tất cả →</a>
    </div>
    <table>
      <thead><tr>
        <th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Ngày</th>
      </tr></thead>
      <tbody>
      <?php foreach ($donHangMoi as $dh): ?>
      <tr>
        <td><a href="<?= url('admin/don-hang/' . $dh['id']) ?>" class="order-code"><?= e($dh['ma_don']) ?></a></td>
        <td>
          <div class="customer-cell">
            <div class="name"><?= e($dh['ten_kh'] ?? $dh['ten_nguoi_nhan']) ?></div>
            <div class="phone"><?= e($dh['so_dien_thoai']) ?></div>
          </div>
        </td>
        <td><span class="price-td"><?= format_price($dh['tong_tien']) ?></span></td>
        <td><span class="pay-badge <?= $ptClass[$dh['phuong_thuc_tt']] ?? 'pay-cod' ?>"><?= $ptLabel[$dh['phuong_thuc_tt']] ?? $dh['phuong_thuc_tt'] ?></span></td>
        <td><span class="badge-status <?= $statusClass[$dh['trang_thai']] ?? 's-pending' ?>"><?= $statusLabel[$dh['trang_thai']] ?? $dh['trang_thai'] ?></span></td>
        <td style="font-size:.78rem;color:#94a3b8"><?= format_date($dh['created_at'],'d/m/Y H:i') ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Top sản phẩm -->
  <div class="table-card">
    <div class="table-head">
      <h3>Sản phẩm bán chạy</h3>
      <a href="<?= url('admin/san-pham') ?>" class="see-all">Quản lý →</a>
    </div>
    <table>
      <thead><tr><th>Sản phẩm</th><th>Đã bán</th><th>Doanh thu</th></tr></thead>
      <tbody>
      <?php foreach ($topSanPham as $sp): ?>
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:.6rem">
            <div class="top-product-img">
              <?php if (!empty($sp['hinh_chinh'])): ?>
                <img src="<?= img_url(e($sp['hinh_chinh'])) ?>">
              <?php else: ?>
                <i class="fas fa-mobile-alt" style="color:#cbd5e1;font-size:.8rem"></i>
              <?php endif; ?>
            </div>
            <span style="font-size:.8rem;font-weight:600;color:#0f172a;max-width:120px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($sp['ten']) ?></span>
          </div>
        </td>
        <td style="font-weight:700;color:#3b82f6"><?= number_format($sp['da_ban']) ?></td>
        <td><span class="price-td"><?= format_price($sp['doanh_thu']) ?></span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- CHARTS JS -->
<script>
const months  = <?= json_encode(array_column($doanhThu6Thang, 'thang')) ?>;
const revenue = <?= json_encode(array_map(fn($r) => round($r['tong']/1000000,1), $doanhThu6Thang)) ?>;

new Chart(document.getElementById('revenueChart'), {
  type:'bar',
  data:{ labels:months, datasets:[{
    label:'Doanh thu (triệu ₫)', data:revenue,
    backgroundColor:'rgba(59,130,246,0.8)', borderRadius:6
  }]},
  options:{ responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{callback:v=>v+'M'}}} }
});

const statusData = <?= json_encode($thongKeTrangThai) ?>;
const sLabels = statusData.map(s=>({cho_xac_nhan:'Chờ XN',da_xac_nhan:'Đã XN',dang_giao:'Đang giao',da_giao:'Đã giao',da_huy:'Đã hủy'}[s.trang_thai]||s.trang_thai));
const sCounts = statusData.map(s=>parseInt(s.so_luong));
new Chart(document.getElementById('statusChart'), {
  type:'doughnut',
  data:{ labels:sLabels, datasets:[{data:sCounts, backgroundColor:['#fbbf24','#3b82f6','#8b5cf6','#10b981','#ef4444'], borderWidth:2}]},
  options:{ responsive:true, plugins:{legend:{position:'bottom',labels:{font:{size:11}}}} }
});
</script>
