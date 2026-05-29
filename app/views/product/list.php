<?php
/**
 * app/views/product/list.php
 * @var array  $products    Danh sách sản phẩm
 * @var array  $pager       Phân trang
 * @var array  $filters     Bộ lọc hiện tại
 * @var array  $brands      Danh sách hãng
 * @var array  $categories  Danh sách danh mục
 * @var string $title       Tiêu đề
 */
$products   ??= []; $pager  ??= []; $filters ??= [];
$brands     ??= []; $title  ??= 'Sản phẩm';
$sortOptions = [
    'moi_nhat' => 'Mới nhất',
    'ban_chay' => 'Bán chạy',
    'gia_tang' => 'Giá tăng dần',
    'gia_giam' => 'Giá giảm dần',
    'danh_gia' => 'Đánh giá cao',
];
$currentSort = $filters['sort'] ?? 'moi_nhat';

// Giá preset nhanh
$pricePresets = [
    ['Dưới 5 triệu',    0,        4999000],
    ['5 – 10 triệu',    5000000,  9999000],
    ['10 – 20 triệu',   10000000, 19999000],
    ['Trên 20 triệu',   20000000, 0],
];
?>

<!-- Breadcrumb -->
<div class="container">
  <div class="breadcrumb">
    <a href="<?= url() ?>">Trang chủ</a>
    <i class="fas fa-chevron-right"></i>
    <a href="<?= url('san-pham') ?>">Sản phẩm</a>
    <?php if (!empty($currentCategory)): ?>
      <i class="fas fa-chevron-right"></i>
      <span><?= e($currentCategory['ten']) ?></span>
    <?php elseif (!empty($currentBrand)): ?>
      <i class="fas fa-chevron-right"></i>
      <span><?= e($currentBrand['ten']) ?></span>
    <?php elseif (!empty($filters['q'])): ?>
      <i class="fas fa-chevron-right"></i>
      <span>Tìm: "<?= e($filters['q']) ?>"</span>
    <?php endif; ?>
  </div>
</div>

