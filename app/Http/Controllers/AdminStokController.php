<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StokMasuk; 
use App\Models\Barang;
use App\Models\LogAktivitas;
use App\Models\PesananPelanggan;
use App\Models\DetailPesananPelanggan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminStokController extends Controller
{
// ...

    /**
     * 1. HALAMAN UTAMA STOK MASUK (Dikelompokkan per tanggal)
     */
    public function indexStokMasuk()
    {
        $riwayatHarian = StokMasuk::select(
                'tanggal_masuk', 
                DB::raw('COUNT(id) as total_macam_barang'),
                DB::raw('SUM(jumlah_unit) as total_ekor'),
                DB::raw('SUM(jumlah_berat) as total_berat'),
                DB::raw('SUM(CASE WHEN is_verified = false THEN 1 ELSE 0 END) as jumlah_pending')
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

    // acc stok masuk
    public function accStok($id)
{
    $stokMasuk = StokMasuk::findOrFail($id);

    if ($stokMasuk->is_verified == 1) {
        $stokMasuk->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
    }

    // 1. Ubah status stok masuk
    $stokMasuk->update([
        'is_verified' => 1
    ]);

    // 2. Tambahkan ke stok master barang
    $barang = Barang::findOrFail($stokMasuk->barang_id);
    $barang->increment('stok_ekor', $stokMasuk->jumlah_unit ?? 0);
    $barang->increment('stok_berat', $stokMasuk->jumlah_berat ?? 0);

    // 3. Catat Log Aktivitas 
    LogAktivitas::create([
        'user_id' => Auth::id(),
        'modul' => 'Gudang',
        'aktivitas' => 'Verifikasi stok masuk pada tanggal ' . $stokMasuk->tanggal_masuk 
    ]);

    return back()->with('success', 'Stok berhasil diverifikasi dan masuk ke Master Barang!');
}

// 1. HALAMAN UTAMA: Menampilkan 1 Baris = 1 No. Pesanan
    public function indexPesanan()
    {
        // Tarik data pesanan, hitung sekalian ada berapa macam barang di dalamnya
        $daftarPesanan = PesananPelanggan::with('marketing')
                            ->withCount('detailPesanan as total_macam_barang')
                            ->latest()
                            ->paginate(10);

        return view('fitur.Admin.pesanan-index', compact('daftarPesanan'));
    }

    // 2. HALAMAN DETAIL: Menampilkan rincian pesanan pas tombol diklik
    public function detailPesanan($id)
    {
        // Tarik 1 pesanan spesifik beserta detail barangnya
        $pesanan = PesananPelanggan::with(['marketing', 'detailPesanan.barang'])->findOrFail($id);

        return view('fitur.Admin.pesanan-detail', compact('pesanan'));
    }
}
