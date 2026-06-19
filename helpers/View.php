<?php
class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "View tidak ditemukan: " . $viewPath;
        }
    }

    public static function notifStokKritis(): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok <= stok_min");
        $list = [];
        while ($r = mysqli_fetch_assoc($q)) {
            $list[] = $r;
        }
        return $list;
    }

    public static function jumlahNotifKritis(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM barang WHERE stok <= stok_min");
        $r = mysqli_fetch_assoc($q);
        return (int) $r['jml'];
    }
}
