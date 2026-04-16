@extends('layouts.app')

@section('title', 'Tambah Barang')

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
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center mb-6">
                    <a href="{{ route('gudang.barang.index') }}" class="text-gray-600 hover:text-gray-800 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold">Tambah Barang Baru</h1>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <form action="{{ route('gudang.barang.store') }}" method="POST">
                        @csrf

                        <!-- Nama Barang -->
                        <div class="mb-6">
                            <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_barang" 
                                   name="nama_barang" 
                                   value="{{ old('nama_barang') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan nama barang"
                                   required>
                            @error('nama_barang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="mb-6">
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori" 
                                    name="kategori" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Pilih Kategori</option>
                                <option value="atk" {{ old('kategori') == 'atk' ? 'selected' : '' }}>ATK (Alat Tulis Kantor)</option>
                                <option value="art" {{ old('kategori') == 'art' ? 'selected' : '' }}>ART (Alat Rumah Tangga)</option>
                                <option value="tinta" {{ old('kategori') == 'tinta' ? 'selected' : '' }}>Tinta</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div class="mb-6">
                            <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                Stok Awal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="stok" 
                                   name="stok" 
                                   value="{{ old('stok', 0) }}"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan jumlah stok"
                                   required>
                            @error('stok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div class="mb-6">
                            <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <select id="satuan" 
                                    name="satuan" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Pilih Satuan</option>
                                <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="pak" {{ old('satuan') == 'pak' ? 'selected' : '' }}>Pak</option>
                                <option value="rim" {{ old('satuan') == 'rim' ? 'selected' : '' }}>Rim</option>
                            </select>
                            @error('satuan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('gudang.barang.index') }}" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>
</div>
@endsection
