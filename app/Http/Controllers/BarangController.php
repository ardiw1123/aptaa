<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang; 
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    // Nampilin Form Tambah Barang
    public function create()
    {
        // Opsional: Cek manual kalau lo ga pake middleware checkrole
        if (Auth::user()->role !== 'manajer') {
            abort(403, 'Hanya Manajer yang boleh akses halaman ini.');
        }

        return view('fitur.Manajer.tambah-barang');
    }

    // Proses Simpan ke Database
    public function store(Request $request)
{
    // 1. Validasi Inputan
    $request->validate([
        'sku' => 'required|unique:barangs,sku|max:50',
        'nama_barang' => 'required|string|max:255',
        'kategori' => 'required|string|max:100',
        'satuan_utama' => 'required|string|max:50',
    ], [
        'sku.unique' => 'Kode SKU ini sudah dipakai!',
        'sku.required' => 'Kode SKU wajib diisi.'
    ]);

    // 2. Simpan Data (Stok di-set 0 dari awal)
    Barang::create([
        'sku' => strtoupper($request->sku),
        'nama_barang' => $request->nama_barang,
        'kategori' => $request->kategori,
        'satuan_utama' => $request->satuan_utama,
        'stok_ekor' => 0,  // Kunci otomatis ke 0
        'stok_berat' => 0, // Kunci otomatis ke 0
    ]);

    return redirect()->back()->with('success', 'Master Data Barang berhasil ditambahkan ke sistem!');
}
public function index()
{
    $barangs = Barang::orderBy('created_at', 'desc')->get(); 
    
    return view('fitur.Manajer.barang-index', compact('barangs'));
}

// Nampilin Form Edit
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('fitur.Manajer.edit-barang', compact('barang'));
    }

    // Proses Simpan Edit ke Database
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            // Pengecualian unik untuk ID barang ini sendiri biar ga error pas di-save tanpa ganti SKU
            'sku' => 'required|max:50|unique:barangs,sku,'.$id, 
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan_utama' => 'required|string|max:50',
        ], [
            'sku.unique' => 'Kode SKU ini sudah dipakai oleh barang lain!',
        ]);

        $barang->update([
            'sku' => strtoupper($request->sku),
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'satuan_utama' => $request->satuan_utama,
        ]);

        return redirect()->route('manajer.barang.index')->with('success', 'Data Master Barang berhasil diperbarui!');
    }
}