<?php // app/views/admin/reviews/index.php ?>
<!-- Status tabs -->
<div class="status-tabs" style="margin-bottom:1.25rem">
  <?php foreach ([''=>'Tất cả','cho_duyet'=>'Chờ duyệt','da_duyet'=>'Đã duyệt','an'=>'Đã ẩn'] as $k=>$v): ?>
  <a href="<?= url('admin/danh-gia') ?>?status=<?= $k ?>" class="status-tab <?= $status===$k?'active':'' ?>">
    <?= $v ?>
    <span class="tab-count"><?= $k ? ($counts[$k]??0) : array_sum($counts) ?></span>
  </a>
  <?php endforeach; ?>
</div>

<div class="table-card">
  <table>
    <thead><tr>
      <th>Khách hàng</th><th>Sản phẩm</th><th>Sao</th>
      <th>Nội dung</th><th>Trạng thái</th><th>Ngày</th><th>Thao tác</th>
    </tr></thead>
    <tbody>
    <?php if (empty($reviews)): ?>
      <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--gray-400)">Không có đánh giá nào</td></tr>
    <?php else: ?>
    <?php foreach ($reviews as $r): ?>
    <tr>
      <td>
        <div class="customer-cell">
          <div class="name"><?= e($r['ten_kh'] ?? $r['ho_ten']) ?></div>
          <div class="phone" style="font-size:.72rem"><?= e($r['email_kh'] ?? '') ?></div>
        </div>
      </td>
      <td>
        <a href="<?= url('san-pham/'.$r['slug_sp']) ?>" target="_blank"
           style="font-size:.82rem;color:var(--accent);font-weight:500;max-width:160px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
          <?= e($r['ten_sp'] ?? '—') ?>
        </a>
      </td>
      <td>
        <span style="color:#fbbf24;font-size:.9rem"><?= str_repeat('★',(int)$r['so_sao']) ?></span>
        <span style="font-weight:700;color:var(--primary);font-size:.82rem"> <?= $r['so_sao'] ?>/5</span>
      </td>
      <td style="max-width:220px">
        <?php if (!empty($r['tieu_de'])): ?>
          <div style="font-size:.82rem;font-weight:600;color:var(--primary)"><?= e($r['tieu_de']) ?></div>
        <?php endif; ?>
        <div style="font-size:.78rem;color:var(--gray-500);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px"><?= e($r['noi_dung']) ?></div>
      </td>
      <td>
        <?php $cls=['cho_duyet'=>'s-pending','da_duyet'=>'s-confirmed','an'=>'s-canceled']; ?>
        <?php $lbl=['cho_duyet'=>'Chờ duyệt','da_duyet'=>'Đã duyệt','an'=>'Đã ẩn']; ?>
        <span class="badge-status <?= $cls[$r['trang_thai']]??'s-pending' ?>"><?= $lbl[$r['trang_thai']]??$r['trang_thai'] ?></span>
      </td>
      <td style="font-size:.75rem;color:var(--gray-400)"><?= format_date($r['created_at'],'d/m/Y') ?></td>
      <td>
        <div class="actions-td">
          <?php if ($r['trang_thai'] === 'cho_duyet'): ?>
          <form method="POST" action="<?= url('admin/danh-gia/'.$r['id'].'/duyet') ?>" style="display:inline">
            <?= Session::csrfField() ?>
            <button type="submit" class="act-btn" title="Duyệt" style="color:var(--success)" onclick="return confirm('Duyệt đánh giá này?')">
              <i class="fas fa-check"></i>
            </button>
          </form>
          <?php endif; ?>
          <form method="POST" action="<?= url('admin/danh-gia/'.$r['id'].'/xoa') ?>" style="display:inline" onsubmit="return confirm('Xóa đánh giá này?')">
            <?= Session::csrfField() ?>
            <button type="submit" class="act-btn del" title="Xóa"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?= render_pagination($pager, url('admin/danh-gia').'?status='.urlencode($status)) ?>
