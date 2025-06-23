<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4 md:px-6 lg:px-4">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Kolom 1 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">Halo, {{ Auth::user()->name }}</h1>
                        <p>Role: <span class="font-semibold">{{ Auth::user()->role }}</span></p>
                        <!-- @if(Auth::user()->role === 'admin')
                            <div class="space-y-2">
                                <p class="text-green-600 dark:text-green-300">Fitur khusus <strong>Admin</strong> telah aktif</p>
                            </div>
                        @elseif(Auth::user()->role === 'kasir')
                            <div class="space-y-2">
                                <p class="text-blue-600 dark:text-blue-300">Fitur khusus <strong>Kasir</strong></p>
                            </div>
                        @endif -->
                    </div>

                    <!-- Kolom 2 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">Rp. {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('d F Y') }}</p>
                        <p class="font-medium">Penghasilan hari ini</p>
                    </div>

                    <!-- Kolom 3 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">{{ $todayTransactions ?? 0 }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('d F Y') }}</p>
                        <p class="font-medium">Transaksi hari ini</p>
                    </div>

                    <!-- Kolom 4 -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h1 class="text-xl font-bold mb-2">{{ $todayItemsSold ?? 0 }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ date('d F Y') }}</p>
                        <p class="font-medium">Item Terjual hari ini</p>
                    </div>

                    <!-- Transaction Graph -->
                    <div class="col-span-1 sm:col-span-2 lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Monthly Transactions</h3>
                        <canvas id="transactionChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Revenue Graph -->
                    <div class="col-span-1 sm:col-span-2 lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Monthly Revenue</h3>
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data from controller
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const transactionData = @json($monthlyTransactions ?? []);
        const revenueData = @json($monthlyRevenue ?? []);

        // Transaction Chart
        new Chart(document.getElementById('transactionChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Transactions',
                    data: transactionData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: revenueData,
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
            </div>
        </div>
    </div>
</x-app-layout>
