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

        // Ambil 3 Aktivitas terakhir pegawai ini
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

        $absen = Absensi::where('user_id', $user->id)->where('tanggal', $hariIni)->first();

        if (!$absen) {
            // === LOGIKA ABSEN MASUK & CEK KETERLAMBATAN ===
            $batasJam = '08:00:00';
            $isTerlambat = $waktuSekarang > $batasJam;
            
            $statusAbsen = 'Tepat Waktu';
            $tipeNotif = 'success';
            $pesanNotif = 'Berhasil Absen Masuk! Selamat bekerja!';

            if ($isTerlambat) {
                $statusAbsen = 'Terlambat';
                
                // Cek udah berapa kali terlambat bulan ini (sebelum absen hari ini)
                $riwayatTerlambat = Absensi::where('user_id', $user->id)
                                           ->whereMonth('tanggal', Carbon::now()->month)
                                           ->where('status', 'Terlambat')
                                           ->count();
                
                $sisaKuota = 3 - $riwayatTerlambat;

                if ($sisaKuota > 0) {
                    // Masih ada kuota (08:01 tapi baru terlambat ke-1 atau ke-2)
                    $sisaSekarang = $sisaKuota - 1; // Dikurang 1 karena hari ini kepakai
                    $tipeNotif = 'warning';
                    $pesanNotif = "Anda Terlambat! Sisa toleransi bulan ini tinggal {$sisaSekarang} kali.";
                } else {
                    // Kuota habis (udah terlambat 3x atau lebih)
                    $tipeNotif = 'error'; // Atau danger
                    $pesanNotif = "Batas toleransi terlambat (3x) HABIS! Absen dicatat sebagai Pelanggaran Kedisiplinan.";
                }
            }

            // Simpan data ke Database
            Absensi::create([
                'user_id' => $user->id,
                'tanggal' => $hariIni,
                'jam_masuk' => $waktuSekarang,
                'lokasi_masuk' => $koordinat,
                'status' => $statusAbsen
            ]);

            // Catat Log
            LogAktivitas::create(['user_id' => $user->id, 'modul' => 'Kepegawaian', 'aktivitas' => 'Melakukan Absen Masuk']);
            
            // Return dengan dinamis notifikasi (bisa success, warning, atau error)
            return back()->with($tipeNotif, $pesanNotif);
            
        } else {
            // === LOGIKA ABSEN KELUAR ===
            if ($absen->jam_keluar) {
                return back()->with('error', 'Anda sudah melakukan absen keluar hari ini!');
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