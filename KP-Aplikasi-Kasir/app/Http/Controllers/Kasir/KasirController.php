<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Barang::where('stok', '>', 0)->get(); // Only show items with stock
        return view('kasir.index', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'atas_nama' => 'required|string|max:255',
            'status' => 'required|in:lunas,belum_bayar',
            'order' => 'required|in:ditempat,dibawa_pulang',
            'total_bayar' => 'required|integer|min:0',
            'pajak' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            // Generate nomor bon
            $noBon = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Validate stock availability before creating transaction
            foreach ($request->items as $item) {
                $barang = Barang::find($item['barang_id']);
                
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$item['barang_id']} tidak ditemukan!");
                }
                
                if ($barang->stok < $item['qty']) {
                    throw new \Exception("Stok barang '{$barang->nama}' tidak mencukupi! Tersedia: {$barang->stok}, Diminta: {$item['qty']}");
                }
            }

            // Create main transaction record
            $transaksi = Transaksi::create([
                'no_bon' => $noBon,
                'atas_nama' => $request->atas_nama,
                'status' => $request->status,
                'order' => $request->order,
                'total_bayar' => $request->total_bayar,
                'pajak' => $request->pajak,
            ]);

            // Process each item: create detail and reduce stock
            foreach ($request->items as $item) {
                $barang = Barang::find($item['barang_id']);
                
                // Create transaction detail
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                ]);

                // Reduce stock - CRITICAL PART
                $oldStock = $barang->stok;
                $barang->stok = $barang->stok - $item['qty'];
                $barang->save();

                // Log for debugging
                Log::info("Stock updated for {$barang->nama}: {$oldStock} -> {$barang->stok} (reduced by {$item['qty']})");
                
                // Alternative method using decrement (choose one):
                // $barang->decrement('stok', $item['qty']);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('kasir.index')->with('success', "Transaksi berhasil! No. Bon: {$noBon}. Stok barang telah diperbarui.");

        } catch (\Exception $e) {
            // Rollback if any error occurs
            DB::rollback();
            
            Log::error('Transaction failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Transaksi gagal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}