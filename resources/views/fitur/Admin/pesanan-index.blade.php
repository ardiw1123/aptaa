@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6 md:py-10 px-4">
    
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-1">
            <h1 class="text-2xl font-extrabold text-slate-800">Daftar Pesanan Pelanggan</h1>    
        </div>
        <p class="text-sm text-slate-500">Rekap pesanan dari tim Marketing. Silakan klik detail untuk melihat detail pesanan</p>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest">No. Pesanan & Tanggal</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest">Pelanggan</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Total Item</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($daftarPesanan as $pesanan)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">PO-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-700">{{ $pesanan->nama_pelanggan }}</p>
                            <p class="text-[10px] text-slate-400 uppercase">Sales: {{ $pesanan->marketing->name ?? 'Tim Marketing' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-800">
                            {{ $pesanan->total_macam_barang }} Jenis
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($pesanan->status == 'pending')
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold border border-amber-100">Menunggu Stok</span>
                            @else
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-bold border border-green-100">Diproses</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.pesanan.detail', $pesanan->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-all shadow-sm">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400 text-sm italic">Belum ada pesanan dari Marketing.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($daftarPesanan->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $daftarPesanan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection