<?php
class TransaksiKeluarController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $keluarList = TransaksiKeluar::all();

        $koneksi = Database::getInstance()->getConnection();
        $qBrg = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
        $barangStok = [];
        while ($r = mysqli_fetch_assoc($qBrg)) {
            $barangStok[] = new Barang($r);
        }

        View::render('transaksi_keluar/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'keluarList' => $keluarList,
            'barangStok' => $barangStok,
            'totalNota' => TransaksiKeluar::total(),
            'totalQtyKeluar' => TransaksiKeluar::totalQty(),
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function simpan(): void
    {
        Auth::checkLogin();
        $tk = new TransaksiKeluar($_POST);
        $tk->id_user = Auth::user()['id_user'];

        if ($tk->simpan()) {
            echo "<script>alert('Berhasil! Stok barang telah berkurang.'); window.location.href='index.php?page=transaksi_keluar';</script>";
        } else {
            echo "<script>alert('Gagal! Stok tidak mencukupi.'); window.location.href='index.php?page=transaksi_keluar';</script>";
        }
    }
}
