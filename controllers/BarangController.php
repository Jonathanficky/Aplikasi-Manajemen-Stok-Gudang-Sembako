<?php
class BarangController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $barangList = Barang::all();
        $kategoriList = Kategori::all();

        View::render('barang/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'barangList' => $barangList,
            'kategoriList' => $kategoriList,
            'totalBarang' => count($barangList),
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function simpan(): void
    {
        Auth::checkLogin();
        $barang = new Barang($_POST);
        $barang->save();
        header("location:index.php?page=barang");
        exit();
    }

    public function update(): void
    {
        Auth::checkLogin();
        $barang = Barang::find($_POST['id_barang']);
        if ($barang) {
            $barang->kode_barang = $_POST['kode_barang'];
            $barang->nama_barang = $_POST['nama_barang'];
            $barang->id_kategori = $_POST['id_kategori'];
            $barang->stok = $_POST['stok'];
            $barang->stok_min = $_POST['stok_min'];
            $barang->harga_beli = $_POST['harga_beli'];
            $barang->save();
        }
        header("location:index.php?page=barang");
        exit();
    }

    public function hapus(int $id): void
    {
        Auth::checkLogin();
        $barang = Barang::find($id);
        if ($barang) {
            $barang->delete();
        }
        header("location:index.php?page=barang");
        exit();
    }
}
