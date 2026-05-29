<?php
class AccountController extends Controller
{
    private function uid(): int { return (int)Session::get('user_id'); }

    public function index(): void
    {
        $this->requireAuth();
        $user    = User::find($this->uid());

        // Sync avatar vào session
        if (!empty($user['avatar'])) {
            Session::set('user_avatar', $user['avatar']);
        }

        $donGanDay = Database::fetchAll(
            "SELECT * FROM don_hang WHERE khach_hang_id = ? ORDER BY created_at DESC LIMIT 3",
            [$this->uid()]
        );
        $soYeuThich = (int)Database::fetchScalar("SELECT COUNT(*) FROM yeu_thich WHERE khach_hang_id = ?", [$this->uid()]);
        $soDon      = (int)Database::fetchScalar("SELECT COUNT(*) FROM don_hang WHERE khach_hang_id = ?", [$this->uid()]);

        $this->render('account.index', [
            'title'      => 'Tài khoản của tôi',
            'extraCss'   => ['account.css'],
            'user'       => $user,
            'donGanDay'  => $donGanDay,
            'soYeuThich' => $soYeuThich,
            'soDon'      => $soDon,
            'activeTab'  => 'dashboard',
        ]);
    }

    public function orders(): void
    {
        $this->requireAuth();
        $page    = max(1, (int)$this->input('page', 1));
        $status  = $this->input('status', '');
        $perPage = 10;

        $where = 'khach_hang_id = ?'; $params = [$this->uid()];
        if ($status) { $where .= ' AND trang_thai = ?'; $params[] = $status; }

        $total  = (int)Database::fetchScalar("SELECT COUNT(*) FROM don_hang WHERE $where", $params);
        $offset = ($page - 1) * $perPage;
        $stmt   = Database::pdo()->prepare(
            "SELECT * FROM don_hang WHERE $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset"
        );
        foreach ($params as $i => $v) $stmt->bindValue($i+1, $v);
        $stmt->execute();
        $orders = $stmt->fetchAll();

        // Lấy chi tiết sản phẩm cho mỗi đơn
        foreach ($orders as &$order) {
            $order['items'] = Database::fetchAll(
                "SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = ? LIMIT 3",
                [$order['id']]
            );
            $order['tong_sp'] = (int)Database::fetchScalar(
                "SELECT COUNT(*) FROM chi_tiet_don_hang WHERE don_hang_id = ?", [$order['id']]
            );
        }

        $this->render('account.orders', [
            'title'     => 'Đơn hàng của tôi',
            'extraCss'  => ['account.css'],
            'orders'    => $orders,
            'pager'     => ['total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>ceil($total/$perPage)],
            'status'    => $status,
            'user'      => User::find($this->uid()),
            'activeTab' => 'orders',
        ]);
    }

    public function wishlist(): void
    {
        $this->requireAuth();
        $items = Database::fetchAll("
            SELECT sp.*, h.ten AS hang_ten, h.slug AS hang_slug,
                   bt.gia_ban, bt.gia_khuyen_mai, bt.id AS bien_the_id,
                   yt.created_at AS ngay_yeu_thich,
                   COALESCE(dg.avg_sao, 0) AS avg_sao,
                   COALESCE(dg.so_danh_gia, 0) AS so_danh_gia
            FROM yeu_thich yt
            JOIN san_pham sp ON sp.id = yt.san_pham_id
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            LEFT JOIN (SELECT san_pham_id, MIN(COALESCE(gia_khuyen_mai,gia_ban)) AS gia_khuyen_mai, MIN(gia_ban) AS gia_ban, MIN(id) AS id
                       FROM bien_the_san_pham WHERE an_hien=1 GROUP BY san_pham_id) bt ON bt.san_pham_id=sp.id
            LEFT JOIN (SELECT san_pham_id, ROUND(AVG(so_sao),1) AS avg_sao, COUNT(*) AS so_danh_gia
                       FROM danh_gia WHERE trang_thai='da_duyet' GROUP BY san_pham_id) dg ON dg.san_pham_id=sp.id
            WHERE yt.khach_hang_id = ?
            ORDER BY yt.created_at DESC
        ", [$this->uid()]);

        $this->render('account.wishlist', [
            'title'     => 'Sản phẩm yêu thích',
            'extraCss'  => ['account.css'],
            'items'     => $items,
            'user'      => User::find($this->uid()),
            'activeTab' => 'wishlist',
        ]);
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $hoTen   = trim($this->input('ho_ten', ''));
        $sdt     = trim($this->input('so_dien_thoai', ''));
        $ngaySinh= trim($this->input('ngay_sinh', ''));
        $passwd  = $this->input('mat_khau_moi', '');
        $confirm = $this->input('xac_nhan_mat_khau', '');

        if (strlen($hoTen) < 2) {
            Session::flash('error', 'Họ tên phải ít nhất 2 ký tự.');
            $this->redirect('tai-khoan'); return;
        }

        $update = [
            'ho_ten'        => $hoTen,
            'so_dien_thoai' => $sdt,
            'ngay_sinh'     => $ngaySinh ?: null,
        ];

        // Upload avatar
        if (!empty($_FILES['avatar']['tmp_name'])) {
            if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                Session::flash('error', 'Ảnh đại diện tối đa 2MB.');
                $this->redirect('tai-khoan'); return;
            }
            $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
            if (!in_array($_FILES['avatar']['type'], $allowed)) {
                Session::flash('error', 'Chỉ chấp nhận ảnh JPG, PNG, WEBP.');
                $this->redirect('tai-khoan'); return;
            }
            $avatarPath = upload_image($_FILES['avatar'], 'avatars');
            if ($avatarPath) {
                // Xóa avatar cũ nếu có
                $oldUser = User::find($this->uid());
                if (!empty($oldUser['avatar']) && !str_starts_with($oldUser['avatar'],'http')) {
                    $oldFile = UPLOAD_PATH . $oldUser['avatar'];
                    if (file_exists($oldFile)) @unlink($oldFile);
                }
                $update['avatar'] = $avatarPath;
                // Cập nhật session avatar
                Session::set('user_avatar', $avatarPath);
            }
        }

        // Đổi mật khẩu
        if ($passwd) {
            if (strlen($passwd) < 6) {
                Session::flash('error', 'Mật khẩu mới tối thiểu 6 ký tự.');
                $this->redirect('tai-khoan'); return;
            }
            if ($passwd !== $confirm) {
                Session::flash('error', 'Xác nhận mật khẩu không khớp.');
                $this->redirect('tai-khoan'); return;
            }
            $update['mat_khau'] = password_hash($passwd, PASSWORD_BCRYPT, ['cost' => 12]);
        }

        User::update($this->uid(), $update);
        Session::set('user_name', $hoTen);
        Session::flash('success', 'Cập nhật thông tin thành công!');
        $this->redirect('tai-khoan');
    }
}
