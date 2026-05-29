<?php // app/views/admin/products/index.php ?>
<?php ob_start(); ?>
  <a href="<?= url('admin/san-pham/them') ?>" class="topbar-btn primary"><i class="fas fa-plus"></i> Thêm sản phẩm</a>
<?php $topbarActions = ob_get_clean(); ?>

<!-- Filter -->
<div class="admin-filter-bar">
  <form method="GET" action="<?= url('admin/san-pham') ?>" style="display:contents">
    <div class="admin-search">
      <i class="fas fa-search"></i>
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Tên sản phẩm, hãng...">
    </div>
    <select name="filter" class="filter-select" onchange="this.form.submit()">
      <option value="">Tất cả sản phẩm</option>
      <option value="low_stock" <?= $filter==='low_stock'?'selected':'' ?>>Sắp hết hàng (≤5)</option>
    </select>
    <button type="submit" class="topbar-btn"><i class="fas fa-search"></i> Lọc</button>
    <a href="<?= url('admin/san-pham') ?>" class="topbar-btn">Reset</a>
    <span style="margin-left:auto;font-size:.82rem;color:#64748b">
      Tổng <strong><?= number_format($pager['total']) ?></strong> sản phẩm
    </span>
  </form>
</div>

<div class="table-card">
  <table>
    <thead><tr>
      <th style="width:40px"><input type="checkbox" id="check-all" onclick="toggleAll(this)"></th>
      <th>Sản phẩm</th>
      <th>Danh mục</th>
      <th>Giá bán</th>
      <th>Tồn kho</th>
      <th>Trạng thái</th>
      <th>Ngày tạo</th>
      <th>Thao tác</th>
    </tr></thead>
    <tbody>
    <?php if (empty($products)): ?>
      <tr><td colspan="8" style="text-align:center;padding:2rem;color:#94a3b8">Không có sản phẩm nào</td></tr>
    <?php else: ?>
    <?php foreach ($products as $sp): ?>
    <tr>
      <td><input type="checkbox" class="row-check" value="<?= $sp['id'] ?>"></td>
      <td>
        <div style="display:flex;align-items:center;gap:.75rem">
          <div class="product-img-cell">
            <?php if (!empty($sp['hinh_chinh'])): ?>
              <img src="<?= img_url(e($sp['hinh_chinh'])) ?>" alt="">
            <?php else: ?>
              <i class="fas fa-mobile-alt" style="color:#cbd5e1"></i>
            <?php endif; ?>
          </div>
          <div class="product-name-cell">
            <div class="name"><?= e($sp['ten']) ?></div>
            <div class="meta"><?= e($sp['hang_ten']) ?> · <?= number_format($sp['luot_xem']) ?> lượt xem</div>
          </div>
        </div>
      </td>
      <td style="font-size:.82rem;color:#64748b"><?= e($sp['dm_ten']) ?></td>
      <td><span class="price-td"><?= format_price($sp['gia_hien_thi'] ?? 0) ?></span></td>
      <td>
        <?php $ton = (int)($sp['tong_ton'] ?? 0); $minTon = (int)($sp['min_ton'] ?? 0); ?>
        <span class="<?= $ton === 0 ? 'stock-out' : ($minTon <= 5 ? 'stock-low' : 'stock-ok') ?>">
          <?= $ton ?> sp <?= $minTon <= 5 && $ton > 0 ? '⚠️' : '' ?>
        </span>
      </td>
      <td>
        <?php if ($sp['an_hien']): ?>
          <span class="badge-status s-confirmed">Hiển thị</span>
        <?php else: ?>
          <span class="badge-status s-canceled">Đã ẩn</span>
        <?php endif; ?>
        <?php if ($sp['noi_bat']): ?><span class="badge-status s-shipping" style="margin-left:.25rem">Nổi bật</span><?php endif; ?>
      </td>
      <td style="font-size:.78rem;color:#94a3b8"><?= format_date($sp['created_at'],'d/m/Y') ?></td>
      <td>
        <div class="actions-td">
          <a href="<?= url('san-pham/' . e($sp['slug'])) ?>" target="_blank" class="act-btn view" title="Xem"><i class="fas fa-eye"></i></a>
          <a href="<?= url('admin/san-pham/sua/' . $sp['id']) ?>" class="act-btn edit" title="Sửa"><i class="fas fa-edit"></i></a>
          <form method="POST" action="<?= url('admin/san-pham/xoa/' . $sp['id']) ?>" style="display:inline"
                onsubmit="return confirm('Ẩn sản phẩm này?')">
            <?= Session::csrfField() ?>
            <button type="submit" class="act-btn del" title="Ẩn"><i class="fas fa-eye-slash"></i></button>
          </form>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<?php
$baseUrl = url('admin/san-pham') . '?q=' . urlencode($q) . '&filter=' . urlencode($filter);
echo render_pagination($pager, $baseUrl);
?>

<script>
function toggleAll(el) {
  document.querySelectorAll('.row-check').forEach(c => c.checked = el.checked);
}
</script>
