<?php
// app/views/home/index.php
// Các biến: $flashSale, $featured, $bestSeller, $brands
?>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-badge">
      <i class="fas fa-bolt"></i>
      🔥 Siêu Sale tháng <?= date('n/Y') ?>
    </div>
    <h1>Điện thoại xịn sò<br><span>Giá cực đỉnh</span></h1>
    <p class="hero-subtitle">
      Hàng chính hãng 100%, bảo hành 18 tháng, freeship toàn quốc.
      Trả góp 0% qua 32 ngân hàng &amp; ví điện tử.
    </p>
    <div class="hero-cta">
      <a href="<?= url('san-pham') ?>" class="btn-primary">
        <i class="fas fa-shopping-bag"></i> Mua ngay
      </a>
      <a href="#flash-sale" class="btn-secondary">
        <i class="fas fa-fire"></i> Xem Flash Sale
      </a>
    </div>
  </div>
  <div class="hero-phones">
    <div class="phone-mockup">
      <img src="<?= UPLOAD_URL ?>hero_iphone.jpg"
           alt="iPhone 15 Pro" loading="eager"
           onerror="this.src='https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=200&q=85'">
      <div class="phone-label">iPhone 15 Pro</div>
    </div>
    <div class="phone-mockup large">
      <img src="<?= UPLOAD_URL ?>hero_samsung.jpg"
           alt="Samsung S24 Ultra" loading="eager"
           onerror="this.src='https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=220&q=85'">
      <div class="phone-label">Samsung S24 Ultra</div>
    </div>
    <div class="phone-mockup">
      <img src="<?= UPLOAD_URL ?>hero_xiaomi.jpg"
           alt="Xiaomi 14 Ultra" loading="eager"
           onerror="this.src='https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=200&q=85'">
      <div class="phone-label">Xiaomi 14 Ultra</div>
    </div>
  </div>
</section>

