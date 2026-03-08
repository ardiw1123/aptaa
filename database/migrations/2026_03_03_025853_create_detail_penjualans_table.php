<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            // onDelete('cascade') artinya kalau struk (header) dihapus, rinciannya ikut kehapus otomatis
            $table->foreignId('penjualan_id')->constrained('penjualans')->onDelete('cascade'); 
            $table->foreignId('barang_id')->constrained('barangs');
            
            // Sama kaya stok masuk, kita pisah ekor dan berat (kg/liter)
            $table->decimal('jumlah_unit', 12, 2)->nullable(); 
            $table->decimal('jumlah_berat', 12, 2)->nullable(); 
            
            // INI PENTING: Nyimpen harga saat transaksi terjadi buat laporan bulanan
            $table->decimal('harga_satuan', 15, 2); 
            $table->decimal('subtotal', 15, 2); // jumlah * harga_satuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};