<?php
// ============================================================
// AdminCouponController
// ============================================================
class AdminCouponController extends AdminController
{
    public function index(): void
    {
        $coupons = Database::fetchAll("SELECT * FROM ma_giam_gia ORDER BY created_at DESC");
        $this->render('admin.coupons.index', ['title' => 'Mã giảm giá', 'coupons' => $coupons]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $data = [
            'ma'                 => strtoupper(trim($this->input('ma',''))),
            'ten'                => trim($this->input('ten','')),
            'loai'               => $this->input('loai','phan_tram'),
            'gia_tri'            => (float)str_replace(',','.',$this->input('gia_tri','0')),
            'giam_toi_da'        => (int)str_replace(['.',',' ], '', $this->input('giam_toi_da','0')) ?: null,
            'don_hang_toi_thieu' => (int)str_replace(['.',',' ], '', $this->input('don_hang_toi_thieu','0')),
            'so_luong_tong'      => (int)$this->input('so_luong_tong','0') ?: null,
            'gioi_han_moi_kh'    => (int)$this->input('gioi_han_moi_kh','1'),
            'bat_dau'            => $this->input('bat_dau','') ?: null,
            'ket_thuc'           => $this->input('ket_thuc','') ?: null,
            'an_hien'            => 1,
        ];
        if (!$data['ma']) { Session::flash('error','Vui lòng nhập mã.'); $this->redirect('admin/ma-giam-gia'); return; }
        if (Database::fetchOne("SELECT id FROM ma_giam_gia WHERE ma = ?", [$data['ma']])) {
            Session::flash('error', "Mã \"{$data['ma']}\" đã tồn tại."); $this->redirect('admin/ma-giam-gia'); return;
        }
        Database::execute("
            INSERT INTO ma_giam_gia (ma,ten,loai,gia_tri,giam_toi_da,don_hang_toi_thieu,so_luong_tong,gioi_han_moi_kh,bat_dau,ket_thuc,an_hien)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ", array_values($data));
        Session::flash('success', "Đã tạo mã \"{$data['ma']}\".");
        $this->redirect('admin/ma-giam-gia');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        Database::execute("DELETE FROM ma_giam_gia WHERE id = ?", [$id]);
        Session::flash('success', 'Đã xóa mã giảm giá.');
        $this->redirect('admin/ma-giam-gia');
    }
}
