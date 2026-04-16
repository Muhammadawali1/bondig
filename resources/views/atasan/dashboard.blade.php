@extends('layouts.app')

@section('title', 'Dashboard Atasan - Bonn DIG')

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

                        <div
                            class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-transparent hover:shadow-xl transition-all duration-300 hover:border-green-600 hover:scale-[1.02]">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h1
                                        class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center group-hover:text-green-600 transition-colors duration-300">

                                        <svg class="w-8 h-8 mr-3 text-green-600 group-hover:text-green-700 transition-colors duration-300"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                                        </svg>

                                        Dashboard Atasan

                                    </h1>

                                    <p
                                        class="mt-2 text-gray-600 group-hover:text-green-600 transition-colors duration-300">

                                        Selamat datang, <span
                                            class="font-semibold text-green-600 group-hover:text-green-700 transition-colors duration-300">{{ auth()->user()->name ?? 'Atasan' }}</span>

                                    </p>

                                    <p
                                        class="text-sm text-gray-500 mt-1 group-hover:text-gray-600 transition-colors duration-300">

                                        NIP: {{ auth()->user()->nip ?? '-' }} | Divisi:
                                        {{ auth()->user()->divisi ?? '-' }}

                                    </p>

                                </div>

                                <div class="text-right">

                                    <p
                                        class="text-sm text-gray-500 group-hover:text-gray-600 transition-colors duration-300">
                                        {{ now()->timezone('Asia/Jakarta')->format('l, d F Y H:i') }} WIB</p>

                                    <div
                                        class="mt-2 h-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">

                                        <svg class="w-5 h-5 text-green-600 inline-block" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />

                                        </svg>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>



                <!-- Quick Actions -->

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                    <a href="{{ route('atasan.barang.index') }}" class="group">

                        <div
                            class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-300 hover:scale-105">

                            <div class="flex items-center">

                                <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">

                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />

                                    </svg>

                                </div>

                                <div class="ml-4">

                                    <p class="text-sm text-gray-600">Inventaris</p>

                                    <p class="text-lg font-semibold text-gray-900">Kelola Barang</p>

                                </div>

                            </div>

                        </div>

                    </a>



                    <a href="{{ route('atasan.bon.index') }}" class="group">

                        <div
                            class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-300 hover:scale-105">

                            <div class="flex items-center">

                                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">

                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />

                                    </svg>

                                </div>

                                <div class="ml-4">

                                    <p class="text-sm text-gray-600">Pengajuan</p>

                                    <p class="text-lg font-semibold text-gray-900">Bon Pegawai</p>

                                </div>

                            </div>

                        </div>

                    </a>



                    <a href="{{ route('atasan.bon.my') }}" class="group">

                        <div
                            class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-300 hover:scale-105">

                            <div class="flex items-center">

                                <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">

                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                                    </svg>

                                </div>

                                <div class="ml-4">

                                    <p class="text-sm text-gray-600">Pengajuan</p>

                                    <p class="text-lg font-semibold text-gray-900">Bon Saya</p>

                                </div>

                            </div>

                        </div>

                    </a>



                    <div class="group">

                        <a href="{{ route('atasan.bon.index') }}" class="block">

                            <div
                                class="bg-white rounded-lg shadow-md p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:border-green-300 hover:scale-105">

                                <div class="flex items-center">

                                    <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">

                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

                                        </svg>

                                    </div>

                                    <div class="ml-4">

                                        <p class="text-sm text-gray-600">Pengajuan</p>

                                        <p class="text-lg font-semibold text-green-600">Approval Bon</p>

                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                </div>



                
        </main>



        <!-- Footer -->

        @include('components.footer')

    </div>

</div>

@endsection