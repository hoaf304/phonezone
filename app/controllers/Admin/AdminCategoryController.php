<?php
class AdminCategoryController extends AdminController
{
    public function index(): void
    {
        $categories = Database::fetchAll("
            SELECT dm.*, COUNT(sp.id) AS so_san_pham
            FROM danh_muc dm
            LEFT JOIN san_pham sp ON sp.danh_muc_id = dm.id AND sp.an_hien = 1
            GROUP BY dm.id ORDER BY dm.thu_tu ASC, dm.id ASC
        ");
        $this->render('admin.categories.index', [
            'title'      => 'Quản lý danh mục',
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $ten  = trim($this->input('ten', ''));
        $slug = trim($this->input('slug', '')) ?: to_slug($ten);
        $moTa = trim($this->input('mo_ta', ''));
        $thuTu= (int)$this->input('thu_tu', 0);

        if (!$ten) { Session::flash('error', 'Vui lòng nhập tên danh mục.'); $this->redirect('admin/danh-muc'); return; }

        // Kiểm tra slug trùng
        $existing = Database::fetchOne("SELECT id FROM danh_muc WHERE slug = ?", [$slug]);
        if ($existing) $slug .= '-' . time();

        Database::execute("INSERT INTO danh_muc (ten, slug, mo_ta, thu_tu, an_hien) VALUES (?,?,?,?,1)", [$ten, $slug, $moTa, $thuTu]);
        Session::flash('success', "Đã thêm danh mục \"$ten\".");
        $this->redirect('admin/danh-muc');
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $ten   = trim($this->input('ten', ''));
        $moTa  = trim($this->input('mo_ta', ''));
        $thuTu = (int)$this->input('thu_tu', 0);
        $anHien= $this->input('an_hien', 0) ? 1 : 0;

        if (!$ten) { Session::flash('error', 'Tên danh mục không được trống.'); $this->redirect('admin/danh-muc'); return; }

        Database::execute("UPDATE danh_muc SET ten=?, mo_ta=?, thu_tu=?, an_hien=? WHERE id=?", [$ten, $moTa, $thuTu, $anHien, (int)$id]);
        Session::flash('success', 'Cập nhật danh mục thành công!');
        $this->redirect('admin/danh-muc');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        $count = (int)Database::fetchScalar("SELECT COUNT(*) FROM san_pham WHERE danh_muc_id = ?", [$id]);
        if ($count > 0) {
            Session::flash('error', "Không thể xóa — danh mục đang có $count sản phẩm.");
        } else {
            Database::execute("DELETE FROM danh_muc WHERE id = ?", [$id]);
            Session::flash('success', 'Đã xóa danh mục.');
        }
        $this->redirect('admin/danh-muc');
    }
}
