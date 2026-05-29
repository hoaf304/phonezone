<?php
class AdminController extends Controller
{
    public function __construct()
    {
        $this->requireAdmin();
    }

    // Override render để dùng admin layout
    protected function render(string $view, array $data = [], string $layout = 'layouts.admin_layout'): void
    {
        extract($data);
        $viewFile = VIEW_PATH . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) die("View không tồn tại: $viewFile");

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = VIEW_PATH . str_replace('.', '/', $layout) . '.php';
        if (file_exists($layoutFile)) require $layoutFile;
        else echo $content;
    }
}
