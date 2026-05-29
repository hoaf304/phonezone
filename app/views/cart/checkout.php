<?php
/**
 * @var array $items     Sản phẩm trong giỏ
 * @var array $totals    Tổng tiền
 * @var array|null $coupon
 * @var int   $giamMa
 * @var array $addresses Địa chỉ đã lưu
 */
$items ??= []; $totals ??= []; $coupon ??= null; $giamMa ??= 0; $addresses ??= [];
$phuongThucList = [
  'cod'          => ['icon'=>'fas fa-money-bill-wave','cls'=>'pay-cod', 'ten'=>'Thanh toán khi nhận hàng (COD)','mo_ta'=>'Trả tiền mặt khi nhận hàng. Miễn phí, toàn quốc.'],
  'momo'         => ['icon'=>'fas fa-qrcode',         'cls'=>'pay-momo','ten'=>'Ví MoMo',                       'mo_ta'=>'Quét QR MoMo thanh toán ngay. Xác nhận tức thì.'],
  'chuyen_khoan' => ['icon'=>'fas fa-university',     'cls'=>'pay-bank','ten'=>'Chuyển khoản ngân hàng',        'mo_ta'=>'Chuyển khoản tới tài khoản PhoneZone. Xử lý trong 1h.'],
  'vnpay'        => ['icon'=>'fas fa-credit-card',    'cls'=>'pay-vnpay','ten'=>'VNPay / Thẻ ATM / Visa',       'mo_ta'=>'Thanh toán qua cổng VNPay, hỗ trợ ATM, Visa, MasterCard.'],
];
?>

<!-- Stepper -->
<div class="stepper">
  <div class="stepper-inner">
    <div class="step done"><div class="step-circle"><i class="fas fa-check"></i></div><span class="step-label">Giỏ hàng</span></div>
    <div class="step-line done"></div>
    <div class="step active"><div class="step-circle">2</div><span class="step-label">Địa chỉ & Thanh toán</span></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">3</div><span class="step-label">Xác nhận</span></div>
  </div>
</div>

<form action="<?= url('dat-hang/xu-ly') ?>" method="POST" id="checkout-form">
<?= Session::csrfField() ?>

