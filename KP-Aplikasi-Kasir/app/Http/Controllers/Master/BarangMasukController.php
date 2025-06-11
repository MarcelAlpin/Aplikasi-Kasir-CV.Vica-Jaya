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
        $barang = Barang::all();
        return view('master.barangmasuk.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_masuk' => 'required|integer|min:1',
        ]);
        
        // Generate ID barang masuk (BM0000000001)
        $lastBarangMasuk = BarangMasuk::orderBy('id', 'desc')->first();
        if ($lastBarangMasuk) {
            $lastNumber = (int)substr($lastBarangMasuk->id, 2); // Ambil angka dari ID terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $barangMasukId = 'BM' . str_pad($newNumber, 10, '0', STR_PAD_LEFT);
        
        // Create record
        BarangMasuk::create([
            'id' => $barangMasukId,
            'barang_id' => $request->barang_id,
            'jumlah_masuk' => $request->jumlah_masuk,
        ]);
        
        // Update stock in Barang table
        $barang = Barang::find($request->barang_id);
        $barang->stok += $request->jumlah_masuk;
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
