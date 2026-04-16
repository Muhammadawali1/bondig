@extends('layouts.app')

@section('title', 'Edit Akun - Administrator')

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
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Akun</h1>
                            <p class="mt-2 text-gray-600">Edit informasi akun pengguna</p>
                        </div>
                        <a href="{{ route('administrator.accounts.index') }}" 
                           class="text-gray-600 hover:text-gray-900 font-medium">
                            &larr; Kembali
                        </a>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl">
                    <form method="POST" action="{{ route('administrator.accounts.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   required 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div class="mb-6">
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                NIP
                            </label>
                            <input type="text" 
                                   id="nip" 
                                   name="nip" 
                                   required 
                                   value="{{ old('nip', $user->nip) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="Masukkan NIP (maksimal 18 digit)"
                                   maxlength="18"
                                   pattern="[0-9]+"
                                   inputmode="numeric">
                            @error('nip')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-6">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Role
                            </label>
                            <select id="role" 
                                    name="role" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                                <option value="">Pilih Role</option>
                                @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Divisi -->
                        <div class="mb-6">
                            <label for="divisi" class="block text-sm font-medium text-gray-700 mb-2">
                                Divisi
                            </label>
                            <select id="divisi" 
                                    name="divisi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                                <option value="">Pilih Divisi (Opsional)</option>
                                @foreach($divisions as $divisi)
                                <option value="{{ $divisi->nama }}" {{ old('divisi', $user->divisi) == $divisi->nama ? 'selected' : '' }}>{{ $divisi->nama }}</option>
                                @endforeach
                            </select>
                            @error('divisi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('administrator.accounts.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition duration-200">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-200">
                                Update Akun
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
