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
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Total Penjualan</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5">IDR <?= number_format($totalPenjualan, 0, ',', '.') ?></h3>
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
                        <th class="py-4 px-4">Tanggal</th>
                        <th class="py-4 px-4">Barang</th>
                        <th class="py-4 px-4 text-right">Harga</th>
                        <th class="py-4 px-4 text-center">Qty</th>
                        <th class="py-4 px-4 text-right">Subtotal</th>
                        <th class="py-4 px-4">Tujuan</th>
                        <th class="py-4 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (count($keluarList) > 0): $no = 1; foreach ($keluarList as $row):
                        $subtotal = (int) $row->jumlah * (int) ($row->harga_jual ?? 0);
                    ?>
                        <tr class="hover:bg-gray-50/80 transition-colors text-xs">
                            <td class="py-4 px-6 text-center text-gray-400 font-medium"><?= $no++; ?></td>
                            <td class="py-4 px-4 font-mono font-bold text-blue-600">NTA-<?= str_pad($row->id_keluar, 5, '0', STR_PAD_LEFT); ?></td>
                            <td class="py-4 px-4 text-gray-600"><?= date('d F Y', strtotime($row->tanggal)); ?></td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($row->nama_barang); ?></div>
                                <div class="text-[10px] text-gray-400 mt-0.5 font-normal">ID-BRG: #<?= $row->id_barang; ?></div>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-gray-700">Rp <?= number_format((int) ($row->harga_jual ?? 0), 0, ',', '.') ?></td>
                            <td class="py-4 px-4 text-center">
                                <span class="bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md font-extrabold text-[11px]"><?= $row->jumlah; ?> pcs</span>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-gray-900">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td class="py-4 px-4">
                                <span class="text-gray-900 font-medium"><?= htmlspecialchars($row->tujuan); ?></span>
                                <?php if (!empty($row->keterangan)): ?>
                                    <p class="text-[10px] text-gray-400 font-normal truncate max-w-[120px]" title="<?= htmlspecialchars($row->keterangan); ?>">Ket: <?= htmlspecialchars($row->keterangan); ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button"
                                        data-keluar="<?= $row->id_keluar ?>"
                                        data-tanggal="<?= date('d F Y', strtotime($row->tanggal)) ?>"
                                        data-barang="<?= htmlspecialchars($row->nama_barang, ENT_QUOTES) ?>"
                                        data-idbarang="<?= $row->id_barang ?>"
                                        data-jumlah="<?= $row->jumlah ?>"
                                        data-harga="<?= (int) ($row->harga_jual ?? 0) ?>"
                                        data-tujuan="<?= htmlspecialchars($row->tujuan, ENT_QUOTES) ?>"
                                        data-keterangan="<?= htmlspecialchars($row->keterangan ?? '-', ENT_QUOTES) ?>"
                                        data-pencatat="<?= htmlspecialchars($row->pencatat, ENT_QUOTES) ?>"
                                        onclick="openDetail(this)"
                                        class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-800 font-bold text-[10px] uppercase tracking-wider transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </button>
                                    <a href="index.php?page=transaksi_keluar&cetak_pdf=<?= $row->id_keluar ?>" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-bold text-[10px] uppercase tracking-wider" target="_blank">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="9" class="py-20 text-center">
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

<div id="modalKeluar" class="modal fixed inset-0 flex items-start justify-center overflow-y-auto z-[120]">
    <div class="modal-overlay fixed inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden border border-gray-100 relative mt-8 mb-8">
        <div class="p-8">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <p class="text-lg font-black text-gray-900">Penerbitan Nota Keluar</p>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="index.php?page=transaksi_keluar" method="POST" class="space-y-4 mt-4" onsubmit="return disableSubmit(this)">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tanggal Operasional</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Komoditas / Barang</label>
                    <select name="id_barang" id="fBarang" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs appearance-none">
                        <option value="">Pilih ketersediaan barang...</option>
                        <?php foreach ($barangStok as $b):
                            $kritis = $b->stok <= $b->stok_min;
                        ?>
                            <option value="<?= $b->id_barang ?>"<?= $kritis ? ' class="text-red-600 font-bold"' : '' ?>><?= $b->nama_barang ?> (Ready: <?= $b->stok ?>)<?= $kritis ? ' ⚠️' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Kuantitas Keluar</label>
                        <input type="number" name="jumlah" id="fJumlah" min="1" placeholder="0" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Harga Jual Satuan <span class="text-blue-500">*</span></label>
                        <input type="number" name="harga_jual" id="fHargaJual" min="0" placeholder="0" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tujuan / Mitra</label>
                        <input type="text" name="tujuan" placeholder="Pembeli/Toko" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl outline-none focus:border-blue-600 bg-gray-50 text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Subtotal</label>
                        <p id="fSubtotal" class="text-lg font-bold text-gray-900 bg-gray-50 rounded-xl px-4 py-3 border border-gray-200">Rp 0</p>
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

