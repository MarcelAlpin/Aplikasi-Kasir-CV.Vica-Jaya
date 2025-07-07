<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Transaksi - {{ $transaksi->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header,
        .footer {
            text-align: center;
        }
        .header h2 {
            margin: 0;
        }
        .info, .items {
            margin-top: 20px;
            width: 100%;
        }
        .info td {
            padding: 4px 0;
        }
        .items th,
        .items td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        .items {
            border-collapse: collapse;
            margin-top: 10px;
        }
        .right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .note {
            font-size: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h2>CV. Vica Jaya</h2>
        <p>Palembang, Indonesia</p>
        <p></p>
    </div>

    <!-- Info -->
    <table class="info">
        <tr>
            <td><strong>Invoice #:</strong> {{ $transaksi->id }}</td>
            <td class="right"><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Kasir:</strong> {{ $transaksi->user->name }}</td>
            <td class="right"><strong>Metode Pembayaran:</strong> {{ $transaksi->pembayaran }}</td>
        </tr>
    </table>

    <!-- Item Table -->
    <table class="items">
        <thead>
        <tr>
            <th>#</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @php $total = 0; @endphp
        @foreach ($transaksi->detail as $index => $item)
            @php
                $subtotal = $item->qty * $item->harga;
                $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4" class="right bold">Sub Total</td>
            <td>Rp{{ number_format($transaksi->total_bayar - $transaksi->pajak, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4" class="right bold">Pajak </td>
            <td>Rp{{ number_format($transaksi->pajak, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4" class="right bold">Total</td>
            <td><strong>Rp{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</strong></td>
        </tr>
        </tfoot>
    </table>

    <!-- Note -->
    <div class="note">
        <p>Terima kasih telah berbelanja di CV. Vica Jaya</p>
        <p>* Simpan invoice ini untuk keperluan garansi atau retur barang.</p>
    </div>
</div>
</body>
</html>
