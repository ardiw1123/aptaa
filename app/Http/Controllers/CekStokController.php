<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CekStok;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekStokController extends Controller
{
    /**
     * Menampilkan daftar riwayat cek stok fisik
     */
    public function index(Request $request)
    {
        // Siapkan query dasar
        $query = CekStok::with(['barang', 'user'])->latest('tanggal_cek')->latest('id');

        // Logika Filter 1 Tanggal Saja
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_cek', $request->tanggal);
        }

        // Jangan lupa tambahkan withQueryString()
        $cekStoks = $query->paginate(20)->withQueryString();

        return view('fitur.Tim Barang.cek-stok-index', compact('cekStoks'));
    }
    
    /**
     * Menampilkan form edit (Hanya untuk pemilik data)
     */
    public function edit($id)
    {
        $cekStok = CekStok::with('barang')->findOrFail($id);
        // GEMBOK UTAMA: Kalau udah di-acc Admin, haram hukumnya diedit lagi!
        if ($cekStok->is_verified) {
            return redirect()->route('cek-stok.index')->with('error', 'Akses ditolak! Data ini sudah diverifikasi Admin dan dikunci permanen.');
        }

        // GEMBOK KEAMANAN: Cek apakah user yang login adalah yang bikin data ini
        if (Auth::id() !== $cekStok->user_id) {
            return redirect()->route('cek-stok.index')->with('error', 'Akses ditolak! Anda hanya bisa mengedit data yang Anda input sendiri.');
        }

        return view('fitur.Tim Barang.cek-stok-edit', compact('cekStok'));
    }

    /**
     * Menyimpan update data cek fisik
     */
    public function update(Request $request, $id)
    {
        $cekStok = CekStok::findOrFail($id);
        // GEMBOK UTAMA: Kalau udah di-acc Admin, haram hukumnya diedit lagi!
        if ($cekStok->is_verified) {
            return redirect()->route('cek-stok.index')->with('error', 'Akses ditolak! Data ini sudah diverifikasi Admin dan dikunci permanen.');
        }

        // GEMBOK KEAMANAN: Cek sekali lagi pas data dikirim
        if (Auth::id() !== $cekStok->user_id) {
            return redirect()->route('cek-stok.index')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'stok_ekor_fisik' => 'required|numeric|min:0',
            'stok_berat_fisik' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        // Cuma update angka fisik dan catatan. Data sistem dan barang dibiarkan utuh.
        $cekStok->update([
            'stok_ekor_fisik' => $request->stok_ekor_fisik,
            'stok_berat_fisik' => $request->stok_berat_fisik,
            'catatan' => $request->catatan,
        ]);

        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Mengedit data ketersediaan stok'
        ]);

        return redirect()->route('cek-stok.index')->with('success', 'Data Opname berhasil diperbarui!');
    }
    
    /**
     * Menampilkan halaman form input cek stok fisik
     */
    public function create()
    {
        // Ambil semua barang untuk masuk ke dropdown pilihan
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('fitur.Tim Barang.cek-stok-create', compact('barangs'));
    }

    /**
     * Menyimpan data hasil cek fisik dan mengambil snapshot stok sistem
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_cek' => 'required|date',
            'barang_id' => 'required|exists:barangs,id',
            'stok_ekor_fisik' => 'nullable|numeric|min:0',
            'stok_berat_fisik' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        // Cari barang yang dipilih untuk ngambil "Snapshot" stok sistem saat ini
        $barang = Barang::find($request->barang_id);

        CekStok::create([
            'tanggal_cek' => $request->tanggal_cek,
            'barang_id' => $request->barang_id,
            'user_id' => Auth::id(), // Nyatet siapa anak Tim Barang yang nginput
            
            // Merekam angka sistem (Rahasia, jalan di background)
            'stok_ekor_sistem' => $barang->stok_ekor,
            'stok_berat_sistem' => $barang->stok_berat,
            
            // Menyimpan angka hitungan manual dari form
            'stok_ekor_fisik' => $request->stok_ekor_fisik ?? 0,
            'stok_berat_fisik' => $request->stok_berat_fisik ?? 0,
            
            'catatan' => $request->catatan,
        ]);

        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Menginput data ketersediaan stok'
        ]);

        return redirect()->back()->with('success', 'Data Cek Stok Fisik berhasil disimpan! Terima kasih sudah melakukan pengecekan.');
    }
    /**
     * FUNGSI KHUSUS ADMIN: Memverifikasi Data
     */
    public function verify($id)
    {
        $cekStok = CekStok::findOrFail($id);

        // Kasih verifikasi: Ubah status jadi true, catet siapa adminnya dan jam berapa
        $cekStok->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Memverifikasi data ketersediaan stok'
        ]);

        return redirect()->back()->with('success', 'Data Cek Fisik berhasil diverifikasi & dikunci!');
    }
}