<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PermintaanStok;
use App\Models\DetailPermintaanStok;
use App\Models\DetailPesananPelanggan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermintaanStokExport;

class PermintaanStokController extends Controller
{
    /**
     * Nampilin Form Bikin Request PO Baru
     */
    public function create()
    {
        // 1. Ambil data barang + Data Opname Terakhir yang SUDAH DIVERIFIKASI Admin
        $barangs = Barang::with(['latestCekStok' => function($query) {
            $query->where('is_verified', true);
        }])->orderBy('nama_barang', 'asc')->get();

        // 2. Tarik Data Pesanan Marketing HARI INI (yang sudah diklik "Kirim" / is_sent = true)
        $pesananHariIni = DetailPesananPelanggan::whereHas('pesanan', function($query) {
            $query->whereDate('tanggal_pesanan', today())
                  ->where('is_sent', true);
        })
        ->selectRaw('barang_id, SUM(jumlah_unit) as total_unit, SUM(jumlah_berat) as total_berat')
        ->groupBy('barang_id')
        ->get()
        ->keyBy('barang_id'); // Jadikan barang_id sebagai key array biar gampang dipanggil di View

        return view('fitur.Admin.permintaan-stok-create', compact('barangs', 'pesananHariIni'));
    }

    /**
     * Simpan Data PO ke Database (Status: Pending)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_request' => 'required|date',
            'keterangan' => 'nullable|string',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah_unit' => 'required|array',
            'jumlah_berat' => 'required|array',
        ]);

        // Generate Nomor Surat Otomatis (Format: PO-YYYYMMDD-001)
        $today = now()->format('Ymd');
        $hariIniCount = PermintaanStok::whereDate('created_at', today())->count() + 1;
        $noRequest = 'PO-' . $today . '-' . str_pad($hariIniCount, 3, '0', STR_PAD_LEFT);

        // Simpan Header Surat PO (otomatis pending buat Manajer)
        $permintaan = PermintaanStok::create([
            'no_request' => $noRequest,
            'user_id' => Auth::id(),
            'tanggal_request' => $request->tanggal_request,
            'status' => 'pending', 
            'keterangan' => $request->keterangan,
        ]);

        // Simpan Detail Barang yang di-PO
        foreach ($request->barang_id as $key => $barangId) {
            if ($request->jumlah_unit[$key] > 0 || $request->jumlah_berat[$key] > 0) {
                DetailPermintaanStok::create([
                    'permintaan_stok_id' => $permintaan->id,
                    'barang_id' => $barangId,
                    'jumlah_unit' => $request->jumlah_unit[$key] ?? 0,
                    'jumlah_berat' => $request->jumlah_berat[$key] ?? 0,
                ]);
            }
        }
        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Membuat data permintaan stok'
        ]);
        return redirect()->back()->with('success', 'Permintaan Stok ('.$noRequest.') berhasil dibuat dan menunggu verifikasi Manajer!');
    }

    /**
     * Menampilkan daftar riwayat Permintaan Stok (PO) untuk Admin
     */
    public function index()
    {
        // Ambil data PO, urutkan dari yang paling baru
        $permintaans = PermintaanStok::with(['pembuat', 'manajer'])
                        ->latest('tanggal_request')
                        ->paginate(15);

        return view('fitur.Admin.permintaan-stok-index', compact('permintaans'));
    }
    /**
     * Nampilin Form Edit PO (Khusus yang masih Pending)
     */
    public function edit($id)
    {
        // Ambil data header sekaligus detail barangnya
        $po = PermintaanStok::with('detailPermintaan')->findOrFail($id);

        // GEMBOK KEAMANAN: Cuma bisa diedit kalau belum di-ACC Manajer
        if ($po->status !== 'pending') {
            return redirect()->route('permintaan-stok.index')->with('error', 'Akses ditolak! Surat PO ini sudah diproses Manajer.');
        }

        // 1. Ambil data acuan fisik Gudang
        $barangs = Barang::with(['latestCekStok' => function($query) {
            $query->where('is_verified', true);
        }])->orderBy('nama_barang', 'asc')->get();

        // 2. Ambil data pesanan Marketing hari ini
        $pesananHariIni = DetailPesananPelanggan::whereHas('pesanan', function($query) {
            $query->whereDate('tanggal_pesanan', today())->where('is_sent', true);
        })->selectRaw('barang_id, SUM(jumlah_unit) as total_unit, SUM(jumlah_berat) as total_berat')
          ->groupBy('barang_id')->get()->keyBy('barang_id');

        // 3. Bikin contekan keranjang lama buat di-passing otomatis ke form edit
        $keranjangLama = [];
        foreach ($po->detailPermintaan as $detail) {
            $keranjangLama[$detail->barang_id] = [
                'unit' => $detail->jumlah_unit,
                'berat' => $detail->jumlah_berat
            ];
        }

        return view('fitur.Admin.permintaan-stok-edit', compact('po', 'barangs', 'pesananHariIni', 'keranjangLama'));
    }

