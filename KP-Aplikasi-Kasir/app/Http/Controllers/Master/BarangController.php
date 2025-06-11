<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $barang = Barang::latest()->get();
        return view('master.barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('master.barang.create', [
            'kategori' => Kategori::all(),
            'satuan' => Satuan::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|integer',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ]);

        $lastBarang = Barang::orderBy('id', 'desc')->first();
        if ($lastBarang) {
            $lastNumber = (int)substr($lastBarang->id, 2); // Ambil angka dari ID terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $barangId = 'BR' . str_pad($newNumber, 10, '0', STR_PAD_LEFT);
        
        // Create barang with stok set to 0 by default
        $barang = new Barang();
        $barang->id = $barangId;
        $barang->nama = $request->nama;
        $barang->deskripsi = $request->deskripsi;
        $barang->stok = 0; // Set default stok to 0
        $barang->harga = $request->harga;
        $barang->kategori_id = $request->kategori_id;
        $barang->satuan_id = $request->satuan_id;
        
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $filename);
            $barang->gambar = 'uploads/barang/' . $filename;
        }
        
        $barang->save();
        
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
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
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        $satuan = Satuan::all();
        return view('master.barang.edit', compact('barang', 'kategori', 'satuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|integer',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ]);
        
        $barang = Barang::findOrFail($id);
        $barang->nama = $request->nama;
        $barang->deskripsi = $request->deskripsi;
        $barang->harga = $request->harga;
        $barang->kategori_id = $request->kategori_id;
        $barang->satuan_id = $request->satuan_id;
        
        if ($request->hasFile('gambar')) {
            // Delete old image if it exists
            if ($barang->gambar && file_exists(public_path($barang->gambar))) {
                unlink(public_path($barang->gambar));
            }
            
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $filename);
            $barang->gambar = 'uploads/barang/' . $filename;
        }
        
        $barang->save();
        
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
