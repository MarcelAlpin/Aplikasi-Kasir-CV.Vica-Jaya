<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Satuan;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $keyword = $request->query('search');
        
        $satuan = Satuan::query()
            ->when($keyword, function ($query, $keyword) {
            return $query->where('nama', 'like', '%' . $keyword . '%')
                     ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            })
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('master.satuan.index', compact('satuan', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('master.satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama' =>'required|max:100',
            'deskripsi' => 'nullable',
        ]);

        // Buat ID Otomatis
        $lastSatuan = Satuan::orderBy('id', 'desc')->first();
        if ($lastSatuan) {
            $lastNumber = (int)substr($lastSatuanr->id, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'ST' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['id'] = $newId;

        Satuan::create($data);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambah.');
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
        $satuan = Satuan::findOrFail($id);
        return view('master.satuan.edit', compact('satuan'));
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
        ]);

        $satuan = Satuan::findOrFail($id);
        $satuan->update($request->all());

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
