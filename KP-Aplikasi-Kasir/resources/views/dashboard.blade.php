<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 md:px-6 lg:px-4">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Kolom 1 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">Halo, {{ Auth::user()->name }}</h1>
                        <p>Role: <span class="font-semibold">{{ Auth::user()->role }}</span></p>
                        <!-- @if(Auth::user()->role === 'admin')
                            <div class="space-y-2">
                                <p class="text-green-600 dark:text-green-300">Fitur khusus <strong>Admin</strong> telah aktif</p>
                            </div>
                        @elseif(Auth::user()->role === 'kasir')
                            <div class="space-y-2">
                                <p class="text-blue-600 dark:text-blue-300">Fitur khusus <strong>Kasir</strong></p>
                            </div>
                        @endif -->
                    </div>

                    <!-- Kolom 2 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">Rp. {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('d F Y') }}</p>
                        <p class="font-medium">Penghasilan hari ini</p>
                    </div>

                    <!-- Kolom 3 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">{{ $todayTransactions ?? 0 }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('d F Y') }}</p>
                        <p class="font-medium">Transaksi hari ini</p>
                    </div>

                    <!-- Kolom 4 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">{{ $todayItemsSold ?? 0 }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('d F Y') }}</p>
                        <p class="font-medium">Item Terjual hari ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
