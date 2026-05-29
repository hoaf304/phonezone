<?php
class AdminCustomerController extends AdminController
{
    public function index(): void
    {
        $q       = trim($this->input('q', ''));
        $status  = $this->input('status', '');
        $page    = max(1, (int)$this->input('page', 1));
        $perPage = ADMIN_ITEMS_PER_PAGE;

        $where = ['1=1']; $params = [];
        if ($q)      { $where[] = '(kh.ho_ten LIKE ? OR kh.email LIKE ? OR kh.so_dien_thoai LIKE ?)'; $params = array_merge($params, ["%$q%","%$q%","%$q%"]); }
        if ($status) { $where[] = 'kh.trang_thai = ?'; $params[] = $status; }

        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;
        $total    = (int)Database::fetchScalar("SELECT COUNT(*) FROM khach_hang kh WHERE $whereStr", $params);

        $stmt = Database::pdo()->prepare("
            SELECT kh.*,
                   COUNT(dh.id)       AS so_don,
                   COALESCE(SUM(dh.tong_tien),0) AS tong_chi
            FROM khach_hang kh
            LEFT JOIN don_hang dh ON dh.khach_hang_id = kh.id AND dh.trang_thai != 'da_huy'
            WHERE $whereStr
            GROUP BY kh.id ORDER BY kh.created_at DESC
            LIMIT $perPage OFFSET $offset
        ");
        foreach ($params as $i => $v) $stmt->bindValue($i+1, $v);
        $stmt->execute();
        $customers = $stmt->fetchAll();

        $this->render('admin.customers.index', [
            'title'     => 'Quản lý khách hàng',
            'customers' => $customers,
            'pager'     => ['total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>(int)ceil($total/$perPage)],
            'q'         => $q, 'status' => $status,
        ]);
    }

    public function detail(string $id): void
    {
        $customer = User::find((int)$id);
        if (!$customer) { Session::flash('error','Không tìm thấy khách hàng.'); $this->redirect('admin/khach-hang'); return; }

        $orders = Database::fetchAll("SELECT * FROM don_hang WHERE khach_hang_id = ? ORDER BY created_at DESC LIMIT 10", [$id]);
        $stats  = Database::fetchOne("
            SELECT COUNT(*) AS so_don,
                   COALESCE(SUM(tong_tien),0) AS tong_chi,
                   COALESCE(MAX(tong_tien),0)  AS don_lon_nhat
            FROM don_hang WHERE khach_hang_id = ? AND trang_thai != 'da_huy'
        ", [$id]);

        $this->render('admin.customers.detail', [
            'title'    => 'Chi tiết khách hàng',
            'customer' => $customer,
            'orders'   => $orders,
            'stats'    => $stats,
        ]);
    }
}
