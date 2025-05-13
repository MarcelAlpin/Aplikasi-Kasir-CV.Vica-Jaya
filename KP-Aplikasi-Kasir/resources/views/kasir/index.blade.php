<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kasir - Transaksi
        </h2>
    </x-slot>

    <div class="py-4 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Kiri - Menu --}}
            <div class="col-span-2 bg-white dark:bg-gray-800 p-4 rounded shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">🧾 Data Menu</h3>
                    <div class="flex items-center gap-2">
                        <select class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                            <option>Semua Kategori</option>
                            {{-- Loop kategori jika ada --}}
                        </select>
                        <input type="text" placeholder="Cari Menu" class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                        <button class="bg-blue-600 text-white px-2 py-1 rounded">
                            🔍
                        </button>
                    </div>
                </div>

                {{-- Menu Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($menus as $menu)
                        <div class="border rounded shadow hover:shadow-lg cursor-pointer p-2" wire:click="tambahKeranjang({{ $menu->id }})">
                            <img src="data:image/png;base64,{{ $menu->gambar }}" alt="{{ $menu->nama }}" class="w-full h-32 object-cover rounded">
                            <div class="text-center mt-2">
                                <p class="text-sm text-gray-500">({{ $menu->kategori->nama }})</p>
                                <h4 class="font-semibold text-blue-700">{{ $menu->nama }}</h4>
                                <p class="text-green-600 font-semibold">Rp{{ number_format($menu->harga, 0, ',', '.') }},-</p>
                                <p class="text-sm text-gray-600">(Tersedia: {{ $menu->stok }}x)</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kanan - Keranjang --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">🛒 Keranjang</h3>

                <div class="mb-2">
                    <label class="text-sm font-medium">NO BON</label>
                    <input type="text" readonly value="B{{ $bonNumber }}" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-2">
                    <label class="text-sm font-medium">CUSTOMER</label>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Nama Customer" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                        <button class="bg-blue-600 text-white px-3 py-1 rounded">🔍</button>
                        <button class="bg-red-600 text-white px-3 py-1 rounded">🗑️</button>
                    </div>
                    <p class="text-red-500 text-xs mt-1">* Untuk customer yang sudah terdaftar</p>
                </div>

                <div class="mb-2">
                    <label class="text-sm font-medium">ATAS NAMA</label>
                    <input type="text" placeholder="Atas Nama" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                </div>

                <h4 class="text-md font-semibold mt-3 mb-2">🧾 List Keranjang</h4>
                <table class="w-full text-sm border">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                            <th class="px-2">No</th>
                            <th>Nama</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($keranjang as $index => $item)
                        <tr>
                            <td class="px-2">{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>
                                <input type="number" min="1" value="{{ $item->qty }}"
                                    class="w-12 border rounded text-center text-sm dark:bg-gray-700 dark:text-white">
                            </td>
                            <td>Rp{{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                            <td>
                                <button class="text-red-500">🗑️</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Info & Total --}}
                <div class="mt-4 space-y-2 text-sm">
                    <div>
                        <label>Status</label>
                        <select class="w-full border rounded px-2 py-1 dark:bg-gray-700 dark:text-white">
                            <option value="">- Status Pembayaran -</option>
                            <option value="lunas">Lunas</option>
                            <option value="belum">Belum Bayar</option>
                        </select>
                    </div>
                    <div>
                        <label>Order</label>
                        <select class="w-full border rounded px-2 py-1 dark:bg-gray-700 dark:text-white">
                            <option value="">Ditempat</option>
                            <option value="bungkus">Bungkus</option>
                        </select>
                    </div>
                    <div>
                        <label>Total Bayar</label>
                        <input type="text" readonly value="Rp{{ number_format($totalBayar, 0, ',', '.') }}" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label>Pajak</label>
                        <input type="text" readonly value="Rp0" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="text-right mt-2">
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
