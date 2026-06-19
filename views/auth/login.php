<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Sembako</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .login-card { border: 1px solid #e5e7eb; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .feature-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">
    <main class="flex-grow flex items-center justify-center px-6 lg:px-16 py-8">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <div class="flex items-center gap-2.5">
                    <div class="bg-[#05051a] p-1.5 rounded-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none">Toko Sembako</h1>
                        <p class="text-gray-400 text-[10px] uppercase tracking-wider mt-0.5">Sistem Manajemen Stok</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <h2 class="text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">Sistem Manajemen <br> Stok Gudang</h2>
                    <p class="text-gray-500 text-sm lg:text-base max-w-sm leading-relaxed">Solusi lengkap untuk mengelola stok barang toko sembako dan grosir dengan mudah, cepat, dan efisien.</p>
                </div>
                <div class="space-y-5 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="feature-icon bg-green-50 text-green-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Kelola Stok Real-time</h4>
                            <p class="text-gray-500 text-xs leading-normal">Pantau stok barang secara real-time dengan notifikasi otomatis.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="feature-icon bg-blue-50 text-blue-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Laporan & Analytics</h4>
                            <p class="text-gray-500 text-xs leading-normal">Dashboard lengkap dengan grafik pergerakan stok harian.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="feature-icon bg-pink-50 text-pink-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Export Data</h4>
                            <p class="text-gray-500 text-xs leading-normal">Export laporan ke format Excel untuk analisis lebih lanjut.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="feature-icon bg-orange-50 text-orange-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Multi-role access</h4>
                            <p class="text-gray-500 text-xs leading-normal">Hak akses berbeda untuk Admin Gudang dan Manajer.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center lg:justify-end">
                <div class="login-card bg-white p-8 w-full max-w-[380px]">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Login ke Sistem</h3>
                    <p class="text-gray-400 text-xs mb-8">Masukkan kredensial Anda untuk masuk.</p>
                    <form action="index.php?page=proses_login" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1.5 text-xs">Username</label>
                            <input type="text" name="username" required autocomplete="off" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:border-black outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1.5 text-xs">Password</label>
                            <input type="password" name="password" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:border-black outline-none transition-all text-sm">
                        </div>
                        <button type="submit" name="login" class="w-full bg-[#05051a] text-white py-3 rounded-lg font-bold text-sm hover:bg-black transition-all shadow-lg shadow-gray-200">Login ke Sistem</button>
                    </form>
                    <p class="mt-12 text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest">2024 Sistem Manajemen Stok Gudang</p>
                </div>
            </div>
        </div>
    </main>
    <footer class="py-6 text-center text-gray-400 text-[11px] font-medium italic">Sistem Manajemen Stok untuk Toko Sembako & Grosir - Professional & Reliable</footer>
    <script src="assets/script.js"></script>
</body>
</html>
