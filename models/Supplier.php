<?php
class Supplier
{
    public $id_supplier;
    public $nama_supplier;
    public $no_telp;
    public $alamat;

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
        $q = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id_supplier DESC");
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
        $q = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
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
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM supplier WHERE id_supplier = ?");
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

    public function save(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $nama_supplier = mysqli_real_escape_string($koneksi, $this->nama_supplier);
        $no_telp = mysqli_real_escape_string($koneksi, $this->no_telp);
        $alamat = mysqli_real_escape_string($koneksi, $this->alamat);

        if ($this->id_supplier) {
            $id_supplier = (int) $this->id_supplier;
            $stmt = mysqli_prepare($koneksi, "UPDATE supplier SET nama_supplier=?, no_telp=?, alamat=? WHERE id_supplier=?");
            mysqli_stmt_bind_param($stmt, 'sssi', $nama_supplier, $no_telp, $alamat, $id_supplier);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO supplier (nama_supplier, no_telp, alamat) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sss', $nama_supplier, $no_telp, $alamat);
        }

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function delete(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $id_supplier = (int) $this->id_supplier;
        $stmt = mysqli_prepare($koneksi, "DELETE FROM supplier WHERE id_supplier = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id_supplier);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
}
