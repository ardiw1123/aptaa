@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Opname & Cek Fisik</h1>
            <p class="text-slate-500 mt-1 text-sm">Laporan perbandingan antara stok di database sistem dengan fisik di gudang.</p>
        </div>
        
        <a href="{{ route('cek-stok.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2 w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Input Cek Fisik Baru
        </a>
    </div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-[1.5rem] shadow-xl shadow-blue-200/20 border border-slate-100">
        <div class="flex items-center text-slate-500 font-bold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            Cek Snapshot Harian
        </div>
        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        @endif
        
        <form action="{{ route('cek-stok.index') }}" method="GET" class="flex flex-col sm:flex-row items-end sm:items-center gap-3 w-full md:w-auto">
            <div class="w-full sm:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium outline-none focus:border-blue-500 text-slate-700">
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0 pt-1">
                <button type="submit" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-blue-600/20">
                    Cari Data
                </button>
                @if(request()->filled('tanggal'))
                    <a href="{{ route('cek-stok.index') }}" class="flex-1 sm:flex-none text-center bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-5 font-bold">Tanggal & Petugas</th>
                        <th class="px-6 py-5 font-bold">Produk</th>
                        <th class="px-6 py-5 font-bold text-center bg-slate-100/50">Data Sistem</th>
                        <th class="px-6 py-5 font-bold text-center bg-blue-50/50">Cek Fisik</th>
                        <th class="px-6 py-5 font-bold text-center">Selisih (Variance)</th>
                        <th class="px-6 py-5 font-bold">Catatan</th>
                        <th class="px-6 py-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($cekStoks as $cek)
                        @php
                            // Hitung Selisih
                            $selisihEkor = $cek->stok_ekor_fisik - $cek->stok_ekor_sistem;
                            $selisihBerat = $cek->stok_berat_fisik - $cek->stok_berat_sistem;
                            
                            // Tentukan Warna Status
                            $isMinus = $selisihEkor < 0 || $selisihBerat < 0;
                            $isSurplus = $selisihEkor > 0 || $selisihBerat > 0;
                        @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800 text-sm">{{ \Carbon\Carbon::parse($cek->tanggal_cek)->format('d M Y') }}</p>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                {{ explode(' ', $cek->user->name)[0] }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-blue-600 text-sm">{{ $cek->barang->nama_barang }}</p>
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-wider">{{ $cek->barang->sku }}</span>
                        </td>

                        <td class="px-6 py-4 text-center bg-slate-50/30">
                            <p class="font-semibold text-slate-500 text-sm">{{ number_format($cek->stok_ekor_sistem, 0, ',', '.') }} <span class="text-xs">ekor</span></p>
                            <p class="font-semibold text-slate-500 text-sm">{{ number_format($cek->stok_berat_sistem, 2, ',', '.') }} <span class="text-xs">kg</span></p>
                        </td>

                        <td class="px-6 py-4 text-center bg-blue-50/30">
                            <p class="font-extrabold text-slate-800 text-sm">{{ number_format($cek->stok_ekor_fisik, 0, ',', '.') }} <span class="text-xs font-medium">ekor</span></p>
                            <p class="font-extrabold text-blue-600 text-sm">{{ number_format($cek->stok_berat_fisik, 2, ',', '.') }} <span class="text-xs font-medium">kg</span></p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($selisihEkor == 0 && $selisihBerat == 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Klop / Sesuai
                                </span>
                            @else
                                <div class="space-y-1">
                                    <p class="text-xs font-bold {{ $selisihEkor < 0 ? 'text-red-600' : ($selisihEkor > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                                        {{ $selisihEkor > 0 ? '+' : '' }}{{ number_format($selisihEkor, 0, ',', '.') }} ekor
                                    </p>
                                    <p class="text-xs font-bold {{ $selisihBerat < 0 ? 'text-red-600' : ($selisihBerat > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                                        {{ $selisihBerat > 0 ? '+' : '' }}{{ number_format($selisihBerat, 2, ',', '.') }} kg
                                    </p>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 max-w-[200px]">
                            @if($cek->catatan)
                                <p class="text-xs text-slate-600 line-clamp-2" title="{{ $cek->catatan }}">{{ $cek->catatan }}</p>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                        @if($cek->is_verified)
                            <div class="flex flex-col items-center justify-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-extrabold bg-green-100 text-green-700 uppercase tracking-wider mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Verified
                                </span>
                            </div>
                        @else
                            @if(auth()->id() == $cek->user_id)
                                <a href="{{ route('cek-stok.edit', $cek->id) }}" class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-colors border border-amber-200" title="Edit Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400 italic">Menunggu Verifikasi</span>
                            @endif
                        @endif
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-slate-500 font-medium">Belum ada riwayat pengecekan stok fisik.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($cekStoks->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50">
            {{ $cekStoks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection