<?php $title = 'Kategori - Toko Sembako'; $activeMenu = 'kategori'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<main class="p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Kategori</h2>
            <p class="text-gray-400 text-sm mt-1">Kelola daftar kategori produk sembako.</p>
        </div>
        <button onclick="openTambahModal()" class="bg-black text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-800 transition-all flex items-center gap-2 shadow-lg">
            <span class="text-lg">+</span> Tambah Kategori
        </button>
    </div>

    <div class="border border-gray-900 rounded-2xl p-6 bg-white shadow-sm">
        <div class="search-bar flex items-center px-4 py-3 gap-3 bg-gray-100 rounded-xl">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari kategori..." class="bg-transparent border-none outline-none text-sm w-full text-gray-600">
        </div>
    </div>

    <div class="border border-gray-900 rounded-2xl p-8 bg-white shadow-sm min-h-[400px] overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead>
                <tr class="text-gray-900 font-extrabold text-xs uppercase border-b border-gray-200 pb-3">
                    <th class="pb-3 w-16">No</th>
                    <th class="pb-3">Nama kategori</th>
                    <th class="pb-3">Deskripsi</th>
                    <th class="pb-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($kategoriList) > 0): $no = 1; foreach ($kategoriList as $k): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 text-gray-500 font-medium"><?= $no++; ?></td>
                        <td class="py-4 font-bold text-gray-900"><?= htmlspecialchars($k->nama_kategori); ?></td>
                        <td class="py-4 text-gray-500"><?= htmlspecialchars($k->deskripsi); ?></td>
                        <td class="py-4 text-right">
                            <button onclick="openEditModal('<?= $k->id_kategori ?>', '<?= htmlspecialchars($k->nama_kategori) ?>', '<?= htmlspecialchars($k->deskripsi) ?>')" class="text-blue-600 font-bold mr-4 hover:underline">Edit</button>
                            <a href="index.php?page=kategori&amp;hapus_id=<?= $k->id_kategori ?>" onclick="return confirm('Hapus kategori ini?')" class="text-red-600 font-bold hover:underline">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="py-12 text-center text-gray-400 font-medium">Belum ada data kategori.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="modalKategori" class="modal fixed inset-0 flex items-center justify-center z-[100]">
    <div class="modal-overlay absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-2xl z-50 overflow-hidden">
        <div class="p-8">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <p id="modalTitle" class="text-xl font-black">Tambah Kategori Baru</p>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black text-2xl">&times;</button>
            </div>
            <form action="index.php?page=kategori" method="POST" class="mt-6 space-y-5">
                <input type="hidden" name="id_kategori" id="form_id">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="form_nama" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="form_deskripsi" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-black bg-gray-50 h-24 resize-none transition-all"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" id="btnSubmit" name="simpan_kategori" class="flex-1 py-3 bg-black text-white rounded-xl font-bold hover:bg-gray-800 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalKategori');
    function openTambahModal() {
        document.getElementById('modalTitle').innerText = "Tambah Kategori Baru";
        document.getElementById('btnSubmit').name = "simpan_kategori";
        document.getElementById('form_id').value = "";
        document.getElementById('form_nama').value = "";
        document.getElementById('form_deskripsi').value = "";
        modal.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function openEditModal(id, nama, deskripsi) {
        document.getElementById('modalTitle').innerText = "Edit Kategori";
        document.getElementById('form_id').value = id;
        document.getElementById('form_nama').value = nama;
        document.getElementById('form_deskripsi').value = deskripsi;
        document.getElementById('btnSubmit').name = "update_kategori";
        modal.classList.add('active');
        document.body.classList.add('modal-active');
    }
    function closeModal() {
        modal.classList.remove('active');
        document.body.classList.remove('modal-active');
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
