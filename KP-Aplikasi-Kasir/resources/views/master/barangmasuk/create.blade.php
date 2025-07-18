<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Barang Masuk') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('barangmasuk.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="barang_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Barang</label>
                        <select name="barang_id" id="barang_id" required class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Barang</option>
                            @foreach ($barang as $item)
                                <option value="{{ $item->id }}" data-harga="{{ $item->harga }}">{{ $item->nama }} (Stok saat ini: {{ $item->stok }})</option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="jumlah_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Masuk</label>
                        <input type="number" name="jumlah_masuk" id="jumlah_masuk" required min="1" value="{{ old('jumlah_masuk') }}"
                            class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('jumlah_masuk')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="harga" id="harga" value="{{ old('harga') }}">
                    
                    <div class="mb-4">
                        <label for="harga_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Barang</label>
                        <input type="text" id="harga_display" readonly 
                            class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm" disabled>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const barangSelect = document.getElementById('barang_id');
                            const hargaInput = document.getElementById('harga');
                            const hargaDisplay = document.getElementById('harga_display');
                            
                            barangSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                const harga = selectedOption.dataset.harga || '';
                                
                                hargaInput.value = harga;
                                hargaDisplay.value = harga ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(harga) : '';
                            });
                            
                            // Initialize on page load if there's a pre-selected value
                            if (barangSelect.value) {
                                barangSelect.dispatchEvent(new Event('change'));
                            }
                        });
                    </script>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('barangmasuk.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>