<?php

namespace App\Helpers;

use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogAktivitas
{
    public static function simpan(string $keterangan)
    {
        if (Auth::check()) {
            request()->merge(['_log_aktivitas_tercatat' => true]); // Tambahkan flag!

            Aktivitas::create([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'halaman'    => request()->fullUrl(),
                'keterangan' => $keterangan,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }
    }
}