<div id="modalDetail" class="modal fixed inset-0 flex items-start justify-center overflow-y-auto z-[120]">
    <div class="modal-overlay fixed inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeDetail()"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden relative mt-8 mb-8">
        <div class="p-8">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <p class="text-lg font-black text-gray-900">Detail Nota</p>
                <button onclick="closeDetail()" class="text-gray-400 hover:text-black transition-transform"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="mt-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">No. Nota</label>
                        <p id="dNoNota" class="text-sm font-bold text-blue-600 font-mono">-</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                        <p id="dTanggal" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Barang</label>
                        <p id="dNama" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ID Barang</label>
                        <p id="dIdBarang" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jumlah</label>
                        <p id="dJumlah" class="text-sm font-bold text-rose-700">-</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tujuan</label>
                        <p id="dTujuan" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Harga Jual Satuan</label>
                        <p id="dHarga" class="text-sm font-bold text-gray-900">-</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Subtotal</label>
                        <p id="dSubtotal" class="text-sm font-bold text-emerald-700">-</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Keterangan</label>
                        <p id="dKeterangan" class="text-sm text-gray-700 bg-gray-50 rounded-lg px-4 py-3 border border-gray-100">-</p>
                    </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Otorisasi / Pencatat</label>
                    <p id="dPencatat" class="text-sm font-bold text-gray-900">-</p>
                </div>
            </div>
            <div class="flex justify-end pt-6 border-t border-gray-100 mt-6">
                <button type="button" onclick="closeDetail()" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const modalK = document.getElementById('modalKeluar');
    const modalD = document.getElementById('modalDetail');

    var barangHarga = <?= json_encode(array_column($barangData, 'harga_beli', 'id_barang')) ?>;

    function openModal() {
        document.getElementById('fJumlah').value = '';
        document.getElementById('fHargaJual').value = '';
        document.getElementById('fSubtotal').textContent = 'Rp 0';
        modalK.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function closeModal() { modalK.classList.remove('active'); document.body.classList.remove('modal-active'); }

    document.getElementById('fBarang').addEventListener('change', function() {
        var hb = barangHarga[this.value] || 0;
        document.getElementById('fHargaJual').value = Math.round(hb * 1.2);
        hitungSubtotal();
    });

    document.getElementById('fJumlah').addEventListener('input', hitungSubtotal);
    document.getElementById('fHargaJual').addEventListener('input', hitungSubtotal);

    function hitungSubtotal() {
        var qty = parseFloat(document.getElementById('fJumlah').value) || 0;
        var harga = parseFloat(document.getElementById('fHargaJual').value) || 0;
        document.getElementById('fSubtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(qty * harga);
    }

    function openDetail(btn) {
        document.getElementById('dNoNota').textContent = 'NTA-' + String(btn.dataset.keluar).padStart(5, '0');
        document.getElementById('dTanggal').textContent = btn.dataset.tanggal;
        document.getElementById('dNama').textContent = btn.dataset.barang;
        document.getElementById('dIdBarang').textContent = '# ' + btn.dataset.idbarang;
        document.getElementById('dJumlah').textContent = btn.dataset.jumlah + ' pcs';
        var subtotal = parseInt(btn.dataset.jumlah) * parseInt(btn.dataset.harga || 0);
        document.getElementById('dHarga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(btn.dataset.harga || 0);
        document.getElementById('dSubtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        document.getElementById('dTujuan').textContent = btn.dataset.tujuan;
        document.getElementById('dKeterangan').textContent = btn.dataset.keterangan;
        document.getElementById('dPencatat').textContent = btn.dataset.pencatat;
        modalD.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function closeDetail() {
        modalD.classList.remove('active');
        document.body.classList.remove('modal-active');
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
