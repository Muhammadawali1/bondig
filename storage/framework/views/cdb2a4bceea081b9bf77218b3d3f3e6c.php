<?php $__env->startSection('title', 'Detail Bon Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
    <div class="hidden md:flex">
        <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden main-content-with-sticky-sidebar main-content-with-sticky-navbar">

        <!-- Navbar (desktop only) -->
        <div class="hidden md:block">
            <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Mobile Navbar -->
        <?php echo $__env->make('components.mobile-navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto">
                <!-- Success Message -->
                <?php if(session('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if(session('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="flex items-center mb-6">
                    <a href="<?php echo e(route('atasan.bon.my')); ?>" class="text-gray-600 hover:text-gray-900 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Detail Bon Barang</h1>
                </div>

                <!-- Bon Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Bon</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kode Bon:</span>
                                    <span class="font-mono font-semibold"><?php echo e($bonBarang->kode_bon ?: 'Menunggu Persetujuan'); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Pengajuan:</span>
                                    <span><?php echo e($bonBarang->tanggal_pengajuan->format('d/m/Y H:i')); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Divisi:</span>
                                    <span><?php echo e($bonBarang->divisi); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <?php switch($bonBarang->status):
                                        case ('menunggu_gudang'): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                ⏳ Menunggu Gudang
                                            </span>
                                            <?php break; ?>
                                        <?php case ('disetujui'): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✅ Disetujui
                                            </span>
                                            <?php break; ?>
                                        <?php case ('ditolak'): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Ditolak
                                            </span>
                                            <?php break; ?>
                                    <?php endswitch; ?>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Pemohon</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span><?php echo e($bonBarang->pegawai->name); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">NIP:</span>
                                    <span><?php echo e($bonBarang->pegawai->nip); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Divisi:</span>
                                    <span><?php echo e($bonBarang->pegawai->divisi); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($bonBarang->keterangan): ?>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">Keterangan</h3>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded"><?php echo e($bonBarang->keterangan); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Bon Details -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold">Daftar Barang</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Nama Barang</th>
                                    <th class="p-3 text-left">Jumlah Diminta</th>
                                    <th class="p-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bonBarang->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="p-3"><?php echo e($detail->barang->nama_barang); ?></td>
                                        <td class="p-3"><?php echo e($detail->jumlah_diminta); ?></td>
                                        <td class="p-3">
                                            <?php switch($detail->status_detail):
                                                case ('menunggu'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Menunggu
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('disetujui'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Disetujui
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('ditolak'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ❌ Ditolak
                                                    </span>
                                                    <?php break; ?>
                                            <?php endswitch; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="<?php echo e(route('atasan.bon.my')); ?>" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Kembali
                    </a>
                    <?php if($bonBarang->status === 'menunggu_gudang'): ?>
                        
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/atasan/bon/my-show.blade.php ENDPATH**/ ?>