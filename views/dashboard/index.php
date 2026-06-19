<?php $title = 'Dashboard - Toko Sembako'; $activeMenu = 'dashboard'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-8 space-y-6">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900">Dashboard</h2>
        <p class="text-gray-400 text-sm mt-1">Halo, <?= $nama_user ?>! Berikut ringkasan stok barang hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium">Total Barang</span>
            <span class="text-3xl font-black text-gray-900 mt-6"><?= $jmlBarang ?> <span class="text-xs text-gray-400 font-normal">Item</span></span>
        </div>
        <div class="border <?= ($jmlKritis > 0) ? 'border-red-500 bg-red-50' : 'border-gray-900 bg-white' ?> rounded-2xl p-6 flex flex-col justify-between min-h-[140px]">
            <span class="<?= ($jmlKritis > 0) ? 'text-red-500' : 'text-gray-500' ?> font-medium">Stok Rendah</span>
            <span class="text-3xl font-black <?= ($jmlKritis > 0) ? 'text-red-600' : 'text-gray-900' ?> mt-6"><?= $jmlKritis ?> <span class="text-xs font-normal">Perlu diisi</span></span>
        </div>
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium">Transaksi Masuk</span>
            <span class="text-3xl font-black text-gray-900 mt-6"><?= $jmlMasuk ?> <span class="text-xs text-gray-400 font-normal">Pembelian</span></span>
        </div>
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium">Transaksi Keluar</span>
            <span class="text-3xl font-black text-gray-900 mt-6"><?= $jmlKeluar ?> <span class="text-xs text-gray-400 font-normal">Penjualan</span></span>
        </div>
    </div>

    <?php if ($jmlKritis > 0): ?>
    <div class="border border-red-500 bg-white p-4 flex items-center gap-4 rounded-xl shadow-sm">
        <div class="bg-red-500 text-white p-1.5 rounded">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
            <h4 class="text-red-600 font-bold text-sm leading-none">Peringatan Stok Rendah</h4>
            <p class="text-[10px] text-red-500 mt-1 uppercase">Terdapat <?= $jmlKritis ?> barang dengan stok di bawah batas minimum. Segera lakukan pengadaan.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-3">
            <h3 class="font-bold text-gray-900 text-sm">Barang Stok Rendah</h3>
            <div class="border border-gray-900 rounded-2xl p-5 space-y-4 bg-white min-h-[200px]">
                <?php if (count($barangKritis) > 0): ?>
                    <?php foreach ($barangKritis as $b): ?>
                    <div class="border border-red-200 bg-red-50/30 rounded-xl h-14 flex items-center justify-between px-5">
                        <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($b->nama_barang) ?></div>
                        <div class="text-xs font-bold text-red-600 bg-white border border-red-200 px-3 py-1 rounded-full">Sisa: <?= $b->stok ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="h-full flex items-center justify-center pt-10">
                        <span class="text-gray-400 font-medium text-sm">Semua stok barang aman</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="font-bold text-gray-900 text-sm">Transaksi Masuk Terbaru</h3>
            <div class="border border-gray-900 rounded-2xl p-5 space-y-4 bg-white min-h-[200px]">
                <?php if (count($transaksiTerbaru) > 0): ?>
                    <?php foreach ($transaksiTerbaru as $trx): ?>
                    <div class="border border-gray-200 rounded-xl h-14 flex items-center justify-between px-5">
                        <div>
                            <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($trx['nama_barang']) ?></div>
                            <div class="text-[10px] text-gray-400"><?= date('d M Y', strtotime($trx['tanggal'])) ?></div>
                        </div>
                        <div class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full">+ <?= $trx['jumlah'] ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="h-full flex items-center justify-center pt-10">
                        <span class="text-gray-400 font-medium text-sm">Belum ada transaksi masuk</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
