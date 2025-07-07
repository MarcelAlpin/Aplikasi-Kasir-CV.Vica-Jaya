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
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow col-span-1 sm:col-span-2 lg:col-span-4">
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
                <!-- Kolom 2 - Stok Kosong -->
                </div>
                
                <!-- Stok Cards - Moved below user card -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Stok Habis -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-3 text-red-600 dark:text-red-400 animate-pulse" style="animation-delay: 5s;">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Stok Habis
                        </h3>
                        
                        @php
                            $stokKosong = \App\Models\Barang::where('stok', 0)->get();
                        @endphp
                        
                        @if($stokKosong->count() > 0)
                            <div class="space-y-2">
                                @foreach($stokKosong->take(5) as $item)
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg flex items-center">
                                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                                        <span class="flex-1">{{ $item->nama }} ({{ $item->satuan->nama ?? 'N/A' }})</span>
                                        <span class="bg-red-200 dark:bg-red-800 px-2 py-1 rounded-lg text-xs font-medium">Habis</span>
                                    </div>
                                @endforeach
                                @if($stokKosong->count() > 5)
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-2 rounded-lg text-center text-sm">
                                        <i class="fas fa-ellipsis-h mr-2"></i>{{ $stokKosong->count() - 5 }} item lainnya juga habis
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border-l-4 border-green-500">
                                <p class="text-green-800 dark:text-green-300 text-center">
                                    <i class="fas fa-check-circle mr-2"></i>Tidak ada stok kosong
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Stok Menipis -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-3 text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-exclamation-circle mr-2"></i>Stok Menipis
                        </h3>
                        
                        @php
                            $stokSedikit = \App\Models\Barang::where('stok', '>', 0)->where('stok', '<', 5)->get();
                        @endphp
                        
                        @if($stokSedikit->count() > 0)
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded border-l-4 border-yellow-500">
                                <h4 class="font-medium text-yellow-800 dark:text-yellow-300 mb-2">{{ $stokSedikit->count() }} item</h4>
                                <div class="text-sm text-yellow-700 dark:text-yellow-400 max-h-20 overflow-y-auto">
                                    @foreach($stokSedikit->take(9) as $item)
                                        <p>• {{ $item->nama }} ({{ $item->stok }} {{ $item->satuan->nama ?? 'N/A' }} tersisa)</p>
                                    @endforeach
                                    @if($stokSedikit->count() > 9)
                                        <p class="italic">dan {{ $stokSedikit->count() - 9 }} lainnya...</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded border-l-4 border-green-500">
                                <p class="text-green-800 dark:text-green-300 text-center">
                                    <i class="fas fa-check-circle mr-2"></i>Stok masih aman
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Notification for Low/Empty Stock -->
                @if($stokKosong->count() > 0 || $stokSedikit->count() > 0)
                    <div class="mt-6 mb-4">
                        <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone-alt text-orange-500 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-orange-800 dark:text-orange-200">
                                        Tindakan Diperlukan
                                    </h3>
                                    <div class="mt-2 text-sm text-orange-700 dark:text-orange-300">
                                        @if($stokKosong->count() > 0 && $stokSedikit->count() > 0)
                                            <p><i class="fas fa-exclamation-triangle mr-1"></i>Ada {{ $stokKosong->count() }} item habis dan {{ $stokSedikit->count() }} item menipis. Segera hubungi Agen dan konfirmasi dengan Manager.</p>
                                        @elseif($stokKosong->count() > 0)
                                            <p><i class="fas fa-exclamation-triangle mr-1"></i>Ada {{ $stokKosong->count() }} item yang habis. Segera hubungi Agen dan konfirmasi dengan Manager untuk restok.</p>
                                        @else
                                            <p><i class="fas fa-exclamation-triangle mr-1"></i>Ada {{ $stokSedikit->count() }} item yang menipis. Pertimbangkan untuk menghubungi Agen atau koordinasi dengan Manager/Pemilik.</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Agen Contact Information -->
                                    @php
                                        $Agens = \App\Models\Agen::all();
                                    @endphp
                                    
                                    @if($Agens->count() > 0)
                                        <div class="mt-3 pt-3 border-t border-orange-200 dark:border-orange-700">
                                            <h4 class="text-sm font-medium text-orange-800 dark:text-orange-200 mb-2">
                                                <i class="fas fa-address-book mr-1"></i>Kontak Agen Terkait:
                                            </h4>
                                            <div class="space-y-1">
                                                @php
                                                    $relatedAgens = collect();
                                                    foreach($stokKosong as $item) {
                                                        if($item->agen_id && !$relatedAgens->contains('id', $item->agen_id)) {
                                                            $relatedAgens->push($item->agen);
                                                        }
                                                    }
                                                    foreach($stokSedikit as $item) {
                                                        if($item->agen_id && !$relatedAgens->contains('id', $item->agen_id)) {
                                                            $relatedAgens->push($item->agen);
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($relatedAgens->count() > 0)
                                                    @foreach($relatedAgens as $Agen)
                                                        <div class="flex items-center text-xs text-orange-600 dark:text-orange-300">
                                                            <span class="font-medium">{{ $Agen->nama }}:</span>
                                                            <a href="tel:{{ $Agen->no_telepon }}" class="ml-2 hover:underline">
                                                                <i class="fas fa-phone mr-1"></i>{{ $Agen->no_telepon }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-xs text-orange-600 dark:text-orange-300">
                                                        <i class="fas fa-info-circle mr-1"></i>Tidak ada Agen terkait dengan barang yang kosong/menipis
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            </div>
            </div>
        </div>
    </div>

</x-app-layout>
