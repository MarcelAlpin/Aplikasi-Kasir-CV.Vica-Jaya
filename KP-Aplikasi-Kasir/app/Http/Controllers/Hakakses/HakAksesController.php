<?php

namespace App\Http\Controllers\Hakakses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogAktivitas;

class HakAksesController extends Controller
{
    /**
     * Menampilkan daftar kasir aktif & tidak aktif.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $kasirAktif = User::query()
            ->where('role', 'kasir')
            ->whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'aktif')
            ->withQueryString();

        $kasirNonaktif = User::onlyTrashed()
            ->where('role', 'kasir')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'nonaktif')
            ->withQueryString();

        LogAktivitas::simpan('Mengakses halaman daftar kasir');

        return view('hakakses.index', compact('kasirAktif', 'kasirNonaktif', 'search'));
    }

    /**
     * Tambah akun kasir baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'kasir',
            'password' => Hash::make($request->password),
        ]);

        LogAktivitas::simpan("Menambahkan akun kasir: {$request->name}");

        return back()->with('success', 'Akun kasir berhasil ditambahkan.');
    }

    /**
     * Update nama/password kasir.
     */
    public function update(Request $request, string $id)
    {
        $kasir = User::withTrashed()->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'password' => 'nullable|min:6',
        ]);

        $kasir->name = $request->name;
        if ($request->password) {
            $kasir->password = Hash::make($request->password);
        }
        $kasir->save();

        LogAktivitas::simpan("Memperbarui akun kasir: {$kasir->name}");

        return back()->with('success', 'Data kasir berhasil diperbarui.');
    }

    /**
     * Soft delete (nonaktifkan) akun kasir.
     */
    public function destroy(string $id)
    {
        $kasir = User::findOrFail($id);
        $kasir->delete();

        LogAktivitas::simpan("Menonaktifkan akun kasir: {$kasir->name}");

        return back()->with('success', 'Akun kasir berhasil dinonaktifkan.');
    }

    /**
     * Restore akun kasir yang telah dinonaktifkan.
     */
    public function restore(string $id)
    {
        $kasir = User::onlyTrashed()->findOrFail($id);
        $kasir->restore();

        LogAktivitas::simpan("Mengaktifkan kembali akun kasir: {$kasir->name}");

        return back()->with('success', 'Akun kasir berhasil diaktifkan kembali.');
    }
}
