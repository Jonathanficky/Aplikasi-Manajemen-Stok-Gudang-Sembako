<?php
class KategoriController
{
    public function index(): void
    {
        Auth::checkLogin();
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
        Auth::checkLogin();
        $kategori = new Kategori($_POST);
        $kategori->save();
        header("location:index.php?page=kategori");
        exit();
    }

    public function update(): void
    {
        Auth::checkLogin();
        $kategori = Kategori::find($_POST['id_kategori']);
        if ($kategori) {
            $kategori->nama_kategori = $_POST['nama_kategori'];
            $kategori->deskripsi = $_POST['deskripsi'];
            $kategori->save();
        }
        header("location:index.php?page=kategori");
        exit();
    }

    public function hapus(int $id): void
    {
        Auth::checkLogin();
        $kategori = Kategori::find($id);
        if ($kategori) {
            $kategori->delete();
        }
        header("location:index.php?page=kategori");
        exit();
    }
}
