<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-grid">

      <!-- Cột 1: Giới thiệu -->
      <div>
        <a href="<?= url() ?>" class="footer-logo">Phone<span style="-webkit-text-fill-color:var(--accent)">Zone</span></a>
        <p class="footer-desc">Hệ thống bán lẻ điện thoại chính hãng #1 Việt Nam. Cam kết giá tốt nhất, dịch vụ 5 sao.</p>
        <div style="display:flex;gap:.75rem;margin-top:1.25rem">
          <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;transition:background .25s"
             onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;transition:background .25s"
             onmouseover="this.style.background='#e1306c'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" style="width:36px;height:36px;background:rgba(255,255,255,.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;transition:background .25s"
             onmouseover="this.style.background='#ff0000'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
            <i class="fab fa-youtube"></i>
          </a>
        </div>
      </div>

      <!-- Cột 2: Sản phẩm -->
      <div class="footer-col">
        <h3>Thương hiệu</h3>
        <ul class="footer-links">
          <li><a href="<?= url('hang/apple') ?>"    class="footer-link"><i class="fab fa-apple"></i> Apple iPhone</a></li>
          <li><a href="<?= url('hang/samsung') ?>"  class="footer-link"><i class="fab fa-android"></i> Samsung Galaxy</a></li>
          <li><a href="<?= url('hang/xiaomi') ?>"   class="footer-link"><i class="fas fa-leaf"></i> Xiaomi</a></li>
          <li><a href="<?= url('hang/oppo') ?>"     class="footer-link"><i class="fas fa-gem"></i> OPPO</a></li>
          <li><a href="<?= url('hang/vivo') ?>"     class="footer-link"><i class="fas fa-eye"></i> Vivo</a></li>
          <li><a href="<?= url('hang/realme') ?>"   class="footer-link"><i class="fas fa-star"></i> Realme</a></li>
        </ul>
      </div>

      <!-- Cột 3: Hỗ trợ -->
      <div class="footer-col">
        <h3>Hỗ trợ</h3>
        <ul class="footer-links">
          <li><a href="#" class="footer-link">Chính sách bảo hành</a></li>
          <li><a href="#" class="footer-link">Đổi trả & hoàn tiền</a></li>
          <li><a href="#" class="footer-link">Hướng dẫn mua hàng</a></li>
          <li><a href="#" class="footer-link">Phương thức thanh toán</a></li>
          <li><a href="#" class="footer-link">Câu hỏi thường gặp</a></li>
          <li><a href="<?= url('theo-doi-don-hang') ?>" class="footer-link">Theo dõi đơn hàng</a></li>
        </ul>
      </div>

      <!-- Cột 4: Liên hệ -->
      <div class="footer-col">
        <h3>Liên hệ</h3>
        <ul class="footer-links">
          <li><a href="tel:18006789" class="footer-link"><i class="fas fa-phone"></i> 1800.6789 (miễn phí)</a></li>
          <li><a href="mailto:support@phonezone.vn" class="footer-link"><i class="fas fa-envelope"></i> support@phonezone.vn</a></li>
          <li><a href="#" class="footer-link"><i class="fas fa-map-marker-alt"></i> Vĩnh Hưng, Hoàng Mai, Hà Nội</a></li>
          <li><a href="#" class="footer-link"><i class="fas fa-clock"></i> 8:00 – 22:00 mỗi ngày</a></li>
        </ul>
        <!-- Thanh toán -->
        <div style="margin-top:1.25rem">
          <p style="font-size:.78rem;color:rgba(255,255,255,.4);margin-bottom:.6rem">Thanh toán</p>
          <div style="display:flex;gap:.5rem;flex-wrap:wrap">
            <?php foreach(['COD','ATM','Visa','Momo','VNPay','ZaloPay'] as $p): ?>
            <span style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.7);font-size:.7rem;font-weight:600;padding:.25rem .6rem;border-radius:6px"><?= $p ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <span>© <?= date('Y') ?> <?= APP_NAME ?>. Đồ án tốt nghiệp – Trịnh Thị Hòa</span>
      <span>Trường Đại học Kinh doanh và Công nghệ Hà Nội</span>
    </div>
  </div>
</footer>
<!-- ===== END FOOTER ===== -->

<!-- JS -->
<script src="<?= asset('js/main.js') ?>"></script>
<?php if (isset($extraJs)): foreach ($extraJs as $js): ?>
<script src="<?= asset('js/' . $js) ?>"></script>
<?php endforeach; endif; ?>

</body>
</html>
