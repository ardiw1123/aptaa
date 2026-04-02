@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('permintaan-stok.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-500 hover:text-amber-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Revisi Permintaan Stok</h1>
            <p class="text-slate-500 mt-1 text-sm">
                No. Surat: <span class="font-bold text-amber-600">{{ $po->no_request }}</span> 
                (Masih berstatus <span class="uppercase font-bold text-amber-500 text-[10px] tracking-wider">Pending</span>)
            </p>
        </div>
    </div>

    <form action="{{ route('permintaan-stok.update', $po->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="bg-white rounded-[2rem] shadow-xl shadow-amber-200/20 border border-white p-6 md:p-8 mb-8">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center uppercase tracking-widest text-xs">
                <span class="h-2 w-2 bg-amber-500 rounded-full mr-3"></span> Revisi Info Surat
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Tanggal Request</label>
                    <input type="date" name="tanggal_request" value="{{ \Carbon\Carbon::parse($po->tanggal_request)->format('Y-m-d') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-medium text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Keterangan / Catatan Admin</label>
                    <input type="text" name="keterangan" value="{{ $po->keterangan }}" placeholder="Contoh: Stok tambahan untuk pesanan Partai Besar besok"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-medium text-slate-700">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-amber-200/20 border border-white overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center uppercase tracking-widest text-xs">
                    <span class="h-2 w-2 bg-blue-500 rounded-full mr-3"></span> Revisi Daftar Barang
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 font-bold w-1/4">Produk</th>
                            <th class="px-6 py-4 font-bold text-center bg-slate-50/50">Stok Fisik Gudang</th>
                            <th class="px-6 py-4 font-bold text-center bg-green-50/30">Pesanan Klien</th>
                            <th class="px-6 py-4 font-bold text-center bg-amber-50/30">Revisi Ekor</th>
                            <th class="px-6 py-4 font-bold text-center bg-amber-50/30">Revisi Kg</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($barangs as $index => $item)
                            @php
                                // 1. Acuan Fisik
                                $acuanEkor = $item->latestCekStok ? $item->latestCekStok->stok_ekor_fisik : $item->stok_ekor;
                                $acuanBerat = $item->latestCekStok ? $item->latestCekStok->stok_berat_fisik : $item->stok_berat;
                                
                                // 2. Acuan Pesanan Marketing
                                $butuhEkor = isset($pesananHariIni[$item->id]) ? $pesananHariIni[$item->id]->total_unit : 0;
                                $butuhBerat = isset($pesananHariIni[$item->id]) ? $pesananHariIni[$item->id]->total_berat : 0;
                                
                                // 3. Ambil Nilai dari Keranjang PO Sebelumnya (Kalau ada)
                                $qtyEkor = isset($keranjangLama[$item->id]) ? $keranjangLama[$item->id]['unit'] : '';
                                $qtyBerat = isset($keranjangLama[$item->id]) ? $keranjangLama[$item->id]['berat'] : '';
                            @endphp
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <input type="hidden" name="barang_id[{{ $index }}]" value="{{ $item->id }}">
                            
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 text-sm">{{ $item->nama_barang }}</p>
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
                                @else
                                    <p class="font-bold text-slate-300 text-sm">-</p>
                                @endif
                            </td>

                            <td class="px-6 py-3 bg-amber-50/10">
                                <input type="number" name="jumlah_unit[{{ $index }}]" value="{{ $qtyEkor }}" step="0.01" placeholder="0"
                                    class="w-full text-center px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-extrabold text-slate-800 text-sm transition-all">
                            </td>

                            <td class="px-6 py-3 bg-amber-50/10">
                                <input type="number" name="jumlah_berat[{{ $index }}]" value="{{ $qtyBerat }}" step="0.01" placeholder="0"
                                    class="w-full text-center px-3 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none font-extrabold text-amber-600 text-sm transition-all">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="w-full md:w-auto px-8 bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-amber-500/30 flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            <span>Simpan Perubahan Surat</span>
        </button>
    </form>
</div>
@endsection