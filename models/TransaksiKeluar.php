<?php
class TransaksiKeluar
{
    public $id_keluar;
    public $id_barang;
    public $id_user;
    public $tanggal;
    public $jumlah;
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

    public static function total(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM transaksi_keluar");
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

    public function simpan(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $id_barang = (int) $this->id_barang;
        $id_user = (int) $this->id_user;
        $tanggal = $this->tanggal;
        $jumlah = (int) $this->jumlah;
        $tujuan = mysqli_real_escape_string($koneksi, $this->tujuan);
        $keterangan = mysqli_real_escape_string($koneksi, $this->keterangan ?? '');

        $cek_stok = Barang::cekStok($id_barang);
        if ($cek_stok < $jumlah) {
            return false;
        }

        mysqli_begin_transaction($koneksi);

        $stmt1 = mysqli_prepare($koneksi, "INSERT INTO transaksi_keluar (id_barang, id_user, tanggal, jumlah, tujuan, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt1, 'iisiis', $id_barang, $id_user, $tanggal, $jumlah, $tujuan, $keterangan);
        $q1 = mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);

        $stmt2 = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok - ? WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt2, 'ii', $jumlah, $id_barang);
        $q2 = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        if ($q1 && $q2) {
            mysqli_commit($koneksi);
            return true;
        }
        mysqli_rollback($koneksi);
        return false;
    }
}
