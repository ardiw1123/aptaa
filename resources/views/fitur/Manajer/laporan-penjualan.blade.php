@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto py-6 md:py-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard Penjualan</h1>
            <p class="text-slate-500 mt-1 text-sm">Analisis performa bisnis dan pantau tren secara *real-time*.</p>
        </div>

        <div class="flex gap-2 bg-white p-2 rounded-xl shadow-sm border border-slate-100">
            <div class="px-4 py-2 bg-slate-50 rounded-lg text-center">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Hari Ini</p>
                <p class="text-sm font-black text-blue-600">{{ $quickStats['hari_ini'] }} Trx</p>
            </div>
            <div class="px-4 py-2 bg-slate-50 rounded-lg text-center border-l border-slate-200">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Minggu Ini</p>
                <p class="text-sm font-black text-green-600">{{ $quickStats['minggu_ini'] }} Trx</p>
            </div>
            <div class="px-4 py-2 bg-slate-50 rounded-lg text-center border-l border-slate-200">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bulan Ini</p>
                <p class="text-sm font-black text-purple-600">{{ $quickStats['bulan_ini'] }} Trx</p>
            </div>
        </div>
    </div>

    <div class="bg-slate-800 p-6 rounded-3xl mb-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-slate-800/20">
        <div class="flex gap-8 w-full md:w-auto">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Trx (Filter)</p>
                <h3 class="text-2xl font-black text-white">{{ number_format($stats['total_trx']) }}</h3>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Keluar (Filter)</p>
                <h3 class="text-2xl font-black text-amber-400">{{ number_format($stats['total_berat'], 1) }} <span class="text-xs font-normal">Kg</span></h3>
            </div>
        </div>
        
        <form action="{{ route('manager.laporan.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto bg-slate-700/50 p-2 rounded-xl border border-slate-600">
            <input type="date" name="start_date" value="{{ $startDate }}" class="text-xs bg-transparent text-white border-none outline-none focus:ring-0">
            <span class="text-slate-400 text-xs font-medium">s/d</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="text-xs bg-transparent text-white border-none outline-none focus:ring-0">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 transition-colors text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/30">
                Filter Data
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white p-6 md:p-8">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center uppercase tracking-widest text-xs">
                <span class="h-2 w-2 bg-blue-500 rounded-full mr-3"></span> Tren Penjualan Harian
            </h3>
            <div class="w-full h-[250px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white p-6 md:p-8 h-full">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center uppercase tracking-widest text-xs">
                <span class="h-2 w-2 bg-amber-500 rounded-full mr-3"></span> Top Produk (Kg)
            </h3>
            @php $maxBerat = ($topItems->max('total_berat') > 0) ? $topItems->max('total_berat') : 1; @endphp
            <div class="space-y-6">
                @forelse($topItems as $item)
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <p class="text-sm font-bold text-slate-700">{{ $item->barang->nama_barang }}</p>
                        <p class="text-xs font-black text-amber-600">{{ number_format($item->total_berat, 1) }} Kg</p>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full" style="width: {{ ($item->total_berat / $maxBerat) * 100 }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-slate-400 text-xs italic">Belum ada data penjualan</div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-3 bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden mt-4">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center uppercase tracking-widest text-xs">
                    <span class="h-2 w-2 bg-green-500 rounded-full mr-3"></span> Rincian Transaksi
                </h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] text-slate-400 uppercase font-bold tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Faktur & Tgl</th>
                            <th class="px-6 py-4">Pembeli / Admin</th>
                            <th class="px-6 py-4 text-center">Item</th>
                            <th class="px-6 py-4 text-center">Total Berat</th>
                            <th class="px-6 py-4 text-center">Lihat Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($laporanDetail as $laporan)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-blue-600">{{ $laporan->no_invoice ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal_transaksi)->format('d M Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-700">{{ $laporan->nama_pelanggan ?? 'Pelanggan Umum' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-bold mt-0.5">{{ $laporan->admin->name ?? 'Admin' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ $laporan->detailPenjualan->count() }} Tipe</span>
                            </td>
                            <td class="px-6 py-4 text-center font-black text-slate-800">
                                {{ number_format($laporan->detailPenjualan->sum('jumlah_berat'), 2) }} <span class="text-[10px] font-normal text-slate-400">Kg</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('manager.laporan.show', $laporan->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-blue-100 flex items-center justify-center inline-flex" title="Lihat Detail Barang">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-slate-400 text-sm italic">Belum ada transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporanDetail->hasPages())
            <div class="p-4 bg-slate-50/50 border-t border-slate-50">
                {{ $laporanDetail->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Data dilempar dari Controller via JSON
        const labels = {!! json_encode($chartDates) !!};
        const dataValues = {!! json_encode($chartTotals) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: dataValues,
                    borderColor: '#3b82f6', // Warna biru Tailwind
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4 // Bikin garisnya melengkung halus (smooth)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Sembunyiin legend biar bersih
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8' },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection