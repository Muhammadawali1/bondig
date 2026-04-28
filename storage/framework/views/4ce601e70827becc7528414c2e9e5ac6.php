<?php $__env->startSection('title', 'Input Tanggal Cetak - Print Bon'); ?>

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
                    <a href="<?php echo e(route('gudang.bon.show-history', $bonBarang->id)); ?>" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Input Tanggal Cetak - Print Bon</h1>
                </div>

                <!-- Info Banner -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Silakan input tanggal cetak sebelum mencetak bon.</p>
                                <p class="mt-1">Tanggal cetak otomatis diset sesuai tanggal pengajuan (<?php echo e($bonBarang->tanggal_pengajuan->format('F Y')); ?>).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header Info -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Bon</h3>
                            <p class="text-lg font-semibold"><?php echo e($bonBarang->kode_bon); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Divisi</h3>
                            <p class="text-lg"><?php echo e($bonBarang->divisi); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</h3>
                            <p class="text-lg"><?php echo e($bonBarang->tanggal_pengajuan ? $bonBarang->tanggal_pengajuan->format('d/m/Y') : '-'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-blue-50 border-b">
                        <h2 class="text-lg font-semibold">Input Tanggal Cetak untuk Print Bon</h2>
                    </div>
                    
                    <form method="POST" action="<?php echo e(route('gudang.bon.print.process', $bonBarang->id)); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Tanggal Cetak Input -->
                        <div class="px-6 py-4 border-b">
                            <label for="tanggal_cetak" class="block text-gray-700 font-bold mb-2">
                                Tanggal Cetak (Hanya Tanggal)
                            </label>
                            <select id="tanggal_cetak" name="tanggal_cetak"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Tanggal --</option>
                                <?php for($i = 1; $i <= 31; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e($bonBarang->tanggal_pengajuan->day == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">
                                Bulan dan tahun otomatis: <?php echo e($bonBarang->tanggal_pengajuan->format('F Y')); ?>

                            </p>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
                            <a href="<?php echo e(route('gudang.bon.show-history', $bonBarang->id)); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Cetak Bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>

<script>
// Add form submission handler to prevent issues
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/print/print-date-form.blade.php ENDPATH**/ ?>