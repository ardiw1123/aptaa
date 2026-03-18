@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Daftar Pesanan Pelanggan</h1>
            <p class="text-slate-500 mt-1 text-sm">Kelola draft pesanan dan kirim data final ke Admin untuk diproses.</p>
        </div>
        
        <a href="{{ route('pesanan-pelanggan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2 w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Pesanan Baru
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
                        <th class="px-6 py-5 font-bold">No. Pesanan & Tgl</th>
                        <th class="px-6 py-5 font-bold">Pelanggan</th>
                        <th class="px-6 py-5 font-bold text-center">Total Item</th>
                        <th class="px-6 py-5 font-bold text-center">Status</th>
                        <th class="px-6 py-5 font-bold text-center">Aksi (Kirim Data)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pesanans as $pesanan)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <p class="font-bold text-blue-600 text-sm">{{ $pesanan->no_pesanan }}</p>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5">
                                {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y') }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800 text-sm">{{ $pesanan->nama_pelanggan }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-bold rounded uppercase tracking-wider">
                                Tipe: {{ $pesanan->tipe }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-600 text-sm">
                                {{ $pesanan->detailPesanan->count() }} <span class="text-[10px] font-normal text-slate-400 uppercase">Produk</span>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($pesanan->is_sent)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-extrabold rounded-full uppercase tracking-wider flex items-center justify-center gap-1 w-max mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Terkirim
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[10px] font-extrabold rounded-full uppercase tracking-wider flex items-center justify-center gap-1 w-max mx-auto animate-pulse">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Draft
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($pesanan->is_sent)
                                <span class="text-[10px] text-slate-300 italic font-medium">Terkunci</span>
                            @else
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('pesanan-pelanggan.edit', $pesanan->id) }}" class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-colors border border-amber-200" title="Edit Pesanan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>

                                    <form action="{{ route('pesanan-pelanggan.kirim', $pesanan->id) }}" method="POST" onsubmit="return confirm('Kirim data ini ke Admin? Setelah dikirim, data tidak bisa diedit lagi.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg transition-colors shadow-md shadow-blue-600/20 uppercase tracking-widest">
                                            Kirim Data
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-slate-500 font-medium">Belum ada data pesanan pelanggan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pesanans->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50">
            {{ $pesanans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection