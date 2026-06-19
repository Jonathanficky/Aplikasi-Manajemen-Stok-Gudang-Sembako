<?php
class Auth
{
    public static function checkLogin(): void
    {
        if (!Session::isLoggedIn()) {
            header("location:login.php");
            exit();
        }
    }

    public static function user(): array
    {
        return [
            'id_user'      => Session::get('id_user'),
            'nama_lengkap'  => Session::get('nama_lengkap'),
            'username'     => Session::get('username'),
            'role'         => Session::get('role'),
        ];
    }

    public static function namaUser(): string
    {
        return Session::get('nama_lengkap') ?? '';
    }

    public static function role(): string
    {
        return Session::get('role') ?? '';
    }

    public static function isAdmin(): bool
    {
        return Session::get('role') === 'admin';
    }
}
