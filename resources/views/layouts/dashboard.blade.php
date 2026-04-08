@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6 md:py-10 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800">Halo, {{ Auth::user()->name }}!</h1>
        <p class="text-slate-500 mt-1">Siap untuk produktif hari ini? Jangan lupa absen ya.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-2 space-y-8">
            
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-blue-200/20 border border-slate-100 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full opacity-50"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-1">Status Kehadiran Hari Ini</h3>
                        
                        @if(!$absenHariIni)
                            <h2 class="text-3xl font-black text-slate-800">Belum Absen</h2>
                            <p class="text-sm text-slate-500 mt-2">Batas absen masuk pukul 08:00 WIB.</p>
                        @elseif($absenHariIni && !$absenHariIni->jam_keluar)
                            <h2 class="text-3xl font-black text-green-600">Sedang Bekerja</h2>
                            <p class="text-sm text-slate-500 mt-2">Masuk pukul: <span class="font-bold">{{ $absenHariIni->jam_masuk }}</span> WIB</p>
                        @else
                            <h2 class="text-3xl font-black text-slate-400">Selesai Bekerja</h2>
                            <p class="text-sm text-slate-500 mt-2">Pulang pukul: <span class="font-bold">{{ $absenHariIni->jam_keluar }}</span> WIB</p>
                        @endif
                    </div>

                    <form action="{{ route('pegawai.absen.proses') }}" method="POST" id="form-absen">
                        @csrf
                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="long">
                        
                        @if(!$absenHariIni)
                            <button type="button" onclick="getLocationAndSubmit()" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-lg shadow-lg shadow-blue-500/30 transition-all flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                Absen Masuk
                            </button>
                        @elseif($absenHariIni && !$absenHariIni->jam_keluar)
                            <button type="button" onclick="getLocationAndSubmit()" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-lg shadow-lg shadow-amber-500/30 transition-all flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Absen Pulang
                            </button>
                        @endif
                    </form>
                </div>
                <p id="lokasi-status" class="text-xs text-blue-500 font-medium mt-4 hidden animate-pulse">📍 Sedang mencari koordinat GPS Anda...</p>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 uppercase tracking-widest text-xs mb-6">Performa Bulan Ini</h3>
                <div class="flex items-center gap-6">
                    <div class="h-24 w-24 rounded-full border-8 border-green-500 flex items-center justify-center">
                        <span class="text-2xl font-black text-slate-800">{{ $totalHadirBulanIni }}</span>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800">Total Hari Masuk</p>
                        <p class="text-sm text-slate-500 mt-1">Tingkatkan terus kedisiplinan Anda. Data kehadiran ini mempengaruhi evaluasi bulanan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-800 uppercase tracking-widest text-xs mb-6">Aktivitas Terakhir Anda</h3>
            
            <div class="relative border-l-2 border-slate-100 ml-3 space-y-8">
                @forelse($logAktivitas as $log)
                <div class="relative pl-6">
                    <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full bg-blue-100 border-2 border-blue-500"></span>
                    
                    <p class="text-xs font-bold text-blue-600 mb-1">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
                    <p class="text-sm font-bold text-slate-800">{{ $log->aktivitas }}</p>
                    <p class="text-xs text-slate-500 mt-1">Modul: {{ $log->modul }}</p>
                </div>
                @empty
                <div class="pl-6">
                    <p class="text-sm text-slate-400 italic">Belum ada aktivitas tercatat di sistem.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    function getLocationAndSubmit() {
        const statusText = document.getElementById('lokasi-status');
        statusText.classList.remove('hidden');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Berhasil dapet lokasi
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('long').value = position.coords.longitude;
                    
                    statusText.innerText = "📍 Kordinat didapat. Menyimpan absensi...";
                    
                    // Submit formnya
                    document.getElementById('form-absen').submit();
                },
                function(error) {
                    // Gagal dapet lokasi (user nolak, atau GPS mati)
                    statusText.classList.add('hidden');
                    alert("Akses lokasi gagal! Pastikan GPS menyala dan berikan izin akses lokasi pada browser untuk melakukan absen.");
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("Browser Anda tidak mendukung fitur Geolocation.");
        }
    }
</script>
@endsection