<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\PermintaanStok;
use App\Models\DetailPermintaanStok;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class PermintaanStokSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // MANTRA PEMBERSIH: Hapus data lama biar ga dobel
        // =========================================================
        Schema::disableForeignKeyConstraints();
        DetailPermintaanStok::truncate();
        PermintaanStok::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('Data permintaan stok lama berhasil dibersihkan!');

        // 1. Ambil data User (Tim Gudang yang request & Manajer yang ACC)
        $userGudang = User::where('role', 'tim_gudang')->first() ?? User::first();
        $userManajer = User::where('role', 'manajer')->first();
        $barangs = Barang::all();

        if ($barangs->isEmpty()) {
            $this->command->info('Data Barang kosong! Harap jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $keteranganList = [
            'Stok menipis untuk persiapan akhir pekan',
            'Permintaan rutin mingguan',
            'Stok untuk pesanan hajatan Pak Joko',
            'Restock gudang utama',
            'Penambahan stok untuk promo'
        ];

        // 2. Bikin 15 data PO (Purchase Order / Permintaan Stok)
        for ($i = 1; $i <= 15; $i++) {
            
            $tanggal = Carbon::now()->subDays(rand(0, 30));
            // Format PO menyesuaikan UI: PO-YYYYMMDD-XXX
            $noRequest = 'PO-' . $tanggal->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Bikin probabilitas: 40% PENDING (biar bisa dites manajer), sisanya DI-ACC / DITOLAK
            $statusOptions = ['PENDING', 'PENDING', 'DI-ACC', 'DI-ACC', 'DITOLAK'];
            $status = $statusOptions[array_rand($statusOptions)];

            $verifiedBy = null;
            $verifiedAt = null;

            // Kalau statusnya udah diproses, isi data verifikatornya
            if ($status !== 'PENDING' && $userManajer) {
                $verifiedBy = $userManajer->id;
                // Di-ACC/Ditolak sekitar 1-2 hari setelah di-request
                $verifiedAt = (clone $tanggal)->addHours(rand(12, 48)); 
            }

            // 3. Insert ke tabel permintaan_stoks
            $permintaan = PermintaanStok::create([
                'no_request' => $noRequest,
                'user_id' => $userGudang->id,
                'tanggal_request' => $tanggal,
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'status' => $status,
                'verified_by' => $verifiedBy,
                'verified_at' => $verifiedAt,
            ]);

            $jumlahItem = rand(1, 4);
            $barangRandom = $barangs->random($jumlahItem);

            // 4. Insert ke tabel detail_permintaan_stoks
            foreach ($barangRandom as $barang) {
                DetailPermintaanStok::create([
                    'permintaan_stok_id' => $permintaan->id,
                    'barang_id' => $barang->id,
                    'jumlah_unit' => rand(50, 200), // Minta restock 50-200 ekor/pack
                    'jumlah_berat' => rand(50, 250), // Estimasi 50kg - 250kg
                ]);
            }
        }

        $this->command->info('Mantap! 15 Data Dummy Permintaan Stok (PO) berhasil ditambahkan.');
    }
}