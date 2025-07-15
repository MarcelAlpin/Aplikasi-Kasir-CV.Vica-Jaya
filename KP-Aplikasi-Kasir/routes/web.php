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
use App\Http\Controllers\Hakakses\HakAksesController;
use App\Http\Controllers\Aktivitas\AktivitasController;

Route::get('/', function () {
    // jika sudah login
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    
    // kalau gak ya login dulu
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'aktivitas')->group(function () {
    // Role Admin
    Route::middleware('role:admin')->group(function () {
        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

        Route::get('barangmasuk/history/{barang_id}', [BarangMasukController::class, 'history'])
        ->name('barangmasuk.history');

        // Download All PDF Transaksi - Move this BEFORE the resource route
        Route::get('transaksi-pdf', [TransaksiController::class, 'downloadAllPDF'])->name('transaksi.all.pdf');
    
        // Laporan /Transaksi
        Route::resource('transaksi', TransaksiController::class, ['except' => ['create']]);

        // Add this new route for transaction details
        Route::get('transaksi/{transaksi}/detail', [TransaksiController::class, 'show'])->name('transaksi.detail');

        // Download PDF Transaksi Detail
        Route::get('transaksi/{id}/pdf', [TransaksiController::class, 'downloadPDF'])->name('transaksi.pdf');

        // Hak akses
        Route::get('/hakakses', [HakAksesController::class, 'index'])->name('hakakses.index');
        Route::post('/hakakses', [HakAksesController::class, 'store'])->name('hakakses.store');
        Route::put('/hakakses/{id}', [HakAksesController::class, 'update'])->name('hakakses.update');
        Route::delete('/hakakses/{id}', [HakAksesController::class, 'destroy'])->name('hakakses.destroy');
        Route::patch('/hakakses/{id}/restore', [HakAksesController::class, 'restore'])->name('hakakses.restore');

        // Aktivitas
        Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas.index');
    });

    // Role Kasir
    Route::middleware('role:kasir')->group(function () {
        // Kasir
        Route::resource('kasir', KasirController::class);
        // Laporan /Transaksi
        Route::resource('transaksi', TransaksiController::class, ['except' => ['index', 'detail']]);
        // Download All PDF Transaksi - Move this BEFORE the resource route
        Route::get('transaksi-pdf', [TransaksiController::class, 'downloadAllPDF'])->name('transaksi.all.pdf');
    
        // Laporan /Transaksi
        Route::resource('transaksi', TransaksiController::class, ['except' => ['create']]);

        // Add this new route for transaction details
        Route::get('transaksi/{transaksi}/detail', [TransaksiController::class, 'show'])->name('transaksi.detail');

        // Download PDF Transaksi Detail
        Route::get('transaksi/{id}/pdf', [TransaksiController::class, 'downloadPDF'])->name('transaksi.pdf');

    });
});

require __DIR__.'/auth.php';