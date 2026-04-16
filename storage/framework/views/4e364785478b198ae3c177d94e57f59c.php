<?php $__env->startSection('title', 'Detail Approval Bon Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="hidden md:flex">
        <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="hidden md:block">
            <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <?php echo $__env->make('components.mobile-navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center mb-6">
                    <a href="<?php echo e(route('atasan.bon.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Detail Approval Bon Barang</h1>
                </div>

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

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Bon</h3>
                            <p class="text-lg font-semibold"><?php echo e($bonBarang->kode_bon); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                <?php echo e($bonBarang->pegawai->role === 'atasan' ? 'Atasan' : 'Pegawai'); ?>

                            </h3>
                            <p class="text-lg"><?php echo e($bonBarang->pegawai->name); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</h3>
                            <p class="text-lg"><?php echo e($bonBarang->tanggal_pengajuan->format('F Y')); ?></p>
                        </div>
                        <?php if($bonBarang->keterangan): ?>
                            <div class="md:col-span-3">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Keterangan</h3>
                                <p class="text-lg"><?php echo e($bonBarang->keterangan); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Approval Form -->
                <?php if($bonBarang->status === 'menunggu_atasan'): ?>
                    <form action="<?php echo e(route('atasan.bon.add-item', $bonBarang->id)); ?>" method="POST" class="p-6 bg-white shadow rounded-lg overflow-hidden mb-6">
                        <?php echo csrf_field(); ?>
                        <div class="px-6 py-4 bg-green-50 border-b mb-4">
                            <h2 class="text-lg font-semibold">Tambah Barang</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                                <select name="barang_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Pilih Barang</option>
                                    <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($barang->id); ?>"><?php echo e($barang->nama_barang); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                <input type="number" name="jumlah" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0" required>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                    + Tambah Barang
                                </button>
                            </div>
                        </div>
                    </form>

                    <form action="<?php echo e(route('atasan.bon.approve', $bonBarang->id)); ?>" method="POST" id="approvalForm">
                        <?php echo csrf_field(); ?>
                        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                            <div class="px-6 py-4 bg-blue-50 border-b">
                                <h2 class="text-lg font-semibold">Form Approval</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-3 text-left">Barang</th>
                                            <th class="p-3 text-left">Jumlah Diminta</th>
                                            <th class="p-3 text-left">Jumlah Disetujui</th>
                                            <th class="p-3 text-left">Status</th>
                                            <th class="p-3 text-left">Catatan</th>
                                            <th class="p-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bonBarang->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="border-t">
                                                <td class="p-3">
                                                    <strong><?php echo e($detail->barang->nama_barang); ?></strong>
                                                    <br>
                                                    <span class="text-xs text-gray-500"><?php echo e($detail->barang->satuan); ?></span>
                                                </td>
                                                <td class="p-3"><?php echo e($detail->jumlah_diminta); ?> <?php echo e($detail->barang->satuan); ?></td>
                                                <td class="p-3">
                                                    <input type="hidden" name="detail_id[]" value="<?php echo e($detail->id); ?>">
                                                    <input type="number" name="jumlah_disetujui[]" value="<?php echo e($detail->jumlah_diminta); ?>" min="1" class="w-20 px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="updateStatus(this)">
                                                    <?php echo e($detail->barang->satuan); ?>

                                                </td>
                                                <td class="p-3">
                                                    <select name="status_detail[]" class="px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="updateJumlah(this)">
                                                        <option value="disetujui" <?php echo e($detail->status_detail === 'disetujui' ? 'selected' : ''); ?>>Disetujui</option>
                                                        <option value="sebagian" <?php echo e($detail->status_detail === 'sebagian' ? 'selected' : ''); ?>>Sebagian</option>
                                                        <option value="ditolak" <?php echo e($detail->status_detail === 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                                                    </select>
                                                </td>
                                                <td class="p-3">
                                                    <input type="text" name="catatan[]" class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan..." value="<?php echo e($detail->catatan ?? ''); ?>">
                                                </td>
                                                <td class="p-3 text-center">
                                                    <button type="button" onclick="confirmDelete(<?php echo e($detail->id); ?>)" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition text-sm">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-between gap-3">
                            <button type="button" onclick="showRejectModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Tolak Bon
                            </button>
                            
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Setujui & Kirim ke Gudang
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h2 class="text-lg font-semibold">Detail Barang</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-3 text-left">Barang</th>
                                        <th class="p-3 text-left">Jumlah Diminta</th>
                                        <th class="p-3 text-left">Jumlah Disetujui</th>
                                        <th class="p-3 text-left">Status Detail</th>
                                        <th class="p-3 text-left">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $bonBarang->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="border-t">
                                            <td class="p-3">
                                                <strong><?php echo e($detail->barang->nama_barang); ?></strong>
                                                <br>
                                                <span class="text-xs text-gray-500"><?php echo e($detail->barang->satuan); ?></span>
                                            </td>
                                            <td class="p-3"><?php echo e($detail->jumlah_diminta); ?> <?php echo e($detail->barang->satuan); ?></td>
                                            <td class="p-3">
                                                <?php echo $detail->jumlah_disetujui ? $detail->jumlah_disetujui . ' ' . $detail->barang->satuan : '<span class="text-gray-400">-</span>'; ?>

                                            </td>
                                            <td class="p-3">
                                                <?php switch($detail->status_detail):
                                                    case ('menunggu'): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                                                        <?php break; ?>
                                                    <?php case ('disetujui'): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                                        <?php break; ?>
                                                    <?php case ('ditolak'): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                                        <?php break; ?>
                                                    <?php case ('sebagian'): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Sebagian</span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            </td>
                                            <td class="p-3">
                                                <?php echo $detail->catatan ? '<span class="text-gray-600">' . $detail->catatan . '</span>' : '<span class="text-gray-400">-</span>'; ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($bonBarang->tanggal_atasan || $bonBarang->tanggal_gudang): ?>
                    <div class="bg-white shadow rounded-lg p-6">
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
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Alasan Penolakan</h3>
            <form id="rejectForm" method="POST" action="<?php echo e(route('atasan.bon.reject', $bonBarang->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="mt-4">
                    <textarea name="alasan_penolakan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan alasan penolakan..." required></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(select) {
    const row = select.closest('tr');
    const jumlahInput = row.querySelector('input[name="jumlah_disetujui[]"]');
    const statusSelect = row.querySelector('select[name="status_detail[]"]');
    const jumlahDiminta = parseInt(row.querySelector('td:nth-child(2)').textContent);
    const jumlahDisetujui = parseInt(jumlahInput.value);
    
    statusSelect.value = jumlahDisetujui === 0 ? 'ditolak' : jumlahDisetujui < jumlahDiminta ? 'sebagian' : 'disetujui';
}

function updateJumlah(select) {
    const row = select.closest('tr');
    const jumlahInput = row.querySelector('input[name="jumlah_disetujui[]"]');
    const jumlahDiminta = parseInt(row.querySelector('td:nth-child(2)').textContent);
    
    if (select.value === 'ditolak') {
        jumlahInput.value = 0;
    } else if (select.value === 'sebagian' && (!jumlahInput.value || jumlahInput.value > jumlahDiminta)) {
        jumlahInput.value = Math.floor(jumlahDiminta / 2);
    } else if (select.value === 'disetujui') {
        jumlahInput.value = jumlahDiminta;
    }
}

function confirmDelete(detailId) {
    if (confirm('Hapus barang ini dari bon?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("atasan.bon.remove-item", ":id")); ?>'.replace(':id', detailId);
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/atasan/bon/show.blade.php ENDPATH**/ ?>