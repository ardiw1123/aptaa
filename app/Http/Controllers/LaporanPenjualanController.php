<?php

namespace App\Http\Controllers;

use App\Models\Penjualan; 
use App\Models\DetailPenjualan; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        // 1. REKAP CEPAT (Selalu mutlak, kebal dari filter)
        $quickStats = [
            'hari_ini' => Penjualan::whereDate('tanggal_transaksi', Carbon::today())->count(),
            'minggu_ini' => Penjualan::whereBetween('tanggal_transaksi', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'bulan_ini' => Penjualan::whereMonth('tanggal_transaksi', Carbon::now()->month)->whereYear('tanggal_transaksi', Carbon::now()->year)->count(),
        ];

        // 2. TANGKAP FILTER TANGGAL
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // 3. STATISTIK DINAMIS (Ngikutin Filter)
        $stats = [
            'total_trx' => Penjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])->count(),
            'total_berat' => DetailPenjualan::whereHas('penjualan', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })->sum('jumlah_berat'),
        ];

        // 4. DATA GRAFIK (Tren Penjualan Harian sesuai filter)
        $chartDataRaw = Penjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->select(DB::raw('DATE(tanggal_transaksi) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartDates = [];
        $chartTotals = [];
        foreach ($chartDataRaw as $data) {
            $chartDates[] = Carbon::parse($data->date)->format('d M');
            $chartTotals[] = $data->total;
        }

        // 5. ITEM PALING LAKU
        $topItems = DetailPenjualan::with('barang')
            ->whereHas('penjualan', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })
            ->select('barang_id', DB::raw('SUM(jumlah_berat) as total_berat'))
            ->groupBy('barang_id')
            ->orderBy('total_berat', 'desc')
            ->take(5)
            ->get();

        // 6. DETAIL LAPORAN TABEL
        $laporanDetail = Penjualan::with(['admin', 'detailPenjualan.barang'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->latest('tanggal_transaksi')
            ->paginate(10);

        return view('fitur.Manajer.laporan-penjualan', compact('quickStats', 'stats', 'chartDates', 'chartTotals', 'topItems', 'laporanDetail', 'startDate', 'endDate'));
    }

    /**
     * LIHAT DETAIL TRANSAKSI (Khusus Manajer)
     */
    public function show($id)
    {
        // Ambil data penjualan beserta admin yang input dan rincian barangnya
        $penjualan = Penjualan::with(['admin', 'detailPenjualan.barang'])->findOrFail($id);

        return view('fitur.Manajer.laporan-penjualan-detail', compact('penjualan'));
    }
}