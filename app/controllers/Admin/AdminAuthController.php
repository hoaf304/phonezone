<?php
class AdminAuthController extends Controller
{
    public function loginForm(): void
    {
        if (Session::has('admin_id')) { $this->redirect('admin/dashboard'); return; }
        // Render trang login đơn giản không dùng layout admin
        $this->view('admin.login');
    }

    public function login(): void
    {
        $this->verifyCsrf();
        $email   = trim($this->input('email', ''));
        $matKhau = $this->input('mat_khau', '');

        $admin = Database::fetchOne(
            "SELECT * FROM admin WHERE email = ? AND trang_thai = 1", [$email]
        );

        if (!$admin || !password_verify($matKhau, $admin['mat_khau'])) {
            Session::flash('error', 'Email hoặc mật khẩu không đúng.');
            $this->redirect('admin/dang-nhap'); return;
        }

        Session::set('admin_id',   $admin['id']);
        Session::set('admin_name', $admin['ho_ten']);
        Session::set('admin_role', $admin['vai_tro']);
        Database::execute("UPDATE admin SET last_login = NOW() WHERE id = ?", [$admin['id']]);

        $this->redirect('admin/dashboard');
    }

    public function logout(): void
    {
        Session::remove('admin_id');
        Session::remove('admin_name');
        Session::remove('admin_role');
        $this->redirect('admin/dang-nhap');
    }
}
