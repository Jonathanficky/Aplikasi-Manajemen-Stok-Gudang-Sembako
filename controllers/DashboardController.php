<?php
class DashboardController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $jmlKritis = Barang::totalStokKritis();
        $barangKritis = Barang::stokKritis();

        $koneksi = Database::getInstance()->getConnection();
        $qTrx = mysqli_query($koneksi, "SELECT t.tanggal, b.nama_barang, t.jumlah 
            FROM transaksi_masuk t JOIN barang b ON t.id_barang = b.id_barang 
            ORDER BY t.id_masuk DESC LIMIT 5");
        $transaksiTerbaru = [];
        while ($r = mysqli_fetch_assoc($qTrx)) {
            $transaksiTerbaru[] = $r;
        }

        View::render('dashboard/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'jmlBarang' => Barang::total(),
            'jmlKritis' => $jmlKritis,
            'jmlMasuk' => TransaksiMasuk::total(),
            'jmlKeluar' => TransaksiKeluar::total(),
            'barangKritis' => $barangKritis,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}
