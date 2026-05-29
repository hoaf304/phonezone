<?php
class User extends Model
{
    protected static string $table = 'khach_hang';

    public static function findByEmail(string $email): array|false
    {
        return Database::fetchOne("SELECT * FROM khach_hang WHERE email = ?", [$email]);
    }

    public static function register(array $data): int
    {
        return static::create([
            'ho_ten'       => $data['ho_ten'],
            'email'        => $data['email'],
            'mat_khau'     => password_hash($data['mat_khau'], PASSWORD_BCRYPT, ['cost' => 12]),
            'so_dien_thoai'=> $data['so_dien_thoai'] ?? null,
            'trang_thai'   => 'hoat_dong',
        ]);
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public static function loginSession(array $user): void
    {
        Session::set('user_id',    $user['id']);
        Session::set('user_name',  $user['ho_ten']);
        Session::set('user_email', $user['email']);
    }

    public static function logout(): void
    {
        Session::remove('user_id');
        Session::remove('user_name');
        Session::remove('user_email');
    }

    public static function getAddresses(int $userId): array
    {
        return Database::fetchAll(
            "SELECT * FROM dia_chi_khach_hang WHERE khach_hang_id = ? ORDER BY la_mac_dinh DESC, id DESC",
            [$userId]
        );
    }
}
