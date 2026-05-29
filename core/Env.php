<?php
// ============================================================
// PHONEZONE - Env Helper
// Đọc file .env và lưu vào $_ENV
// ============================================================

class Env
{
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded || !file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            // Bỏ qua comment
            if (str_starts_with($line, '#')) continue;
            if (!str_contains($line, '=')) continue;

            [$key, $value] = array_map('trim', explode('=', $line, 2));
            // Bỏ dấu nháy nếu có
            $value = trim($value, '"\'');

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
        self::$loaded = true;
    }

    public static function get(string $key, string $default = ''): string
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
