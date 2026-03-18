<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan_pelanggans', function (Blueprint $table) {
            $table->id();
            // Relasi ke Header (Kalau nota pesanan dihapus, detail barangnya ikut kehapus)
            $table->foreignId('pesanan_pelanggan_id')->constrained('pesanan_pelanggans')->onDelete('cascade');
            
            // Relasi ke Master Barang (Biar tau yang dipesen Ayam Utuh, Kulit, dll)
            $table->foreignId('barang_id')->constrained('barangs');
            
            // Jumlah yang dipesan
            $table->decimal('jumlah_unit', 12, 2)->default(0);  // Ekor
            $table->decimal('jumlah_berat', 12, 2)->default(0); // Kg
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan_pelanggans');
    }
};