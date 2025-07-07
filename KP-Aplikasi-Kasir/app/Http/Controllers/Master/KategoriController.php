<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Helpers\LogAktivitas;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        
        $kategori = Kategori::query()
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama', 'like', '%' . $keyword . '%')
                             ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            })
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        LogAktivitas::simpan('Mengakses halaman daftar kategori');

        return view('master.kategori.index', compact('kategori', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        LogAktivitas::simpan('Mengakses halaman tambah kategori');
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

        LogAktivitas::simpan("Menambahkan kategori baru: {$data['nama']}");

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
        LogAktivitas::simpan("Mengakses halaman edit kategori: {$kategori->nama}");
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
        LogAktivitas::simpan("Memperbarui kategori: {$kategori->nama}");
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        LogAktivitas::simpan("Menghapus kategori: {$kategori->nama}");

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
