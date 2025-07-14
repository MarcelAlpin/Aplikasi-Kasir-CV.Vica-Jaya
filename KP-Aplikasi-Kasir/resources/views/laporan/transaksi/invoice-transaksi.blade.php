<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Transaksi</h1>
    <div class="periode">
        Periode: <strong>{{ $from ?? '-' }}</strong> s/d <strong>{{ $to ?? '-' }}</strong>
    </div>
    <div class="total-summary" style="margin-bottom: 20px; text-align: right;">
        <strong>Total Transaksi: {{ $transaksi->count() }}</strong> |
        <strong>Total Pendapatan: Rp{{ number_format($transaksi->sum('total_bayar'), 0, ',', '.') }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">No Transaksi</th>
                <th style="width: 25%;">Nama Kasir</th>
                <th style="width: 20%;">Total Bayar</th>
                <th style="width: 20%;">Waktu</th>
                <th style="width: 20%;">Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td class="text-right">Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</td>
                    <td>{{ $item->pembayaran }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y H:i') }}
    </div>
</body>
</html>
