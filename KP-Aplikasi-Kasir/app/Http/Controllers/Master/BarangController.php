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
        //
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

        // Generate ID baru dengan panjang total 12 karakter
        $newId = 'BR' . str_pad($newNumber, 10, '0', STR_PAD_LEFT);

        // Masukkan ID ke data request
        $data = $request->all();
        $data['id'] = $newId;

        // Set stok to 0 by default
        $data['stok'] = 0;

        // Handle file upload, image to base64
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $mimeType = $image->getMimeType();
            $data['gambar'] = "data:$mimeType;base64,$imageData";
        }

        Barang::create($data);
        
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
        //
        $request->validate([
            'nama' => 'required|max:100',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok' => 'required|integer',
            'harga' => 'required|integer',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ]);
        
        $barang = Barang::findOrFail($id);
        $data = $request->except('gambar');

        // Handle file upload, image to base64
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $mimeType = $image->getMimeType();
            $data['gambar'] = "data:$mimeType;base64,$imageData";
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diubah.');
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
