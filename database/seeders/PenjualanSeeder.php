<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\Schema;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {


    Schema::disableForeignKeyConstraints();
        DetailPenjualan::truncate();
        Penjualan::truncate();
        Schema::enableForeignKeyConstraints();
        
        $this->command->info('Data penjualan lama berhasil dibersihkan!');
        
        // 1. Ambil data User (Tim Marketing) & Barang buat direlasikan
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $barangs = Barang::all();

        // Jaga-jaga kalau data barang kosong
        if ($barangs->isEmpty()) {
            $this->command->info('Data Barang kosong! Harap jalankan DatabaseSeeder (master) terlebih dahulu.');
            return;
        }

        // List nama pelanggan fiktif biar realistis
        $pembeliList = [
            'Warung Nasi Uduk Bu Siti', 
            'Restoran Padang Sederhana', 
            'Ayam Geprek Bensu Cabang X', 
            'Katering Barokah', 
            'Pasar Tradisional Pak Budi',
            'Sate Taichan Senayan',
            'Supermarket SuperIndo'
        ];

        // 2. Kita bikin 15 transaksi dummy dengan tanggal acak (1-30 hari ke belakang)
        for ($i = 1; $i <= 15; $i++) {
            
            // Bikin tanggal random di bulan ini
            $tanggal = Carbon::now()->subDays(rand(0, 30));
            
            // Format No Invoice (Contoh: INV-20260424-001)
            $noInvoice = 'INV-' . $tanggal->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Insert ke tabel penjualans (total_harga di-set 0 dulu)
            $penjualan = Penjualan::create([
                'no_invoice' => $noInvoice,
                'user_id' => $admin->id,
                'nama_pembeli' => $pembeliList[array_rand($pembeliList)],
                'tanggal_transaksi' => $tanggal,
                'total_harga' => 0, 
                'keterangan' => 'Penjualan reguler B2B',
            ]);

            $totalHargaTransaksi = 0;
            $jumlahItemBarang = rand(1, 3); // Tiap transaksi beli 1 sampai 3 macam barang
            
            // Ambil barang secara acak sesuai jumlah item
            $barangRandom = $barangs->random($jumlahItemBarang);

            // 3. Insert ke tabel detail_penjualans
            foreach ($barangRandom as $barang) {
                $jumlahBerat = rand(10, 50); // Beli 10 kg - 50 kg
                $jumlahUnit = rand(5, 20); // 5 - 20 ekor/pack
                
                // Harga satuan acak antara 30.000 sampai 45.000
                $hargaSatuan = rand(30, 45) * 1000; 
                
                // Hitung subtotal (misal patokannya dari berat per kg)
                $subtotal = $jumlahBerat * $hargaSatuan;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barang->id,
                    'jumlah_unit' => $jumlahUnit,
                    'jumlah_berat' => $jumlahBerat,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                ]);

                // Tambahkan subtotal ke total harga transaksi
                $totalHargaTransaksi += $subtotal;
            }

            // 4. Update total_harga yang sebenarnya di tabel penjualans
            $penjualan->update(['total_harga' => $totalHargaTransaksi]);
        }

        $this->command->info('Mantap! 15 Data Dummy Penjualan dan Detailnya berhasil ditambahkan.');
    }
}