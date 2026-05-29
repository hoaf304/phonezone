<?php
/**
 * app/views/product/compare.php
 * Biến được truyền vào từ Controller::render() qua extract()
 *
 * @var array[] $products  Danh sách sản phẩm so sánh (mỗi phần tử có specs[], variants[])
 * @var string  $title     Tiêu đề trang
 */

// Đảm bảo $products luôn là array
$products ??= [];

// Group specs theo tên thông số chung
$allSpecKeys = [];
foreach ($products as $p) {
    foreach ($p['specs'] as $s) {
        if (!in_array($s['ten_thong_so'], $allSpecKeys)) {
            $allSpecKeys[] = $s['ten_thong_so'];
        }
    }
}
// Map spec theo sản phẩm
$specMap = [];
foreach ($products as $pi => $p) {
    foreach ($p['specs'] as $s) {
        $specMap[$s['ten_thong_so']][$pi] = $s['gia_tri'];
    }
}

// Nhóm spec theo section (5 spec đầu là tổng quan)
$sections = [
    'Tổng quan'   => ['Hệ điều hành','Thương hiệu','Năm ra mắt'],
    'Màn hình'    => ['Màn hình','Kích thước','Công nghệ màn hình','Độ phân giải','Tần số quét'],
    'Hiệu năng'   => ['Chip','RAM','Bộ nhớ','Dung lượng'],
    'Camera'      => ['Camera sau','Camera trước','Tính năng camera'],
    'Pin & Sạc'   => ['Pin','Sạc','Sạc không dây'],
    'Kết nối'     => ['Kết nối','Wi-Fi','Bluetooth','NFC','USB','5G'],
    'Thiết kế'    => ['Chất liệu','Kháng nước','Trọng lượng','Kích thước máy'],
];
?>

<!-- Breadcrumb -->
<div class="container">
  <div class="breadcrumb">
    <a href="<?= url() ?>">Trang chủ</a>
    <i class="fas fa-chevron-right"></i>
    <span>So sánh sản phẩm</span>
  </div>
</div>

