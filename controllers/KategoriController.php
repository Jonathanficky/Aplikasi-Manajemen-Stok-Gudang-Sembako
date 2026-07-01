<?php
class KategoriController
{
    public function index(): void
    {
        Auth::checkAdmin();
        $user = Auth::user();
        $kategoriList = Kategori::all();

        View::render('kategori/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'kategoriList' => $kategoriList,
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function simpan(): void
    {
        Auth::checkAdmin();
        $nama = trim($_POST['nama_kategori'] ?? '');
        if (empty($nama)) {
            Session::setFlash('error', 'Nama kategori harus diisi!');
            header("location:index.php?page=kategori");
            exit();
        }
        $kategori = new Kategori($_POST);
        $kategori->save();
        Session::setFlash('success', 'Kategori berhasil ditambahkan!');
        header("location:index.php?page=kategori");
        exit();
    }

    public function update(): void
    {
        Auth::checkAdmin();
        $nama = trim($_POST['nama_kategori'] ?? '');
        if (empty($nama)) {
            Session::setFlash('error', 'Nama kategori harus diisi!');
            header("location:index.php?page=kategori");
            exit();
        }
        $kategori = Kategori::find($_POST['id_kategori']);
        if ($kategori) {
            $kategori->nama_kategori = $_POST['nama_kategori'];
            $kategori->deskripsi = $_POST['deskripsi'];
            $kategori->save();
            Session::setFlash('success', 'Kategori berhasil diperbarui!');
        } else {
            Session::setFlash('error', 'Kategori tidak ditemukan!');
        }
        header("location:index.php?page=kategori");
        exit();
    }

    public function hapus(int $id): void
    {
        Auth::checkAdmin();
        $kategori = Kategori::find($id);
        if ($kategori) {
            $kategori->delete();
            Session::setFlash('success', 'Kategori berhasil dihapus!');
        } else {
            Session::setFlash('error', 'Kategori tidak ditemukan!');
        }
        header("location:index.php?page=kategori");
        exit();
    }
}
