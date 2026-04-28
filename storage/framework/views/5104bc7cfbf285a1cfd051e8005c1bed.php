<?php $__env->startSection('title', 'Detail Bon Masuk - Gudang'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
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

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center mb-6">
                    <a href="<?php echo e(route('gudang.bon-masuk.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Detail Bon Masuk</h1>
                </div>

                <!-- Header Info -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">ID Bon Masuk</h3>
                            <p class="text-lg font-semibold"><?php echo e($bonMasuk->id); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Supplier</h3>
                            <p class="text-lg"><?php echo e($bonMasuk->supplier ?: '-'); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Masuk</h3>
                            <p class="text-lg"><?php echo e($bonMasuk->tanggal_masuk ? $bonMasuk->tanggal_masuk->format('d/m/Y') : '-'); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Petugas Gudang</h3>
                            <p class="text-lg"><?php echo e($bonMasuk->gudang->name); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                            <?php if($bonMasuk->status === 'selesai'): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Selesai
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏳ Pending
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Success Alert -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Stok Telah Ditambahkan</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Semua barang pada bon masuk ini telah otomatis ditambahkan ke stok.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Details -->
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-blue-50 border-b">
                        <h2 class="text-lg font-semibold">Detail Barang Masuk</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Barang</th>
                                    <th class="p-3 text-left">Stok Sebelum</th>
                                    <th class="p-3 text-left">Jumlah Masuk</th>
                                    <th class="p-3 text-left">Stok Sesudah</th>
                                    <th class="p-3 text-left">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bonMasuk->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t">
                                        <td class="p-3">
                                            <strong><?php echo e($detail->barang->nama_barang); ?></strong>
                                            <br>
                                            <span class="text-xs text-gray-500"><?php echo e($detail->barang->satuan); ?></span>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?php echo e($detail->barang->stok - $detail->jumlah_masuk); ?> <?php echo e($detail->barang->satuan); ?>

                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                +<?php echo e($detail->jumlah_masuk); ?> <?php echo e($detail->barang->satuan); ?>

                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($detail->barang->stok); ?> <?php echo e($detail->barang->satuan); ?>

                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <?php echo e($detail->catatan ?: '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total Jenis Barang</p>
                            <p class="text-3xl font-bold text-blue-600"><?php echo e($bonMasuk->details->count()); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total Barang Masuk</p>
                            <p class="text-3xl font-bold text-green-600"><?php echo e($bonMasuk->details->sum('jumlah_masuk')); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total Stok Bertambah</p>
                            <p class="text-3xl font-bold text-purple-600"><?php echo e($bonMasuk->details->sum('jumlah_masuk')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-3">
                    <a href="<?php echo e(route('gudang.bon-masuk.print', $bonMasuk->id)); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        🖨️ Print Faktur
                    </a>
                    <a href="<?php echo e(route('gudang.bon-masuk.index')); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </main>

        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/bon-masuk/show.blade.php ENDPATH**/ ?>