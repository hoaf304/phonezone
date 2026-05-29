<?php
// ============================================================
// PHONEZONE - Base Model
// Tất cả model kế thừa class này
// ============================================================

abstract class Model
{
    // Mỗi model con khai báo tên bảng và primary key
    protected static string $table  = '';
    protected static string $pk     = 'id';

    // Lấy PDO
    protected static function db(): PDO
    {
        return Database::pdo();
    }

    // ---- CRUD cơ bản ----

    // Lấy tất cả bản ghi
    public static function all(string $orderBy = 'id DESC'): array
    {
        $sql = "SELECT * FROM `" . static::$table . "` ORDER BY $orderBy";
        return Database::fetchAll($sql);
    }

    // Lấy theo ID
    public static function find(int $id): array|false
    {
        $sql = "SELECT * FROM `" . static::$table . "` WHERE `" . static::$pk . "` = ?";
        return Database::fetchOne($sql, [$id]);
    }

    // Lấy theo điều kiện (một hàng)
    public static function findBy(string $column, mixed $value): array|false
    {
        $sql = "SELECT * FROM `" . static::$table . "` WHERE `$column` = ? LIMIT 1";
        return Database::fetchOne($sql, [$value]);
    }

    // Lấy nhiều hàng theo điều kiện
    public static function where(string $column, mixed $value, string $orderBy = 'id DESC'): array
    {
        $sql = "SELECT * FROM `" . static::$table . "` WHERE `$column` = ? ORDER BY $orderBy";
        return Database::fetchAll($sql, [$value]);
    }

    // Đếm bản ghi
    public static function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `" . static::$table . "`";
        if ($where) $sql .= " WHERE $where";
        return (int) Database::fetchScalar($sql, $params);
    }

    // Insert bản ghi mới, trả về ID
    public static function create(array $data): int
    {
        $cols    = implode('`,`', array_keys($data));
        $holders = implode(',', array_fill(0, count($data), '?'));
        $sql     = "INSERT INTO `" . static::$table . "` (`$cols`) VALUES ($holders)";
        return Database::insert($sql, array_values($data));
    }

    // Update theo ID, trả về số hàng bị ảnh hưởng
    public static function update(int $id, array $data): int
    {
        $sets = implode(',', array_map(fn($k) => "`$k` = ?", array_keys($data)));
        $sql  = "UPDATE `" . static::$table . "` SET $sets WHERE `" . static::$pk . "` = ?";
        return Database::execute($sql, [...array_values($data), $id]);
    }

    // Delete theo ID
    public static function delete(int $id): int
    {
        $sql = "DELETE FROM `" . static::$table . "` WHERE `" . static::$pk . "` = ?";
        return Database::execute($sql, [$id]);
    }

    // ---- Phân trang ----
    public static function paginate(int $page = 1, int $perPage = ITEMS_PER_PAGE,
                                    string $where = '', array $params = [],
                                    string $orderBy = 'id DESC'): array
    {
        $total  = self::count($where, $params);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM `" . static::$table . "`";
        if ($where) $sql .= " WHERE $where";
        $sql .= " ORDER BY $orderBy LIMIT $perPage OFFSET $offset";

        $items = Database::fetchAll($sql, $params);

        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
            'from'         => $offset + 1,
            'to'           => min($offset + $perPage, $total),
        ];
    }
}
