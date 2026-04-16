@extends('layouts.app')

@section('title', 'Kelola Permintaan Ubah Password')

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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Kelola Permintaan Ubah Password</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('password.direct-change') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                        Ubah Password Saya
                    </a>
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
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Semua Permintaan Ubah Password</h2>
            
            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($request->user->photo)
                                                <img src="{{ asset('uploads/profile/' . $request->user->photo) }}" 
                                                     alt="{{ $request->user->name }}" 
                                                     class="h-8 w-8 rounded-full object-cover mr-3">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center mr-3">
                                                    <span class="text-white font-medium text-xs">
                                                        {{ strtoupper(substr($request->user->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $request->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $request->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($request->user->role == 'administrator') bg-purple-100 text-purple-800
                                            @elseif($request->user->role == 'atasan') bg-green-100 text-green-800
                                            @elseif($request->user->role == 'gudang') bg-orange-100 text-orange-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($request->user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $request->created_at->format('d/m/Y H:i') }}
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($request->status == 'pending')
                                            <form action="{{ route('password.approve', $request->id) }}" method="POST" class="inline" style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-800 mr-3"
                                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>
                                            <button onclick="showRejectModal({{ $request->id }})" 
                                                    class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tolak
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs">
                                                @if($request->status == 'completed')
                                                    Disetujui oleh {{ $request->approvedBy->name ?? 'Admin' }} pada {{ $request->approved_at ? $request->approved_at->format('d/m/Y H:i') : '-' }}
                                                @elseif($request->status == 'rejected')
                                                    Ditolak oleh {{ $request->approvedBy->name ?? 'Admin' }} pada {{ $request->approved_at ? $request->approved_at->format('d/m/Y H:i') : '-' }}
                                                    @if($request->rejection_reason)
                                                        <br><span class="text-red-600">Alasan: {{ $request->rejection_reason }}</span>
                                                    @endif
                                                @endif
                                            </span>
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
                    <p class="mt-2 text-gray-500">Tidak ada permintaan ubah password</p>
                    <p class="text-sm text-gray-400 mt-1">Belum ada user yang mengajukan permintaan ubah password</p>
                </div>
            @endif
        </div>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tolak Permintaan Ubah Password</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan
                    </label>
                    <textarea id="rejection_reason" 
                              name="rejection_reason" 
                              rows="4" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="hideRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(requestId) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectForm').action = '/password/' + requestId + '/reject';
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endsection
