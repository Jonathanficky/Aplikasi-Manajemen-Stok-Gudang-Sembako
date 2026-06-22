<?php
class AuthController
{
    public function login(): void
    {
        if (Session::isLoggedIn()) {
            header("location:index.php?page=dashboard");
            exit();
        }
        View::render('auth/login');
    }

    public function prosesLogin(): void
    {
        if (!isset($_POST['login'])) {
            header("location:index.php?page=login");
            exit();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = User::login($username, $password);

        if ($user) {
            Session::getFlash(); // hapus flash lama dari login gagal sebelumnya
            $user->simpanSession();
            header("location:index.php?page=dashboard");
            exit();
        } else {
            Session::setFlash('error', 'Login Gagal! Username atau Password salah.');
            header("location:index.php?page=login");
        }
        exit();
    }

    public function logout(): void
    {
        Session::destroy();
        header("location:index.php?page=login");
        exit();
    }
}
