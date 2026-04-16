<?php $__env->startSection('title', 'Detail Bon Barang'); ?>

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
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center mb-6">
                    <a href="<?php echo e(route('pegawai.bon.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Detail Bon Barang</h1>
                </div>

                <!-- Header Info -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Bon</h3>
                            <p class="text-lg font-semibold"><?php echo e($bonBarang->kode_bon ?: 'Menunggu Persetujuan'); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</h3>
                            <p class="text-lg"><?php echo e($bonBarang->tanggal_pengajuan->format('F Y')); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                            <?php switch($bonBarang->status):
                                case ('menunggu_atasan'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        ⏳ Menunggu Persetujuan Atasan
                                    </span>
                                    <?php break; ?>
                                <?php case ('menunggu_gudang'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        ⏳ Menunggu Persetujuan Gudang
                                    </span>
                                    <?php break; ?>
                                <?php case ('disetujui'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✅ Disetujui
                                    </span>
                                    <?php break; ?>
                                <?php case ('ditolak'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        ❌ Ditolak
                                    </span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </div>
                        <?php if($bonBarang->keterangan): ?>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Keterangan</h3>
                                <p class="text-lg"><?php echo e($bonBarang->keterangan); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h2 class="text-lg font-semibold">Daftar Barang</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">No</th>
                                    <th class="p-3 text-left">Nama Barang</th>
                                    <th class="p-3 text-left">Jumlah Diminta</th>
                                    <th class="p-3 text-left">Jumlah Disetujui</th>
                                    <th class="p-3 text-left">Status Detail</th>
                                    <th class="p-3 text-left">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bonBarang->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t">
                                        <td class="p-3"><?php echo e($index + 1); ?></td>
                                        <td class="p-3 font-medium"><?php echo e($detail->barang->nama_barang); ?></td>
                                        <td class="p-3"><?php echo e($detail->jumlah_diminta); ?> <?php echo e($detail->barang->satuan); ?></td>
                                        <td class="p-3">
                                            <?php if($detail->jumlah_disetujui): ?>
                                                <?php echo e($detail->jumlah_disetujui); ?> <?php echo e($detail->barang->satuan); ?>

                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3">
                                            <?php switch($detail->status_detail):
                                                case ('menunggu'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('disetujui'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Disetujui
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('ditolak'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('sebagian'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Sebagian
                                                    </span>
                                                    <?php break; ?>
                                            <?php endswitch; ?>
                                        </td>
                                        <td class="p-3">
                                            <?php if($detail->catatan): ?>
                                                <span class="text-gray-600"><?php echo e($detail->catatan); ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Timeline -->
                <?php if($bonBarang->tanggal_atasan || $bonBarang->tanggal_gudang): ?>
                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h2 class="text-lg font-semibold mb-4">Riwayat Persetujuan</h2>
                        <div class="space-y-4">
                            <?php if($bonBarang->tanggal_atasan): ?>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 <?php echo e($bonBarang->status === 'ditolak' ? 'bg-red-100' : 'bg-blue-100'); ?> rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 <?php echo e($bonBarang->status === 'ditolak' ? 'text-red-600' : 'text-blue-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <?php if($bonBarang->status === 'ditolak'): ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            <?php else: ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            <?php endif; ?>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium"><?php echo e($bonBarang->status === 'ditolak' ? 'Ditolak Atasan' : 'Disetujui Atasan'); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo e($bonBarang->tanggal_atasan->format('F Y')); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($bonBarang->tanggal_gudang): ?>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">Disetujui Gudang</p>
                                        <p class="text-sm text-gray-500"><?php echo e($bonBarang->tanggal_gudang->format('F Y')); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/pegawai/bon/show.blade.php ENDPATH**/ ?>