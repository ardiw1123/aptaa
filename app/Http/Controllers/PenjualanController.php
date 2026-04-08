<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use \App\Models\LogAktivitas;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    /**
     * Menampilkan halaman form kasir / penjualan
     */
    public function create()
    {
        // Ambil barang yang stoknya masih ada (tidak 0)
        $barangs = Barang::where('stok_ekor', '>', 0)
                         ->orWhere('stok_berat', '>', 0)
                         ->orderBy('nama_barang', 'asc')
                         ->get();
                         
        return view('fitur.Admin.penjualan-create', compact('barangs'));
    }

    /**
     * Menampilkan Riwayat Penjualan dengan Filter Tanggal
     */
    public function index(Request $request)
    {
        // Siapkan query dasar, urutkan dari yang paling baru
        $query = Penjualan::with(['admin', 'detailPenjualan'])->latest('tanggal_transaksi')->latest('id');

        // Logika Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        // Hitung grand total pendapatan HANYA untuk tanggal yang difilter
        $totalPendapatan = $query->sum('total_harga');

        // Gunakan pagination biar halaman nggak berat kalau data ribuan
        $penjualans = $query->paginate(20)->withQueryString();

        return view('fitur.Admin.penjualan-index', compact('penjualans', 'totalPendapatan'));
    }
    
    /**
     * Memproses checkout dan memotong stok otomatis
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'nullable|string|max:255',
            'tanggal_transaksi' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah_unit' => 'nullable|numeric|min:0',
            'items.*.jumlah_berat' => 'nullable|numeric|min:0',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        // CEK STOK MINUS SEBELUM LANJUT [FIX ERROR 2]
        foreach ($request->items as $item) {
            $barang = Barang::find($item['barang_id']);
            $reqUnit = $item['jumlah_unit'] ?? 0;
            $reqBerat = $item['jumlah_berat'] ?? 0;

            if ($reqUnit > $barang->stok_ekor) {
                return redirect()->back()
                    ->withErrors(['stok' => "Transaksi Gagal: Stok EKOR untuk {$barang->nama_barang} tidak cukup! (Sisa: {$barang->stok_ekor})"])
                    ->withInput();
            }
            if ($reqBerat > $barang->stok_berat) {
                return redirect()->back()
                    ->withErrors(['stok' => "Transaksi Gagal: Stok BERAT (Kg) untuk {$barang->nama_barang} tidak cukup! (Sisa: {$barang->stok_berat} Kg)"])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request) {
            $tanggalInput = Carbon::parse($request->tanggal_transaksi);
            $formatTanggal = $tanggalInput->format('Ymd');
            $jumlahTransaksi = Penjualan::whereDate('tanggal_transaksi', $tanggalInput->toDateString())->count();
            $urutan = str_pad($jumlahTransaksi + 1, 4, '0', STR_PAD_LEFT);
            $noInvoice = 'INV-' . $formatTanggal . '-' . $urutan;

            $penjualan = Penjualan::create([
                'no_invoice' => $noInvoice,
                'user_id' => Auth::id(),
                'nama_pembeli' => $request->nama_pembeli,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'total_harga' => 0,
                'keterangan' => $request->keterangan,
            ]);

            $totalHarga = 0;

            foreach ($request->items as $item) {
                // PRIORITAS HARGA BERDASARKAN KG 
                $qty = (!empty($item['jumlah_berat']) && $item['jumlah_berat'] > 0) 
                        ? $item['jumlah_berat'] 
                        : ($item['jumlah_unit'] ?? 0);
                        
                $subtotal = $qty * $item['harga_satuan'];
                $totalHarga += $subtotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah_unit' => $item['jumlah_unit'] ?? 0,
                    'jumlah_berat' => $item['jumlah_berat'] ?? 0,
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $subtotal,
                ]);

                $barang = Barang::find($item['barang_id']);
                if (!empty($item['jumlah_unit']) && $item['jumlah_unit'] > 0) {
                    $barang->decrement('stok_ekor', $item['jumlah_unit']);
                }
                if (!empty($item['jumlah_berat']) && $item['jumlah_berat'] > 0) {
                    $barang->decrement('stok_berat', $item['jumlah_berat']);
                }
            }

            $penjualan->update(['total_harga' => $totalHarga]);

            LogAktivitas::create([
            'user_id' => Auth::id(),
            'modul' => 'Penjualan',
            'aktivitas' => 'Membuat faktur penjualan baru'
        ]);
        });

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    }
}
