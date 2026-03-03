@extends('layouts.app') 

@section('content')
<div class="max-w-4xl mx-auto pb-10">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-bold transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tim Gudang / Input Stok</span>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-3xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-blue-200/40 border border-white overflow-hidden">
        
        <div class="px-8 md:px-12 py-10 border-b border-blue-50 bg-gradient-to-r from-blue-50/50 to-white">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Form Pencatatan Stok Masuk</h1>
            <p class="text-slate-500 mt-1 text-sm leading-relaxed">Pindahkan data dari laporan fisik ke sistem digital APTAA secara akurat.</p>
        </div>

        <form action="{{ route('stok_masuk.store') }}" method="POST" class="p-8 md:p-12 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}"
                        class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium">
                    @error('tanggal_masuk') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Lokasi HUB</label>
                    <input type="text" name="nama_hub" placeholder="Contoh: MUNDU" value="{{ old('nama_hub') }}"
                        class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300 font-medium">
                    @error('nama_hub') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Pilih Produk / Item</label>
                <div class="relative">
                    <select name="barang_id" 
                        class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none transition-all font-medium">
                        <option value="" disabled selected>Cari berdasarkan SKU atau Nama...</option>
                        @foreach($barangs as $item)
                            <option value="{{ $item->id }}">[{{ $item->sku }}] {{ $item->nama_barang }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('barang_id') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <hr class="border-blue-50">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Jumlah (EKOR)</label>
                    <div class="relative">
                        <input type="number" name="jumlah_unit" step="0.01" placeholder="0" 
                            class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-lg">
                        <div class="absolute inset-y-0 right-6 flex items-center text-slate-400 font-bold text-xs uppercase tracking-widest">Ekor</div>
                    </div>
                    <p class="mt-2 text-[10px] text-slate-400 font-bold italic ml-1">* Kosongkan jika bukan produk ayam utuh</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Total Berat (KG / LITER)</label>
                    <div class="relative">
                        <input type="number" name="jumlah_berat" step="0.01" placeholder="0.00" 
                            class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-lg text-blue-600">
                        <div class="absolute inset-y-0 right-6 flex items-center text-slate-400 font-bold text-xs uppercase tracking-widest">Kg/Ltr</div>
                    </div>
                    @error('jumlah_berat') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Catatan Tambahan</label>
                <textarea name="keterangan" rows="3" placeholder="Contoh: Kondisi barang baik, diterima oleh Security..."
                    class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300 font-medium"></textarea>
            </div>

            <div class="pt-6">
                <button type="submit" 
                    class="w-full bg-slate-900 hover:bg-blue-600 text-white font-bold py-5 rounded-[1.8rem] shadow-xl shadow-blue-900/20 transform hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 flex items-center justify-center space-x-3 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span class="text-lg uppercase tracking-widest">Simpan Data Stok</span>
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em]">Secured Data Entry System v1.2 • APTAA Management</p>
    </div>
</div>
@endsection