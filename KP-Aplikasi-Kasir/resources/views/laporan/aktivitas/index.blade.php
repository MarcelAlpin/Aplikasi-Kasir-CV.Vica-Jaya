<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Aktivitas User
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <!-- Filter Form -->
            <form method="GET" class="mb-4 flex flex-wrap gap-4">
                <div>
                    <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="rounded border px-2 py-1 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="rounded border px-2 py-1 dark:bg-gray-700 dark:text-white">
                </div>
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                </div>
            </form>

            <!-- Tabel Aktivitas -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-200 border dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-2 text-left">Nama User</th>
                            <th class="p-2 text-left">Role</th>
                            <th class="p-2 text-left">Waktu</th>
                            <th class="p-2 text-left">Halaman / URL</th>
                            <th class="p-2 text-left">Keterangan</th>
                            <th class="p-2 text-left">IP</th>
                            <th class="p-2 text-left">Browser</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($aktivitas as $log)
                            <tr>
                                <td class="p-2">{{ $log->user->name ?? '-' }}</td>
                                <td class="p-2 capitalize">{{ $log->user->role ?? '-' }}</td>
                                <td class="p-2">{{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }}</td>
                                <td class="p-2">{{ $log->halaman ?? '-' }}</td>
                                <td class="p-2">{{ $log->keterangan ?? '-' }}</td>
                                <td class="p-2">{{ $log->ip_address ?? '-' }}</td>
                                <td class="p-2">
                                    <div class="max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 30) }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data aktivitas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Info & Pagination -->
            <div class="mt-4 flex justify-between items-center text-sm text-gray-600 dark:text-gray-300">
                <div>
                    Menampilkan {{ $aktivitas->count() }} dari total {{ $aktivitas->total() }} data
                </div>
                <div>
                    {{ $aktivitas->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
