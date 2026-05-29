<?php
// ============================================================
// AdminReviewController
// ============================================================
class AdminReviewController extends AdminController
{
    public function index(): void
    {
        $status  = $this->input('status', 'cho_duyet');
        $page    = max(1, (int)$this->input('page', 1));
        $perPage = ADMIN_ITEMS_PER_PAGE;
        $offset  = ($page - 1) * $perPage;

        $where  = $status ? "dg.trang_thai = ?" : "1=1";
        $params = $status ? [$status] : [];
        $total  = (int)Database::fetchScalar("SELECT COUNT(*) FROM danh_gia dg WHERE $where", $params);

        $stmt = Database::pdo()->prepare("
            SELECT dg.*, sp.ten AS ten_sp, sp.slug AS slug_sp,
                   kh.ho_ten AS ten_kh, kh.email AS email_kh
            FROM danh_gia dg
            LEFT JOIN san_pham sp ON sp.id = dg.san_pham_id
            LEFT JOIN khach_hang kh ON kh.id = dg.khach_hang_id
            WHERE $where ORDER BY dg.created_at DESC
            LIMIT $perPage OFFSET $offset
        ");
        foreach ($params as $i => $v) $stmt->bindValue($i+1, $v);
        $stmt->execute();

        $counts = [];
        foreach (['cho_duyet','da_duyet','an'] as $s) {
            $counts[$s] = (int)Database::fetchScalar("SELECT COUNT(*) FROM danh_gia WHERE trang_thai = ?", [$s]);
        }

        $this->render('admin.reviews.index', [
            'title'   => 'Quản lý đánh giá',
            'reviews' => $stmt->fetchAll(),
            'pager'   => ['total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>(int)ceil($total/$perPage)],
            'status'  => $status, 'counts' => $counts,
        ]);
    }

    public function approve(string $id): void
    {
        $this->verifyCsrf();
        Database::execute("UPDATE danh_gia SET trang_thai = 'da_duyet' WHERE id = ?", [$id]);
        Session::flash('success', 'Đã duyệt đánh giá.');
        $this->redirect('admin/danh-gia');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        Database::execute("DELETE FROM danh_gia WHERE id = ?", [$id]);
        Session::flash('success', 'Đã xóa đánh giá.');
        $this->redirect('admin/danh-gia');
    }
}
