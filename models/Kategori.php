<?php
class Kategori
{
    public $id_kategori;
    public $nama_kategori;
    public $deskripsi;

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
        $q = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori DESC");
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
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM kategori WHERE id_kategori = ?");
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
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM kategori");
        $r = mysqli_fetch_assoc($q);
        return (int) ($r['jml'] ?? 0);
    }

    public function save(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $nama_kategori = mysqli_real_escape_string($koneksi, $this->nama_kategori);
        $deskripsi = mysqli_real_escape_string($koneksi, $this->deskripsi);

        if ($this->id_kategori) {
            $id_kategori = (int) $this->id_kategori;
            $stmt = mysqli_prepare($koneksi, "UPDATE kategori SET nama_kategori=?, deskripsi=? WHERE id_kategori=?");
            mysqli_stmt_bind_param($stmt, 'ssi', $nama_kategori, $deskripsi, $id_kategori);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, 'ss', $nama_kategori, $deskripsi);
        }

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function delete(): bool
    {
        $koneksi = Database::getInstance()->getConnection();
        $id_kategori = (int) $this->id_kategori;
        $stmt = mysqli_prepare($koneksi, "DELETE FROM kategori WHERE id_kategori = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id_kategori);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
}
