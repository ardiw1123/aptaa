@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Verifikasi Permintaan Stok</h1>
        <p class="text-slate-500 mt-1 text-sm">Overview pengajuan barang dari Admin. Klik "Review" untuk melihat detail.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-5 font-bold">No. Request & Tgl</th>
                        <th class="px-6 py-5 font-bold">Admin</th>
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
                            <p class="text-xs text-slate-700 font-bold">{{ $po->pembuat->name }}</p>
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
                                <a href="{{ route('manager.permintaan-stok.show', $po->id) }}" class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-200 text-[10px] font-bold uppercase tracking-widest">
                                    Review
                                </a>
                                
                                <a href="{{ route('permintaan-stok.pdf', $po->id) }}" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-200" title="PDF">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </a>
                                <a href="{{ route('permintaan-stok.excel', $po->id) }}" class="p-1.5 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white rounded-lg transition-colors border border-green-200" title="Excel">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                            <p class="text-sm italic">Belum ada surat PO masuk.</p>
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