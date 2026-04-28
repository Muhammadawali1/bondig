

<?php $__env->startSection('title', 'History Bon Barang'); ?>

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
        <main class="flex-1 p-6">
            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="flex justify-start items-center mb-6">
                <h1 class="text-2xl font-bold">📋 History Bon Barang</h1>
            </div>

            <!-- Kategori Filter -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="border-b">
                    <nav class="flex space-x-8 px-6" aria-label="Kategori Filter">
                        <button onclick="filterByCategory('all')" class="category-tab py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" data-category="all">
                            📋 Lihat Semua Bon Berdasarkan Divisi
                        </button>
                        <button onclick="filterByCategory('semua')" class="category-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-category="semua">
                            📄 Lihat Semua Bon
                        </button>
                        <button onclick="filterByCategory('bulan')" class="category-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-category="bulan">
                            📅 Lihat Bon Berdasarkan Bulan
                        </button>
                    </nav>
                </div>
                
                <!-- Bulan Filter (Hidden by default) -->
                <div id="bulanFilter" class="hidden p-4 border-t">
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700">Pilih Tahun:</label>
                        <select id="tahunSelect" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Tahun --</option>
                            <?php
                                // Get unique years from bon data
                                $bonYears = $bonBarangs->flatten()->pluck('tanggal_pengajuan')->map(function($date) {
                                    return $date->format('Y');
                                })->unique()->sort()->values();
                            ?>
                            <?php $__currentLoopData = $bonYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        
                        <label class="text-sm font-medium text-gray-700 ml-4">Pilih Bulan:</label>
                        <select id="bulanSelect" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Bulan --</option>
                            <?php
                                $months = [
                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                ];
                                
                                // Get unique months from bon data
                                $bonMonths = $bonBarangs->flatten()->pluck('tanggal_pengajuan')->map(function($date) {
                                    return $date->format('m');
                                })->unique()->sort()->values();
                            ?>
                            <?php $__currentLoopData = $bonMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($month); ?>"><?php echo e($months[$month]); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Disetujui</p>
                            <p class="text-2xl font-semibold text-gray-900 stat-disetujui">
                                <?php echo e($bonBarangs->flatten()->where('status', 'disetujui')->count()); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Disetujui Sebagian</p>
                            <p class="text-2xl font-semibold text-gray-900 stat-disetujui-sebagian">
                                <?php echo e($bonBarangs->flatten()->where('status', 'disetujui_sebagian')->count()); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Bon</p>
                            <p class="text-2xl font-semibold text-gray-900 stat-total"><?php echo e($bonBarangs->flatten()->count()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Hapus Berdasarkan Tahun</p>
                                <p class="text-lg font-semibold text-gray-900">Hapus Bon per Tahun</p>
                            </div>
                        </div>
                        <button onclick="showDeleteAllModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                            Hapus Bon
                        </button>
                    </div>
                </div>
            </div>

            <!-- History List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <?php if($bonBarangs->count() > 0): ?>
                    <?php $__currentLoopData = $bonBarangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $divisi => $bons): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-6 divisi-group" data-divisi="<?php echo e($divisi); ?>">
                            <div class="px-6 py-4 bg-gray-50 border-b">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Divisi: <?php echo e($divisi ?: 'Tidak ada divisi'); ?>

                                    <span class="ml-2 text-sm text-gray-500">(<?php echo e($bons->count()); ?> bon)</span>
                                </h3>
                                <div class="mt-2 flex gap-4 text-sm">
    <span class="text-green-600 stat-divisi-disetujui">
        ✅ Disetujui: <?php echo e($bons->where('status', 'disetujui')->count()); ?>

    </span>
    <span class="text-yellow-600 stat-divisi-disetujui-sebagian">
        ⚠️ Disetujui Sebagian: <?php echo e($bons->where('status', 'disetujui_sebagian')->count()); ?>

    </span>
