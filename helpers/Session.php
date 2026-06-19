<?php
/**
 * ==============================================
 * Nama Anggota  : Ariyan
 * Nama File     : helpers/Session.php
 * Deskripsi     : Manajemen session (start, set, get, destroy, isLoggedIn)
 * ==============================================
 */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['status']) && $_SESSION['status'] === 'sudah_login';
    }
}
