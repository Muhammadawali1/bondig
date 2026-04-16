@extends('layouts.app')

@section('title', 'Detail Bon Barang')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
    <div class="hidden md:flex">
        @include('components.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden main-content-with-sticky-sidebar main-content-with-sticky-navbar">

        <!-- Navbar (desktop only) -->
        <div class="hidden md:block">
            @include('components.navbar')
        </div>

        <!-- Mobile Navbar -->
        @include('components.mobile-navbar')

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex items-center mb-6">
                    <a href="{{ route('atasan.bon.my') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Detail Bon Barang</h1>
                </div>

                <!-- Bon Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Bon</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kode Bon:</span>
                                    <span class="font-mono font-semibold">{{ $bonBarang->kode_bon ?: 'Menunggu Persetujuan' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Pengajuan:</span>
                                    <span>{{ $bonBarang->tanggal_pengajuan->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Divisi:</span>
                                    <span>{{ $bonBarang->divisi }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    @switch($bonBarang->status)
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
                                        @case('ditolak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Ditolak
                                            </span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Pemohon</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span>{{ $bonBarang->pegawai->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">NIP:</span>
                                    <span>{{ $bonBarang->pegawai->nip }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Divisi:</span>
                                    <span>{{ $bonBarang->pegawai->divisi }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($bonBarang->keterangan)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">Keterangan</h3>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $bonBarang->keterangan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Bon Details -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold">Daftar Barang</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">Nama Barang</th>
                                    <th class="p-3 text-left">Jumlah Diminta</th>
                                    <th class="p-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bonBarang->details as $detail)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="p-3">{{ $detail->barang->nama_barang }}</td>
                                        <td class="p-3">{{ $detail->jumlah_diminta }}</td>
                                        <td class="p-3">
                                            @switch($detail->status_detail)
                                                @case('menunggu')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Menunggu
                                                    </span>
                                                    @break
                                                @case('disetujui')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Disetujui
                                                    </span>
                                                    @break
                                                @case('ditolak')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ❌ Ditolak
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('atasan.bon.my') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Kembali
                    </a>
                    @if($bonBarang->status === 'menunggu_gudang')
                        
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
