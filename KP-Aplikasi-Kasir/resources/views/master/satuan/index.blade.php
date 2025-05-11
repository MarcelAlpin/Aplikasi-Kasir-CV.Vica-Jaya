<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Satuan') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 md:px-6 lg:px-4">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                    <!-- Kolom 1 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Daftar Satuan</h3>
                            <a href="{{ route('satuan.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                + Tambah Satuan
                            </a>
                        </div>
                        <table class="w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                                    <th class="px-4 py-2 border dark:border-gray-600">#</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">Nama Satuan</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">Deskripsi</th>
                                    <th class="px-4 py-2 border dark:border-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($satuan as $satuan)
                                    <tr class="text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $satuan->nama }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">{{ $satuan->deskripsi }}</td>
                                        <td class="px-4 py-2 border dark:border-gray-600">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('satuan.edit', $satuan->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                                <button type="button" onclick="openDeleteModal({{ $satuan->id }})" class="text-red-500 hover:underline">Hapus</button>
                                            </div>

                                            <!-- Delete confirmation modal (will be positioned fixed in middle of screen) -->
                                            <div id="deleteModal{{ $satuan->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
                                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm mx-auto">
                                                    <h3 class="text-lg font-semibold mb-4">Konfirmasi Penghapusan</h3>
                                                    <p class="mb-6">Apakah Anda yakin ingin menghapus satuan ini?</p>
                                                    <div class="flex justify-end space-x-3">
                                                        <button onclick="closeDeleteModal({{ $satuan->id }})" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                                                        <form action="{{ route('satuan.destroy', $satuan->id) }}" method="POST" class="inline" 
                                                              onsubmit="showDeleteNotification('Satuan berhasil dihapus')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Hapus</button>
                                                        </form>
                                                    </div>
                                                    <script>
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
                                                </div>
                                            </div>
                                            <script>
                                                function openDeleteModal(id) {
                                                    document.getElementById('deleteModal'+id).classList.remove('hidden');
                                                    document.getElementById('deleteModal'+id).classList.add('flex');
                                                }
                                                
                                                function closeDeleteModal(id) {
                                                    document.getElementById('deleteModal'+id).classList.remove('flex');
                                                    document.getElementById('deleteModal'+id).classList.add('hidden');
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Belum ada data satuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>