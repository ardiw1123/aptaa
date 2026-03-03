@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 md:py-10"> {{-- py-10 ini yang bikin form lo nggak mepet atas-bawah --}}
    
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('stok_masuk.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-bold transition-colors bg-white/50 px-4 py-2 rounded-full shadow-sm backdrop-blur-sm border border-blue-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Riwayat
        </a>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-white/50 px-3 py-1.5 rounded-full border border-slate-100">Mode Edit Data</span>
    </div>

    <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-blue-200/40 border border-white overflow-hidden">
        
        <div class="px-8 md:px-12 py-10 border-b border-amber-50 bg-gradient-to-r from-amber-50/50 to-white">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Koreksi Data Stok</h1>
            </div>
            <p class="text-slate-500 text-sm leading-relaxed ml-11">Perbaiki kesalahan input. Sistem akan otomatis menyesuaikan ulang di data master.</p>
        </div>

        <form action="{{ route('stok_masuk.update', $stok->id) }}" method="POST" class="p-8 md:p-12 space-y-8">
            @csrf
            @method('PUT') {{-- WAJIB ADA: Penanda bahwa ini adalah proses UPDATE, bukan INSERT --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $stok->tanggal_masuk) }}"
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-medium text-slate-700">
                    @error('tanggal_masuk') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Lokasi HUB</label>
                    <input type="text" name="nama_hub" value="{{ old('nama_hub', $stok->nama_hub) }}"
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-medium text-slate-700">
                    @error('nama_hub') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Produk / Item</label>
                <div class="relative">
                    <select name="barang_id" 
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none appearance-none transition-all font-medium text-slate-700">
                        @foreach($barangs as $item)
                            <option value="{{ $item->id }}" {{ (old('barang_id', $stok->barang_id) == $item->id) ? 'selected' : '' }}>
                                [{{ $item->sku }}] {{ $item->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('barang_id') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <hr class="border-slate-100 my-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Jumlah (EKOR)</label>
                    <div class="relative">
                        <input type="number" name="jumlah_unit" step="0.01" value="{{ old('jumlah_unit', $stok->jumlah_unit) }}"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-lg text-slate-800">
                        <div class="absolute inset-y-0 right-6 flex items-center text-slate-400 font-bold text-xs uppercase tracking-widest">Ekor</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Total Berat (KG / LITER)</label>
                    <div class="relative">
                        <input type="number" name="jumlah_berat" step="0.01" value="{{ old('jumlah_berat', $stok->jumlah_berat) }}"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-lg text-amber-600">
                        <div class="absolute inset-y-0 right-6 flex items-center text-slate-400 font-bold text-xs uppercase tracking-widest">Kg/Ltr</div>
                    </div>
                    @error('jumlah_berat') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Catatan / Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-medium text-slate-700">{{ old('keterangan', $stok->keterangan) }}</textarea>
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <a href="{{ route('stok_masuk.index') }}" 
                    class="w-full md:w-1/3 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 font-bold py-5 rounded-[1.8rem] flex items-center justify-center transition-all duration-300">
                    Batal
                </a>
                
                <button type="submit" 
                    class="w-full md:w-2/3 bg-slate-900 hover:bg-amber-500 text-white font-bold py-5 rounded-[1.8rem] shadow-xl shadow-slate-900/20 transform hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 flex items-center justify-center space-x-3 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-lg uppercase tracking-widest">Update Data Stok</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection