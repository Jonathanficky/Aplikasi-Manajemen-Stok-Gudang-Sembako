<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Toko Sembako' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .modal { transition: opacity 0.2s ease; opacity: 0; pointer-events: none; }
        .modal.active { opacity: 1; pointer-events: auto; }
        body.modal-active { overflow: hidden; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#fcfcfc]">

    <header class="bg-white border-b border-gray-200 px-8 py-3 flex justify-between items-center sticky top-0 z-[110]">
        <div class="flex items-center gap-3">
            <div class="bg-[#05051a] p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-gray-900 leading-none">Toko Sembako</h1>
                <p class="text-gray-400 text-[10px] uppercase tracking-widest mt-1">Sistem Manajemen Stok</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="relative">
                <button id="btnNotif" class="text-gray-500 hover:text-[#05051a] relative p-2 transition-colors rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <?php if ($jmlKritis > 0): ?><span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white border-2 border-white"><?= $jmlKritis ?></span><?php endif; ?>
                </button>

                <div id="dropdownNotif" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[100] overflow-hidden text-left">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h4 class="font-bold text-gray-900 text-xs uppercase tracking-widest">Notifikasi Stok</h4>
                    </div>
                    <div class="max-h-80 overflow-y-auto custom-scrollbar text-left">
                        <?php if ($jmlKritis > 0): $notifList = View::notifStokKritis(); ?>
                            <?php foreach ($notifList as $n): ?>
                            <div class="px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full flex-shrink-0"></div>
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900 leading-tight">Stok Menipis: <span class="text-red-600"><?= htmlspecialchars($n['nama_barang']) ?></span></p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">Sisa stok: <span class="font-bold text-red-600"><?= $n['stok'] ?></span>. Segera restok.</p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="py-10 text-center">
                                <p class="text-xs text-gray-400 font-medium">Semua stok aman.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 border-l pl-6 border-gray-300">
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-900 leading-none"><?= $nama_user ?></p>
                    <p class="text-[10px] text-gray-400"><?= strtoupper($role_user) ?></p>
                </div>
                <a href="index.php?page=logout" onclick="return confirm('Keluar?')" class="text-gray-900 hover:text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </div>
    </header>

    <button id="hamburger" class="md:hidden bg-[#05051a] text-white px-4 py-3 flex items-center gap-2 w-full text-sm font-medium" onclick="document.getElementById('navMenu').classList.toggle('hidden')">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        Menu
    </button>

    <nav id="navMenu" class="bg-[#05051a] px-4 md:px-8 flex-col md:flex-row md:flex gap-1 hidden md:flex overflow-x-auto">
        <a href="index.php?page=dashboard" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'dashboard' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <?php if (Auth::isAdmin()): ?>
        <a href="index.php?page=kategori" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'kategori' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            Kategori
        </a>
        <a href="index.php?page=barang" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'barang' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Barang
        </a>
        <a href="index.php?page=supplier" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'supplier' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Supplier
        </a>
        <a href="index.php?page=transaksi_masuk" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'transaksi_masuk' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Transaksi Masuk
        </a>
        <a href="index.php?page=transaksi_keluar" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'transaksi_keluar' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Transaksi Keluar
        </a>
        <?php endif; ?>

        <a href="index.php?page=laporan" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-all <?= $activeMenu === 'laporan' ? 'text-white border-b-2 border-white bg-white/10' : 'text-gray-300 hover:text-white' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Laporan
        </a>
    </nav>
