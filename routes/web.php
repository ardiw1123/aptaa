<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\CekStokController;
use App\Http\Controllers\MonitorStokController;
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
    Route::patch('/stok-masuk/{id}/verify', [StokMasukController::class, 'verify'])->name('stok-masuk.verify');
});

Route::middleware(['auth'])->group(function (){
    Route::get('/penjualan/riwayat', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/kasir', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/kasir', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/monitor-stok', [MonitorStokController::class, 'index'])->name('monitor-stok.index');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/cek-stok/input', [CekStokController::class, 'create'])->name('cek-stok.create');
    Route::post('/cek-stok/input', [CekStokController::class, 'store'])->name('cek-stok.store');
    Route::get('/cek-stok', [CekStokController::class, 'index'])->name('cek-stok.index');
    Route::get('/cek-stok/{id}/edit', [CekStokController::class, 'edit'])->name('cek-stok.edit');
    Route::put('/cek-stok/{id}', [CekStokController::class, 'update'])->name('cek-stok.update');
    Route::patch('/cek-stok/{id}/verify', [CekStokController::class, 'verify'])->name('cek-stok.verify');
});
require __DIR__.'/auth.php';
