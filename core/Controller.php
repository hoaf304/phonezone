<?php
// ============================================================
// PHONEZONE - Base Controller
// Tất cả controller kế thừa class này
// ============================================================

class Controller
{
    // Render một view, truyền data vào
    protected function view(string $view, array $data = []): void
    {
        // Giải nén array thành biến (vd: $data['title'] → $title)
        extract($data);

        $viewFile = VIEW_PATH . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            die("View không tồn tại: <code>$viewFile</code>");
        }

        require $viewFile;
    }

    // Render view trong layout (header + content + footer)
    protected function render(string $view, array $data = [], string $layout = 'layouts.main'): void
    {
        // Gói nội dung view vào buffer
        extract($data);
        $viewFile = VIEW_PATH . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            die("View không tồn tại: <code>$viewFile</code>");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render layout với $content đã có
        $layoutFile = VIEW_PATH . str_replace('.', '/', $layout) . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    // Redirect đến URL
    protected function redirect(string $url): void
    {
        // Tự thêm APP_URL nếu là đường dẫn tương đối
        if (!str_starts_with($url, 'http')) {
            $url = APP_URL . '/' . ltrim($url, '/');
        }
        header("Location: $url");
        exit;
    }

    // Redirect về trang trước
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL;
        $this->redirect($referer);
    }

    // Trả về JSON (dùng cho AJAX)
    protected function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Kiểm tra CSRF token (POST request)
    protected function verifyCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($token)) {
            Session::flash('error', 'Phiên làm việc hết hạn. Vui lòng thử lại.');
            $this->back();
        }
    }

    // Yêu cầu đăng nhập (khách hàng)
    protected function requireAuth(): void
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            $this->redirect('dang-nhap');
        }
    }

    // Yêu cầu đăng nhập admin
    protected function requireAdmin(): void
    {
        if (!Session::has('admin_id')) {
            $this->redirect('admin/dang-nhap');
        }
    }

    // Lấy dữ liệu POST đã sanitize
    protected function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    // Lấy tất cả POST data đã sanitize
    protected function inputs(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->input($key);
        }
        return $result;
    }
}
