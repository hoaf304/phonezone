<?php
/**
 * @method static int createOrder(array $data, array $items)
 */
class Order extends Model
{
    protected static string $table = 'don_hang';

    /**
     * Tạo đơn hàng mới (đặt tên khác create() để tránh conflict với Model::create)
     */
    public static function createOrder(array $data, array $items): int
    {
        $madon = generate_order_code();

        $tamTinh  = 0; $giamSP = 0;
        foreach ($items as $item) {
            $gia    = (int)($item['gia_khuyen_mai'] > 0 ? $item['gia_khuyen_mai'] : $item['gia_ban']);
            $giaBan = (int)$item['gia_ban'];
            $qty    = (int)$item['so_luong'];
            $tamTinh += $giaBan * $qty;
            $giamSP  += ($giaBan - $gia) * $qty;
        }
        $sauGiam = $tamTinh - $giamSP;
        $coupon  = $data['coupon'] ?? null;
        $giamMa  = 0;
        if ($coupon) {
            $giamMa = $coupon['loai'] === 'phan_tram'
                ? min((int)($sauGiam * $coupon['gia_tri'] / 100), (int)($coupon['giam_toi_da'] ?: PHP_INT_MAX))
                : (int)$coupon['gia_tri'];
            $giamMa = min($giamMa, $sauGiam);
        }
        $phiShip = $sauGiam >= 1000000 ? 0 : 30000;
        $tong    = max(0, $sauGiam - $giamMa + $phiShip);

        $orderId = Database::insert(
            "INSERT INTO don_hang
             (ma_don, khach_hang_id, ten_nguoi_nhan, so_dien_thoai,
              tinh_thanh, quan_huyen, phuong_xa, so_nha_duong, ghi_chu,
              tam_tinh, giam_gia, phi_van_chuyen, tong_tien,
              ma_giam_gia_id, phuong_thuc_tt, trang_thai)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'cho_xac_nhan')",
            [
                $madon,
                $data['khach_hang_id'] ?? null,
                $data['ten_nguoi_nhan'],
                $data['so_dien_thoai'],
                $data['tinh_thanh'],
                $data['quan_huyen'],
                $data['phuong_xa'],
                $data['so_nha_duong'],
                $data['ghi_chu'] ?? '',
                $tamTinh - $giamSP,
                $giamSP + $giamMa,
                $phiShip,
                $tong,
                $coupon['id'] ?? null,
                $data['phuong_thuc_tt'],
            ]
        );

        // Chi tiết đơn hàng
        foreach ($items as $item) {
            $gia = (int)($item['gia_khuyen_mai'] > 0 ? $item['gia_khuyen_mai'] : $item['gia_ban']);
            Database::execute(
                "INSERT INTO chi_tiet_don_hang
                 (don_hang_id, bien_the_id, ten_san_pham, mau_sac, dung_luong, sku, hinh_anh, don_gia, so_luong, thanh_tien)
                 VALUES (?,?,?,?,?,?,?,?,?,?)",
                [
                    $orderId,
                    $item['bien_the_id'],
                    $item['ten_san_pham'],
                    $item['mau_sac'],
                    $item['dung_luong'],
                    $item['sku'],
                    $item['hinh_chinh'] ?? null,
                    $gia,
                    $item['so_luong'],
                    $gia * $item['so_luong'],
                ]
            );
            // Trừ tồn kho
            Database::execute(
                "UPDATE bien_the_san_pham SET ton_kho = ton_kho - ? WHERE id = ? AND ton_kho >= ?",
                [$item['so_luong'], $item['bien_the_id'], $item['so_luong']]
            );
        }

        // Lịch sử
        Database::execute(
            "INSERT INTO lich_su_don_hang (don_hang_id, trang_thai, ghi_chu, nguoi_thuc_hien) VALUES (?,?,?,?)",
            [$orderId, 'cho_xac_nhan', 'Đơn hàng được tạo', 'system']
        );

        // Tăng lượt dùng coupon
        if ($coupon) {
            Database::execute("UPDATE ma_giam_gia SET so_luong_da_dung = so_luong_da_dung + 1 WHERE id = ?", [$coupon['id']]);
        }

        return $orderId;
    }

    public static function getByCode(string $code): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM don_hang WHERE ma_don = ?", [$code]
        );
    }

    public static function getDetail(int $id): array|false
    {
        $order = Database::fetchOne("SELECT * FROM don_hang WHERE id = ?", [$id]);
        if (!$order) return false;
        $order['items']   = Database::fetchAll("SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = ?", [$id]);
        $order['history'] = Database::fetchAll("SELECT * FROM lich_su_don_hang WHERE don_hang_id = ? ORDER BY created_at ASC", [$id]);
        return $order;
    }

    public static function getByUser(int $userId, int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT * FROM don_hang WHERE khach_hang_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }
}