</div>
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
                                            <th class="p-3 text-left">Status</th>
                                            <th class="p-3 text-left">Proses Selesai</th>
                                            <th class="p-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="border-t hover:bg-gray-50 history-row" 
                                                data-divisi="<?php echo e($bon->divisi); ?>" 
                                                data-bulan="<?php echo e($bon->tanggal_pengajuan->format('m')); ?>"
                                                data-tahun="<?php echo e($bon->tanggal_pengajuan->format('Y')); ?>"
                                                data-status="<?php echo e($bon->status); ?>">
                                                <td class="p-3 font-mono text-sm"><?php echo e($bon->kode_bon); ?></td>
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
                                                <td class="p-3"><?php echo e($bon->tanggal_pengajuan->format('F Y')); ?></td>
                                                <td class="p-3"><?php echo e($bon->details->count()); ?> item</td>
                                                <td class="p-3">
                                                    <?php switch($bon->status):
                                                        case ('disetujui'): ?>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                ✅ Disetujui
                                                            </span>
                                                            <?php break; ?>
                                                        <?php case ('disetujui_sebagian'): ?>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⚠️ Disetujui Sebagian
                                                            </span>
                                                            <?php break; ?>
                                                    <?php endswitch; ?>
                                                </td>
                                                <td class="p-3">
                                                    <?php if(($bon->status === 'disetujui' || $bon->status === 'disetujui_sebagian') && $bon->tanggal_gudang): ?>
                                                        <?php echo e($bon->tanggal_gudang->format('F Y')); ?>

                                                    <?php elseif($bon->status === 'menunggu_atasan' || $bon->status === 'menunggu_gudang'): ?>
                                                        <span class="text-yellow-600">Menunggu Persetujuan</span>
                                                    <?php else: ?>
                                                        <span class="text-gray-400">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="p-3">
                                                    <div class="flex justify-center gap-2">
                                                        <a href="<?php echo e(route('gudang.bon.show-history', $bon->id)); ?>" 
                                                           class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-sm">
                                                            Detail
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium mb-2">Belum ada history bon barang</p>
                        <p class="text-sm">History akan muncul setelah ada bon yang disetujui</p>
                        <div class="mt-4">
                            <a href="<?php echo e(route('gudang.bon.index')); ?>" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Lihat Bon Menunggu
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Semua Bon List (Hidden by default) -->
            <div id="semuaBonList" class="bg-white shadow rounded-lg overflow-hidden hidden">
                <?php
                    // Get all bon data for 'semua' category
                    $allBons = $bonBarangs;
                    if (is_object($allBons) && method_exists($allBons, 'flatten')) {
                        $allBons = $allBons->flatten();
                    }
                ?>
                <?php if($allBons->count() > 0): ?>
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            📄 Semua Bon
                            <span class="ml-2 text-sm text-gray-500">(<?php echo e($allBons->count()); ?> bon)</span>
                        </h3>
                        <div class="mt-2 flex gap-4 text-sm">
                            <span class="text-green-600">
                                ✅ Disetujui: <?php echo e($allBons->whereIn('status', ['disetujui', 'disetujui_sebagian'])->count()); ?>

                            </span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Kode Bon</th>
                                    <th class="p-3 text-left">Pemohon</th>
                                    <th class="p-3 text-left">Role</th>
                                    <th class="p-3 text-left">Divisi</th>
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Jumlah Item</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Proses Selesai</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allBons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t hover:bg-gray-50 semua-bon-row" 
                                        data-divisi="<?php echo e($bon->divisi); ?>" 
                                        data-bulan="<?php echo e($bon->tanggal_pengajuan->format('m')); ?>"
                                        data-tahun="<?php echo e($bon->tanggal_pengajuan->format('Y')); ?>"
                                        data-status="<?php echo e($bon->status); ?>">
                                        <td class="p-3 font-mono text-sm"><?php echo e($bon->kode_bon); ?></td>
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
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?php echo e($bon->divisi ?: 'Tidak ada divisi'); ?>

                                            </span>
                                        </td>
                                        <td class="p-3"><?php echo e($bon->tanggal_pengajuan->format('F Y')); ?></td>
                                        <td class="p-3"><?php echo e($bon->details->count()); ?> item</td>
                                        <td class="p-3">
                                            <?php switch($bon->status):
                                                case ('disetujui'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Disetujui
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('disetujui_sebagian'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⚠️ Disetujui Sebagian
                                                    </span>
                                                    <?php break; ?>
                                            <?php endswitch; ?>
                                        </td>
                                        <td class="p-3">
                                            <?php if(($bon->status === 'disetujui' || $bon->status === 'disetujui_sebagian') && $bon->tanggal_gudang): ?>
                                                <?php echo e($bon->tanggal_gudang->format('F Y')); ?>

                                            <?php elseif($bon->status === 'menunggu_atasan' || $bon->status === 'menunggu_gudang'): ?>
                                                <span class="text-yellow-600">Menunggu Persetujuan</span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex justify-center gap-2">
                                                <a href="<?php echo e(route('gudang.bon.show-history', $bon->id)); ?>" 
                                                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-sm">
                                                    Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center text-gray-500">
                        <div class="mb-4">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium mb-2">Belum ada history bon barang</p>
                        <p class="text-sm">History akan muncul setelah ada bon yang disetujui</p>
                        <div class="mt-4">
                            <a href="<?php echo e(route('gudang.bon.index')); ?>" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Lihat Bon Menunggu
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Delete All Modal -->
<div id="deleteAllModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Hapus Bon Berdasarkan Tahun</h3>
            <form id="deleteAllForm" method="POST" action="<?php echo e(route('gudang.bon.delete-all')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Pilih tahun untuk menghapus bon barang pada tahun tersebut.</p>
                    <p class="text-xs text-red-600 mt-2">Tindakan ini akan menghapus semua bon pada tahun yang dipilih dan tidak dapat dibatalkan.</p>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun:</label>
                        <select name="tahun" id="deleteYearSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                            <option value="">-- Pilih Tahun --</option>
                            <?php
                                $availableYears = $bonBarangs->flatten()->pluck('tanggal_pengajuan')->map(function($date) {
                                    return $date->format('Y');
                                })->unique()->sort()->values();
                            ?>
                            <?php $__currentLoopData = $availableYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteAllModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus Bon Tahun Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('js/gudang-history.js')); ?>" defer></script>
<script>
function showDeleteAllModal() {
    console.log('Showing delete all modal');
    document.getElementById('deleteAllModal').classList.remove('hidden');
}

function closeDeleteAllModal() {
    console.log('Closing delete all modal');
    document.getElementById('deleteAllModal').classList.add('hidden');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/bon/history.blade.php ENDPATH**/ ?>