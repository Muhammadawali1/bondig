@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar (hidden di mobile) -->
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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Ubah Password</h1>
                <div class="flex space-x-3">
                    @if(!auth()->user()->isAdministrator())
                        <a href="{{ route('password.request') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            Ajukan Permintaan
                        </a>
                    @else
                        <a href="{{ route('password.direct-change') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                            Ubah Password Langsung
                        </a>
                    @endif
                </div>
            </div>
            
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
        </div>

        <!-- Daftar Permintaan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Permintaan Ubah Password</h2>
            
            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Diproses Oleh
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($request->status)
                                            @case('pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    ⏳ Menunggu Persetujuan
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ✅ Disetujui
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    ❌ Ditolak
                                                </span>
                                                @break
                                            @default
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    ❓ Status Tidak Diketahui
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($request->approvedBy)
                                            {{ $request->approvedBy->name }}
                                            @if($request->approved_at)
                                                <br><span class="text-xs text-gray-500">{{ $request->approved_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($request->rejection_reason)
                                            <span class="text-red-600">Alasan: {{ $request->rejection_reason }}</span>
                                        @elseif($request->status == 'completed')
                                            <span class="text-green-600">✅ Password berhasil diubah</span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($request->status == 'pending')
                                            <span class="text-xs text-gray-500">Menunggu persetujuan admin</span>
                                        @else
                                            <span class="text-xs text-gray-400">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    <p class="mt-2 text-gray-500">Belum ada permintaan ubah password</p>
                    <p class="text-sm text-gray-400 mt-1">
                        @if(!auth()->user()->isAdministrator())
                            Klik "Ajukan Permintaan" untuk mengubah password Anda.
                        @else
                            Klik "Ubah Password Langsung" untuk mengubah password Anda.
                        @endif
                    </p>
                </div>
            @endif
        </div>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
</div>
@endsection
