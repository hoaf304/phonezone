<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= Session::csrfToken() ?>">
  <meta name="base-url" content="<?= APP_URL ?>">
  <title><?= isset($title) ? e($title) . ' – ' . APP_NAME : APP_NAME ?></title>
  <meta name="description" content="<?= isset($metaDesc) ? e($metaDesc) : 'Hệ thống bán lẻ điện thoại chính hãng #1 Việt Nam' ?>">

  <!-- Font & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
  <?php if (isset($extraCss)): foreach ($extraCss as $css): ?>
  <link rel="stylesheet" href="<?= asset('css/' . $css) ?>">
  <?php endforeach; endif; ?>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
  <div class="nav-container">

    <!-- Logo -->
    <a href="<?= url() ?>" class="logo">Phone<span>Zone</span></a>

    <!-- Search -->
    <form class="nav-search" action="<?= url('san-pham') ?>" method="GET">
      <i class="fas fa-search search-icon"></i>
      <input type="text" name="q" class="search-input"
             placeholder="Tìm kiếm iPhone, Samsung, Xiaomi..."
             value="<?= isset($searchQuery) ? e($searchQuery) : '' ?>"
             autocomplete="off">
    </form>

    <!-- Actions -->
    <div class="nav-actions">

      <!-- So sánh -->
      <?php $compareCount = count(array_filter(Session::get('compare', []))); ?>
      <a href="<?= url('so-sanh') ?>" class="nav-btn">
        <i class="fas fa-exchange-alt"></i>
        <span>So sánh</span>
        <?php if ($compareCount > 0): ?>
          <span class="badge"><?= $compareCount ?></span>
        <?php endif; ?>
      </a>

      <!-- Yêu thích -->
      <a href="<?= url('tai-khoan/yeu-thich') ?>" class="nav-btn">
        <i class="far fa-heart"></i>
        <span>Yêu thích</span>
      </a>

      <!-- Giỏ hàng -->
      <a href="<?= url('gio-hang') ?>" class="nav-btn" id="nav-cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Giỏ hàng</span>
        <?php $cartCount = cart_count(); if ($cartCount > 0): ?>
          <span class="badge" id="cart-badge"><?= $cartCount ?></span>
        <?php else: ?>
          <span class="badge" id="cart-badge" style="display:none">0</span>
        <?php endif; ?>
      </a>

      <!-- User -->
      <?php if (Session::has('user_id')):
        $userAvatar = Session::get('user_avatar', '');
        $userName   = Session::get('user_name', 'Tài khoản');
      ?>
        <div class="user-menu" id="user-menu">
          <button type="button" class="nav-btn login-btn" id="user-menu-btn"
                  aria-haspopup="true" aria-expanded="false"
                  onclick="toggleUserMenu(event)">
            <?php if (!empty($userAvatar)): ?>
              <img src="<?= img_url(e($userAvatar)) ?>"
                   style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.4);flex-shrink:0"
                   alt="<?= e($userName) ?>">
            <?php else: ?>
              <span style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;flex-shrink:0">
                <?= mb_substr($userName, 0, 1, 'UTF-8') ?>
              </span>
            <?php endif; ?>
            <span><?= e($userName) ?></span>
            <i class="fas fa-chevron-down" id="user-chevron"
               style="font-size:.65rem;transition:transform .2s"></i>
          </button>
          <div class="user-dropdown" id="user-dropdown" role="menu">
            <!-- Header dropdown -->
            <div style="padding:.85rem 1rem;background:var(--light);border-bottom:1px solid var(--gray-100);display:flex;align-items:center;gap:.75rem">
              <?php if (!empty($userAvatar)): ?>
                <img src="<?= img_url(e($userAvatar)) ?>"
                     style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--accent-light)">
              <?php else: ?>
                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-hover));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0">
                  <?= mb_substr($userName, 0, 1, 'UTF-8') ?>
                </div>
              <?php endif; ?>
              <div>
                <div style="font-size:.88rem;font-weight:700;color:var(--primary)"><?= e($userName) ?></div>
                <div style="font-size:.72rem;color:var(--gray-400)">Tài khoản của tôi</div>
              </div>
            </div>
            <a href="<?= url('tai-khoan') ?>" role="menuitem">
              <i class="fas fa-user-circle"></i> Hồ sơ cá nhân
            </a>
            <a href="<?= url('tai-khoan/don-hang') ?>" role="menuitem">
              <i class="fas fa-box"></i> Đơn hàng của tôi
            </a>
            <a href="<?= url('tai-khoan/yeu-thich') ?>" role="menuitem">
              <i class="fas fa-heart"></i> Sản phẩm yêu thích
            </a>
            <a href="<?= url('dang-xuat') ?>" role="menuitem" style="color:var(--danger)">
              <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
          </div>
        </div>
      <?php else: ?>
        <a href="<?= url('dang-nhap') ?>" class="nav-btn login-btn">
          <i class="fas fa-user"></i>
          <span>Đăng nhập</span>
        </a>
      <?php endif; ?>

    </div>
  </div>
</nav>
<!-- ===== END NAVBAR ===== -->

<script>
// ---- User dropdown: click toggle ----
function toggleUserMenu(e) {
  e.stopPropagation();
  const menu     = document.getElementById('user-menu');
  const btn      = document.getElementById('user-menu-btn');
  const chevron  = document.getElementById('user-chevron');
  const isOpen   = menu.classList.toggle('open');

  btn.setAttribute('aria-expanded', isOpen);
  if (chevron) chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0)';
}

// Đóng dropdown khi click bất kỳ chỗ nào bên ngoài
document.addEventListener('click', function(e) {
  const menu = document.getElementById('user-menu');
  if (!menu) return;
  if (!menu.contains(e.target)) {
    menu.classList.remove('open');
    const btn     = document.getElementById('user-menu-btn');
    const chevron = document.getElementById('user-chevron');
    if (btn)     btn.setAttribute('aria-expanded', 'false');
    if (chevron) chevron.style.transform = 'rotate(0)';
  }
});

// Đóng dropdown khi nhấn Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const menu = document.getElementById('user-menu');
    if (menu) {
      menu.classList.remove('open');
      document.getElementById('user-menu-btn')?.setAttribute('aria-expanded','false');
      document.getElementById('user-chevron').style.transform = 'rotate(0)';
      document.getElementById('user-menu-btn')?.focus();
    }
  }
});
</script>

<!-- Flash messages -->
<div class="container" style="padding-top:0;padding-bottom:0">
  <?= flash_messages() ?>
</div>
