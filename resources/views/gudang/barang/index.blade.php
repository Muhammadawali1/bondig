    @extends('layouts.app')

@section('title', 'Manajemen Stok Barang')

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

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">🏭 Manajemen Barang</h1>
                <a href="{{ route('gudang.barang.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Barang
                </a>
            </div>

            <!-- Category Filter -->
            <div class="mb-6 flex gap-4 items-center">
                <span class="text-sm font-medium text-gray-700">Filter Kategori:</span>
                <div class="flex gap-2">
                    <a href="{{ route('gudang.barang.index') }}" 
                       class="px-3 py-1 rounded-full text-xs font-medium {{ !request('kategori') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
                        Semua
                    </a>
                    <a href="{{ route('gudang.barang.index') }}?kategori=atk" 
                       class="px-3 py-1 rounded-full text-xs font-medium {{ request('kategori') == 'atk' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
                        ATK
                    </a>
                    <a href="{{ route('gudang.barang.index') }}?kategori=art" 
                       class="px-3 py-1 rounded-full text-xs font-medium {{ request('kategori') == 'art' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
                        ART
                    </a>
                    <a href="{{ route('gudang.barang.index') }}?kategori=tinta" 
                       class="px-3 py-1 rounded-full text-xs font-medium {{ request('kategori') == 'tinta' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
                        Tinta
                    </a>
                </div>
            </div>

            <!-- Search Box -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" 
                           id="barangSearch" 
                           placeholder="Cari barang..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 pl-10 border border-gray-300 rounded[-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onkeyup="liveSearch()">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Nama Barang</th>
                            <th class="p-3 text-left">Kategori</th>
                            <th class="p-3 text-left">Stok</th>
                            <th class="p-3 text-left">Satuan</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangs as $barang)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">{{ $loop->iteration }}</td>
                                <td class="p-3 font-medium">{{ $barang->nama_barang }}</td>
                                <td class="p-3">
                                    @switch($barang->kategori)
                                        @case('atk')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">ATK</span>
                                            @break
                                        @case('art')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">ART</span>
                                            @break
                                        @case('tinta')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Tinta</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $barang->kategori }}</span>
                                    @endswitch
                                </td>
                                <td class="p-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $barang->stok <= 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $barang->stok }}
                                    </span>
                                </td>
                                <td class="p-3">{{ $barang->satuan }}</td>
                                <td class="p-3">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('gudang.barang.edit', $barang->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('gudang.barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    <div class="mb-4">
                                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    Data barang tidak ditemukan
                                    <br>
                                    <a href="{{ route('gudang.barang.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                        Lihat semua barang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>

<script>
function liveSearch() {
    const searchValue = document.getElementById('barangSearch').value;
    
    if (searchValue.length >= 1) {
        // Redirect dengan search parameter
        window.location.href = `{{ route('gudang.barang.index') }}?search=${encodeURIComponent(searchValue)}`;
    } else if (searchValue.length === 0) {
        // Redirect ke index tanpa search
        window.location.href = `{{ route('gudang.barang.index') }}`;
    }
}
</script>
@endsection
