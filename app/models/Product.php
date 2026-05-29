<?php
// ============================================================
// PHONEZONE – Product Model
// ============================================================

class Product extends Model
{
    protected static string $table = 'san_pham';

    // ---- Trang chủ ----

    // Lấy sản phẩm flash sale
    public static function getFlashSale(int $limit = 8): array
    {
        $sql = "
            SELECT sp.*, h.ten AS hang_ten, h.slug AS hang_slug,
                   bt.gia_ban, bt.gia_khuyen_mai, bt.id AS bien_the_id,
                   bt.mau_sac, bt.dung_luong,
                   ROUND((bt.gia_ban - bt.gia_khuyen_mai) / bt.gia_ban * 100) AS pct_giam,
                   COALESCE(dg.avg_sao, 0) AS avg_sao,
                   COALESCE(dg.so_danh_gia, 0) AS so_danh_gia
            FROM san_pham sp
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            JOIN bien_the_san_pham bt ON bt.san_pham_id = sp.id
                AND bt.gia_khuyen_mai IS NOT NULL
                AND bt.an_hien = 1
            LEFT JOIN (
                SELECT san_pham_id,
                       ROUND(AVG(so_sao),1) AS avg_sao,
                       COUNT(*) AS so_danh_gia
                FROM danh_gia
                WHERE trang_thai = 'da_duyet'
                GROUP BY san_pham_id
            ) dg ON dg.san_pham_id = sp.id
            WHERE sp.an_hien = 1
            ORDER BY pct_giam DESC, sp.luot_xem DESC
            LIMIT :limit
        ";

        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Lấy sản phẩm nổi bật
    public static function getFeatured(int $limit = 8): array
    {
        $sql = "
            SELECT sp.*, h.ten AS hang_ten, h.slug AS hang_slug,
                   bt.gia_ban, bt.gia_khuyen_mai, bt.id AS bien_the_id,
                   COALESCE(dg.avg_sao, 0) AS avg_sao,
                   COALESCE(dg.so_danh_gia, 0) AS so_danh_gia
            FROM san_pham sp
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            JOIN (
                SELECT san_pham_id,
                       MIN(COALESCE(gia_khuyen_mai, gia_ban)) AS gia_khuyen_mai,
                       MIN(gia_ban) AS gia_ban,
                       MIN(id) AS id
                FROM bien_the_san_pham
                WHERE an_hien = 1
                GROUP BY san_pham_id
            ) bt ON bt.san_pham_id = sp.id
            LEFT JOIN (
                SELECT san_pham_id,
                       ROUND(AVG(so_sao),1) AS avg_sao,
                       COUNT(*) AS so_danh_gia
                FROM danh_gia
                WHERE trang_thai = 'da_duyet'
                GROUP BY san_pham_id
            ) dg ON dg.san_pham_id = sp.id
            WHERE sp.an_hien = 1
              AND sp.noi_bat = 1
            ORDER BY sp.luot_xem DESC
            LIMIT :limit
        ";

        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Lấy sản phẩm bán chạy
    public static function getBestSeller(int $limit = 8): array
    {
        $sql = "
            SELECT sp.*, h.ten AS hang_ten, h.slug AS hang_slug,
                   bt.gia_ban, bt.gia_khuyen_mai,
                   COALESCE(dg.avg_sao, 0) AS avg_sao,
                   COALESCE(dg.so_danh_gia, 0) AS so_danh_gia
            FROM san_pham sp
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            JOIN (
                SELECT san_pham_id,
                       MIN(COALESCE(gia_khuyen_mai, gia_ban)) AS gia_khuyen_mai,
                       MIN(gia_ban) AS gia_ban
                FROM bien_the_san_pham
                WHERE an_hien = 1
                GROUP BY san_pham_id
            ) bt ON bt.san_pham_id = sp.id
            LEFT JOIN (
                SELECT san_pham_id,
                       ROUND(AVG(so_sao),1) AS avg_sao,
                       COUNT(*) AS so_danh_gia
                FROM danh_gia
                WHERE trang_thai = 'da_duyet'
                GROUP BY san_pham_id
            ) dg ON dg.san_pham_id = sp.id
            WHERE sp.an_hien = 1
              AND sp.ban_chay = 1
            ORDER BY sp.luot_xem DESC
            LIMIT :limit
        ";

        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // ---- Danh sách sản phẩm ----
    public static function getList(
        array $filters = [],
        int $page = 1,
        int $perPage = ITEMS_PER_PAGE
    ): array {

        $where = ['sp.an_hien = 1'];
        $params = [];

        // Search
        if (!empty($filters['q'])) {
            $where[] = '(sp.ten LIKE :q1 OR h.ten LIKE :q2)';
            $params[':q1'] = '%' . $filters['q'] . '%';
            $params[':q2'] = '%' . $filters['q'] . '%';
        }

        // Danh mục
        if (!empty($filters['danh_muc'])) {
            $where[] = 'dm.slug = :danh_muc';
            $params[':danh_muc'] = $filters['danh_muc'];
        }

        // Hãng
        if (!empty($filters['hang'])) {
            $where[] = 'h.slug = :hang';
            $params[':hang'] = $filters['hang'];
        }

        // Giá từ
        if (!empty($filters['gia_tu'])) {
            $where[] = 'bt.gia_hien_thi >= :gia_tu';
            $params[':gia_tu'] = (int)$filters['gia_tu'];
        }

        // Giá đến
        if (!empty($filters['gia_den'])) {
            $where[] = 'bt.gia_hien_thi <= :gia_den';
            $params[':gia_den'] = (int)$filters['gia_den'];
        }

        // Sort
        $orderMap = [
            'moi_nhat' => 'sp.created_at DESC',
            'ban_chay' => 'sp.luot_xem DESC',
            'gia_tang' => 'bt.gia_hien_thi ASC',
            'gia_giam' => 'bt.gia_hien_thi DESC',
            'danh_gia' => 'dg.avg_sao DESC',
        ];

        $orderBy = $orderMap[$filters['sort'] ?? '']
            ?? 'sp.created_at DESC';

        $whereStr = implode(' AND ', $where);

        $offset = ($page - 1) * $perPage;

        $baseSql = "
            FROM san_pham sp

            JOIN hang_san_xuat h
                ON h.id = sp.hang_id

            JOIN danh_muc dm
                ON dm.id = sp.danh_muc_id

            JOIN (
                SELECT san_pham_id,
                       MIN(COALESCE(gia_khuyen_mai, gia_ban)) AS gia_hien_thi,
                       MIN(gia_ban) AS gia_ban,
                       MIN(gia_khuyen_mai) AS gia_khuyen_mai
                FROM bien_the_san_pham
                WHERE an_hien = 1
                GROUP BY san_pham_id
            ) bt ON bt.san_pham_id = sp.id

            LEFT JOIN (
                SELECT san_pham_id,
                       ROUND(AVG(so_sao),1) AS avg_sao,
                       COUNT(*) AS so_danh_gia
                FROM danh_gia
                WHERE trang_thai = 'da_duyet'
                GROUP BY san_pham_id
            ) dg ON dg.san_pham_id = sp.id

            WHERE $whereStr
        ";

        // Count total
        $countStmt = Database::pdo()->prepare("
            SELECT COUNT(*)
            $baseSql
        ");

        $countStmt->execute($params);

        $total = (int)$countStmt->fetchColumn();

        // Get items
        $sql = "
            SELECT sp.*,
                   h.ten AS hang_ten,
                   h.slug AS hang_slug,

                   dm.ten AS danh_muc_ten,
                   dm.slug AS danh_muc_slug,

                   bt.gia_ban,
                   bt.gia_khuyen_mai,

                   COALESCE(dg.avg_sao, 0) AS avg_sao,
                   COALESCE(dg.so_danh_gia, 0) AS so_danh_gia

            $baseSql

            ORDER BY $orderBy

            LIMIT :limit OFFSET :offset
        ";

        $stmt = Database::pdo()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }

    // ---- Chi tiết sản phẩm ----

    public static function getBySlug(string $slug): array|false
    {
        return Database::fetchOne(
            "
            SELECT sp.*,
                   h.ten AS hang_ten,
                   h.slug AS hang_slug,
                   dm.ten AS danh_muc_ten,
                   dm.slug AS danh_muc_slug

            FROM san_pham sp

            JOIN hang_san_xuat h
                ON h.id = sp.hang_id

            JOIN danh_muc dm
                ON dm.id = sp.danh_muc_id

            WHERE sp.slug = ?
              AND sp.an_hien = 1
            ",
            [$slug]
        );
    }

    // Biến thể sản phẩm
    public static function getVariants(int $productId): array
    {
        return Database::fetchAll(
            "
            SELECT *
            FROM bien_the_san_pham
            WHERE san_pham_id = ?
              AND an_hien = 1
            ORDER BY gia_ban ASC
            ",
            [$productId]
        );
    }

    // Hình ảnh sản phẩm
    public static function getImages(int $productId): array
    {
        return Database::fetchAll(
            "
            SELECT *
            FROM hinh_anh_san_pham
            WHERE san_pham_id = ?
            ORDER BY thu_tu ASC
            ",
            [$productId]
        );
    }

    // Thông số kỹ thuật
    public static function getSpecs(int $productId): array
    {
        return Database::fetchAll(
            "
            SELECT *
            FROM thong_so_ky_thuat
            WHERE san_pham_id = ?
            ORDER BY thu_tu ASC
            ",
            [$productId]
        );
    }

    // Sản phẩm liên quan
    public static function getRelated(
        int $categoryId,
        int $excludeId,
        int $limit = 4
    ): array {

        $sql = "
            SELECT sp.*,
                   h.ten AS hang_ten,
                   bt.gia_ban,
                   bt.gia_khuyen_mai,
                   COALESCE(dg.avg_sao, 0) AS avg_sao

            FROM san_pham sp

            JOIN hang_san_xuat h
                ON h.id = sp.hang_id

            JOIN (
                SELECT san_pham_id,
                       MIN(COALESCE(gia_khuyen_mai, gia_ban)) AS gia_khuyen_mai,
                       MIN(gia_ban) AS gia_ban
                FROM bien_the_san_pham
                WHERE an_hien = 1
                GROUP BY san_pham_id
            ) bt ON bt.san_pham_id = sp.id

            LEFT JOIN (
                SELECT san_pham_id,
                       ROUND(AVG(so_sao),1) AS avg_sao
                FROM danh_gia
                WHERE trang_thai = 'da_duyet'
                GROUP BY san_pham_id
            ) dg ON dg.san_pham_id = sp.id

            WHERE sp.danh_muc_id = ?
              AND sp.id != ?
              AND sp.an_hien = 1

            ORDER BY RAND()

            LIMIT ?
        ";

        $stmt = Database::pdo()->prepare($sql);

        $stmt->execute([
            $categoryId,
            $excludeId,
            $limit
        ]);

        return $stmt->fetchAll();
    }

    // Tăng lượt xem
    public static function incrementView(int $id): void
    {
        Database::execute(
            "UPDATE san_pham SET luot_xem = luot_xem + 1 WHERE id = ?",
            [$id]
        );
    }
}