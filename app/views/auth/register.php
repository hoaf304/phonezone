<?php // app/views/auth/register.php ?>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">Phone<span style="-webkit-text-fill-color:var(--accent)">Zone</span></div>
    <div class="auth-subtitle">Tạo tài khoản mới – Mua sắm dễ dàng hơn</div>

    <?= flash_messages() ?>

    <form action="<?= url('dang-ky') ?>" method="POST">
      <?= Session::csrfField() ?>

      <div class="form-group">
        <label class="form-label">Họ và tên</label>
        <input type="text" name="ho_ten" class="form-input" placeholder="Nguyễn Văn A"
               value="<?= e($_POST['ho_ten'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input" placeholder="example@email.com"
               value="<?= e($_POST['email'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Số điện thoại</label>
        <input type="tel" name="so_dien_thoai" class="form-input" placeholder="0912 345 678"
               value="<?= e($_POST['so_dien_thoai'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Mật khẩu <span style="color:var(--gray-400);font-size:.78rem">(tối thiểu 6 ký tự)</span></label>
        <input type="password" name="mat_khau" class="form-input" placeholder="••••••••" required minlength="6">
      </div>

      <div class="form-group">
        <label class="form-label">Xác nhận mật khẩu</label>
        <input type="password" name="xac_nhan_mat_khau" class="form-input" placeholder="••••••••" required>
      </div>

      <div style="margin-bottom:1.25rem;font-size:.82rem;color:var(--gray-500)">
        Bằng cách đăng ký, bạn đồng ý với
        <a href="#" style="color:var(--accent)">Điều khoản dịch vụ</a> và
        <a href="#" style="color:var(--accent)">Chính sách bảo mật</a> của PhoneZone.
      </div>

      <button type="submit" class="btn btn-blue btn-lg" style="width:100%;justify-content:center">
        <i class="fas fa-user-plus"></i> Tạo tài khoản
      </button>
    </form>

    <div class="auth-footer">
      Đã có tài khoản? <a href="<?= url('dang-nhap') ?>">Đăng nhập</a>
    </div>
  </div>
</div>
