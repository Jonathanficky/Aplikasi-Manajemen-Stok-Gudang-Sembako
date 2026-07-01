<?php
class DashboardController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $barangKritis = Barang::stokKritis();
        $jmlKritis = count($barangKritis);

        $koneksi = Database::getInstance()->getConnection();
        $qTrx = mysqli_query($koneksi, "SELECT t.tanggal, b.nama_barang, t.jumlah 
            FROM transaksi_masuk t JOIN barang b ON t.id_barang = b.id_barang 
            ORDER BY t.id_masuk DESC LIMIT 5");
        $transaksiTerbaru = [];
        while ($r = mysqli_fetch_assoc($qTrx)) {
            $transaksiTerbaru[] = $r;
        }

        $totalPenjualan = TransaksiKeluar::totalPenjualan();
        $totalPembelian = TransaksiMasuk::totalNilai();
        $totalAset = Barang::totalNilaiAsetAll();

        $kategoriStok = Kategori::allWithStokCount();
        $chartLabels = [];
        $chartData = [];
        $chartDetail = [];
        foreach ($kategoriStok as $ks) {
            $chartLabels[] = $ks['nama_kategori'];
            $chartData[] = (int) $ks['total_stok'];
            $chartDetail[] = [
                'nama' => $ks['nama_kategori'],
                'stok' => (int) $ks['total_stok'],
                'barang' => (int) $ks['total_barang'],
            ];
        }

        View::render('dashboard/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'jmlBarang' => Barang::total(),
            'jmlKritis' => $jmlKritis,
            'jmlMasuk' => TransaksiMasuk::total(),
            'jmlKeluar' => TransaksiKeluar::total(),
            'totalAset' => $totalAset,
            'totalPenjualan' => $totalPenjualan,
            'totalPembelian' => $totalPembelian,
            'labaRugi' => $totalPenjualan - $totalPembelian,
            'barangKritis' => $barangKritis,
            'transaksiTerbaru' => $transaksiTerbaru,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'chartDetail' => $chartDetail,
        ]);
    }
}
