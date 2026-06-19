<?php $title = 'Supplier - Toko Sembako'; $activeMenu = 'supplier'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Supplier</h2>
            <p class="text-gray-400 text-sm mt-1">Kelola daftar pemasok barang sembako.</p>
        </div>
        <button onclick="openTambahModal()" class="bg-black text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-800 transition-all flex items-center gap-2 shadow-lg">
            <span class="text-lg">+</span> Tambah Supplier
        </button>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm">
        <div class="search-bar flex items-center px-4 py-3 gap-3 bg-gray-100 rounded-xl">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari supplier..." class="bg-transparent border-none outline-none text-sm w-full text-gray-600">
        </div>
    </div>

    <div class="border border-gray-900 rounded-2xl p-8 bg-white shadow-sm min-h-[400px]">
        <div class="mb-6 flex justify-between items-center border-b pb-4">
            <h3 class="font-bold text-gray-800 uppercase tracking-wider text-sm">Daftar Supplier</h3>
            <p class="text-gray-400 text-xs font-medium">Total: <?= $totalSupplier ?> Data</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="text-gray-900 font-extrabold text-xs uppercase border-b border-gray-200">
                        <th class="pb-3 px-2">No</th>
                        <th class="pb-3 px-2">Nama Supplier</th>
                        <th class="pb-3 px-2">No. Telpon</th>
                        <th class="pb-3 px-2">Alamat</th>
                        <th class="pb-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($totalSupplier > 0): $no = 1; foreach ($supplierList as $row): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-2 text-gray-500 font-medium"><?= $no++; ?></td>
                            <td class="py-4 px-2 font-bold text-gray-900"><?= htmlspecialchars($row->nama_supplier); ?></td>
                            <td class="py-4 px-2 text-gray-600"><?= htmlspecialchars($row->no_telp); ?></td>
                            <td class="py-4 px-2 text-gray-500 max-w-xs truncate"><?= htmlspecialchars($row->alamat); ?></td>
                            <td class="py-4 px-2 text-right">
                                <button onclick="openEditModal('<?= $row->id_supplier ?>', '<?= htmlspecialchars($row->nama_supplier) ?>', '<?= htmlspecialchars($row->no_telp) ?>', '<?= htmlspecialchars($row->alamat) ?>')" class="text-blue-600 font-bold mr-4">Edit</button>
                                <a href="index.php?page=supplier&amp;hapus_id=<?= $row->id_supplier ?>" onclick="return confirm('Hapus supplier ini?')" class="text-red-600 font-bold">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="py-12 text-center text-gray-400 font-medium">Belum ada data supplier.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="modalSupplier" class="modal fixed inset-0 flex items-center justify-center z-[100]">
    <div class="modal-overlay absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
        <div class="p-8">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <p id="modalTitle" class="text-xl font-black">Tambah Supplier Baru</p>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black text-2xl">&times;</button>
            </div>
            <form action="index.php?page=supplier" method="POST" class="mt-6 space-y-4">
                <input type="hidden" name="id_supplier" id="form_id">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Supplier</label>
                    <input type="text" name="nama_supplier" id="form_nama" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">No. Telpon</label>
                    <input type="text" name="no_telp" id="form_telp" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Alamat</label>
                    <textarea name="alamat" id="form_alamat" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 text-sm h-24 resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" id="btnSubmit" name="simpan_supplier" class="flex-1 py-3 bg-black text-white rounded-xl font-bold hover:bg-gray-800 shadow-lg transition-all">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalSupplier');
    function openTambahModal() {
        document.getElementById('modalTitle').innerText = "Tambah Supplier Baru";
        document.getElementById('btnSubmit').name = "simpan_supplier";
        document.getElementById('form_id').value = "";
        document.getElementById('form_nama').value = "";
        document.getElementById('form_telp').value = "";
        document.getElementById('form_alamat').value = "";
        modal.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function openEditModal(id, nama, telp, alamat) {
        document.getElementById('modalTitle').innerText = "Edit Supplier";
        document.getElementById('btnSubmit').name = "update_supplier";
        document.getElementById('form_id').value = id;
        document.getElementById('form_nama').value = nama;
        document.getElementById('form_telp').value = telp;
        document.getElementById('form_alamat').value = alamat;
        modal.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function closeModal() {
        modal.classList.remove('active');
        document.body.classList.remove('modal-active');
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
