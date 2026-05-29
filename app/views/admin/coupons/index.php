<?php // app/views/admin/coupons/index.php ?>
<?php ob_start(); ?>
  <button class="topbar-btn primary" onclick="openModal('modal-add')">
    <i class="fas fa-plus"></i> Tạo mã giảm giá
  </button>
<?php $topbarActions = ob_get_clean(); ?>

<div class="table-card">
  <table>
    <thead><tr>
      <th>Mã</th><th>Tên</th><th>Loại</th><th>Giá trị</th>
      <th>Đơn tối thiểu</th><th>Đã dùng / Tổng</th><th>Hạn dùng</th><th>Trạng thái</th><th>Xóa</th>
    </tr></thead>
    <tbody>
    <?php if (empty($coupons)): ?>
      <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--gray-400)">Chưa có mã giảm giá nào</td></tr>
    <?php else: ?>
    <?php foreach ($coupons as $c):
      $expired  = $c['ket_thuc'] && strtotime($c['ket_thuc']) < time();
      $hetSoLuong = $c['so_luong_tong'] && $c['so_luong_da_dung'] >= $c['so_luong_tong'];
      $active   = $c['an_hien'] && !$expired && !$hetSoLuong;
    ?>
    <tr>
      <td>
        <code style="font-size:.85rem;font-weight:700;color:var(--accent);background:var(--accent-light);padding:.2rem .6rem;border-radius:6px">
          <?= e($c['ma']) ?>
        </code>
      </td>
      <td style="font-size:.85rem;color:var(--primary)"><?= e($c['ten'] ?? '—') ?></td>
      <td>
        <?php if ($c['loai'] === 'phan_tram'): ?>
          <span class="pay-badge pay-bank">% Phần trăm</span>
        <?php else: ?>
          <span class="pay-badge pay-cod">₫ Cố định</span>
        <?php endif; ?>
      </td>
      <td style="font-weight:700;color:var(--danger)">
        <?= $c['loai']==='phan_tram' ? $c['gia_tri'].'%' : format_price($c['gia_tri']) ?>
        <?php if ($c['giam_toi_da'] > 0 && $c['loai']==='phan_tram'): ?>
          <div style="font-size:.72rem;color:var(--gray-400)">Tối đa <?= format_price($c['giam_toi_da']) ?></div>
        <?php endif; ?>
      </td>
      <td style="font-size:.82rem"><?= $c['don_hang_toi_thieu'] > 0 ? format_price($c['don_hang_toi_thieu']) : 'Không giới hạn' ?></td>
      <td style="text-align:center">
        <span style="font-weight:700;color:<?= $hetSoLuong?'var(--danger)':'var(--primary)' ?>">
          <?= $c['so_luong_da_dung'] ?>
        </span>
        <span style="color:var(--gray-400)"> / <?= $c['so_luong_tong'] ?? '∞' ?></span>
      </td>
      <td style="font-size:.78rem">
        <?php if ($c['bat_dau']): ?><div style="color:var(--gray-500)">Từ: <?= format_date($c['bat_dau'],'d/m/Y') ?></div><?php endif; ?>
        <?php if ($c['ket_thuc']): ?><div style="color:<?= $expired?'var(--danger)':'var(--gray-500)' ?>">Đến: <?= format_date($c['ket_thuc'],'d/m/Y') ?></div>
        <?php else: ?><div style="color:var(--gray-400)">Không giới hạn</div><?php endif; ?>
      </td>
      <td>
        <?php if (!$c['an_hien']): ?>
          <span class="badge-status s-canceled">Tắt</span>
        <?php elseif ($expired): ?>
          <span class="badge-status s-canceled">Hết hạn</span>
        <?php elseif ($hetSoLuong): ?>
          <span class="badge-status s-canceled">Hết lượt</span>
        <?php else: ?>
          <span class="badge-status s-confirmed">Đang dùng</span>
        <?php endif; ?>
      </td>
      <td>
        <form method="POST" action="<?= url('admin/ma-giam-gia/xoa/'.$c['id']) ?>" onsubmit="return confirm('Xóa mã <?= e($c['ma']) ?>?')">
          <?= Session::csrfField() ?>
          <button type="submit" class="act-btn del" title="Xóa"><i class="fas fa-trash"></i></button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal tạo mã -->
<div class="modal-overlay" id="modal-add" style="display:none" onclick="if(event.target===this)closeModal('modal-add')">
  <div class="modal-box" style="max-width:520px">
    <div class="modal-head">
      <h3><i class="fas fa-ticket-alt" style="color:var(--accent)"></i> Tạo mã giảm giá</h3>
      <button class="modal-close" onclick="closeModal('modal-add')"><i class="fas fa-times"></i></button>
    </div>
    <form action="<?= url('admin/ma-giam-gia/them') ?>" method="POST">
      <?= Session::csrfField() ?>
      <div class="modal-body">
        <div class="form-grid-2">
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Mã giảm giá <span style="color:var(--danger)">*</span></label>
            <div style="display:flex;gap:.4rem">
              <input type="text" name="ma" id="inp-ma" class="form-input-a" required placeholder="VD: SUMMER20" style="text-transform:uppercase">
              <button type="button" class="topbar-btn" onclick="genCode()" style="white-space:nowrap;flex-shrink:0">Tạo ngẫu nhiên</button>
            </div>
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Tên / Ghi chú</label>
            <input type="text" name="ten" class="form-input-a" placeholder="VD: Ưu đãi mùa hè">
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Loại giảm giá</label>
            <select name="loai" class="form-select-a" id="loai-select" onchange="toggleLoai(this.value)">
              <option value="phan_tram">Phần trăm (%)</option>
              <option value="co_dinh">Số tiền cố định (₫)</option>
            </select>
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Giá trị <span style="color:var(--danger)">*</span></label>
            <input type="number" name="gia_tri" class="form-input-a" required placeholder="VD: 10 (cho %) hoặc 50000 (cho ₫)" step="0.01" min="0">
          </div>
          <div class="form-group" style="margin-bottom:1rem" id="wrap-toi-da">
            <label class="form-label">Giảm tối đa (₫)</label>
            <input type="text" name="giam_toi_da" class="form-input-a" placeholder="VD: 200000 (để trống = không giới hạn)">
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Đơn hàng tối thiểu (₫)</label>
            <input type="text" name="don_hang_toi_thieu" class="form-input-a" value="0">
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Tổng lượt dùng</label>
            <input type="number" name="so_luong_tong" class="form-input-a" placeholder="Để trống = không giới hạn" min="1">
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Giới hạn mỗi khách</label>
            <input type="number" name="gioi_han_moi_kh" class="form-input-a" value="1" min="1">
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="datetime-local" name="bat_dau" class="form-input-a">
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Ngày kết thúc</label>
            <input type="datetime-local" name="ket_thuc" class="form-input-a">
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="topbar-btn" onclick="closeModal('modal-add')">Hủy</button>
        <button type="submit" class="topbar-btn primary"><i class="fas fa-save"></i> Tạo mã</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id){ document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id){ document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.addEventListener('keydown',e=>{ if(e.key==='Escape') document.querySelectorAll('.modal-overlay').forEach(m=>{m.style.display='none';document.body.style.overflow='';}) });

function genCode(){
  const chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  let code='';
  for(let i=0;i<8;i++) code+=chars[Math.floor(Math.random()*chars.length)];
  document.getElementById('inp-ma').value=code;
}

function toggleLoai(val){
  document.getElementById('wrap-toi-da').style.display = val==='phan_tram' ? 'block' : 'none';
}
</script>
