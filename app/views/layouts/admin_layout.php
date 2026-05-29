<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? e($title) . ' – Admin ' . APP_NAME : 'Admin ' . APP_NAME ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
  <meta name="csrf-token" content="<?= Session::csrfToken() ?>">
  <meta name="base-url"   content="<?= APP_URL ?>">
</head>
<body class="admin-body">

<!-- ===== SIDEBAR ===== -->
<div class="admin-sidebar" id="admin-sidebar">
  <div class="sidebar-logo">
    <div class="logo">Phone<span>Zone</span></div>
    <div class="sub">Bảng quản trị</div>
  </div>

  <div class="sidebar-nav">
    <?php
    $uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base    = parse_url(APP_URL, PHP_URL_PATH) ?? '';
    $current = trim(str_replace($base, '', $uri), '/');
    function navItem(string $icon, string $label, string $path, string $current, ?int $badge = null): void {
        $active = (str_starts_with($current, $path) || $current === $path) ? 'active' : '';
        $url    = url($path);
        echo "<a href=\"$url\" class=\"nav-item $active\">
            <i class=\"$icon\"></i> $label"
            . ($badge ? "<span class=\"badge\">$badge</span>" : '')
            . "</a>";
    }
    $pendingOrders = (int)Database::fetchScalar("SELECT COUNT(*) FROM don_hang WHERE trang_thai = 'cho_xac_nhan'");
    $pendingReviews = (int)Database::fetchScalar("SELECT COUNT(*) FROM danh_gia WHERE trang_thai = 'cho_duyet'");
    ?>
    <div class="nav-group">
      <div class="nav-group-label">Tổng quan</div>
      <?php navItem('fas fa-th-large', 'Dashboard',        'admin/dashboard', $current); ?>
      <?php navItem('fas fa-chart-bar','Báo cáo thống kê', 'admin/bao-cao',   $current); ?>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Quản lý</div>
      <?php navItem('fas fa-mobile-alt',  'Sản phẩm',    'admin/san-pham',  $current); ?>
      <?php navItem('fas fa-tags',        'Danh mục',    'admin/danh-muc',  $current); ?>
      <?php navItem('fas fa-shopping-bag','Đơn hàng',    'admin/don-hang',  $current, $pendingOrders ?: null); ?>
      <?php navItem('fas fa-users',       'Khách hàng',  'admin/khach-hang',$current); ?>
      <?php navItem('fas fa-star',        'Đánh giá',    'admin/danh-gia',  $current, $pendingReviews ?: null); ?>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Khuyến mãi</div>
      <?php navItem('fas fa-ticket-alt','Mã giảm giá', 'admin/ma-giam-gia', $current); ?>
    </div>
    <div class="nav-group">
      <div class="nav-group-label">Hệ thống</div>
      <?php navItem('fas fa-cog',          'Cài đặt',   'admin/cai-dat',   $current); ?>
      <a href="<?= url('admin/dang-xuat') ?>" class="nav-item" style="color:rgba(255,100,100,.8)">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
      </a>
    </div>
  </div>

  <div class="sidebar-user">
    <div class="user-avatar"><?= mb_substr(Session::get('admin_name','A'), 0, 1) ?></div>
    <div class="user-info">
      <div class="name"><?= e(Session::get('admin_name','Admin')) ?></div>
      <div class="role"><?= e(Session::get('admin_role','admin')) ?></div>
    </div>
  </div>
</div>
<!-- ===== END SIDEBAR ===== -->

<!-- ===== MAIN ===== -->
<div class="admin-main">
  <div class="topbar">
    <button class="topbar-btn" id="sidebar-toggle" onclick="document.getElementById('admin-sidebar').classList.toggle('open')" style="display:none">
      <i class="fas fa-bars"></i>
    </button>
    <h1><?= isset($pageTitle) ? e($pageTitle) : e($title ?? 'Dashboard') ?></h1>
    <span class="topbar-date"><i class="fas fa-calendar-alt"></i> <?= date('d/m/Y') ?></span>

    <?php if (!empty($topbarActions)): ?>
      <div class="topbar-actions"><?= $topbarActions ?></div>
    <?php endif; ?>
  </div>

  <div class="admin-content">
    <?= flash_messages() ?>
    <?= $content ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
