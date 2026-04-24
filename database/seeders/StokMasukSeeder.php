<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\StokMasuk;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class StokMasukSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // MANTRA PEMBERSIH: Hapus data lama biar ga dobel
        // =========================================================
        Schema::disableForeignKeyConstraints();
        StokMasuk::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('Data stok masuk lama berhasil dibersihkan!');

        // 1. Ambil data User (Tim Gudang yang input & admin yang ACC)
        $userGudang = User::where('role', 'tim_gudang')->first() ?? User::first();
        $admin = User::where('role', 'admin')->first();
        $barangs = Barang::all();

        if ($barangs->isEmpty()) {
            $this->command->info('Data Barang kosong! Harap jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $hubList = [
            'Hub Brebes (Pusat)', 
            'Hub Cirebon', 
            'Hub Jakarta Timur', 
            'Hub Bandung',
            'Hub Semarang'
        ];

        $keteranganList = [
            'Penerimaan stok dari peternak mitra',
            'Barang masuk batch pagi',
            'Restock mingguan',
            'Pengiriman darurat dari supplier',
            'Stok tambahan persiapan akhir pekan'
        ];

        // 2. Bikin 25 data Stok Masuk dummy
        for ($i = 1; $i <= 25; $i++) {
            
            $tanggal = Carbon::now()->subDays(rand(0, 30))->subHours(rand(1, 12));
            $barang = $barangs->random();

            // Bikin probabilitas: 30% Belum Terverifikasi (false), 70% Sudah (true)
            $isVerified = rand(1, 10) > 3 ? true : false;

            $verifiedBy = null;
            $verifiedAt = null;

            // Kalau udah diverifikasi, isi data admin yang nge-ACC
            if ($isVerified && $admin) {
                $verifiedBy = $admin->id;
                // Waktu verifikasi biasanya beberapa jam setelah barang masuk
                $verifiedAt = (clone $tanggal)->addHours(rand(2, 24));
            }

            // 3. Insert ke tabel stok_masuks
            StokMasuk::create([
                'barang_id' => $barang->id,
                'user_id' => $userGudang->id,
                'tanggal_masuk' => $tanggal,
                'jumlah_unit' => rand(100, 1000), // Masuk 100 - 1000 ekor/pack
                // Kasih desimal dikit biar data beratnya realistis (contoh: 150.5 kg)
                'jumlah_berat' => rand(100, 1500) + (rand(0, 99) / 100), 
                'is_verified' => $isVerified, // Pakai boolean asli buat PostgreSQL
                'nama_hub' => $hubList[array_rand($hubList)],
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'verified_by' => $verifiedBy,
                'verified_at' => $verifiedAt,
            ]);
        }

        $this->command->info('Mantap! 25 Data Dummy Stok Masuk berhasil ditambahkan.');
    }
}