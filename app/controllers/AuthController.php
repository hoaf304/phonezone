<?php
class AuthController extends Controller
{
    // ---- Form đăng nhập ----
    public function loginForm(): void
    {
        if (Session::has('user_id')) { $this->redirect(''); return; }
        $this->render('auth.login', ['title' => 'Đăng nhập', 'extraCss' => ['cart.css']]);
    }

    // ---- Xử lý đăng nhập ----
    public function login(): void
    {
        $this->verifyCsrf();
        $email    = trim($this->input('email', ''));
        $matKhau  = $this->input('mat_khau', '');
        $remember = $this->input('remember', '');

        if (!$email || !$matKhau) {
            Session::flash('error', 'Vui lòng nhập email và mật khẩu.');
            $this->redirect('dang-nhap'); return;
        }

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($matKhau, $user['mat_khau'])) {
            Session::flash('error', 'Email hoặc mật khẩu không đúng.');
            $this->redirect('dang-nhap'); return;
        }

        if ($user['trang_thai'] !== 'hoat_dong') {
            Session::flash('error', 'Tài khoản của bạn đã bị khoá. Vui lòng liên hệ hỗ trợ.');
            $this->redirect('dang-nhap'); return;
        }

        User::loginSession($user);

        // Merge giỏ hàng session vào DB
        $this->mergeCart($user['id']);

        Session::flash('success', 'Chào mừng trở lại, ' . $user['ho_ten'] . '!');

        $redirect = Session::get('redirect_after_login', '');
        Session::remove('redirect_after_login');
        $this->redirect($redirect ?: '');
    }

    // ---- Form đăng ký ----
    public function registerForm(): void
    {
        if (Session::has('user_id')) { $this->redirect(''); return; }
        $this->render('auth.register', ['title' => 'Đăng ký tài khoản', 'extraCss' => ['cart.css']]);
    }

    // ---- Xử lý đăng ký ----
    public function register(): void
    {
        $this->verifyCsrf();

        $hoTen    = trim($this->input('ho_ten', ''));
        $email    = trim($this->input('email', ''));
        $matKhau  = $this->input('mat_khau', '');
        $xacNhan  = $this->input('xac_nhan_mat_khau', '');
        $sdt      = trim($this->input('so_dien_thoai', ''));

        // Validate
        $errors = [];
        if (strlen($hoTen) < 2)                 $errors[] = 'Họ tên phải ít nhất 2 ký tự.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
        if (strlen($matKhau) < 6)               $errors[] = 'Mật khẩu phải ít nhất 6 ký tự.';
        if ($matKhau !== $xacNhan)              $errors[] = 'Xác nhận mật khẩu không khớp.';
        if (User::findByEmail($email))           $errors[] = 'Email này đã được đăng ký.';

        if ($errors) {
            Session::flash('error', implode(' ', $errors));
            $this->redirect('dang-ky'); return;
        }

        $id = User::register(['ho_ten' => $hoTen, 'email' => $email, 'mat_khau' => $matKhau, 'so_dien_thoai' => $sdt]);
        $user = User::find($id);
        User::loginSession($user);
        $this->mergeCart($id);

        Session::flash('success', 'Đăng ký thành công! Chào mừng ' . $hoTen . '.');
        $this->redirect('');
    }

    // ---- Đăng xuất ----
    public function logout(): void
    {
        User::logout();
        Session::flash('success', 'Đã đăng xuất thành công.');
        $this->redirect('');
    }

    // ---- Quên mật khẩu (form) ----
    public function forgotForm(): void
    {
        $this->render('auth.forgot', ['title' => 'Quên mật khẩu', 'extraCss' => ['cart.css']]);
    }

    public function forgot(): void
    {
        // TODO: gửi email reset (cần cấu hình SMTP)
        Session::flash('info', 'Nếu email tồn tại, chúng tôi đã gửi link đặt lại mật khẩu.');
        $this->redirect('dang-nhap');
    }

    // ---- Merge giỏ session → DB ----
    private function mergeCart(int $userId): void
    {
        $sessionCart = Session::get('cart', []);
        if (empty($sessionCart)) return;

        foreach ($sessionCart as $bienTheId => $item) {
            $existing = Database::fetchOne(
                "SELECT id, so_luong FROM gio_hang WHERE khach_hang_id = ? AND bien_the_id = ?",
                [$userId, $bienTheId]
            );
            if ($existing) {
                Database::execute(
                    "UPDATE gio_hang SET so_luong = so_luong + ? WHERE id = ?",
                    [$item['qty'], $existing['id']]
                );
            } else {
                Database::execute(
                    "INSERT INTO gio_hang (khach_hang_id, bien_the_id, so_luong) VALUES (?,?,?)",
                    [$userId, $bienTheId, $item['qty']]
                );
            }
        }
        Session::remove('cart'); // Xóa session cart sau khi merge
    }
}
