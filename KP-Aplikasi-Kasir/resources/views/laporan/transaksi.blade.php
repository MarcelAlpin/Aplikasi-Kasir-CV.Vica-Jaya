<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>
<div class="py-4 px-6">
        <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg">
            {{-- Export Buttons --}}
            <div class="mb-4 flex flex-wrap gap-2">
                <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </button>
                <button onclick="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
            </div>
    <div class="py-4 px-6">
        <div class="bg-white dark:bg-gray-800 p-4 shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">No Transaksi</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Total Bayar</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-600 text-gray-700 dark:text-gray-100">
                    @foreach($transaksi as $trx)
                        <tr>
                            <td class="px-4 py-2">{{ $trx->id }}</td>
                            <td class="px-4 py-2">{{ $trx->atas_nama }}</td>
                            <td class="px-4 py-2">Rp{{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $trx->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <ul class="list-disc pl-5">
                                    @foreach($trx->detail as $item)
                                        <li>
                                            {{ $item->barang->nama }} x{{ $item->qty }}
                                            (Rp{{ number_format($item->harga, 0, ',', '.') }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Include CDN Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation

            // Add title
            doc.setFontSize(16);
            doc.text('Laporan Transaksi CV. Vica Jaya', 14, 15);
            
            // Add date
            doc.setFontSize(10);
            doc.text('Tanggal Export: ' + new Date().toLocaleDateString('id-ID'), 14, 25);

            // Prepare table data
            const tableData = [];
            @foreach($transaksi as $trx)
                const details = [
                    @foreach($trx->detail as $item)
                        '{{ $item->barang->nama }} x{{ $item->qty }} (Rp{{ number_format($item->harga, 0, ",", ".") }})'{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ].join(', ');
                
                tableData.push([
                    '{{ $trx->id }}',
                    '{{ $trx->no_bon }}',
                    '{{ $trx->atas_nama }}',
                    '{{ ucfirst($trx->status) }}',
                    '{{ ucfirst(str_replace("_", " ", $trx->order)) }}',
                    'Rp{{ number_format($trx->total_bayar, 0, ",", ".") }}',
                    'Rp{{ number_format($trx->pajak, 0, ",", ".") }}',
                    '{{ $trx->created_at->setTimezone("Asia/Jakarta")->format("d M Y H:i") }}',
                    details
                ]);
            @endforeach

            // Create table
            doc.autoTable({
                head: [['No Transaksi', 'No Bon', 'Nama', 'Status', 'Order', 'Total', 'Pajak', 'Waktu', 'Detail']],
                body: tableData,
                startY: 35,
                styles: { fontSize: 8 },
                headStyles: { fillColor: [66, 139, 202] },
                columnStyles: {
                    8: { cellWidth: 60 } // Detail column wider
                }
            });

            // Save PDF
            doc.save('Laporan_Transaksi_' + new Date().toISOString().slice(0, 10) + '.pdf');
        }

        // Export to Excel
        function exportToExcel() {
            // Prepare data for Excel
            const data = [
                ['Laporan Transaksi CV. Vica Jaya'],
                ['Tanggal Export: ' + new Date().toLocaleDateString('id-ID')],
                [], // Empty row
                ['No Transaksi', 'No Bon', 'Nama Pelanggan', 'Status', 'Order', 'Total Bayar', 'Pajak', 'Waktu', 'Detail Barang']
            ];

            @foreach($transaksi as $trx)
                const details{{ $loop->index }} = [
                    @foreach($trx->detail as $item)
                        '{{ $item->barang->nama }} x{{ $item->qty }} (Rp{{ number_format($item->harga, 0, ",", ".") }})'{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                ].join('');
                
                data.push([
                    '{{ $trx->id }}',
                    '{{ $trx->no_bon }}',
                    '{{ $trx->atas_nama }}',
                    '{{ ucfirst($trx->status) }}',
                    '{{ ucfirst(str_replace("_", " ", $trx->order)) }}',
                    {{ $trx->total_bayar }},
                    {{ $trx->pajak }},
                    '{{ $trx->created_at->setTimezone("Asia/Jakarta")->format("d M Y H:i") }}',
                    details{{ $loop->index }}
                ]);
            @endforeach

            // Create workbook and worksheet
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            // Set column widths
            ws['!cols'] = [
                { wch: 12 }, // No Transaksi
                { wch: 20 }, // No Bon
                { wch: 20 }, // Nama
                { wch: 12 }, // Status
                { wch: 15 }, // Order
                { wch: 15 }, // Total
                { wch: 12 }, // Pajak
                { wch: 18 }, // Waktu
                { wch: 50 }  // Detail
            ];

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Laporan Transaksi');

            // Save Excel file
            XLSX.writeFile(wb, 'Laporan_Transaksi_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        }
    </script>
</x-app-layout>
