<?php
class OrderController extends Controller
{
    private function uid(): int { return (int)Session::get('user_id', 0); }

    // ---- Form checkout ----
    public function checkout(): void
    {
        $items = Cart::getItems($this->uid());
        if (empty($items)) { $this->redirect('gio-hang'); return; }

        $totals    = Cart::calcTotal($items);
        $coupon    = Session::get('coupon');
        $giamMa    = 0;
        if ($coupon) {
            $giamMa = $this->calcCouponDiscount($coupon, $totals['sau_giam']);
            $totals['tong'] = max(0, $totals['sau_giam'] - $giamMa + $totals['phi_ship']);
        }

        $addresses = $this->uid() ? User::getAddresses($this->uid()) : [];

        $this->render('cart.checkout', [
            'title'     => 'Đặt hàng',
            'extraCss'  => ['cart.css'],
            'items'     => $items,
            'totals'    => $totals,
            'coupon'    => $coupon,
            'giamMa'    => $giamMa,
            'addresses' => $addresses,
        ]);
    }

    // ---- Xử lý đặt hàng ----
    public function process(): void
    {
        $this->verifyCsrf();

        $items = Cart::getItems($this->uid());
        if (empty($items)) { $this->redirect('gio-hang'); return; }

        // Validate địa chỉ
        $tenNguoiNhan = trim($this->input('ten_nguoi_nhan', ''));
        $sdt          = trim($this->input('so_dien_thoai', ''));
        $tinhThanh    = trim($this->input('tinh_thanh', ''));
        $quanHuyen    = trim($this->input('quan_huyen', ''));
        $phuongXa     = trim($this->input('phuong_xa', ''));
        $soNha        = trim($this->input('so_nha_duong', ''));
        $ghiChu       = trim($this->input('ghi_chu', ''));
        $phuongThuc   = $this->input('phuong_thuc_tt', 'cod');

        if (!$tenNguoiNhan || !$sdt || !$tinhThanh || !$quanHuyen || !$phuongXa || !$soNha) {
            Session::flash('error', 'Vui lòng điền đầy đủ địa chỉ giao hàng.');
            $this->redirect('dat-hang'); return;
        }

        $coupon = Session::get('coupon');

        try {
            $orderId = Order::createOrder([
                'khach_hang_id'  => $this->uid() ?: null,
                'ten_nguoi_nhan' => $tenNguoiNhan,
                'so_dien_thoai'  => $sdt,
                'tinh_thanh'     => $tinhThanh,
                'quan_huyen'     => $quanHuyen,
                'phuong_xa'      => $phuongXa,
                'so_nha_duong'   => $soNha,
                'ghi_chu'        => $ghiChu,
                'phuong_thuc_tt' => $phuongThuc,
                'coupon'         => $coupon,
            ], $items);

            // Lấy mã đơn
            $order = Order::getDetail($orderId);

            // Xóa giỏ hàng + coupon
            Cart::clear($this->uid());
            Session::remove('coupon');

            // Lưu mã đơn vào session để hiển thị trang success
            Session::set('last_order_code', $order['ma_don']);
            Session::set('last_order_id',   $orderId);

            $this->redirect('dat-hang/thanh-cong');

        } catch (\Exception $e) {
            Session::flash('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
            $this->redirect('dat-hang');
        }
    }

    // ---- Trang đặt hàng thành công ----
    public function success(): void
    {
        $madon   = Session::get('last_order_code');
        $orderId = Session::get('last_order_id');

        if (!$madon) { $this->redirect(''); return; }

        $order = Order::getDetail((int)$orderId);

        // Xóa session sau khi hiển thị một lần
        Session::remove('last_order_code');
        Session::remove('last_order_id');

        $this->render('cart.success', [
            'title'    => 'Đặt hàng thành công',
            'extraCss' => ['cart.css'],
            'order'    => $order,
        ]);
    }

    // ---- Theo dõi đơn hàng ----
    public function trackOrder(string $code): void
    {
        $order = Order::getByCode($code);
        if (!$order) {
            Session::flash('error', 'Không tìm thấy đơn hàng.'); $this->redirect(''); return;
        }
        $order['items']   = Database::fetchAll("SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = ?", [$order['id']]);
        $order['history'] = Database::fetchAll("SELECT * FROM lich_su_don_hang WHERE don_hang_id = ? ORDER BY created_at ASC", [$order['id']]);

        $this->render('cart.track', [
            'title'    => 'Theo dõi đơn hàng #' . $code,
            'extraCss' => ['cart.css'],
            'order'    => $order,
        ]);
    }

    private function calcCouponDiscount(array $coupon, int $sauGiam): int
    {
        $giam = $coupon['loai'] === 'phan_tram'
            ? (int)($sauGiam * $coupon['gia_tri'] / 100)
            : (int)$coupon['gia_tri'];
        if ($coupon['giam_toi_da'] > 0) $giam = min($giam, (int)$coupon['giam_toi_da']);
        return min($giam, $sauGiam);
    }
}
