<?php $__env->startSection('title', 'Detail Bon Barang - Gudang'); ?>

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

            <!-- Bon Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Bon Barang</h1>
                        <p class="text-gray-600 mt-1">
                            Kode Bon: <span class="font-mono font-semibold"><?php echo e($bonBarang->kode_bon); ?></span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php switch($bonBarang->status):
                            case ('disetujui'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✅ Disetujui
                                </span>
                                <?php break; ?>
                            <?php case ('ditolak'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ❌ Ditolak
                                </span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    📋 <?php echo e($bonBarang->status); ?>

                                </span>
                        <?php endswitch; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Pemohon</p>
                        <p class="text-lg font-semibold"><?php echo e($bonBarang->pegawai->name); ?></p>
                      
                        <p class="text-sm text-gray-600"><?php echo e($bonBarang->divisi); ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-lg font-semibold"><?php echo e($bonBarang->tanggal_pengajuan->format('F Y')); ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Proses Selesai</p>
                        <?php if($bonBarang->status === 'disetujui' && $bonBarang->tanggal_gudang): ?>
                            <p class="text-lg font-semibold text-green-600"><?php echo e($bonBarang->tanggal_gudang->format('F Y')); ?></p>
                        <?php elseif($bonBarang->status === 'ditolak' && $bonBarang->tanggal_atasan): ?>
                            <p class="text-lg font-semibold text-red-600"><?php echo e($bonBarang->tanggal_atasan->format('F Y')); ?></p>
                        <?php elseif($bonBarang->status === 'menunggu_atasan' || $bonBarang->status === 'menunggu_gudang'): ?>
                            <p class="text-lg font-semibold text-yellow-600">Menunggu Persetujuan</p>
                            <p class="text-sm text-gray-600">-</p>
                        <?php else: ?>
                            <p class="text-lg font-semibold text-gray-400">-</p>
                            <p class="text-sm text-gray-400">-</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($bonBarang->keterangan): ?>
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Keterangan:</p>
                        <p class="text-blue-700 mt-1"><?php echo e($bonBarang->keterangan); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Bon Details -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Detail Barang</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Barang</th>
                                <th class="p-3 text-left">Stok Saat Ini</th>
                                <th class="p-3 text-left">Jumlah Diminta</th>
                                <th class="p-3 text-left">Jumlah Disetujui</th>
                                <th class="p-3 text-left">Status Detail</th>
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
                                        <span class="text-gray-500 text-xs"><?php echo e($detail->barang->kode_barang); ?></span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium"><?php echo e($detail->barang->stok); ?></span>
                                        <span class="text-gray-500 text-xs"> <?php echo e($detail->barang->satuan); ?></span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium"><?php echo e($detail->jumlah_diminta); ?></span>
                                        <span class="text-gray-500 text-xs"> <?php echo e($detail->barang->satuan); ?></span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium"><?php echo e($detail->jumlah_disetujui); ?></span>
                                        <span class="text-gray-500 text-xs"> <?php echo e($detail->barang->satuan); ?></span>
                                    </td>
                                    <td class="p-3">
                                        <?php switch($bonBarang->status === 'ditolak' ? 'ditolak' : $detail->status_detail):
                                            case ('disetujui'): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Disetujui
                                                </span>
                                                <?php break; ?>
                                            <?php case ('sebagian'): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⚠️ Sebagian
                                                </span>
                                                <?php break; ?>
                                            <?php case ('ditolak'): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    ❌ Ditolak
                                                </span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    📋 <?php echo e($bonBarang->status === 'ditolak' ? 'ditolak' : $detail->status_detail); ?>

                                                </span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td class="p-3">
                                        <?php if($detail->catatan): ?>
                                            <span class="text-gray-700"><?php echo e($detail->catatan); ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-center">
                                        <button onclick="showEditModal(<?php echo e($detail->id); ?>)" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="showAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Tambah Barang
                </button>
                <a href="<?php echo e(route('gudang.bon.history')); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Kembali ke History
                </a>
            </div>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</div>

<!-- Edit Item Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Edit Barang</h3>
            <form id="editForm" method="POST" action="<?php echo e(route('gudang.bon.edit-detail', $bonBarang->id)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="detail_id" id="editDetailId">
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Disetujui</label>
                    <input type="number" name="jumlah_disetujui" id="editJumlah" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Detail</label>
                    <select name="status_detail" id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="disetujui">Disetujui</option>
                        <option value="sebagian">Sebagian</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" id="editCatatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Tambah Barang</h3>
            <form id="addForm" method="POST" action="<?php echo e(route('gudang.bon.add-detail', $bonBarang->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                    <select name="barang_id" id="addBarang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Pilih Barang</option>
                        <?php
                            $existingBarangIds = $bonBarang->details->pluck('barang_id')->toArray();
                            $allBarangs = \App\Models\Barang::whereNotIn('id', $existingBarangIds)->get();
                        ?>
                        <?php $__currentLoopData = $allBarangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($barang->id); ?>"><?php echo e($barang->nama_barang); ?> (Stok: <?php echo e($barang->stok); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="jumlah" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showEditModal(detailId) {
    const detail = <?php echo e(json_encode($bonBarang->details->keyBy('id'))); ?>;
    const selectedDetail = detail[detailId];

    document.getElementById('editDetailId').value = detailId;
    document.getElementById('editJumlah').value = selectedDetail.jumlah_disetujui;
    document.getElementById('editStatus').value = selectedDetail.status_detail;
    document.getElementById('editCatatan').value = selectedDetail.catatan || '';

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}

function showAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addForm').reset();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/bon/show-history.blade.php ENDPATH**/ ?>