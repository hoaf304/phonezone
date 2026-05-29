<?php
class CartController extends Controller
{
    private function uid(): int { return (int)Session::get('user_id', 0); }

    // ---- Hiển thị giỏ hàng ----
    public function index(): void
    {
        $items  = Cart::getItems($this->uid());
        $totals = Cart::calcTotal($items);
        $coupon = Session::get('coupon', null);
        if ($coupon) {
            $giamMa = $this->calcCouponDiscount($coupon, $totals['sau_giam']);
            $totals['giam_ma']  = $giamMa;
            $totals['tong']     = max(0, $totals['sau_giam'] - $giamMa + $totals['phi_ship']);
        } else {
            $totals['giam_ma']  = 0;
        }

        $this->render('cart.index', [
            'title'   => 'Giỏ hàng',
            'extraCss'=> ['cart.css'],
            'items'   => $items,
            'totals'  => $totals,
            'coupon'  => $coupon,
        ]);
    }

    // ---- Thêm vào giỏ (AJAX + form thường) ----
    public function add(): void
    {
        $isAjax    = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
        $input     = $isAjax ? json_decode(file_get_contents('php://input'), true) : $_POST;
        $bienTheId = (int)($input['bien_the_id'] ?? 0);
        $soLuong   = max(1, (int)($input['so_luong'] ?? 1));

        if (!$bienTheId) {
            $isAjax ? $this->json(['success'=>false,'message'=>'Sản phẩm không hợp lệ.']) : $this->back();
            return;
        }

        $ok = Cart::add($bienTheId, $soLuong, $this->uid());

        if ($isAjax) {
            $this->json([
                'success'    => $ok,
                'message'    => $ok ? 'Đã thêm vào giỏ.' : 'Sản phẩm hết hàng hoặc không tồn tại.',
                'cart_count' => Cart::countItems($this->uid()),
            ]);
        } else {
            Session::flash($ok ? 'success' : 'error', $ok ? 'Đã thêm vào giỏ hàng!' : 'Không thể thêm sản phẩm.');
            $this->back();
        }
    }

    // ---- Cập nhật số lượng (AJAX) ----
    public function update(): void
    {
        $input     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $bienTheId = (int)($input['bien_the_id'] ?? 0);
        $soLuong   = (int)($input['so_luong']    ?? 1);

        if ($bienTheId > 0) Cart::updateQty($bienTheId, $soLuong, $this->uid());

        // Tính lại tổng
        $items  = Cart::getItems($this->uid());
        $totals = Cart::calcTotal($items);
        $coupon = Session::get('coupon');
        $giamMa = 0;
        if ($coupon) {
            $giamMa = $this->calcCouponDiscount($coupon, $totals['sau_giam']);
            $totals['tong'] = max(0, $totals['sau_giam'] - $giamMa + $totals['phi_ship']);
        }

        $this->json(['success' => true, 'totals' => $totals, 'giam_ma' => $giamMa, 'cart_count' => Cart::countItems($this->uid())]);
    }

    // ---- Xóa item ----
    public function remove(string $id): void
    {
        Cart::remove((int)$id, $this->uid());

        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            $items  = Cart::getItems($this->uid());
            $totals = Cart::calcTotal($items);
            $this->json(['success' => true, 'totals' => $totals, 'cart_count' => Cart::countItems($this->uid())]);
        } else {
            Session::flash('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
            $this->redirect('gio-hang');
        }
    }

    // ---- Xóa toàn bộ ----
    public function clear(): void
    {
        Cart::clear($this->uid());
        Session::remove('coupon');
        Session::flash('info', 'Đã xóa toàn bộ giỏ hàng.');
        $this->redirect('gio-hang');
    }

    // ---- Áp mã giảm giá (AJAX) ----
    public function applyCoupon(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $ma    = strtoupper(trim($input['ma'] ?? $this->input('ma', '')));

        if (!$ma) { $this->json(['success'=>false,'message'=>'Vui lòng nhập mã giảm giá.']); return; }

        // Tìm mã (bất kể trạng thái) để báo lỗi cụ thể
        $coupon = Database::fetchOne("SELECT * FROM ma_giam_gia WHERE ma = ?", [$ma]);

        if (!$coupon) {
            $this->json(['success'=>false,'message'=>'❌ Mã giảm giá "' . $ma . '" không tồn tại.']);
            return;
        }

        // Kiểm tra từng điều kiện — báo lỗi cụ thể
        if (!$coupon['an_hien']) {
            $this->json(['success'=>false,'message'=>'❌ Mã này đã bị vô hiệu hóa.']);
            return;
        }

        if (!empty($coupon['bat_dau']) && strtotime($coupon['bat_dau']) > time()) {
            $this->json(['success'=>false,'message'=>'⏳ Mã chưa đến thời gian sử dụng. Có hiệu lực từ ' . date('d/m/Y H:i', strtotime($coupon['bat_dau'])) . '.']);
            return;
        }

        if (!empty($coupon['ket_thuc']) && strtotime($coupon['ket_thuc']) < time()) {
            $this->json(['success'=>false,'message'=>'⌛ Mã đã hết hạn từ ' . date('d/m/Y', strtotime($coupon['ket_thuc'])) . '. Vui lòng dùng mã khác.']);
            return;
        }

        if ($coupon['so_luong_tong'] && $coupon['so_luong_da_dung'] >= $coupon['so_luong_tong']) {
            $this->json(['success'=>false,'message'=>'😔 Mã đã được sử dụng hết ' . $coupon['so_luong_tong'] . ' lượt. Vui lòng dùng mã khác.']);
            return;
        }

        // Kiểm tra đơn hàng tối thiểu
        $items  = Cart::getItems($this->uid());
        $totals = Cart::calcTotal($items);

        if ($totals['sau_giam'] < $coupon['don_hang_toi_thieu']) {
            $this->json(['success'=>false,'message'=>'🛒 Đơn hàng tối thiểu ' . format_price($coupon['don_hang_toi_thieu']) . ' để dùng mã này. Hiện tại: ' . format_price($totals['sau_giam']) . '.']);
            return;
        }

        // Tính số tiền giảm
        $giam = $this->calcCouponDiscount($coupon, $totals['sau_giam']);
        $tongSauGiam = max(0, $totals['sau_giam'] - $giam + $totals['phi_ship']);

        // Lưu vào session
        Session::set('coupon', $coupon);

        // Thông báo thành công
        $moTaGiam = $coupon['loai'] === 'phan_tram'
            ? 'Giảm ' . $coupon['gia_tri'] . '%'
            : 'Giảm ' . format_price($coupon['gia_tri']);

        $this->json([
            'success' => true,
            'message' => '🎉 Áp dụng thành công! ' . $moTaGiam . ' → Tiết kiệm ' . format_price($giam),
            'giam'    => $giam,
            'tong'    => $tongSauGiam,
            'phi_ship'=> $totals['phi_ship'],
        ]);
    }

    // ---- Xóa mã ----
    public function removeCoupon(): void
    {
        Session::remove('coupon');
        $this->redirect('gio-hang');
    }

    // ---- Helper tính giảm mã ----
    private function calcCouponDiscount(array $coupon, int $sauGiam): int
    {
        if ($coupon['loai'] === 'phan_tram') {
            $giam = (int)($sauGiam * $coupon['gia_tri'] / 100);
            if ($coupon['giam_toi_da'] > 0) $giam = min($giam, $coupon['giam_toi_da']);
        } else {
            $giam = (int)$coupon['gia_tri'];
        }
        return min($giam, $sauGiam);
    }
}
