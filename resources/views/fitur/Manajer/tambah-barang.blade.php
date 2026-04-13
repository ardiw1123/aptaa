@extends('layouts.app') 

@section('content')
<main class="flex-1 px-6 pb-6 pt-2 md:px-10 md:pb-10 md:pt-4 max-w-4xl mx-auto w-full">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('pegawai.dashboard') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-bold transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Manajemen / Master Data</span>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-3xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-3xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-3xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span class="font-bold text-sm">Gagal menyimpan! Silakan periksa kembali form isian di bawah.</span>
    </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-blue-200/40 border border-white overflow-hidden">
        
        <div class="px-8 md:px-12 pt-10 pb-5 border-b border-blue-50 bg-gradient-to-r from-blue-50/50 to-white">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Tambah Data Barang</h1>
            <p class="text-slate-500 mt-1 text-sm leading-relaxed">Daftarkan produk baru yang akan dikelola dalam stok dan penjualan APTAA.</p>
        </div>

        <form action="{{ route('manajer.barang.store') }}" method="POST" class="px-8 md:px-12 pt-6 pb-12 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Kode SKU</label>
                    <input type="text" name="sku" placeholder="Contoh: AYM-UTUH-01" value="{{ old('sku') }}"
                        class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-slate-800 uppercase">
                    @error('sku') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                    <p class="mt-2 text-[10px] text-slate-400 font-bold italic ml-1">* Pastikan SKU unik dan belum pernah dipakai</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Nama Produk / Barang</label>
                    <input type="text" name="nama_barang" placeholder="Contoh: Ayam Broiler Utuh Grade A" value="{{ old('nama_barang') }}"
                        class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300 font-medium text-slate-800">
                    @error('nama_barang') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Kategori Produk</label>
                    <div class="relative">
                        <select name="kategori" class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none transition-all font-bold text-slate-800">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="Ayam Utuh" {{ old('kategori') == 'Ayam Utuh' ? 'selected' : '' }}>Ayam Utuh</option>
                            <option value="Sampingan" {{ old('kategori') == 'Sampingan' ? 'selected' : '' }}>Sampingan</option>
                            <option value="Komoditas" {{ old('kategori') == 'Komoditas' ? 'selected' : '' }}>Komoditas</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('kategori') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 ml-1 uppercase tracking-wider">Satuan Utama</label>
                    <div class="relative">
                        <select name="satuan_utama" class="w-full px-6 py-4 bg-blue-50/30 border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none transition-all font-bold text-slate-800">
                            <option value="" disabled selected>Pilih Satuan...</option>
                            <option value="Kilogram (KG)" {{ old('satuan_utama') == 'Kilogram (KG)' ? 'selected' : '' }}>Kilogram (KG)</option>
                            <option value="Ekor" {{ old('satuan_utama') == 'Ekor' ? 'selected' : '' }}>Ekor</option>
                            <option value="Liter" {{ old('satuan_utama') == 'Liter' ? 'selected' : '' }}>Liter</option>
                            <option value="Pack" {{ old('satuan_utama') == 'Pack' ? 'selected' : '' }}>Pack/Dus</option>
                        </select>
                        <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('satuan_utama') <p class="mt-2 text-xs text-red-500 ml-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" 
                    class="w-full bg-slate-900 hover:bg-blue-600 text-white font-bold py-5 rounded-[1.8rem] shadow-xl shadow-blue-900/20 transform hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 flex items-center justify-center space-x-3 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="text-lg uppercase tracking-widest">Simpan Data Barang</span>
                </button>
            </div>
        </form>
    </div>
</main>
@endsection