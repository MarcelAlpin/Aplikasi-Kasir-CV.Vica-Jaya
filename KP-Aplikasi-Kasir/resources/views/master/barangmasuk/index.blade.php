<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barang Masuk') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold mb-2 sm:mb-0">Daftar Barang Masuk</h3>
                    <a href="{{ route('barangmasuk.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        + Tambah Barang Masuk
                    </a>
                </div>
                
                <div class="overflow-x-auto relative">
                    <table class="w-full text-left table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                                <th class="px-4 py-2 border dark:border-gray-600">ID</th>
                                <th class="px-4 py-2 border dark:border-gray-600">Nama Barang</th>
                                <th class="px-4 py-2 border dark:border-gray-600">Jumlah Masuk</th>
                                <th class="px-4 py-2 border dark:border-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangMasuk as $item)
                                <tr class="text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border dark:border-gray-600">{{ $item->id }}</td>
                                    <td class="px-4 py-2 border dark:border-gray-600">{{ $item->barang->nama }}</td>
                                    <td class="px-4 py-2 border dark:border-gray-600">{{ $item->jumlah_masuk }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Belum ada data barang masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>