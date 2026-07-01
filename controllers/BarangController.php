<?php
class BarangController
{
    public function index(): void
    {
        Auth::checkAdmin();
        $user = Auth::user();
        $kategoriId = (int) ($_GET['kategori'] ?? 0);

        if ($kategoriId > 0) {
            $barangList = Barang::byKategori($kategoriId);
        } else {
            $barangList = Barang::all();
        }

        $kategoriList = Kategori::all();
        $totalKritis = Barang::totalStokKritis();
        $stokKritis = [];
        if ($totalKritis > 0) {
            $stokKritis = Barang::stokKritis();
        }

        View::render('barang/index', [
            'nama_user' => $user['nama_lengkap'],
            'role_user' => $user['role'],
            'barangList' => $barangList,
            'kategoriList' => $kategoriList,
            'totalBarang' => count($barangList),
            'jmlKritis' => $totalKritis,
            'stokKritis' => $stokKritis,
            'selectedKategori' => $kategoriId,
        ]);
    }

    public function simpan(): void
    {
        Auth::checkAdmin();

        $kode = trim($_POST['kode_barang'] ?? '');
        if (empty($kode)) {
            Session::setFlash('error', 'Kode barang harus diisi!');
            header("location:index.php?page=barang");
            exit();
        }

        $user = Auth::user();
        $stokTambahan = max(0, (int) ($_POST['stok'] ?? 0));
        $hargaBeli = (int) ($_POST['harga_beli'] ?? 0);
        $existing = Barang::findByKode($kode);

        if ($existing) {
            $existing->harga_beli = $hargaBeli;
            $existing->save();

            if ($stokTambahan > 0) {
                $trx = new TransaksiMasuk([
                    'id_barang' => $existing->id_barang,
                    'id_supplier' => Supplier::getStokAwal(),
                    'tanggal' => date('Y-m-d'),
                    'jumlah' => $stokTambahan,
                    'harga_beli' => $hargaBeli,
                    'status' => 'lunas',
                    'keterangan' => 'Penambahan stok ' . htmlspecialchars($existing->nama_barang),
                    'id_user' => $user['id_user'] ?? 0,
                    'pencatat' => $user['nama_lengkap'] ?? '',
                ]);
                $trx->save();
            }

            Session::setFlash('success', "Stok {$existing->nama_barang} bertambah (+{$stokTambahan})!");
        } else {
            $_POST['stok'] = 0;
            $barang = new Barang($_POST);
            $barang->stok = 0;

            $id_barang = $barang->save();

            if ($id_barang && $stokTambahan > 0) {
                $trx = new TransaksiMasuk([
                    'id_barang' => $id_barang,
                    'id_supplier' => Supplier::getStokAwal(),
                    'tanggal' => date('Y-m-d'),
                    'jumlah' => $stokTambahan,
                    'harga_beli' => $hargaBeli,
                    'status' => 'lunas',
                    'keterangan' => 'Stok awal barang baru',
                    'id_user' => $user['id_user'] ?? 0,
                    'pencatat' => $user['nama_lengkap'] ?? '',
                ]);
                $trx->save();
            }

            Session::setFlash('success', 'Barang berhasil ditambahkan!');
        }

        header("location:index.php?page=barang");
        exit();
    }

    public function update(): void
    {
        Auth::checkAdmin();
        $barang = Barang::find($_POST['id_barang']);
        if ($barang) {
            $user = Auth::user();
            $stokLama = (int) $barang->stok;
            $stokBaru = max(0, (int) ($_POST['stok'] ?? 0));
            $selisih = $stokBaru - $stokLama;
            $hargaBeli = (int) ($_POST['harga_beli'] ?? 0);

            $barang->kode_barang = $_POST['kode_barang'];
            $barang->nama_barang = $_POST['nama_barang'];
            $barang->id_kategori = $_POST['id_kategori'];
            $barang->stok = $stokBaru;
            $barang->stok_min = $_POST['stok_min'];
            $barang->harga_beli = $hargaBeli;
            $barang->save();

            $koneksi = Database::getInstance()->getConnection();

            if ($selisih > 0) {
                $supplierId = Supplier::getAdmin();
                $tanggal = date('Y-m-d');
                $status = 'lunas';
                $ket = 'Penyesuaian stok (tambah)';
                $userId = (int) ($user['id_user'] ?? 0);
                $pencatat = $user['nama_lengkap'] ?? '';
                $stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi_masuk (id_barang, id_supplier, tanggal, jumlah, harga_beli, status, keterangan, id_user, pencatat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, 'iississis', $barang->id_barang, $supplierId, $tanggal, $selisih, $hargaBeli, $status, $ket, $userId, $pencatat);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } elseif ($selisih < 0) {
                $abs = abs($selisih);
                $tanggal = date('Y-m-d');
                $tujuan = 'Admin';
                $ket = 'Penyesuaian stok (kurang)';
                $userId = (int) ($user['id_user'] ?? 0);
                $stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi_keluar (id_barang, id_user, tanggal, jumlah, tujuan, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, 'iisiss', $barang->id_barang, $userId, $tanggal, $abs, $tujuan, $ket);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            Session::setFlash('success', 'Barang berhasil diperbarui!');
        } else {
            Session::setFlash('error', 'Barang tidak ditemukan!');
        }
        header("location:index.php?page=barang");
        exit();
    }

    public function hapus(int $id): void
    {
        Auth::checkAdmin();
        $barang = Barang::find($id);
        if ($barang) {
            $barang->delete();
            Session::setFlash('success', 'Barang berhasil dihapus!');
        } else {
            Session::setFlash('error', 'Barang tidak ditemukan!');
        }
        header("location:index.php?page=barang");
        exit();
    }


}
