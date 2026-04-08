<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // 1. TAMPILIN DASHBOARD PEGAWAI
    public function index()
    {
        $user = Auth::user();
        $role = Auth::user()->role;
        $hariIni = Carbon::today()->format('Y-m-d');
        
        // Cek status absen hari ini
        $absenHariIni = Absensi::where('user_id', $user->id)
                               ->where('tanggal', $hariIni)
                               ->first();

        // Hitung Performa (Bulan ini hadir berapa kali)
        $totalHadirBulanIni = Absensi::where('user_id', $user->id)
                                     ->whereMonth('tanggal', Carbon::now()->month)
                                     ->count();

        // Ambil 5 Aktivitas terakhir pegawai ini
        $logAktivitas = LogAktivitas::where('user_id', $user->id)
                                    ->latest()
                                    ->take(3)
                                    ->get();

        return view('layouts.dashboard', compact('absenHariIni', 'totalHadirBulanIni', 'logAktivitas'));
    }

    // 2. PROSES ABSEN (MASUK / KELUAR) BY LOCATION
    public function prosesAbsen(Request $request)
    {
        $user = Auth::user();
        $hariIni = Carbon::today()->format('Y-m-d');
        $waktuSekarang = Carbon::now()->format('H:i:s');
        
        // Tangkap kordinat GPS dari frontend
        $koordinat = $request->latitude . ',' . $request->longitude;

        /* * OPSIONAL: Di sini lo bisa masukin rumus "Haversine" buat ngecek jarak
         * radius kordinat ini dengan kordinat Gudang/Kantor APTAA.
         * Kalau kejauhan (> 100 meter), bisa di-return error "Anda di luar jangkauan kantor!"
         */

        $absen = Absensi::where('user_id', $user->id)->where('tanggal', $hariIni)->first();

        if (!$absen) {
            // BELUM ABSEN MASUK -> Bikin absen masuk
            Absensi::create([
                'user_id' => $user->id,
                'tanggal' => $hariIni,
                'jam_masuk' => $waktuSekarang,
                'lokasi_masuk' => $koordinat,
                'status' => ($waktuSekarang > '08:00:00') ? 'Terlambat' : 'Tepat Waktu' // Misal batas jam 8
            ]);

            // Catat Log
            LogAktivitas::create(['user_id' => $user->id, 'modul' => 'Kepegawaian', 'aktivitas' => 'Melakukan Absen Masuk']);
            
            return back()->with('success', 'Berhasil Absen Masuk! Selamat bekerja!');
        } else {
            // UDAH ABSEN MASUK -> Berarti sekarang Absen Keluar
            if ($absen->jam_keluar) {
                return back()->with('error', 'udah absen keluar hari ini!');
            }

            $absen->update([
                'jam_keluar' => $waktuSekarang,
                'lokasi_keluar' => $koordinat
            ]);

            // Catat Log
            LogAktivitas::create(['user_id' => $user->id, 'modul' => 'Kepegawaian', 'aktivitas' => 'Melakukan Absen Pulang']);

            return back()->with('success', 'Berhasil Absen Pulang. Hati-hati di jalan!');
        }
    }
}