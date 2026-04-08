@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 md:py-10 px-4">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.stok-masuk.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 hover:text-blue-600 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Detail Stok Masuk</h1>
                <p class="text-sm text-slate-500">Tanggal: <span class="font-bold text-slate-700">{{ $tanggalFormat }}</span></p>
            </div>
        </div>

        <div class="flex gap-2">
            </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest">Barang</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Input Oleh</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Kuantitas</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest">Hub/Lokasi</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($detailStok as $item)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $item->barang->nama_barang }}</p>
                            <p class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">ID: {{ $item->barang_id }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-sm font-medium text-slate-600">{{ $item->user->name ?? 'Gudang' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase italic">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-block text-left">
                                <p class="text-sm font-bold text-slate-800">{{ number_format($item->jumlah_unit) }} <span class="text-[10px] text-slate-400 font-normal">Ekor</span></p>
                                <p class="text-sm font-bold text-blue-600">{{ number_format($item->jumlah_berat, 2) }} <span class="text-[10px] text-blue-400 font-normal">Kg</span></p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-500 italic">
                            {{ $item->nama_hub ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_verified == 0)
                                <form action="{{ route('admin.stok-masuk.acc', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-all shadow-lg shadow-slate-800/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Verifikasi
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold border border-slate-200 cursor-default">
                                    Sudah Valid
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400 text-sm italic">Data barang tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection