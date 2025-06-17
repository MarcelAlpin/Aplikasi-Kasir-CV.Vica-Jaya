<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Barang;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangMasuk = BarangMasuk::with('barang')->latest()->get();
        return view('master.barangmasuk.index', compact('barangMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.barangmasuk.create', [
            'barang' => Barang::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_masuk' => 'required|integer|min:1',
            // Other validations...
        ]);
        
        // Generate ID barang masuk
        $lastBarangMasuk = BarangMasuk::orderBy('id', 'desc')->first();
        if ($lastBarangMasuk) {
            $lastNumber = (int)substr($lastBarangMasuk->id, 2); // Ambil angka dari ID terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $barangMasukId = 'BM' . str_pad($newNumber, 10, '0', STR_PAD_LEFT);
        
        BarangMasuk::create([
            'id' => $barangMasukId,
            'barang_id' => $request->barang_id,
            'jumlah_masuk' => $request->jumlah_masuk,
            // Other fields...
        ]);
        
        $barang = Barang::find($request->barang_id);
        $barang->stok += $request->jumlah_masuk; // Increment stock
        $barang->save();
        
        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil dicatat dengan ID: ' . $barangMasukId);
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
        return view('master.barangmasuk.edit', [
            'barang'  => Barang::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_masuk' => 'required|integer|min:1',
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);
        
        // kalkulasi selisih stok
        $oldJumlahMasuk = $barangMasuk->jumlah_masuk;
        $newJumlahMasuk = $request->jumlah_masuk;
        $stockDifference = $newJumlahMasuk - $oldJumlahMasuk;
        
        // Cek apakah barang_id berubah
        $oldBarangId = $barangMasuk->barang_id;
        $newBarangId = $request->barang_id;
        
        // Update barang masuk record
        $barangMasuk->update([
            'barang_id' => $newBarangId,
            'jumlah_masuk' => $newJumlahMasuk,
        ]);
        
        // perbarui stok barang
        if ($oldBarangId === $newBarangId) {
            // Same product, just update the stock difference
            $barang = Barang::find($newBarangId);
            $barang->stok += $stockDifference;
            $barang->save();
        } else {
            // perberubahan barang, update stok barang lama dan baru
            $oldBarang = Barang::find($oldBarangId);
            $oldBarang->stok -= $oldJumlahMasuk;
            $oldBarang->save();
            
            $newBarang = Barang::find($newBarangId);
            $newBarang->stok += $newJumlahMasuk;
            $newBarang->save();
        }

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk -> delete();

        return redirect()->route('barangmasuk.index')->with('success', 'Barang Masuk berhasil dihapus.');
    }
}
