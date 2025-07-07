<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg overflow-x-auto">
            <!-- buatkan sebuah field tanggal dari tgl brp sampai tgl brp untuk menampilkan data diantar tgl tersebut -->
            <form method="GET" class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end">
                    <!-- Tanggal Dari -->
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Tanggal Sampai -->
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Pembayaran -->
                    <div>
                        <label for="pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Pembayaran</label>
                        <select name="pembayaran"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="Cash" {{ request('pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Qris" {{ request('pembayaran') == 'Qris' ? 'selected' : '' }}>Qris</option>
                            <option value="Debit" {{ request('pembayaran') == 'Debit' ? 'selected' : '' }}>Debit</option>
                            <option value="Kredit" {{ request('pembayaran') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                    </div>

                    <!-- ID Transaksi -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">ID Transaksi</label>
                        <input type="text" name="search" placeholder="Cari ID..." value="{{ request('search') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            Filter
                        </button>
                        <a href="{{ route('transaksi.all.pdf', ['from' => request('from'), 'to' => request('to'), 'search' => request('search'), 'pembayaran' => request('pembayaran')]) }}"
                            class="w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                            Download PDF
                        </a>
                    </div>
                </div>
            </form>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">No Transaksi</th>
                        <th class="px-4 py-2 text-left">Nama Kasir</th>
                        <th class="px-4 py-2 text-left">Total Bayar</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Pembayaran</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-600 text-gray-700 dark:text-gray-100">
                    @foreach($transaksi as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->id }}</td>
                            <td class="px-4 py-2">{{ $item->user->name }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $item->pembayaran }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('transaksi.detail', $item->id) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $transaksi->links() }}
            </div>
            @if(session('success'))
                <div id="successNotification" 
                    class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg z-50 transform transition-all duration-500 translate-x-full">
                    {{ session('success') }}
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const notification = document.getElementById('successNotification');
                        if (notification) {
                            // Slide in
                            setTimeout(() => {
                                notification.classList.remove('translate-x-full');
                            }, 100);

                            // Slide out after 3 seconds
                            setTimeout(() => {
                                notification.classList.add('translate-x-full');
                                setTimeout(() => {
                                    notification.remove();
                                }, 500);
                            }, 3000);
                        }
                    });
                </script>
            @endif
        </div>
    </div>
</x-app-layout>
