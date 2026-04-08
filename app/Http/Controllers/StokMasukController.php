<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokMasukController extends Controller
{
    public function index()
    {
        // Mengambil data stok masuk beserta relasi barang dan user pembawa datanya
        // latest() berfungsi untuk mengurutkan dari data yang paling baru diinput
        $riwayatStok = StokMasuk::with(['barang', 'user'])->latest()->get();
        
        return view('fitur.Tim Gudang.riwayat_stok', compact('riwayatStok'));
    }
    public function create()
    {
        // Mengambil data barang untuk pilihan di form
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('fitur.Tim Gudang.stok_masuk', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_masuk' => 'required|date',
            'jumlah_unit' => 'nullable|numeric|min:0', // Kolom EKOR
            'jumlah_berat' => 'nullable|numeric|min:0', // Kolom KG/LITER
            'nama_hub' => 'required|string', // Contoh: MUNDU
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan riwayat transaksi
            StokMasuk::create([
                'barang_id' => $request->barang_id,
                'user_id' => Auth::id(),
                'tanggal_masuk' => $request->tanggal_masuk,
                'jumlah_unit' => $request->jumlah_unit ?? 0,
                'jumlah_berat' => $request->jumlah_berat ?? 0,
                'nama_hub' => $request->nama_hub,
                'keterangan' => $request->keterangan,
            ]);

            // // 2. Update saldo stok di tabel master barangs
            // $barang = Barang::find($request->barang_id);
            // $barang->increment('stok_ekor', $request->jumlah_unit ?? 0);
            // $barang->increment('stok_berat', $request->jumlah_berat ?? 0);
        });
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Gudang',
            'aktivitas' => 'Menambahkan catatan barang baru masuk ke gudang'
        ]);
        return redirect()->back()->with('success', 'Data stok berhasil disimpan!');
    }
    /**
     * Menampilkan form edit data stok
     */
    /**
     * Menampilkan form edit stok masuk (Khusus Tim Gudang & Belum di-ACC)
     */
    public function edit($id)
    {
        // 1. AMBIL DATA DULU (Ini yang bikin variabel $stokMasuk jadi ada isinya)
        $stokMasuk = StokMasuk::findOrFail($id);

        // 2. CEK GEMBOK KEAMANAN
        if ($stokMasuk->is_verified) {
            return redirect()->route('stok-masuk.index')->with('error', 'Akses ditolak! Data ini sudah diverifikasi Admin dan tidak bisa diubah lagi.');
        }
        // 3. KALAU AMAN, TAMPILIN FORM
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('fitur.Tim Gudang.stok-masuk-edit', compact('stokMasuk', 'barangs'));
    }

    /**
     * Menyimpan perubahan data stok masuk
     */
    public function update(Request $request, $id)
    {
        // 1. AMBIL DATA DULU
        $stokMasuk = StokMasuk::findOrFail($id);

        // 2. CEK GEMBOK KEAMANAN (Cek lagi buat jaga-jaga ada yang nekat tembusin URL)
        if ($stokMasuk->is_verified) {
            return redirect()->route('stok-masuk.index')->with('error', 'Akses ditolak! Data ini sudah diverifikasi Admin.');
        }

        // 3. VALIDASI FORM
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_unit' => 'nullable|numeric|min:0',
            'jumlah_berat' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string'
        ]);

        // 4. UPDATE DATA
        $stokMasuk->update([
            'barang_id' => $request->barang_id,
            'jumlah_unit' => $request->jumlah_unit ?? 0,
            'jumlah_berat' => $request->jumlah_berat ?? 0,
            'keterangan' => $request->keterangan,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Gudang',
            'aktivitas' => 'Memperbarui catatan data stok masuk'
        ]);

        return redirect()->route('stok-masuk.index')->with('success', 'Data Stok Masuk berhasil diperbarui!');
    }

    /**
     * FUNGSI KHUSUS ADMIN: Memverifikasi Stok Datang
     */
    // public function verify($id)
    // {
    //     $stokMasuk = StokMasuk::findOrFail($id);

    //     $stokMasuk->update([
    //         'is_verified' => true,
    //         'verified_by' => Auth::id(),
    //         'verified_at' => now(),
    //     ]);

    //     // 2. Update saldo stok di tabel master barangs
    //         $barang = Barang::find($stokMasuk->barang_id);
    //         $barang->increment('stok_ekor', $stokMasuk->jumlah_unit ?? 0);
    //         $barang->increment('stok_berat', $stokMasuk->jumlah_berat ?? 0);
        
    //     // log aktivitas
        
    //     LogAktivitas::create([
    //         'user_id' => Auth::id(),
    //         'modul' => 'Gudang',
    //         'aktivitas' => 'Memverifikasi stok pada tanggal: '. $stokMasuk->tanggal_masuk
    //     ]);

    //     return redirect()->back()->with('success', 'Stok Masuk dari supplier berhasil diverifikasi dan dikunci!');
    // }
}
