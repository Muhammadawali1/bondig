@extends('layouts.app')

@section('title', 'Detail Bon Barang')

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
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center mb-6">
                    <a href="{{ route('pegawai.bon.index') }}" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Detail Bon Barang</h1>
                </div>

                <!-- Header Info -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Bon</h3>
                            <p class="text-lg font-semibold">{{ $bonBarang->kode_bon ?: 'Menunggu Persetujuan' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</h3>
                            <p class="text-lg">{{ $bonBarang->tanggal_pengajuan->format('F Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                            @switch($bonBarang->status)
                                @case('menunggu_atasan')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        ⏳ Menunggu Persetujuan Atasan
                                    </span>
                                    @break
                                @case('menunggu_gudang')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        ⏳ Menunggu Persetujuan Gudang
                                    </span>
                                    @break
                                @case('disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✅ Disetujui
                                    </span>
                                    @break
                                @case('ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        ❌ Ditolak
                                    </span>
                                    @break
                            @endswitch
                        </div>
                        @if($bonBarang->keterangan)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Keperluan</h3>
                                <p class="text-lg">{{ $bonBarang->keterangan }}</p>
                            </div>
                        @endif
                        @if($bonBarang->status === 'ditolak' && $bonBarang->alasan_penolakan)
                            <div>
                                <h3 class="text-sm font-medium text-red-600 mb-1">Alasan Penolakan</h3>
                                <p class="text-lg text-red-700">{{ $bonBarang->alasan_penolakan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h2 class="text-lg font-semibold">Daftar Barang</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3 text-left">No</th>
                                    <th class="p-3 text-left">Nama Barang</th>
                                    <th class="p-3 text-left">Jumlah Diminta</th>
                                    <th class="p-3 text-left">Jumlah Disetujui</th>
                                    <th class="p-3 text-left">Status Detail</th>
                                    <th class="p-3 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bonBarang->details as $index => $detail)
                                    <tr class="border-t">
                                        <td class="p-3">{{ $index + 1 }}</td>
                                        <td class="p-3 font-medium">{{ $detail->barang->nama_barang }}</td>
                                        <td class="p-3">{{ $detail->jumlah_diminta }} {{ $detail->barang->satuan }}</td>
                                        <td class="p-3">
                                            @if($detail->jumlah_disetujui)
                                                {{ $detail->jumlah_disetujui }} {{ $detail->barang->satuan }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @switch($detail->status_detail)
                                                @case('menunggu')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                    @break
                                                @case('disetujui')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Disetujui
                                                    </span>
                                                    @break
                                                @case('ditolak')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                    @break
                                                @case('sebagian')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Sebagian
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="p-3">
                                            @if($detail->catatan)
                                                <span class="text-gray-600">{{ $detail->catatan }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Timeline -->
                @if($bonBarang->tanggal_atasan || $bonBarang->tanggal_gudang)
                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h2 class="text-lg font-semibold mb-4">Riwayat Persetujuan</h2>
                        <div class="space-y-4">
                            @if($bonBarang->tanggal_atasan)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 {{ $bonBarang->status === 'ditolak' && !$bonBarang->tanggal_gudang ? 'bg-red-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 {{ $bonBarang->status === 'ditolak' && !$bonBarang->tanggal_gudang ? 'text-red-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($bonBarang->status === 'ditolak' && !$bonBarang->tanggal_gudang)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $bonBarang->status === 'ditolak' && !$bonBarang->tanggal_gudang ? 'Ditolak Atasan' : 'Disetujui Atasan' }}</p>
                                        <p class="text-sm text-gray-500">{{ $bonBarang->tanggal_atasan->format('F Y') }}</p>
                                        @if($bonBarang->status === 'ditolak' && !$bonBarang->tanggal_gudang && $bonBarang->alasan_penolakan)
                                            <p class="text-sm text-red-600 mt-1">Alasan: {{ $bonBarang->alasan_penolakan }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            @if($bonBarang->tanggal_gudang)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 {{ $bonBarang->status === 'ditolak' ? 'bg-red-100' : 'bg-green-100' }} rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 {{ $bonBarang->status === 'ditolak' ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($bonBarang->status === 'ditolak')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $bonBarang->status === 'ditolak' ? 'Ditolak Gudang' : 'Disetujui Gudang' }}</p>
                                        <p class="text-sm text-gray-500">{{ $bonBarang->tanggal_gudang->format('F Y') }}</p>
                                        @if($bonBarang->status === 'ditolak' && $bonBarang->alasan_penolakan)
                                            <p class="text-sm text-red-600 mt-1">Alasan: {{ $bonBarang->alasan_penolakan }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>
@endsection
