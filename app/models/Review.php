<?php
class Review extends Model
{
    protected static string $table = 'danh_gia';

    public static function getByProduct(int $productId, int $limit = 10): array
    {
        $sql = "SELECT dg.*, kh.ho_ten AS ten_hien_thi
                FROM danh_gia dg
                LEFT JOIN khach_hang kh ON kh.id = dg.khach_hang_id
                WHERE dg.san_pham_id = ? AND dg.trang_thai = 'da_duyet'
                ORDER BY dg.created_at DESC LIMIT ?";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$productId, $limit]);
        return $stmt->fetchAll();
    }

    public static function getStats(int $productId): array
    {
        $sql = "SELECT
                    COUNT(*)                        AS tong,
                    ROUND(AVG(so_sao), 1)           AS avg_sao,
                    SUM(so_sao = 5)                 AS sao5,
                    SUM(so_sao = 4)                 AS sao4,
                    SUM(so_sao = 3)                 AS sao3,
                    SUM(so_sao = 2)                 AS sao2,
                    SUM(so_sao = 1)                 AS sao1
                FROM danh_gia
                WHERE san_pham_id = ? AND trang_thai = 'da_duyet'";
        return Database::fetchOne($sql, [$productId]) ?: [
            'tong' => 0, 'avg_sao' => 0,
            'sao5' => 0, 'sao4' => 0, 'sao3' => 0, 'sao2' => 0, 'sao1' => 0,
        ];
    }

    public static function store(array $data): int
    {
        return static::create([
            'san_pham_id'   => $data['san_pham_id'],
            'khach_hang_id' => $data['khach_hang_id'] ?? null,
            'don_hang_id'   => $data['don_hang_id']   ?? null,
            'ho_ten'        => $data['ho_ten'],
            'so_sao'        => min(5, max(1, (int)$data['so_sao'])),
            'tieu_de'       => $data['tieu_de']  ?? '',
            'noi_dung'      => $data['noi_dung'] ?? '',
            'trang_thai'    => 'cho_duyet',
        ]);
    }
}
