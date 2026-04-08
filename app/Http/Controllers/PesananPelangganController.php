<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PesananPelanggan;
use App\Models\DetailPesananPelanggan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananPelangganController extends Controller
{
    /**
     * Nampilin Form Input Pesanan (simpanData)
     */
    public function create()
    {
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
        return view('fitur.Tim Marketing.pesanan-pelanggan-create', compact('barangs'));
    }

    /**
     * Simpan Data Pesanan ke 2 Tabel (Header & Detail)
     */
    public function store(Request $request)
    {
        // 1. Validasi Inputan Marketing
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal_pesanan' => 'required|date',
            'tipe' => 'required|string', 
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah_unit' => 'required|array',
            'jumlah_berat' => 'required|array',
        ]);

        // 2. Bikin Nomor Invoice Otomatis (Format: ORD-YYYYMMDD-001)
        $today = now()->format('Ymd');
        $hariIniCount = PesananPelanggan::whereDate('created_at', today())->count() + 1;
        $noPesanan = 'ORD-' . $today . '-' . str_pad($hariIniCount, 3, '0', STR_PAD_LEFT);

        // 3. Simpan ke Tabel Header (Otomatis statusnya is_sent = false / Draft)
        $pesanan = PesananPelanggan::create([
            'user_id' => Auth::id(),
            'no_pesanan' => $noPesanan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'tanggal_pesanan' => $request->tanggal_pesanan,
            'tipe' => $request->tipe,
            'is_sent' => false, 
        ]);

        // 4. Simpan ke Tabel Detail (Keranjang Belanjanya)
        foreach ($request->barang_id as $key => $barangId) {
            // Cuma simpan barang yang diisi angkanya (> 0) sama Marketing
            if ($request->jumlah_unit[$key] > 0 || $request->jumlah_berat[$key] > 0) {
                DetailPesananPelanggan::create([
                    'pesanan_pelanggan_id' => $pesanan->id,
                    'barang_id' => $barangId,
                    'jumlah_unit' => $request->jumlah_unit[$key] ?? 0,
                    'jumlah_berat' => $request->jumlah_berat[$key] ?? 0,
                ]);
            }
        }
        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Penjualan',
            'aktivitas' => 'Menginput data pesanan pelanggan'
        ]);
        // Kembali ke form sambil bawa pesan sukses
        return redirect()->back()->with('success', 'Pesanan pelanggan berhasil disimpan sebagai DRAFT!');
    }

    /**
     * Menampilkan daftar pesanan (Halaman Index)
     */
    public function index()
    {
        // Ambil data pesanan, urutkan dari yang terbaru
        // Kita load relasi detailPesanan biar bisa ngitung total itemnya
        $pesanans = PesananPelanggan::with(['marketing', 'detailPesanan'])
                        ->latest('tanggal_pesanan')
                        ->latest('id')
                        ->paginate(20);

        return view('fitur.Tim Marketing.pesanan-pelanggan-index', compact('pesanans'));
    }

    /**
     * Mengubah status pesanan menjadi Terkirim (Gembok / kirimData)
     */
    public function kirim($id)
    {
        $pesanan = PesananPelanggan::findOrFail($id);

        // Pastikan datanya belum terkirim
        if ($pesanan->is_sent) {
            return redirect()->back()->with('error', 'Pesanan ini sudah terkirim ke Admin!');
        }

        // Kunci data (is_sent = true)
        $pesanan->update([
            'is_sent' => true
        ]);

        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Penjualan',
            'aktivitas' => 'Mengirim data pesanan pelanggan ke admin'
        ]);

        return redirect()->back()->with('success', 'Data pesanan ('.$pesanan->no_pesanan.') berhasil dikirim ke Admin dan telah dikunci!');
    }

    /**
     * Menampilkan form edit pesanan (Khusus Draft)
     */
    public function edit($id)
    {
        // Ambil data header sekaligus relasi detailnya
        $pesanan = PesananPelanggan::with('detailPesanan')->findOrFail($id);

        // GEMBOK KEAMANAN
        if ($pesanan->is_sent) {
            return redirect()->route('pesanan-pelanggan.index')->with('error', 'Akses ditolak! Pesanan ini sudah dikirim ke Admin.');
        }

        $barangs = Barang::orderBy('nama_barang', 'asc')->get();

        // Bikin array "Contekan" dari keranjang lama biar gampang ditampilin di form
        // Formatnya: [barang_id => ['unit' => x, 'berat' => y]]
        $keranjangLama = [];
        foreach ($pesanan->detailPesanan as $detail) {
            $keranjangLama[$detail->barang_id] = [
                'unit' => $detail->jumlah_unit,
                'berat' => $detail->jumlah_berat
            ];
        }
        return view('fitur.Tim Marketing.pesanan-pelanggan-edit', compact('pesanan', 'barangs', 'keranjangLama'));
    }

    /**
     * Menyimpan perubahan pesanan pelanggan
     */
    public function update(Request $request, $id)
    {
        $pesanan = PesananPelanggan::findOrFail($id);

        // GEMBOK KEAMANAN
        if ($pesanan->is_sent) {
            return redirect()->route('pesanan-pelanggan.index')->with('error', 'Akses ditolak! Pesanan ini sudah dikirim ke Admin.');
        }

        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal_pesanan' => 'required|date',
            'tipe' => 'required|string',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah_unit' => 'required|array',
            'jumlah_berat' => 'required|array',
        ]);

        // 1. Update Tabel Header (Data Pelanggan)
        $pesanan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'tanggal_pesanan' => $request->tanggal_pesanan,
            'tipe' => $request->tipe,
        ]);

        // 2. Sapu Bersih Keranjang Lama
        DetailPesananPelanggan::where('pesanan_pelanggan_id', $pesanan->id)->delete();

        // 3. Masukkan Keranjang yang Baru
        foreach ($request->barang_id as $key => $barangId) {
            if ($request->jumlah_unit[$key] > 0 || $request->jumlah_berat[$key] > 0) {
                DetailPesananPelanggan::create([
                    'pesanan_pelanggan_id' => $pesanan->id,
                    'barang_id' => $barangId,
                    'jumlah_unit' => $request->jumlah_unit[$key] ?? 0,
                    'jumlah_berat' => $request->jumlah_berat[$key] ?? 0,
                ]);
            }
        }
        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Penjualan',
            'aktivitas' => 'Mengedit data pesanan pelanggan'
        ]);
        return redirect()->route('pesanan-pelanggan.index')->with('success', 'Draft Pesanan ('.$pesanan->no_pesanan.') berhasil diperbarui!');
    }
}