<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesananPelangganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\CekStokController;
use App\Http\Controllers\MonitorStokController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\PermintaanStokController;
use App\Http\Controllers\AdminStokController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return auth::check()
    ? redirect()->route('pegawai.dashboard')
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
// tim gudang
Route::middleware(['auth'])->group(function () {
    Route::get('/stok_masuk', [StokMasukController::class, 'create'])->name('stok_masuk.create');
    Route::post('/stok_masuk', [StokMasukController::class, 'store'])->name('stok_masuk.store');
    Route::get('/riwayat_stok', [StokMasukController::class, 'index'])->name('stok_masuk.index');
    Route::get('/stok_masuk/{id}/edit', [StokMasukController::class, 'edit'])->name('stok_masuk.edit');
    Route::put('/stok_masuk/{id}', [StokMasukController::class, 'update'])->name('stok_masuk.update');
    Route::patch('/stok-masuk/{id}/verify', [StokMasukController::class, 'verify'])->name('stok-masuk.verify');
    Route::post('/admin/stok-masuk/acc/{id}', [AdminStokController::class, 'accStok'])->name('admin.stok-masuk.acc');
});
// Admin
Route::middleware(['auth'])->group(function (){
    Route::get('/penjualan/riwayat', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/kasir', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/kasir', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/monitor-stok', [MonitorStokController::class, 'index'])->name('monitor-stok.index');
    Route::get('/permintaan-stok/buat', [PermintaanStokController::class, 'create'])->name('permintaan-stok.create');
    Route::post('/permintaan-stok/buat', [PermintaanStokController::class, 'store'])->name('permintaan-stok.store');
    Route::get('/permintaan-stok', [PermintaanStokController::class, 'index'])->name('permintaan-stok.index');
    Route::get('/permintaan-stok/{id}/edit', [PermintaanStokController::class, 'edit'])->name('permintaan-stok.edit');
    Route::put('/permintaan-stok/{id}', [PermintaanStokController::class, 'update'])->name('permintaan-stok.update');
    Route::get('/permintaan-stok/{id}/pdf', [PermintaanStokController::class, 'downloadPdf'])->name('permintaan-stok.pdf');
    Route::get('/permintaan-stok/{id}/excel', [PermintaanStokController::class, 'downloadExcel'])->name('permintaan-stok.excel');
    Route::get('/admin/stok-masuk', [AdminStokController::class, 'indexStokMasuk'])->name('admin.stok-masuk.index');
    Route::get('/admin/stok-masuk/{tanggal}', [AdminStokController::class, 'detailStokMasuk'])->name('admin.stok-masuk.detail');
    Route::get('/admin/pesanan-marketing', [AdminStokController::class, 'indexPesanan'])->name('admin.pesanan.index');
    Route::get('/admin/pesanan-marketing/{id}', [AdminStokController::class, 'detailPesanan'])->name('admin.pesanan.detail');
});

// tim barang
Route::middleware(['auth'])->group(function(){
    Route::get('/cek-stok/input', [CekStokController::class, 'create'])->name('cek-stok.create');
    Route::post('/cek-stok/input', [CekStokController::class, 'store'])->name('cek-stok.store');
    Route::get('/cek-stok', [CekStokController::class, 'index'])->name('cek-stok.index');
    Route::get('/cek-stok/{id}/edit', [CekStokController::class, 'edit'])->name('cek-stok.edit');
    Route::put('/cek-stok/{id}', [CekStokController::class, 'update'])->name('cek-stok.update');
    Route::patch('/cek-stok/{id}/verify', [CekStokController::class, 'verify'])->name('cek-stok.verify');
});

// tim marketing
Route::middleware(['auth'])->group(function(){
    Route::get('/pesanan-pelanggan/buat', [PesananPelangganController::class, 'create'])->name('pesanan-pelanggan.create');
    Route::post('/pesanan-pelanggan/buat', [PesananPelangganController::class, 'store'])->name('pesanan-pelanggan.store');
    Route::get('/pesanan-pelanggan', [PesananPelangganController::class, 'index'])->name('pesanan-pelanggan.index');
    Route::patch('/pesanan-pelanggan/{id}/kirim', [PesananPelangganController::class, 'kirim'])->name('pesanan-pelanggan.kirim');
    Route::get('/pesanan-pelanggan/{id}/edit', [PesananPelangganController::class, 'edit'])->name('pesanan-pelanggan.edit');
    Route::put('/pesanan-pelanggan/{id}', [PesananPelangganController::class, 'update'])->name('pesanan-pelanggan.update');
});

// manajer
Route::middleware(['auth'])->group(function(){
    Route::get('/manager/permintaan-stok', [PermintaanStokController::class, 'managerIndex'])->name('manager.permintaan-stok.index');
    Route::patch('/manager/permintaan-stok/{id}/verify', [PermintaanStokController::class, 'verify'])->name('manager.permintaan-stok.verify');
    Route::get('/manager/permintaan-stok', [PermintaanStokController::class, 'managerIndex'])->name('manager.permintaan-stok.index');
    Route::get('/manager/permintaan-stok/{id}', [PermintaanStokController::class, 'show'])->name('manager.permintaan-stok.show'); // TAMBAHAN BARU
    Route::patch('/manager/permintaan-stok/{id}/verify', [PermintaanStokController::class, 'verify'])->name('manager.permintaan-stok.verify');
    Route::get('/manager/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('manager.laporan.index');
    Route::get('/manager/laporan-penjualan/{id}', [LaporanPenjualanController::class, 'show'])->name('manager.laporan.show');
});

// dashboard
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pegawai.dashboard');
    Route::post('/absen', [DashboardController::class, 'prosesAbsen'])->name('pegawai.absen.proses');
});
require __DIR__.'/auth.php';
