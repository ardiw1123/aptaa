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
        Schema::create('stok_masuks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('barang_id')->constrained('barangs');
        $table->foreignId('user_id')->constrained('users'); // Tim Gudang yang input
        $table->date('tanggal_masuk');
        $table->decimal('jumlah_unit', 12, 2)->nullable(); // Mengisi kolom 'EKOR' di sheet 
        $table->decimal('jumlah_berat', 12, 2)->nullable(); // Mengisi kolom 'KG' atau 'LITER' 
        $table->string('nama_hub')->nullable(); // Contoh: MUNDU 
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};
