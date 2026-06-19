<?php $title = 'Transaksi Keluar - Toko Sembako'; $activeMenu = 'transaksi_keluar'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Nota Penjualan</h2>
            <p class="text-gray-500 text-xs mt-1">Kelola, cetak, dan pantau rekaman kelayakan transaksi barang keluar (Nota).</p>
        </div>
        <button onclick="openModal()" class="bg-[#05051a] text-white px-5 py-3 rounded-xl font-bold text-xs hover:bg-black transition-all flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Buat Nota Baru
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Total Nota Terbit</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5"><?= $totalNota ?> <span class="text-xs font-normal text-gray-400">Transaksi</span></h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Total Produk Keluar</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5"><?= $totalQtyKeluar ?> <span class="text-xs font-normal text-gray-400">Pcs / Pack</span></h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Status Pencatatan</p>
                <h3 class="text-base font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full inline-block animate-pulse"></span> Ready / Sinkron
                </h3>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
        <div class="flex items-center px-4 py-2.5 gap-3 bg-gray-50 border border-gray-100 rounded-xl">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari nomor nota, nama barang, atau tujuan distribusi..." class="bg-transparent border-none outline-none text-xs w-full text-gray-600 placeholder-gray-400">
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden min-h-[400px]">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold text-[11px] uppercase tracking-wider">
                        <th class="py-4 px-6 text-center w-12">No</th>
                        <th class="py-4 px-4">No. Nota</th>
                        <th class="py-4 px-4">Tanggal Batas</th>
                        <th class="py-4 px-4">Deskripsi Barang</th>
                        <th class="py-4 px-4 text-center">Volume (Qty)</th>
                        <th class="py-4 px-4">Tujuan / Penerima</th>
                        <th class="py-4 px-4 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Otorisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (count($keluarList) > 0): $no = 1; foreach ($keluarList as $row): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors text-xs">
                            <td class="py-4 px-6 text-center text-gray-400 font-medium"><?= $no++; ?></td>
                            <td class="py-4 px-4 font-mono font-bold text-blue-600">NTA-<?= str_pad($row->id_keluar, 5, '0', STR_PAD_LEFT); ?></td>
                            <td class="py-4 px-4 text-gray-600"><?= date('d F Y', strtotime($row->tanggal)); ?></td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($row->nama_barang); ?></div>
                                <div class="text-[10px] text-gray-400 mt-0.5 font-normal">ID-BRG: #<?= $row->id_barang; ?></div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md font-extrabold text-[11px]">- <?= $row->jumlah; ?> pcs</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-gray-900 font-medium"><?= htmlspecialchars($row->tujuan); ?></span>
                                <?php if (!empty($row->keterangan)): ?>
                                    <p class="text-[10px] text-gray-400 font-normal truncate max-w-[150px]" title="<?= htmlspecialchars($row->keterangan); ?>">Ket: <?= htmlspecialchars($row->keterangan); ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="bg-emerald-50 text-emerald-700 px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-emerald-100">Selesai</span>
                            </td>
                            <td class="py-4 px-6 text-right text-gray-500 font-medium">
                                <div class="font-bold text-gray-700"><?= $row->pencatat; ?></div>
                                <div class="text-[9px] text-gray-400 uppercase tracking-widest mt-0.5">Sistem Validated</div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-xs text-gray-400 font-medium">Belum ada dokumen nota transaksi keluar terbit.</p>
                            </div>
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="modalKeluar" class="modal fixed inset-0 flex items-center justify-center z-[100]">
    <div class="modal-overlay absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden border border-gray-100">
        <div class="p-8">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <p class="text-lg font-black text-gray-900">Penerbitan Nota Keluar</p>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="index.php?page=transaksi_keluar" method="POST" class="space-y-4 mt-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tanggal Operasional</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Komoditas / Barang</label>
                    <select name="id_barang" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs appearance-none">
                        <option value="">Pilih ketersediaan barang...</option>
                        <?php foreach ($barangStok as $b): ?>
                            <option value="<?= $b->id_barang ?>"><?= $b->nama_barang ?> (Ready: <?= $b->stok ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Kuantitas Keluar</label>
                        <input type="number" name="jumlah" min="1" placeholder="0" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tujuan / Mitra</label>
                        <input type="text" name="tujuan" placeholder="Pembeli/Toko" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Memo / Keterangan Nota</label>
                    <textarea name="keterangan" placeholder="Catatan opsional distribusi..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs h-16 resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold uppercase text-[10px] tracking-wider">Batal</button>
                    <button type="submit" name="simpan_keluar" class="flex-1 py-3 bg-[#05051a] text-white rounded-xl font-bold uppercase text-[10px] tracking-wider shadow-md hover:bg-black transition-colors">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modalK = document.getElementById('modalKeluar');
    function openModal() { modalK.classList.add('active'); document.body.classList.add('modal-active'); }
    function closeModal() { modalK.classList.remove('active'); document.body.classList.remove('modal-active'); }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
