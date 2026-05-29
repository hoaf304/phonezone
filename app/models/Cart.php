<?php
class Cart extends Model
{
    protected static string $table = 'gio_hang';

    // Lấy giỏ hàng đầy đủ (kèm thông tin sản phẩm + biến thể)
    public static function getItems(int $userId = 0): array
    {
        if ($userId > 0) {
            // User đã login: lấy từ DB
            return Database::fetchAll("
                SELECT gh.*,
                       bt.gia_ban, bt.gia_khuyen_mai, bt.mau_sac, bt.dung_luong, bt.ton_kho, bt.sku,
                       sp.ten AS ten_san_pham, sp.slug AS slug_san_pham, sp.hinh_chinh,
                       h.ten AS hang_ten
                FROM gio_hang gh
                JOIN bien_the_san_pham bt ON bt.id = gh.bien_the_id
                JOIN san_pham sp ON sp.id = bt.san_pham_id
                JOIN hang_san_xuat h ON h.id = sp.hang_id
                WHERE gh.khach_hang_id = ?
                ORDER BY gh.created_at DESC
            ", [$userId]);
        }

        // Khách vãng lai: lấy từ session, rồi join DB
        $sessionCart = Session::get('cart', []);
        if (empty($sessionCart)) return [];

        $ids     = array_keys($sessionCart);
        $holders = implode(',', array_fill(0, count($ids), '?'));
        $rows    = Database::fetchAll("
            SELECT bt.id AS bien_the_id,
                   bt.gia_ban, bt.gia_khuyen_mai, bt.mau_sac, bt.dung_luong, bt.ton_kho, bt.sku,
                   sp.ten AS ten_san_pham, sp.slug AS slug_san_pham, sp.hinh_chinh,
                   h.ten AS hang_ten
            FROM bien_the_san_pham bt
            JOIN san_pham sp ON sp.id = bt.san_pham_id
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            WHERE bt.id IN ($holders)
        ", $ids);

        // Gắn số lượng từ session
        foreach ($rows as &$row) {
            $row['so_luong'] = $sessionCart[$row['bien_the_id']]['qty'] ?? 1;
        }
        return $rows;
    }

    // Thêm vào giỏ
    public static function add(int $bienTheId, int $soLuong = 1, int $userId = 0): bool
    {
        // Kiểm tra tồn kho
        $bt = Database::fetchOne("SELECT ton_kho FROM bien_the_san_pham WHERE id = ? AND an_hien = 1", [$bienTheId]);
        if (!$bt || $bt['ton_kho'] < 1) return false;

        $soLuong = min($soLuong, $bt['ton_kho']);

        if ($userId > 0) {
            $existing = Database::fetchOne(
                "SELECT id, so_luong FROM gio_hang WHERE khach_hang_id = ? AND bien_the_id = ?",
                [$userId, $bienTheId]
            );
            if ($existing) {
                $newQty = min($existing['so_luong'] + $soLuong, $bt['ton_kho']);
                Database::execute("UPDATE gio_hang SET so_luong = ? WHERE id = ?", [$newQty, $existing['id']]);
            } else {
                Database::execute(
                    "INSERT INTO gio_hang (khach_hang_id, bien_the_id, so_luong) VALUES (?,?,?)",
                    [$userId, $bienTheId, $soLuong]
                );
            }
        } else {
            $cart = Session::get('cart', []);
            $currentQty = $cart[$bienTheId]['qty'] ?? 0;
            $cart[$bienTheId] = ['qty' => min($currentQty + $soLuong, $bt['ton_kho'])];
            Session::set('cart', $cart);
        }
        return true;
    }

    // Cập nhật số lượng
    public static function updateQty(int $bienTheId, int $soLuong, int $userId = 0): void
    {
        $soLuong = max(1, $soLuong);
        if ($userId > 0) {
            Database::execute(
                "UPDATE gio_hang SET so_luong = ? WHERE khach_hang_id = ? AND bien_the_id = ?",
                [$soLuong, $userId, $bienTheId]
            );
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$bienTheId])) { $cart[$bienTheId]['qty'] = $soLuong; Session::set('cart', $cart); }
        }
    }

    // Xóa một item
    public static function remove(int $bienTheId, int $userId = 0): void
    {
        if ($userId > 0) {
            Database::execute("DELETE FROM gio_hang WHERE khach_hang_id = ? AND bien_the_id = ?", [$userId, $bienTheId]);
        } else {
            $cart = Session::get('cart', []);
            unset($cart[$bienTheId]);
            Session::set('cart', $cart);
        }
    }

    // Xóa toàn bộ
    public static function clear(int $userId = 0): void
    {
        if ($userId > 0) {
            Database::execute("DELETE FROM gio_hang WHERE khach_hang_id = ?", [$userId]);
        }
        Session::remove('cart');
    }

    // Tính tổng tiền
    public static function calcTotal(array $items): array
    {
        $tamTinh  = 0;
        $giamSP   = 0;
        foreach ($items as $item) {
            $gia    = (int)($item['gia_khuyen_mai'] > 0 ? $item['gia_khuyen_mai'] : $item['gia_ban']);
            $giaBan = (int)$item['gia_ban'];
            $qty    = (int)$item['so_luong'];
            $tamTinh += $giaBan * $qty;
            $giamSP  += ($giaBan - $gia) * $qty;
        }
        $sauGiam   = $tamTinh - $giamSP;
        $phiShip   = $sauGiam >= 1000000 ? 0 : 30000;
        return [
            'tam_tinh' => $tamTinh,
            'giam_sp'  => $giamSP,
            'sau_giam' => $sauGiam,
            'phi_ship' => $phiShip,
            'tong'     => $sauGiam + $phiShip,
        ];
    }

    // Đếm số lượng item trong giỏ
    public static function countItems(int $userId = 0): int
    {
        if ($userId > 0) {
            return (int)Database::fetchScalar("SELECT SUM(so_luong) FROM gio_hang WHERE khach_hang_id = ?", [$userId]);
        }
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'qty'));
    }
}
