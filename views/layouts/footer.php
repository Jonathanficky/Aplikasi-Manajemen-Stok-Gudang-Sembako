    <?php
    /**
     * ==============================================
     * Nama Anggota  : Ariyan
     * Nama File     : views/layouts/footer.php
     * ==============================================
     */
    if (Session::hasFlash()): $flash = Session::getFlash(); ?>
    <style>
        .toast-popup {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            font-size: 13px;
            font-weight: 600;
            max-width: 360px;
            animation: toastIn 0.35s ease-out forwards;
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(60px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .toast-popup-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .toast-popup-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .toast-popup svg { width: 18px !important; height: 18px !important; flex-shrink: 0; }
        .toast-popup .toast-close {
            margin-left: auto;
            cursor: pointer;
            opacity: 0.4;
            background: none;
            border: none;
            font-size: 18px;
            line-height: 1;
            padding: 0 2px;
            color: inherit;
        }
        .toast-popup .toast-close:hover { opacity: 1; }
    </style>
    <div class="toast-popup toast-popup-<?= $flash['type'] ?>" id="toastMessage">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?php if ($flash['type'] === 'success'): ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            <?php else: ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            <?php endif; ?>
        </svg>
        <span><?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?></span>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <script>
        setTimeout(function(){
            var t = document.getElementById('toastMessage');
            if(t) { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; setTimeout(function(){ t.remove(); }, 300); }
        }, 4000);
    </script>
    <?php endif; ?>

    <div id="confirmModal" class="fixed inset-0 flex items-center justify-center z-[9999] hidden">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="bg-white w-11/12 md:max-w-sm mx-auto rounded-2xl shadow-2xl z-50 p-8 text-center relative">
            <div class="w-14 h-14 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <p id="confirmMsg" class="text-gray-800 font-bold text-lg mb-1">Hapus data ini?</p>
            <p class="text-gray-400 text-xs mb-6">Tindakan ini tidak bisa dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">Batal</button>
                <a id="confirmLink" href="#" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all text-center">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>
