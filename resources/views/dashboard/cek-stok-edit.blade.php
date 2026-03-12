@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('cek-stok.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-500 hover:text-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Edit Cek Stok Fisik</h1>
            <p class="text-slate-500 mt-1 text-sm">Koreksi kesalahan input untuk <span class="font-bold text-blue-600">{{ $cekStok->barang->nama_barang }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-6 md:p-10">
        <div class="flex flex-col sm:flex-row gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100 mb-6">
            <div class="flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pengecekan</p>
                <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($cekStok->tanggal_cek)->format('d M Y') }}</p>
            </div>
            <div class="flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Data Sistem Saat Itu</p>
                <p class="font-bold text-slate-700">{{ number_format($cekStok->stok_ekor_sistem, 0, ',', '.') }} Ekor | {{ number_format($cekStok->stok_berat_sistem, 2, ',', '.') }} Kg</p>
            </div>
        </div>

        <form action="{{ route('cek-stok.update', $cekStok->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-6">
                <h3 class="font-bold text-amber-800 mb-4 text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Koreksi Hasil Fisik
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Revisi Fisik (Ekor)</label>
                        <input type="number" name="stok_ekor_fisik" value="{{ $cekStok->stok_ekor_fisik }}" step="0.01" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-extrabold text-slate-800 text-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Revisi Fisik (Kg)</label>
                        <input type="number" name="stok_berat_fisik" value="{{ $cekStok->stok_berat_fisik }}" step="0.01" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-extrabold text-amber-600 text-lg">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Revisi Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-medium text-slate-700">{{ $cekStok->catatan }}</textarea>
            </div>

            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-amber-500/30">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection