@extends('layouts.app')


@section('title', 'Edit Profile - Bonn DIG')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
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

        <main class="flex-1 overflow-y-auto focus:outline-none">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Profile</h2>
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Profile Card -->
            <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-full md:max-w-4xl mx-auto hover:shadow-xl transition-shadow">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Current Photo Preview -->
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <div class="flex-shrink-0">
                            @if($user->photo)
                                <img src="{{ asset('uploads/profile/' . $user->photo) }}" 
                                     alt="Profile Photo" 
                                     class="h-24 w-24 md:h-28 md:w-28 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="h-24 w-24 md:h-28 md:w-28 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-3xl md:text-4xl font-bold text-white border-4 border-white">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Photo Upload -->
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-2">Foto Profile</h3>
                            <p class="text-gray-500 text-sm mb-3">Upload foto baru (JPEG, PNG, JPG, GIF - Max 2MB)</p>
                            <input type="file" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Read-only Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                NIP
                            </label>
                            <input 
                                type="text" 
                                value="{{ $user->nip ?? '-' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                readonly
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Divisi
                            </label>
                            <input 
                                type="text" 
                                value="{{ $user->divisi ?? '-' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                readonly
                            >
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('profile') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 w-full max-w-full md:max-w-4xl mx-auto">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Anda dapat mengubah nama dan foto profile. NIP dan divisi hanya dapat diubah oleh administrator.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>
</div>
@endsection
