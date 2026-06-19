document.addEventListener("DOMContentLoaded", function() {
    
    // ==========================================
    // 1. FITUR MENU AKTIF OTOMATIS (NAVBAR)
    // ==========================================
    let currentPage = window.location.pathname.split('/').pop();
    if (currentPage === '') currentPage = 'dashboard.html';

    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.classList.remove('nav-item-active', 'text-white');
        link.classList.add('text-gray-300');
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('nav-item-active', 'text-white');
            link.classList.remove('text-gray-300');
        }
    });

    // ==========================================
    // 2. FITUR NOTIFIKASI LONCENG
    // ==========================================
    const btnNotif = document.getElementById('btnNotif');
    const dropdownNotif = document.getElementById('dropdownNotif');

    if (btnNotif && dropdownNotif) {
        btnNotif.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownNotif.classList.toggle('hidden');
        });
        window.addEventListener('click', function(e) {
            if (!dropdownNotif.contains(e.target) && !btnNotif.contains(e.target)) {
                dropdownNotif.classList.add('hidden');
            }
        });
    }

    // ==========================================
    // 3. FITUR PENCARIAN REAL-TIME (FINAL - UNTUK SEMUA HALAMAN)
    // ==========================================
    const searchInput = document.querySelector('input[placeholder*="Cari"]');
    
    if (searchInput) {
        // Matikan fungsi ENTER agar halaman tidak refresh/reload saat mencari
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        searchInput.addEventListener('input', function() {
            let filter = this.value.toLowerCase().trim();
            let table = document.querySelector('table');
            if (!table) return;

            let tbody = table.querySelector('tbody');
            if (!tbody) return;

            let rows = tbody.querySelectorAll('tr');

            rows.forEach(row => {
                // Abaikan baris jika hanya berisi pesan "Belum ada data"
                if (row.cells.length === 1 && row.innerText.includes("Belum ada")) return;

                let found = false;
                let cells = row.querySelectorAll('td');

                // Cek setiap kolom di baris tersebut
                cells.forEach(cell => {
                    if (cell.innerText.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                if (found) {
                    // Tampilkan baris jika cocok
                    row.style.setProperty('display', '', '');
                } else {
                    // SEMBUNYIKAN TOTAL JIKA TIDAK COCOK (!important)
                    // Ini yang bikin baris lain hilang dan yang dicari otomatis naik ke atas
                    row.style.setProperty('display', 'none', 'important');
                }
            });
        });
    }

    /*/ ==========================================
    // 4. FITUR MODAL "TAMBAH DATA" (POPUP FORM)
    // ==========================================
    const allButtons = document.querySelectorAll('button');
    let btnTambah = null;
    allButtons.forEach(btn => {
        if (btn.innerText.includes('Tambah') && btn.innerText.includes('+')) {
            btnTambah = btn;
        }
    }); */

    if (btnTambah) {
        btnTambah.addEventListener('click', function() {
            let title = "";
            let formHtml = "";

            if (currentPage.includes('kategori')) {
                title = "Tambah Kategori Baru";
                formHtml = `
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Nama Kategori</label><input type="text" id="input1" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-black outline-none" placeholder="Misal: Minuman"></div>
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Deskripsi</label><input type="text" id="input2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-black outline-none" placeholder="Deskripsi kategori..."></div>
                `;
            } else if (currentPage.includes('barang')) {
                title = "Tambah Barang Baru";
                formHtml = `
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Kode Barang</label><input type="text" id="input1" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="BRG-001"></div>
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Nama Barang</label><input type="text" id="input2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="Nama produk..."></div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div><label class="block text-xs font-bold mb-1 text-gray-700">Stok Awal</label><input type="number" id="input3" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="0"></div>
                        <div><label class="block text-xs font-bold mb-1 text-gray-700">Harga</label><input type="text" id="input4" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="Rp ..."></div>
                    </div>
                `;
            } else if (currentPage.includes('supplier')) {
                title = "Tambah Supplier Baru";
                formHtml = `
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Nama Supplier</label><input type="text" id="input1" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="PT / CV ..."></div>
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">No. Telpon</label><input type="text" id="input2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="08..."></div>
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Alamat Lengkap</label><textarea id="input3" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" rows="2" placeholder="Alamat..."></textarea></div>
                `;
            } else if (currentPage.includes('transaksi')) {
                title = "Catat Transaksi Baru";
                formHtml = `
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Tanggal</label><input type="date" id="input1" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none"></div>
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Nama Barang</label><input type="text" id="input2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="Pilih barang..."></div>
                    <div class="mb-4"><label class="block text-xs font-bold mb-1 text-gray-700">Jumlah Item</label><input type="number" id="input3" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm outline-none" placeholder="0"></div>
                `;
            } else {
                return; 
            }

            const modalOverlay = document.createElement('div');
            modalOverlay.className = "fixed inset-0 bg-black/60 z-[100] flex items-center justify-center backdrop-blur-sm";
            modalOverlay.id = "customModal";
            
            modalOverlay.innerHTML = `
                <div class="bg-white rounded-2xl p-7 w-full max-w-md shadow-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-extrabold text-xl text-gray-900">${title}</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-red-500 font-bold text-xl leading-none">&times;</button>
                    </div>
                    <div>
                        ${formHtml}
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button id="cancelModal" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-200 transition-colors">Batal</button>
                        <button id="saveModal" class="px-5 py-2.5 bg-[#05051a] text-white font-bold rounded-xl text-sm hover:bg-black transition-colors shadow-lg">Simpan Data</button>
                    </div>
                </div>
            `;

            document.body.appendChild(modalOverlay);

            const closeFn = () => document.body.removeChild(modalOverlay);
            document.getElementById('closeModal').addEventListener('click', closeFn);
            document.getElementById('cancelModal').addEventListener('click', closeFn);

            document.getElementById('saveModal').addEventListener('click', function() {
                const tbody = document.querySelector('tbody');
                if(!tbody) { closeFn(); return; }

                let existingRows = Array.from(tbody.querySelectorAll('tr')).filter(tr => tr.innerText.trim() !== '').length;
                let no = existingRows + 1;

                let newRow = document.createElement('tr');
                newRow.className = "border-b border-gray-200 hover:bg-gray-50 transition-colors text-gray-800";
                
                if (currentPage.includes('kategori')) {
                    let v1 = document.getElementById('input1').value || 'Tanpa Nama';
                    let v2 = document.getElementById('input2').value || '-';
                    newRow.innerHTML = `<td class="py-4 px-2">${no}</td><td class="py-4 px-2 font-bold">${v1}</td><td class="py-4 px-2 text-gray-500">${v2}</td><td class="py-4 px-2 text-right"><button class="text-blue-600 font-bold text-xs mr-3">Edit</button><button class="text-red-600 font-bold text-xs">Hapus</button></td>`;
                } 
                else if (currentPage.includes('barang')) {
                    let v1 = document.getElementById('input1').value || 'NEW';
                    let v2 = document.getElementById('input2').value || 'Item Baru';
                    let v3 = document.getElementById('input3').value || '0';
                    let v4 = document.getElementById('input4').value || 'Rp 0';
                    newRow.innerHTML = `<td class="py-4 px-2">${no}</td><td class="py-4 px-2 text-gray-500 font-mono text-xs">${v1}</td><td class="py-4 px-2 font-bold">${v2}</td><td class="py-4 px-2">-</td><td class="py-4 px-2 font-medium">${v3}</td><td class="py-4 px-2">${v4}</td><td class="py-4 px-2"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-[10px] font-bold">Aman</span></td><td class="py-4 px-2 text-right"><button class="text-blue-600 font-bold text-xs">Edit</button></td>`;
                } 
                else if (currentPage.includes('supplier')) {
                    let v1 = document.getElementById('input1').value || 'Supplier Baru';
                    let v2 = document.getElementById('input2').value || '-';
                    let v3 = document.getElementById('input3').value || '-';
                    newRow.innerHTML = `<td class="py-4 px-2">${no}</td><td class="py-4 px-2 font-mono text-xs text-gray-500">SPL-NEW</td><td class="py-4 px-2 font-bold">${v1}</td><td class="py-4 px-2">${v3}</td><td class="py-4 px-2">${v2}</td><td class="py-4 px-2">-</td><td class="py-4 px-2 text-right"><button class="text-blue-600 font-bold text-xs">Edit</button></td>`;
                }
                else if (currentPage.includes('transaksi')) {
                    let v1 = document.getElementById('input1').value || 'Hari ini';
                    let v2 = document.getElementById('input2').value || 'Item';
                    let v3 = document.getElementById('input3').value || '0';
                    let color = currentPage.includes('masuk') ? 'text-green-600' : 'text-red-600';
                    let sign = currentPage.includes('masuk') ? '+' : '-';
                    newRow.innerHTML = `<td class="py-4 px-2">${no}</td><td class="py-4 px-2">${v1}</td><td class="py-4 px-2 font-bold">${v2}</td><td class="py-4 px-2 ${color} font-extrabold">${sign}${v3}</td><td class="py-4 px-2">-</td><td class="py-4 px-2">-</td><td class="py-4 px-2 font-medium">Admin</td>`;
                }

                tbody.prepend(newRow);

                const emptyText = document.querySelector('.flex.justify-center.items-center.mt-12');
                if(emptyText) emptyText.remove();

                const totalText = document.querySelector('h3 + p.text-xs');
                if(totalText) totalText.innerText = `Total: ${no} Data`;

                closeFn();
                setTimeout(() => alert('Data berhasil ditambahkan ke tabel!'), 100);
            });
        });
    }

});

window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});     
// Ambil elemen yang dibutuhkan (khusus halaman transaksi_keluar)
const tombolSimpan = document.querySelector('button[name="simpan_keluar"]');

if (tombolSimpan) {
    const selectBarang = document.querySelector('select[name="id_barang"]');
    const inputJumlah = document.querySelector('input[name="jumlah"]');

    function validasiInstan() {
        const optionTerpilih = selectBarang.options[selectBarang.selectedIndex];
        const infoStok = optionTerpilih.text.match(/\d+/);
        const stokTersedia = infoStok ? parseInt(infoStok[0]) : 0;
        const jumlahInput = parseInt(inputJumlah.value) || 0;

        if (jumlahInput > stokTersedia) {
            tombolSimpan.disabled = true;
            tombolSimpan.classList.add('opacity-50', 'cursor-not-allowed');
            inputJumlah.classList.add('border-red-500', 'text-red-600');
        } else {
            tombolSimpan.disabled = false;
            tombolSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
            inputJumlah.classList.remove('border-red-500', 'text-red-600');
        }
    }

    inputJumlah.addEventListener('input', validasiInstan);
    selectBarang.addEventListener('change', validasiInstan);
}