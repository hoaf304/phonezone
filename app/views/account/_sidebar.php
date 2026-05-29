<?php
/**
 * app/views/account/_sidebar.php
 * @var array  $user      Thông tin khách hàng
 * @var string $activeTab Tab đang active
 */
$user ??= []; $activeTab ??= '';
$initial = mb_substr($user['ho_ten'] ?? 'U', 0, 1, 'UTF-8');
$avatar  = $user['avatar'] ?? Session::get('user_avatar', '');
?>
<div class="account-sidebar">
  <div class="account-user">
    <!-- Avatar -->
    <div style="position:relative;display:inline-block;margin-bottom:.75rem">
      <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;border:3px solid rgba(255,255,255,.25);background:linear-gradient(135deg,var(--accent),var(--accent-hover));margin:0 auto;display:flex;align-items:center;justify-content:center">
        <?php if (!empty($avatar)): ?>
          <img src="<?= img_url(e($avatar)) ?>"
               style="width:100%;height:100%;object-fit:cover"
               alt="<?= e($user['ho_ten'] ?? '') ?>">
        <?php else: ?>
          <span style="font-size:1.75rem;font-weight:800;color:#fff"><?= e($initial) ?></span>
        <?php endif; ?>
      </div>
      <!-- Nút đổi ảnh nhỏ -->
      <a href="<?= url('tai-khoan') ?>#avatar-input"
         style="position:absolute;bottom:0;right:-4px;width:24px;height:24px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.2)"
         title="Thay ảnh đại diện"
         onclick="event.preventDefault();document.getElementById('avatar-input')?.click()">
        <i class="fas fa-camera" style="font-size:.6rem;color:var(--accent)"></i>
      </a>
    </div>
    <div class="account-name"><?= e($user['ho_ten'] ?? '') ?></div>
    <div class="account-email"><?= e($user['email'] ?? '') ?></div>
    <?php if (!empty($user['ngay_sinh'])): ?>
      <div style="font-size:.72rem;color:rgba(255,255,255,.5);margin-top:.25rem">
        <i class="fas fa-birthday-cake"></i>
        <?= date('d/m/Y', strtotime($user['ngay_sinh'])) ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="account-nav">
    <a href="<?= url('tai-khoan') ?>"
       class="account-nav-item <?= $activeTab === 'dashboard' ? 'active' : '' ?>">
      <i class="fas fa-th-large"></i> Tổng quan
    </a>
    <a href="<?= url('tai-khoan/don-hang') ?>"
       class="account-nav-item <?= $activeTab === 'orders' ? 'active' : '' ?>">
      <i class="fas fa-box"></i> Đơn hàng của tôi
    </a>
    <a href="<?= url('tai-khoan/yeu-thich') ?>"
       class="account-nav-item <?= $activeTab === 'wishlist' ? 'active' : '' ?>">
      <i class="fas fa-heart"></i> Sản phẩm yêu thích
    </a>
    <div class="account-nav-divider"></div>
    <a href="<?= url('dang-xuat') ?>"
       class="account-nav-item" style="color:var(--danger)">
      <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </a>
  </div>
</div>
