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
        <a href="index.php?page=barang" class="border <?= ($jmlKritis > 0) ? 'border-red-500 bg-red-50' : 'border-gray-900 bg-white' ?> rounded-2xl p-6 flex flex-col justify-between min-h-[140px] cursor-pointer hover:shadow-md transition-shadow">
            <span class="<?= ($jmlKritis > 0) ? 'text-red-500' : 'text-gray-500' ?> font-medium">Stok Rendah</span>
            <span class="text-3xl font-black <?= ($jmlKritis > 0) ? 'text-red-600' : 'text-gray-900' ?> mt-6"><?= $jmlKritis ?> <span class="text-xs font-normal">Perlu diisi</span></span>
        </a>
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium">Transaksi Masuk</span>
            <span class="text-3xl font-black text-gray-900 mt-6"><?= $jmlMasuk ?> <span class="text-xs text-gray-400 font-normal">Pembelian</span></span>
        </div>
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium">Transaksi Keluar</span>
            <span class="text-3xl font-black text-gray-900 mt-6"><?= $jmlKeluar ?> <span class="text-xs text-gray-400 font-normal">Penjualan</span></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium text-sm">Total Nilai Aset</span>
            <span class="text-2xl font-black text-gray-900 mt-4">IDR <?= number_format($totalAset, 0, ',', '.') ?></span>
        </div>
        <div class="border border-gray-900 rounded-2xl bg-white p-6 flex flex-col justify-between min-h-[140px]">
            <span class="text-gray-500 font-medium text-sm">Total Penjualan</span>
            <span class="text-2xl font-black text-emerald-600 mt-4">IDR <?= number_format($totalPenjualan, 0, ',', '.') ?></span>
        </div>
        <div class="border <?= $labaRugi >= 0 ? 'border-emerald-500 bg-emerald-50' : 'border-red-500 bg-red-50' ?> rounded-2xl p-6 flex flex-col justify-between min-h-[140px]">
            <span class="<?= $labaRugi >= 0 ? 'text-emerald-600' : 'text-red-600' ?> font-medium text-sm">Laba / Rugi</span>
            <span class="text-2xl font-black <?= $labaRugi >= 0 ? 'text-emerald-700' : 'text-red-700' ?> mt-4">IDR <?= number_format(abs($labaRugi), 0, ',', '.') ?> <span class="text-xs font-normal"><?= $labaRugi >= 0 ? '💰 Untung' : '🔴 Rugi' ?></span></span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 border border-gray-900 rounded-2xl p-5 bg-white">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                <h3 class="font-bold text-gray-900 text-sm">Stok per Kategori</h3>
            </div>
            <div class="relative" style="height: 260px;">
                <canvas id="chartStok"></canvas>
            </div>
        </div>
        <div class="lg:col-span-2 border border-gray-900 rounded-2xl p-5 bg-white flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                <h3 class="font-bold text-gray-900 text-sm">Rincian per Kategori</h3>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="pb-2 pr-2">Kategori</th>
                            <th class="pb-2 px-2 text-right">Stok</th>
                            <th class="pb-2 pl-2 text-right">Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalStok = 0; $totalBarang = 0; ?>
                        <?php foreach ($chartDetail as $i => $cd): ?>
                        <?php $totalStok += $cd['stok']; $totalBarang += $cd['barang']; ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors kategori-row" data-index="<?= $i ?>">
                            <td class="py-3 pr-2">
                                <span class="inline-block w-2.5 h-2.5 rounded-full mr-2 align-middle" style="background: <?= ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#06B6D4','#F97316'][$i % 8] ?>"></span>
                                <span class="font-semibold text-gray-800"><?= htmlspecialchars($cd['nama']) ?></span>
                            </td>
                            <td class="py-3 px-2 text-right font-bold text-gray-900"><?= number_format($cd['stok'], 0, ',', '.') ?></td>
                            <td class="py-3 pl-2 text-right text-gray-600"><?= $cd['barang'] ?> item</td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="font-bold text-gray-900 bg-gray-50/50">
                            <td class="py-3 pr-2">Total</td>
                            <td class="py-3 px-2 text-right"><?= number_format($totalStok, 0, ',', '.') ?></td>
                            <td class="py-3 pl-2 text-right"><?= $totalBarang ?> item</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var COLORS = ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#06B6D4','#F97316'];

var chartStok = new Chart(document.getElementById('chartStok'), {
    type: 'doughnut',
    data: {
        labels: <?= $chartLabels ?>,
        datasets: [{
            data: <?= $chartData ?>,
            backgroundColor: COLORS,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                        var pct = ((ctx.parsed / total) * 100).toFixed(1);
                        return ' ' + ctx.label + ': ' + ctx.parsed + ' stok (' + pct + '%)';
                    }
                }
            }
        },
        animation: { animateRotate: true },
        onHover: function(e, el) {
            var index = el.length ? el[0].element.$context.dataIndex : -1;
document.querySelectorAll('.kategori-row').forEach(function(row, i) {
                row.style.opacity = index === -1 || i === index ? '1' : '0.5';
            });
        }
    }
});

document.querySelectorAll('.kategori-row').forEach(function(row, i) {
    row.addEventListener('mouseenter', function() {
        chartStok.setActiveElements([{datasetIndex: 0, index: i}]);
        chartStok.draw();
        row.style.opacity = '1';
        document.querySelectorAll('.kategori-row').forEach(function(r, j) {
            if (j !== i) r.style.opacity = '0.5';
        });
    });
    row.addEventListener('mouseleave', function() {
        chartStok.setActiveElements([]);
        chartStok.draw();
        document.querySelectorAll('.kategori-row').forEach(function(r) {
            r.style.opacity = '1';
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