<div class="compare-wrap">
  <div class="section-header" style="margin-bottom:1.5rem">
    <h2 class="section-title">So sánh <span><?= count($products) ?> sản phẩm</span></h2>
    <?php if (!empty($products)): ?>
      <a href="<?= url('so-sanh/xoa/all') ?>" class="link-more" style="color:var(--danger)">
        <i class="fas fa-trash-alt"></i> Xóa tất cả
      </a>
    <?php endif; ?>
  </div>

  <?php if (empty($products)): ?>
    <div class="compare-empty">
      <i class="fas fa-exchange-alt"></i>
      <h3 style="color:var(--gray-600);margin-bottom:.5rem">Chưa có sản phẩm để so sánh</h3>
      <p style="color:var(--gray-400);margin-bottom:1.25rem">Thêm tối đa 3 sản phẩm bằng nút <strong>So sánh</strong> trên card sản phẩm</p>
      <a href="<?= url('san-pham') ?>" class="btn btn-blue">
        <i class="fas fa-shopping-bag"></i> Chọn sản phẩm
      </a>
    </div>

  <?php else: ?>
  <div style="overflow-x:auto">
  <table class="compare-table">
    <colgroup>
      <col class="col-label">
      <?php foreach ($products as $_): ?><col class="col-product"><?php endforeach; ?>
      <?php if (count($products) < 3): ?><col class="col-product"><?php endif; ?>
    </colgroup>

    <!-- PRODUCT HEADERS -->
    <thead>
    <tr>
      <th style="background:var(--light)"></th>
      <?php foreach ($products as $idx => $p):
        $variants = $p['variants'] ?? [];
        $giaMin   = !empty($variants) ? min(array_map(fn($v) => (int)($v['gia_khuyen_mai'] > 0 ? $v['gia_khuyen_mai'] : $v['gia_ban']), $variants)) : 0;
        $giaBan   = !empty($variants) ? min(array_map(fn($v) => (int)$v['gia_ban'], $variants)) : 0;
      ?>
      <th>
        <div class="product-header">
          <div class="remove-btn" onclick="removeCompare(<?= $p['id'] ?>)" title="Xóa khỏi so sánh">
            <i class="fas fa-times"></i>
          </div>
          <div class="prod-img">
            <?php if (!empty($p['hinh_chinh'])): ?>
              <img src="<?= img_url(e($p['hinh_chinh'])) ?>" alt="<?= e($p['ten']) ?>">
            <?php else: ?>
              <i class="fas fa-mobile-alt" style="font-size:3rem;color:var(--gray-300)"></i>
            <?php endif; ?>
          </div>
          <div class="prod-brand"><?= e($p['hang_ten'] ?? '') ?></div>
          <div class="prod-name">
            <a href="<?= url('san-pham/' . e($p['slug'] ?? '')) ?>" style="color:inherit"><?= e($p['ten'] ?? '') ?></a>
          </div>
          <?php if ($giaMin > 0): ?>
            <div class="prod-price"><?= format_price($giaMin) ?></div>
            <?php if ($giaBan > $giaMin): ?>
              <div class="prod-price-old"><?= format_price($giaBan) ?></div>
            <?php endif; ?>
          <?php endif; ?>
          <button class="btn-buy-sm" data-id="<?= !empty($variants) ? $variants[0]['id'] : 0 ?>"
                  data-ten="<?= e($p['ten']) ?>" onclick="addToCart(this)">
            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
          </button>
        </div>
      </th>
      <?php endforeach; ?>

      <!-- Ô thêm sản phẩm nếu chưa đủ 3 -->
      <?php if (count($products) < 3): ?>
      <th>
        <div class="add-compare-box">
          <i class="fas fa-plus-circle"></i>
          <p>Thêm sản phẩm để so sánh</p>
          <a href="<?= url('san-pham') ?>" class="btn btn-outline btn-sm">Chọn sản phẩm</a>
        </div>
      </th>
      <?php endif; ?>
    </tr>
    </thead>

    <tbody>
    <?php
    // Render spec theo sections
    $renderedKeys = [];
    foreach ($sections as $sectionName => $keys) {
        $sectionRows = [];
        foreach ($keys as $key) {
            if (isset($specMap[$key])) $sectionRows[$key] = $specMap[$key];
        }
        if (empty($sectionRows)) continue;

        echo "<tr><td colspan=\"" . (count($products) + (count($products) < 3 ? 2 : 1)) . "\" class=\"section-label-cell\">$sectionName</td></tr>";

        foreach ($sectionRows as $specKey => $vals) {
            $renderedKeys[] = $specKey;
            echo '<tr>';
            echo "<td class=\"spec-label\">$specKey</td>";
            foreach ($products as $pi => $p) {
                $val = $vals[$pi] ?? '—';
                echo "<td class=\"spec-val\">" . e($val) . "</td>";
            }
            if (count($products) < 3) echo '<td></td>';
            echo '</tr>';
        }
    }

    // Các thông số còn lại chưa vào section
    $remaining = array_diff($allSpecKeys, $renderedKeys);
    if (!empty($remaining)) {
        echo "<tr><td colspan=\"" . (count($products) + (count($products) < 3 ? 2 : 1)) . "\" class=\"section-label-cell\">Thông số khác</td></tr>";
        foreach ($remaining as $specKey) {
            echo '<tr>';
            echo "<td class=\"spec-label\">$specKey</td>";
            foreach ($products as $pi => $p) {
                $val = $specMap[$specKey][$pi] ?? '—';
                echo "<td class=\"spec-val\">" . e($val) . "</td>";
            }
            if (count($products) < 3) echo '<td></td>';
            echo '</tr>';
        }
    }

    // Nếu không có thông số nào
    if (empty($allSpecKeys)):
    ?>
    <tr>
      <td class="spec-label">Lưu ý</td>
      <?php foreach ($products as $_): ?>
        <td class="spec-val" style="color:var(--gray-400);font-size:.82rem">Chưa có thông số kỹ thuật</td>
      <?php endforeach; ?>
      <?php if (count($products) < 3): ?><td></td><?php endif; ?>
    </tr>
    <?php endif; ?>

    <!-- Hành động -->
    <tr>
      <td class="spec-label" style="font-weight:700">Mua ngay</td>
      <?php foreach ($products as $p): ?>
        <td>
          <a href="<?= url('san-pham/' . e($p['slug'])) ?>" class="btn-buy-sm" style="text-decoration:none">
            <i class="fas fa-eye"></i> Xem sản phẩm
          </a>
        </td>
      <?php endforeach; ?>
      <?php if (count($products) < 3): ?><td></td><?php endif; ?>
    </tr>

    </tbody>
  </table>
  </div>
  <?php endif; ?>
</div>

<script>
function removeCompare(id) {
  fetch('<?= APP_URL ?>/so-sanh/xoa/' + id, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  }).then(() => location.reload());
}
</script>
