@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Penjualan</h1>
            <p class="text-slate-500 mt-1 text-sm">Pantau transaksi keluar dan total pendapatan kotor.</p>
        </div>
        
        <form action="{{ route('penjualan.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium outline-none focus:border-blue-500 text-slate-700">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium outline-none focus:border-blue-500 text-slate-700">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-md shadow-blue-600/20">
                    Filter
                </button>
                @if(request()->has('start_date') || request()->has('end_date'))
                    <a href="{{ route('penjualan.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="mb-6 bg-slate-900 rounded-2xl p-6 flex items-center justify-between text-white shadow-xl shadow-slate-900/20 border border-slate-800">
        <div>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Total Pendapatan (Berdasarkan Filter)</p>
            <h2 class="text-3xl font-extrabold text-green-400 tracking-tight">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
        <div class="hidden sm:block p-3 bg-white/10 rounded-xl backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-5 font-bold">No. Invoice</th>
                        <th class="px-6 py-5 font-bold">Tanggal</th>
                        <th class="px-6 py-5 font-bold">Pembeli</th>
                        <th class="px-6 py-5 font-bold">Item</th>
                        <th class="px-6 py-5 font-bold text-right">Total Transaksi</th>
                        <th class="px-6 py-5 font-bold text-center">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($penjualans as $trx)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-blue-600 text-sm">{{ $trx->no_invoice }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 text-sm">{{ $trx->nama_pembeli ?? 'Umum / Tanpa Nama' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                                {{ $trx->detailPenjualans->count() }} Jenis
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-extrabold text-slate-800">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center" title="{{ $trx->admin->name ?? 'Unknown' }}">
                                <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs border-2 border-white shadow-sm">
                                    {{ strtoupper(substr($trx->admin->name ?? 'U', 0, 2)) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-slate-500 font-medium">Tidak ada data transaksi pada rentang tanggal tersebut.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($penjualans->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50">
            {{ $penjualans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection