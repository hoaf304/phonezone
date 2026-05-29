<?php
// ============================================================
// PHONEZONE - Session Helper
// ============================================================

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(Env::get('SESSION_NAME', 'phonezone_session'));
            session_set_cookie_params([
                'lifetime' => (int) Env::get('SESSION_LIFETIME', '7200'),
                'path'     => '/',
                'secure'   => false, // true nếu dùng HTTPS
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }

        // Tạo CSRF token nếu chưa có
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    // Đặt giá trị
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    // Lấy giá trị
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    // Kiểm tra tồn tại
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    // Xóa key
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    // Xóa toàn bộ session
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    // ---- Flash Message ----
    // Đặt flash (hiển thị một lần rồi tự xóa)
    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    // Lấy flash và xóa luôn
    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    // Kiểm tra flash tồn tại
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    // ---- CSRF ----
    public static function csrfToken(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }

    public static function verifyCsrf(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    // Tạo hidden input CSRF cho form
    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::csrfToken() . '">';
    }
}
