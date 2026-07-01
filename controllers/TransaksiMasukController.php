<?php
class TransaksiMasukController
{
    public function index(): void
    {
        Auth::checkAdmin();
        $user = Auth::user();
        $barangList = Barang::all();
        $supplierList = Supplier::allAsc();

        $transaksiList = TransaksiMasuk::all();

        $totalFakturBulanIni = 0;
        $totalHutangBerjalan = 0;
        $fakturJatuhTempo = 0;

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

        $monthlySummary = TransaksiMasuk::monthlySummary();

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
            'monthlySummary' => $monthlySummary,
        ]);
    }

    public function simpan(): void
    {
        Auth::checkAdmin();
        $user = Auth::user();
        $tm = new TransaksiMasuk($_POST);
        $tm->id_user = (int) $user['id_user'];
        $tm->pencatat = $user['nama_lengkap'];
        $tm->status = $_POST['status'] ?? 'belum_lunas';

        if ($tm->save()) {
            Session::setFlash('success', 'Berhasil Simpan Faktur!');
        } else {
            Session::setFlash('error', 'Gagal Simpan Faktur!');
        }
        header("location:index.php?page=transaksi_masuk");
        exit();
    }

    public function update(): void
    {
        Auth::checkAdmin();
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
                Session::setFlash('success', 'Update Faktur Berhasil!');
            } else {
                Session::setFlash('error', 'Gagal Update Faktur!');
            }
            header("location:index.php?page=transaksi_masuk");
            exit();
        } else {
            Session::setFlash('error', 'Data tidak ditemukan!');
            header("location:index.php?page=transaksi_masuk");
            exit();
        }
    }

    public function cetakPDF(int $id): void
    {
        Auth::checkAdmin();
        $faktur = TransaksiMasuk::find($id);
        if (!$faktur) {
            Session::setFlash('error', 'Data tidak ditemukan!');
            header("location:index.php?page=transaksi_masuk");
            exit();
        }

        while (ob_get_level()) { ob_end_clean(); }

        $noFaktur = 'INV-' . date('Y') . '-' . sprintf('%03d', $faktur->id_masuk);
        $tanggal = date('d F Y', strtotime($faktur->tanggal));
        $totalHarga = $faktur->jumlah * ($faktur->harga_beli ?? 0);
        $tglCetak = date('d F Y H:i');
        $statusLabel = match ($faktur->status) {
            'lunas' => 'LUNAS',
            'belum_lunas' => 'BELUM LUNAS',
            'cicilan' => 'CICILAN',
            default => strtoupper($faktur->status)
        };

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
            .status-box { text-align: center; margin: 20px 0; padding: 10px; border: 2px solid #333; border-radius: 5px; font-weight: bold; font-size: 14px; }
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
        <div class="title">Faktur Pembelian</div>
        <div class="info">
            <table>
                <tr><td class="label">No. Faktur</td><td>: ' . $noFaktur . '</td></tr>
                <tr><td class="label">Tanggal</td><td>: ' . $tanggal . '</td></tr>
                <tr><td class="label">Pemasok</td><td>: ' . htmlspecialchars($faktur->nama_supplier ?? '-', ENT_QUOTES, 'UTF-8') . '</td></tr>
            </table>
        </div>
        <table class="detail">
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Barang</th>
                <th style="width:80px;">Jumlah</th>
                <th style="width:120px;">Harga Satuan</th>
                <th style="width:120px;" class="text-right">Total</th>
            </tr>
            <tr>
                <td style="text-align:center;">1</td>
                <td>' . htmlspecialchars($faktur->nama_barang ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td style="text-align:center;">' . $faktur->jumlah . ' pcs</td>
                <td style="text-align:right;">Rp ' . number_format($faktur->harga_beli ?? 0, 0, ',', '.') . '</td>
                <td style="text-align:right;">Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right; font-weight:bold;">Grand Total</td>
                <td style="text-align:right; font-weight:bold;">Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>
            </tr>
        </table>
        <div class="status-box">
            Status Pembayaran: ' . $statusLabel . '
        </div>
        <div class="footer">
            <p>Dicetak oleh: ' . htmlspecialchars($faktur->pencatat ?? '-', ENT_QUOTES, 'UTF-8') . '</p>
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
        $dompdf->stream('Faktur_' . $noFaktur . '.pdf', ['Attachment' => true]);
        exit();
    }
}
