<?php $__env->startSection('title', 'Buat Bon Barang'); ?>

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
                    <h1 class="text-2xl font-bold">Buat Bon Barang Baru</h1>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <form action="<?php echo e(route('pegawai.bon.store')); ?>" method="POST" id="bonForm">
                        <?php echo csrf_field(); ?>

                        <!-- Keterangan -->
                        <div class="mb-6">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" 
                                      name="keterangan" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Masukkan keterangan pengajuan barang"><?php echo e(old('keterangan')); ?></textarea>
                            <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Dynamic Item List -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Daftar Barang <span class="text-red-500">*</span>
                                </label>
                                <button type="button" onclick="addItem()" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition text-sm">
                                    + Tambah Barang
                                </button>
                            </div>

                            <div id="itemList" class="space-y-3">
                                <!-- Initial Item -->
                                <div class="item-row border rounded-lg p-4 bg-gray-50">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                                            <select name="barang_id[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                                <option value="">Pilih Barang</option>
                                                <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($barang->id); ?>" <?php echo e($selectedBarang && $selectedBarang->id == $barang->id ? 'selected' : ''); ?>>
                                                        <?php echo e($barang->nama_barang); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                            <input type="number" name="jumlah[]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0" required>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" onclick="removeItem(this)" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $__errorArgs = ['barang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3">
                            <a href="<?php echo e(route('pegawai.bon.index')); ?>" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ajukan Bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>

<script>
let itemCount = 1;

function addItem() {
    itemCount++;
    const itemList = document.getElementById('itemList');
    
    const newItem = document.createElement('div');
    newItem.className = 'item-row border rounded-lg p-4 bg-gray-50';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                <select name="barang_id[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Pilih Barang</option>
                    <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($barang->id); ?>">
                            <?php echo e($barang->nama_barang); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah[]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0" required>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeItem(this)" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition">
                    Hapus
                </button>
            </div>
        </div>
        <div class="mt-2 text-sm text-gray-600 stok-info"></div>
    `;
    
    itemList.appendChild(newItem);
}

function removeItem(button) {
    const itemRows = document.querySelectorAll('.item-row');
    if (itemRows.length > 1) {
        button.closest('.item-row').remove();
    } else {
        alert('Minimal harus ada satu barang!');
    }
}

// Auto-fill selected barang when page loads
document.addEventListener('DOMContentLoaded', function() {
    <?php if($selectedBarang): ?>
        const firstSelect = document.querySelector('select[name="barang_id[]"]');
        if (firstSelect) {
            firstSelect.value = '<?php echo e($selectedBarang->id); ?>';
            // Auto-focus on jumlah input
            const firstJumlahInput = document.querySelector('input[name="jumlah[]"]');
            if (firstJumlahInput) {
                firstJumlahInput.focus();
            }
        }
    <?php endif; ?>
});

// Form validation
document.getElementById('bonForm').addEventListener('submit', function(e) {
    const barangSelects = document.querySelectorAll('select[name="barang_id[]"]');
    const jumlahInputs = document.querySelectorAll('input[name="jumlah[]"]');
    
    let hasValidItem = false;
    
    for (let i = 0; i < barangSelects.length; i++) {
        if (barangSelects[i].value && jumlahInputs[i].value > 0) {
            hasValidItem = true;
            break;
        }
    }
    
    if (!hasValidItem) {
        e.preventDefault();
        alert('Minimal harus ada satu barang yang dipilih dengan jumlah yang valid!');
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/pegawai/bon/create.blade.php ENDPATH**/ ?>