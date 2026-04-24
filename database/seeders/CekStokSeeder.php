<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\CekStok;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class CekStokSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // MANTRA PEMBERSIH: Hapus data lama biar ga dobel
        // =========================================================
        Schema::disableForeignKeyConstraints();
        CekStok::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('Data cek stok lama berhasil dibersihkan!');

        // 1. Ambil data User
        $userBarang = User::where('role', 'tim_barang')->first() ?? User::first();
        $admin = User::where('role', 'admin')->first();
        $barangs = Barang::all();

        if ($barangs->isEmpty()) {
            $this->command->info('Data Barang kosong! Harap jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        // 2. Bikin 20 data Cek Stok (Opname) dummy
        for ($i = 1; $i <= 20; $i++) {
            
            $barang = $barangs->random();
            $tanggal = Carbon::now()->subDays(rand(0, 30))->subHours(rand(1, 8));

            $stokSistemEkor = rand(50, 300);
            $stokSistemBerat = $stokSistemEkor * 1.2;

            $isAman = rand(1, 10) > 4;

            if ($isAman) {
                $stokFisikEkor = $stokSistemEkor;
                $stokFisikBerat = $stokSistemBerat;
                $catatan = 'Stok fisik aman, sesuai dengan sistem.';
            } else {
                $stokFisikEkor = $stokSistemEkor - rand(0, 3);
                $stokFisikBerat = $stokSistemBerat - (rand(5, 30) / 10); 
                
                $catatanOpsi = [
                    'Ada penyusutan berat karena efek freezer',
                    'Ditemukan 2 ekor mati di kandang penampungan',
                    'Selisih timbangan fisik, sudah disesuaikan',
                    'Ayam mengalami susut perjalanan'
                ];
                $catatan = $catatanOpsi[array_rand($catatanOpsi)];
            }

            // Status Verifikasi
            $isVerified = rand(1, 10) > 4 ? true : false;
            $verifiedBy = null;
            $verifiedAt = null; // INI YANG TADI KETINGGALAN BRO

            if ($isVerified && $admin) {
                $verifiedBy = $admin->id;
                // Logika: admin nge-ACC sekitar 1-5 jam setelah dicek sama tim barang
                $verifiedAt = (clone $tanggal)->addHours(rand(1, 5)); 
            }

            // 3. Insert ke tabel cek_stoks persis sesuai kolom lo
            CekStok::create([
                'tanggal_cek' => $tanggal,
                'barang_id' => $barang->id,
                'user_id' => $userBarang->id,
                'stok_ekor_sistem' => $stokSistemEkor,
                'stok_berat_sistem' => round($stokSistemBerat, 2),
                'stok_ekor_fisik' => $stokFisikEkor,
                'stok_berat_fisik' => round($stokFisikBerat, 2),
                'catatan' => $catatan,
                'is_verified' => $isVerified,
                'verified_by' => $verifiedBy,
                'verified_at' => $verifiedAt, 
            ]);
        }

        $this->command->info('Mantap! 20 Data Dummy Cek Stok (Revisi) berhasil ditambahkan.');
    }
}