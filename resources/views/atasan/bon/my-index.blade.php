@extends('layouts.app')

@section('title', 'My Bon')

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
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">📋 My Bon</h1>
                    <span class="ml-3 text-sm text-gray-500">Bon barang yang saya ajukan</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('atasan.bon.my.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Bon Baru
                    </a>
                    <a href="{{ route('atasan.bon.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                        Bon Barang
                    </a>
                    <a href="{{ route('atasan.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                        Dashboard
                    </a>
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
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Jumlah Item</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bonBarangs as $bon)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="p-3 font-mono text-sm">{{ $bon->kode_bon ?: '-' }}</td>
                                        <td class="p-3">{{ $bon->tanggal_pengajuan->format('d/m/Y') }}</td>
                                        <td class="p-3">
                                            @switch($bon->status)
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
                                        </td>
                                        <td class="p-3">{{ $bon->details->count() }} item</td>
                                        <td class="p-3">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('atasan.bon.my.show', $bon->id) }}" 
                                                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-sm">
                                                    Detail
                                                </a>
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
                        <p class="text-lg font-medium mb-2">Belum ada bon barang</p>
                        <p class="text-sm mb-4">Anda belum mengajukan bon barang apapun</p>
                        <a href="{{ route('atasan.bon.my.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Bon Baru
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
