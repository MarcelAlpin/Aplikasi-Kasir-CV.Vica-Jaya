<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = $request->input('search');
    
        $query = Kategori::query();
        
        // Apply search filter if search parameter exists
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        }
        
        // Get paginated results
        $kategori = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('master.kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {    
        $request->validate([
            'nama' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);

        // Buat ID Otomatis
        // Ambil ID terakhir dari database
        // dan tambahkan 1 untuk ID baru
        $lastKategori = Kategori::orderBy('id', 'desc')->first();
        if ($lastKategori) {
            $lastNumber = (int)substr($lastKategori->id, 2); // Ambil angka dari ID terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Generate ID baru
        $newId = 'KT' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        // Masukkan ID ke data request
        $data = $request->all();
        $data['id'] = $newId;

        // Simpan ke database
        Kategori::create($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
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
        $kategori = Kategori::findOrFail($id);
        return view('master.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
