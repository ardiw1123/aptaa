<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_permintaan_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_stok_id')->constrained('permintaan_stoks')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            
            // Jumlah barang yang diminta ke supplier
            $table->decimal('jumlah_unit', 12, 2)->default(0);  // Ekor
            $table->decimal('jumlah_berat', 12, 2)->default(0); // Kg
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_permintaan_stoks');
    }
};
