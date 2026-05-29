<?php // app/views/admin/customers/index.php ?>
<div class="admin-filter-bar">
  <form method="GET" action="<?= url('admin/khach-hang') ?>" style="display:contents">
    <div class="admin-search">
      <i class="fas fa-search"></i>
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Tên, email, số điện thoại...">
    </div>
    <select name="status" class="filter-select" onchange="this.form.submit()">
      <option value="">Tất cả trạng thái</option>
      <option value="hoat_dong" <?= $status==='hoat_dong'?'selected':'' ?>>Hoạt động</option>
      <option value="bi_khoa"   <?= $status==='bi_khoa'  ?'selected':'' ?>>Bị khóa</option>
    </select>
    <button type="submit" class="topbar-btn"><i class="fas fa-search"></i> Tìm</button>
    <a href="<?= url('admin/khach-hang') ?>" class="topbar-btn">Reset</a>
    <span style="margin-left:auto;font-size:.82rem;color:var(--gray-500)">Tổng <strong><?= number_format($pager['total']) ?></strong> khách hàng</span>
  </form>
</div>

<div class="table-card">
  <table>
    <thead><tr>
      <th>#</th><th>Khách hàng</th><th>Email</th><th>SĐT</th>
      <th>Số đơn</th><th>Tổng chi</th><th>Trạng thái</th><th>Ngày đăng ký</th><th>Thao tác</th>
    </tr></thead>
    <tbody>
    <?php if (empty($customers)): ?>
      <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--gray-400)">Không tìm thấy khách hàng nào</td></tr>
    <?php else: ?>
    <?php foreach ($customers as $kh): ?>
    <tr>
      <td style="color:var(--gray-400);font-size:.8rem">#<?= $kh['id'] ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:.6rem">
          <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-hover));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0">
            <?= mb_substr($kh['ho_ten'],0,1,'UTF-8') ?>
          </div>
          <span style="font-weight:600;color:var(--primary)"><?= e($kh['ho_ten']) ?></span>
        </div>
      </td>
      <td style="font-size:.82rem"><?= e($kh['email']) ?></td>
      <td style="font-size:.82rem"><?= e($kh['so_dien_thoai'] ?? '—') ?></td>
      <td style="text-align:center;font-weight:700;color:var(--accent)"><?= $kh['so_don'] ?></td>
      <td><span class="price-td"><?= format_price($kh['tong_chi']) ?></span></td>
      <td>
        <?= $kh['trang_thai'] === 'hoat_dong'
          ? '<span class="badge-status s-confirmed">Hoạt động</span>'
          : '<span class="badge-status s-canceled">Bị khóa</span>' ?>
      </td>
      <td style="font-size:.78rem;color:var(--gray-400)"><?= format_date($kh['created_at'],'d/m/Y') ?></td>
      <td>
        <div class="actions-td">
          <a href="<?= url('admin/khach-hang/'.$kh['id']) ?>" class="act-btn view" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?= render_pagination($pager, url('admin/khach-hang').'?q='.urlencode($q).'&status='.urlencode($status)) ?>
