@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 md:py-10">
    
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Monitor Ketersediaan Stok</h1>
        <p class="text-slate-500 mt-1 text-sm">Pantau ketersediaan barang secara real-time dan log pengiriman terbaru.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center uppercase tracking-widest text-xs">
                        <span class="h-2 w-2 bg-blue-600 rounded-full mr-3"></span> Stok Tersedia (Master)
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                <th class="px-6 py-4 font-bold">Produk (SKU)</th>
                                <th class="px-6 py-4 font-bold text-center bg-slate-50/50">Stok Sistem</th>
                                <th class="px-6 py-4 font-bold text-center bg-blue-50/50">Stok Fisik Terakhir</th>
                                <th class="px-6 py-4 font-bold text-center">Status / Info</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($barangs as $item)
                                @php
                                    // Cek apakah barang ini sudah pernah di-opname
                                    $hasOpname = $item->latestCekStok ? true : false;
                                    
                                    // Tentukan angka acuan (Prioritas: Fisik > Sistem)
                                    $acuanEkor = $hasOpname ? $item->latestCekStok->stok_ekor_fisik : $item->stok_ekor;
                                    $acuanBerat = $hasOpname ? $item->latestCekStok->stok_berat_fisik : $item->stok_berat;
                                    
                                    // Tentukan Status Peringatan berdasarkan angka acuan
                                    $isKritis = $acuanEkor <= 5 || $acuanBerat <= 5;
                                    $isMenipis = $acuanEkor <= 15 || $acuanBerat <= 15;
                                @endphp
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-sm">{{ $item->nama_barang }}</p>
                                    <p class="text-xs text-slate-400 font-semibold">{{ $item->sku }}</p>
                                </td>

                                <td class="px-6 py-4 text-center bg-slate-50/30">
                                    <p class="font-semibold text-slate-500 text-sm">{{ number_format($item->stok_ekor, 0, ',', '.') }} <span class="text-[10px] uppercase">ekor</span></p>
                                    <p class="font-semibold text-slate-500 text-sm">{{ number_format($item->stok_berat, 2, ',', '.') }} <span class="text-[10px] uppercase">kg</span></p>
                                </td>

                                <td class="px-6 py-4 text-center bg-blue-50/30">
                                    @if($hasOpname)
                                        <p class="font-extrabold text-slate-800 text-sm">{{ number_format($item->latestCekStok->stok_ekor_fisik, 0, ',', '.') }} <span class="text-[10px] uppercase font-medium">ekor</span></p>
                                        <p class="font-extrabold text-blue-600 text-sm">{{ number_format($item->latestCekStok->stok_berat_fisik, 2, ',', '.') }} <span class="text-[10px] uppercase font-medium">kg</span></p>
                                        <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-wider font-bold" title="Diopname oleh: {{ $item->latestCekStok->user->name }}">
                                            Cek: {{ \Carbon\Carbon::parse($item->latestCekStok->tanggal_cek)->format('d/m/Y') }}
                                        </p>
                                    @else
                                        <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded">Belum Diopname</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center flex flex-col items-center justify-center gap-2">
                                @if($hasOpname && !$item->latestCekStok->is_verified)
                                    <form action="{{ route('cek-stok.verify', $item->latestCekStok->id) }}" method="POST" onsubmit="return confirm('Verifikasi opname untuk {{ $item->nama_barang }}?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full max-w-[100px] px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-extrabold rounded-lg uppercase tracking-wider shadow-md shadow-blue-600/30 flex items-center justify-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            ACC Fisik
                                        </button>
                                    </form>
                                @elseif($hasOpname && $item->latestCekStok->is_verified)
                                    <span class="px-3 py-1 bg-green-50 text-green-600 border border-green-200 text-[9px] font-bold rounded-full uppercase flex items-center gap-1 w-full max-w-[100px] justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944a11.954 11.954 0 007.834 3.055 1 1 0 01.508.863v5.631a12.02 12.02 0 01-6.793 10.74 1 1 0 01-.898 0A12.02 12.02 0 012.166 11.54v-5.63a1 1 0 01.508-.863z" clip-rule="evenodd" /></svg>
                                        Tervalidasi
                                    </span>
                                @endif

                                @if($isKritis)
                                    <span class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-extrabold rounded-full uppercase tracking-wider animate-pulse w-full max-w-[100px]">Kritis</span>
                                @elseif($isMenipis)
                                    <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[10px] font-extrabold rounded-full uppercase tracking-wider w-full max-w-[100px]">Menipis</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-extrabold rounded-full uppercase tracking-wider w-full max-w-[100px]">Aman</span>
                                @endif
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-slate-900 rounded-[2rem] shadow-xl shadow-slate-900/20 border border-slate-800 p-8 text-white">
                <h3 class="font-bold text-white mb-6 flex items-center uppercase tracking-widest text-xs">
                    <span class="h-2 w-2 bg-green-400 rounded-full mr-3 animate-ping absolute"></span>
                    <span class="h-2 w-2 bg-green-400 rounded-full mr-3 relative"></span> 
                    Stok Datang Terbaru
                </h3>

                <div class="space-y-6">
                    @forelse($stokMasuks as $masuk)
                    <div class="flex items-center justify-between border-b border-slate-800 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start space-x-4">
                            <div class="p-2 bg-slate-800 rounded-xl text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-300">{{ $masuk->barang->nama_barang }}</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    Masuk: <span class="text-white font-semibold">{{ $masuk->jumlah_berat > 0 ? $masuk->jumlah_berat.' Kg' : $masuk->jumlah_unit.' Ekor' }}</span>
                                </p>
                                <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-wider">
                                    Oleh {{ explode(' ', $masuk->user->name)[0] }} • {{ \Carbon\Carbon::parse($masuk->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="ml-4">
                            @if($masuk->is_verified)
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 border border-green-500/30 text-[9px] font-bold rounded flex items-center gap-1 uppercase tracking-widest" title="ACC oleh {{ $masuk->verifikator->name ?? 'Admin' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    ACC
                                </span>
                            @else
                                <form action="{{ route('stok-masuk.verify', $masuk->id) }}" method="POST" onsubmit="return confirm('Verifikasi stok masuk ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold rounded shadow-md transition-colors uppercase tracking-widest">
                                        Verifikasi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-slate-500 text-sm">Belum ada stok masuk hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection