<?php
class DashboardController extends AdminController
{
    public function index(): void
    {
        // KPI hôm nay
        $doanhThuHomNay = (int)Database::fetchScalar(
            "SELECT COALESCE(SUM(tong_tien),0) FROM don_hang WHERE DATE(created_at)=CURDATE() AND trang_thai != 'da_huy'"
        );
        $donHomNay = (int)Database::fetchScalar(
            "SELECT COUNT(*) FROM don_hang WHERE DATE(created_at)=CURDATE()"
        );
        $khachMoiThang = (int)Database::fetchScalar(
            "SELECT COUNT(*) FROM khach_hang WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())"
        );
        $sapHetHang = (int)Database::fetchScalar(
            "SELECT COUNT(*) FROM bien_the_san_pham WHERE ton_kho <= 5 AND an_hien = 1"
        );

        // Doanh thu 6 tháng gần nhất
        $doanhThu6Thang = Database::fetchAll("
            SELECT DATE_FORMAT(created_at,'%m/%Y') AS thang,
                   SUM(tong_tien) AS tong
            FROM don_hang
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) AND trang_thai != 'da_huy'
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY created_at ASC
        ");

        // Đơn hàng mới nhất
        $donHangMoi = Database::fetchAll("
            SELECT dh.*, kh.ho_ten AS ten_kh
            FROM don_hang dh
            LEFT JOIN khach_hang kh ON kh.id = dh.khach_hang_id
            ORDER BY dh.created_at DESC LIMIT 8
        ");

        // Top sản phẩm bán chạy
        $topSanPham = Database::fetchAll("
            SELECT sp.ten, sp.hinh_chinh, SUM(ct.so_luong) AS da_ban,
                   SUM(ct.thanh_tien) AS doanh_thu
            FROM chi_tiet_don_hang ct
            JOIN bien_the_san_pham bt ON bt.id = ct.bien_the_id
            JOIN san_pham sp ON sp.id = bt.san_pham_id
            JOIN don_hang dh ON dh.id = ct.don_hang_id
            WHERE dh.trang_thai != 'da_huy'
            GROUP BY sp.id ORDER BY da_ban DESC LIMIT 5
        ");

        // Thống kê theo trạng thái
        $thongKeTrangThai = Database::fetchAll("
            SELECT trang_thai, COUNT(*) AS so_luong FROM don_hang GROUP BY trang_thai
        ");

        $this->render('admin.dashboard', [
            'title'             => 'Dashboard',
            'doanhThuHomNay'    => $doanhThuHomNay,
            'donHomNay'         => $donHomNay,
            'khachMoiThang'     => $khachMoiThang,
            'sapHetHang'        => $sapHetHang,
            'doanhThu6Thang'    => $doanhThu6Thang,
            'donHangMoi'        => $donHangMoi,
            'topSanPham'        => $topSanPham,
            'thongKeTrangThai'  => $thongKeTrangThai,
        ]);
    }
}
