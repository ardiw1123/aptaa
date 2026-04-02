@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Buat Permintaan Stok (PO)</h1>
        <p class="text-slate-500 mt-1 text-sm">Ajukan pesanan barang berdasarkan sinkronisasi data stok saat ini dan pesanan pelanggan.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('permintaan-stok.store') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-6 md:p-8 mb-8">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center uppercase tracking-widest text-xs">
                <span class="h-2 w-2 bg-blue-600 rounded-full mr-3"></span> Info Surat PO
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Tanggal Request</label>
                    <input type="date" name="tanggal_request" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Keterangan / Catatan Admin</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Stok tambahan untuk pesanan Partai Besar besok"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center uppercase tracking-widest text-xs">
                    <span class="h-2 w-2 bg-amber-500 rounded-full mr-3"></span> Input Permintaan Barang
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 font-bold w-1/4">Produk</th>
                            <th class="px-6 py-4 font-bold text-center bg-slate-50/50">Stok Tersedia</th>
                            <th class="px-6 py-4 font-bold text-center bg-green-50/30">Pesanan Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-center bg-blue-50/30">Input PO (Ekor)</th>
                            <th class="px-6 py-4 font-bold text-center bg-blue-50/30">Input PO (Kg)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($barangs as $index => $item)
                            @php
                                // 1. Acuan Fisik (Prioritas dari Opname yang di-ACC)
                                $acuanEkor = $item->latestCekStok ? $item->latestCekStok->stok_ekor_fisik : $item->stok_ekor;
                                $acuanBerat = $item->latestCekStok ? $item->latestCekStok->stok_berat_fisik : $item->stok_berat;
                                
                                // 2. Acuan Pesanan Marketing (Jika ada)
                                $butuhEkor = isset($pesananHariIni[$item->id]) ? $pesananHariIni[$item->id]->total_unit : 0;
                                $butuhBerat = isset($pesananHariIni[$item->id]) ? $pesananHariIni[$item->id]->total_berat : 0;
                                
                                // 3. Indikator Warning Kurang Stok (Simple logic: kalau pesanan > stok fisik)
                                $isKurang = ($butuhEkor > $acuanEkor) || ($butuhBerat > $acuanBerat);
                            @endphp
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <input type="hidden" name="barang_id[{{ $index }}]" value="{{ $item->id }}">
                            
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 text-sm">{{ $item->nama_barang }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $item->sku }}</p>
                            </td>

                            <td class="px-6 py-4 text-center bg-slate-50/30">
                                <p class="font-bold text-slate-700 text-sm">
                                    {{ number_format($acuanEkor, 0) }} <span class="text-[9px] text-slate-400 uppercase font-normal">ekor</span> | 
                                    {{ number_format($acuanBerat, 2) }} <span class="text-[9px] text-slate-400 uppercase font-normal">kg</span>
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center bg-green-50/10">
                                @if($butuhEkor > 0 || $butuhBerat > 0)
                                    <p class="font-extrabold text-green-700 text-sm">
                                        {{ number_format($butuhEkor, 0) }} <span class="text-[9px] text-green-500 uppercase font-normal">ekor</span> | 
                                        {{ number_format($butuhBerat, 2) }} <span class="text-[9px] text-green-500 uppercase font-normal">kg</span>
                                    </p>
                                    @if($isKurang)
                                        <p class="text-[9px] text-red-500 mt-1 uppercase font-bold tracking-wider animate-pulse">Defisit Stok!</p>
                                    @endif
                                @else
                                    <p class="font-bold text-slate-300 text-sm">-</p>
                                @endif
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
            </svg>
            <span>Buat Permintaan Stok</span>
        </button>
    </form>
</div>
@endsection