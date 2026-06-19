<?php $title = 'Laporan - Toko Sembako'; $activeMenu = 'laporan'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-10 space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900">Laporan & Analitik</h2>
        <p class="text-gray-400 text-sm mt-1">Laporan stok dan rekapitulasi nilai barang</p>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-900 text-sm">Unduh Laporan Stok Saat Ini</h3>
            <p class="text-xs text-gray-500 mt-1">Ekspor data ke dalam format Excel (.xls) untuk dianalisis lebih lanjut.</p>
        </div>
        <a href="index.php?page=export_laporan" class="bg-green-600 text-white px-8 py-3 rounded-xl flex items-center gap-2 font-bold text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-2m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel
        </a>
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
