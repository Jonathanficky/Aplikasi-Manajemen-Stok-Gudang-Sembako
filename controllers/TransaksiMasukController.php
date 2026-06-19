<?php
class TransaksiMasukController
{
    public function index(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $transaksiList = TransaksiMasuk::all();
        $barangList = Barang::all();
        $supplierList = Supplier::allAsc();

        $totalFakturBulanIni = 0;
        $totalHutangBerjalan = 0;
        $fakturJatuhTempo = 0;
        $now = new DateTime();

        foreach ($transaksiList as $row) {
            $subtotal = $row->jumlah * ($row->harga_beli ?? 0);
            $totalFakturBulanIni += $subtotal;

            if ($row->status === 'belum_lunas') {
                $totalHutangBerjalan += $subtotal;
                $fakturJatuhTempo++;
            } elseif ($row->status === 'cicilan') {
                $totalHutangBerjalan += $subtotal * 0.3;
            }
        }

        View::render('transaksi_masuk/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'transaksiList' => $transaksiList,
            'barangList' => $barangList,
            'supplierList' => $supplierList,
            'totalFakturBulanIni' => $totalFakturBulanIni,
            'totalHutangBerjalan' => $totalHutangBerjalan,
            'fakturJatuhTempo' => $fakturJatuhTempo,
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function simpan(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $tm = new TransaksiMasuk($_POST);
        $tm->id_user = (int) $user['id_user'];
        $tm->pencatat = $user['nama_lengkap'];
        $tm->status = $_POST['status'] ?? 'belum_lunas';

        if ($tm->save()) {
            echo "<script>alert('Berhasil Simpan Faktur!'); window.location.href='index.php?page=transaksi_masuk';</script>";
        } else {
            echo "<script>alert('Gagal Simpan Faktur!');</script>";
        }
    }

    public function update(): void
    {
        Auth::checkLogin();
        $user = Auth::user();
        $tm = TransaksiMasuk::find((int) ($_POST['id_masuk'] ?? 0));
        if ($tm) {
            $tm->id_barang = (int) ($_POST['id_barang'] ?? 0);
            $tm->id_supplier = (int) ($_POST['id_supplier'] ?? 0);
            $tm->tanggal = $_POST['tanggal'] ?? '';
            $tm->jumlah = (int) ($_POST['jumlah'] ?? 0);
            $tm->harga_beli = (int) ($_POST['harga_beli'] ?? 0);
            $tm->status = $_POST['status'] ?? 'belum_lunas';
            $tm->keterangan = $_POST['keterangan'] ?? '';
            $tm->id_user = (int) $user['id_user'];
            $tm->pencatat = $user['nama_lengkap'];
            if ($tm->save()) {
                echo "<script>alert('Update Berhasil!'); window.location.href='index.php?page=transaksi_masuk';</script>";
            } else {
                echo "<script>alert('Gagal Update!');</script>";
            }
        }
    }
}
