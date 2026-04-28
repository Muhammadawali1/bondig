@extends('layouts.app')

@section('title', 'Detail Bon Masuk - Gudang')

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
                    <h1 class="text-2xl font-bold">Detail Bon Masuk</h1>
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
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Petugas Gudang</h3>
                            <p class="text-lg">{{ $bonMasuk->gudang->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                            @if($bonMasuk->status === 'selesai')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏳ Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Success Alert -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Stok Telah Ditambahkan</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Semua barang pada bon masuk ini telah otomatis ditambahkan ke stok.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Details -->
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-blue-50 border-b">
                        <h2 class="text-lg font-semibold">Detail Barang Masuk</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Barang</th>
                                    <th class="p-3 text-left">Stok Sebelum</th>
                                    <th class="p-3 text-left">Jumlah Masuk</th>
                                    <th class="p-3 text-left">Stok Sesudah</th>
                                    <th class="p-3 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bonMasuk->details as $detail)
                                    <tr class="border-t">
                                        <td class="p-3">
                                            <strong>{{ $detail->barang->nama_barang }}</strong>
                                            <br>
                                            <span class="text-xs text-gray-500">{{ $detail->barang->satuan }}</span>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $detail->barang->stok - $detail->jumlah_masuk }} {{ $detail->barang->satuan }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                +{{ $detail->jumlah_masuk }} {{ $detail->barang->satuan }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $detail->barang->stok }} {{ $detail->barang->satuan }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            {{ $detail->catatan ?: '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total Jenis Barang</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $bonMasuk->details->count() }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total Barang Masuk</p>
                            <p class="text-3xl font-bold text-green-600">{{ $bonMasuk->details->sum('jumlah_masuk') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total Stok Bertambah</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $bonMasuk->details->sum('jumlah_masuk') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('gudang.bon-masuk.print', $bonMasuk->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        🖨️ Print Faktur
                    </a>
                    <a href="{{ route('gudang.bon-masuk.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </main>

        @include('components.footer')

    </div>
</div>

@endsection
