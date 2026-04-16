<?php $__env->startSection('title', 'Approval Bon Barang - Gudang'); ?>

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
            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <h1 class="text-2xl font-bold mb-6">📋 Approval Bon Barang</h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Menunggu Approval</p>
                            <p class="text-2xl font-semibold"><?php echo e($bonBarangs->count()); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Item</p>
                            <p class="text-2xl font-semibold"><?php echo e($bonBarangs->sum(function($bon) { return $bon->details->count(); })); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Stok Aman</p>
                            <p class="text-2xl font-semibold">
                                <?php echo e($bonBarangs->filter(function($bon) {
                                    return $bon->details->every(function($detail) {
                                        return $detail->barang->stok >= $detail->jumlah_diminta;
                                    });
                                })->count()); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bon List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <?php if($bonBarangs->count() > 0): ?>
                    <?php $__currentLoopData = $bonBarangs->groupBy('divisi'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $divisi => $bons): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-6">
                            <div class="px-6 py-4 bg-gray-50 border-b">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    📍 Divisi: <?php echo e($divisi ?: 'Tidak ada divisi'); ?>

                                    <span class="ml-2 text-sm text-gray-500">(<?php echo e($bons->count()); ?> bon)</span>
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-3 text-left">Kode Bon</th>
                                            <th class="p-3 text-left">Pemohon</th>
                                            <th class="p-3 text-left">Role</th>
                                            <th class="p-3 text-left">Tanggal</th>
                                            <th class="p-3 text-left">Jumlah Item</th>
                                            <th class="p-3 text-left">Status Stok</th>
                                            <th class="p-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="border-t hover:bg-gray-50">
                                                <td class="p-3 font-mono text-sm"><?php echo e($bon->kode_bon ?: '-'); ?></td>
                                                <td class="p-3">
                                                    <span><?php echo e($bon->pegawai->name); ?></span>
                                                </td>
                                                <td class="p-3">
                                                    <?php if($bon->pegawai->role === 'atasan'): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            Atasan
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Pegawai
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-3"><?php echo e($bon->tanggal_pengajuan->format('d/m/Y')); ?></td>
                                                <td class="p-3"><?php echo e($bon->details->count()); ?> item</td>
                                                <td class="p-3">
                                                    <?php
                                                        $allStokSafe = $bon->details->every(function($detail) {
                                                            return $detail->barang->stok >= $detail->jumlah_diminta;
                                                        });
                                                    ?>
                                                    <?php if($allStokSafe): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ✅ Stok Aman
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            ⚠️ Stok Tidak Mencukupi
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex justify-center gap-2">
                                                        <a href="<?php echo e(route('gudang.bon.show', $bon->id)); ?>" 
                                                           class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-sm">
                                                            Detail & Approve
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="p-8 text-center text-gray-500">
                        <div class="mb-4">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v16a2 2 0 002 2h8a2 2 0 002-2v-2z"></path>
                            </svg>
                        </div>
                        <p>Tidak ada bon barang yang menunggu approval</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/bon/index.blade.php ENDPATH**/ ?>