<?php $title = 'Barang - Toko Sembako'; $activeMenu = 'barang'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900">Stok Barang</h2>
            <p class="text-gray-400 text-sm mt-1">Kelola data produk dan stok sembako.</p>
        </div>
        <button onclick="openTambahModal()" class="bg-black text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-800 transition-all flex items-center gap-2 shadow-lg">
            <span class="text-lg">+</span> Tambah Barang
        </button>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm">
        <div class="search-bar flex items-center px-4 py-3 gap-3 bg-gray-100 rounded-xl">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari barang..." class="bg-transparent border-none outline-none text-sm w-full text-gray-600">
        </div>
    </div>

    <div class="border border-gray-900 rounded-2xl p-8 bg-white shadow-sm min-h-[400px]">
        <div class="mb-6 flex justify-between items-center border-b pb-4">
            <h3 class="font-bold text-gray-800 uppercase tracking-wider text-sm">Daftar Barang</h3>
            <p class="text-gray-400 text-xs font-medium">Total: <?= $totalBarang ?> Item</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-gray-900 font-extrabold text-xs uppercase border-b border-gray-200 pb-3">
                        <th class="pb-3 px-2">No</th>
                        <th class="pb-3 px-2">Kode</th>
                        <th class="pb-3 px-2">Nama Barang</th>
                        <th class="pb-3 px-2 text-center">Stok</th>
                        <th class="pb-3 px-2">Harga Beli</th>
                        <th class="pb-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($totalBarang > 0): $no = 1; foreach ($barangList as $row): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-2"><?= $no++; ?></td>
                            <td class="py-4 px-2 font-mono text-xs text-gray-500 uppercase"><?= $row->kode_barang; ?></td>
                            <td class="py-4 px-2 font-bold text-gray-900"><?= htmlspecialchars($row->nama_barang); ?></td>
                            <td class="py-4 px-2 text-center">
                                <span class="px-3 py-1 rounded-full font-bold text-xs <?= $row->isStokKritis() ? 'bg-red-100 text-red-600 border border-red-200' : 'bg-green-100 text-green-600 border border-green-200' ?>">
                                    <?= $row->stok ?>
                                </span>
                            </td>
                            <td class="py-4 px-2 font-medium text-gray-900">Rp <?= number_format($row->harga_beli, 0, ',', '.') ?></td>
                            <td class="py-4 px-2 text-right">
                                <button onclick="openEditModal('<?= $row->id_barang ?>', '<?= $row->kode_barang ?>', '<?= htmlspecialchars($row->nama_barang) ?>', '<?= $row->id_kategori ?>', '<?= $row->stok ?>', '<?= $row->stok_min ?>', '<?= $row->harga_beli ?>')" class="text-blue-600 font-bold mr-4">Edit</button>
                                <a href="index.php?page=barang&amp;hapus_id=<?= $row->id_barang ?>" onclick="return confirm('Hapus?')" class="text-red-600 font-bold">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="py-12 text-center text-gray-400">Belum ada data barang.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="modalBarang" class="modal fixed inset-0 flex items-center justify-center z-[100]">
    <div class="modal-overlay absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-2xl mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
        <div class="p-8">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <p id="modalTitle" class="text-xl font-black">Tambah Barang Baru</p>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black text-2xl">&times;</button>
            </div>
            <form action="index.php?page=barang" method="POST" class="mt-6 space-y-4">
                <input type="hidden" name="id_barang" id="form_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kode Barang</label>
                        <input type="text" name="kode_barang" id="form_kode" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori</label>
                        <select name="id_kategori" id="form_kategori" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($kategoriList as $k): ?>
                                <option value="<?= $k->id_kategori ?>"><?= $k->nama_kategori ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Barang</label>
                    <input type="text" name="nama_barang" id="form_nama" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Stok</label>
                        <input type="number" name="stok" id="form_stok" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Stok Min</label>
                        <input type="number" name="stok_min" id="form_stok_min" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Harga Beli</label>
                        <input type="number" name="harga_beli" id="form_harga" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" id="btnSubmit" name="simpan_barang" class="flex-1 py-3 bg-black text-white rounded-xl font-bold hover:bg-gray-800 shadow-lg transition-all">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalBarang');
    function openTambahModal() {
        document.getElementById('modalTitle').innerText = "Tambah Barang Baru";
        document.getElementById('btnSubmit').name = "simpan_barang";
        document.getElementById('form_id').value = "";
        document.getElementById('form_kode').value = "";
        document.getElementById('form_nama').value = "";
        document.getElementById('form_kategori').value = "";
        document.getElementById('form_stok').value = "0";
        document.getElementById('form_stok_min').value = "5";
        document.getElementById('form_harga').value = "";
        modal.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function openEditModal(id, kode, nama, kat, stok, stok_min, harga) {
        document.getElementById('modalTitle').innerText = "Edit Barang";
        document.getElementById('btnSubmit').name = "update_barang";
        document.getElementById('form_id').value = id;
        document.getElementById('form_kode').value = kode;
        document.getElementById('form_nama').value = nama;
        document.getElementById('form_kategori').value = kat;
        document.getElementById('form_stok').value = stok;
        document.getElementById('form_stok_min').value = stok_min;
        document.getElementById('form_harga').value = harga;
        modal.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function closeModal() {
        modal.classList.remove('active');
        document.body.classList.remove('modal-active');
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
