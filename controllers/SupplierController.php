<?php
// Menampilkan daftar supplier
class SupplierController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $supplierList = Supplier::all();

        View::render('supplier/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'supplierList' => $supplierList,
            'totalSupplier' => count($supplierList),
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function simpan(): void
    {
        Auth::checkLogin();
        $supplier = new Supplier($_POST);
        $supplier->save();
        Session::setFlash('success', 'Supplier berhasil ditambahkan!');
        header("location:index.php?page=supplier");
        exit();
    }

    public function update(): void
    {
        Auth::checkLogin();
        $supplier = Supplier::find($_POST['id_supplier']);
        if ($supplier) {
            $supplier->nama_supplier = $_POST['nama_supplier'];
            $supplier->no_telp = $_POST['no_telp'];
            $supplier->alamat = $_POST['alamat'];
            $supplier->save();
            Session::setFlash('success', 'Supplier berhasil diperbarui!');
        } else {
            Session::setFlash('error', 'Supplier tidak ditemukan!');
        }
        header("location:index.php?page=supplier");
        exit();
    }

    public function hapus(int $id): void
    {
        Auth::checkLogin();
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
            Session::setFlash('success', 'Supplier berhasil dihapus!');
        } else {
            Session::setFlash('error', 'Supplier tidak ditemukan!');
        }
        header("location:index.php?page=supplier");
        exit();
    }
}
