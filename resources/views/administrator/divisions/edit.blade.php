@extends('layouts.app')

@section('title', 'Edit Divisi - Administrator')

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
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Divisi</h1>
                            <p class="mt-2 text-gray-600">Edit informasi divisi</p>
                        </div>
                        <a href="{{ route('administrator.divisions.index') }}" 
                           class="text-gray-600 hover:text-gray-900 font-medium">
                            &larr; Kembali
                        </a>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl">
                    <form method="POST" action="{{ route('administrator.divisions.update', $divisi->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama Divisi -->
                        <div class="mb-6">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Divisi
                            </label>
                            <input type="text" 
                                   id="nama" 
                                   name="nama" 
                                   required 
                                   value="{{ old('nama', $divisi->nama) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-300"
                                   placeholder="Contoh: IT, Akuntansi, SDM">
                            @error('nama')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('administrator.divisions.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition duration-200">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition duration-200">
                                Update Divisi
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
