@extends('layouts.app')

@section('title', 'Profile Pegawai - Bonn DIG')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        @include('components.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Navbar (desktop only) -->
        <div class="hidden md:block">
            @include('components.navbar')
        </div>

        <!-- Mobile Navbar -->
        @include('components.mobile-navbar')

        <main class="flex-1 overflow-y-auto focus:outline-none">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile Saya
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 ml-11">Kelola informasi pribadi dan detail akun Anda.</p>
                </div>

                <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-md">
                    <div class="p-6 sm:p-10">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                            
                            <div class="relative group">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}" 
                                         alt="Profile Photo" 
                                         class="h-32 w-32 rounded-2xl object-cover border-4 border-white shadow-md group-hover:scale-[1.02] transition-transform duration-300">
                                @else
                                    <div class="h-32 w-32 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-5xl font-bold text-white border-4 border-white shadow-md group-hover:scale-[1.02] transition-transform duration-300">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="absolute -bottom-2 -right-2 bg-green-500 border-4 border-white h-6 w-6 rounded-full" title="Status Aktif"></div>
                            </div>

                            <div class="flex-1 w-full">
                                <div class="text-center md:text-left mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                                    <p class="text-blue-600 font-medium">{{ auth()->user()->email }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Nomor Induk Pegawai (NIP)</p>
                                        <p class="text-gray-800 font-medium">{{ auth()->user()->nip ?? '-' }}</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Divisi / Unit Kerja</p>
                                        <p class="text-gray-800 font-medium">{{ auth()->user()->divisi ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('profile.edit') }}"
                                       class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl shadow-sm hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Profile
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                        <span>Terakhir diperbarui: {{ auth()->user()->updated_at->diffForHumans() }}</span>
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Verified Account
                        </span>
                    </div>
                </div>
            </div>
        </main>

        @include('components.footer')
    </div>
</div>
@endsection