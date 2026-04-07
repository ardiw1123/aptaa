@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 md:py-10 px-4">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-800">Riwayat Stok Masuk (Harian)</h1>
        <p class="text-slate-500 text-sm mt-1">Daftar manifest barang masuk dari Tim Gudang per hari.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest">Tanggal Masuk</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Total Item</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Kuantitas Total</th>
                        <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-bold tracking-widest text-center">Status Verifikasi</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatHarian as $riwayat)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($riwayat->tanggal_masuk)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">
                            {{ $riwayat->total_macam_barang }} Jenis
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-800">{{ number_format($riwayat->total_ekor) }} Ekor</span>
                            <br>
                            <span class="text-xs font-bold text-blue-600">{{ number_format($riwayat->total_berat, 2) }} Kg</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($riwayat->jumlah_pending > 0)
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">
                                    {{ $riwayat->jumlah_pending }} Butuh ACC
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">
                                    Selesai Di-ACC
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.stok-masuk.detail', $riwayat->tanggal_masuk) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-700 transition-colors">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400 text-sm">Belum ada riwayat stok masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayatHarian->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $riwayatHarian->links() }}
        </div>
        @endif
    </div>
</div>
@endsection