<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori: Ayam Utuh
        Barang::create([
            'sku' => 'AYM-0.8',
            'nama_barang' => 'Ayam Utuh 0.7 - 0.8',
            'kategori' => 'Ayam Utuh',
            'satuan_utama' => 'Ekor',
            'stok_ekor' => 0,
            'stok_berat' => 0,
        ]);

        // Kategori: Sampingan (Ayam)
        Barang::create([
            'sku' => 'SMP-KULIT',
            'nama_barang' => 'Kulit Ayam',
            'kategori' => 'Sampingan',
            'satuan_utama' => 'Kg',
            'stok_ekor' => 0,
            'stok_berat' => 0,
        ]);

        // Kategori: Komoditas Lainnya
        Barang::create([
            'sku' => 'KOM-TELUR',
            'nama_barang' => 'Telur Ayam',
            'kategori' => 'Komoditas',
            'satuan_utama' => 'Kg',
            'stok_ekor' => 0,
            'stok_berat' => 0,
        ]);

        Barang::create([
            'sku' => 'KOM-MINYAK',
            'nama_barang' => 'Minyak Goreng',
            'kategori' => 'Komoditas',
            'satuan_utama' => 'Liter',
            'stok_ekor' => 0,
            'stok_berat' => 0,
        ]);
    }
}