<?php
class Category extends Model
{
    protected static string $table = 'danh_muc';

    public static function getAllVisible(): array
    {
        return Database::fetchAll("SELECT * FROM danh_muc WHERE an_hien = 1 ORDER BY thu_tu ASC");
    }

    public static function getBySlug(string $slug): array|false
    {
        return Database::fetchOne("SELECT * FROM danh_muc WHERE slug = ? AND an_hien = 1", [$slug]);
    }
}
