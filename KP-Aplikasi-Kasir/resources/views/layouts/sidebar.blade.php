<aside x-show="sidebarOpen" x-transition class="w-64 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 min-h-screen px-4 py-6 shadow-lg" style="display: none;">
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
            Dashboard
        </a>

        @if(Auth::user()->role === 'admin')
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                Kelola Produk
            </a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                Laporan
            </a>
        @endif

        @if(Auth::user()->role === 'kasir')
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                Transaksi
            </a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                Riwayat Transaksi
            </a>
        @endif
    </nav>
</aside>