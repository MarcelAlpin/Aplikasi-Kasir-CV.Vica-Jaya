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
        'no_bon' => $request->no_bon,
        'atas_nama' => $request->atas_nama,
        'status' => $request->status,
        'order' => $request->order,
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
    return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}
