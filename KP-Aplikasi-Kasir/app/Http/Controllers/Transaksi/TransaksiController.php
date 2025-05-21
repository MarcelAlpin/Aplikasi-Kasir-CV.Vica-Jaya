<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    //
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'nama' => $request->input('nama'),
                'total' => $request->input('total'),
            ]);

            foreach ($request->items as $item) {
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['jumlah'] * $item['harga'],
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}
