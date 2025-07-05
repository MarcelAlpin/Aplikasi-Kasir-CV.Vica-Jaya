<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\LogAktivitas;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $search = $request->input('search');
        $pembayaran = $request->input('pembayaran');

        $transaksi = Transaksi::with(['detail.barang', 'user'])
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [
                    $from . ' 00:00:00',
                    $to . ' 23:59:59'
                ]);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('id', 'like', "%$search%");
            })
            ->when($pembayaran, function ($query) use ($pembayaran) {
                return $query->where('pembayaran', $pembayaran);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // agar parameter URL tetap terbawa saat navigasi halaman

        LogAktivitas::simpan('Mengakses halaman daftar transaksi');
        
        return view('laporan.transaksi.transaksi', compact('transaksi', 'from', 'to', 'search', 'pembayaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_bayar' => 'required|numeric',
            'pembayaran' => 'required|in:Cash,Qris,Debit,Kredit',
            'items' => 'required|array',
            'pajak' => 'numeric',
            'users_id' => 'required|exists:users,id',
        ]);

        // Generate Transaksi ID (TR0000000001)
        $lastTransaksi = Transaksi::orderBy('id', 'desc')->first();
        if ($lastTransaksi) {
            $lastNumber = (int)substr($lastTransaksi->id, 2); // Extract number from ID
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $transaksiId = 'TR' . str_pad($newNumber, 10, '0', STR_PAD_LEFT);

        // Simpan Transaksi Utama
        $transaksi = Transaksi::create([
            'id' => $transaksiId,
            'users_id' => $request->users_id,
            'pembayaran' => $request->pembayaran,
            'total_bayar' => $request->total_bayar,
            'pajak' => $request->pajak ?? 0,
        ]);

        // Simpan Detail Transaksi
        foreach ($request->items as $index => $item) {
            // Generate TransaksiDetail ID (TD0000000001)
            $lastDetail = TransaksiDetail::orderBy('id', 'desc')->first();
            if ($lastDetail) {
                $lastDetailNumber = (int)substr($lastDetail->id, 2);
                $newDetailNumber = $lastDetailNumber + 1;
            } else {
                $newDetailNumber = 1;
            }
            $detailId = 'TD' . str_pad($newDetailNumber, 10, '0', STR_PAD_LEFT);

            TransaksiDetail::create([
                'id' => $detailId,
                'transaksi_id' => $transaksi->id,
                'barang_id' => $item['barang_id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
            ]);
            
            // Update Stok Barang
            $barang = Barang::find($item['barang_id']);
            if ($barang) {
                $barang->stok -= $item['qty'];
                $barang->save();
            }
        }

        LogAktivitas::simpan("Transaksi baru telah dibuat dengan ID: {$transaksi->id} oleh pengguna: " . Auth::user()->name);
        
        return redirect()->route('kasir.index')
            ->with('success', "Transaksi telah berhasil disimpan");
    }

    // detail transaksi
    public function show(string $id)
    {
        LogAktivitas::simpan("Mengakses detail transaksi dengan ID: {$id}");

        $transaksi = Transaksi::with('detail.barang', 'user')->findOrFail($id);
        return view('laporan.transaksi.transaksi-detail', compact('transaksi'));
    }

    // Download Semua Riwayat Transaksi
    public function downloadAllPDF(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $search = $request->input('search');
        $pembayaran = $request->input('pembayaran');

        $transaksi = Transaksi::with(['detail.barang', 'user'])
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [
                    $from . ' 00:00:00',
                    $to . ' 23:59:59'
                ]);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('id', 'like', "%$search%");
            })
            ->when($pembayaran, function ($query) use ($pembayaran) {
                return $query->where('pembayaran', $pembayaran);
            })
            ->latest()
            ->get();

        LogAktivitas::simpan('Mengunduh semua riwayat transaksi sebagai PDF');
        
        $pdf = Pdf::loadView('laporan.transaksi.invoice-transaksi', compact('transaksi', 'from', 'to', 'search', 'pembayaran'));
        return $pdf->download('Riwayat_Transaksi.pdf');
    }

    // Download PDF Transaksi Detail
    public function downloadPDF($id)
    {
        $transaksi = Transaksi::with(['detail.barang', 'user'])->findOrFail($id);

        LogAktivitas::simpan("Mengunduh detail transaksi dengan ID: {$id} sebagai PDF");

        $pdf = Pdf::loadView('laporan.transaksi.invoice-detail', compact('transaksi'));
        return $pdf->download("Invoice_{$transaksi->id}.pdf");
    }
}