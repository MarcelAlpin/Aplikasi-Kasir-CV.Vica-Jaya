<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\SatuanController;
use App\Http\Controllers\Master\BarangController;
use App\Http\Controllers\Kasir\KasirController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Master /Kategori
Route::resource('kategori', KategoriController::class)->middleware('auth');

// Master /Satuan
Route::resource('satuan', SatuanController::class)->middleware('auth');

// Master /Barang
Route::resource('barang', BarangController::class)->middleware('auth');

// Kasir
Route::resource('kasir', KasirController::class)->middleware('auth');

require __DIR__.'/auth.php';
