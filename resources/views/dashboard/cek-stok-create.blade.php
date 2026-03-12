@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Input Cek Stok Fisik</h1>
        <p class="text-slate-500 mt-1 text-sm">Catat jumlah riil barang di gudang. Sistem akan otomatis membandingkannya dengan database.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-6 md:p-10">
        <form action="{{ route('cek-stok.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Tanggal Pengecekan</label>
                    <input type="date" name="tanggal_cek" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Pilih Produk</label>
                    <select name="barang_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                        <option value="" disabled selected>-- Pilih Barang yang Dicek --</option>
                        @foreach($barangs as $item)
                            <option value="{{ $item->id }}">[{{ $item->sku }}] {{ $item->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6 mt-6">
                <h3 class="font-bold text-blue-800 mb-4 text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                    Hasil Perhitungan Fisik (Manual)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Sisa Fisik (Ekor)</label>
                        <input type="number" name="stok_ekor_fisik" step="0.01" placeholder="0"     
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-extrabold text-slate-800 text-lg">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Sisa Fisik (Kg)</label>
                        <input type="number" name="stok_berat_fisik" step="0.01" placeholder="0" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-extrabold text-blue-600 text-lg">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Ada 2 ekor ayam rusak di freezer sudut..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-600/30 flex items-center justify-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                <span>Simpan Data Pengecekan</span>
            </button>
        </form>
    </div>
</div>
@endsection