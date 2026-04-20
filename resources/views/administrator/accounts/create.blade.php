@extends('layouts.app')

@section('title', 'Tambah Akun - Administrator')

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
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Akun</h1>
                            <p class="mt-2 text-gray-600">Tambah akun pengguna baru</p>
                        </div>
                        <a href="{{ route('administrator.accounts.index') }}" 
                           class="text-gray-600 hover:text-gray-900 font-medium">
                            &larr; Kembali
                        </a>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl">
                    <form method="POST" action="{{ route('administrator.accounts.store') }}">
                        @csrf

                        <!-- Nama -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   required 
                                   value="{{ old('name') }}"
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
                                   value="{{ old('nip') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                   placeholder="Masukkan NIP (maksimal 18 digit)"
                                   maxlength="18"
                                   pattern="[0-9]+"
                                   inputmode="numeric">
                            @error('nip')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
<!-- Password -->
<div class="mb-6">
    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
        Password
    </label>

    <div class="relative">
        <input type="password" 
               id="password" 
               name="password" 
               required
               minlength="8"
               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
               placeholder="Masukkan password (minimal 8 karakter)">

        <button type="button" onclick="togglePassword('password', this)"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 transition duration-200">

            <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <!-- default icon (mata terbuka) -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7
                       -4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
    </div>

    @error('password')
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
                                <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                <option value="{{ $divisi->nama }}" {{ old('divisi') == $divisi->nama ? 'selected' : '' }}>{{ $divisi->nama }}</option>
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
                                Simpan Akun
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
<script>
function togglePassword(fieldId, button) {
    const input = document.getElementById(fieldId);
    const icon = button.querySelector('svg');

    if (input.type === "password") {
        input.type = "text";

        // icon mata dicoret (hidden)
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.828M9.88 5.09A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.132 5.411M6.223 6.223A9.956 9.956 0 002.458 12c1.274 4.057 5.065 7 9.542 7 1.61 0 3.13-.38 4.47-1.05" />
        `;
    } else {
        input.type = "password";

        // icon mata normal
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                -1.274 4.057-5.065 7-9.542 7
                -4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
@endsection
