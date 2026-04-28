@extends('layouts.app')

@section('title', 'Input Harga Satuan - Print Faktur')

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
                    <a href="{{ route('gudang.bon-masuk.show', $bonMasuk->id) }}" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Input Harga Satuan - Print Faktur</h1>
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
                                <p>Silakan input harga satuan untuk setiap barang sebelum mencetak faktur.</p>
                                <p class="mt-1">Tanggal faktur otomatis diset sesuai tanggal masuk ({{ $bonMasuk->tanggal_masuk->format('F Y') }}).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header Info -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">ID Bon Masuk</h3>
                            <p class="text-lg font-semibold">{{ $bonMasuk->id }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Supplier</h3>
                            <p class="text-lg">{{ $bonMasuk->supplier ?: '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Masuk</h3>
                            <p class="text-lg">{{ $bonMasuk->tanggal_masuk ? $bonMasuk->tanggal_masuk->format('d/m/Y') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-blue-50 border-b">
                        <h2 class="text-lg font-semibold">Input Harga Satuan untuk Print Faktur</h2>
                    </div>
                    
                    <form method="POST" action="{{ route('gudang.bon-masuk.print.process', $bonMasuk->id) }}">
                        @csrf
                        
                        <!-- Tanggal Faktur Input -->
                        <div class="px-6 py-4 border-b">
                            <label for="tanggal_faktur" class="block text-gray-700 font-bold mb-2">
                                Tanggal Faktur (Hanya Tanggal)
                            </label>
                            <select id="tanggal_faktur" name="tanggal_faktur"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Tanggal --</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <p class="text-sm text-gray-500 mt-1">
                                Bulan dan tahun otomatis: {{ $bonMasuk->tanggal_masuk->format('F Y') }}
                            </p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-3 text-left">Barang</th>
                                        <th class="p-3 text-left">Jumlah Masuk</th>
                                        <th class="p-3 text-left">Satuan</th>
                                        <th class="p-3 text-left">Harga Satuan (Rp)</th>
                                        <th class="p-3 text-left">Total Harga (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bonMasuk->details as $detail)
                                        <tr class="border-t">
                                            <td class="p-3">
                                                <strong>{{ $detail->barang->nama_barang }}</strong>
                                            </td>
                                            <td class="p-3">
                                                <span class="font-medium">{{ $detail->jumlah_masuk }}</span>
                                            </td>
                                            <td class="p-3">
                                                <span class="text-gray-600">{{ $detail->barang->satuan }}</span>
                                            </td>
                                            <td class="p-3">
                                                <input type="number" 
                                                       name="harga_satuan[{{ $detail->id }}]" 
                                                       value="{{ $detail->harga_satuan ?? '' }}"
                                                       step="0.01"
                                                       min="0"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="0.00"
                                                       oninput="calculateTotal(this, {{ $detail->jumlah_masuk }})">
                                            </td>
                                            <td class="p-3">
                                                <span id="total-{{ $detail->id }}" class="font-semibold text-green-600">
                                                    {{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan * $detail->jumlah_masuk, 0, ',', '.') : '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
                            <a href="{{ route('gudang.bon-masuk.show', $bonMasuk->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Cetak Faktur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        @include('components.footer')

    </div>
</div>

<script>
function calculateTotal(input, jumlah) {
    // Remove dots from Indonesian format before parsing
    const hargaValue = input.value.replace(/\./g, '').replace(/,/g, '.');
    const harga = parseFloat(hargaValue) || 0;
    const total = harga * jumlah;
    const row = input.closest('tr');
    const totalCell = row.querySelector('td:last-child span');
    
    // Format with Indonesian number format (dot as thousand separator)
    if (total > 0) {
        const formatted = total.toLocaleString('id-ID');
        totalCell.textContent = 'Rp ' + formatted;
    } else {
        totalCell.textContent = '-';
    }
}

// Add form submission handler to prevent issues
document.addEventListener('DOMContentLoaded', function() {
    console.log('Form handler loaded');
    const form = document.querySelector('form[method="POST"]');
    console.log('Form found:', form);
    
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit triggered');
            alert('Form submit triggered - check console for details');
            
            // Validate all harga inputs
            const hargaInputs = form.querySelectorAll('input[name^="harga_satuan"]');
            let isValid = true;
            
            hargaInputs.forEach(input => {
                const value = input.value.trim();
                console.log('Input value:', value);
                if (value && isNaN(parseFloat(value.replace(/\./g, '').replace(/,/g, '.')))) {
                    isValid = false;
                    input.classList.add('border-red-500');
                    console.log('Invalid input detected:', value);
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            console.log('Form validation result:', isValid);
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon periksa kembali input harga satuan. Pastikan format angka benar.');
                return false;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
                console.log('Submit button disabled');
            }
        });
    }
    
    // Add input formatting
    const hargaInputs = document.querySelectorAll('input[name^="harga_satuan"]');
    hargaInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = this.value.replace(/\./g, '');
            if (value && !isNaN(value)) {
                const numValue = parseFloat(value);
                if (numValue > 0) {
                    this.value = numValue.toLocaleString('id-ID');
                }
            }
        });
    });
});
</script>
@endsection