<!-- ===== BRANDS ===== -->
<?php if (!empty($brands)): ?>
<section class="brands-section">
  <div class="brands-inner">
    <div class="brands-header">
      <span class="brands-eyebrow">Thương hiệu chính hãng</span>
      <a href="<?= url('san-pham') ?>" class="brands-see-all">
        Xem tất cả <i class="fas fa-arrow-right"></i>
      </a>
    </div>
    <div class="brands-track">
      <?php
      // SVG logo paths cho từng hãng (inline SVG chuyên nghiệp)
      $brandSVGs = [
        'apple' => [
          'svg' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.3.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>',
          'color' => '#1d1d1f', 'bg' => '#f5f5f7',
        ],
        'samsung' => [
          'svg' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M5.37 21.375C2.61 21.375 0 19.636 0 16.56v-9.12C0 4.364 2.61 2.625 5.37 2.625h13.26C21.39 2.625 24 4.364 24 7.44v9.12c0 3.076-2.61 4.815-5.37 4.815H5.37zm6.63-3.9c3.15 0 5.7-2.55 5.7-5.7S15.15 6.075 12 6.075 6.3 8.625 6.3 11.775s2.55 5.7 5.7 5.7zm0-9.45c2.07 0 3.75 1.68 3.75 3.75S14.07 15.525 12 15.525s-3.75-1.68-3.75-3.75S9.93 8.025 12 8.025z"/></svg>',
          'color' => '#1428A0', 'bg' => '#f0f4ff',
        ],
        'xiaomi' => [
          'svg' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M5.25 2.25h13.5A3 3 0 0 1 21.75 5.25v13.5a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3zm6.75 4.5H9.75v10.5H12V12h1.5a3 3 0 0 0 0-6H9.75v1.5H13.5a1.5 1.5 0 0 1 0 3H12V6.75z"/></svg>',
          'color' => '#FF6900', 'bg' => '#fff3eb',
        ],
        'oppo' => [
          'svg' => '<svg viewBox="0 0 100 40" fill="currentColor"><text x="50" y="30" font-size="28" font-weight="bold" text-anchor="middle" font-family="Arial">OPPO</text></svg>',
          'color' => '#1D8348', 'bg' => '#eafaf1',
        ],
        'vivo' => [
          'svg' => '<svg viewBox="0 0 100 40" fill="currentColor"><text x="50" y="30" font-size="28" font-weight="bold" text-anchor="middle" font-family="Arial">vivo</text></svg>',
          'color' => '#415FFF', 'bg' => '#eff1ff',
        ],
        'realme' => [
          'svg' => '<svg viewBox="0 0 100 40" fill="currentColor"><text x="50" y="30" font-size="24" font-weight="bold" text-anchor="middle" font-family="Arial">realme</text></svg>',
          'color' => '#F5A623', 'bg' => '#fffbf0',
        ],
        'nokia' => [
          'svg' => '<svg viewBox="0 0 100 40" fill="currentColor"><text x="50" y="30" font-size="26" font-weight="800" text-anchor="middle" font-family="Arial">NOKIA</text></svg>',
          'color' => '#005AFF', 'bg' => '#f0f5ff',
        ],
      ];
      foreach ($brands as $b):
        $slug  = $b['slug'];
        $brand = $brandSVGs[$slug] ?? null;
      ?>
      <a href="<?= url('hang/' . e($slug)) ?>" class="brand-pill"
         style="--brand-color:<?= $brand['color'] ?? '#3b82f6' ?>;--brand-bg:<?= $brand['bg'] ?? '#eff6ff' ?>">
        <div class="brand-pill-logo">
          <?php if (!empty($b['logo'])): ?>
            <img src="<?= img_url(e($b['logo'])) ?>" alt="<?= e($b['ten']) ?>">
          <?php elseif ($brand): ?>
            <span class="brand-svg" style="color:<?= $brand['color'] ?>">
              <?= $brand['svg'] ?>
            </span>
          <?php else: ?>
            <span style="font-size:1.4rem;font-weight:900;color:var(--brand-color)"><?= strtoupper(substr($b['ten'],0,2)) ?></span>
          <?php endif; ?>
        </div>
        <div class="brand-pill-info">
          <span class="brand-pill-name"><?= e($b['ten']) ?></span>
          <span class="brand-pill-sub">Chính hãng</span>
        </div>
        <i class="fas fa-chevron-right brand-pill-arrow"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== FLASH SALE ===== -->
<?php if (!empty($flashSale)): ?>
<section class="section" id="flash-sale">
  <!-- Banner đếm ngược -->
  <div class="flash-banner">
    <div class="flash-title">
      <i class="fas fa-bolt flash-icon"></i>
      FLASH SALE HÔM NAY
    </div>
    <div class="countdown" id="countdown">
      <div class="count-item"><div class="count-number" id="c-h">00</div><div class="count-label">Giờ</div></div>
      <div class="count-sep">:</div>
      <div class="count-item"><div class="count-number" id="c-m">00</div><div class="count-label">Phút</div></div>
      <div class="count-sep">:</div>
      <div class="count-item"><div class="count-number" id="c-s">00</div><div class="count-label">Giây</div></div>
    </div>
  </div>

  <div class="section-header">
    <h2 class="section-title">Flash Sale <span>đang HOT</span></h2>
    <a href="<?= url('san-pham?sort=gia_giam') ?>" class="link-more">
      Xem tất cả <i class="fas fa-arrow-right"></i>
    </a>
  </div>

  <div class="product-grid">
    <?php foreach ($flashSale as $sp): ?>
      <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ===== SẢN PHẨM NỔI BẬT ===== -->
<?php if (!empty($featured)): ?>
<section class="section" style="background:#fff; padding-top:3rem; padding-bottom:3rem;">
  <div class="section-header">
    <h2 class="section-title">Sản phẩm <span>nổi bật</span></h2>
    <a href="<?= url('san-pham') ?>" class="link-more">
      Xem tất cả <i class="fas fa-arrow-right"></i>
    </a>
  </div>
  <div class="product-grid">
    <?php foreach ($featured as $sp): ?>
      <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ===== BÁN CHẠY ===== -->
