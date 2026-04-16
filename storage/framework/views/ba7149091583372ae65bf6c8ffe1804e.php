

<?php $__env->startSection('title', 'Dashboard Gudang - Bonn DIG'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar (hidden di mobile) -->
    <div class="hidden md:flex">
        <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar (desktop only) -->
        <div class="hidden md:block">
            <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Mobile Navbar -->
        <?php echo $__env->make('components.mobile-navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

                <!-- Header Section -->
                <div class="mb-8">
                    <a href="<?php echo e(route('profile')); ?>" class="block group">
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-transparent hover:shadow-xl transition-all duration-300 hover:border-purple-600 hover:scale-[1.02]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center group-hover:text-purple-600 transition-colors duration-300">
                                        <svg class="w-8 h-8 mr-3 text-purple-600 group-hover:text-purple-700 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Dashboard Gudang
                                    </h1>
                                    <p class="mt-2 text-gray-600 group-hover:text-purple-600 transition-colors duration-300">
                                        Selamat datang, <span class="font-semibold text-purple-600 group-hover:text-purple-700 transition-colors duration-300"><?php echo e(auth()->user()->name ?? 'Gudang'); ?></span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-600 transition-colors duration-300">
                                        NIP: <?php echo e(auth()->user()->nip ?? '-'); ?> | Divisi: <?php echo e(auth()->user()->divisi ?? '-'); ?>

                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 group-hover:text-gray-600 transition-colors duration-300"><?php echo e(now()->timezone('Asia/Jakarta')->format('l, d F Y H:i')); ?> WIB</p>
                                    <div class="mt-2 h-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <svg class="w-5 h-5 text-purple-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <a href="<?php echo e(route('gudang.barang.index')); ?>" class="group">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-300 hover:scale-105">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Inventaris</p>
                                    <p class="text-lg font-semibold text-gray-900">Kelola Barang</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?php echo e(route('gudang.barang.create')); ?>" class="group">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-300 hover:scale-105">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Barang</p>
                                    <p class="text-lg font-semibold text-gray-900">Tambah Barang</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?php echo e(route('gudang.bon.index')); ?>" class="group">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-300 hover:scale-105">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Pengajuan</p>
                                    <p class="text-lg font-semibold text-gray-900">Bon Barang</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="group">
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                            <div class="flex items-center">
                                <div class="p-3 bg-orange-100 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Status</p>
                                    <p class="text-lg font-semibold text-orange-600">Inventory</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/dashboard.blade.php ENDPATH**/ ?>