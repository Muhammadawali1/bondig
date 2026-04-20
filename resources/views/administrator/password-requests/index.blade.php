@extends('layouts.app')

@section('title', 'Permintaan Password - Administrator')

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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                
                <!-- Header -->
                <div class="mb-8">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Permintaan Ubah Password</h1>
                        <p class="mt-2 text-gray-600">Setujui atau tolak permintaan perubahan password dari pengguna</p>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
                @endif

                <!-- Password Requests Table -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($requests as $index => $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($request->user)
                                            <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $request->user->nip }}</div>
                                        @else
                                            <div class="text-sm font-medium text-gray-900">Pengguna Dihapus</div>
                                            <div class="text-sm text-gray-500">N/A</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($request->user)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($request->user->role === 'administrator') bg-red-100 text-red-800
                                                @elseif($request->user->role === 'atasan') bg-green-100 text-green-800
                                                @elseif($request->user->role === 'gudang') bg-purple-100 text-purple-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($request->user->role) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($request->status)
                                            @case('pending')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu Persetujuan</span>
                                                @break
                                            @case('completed')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Disetujui</span>
                                                @break
                                            @case('rejected')
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>
                                                @break
                                            @default
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Status Tidak Diketahui</span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($request->status === 'pending')
                                            <form action="{{ route('administrator.password-requests.approve', $request->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Setujui</button>
                                            </form>
                                            <button onclick="showRejectModal({{ $request->id }})" 
                                                    class="text-red-600 hover:text-red-900">Tolak</button>
                                        @else
                                            <span class="text-gray-400">{{ $request->status === 'completed' ? 'Sudah disetujui' : 'Sudah ditolak' }}</span>
                                            @if($request->rejection_reason)
                                                <div class="text-xs text-gray-500 mt-1">Alasan: {{ $request->rejection_reason }}</div>
                                            @endif
                                            @if($request->approvedBy)
                                                <div class="text-xs text-gray-500 mt-1">Oleh: {{ $request->approvedBy->name }}</div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($requests->count() === 0)
                    <div class="text-center py-12">
                        <p class="text-gray-500">Belum ada permintaan perubahan password.</p>
                    </div>
                    @endif
                </div>

            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Permintaan Password</h3>
        <form id="rejectForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan
                </label>
                <textarea id="rejection_reason" 
                          name="rejection_reason" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-300"
                          placeholder="Masukkan alasan penolakan (opsional)"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" 
                        onclick="hideRejectModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(requestId) {
    document.getElementById('rejectForm').action = '/administrator/password-requests/' + requestId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
    document.getElementById('rejection_reason').value = '';
}
</script>

@endsection
