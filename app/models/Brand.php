<?php
class Brand extends Model
{
    protected static string $table = 'hang_san_xuat';

    public static function getAllVisible(): array
    {
        return Database::fetchAll("SELECT * FROM hang_san_xuat WHERE an_hien = 1 ORDER BY ten ASC");
    }

    public static function getBySlug(string $slug): array|false
    {
        return Database::fetchOne("SELECT * FROM hang_san_xuat WHERE slug = ? AND an_hien = 1", [$slug]);
    }
}
