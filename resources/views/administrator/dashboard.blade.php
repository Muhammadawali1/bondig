@extends('layouts.app')

@section('title', 'Dashboard Administrator - Bonn DIG')

@section('content')

<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar (hidden di mobile) -->
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

                <!-- Header Section -->
                <div class="mb-8">
                    <a href="{{ route('profile') }}" class="block group">
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-transparent hover:shadow-xl transition-all duration-300 hover:border-red-600 hover:scale-[1.02]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center group-hover:text-red-600 transition-colors duration-300">
                                        <svg class="w-8 h-8 mr-3 text-red-600 group-hover:text-red-700 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Dashboard Administrator
                                    </h1>
                                    <p class="mt-2 text-gray-600 group-hover:text-red-600 transition-colors duration-300">
                                        Selamat datang, <span class="font-semibold text-red-600 group-hover:text-red-700 transition-colors duration-300">{{ auth()->user()->name ?? 'Administrator' }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-600 transition-colors duration-300">
                                        NIP: {{ auth()->user()->nip ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 group-hover:text-gray-600 transition-colors duration-300">
                                        {{ now()->timezone('Asia/Jakarta')->format('l, d F Y H:i') }} WIB</p>
                                    <div class="mt-2 h-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <svg class="w-5 h-5 text-red-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Pengguna</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $totalUsers }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Divisi</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $totalDivisions }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Permintaan Password</p>
                                <p class="text-lg font-semibold text-yellow-600">{{ $pendingPasswordRequests }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <a href="{{ route('administrator.divisions.index') }}" class="group">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-300 hover:scale-105">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Kelola</p>
                                    <p class="text-lg font-semibold text-gray-900">Divisi</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('administrator.accounts.index') }}" class="group">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-300 hover:scale-105">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Kelola</p>
                                    <p class="text-lg font-semibold text-gray-900">Akun</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('administrator.password-requests.index') }}" class="group">
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-yellow-300 hover:scale-105">
                            <div class="flex items-center">
                                <div class="p-3 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Setujui</p>
                                    <p class="text-lg font-semibold text-gray-900">Password</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="group">
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                            <div class="flex items-center">
                                <div class="p-3 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">Status</p>
                                    <p class="text-lg font-semibold text-green-600">Aktif</p>
                                </div>
                            </div>
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
