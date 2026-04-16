<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50" 
     x-data="{ 
         mobileMenuOpen: false,
         activeItems: 3,
         totalItems: 0,
         items: []
     }"
     x-init="
         // Set menu items based on user role
        @if(auth()->user()->role === 'pegawai')
            totalItems = 6;
            items = [
                { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: '{{ route('dashboard') }}', active: {{ request()->is('dashboard') ? 'true' : 'false' }} },
                { name: 'Barang', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', route: '{{ route('pegawai.barang.index') }}', active: {{ request()->is('pegawai/barang') ? 'true' : 'false' }} },
                { name: 'Bon', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', route: '{{ route('pegawai.bon.index') }}', active: {{ request()->is('pegawai/bon*') ? 'true' : 'false' }} },
                { name: 'Profil', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', route: '/profile', active: {{ request()->is('profile*') ? 'true' : 'false' }} },
                { name: 'Notifikasi', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', route: '/notifikasi', active: {{ request()->is('notifikasi*') ? 'true' : 'false' }} },
                { name: 'Password', icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', route: '{{ route('password.request') }}', active: {{ request()->is('password*') ? 'true' : 'false' }} }
            ];
        @elseif(auth()->user()->role === 'atasan')
            totalItems = 7;
            items = [
                { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: '{{ route('dashboard') }}', active: {{ request()->is('dashboard') ? 'true' : 'false' }} },
                { name: 'Barang', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', route: '{{ route('atasan.barang.index') }}', active: {{ request()->is('atasan/barang') ? 'true' : 'false' }} },
                { name: 'Bon Saya', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', route: '{{ route('atasan.bon.my') }}', active: {{ request()->is('atasan/bon-saya*') ? 'true' : 'false' }} },
                { name: 'Approval', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', route: '{{ route('atasan.bon.index') }}', active: {{ request()->is('atasan/bon') && !request()->is('atasan/bon-saya*') ? 'true' : 'false' }} },
                { name: 'Profil', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', route: '/profile', active: {{ request()->is('profile*') ? 'true' : 'false' }} },
                { name: 'Notifikasi', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', route: '/notifikasi', active: {{ request()->is('notifikasi*') ? 'true' : 'false' }} },
                { name: 'Password', icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', route: '{{ route('password.request') }}', active: {{ request()->is('password*') ? 'true' : 'false' }} }
            ];
        @elseif(auth()->user()->role === 'gudang')
            totalItems = 7;
            items = [
                { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: '{{ route('dashboard') }}', active: {{ request()->is('dashboard') ? 'true' : 'false' }} },
                { name: 'Barang', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', route: '{{ route('gudang.barang.index') }}', active: {{ request()->is('gudang/barang') ? 'true' : 'false' }} },
                { name: 'Bon', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', route: '{{ route('gudang.bon.index') }}', active: {{ request()->is('gudang/bon') ? 'true' : 'false' }} },
                { name: 'History', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', route: '{{ route('gudang.bon.history') }}', active: {{ request()->is('gudang/bon-history') ? 'true' : 'false' }} },
                { name: 'Profil', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', route: '/profile', active: {{ request()->is('profile*') ? 'true' : 'false' }} },
                { name: 'Notifikasi', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', route: '/notifikasi', active: {{ request()->is('notifikasi*') ? 'true' : 'false' }} },
                { name: 'Password', icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', route: '{{ route('password.request') }}', active: {{ request()->is('password*') ? 'true' : 'false' }} }
            ];
        @elseif(auth()->user()->role === 'administrator')
            totalItems = 7;
            items = [
                { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: '{{ route('dashboard') }}', active: {{ request()->is('dashboard') ? 'true' : 'false' }} },
                { name: 'Divisi', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', route: '{{ route('administrator.divisions.index') }}', active: {{ request()->is('administrator/divisions*') ? 'true' : 'false' }} },
                { name: 'Akun', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', route: '{{ route('administrator.accounts.index') }}', active: {{ request()->is('administrator/accounts*') ? 'true' : 'false' }} },
                { name: 'Password', icon: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', route: '{{ route('administrator.password-requests.index') }}', active: {{ request()->is('administrator/password-requests*') ? 'true' : 'false' }} },
                { name: 'Profil', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', route: '/profile', active: {{ request()->is('profile*') ? 'true' : 'false' }} },
                { name: 'Notifikasi', icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', route: '/notifikasi', active: {{ request()->is('notifikasi*') ? 'true' : 'false' }} },
                { name: 'Pengaturan', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', route: '/settings', active: {{ request()->is('settings*') ? 'true' : 'false' }} }
            ];
        @endif
         
         // Always show only 3 main items in bottom nav
        activeItems = 3;
     ">
    
    <!-- Main Navigation Items -->
    <div class="flex justify-around items-center py-2">
        <template x-for="(item, index) in items.slice(0, activeItems)" :key="index">
            <a :href="item.route" 
               class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] transition-colors duration-200"
               :class="item.active ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900'">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                    </svg>
                    <div x-show="item.active" class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></div>
                </div>
                <span class="text-xs mt-1 font-medium" x-text="item.name"></span>
            </a>
        </template>
        
                
        <!-- Hamburger Menu -->
        <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="text-xs mt-1 font-medium">Menu</span>
        </button>
        
        <!-- User Menu -->
        <div class="relative" x-data="{ userMenuOpen: false }">
            <button @click="userMenuOpen = !userMenuOpen"
                    class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] text-gray-600 hover:text-gray-900 transition-colors duration-200">
                @if(auth()->user()->photo)
                    <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}" 
                         alt="Profile" 
                         class="w-6 h-6 rounded-full object-cover border border-gray-300">
                @else
                    <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                @endif
                <span class="text-xs mt-1 font-medium">Profile</span>
            </button>
            
            <!-- User Dropdown -->
            <div x-show="userMenuOpen" 
                 @click.away="userMenuOpen = false" 
                 x-transition
                 class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                @if(auth()->user()->isAdministrator())
                    <a href="{{ route('password.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ubah Password</a>
                @else
                    <a href="{{ route('password.request') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ubah Password</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
        
    <!-- Slide-in Mobile Sidebar -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 md:hidden"
         @click.away="mobileMenuOpen = false">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="mobileMenuOpen = false"></div>
        
        <!-- Sidebar -->
        <div class="fixed left-0 top-0 h-full w-72 bg-white shadow-xl transform transition-transform duration-300 ease-in-out"
             :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-xl font-bold">Bonn DIG</span>
                    </div>
                    <button @click="mobileMenuOpen = false" class="text-white hover:text-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-sm opacity-90">{{ auth()->user()->name }}</p>
                    <p class="text-xs opacity-75">{{ auth()->user()->nip }}</p>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2">
                @if(auth()->user()->role === 'pegawai')
                    <!-- Pegawai Menu -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('pegawai.barang.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('pegawai/barang') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Barang
                    </a>
                    <a href="{{ route('pegawai.bon.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('pegawai/bon*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Bon Barang
                    </a>
                    
                @elseif(auth()->user()->role === 'atasan')
                    <!-- Atasan Menu -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('atasan.barang.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('atasan/barang') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Barang
                    </a>
                    <a href="{{ route('atasan.bon.my') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('atasan/bon-saya*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Bon Saya
                    </a>
                    <a href="{{ route('atasan.bon.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('atasan/bon') && !request()->is('atasan/bon-saya*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approval Bon
                    </a>
                    
                @elseif(auth()->user()->role === 'gudang')
                    <!-- Gudang Menu -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('gudang.barang.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('gudang/barang') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Barang
                    </a>
                    <a href="{{ route('gudang.bon.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('gudang/bon') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Bon Barang
                    </a>
                    <a href="{{ route('gudang.bon.history') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('gudang/bon-history') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        History Bon
                    </a>
                    
                @elseif(auth()->user()->role === 'administrator')
                    <!-- Administrator Menu -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('administrator.divisions.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('administrator/divisions*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kelola Divisi
                    </a>
                    <a href="{{ route('administrator.accounts.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('administrator/accounts*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kelola Akun
                    </a>
                    <a href="{{ route('administrator.password-requests.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('administrator/password-requests*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        Permintaan Password
                    </a>
                @endif
            </nav>
            
            <!-- User Actions -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                <div class="space-y-2">
                    <a href="/profile" class="block w-full text-center px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Profile
                    </a>
                    @if(auth()->user()->isAdministrator())
                        <a href="{{ route('password.index') }}" class="block w-full text-center px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Ubah Password
                        </a>
                    @else
                        <a href="{{ route('password.request') }}" class="block w-full text-center px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Ubah Password
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

<!-- Add padding to body to account for fixed bottom nav on mobile -->
<style>
@media (max-width: 768px) {
    body {
        padding-bottom: 70px;
    }
}
</style>
