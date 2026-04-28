<!-- Sidebar -->
<aside class="w-64 bg-white shadow-sm border-r border-gray-200 min-h-screen">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Menu</h2>
        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="<?php echo e(route('dashboard')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('dashboard') || request()->is('dashboard/*') || request()->is('pegawai/dashboard') || request()->is('atasan/dashboard') || request()->is('gudang/dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Dashboard
            </a>

            <?php if(auth()->user()->role === 'pegawai'): ?>
            <!-- Pegawai Menu -->
            <a href="<?php echo e(route('pegawai.barang.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('pegawai/barang') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Barang
            </a>

            <a href="<?php echo e(route('pegawai.bon.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('pegawai/bon*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Bon Barang
            </a>

            <?php elseif(auth()->user()->role === 'atasan'): ?>
            <!-- Atasan Menu -->
            <a href="<?php echo e(route('atasan.barang.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('atasan/barang') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Barang
            </a>

            <a href="<?php echo e(route('atasan.bon.my')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('atasan/bon-saya*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Bon Barang
            </a>

            <a href="<?php echo e(route('atasan.bon.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('atasan/bon') && !request()->is('atasan/bon-saya*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Approval Bon
            </a>

            <?php elseif(auth()->user()->role === 'gudang'): ?>
            <!-- Gudang Menu -->
            <a href="<?php echo e(route('gudang.barang.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('gudang/barang') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Barang
            </a>

            <a href="<?php echo e(route('gudang.bon.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('gudang/bon') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Approval Bon Barang
            </a>

 <a href="<?php echo e(route('gudang.bon-masuk.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('gudang/bon-masuk*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                </svg>
                Bon Masuk
            </a>

            <a href="<?php echo e(route('gudang.bon.history')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('gudang/bon-history') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                History Bon
            </a>

           

            <?php elseif(auth()->user()->role === 'administrator'): ?>
            <!-- Administrator Menu -->
            <a href="<?php echo e(route('administrator.divisions.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('administrator/divisions*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Kelola Divisi
            </a>

            <a href="<?php echo e(route('administrator.accounts.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('administrator/accounts*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Kelola Akun
            </a>

            <a href="<?php echo e(route('administrator.password-requests.index')); ?>"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?php echo e(request()->is('administrator/password-requests*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'); ?> transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                Permintaan Password
            </a>
            <?php endif; ?>
        </nav>
    </div>
</aside>

<?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/components/sidebar.blade.php ENDPATH**/ ?>