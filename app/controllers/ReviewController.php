<?php
class ReviewController extends Controller
{
    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $productId = (int)$this->input('san_pham_id');
        $soSao     = (int)$this->input('so_sao', 5);
        $noiDung   = trim($this->input('noi_dung', ''));

        if (!$productId || $soSao < 1 || $soSao > 5) {
            Session::flash('error', 'Dữ liệu không hợp lệ.');
            $this->back();
            return;
        }

        Review::store([
            'san_pham_id'   => $productId,
            'khach_hang_id' => Session::get('user_id'),
            'ho_ten'        => Session::get('user_name', 'Ẩn danh'),
            'so_sao'        => $soSao,
            'tieu_de'       => $this->input('tieu_de', ''),
            'noi_dung'      => $noiDung,
        ]);

        Session::flash('success', 'Cảm ơn bạn! Đánh giá đang chờ duyệt.');
        $this->back();
    }
}
