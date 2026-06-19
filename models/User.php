<?php
class User
{
    public $id_user;
    public $username;
    public $password;
    public $nama_lengkap;
    public $role;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function login(string $username, string $password): ?self
    {
        $koneksi = Database::getInstance()->getConnection();
        $username = mysqli_real_escape_string($koneksi, $username);

        $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
        if ($r = mysqli_fetch_assoc($q)) {
            if (password_verify($password, $r['password'])) {
                return new self($r);
            }
        }
        return null;
    }

    public function simpanSession(): void
    {
        $_SESSION['id_user'] = $this->id_user;
        $_SESSION['nama_lengkap'] = $this->nama_lengkap;
        $_SESSION['username'] = $this->username;
        $_SESSION['role'] = $this->role;
        $_SESSION['status'] = 'sudah_login';
        session_regenerate_id(true);
    }
}
