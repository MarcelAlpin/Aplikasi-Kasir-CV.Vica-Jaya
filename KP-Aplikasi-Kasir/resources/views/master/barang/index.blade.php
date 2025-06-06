<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barang') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 md:px-6 lg:px-4">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                    <!-- Kolom 1 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Daftar Barang</h3>
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button type="button" id="tableViewBtn" class="p-2 text-blue-700 bg-white border border-r-0 rounded-l-lg hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <i class="fas fa-list"></i>
                                </button>
                                <button type="button" id="gridViewBtn" class="p-2 text-gray-900 bg-white border rounded-r-lg hover:bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <i class="fas fa-th-large"></i>
                                </button>
                            </div>
                            <a href="{{ route('barang.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                + Tambah Barang
                            </a>
                        </div>
                        <div id="tableView" class="w-full">
                            <table class="w-full text-left table-auto border-collapse">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                                        <th class="px-4 py-2 border dark:border-gray-600">#</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Gambar</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Nama Barang</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Kategori</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Deskripsi</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Stok</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Harga</th>
                                        <th class="px-4 py-2 border dark:border-gray-600">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($barang as $item)
                                        <tr class="text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="px-4 py-2 border dark:border-gray-600">{{ $loop->iteration }}</td>
                                            {{-- Kolom gambar --}}
                                            <td class="px-4 py-2">
                                                @if ($item->gambar)
                                                    <img src="{{ $item->gambar }}" class="w-16 h-16 object-cover rounded">
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border dark:border-gray-600">{{ $item->nama }}</td>
                                            <td class="px-4 py-2 border dark:border-gray-600">{{ $item->kategori->nama }}</td>
                                            <td class="px-4 py-2 border dark:border-gray-600">{{ $item->deskripsi }}</td>
                                            <td class="px-4 py-2 border dark:border-gray-600">{{ $item->stok }} {{ $item->satuan->nama}}</td>
                                            <td class="px-4 py-2 border dark:border-gray-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 border dark:border-gray-600">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('barang.edit', $item->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                                    <button type="button" onclick="openDeleteModal({{ $item->id }})" class="text-red-500 hover:underline">Hapus</button>
                                                </div>
                                                <div id="deleteModal{{ $item->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
                                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm mx-auto">
                                                        <h3 class="text-lg font-semibold mb-4">Konfirmasi Penghapusan</h3>
                                                        <p class="mb-6">Apakah Anda yakin ingin menghapus barang ini?</p>
                                                        <div class="flex justify-end space-x-3">
                                                            <button onclick="closeDeleteModal({{ $item->id }})" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                                                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="inline" 
                                                                  onsubmit="showDeleteNotification('Kategori berhasil dihapus')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Belum ada data barang.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div id="gridView" class="hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse ($barang as $item)
                                <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow p-4 hover:shadow-md">
                                    <div class="text-center">
                                        @if ($item->gambar)
                                            <img src="{{ $item->gambar }}" class="w-32 h-32 mx-auto object-cover rounded">
                                        @else
                                            <div class="w-32 h-32 mx-auto flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded">
                                                <span class="text-gray-400 italic">Tidak ada gambar</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="mt-3 font-bold text-gray-900 dark:text-gray-100">{{ $item->nama }}</h3>
                                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                        <p>Kategori: {{ $item->kategori->nama }}</p>
                                        <p>Stok: {{ $item->stok }} {{ $item->satuan->nama}}</p>
                                        <p class="font-medium text-green-600 dark:text-green-400">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="mt-3 flex justify-between">
                                        <a href="{{ route('barang.edit', $item->id) }}" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">Edit</a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">
                                    Belum ada data barang.
                                </div>
                            @endforelse
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const tableViewBtn = document.getElementById('tableViewBtn');
                                const gridViewBtn = document.getElementById('gridViewBtn');
                                const tableView = document.getElementById('tableView');
                                const gridView = document.getElementById('gridView');
                                
                                // Load preference from localStorage if available
                                const viewPreference = localStorage.getItem('barangViewPreference') || 'table';
                                
                                function setActiveView(view) {
                                    if (view === 'grid') {
                                        tableView.classList.add('hidden');
                                        gridView.classList.remove('hidden');
                                        tableViewBtn.classList.remove('text-blue-700');
                                        tableViewBtn.classList.add('text-gray-900');
                                        gridViewBtn.classList.add('text-blue-700');
                                        gridViewBtn.classList.remove('text-gray-900');
                                        localStorage.setItem('barangViewPreference', 'grid');
                                    } else {
                                        gridView.classList.add('hidden');
                                        tableView.classList.remove('hidden');
                                        gridViewBtn.classList.remove('text-blue-700');
                                        gridViewBtn.classList.add('text-gray-900');
                                        tableViewBtn.classList.add('text-blue-700');
                                        tableViewBtn.classList.remove('text-gray-900');
                                        localStorage.setItem('barangViewPreference', 'table');
                                    }
                                }
                                
                                // Set initial view based on preference
                                setActiveView(viewPreference);
                                
                                tableViewBtn.addEventListener('click', () => setActiveView('table'));
                                gridViewBtn.addEventListener('click', () => setActiveView('grid'));
                            });
                            
                            function openDeleteModal(id) {
                                document.getElementById('deleteModal'+id).classList.remove('hidden');
                                document.getElementById('deleteModal'+id).classList.add('flex');
                            }
                            
                            function closeDeleteModal(id) {
                                document.getElementById('deleteModal'+id).classList.remove('flex');
                                document.getElementById('deleteModal'+id).classList.add('hidden');
                            }
                            
                            function showDeleteNotification(message) {
                                // Create notification element
                                const notification = document.createElement('div');
                                notification.className = 'fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg z-50 transform transition-all duration-500 translate-x-full';
                                notification.textContent = message;
                                document.body.appendChild(notification);
                                
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
                        </script>
                        
                        @if(session('success'))
                            <div id="successNotification" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg z-50 transform transition-all duration-500 translate-x-full">
                                {{ session('success') }}
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
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
            </div>
        </div>
    </div>
</x-app-layout>