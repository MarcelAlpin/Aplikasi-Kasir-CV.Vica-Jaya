<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Hak Akses Kasir
        </h2>
    </x-slot>

    <div class="p-6" x-data="{ showModal: false, deleteUrl: '' }">
        <!-- Form Pencarian -->
        <form method="GET" class="mb-4 flex flex-col sm:flex-row sm:items-center gap-2">
            <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-4 py-2 text-sm w-full sm:w-auto">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                Cari
            </button>
        </form>

        <!-- Form Tambah User -->
        <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200 mb-2">Tambah Akun Kasir</h3>
            <form method="POST" action="{{ route('hakakses.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @csrf
                <input type="text" name="name" placeholder="Nama" class="rounded border px-3 py-2 text-sm dark:bg-gray-700 dark:text-white" required>
                <input type="email" name="email" placeholder="Email" class="rounded border px-3 py-2 text-sm dark:bg-gray-700 dark:text-white" required>
                <input type="password" name="password" placeholder="Password" class="rounded border px-3 py-2 text-sm dark:bg-gray-700 dark:text-white" required>
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Tambah</button>
            </form>
        </div>

        <!-- Kasir Aktif -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-6">
            <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Kasir Aktif</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-600">
                        @forelse ($kasirAktif as $k)
                            <tr>
                                <td class="px-4 py-2">
                                    <form action="{{ route('hakakses.update', $k->id) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $k->name }}" class="w-full border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                                </td>
                                <td class="px-4 py-2">{{ $k->email }}</td>
                                <td class="px-4 py-2 flex flex-col sm:flex-row gap-2">
                                        <input type="password" name="password" placeholder="Ganti Password" class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-white">
                                        <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Update</button>
                                    </form>
                                    <button type="button"
                                        class="text-red-600 hover:text-red-800 text-sm"
                                        @click="showModal = true; deleteUrl = '{{ route('hakakses.destroy', $k->id) }}';"
                                    >
                                        Nonaktifkan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500">Tidak ada kasir aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $kasirAktif->withQueryString()->links() }}
            </div>
        </div>

        <!-- Kasir Nonaktif -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Kasir Nonaktif</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-600">
                        @forelse ($kasirNonaktif as $k)
                            <tr>
                                <td class="px-4 py-2">{{ $k->name }}</td>
                                <td class="px-4 py-2">{{ $k->email }}</td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('hakakses.restore', $k->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Pulihkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500">Tidak ada kasir nonaktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $kasirNonaktif->withQueryString()->links() }}
            </div>
        </div>

        <!-- Modal Konfirmasi Nonaktifkan -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-sm w-full">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Konfirmasi Nonaktifkan</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Yakin ingin menonaktifkan akun ini?</p>
                <div class="flex justify-end gap-3">
                    <button @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Batal</button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Nonaktifkan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifikasi -->
        @if(session('success'))
            <div id="successNotification" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg z-50 transition transform translate-x-full">
                {{ session('success') }}
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const notif = document.getElementById('successNotification');
                    if (notif) {
                        setTimeout(() => notif.classList.remove('translate-x-full'), 100);
                        setTimeout(() => notif.classList.add('translate-x-full'), 3000);
                        setTimeout(() => notif.remove(), 3500);
                    }
                });
            </script>
        @endif
    </div>
</x-app-layout>
