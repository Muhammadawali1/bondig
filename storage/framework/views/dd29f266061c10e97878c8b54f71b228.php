<?php $__env->startSection('title', 'Daftar Bon Barang'); ?>

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

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">📋 Daftar Bon Barang</h1>
                <a href="<?php echo e(route('pegawai.bon.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Bon Baru
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="border-b">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="filterBon('all')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" data-filter="all">
                            Semua Bon
                            <span class="bg-blue-100 text-blue-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                <?php echo e($bonBarangs->count()); ?>

                            </span>
                        </button>
                        <button onclick="filterBon('menunggu_atasan')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="menunggu_atasan">
                            Menunggu Atasan
                            <span class="bg-gray-100 text-gray-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                <?php echo e($bonBarangs->where('status', 'menunggu_atasan')->count()); ?>

                            </span>
                        </button>
                        <button onclick="filterBon('menunggu_gudang')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="menunggu_gudang">
                            Menunggu Gudang
                            <span class="bg-gray-100 text-gray-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                <?php echo e($bonBarangs->where('status', 'menunggu_gudang')->count()); ?>

                            </span>
                        </button>
                        <button onclick="filterBon('ditolak')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="ditolak">
                            Ditolak
                            <span class="bg-gray-100 text-gray-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                <?php echo e($bonBarangs->where('status', 'ditolak')->count()); ?>

                            </span>
                        </button>
                    </nav>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <?php if($bonBarangs->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Kode Bon</th>
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Jumlah Item</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bonTableBody">
                                <?php $__currentLoopData = $bonBarangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t hover:bg-gray-50 bon-row" data-status="<?php echo e($bon->status); ?>">
                                        <td class="p-3 font-mono text-sm"><?php echo e($bon->kode_bon ?: '-'); ?></td>
                                        <td class="p-3"><?php echo e($bon->tanggal_pengajuan->format('F Y')); ?></td>
                                        <td class="p-3">
                                            <?php switch($bon->status):
                                                case ('menunggu_atasan'): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Menunggu Atasan
                                                    </span>
                                                    <?php break; ?>
                                                <?php case ('menunggu_gudang'): ?>
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
                                        </td>
                                        <td class="p-3"><?php echo e($bon->details->count()); ?> item</td>
                                        <td class="p-3">
                                            <div class="flex justify-center">
                                                <a href="<?php echo e(route('pegawai.bon.show', $bon->id)); ?>" 
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="mb-4">Belum ada data bon barang</p>
                        <a href="<?php echo e(route('pegawai.bon.create')); ?>" class="text-blue-600 hover:text-blue-800">
                            Buat bon pertama
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
function filterBon(status) {
    // Update tab styles
    document.querySelectorAll('.filter-tab').forEach(tab => {
        if (tab.dataset.filter === status) {
            tab.classList.remove('border-transparent', 'text-gray-500');
            tab.classList.add('border-blue-500', 'text-blue-600');
            // Update badge color
            const badge = tab.querySelector('span');
            badge.classList.remove('bg-gray-100', 'text-gray-600');
            badge.classList.add('bg-blue-100', 'text-blue-600');
        } else {
            tab.classList.remove('border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-500');
            // Update badge color
            const badge = tab.querySelector('span');
            badge.classList.remove('bg-blue-100', 'text-blue-600');
            badge.classList.add('bg-gray-100', 'text-gray-600');
        }
    });

    // Filter table rows
    const rows = document.querySelectorAll('.bon-row');
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.status === status ? '' : 'none';
        }
    });
}

// Initialize with 'all' filter
document.addEventListener('DOMContentLoaded', function() {
    filterBon('all');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/pegawai/bon/index.blade.php ENDPATH**/ ?>