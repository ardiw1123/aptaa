<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokMasuk;
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
        
        return view('dashboard.riwayat_stok', compact('riwayatStok'));
    }
    public function create()
    {
        // Mengambil data barang untuk pilihan di form
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('dashboard.stok_masuk', compact('barangs'));
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

            // 2. Update saldo stok di tabel master barangs
            $barang = Barang::find($request->barang_id);
            $barang->increment('stok_ekor', $request->jumlah_unit ?? 0);
            $barang->increment('stok_berat', $request->jumlah_berat ?? 0);
        });

        return redirect()->back()->with('success', 'Data stok berhasil disimpan!');
    }
    /**
     * Menampilkan form edit data stok
     */
    public function edit($id)
    {
        $stok = StokMasuk::findOrFail($id);
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('dashboard.stok_masuk_edit', compact('stok', 'barangs'));
    }

    /**
     * Memproses update data dan menyesuaikan kalkulasi stok master
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_masuk' => 'required|date',
            'jumlah_unit' => 'nullable|numeric|min:0',
            'jumlah_berat' => 'nullable|numeric|min:0',
            'nama_hub' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $id) {
            $stokLama = StokMasuk::findOrFail($id);

            // 1. KEMBALIKAN STOK LAMA (Rollback)
            // Kurangi stok di master barang sejumlah data lama sebelum diedit
            $barangLama = Barang::find($stokLama->barang_id);
            $barangLama->decrement('stok_ekor', $stokLama->jumlah_unit ?? 0);
            $barangLama->decrement('stok_berat', $stokLama->jumlah_berat ?? 0);

            // 2. UPDATE DATA TRANSAKSI
            $stokLama->update([
                'barang_id' => $request->barang_id,
                'tanggal_masuk' => $request->tanggal_masuk,
                'jumlah_unit' => $request->jumlah_unit ?? 0,
                'jumlah_berat' => $request->jumlah_berat ?? 0,
                'nama_hub' => $request->nama_hub,
                'keterangan' => $request->keterangan,
            ]);

            // 3. MASUKKAN STOK BARU
            // Tambahkan stok master dengan angka/barang yang baru diinput
            $barangBaru = Barang::find($request->barang_id);
            $barangBaru->increment('stok_ekor', $request->jumlah_unit ?? 0);
            $barangBaru->increment('stok_berat', $request->jumlah_berat ?? 0);
        });

        return redirect()->route('stok_masuk.index')->with('success', 'Data stok berhasil diperbaiki dan saldo telah disesuaikan!');
    }
}
