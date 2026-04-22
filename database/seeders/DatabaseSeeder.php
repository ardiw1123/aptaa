<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Barang;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. SEEDER TABEL USERS (Sesuai Skema lo)
        // ==========================================
        $users = [
            [
                'name' => 'Bapak Manajer',
                'email' => 'manajer@aptaa.com',
                'password' => Hash::make('password123'),
                'role' => 'manajer',
            ],
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@aptaa.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Tim Gudang (Penerimaan)',
                'email' => 'gudang@aptaa.com',
                'password' => Hash::make('password123'),
                'role' => 'tim_gudang',
            ],
            [
                'name' => 'Tim Barang (Cek Fisik)',
                'email' => 'barang@aptaa.com',
                'password' => Hash::make('password123'),
                'role' => 'tim_barang',
            ],
            [
                'name' => 'Tim Marketing',
                'email' => 'marketing@aptaa.com',
                'password' => Hash::make('password123'),
                'role' => 'tim_marketing',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // ==========================================
        // 2. SEEDER TABEL BARANGS (Sesuai Skema lo)
        // ==========================================
        $barangs = [
            [
                'sku' => 'AYM-UTUH-001',
                'nama_barang' => 'Ayam Broiler Utuh Grade A',
                'kategori' => 'Ayam Utuh',
                'satuan_utama' => 'Ekor',
                'stok_ekor' => 150,
                'stok_berat' => 180.5, // Kg
            ],
            [
                'sku' => 'AYM-PTG-002',
                'nama_barang' => 'Dada Ayam Fillet (Boneless)',
                'kategori' => 'Ayam Potong',
                'satuan_utama' => 'Kilogram (KG)',
                'stok_ekor' => 0,
                'stok_berat' => 55.0, // Kg
            ],
            [
                'sku' => 'SMP-ATI-003',
                'nama_barang' => 'Ati Ampela Bersih',
                'kategori' => 'Sampingan',
                'satuan_utama' => 'Pack',
                'stok_ekor' => 0,
                'stok_berat' => 20.0, // Kg
            ],
            [
                'sku' => 'KMD-DOC-004',
                'nama_barang' => 'Ayam Hidup (DOC)',
                'kategori' => 'Komoditas',
                'satuan_utama' => 'Ekor',
                'stok_ekor' => 500,
                'stok_berat' => 50.0, // Kg
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        $this->command->info('Mantap Bro! Data dummy Users dan Barangs berhasil di-seed.');
    }
}