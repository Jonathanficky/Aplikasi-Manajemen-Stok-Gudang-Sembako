<?php
class TransaksiMasuk
{
    public $id_masuk;
    public $id_barang;
    public $id_supplier;
    public $tanggal;
    public $jumlah;
    public $harga_beli;
    public $status;
    public $keterangan;
    public $id_user;
    public $pencatat;
    public $nama_barang;
    public $nama_supplier;

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
        $q = mysqli_query($koneksi, "SELECT tm.*, b.nama_barang, s.nama_supplier
            FROM transaksi_masuk tm
            JOIN barang b ON tm.id_barang = b.id_barang
            JOIN supplier s ON tm.id_supplier = s.id_supplier
            ORDER BY tm.id_masuk DESC");
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
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM transaksi_masuk WHERE id_masuk = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);
            if ($r) {
                return new self($r);
            }
        }
        return null;
    }

    public static function total(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM transaksi_masuk");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public function save(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        mysqli_begin_transaction($koneksi);

        $tanggal = $this->tanggal;
        $id_barang = (int) $this->id_barang;
        $id_supplier = (int) $this->id_supplier;
        $jumlah = (int) $this->jumlah;
        $harga_beli = (int) ($this->harga_beli ?? 0);
        $status = $this->status ?? 'belum_lunas';
        $keterangan = mysqli_real_escape_string($koneksi, $this->keterangan ?? '');
        $id_user = (int) ($this->id_user ?? 0);
        $pencatat = mysqli_real_escape_string($koneksi, $this->pencatat ?? '');

        if ($this->id_masuk) {
            $id_masuk = (int) $this->id_masuk;

            $stmtLama = mysqli_prepare($koneksi, "SELECT id_barang, jumlah FROM transaksi_masuk WHERE id_masuk = ?");
            mysqli_stmt_bind_param($stmtLama, 'i', $id_masuk);
            mysqli_stmt_execute($stmtLama);
            $lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtLama));
            mysqli_stmt_close($stmtLama);

            $id_barang_lama = (int) ($lama['id_barang'] ?? 0);
            $jumlah_lama = (int) ($lama['jumlah'] ?? 0);

            $stmt1 = mysqli_prepare($koneksi, "UPDATE transaksi_masuk SET id_barang=?, id_supplier=?, tanggal=?, jumlah=?, harga_beli=?, status=?, keterangan=?, id_user=?, pencatat=? WHERE id_masuk=?");
            mysqli_stmt_bind_param($stmt1, 'iisiississ', $id_barang, $id_supplier, $tanggal, $jumlah, $harga_beli, $status, $keterangan, $id_user, $pencatat, $id_masuk);
            $q1 = mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);

            if ($id_barang_lama == $id_barang) {
                $selisih = $jumlah - $jumlah_lama;
                $stmt2 = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok + ? WHERE id_barang = ?");
                mysqli_stmt_bind_param($stmt2, 'ii', $selisih, $id_barang);
                $q2 = mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
                $q3 = true;
            } else {
                $stmt2 = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok - ? WHERE id_barang = ?");
                mysqli_stmt_bind_param($stmt2, 'ii', $jumlah_lama, $id_barang_lama);
                $q2 = mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
                $stmt3 = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok + ? WHERE id_barang = ?");
                mysqli_stmt_bind_param($stmt3, 'ii', $jumlah, $id_barang);
                $q3 = mysqli_stmt_execute($stmt3);
                mysqli_stmt_close($stmt3);
            }

            if ($q1 && $q2 && $q3) {
                mysqli_commit($koneksi);
                return true;
            }
            mysqli_rollback($koneksi);
            return false;
        } else {
            $stmt1 = mysqli_prepare($koneksi, "INSERT INTO transaksi_masuk (id_barang, id_supplier, tanggal, jumlah, harga_beli, status, keterangan, id_user, pencatat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt1, 'iississis', $id_barang, $id_supplier, $tanggal, $jumlah, $harga_beli, $status, $keterangan, $id_user, $pencatat);
            $q1 = mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);

            $stmt2 = mysqli_prepare($koneksi, "UPDATE barang SET stok = stok + ? WHERE id_barang = ?");
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
}