<div class="checkout-layout">
  <div>

    <!-- ===== ĐỊA CHỈ GIAO HÀNG ===== -->
    <div class="form-card">
      <div class="form-card-head">
        <div style="width:32px;height:32px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;font-weight:700;flex-shrink:0">1</div>
        <h2>Địa chỉ giao hàng</h2>
      </div>
      <div class="form-body">

        <!-- Hidden inputs thực sự submit - LUÔN tồn tại -->
        <input type="hidden" name="ten_nguoi_nhan" id="hid-ten">
        <input type="hidden" name="so_dien_thoai"  id="hid-sdt">
        <input type="hidden" name="tinh_thanh"     id="hid-tinh">
        <input type="hidden" name="quan_huyen"     id="hid-quan">
        <input type="hidden" name="phuong_xa"      id="hid-phuong">
        <input type="hidden" name="so_nha_duong"   id="hid-sonha">

        <?php if (!empty($addresses)): ?>
        <!-- Địa chỉ đã lưu -->
        <div id="saved-addresses" style="margin-bottom:1.25rem">
          <?php foreach ($addresses as $i => $addr): ?>
          <div class="addr-item <?= ($addr['la_mac_dinh'] || $i===0) ? 'active' : '' ?>"
               onclick="selectAddr(this)"
               data-ten="<?= e($addr['ho_ten']) ?>"
               data-sdt="<?= e($addr['so_dien_thoai']) ?>"
               data-tinh="<?= e($addr['tinh_thanh']) ?>"
               data-quan="<?= e($addr['quan_huyen']) ?>"
               data-phuong="<?= e($addr['phuong_xa']) ?>"
               data-sonha="<?= e($addr['so_nha_duong']) ?>">
            <div style="display:flex;align-items:center;gap:.75rem">
              <div style="width:20px;height:20px;border-radius:50%;border:2px solid var(--accent);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <div class="addr-dot" style="width:10px;height:10px;border-radius:50%;background:<?= ($addr['la_mac_dinh']||$i===0)?'var(--accent)':'transparent' ?>"></div>
              </div>
              <div style="flex:1">
                <div style="font-weight:700;color:var(--primary);font-size:.9rem">
                  <?= e($addr['ho_ten']) ?>
                  <?php if ($addr['la_mac_dinh']): ?>
                    <span style="background:var(--accent-light);color:var(--accent);font-size:.68rem;padding:.1rem .45rem;border-radius:8px;margin-left:.35rem;font-weight:600">Mặc định</span>
                  <?php endif; ?>
                </div>
                <div style="font-size:.82rem;color:var(--gray-500);margin-top:.15rem">
                  <?= e($addr['so_dien_thoai']) ?> &mdash;
                  <?= e($addr['so_nha_duong']) ?>, <?= e($addr['phuong_xa']) ?>, <?= e($addr['quan_huyen']) ?>, <?= e($addr['tinh_thanh']) ?>
                </div>
              </div>
              <button type="button" class="addr-edit-btn"
                      onclick="event.stopPropagation();editAddr(this.closest('.addr-item'))"
                      title="Chỉnh sửa">
                <i class="fas fa-edit"></i> Sửa
              </button>
            </div>
          </div>
          <?php endforeach; ?>
          <div class="addr-new-btn" onclick="openNewAddrForm()">
            <i class="fas fa-plus-circle"></i> Giao đến địa chỉ khác
          </div>
        </div>
        <?php endif; ?>

        <!-- Form nhập/sửa địa chỉ -->
        <div id="addr-form" style="<?= !empty($addresses) ? 'display:none' : '' ?>">
          <?php if (!empty($addresses)): ?>
          <div style="font-size:.82rem;font-weight:600;color:var(--gray-600);margin-bottom:.85rem;padding-bottom:.75rem;border-bottom:1px solid var(--gray-100)">
            <i class="fas fa-edit" style="color:var(--accent)"></i>
            <span id="form-addr-title">Chỉnh sửa địa chỉ</span>
          </div>
          <?php endif; ?>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Họ và tên <span style="color:var(--danger)">*</span></label>
              <input type="text" id="edit-ten" class="form-input" placeholder="Nguyễn Văn A">
            </div>
            <div class="form-group">
              <label class="form-label">Số điện thoại <span style="color:var(--danger)">*</span></label>
              <input type="tel" id="edit-sdt" class="form-input" placeholder="0912 345 678">
            </div>
            <div class="form-group">
              <label class="form-label">Tỉnh / Thành phố <span style="color:var(--danger)">*</span></label>
              <input type="text" id="edit-tinh" class="form-input" placeholder="Hà Nội">
            </div>
            <div class="form-group">
              <label class="form-label">Quận / Huyện <span style="color:var(--danger)">*</span></label>
              <input type="text" id="edit-quan" class="form-input" placeholder="Quận Hai Bà Trưng">
            </div>
            <div class="form-group">
              <label class="form-label">Phường / Xã <span style="color:var(--danger)">*</span></label>
              <input type="text" id="edit-phuong" class="form-input" placeholder="Phường Bách Khoa">
            </div>
            <div class="form-group">
              <label class="form-label">Số nhà, tên đường <span style="color:var(--danger)">*</span></label>
              <input type="text" id="edit-sonha" class="form-input" placeholder="123 Phố Huế">
            </div>
          </div>
          <?php if (!empty($addresses)): ?>
          <div style="margin-top:.85rem;display:flex;gap:.75rem">
            <button type="button" class="btn btn-blue btn-sm" onclick="applyAddr()">
              <i class="fas fa-check"></i> Dùng địa chỉ này
            </button>
            <button type="button" class="btn btn-outline btn-sm" onclick="cancelEdit()">
              Hủy
            </button>
          </div>
          <?php endif; ?>
        </div>

        <div class="form-group" style="margin-top:1rem">
          <label class="form-label">Ghi chú cho đơn hàng</label>
          <textarea name="ghi_chu" class="form-textarea" rows="2"
                    placeholder="VD: Giao giờ hành chính, gọi trước khi giao..."></textarea>
        </div>
      </div>
    </div>

    <!-- ===== THANH TOÁN ===== -->
    <div class="form-card">
      <div class="form-card-head">
        <div style="width:32px;height:32px;background:var(--gray-200);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:var(--gray-600);flex-shrink:0">2</div>
        <h2>Phương thức thanh toán</h2>
      </div>
      <div class="form-body">

        <?php foreach ($phuongThucList as $key => $pt): ?>
        <label class="payment-item <?= $key==='cod'?'active':'' ?>" onclick="selectPayment(this)">
          <input type="radio" name="phuong_thuc_tt" value="<?= $key ?>" <?= $key==='cod'?'checked':'' ?>>
          <div class="payment-icon <?= $pt['cls'] ?>"><i class="<?= $pt['icon'] ?>"></i></div>
          <div class="payment-info">
            <div class="payment-name"><?= $pt['ten'] ?></div>
            <div class="payment-desc"><?= $pt['mo_ta'] ?></div>
          </div>
        </label>
        <?php endforeach; ?>

        <!-- MoMo QR box -->
        <div id="momo-box" style="display:none;margin-top:.75rem">
          <div style="background:linear-gradient(135deg,#fff0f6,#ffe4f0);border:1px solid #fbb6ce;border-radius:12px;padding:1.25rem;display:flex;gap:1.25rem;align-items:center;flex-wrap:wrap">
            <!-- QR Code thật - đặt ảnh vào public/uploads/momo_qr.jpg -->
            <div style="background:#fff;border-radius:10px;padding:.6rem;box-shadow:0 2px 8px rgba(0,0,0,.1);flex-shrink:0;width:122px;height:122px;display:flex;align-items:center;justify-content:center;overflow:hidden">
              <?php
              $qrPath = UPLOAD_PATH . 'momo_qr.jpg';
              $qrUrl  = UPLOAD_URL . 'momo_qr.jpg';
              if (file_exists($qrPath)):
              ?>
                <img src="<?= $qrUrl ?>" style="width:110px;height:110px;object-fit:contain">
              <?php else: ?>
                <!-- Placeholder khi chưa có ảnh QR -->
                <div style="text-align:center;color:#ae1b6f">
                  <i class="fas fa-qrcode" style="font-size:3rem;opacity:.3"></i>
                  <div style="font-size:.6rem;margin-top:.3rem;color:#ae1b6f;opacity:.5">Chưa có QR</div>
                </div>
              <?php endif; ?>
            </div>
            <div style="flex:1;min-width:180px">
              <div style="font-weight:700;color:#ae1b6f;font-size:.95rem;margin-bottom:.5rem">
                <i class="fas fa-mobile-alt"></i> Thanh toán qua MoMo
              </div>
              <div style="font-size:.82rem;color:#9d174d;line-height:1.8">
                <div>Mở app <strong>MoMo</strong> → Quét QR</div>
                <div>Hoặc chuyển đến: <strong style="font-size:.9rem">0364 663 178</strong></div>
                <div>Tên: <strong>TRINH THI HOA</strong></div>
                <div>Số tiền: <strong id="momo-amount" style="color:#ae1b6f"></strong></div>
              </div>
              <div style="margin-top:.6rem;padding:.5rem .75rem;background:rgba(174,27,111,.08);border-radius:8px;font-size:.75rem;color:#9d174d">
                <i class="fas fa-info-circle"></i>
                Ghi nội dung: <strong>DH [Họ tên] [SĐT]</strong> để xác nhận nhanh
              </div>
            </div>
          </div>
        </div>

        <!-- Chuyển khoản box -->
        <div class="bank-box" id="bank-info" style="display:none;margin-top:.75rem">
          <div style="display:flex;gap:1.25rem;align-items:flex-start;flex-wrap:wrap">

            <!-- QR ngân hàng -->
            <div style="background:#fff;border-radius:10px;padding:.6rem;box-shadow:0 2px 8px rgba(0,0,0,.1);flex-shrink:0;width:122px;height:122px;display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid #e2e8f0">
              <?php
              $bankQrPath = UPLOAD_PATH . 'bank_qr.jpg';
              $bankQrUrl  = UPLOAD_URL  . 'bank_qr.jpg';
              if (file_exists($bankQrPath)):
              ?>
                <img src="<?= $bankQrUrl ?>" style="width:110px;height:110px;object-fit:contain">
              <?php else: ?>
                <div style="text-align:center;color:#1d4ed8">
                  <i class="fas fa-qrcode" style="font-size:3rem;opacity:.3"></i>
                  <div style="font-size:.6rem;margin-top:.3rem;opacity:.5">Chưa có QR</div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Thông tin tài khoản -->
            <div style="flex:1;min-width:180px">
              <div class="bank-box-title" style="margin-bottom:.75rem">
                <i class="fas fa-university" style="color:var(--accent)"></i> Thông tin chuyển khoản
              </div>
              <div class="bank-row"><span class="lbl">Ngân hàng</span><span class="val">Ngân hàng TMCP Đầu tư & Phát triển Việt Nam (BIDV) </span></div>
              <div class="bank-row"><span class="lbl">Số tài khoản</span>
                <span class="val" style="display:flex;align-items:center;gap:.4rem">
                  5050601244
                  <button type="button" onclick="copyBankNum()"
                          style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;font-size:.7rem;font-weight:600;padding:.1rem .45rem;border-radius:5px;cursor:pointer;font-family:inherit"
                          id="copy-stk-btn">Copy</button>
                </span>
              </div>
              <div class="bank-row"><span class="lbl">Chủ tài khoản</span><span class="val">TRINH THI HOA</span></div>
              <div class="bank-row"><span class="lbl">Số tiền</span>
                <span class="val" style="color:var(--danger);font-size:.95rem">
                  <?= format_price($totals['tong']) ?>
                </span>
              </div>
              <div class="bank-row"><span class="lbl">Nội dung CK</span>
                <span class="val" id="ck-noidung" style="color:var(--accent)">DH [Họ tên] [SĐT]</span>
              </div>
              <div class="bank-note" style="margin-top:.6rem">
                <i class="fas fa-exclamation-circle"></i>
                Vui lòng chuyển khoản trong vòng <strong>24h</strong>. Đơn hàng tự hủy nếu quá hạn.
              </div>
            </div>
          </div>

          <!-- Hướng dẫn nhanh -->
          <div style="margin-top:.85rem;padding:.6rem .85rem;background:#eff6ff;border-radius:8px;font-size:.78rem;color:#1e40af;line-height:1.8">
            <strong>Quét QR nhanh:</strong> Mở app ngân hàng bất kỳ → Chuyển tiền → Quét QR → Kiểm tra số tiền → Xác nhận
          </div>
        </div>

      </div>
    </div>

  </div><!-- /left col -->

  <!-- ===== SUMMARY ===== -->
  <div>
    <div class="summary-card" style="position:sticky;top:90px">
      <div class="summary-title">Đơn hàng (<?= count($items) ?> sp)</div>

      <div style="margin-bottom:1rem;max-height:260px;overflow-y:auto">
        <?php foreach ($items as $item):
          $gia = (int)($item['gia_khuyen_mai'] > 0 ? $item['gia_khuyen_mai'] : $item['gia_ban']);
        ?>
        <div style="display:flex;gap:.75rem;align-items:center;padding:.6rem 0;border-bottom:1px solid var(--gray-100)">
          <div style="width:52px;height:52px;border-radius:8px;background:var(--gray-100);flex-shrink:0;display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--gray-200)">
            <?php if (!empty($item['hinh_chinh'])): ?>
              <img src="<?= img_url(e($item['hinh_chinh'])) ?>" style="width:100%;height:100%;object-fit:contain;padding:.2rem">
            <?php else: ?>
              <i class="fas fa-mobile-alt" style="color:var(--gray-300)"></i>
            <?php endif; ?>
          </div>
          <div style="flex:1;min-width:0">
            <div style="font-size:.82rem;font-weight:600;color:var(--primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($item['ten_san_pham']) ?></div>
            <div style="font-size:.72rem;color:var(--gray-400)"><?= e($item['mau_sac']) ?> / <?= e($item['dung_luong']) ?> × <?= $item['so_luong'] ?></div>
          </div>
          <div style="font-size:.88rem;font-weight:700;color:var(--danger);white-space:nowrap"><?= format_price($gia * $item['so_luong']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="summary-rows">
        <div class="summary-row">
          <span>Tạm tính</span>
          <span><?= format_price($totals['tam_tinh'] - $totals['giam_sp']) ?></span>
        </div>
        <?php if ($giamMa > 0): ?>
        <div class="summary-row">
          <span>Mã <?= e($coupon['ma']) ?></span>
          <span class="discount">−<?= format_price($giamMa) ?></span>
        </div>
        <?php endif; ?>
        <div class="summary-row">
          <span>Phí vận chuyển</span>
          <span><?= $totals['phi_ship'] > 0 ? format_price($totals['phi_ship']) : '<span style="color:var(--success);font-weight:600">Miễn phí</span>' ?></span>
        </div>
        <div class="summary-row total">
          <span>Tổng thanh toán</span>
          <span class="val" id="summary-total"><?= format_price($totals['tong']) ?></span>
        </div>
      </div>

      <button type="submit" class="btn-checkout" id="btn-checkout">
        <i class="fas fa-lock"></i> Xác nhận đặt hàng
      </button>
      <a href="<?= url('gio-hang') ?>"
         style="display:block;text-align:center;margin-top:1rem;font-size:.82rem;color:var(--gray-500)">
        ← Quay lại giỏ hàng
      </a>
    </div>
  </div>
</div>
</form>

<style>
/* Địa chỉ items */
.addr-item {
  border: 2px solid var(--gray-200);
  border-radius: 12px; padding: .85rem 1rem;
  margin-bottom: .6rem; cursor: pointer;
  transition: all .2s; position: relative;
}
.addr-item:hover { border-color: var(--accent); background: var(--accent-light); }
.addr-item.active { border-color: var(--accent); background: var(--accent-light); }
.addr-item.active .addr-dot { background: var(--accent) !important; }
.addr-edit-btn {
  background: #fff; border: 1px solid var(--gray-200);
  border-radius: 8px; padding: .35rem .65rem;
  font-size: .78rem; color: var(--gray-500); cursor: pointer;
  transition: all .2s; white-space: nowrap; flex-shrink: 0;
}
.addr-edit-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
.addr-new-btn {
  border: 2px dashed var(--gray-200);
  border-radius: 12px; padding: .75rem 1rem;
  font-size: .85rem; font-weight: 600; color: var(--accent);
  cursor: pointer; text-align: center; transition: all .2s; margin-top: .4rem;
}
.addr-new-btn:hover { border-color: var(--accent); background: var(--accent-light); }
</style>

<script>
function copyBankNum() {
  navigator.clipboard.writeText('1234567890012').then(() => {
    const btn = document.getElementById('copy-stk-btn');
    btn.textContent = '✓ Copied';
    btn.style.background = '#d1fae5';
    btn.style.color = '#065f46';
    setTimeout(() => { btn.textContent = 'Copy'; btn.style.background = '#eff6ff'; btn.style.color = '#1d4ed8'; }, 2000);
  });
}

const BASE = '<?= APP_URL ?>';
const totalAmount = <?= $totals['tong'] ?>;

// ============================================================
// ĐỊA CHỈ - dùng hidden inputs để submit, edit inputs để nhập
// ============================================================

// Ghi giá trị vào hidden inputs (đây là cái thực sự submit)
function setHiddenAddr(ten, sdt, tinh, quan, phuong, sonha) {
  document.getElementById('hid-ten').value    = ten;
  document.getElementById('hid-sdt').value    = sdt;
  document.getElementById('hid-tinh').value   = tinh;
  document.getElementById('hid-quan').value   = quan;
  document.getElementById('hid-phuong').value = phuong;
  document.getElementById('hid-sonha').value  = sonha;
}

// Điền vào ô edit (chỉ để nhìn/sửa)
function fillEditInputs(ten, sdt, tinh, quan, phuong, sonha) {
  document.getElementById('edit-ten').value    = ten;
  document.getElementById('edit-sdt').value    = sdt;
  document.getElementById('edit-tinh').value   = tinh;
  document.getElementById('edit-quan').value   = quan;
  document.getElementById('edit-phuong').value = phuong;
  document.getElementById('edit-sonha').value  = sonha;
}

// Chọn một địa chỉ đã lưu → update hidden + UI
function selectAddr(el) {
  // Bỏ active tất cả
  document.querySelectorAll('.addr-item').forEach(a => {
    a.classList.remove('active');
    const dot = a.querySelector('.addr-dot');
    if (dot) dot.style.background = 'transparent';
  });
  // Active item được chọn
  el.classList.add('active');
  const dot = el.querySelector('.addr-dot');
  if (dot) dot.style.background = 'var(--accent)';

  // Ghi vào hidden inputs ngay
  setHiddenAddr(
    el.dataset.ten, el.dataset.sdt,
    el.dataset.tinh, el.dataset.quan,
    el.dataset.phuong, el.dataset.sonha
  );

  // Đóng form sửa
  document.getElementById('addr-form').style.display = 'none';
}

// Mở form sửa với dữ liệu của địa chỉ được chọn
function editAddr(el) {
  fillEditInputs(
    el.dataset.ten, el.dataset.sdt,
    el.dataset.tinh, el.dataset.quan,
    el.dataset.phuong, el.dataset.sonha
  );
  document.getElementById('form-addr-title').textContent = 'Chỉnh sửa địa chỉ';
  document.getElementById('addr-form').style.display = 'block';
  document.getElementById('addr-form').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Mở form nhập địa chỉ mới (trống)
function openNewAddrForm() {
  fillEditInputs('', '', '', '', '', '');
  document.getElementById('form-addr-title').textContent = 'Nhập địa chỉ giao hàng mới';
  // Bỏ active tất cả địa chỉ đã lưu
  document.querySelectorAll('.addr-item').forEach(a => {
    a.classList.remove('active');
    const dot = a.querySelector('.addr-dot');
    if (dot) dot.style.background = 'transparent';
  });
  document.getElementById('addr-form').style.display = 'block';
  document.getElementById('addr-form').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Bấm "Dùng địa chỉ này" → lưu từ edit inputs → hidden inputs → đóng form
function applyAddr() {
  const ten    = document.getElementById('edit-ten').value.trim();
  const sdt    = document.getElementById('edit-sdt').value.trim();
  const tinh   = document.getElementById('edit-tinh').value.trim();
  const quan   = document.getElementById('edit-quan').value.trim();
  const phuong = document.getElementById('edit-phuong').value.trim();
  const sonha  = document.getElementById('edit-sonha').value.trim();

  // Validate cơ bản
  if (!ten || !sdt || !tinh || !quan || !phuong || !sonha) {
    alert('⚠️ Vui lòng điền đầy đủ tất cả các trường địa chỉ!');
    return;
  }

  // Ghi vào hidden inputs
  setHiddenAddr(ten, sdt, tinh, quan, phuong, sonha);

  // Hiển thị địa chỉ đang dùng lên giao diện (cập nhật thẻ địa chỉ active)
  const activeItem = document.querySelector('.addr-item.active');
  if (activeItem) {
    // Cập nhật data attributes và text hiển thị
    activeItem.dataset.ten    = ten;
    activeItem.dataset.sdt    = sdt;
    activeItem.dataset.tinh   = tinh;
    activeItem.dataset.quan   = quan;
    activeItem.dataset.phuong = phuong;
    activeItem.dataset.sonha  = sonha;
    // Cập nhật text hiển thị trong thẻ
    const nameEl = activeItem.querySelector('.font-weight-700, [style*="font-weight:700"]');
    const infoEl = activeItem.querySelectorAll('div[style*="color:var(--gray-500)"]');
    if (infoEl.length > 0) {
      infoEl[infoEl.length-1].textContent = sdt + ' — ' + sonha + ', ' + phuong + ', ' + quan + ', ' + tinh;
    }
  }

  // Đóng form + thông báo
  document.getElementById('addr-form').style.display = 'none';
  showToast('✅ Đã lưu địa chỉ giao hàng!', 'success');
}

// Hủy sửa → khôi phục hidden inputs về địa chỉ active đang dùng
function cancelEdit() {
  document.getElementById('addr-form').style.display = 'none';
  const active = document.querySelector('.addr-item.active');
  if (active) {
    // Khôi phục hidden inputs từ active item
    setHiddenAddr(
      active.dataset.ten, active.dataset.sdt,
      active.dataset.tinh, active.dataset.quan,
      active.dataset.phuong, active.dataset.sonha
    );
  }
}

// Tự động điền địa chỉ mặc định khi trang load
document.addEventListener('DOMContentLoaded', () => {
  const active = document.querySelector('.addr-item.active');
  if (active) {
    setHiddenAddr(
      active.dataset.ten, active.dataset.sdt,
      active.dataset.tinh, active.dataset.quan,
      active.dataset.phuong, active.dataset.sonha
    );
  }
});

// ============================================================
// THANH TOÁN
// ============================================================
function selectPayment(el) {
  document.querySelectorAll('.payment-item').forEach(x => x.classList.remove('active'));
  el.classList.add('active');
  const radio = el.querySelector('input[type=radio]');
  if (radio) radio.checked = true;
  const val = radio?.value;

  document.getElementById('bank-info').style.display  = val === 'chuyen_khoan' ? 'block' : 'none';
  const momoBox = document.getElementById('momo-box');
  if (val === 'momo') {
    momoBox.style.display = 'block';
    const amountEl = document.getElementById('momo-amount');
    if (amountEl) amountEl.textContent = new Intl.NumberFormat('vi-VN').format(totalAmount) + '₫';
  } else {
    momoBox.style.display = 'none';
  }
}

// Validate + submit
document.getElementById('checkout-form').addEventListener('submit', function(e) {
  // Nếu không có địa chỉ đã lưu → lấy từ edit inputs rồi ghi vào hidden
  const hasSaved = document.querySelector('.addr-item');
  if (!hasSaved) {
    // Không có địa chỉ lưu → lấy từ edit inputs
    setHiddenAddr(
      document.getElementById('edit-ten')?.value.trim()    || '',
      document.getElementById('edit-sdt')?.value.trim()    || '',
      document.getElementById('edit-tinh')?.value.trim()   || '',
      document.getElementById('edit-quan')?.value.trim()   || '',
      document.getElementById('edit-phuong')?.value.trim() || '',
      document.getElementById('edit-sonha')?.value.trim()  || ''
    );
  }

  // Validate hidden inputs
  const fields = [
    ['hid-ten',    'Họ và tên'],
    ['hid-sdt',    'Số điện thoại'],
    ['hid-tinh',   'Tỉnh / Thành phố'],
    ['hid-quan',   'Quận / Huyện'],
    ['hid-phuong', 'Phường / Xã'],
    ['hid-sonha',  'Số nhà, đường'],
  ];
  for (const [id, label] of fields) {
    if (!document.getElementById(id)?.value.trim()) {
      e.preventDefault();
      alert(`⚠️ Vui lòng điền "${label}" trong địa chỉ giao hàng!`);
      document.getElementById('addr-form').style.display = 'block';
      return;
    }
  }

  const btn = document.getElementById('btn-checkout');
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
  btn.disabled = true;
});
</script>
