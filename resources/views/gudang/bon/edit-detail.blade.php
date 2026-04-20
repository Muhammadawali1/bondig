@extends('layouts.app')

@section('title', 'Edit Detail Barang - Gudang')

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

            <!-- Edit Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Detail Barang</h1>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-gray-500">Barang</p>
                    <p class="text-lg font-semibold">{{ $detail->barang->nama_barang }}</p>
                    <p class="text-sm text-gray-600">{{ $detail->barang->kode_barang }}</p>
                </div>

                <form method="POST" action="{{ route('gudang.bon.edit-detail', $bonBarang->id) }}">
                    @csrf
                    <input type="hidden" name="detail_id" value="{{ $detail->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Disetujui</label>
                            <input type="number" name="jumlah_disetujui" value="{{ $detail->jumlah_disetujui }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Detail</label>
                            <select name="status_detail" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="disetujui" {{ $detail->status_detail === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="sebagian" {{ $detail->status_detail === 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                <option value="ditolak" {{ $detail->status_detail === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $detail->catatan ?? '' }}</textarea>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('gudang.bon.show-history', $bonBarang->id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>
@endsection
