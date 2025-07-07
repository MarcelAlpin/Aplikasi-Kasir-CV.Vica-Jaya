<?php

namespace App\Http\Controllers\Aktivitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktivitas;
use Carbon\Carbon;
use App\Helpers\LogAktivitas;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = Aktivitas::with('user')->latest();

        // Optional: filter berdasarkan tanggal
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59',
            ]);
        }

        $aktivitas = $query->paginate(20); // pakai pagination

        LogAktivitas::simpan('Mengakses halaman daftar aktivitas');

        return view('laporan.aktivitas.index', compact('aktivitas'));
    }
}
