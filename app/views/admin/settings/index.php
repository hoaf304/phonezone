<?php // app/views/admin/settings/index.php
$s = fn($nhom, $khoa, $default='') => $settings[$nhom][$khoa] ?? $default;
?>
<?php ob_start(); ?>
  <button type="submit" form="settings-form" class="topbar-btn primary">
    <i class="fas fa-save"></i> Lưu tất cả cài đặt
  </button>
<?php $topbarActions = ob_get_clean(); ?>

<form id="settings-form" method="POST" action="<?= url('admin/cai-dat') ?>">
<?= Session::csrfField() ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">

  <!-- Thông tin cửa hàng -->
  <div class="form-card">
    <div class="form-card-head"><i class="fas fa-store"></i><h3>Thông tin cửa hàng</h3></div>
    <div class="form-body" style="display:flex;flex-direction:column;gap:1rem">
      <div>
        <label class="form-label">Tên cửa hàng</label>
        <input type="text" name="ten_cua_hang" class="form-input-a" value="<?= e($s('chung','ten_cua_hang','PhoneZone')) ?>">
      </div>
      <div>
        <label class="form-label">Slogan</label>
        <input type="text" name="slogan" class="form-input-a" value="<?= e($s('chung','slogan')) ?>">
      </div>
      <div>
        <label class="form-label">Email liên hệ</label>
        <input type="email" name="email_lien_he" class="form-input-a" value="<?= e($s('chung','email_lien_he')) ?>">
      </div>
      <div>
        <label class="form-label">Hotline</label>
        <input type="text" name="hotline" class="form-input-a" value="<?= e($s('chung','hotline')) ?>">
      </div>
      <div>
        <label class="form-label">Địa chỉ</label>
        <textarea name="dia_chi" class="form-textarea-a" rows="2"><?= e($s('chung','dia_chi')) ?></textarea>
      </div>
    </div>
  </div>

  <!-- Vận chuyển & Thanh toán -->
  <div>
    <div class="form-card" style="margin-bottom:1.25rem">
      <div class="form-card-head"><i class="fas fa-truck"></i><h3>Vận chuyển</h3></div>
      <div class="form-body" style="display:flex;flex-direction:column;gap:1rem">
        <div>
          <label class="form-label">Phí vận chuyển mặc định (₫)</label>
          <input type="number" name="phi_ship_mac_dinh" class="form-input-a" value="<?= e($s('van_chuyen','phi_ship_mac_dinh','30000')) ?>" min="0">
        </div>
        <div>
          <label class="form-label">Miễn phí ship từ (₫)</label>
          <input type="number" name="mien_phi_ship_tu" class="form-input-a" value="<?= e($s('van_chuyen','mien_phi_ship_tu','1000000')) ?>" min="0">
          <p style="font-size:.75rem;color:var(--gray-400);margin-top:.25rem">Đơn hàng từ số tiền này trở lên sẽ được miễn phí ship</p>
        </div>
      </div>
    </div>

    <div class="form-card" style="margin-bottom:1.25rem">
      <div class="form-card-head"><i class="fas fa-credit-card"></i><h3>Thanh toán</h3></div>
      <div class="form-body" style="display:flex;flex-direction:column;gap:1rem">
        <div>
          <label class="form-label">Đơn vị tiền tệ</label>
          <input type="text" name="tien_te" class="form-input-a" value="<?= e($s('thanh_toan','tien_te','VND')) ?>">
        </div>
        <div>
          <label class="form-label">Thuế VAT (%)</label>
          <input type="number" name="thue_vat" class="form-input-a" value="<?= e($s('thanh_toan','thue_vat','0')) ?>" min="0" max="100">
        </div>
      </div>
    </div>

    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-university"></i><h3>Thông tin chuyển khoản</h3></div>
      <div class="form-body" style="display:flex;flex-direction:column;gap:1rem">
        <div>
          <label class="form-label">Ngân hàng</label>
          <input type="text" name="ngan_hang" class="form-input-a" value="<?= e($s('thanh_toan','ngan_hang','Vietcombank (VCB)')) ?>">
        </div>
        <div>
          <label class="form-label">Số tài khoản</label>
          <input type="text" name="so_tai_khoan" class="form-input-a" value="<?= e($s('thanh_toan','so_tai_khoan','')) ?>">
        </div>
        <div>
          <label class="form-label">Chủ tài khoản</label>
          <input type="text" name="chu_tai_khoan" class="form-input-a" value="<?= e($s('thanh_toan','chu_tai_khoan','')) ?>">
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Thông tin thêm -->
<div class="form-card" style="margin-top:1.25rem">
  <div class="form-card-head"><i class="fas fa-info-circle"></i><h3>Mạng xã hội & Liên kết</h3></div>
  <div class="form-body">
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem">
      <div>
        <label class="form-label"><i class="fab fa-facebook" style="color:#1877f2"></i> Facebook</label>
        <input type="url" name="facebook" class="form-input-a" value="<?= e($s('mxh','facebook','')) ?>" placeholder="https://facebook.com/...">
      </div>
      <div>
        <label class="form-label"><i class="fab fa-instagram" style="color:#e1306c"></i> Instagram</label>
        <input type="url" name="instagram" class="form-input-a" value="<?= e($s('mxh','instagram','')) ?>" placeholder="https://instagram.com/...">
      </div>
      <div>
        <label class="form-label"><i class="fab fa-youtube" style="color:#ff0000"></i> YouTube</label>
        <input type="url" name="youtube" class="form-input-a" value="<?= e($s('mxh','youtube','')) ?>" placeholder="https://youtube.com/...">
      </div>
    </div>
  </div>
</div>

</form>
