<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Barang Masuk') }} - {{ $barang->nama }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 md:px-6 lg:px-4">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Detail Barang</h3>
                            <a href="{{ route('barangmasuk.index') }}" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                                Kembali
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Barang</p>
                                <p class="font-semibold">{{ $barang->nama }}</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kategori</p>
                                <p class="font-semibold">{{ $barang->kategori->nama ?? 'Tidak ada' }}</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Stok Saat Ini</p>
                                <p class="font-semibold">{{ $barang->stok }} {{ $barang->satuan->nama ?? '' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Riwayat Barang Masuk</h3>
                        </div>

                        <table class="w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                                    <th class="px-4 py-2 border dark:border-gray-600">#</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">ID</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">Tanggal</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">Jumlah Masuk</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayat as $item)
                                    <tr class="text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $item->id }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $item->jumlah_masuk }} {{ $barang->satuan->nama ?? '' }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">
                                            @if ($item->harga)
                                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat barang masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $riwayat->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>