<?php
class Barang
{
    public $id_barang;
    public $kode_barang;
    public $nama_barang;
    public $id_kategori;
    public $stok;
    public $stok_min;
    public $harga_beli;
    public $nama_kategori;

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
        $q = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori ORDER BY b.id_barang DESC");
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $result[] = new self($r);
            }
        }
        return $result;
    }

    public static function allAsc(): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $result = [];
        $q = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori ORDER BY b.nama_barang ASC");
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
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM barang WHERE id_barang = ?");
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

    public static function findByKode(string $kode): ?self
    {
        $koneksi = Database::getInstance()->getConnection();
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM barang WHERE kode_barang = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $kode);
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
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM barang");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public static function stokKritis(): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $result = [];
        $q = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori WHERE b.stok <= b.stok_min ORDER BY b.stok ASC");
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $result[] = new self($r);
            }
        }
        return $result;
    }

    public static function totalStokKritis(): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM barang WHERE stok <= stok_min");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public static function isKodeExists(string $kode): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) as jml FROM barang WHERE kode_barang = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $kode);
            mysqli_stmt_execute($stmt);
            $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);
            return ((int) ($r['jml'] ?? 0)) > 0;
        }
        return false;
    }

    public static function cekStok(int $id_barang): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $stmt = mysqli_prepare($koneksi, "SELECT stok FROM barang WHERE id_barang = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id_barang);
            mysqli_stmt_execute($stmt);
            $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);
            return (int) ($r['stok'] ?? 0);
        }
        return 0;
    }

    public function save()
    {
        $koneksi = Database::getInstance()->getConnection();
        $kode_barang = mysqli_real_escape_string($koneksi, $this->kode_barang);
        $nama_barang = mysqli_real_escape_string($koneksi, $this->nama_barang);
        $id_kategori = (int) ($this->id_kategori ?? 0);
        $stok = max(0, (int) ($this->stok ?? 0));
        $stok_min = (int) ($this->stok_min ?? 0);
        $harga_beli = (int) ($this->harga_beli ?? 0);

        if ($this->id_barang) {
            $id_barang = (int) $this->id_barang;
            $stmt = mysqli_prepare($koneksi, "UPDATE barang SET kode_barang=?, nama_barang=?, id_kategori=?, stok=?, stok_min=?, harga_beli=? WHERE id_barang=?");
            mysqli_stmt_bind_param($stmt, 'ssiiiii', $kode_barang, $nama_barang, $id_kategori, $stok, $stok_min, $harga_beli, $id_barang);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO barang (kode_barang, nama_barang, id_kategori, stok, stok_min, harga_beli) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssiiii', $kode_barang, $nama_barang, $id_kategori, $stok, $stok_min, $harga_beli);
        }

        $result = mysqli_stmt_execute($stmt);

        if ($result && !$this->id_barang) {
            $newId = (int) mysqli_insert_id($koneksi);
            mysqli_stmt_close($stmt);
            return $newId;
        }

        mysqli_stmt_close($stmt);
        return $result;
    }

    public function delete(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $id_barang = (int) $this->id_barang;
        $stmt = mysqli_prepare($koneksi, "DELETE FROM barang WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id_barang);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function isStokKritis(): bool
    {
        return $this->stok <= $this->stok_min;
    }

    public function totalNilaiAset(): float
    {
        return $this->stok * $this->harga_beli;
    }

    public static function byKategori(int $id_kategori): array
    {
        $koneksi = Database::getInstance()->getConnection();
        $result = [];
        $stmt = mysqli_prepare($koneksi, "SELECT b.*, k.nama_kategori FROM barang b LEFT JOIN kategori k ON b.id_kategori = k.id_kategori WHERE b.id_kategori = ? ORDER BY b.nama_barang ASC");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id_kategori);
            mysqli_stmt_execute($stmt);
            $q = mysqli_stmt_get_result($stmt);
            while ($r = mysqli_fetch_assoc($q)) {
                $result[] = new self($r);
            }
            mysqli_stmt_close($stmt);
        }
        return $result;
    }

    public static function totalByKategori(int $id_kategori): int
    {
        $koneksi = Database::getInstance()->getConnection();
        $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) as jml FROM barang WHERE id_kategori = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id_kategori);
            mysqli_stmt_execute($stmt);
            $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);
            return (int) ($r['jml'] ?? 0);
        }
        return 0;
    }

    public function namaKategori(): string
    {
        return $this->nama_kategori ?? '-';
    }
}
