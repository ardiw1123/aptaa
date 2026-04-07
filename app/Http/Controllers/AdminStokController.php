<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StokMasuk; 
use Carbon\Carbon;

class AdminStokController extends Controller
{
// ...

    /**
     * 1. HALAMAN UTAMA STOK MASUK (Dikelompokkan per tanggal)
     */
    public function indexStokMasuk()
    {
        // Kita langsung panggil kolom tanggal_masuk bawaan dari tabel lo
        $riwayatHarian = StokMasuk::select(
                'tanggal_masuk', // Nggak perlu pakai DB::raw DATE() lagi
                DB::raw('COUNT(id) as total_macam_barang'),
                DB::raw('SUM(jumlah_unit) as total_ekor'),
                DB::raw('SUM(jumlah_berat) as total_berat'),
                // Berdasarkan screenshot lo, gue pake is_verified = 0 buat deteksi pending
                DB::raw('SUM(CASE WHEN is_verified = 0 THEN 1 ELSE 0 END) as jumlah_pending')
            )
            ->groupBy('tanggal_masuk')
            ->orderBy('tanggal_masuk', 'desc')
            ->paginate(10);

        return view('fitur.Admin.stok-masuk-monitor', compact('riwayatHarian'));
    }
    /**
     * 2. HALAMAN DETAIL PER TANGGAL (Tempat nge-ACC)
     */
    public function detailStokMasuk($tanggal)
    {
        // Tarik semua rincian barang masuk khusus di tanggal yang dipilih
        $detailStok = StokMasuk::with('barang')
            ->whereDate('created_at', $tanggal)
            ->latest()
            ->get();

        $tanggalFormat = Carbon::parse($tanggal)->format('d F Y');

        return view('fitur.Admin.stok-masuk-detail', compact('detailStok', 'tanggalFormat', 'tanggal'));
    }
}
