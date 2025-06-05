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
                    <div class="border p-2 rounded hover:shadow cursor-pointer" onclick="addToCart({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})">
                        <img src="{{ $menu->gambar }}" alt="{{ $menu->nama }}" class="w-full h-32 object-contain rounded">
                        <div class="mt-2 text-center">
                            <p class="text-sm text-gray-700 dark:text-white font-semibold">{{ $menu->nama }}</p>
                            <p class="text-green-600 font-bold text-sm">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">(Tersedia: {{ $menu->stok }}x)</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Keranjang --}}
        <form action="{{ route('transaksi.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg">
            @csrf

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No Nota</label>
                <input type="text" name="no_bon" value="B{{ date('His') }}" readonly class="form-input w-full dark:bg-gray-700 dark:text-white text-gray-500" />
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Atas Nama</label>
                <input type="text" name="atas_nama" placeholder="Nama untuk nota" value="{{ Auth::user()->name }}" readonly 
                    class="form-input w-full dark:bg-gray-700 dark:text-white text-gray-500" />
            </div>

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

            <div class="mb-3">
                <label>Order</label>
                <select name="status" id="orderType" class="form-select w-full dark:bg-gray-700 dark:text-white" onchange="updatePaymentOptions()">
                    <option value="Delivery">Delivery</option>
                    <option value="Ditempat" selected>Ditempat</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Pembayaran</label>
                <select name="order" id="paymentOption" class="form-select w-full dark:bg-gray-700 dark:text-white">
                    <option value="Cash" selected>Cash</option>
                </select>
            </div>

            <script>
                function updatePaymentOptions() {
                    const orderType = document.getElementById('orderType').value;
                    const paymentSelect = document.getElementById('paymentOption');
                    
                    // Clear existing options
                    paymentSelect.innerHTML = '';
                    
                    if (orderType === 'Delivery') {
                        // For delivery, show only COD
                        paymentSelect.add(new Option('COD', 'COD'));
                    } else {
                        // For "Ditempat", show Cash and QRIS
                        paymentSelect.add(new Option('Cash', 'Cash', true, true));
                        paymentSelect.add(new Option('QRIS', 'QRIS'));
                    }
                }
                
                // Initialize payment options when page loads
                document.addEventListener('DOMContentLoaded', updatePaymentOptions);
            </script>

            <div class="mb-3 text-right">
                <strong>Total: <span id="totalBayarText">Rp0</span></strong>
            </div>
              <div class="mb-3 text-right">
            <strong>PPN (11%): <span id="PPN">Rp0</span></strong>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pembayaran</label>
                <select name="status_pembayaran" class="form-select w-full dark:bg-gray-700 dark:text-white">
                    <option value="Lunas" selected>Lunas</option>
                    <option value="Belum Bayar">Belum Bayar</option>
                </select>
            </div>

            <div class="text-right">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <script>
        let cart = [];

        function addToCart(id, name, price) {
            let found = cart.find(item => item.barang_id === id);
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
            cart[index].qty = parseInt(qty);
            renderCart();
        }

        function renderCart() {
            let cartTable = document.getElementById('cartItems');
            cartTable.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                let subtotal = item.qty * item.harga;
                total += subtotal;
            });

            // Apply 11% tax if total exceeds 2,000,000
            let finalTotal = total;
            let taxAmount = 0;
            if (total > 2000000) {
                taxAmount = total * 0.11;
                finalTotal = total + taxAmount;
            }

            // Update hidden tax input
            document.querySelector('input[name="pajak"]').value = taxAmount;

            cart.forEach((item, index) => {
                cartTable.innerHTML += `
                    <tr>
                        <td>
                            ${item.name}
                            <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id}">
                            <input type="hidden" name="items[${index}][harga]" value="${item.harga}">
                        </td>
                        <td>
                            <input type="number" name="items[${index}][qty]" value="${item.qty}" min="1"
                                   onchange="updateQty(${index}, this.value)"
                                   class="w-20 text-center border border-gray-300 rounded" />
                        </td>
                        <td>Rp${item.harga.toLocaleString()}</td>
                        <td><button type="button" onclick="removeCartItem(${index})" class="text-red-500">x</button></td>
                    </tr>
                `;
            });

            document.getElementById('totalBayarText').innerText = 'Rp' + total.toLocaleString();
            document.getElementById('PPN').innerText = 'Rp' + taxAmount.toLocaleString();
            document.getElementById('totalBayarInput').value = total;
        }
    </script>
</x-app-layout>