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

// ==========================================
// 4. DISABLE SUBMIT BUTTON + SPINNER
// ==========================================
function disableSubmit(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.6';
        btn.innerHTML = '<svg class="spinner-inline" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="31.4 31.4" stroke-linecap="round" stroke-dashoffset="0"/></svg> Menyimpan...';
    }
    return true;
}

// ==========================================
// 5. KONFIRMASI DELETE MODAL
// ==========================================
function confirmDelete(url, msg) {
    document.getElementById('confirmMsg').textContent = msg || 'Hapus data ini?';
    document.getElementById('confirmLink').href = url;
    document.getElementById('confirmModal').classList.remove('hidden');
    return false;
}
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}