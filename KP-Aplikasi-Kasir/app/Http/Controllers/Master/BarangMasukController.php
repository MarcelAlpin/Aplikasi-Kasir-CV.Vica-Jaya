<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Helpers\LogAktivitas;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        
        $barangMasuk = BarangMasuk::with('barang')
            ->when($keyword, function ($query, $keyword) {
                return $query->whereHas('barang', function($q) use ($keyword) {
                    $q->where('nama', 'like', '%' . $keyword . '%');
                })
                ->orWhere('id', 'like', '%' . $keyword . '%')
                ->orWhere('jumlah_masuk', 'like', '%' . $keyword . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        LogAktivitas::simpan('Mengakses halaman daftar barang masuk');

        return view('master.barangmasuk.index', compact('barangMasuk', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        LogAktivitas::simpan('Mengakses halaman tambah barang masuk');
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
        ]);
        
        $barang = Barang::find($request->barang_id);
        $barang->stok += $request->jumlah_masuk; // Increment stock
        $barang->save();
        
        LogAktivitas::simpan("Menambah barang masuk baru: {$barang->nama} dengan ID: {$barangMasukId}");

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
        LogAktivitas::simpan("Mengakses halaman edit barang masuk dengan ID: {$id}");

        return view('master.barangmasuk.edit', [
            'barangMasuk' => BarangMasuk::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jumlah_masuk' => 'required|integer|min:1',
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);
        
        // kalkulasi selisih stok
        $oldJumlahMasuk = $barangMasuk->jumlah_masuk;
        $newJumlahMasuk = $request->jumlah_masuk;
        $stockDifference = $newJumlahMasuk - $oldJumlahMasuk;
        
        // Update barang masuk record
        $barangMasuk->update([
            'jumlah_masuk' => $newJumlahMasuk,
        ]);
        
        // perbarui stok barang
        $barang = Barang::find($barangMasuk->barang_id);
        $barang->stok += $stockDifference;
        $barang->save();

        LogAktivitas::simpan("Memperbarui jumlah barang masuk dengan ID: {$id} dari {$oldJumlahMasuk} menjadi {$newJumlahMasuk}");

        return redirect()->route('barangmasuk.index')
            ->with('success', 'Jumlah barang masuk berhasil diperbarui.');
    }
}