<div class="page-layout">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <form method="GET" action="<?= url('san-pham') ?>" id="filter-form">
    <!-- Giữ các filter không thuộc sidebar -->
    <?php if (!empty($filters['q'])): ?>
      <input type="hidden" name="q" value="<?= e($filters['q']) ?>">
    <?php endif; ?>

    <div class="filter-card">

      <!-- Header -->
      <div class="filter-head">
        <h3><i class="fas fa-sliders-h" style="color:var(--accent);font-size:.9rem;margin-right:.4rem"></i> Bộ lọc</h3>
        <a href="<?= url('san-pham') . (!empty($filters['q']) ? '?q='.urlencode($filters['q']) : '') ?>"
           class="filter-reset">Xóa tất cả</a>
      </div>

      <!-- Hãng -->
      <div class="filter-section">
        <div class="filter-label">Hãng sản xuất</div>
        <?php foreach ($brands as $b): ?>
        <div class="brand-item">
          <input type="checkbox" name="hang" value="<?= e($b['slug']) ?>"
                 id="hang_<?= e($b['slug']) ?>"
                 <?= (($filters['hang'] ?? '') === $b['slug']) ? 'checked' : '' ?>
                 onchange="this.form.submit()">
          <label for="hang_<?= e($b['slug']) ?>"><?= e($b['ten']) ?></label>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Khoảng giá -->
      <div class="filter-section">
        <div class="filter-label">Khoảng giá</div>
        <!-- Preset nhanh -->
        <div style="display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem">
          <?php foreach ($pricePresets as [$label, $from, $to]):
            $isActive = ((string)($filters['gia_tu'] ?? '') === (string)$from && (string)($filters['gia_den'] ?? '') === (string)$to);
          ?>
          <div class="price-preset <?= $isActive ? 'active' : '' ?>"
               onclick="setPrice(<?= $from ?>, <?= $to ?>)">
            <?= $label ?>
          </div>
          <?php endforeach; ?>
        </div>
        <!-- Nhập tay -->
        <div style="font-size:.75rem;color:var(--gray-500);margin-bottom:.4rem">Hoặc nhập khoảng giá:</div>
        <div class="price-inputs">
          <input type="number" name="gia_tu" id="inp-gia-tu"
                 placeholder="Từ" value="<?= e($filters['gia_tu'] ?? '') ?>" min="0" step="500000">
          <span>–</span>
          <input type="number" name="gia_den" id="inp-gia-den"
                 placeholder="Đến" value="<?= e($filters['gia_den'] ?? '') ?>" min="0" step="500000">
        </div>
      </div>

      <!-- Đánh giá -->
      <div class="filter-section">
        <div class="filter-label">Đánh giá</div>
        <?php foreach ([5,4,3] as $sao):
          $isActive = ((int)($filters['sao'] ?? 0) == $sao);
        ?>
        <div class="star-item" onclick="setSao(<?= $sao ?>)"
             style="<?= $isActive ? 'background:var(--accent-light);border-radius:8px;padding-left:.5rem;' : '' ?>">
          <input type="radio" name="sao" value="<?= $sao ?>"
                 <?= $isActive ? 'checked' : '' ?> style="pointer-events:none">
          <span class="stars"><?= str_repeat('★', $sao) . str_repeat('☆', 5 - $sao) ?></span>
          <span class="star-lbl"><?= $sao ?> sao<?= $sao < 5 ? ' trở lên' : '' ?></span>
        </div>
        <?php endforeach; ?>
        <?php if (!empty($filters['sao'])): ?>
        <div style="margin-top:.5rem">
          <a href="#" onclick="clearSao(event)"
             style="font-size:.78rem;color:var(--accent)">Bỏ lọc sao</a>
        </div>
        <?php endif; ?>
      </div>

      <!-- Nút áp dụng -->
      <div class="filter-section">
        <button type="submit" class="btn-filter">
          <i class="fas fa-filter"></i> Áp dụng bộ lọc
        </button>
      </div>

    </div><!-- /.filter-card -->
    </form>
  </aside>

  <!-- ===== MAIN ===== -->
  <div class="main-content">

    <!-- Page title + mobile filter toggle -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
      <h1 style="font-size:1.2rem;font-weight:800;color:var(--primary)">
        <?= e($title) ?>
        <span style="font-size:.85rem;font-weight:500;color:var(--gray-400);margin-left:.5rem">
          (<?= number_format($pager['total'] ?? 0) ?> sản phẩm)
        </span>
      </h1>
      <button type="button" class="btn btn-outline btn-sm" id="toggle-filter"
              onclick="document.getElementById('sidebar').classList.toggle('open')"
              style="display:none">
        <i class="fas fa-sliders-h"></i> Lọc
      </button>
    </div>

    <!-- Active filters tags -->
    <?php
    $activeFilters = [];
    if (!empty($filters['hang']))   $activeFilters[] = ['Hãng: ' . ucfirst($filters['hang']),   'hang'];
    if (!empty($filters['gia_tu'])) $activeFilters[] = ['Từ: ' . format_price((int)$filters['gia_tu']), 'gia_tu'];
    if (!empty($filters['gia_den']))$activeFilters[] = ['Đến: ' . format_price((int)$filters['gia_den']),'gia_den'];
    if (!empty($filters['sao']))    $activeFilters[] = [($filters['sao'] ?? '') . ' sao trở lên', 'sao'];
    if (!empty($activeFilters)):
    ?>
    <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.85rem">
      <span style="font-size:.8rem;color:var(--gray-500);padding:.35rem 0">Đang lọc:</span>
      <?php foreach ($activeFilters as [$label, $key]): ?>
      <span class="filter-active-tag">
        <?= $label ?>
        <a href="<?= url('san-pham') . '?' . http_build_query(array_merge($filters, [$key => ''])) ?>"
           style="margin-left:.35rem;color:inherit;opacity:.6;font-weight:700">✕</a>
      </span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Sort bar -->
    <div class="sort-bar">
      <span class="sort-label">Sắp xếp:</span>
      <div class="sort-btns">
        <?php foreach ($sortOptions as $key => $label): ?>
          <a href="?<?= http_build_query(array_merge($filters, ['sort' => $key, 'page' => 1])) ?>"
             class="sort-btn <?= $currentSort === $key ? 'active' : '' ?>">
            <?= $label ?>
          </a>
        <?php endforeach; ?>
      </div>
      <span class="result-count" style="margin-left:auto">
        Tìm thấy <strong><?= number_format($pager['total'] ?? 0) ?></strong> sản phẩm
      </span>
    </div>

    <!-- Lưới sản phẩm -->
    <?php if (!empty($products)): ?>
      <div class="product-grid">
        <?php foreach ($products as $sp): ?>
          <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
        <?php endforeach; ?>
      </div>

      <!-- Phân trang -->
      <?php
        $queryParams = array_merge($filters, ['sort' => $currentSort]);
        unset($queryParams['page']);
        $paginationBase = url('san-pham') . '?' . http_build_query($queryParams);
        echo render_pagination($pager, $paginationBase);
      ?>

    <?php else: ?>
      <div style="text-align:center;padding:4rem 2rem;background:#fff;border-radius:16px;border:1px solid var(--gray-200)">
        <i class="fas fa-search" style="font-size:3rem;color:var(--gray-300);margin-bottom:1rem;display:block"></i>
        <h3 style="color:var(--gray-600);margin-bottom:.5rem">Không tìm thấy sản phẩm</h3>
        <p style="color:var(--gray-400);margin-bottom:1.5rem">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
        <a href="<?= url('san-pham') ?>" class="btn btn-blue">Xem tất cả sản phẩm</a>
      </div>
    <?php endif; ?>

  </div><!-- /.main-content -->
</div><!-- /.page-layout -->

<script>
function setPrice(from, to) {
  document.getElementById('inp-gia-tu').value = from || '';
  document.getElementById('inp-gia-den').value = to   || '';
  document.getElementById('filter-form').submit();
}
function setSao(val) {
  document.querySelector('[name=sao][value="'+val+'"]').checked = true;
  document.getElementById('filter-form').submit();
}
function clearSao(e) {
  e.preventDefault();
  const saoInput = document.querySelector('[name=sao]:checked');
  if (saoInput) saoInput.checked = false;
  // Thêm hidden input sao = '' để clear
  let h = document.getElementById('clear-sao');
  if (!h) { h=document.createElement('input'); h.type='hidden'; h.name='sao'; h.id='clear-sao'; document.getElementById('filter-form').appendChild(h); }
  h.value = '';
  document.getElementById('filter-form').submit();
}
// Hiện nút lọc mobile
if (window.innerWidth <= 768) document.getElementById('toggle-filter').style.display='flex';
window.addEventListener('resize', () => {
  document.getElementById('toggle-filter').style.display = window.innerWidth <= 768 ? 'flex' : 'none';
});
</script>