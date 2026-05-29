<?php
// ============================================================
// PHONEZONE - Router
// Phân tích URL → gọi đúng Controller@method
// ============================================================

class Router
{
    private array $routes = [];

    // Đăng ký route GET
    public function get(string $path, string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    // Đăng ký route POST
    public function post(string $path, string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    // Đăng ký route GET + POST
    public function any(string $path, string $handler): void
    {
        $this->addRoute('GET',  $path, $handler);
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, string $handler): void
    {
        // Chuyển :slug, :id ... thành regex capture group
        $pattern = preg_replace('/\/:([a-z_]+)/', '/(?P<$1>[^/]+)', $path);
        $pattern = '@^' . $pattern . '$@';

        $this->routes[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $this->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Lọc chỉ lấy named params (bỏ index số)
                $params = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);

                [$controllerName, $action] = $this->parseHandler($route['handler']);

                if (!class_exists($controllerName)) {
                    $this->abort(500, "Controller '$controllerName' không tồn tại.");
                    return;
                }

                $controller = new $controllerName();

                if (!method_exists($controller, $action)) {
                    $this->abort(500, "Method '$action' không tồn tại trong '$controllerName'.");
                    return;
                }

                // Gọi controller method với params từ URL
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Không tìm thấy route
        $this->abort(404);
    }

    // Lấy URI sạch (bỏ query string, bỏ tiền tố /phonezone/)
    private function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Bỏ subfolder nếu chạy trong /phonezone/
        $base = parse_url(APP_URL, PHP_URL_PATH) ?? '';
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        return '/' . trim($uri, '/') ?: '/';
    }

    // "HomeController@index" → ['HomeController', 'index']
    private function parseHandler(string $handler): array
    {
        $parts = explode('@', $handler, 2);
        return count($parts) === 2 ? $parts : [$parts[0], 'index'];
    }

    private function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $titles = [404 => '404 – Không tìm thấy trang', 500 => '500 – Lỗi hệ thống'];
        $title  = $titles[$code] ?? "Lỗi $code";
        echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
              <title>$title</title>
              <style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;
              height:100vh;margin:0;background:#0f172a;color:#e2e8f0;flex-direction:column;}
              h1{font-size:4rem;color:#3b82f6;margin:0}p{color:#64748b}</style></head>
              <body><h1>$code</h1>
              <p>" . ($message ?: ($code === 404 ? 'Trang bạn tìm không tồn tại.' : 'Lỗi máy chủ.')) . "</p>
              <a href='" . APP_URL . "' style='color:#3b82f6;margin-top:16px'>← Về trang chủ</a>
              </body></html>";
    }
}
