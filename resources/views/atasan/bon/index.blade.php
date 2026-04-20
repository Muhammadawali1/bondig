@extends('layouts.app')

@section('title', 'Bon Barang')

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

            <h1 class="text-2xl font-bold mb-6">📋 Approval Bon Barang</h1>

            <!-- Filter Tabs -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="border-b">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="filterBon('menunggu_atasan')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" data-filter="menunggu_atasan">
                            Menunggu Approval
                            <span class="bg-blue-100 text-blue-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                {{ $bonBarangs->where('status', 'menunggu_atasan')->count() }}
                            </span>
                        </button>
                        <button onclick="filterBon('menunggu_gudang')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="menunggu_gudang">
                            Menunggu Gudang
                            <span class="bg-gray-100 text-gray-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                {{ $bonBarangs->where('status', 'menunggu_gudang')->count() }}
                            </span>
                        </button>
                        <button onclick="filterBon('ditolak')" class="filter-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="ditolak">
                            Ditolak
                            <span class="bg-gray-100 text-gray-600 ml-2 py-0.5 px-2.5 rounded-full text-xs">
                                {{ $bonBarangs->where('status', 'ditolak')->count() }}
                            </span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Bon List -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($bonBarangs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Kode Bon</th>
                                    <th class="p-3 text-left">Pegawai</th>
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Jumlah Item</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bonTableBody">
                                @foreach($bonBarangs as $bon)
                                    <tr class="border-t hover:bg-gray-50 bon-row" data-status="{{ $bon->status }}">
                                        <td class="p-3 font-mono text-sm">{{ $bon->kode_bon ?: '-' }}</td>
                                        <td class="p-3">{{ $bon->pegawai->name }}</td>
                                        <td class="p-3">{{ $bon->tanggal_pengajuan->format('d/m/Y') }}</td>
                                        <td class="p-3">
                                            @switch($bon->status)
                                                @case('menunggu_atasan')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Menunggu Approval
                                                    </span>
                                                    @break
                                                @case('menunggu_gudang')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        ⏳ Menunggu Gudang
                                                    </span>
                                                    @break
                                                @case('disetujui')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Disetujui
                                                    </span>
                                                    @break
                                                @case('sebagian_disetujui')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⚡ Sebagian Disetujui
                                                    </span>
                                                    @break
                                                @case('ditolak')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ❌ Ditolak
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="p-3">{{ $bon->details->count() }} item</td>
                                        <td class="p-3">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('atasan.bon.show', $bon->id) }}" 
                                                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-sm">
                                                    Detail
                                                </a>
                                                @if($bon->status === 'menunggu_atasan')
                                                    <form action="{{ route('atasan.bon.reject', $bon->id) }}" method="POST" class="inline" onsubmit="return confirmReject(this, '{{ $bon->kode_bon }}')">
                                                        @csrf
                                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-sm">
                                                            Tolak
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <div class="mb-4">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p>Belum ada data bon barang</p>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Alasan Penolakan</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
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
let currentRejectUrl = '';

function filterBon(status) {
    const rows = document.querySelectorAll('.bon-row');
    const tabs = document.querySelectorAll('.filter-tab');
    
    // Update tab styles
    tabs.forEach(tab => {
        if (tab.dataset.filter === status) {
            tab.className = 'filter-tab py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600';
        } else {
            tab.className = 'filter-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
        }
    });
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmReject(form, kodeBon) {
    const modal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');
    
    currentRejectUrl = form.action;
    rejectForm.action = currentRejectUrl;
    
    modal.classList.remove('hidden');
    return false;
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Show only waiting approval by default
document.addEventListener('DOMContentLoaded', function() {
    filterBon('menunggu_atasan');
});
</script>
@endsection
