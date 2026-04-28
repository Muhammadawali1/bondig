<?php $__env->startSection('title', 'Bon Masuk - Gudang'); ?>

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

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold">📥 Bon Masuk</h1>
                <div class="flex gap-2">
                    <a href="<?php echo e(route('gudang.bon-masuk.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Bon Masuk
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="border-b">
                    <nav class="flex space-x-8 px-6" aria-label="Kategori Filter">
                        <button onclick="filterByCategory('all')" class="category-tab py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" data-category="all">
                            📋 Lihat Semua
                        </button>
                        <button onclick="filterByCategory('bulan')" class="category-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-category="bulan">
                            📅 Lihat Berdasarkan Bulan
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
                                $bonYears = $bonMasuks->pluck('tanggal_masuk')->map(function($date) {
                                    return $date ? $date->format('Y') : null;
                                })->filter()->unique()->sort()->values();
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
                                $bonMonths = $bonMasuks->pluck('tanggal_masuk')->map(function($date) {
                                    return $date ? $date->format('m') : null;
                                })->filter()->unique()->sort()->values();
                            ?>
                            <?php $__currentLoopData = $bonMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($month); ?>"><?php echo e($months[$month]); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Bon Masuk</p>
                            <p class="text-2xl font-semibold"><?php echo e($bonMasuks->count()); ?></p>
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
                            <p class="text-sm font-medium text-gray-500">Total Item Masuk</p>
                            <p class="text-2xl font-semibold"><?php echo e($bonMasuks->sum(function($bon) { return $bon->details->count(); })); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                            <p class="text-2xl font-semibold"><?php echo e($bonMasuks->filter(function($bon) { return $bon->tanggal_masuk->month === now()->month; })->count()); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bon Masuk List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <?php if($bonMasuks->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">ID Bon Masuk</th>
                                    <th class="p-3 text-left">Supplier</th>
                                    <th class="p-3 text-left">Tanggal Masuk</th>
                                    <th class="p-3 text-left">Jumlah Item</th>
                                    <th class="p-3 text-left">Total Barang</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bonMasuks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonMasuk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="p-3"><?php echo e($bonMasuk->id); ?></td>
                                        <td class="p-3"><?php echo e($bonMasuk->supplier ?: '-'); ?></td>
                                        <td class="p-3"><?php echo e($bonMasuk->tanggal_masuk ? $bonMasuk->tanggal_masuk->format('d/m/Y') : '-'); ?></td>
                                        <td class="p-3"><?php echo e($bonMasuk->details->count()); ?> item</td>
                                        <td class="p-3"><?php echo e($bonMasuk->details->sum('jumlah_masuk')); ?> barang</td>
                                        <td class="p-3">
                                            <?php if($bonMasuk->status === 'selesai'): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Selesai
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⏳ Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex justify-center gap-2">
                                                <a href="<?php echo e(route('gudang.bon-masuk.show', $bonMasuk->id)); ?>" 
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v16a2 2 0 002 2h8a2 2 0 002-2v-2z"></path>
                            </svg>
                        </div>
                        <p>Belum ada bon masuk</p>
                        <a href="<?php echo e(route('gudang.bon-masuk.create')); ?>" class="text-blue-600 hover:underline mt-2 inline-block">
                            Buat bon masuk pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>

<script>
function filterByCategory(category) {
    // Update tab styles
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeTab = document.querySelector(`[data-category="${category}"]`);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
    
    // Show/hide filter sections
    const bulanFilter = document.getElementById('bulanFilter');
    
    if (category === 'bulan') {
        bulanFilter.classList.remove('hidden');
    } else {
        bulanFilter.classList.add('hidden');
    }
}

// Initialize filter on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if there are existing filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilter = urlParams.has('bulan') || urlParams.has('tahun');
    
    if (hasFilter) {
        filterByCategory('bulan');
        // Set the select values
        if (urlParams.has('tahun')) {
            document.getElementById('tahunSelect').value = urlParams.get('tahun');
        }
        if (urlParams.has('bulan')) {
            document.getElementById('bulanSelect').value = urlParams.get('bulan');
        }
    }
});

// Add event listeners for select changes
document.getElementById('tahunSelect').addEventListener('change', function() {
    applyFilter();
});

document.getElementById('bulanSelect').addEventListener('change', function() {
    applyFilter();
});

function applyFilter() {
    const tahun = document.getElementById('tahunSelect').value;
    const bulan = document.getElementById('bulanSelect').value;
    
    const url = new URL(window.location.href);
    url.searchParams.delete('bulan');
    url.searchParams.delete('tahun');
    
    if (tahun) {
        url.searchParams.set('tahun', tahun);
    }
    if (bulan) {
        url.searchParams.set('bulan', bulan);
    }
    
    window.location.href = url.toString();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/bon-masuk/index.blade.php ENDPATH**/ ?>