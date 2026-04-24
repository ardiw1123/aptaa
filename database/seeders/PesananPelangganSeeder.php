<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\PesananPelanggan;
use App\Models\DetailPesananPelanggan;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class PesananPelangganSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================
        // MANTRA PEMBERSIH: Hapus data lama biar ga dobel
        // =========================================================
        Schema::disableForeignKeyConstraints();
        DetailPesananPelanggan::truncate();
        PesananPelanggan::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('Data pesanan lama berhasil dibersihkan!');

        // 1. Ambil data User & Barang buat direlasikan
        $userMarketing = User::where('role', 'tim_marketing')->first() ?? User::first();
        $barangs = Barang::all();

        if ($barangs->isEmpty()) {
            $this->command->info('Data Barang kosong! Harap jalankan DatabaseSeeder (master) terlebih dahulu.');
            return;
        }

        // List pelanggan langganan fiktif
        $pelangganList = [
            'Katering Barokah',
            'Ayam Geprek Kampus',
            'Warung Nasi Padang Jaya',
            'Supermarket Lokal',
            'Bapak Joko (Hajatan)',
            'Resto Cepat Saji Cabang Y'
        ];

        $tipePesanan = ['Grosir', 'Eceran', 'Pre-Order'];

        // 2. Bikin 20 pesanan dummy
        for ($i = 1; $i <= 20; $i++) {
            
            // Acak tanggal sebulan ke belakang
            $tanggal = Carbon::now()->subDays(rand(0, 30));
            $noPesanan = 'ORD-' . $tanggal->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Insert ke tabel pesanan_pelanggans
            $pesanan = PesananPelanggan::create([
                'user_id' => $userMarketing->id,
                'no_pesanan' => $noPesanan,
                'nama_pelanggan' => $pelangganList[array_rand($pelangganList)],
                'tanggal_pesanan' => $tanggal,
                'tipe' => $tipePesanan[array_rand($tipePesanan)],
                'is_sent' => rand(0, 1) == 1 ? true : false, // Random status pengiriman
            ]);

            // Acak jumlah macam barang per pesanan (1 sampai 3 macam)
            $jumlahItem = rand(1, 3);
            $barangRandom = $barangs->random($jumlahItem);

            // 3. Insert ke tabel detail_pesanan_pelanggans
            foreach ($barangRandom as $barang) {
                DetailPesananPelanggan::create([
                    'pesanan_pelanggan_id' => $pesanan->id,
                    'barang_id' => $barang->id,
                    'jumlah_unit' => rand(10, 50), // Pesen 10 - 50 ekor/pack
                    'jumlah_berat' => rand(15, 60), // Estimasi 15kg - 60kg
                ]);
            }
        }

        $this->command->info('Mantap! 20 Data Dummy Pesanan Pelanggan berhasil ditambahkan.');
    }
}