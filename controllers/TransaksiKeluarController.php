<?php
class TransaksiKeluarController
{
    public function index(): void
    {
        Auth::checkAdmin();
        $user = Auth::user();
        $keluarList = TransaksiKeluar::all();

        $koneksi = Database::getInstance()->getConnection();
        $qBrg = mysqli_query($koneksi, "SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
        $barangStok = [];
        while ($r = mysqli_fetch_assoc($qBrg)) {
            $barangStok[] = new Barang($r);
        }

        $qAllBrg = mysqli_query($koneksi, "SELECT id_barang, nama_barang, harga_beli FROM barang ORDER BY nama_barang ASC");
        $barangData = [];
        while ($r = mysqli_fetch_assoc($qAllBrg)) {
            $barangData[] = $r;
        }

        View::render('transaksi_keluar/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'keluarList' => $keluarList,
            'barangStok' => $barangStok,
            'barangData' => $barangData,
            'totalNota' => TransaksiKeluar::total(),
            'totalQtyKeluar' => TransaksiKeluar::totalQty(),
            'totalPenjualan' => TransaksiKeluar::totalPenjualan(),
            'jmlKritis' => Barang::totalStokKritis(),
        ]);
    }

    public function simpan(): void
    {
        Auth::checkAdmin();
        $barang = Barang::find((int) ($_POST['id_barang'] ?? 0));
        $jumlah = (int) ($_POST['jumlah'] ?? 0);
        if ($barang && $barang->stok_min > 0 && ($barang->stok - $jumlah) < $barang->stok_min) {
            Session::setFlash('error', 'Stok "' . $barang->nama_barang . '" akan menipis! Jumlah penjualan melebihi batas stok minimum. Kurangi jumlah atau segera restok!');
            header("location:index.php?page=transaksi_keluar");
            exit();
        }
        $tk = new TransaksiKeluar($_POST);
        $tk->id_user = Auth::user()['id_user'];

        if ($tk->simpan()) {
            Session::setFlash('success', 'Berhasil! Stok barang telah berkurang.');
        } else {
            Session::setFlash('error', 'Gagal! Stok tidak mencukupi.');
        }
        header("location:index.php?page=transaksi_keluar");
        exit();
    }

    public function cetakPDF(int $id): void
    {
        Auth::checkAdmin();
        $nota = TransaksiKeluar::find($id);
        if (!$nota) {
            Session::setFlash('error', 'Data tidak ditemukan!');
            header("location:index.php?page=transaksi_keluar");
            exit();
        }

        while (ob_get_level()) { ob_end_clean(); }

        $noNota = 'NTA-' . str_pad($nota->id_keluar, 5, '0', STR_PAD_LEFT);
        $tanggal = date('d F Y', strtotime($nota->tanggal));
        $tglCetak = date('d F Y H:i');
        $hargaSatuan = (int) ($nota->harga_jual ?? 0);
        $jumlah = (int) $nota->jumlah;
        $grandTotal = $hargaSatuan * $jumlah;
        $keterangan = htmlspecialchars($nota->keterangan ?? '-', ENT_QUOTES, 'UTF-8');

        $html = '
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
            .header h1 { font-size: 22px; margin: 0 0 3px 0; }
            .header p { margin: 2px 0; font-size: 10px; color: #666; }
            .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 3px; }
            .info { margin-bottom: 20px; }
            .info table { width: 100%; }
            .info td { padding: 2px 5px; font-size: 11px; }
            .info .label { font-weight: bold; width: 120px; }
            table.detail { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
            table.detail th { background: #05051a; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
            table.detail td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
            table.detail tr:last-child td { border-bottom: 2px solid #333; }
            .footer { margin-top: 40px; text-align: right; }
            .footer .ttd { margin-top: 60px; }
            .footer .ttd p { margin: 2px 0; font-size: 11px; }
            .footer-info { text-align: center; font-size: 9px; color: #999; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
            .text-right { text-align: right; }
        </style>
        <div class="header">
            <h1>TOKO SEMBAKO</h1>
            <p>Sistem Manajemen Stok</p>
        </div>
        <div class="title">Nota Penjualan</div>
        <div class="info">
            <table>
                <tr><td class="label">No. Nota</td><td>: ' . $noNota . '</td></tr>
                <tr><td class="label">Tanggal</td><td>: ' . $tanggal . '</td></tr>
                <tr><td class="label">Tujuan / Penerima</td><td>: ' . htmlspecialchars($nota->tujuan, ENT_QUOTES, 'UTF-8') . '</td></tr>
            </table>
        </div>
        <table class="detail">
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Barang</th>
                <th style="width:100px; text-align:right;">Harga</th>
                <th style="width:80px; text-align:center;">Jumlah</th>
                <th style="width:120px; text-align:right;">Subtotal</th>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td>' . htmlspecialchars($nota->nama_barang ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td style="text-align:right;">Rp ' . number_format($hargaSatuan, 0, ',', '.') . '</td>
                <td style="text-align:center;">' . $jumlah . ' pcs</td>
                <td style="text-align:right;">Rp ' . number_format($grandTotal, 0, ',', '.') . '</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right; font-weight:bold;">Grand Total</td>
                <td style="text-align:right; font-weight:bold;">Rp ' . number_format($grandTotal, 0, ',', '.') . '</td>
            </tr>
        </table>
        <div class="footer">
            <p>Dicetak oleh: ' . htmlspecialchars($nota->pencatat ?? '-', ENT_QUOTES, 'UTF-8') . '</p>
            <div class="ttd">
                <p>Mengetahui,</p>
                <br><br>
                <p>_____________________</p>
            </div>
        </div>
        <div class="footer-info">Dicetak pada ' . $tglCetak . '</div>';

        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Nota_' . $noNota . '.pdf', ['Attachment' => true]);
        exit();
    }
}
