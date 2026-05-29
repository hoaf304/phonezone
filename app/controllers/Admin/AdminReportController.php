<?php
class AdminReportController extends AdminController
{
    public function index(): void
    {
        $nam  = (int)$this->input('nam',  date('Y'));
        $thang= (int)$this->input('thang', 0);

        // Doanh thu theo tháng trong năm
        $doanhThuThang = Database::fetchAll("
            SELECT MONTH(created_at) AS thang,
                   SUM(tong_tien)    AS doanh_thu,
                   COUNT(*)          AS so_don
            FROM don_hang
            WHERE YEAR(created_at) = ? AND trang_thai != 'da_huy'
            GROUP BY MONTH(created_at)
            ORDER BY thang ASC
        ", [$nam]);

        // Doanh thu hôm nay / tuần / tháng / năm
        $kpi = [
            'hom_nay' => (int)Database::fetchScalar("SELECT COALESCE(SUM(tong_tien),0) FROM don_hang WHERE DATE(created_at)=CURDATE() AND trang_thai!='da_huy'"),
            'tuan'    => (int)Database::fetchScalar("SELECT COALESCE(SUM(tong_tien),0) FROM don_hang WHERE YEARWEEK(created_at)=YEARWEEK(NOW()) AND trang_thai!='da_huy'"),
            'thang'   => (int)Database::fetchScalar("SELECT COALESCE(SUM(tong_tien),0) FROM don_hang WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW()) AND trang_thai!='da_huy'"),
            'nam'     => (int)Database::fetchScalar("SELECT COALESCE(SUM(tong_tien),0) FROM don_hang WHERE YEAR(created_at)=YEAR(NOW()) AND trang_thai!='da_huy'"),
            'don_hom_nay'  => (int)Database::fetchScalar("SELECT COUNT(*) FROM don_hang WHERE DATE(created_at)=CURDATE()"),
            'don_cho_xu_ly'=> (int)Database::fetchScalar("SELECT COUNT(*) FROM don_hang WHERE trang_thai='cho_xac_nhan'"),
        ];

        // Top sản phẩm bán chạy
        $topSanPham = Database::fetchAll("
            SELECT sp.ten, sp.hinh_chinh, sp.slug,
                   h.ten AS hang_ten,
                   SUM(ct.so_luong)  AS da_ban,
                   SUM(ct.thanh_tien)AS doanh_thu,
                   COUNT(DISTINCT ct.don_hang_id) AS so_don
            FROM chi_tiet_don_hang ct
            JOIN bien_the_san_pham bt ON bt.id = ct.bien_the_id
            JOIN san_pham sp ON sp.id = bt.san_pham_id
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            JOIN don_hang dh ON dh.id = ct.don_hang_id
            WHERE dh.trang_thai != 'da_huy'
              AND YEAR(dh.created_at) = ?
            GROUP BY sp.id
            ORDER BY da_ban DESC LIMIT 10
        ", [$nam]);

        // Doanh thu theo hãng
        $doanhThuHang = Database::fetchAll("
            SELECT h.ten AS hang_ten,
                   SUM(ct.thanh_tien) AS doanh_thu,
                   SUM(ct.so_luong)   AS da_ban
            FROM chi_tiet_don_hang ct
            JOIN bien_the_san_pham bt ON bt.id = ct.bien_the_id
            JOIN san_pham sp ON sp.id = bt.san_pham_id
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            JOIN don_hang dh ON dh.id = ct.don_hang_id
            WHERE dh.trang_thai != 'da_huy' AND YEAR(dh.created_at) = ?
            GROUP BY h.id ORDER BY doanh_thu DESC
        ", [$nam]);

        // Phương thức thanh toán
        $thongKeTT = Database::fetchAll("
            SELECT phuong_thuc_tt, COUNT(*) AS so_don,
                   SUM(tong_tien) AS doanh_thu
            FROM don_hang WHERE trang_thai != 'da_huy' AND YEAR(created_at) = ?
            GROUP BY phuong_thuc_tt ORDER BY so_don DESC
        ", [$nam]);

        // Khách hàng mới theo tháng
        $khachMoiThang = Database::fetchAll("
            SELECT MONTH(created_at) AS thang, COUNT(*) AS so_kh
            FROM khach_hang WHERE YEAR(created_at) = ?
            GROUP BY MONTH(created_at) ORDER BY thang ASC
        ", [$nam]);

        $namList = range(date('Y'), date('Y') - 3);

        $this->render('admin.reports.index', [
            'title'          => 'Báo cáo thống kê',
            'kpi'            => $kpi,
            'doanhThuThang'  => $doanhThuThang,
            'topSanPham'     => $topSanPham,
            'doanhThuHang'   => $doanhThuHang,
            'thongKeTT'      => $thongKeTT,
            'khachMoiThang'  => $khachMoiThang,
            'nam'            => $nam,
            'namList'        => $namList,
        ]);
    }
}
