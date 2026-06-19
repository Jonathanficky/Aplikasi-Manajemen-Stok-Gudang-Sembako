<?php $title = 'Transaksi Masuk - Toko Sembako'; $activeMenu = 'transaksi_masuk'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Faktur Pembelian (Masuk)</h2>
        </div>
        <button onclick="openTambah()" class="bg-[#05051a] text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-black transition-all flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Buat Faktur Baru
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Faktur Bulan Ini</p>
            <h3 class="text-2xl font-black text-gray-900 mt-2">IDR <?= number_format($totalFakturBulanIni, 0, ',', '.') ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Hutang Berjalan</p>
            <h3 class="text-2xl font-black text-gray-900 mt-2">IDR <?= number_format($totalHutangBerjalan, 0, ',', '.') ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Faktur Jatuh Tempo</p>
                <h3 class="text-2xl font-black text-gray-900 mt-2">(<?= $fakturJatuhTempo ?>)</h3>
            </div>
            <div class="relative bg-red-50 p-3 rounded-xl text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="absolute -top-1 -right-1 flex h-3 w-3 rounded-full bg-red-500"></span>
            </div>
        </div>
    </div>

    <div class="border border-gray-100 rounded-2xl p-4 bg-white shadow-sm">
        <div class="search-bar flex items-center px-4 py-2.5 gap-3 bg-gray-50 rounded-xl border border-gray-100">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="cariTransaksi" placeholder="Cari transaksi..." class="bg-transparent border-none outline-none text-sm w-full text-gray-600">
        </div>
    </div>

    <div class="border border-gray-100 rounded-2xl p-6 bg-white shadow-sm min-h-[300px] overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-gray-400 font-bold text-[11px] uppercase border-b border-gray-100 pb-3">
                    <th class="pb-3 px-3">No Faktur</th>
                    <th class="pb-3 px-3">Tanggal Faktur</th>
                    <th class="pb-3 px-3">Pemasok</th>
                    <th class="pb-3 px-3">Barang (Qty)</th>
                    <th class="pb-3 px-3">Total</th>
                    <th class="pb-3 px-3 text-center">Status</th>
                    <th class="pb-3 px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                    <?php if (count($transaksiList) > 0): foreach ($transaksiList as $row):
                    $total_harga = $row->jumlah * ($row->harga_beli ?? 0);
                ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors text-sm">
                        <td class="py-4 px-3 font-bold text-gray-900">INV-2026-<?= sprintf('%03d', $row->id_masuk); ?></td>
                        <td class="py-4 px-3 text-gray-500"><?= date('Y-m-d', strtotime($row->tanggal)); ?></td>
                        <td class="py-4 px-3 font-semibold text-gray-900"><?= htmlspecialchars($row->nama_supplier); ?></td>
                        <td class="py-4 px-3 text-gray-600"><?= htmlspecialchars($row->nama_barang); ?> <span class="text-xs font-bold text-gray-400">(x<?= $row->jumlah; ?>)</span></td>
                        <td class="py-4 px-3 font-bold text-gray-900">IDR <?= number_format($total_harga, 0, ',', '.'); ?></td>
                        <td class="py-4 px-3 text-center">
                            <?php if ($row->status === 'lunas'): ?>
                                <span class="bg-green-600 text-white px-3 py-1 rounded-md text-[11px] font-bold block text-center max-w-[90px] mx-auto">Lunas</span>
                            <?php elseif ($row->status === 'belum_lunas'): ?>
                                <span class="bg-red-600 text-white px-3 py-1 rounded-md text-[11px] font-bold block text-center max-w-[90px] mx-auto">Belum Lunas</span>
                            <?php else: ?>
                                <span class="bg-orange-500 text-white px-2 py-1 rounded-md text-[11px] font-bold block text-center max-w-[100px] mx-auto">Cicilan</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-3 text-right">
                            <button onclick="openEdit('<?= $row->id_masuk ?>', '<?= $row->tanggal ?>', '<?= $row->id_barang ?>', '<?= $row->id_supplier ?>', '<?= $row->jumlah ?>', '<?= $row->harga_beli ?? 0 ?>', '<?= $row->status ?>', '<?= htmlspecialchars($row->keterangan ?? '') ?>')" class="text-blue-600 font-bold hover:underline text-xs uppercase tracking-wider">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8" class="py-12 text-center text-gray-400 font-medium">Belum ada riwayat transaksi faktur masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="modalM" class="modal fixed inset-0 flex items-center justify-center z-[100]">
    <div class="modal-overlay absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="modal-container bg-white w-full max-w-xl mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden border border-gray-100 transform transition-all scale-95 duration-200 relative p-6">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="mb-5">
            <h3 id="mTitle" class="text-xl font-bold text-gray-900 tracking-tight">Buat Faktur Pembelian Baru</h3>
            <p class="text-xs text-gray-500 mt-1">Catat barang yang masuk ke gudang dari pemasok</p>
        </div>
        <form action="index.php?page=transaksi_masuk" method="POST" class="space-y-4">
            <input type="hidden" name="id_masuk" id="mIdMasuk">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal <span class="text-blue-500">*</span></label>
                    <input type="date" name="tanggal" id="mTanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-medium transition-all bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Barang <span class="text-blue-500">*</span></label>
                    <select name="id_barang" id="mBarang" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-medium transition-all bg-white">
                        <option value="">Pilih barang</option>
                        <?php foreach ($barangList as $b): ?>
                            <option value="<?= $b->id_barang ?>" data-harga="<?= $b->harga_beli ?>"><?= $b->nama_barang ?> (Stok: <?= $b->stok ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pemasok <span class="text-blue-500">*</span></label>
                    <select name="id_supplier" id="mSupplier" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-medium transition-all bg-white">
                        <option value="">Pilih pemasok</option>
                        <?php foreach ($supplierList as $s): ?>
                            <option value="<?= $s->id_supplier ?>"><?= $s->nama_supplier ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah <span class="text-blue-500">*</span></label>
                    <input type="number" name="jumlah" id="mJumlah" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-bold transition-all bg-white" value="0">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Harga Beli (per unit) <span class="text-blue-500">*</span></label>
                    <input type="number" name="harga_beli" id="mHargaBeli" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-bold transition-all bg-white" value="0">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Pembayaran <span class="text-blue-500">*</span></label>
                    <select name="status" id="mStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-bold transition-all bg-white">
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                        <option value="cicilan">Cicilan</option>
                    </select>
                </div>
                <div class="flex flex-col justify-center">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Total Harga</label>
                    <span id="mTotalHargaLabel" class="text-base font-bold text-blue-600">Rp 0</span>
                </div>
            </div>
            <div class="pt-2">
                <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="keterangan" id="mKeterangan" placeholder="Keterangan tambahan (opsional)" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-sm font-medium transition-all bg-white">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                <button type="button" onclick="closeModal()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition-colors">Batal</button>
                <button type="submit" id="mBtn" name="simpan_masuk" class="px-5 py-2 bg-[#1b305b] hover:bg-[#112040] text-white rounded-lg font-semibold text-sm transition-colors">Simpan Faktur</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalM = document.getElementById('modalM');
    const mJumlah = document.getElementById('mJumlah');
    const mHargaBeli = document.getElementById('mHargaBeli');
    const mTotalHargaLabel = document.getElementById('mTotalHargaLabel');
    const mBarang = document.getElementById('mBarang');
    function hitungTotal() {
        const qty = parseFloat(mJumlah.value) || 0;
        const harga = parseFloat(mHargaBeli.value) || 0;
        mTotalHargaLabel.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(qty * harga);
    }
    mJumlah.addEventListener('input', hitungTotal);
    mHargaBeli.addEventListener('input', hitungTotal);
    mBarang.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const h = opt.getAttribute('data-harga');
        if (h) { mHargaBeli.value = h; hitungTotal(); }
    });
    function openTambah() {
        document.getElementById('mTitle').innerText = "Buat Faktur Pembelian Baru";
        document.getElementById('mBtn').name = "simpan_masuk";
        document.getElementById('mBtn').innerText = "Simpan Faktur";
        document.getElementById('mIdMasuk').value = "";
        document.getElementById('mTanggal').value = "<?= date('Y-m-d') ?>";
        mBarang.value = "";
        document.getElementById('mSupplier').value = "";
        mJumlah.value = "0";
        mHargaBeli.value = "0";
        document.getElementById('mStatus').value = "belum_lunas";
        document.getElementById('mKeterangan').value = "";
        hitungTotal();
        modalM.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function openEdit(id, tgl, brg, sup, jml, harga, status, ket) {
        document.getElementById('mTitle').innerText = "Edit Transaksi Faktur";
        document.getElementById('mBtn').name = "update_masuk";
        document.getElementById('mBtn').innerText = "Simpan Faktur";
        document.getElementById('mIdMasuk').value = id;
        document.getElementById('mTanggal').value = tgl;
        mBarang.value = brg;
        document.getElementById('mSupplier').value = sup;
        mJumlah.value = jml;
        mHargaBeli.value = harga;
        document.getElementById('mStatus').value = status;
        document.getElementById('mKeterangan').value = ket;
        hitungTotal();
        modalM.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function closeModal() { modalM.classList.remove('active'); document.body.classList.remove('modal-active'); }
    document.getElementById('cariTransaksi').addEventListener('keyup', function() {
        const kw = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            if (row.cells.length > 1) row.style.display = row.textContent.toLowerCase().includes(kw) ? '' : 'none';
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
