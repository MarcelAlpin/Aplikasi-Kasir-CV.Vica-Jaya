<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agen;

class AgenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agen = Agen::latest()->get();
        return view('master.agen.index', compact('agen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.agen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'perushaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $lastAgen = Agen::orderBy('id', 'desc')->first();
        if ($lastAgen) {
            $lastNumber = (int)substr($lastAgen->id, 3); // Ambil angka dari ID terakhir
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newId = 'AG' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        $data = request()->all();
        $data['id'] = $newId;

        Agen::create($data);

        return redirect()->route('agen.index')->with('success', 'Agen berhasil ditambahkan');
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
        $agen = Agen::findOrFail($id);
        return view('master.agen.edit', compact('agen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'perushaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $agen = Agen::findOrFail($id);
        $agen->update($request->all());

        return redirect()->route('agen.index')->with('success', 'Agen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $agen = Agen::findOrFail($id);
        $agen->delete();

        return redirect()->route('agen.index')->with('success', 'Agen berhasil dihapus');
    }
}
