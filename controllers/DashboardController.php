<?php
/**
 * ==============================================
 * Nama Anggota  : Ariyan
 * Nama File     : controllers/DashboardController.php
 * Deskripsi     : Controller untuk halaman dashboard (ringkasan & notifikasi)
 * ==============================================
 */
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

        $kategoriStok = Kategori::allWithStokCount();
        $chartLabels = [];
        $chartData = [];
        foreach ($kategoriStok as $ks) {
            $chartLabels[] = $ks['nama_kategori'];
            $chartData[] = (int) $ks['total_stok'];
        }

        $monthly = TransaksiMasuk::monthlySummary();
        $chartMonthlyLabels = [];
        $chartMonthlyData = [];
        foreach (array_reverse($monthly) as $m) {
            $chartMonthlyLabels[] = date('M Y', strtotime($m['bulan'] . '-01'));
            $chartMonthlyData[] = (int) $m['total_nilai'];
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
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'chartMonthlyLabels' => json_encode($chartMonthlyLabels),
            'chartMonthlyData' => json_encode($chartMonthlyData),
        ]);
    }
}
