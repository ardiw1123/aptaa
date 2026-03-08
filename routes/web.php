<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return auth::check()
    ? redirect()->route('dashboard')
    : redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'role:admin,tim_gudang,tim_barang,tim_marketing,manajer'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin,tim_gudang,tim_barang,tim_marketing,manajer'])
    ->name('dashboard');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/stok_masuk', [StokMasukController::class, 'create'])->name('stok_masuk.create');
    Route::post('/stok_masuk', [StokMasukController::class, 'store'])->name('stok_masuk.store');
    Route::get('/riwayat_stok', [StokMasukController::class, 'index'])->name('stok_masuk.index');
    Route::get('/stok_masuk/{id}/edit', [StokMasukController::class, 'edit'])->name('stok_masuk.edit');
    Route::put('/stok_masuk/{id}', [StokMasukController::class, 'update'])->name('stok_masuk.update');
});

Route::middleware(['auth'])->group(function (){
    Route::get('/penjualan/riwayat', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/kasir', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/kasir', [PenjualanController::class, 'store'])->name('penjualan.store');
});
require __DIR__.'/auth.php';
