@extends('layouts.app')

@section('title', 'Tambah Bon Masuk - Gudang')

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
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center mb-6">
                    <a href="{{ route('gudang.bon-masuk.index') }}" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Tambah Bon Masuk</h1>
                </div>

                <!-- Info Alert -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Informasi</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Stok barang akan otomatis bertambah setelah bon masuk disimpan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('gudang.bon-masuk.store') }}" method="POST" id="bonMasukForm">
                    @csrf

                    <!-- Header Info -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                                <input type="text" 
                                       name="supplier" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nama supplier/Nama Toko">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Masuk</label>
                                <input type="date" 
                                       name="tanggal_masuk" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Barang List -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b flex justify-between items-center">
                            <h2 class="text-lg font-semibold">Daftar Barang Masuk</h2>
                            <button type="button" onclick="addBarangRow()" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Barang
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-3 text-left">Barang</th>
                                        <th class="p-3 text-left">Stok Saat Ini</th>
                                        <th class="p-3 text-left">Jumlah Masuk</th>
                                        <th class="p-3 text-left">Stok Setelah</th>
                                        <th class="p-3 text-left">Keterangan</th>
                                        <th class="p-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="barangTableBody">
                                    <!-- Initial row -->
                                    <tr class="border-t">
                                        <td class="p-3">
                                            <select name="barang_id[]" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="updateStokInfo(this)" required>
                                                <option value="">Pilih Barang</option>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" data-satuan="{{ $barang->satuan }}">
                                                        {{ $barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-3">
                                            <span class="stok-saat-ini text-gray-400">-</span>
                                        </td>
                                        <td class="p-3">
                                            <input type="number" 
                                                   name="jumlah_masuk[]" 
                                                   min="1" 
                                                   class="w-24 px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   onchange="calculateStokSetelah(this)"
                                                   oninput="calculateStokSetelah(this)"
                                                   required>
                                            <span class="satuan text-gray-500"></span>
                                        </td>
                                        <td class="p-3">
                                            <span class="stok-setelah font-medium text-green-600">-</span>
                                        </td>
                                        <td class="p-3">
                                            <input type="text" 
                                                   name="catatan[]" 
                                                   class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Keterangan...">
                                        </td>
                                        <td class="p-3">
                                            <button type="button" onclick="removeBarangRow(this)" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('gudang.bon-masuk.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Simpan & Tambah Stok
                        </button>
                    </div>
                </form>
            </div>
        </main>

        @include('components.footer')

    </div>
</div>

<script>
function addBarangRow() {
    const tbody = document.getElementById('barangTableBody');
    const newRow = document.createElement('tr');
    newRow.className = 'border-t';
    newRow.innerHTML = `
        <td class="p-3">
            <select name="barang_id[]" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="updateStokInfo(this)" required>
                <option value="">Pilih Barang</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}" data-satuan="{{ $barang->satuan }}">
                        {{ $barang->nama_barang }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="p-3">
            <span class="stok-saat-ini text-gray-400">-</span>
        </td>
        <td class="p-3">
            <input type="number" 
                   name="jumlah_masuk[]" 
                   min="1" 
                   class="w-24 px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   onchange="calculateStokSetelah(this)"
                   oninput="calculateStokSetelah(this)"
                   required>
            <span class="satuan text-gray-500"></span>
        </td>
        <td class="p-3">
            <span class="stok-setelah font-medium text-green-600">-</span>
        </td>
        <td class="p-3">
            <input type="text" 
                   name="catatan[]" 
                   class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Keterangan...">
        </td>
        <td class="p-3">
            <button type="button" onclick="removeBarangRow(this)" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
}

function removeBarangRow(button) {
    const tbody = document.getElementById('barangTableBody');
    if (tbody.children.length > 1) {
        button.closest('tr').remove();
    } else {
        alert('Minimal harus ada satu barang');
    }
}

function updateStokInfo(select) {
    const row = select.closest('tr');
    const selectedOption = select.options[select.selectedIndex];
    const stokSaatIni = row.querySelector('.stok-saat-ini');
    const satuan = row.querySelector('.satuan');
    
    if (selectedOption.value) {
        const stok = selectedOption.dataset.stok;
        const unit = selectedOption.dataset.satuan;
        stokSaatIni.textContent = stok + ' ' + unit;
        satuan.textContent = unit;
        calculateStokSetelah(row.querySelector('input[name="jumlah_masuk[]"]'));
    } else {
        stokSaatIni.textContent = '-';
        satuan.textContent = '';
        row.querySelector('.stok-setelah').textContent = '-';
    }
}

function calculateStokSetelah(input) {
    const row = input.closest('tr');
    const select = row.querySelector('select[name="barang_id[]"]');
    const selectedOption = select.options[select.selectedIndex];
    const stokSetelah = row.querySelector('.stok-setelah');
    
    if (selectedOption.value) {
        const stokAwal = parseInt(selectedOption.dataset.stok);
        const jumlahMasuk = parseInt(input.value) || 0;
        const stokFinal = stokAwal + jumlahMasuk;
        const unit = selectedOption.dataset.satuan;
        
        stokSetelah.textContent = stokFinal + ' ' + unit;
    }
}

// Initialize first row
document.addEventListener('DOMContentLoaded', function() {
    const firstSelect = document.querySelector('select[name="barang_id[]"]');
    if (firstSelect) {
        // Auto-select first barang if available
        if (firstSelect.options.length > 1) {
            firstSelect.selectedIndex = 1;
            updateStokInfo(firstSelect);
        }
    }
});
</script>
@endsection
