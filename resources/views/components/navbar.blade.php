<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo Perusahaan" class="h-8 w-auto">
                <span class="text-xl font-bold text-gray-800">Bonn DIG</span>
            </div>

            <!-- User + Logout -->
            <div class="flex flex-wrap items-center space-x-2 sm:space-x-4 mt-2 sm:mt-0">
                
                <!-- Notifikasi -->
                <div class="relative" x-data="{ open: false, unreadCount: 0, notifikasis: [] }" x-init="
                    // Load unread count
                    fetch('/notifikasi/unread-count')
                        .then(response => response.json())
                        .then(data => unreadCount = data.count);
                    
                    // Load recent notifications
                    fetch('/notifikasi/recent')
                        .then(response => response.json())
                        .then(data => notifikasis = data);
                ">
                    <button @click="open = !open" 
                        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span x-show="unreadCount > 0" 
                            x-text="unreadCount > 99 ? '99+' : unreadCount"
                            class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[20px] h-5">
                        </span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-2 z-50 max-h-96 overflow-y-auto">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                            <button @click="
                                fetch('/notifikasi/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') } })
                                    .then(() => {
                                        unreadCount = 0;
                                        notifikasis.forEach(n => n.dibaca = true);
                                    });
                            " class="text-xs text-blue-600 hover:text-blue-800">
                                Tandai semua dibaca
                            </button>
                        </div>
                        
                        <!-- Notifikasi List -->
                        <div x-show="notifikasis.length > 0">
                            <template x-for="notifikasi in notifikasis" :key="notifikasi.id">
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition-colors duration-150"
                                    @click="
                                        // Mark as read first
                                        fetch('/notifikasi/' + notifikasi.id + '/mark-read', { 
                                            method: 'POST', 
                                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') } 
                                        })
                                            .then(() => {
                                                // Update UI immediately
                                                notifikasi.dibaca = true;
                                                unreadCount = Math.max(0, unreadCount - 1);
                                                
                                                // Then redirect based on notification type
                                                if(notifikasi.password_change_request_id) {
                                                    // Password change notifications - redirect to password index
                                                    window.location.href = '/password';
                                                } else if(notifikasi.bon_barang_id) {
                                                    // Bon notifications
                                                    @if(auth()->user()->isPegawai())
                                                        window.location.href = '/pegawai/bon/' + notifikasi.bon_barang_id;
                                                    @elseif(auth()->user()->isAtasan())
                                                        window.location.href = '/atasan/bon/' + notifikasi.bon_barang_id;
                                                    @elseif(auth()->user()->isGudang())
                                                        window.location.href = '/gudang/bon/' + notifikasi.bon_barang_id;
                                                    @else
                                                        window.location.href = '/bon/' + notifikasi.bon_barang_id;
                                                    @endif
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error marking notification as read:', error);
                                                // Still redirect even if marking as read fails
                                                if(notifikasi.password_change_request_id) {
                                                    // Password change notifications
                                                    window.location.href = '/password';
                                                } else if(notifikasi.bon_barang_id) {
                                                    // Bon notifications
                                                    @if(auth()->user()->isPegawai())
                                                        window.location.href = '/pegawai/bon/' + notifikasi.bon_barang_id;
                                                    @elseif(auth()->user()->isAtasan())
                                                        window.location.href = '/atasan/bon/' + notifikasi.bon_barang_id;
                                                    @elseif(auth()->user()->isGudang())
                                                        window.location.href = '/gudang/bon/' + notifikasi.bon_barang_id;
                                                    @elseif(auth()->user()->isAdministrator())
                                                        window.location.href = '/bon/' + notifikasi.bon_barang_id;
                                                    @else
                                                        window.location.href = '/bon/' + notifikasi.bon_barang_id;
                                                    @endif
                                                }
                                            });
                                    ">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 rounded-full mt-2 transition-colors duration-150" 
                                                :class="notifikasi.dibaca ? 'bg-gray-300' : 'bg-blue-600'">
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900" x-text="notifikasi.judul"></p>
                                            <p class="text-sm text-gray-600" x-text="notifikasi.pesan"></p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="new Date(notifikasi.created_at).toLocaleString('id-ID')"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Empty State -->
                        <div x-show="notifikasis.length === 0" class="px-4 py-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tidak ada notifikasi</p>
                        </div>
                        
                        <!-- Footer -->
                        <div class="px-4 py-2 border-t border-gray-200">
                            <a href="/notifikasi" class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat semua notifikasi
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('uploads/profile/' . auth()->user()->photo) }}" 
                                 alt="Profile Photo" 
                                 class="h-8 w-8 rounded-full object-cover border-2 border-blue-600">
                        @else
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <span class="text-white font-medium">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👤 Profile</a>
                        @if(auth()->user()->isAdministrator())
                            <a href="{{ route('password.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⚙️ Ubah Password</a>
                        @else
                            <a href="{{ route('password.request') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">⚙️ Ubah Password</a>
                        @endif
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>

<script src="//unpkg.com/alpinejs" defer></script>