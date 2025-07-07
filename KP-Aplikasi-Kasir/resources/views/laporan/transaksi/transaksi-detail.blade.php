<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Invoice Transaksi
        </h2>
    </x-slot>

    <div class="py-4 px-6" id="invoiceArea">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-sm">
            <div class="mb-6 border-b pb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">CV. Vica Jaya</h1>
                        <p class="text-gray-600 dark:text-gray-400">Palembang, Indonesia</p>
                        <p class="text-gray-600 dark:text-gray-400">Telp: (0711)354147</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500">Invoice #: <strong>{{ $transaksi->id }}</strong></p>
                        <p class="text-gray-500">Tanggal: <strong>{{ $transaksi->created_at->format('d M Y H:i') }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="mb-6 flex justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Kasir:</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaksi->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Metode Pembayaran:</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaksi->pembayaran }}</p>
                </div>
            </div>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-left border border-gray-200 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">Nama Barang</th>
                            <th class="p-2 border">Harga</th>
                            <th class="p-2 border">Qty</th>
                            <th class="p-2 border">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detail as $index => $item)
                            <tr>
                                <td class="p-2 border">{{ $index + 1 }}</td>
                                <td class="p-2 border">{{ $item->barang->nama }}</td>
                                <td class="p-2 border">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="p-2 border">{{ $item->qty }}</td>
                                <td class="p-2 border">Rp{{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="p-2 border text-right font-semibold">Sub Total</td>
                            <td class="p-2 border">Rp{{ number_format($transaksi->total_bayar - $transaksi->pajak, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="p-2 border text-right font-semibold">Pajak </td>
                            <td class="p-2 border">Rp{{ number_format($transaksi->pajak, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <td colspan="4" class="p-2 border text-right font-bold">Total</td>
                            <td class="p-2 border font-bold">Rp{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-center mt-6">
                <p class="text-gray-600 dark:text-gray-400">Terima kasih telah berbelanja di CV. Vica Jaya</p>
                <p class="text-xs text-gray-400 mt-2">* Mohon simpan bukti transaksi ini untuk keperluan garansi atau retur.</p>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('transaksi.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded text-sm">Kembali</a>
                <a href="{{ route('transaksi.pdf', $transaksi->id) }}"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                Download PDF
                </a>
            </div>
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
</x-app-layout>
