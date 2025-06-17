<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\SatuanController;
use App\Http\Controllers\Master\AgenController;
use App\Http\Controllers\Master\BarangController;
use App\Http\Controllers\Master\BarangMasukController;
use App\Http\Controllers\Kasir\KasirController;
use App\Http\Controllers\Transaksi\TransaksiController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role Admin
    Route::middleware('role:admin')->group(function () {
        // Master /Kategori
        Route::resource('kategori', KategoriController::class);

        // Master /Satuan
        Route::resource('satuan', SatuanController::class);

        // Master /Agen
        Route::resource('agen', AgenController::class);

        // Master /Barang
        Route::resource('barang', BarangController::class);

        // Master /BarangMasuk
        Route::resource('barangmasuk', BarangMasukController::class);
    });

    // Role Kasir
    Route::middleware('role:kasir')->group(function () {
        // Kasir
        Route::resource('kasir', KasirController::class);
    });

    // Transaksi
    Route::resource('transaksi', TransaksiController::class);
});

require __DIR__.'/auth.php';