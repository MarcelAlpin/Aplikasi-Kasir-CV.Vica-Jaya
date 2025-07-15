<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kasir - Transaksi
        </h2>
    </x-slot>

    <div class="py-4 px-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Menu --}}
        <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg">
            <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-700 dark:text-white">Data Menu</h3>
            <input type="text" id="searchMenu" placeholder="Cari menu..." class="px-3 py-1 rounded border dark:bg-gray-700 dark:text-white" oninput="searchMenus()" />
            </div>

            <div id="noResultsMessage" class="text-center text-gray-500 dark:text-gray-400 py-8 hidden">
            <p>Menu yang dicari tidak ditemukan</p>
            </div>

            <script>
            function searchMenus() {
                const searchTerm = document.getElementById('searchMenu').value.toLowerCase();
                const menuItems = document.querySelectorAll('.grid.grid-cols-2 > div');
                const noResultsMessage = document.getElementById('noResultsMessage');
                let visibleCount = 0;
                
                menuItems.forEach(item => {
                const menuNameElement = item.querySelector('p.font-semibold');
                if (menuNameElement) {
                    const menuName = menuNameElement.textContent.toLowerCase();
                    if (menuName.includes(searchTerm)) {
                    item.style.display = 'block';
                    visibleCount++;
                    } else {
                    item.style.display = 'none';
                    }
                }
                });
                // Show no results message when no items match and search term is not empty
                if (visibleCount === 0 && searchTerm.trim() !== '') {
                noResultsMessage.classList.remove('hidden');
                } else {
                noResultsMessage.classList.add('hidden');
                }
            }
            </script>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[70vh] overflow-y-auto">
            @foreach ($menus as $menu)
                <div class="border p-2 rounded {{ $menu->stok > 0 ? 'hover:shadow cursor-pointer' : 'opacity-50 cursor-not-allowed bg-gray-100 dark:bg-gray-700' }} relative" 
                @if($menu->stok > 0)
                    onclick="addToCart('{{ $menu->id }}', '{{ addslashes($menu->nama) }}', {{ $menu->harga }})"
                @endif>
                    @if($menu->stok <= 0)
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10">
                            <div class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow-lg transform -rotate-12 animate-pulse">
                                🚫 STOK HABIS - Silahkan Restok
                            </div>
                            <div class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded-full shadow-lg ">
                                ⚠️Stok Habis
                            </div>
                        </div>
                    @endif
                    <img src="{{ $menu->gambar }}" alt="{{ $menu->nama }}" class="w-full h-32 object-contain rounded {{ $menu->stok <= 0 ? 'grayscale' : '' }}">
                    <div class="mt-2 text-center">
                        <p class="text-sm text-gray-700 dark:text-white font-semibold">{{ $menu->nama }}</p>
                        <p class="text-green-600 font-bold text-sm">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        @if($menu->stok > 0)
                        <p class="text-xs text-gray-500">(Tersedia: {{ $menu->stok }}x)</p>
                        @else
                        <p class="text-xs text-red-500 font-semibold">Tolong restok</p>
                        <p class="text-xs text-red-400">(Stok habis)</p>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        </div>

        {{-- Keranjang --}}
        <form action="{{ route('transaksi.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg">
            @csrf
            <h4 class="text-sm font-bold mb-2 text-gray-700 dark:text-white">List Keranjang</h4>
            <table class="w-full text-sm text-left mb-3">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="cartItems"></tbody>
            </table>

            <input type="hidden" name="total_bayar" id="totalBayarInput">
            <input type="hidden" name="pajak" value="0">

            <!-- Tampilan field sesuai denan lainnya, menampilkan nama kasir yang sedang login tetapi menahan nilai id ny untuk di submit -->
            <div class="mb-3">
                
                <input type="hidden" name="kasir" id="kasir" value="{{ auth()->user()->name }}" class="form-control w-full dark:bg-gray-700 dark:text-white" readonly>
                <input type="hidden" name="users_id" value="{{ auth()->user()->id }}">
            </div>

            <div class="mb-3">
                <label>Pembayaran</label>
                <select name="pembayaran" id="paymentOption" class="form-select w-full dark:bg-gray-700 dark:text-white" onchange="updateStatus()">
                    <option value="Cash">Cash</option>
                    <option value="QRis">QRis</option>
                    <option value="Debit">Debit</option>
                    <option value="Kredit">Kredit</option>
                </select>
            </div>

            <div class="mb-3 text-right">
                <strong>Total: <span id="totalBayarText">Rp0</span></strong>
            </div>
            <div class="mb-3 text-right">
                <strong>PPN (0% dibawah 2jt, 11% diatas 2jt): <span id="PPN">Rp0</span></strong>
            </div>
            <div class="mb-3 text-right">
                <strong>Total + PPN: <span id="totalFinalText">Rp0</span></strong>
            </div>

            <div class="text-right">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Simpan Transaksi
                </button>
            </div>
        </form>
        @if(session('success'))
            <div id="successNotification" 
                class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg z-50 transform transition-all duration-500 translate-x-full">
                {{ session('success') }}
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
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

   <script>
    let cart = [];
    // Store menu stock data for validation
    let menuStock = {};
    
    // Initialize stock data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($menus as $menu)
            menuStock['{{ $menu->id }}'] = {{ $menu->stok }};
        @endforeach
    });

    function addToCart(id, name, price) {
        // Convert id to string to ensure consistent comparison
        id = String(id); 
        let found = cart.find(item => String(item.barang_id) === id);
        let currentQty = found ? found.qty : 0;
        let availableStock = menuStock[id] || 0;
        
        // Check if adding one more item would exceed stock
        if (currentQty >= availableStock) {
            alert(`Stok tidak cukup! Stok tersedia: ${availableStock}`);
            return;
        }
        
        if (found) {
            found.qty += 1;
        } else {
            cart.push({ barang_id: id, name: name, qty: 1, harga: price });
        }
        renderCart();
    }

    function removeCartItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateQty(index, qty) {
        qty = parseInt(qty);
        let item = cart[index];
        let availableStock = menuStock[item.barang_id] || 0;
        
        // Validate quantity doesn't exceed stock
        if (qty > availableStock) {
            // Use a custom modal for better mobile/tablet experience
            showStockAlert(`Stok tidak cukup! Stok tersedia: ${availableStock}`);
            // Reset to previous valid quantity or max stock
            let validQty = Math.min(item.qty, availableStock);
            document.querySelector(`input[name="items[${index}][qty]"]`).value = validQty;
            cart[index].qty = validQty;
        } else if (qty <= 0) {
            // Remove item if quantity is 0 or negative
            removeCartItem(index);
            return;
        } else {
            cart[index].qty = qty;
        }
        renderCart();
    }

    function renderCart() {
        let cartTable = document.getElementById('cartItems');
        cartTable.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            let subtotal = item.qty * item.harga;
            total += subtotal;
            let availableStock = menuStock[item.barang_id] || 0;
            
            cartTable.innerHTML += `
                <tr>
                    <td>
                        ${item.name}
                        <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id}">
                        <input type="hidden" name="items[${index}][harga]" value="${item.harga}">
                        <small class="text-gray-500 block">Stok: ${availableStock}</small>
                    </td>
                    <td>
                        <input type="number" name="items[${index}][qty]" value="${item.qty}" 
                            min="1" max="${availableStock}"
                            onchange="updateQty(${index}, this.value)"
                            class="w-20 text-center border border-gray-300 rounded" />
                    </td>
                    <td>Rp${item.harga.toLocaleString()}</td>
                    <td><button type="button" onclick="removeCartItem(${index})" class="text-red-500">×</button></td>
                </tr>
            `;
        });

        // Calculate tax (11% of total)
        let taxAmount = total > 2000000 ? Math.round(total * 0.11) : 0;
        let finalTotal = total + taxAmount;

        // Update hidden inputs
        document.querySelector('input[name="pajak"]').value = taxAmount;
        document.getElementById('totalBayarInput').value = finalTotal; 

        document.getElementById('totalBayarText').innerText = 'Rp' + total.toLocaleString();
        document.getElementById('PPN').innerText = 'Rp' + taxAmount.toLocaleString();
        document.getElementById('totalFinalText').innerText = 'Rp' + finalTotal.toLocaleString();
    }
</script>
</x-app-layout>