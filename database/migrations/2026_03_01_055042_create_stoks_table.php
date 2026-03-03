<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('barangs', function (Blueprint $table) {
    $table->id();
    $table->string('sku')->unique(); // Contoh: AYM-1.5, SMP-KULIT, KOM-TFL
    $table->string('nama_barang');
    $table->string('kategori'); // Ayam Utuh, Sampingan, atau Komoditas 
    $table->string('satuan_utama'); // Ekor, Kg, atau Liter
    $table->decimal('stok_ekor', 12, 2)->default(0); // Khusus untuk ayam utuh 
    $table->decimal('stok_berat', 12, 2)->default(0); // Untuk Kg atau Liter 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stoks');
    }
};
