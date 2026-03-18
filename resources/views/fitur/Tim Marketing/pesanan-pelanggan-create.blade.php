@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Input Pesanan Pelanggan</h1>
        <p class="text-slate-500 mt-1 text-sm">Catat pesanan dari klien. Data ini masih bisa diedit sebelum dikirim ke Admin.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('pesanan-pelanggan.store') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-6 md:p-8 mb-8">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center uppercase tracking-widest text-xs">
                <span class="h-2 w-2 bg-blue-600 rounded-full mr-3"></span> Info Klien & Pesanan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Nama Pelanggan / Restoran</label>
                    <input type="text" name="nama_pelanggan" placeholder="Contoh: Resto Ayam Geprek Pak Budi" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Tanggal Pesanan</label>
                    <input type="date" name="tanggal_pesanan" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Tipe Pesanan</label>
                    <select name="tipe" required class="w-full md:w-1/3 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                        <option value="" disabled selected>-- Pilih Tipe --</option>
                        <option value="B2B">B2B (Bisnis/Restoran)</option>
                        <option value="B2C">B2C (Konsumen Langsung)</option>
                        <option value="Partai">Partai Besar</option>
                        <option value="Eceran">Eceran</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center uppercase tracking-widest text-xs">
                    <span class="h-2 w-2 bg-amber-500 rounded-full mr-3"></span> Keranjang Barang
                </h3>
                <span class="text-[10px] font-bold text-slate-400 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                    Isi angka > 0 pada barang yang dipesan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 font-bold w-1/2">Produk Tersedia</th>
                            <th class="px-6 py-4 font-bold text-center bg-blue-50/30">Dipesan (Ekor)</th>
                            <th class="px-6 py-4 font-bold text-center bg-blue-50/30">Dipesan (Kg)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($barangs as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <input type="hidden" name="barang_id[{{ $index }}]" value="{{ $item->id }}">
                            
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 text-sm">{{ $item->nama_barang }}</p>
                            </td>

                            <td class="px-6 py-3 bg-blue-50/10">
                                <input type="number" name="jumlah_unit[{{ $index }}]" step="0.01" placeholder="0"
                                    class="w-full text-center px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-extrabold text-slate-800 text-sm transition-all">
                            </td>

                            <td class="px-6 py-3 bg-blue-50/10">
                                <input type="number" name="jumlah_berat[{{ $index }}]" step="0.01" placeholder="0"
                                    class="w-full text-center px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-extrabold text-blue-600 text-sm transition-all">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="w-full md:w-auto px-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-600/30 flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
            <span>Simpan Sebagai Draft</span>
        </button>
    </form>
</div>
@endsection