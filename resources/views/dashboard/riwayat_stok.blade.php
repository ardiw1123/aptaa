@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Stok Datang</h1>
            <p class="text-slate-500 mt-1 text-sm">Daftar rekapan barang masuk dari berbagai lokasi HUB.</p>
        </div>
        <a href="{{ route('stok_masuk.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Input Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-50/50 text-slate-500 text-xs uppercase tracking-widest border-b border-blue-50">
                        <th class="px-6 py-5 font-bold">Tanggal</th>
                        <th class="px-6 py-5 font-bold">HUB</th>
                        <th class="px-6 py-5 font-bold">Produk (SKU)</th>
                        <th class="px-6 py-5 font-bold text-right">Ekor</th>
                        <th class="px-6 py-5 font-bold text-right">Berat (Kg/Ltr)</th>
                        <th class="px-6 py-5 font-bold text-center">Petugas</th>
                        <th class="px-6 py-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatStok as $stok)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($stok->tanggal_masuk)->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">{{ $stok->nama_hub ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-blue-600">{{ $stok->barang->nama_barang }}</p>
                            <p class="text-xs text-slate-400 font-semibold">{{ $stok->barang->sku }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-slate-800">{{ $stok->jumlah_unit > 0 ? number_format($stok->jumlah_unit, 0, ',', '.') : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-green-600">{{ $stok->jumlah_berat > 0 ? number_format($stok->jumlah_berat, 2, ',', '.') : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center" title="{{ $stok->user->name }}">
                                <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($stok->user->name, 0, 2)) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($stok->is_verified)
                                <div class="flex flex-col items-center justify-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-extrabold bg-green-100 text-green-700 uppercase tracking-wider mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        Verified
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('stok_masuk.edit', $stok->id) }}" class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-colors border border-amber-200" title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                            <p class="text-slate-500 font-medium">Belum ada data stok masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection