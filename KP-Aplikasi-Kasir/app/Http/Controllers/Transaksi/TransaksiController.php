<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;

class TransaksiController extends Controller
{
    //
    public function index()
    {
        //
        $transaksi = Transaksi::with('detail.barang')->latest()->get();
        return view('laporan.transaksi', compact('transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bon' => 'required',
            'atas_nama' => 'required',
            'total_bayar' => 'required|numeric',
            'status' => 'required',
            'order' => 'required',
            'items' => 'required|array',
        ]);

        // Simpan Transaksi Utama
        $transaksi = Transaksi::create([
            'no_bon' => $request->no_bon,
            'atas_nama' => $request->atas_nama,
            'status' => $request->status,
            'order' => $request->order,
            'total_bayar' => $request->total_bayar,
            'pajak' => $request->pajak ?? 0,
        ]);

        // Simpan Detail Transaksi
        foreach ($request->items as $item) {
            TransaksiDetail::create([
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
        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}
