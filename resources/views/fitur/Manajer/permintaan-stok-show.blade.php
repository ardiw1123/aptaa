@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('manager.permintaan-stok.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-500 hover:text-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Detail Purchase Order</h1>
            <p class="text-slate-500 mt-1 text-sm">Rincian barang untuk surat <span class="font-bold text-blue-600">{{ $po->no_request }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-6 md:p-8 mb-6">
        <div class="flex flex-col md:flex-row justify-between gap-6 mb-6 pb-6 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Diajukan Oleh Admin</p>
                <p class="text-lg font-bold text-slate-800">{{ $po->pembuat->name }}</p>
                <p class="text-xs text-slate-500 mt-1">Tanggal Request: {{ \Carbon\Carbon::parse($po->tanggal_request)->format('d F Y') }}</p>
            </div>
            <div class="text-left md:text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Saat Ini</p>
                @if($po->status == 'approved')
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-xs font-extrabold uppercase tracking-widest inline-block">Di-ACC</span>
                @elseif($po->status == 'rejected')
                    <span class="px-4 py-2 bg-red-100 text-red-600 rounded-lg text-xs font-extrabold uppercase tracking-widest inline-block">Ditolak</span>
                @else
                    <span class="px-4 py-2 bg-amber-100 text-amber-600 rounded-lg text-xs font-extrabold uppercase tracking-widest inline-block animate-pulse">Menunggu Review</span>
                @endif
            </div>
        </div>

        <h3 class="font-bold text-slate-800 mb-4 flex items-center uppercase tracking-widest text-xs">
            <span class="h-2 w-2 bg-blue-600 rounded-full mr-3"></span> Rincian Barang
        </h3>
        
        <div class="overflow-hidden border border-slate-100 rounded-2xl mb-6">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] text-slate-400 uppercase font-bold tracking-widest">
                    <tr>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3 text-center">Jumlah (Ekor)</th>
                        <th class="px-6 py-3 text-center">Berat (Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @foreach($po->detailPermintaan as $detail)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $detail->barang->nama_barang }}</td>
                        <td class="px-6 py-3 text-center font-extrabold text-slate-800">{{ number_format($detail->jumlah_unit, 0) }}</td>
                        <td class="px-6 py-3 text-center font-extrabold text-blue-600">{{ number_format($detail->jumlah_berat, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($po->keterangan)
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Catatan Admin</p>
            <p class="text-sm text-slate-600 italic">"{{ $po->keterangan }}"</p>
        </div>
        @endif

        @if($po->status == 'pending')
        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-4 justify-end">
            <form action="{{ route('manager.permintaan-stok.verify', $po->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan ini?');">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-600 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Tolak Surat
                </button>
            </form>
            
            <form action="{{ route('manager.permintaan-stok.verify', $po->id) }}" method="POST" onsubmit="return confirm('ACC permintaan ini? Surat akan terkunci.');">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Setujui & ACC
                </button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection