<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cek_stoks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_cek');
            
            // Relasi ke tabel barang dan user (Tim Barang)
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); 

            // SNAPSHOT SISTEM: Menyimpan angka yang ada di database saat itu
            $table->decimal('stok_ekor_sistem', 12, 2)->default(0);
            $table->decimal('stok_berat_sistem', 12, 2)->default(0);

            // INPUT MANUAL: Angka real yang dihitung Tim Barang di freezer/gudang
            $table->decimal('stok_ekor_fisik', 12, 2)->default(0);
            $table->decimal('stok_berat_fisik', 12, 2)->default(0);

            // Kolom untuk catet alasan kalau ada selisih (misal: "2 ekor busuk")
            $table->text('catatan')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cek_stoks');
    }
};