<?php if (!empty($bestSeller)): ?>
<section class="section">
  <div class="section-header">
    <h2 class="section-title">Bán chạy <span>nhất</span></h2>
    <a href="<?= url('san-pham?sort=ban_chay') ?>" class="link-more">
      Xem tất cả <i class="fas fa-arrow-right"></i>
    </a>
  </div>
  <div class="product-grid">
    <?php foreach ($bestSeller as $sp): ?>
      <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ===== TẤT CẢ SẢN PHẨM (hiện khi chưa có nổi bật / bán chạy) ===== -->
<?php
$showAll = empty($featured) && empty($bestSeller) && !empty($allProducts);
$showAllExtra = !empty($allProducts) && (empty($featured) || empty($bestSeller));
if ($showAll || $showAllExtra):
  // Lọc bỏ SP đã hiện ở featured/bestSeller
  $featuredIds    = array_column($featured   ?? [], 'id');
  $bestSellerIds  = array_column($bestSeller ?? [], 'id');
  $shownIds       = array_map('intval', array_merge($featuredIds, $bestSellerIds));
  $remainProducts = array_filter($allProducts ?? [], fn($p) => !in_array((int)($p['id'] ?? 0), $shownIds, true));
  if (!empty($remainProducts)):
?>
<section class="section" style="background:#fff;padding-top:3rem;padding-bottom:3rem">
  <div class="section-header">
    <h2 class="section-title">Sản phẩm <span>mới nhất</span></h2>
    <a href="<?= url('san-pham') ?>" class="link-more">
      Xem tất cả <i class="fas fa-arrow-right"></i>
    </a>
  </div>
  <div class="product-grid">
    <?php foreach ($remainProducts as $sp): ?>
      <?php include VIEW_PATH . 'layouts/product_card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; endif; ?>


<!-- ===== WHY CHOOSE US ===== -->
<section class="why-section">
  <div class="section-header" style="justify-content:center;margin-bottom:2.5rem">
    <h2 class="section-title">Tại sao chọn <span>PhoneZone?</span></h2>
  </div>
  <div class="why-grid">
    <?php
    $features = [
      ['fas fa-shield-alt',  'Chính hãng 100%',    'Tem nhập khẩu chính hãng, hóa đơn VAT đầy đủ, bảo hành từ nhà sản xuất.'],
      ['fas fa-rocket',      'Giao siêu tốc',       '2–4h nội thành HN & HCM, 24h toàn quốc. Freeship đơn từ 1 triệu.'],
      ['fas fa-undo',        'Đổi trả 45 ngày',     'Miễn phí đổi trả 45 ngày nếu lỗi nhà sản xuất. Dùng thử 7 ngày.'],
      ['fas fa-credit-card', 'Trả góp 0%',          '0% lãi suất qua 32 ngân hàng + MoMo, ZaloPay, VNPay. Duyệt 5 phút.'],
    ];
    foreach ($features as [$icon, $title, $desc]):
    ?>
    <div class="feature-card">
      <div class="feature-icon"><i class="<?= $icon ?>"></i></div>
      <div class="feature-title"><?= $title ?></div>
      <div class="feature-desc"><?= $desc ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Countdown JS -->
<script>
(function() {
  function pad(n) { return String(n).padStart(2,'0'); }
  function tick() {
    const now  = new Date();
    const end  = new Date(); end.setHours(23,59,59,0);
    const diff = end - now;
    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000)   / 1000);
    document.getElementById('c-h').textContent = pad(h);
    document.getElementById('c-m').textContent = pad(m);
    document.getElementById('c-s').textContent = pad(s);
  }
  tick();
  setInterval(tick, 1000);
})();
</script>