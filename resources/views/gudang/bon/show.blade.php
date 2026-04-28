@extends('layouts.app')

@section('title', 'Final Approval Bon Barang')

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
                    <a href="{{ route('gudang.bon.index') }}" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Final Approval Bon Barang</h1>
                </div>

                <!-- Header Info -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Bon</h3>
                            <p class="text-lg font-semibold">{{ $bonBarang->kode_bon ?: 'Menunggu Persetujuan' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">
                                {{ $bonBarang->pegawai->role === 'atasan' ? 'Atasan' : 'Pegawai' }}
                            </h3>
                            <p class="text-lg">{{ $bonBarang->pegawai->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</h3>
                            <p class="text-lg">{{ $bonBarang->tanggal_pengajuan->format('F Y') }}</p>
                        </div>
                        @if($bonBarang->keterangan)
                            <div class="md:col-span-3">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Keperluan</h3>
                                <p class="text-lg">{{ $bonBarang->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Warning Alert -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Perhatian!</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Setelah menyetujui bon ini, stok barang akan otomatis berkurang. Pastikan jumlah yang disetujui sesuai dengan stok yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Approval Form -->
                <form action="{{ route('gudang.bon.approve', $bonBarang->id) }}" method="POST" id="finalApprovalForm">
                    @csrf
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-green-50 border-b">
                            <h2 class="text-lg font-semibold">Final Approval & Pengurangan Stok</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-3 text-left">Barang</th>
                                        <th class="p-3 text-left">Stok Saat Ini</th>
                                        <th class="p-3 text-left">Jumlah Diminta</th>
                                        <th class="p-3 text-left">Jumlah Disetujui Atasan</th>
                                        <th class="p-3 text-left">Jumlah Final</th>
                                        <th class="p-3 text-left">Status</th>
                                        <th class="p-3 text-left">Stok Setelah</th>
                                        <th class="p-3 text-left">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bonBarang->details as $detail)
                                        <tr class="border-t">
                                            <td class="p-3">
                                                <strong>{{ $detail->barang->nama_barang }}</strong>
                                                <br>
                                                <span class="text-xs text-gray-500">{{ $detail->barang->satuan }}</span>
                                            </td>
                                            <td class="p-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $detail->barang->stok <= 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $detail->barang->stok }} {{ $detail->barang->satuan }}
                                                </span>
                                            </td>
                                            <td class="p-3">{{ $detail->jumlah_diminta }} {{ $detail->barang->satuan }}</td>
                                            <td class="p-3">
                                                @if($detail->jumlah_disetujui)
                                                    {{ $detail->jumlah_disetujui }} {{ $detail->barang->satuan }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                <input type="number" 
                                                       name="jumlah_disetujui[]" 
                                                       value="{{ $detail->jumlah_disetujui ?? $detail->jumlah_diminta }}" 
                                                       min="0" 
                                                       max="{{ $detail->barang->stok }}"
                                                       class="w-20 px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       onchange="calculateStokSetelah(this)"
                                                       data-stok-awal="{{ $detail->barang->stok }}"
                                                       data-jumlah-diminta="{{ $detail->jumlah_diminta }}">
                                                {{ $detail->barang->satuan }}
                                            </td>
                                            <td class="p-3">
                                                <select name="status_detail[]" class="px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="updateFinalJumlah(this)">
                                                    <option value="disetujui" {{ ($detail->jumlah_disetujui ?? $detail->jumlah_diminta) > 0 ? 'selected' : '' }}>Disetujui</option>
                                                    <option value="sebagian" {{ ($detail->jumlah_disetujui ?? $detail->jumlah_diminta) < $detail->jumlah_diminta && ($detail->jumlah_disetujui ?? $detail->jumlah_diminta) > 0 ? 'selected' : '' }}>Sebagian</option>
                                                    <option value="ditolak">Ditolak</option>
                                                </select>
                                            </td>
                                            <td class="p-3">
                                                <span class="stok-setelah font-medium" data-stok-awal="{{ $detail->barang->stok }}">
                                                    {{ $detail->barang->stok - ($detail->jumlah_disetujui ?? $detail->jumlah_diminta) }} {{ $detail->barang->satuan }}
                                                </span>
                                            </td>
                                            <td class="p-3">
                                                <input type="text" 
                                                       name="keterangan[]"
                                                       value="{{ $detail->catatan ?? '' }}"
                                                       class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       placeholder="Keterangan...">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-between gap-3">
                        <!-- Tombol Tolak Bon -->
                        <button type="button" onclick="showRejectModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Tolak Bon
                        </button>
                        
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Setujui & Kurangi Stok
                        </button>
                    </div>

                    @if($bonBarang->tanggal_atasan || $bonBarang->tanggal_gudang)
                        <div class="bg-white shadow rounded-lg p-6">
                            <h2 class="text-lg font-semibold mb-4">Riwayat Persetujuan</h2>
                            <div class="space-y-4">
                                @if($bonBarang->tanggal_atasan)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 {{ $bonBarang->status === 'ditolak' ? 'bg-red-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 {{ $bonBarang->status === 'ditolak' ? 'text-red-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($bonBarang->status === 'ditolak')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $bonBarang->status === 'ditolak' ? 'Ditolak Atasan' : 'Disetujui Atasan' }}</p>
                                            <p class="text-sm text-gray-500">{{ $bonBarang->tanggal_atasan->format('F Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($bonBarang->tanggal_gudang)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium">Disetujui Gudang</p>
                                            <p class="text-sm text-gray-500">{{ $bonBarang->tanggal_gudang->format('F Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </main>

        @include('components.footer')

    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Alasan Penolakan</h3>
            <form id="rejectForm" method="POST" action="{{ route('gudang.bon.reject', $bonBarang->id) }}" onsubmit="return validateRejectForm()">
                @csrf
                <div class="mt-4">
                    <textarea name="alasan_penolakan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan alasan penolakan..." required></textarea>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Tolak Bon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Konfirmasi Penolakan</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menolak bon ini?</p>
            </div>
            <div class="mt-4 flex justify-center gap-3">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="button" onclick="confirmReject()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Ya, Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

function validateRejectForm() {
    const alasan = document.querySelector('textarea[name="alasan_penolakan"]').value;
    
    if (!alasan.trim()) {
        alert('Alasan penolakan harus diisi!');
        return false;
    }
    
    return true; // Allow form submission
}

function calculateStokSetelah(input) {
    const row = input.closest('tr');
    const stokAwal = parseInt(input.dataset.stokAwal);
    const jumlahFinal = parseInt(input.value) || 0;
    const jumlahDiminta = parseInt(input.dataset.jumlahDiminta);
    const stokSetelah = stokAwal - jumlahFinal;
    
    const stokSetelahSpan = row.querySelector('.stok-setelah');
    stokSetelahSpan.textContent = stokSetelah + ' ' + row.querySelector('td:nth-child(1) span').textContent.trim();
    
    // Update color based on stock level
    if (stokSetelah <= 10) {
        stokSetelahSpan.className = 'stok-setelah font-medium text-red-600';
    } else if (stokSetelah <= 20) {
        stokSetelahSpan.className = 'stok-setelah font-medium text-yellow-600';
    } else {
        stokSetelahSpan.className = 'stok-setelah font-medium text-green-600';
    }
    
    // Automatically update status based on jumlah_final
    const statusSelect = row.querySelector('select[name="status_detail[]"]');
    if (jumlahFinal === 0) {
        statusSelect.value = 'ditolak';
    } else if (jumlahFinal < jumlahDiminta) {
        statusSelect.value = 'sebagian';
    } else {
        statusSelect.value = 'disetujui';
    }
}

function updateFinalJumlah(select) {
    const row = select.closest('tr');
    const jumlahInput = row.querySelector('input[name="jumlah_disetujui[]"]');
    const jumlahDiminta = parseInt(row.querySelector('td:nth-child(3)').textContent);
    
    if (select.value === 'ditolak') {
        jumlahInput.value = 0;
    } else if (select.value === 'sebagian') {
        // Keep current value or set to half if not set
        if (!jumlahInput.value || jumlahInput.value > jumlahDiminta) {
            jumlahInput.value = Math.floor(jumlahDiminta / 2);
        }
    } else {
        jumlahInput.value = jumlahDiminta;
    }
    
    calculateStokSetelah(jumlahInput);
}

// Initialize calculations on page load
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[name="jumlah_disetujui[]"]');
    inputs.forEach(input => calculateStokSetelah(input));
});
</script>
@endsection
