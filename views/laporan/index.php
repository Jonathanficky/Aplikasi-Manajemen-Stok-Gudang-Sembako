<?php $title = 'Laporan - Toko Sembako'; $activeMenu = 'laporan'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-10 space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900">Laporan & Analitik</h2>
        <p class="text-gray-400 text-sm mt-1">Laporan stok dan rekapitulasi nilai barang</p>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-900 text-sm">Laporan Laba / Rugi</h3>
            <p class="text-xs text-gray-500 mt-1">Ringkasan laba rugi berdasarkan periode bulanan</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <input type="hidden" name="page" value="laporan">
            <label class="text-xs text-gray-500 font-medium" for="tahun">Tahun</label>
            <select name="tahun" id="tahun" class="border border-gray-300 rounded-lg px-3 py-2 text-sm font-medium">
                <?php foreach ([2026, 2025] as $t): ?>
                <option value="<?= $t ?>" <?= $t == $tahunTerpilih ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 transition-all">Tampilkan</button>
        </form>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-gray-900 font-bold text-sm border-b-2 border-gray-900 pb-2">
                        <th class="pb-3 px-2 text-left">Bulan</th>
                        <th class="pb-3 px-2 text-right">Penjualan</th>
                        <th class="pb-3 px-2 text-right">Pembelian</th>
                        <th class="pb-3 px-2 text-right">Laba / Rugi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php $jualTotal = 0; $beliTotal = 0; $lrTotal = 0; ?>
                    <?php foreach ($labaRugiBulan as $lr):
                        $jualTotal += $lr['penjualan'];
                        $beliTotal += $lr['pembelian'];
                        $lrTotal += $lr['laba_rugi'];
                    ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-2 font-bold text-gray-900"><?= $lr['bulan'] ?></td>
                        <td class="py-3 px-2 text-right text-emerald-600 font-semibold">IDR <?= number_format($lr['penjualan'], 0, ',', '.') ?></td>
                        <td class="py-3 px-2 text-right text-blue-600 font-semibold">IDR <?= number_format($lr['pembelian'], 0, ',', '.') ?></td>
                        <td class="py-3 px-2 text-right font-bold <?= $lr['laba_rugi'] >= 0 ? 'text-emerald-700' : 'text-red-600' ?>">IDR <?= number_format(abs($lr['laba_rugi']), 0, ',', '.') ?> <span class="text-xs font-normal"><?= $lr['laba_rugi'] >= 0 ? '💰' : '🔴' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="font-bold text-gray-900 bg-gray-100/70">
                        <td class="py-3 px-2">TOTAL</td>
                        <td class="py-3 px-2 text-right text-emerald-700">IDR <?= number_format($jualTotal, 0, ',', '.') ?></td>
                        <td class="py-3 px-2 text-right text-blue-700">IDR <?= number_format($beliTotal, 0, ',', '.') ?></td>
                        <td class="py-3 px-2 text-right <?= $lrTotal >= 0 ? 'text-emerald-800' : 'text-red-700' ?>">IDR <?= number_format(abs($lrTotal), 0, ',', '.') ?> <span class="text-xs font-normal"><?= $lrTotal >= 0 ? 'Laba' : 'Rugi' ?></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-900 text-sm">Unduh Laporan Stok Saat Ini</h3>
            <p class="text-xs text-gray-500 mt-1">Ekspor data ke dalam format Excel atau PDF untuk dianalisis lebih lanjut.</p>
        </div>
        <div class="flex gap-3">
            <a href="index.php?page=export_laporan" class="bg-green-600 text-white px-6 py-3 rounded-xl flex items-center gap-2 font-bold text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-2m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>

        </div>
    </div>

    <div class="border border-gray-900 rounded-2xl p-8 bg-white shadow-sm min-h-[400px]">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h3 class="font-bold text-gray-800">Preview Laporan Stok Barang</h3>
                <p class="text-gray-400 text-xs mt-0.5 font-medium">Kondisi Stok Real-time</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-gray-900 font-bold text-sm border-b-2 border-gray-900 pb-2">
                        <th class="pb-3 px-2 w-12">No</th>
                        <th class="pb-3 px-2">Kode</th>
                        <th class="pb-3 px-2">Nama Barang</th>
                        <th class="pb-3 px-2">Kategori</th>
                        <th class="pb-3 px-2">Stok</th>
                        <th class="pb-3 px-2">Harga Beli</th>
                        <th class="pb-3 px-2">Total Nilai Aset</th>
                        <th class="pb-3 px-2 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if (count($laporanList) > 0): $no = 1; foreach ($laporanList as $row):
                        $total_aset = $row->totalNilaiAset();
                        $is_kritis = $row->isStokKritis();
                    ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-2 text-gray-600"><?= $no++; ?></td>
                            <td class="py-4 px-2 font-mono text-gray-500 font-medium"><?= htmlspecialchars($row->kode_barang); ?></td>
                            <td class="py-4 px-2 font-bold text-gray-900"><?= htmlspecialchars($row->nama_barang); ?></td>
                            <td class="py-4 px-2 text-gray-500"><?= htmlspecialchars($row->nama_kategori ?? '-'); ?></td>
                            <td class="py-4 px-2 font-bold text-gray-700"><?= $row->stok; ?></td>
                            <td class="py-4 px-2 text-gray-600">Rp <?= number_format($row->harga_beli, 0, ',', '.'); ?></td>
                            <td class="py-4 px-2 font-bold text-gray-900">Rp <?= number_format($total_aset, 0, ',', '.'); ?></td>
                            <td class="py-4 px-2 text-right <?= $is_kritis ? 'text-red-600 font-bold' : 'text-green-600 font-bold' ?>"><?= $is_kritis ? 'Kritis' : 'Aman' ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="py-12 text-center text-gray-400 font-medium bg-gray-50/50 rounded-b-xl">Belum ada data untuk dilaporkan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
