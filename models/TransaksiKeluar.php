<?php
class TransaksiKeluar
{
    public $id_keluar;
    public $id_barang;
    public $id_user;
    public $tanggal;
    public $jumlah;
    public $harga_jual;
    public $tujuan;
    public $keterangan;
    public $nama_barang;
    public $pencatat;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function all(): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $result = [];
        $q = mysqli_query($koneksi, "SELECT tk.*, b.nama_barang, u.nama_lengkap as pencatat
            FROM transaksi_keluar tk
            LEFT JOIN barang b ON tk.id_barang = b.id_barang
            LEFT JOIN users u ON tk.id_user = u.id_user
            ORDER BY tk.id_keluar DESC");
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $result[] = new self($r);
            }
        }
        return $result;
    }

    public static function find(int $id): ?self
    {
        $koneksi = Database::getInstance()->getConnection();
        $stmt = mysqli_prepare($koneksi, "SELECT tk.*, b.nama_barang, u.nama_lengkap as pencatat
            FROM transaksi_keluar tk
            LEFT JOIN barang b ON tk.id_barang = b.id_barang
            LEFT JOIN users u ON tk.id_user = u.id_user
            WHERE tk.id_keluar = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);
            if ($r) return new self($r);
        }
        return null;
    }

    public static function total(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM transaksi_keluar");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public static function totalPenjualan(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT COALESCE(SUM(jumlah * harga_jual), 0) as jml FROM transaksi_keluar");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public static function totalQty(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT SUM(jumlah) as jml FROM transaksi_keluar");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public static function monthlyPenjualan(): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $result = [];
        $q = mysqli_query($koneksi, "SELECT DATE_FORMAT(tanggal, '%Y-%m') as bulan, COUNT(*) as total_transaksi, SUM(jumlah) as total_qty, COALESCE(SUM(jumlah * harga_jual), 0) as total_nilai
            FROM transaksi_keluar
            GROUP BY bulan ORDER BY bulan ASC");
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $result[] = $r;
            }
        }
        return $result;
    }

    public static function monthlySummary(): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $result = [];
        $q = mysqli_query($koneksi, "SELECT DATE_FORMAT(tanggal, '%Y-%m') as bulan, COUNT(*) as total_transaksi, SUM(jumlah) as total_qty
            FROM transaksi_keluar
            GROUP BY bulan ORDER BY bulan DESC LIMIT 12");
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $result[] = $r;
            }
        }
        return $result;
    }

    public function simpan(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $id_barang = (int) $this->id_barang;
        $id_user = (int) $this->id_user;
        $tanggal = $this->tanggal;
        $jumlah = (int) $this->jumlah;
        $harga_jual = (int) ($this->harga_jual ?? 0);
        $tujuan = mysqli_real_escape_string($koneksi, $this->tujuan);
        $keterangan = mysqli_real_escape_string($koneksi, $this->keterangan ?? '');

        mysqli_begin_transaction($koneksi);

        $stmt1 = mysqli_prepare($koneksi, "INSERT INTO transaksi_keluar (id_barang, id_user, tanggal, jumlah, harga_jual, tujuan, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt1, 'iisiiss', $id_barang, $id_user, $tanggal, $jumlah, $harga_jual, $tujuan, $keterangan);
        $q1 = mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);

        $stmt2 = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok - ? WHERE id_barang = ? AND stok >= ?");
        mysqli_stmt_bind_param($stmt2, 'iii', $jumlah, $id_barang, $jumlah);
        $q2 = mysqli_stmt_execute($stmt2);
        $affected = mysqli_affected_rows($koneksi);
        mysqli_stmt_close($stmt2);

        if ($q1 && $q2 && $affected > 0) {
            mysqli_commit($koneksi);
            return true;
        }
        mysqli_rollback($koneksi);
        return false;
    }

}
