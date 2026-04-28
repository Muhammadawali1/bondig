@extends('layouts.app')

@section('title', 'Detail Bon Barang - Gudang')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
    <div class="hidden md:flex">
        @include('components.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar (desktop only) -->
        <div class="hidden md:block">
            @include('components.navbar')
        </div>

        <!-- Mobile Navbar -->
        @include('components.mobile-navbar')

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Bon Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Bon Barang</h1>
                        <p class="text-gray-600 mt-1">
                            Kode Bon: <span class="font-mono font-semibold">{{ $bonBarang->kode_bon }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        @switch($bonBarang->status)
                            @case('disetujui')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✅ Disetujui
                                </span>
                                @break
                            @case('disetujui_sebagian')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    ⚠️ Disetujui Sebagian
                                </span>
                                @break
                            @case('ditolak')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    ❌ Ditolak
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    📋 {{ $bonBarang->status }}
                                </span>
                        @endswitch
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Pemohon</p>
                        <p class="text-lg font-semibold">{{ $bonBarang->pegawai->name }}</p>
                      
                        <p class="text-sm text-gray-600">{{ $bonBarang->divisi }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-lg font-semibold">{{ $bonBarang->tanggal_pengajuan->format('F Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500">Proses Selesai</p>
                        @if(($bonBarang->status === 'disetujui' || $bonBarang->status === 'disetujui_sebagian') && $bonBarang->tanggal_gudang)
                            <p class="text-lg font-semibold text-green-600">{{ $bonBarang->tanggal_gudang->format('F Y') }}</p>
                        @elseif($bonBarang->status === 'ditolak' && $bonBarang->tanggal_atasan)
                            <p class="text-lg font-semibold text-red-600">{{ $bonBarang->tanggal_atasan->format('F Y') }}</p>
                        @elseif($bonBarang->status === 'menunggu_atasan' || $bonBarang->status === 'menunggu_gudang')
                            <p class="text-lg font-semibold text-yellow-600">Menunggu Persetujuan</p>
                            <p class="text-sm text-gray-600">-</p>
                        @else
                            <p class="text-lg font-semibold text-gray-400">-</p>
                            <p class="text-sm text-gray-400">-</p>
                        @endif
                    </div>
                </div>

                @if($bonBarang->keterangan)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Keterangan:</p>
                        <p class="text-blue-700 mt-1">{{ $bonBarang->keterangan }}</p>
                    </div>
                @endif
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
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bonBarang->details as $detail)
                                <tr class="border-t">
                                    <td class="p-3">
                                        <strong>{{ $detail->barang->nama_barang }}</strong>
                                        <br>
                                        <span class="text-gray-500 text-xs">{{ $detail->barang->kode_barang }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium">{{ $detail->barang->stok }}</span>
                                        <span class="text-gray-500 text-xs"> {{ $detail->barang->satuan }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium">{{ $detail->jumlah_diminta }}</span>
                                        <span class="text-gray-500 text-xs"> {{ $detail->barang->satuan }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium">{{ $detail->jumlah_disetujui }}</span>
                                        <span class="text-gray-500 text-xs"> {{ $detail->barang->satuan }}</span>
                                    </td>
                                    <td class="p-3">
                                        @switch($bonBarang->status === 'ditolak' ? 'ditolak' : $detail->status_detail)
                                            @case('disetujui')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Disetujui
                                                </span>
                                                @break
                                            @case('sebagian')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⚠️ Sebagian
                                                </span>
                                                @break
                                            @case('ditolak')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    ❌ Ditolak
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    📋 {{ $bonBarang->status === 'ditolak' ? 'ditolak' : $detail->status_detail }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="p-3">
                                        @if($detail->catatan)
                                            <span class="text-gray-700">{{ $detail->catatan }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <!-- Edit and Hapus buttons removed -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('gudang.bon.print', $bonBarang->id) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    🖨️ Print Bon
                </a>
                <!-- Tambah Barang button removed -->
                <a href="{{ route('gudang.bon.history') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Kembali ke History
                </a>
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Hapus Barang</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus barang ini?</p>
                <p class="text-xs text-gray-400 mt-2">Stok akan dikembalikan secara otomatis.</p>
            </div>
            <form id="deleteForm" method="POST" action="{{ route('gudang.bon.delete-detail', $bonBarang->id) }}">
                @csrf
                <input type="hidden" name="detail_id" id="deleteDetailId">
                <div class="mt-4 flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const detailId = this.getAttribute('data-detail-id');
            document.getElementById('deleteDetailId').value = detailId;
            document.getElementById('deleteModal').classList.remove('hidden');
        });
    });
});

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}
</script>
@endsection
