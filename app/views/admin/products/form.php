<?php
/**
 * app/views/admin/products/form.php
 * @var array|null $product
 * @var array      $variants
 * @var array      $specs
 * @var array      $categories
 * @var array      $brands
 */
$product    ??= null;
$variants   ??= [];
$specs      ??= [];
$categories ??= [];
$brands     ??= [];
$isEdit = !empty($product);
?>
<?php ob_start(); ?>
  <a href="<?= url('admin/san-pham') ?>" class="topbar-btn"><i class="fas fa-arrow-left"></i> Quay lại</a>
  <button type="submit" form="product-form" class="topbar-btn primary"><i class="fas fa-save"></i> Lưu sản phẩm</button>
<?php $topbarActions = ob_get_clean(); ?>

<form id="product-form" method="POST"
      action="<?= $isEdit ? url('admin/san-pham/sua/'.$product['id']) : url('admin/san-pham/them') ?>"
      enctype="multipart/form-data">
<?= Session::csrfField() ?>

<div class="admin-form-layout">
  <!-- LEFT -->
  <div>
    <!-- Thông tin cơ bản -->
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-info-circle"></i><h3>Thông tin cơ bản</h3></div>
      <div class="form-body">
        <div class="form-group full" style="margin-bottom:1rem">
          <label class="form-label">Tên sản phẩm <span class="req">*</span></label>
          <input type="text" name="ten" class="form-input-a" required
                 value="<?= e($product['ten'] ?? '') ?>"
                 oninput="autoSlug(this.value)" placeholder="VD: iPhone 15 Pro Max 256GB">
        </div>
        <div class="form-grid-2" style="margin-bottom:1rem">
          <div>
            <label class="form-label">Danh mục <span class="req">*</span></label>
            <select name="danh_muc_id" class="form-select-a" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($product['danh_muc_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                  <?= e($cat['ten']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label">Hãng sản xuất <span class="req">*</span></label>
            <select name="hang_id" class="form-select-a" required>
              <?php foreach ($brands as $b): ?>
                <option value="<?= $b['id'] ?>" <?= ($product['hang_id'] ?? 0) == $b['id'] ? 'selected' : '' ?>>
                  <?= e($b['ten']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div style="margin-bottom:1rem">
          <label class="form-label">Slug (URL)</label>
          <input type="text" name="slug" id="inp-slug" class="form-input-a"
                 value="<?= e($product['slug'] ?? '') ?>" placeholder="tu-dong-tao-tu-ten">
        </div>
        <div style="margin-bottom:1rem">
          <label class="form-label">Mô tả ngắn</label>
          <input type="text" name="mo_ta_ngan" class="form-input-a"
                 value="<?= e($product['mo_ta_ngan'] ?? '') ?>" placeholder="Hiển thị trong card sản phẩm...">
        </div>
        <div>
          <label class="form-label">Mô tả chi tiết</label>
          <textarea name="mo_ta_chi_tiet" class="form-textarea-a" rows="5"
                    placeholder="Nội dung mô tả đầy đủ..."><?= e($product['mo_ta_chi_tiet'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Thông số kỹ thuật -->
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-list"></i><h3>Thông số kỹ thuật</h3></div>
      <div class="form-body">
        <div class="specs-add">
          <input type="text" id="spec-ten-inp" class="form-input-a" placeholder="Tên (VD: Màn hình)">
          <input type="text" id="spec-gt-inp"  class="form-input-a" placeholder="Giá trị (VD: 6.7 inch OLED)">
          <button type="button" class="topbar-btn primary" onclick="addSpec()"><i class="fas fa-plus"></i> Thêm</button>
        </div>
        <div id="spec-list" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.75rem">
          <?php foreach ($specs as $i => $spec): ?>
          <div class="spec-tag">
            <input type="hidden" name="spec_ten[]"     value="<?= e($spec['ten_thong_so']) ?>">
            <input type="hidden" name="spec_gia_tri[]" value="<?= e($spec['gia_tri']) ?>">
            <span class="key"><?= e($spec['ten_thong_so']) ?>:</span> <?= e($spec['gia_tri']) ?>
            <button type="button" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- ===== BIẾN THỂ - dùng index cố định ===== -->
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-layer-group"></i><h3>Biến thể sản phẩm</h3></div>
      <div class="form-body">
        <div id="variant-list">
          <?php
          $variantsTpl = !empty($variants) ? $variants
            : [['mau_sac'=>'','dung_luong'=>'','gia_ban'=>'','gia_khuyen_mai'=>'','ton_kho'=>0,'sku'=>'']];
          foreach ($variantsTpl as $vi => $v):
          ?>
          <div class="variant-row" data-index="<?= $vi ?>">
            <div class="variant-row-grid">
              <div>
                <div class="variant-label">Màu sắc <span style="color:var(--danger)">*</span></div>
                <input type="text" name="variants[<?= $vi ?>][mau_sac]" class="variant-input"
                       value="<?= e($v['mau_sac']) ?>" placeholder="VD: Titan Đen" required>
              </div>
              <div>
                <div class="variant-label">Dung lượng</div>
                <input type="text" name="variants[<?= $vi ?>][dung_luong]" class="variant-input"
                       value="<?= e($v['dung_luong']) ?>" placeholder="VD: 256GB">
              </div>
              <div>
                <div class="variant-label">Giá bán (₫) <span style="color:var(--danger)">*</span></div>
                <input type="text" name="variants[<?= $vi ?>][gia_ban]" class="variant-input price-inp"
                       value="<?= e($v['gia_ban']) ?>" placeholder="VD: 28990000"
                       oninput="formatPrice(this)" required>
              </div>
              <div>
                <div class="variant-label">Giá KM (₫)</div>
                <input type="text" name="variants[<?= $vi ?>][gia_khuyen_mai]" class="variant-input price-inp"
                       value="<?= e($v['gia_khuyen_mai']) ?>" placeholder="Để trống nếu không KM"
                       oninput="formatPrice(this)">
              </div>
              <div>
                <div class="variant-label">Tồn kho</div>
                <input type="number" name="variants[<?= $vi ?>][ton_kho]" class="variant-input"
                       value="<?= (int)($v['ton_kho'] ?? 0) ?>" min="0" placeholder="0">
              </div>
              <div class="del-variant" onclick="removeVariant(this)" title="Xóa biến thể">
                <i class="fas fa-trash"></i>
              </div>
            </div>
            <div style="margin-top:.6rem;display:flex;align-items:center;gap:.5rem">
              <div class="variant-label" style="width:30px;flex-shrink:0">SKU</div>
              <input type="text" name="variants[<?= $vi ?>][sku]" class="variant-input"
                     style="flex:1;max-width:320px" value="<?= e($v['sku']) ?>"
                     placeholder="VD: APL-IP15PM-256-BLK">
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <button type="button" class="topbar-btn" onclick="addVariant()" style="margin-top:.75rem">
          <i class="fas fa-plus"></i> Thêm biến thể
        </button>

        <div style="margin-top:.75rem;padding:.75rem;background:var(--light);border-radius:8px;font-size:.8rem;color:var(--gray-500)">
          <i class="fas fa-info-circle" style="color:var(--accent)"></i>
          <strong>Lưu ý:</strong> Mỗi sản phẩm cần ít nhất <strong>1 biến thể</strong> với Màu sắc và Giá bán để hiển thị trên trang web.
        </div>

        <!-- NÚT DEBUG - xóa sau khi fix xong -->
        <div style="margin-top:.75rem">
          <button type="button" class="topbar-btn" style="background:#f59e0b;color:#fff;border-color:#f59e0b"
                  onclick="debugSubmit()">
            <i class="fas fa-bug"></i> Debug POST (xem PHP nhận gì)
          </button>
        </div>
      </div>
    </div>

    <!-- SEO -->
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-search"></i><h3>SEO</h3></div>
      <div class="form-body">
        <div style="margin-bottom:1rem">
          <label class="form-label">Meta Title</label>
          <input type="text" name="meta_title" class="form-input-a" value="<?= e($product['meta_title'] ?? '') ?>">
        </div>
        <div>
          <label class="form-label">Meta Description</label>
          <textarea name="meta_desc" class="form-textarea-a" rows="2"><?= e($product['meta_desc'] ?? '') ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT -->
  <div>
    <!-- Hình ảnh -->
    <div class="form-card">
      <div class="form-card-head"><i class="fas fa-image"></i><h3>Hình ảnh chính</h3></div>
      <div class="form-body">

        <!-- Tab chọn nguồn ảnh -->
        <div style="display:flex;border-bottom:1px solid var(--gray-200);margin-bottom:1rem">
          <button type="button" class="img-tab active" id="tab-upload" onclick="switchImgTab('upload')">
            <i class="fas fa-upload"></i> Upload từ máy
          </button>
          <button type="button" class="img-tab" id="tab-url" onclick="switchImgTab('url')">
            <i class="fas fa-link"></i> Nhập link URL
          </button>
        </div>

        <!-- Tab Upload -->
        <div id="panel-upload">
          <!-- Ảnh chính hiện tại -->
          <?php if (!empty($product['hinh_chinh'])): ?>
          <div style="margin-bottom:.75rem">
            <div style="font-size:.75rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem">Ảnh chính hiện tại</div>
            <div style="position:relative;display:inline-block">
              <img src="<?= img_url(e($product['hinh_chinh'])) ?>" id="current-main-img"
                   style="width:100%;max-height:180px;border-radius:10px;object-fit:contain;background:var(--light);border:1px solid var(--gray-200)">
              <span style="position:absolute;top:6px;left:6px;background:var(--accent);color:#fff;font-size:.65rem;font-weight:700;padding:.2rem .5rem;border-radius:5px">Ảnh chính</span>
            </div>
          </div>
          <?php endif; ?>

          <!-- Upload ảnh chính mới -->
          <div style="margin-bottom:1rem">
            <div style="font-size:.78rem;font-weight:600;color:var(--gray-600);margin-bottom:.4rem">
              <?= !empty($product['hinh_chinh']) ? 'Thay ảnh chính' : 'Chọn ảnh chính' ?>
              <span style="color:var(--danger)">*</span>
            </div>
            <label class="upload-zone" for="hinh_chinh" id="upload-zone"
                   style="padding:1.25rem;cursor:pointer">
              <i class="fas fa-cloud-upload-alt"></i>
              <p style="margin:.4rem 0 .2rem;font-size:.85rem">Click hoặc kéo thả ảnh vào đây</p>
              <small style="color:var(--gray-400)">JPG, PNG, WEBP – tối đa 5MB</small>
            </label>
            <input type="file" name="hinh_chinh" id="hinh_chinh"
                   accept="image/jpeg,image/png,image/webp,image/gif"
                   style="display:none" onchange="previewImg(this)">
            <img id="preview-img" style="display:none;width:100%;border-radius:10px;margin-top:.5rem;max-height:180px;object-fit:contain;background:var(--light);border:1px solid var(--gray-200)">
          </div>

          <!-- Upload nhiều ảnh thư viện -->
          <div>
            <div style="font-size:.78rem;font-weight:600;color:var(--gray-600);margin-bottom:.4rem">
              Thư viện ảnh (tùy chọn)
            </div>
            <label class="upload-zone" for="hinh_thu_vien" style="padding:1rem;cursor:pointer">
              <i class="fas fa-images" style="font-size:1.5rem"></i>
              <p style="margin:.3rem 0 .1rem;font-size:.82rem">Chọn nhiều ảnh cùng lúc</p>
              <small style="color:var(--gray-400)">Tối đa 10 ảnh • Mỗi ảnh ≤ 5MB</small>
            </label>
            <input type="file" name="hinh_thu_vien[]" id="hinh_thu_vien"
                   accept="image/*" multiple style="display:none"
                   onchange="previewGallery(this)">
            <div id="gallery-preview" style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.5rem"></div>
          </div>
        </div>

        <!-- Tab URL -->
        <div id="panel-url" style="display:none">
          <div style="margin-bottom:.85rem">
            <div style="font-size:.78rem;font-weight:600;color:var(--gray-600);margin-bottom:.4rem">
              Link ảnh chính <span style="color:var(--danger)">*</span>
            </div>
            <div style="display:flex;gap:.5rem">
              <input type="text" id="url-main-input" class="form-input-a"
                     placeholder="https://example.com/image.jpg"
                     oninput="debouncePreviewUrl(this.value, 'url-main-preview')">
              <button type="button" class="topbar-btn" onclick="applyMainUrl()">
                <i class="fas fa-check"></i> Dùng ảnh này
              </button>
            </div>
            <!-- Hidden input lưu URL ảnh chính -->
            <input type="hidden" name="hinh_chinh_url" id="hinh_chinh_url"
                   value="<?= e($product['hinh_chinh'] ?? '') ?>">
            <img id="url-main-preview"
                 style="display:none;width:100%;max-height:180px;border-radius:10px;margin-top:.5rem;object-fit:contain;background:var(--light);border:1px solid var(--gray-200)">
            <div id="url-main-error" style="display:none;font-size:.78rem;color:var(--danger);margin-top:.3rem">
              ⚠️ Không tải được ảnh từ URL này
            </div>
          </div>

          <!-- Thêm nhiều URL ảnh thư viện -->
          <div>
            <div style="font-size:.78rem;font-weight:600;color:var(--gray-600);margin-bottom:.4rem">
              Thêm ảnh thư viện qua link
            </div>
            <div style="display:flex;gap:.5rem;margin-bottom:.5rem">
              <input type="text" id="url-gallery-input" class="form-input-a"
                     placeholder="https://example.com/image2.jpg">
              <button type="button" class="topbar-btn primary" onclick="addUrlToGallery()">
                <i class="fas fa-plus"></i> Thêm
              </button>
            </div>
            <div id="url-gallery-list" style="display:flex;flex-direction:column;gap:.4rem"></div>
          </div>

          <!-- Gợi ý nguồn ảnh -->
          <div style="margin-top:1rem;padding:.75rem;background:var(--light);border-radius:8px;font-size:.75rem;color:var(--gray-500)">
            <i class="fas fa-lightbulb" style="color:var(--warning)"></i>
            <strong>Nguồn ảnh miễn phí:</strong>
            <a href="https://unsplash.com" target="_blank" style="color:var(--accent)">Unsplash</a> ·
            <a href="https://pexels.com"   target="_blank" style="color:var(--accent)">Pexels</a> ·
            Hoặc copy link ảnh từ Google Images
          </div>
        </div>

        <!-- Ảnh thư viện hiện có (khi sửa) -->
        <?php if ($isEdit && !empty($images)): ?>
        <div style="margin-top:1rem">
          <div style="font-size:.75rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.6rem">
            Ảnh thư viện hiện có (<?= count($images) ?>)
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:.5rem" id="existing-gallery">
            <?php foreach ($images as $img): ?>
            <div style="position:relative;width:70px;height:70px" class="existing-img" data-id="<?= $img['id'] ?>">
              <img src="<?= img_url(e($img['url'])) ?>"
                   style="width:70px;height:70px;border-radius:8px;object-fit:contain;background:var(--light);border:1px solid var(--gray-200)">
              <button type="button" onclick="removeExistingImg(this, <?= $img['id'] ?>)"
                      style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:var(--danger);color:#fff;border:none;cursor:pointer;font-size:.6rem;display:flex;align-items:center;justify-content:center">
                <i class="fas fa-times"></i>
              </button>
              <input type="hidden" name="delete_images[]" value="" id="del-img-<?= $img['id'] ?>">
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>

    <!-- Tùy chọn -->
    <div class="form-card" style="margin-top:1rem">
      <div class="form-card-head"><i class="fas fa-cog"></i><h3>Tùy chọn hiển thị</h3></div>
      <div class="form-body" style="display:flex;flex-direction:column;gap:.85rem">
        <?php foreach ([
          ['an_hien',  'Hiển thị sản phẩm trên web', 1],
          ['noi_bat',  'Sản phẩm nổi bật (trang chủ)', 0],
          ['ban_chay', 'Đánh dấu bán chạy', 0],
        ] as [$name, $label, $default]):
          $checked = isset($product[$name]) ? (int)$product[$name] : $default;
        ?>
        <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;padding:.5rem .75rem;border-radius:8px;border:1px solid var(--gray-200);transition:background .2s"
               onmouseover="this.style.background='var(--light)'" onmouseout="this.style.background=''">
          <input type="checkbox" name="<?= $name ?>" value="1" <?= $checked ? 'checked' : '' ?>
                 style="accent-color:var(--accent);width:16px;height:16px;flex-shrink:0">
          <span style="font-size:.88rem;font-weight:500;color:var(--gray-700)"><?= $label ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Thông tin nhanh (chỉ khi sửa) -->
    <?php if ($isEdit): ?>
    <div class="form-card" style="margin-top:1rem">
      <div class="form-card-head"><i class="fas fa-info"></i><h3>Thông tin</h3></div>
      <div class="form-body" style="font-size:.82rem;display:flex;flex-direction:column;gap:.5rem">
        <div style="display:flex;justify-content:space-between;padding:.3rem 0;border-bottom:1px solid var(--gray-100)">
          <span style="color:var(--gray-500)">ID sản phẩm</span>
          <strong>#<?= $product['id'] ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:.3rem 0;border-bottom:1px solid var(--gray-100)">
          <span style="color:var(--gray-500)">Lượt xem</span>
          <strong><?= number_format($product['luot_xem']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:.3rem 0;border-bottom:1px solid var(--gray-100)">
          <span style="color:var(--gray-500)">Ngày tạo</span>
          <strong><?= format_date($product['created_at']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:.3rem 0">
          <span style="color:var(--gray-500)">Cập nhật</span>
          <strong><?= format_date($product['updated_at']) ?></strong>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
</form>

<script>
let variantIndex = <?= count(!empty($variants) ? $variants : [['']]) ?>;

// ---- Auto slug ----
function autoSlug(val) {
  const slugEl = document.getElementById('inp-slug');
  if (slugEl.dataset.manual) return;
  slugEl.value = val.toLowerCase()
    .replace(/[àáảãạăằắẳẵặâầấẩẫậ]/g,'a').replace(/[èéẻẽẹêềếểễệ]/g,'e')
    .replace(/[ìíỉĩị]/g,'i').replace(/[òóỏõọôồốổỗộơờớởỡợ]/g,'o')
    .replace(/[ùúủũụưừứửữự]/g,'u').replace(/[ỳýỷỹỵ]/g,'y').replace(/đ/g,'d')
    .replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim();
}
document.getElementById('inp-slug')?.addEventListener('input', function(){ this.dataset.manual = '1'; });

// ---- Thêm biến thể mới với index đúng ----
function addVariant() {
  const idx = variantIndex++;
  const html = `
    <div class="variant-row" data-index="${idx}" style="animation:fadeInUp .3s ease">
      <div class="variant-row-grid">
        <div><div class="variant-label">Màu sắc <span style="color:var(--danger)">*</span></div>
          <input type="text" name="variants[${idx}][mau_sac]" class="variant-input" placeholder="VD: Titan Đen" required></div>
        <div><div class="variant-label">Dung lượng</div>
          <input type="text" name="variants[${idx}][dung_luong]" class="variant-input" placeholder="VD: 256GB"></div>
        <div><div class="variant-label">Giá bán (₫) <span style="color:var(--danger)">*</span></div>
          <input type="text" name="variants[${idx}][gia_ban]" class="variant-input price-inp" placeholder="VD: 28990000" oninput="formatPrice(this)" required></div>
        <div><div class="variant-label">Giá KM (₫)</div>
          <input type="text" name="variants[${idx}][gia_khuyen_mai]" class="variant-input price-inp" placeholder="Để trống nếu không KM" oninput="formatPrice(this)"></div>
        <div><div class="variant-label">Tồn kho</div>
          <input type="number" name="variants[${idx}][ton_kho]" class="variant-input" value="0" min="0"></div>
        <div class="del-variant" onclick="removeVariant(this)" title="Xóa"><i class="fas fa-trash"></i></div>
      </div>
      <div style="margin-top:.6rem;display:flex;align-items:center;gap:.5rem">
        <div class="variant-label" style="width:30px;flex-shrink:0">SKU</div>
        <input type="text" name="variants[${idx}][sku]" class="variant-input" style="flex:1;max-width:320px" placeholder="VD: SP-MAU-DUNGLG">
      </div>
    </div>`;
  document.getElementById('variant-list').insertAdjacentHTML('beforeend', html);
}

function removeVariant(btn) {
  const list = document.getElementById('variant-list');
  if (list.querySelectorAll('.variant-row').length <= 1) {
    alert('Cần ít nhất 1 biến thể!');
    return;
  }
  btn.closest('.variant-row').remove();
}

// ---- Thêm thông số kỹ thuật ----
function addSpec() {
  const ten = document.getElementById('spec-ten-inp').value.trim();
  const gt  = document.getElementById('spec-gt-inp').value.trim();
  if (!ten || !gt) { alert('Vui lòng nhập cả tên và giá trị thông số!'); return; }
  const div = document.createElement('div');
  div.className = 'spec-tag';
  div.innerHTML = `<input type="hidden" name="spec_ten[]" value="${escHtml(ten)}">
    <input type="hidden" name="spec_gia_tri[]" value="${escHtml(gt)}">
    <span class="key">${escHtml(ten)}:</span> ${escHtml(gt)}
    <button type="button" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
  document.getElementById('spec-list').appendChild(div);
  document.getElementById('spec-ten-inp').value = '';
  document.getElementById('spec-gt-inp').value  = '';
  document.getElementById('spec-ten-inp').focus();
}

// ---- Preview ảnh ----
function previewImg(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const prev = document.getElementById('preview-img');
      prev.src = e.target.result;
      prev.style.display = 'block';
      const cur = document.getElementById('current-img');
      if (cur) cur.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// ---- Format giá (hiển thị dấu chấm phân cách) ----
function formatPrice(inp) {
  // Lưu giá trị thô không dấu để submit
  let raw = inp.value.replace(/[^\d]/g, '');
  inp.value = raw; // giữ số thuần để backend parse đúng
}

function escHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ---- Submit validation ----
document.getElementById('product-form').addEventListener('submit', function(e) {
  const rows = document.querySelectorAll('#variant-list .variant-row');
  let valid = true;
  rows.forEach(row => {
    const mau = row.querySelector('[name$="[mau_sac]"]');
    const gia = row.querySelector('[name$="[gia_ban]"]');
    if (!mau?.value.trim() || !gia?.value.trim()) {
      mau?.closest('.variant-row').scrollIntoView({behavior:'smooth'});
      valid = false;
    }
  });
  if (!valid) {
    e.preventDefault();
    alert('⚠️ Vui lòng điền Màu sắc và Giá bán cho tất cả biến thể!');
  }
});

// DEBUG: gửi form kèm flag __debug để PHP dump ra màn hình
function debugSubmit() {
  let dbg = document.getElementById('debug-flag');
  if (!dbg) {
    dbg = document.createElement('input');
    dbg.type = 'hidden'; dbg.name = '__debug'; dbg.id = 'debug-flag'; dbg.value = '1';
    document.getElementById('product-form').appendChild(dbg);
  }
  document.getElementById('product-form').submit();
}

// ============================================================
// IMAGE MANAGEMENT
// ============================================================

// -- Tab switch --
function switchImgTab(tab) {
  document.querySelectorAll('.img-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  document.getElementById('panel-upload').style.display = tab === 'upload' ? 'block' : 'none';
  document.getElementById('panel-url').style.display    = tab === 'url'    ? 'block' : 'none';
}

// -- Preview ảnh chính upload --
function previewImg(input) {
  if (!input.files || !input.files[0]) return;
  if (input.files[0].size > 5 * 1024 * 1024) {
    alert('⚠️ Ảnh quá lớn! Tối đa 5MB.'); input.value = ''; return;
  }
  const reader = new FileReader();
  reader.onload = e => {
    const prev = document.getElementById('preview-img');
    prev.src = e.target.result;
    prev.style.display = 'block';
    const cur = document.getElementById('current-main-img');
    if (cur) { cur.style.opacity = '.4'; cur.title = 'Sẽ bị thay thế'; }
  };
  reader.readAsDataURL(input.files[0]);
}

// -- Preview thư viện ảnh (upload nhiều) --
function previewGallery(input) {
  const container = document.getElementById('gallery-preview');
  container.innerHTML = '';
  if (!input.files) return;
  const files = Array.from(input.files).slice(0, 10);
  files.forEach((file, i) => {
    if (file.size > 5 * 1024 * 1024) return;
    const reader = new FileReader();
    reader.onload = e => {
      const wrap = document.createElement('div');
      wrap.style.cssText = 'position:relative;width:70px;height:70px';
      wrap.innerHTML = `
        <img src="${e.target.result}"
             style="width:70px;height:70px;border-radius:8px;object-fit:contain;background:var(--light);border:1px solid var(--gray-200)">
        <span style="position:absolute;bottom:2px;right:2px;background:rgba(0,0,0,.6);color:#fff;font-size:.6rem;padding:.1rem .3rem;border-radius:3px">${i+1}</span>`;
      container.appendChild(wrap);
    };
    reader.readAsDataURL(file);
  });
}

// -- Preview URL ảnh --
let urlTimer = null;
function debouncePreviewUrl(url, previewId) {
  clearTimeout(urlTimer);
  if (!url.trim()) return;
  urlTimer = setTimeout(() => previewUrl(url, previewId), 600);
}

function previewUrl(url, previewId) {
  const prev  = document.getElementById(previewId);
  const error = document.getElementById('url-main-error');
  if (!url.startsWith('http')) return;
  prev.style.display = 'none';
  const img = new Image();
  img.onload = () => {
    prev.src = url; prev.style.display = 'block';
    if (error) error.style.display = 'none';
  };
  img.onerror = () => {
    prev.style.display = 'none';
    if (error) error.style.display = 'block';
  };
  img.src = url;
}

function applyMainUrl() {
  const url = document.getElementById('url-main-input').value.trim();
  if (!url.startsWith('http')) { alert('Vui lòng nhập URL hợp lệ (bắt đầu bằng http)'); return; }
  document.getElementById('hinh_chinh_url').value = url;
  previewUrl(url, 'url-main-preview');
  alert('✅ Đã lưu link ảnh chính! Nhấn "Lưu sản phẩm" để áp dụng.');
}

// -- Thêm URL ảnh vào gallery --
let galleryUrls = [];
function addUrlToGallery() {
  const input = document.getElementById('url-gallery-input');
  const url   = input.value.trim();
  if (!url.startsWith('http')) { alert('URL phải bắt đầu bằng http'); return; }
  if (galleryUrls.includes(url)) { alert('URL này đã được thêm rồi!'); return; }
  if (galleryUrls.length >= 10) { alert('Tối đa 10 ảnh thư viện'); return; }

  galleryUrls.push(url);
  const idx      = galleryUrls.length - 1;
  const list     = document.getElementById('url-gallery-list');
  const item     = document.createElement('div');
  item.style.cssText = 'display:flex;align-items:center;gap:.5rem;padding:.4rem .6rem;background:var(--light);border-radius:8px;border:1px solid var(--gray-200)';
  item.innerHTML = `
    <img src="${url}" style="width:40px;height:40px;border-radius:6px;object-fit:contain;background:#fff;flex-shrink:0"
         onerror="this.src='';this.style.background='var(--danger-light)'">
    <span style="flex:1;font-size:.78rem;color:var(--gray-600);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${url}</span>
    <input type="hidden" name="gallery_urls[]" value="${escHtml(url)}">
    <button type="button" onclick="removeGalleryUrl(this,${idx})"
            style="width:24px;height:24px;border-radius:50%;background:var(--danger);color:#fff;border:none;cursor:pointer;font-size:.7rem;flex-shrink:0">
      <i class="fas fa-times"></i>
    </button>`;
  list.appendChild(item);
  input.value = '';
}

function removeGalleryUrl(btn, idx) {
  galleryUrls[idx] = null;
  btn.closest('div').remove();
}

// -- Xóa ảnh thư viện hiện có --
function removeExistingImg(btn, imgId) {
  const wrap = btn.closest('.existing-img');
  const delInput = document.getElementById('del-img-' + imgId);
  if (delInput) delInput.value = imgId; // đánh dấu xóa
  wrap.style.opacity = '.3';
  wrap.querySelector('img').style.filter = 'grayscale(1)';
  btn.style.background = 'var(--gray-400)';
  btn.onclick = null; btn.title = 'Đã đánh dấu xóa (lưu để áp dụng)';
}
</script>

<style>
.img-tab {
  padding: .55rem 1.1rem;
  font-size: .82rem; font-weight: 600;
  color: var(--gray-500); background: none; border: none;
  cursor: pointer; border-bottom: 2px solid transparent;
  margin-bottom: -1px; transition: all .2s;
  display: flex; align-items: center; gap: .4rem;
}
.img-tab:hover  { color: var(--accent); }
.img-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
</style>


