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
            $user->simpanSession();
            header("location:index.php?page=dashboard");
        } else {
            echo "<script>
                    alert('Login Gagal! Username atau Password yang Anda masukkan salah.');
                    window.location.href='index.php?page=login';
                  </script>";
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
