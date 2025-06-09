<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Laporan Transaksi</h3>
            <a href="{{ route('laporan.transaksi.pdf') }}" 
               class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
            Export PDF
            </a>
        </div>
        @php
            $groupedTransaksi = $transaksi->groupBy(function($item) {
                return $item->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d');
            });
        @endphp

        @foreach($groupedTransaksi as $date => $dailyTransaksi)
            @php
                $dailyTotal = $dailyTransaksi->sum('total_bayar');
            @endphp
            
            <div style="margin-bottom: 20px; page-break-inside: avoid;">
                <div style="background-color: #f3f4f6; padding: 10px; border-radius: 5px 5px 0 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="font-weight: bold; margin: 0;">
                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                        </h4>
                        <span style="font-weight: bold; color: #059669;">
                            Total: Rp{{ number_format($dailyTotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #d1d5db;">
                    <thead style="background-color: #f9fafb;">
                        <tr>
                            <th style="padding: 8px; border: 1px solid #d1d5db; text-align: left;">No Transaksi</th>
                            <th style="padding: 8px; border: 1px solid #d1d5db; text-align: left;">Nama</th>
                            <th style="padding: 8px; border: 1px solid #d1d5db; text-align: left;">Total Bayar</th>
                            <th style="padding: 8px; border: 1px solid #d1d5db; text-align: left;">Waktu</th>
                            <th style="padding: 8px; border: 1px solid #d1d5db; text-align: left;">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyTransaksi as $trx)
                        <tr>
                            <td style="padding: 8px; border: 1px solid #d1d5db;">{{ $trx->id }}</td>
                            <td style="padding: 8px; border: 1px solid #d1d5db;">{{ $trx->atas_nama }}</td>
                            <td style="padding: 8px; border: 1px solid #d1d5db;">Rp{{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                            <td style="padding: 8px; border: 1px solid #d1d5db;">{{ $trx->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</td>
                            <td style="padding: 8px; border: 1px solid #d1d5db;">
                                @foreach($trx->detail as $item)
                                    {{ $item->barang->nama }} x{{ $item->qty }} (Rp{{ number_format($item->harga, 0, ',', '.') }})@if(!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">No Transaksi</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Total Bayar</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-600 text-gray-700 dark:text-gray-100">
                    @foreach($transaksi as $trx)
                        <tr>
                            <td class="px-4 py-2">{{ $trx->id }}</td>
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
