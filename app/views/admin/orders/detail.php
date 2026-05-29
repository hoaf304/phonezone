<?php // app/views/admin/orders/detail.php
$statusClass = ['cho_xac_nhan'=>'s-pending','da_xac_nhan'=>'s-confirmed','dang_dong_hang'=>'s-packing','dang_giao'=>'s-shipping','da_giao'=>'s-done','da_huy'=>'s-canceled'];
$ptLabel     = ['cod'=>'Thanh toán khi nhận hàng (COD)','chuyen_khoan'=>'Chuyển khoản ngân hàng','momo'=>'Ví MoMo','vnpay'=>'VNPay','zalopay'=>'ZaloPay'];
?>
<?php ob_start(); ?>
  <a href="<?= url('admin/don-hang') ?>" class="topbar-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
<?php $topbarActions = ob_get_clean(); ?>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem;align-items:start">
  <!-- LEFT -->
  <div>
    <!-- Thông tin đơn -->
    <div class="form-card" style="margin-bottom:1.25rem">
      <div class="form-card-head"><i class="fas fa-info-circle"></i><h3>Thông tin đơn hàng</h3></div>
      <div class="form-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div>
            <div class="form-label">Mã đơn hàng</div>
            <div style="font-size:1rem;font-weight:800;color:#1e40af;font-family:monospace"><?= e($order['ma_don']) ?></div>
          </div>
          <div>
            <div class="form-label">Ngày đặt</div>
            <div style="font-weight:600"><?= format_date($order['created_at']) ?></div>
          </div>
          <div>
            <div class="form-label">Người nhận</div>
            <div style="font-weight:600"><?= e($order['ten_nguoi_nhan']) ?></div>
            <div style="font-size:.82rem;color:#64748b"><?= e($order['so_dien_thoai']) ?></div>
          </div>
          <div>
            <div class="form-label">Địa chỉ giao hàng</div>
            <div style="font-size:.85rem;line-height:1.6">
              <?= e($order['so_nha_duong']) ?>, <?= e($order['phuong_xa']) ?>,<br>
              <?= e($order['quan_huyen']) ?>, <?= e($order['tinh_thanh']) ?>
            </div>
          </div>
          <div>
            <div class="form-label">Thanh toán</div>
            <div style="font-weight:600"><?= $ptLabel[$order['phuong_thuc_tt']] ?? $order['phuong_thuc_tt'] ?></div>
          </div>
          <div>
            <div class="form-label">Trạng thái hiện tại</div>
            <span class="badge-status <?= $statusClass[$order['trang_thai']] ?? 's-pending' ?>" style="font-size:.82rem">
              <?= $statusLabel[$order['trang_thai']] ?? $order['trang_thai'] ?>
            </span>
          </div>
        </div>
        <?php if (!empty($order['ghi_chu'])): ?>
        <div style="margin-top:1rem;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.75rem">
          <strong style="font-size:.8rem;color:#92400e">Ghi chú:</strong>
          <span style="font-size:.85rem;color:#78350f"> <?= e($order['ghi_chu']) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sản phẩm -->
    <div class="form-card" style="margin-bottom:1.25rem">
      <div class="form-card-head"><i class="fas fa-box"></i><h3>Sản phẩm đặt hàng</h3></div>
      <table>
        <thead><tr><th>Sản phẩm</th><th>Biến thể</th><th>Đơn giá</th><th>SL</th><th>Thành tiền</th></tr></thead>
        <tbody>
        <?php foreach ($order['items'] as $item): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:.6rem">
              <div class="product-img-cell">
                <?php if (!empty($item['hinh_anh'])): ?>
                  <img src="<?= img_url(e($item['hinh_anh'])) ?>">
                <?php else: ?>
                  <i class="fas fa-mobile-alt" style="color:#cbd5e1;font-size:.8rem"></i>
                <?php endif; ?>
              </div>
              <span style="font-size:.85rem;font-weight:600"><?= e($item['ten_san_pham']) ?></span>
            </div>
          </td>
          <td style="font-size:.78rem;color:#64748b"><?= e($item['mau_sac']) ?> / <?= e($item['dung_luong']) ?></td>
          <td><?= format_price($item['don_gia']) ?></td>
          <td style="font-weight:700;text-align:center"><?= $item['so_luong'] ?></td>
          <td><span class="price-td"><?= format_price($item['thanh_tien']) ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <!-- Tổng đơn -->
      <div style="padding:1rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">
        <div style="font-size:.85rem;color:#64748b">Tạm tính: <strong><?= format_price($order['tam_tinh']) ?></strong></div>
        <?php if ($order['giam_gia'] > 0): ?>
          <div style="font-size:.85rem;color:#16a34a">Giảm giá: <strong>−<?= format_price($order['giam_gia']) ?></strong></div>
        <?php endif; ?>
        <?php if ($order['phi_van_chuyen'] > 0): ?>
          <div style="font-size:.85rem;color:#64748b">Phí ship: <strong><?= format_price($order['phi_van_chuyen']) ?></strong></div>
        <?php endif; ?>
        <div style="font-size:1.1rem;font-weight:800;color:#dc2626">Tổng: <?= format_price($order['tong_tien']) ?></div>
      </div>
    </div>

    <!-- Lịch sử -->
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-history"></i><h3>Lịch sử đơn hàng</h3></div>
      <div class="form-body">
        <?php foreach ($order['history'] as $h): ?>
        <div style="display:flex;gap:.75rem;align-items:flex-start;padding:.6rem 0;border-bottom:1px solid #f8fafc">
          <div style="width:8px;height:8px;border-radius:50%;background:#3b82f6;margin-top:.4rem;flex-shrink:0"></div>
          <div>
            <div style="font-size:.85rem;font-weight:600;color:#0f172a"><?= $statusLabel[$h['trang_thai']] ?? $h['trang_thai'] ?></div>
            <?php if ($h['ghi_chu']): ?><div style="font-size:.78rem;color:#64748b"><?= e($h['ghi_chu']) ?></div><?php endif; ?>
            <div style="font-size:.75rem;color:#94a3b8"><?= format_date($h['created_at']) ?> — <?= e($h['nguoi_thuc_hien']) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- RIGHT: cập nhật trạng thái -->
  <div>
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-edit"></i><h3>Cập nhật trạng thái</h3></div>
      <div class="form-body">
        <form method="POST" action="<?= url('admin/don-hang/' . $order['id'] . '/trang-thai') ?>">
          <?= Session::csrfField() ?>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Trạng thái mới</label>
            <select name="trang_thai" class="form-select-a">
              <?php foreach ($statusLabel as $key => $lbl): ?>
                <option value="<?= $key ?>" <?= $order['trang_thai']===$key?'selected':'' ?>><?= $lbl ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label class="form-label">Ghi chú (tuỳ chọn)</label>
            <textarea name="ghi_chu" class="form-textarea-a" rows="3" placeholder="VD: Đã giao cho đơn vị vận chuyển..."></textarea>
          </div>
          <button type="submit" class="topbar-btn primary" style="width:100%;justify-content:center">
            <i class="fas fa-save"></i> Cập nhật
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
