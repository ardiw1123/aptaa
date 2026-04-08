@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 md:py-10 px-4">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pesanan.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 hover:text-blue-600 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Detail PO: PO-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-sm text-slate-500">Pelanggan: <span class="font-bold text-slate-700">{{ $pesanan->nama_pelanggan }}</span></p>
            </div>
        </div>
        <div>
            @if($pesanan->status == 'pending')
                <span class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-sm font-bold border border-amber-100 shadow-sm">Status: Menunggu Stok</span>
            @else
                <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-sm font-bold border border-green-100 shadow-sm">Status: Diproses</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex gap-2 items-center">
            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-widest">Rincian Kebutuhan Barang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest border-b border-slate-100">Nama Barang</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center border-b border-slate-100">Target Kuantitas (Ekor)</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-right border-b border-slate-100">Estimasi Berat (Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pesanan->detailPesanan as $detail)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $detail->barang->nama_barang }}</p>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">
                            {{ number_format($detail->jumlah_unit) }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-blue-600">
                            {{ number_format($detail->jumlah_berat, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-slate-400 text-sm italic">Tidak ada rincian barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection