@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 md:py-10 px-4">
    
    <a href="{{ route('manager.laporan.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-blue-600 transition-colors mb-6 font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Kembali
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Pembeli</p>
                <h2 class="text-2xl font-bold text-slate-800">{{ $penjualan->nama_pelanggan ?? 'Pelanggan Umum' }}</h2>
                <p class="text-sm text-slate-500 mt-1">Tanggal Transaksi: {{ \Carbon\Carbon::parse($penjualan->tanggal_transaksi)->format('d F Y') }}</p>
            </div>
            <div class="md:text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status Saat Ini</p>
                <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-bold tracking-wide">
                    BERHASIL
                </span>
            </div>
        </div>

        <hr class="border-slate-100 mb-8">

        <div class="flex items-center gap-3 mb-6">
            <span class="h-2.5 w-2.5 bg-blue-600 rounded-full"></span>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-widest">Rincian Barang</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest rounded-l-xl">Nama Barang</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Jumlah (Ekor)</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-right rounded-r-xl">Berat (Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($penjualan->detailPenjualan as $item)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-700">
                            {{ $item->barang->nama_barang }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-700 text-center">
                            {{ number_format($item->jumlah_unit) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-blue-600 text-right">
                            {{ number_format($item->jumlah_berat, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr class="border-t-2 border-slate-100">
                    <td class="py-6 font-bold text-slate-400 uppercase text-xs">Total Keseluruhan</td>
                    <td class="py-6 text-center font-black text-slate-800">{{ number_format($penjualan->detailPenjualan->sum('jumlah_unit')) }} Ekor</td>
                    <td class="py-6 text-right font-black text-blue-600 text-lg">{{ number_format($penjualan->detailPenjualan->sum('jumlah_berat'), 2) }} Kg</td>
                </tr>
            </tfoot>
            </table>
        </div>
            <div class="mt-4 p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                <p class="text-xs text-slate-400 font-medium">Diinput oleh Admin: <span class="text-slate-700 font-bold">{{ $penjualan->admin->name ?? 'System' }}</span></p>
                <div class="flex gap-2">
                    </div>
            </div>
    </div>
</div>
@endsection