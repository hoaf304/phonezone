<?php
// ============================================================
// PHONEZONE - Database (PDO Singleton)
// Kết nối MySQL một lần duy nhất trong toàn app
// ============================================================

class Database
{
    private static ?PDO $instance = null;

    // Ngăn khởi tạo trực tiếp
    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (APP_DEBUG) {
                    die('<div style="font-family:monospace;background:#1e293b;color:#f87171;padding:20px;border-radius:8px;margin:20px;">
                        <strong>❌ Lỗi kết nối Database</strong><br><br>'
                        . htmlspecialchars($e->getMessage()) .
                        '<br><br><small>Kiểm tra: XAMPP đã bật MySQL chưa? Thông tin .env có đúng không?</small>
                    </div>');
                }
                die('Lỗi hệ thống. Vui lòng thử lại sau.');
            }
        }
        return self::$instance;
    }

    // Shortcut: lấy PDO instance
    public static function pdo(): PDO
    {
        return self::getInstance();
    }

    // Thực thi query có tham số, trả về PDOStatement
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Lấy nhiều hàng
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    // Lấy một hàng
    public static function fetchOne(string $sql, array $params = []): array|false
    {
        return self::query($sql, $params)->fetch();
    }

    // Lấy một giá trị đơn
    public static function fetchScalar(string $sql, array $params = []): mixed
    {
        return self::query($sql, $params)->fetchColumn();
    }

    // Insert, trả về ID vừa thêm
    public static function insert(string $sql, array $params = []): int
    {
        self::query($sql, $params);
        return (int) self::pdo()->lastInsertId();
    }

    // Update/Delete, trả về số hàng bị ảnh hưởng
    public static function execute(string $sql, array $params = []): int
    {
        return self::query($sql, $params)->rowCount();
    }
}
