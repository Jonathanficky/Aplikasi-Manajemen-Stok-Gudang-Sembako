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
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['status']) && $_SESSION['status'] === 'sudah_login';
    }

    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    public static function hasFlash(): bool
    {
        return isset($_SESSION['flash']);
    }
}
