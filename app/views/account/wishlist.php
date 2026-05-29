<?php
/**
 * app/views/account/wishlist.php
 * @var array  $user      Thông tin khách hàng
 * @var array  $items     Danh sách sản phẩm yêu thích
 * @var string $activeTab Tab đang active
 */
$user ??= []; $items ??= []; ?>

<div class="container"><div class="breadcrumb">
  <a href="<?= url() ?>">Trang chủ</a>
  <i class="fas fa-chevron-right"></i>
  <a href="<?= url('tai-khoan') ?>">Tài khoản</a>
  <i class="fas fa-chevron-right"></i>
  <span>Yêu thích</span>
</div></div>

<div class="account-wrap">
  <?php include VIEW_PATH . 'account/_sidebar.php'; ?>

  <div>
    <div class="account-card">
      <div class="account-card-head">
        <h2><i class="fas fa-heart" style="color:var(--danger)"></i> Sản phẩm yêu thích</h2>
        <span style="font-size:.82rem;color:var(--gray-500)"><strong><?= count($items) ?></strong> sản phẩm</span>
      </div>
      <div class="account-card-body">
        <?php if (empty($items)): ?>
          <div class="wishlist-empty">
            <i class="fas fa-heart-broken" style="color:var(--gray-300)"></i>
            <h3 style="color:var(--gray-600);margin-bottom:.5rem">Chưa có sản phẩm yêu thích</h3>
            <p style="color:var(--gray-400);margin-bottom:1.25rem">Bấm vào ❤️ trên sản phẩm để thêm vào đây</p>
            <a href="<?= url('san-pham') ?>" class="btn btn-blue">
              <i class="fas fa-shopping-bag"></i> Khám phá sản phẩm
            </a>
          </div>
        <?php else: ?>
          <div class="wishlist-grid">
            <?php foreach ($items as $sp): ?>
              <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
// Override wishlist button trong trang này để xóa item ngay khi unlike
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.wishlist-btn');
  if (!btn) return;
  const card = btn.closest('.product-card');
  const id   = btn.dataset.id;

  fetch('<?= APP_URL ?>/yeu-thich/toggle', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({san_pham_id: id, csrf_token: getCSRF()})
  })
  .then(r=>r.json())
  .then(data => {
    if (data.success && !data.liked && card) {
      card.style.animation = 'fadeOut .3s ease forwards';
      setTimeout(() => { card.remove(); checkEmpty(); }, 300);
    }
  });
});

function checkEmpty() {
  const grid = document.querySelector('.wishlist-grid');
  if (grid && grid.children.length === 0) location.reload();
}
</script>
<style>
@keyframes fadeOut { to { opacity:0; transform:scale(.95); } }
</style>
