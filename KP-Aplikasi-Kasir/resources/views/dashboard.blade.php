<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-2xl font-bold mb-4">Halo, {{ Auth::user()->name }}</h1>
                    <p class="mb-4">Role: <span class="font-semibold">{{ Auth::user()->role }}</span></p>

                    @if(Auth::user()->role === 'admin')
                        <div class="space-y-2">
                            <p class="text-green-600 dark:text-green-300">Fitur khusus <strong>Admin</strong></p>

                        </div>
                    @endif

                    @if(Auth::user()->role === 'kasir')
                        <div class="space-y-2">
                            <p class="text-blue-600 dark:text-blue-300">Fitur khusus <strong>Kasir</strong></p>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
