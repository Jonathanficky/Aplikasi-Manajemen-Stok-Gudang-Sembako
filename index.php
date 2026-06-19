<?php
require_once __DIR__ . '/autoload.php';
Session::start();

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'proses_login':
        $controller = new AuthController();
        $controller->prosesLogin();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'barang':
        $controller = new BarangController();
        if (isset($_POST['simpan_barang'])) {
            $controller->simpan();
        } elseif (isset($_POST['update_barang'])) {
            $controller->update();
        } elseif (isset($_GET['hapus_id'])) {
            $controller->hapus((int) $_GET['hapus_id']);
        } else {
            $controller->index();
        }
        break;

    case 'kategori':
        $controller = new KategoriController();
        if (isset($_POST['simpan_kategori'])) {
            $controller->simpan();
        } elseif (isset($_POST['update_kategori'])) {
            $controller->update();
        } elseif (isset($_GET['hapus_id'])) {
            $controller->hapus((int) $_GET['hapus_id']);
        } else {
            $controller->index();
        }
        break;

    case 'supplier':
        $controller = new SupplierController();
        if (isset($_POST['simpan_supplier'])) {
            $controller->simpan();
        } elseif (isset($_POST['update_supplier'])) {
            $controller->update();
        } elseif (isset($_GET['hapus_id'])) {
            $controller->hapus((int) $_GET['hapus_id']);
        } else {
            $controller->index();
        }
        break;

    case 'transaksi_masuk':
        $controller = new TransaksiMasukController();
        if (isset($_POST['simpan_masuk'])) {
            $controller->simpan();
        } elseif (isset($_POST['update_masuk'])) {
            $controller->update();
        } else {
            $controller->index();
        }
        break;

    case 'transaksi_keluar':
        $controller = new TransaksiKeluarController();
        if (isset($_POST['simpan_keluar'])) {
            $controller->simpan();
        } else {
            $controller->index();
        }
        break;

    case 'laporan':
        $controller = new LaporanController();
        $controller->index();
        break;

    case 'export_laporan':
        $controller = new LaporanController();
        $controller->exportExcel();
        break;

    default:
        header("location:index.php?page=login");
        exit();
}
