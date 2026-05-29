<?php // app/views/auth/login.php ?>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">Phone<span style="-webkit-text-fill-color:var(--accent)">Zone</span></div>
    <div class="auth-subtitle">Đăng nhập vào tài khoản của bạn</div>

    <?= flash_messages() ?>

    <form action="<?= url('dang-nhap') ?>" method="POST">
      <?= Session::csrfField() ?>

      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input" placeholder="example@email.com"
               value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
      </div>

      <div class="form-group">
        <label class="form-label" style="display:flex;justify-content:space-between">
          Mật khẩu
          <a href="<?= url('quen-mat-khau') ?>" style="font-size:.8rem;color:var(--accent);font-weight:500">Quên mật khẩu?</a>
        </label>
        <div style="position:relative">
          <input type="password" name="mat_khau" id="inp-pass" class="form-input"
                 placeholder="••••••••" required style="padding-right:2.5rem">
          <i class="far fa-eye" id="toggle-pass"
             style="position:absolute;right:.9rem;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--gray-400)"
             onclick="togglePass()"></i>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem">
        <input type="checkbox" name="remember" id="remember" value="1" style="accent-color:var(--accent)">
        <label for="remember" style="font-size:.85rem;color:var(--gray-600);cursor:pointer">Ghi nhớ đăng nhập</label>
      </div>

      <button type="submit" class="btn btn-blue btn-lg" style="width:100%;justify-content:center">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập
      </button>
    </form>

    <div class="auth-footer">
      Chưa có tài khoản? <a href="<?= url('dang-ky') ?>">Đăng ký ngay</a>
    </div>
  </div>
</div>

<script>
function togglePass() {
  const inp  = document.getElementById('inp-pass');
  const icon = document.getElementById('toggle-pass');
  if (inp.type === 'password') { inp.type='text';     icon.classList.replace('fa-eye','fa-eye-slash'); }
  else                          { inp.type='password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
