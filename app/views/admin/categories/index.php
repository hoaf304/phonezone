<?php // app/views/admin/categories/index.php ?>
<?php ob_start(); ?>
  <button class="topbar-btn primary" onclick="openModal('modal-add')">
    <i class="fas fa-plus"></i> Thêm danh mục
  </button>
<?php $topbarActions = ob_get_clean(); ?>

<!-- Bảng danh mục -->
<div class="table-card">
  <table>
    <thead><tr><th>#</th><th>Tên danh mục</th><th>Slug</th><th>Mô tả</th><th>Thứ tự</th><th>Sản phẩm</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
    <?php foreach ($categories as $cat): ?>
    <tr>
      <td style="color:var(--gray-400);font-size:.8rem">#<?= $cat['id'] ?></td>
      <td><strong style="color:var(--primary)"><?= e($cat['ten']) ?></strong></td>
      <td><code style="font-size:.75rem;background:var(--light);padding:.15rem .4rem;border-radius:4px;color:var(--accent)"><?= e($cat['slug']) ?></code></td>
      <td style="font-size:.82rem;color:var(--gray-500);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($cat['mo_ta'] ?? '—') ?></td>
      <td style="text-align:center;font-weight:600"><?= $cat['thu_tu'] ?></td>
      <td style="text-align:center"><span style="background:var(--accent-light);color:var(--accent);padding:.2rem .6rem;border-radius:10px;font-size:.78rem;font-weight:700"><?= $cat['so_san_pham'] ?></span></td>
      <td><?= $cat['an_hien'] ? '<span class="badge-status s-confirmed">Hiện</span>' : '<span class="badge-status s-canceled">Ẩn</span>' ?></td>
      <td>
        <div class="actions-td">
          <button class="act-btn edit" title="Sửa" onclick="openEdit(<?= htmlspecialchars(json_encode($cat), ENT_QUOTES) ?>)">
            <i class="fas fa-edit"></i>
          </button>
          <form method="POST" action="<?= url('admin/danh-muc/xoa/'.$cat['id']) ?>" style="display:inline"
                onsubmit="return confirm('Xóa danh mục này?')">
            <?= Session::csrfField() ?>
            <button type="submit" class="act-btn del" title="Xóa" <?= $cat['so_san_pham']>0?'disabled title="Có sản phẩm - không thể xóa"':'' ?>>
              <i class="fas fa-trash"></i>
            </button>
          </form>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($categories)): ?>
    <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--gray-400)">Chưa có danh mục nào</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal thêm -->
<div class="modal-overlay" id="modal-add" style="display:none" onclick="if(event.target===this)closeModal('modal-add')">
  <div class="modal-box">
    <div class="modal-head">
      <h3><i class="fas fa-plus" style="color:var(--accent)"></i> Thêm danh mục mới</h3>
      <button class="modal-close" onclick="closeModal('modal-add')"><i class="fas fa-times"></i></button>
    </div>
    <form action="<?= url('admin/danh-muc/them') ?>" method="POST">
      <?= Session::csrfField() ?>
      <div class="modal-body">
        <div class="form-group" style="margin-bottom:1rem">
          <label class="form-label">Tên danh mục <span style="color:var(--danger)">*</span></label>
          <input type="text" name="ten" class="form-input-a" required placeholder="VD: iPhone" oninput="genSlug(this)">
        </div>
        <div class="form-group" style="margin-bottom:1rem">
          <label class="form-label">Slug (URL)</label>
          <input type="text" name="slug" id="add-slug" class="form-input-a" placeholder="iphone">
        </div>
        <div class="form-grid-2" style="margin-bottom:1rem">
          <div class="form-group">
            <label class="form-label">Thứ tự hiển thị</label>
            <input type="number" name="thu_tu" class="form-input-a" value="0" min="0">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Mô tả</label>
          <textarea name="mo_ta" class="form-textarea-a" rows="2" placeholder="Mô tả ngắn về danh mục..."></textarea>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="topbar-btn" onclick="closeModal('modal-add')">Hủy</button>
        <button type="submit" class="topbar-btn primary"><i class="fas fa-save"></i> Lưu danh mục</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal sửa -->
<div class="modal-overlay" id="modal-edit" style="display:none" onclick="if(event.target===this)closeModal('modal-edit')">
  <div class="modal-box">
    <div class="modal-head">
      <h3><i class="fas fa-edit" style="color:var(--warning)"></i> Sửa danh mục</h3>
      <button class="modal-close" onclick="closeModal('modal-edit')"><i class="fas fa-times"></i></button>
    </div>
    <form id="edit-form" action="" method="POST">
      <?= Session::csrfField() ?>
      <div class="modal-body">
        <div class="form-group" style="margin-bottom:1rem">
          <label class="form-label">Tên danh mục <span style="color:var(--danger)">*</span></label>
          <input type="text" name="ten" id="edit-ten" class="form-input-a" required>
        </div>
        <div class="form-grid-2" style="margin-bottom:1rem">
          <div class="form-group">
            <label class="form-label">Thứ tự</label>
            <input type="number" name="thu_tu" id="edit-thu_tu" class="form-input-a" min="0">
          </div>
          <div class="form-group">
            <label class="form-label">Trạng thái</label>
            <select name="an_hien" id="edit-an_hien" class="form-select-a">
              <option value="1">Hiện</option>
              <option value="0">Ẩn</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Mô tả</label>
          <textarea name="mo_ta" id="edit-mo_ta" class="form-textarea-a" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="topbar-btn" onclick="closeModal('modal-edit')">Hủy</button>
        <button type="submit" class="topbar-btn primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id){ document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id){ document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.addEventListener('keydown',e=>{ if(e.key==='Escape') document.querySelectorAll('.modal-overlay').forEach(m=>{ m.style.display='none'; document.body.style.overflow=''; }); });

function genSlug(inp){
  const slug = document.getElementById('add-slug');
  slug.value = inp.value.toLowerCase()
    .replace(/[àáảãạăằắẳẵặâầấẩẫậ]/g,'a').replace(/[èéẻẽẹêềếểễệ]/g,'e')
    .replace(/[ìíỉĩị]/g,'i').replace(/[òóỏõọôồốổỗộơờớởỡợ]/g,'o')
    .replace(/[ùúủũụưừứửữự]/g,'u').replace(/[ỳýỷỹỵ]/g,'y').replace(/đ/g,'d')
    .replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim();
}

function openEdit(cat){
  const base = '<?= APP_URL ?>';
  document.getElementById('edit-form').action = base + '/admin/danh-muc/sua/' + cat.id;
  document.getElementById('edit-ten').value    = cat.ten || '';
  document.getElementById('edit-thu_tu').value = cat.thu_tu || 0;
  document.getElementById('edit-an_hien').value= cat.an_hien;
  document.getElementById('edit-mo_ta').value  = cat.mo_ta || '';
  openModal('modal-edit');
}
</script>
