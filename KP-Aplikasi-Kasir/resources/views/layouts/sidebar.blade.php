<aside x-show="sidebarOpen" x-transition class="fixed top-16 left-0 w-64 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 h-[calc(100vh-4rem)] px-4 py-6 shadow-lg z-40">
    <nav class="space-y-2" x-data="{ openMenu: '{{ request()->is('barang*') ? 'produk' : null }}' }">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>

        <!-- Admin Section -->
        @if(Auth::user()->role === 'admin')
        <!-- Master Menu -->
        <div>
            <button @click="openMenu === 'produk' ? openMenu = null : openMenu = 'produk'"
                class="flex items-center w-full px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-box mr-2"></i> Master
                <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-180': openMenu === 'produk' }"></i>
            </button>
            <div x-show="openMenu === 'produk'" x-transition>
                <a href="{{ route('kategori.index') }}" class="block px-10 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">Kategori</a>
                <a href="{{ route('satuan.index')}}" class="block px-10 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">Satuan</a>
                <a href="#" class="block px-10 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">Barang</a>
                <a href="#" class="block px-10 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">Stok Opname</a>
            </div>
        </div>

        <!-- Laporan -->
        <div>
            <button @click="openMenu === 'laporan' ? openMenu = null : openMenu = 'laporan'"
                class="flex items-center w-full px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-chart-line mr-2"></i> Laporan
                <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-180': openMenu === 'laporan' }"></i>
            </button>
            <div x-show="openMenu === 'laporan'" x-transition>
                <a href="#" class="block px-10 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">Transaksi</a>
                <a href="#" class="block px-10 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">Barang Masuk</a>
            </div>
        </div>

        <!-- Hak Akses -->
        <a href="#" class="flex items-center px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-lock mr-2"></i> Hak Akses
        </a>
        @endif

        <!-- Kasir Section -->
        @if(Auth::user()->role === 'kasir')
        <a href="#" class="flex items-center px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-cash-register mr-2"></i> Kasir
        </a>
        <a href="#" class="flex items-center px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-history mr-2"></i> Riwayat Transaksi
        </a>
        @endif
    </nav>
</aside>
