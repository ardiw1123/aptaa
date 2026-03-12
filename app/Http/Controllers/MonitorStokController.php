<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokMasuk;
use Illuminate\Http\Request;

class MonitorStokController extends Controller
{
    public function index()
    {
        // Ambil barang beserta data cek fisik TERAKHIR-nya
        $barangs = Barang::with('latestCekStok')->orderBy('nama_barang', 'asc')->get();

        // Ambil 5 riwayat stok masuk terbaru (tetap sama)
        $stokMasuks = \App\Models\StokMasuk::with(['barang', 'user'])
                        ->latest('tanggal_masuk')
                        ->latest('id')
                        ->take(5)
                        ->get();

        return view('dashboard.monitor-stok', compact('barangs', 'stokMasuks'));
    }
}