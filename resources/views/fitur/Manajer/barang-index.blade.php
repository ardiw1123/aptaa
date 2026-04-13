@extends('layouts.app')

@section('content')
<main x-data="{ showWarning: false, editUrl: '' }" class="flex-1 px-6 pb-6 pt-2 md:px-10 md:pb-10 md:pt-4 max-w-7xl mx-auto w-full">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Master Data Barang</h1>
            <p class="text-slate-500 mt-1 text-sm">Monitor daftar produk dan komoditas yang terdaftar di dalam sistem.</p>
        </div>
        
        <a href="{{ route('manajer.barang.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all active:scale-95 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Barang Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-widest">
                        <th class="px-8 py-5 font-bold">SKU & Nama Barang</th>
                        <th class="px-8 py-5 font-bold">Kategori</th>
                        <th class="px-8 py-5 font-bold">Satuan</th>
                        <th class="px-8 py-5 font-bold">Stok Sistem</th>
                        <th class="px-8 py-5 font-bold text-center">Aksi</th> 
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($barangs as $barang)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        
                        <td class="px-8 py-5">
                            <p class="font-black text-blue-600 text-sm mb-0.5">{{ $barang->sku }}</p>
                            <p class="font-bold text-slate-700">{{ $barang->nama_barang }}</p>
                        </td>
                        
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold border border-slate-200">{{ $barang->kategori }}</span>
                        </td>
                        
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-slate-500">{{ $barang->satuan_utama }}</span>
                        </td>
                        
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-100">
                                    <span class="text-xs font-bold">{{ $barang->stok_ekor }} Ekor</span>
                                </div>
                                <div class="bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg border border-amber-100">
                                    <span class="text-xs font-bold">{{ number_format($barang->stok_berat, 2, ',', '.') }} Kg</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5 text-center">
                            <button @click="showWarning = true; editUrl = '{{ route('manajer.barang.edit', $barang->id) }}'" 
                                    class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all focus:outline-none" title="Edit Barang">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                            <p class="text-slate-500 font-bold">Belum ada data barang.</p>
                            <p class="text-slate-400 text-sm mt-1">Klik tombol 'Tambah Barang Baru' untuk mulai mendaftarkan produk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showWarning" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div x-show="showWarning" x-transition.opacity @click="showWarning = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <div x-show="showWarning" x-transition.scale.origin.bottom class="relative bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl border border-amber-100 text-center">
            
            <div class="w-16 h-16 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            
            <h3 class="text-xl font-black text-slate-800 mb-2">Peringatan Modifikasi!</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                Mengubah Master Data (seperti SKU atau Kategori) dapat memengaruhi riwayat stok dan laporan penjualan sebelumnya. Pastikan Anda mengubahnya dengan benar. Lanjutkan?
            </p>
            
            <div class="flex gap-4">
                <button @click="showWarning = false" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">Batal</button>
                <a :href="editUrl" class="flex-1 px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all">Ya, Lanjut Edit</a>
            </div>
        </div>
    </div>

</main>
@endsection