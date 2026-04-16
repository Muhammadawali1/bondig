<?php $__env->startSection('title', 'Notifikasi'); ?>

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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
            <p class="mt-1 text-sm text-gray-600">Semua notifikasi Anda</p>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <?php if($notifikasis->count() > 0): ?>
                <ul class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $notifikasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notifikasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <?php if(!$notifikasi->dibaca): ?>
                                            <div class="w-2 h-2 rounded-full mt-2 bg-blue-600"></div>
                                        <?php else: ?>
                                            <div class="w-2 h-2 rounded-full mt-2 bg-gray-300"></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    <?php echo e($notifikasi->judul); ?>

                                                </h3>
                                                <p class="mt-1 text-sm text-gray-600">
                                                    <?php echo e($notifikasi->pesan); ?>

                                                </p>
                                                <div class="mt-2 flex items-center text-xs text-gray-400">
                                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <?php echo e($notifikasi->created_at->locale('id_ID')->diffForHumans()); ?>

                                                    
                                                    <?php if($notifikasi->tipe): ?>
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            <?php if($notifikasi->tipe === 'bon_masuk'): ?> bg-blue-100 text-blue-800
                                                            <?php elseif($notifikasi->tipe === 'bon_disetujui_atasan'): ?> bg-yellow-100 text-yellow-800
                                                            <?php elseif($notifikasi->tipe === 'bon_disetujui_gudang'): ?> bg-green-100 text-green-800
                                                            <?php elseif($notifikasi->tipe === 'bon_ditolak_atasan'): ?> bg-red-100 text-red-800
                                                            <?php elseif($notifikasi->tipe === 'bon_ditolak_gudang'): ?> bg-red-100 text-red-800
                                                            <?php else: ?> bg-gray-100 text-gray-800
                                                            <?php endif; ?>
                                                        ">
                                                            <?php echo e(App\Models\Notifikasi::getTipeNotifikasi()[$notifikasi->tipe] ?? $notifikasi->tipe); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <?php if($notifikasi->bon_barang_id): ?>
                                                    <?php if($notifikasi->tipe === 'bon_disetujui_gudang' && auth()->user()->isPegawai()): ?>
                                                        <!-- Don't show Lihat Bon button for pegawai after gudang approval -->
                                                    <?php elseif($notifikasi->tipe === 'bon_disetujui_gudang' && auth()->user()->isAtasan()): ?>
                                                        <!-- Don't show Lihat Bon button for atasan after gudang approval -->
                                                    <?php elseif(auth()->user()->isPegawai()): ?>
                                                        <a href="<?php echo e(route('pegawai.bon.show', $notifikasi->bon_barang_id)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Lihat Bon
                                                        </a>
                                                    <?php elseif(auth()->user()->isAtasan()): ?>
                                                        <a href="<?php echo e(route('atasan.bon.show', $notifikasi->bon_barang_id)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Lihat Bon
                                                        </a>
                                                    <?php elseif(auth()->user()->isGudang()): ?>
                                                        <a href="<?php echo e(route('gudang.bon.show', $notifikasi->bon_barang_id)); ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Lihat Bon
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                
                                                <?php if(!$notifikasi->dibaca): ?>
                                                    <form method="POST" action="<?php echo e(route('notifikasi.mark-read', $notifikasi->id)); ?>" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="ml-2 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                            Tandai Dibaca
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php echo e($notifikasis->links()); ?>

                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium"><?php echo e($notifikasis->firstItem()); ?></span> 
                                hingga <span class="font-medium"><?php echo e($notifikasis->lastItem()); ?></span> 
                                dari <span class="font-medium"><?php echo e($notifikasis->total()); ?></span> hasil
                            </p>
                        </div>
                        <div>
                            <?php echo e($notifikasis->links()); ?>

                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda belum memiliki notifikasi saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if(auth()->user()->unreadNotifications()->count() > 0): ?>
            <div class="mt-4">
                <form method="POST" action="<?php echo e(route('notifikasi.mark-all-read')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>
        <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/notifikasi/index.blade.php ENDPATH**/ ?>