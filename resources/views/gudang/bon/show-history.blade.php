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
                            @case('sebagian')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    ⚡ Sebagian Disetujui
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
                        @if(($bonBarang->status === 'disetujui' || $bonBarang->status === 'sebagian') && $bonBarang->tanggal_gudang)
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
                                <th class="p-3 text-center">Aksi</th>
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
                                        <button onclick="showEditModal({{ $detail->id }})" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="showAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Tambah Barang
                </button>
                <a href="{{ route('gudang.bon.history') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Kembali ke History
                </a>
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>

<!-- Edit Item Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Edit Barang</h3>
            <form id="editForm" method="POST" action="{{ route('gudang.bon.edit-detail', $bonBarang->id) }}">
                @csrf
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
            <form id="addForm" method="POST" action="{{ route('gudang.bon.add-detail', $bonBarang->id) }}">
                @csrf
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                    <select name="barang_id" id="addBarang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Pilih Barang</option>
                        @php
                            $existingBarangIds = $bonBarang->details->pluck('barang_id')->toArray();
                            $allBarangs = \App\Models\Barang::whereNotIn('id', $existingBarangIds)->get();
                        @endphp
                        @foreach($allBarangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} (Stok: {{ $barang->stok }})</option>
                        @endforeach
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
    const detail = {{ json_encode($bonBarang->details->keyBy('id')) }};
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
@endsection