    /**
     * Simpan Perubahan PO ke Database
     */
    public function update(Request $request, $id)
    {
        $po = PermintaanStok::findOrFail($id);

        // GEMBOK KEAMANAN
        if ($po->status !== 'pending') {
            return redirect()->route('permintaan-stok.index')->with('error', 'Akses ditolak! Data sudah dikunci.');
        }

        $request->validate([
            'tanggal_request' => 'required|date',
            'keterangan' => 'nullable|string',
            'barang_id' => 'required|array',
            'jumlah_unit' => 'required|array',
            'jumlah_berat' => 'required|array',
        ]);

        // 1. Update Tabel Header
        $po->update([
            'tanggal_request' => $request->tanggal_request,
            'keterangan' => $request->keterangan,
        ]);

        // 2. Sapu bersih detail pesanan lama (Delete)
        DetailPermintaanStok::where('permintaan_stok_id', $po->id)->delete();

        // 3. Masukkan keranjang yang baru (hasil revisi Admin)
        foreach ($request->barang_id as $key => $barangId) {
            if ($request->jumlah_unit[$key] > 0 || $request->jumlah_berat[$key] > 0) {
                DetailPermintaanStok::create([
                    'permintaan_stok_id' => $po->id,
                    'barang_id' => $barangId,
                    'jumlah_unit' => $request->jumlah_unit[$key] ?? 0,
                    'jumlah_berat' => $request->jumlah_berat[$key] ?? 0,
                ]);
            }
        }
        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Mengedit data permintaan stok'
        ]);
        return redirect()->route('permintaan-stok.index')->with('success', 'Data PO ('.$po->no_request.') berhasil direvisi!');
    }

    // download pdf
    public function downloadPdf($id)
    {
        $po = PermintaanStok::with(['pembuat', 'manajer', 'detailPermintaan.barang'])->findOrFail($id);
        $pdf = Pdf::loadView('fitur.Admin.permintaan-stok-pdf', compact('po'));
        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Mengunduh data permintaan stok'
        ]);
        return $pdf->download('PO-'.$po->no_request.'.pdf');
    }
    // download excell
    public function downloadExcel($id)
    {
        $po = PermintaanStok::findOrFail($id);
        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Mengunduh data permintaan stok'
        ]);
        return Excel::download(new PermintaanStokExport($id), 'PO-'.$po->no_request.'.xlsx');
    }

    /**
     * HALAMAN MANAJER: Daftar Overview PO (Tabel)
     */
    public function managerIndex()
    {
        // Nggak perlu load detail barang di sini biar query lebih ringan
        $permintaans = PermintaanStok::with(['pembuat', 'manajer'])
                        ->orderByRaw("
                            CASE 
                                WHEN status = 'pending' THEN 1
                                WHEN status = 'approved' THEN 2
                                WHEN status = 'rejected' THEN 3
                                ELSE 4 
                            END
                        ")
                        ->orderBy('tanggal_request', 'desc')
                        ->latest('tanggal_request')
                        ->paginate(15);

        return view('fitur.Manajer.permintaan-stok-index', compact('permintaans'));
    }

    /**
     * HALAMAN MANAJER: Detail & Verifikasi Surat PO
     */
    public function show($id)
    {
        // Di sini baru kita load detail barangnya
        $po = PermintaanStok::with(['pembuat', 'manajer', 'detailPermintaan.barang'])->findOrFail($id);
        
        return view('fitur.Manajer.permintaan-stok-show', compact('po'));
    }

    // (Fungsi verify() yang sebelumnya lo copy biarin aja, udah bener)

    /**
     * PROSES VERIFIKASI: ACC atau Tolak
     */
    public function verify(Request $request, $id)
    {
        $po = PermintaanStok::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $po->update([
            'status' => $request->status,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $pesan = $request->status == 'approved' ? 'disetujui' : 'ditolak';

        // log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Pengelolaan Stok',
            'aktivitas' => 'Memverifikasi data permintaan stok'
        ]);
        return redirect()->back()->with('success', "Permintaan Stok {$po->no_request} berhasil {$pesan}.");
    }
}