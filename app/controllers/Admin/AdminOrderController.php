<?php
class AdminOrderController extends AdminController
{
    private array $statusLabel = [
        'cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận',
        'dang_dong_hang'=>'Đóng hàng','dang_giao'=>'Đang giao',
        'da_giao'=>'Đã giao','da_huy'=>'Đã hủy','hoan_hang'=>'Hoàn hàng',
    ];

    public function index(): void
    {
        $q        = trim($this->input('q', ''));
        $status   = $this->input('status', '');
        $pt       = $this->input('pt', '');
        $page     = max(1, (int)$this->input('page', 1));
        $perPage  = ADMIN_ITEMS_PER_PAGE;

        $where = ['1=1']; $params = [];
        if ($q)      { $where[] = '(dh.ma_don LIKE ? OR dh.ten_nguoi_nhan LIKE ? OR dh.so_dien_thoai LIKE ?)'; $params = array_merge($params, ["%$q%","%$q%","%$q%"]); }
        if ($status) { $where[] = 'dh.trang_thai = ?'; $params[] = $status; }
        if ($pt)     { $where[] = 'dh.phuong_thuc_tt = ?'; $params[] = $pt; }

        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;
        $total    = (int)Database::fetchScalar("SELECT COUNT(*) FROM don_hang dh WHERE $whereStr", $params);

        $stmt = Database::pdo()->prepare(
            "SELECT dh.*, kh.ho_ten AS ten_kh,
                    (SELECT COUNT(*) FROM chi_tiet_don_hang WHERE don_hang_id=dh.id) AS so_sp
             FROM don_hang dh
             LEFT JOIN khach_hang kh ON kh.id = dh.khach_hang_id
             WHERE $whereStr ORDER BY dh.created_at DESC LIMIT $perPage OFFSET $offset"
        );
        foreach ($params as $i => $v) $stmt->bindValue($i+1, $v);
        $stmt->execute();
        $orders = $stmt->fetchAll();

        // Đếm theo từng trạng thái
        $countByStatus = [];
        foreach (Database::fetchAll("SELECT trang_thai, COUNT(*) AS n FROM don_hang GROUP BY trang_thai") as $r) {
            $countByStatus[$r['trang_thai']] = $r['n'];
        }

        $this->render('admin.orders.index', [
            'title'          => 'Quản lý đơn hàng',
            'orders'         => $orders,
            'pager'          => ['total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>ceil($total/$perPage)],
            'q'              => $q, 'status' => $status, 'pt' => $pt,
            'statusLabel'    => $this->statusLabel,
            'countByStatus'  => $countByStatus,
        ]);
    }

    public function detail(string $id): void
    {
        $order = Order::getDetail((int)$id);
        if (!$order) { Session::flash('error','Không tìm thấy đơn hàng.'); $this->redirect('admin/don-hang'); return; }

        $this->render('admin.orders.detail', [
            'title'       => 'Đơn hàng #' . $order['ma_don'],
            'order'       => $order,
            'statusLabel' => $this->statusLabel,
        ]);
    }

    public function updateStatus(string $id): void
    {
        $this->verifyCsrf();
        $newStatus = $this->input('trang_thai', '');
        $ghiChu    = trim($this->input('ghi_chu', ''));

        $allowed = array_keys($this->statusLabel);
        if (!in_array($newStatus, $allowed)) {
            Session::flash('error','Trạng thái không hợp lệ.'); $this->redirect("admin/don-hang/$id"); return;
        }

        Database::execute("UPDATE don_hang SET trang_thai=?, updated_at=NOW() WHERE id=?", [$newStatus, $id]);
        Database::execute(
            "INSERT INTO lich_su_don_hang (don_hang_id,trang_thai,ghi_chu,nguoi_thuc_hien) VALUES (?,?,?,?)",
            [$id, $newStatus, $ghiChu, Session::get('admin_name','Admin')]
        );

        Session::flash('success','Cập nhật trạng thái thành công!');
        $this->redirect("admin/don-hang/$id");
    }
}
