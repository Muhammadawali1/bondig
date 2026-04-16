@extends('layouts.app')

@section('title', 'Kelola Divisi - Administrator')

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
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelola Divisi</h1>
                            <p class="mt-2 text-gray-600">Tambah, edit, atau hapus divisi</p>
                        </div>
                        <a href="{{ route('administrator.divisions.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                            + Tambah Divisi
                        </a>
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

                <!-- Divisions Table -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Divisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pengguna</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($divisions as $index => $divisi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $divisi->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $divisi->users()->count() }} pengguna</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $divisi->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('administrator.divisions.edit', $divisi->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('administrator.divisions.destroy', $divisi->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($divisions->count() === 0)
                    <div class="text-center py-12">
                        <p class="text-gray-500">Belum ada divisi yang ditambahkan.</p>
                    </div>
                    @endif
                </div>

            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
</div>

@endsection
