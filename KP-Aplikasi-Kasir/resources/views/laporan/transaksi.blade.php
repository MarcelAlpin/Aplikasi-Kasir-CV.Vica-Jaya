<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">No BON</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Total Bayar</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-600 text-gray-700 dark:text-gray-100">
                    @foreach($transaksi as $trx)
                        <tr>
                            <td class="px-4 py-2">{{ $trx->transaksiId }}</td>
                            <td class="px-4 py-2">{{ $trx->atas_nama }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $trx->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <ul class="list-disc pl-5">
                                    @foreach($trx->detail as $item)
                                        <li>
                                            {{ $item->barang->nama }} x{{ $item->qty }}
                                            (Rp{{ number_format($item->harga, 0, ',', '.') }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
