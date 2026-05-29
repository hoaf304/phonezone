<?php // app/views/admin/reports/index.php
$ptLabel = ['cod'=>'COD','chuyen_khoan'=>'Chuyển khoản','momo'=>'MoMo','vnpay'=>'VNPay','zalopay'=>'ZaloPay'];
// Tạo mảng 12 tháng đầy đủ
$dtByMonth = array_fill(1, 12, 0);
$donByMonth = array_fill(1, 12, 0);
foreach ($doanhThuThang as $r) { $dtByMonth[(int)$r['thang']] = (float)$r['doanh_thu']/1000000; $donByMonth[(int)$r['thang']] = (int)$r['so_don']; }
$khByMonth = array_fill(1, 12, 0);
foreach ($khachMoiThang as $r) $khByMonth[(int)$r['thang']] = (int)$r['so_kh'];
?>

<?php ob_start(); ?>
  <form method="GET" action="<?= url('admin/bao-cao') ?>" style="display:flex;gap:.5rem;align-items:center">
    <select name="nam" class="filter-select" onchange="this.form.submit()">
      <?php foreach ($namList as $y): ?><option value="<?= $y ?>" <?= $y==$nam?'selected':'' ?>><?= $y ?></option><?php endforeach; ?>
    </select>
  </form>
<?php $topbarActions = ob_get_clean(); ?>

<!-- KPI -->
<div class="kpi-grid" style="margin-bottom:1.5rem">
  <?php
  $kpiItems = [
    ['orange','fas fa-wallet',    'Hôm nay',   format_price($kpi['hom_nay'])],
    ['blue',  'fas fa-calendar-week','Tuần này',format_price($kpi['tuan'])],
    ['green', 'fas fa-calendar-alt','Tháng này',format_price($kpi['thang'])],
    ['purple','fas fa-chart-bar', 'Cả năm '.$nam, format_price($kpi['nam'])],
  ];
  foreach ($kpiItems as [$color,$icon,$label,$val]):
  ?>
  <div class="kpi-card">
    <div class="kpi-icon <?= $color ?>"><i class="<?= $icon ?>"></i></div>
    <div class="kpi-body">
      <div class="kpi-label"><?= $label ?></div>
      <div class="kpi-value" style="font-size:1.2rem"><?= $val ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts row 1 -->
<div class="charts-row" style="margin-bottom:1.25rem">
  <div class="chart-card">
    <div class="chart-head"><div class="chart-title">📈 Doanh thu theo tháng (<?= $nam ?>)</div></div>
    <canvas id="revenueChart" height="100"></canvas>
  </div>
  <div class="chart-card">
    <div class="chart-head"><div class="chart-title">👥 Khách hàng mới</div></div>
    <canvas id="customerChart" height="100"></canvas>
  </div>
</div>

<!-- Charts row 2 -->
<div class="charts-row" style="margin-bottom:1.25rem">
  <div class="chart-card">
    <div class="chart-head"><div class="chart-title">📦 Số đơn hàng theo tháng</div></div>
    <canvas id="orderChart" height="100"></canvas>
  </div>
  <div class="chart-card">
    <div class="chart-head"><div class="chart-title">💳 Phương thức thanh toán</div></div>
    <canvas id="payChart" height="180"></canvas>
  </div>
</div>

<!-- Tables row -->
<div class="tables-row">
  <!-- Top sản phẩm -->
  <div class="table-card">
    <div class="table-head"><h3>🏆 Top sản phẩm bán chạy</h3></div>
    <table>
      <thead><tr><th>#</th><th>Sản phẩm</th><th>Đã bán</th><th>Doanh thu</th></tr></thead>
      <tbody>
      <?php foreach ($topSanPham as $i => $sp): ?>
      <tr>
        <td>
          <span style="width:24px;height:24px;border-radius:50%;background:<?= $i<3?'linear-gradient(135deg,#f59e0b,#d97706)':'var(--gray-100)' ?>;color:<?= $i<3?'#fff':'var(--gray-600)' ?>;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700">
            <?= $i+1 ?>
          </span>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:.6rem">
            <div class="top-product-img">
              <?php if (!empty($sp['hinh_chinh'])): ?>
                <img src="<?= img_url(e($sp['hinh_chinh'])) ?>">
              <?php else: ?>
                <i class="fas fa-mobile-alt" style="color:var(--gray-300);font-size:.8rem"></i>
              <?php endif; ?>
            </div>
            <div>
              <div style="font-size:.82rem;font-weight:600;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($sp['ten']) ?></div>
              <div style="font-size:.72rem;color:var(--gray-400)"><?= e($sp['hang_ten']) ?></div>
            </div>
          </div>
        </td>
        <td style="font-weight:700;color:var(--accent)"><?= number_format($sp['da_ban']) ?></td>
        <td><span class="price-td"><?= format_price($sp['doanh_thu']) ?></span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Doanh thu theo hãng -->
  <div class="table-card">
    <div class="table-head"><h3>🏭 Doanh thu theo hãng</h3></div>
    <table>
      <thead><tr><th>Hãng</th><th>Đã bán</th><th>Doanh thu</th></tr></thead>
      <tbody>
      <?php foreach ($doanhThuHang as $h): ?>
      <tr>
        <td style="font-weight:700;color:var(--primary)"><?= e($h['hang_ten']) ?></td>
        <td><?= number_format($h['da_ban']) ?> sp</td>
        <td><span class="price-td"><?= format_price($h['doanh_thu']) ?></span></td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($doanhThuHang)): ?><tr><td colspan="3" style="text-align:center;color:var(--gray-400);padding:1.5rem">Chưa có dữ liệu</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
const months = ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'];
const rev  = <?= json_encode(array_values($dtByMonth)) ?>;
const ords = <?= json_encode(array_values($donByMonth)) ?>;
const khs  = <?= json_encode(array_values($khByMonth)) ?>;

const chartOpts = {responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#f1f5f9'}}}};

new Chart(document.getElementById('revenueChart'),{type:'bar',data:{labels:months,datasets:[{label:'Triệu ₫',data:rev,backgroundColor:ctx=>{const g=ctx.chart.ctx.createLinearGradient(0,0,0,200);g.addColorStop(0,'rgba(59,130,246,.85)');g.addColorStop(1,'rgba(59,130,246,.3)');return g},borderRadius:6,borderSkipped:false}]},options:{...chartOpts,scales:{y:{beginAtZero:true,ticks:{callback:v=>v+'M'},grid:{color:'#f1f5f9'}}}}});

new Chart(document.getElementById('orderChart'),{type:'line',data:{labels:months,datasets:[{label:'Đơn',data:ords,borderColor:'#10b981',backgroundColor:'rgba(16,185,129,.1)',fill:true,tension:.4,pointBackgroundColor:'#10b981',pointRadius:4}]},options:chartOpts});

new Chart(document.getElementById('customerChart'),{type:'bar',data:{labels:months,datasets:[{label:'KH mới',data:khs,backgroundColor:'rgba(139,92,246,.75)',borderRadius:6}]},options:chartOpts});

const payData = <?= json_encode($thongKeTT) ?>;
const ptLabel = <?= json_encode($ptLabel) ?>;
new Chart(document.getElementById('payChart'),{type:'doughnut',data:{labels:payData.map(p=>ptLabel[p.phuong_thuc_tt]||p.phuong_thuc_tt),datasets:[{data:payData.map(p=>parseInt(p.so_don)),backgroundColor:['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'],borderWidth:3,borderColor:'#fff'}]},options:{responsive:true,plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:12}}}}});
</script>
