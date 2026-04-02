@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Permintaan Stok (PO)</h1>
            <p class="text-slate-500 mt-1 text-sm">Monitor status pengajuan barang ke Supplier dan Manajer.</p>
        </div>
        
        <a href="{{ route('permintaan-stok.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2 w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat PO Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-5 font-bold">No. Request & Tgl</th>
                        <th class="px-6 py-5 font-bold">Keterangan</th>
                        <th class="px-6 py-5 font-bold text-center">Status</th>
                        <th class="px-6 py-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($permintaans as $po)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <p class="font-bold text-blue-600 text-sm">{{ $po->no_request }}</p>
                            <p class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase">
                                {{ \Carbon\Carbon::parse($po->tanggal_request)->format('d M Y') }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-600 font-medium line-clamp-1 max-w-xs">{{ $po->keterangan ?? '-' }}</p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($po->status == 'approved')
                                <div class="flex flex-col items-center justify-center">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-extrabold rounded-full uppercase tracking-wider mb-1">Di-ACC</span>
                                    <span class="text-[8px] text-slate-400 font-bold uppercase">Oleh {{ explode(' ', $po->manajer->name ?? 'Manajer')[0] }}</span>
                                </div>
                            @elseif($po->status == 'rejected')
                                <span class="px-3 py-1 bg-red-100 text-red-600 text-[9px] font-extrabold rounded-full uppercase tracking-wider">Ditolak</span>
                            @else
                                <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[9px] font-extrabold rounded-full uppercase tracking-wider animate-pulse">Menunggu</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($po->status == 'pending')
                                <a href="{{ route('permintaan-stok.edit', $po->id) }}" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-colors border border-amber-200" title="Edit PO">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                @else
                                <span class="text-[10px] text-slate-300 italic">Locked</span>
                                @endif
                                
                                <button class="p-2 bg-slate-50 text-slate-300 rounded-lg border border-slate-100 cursor-not-allowed" title="Fitur Cetak Segera Hadir">
                                    <a href="{{ route('permintaan-stok.pdf', $po->id) }}" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg border border-red-200" title="PDF">
                                        PDF
                                    </a>
                                    <a href="{{ route('permintaan-stok.excel', $po->id) }}" class="p-2 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white rounded-lg border border-green-200" title="Excel">
                                        Excel
                                    </a>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                            <p class="text-sm italic">Belum ada riwayat permintaan stok.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($permintaans->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50">
            {{ $permintaans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection