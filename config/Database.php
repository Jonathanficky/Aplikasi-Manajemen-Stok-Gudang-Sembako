<?php
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host     = "localhost";
        $user     = "root";
        $password = "";
        $database = "db_stok_gudang";

        $this->connection = mysqli_connect($host, $user, $password, $database);

        if (mysqli_connect_error()) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
